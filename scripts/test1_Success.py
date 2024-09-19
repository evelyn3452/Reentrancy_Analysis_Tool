#working codes without web  app passing the sol files
import subprocess
import json

def run_slither(contract_path):
    try:
        # Run slither with JSON output
        result = subprocess.run(
            ['slither', contract_path, '--json', 'output.json'],
            capture_output=True,
            text=True
        )

        # Check if the command was successful
        if result.returncode != 0:
            print(f"Error running Slither: {result.stderr}")
            return None

        # Read the JSON output
        with open('output.json') as f:
            slither_output = json.load(f)
        
        return slither_output

    except Exception as e:
        print(f"Exception: {e}")
        return None

def filter_reentrancy_issues(slither_output):
    reentrancy_issues = []

    for vulnerability in slither_output['results']['detectors']:
        if vulnerability['check'] == 'reentrancy':
            reentrancy_issues.append(vulnerability)

    return reentrancy_issues

if __name__ == "__main__":
    contract_path = 'vulnerable1.sol'
    slither_output = run_slither(contract_path)
    
    if slither_output:
        reentrancy_issues = filter_reentrancy_issues(slither_output)
        
        if reentrancy_issues:
            print("Reentrancy vulnerabilities found:")
            for issue in reentrancy_issues:
                print(issue)
        else:
            print("No reentrancy vulnerabilities found.")
    else:
        print("Failed to analyze the contract.")
