-- Create the database if it doesn't exist
CREATE DATABASE IF NOT EXISTS login_system;

-- Use the database
USE login_system;

-- Create the users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert some sample data (hashed passwords, never store plaintext passwords in real applications)
INSERT INTO users (username, password) VALUES
('user1', '$2y$10$2dmjJrx5YNXZP.nYfhZYtOpFSsGSH3ZQSzAtKYsqeFOqHnlLUVt56'),
('user2', '$2y$10$OZhgg.kx2Hg7IadPpsBLa93uL.MnbNnkG7kgyjIiN4OhTi8kdZpEu');

-- The passwords are bcrypt hashes. Use PHP password_hash() to generate real hashes in your application
-- You can run password_hash("user1password", PASSWORD_BCRYPT) in PHP to generate hashes
