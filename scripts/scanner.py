from oyente import Oyente

def analyze_contract_with_oyente(contract_path):
    oyente = Oyente()
    results = oyente.analyze(contract_path)
    return results

# Example usage
contract_path = 'vulnerable.sol'
results = analyze_contract_with_oyente(contract_path)
print(results)
