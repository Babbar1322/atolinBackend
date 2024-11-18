const express = require("express");
const dotenv = require("dotenv");
const { ethers } = require("ethers");
const Setting = require("./models/Setting");

const app = express();
dotenv.config({
    path: "../node.env",
});

app.use(express.json());


app.use((req, res, next) => {
    if (
        req.header("X-Atolin-Node-Request-Only-X") &&
        req.header("X-Atolin-Node-Request-Only-X") ===
            process.env.SECRET_OF_ATOLIN
    ) {
        next();
    } else {
        res.status(404).end();
    }
});

async function getProvider(network = process.env.DEFAULT_NETWORK, networkEnv = 'test_net', infura_key) {
    let rpcUrl;
    if (network === "ethereum") {
        rpcUrl = networkEnv === "live_net"
            ? process.env.ETH_MAINNET
            : process.env.ETH_TESTNET;
        rpcUrl += `/${infura_key}`
    } else {
        rpcUrl = networkEnv === "live_net"
            ? process.env.BSC_MAINNET
            : process.env.BSC_TESTNET;
    }

    return new ethers.JsonRpcProvider(rpcUrl);
}

app.post("/create-account", (req, res) => {
    try {
        const account = ethers.Wallet.createRandom();

        const cryptoWallet = {
            address: account.address,
            privateKey: account.privateKey,
            secretPhrase: account.mnemonic.phrase,
            publicKey: account.publicKey,
        };
        // const cryptoWallet = {
        //     address: "0xDbeaeb8F410C204A838c19d0DE3521CFE9F4615B",
        //     privateKey: "0xcbbbc2d60b2b8db19312d50f18bc8a83e48e72bbb906df579fb07fb7364dc7aa",
        //     secretPhrase: "unknown frog auto release tumble patrol laundry smile knee spider vintage another",
        //     publicKey: "0x02d62c116b2136967b1f2841bbc2a5ba6be726d5e3273c8391981ee738a0968440",
        // };

        res.status(200).json({ data: cryptoWallet });
    } catch (err) {
        // console.log(err, '\n\n');
        // console.log(err.stack);
        res.status(400).json({ error: err.message });
    }
});

app.post("/get-balance", async (req, res) => {
    try {
        const { walletPrivate, address, network, infura_key } = req.body;

        const TokenNetwork = await Setting.findOne({
            where: {
                key: 'token_network',
            },
        });
        const TokenAddress = await Setting.findOne({
            where: {
                key: 'token_address',
            },
        });
        const tokenNet = TokenNetwork.toJSON();
        const tokenAdd = TokenAddress.toJSON();

        // console.log(tokenNet, '\n\n', tokenNet.value);
        // console.log(tokenNet.value === 'live_net' ? process.env.NODE_RCP_URL : process.env.NODE_TEST_RCP_URL);

        const provider = getProvider(network, tokenNet.value, infura_key);
        const contract = new ethers.Contract(tokenAdd.value, require('./common-abi.json'), provider);

        const wallet = new ethers.Wallet(walletPrivate);
        const connectedWallet = wallet.connect(provider);

        const balance = await provider.getBalance(connectedWallet.address);
        const tokenBalance = await contract.balanceOf(wallet.address);

        const balanceObj = {};

        if (address?.length > 0) {
            address.forEach(async (token) => {
                const tempContract = new ethers.Contract(token, require('./common-abi.json'), provider);
                const balance = await tempContract.balanceOf(wallet.address);
                balanceObj[token] = ethers.formatEther(balance) ?? 0;
            });
        }

        // console.log(await contract.name());
        const tokenName = await contract.name();
        const tokenSymbol = await contract.symbol();
        const tokenDecimals = await contract.decimals();
        const tokenAddress = tokenAdd.value;
        const token = {
            name: tokenName,
            symbol: tokenSymbol,
            decimals: Number(tokenDecimals),
            address: tokenAddress,
            balance: ethers.formatEther(tokenBalance)
        }

        res.status(200).json({ bnb: ethers.formatEther(balance.toString()), token, balanceObj });
    } catch (err) {
        console.log(err.message, '\n\n');
        console.log(err.stack);
        res.status(400).json({ error: err.message });
    }
});

