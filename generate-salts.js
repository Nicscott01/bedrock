const crypto = require('crypto');

const getRandom = function (min, max) {
    const range = max - min;

    const bits_needed = Math.ceil(Math.log2(range));
    if (bits_needed > 53) {
        throw new Error("We cannot generate numbers larger than 53 bits.");
    }
    const bytes_needed = Math.ceil(bits_needed / 8);
    const mask = Math.pow(2, bits_needed) - 1;

    let rval = 0;
    const byteArray = crypto.randomBytes(bytes_needed);

    let p = (bytes_needed - 1) * 8;
    for (let i = 0; i < bytes_needed; i++) {
        rval += byteArray[i] * Math.pow(2, p);
        p -= 8;
    }

    rval = rval & mask;

    if (rval >= range) {
        return getRandom(min, max);
    }

    return min + rval;
};

const getRandomChar = function () {
    const minChar = 33; // !
    const maxChar = 126; // ~
    const char = String.fromCharCode(getRandom(minChar, maxChar));
    if (["'", "\"", "\\"].includes(char)) {
        return getRandomChar();
    }
    return char;
};

const generateSalt = function () {
    return Array.from({ length: 64 }, getRandomChar).join("");
};

const generateEnvLine = function (mode, key) {
    const salt = generateSalt();
    switch (mode) {
        case "yml":
            return key.toLowerCase() + ": \"" + salt + "\"";
        default:
            return key.toUpperCase() + "='" + salt + "'";
    }
};

const generateFile = function (mode, keys) {
    return keys.map(key => generateEnvLine(mode, key)).join("\n");
};

const keys = [
    "AUTH_KEY",
    "SECURE_AUTH_KEY",
    "LOGGED_IN_KEY",
    "NONCE_KEY",
    "AUTH_SALT",
    "SECURE_AUTH_SALT",
    "LOGGED_IN_SALT",
    "NONCE_SALT"
];

const mode = process.argv[2] || "env"; // Choose between "env" or "yml"
console.log(generateFile(mode, keys));
