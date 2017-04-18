CREATE DATABASE IF NOT EXISTS CP;
USE CP;

-- Create the tables if they don't already exist
CREATE TABLE IF NOT EXISTS Users (
	uid INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(32) NOT NULL,
	password VARCHAR(32) NOT NULL
);

INSERT INTO Users (username, password) VALUES ("gus", "pass");


-- CREATE TABLE Customers (
-- 	cid INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
-- 	name VARCHAR(64) NOT NULL,
-- 	address VARCHAR(64),
-- 	city VARCHAR(64),
-- 	state VARCHAR(64),
--   zip INT,
--   gsname VARCHAR(64),
-- 	gstroop VARCHAR(64),
-- 	cookietotal INT
-- );