app.post("/get-balance-by-address", async (req, res) => {
    try {
        const { walletPrivate, contractAddress, network, infura_key } = req.body;

        const TokenNetwork = await Setting.findOne({
            where: {
                key: 'token_network',
            },
        });
        const tokenNet = TokenNetwork.toJSON();
        const provider = getProvider(network, tokenNet.value, infura_key);

        const wallet = new ethers.Wallet(walletPrivate);
        const connectedWallet = wallet.connect(provider);

        let balance;
        if (contractAddress === 'binance' || contractAddress === 'ethereum') {
            balance = await provider.getBalance(connectedWallet.address);
        } else {
            const tempContract = new ethers.Contract(contractAddress, require('./common-abi.json'), provider);
            balance = await tempContract.balanceOf(wallet.address);
        }

        res.status(200).json({ balance: ethers.formatEther(balance) ?? 0 });
    } catch (err) {
        // console.log(err, '\n\n');
        // console.log(err.stack);
        res.status(400).json({ error: err.message });
    }
})

app.post("/import-wallet", (req, res) => {
    try {
        const { phrase } = req.body;

        const wallet = ethers.Wallet.fromPhrase(phrase);

        // console.log(wallet);
        const cryptoWallet = {
            address: wallet.address,
            privateKey: wallet.privateKey,
            secretPhrase: wallet.mnemonic.phrase,
            publicKey: wallet.publicKey,
        };

        res.status(200).json({ data: cryptoWallet });
    } catch (err) {
        // console.log(err, '\n\n');
        // console.log(err.stack);
        res.status(400).json({ error: "Invalid Secret Phrase" });
    }
});

app.post("/transfer", async (req, res) => {
    const { walletPrivate } = req.body;
    const contract = new ethers.Contract(
        process.env.TOKEN_ADDRESS,
        require("./tokenAbi.json")
    );
    // console.log(contract);
    const wallet = new ethers.Wallet(atob(walletPrivate), provider);
    // const connectedWallet = wallet.connect(provider);

    // res.send(connectedWallet.address)

    // const signer = new JsonRpcSigner(provider, wallet.address);
    // console.log(signer);

    // const tx = await wallet.sendTransaction({
    //     to: '0x91897EC90848f1fEDf8F0fbd713f970f24FcA290',
    //     value: ethers.parseUnits('0.001', 'ether')
    // });

    // const result = await tx.wait()
    // console.log(result);
    const amount = 10n * 10n ** 18n;
    // const amount = new BN('10000000000000000000');
    // const hexAmount = '0x' + amount.toString('hex');

    const erc20_rw = new ethers.Contract(
        process.env.TOKEN_ADDRESS,
        require("./tokenAbi.json"),
        wallet
    );

    // // console.log(erc20_rw);
    const tx = await erc20_rw.transfer(
        "0x91897EC90848f1fEDf8F0fbd713f970f24FcA290",
        amount
    );
    await tx.wait();
    // console.log(ethers.parseUnits('10', 18))

    res.status(200).json("hello");
});

