import logging
import os
import re
import sys
import json
import uuid
from slither import Slither
from slither.detectors.reentrancy.reentrancy import Reentrancy 
from slither.core.compilation_unit import CompilationUnit
from crytic_compile import CryticCompile
from slither.analyses.data_dependency.data_dependency import get_dependencies
import subprocess

# Set the HOME environment variable
os.environ['HOME'] = os.path.expanduser("~")

# Setup basic logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

def get_solidity_version(file_contents):
    pragma_pattern = re.compile(r'pragma solidity \^?([0-9]+\.[0-9]+\.[0-9]+);')
    match = pragma_pattern.search(file_contents)
    if match:
        return match.group(1)
    return None

def get_contract_name(file_contents):
    contract_pattern = re.compile(r'contract\s+(\w+)\s*{')
    match = contract_pattern.search(file_contents)
    if match:
        return match.group(1)
    return None

def analyze_solidity(file_contents,solc_version):
    # Write the file contents to a temporary file
    tmp_file_path = 'tmp_file.sol'
    with open(tmp_file_path, 'w') as tmp_file:
        tmp_file.write(file_contents) 

    # Extract Solidity version from the file contents
    version = get_solidity_version(file_contents)
    if version:
        # Use solc-select to switch to the appropriate version
        # subprocess.run(['solc-select', 'install', version], check=True)
        subprocess.run(['solc-select', 'use', version], check=True)
    else:
        raise ValueError("Solidity version not found in the file")
    
    contract_name = get_contract_name(file_contents)
    if not contract_name:
        raise ValueError("No Contract name was found in the file")

    try:
            
            # Run slither with JSON output
            result = subprocess.run(
                ['slither', contract_path, '--json', 'output.json'],
                capture_output=True,
                text=True
            )

            # Check if the command was successful
            if result.returncode != 0:
                print(f"Error running Slither: {result.stderr}", file=sys.stderr)
                return None

            # Read the JSON output
            with open('output.json') as f:
                slither_output = json.load(f)
            
            return slither_output

    except Exception as e:
        print(f"Exception: {e}", file=sys.stderr)
        return None
    # Analyze the file using Slither
    # try:
    #     # Initialize the detector
    #     slither = Slither(tmp_file_path)
    #     crytic_compile = CryticCompile(tmp_file_path)
        
    #     contract_list = slither.get_contract_from_name(contract_name)
    #     if not contract_list:
    #         raise ValueError(f"Contract {contract_name} not found in the file")
        
    #     contract = contract_list[0] if isinstance(contract_list, list) else contract_list
         
    #     print("Contract Detailsssss : ", contract)
    #     print("Contract all function Detailsssss : ", contract._functions)
        
    #     print("Contract_list Detailsssss : ", contract_list)
    #     print("slither returnsssss: ",slither)
    #     if not contract:
    #         raise ValueError(f"Contract {contract_name} not found in the file")

        
    #     # Extract vulnerabilities or any other information
    #     vulnerabilities = []
    #     # for contract in slither.contracts:
    #     unique_id = str(uuid.uuid4())
    #     # Initialize the Reentrancy detector
    #     # bytecode_node = contract.
    #     compilation_unit = CompilationUnit(crytic_compile,unique_id)
    #     print("compilation unitsssss: ",compilation_unit)
    #     #compilation_unit = an object containing information bout the contract's bytecode, name and abi
        
    #     compilation_unit.
    #     compilation_unit._filename_to_contracts = {tmp_file_path: [contract]}
    #     compilation_unit._source_units[tmp_file_path] = contract
        
    #     # Add the contract to the compilation unit manually
    #     # compilation_unit. = [contract]
        
    #     # Initialize Reentrancy detector
    #     reentrancy_detector = Reentrancy(compilation_unit, slither, logger)
    #     reentrancy_results =  reentrancy_detector.detect_reentrancy(contract)
    #     logger.info(f"Reentrancy results: {reentrancy_results}")
        
    #     for result in reentrancy_results:
            
    #         # if result.contract.name == contract.name:
    #         function_name = result['function']
    #         line_number = result['source_mapping']['lines'][0]
    #         code = result['source_mapping']['src']
    #         description = result['description']
            
    #         function_code = ''
    #         for func in contract.functions:
    #             if func.name == function_name:
    #                 function_code = func.full_source_code()
                    
    #         vulnerabilities.append({
    #         'contract': contract.name,
    #         'function': function_name,
    #         'line_number': line_number,
    #         'code': function_code.strip(),
    #         'description': description
    #     })
    #     # return vulnerabilities
    #     print(json.dumps(vulnerabilities, indent=4))
    # except Exception as e:
    #     logger.error(f"Error during analysis: {e}")
    #     raise
    finally:
        # Cleanup temporary file
        os.remove(tmp_file_path)

def filter_reentrancy_issues(slither_output):
    reentrancy_issues = []

    for vulnerability in slither_output['results']['detectors']:
        if vulnerability['check'] == 'reentrancy':
            reentrancy_issues.append(vulnerability)

    return reentrancy_issues

if __name__ == "__main__":
    if len(sys.argv) != 3:
        print("Usage: python run_slither.py <path_to_contract> <solc_version>", file=sys.stderr)
        sys.exit(1)

    contract_path = sys.argv[1]
    solc_version = sys.argv[2]
    slither_output = analyze_solidity(contract_path, solc_version)
    
    if slither_output:
        reentrancy_issues = filter_reentrancy_issues(slither_output)
        
        print(json.dumps(reentrancy_issues, indent=4))
    else:
        print("Failed to analyze the contract.", file=sys.stderr)
        sys.exit(1)
    # file_contents = sys.stdin.read()
    # analyze_solidity(file_contents)
