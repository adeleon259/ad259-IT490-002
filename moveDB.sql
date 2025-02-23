-- Create the database
CREATE DATABASE movDB;

-- Use the database
USE movDB;

-- Create the users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,   -- Storing hashed passwords
    created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Example insert with a hashed password
INSERT INTO users (username, email, password) 
VALUES ('testUser', 'testuser@example.com', PASSWORD('testPassword'));
