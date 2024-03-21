const crypto = require('crypto');
const dotenv = require("dotenv");

dotenv.config({
    path: "./node.env",
});

class Encryption {
    static method = "aes-256-cbc";

    static encrypt(text) {
        try {
            const key = Buffer.from(process.env.DEC_KEY, 'base64');
            const iv = Buffer.from(process.env.DEC_IV, 'base64');
            if (iv.length !== 16) {
                throw new Error('Invalid initialization vector length');
            }
            const cipher = crypto.createCipheriv(this.method, key, iv);
            let encrypted = cipher.update(text, 'utf-8', 'base64');
            encrypted += cipher.final('base64');
            return encrypted;
        } catch (error) {
            console.error('Encryption error:', error.message);
            throw error;
        }
    }

    static decrypt(text) {
        try {
            // console.log(process.env.DEC_IV)
            // console.log('IV Length:', Buffer.from(process.env.DEC_IV, 'base64'));

            // const key = Buffer.from(process.env.DEC_KEY, 'base64');
            // const iv = Buffer.from(process.env.DEC_IV, 'base64');

            // console.log('IV Length:', Buffer.from(process.env.DEC_IV, 'base64').length);

            // if (iv.length !== 16) {
            //     throw new Error('Invalid initialization vector length');
            // }
            const decipher = crypto.createDecipheriv(this.method, process.env.DEC_KEY, process.env.DEC_IV);
            let decrypted = decipher.update(text, 'base64', 'utf-8');
            decrypted += decipher.final('utf-8');
            return decrypted;
        } catch (error) {
            console.error('Decryption error:', error.message);
            throw error;
        }
    }
}

module.exports = Encryption;
