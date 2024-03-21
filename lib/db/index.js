const { Sequelize } = require('sequelize');
const dotenv = require("dotenv");

dotenv.config({
    path: "./node.env",
});

const sequelize = new Sequelize(process.env.DB_DATABASE, process.env.DB_USERNAME, process.env.DB_PASSWORD, {
  host: 'localhost',
  dialect: 'mysql',
});

module.exports = sequelize;