app.post("/transfer-bnb", async (req, res) => {
    try {
        const { amount, toAddress, walletPrivate, network, infura_key } = req.body;

        const TokenNetwork = await Setting.findOne({
            where: {
                key: 'token_network',
            },
        });
        const tokenNet = TokenNetwork.toJSON();
        const provider = getProvider(network, tokenNet.value, infura_key);

        const wallet = new ethers.Wallet(walletPrivate, provider);

        const balance = await provider.getBalance(wallet.address);

        const parsedAmount = ethers.parseEther(amount);
        const txReq = {
            to: toAddress,
            value: ethers.parseEther(amount),
        };

        const transaction = await wallet.sendTransaction(txReq);
        const gas = await provider.estimateGas(transaction);
        if ((parsedAmount + gas) > balance) {
            return res.status(400).json({error: 'Insufficient Balance'});
        }
        const tx = await transaction.wait();
        // if (tx.status === 1) {
            res.status(200).json({ data: tx, amount, toAddress, contractAddress: network });
        // }
    } catch (e) {
        // console.log(err, '\n\n');
        // console.log(err.stack);
        // console.log(e.code)
        if (e.code === 'INSUFFICIENT_FUNDS') {
            return res.status(400).json({error: 'Insufficient Balance'});
        }
        res.status(400).json({ error: e.shortMessage ?? e.message });
    }
});
app.post("/transfer-fees", async (req, res) => {
    try {
        const { amount, toAddress, walletPrivate, network, infura_key } = req.body;

        const TokenNetwork = await Setting.findOne({
            where: {
                key: 'token_network',
            },
        });
        const tokenNet = TokenNetwork.toJSON();
        const provider = getProvider(network, tokenNet.value, infura_key);

        const wallet = new ethers.Wallet(walletPrivate, provider);

        const balance = await provider.getBalance(wallet.address);

        const txReq = {
            to: toAddress,
            value: amount,
        };

        const transaction = await wallet.sendTransaction(txReq);
        const gas = await provider.estimateGas(transaction);
        if ((amount + gas) > balance) {
            return res.status(400).json({error: 'Insufficient Balance'});
        }
        const tx = await transaction.wait();
        // if (tx.status === 1) {
            res.status(200).json({ data: tx, amount: ethers.formatEther(amount), toAddress, contractAddress: network });
        // }
    } catch (e) {
        // console.log(err, '\n\n');
        // console.log(err.stack);
        // console.log(e.code)
        if (e.code === 'INSUFFICIENT_FUNDS') {
            return res.status(400).json({error: 'Insufficient Balance'});
        }
        res.status(400).json({ error: e.shortMessage ?? e.message });
    }
});
app.post("/transfer-token", async (req, res) => {
    try {
        const { toAddress, walletPrivate, contractAddress, network, infura_key } = req.body;
        let amount = req.body.amount;

        const TokenNetwork = await Setting.findOne({
            where: {
                key: 'token_network',
            },
        });
        const TokenAddress = await Setting.findOne({
            where: {
                key: 'token_address',
            },
        });
        const tokenNet = TokenNetwork.toJSON();
        const tokenAdd = TokenAddress.toJSON();
        const provider = getProvider(network, tokenNet.value, infura_key);

        const tokenAddress = contractAddress ?? tokenAdd.value;
        const tokenAbi = contractAddress ? require('./common-abi.json') : require('./tokenAbi.json');

        const wallet = new ethers.Wallet(walletPrivate, provider);
        const tokenContract = new ethers.Contract(tokenAddress, tokenAbi, wallet);

        const balance = await tokenContract.balanceOf(wallet.address);
        const binanceBalance = await provider.getBalance(wallet.address);

        if (typeof amount !== 'string') {
            amount = amount.toString();
        }

        const sendAmount = ethers.parseEther(amount);

        if (sendAmount > balance) {
            return res.status(400).send({error: "Insuficiant Token Balance"})
        }

        const tx = await tokenContract.transfer(toAddress, sendAmount);
        const gas = await provider.estimateGas(tx);

        if (gas > binanceBalance) {
            return res.status(400).send({error: "Insuficiant BNB Balance"})
        }

        const transaction = await tx.wait();

        const symbol = await tokenContract.symbol();

        res.status(200).json({data: transaction, amount, toAddress, contractAddress, symbol});
    } catch (e) {
        console.log(e, '\n\n');
        console.log(e.stack);
        if (e.code === 'INSUFFICIENT_FUNDS') {
            return res.status(400).json({error: 'You don\'t have enough BNB to pay for the gas fee'});
        }
        res.status(400).json({ error: e.shortMessage ?? e.message });
    }
});

app.post('/check-token', async (req, res) => {
    try {
        const {address, network, infura_key} = req.body;
        if (ethers.isAddress(address)) {
            const TokenNetwork = await Setting.findOne({
                where: {
                    key: 'token_network',
                },
            });
            const tokenNet = TokenNetwork.toJSON();
            const provider = getProvider(network, tokenNet.value, infura_key);

            const byteCode = await provider.getCode(address);
            if (byteCode === '0x') {
                return res.status(400).json({error: 'Not Found'})
            } else {
                const tokenContract = new ethers.Contract(address, require('./common-abi.json'), provider);
                const tokenName = await tokenContract.name();
                const tokenSymbol = await tokenContract.symbol();
                const tokenDecimals = await tokenContract.decimals();
                const tokenAddress = await tokenContract.getAddress();

                const token = {
                    tokenAddress, tokenDecimals: Number(tokenDecimals), tokenName, tokenSymbol
                }
                return res.status(200).json({token});
            }
        } else {
            return res.status(400).json({error: 'Not Found'})
        }
    } catch (err) {
        // console.log(err, '\n\n');
        // console.log(err.stack);
        res.status(400).send({error: err})
    }
});

// app.post('/')

app.all("*", (req, res) => {
    res.status(404).end();
});

const port = process.env.PORT;

app.listen(port, "0.0.0.0", () => {
    console.log("Running on port", port);
});
