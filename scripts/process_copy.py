import logging
import os
import re
import sys
import json
from slither import Slither
from slither.detectors.reentrancy.reentrancy import Reentrancy 
from slither.core.compilation_unit import CompilationUnit
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
        # Initialize the detector
        slither = Slither(tmp_file_path)
        # Extract vulnerabilities or any other information
        vulnerabilities = []
        for contract in slither.contracts:
            # Initialize the Reentrancy detector
            try:
                bytecode_node = func.node
                compilation_unit = CompilationUnit(bytecode_node, contract.name, contract.abi)
                    #compilation_unit = an object containing information bout the contract's bytecode, name and abi
                
                reentrancy_detector = Reentrancy( slither, logger)
                reentrancy_results =  reentrancy_detector.detect()
                logger.info(f"Reentrancy results: {reentrancy_results}")
                
                for result in reentrancy_results:
                    dependencies = get_dependencies(slither, function)
                    function_code = ''
                    for dep in dependencies:
                        function_code += f"{dep.source_mapping()}: {dep.code}\n"
                    vulnerabilities.append({
                        'contract': contract.name,
                        'function': result.function.name,
                        'code': function_code.strip(),
                        'description': result.description
                    })
            except Exception as e:
                logger.error(f"Error analyzing function {contract.name}: {e}")
        return json.dumps(vulnerabilities, indent=2)
    except Exception as e:
        logger.error(f"Error during analysis: {e}")
        raise
    finally:
        # Cleanup temporary file
        os.remove(tmp_file_path)

if __name__ == "__main__":
    file_contents = sys.stdin.read()
    analyze_solidity(file_contents)
