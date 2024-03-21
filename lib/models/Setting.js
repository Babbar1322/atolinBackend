const { DataTypes } = require('sequelize');
const sequelize = require('../db/index'); // Assuming you have a separate file for Sequelize configuration

const Setting = sequelize.define('Setting', {
  id: {
    type: DataTypes.INTEGER,
    allowNull: false,
    primaryKey: true,
    autoIncrement: true
  },
  key: {
    type: DataTypes.STRING,
    allowNull: false,
    unique: true,
  },
  value: {
    type: DataTypes.TEXT,
    allowNull: false,
  },
  // Add more fields as needed
}, {
    tableName: 'settings',
    timestamps: false
});

module.exports = Setting;
