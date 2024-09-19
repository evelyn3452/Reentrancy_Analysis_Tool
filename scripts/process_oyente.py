import logging
import os
import re
import sys
import json
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

def analyze_solidity(file_contents):
    # Write the file contents to a temporary file
    tmp_file_path = 'tmp_file.sol'
    with open(tmp_file_path, 'w') as tmp_file:
        tmp_file.write(file_contents)

    # Extract Solidity version from the file contents
    version = get_solidity_version(file_contents)
    if version:
        # Use solc-select to switch to the appropriate version
        subprocess.run(['solc-select', 'install', version], check=True)
        subprocess.run(['solc-select', 'use', version], check=True)
    else:
        raise ValueError("Solidity version not found in the file")

    # Analyze the file using Oyente
    try:
        cmd = f'python oyente/oyente.py -s {tmp_file_path}'
        process = subprocess.run(cmd.split(), capture_output=True, text=True)

        if process.returncode != 0:
            logger.error(f"Oyente analysis failed: {process.stderr}")
            raise Exception(f"Oyente analysis failed: {process.stderr}")

        output = process.stdout

        # Parse Oyente output
        vulnerabilities = []
        for line in output.splitlines():
            if 'WARNING' in line:
                # Assuming the format of Oyente warnings, this might need to be adjusted
                parts = line.split()
                contract = parts[1]
                function = parts[2]
                description = ' '.join(parts[3:])
                vulnerabilities.append({
                    'contract': contract,
                    'function': function,
                    'description': description
                })
        return vulnerabilities
    except Exception as e:
        logger.error(f"Error during analysis: {e}")
        raise
    finally:
        # Cleanup temporary file
        os.remove(tmp_file_path)

if __name__ == "__main__":
    file_contents = sys.stdin.read()
    vulnerabilities = analyze_solidity(file_contents)
    print(json.dumps(vulnerabilities))
