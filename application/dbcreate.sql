CREATE DATABASE IF NOT EXISTS CP;
USE CP;

-- Create the tables if they don't already exist
CREATE TABLE IF NOT EXISTS Users (
	encrypteduid VARCHAR(64) NOT NULL PRIMARY KEY,
	username VARCHAR(32) NOT NULL,
	encryptedpassword VARCHAR(60) NOT NULL,
  filename VARCHAR(32)
);
