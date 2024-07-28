import logging
import os
import re
import sys
import json
from slither import Slither
from slither.detectors.reentrancy.reentrancy import Reentrancy
import subprocess

# Set the HOME environment variable
os.environ['HOME'] = os.path.expanduser("~")

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

    # Analyze the file using Slither
    try:
        slither = Slither(tmp_file_path)
        reentrancy_detector = Reentrancy(slither, slither.logger)
        reentrancy_results = reentrancy_detector.detect()
        # slither.run_detectors()
        # reentrancy_detector = Reentrancy(slither._compilation_units[0], slither, logger=None)
        # reentrancy_detector.detect()
        
        # Extract vulnerabilities or any other information
        vulnerabilities = []
        for result in reentrancy_results:
            vulnerabilities.append({
                'contract': result['contract'].namo,
                'function': result['function'].name,
                'line_number': result['source_mapping']['lines'][0],
                'code': result['source_mapping']['src'],
                'description': result['description']
            })
        # logging.info(f"Total results found: {len(vulnerabilities)}")
        return vulnerabilities
    finally:
        # Cleanup temporary file
        os.remove(tmp_file_path)

if __name__ == "__main__":
    file_contents = sys.stdin.read()
    vulnerabilities = analyze_solidity(file_contents)
    print(json.dumps(vulnerabilities))
