CREATE DATABASE IF NOT EXISTS CP;
USE CP;

-- Create the tables if they don't already exist
CREATE TABLE IF NOT EXISTS Users (
	uid VARCHAR(128) NOT NULL PRIMARY KEY,
	username VARCHAR(32) NOT NULL,
	password VARCHAR(60) NOT NULL
);