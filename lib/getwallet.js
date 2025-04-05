const ethers = require('ethers');

async function get() {
    const tokenContract = new ethers.Contract('0x04984f5A91F916431d0232362211DF740deaC3b4', require('./tokenAbi.json'));

const balance = await tokenContract.balanceOf("0xb03B52B6816ddb7574653Ea033c0b9EC8D15edD8");
const binanceBalance = await provider.getBalance("0xb03B52B6816ddb7574653Ea033c0b9EC8D15edD8");

console.log(balance, "Token")
console.log(binanceBalance, "BNB")
}

get()
