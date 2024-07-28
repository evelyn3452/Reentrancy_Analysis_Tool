import logging
from slither import Slither
from slither.detectors.reentrancy.reentrancy import Reentrancy

# Setup logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

# Path to your Solidity file
sol_file = "C:/Users/Acer/Downloads/reentrancy/0x01f8c4e3fa3edeb29e514cba738d87ce8c091d3f.sol"

# Initialize Slither
slither = Slither(sol_file)

# Try initializing the Reentrancy detector
for contract in slither.contracts:
    try:
        reentrancy_detector = Reentrancy(contract.compilation_unit, slither, logger)
        vulnerabilities = reentrancy_detector.detect_reentrancy(contract)
        print(vulnerabilities)
    except Exception as e:
        logger.error(f"Error during detector initialization: {e}")
