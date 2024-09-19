window.onload = function() {
    const code = `function example() {\n    console.log('Hello, world!');\n}\n`; // Replace with dynamic code
    const codeBlock = document.getElementById('code-block');
    const lineNumbers = document.getElementById('line-numbers');

    // Insert code
    codeBlock.textContent = code;

    // Generate line numbers
    const lines = code.split('\n').length;
    let lineNumberHtml = '';
    for (let i = 1; i <= lines; i++) {
        lineNumberHtml += i + '<br>';
    }
    lineNumbers.innerHTML = lineNumberHtml;
};
