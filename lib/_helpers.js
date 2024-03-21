function encrypt(text, key) {
    let result = '';
    for (let i = 0; i < text.length; i++) {
        result += String.fromCharCode(text.charCodeAt(i) ^ key.charCodeAt(i % key.length));
    }
    return result;
}

function decrypt(text, key) {
    return encrypt(text, key); // XOR encryption is its own inverse for decryption
}

module.exports = {
    encrypt, decrypt
}
