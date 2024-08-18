import subprocess
import json
import re
import sys

def get_solidity_version(file_contents):
    pragma_pattern = re.compile(r'pragma solidity \^?([0-9]+\.[0-9]+\.[0-9]+);')
    match = pragma_pattern.search(file_contents)
    if match:
        return match.group(1)
    return None

def run_slither(contract_path):
    try: 
        # Run slither with JSON output
        result = subprocess.run(
            ['slither', contract_path, '--json','-'],
            capture_output=True,
            text=True
        )
        
        # print(f"result without any action yet: {result}")

        # Check if the command was successful
        if result.returncode == 0:
            print(f"Error running Slither: {result.stderr}")
            
        # Parse the JSON output
        return json.loads(result.stdout) #got the result like the output.json
        
    except Exception as e:
        print(json.dumps({"error": f"Exception: {e}"}))    

def get_code_block(file_contents, first_markdown_element):
    # Extract line numbers from the `first_markdown_element`
    lines_pattern = re.compile(r'tmp_file.sol#L(\d+)-L(\d+)')
    match = lines_pattern.search(first_markdown_element)
    if match:
        start_line = int(match.group(1))
        end_line = int(match.group(2))
        
        # Split file contents into lines and extract the relevant block
        lines = file_contents.split('\n')
        code_block = '\n'.join(lines[start_line-1:end_line])
        return code_block
    return None

def extract_contract_and_function(description):
    contract_function_pattern = re.compile(r'Reentrancy in (\w+)\.(\w+)\([^\)]+\)')
    match = contract_function_pattern.search(description)
    if match:
        return match.group(1), match.group(2)
    return None, None

def filter_reentrancy_issues(slither_output,file_contents):
    # print(f"bfo filter the slither result, to check is the result passed successfully.: {slither_output}")
    if not slither_output or "results" not in slither_output:
        print(json.dumps({"error": "Slither did not produce valid results"}))
        return None

    reentrancy_issues = []
    detectors = slither_output["results"]["detectors"]
    
    for detector in detectors:
        if 'check' in detector and detector['check'] == "reentrancy-eth":
            description=detector['description']
            first_markdown_element=detector['first_markdown_element']
            impact=detector['impact']
            
            contract_name, function_name = extract_contract_and_function(description)
            
            code_block = get_code_block(file_contents, first_markdown_element)
            
            reentrancy_issues.append({
                "contract_name": contract_name,
                "function_name": function_name,
                "first_markdown_element": first_markdown_element,
                "impact": impact,
                "code_block": code_block
            })

    return reentrancy_issues


if __name__ == "__main__":
    file_contents = sys.stdin.read()

    # Write to temporary file
    tmp_file_path = 'tmp_file.sol'
    with open(tmp_file_path, 'w') as tmp_file:
        tmp_file.write(file_contents) 

    # Extract Solidity version from the file contents
    version = get_solidity_version(file_contents)
    if version:
        try:
            subprocess.run(
                ['solc-select', 'use', version],
                stdout=subprocess.DEVNULL,
                stderr=subprocess.DEVNULL,
                check=True
            )
        except subprocess.CalledProcessError as e:
            print(json.dumps({"error": f"Solidity version switch failed: {e}"}))
            sys.exit(1)
    else:
        print(json.dumps({"error": "Solidity version not found in the file"}))
        sys.exit(1)

    slither_output = run_slither(tmp_file_path)
    reentrancy_issues = filter_reentrancy_issues(slither_output,file_contents)
    
    # print(f"slither output : {slither_output}")

    # if slither_output:
    if reentrancy_issues:
        print(json.dumps({"reentrancy_issues": reentrancy_issues}, indent=2))
    else:
        print(json.dumps({"message": "No reentrancy vulnerabilities found."}))
    # else:
    #     print(json.dumps({"error": "Failed to analyze the contract."}))
    #     sys.exit(1)
