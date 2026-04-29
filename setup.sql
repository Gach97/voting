-- Voting System Database Setup
-- Create database and tables for the voting system

CREATE DATABASE IF NOT EXISTS voting_db;
USE voting_db;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    is_admin TINYINT(1) DEFAULT 0,
    has_voted TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Candidates table
CREATE TABLE IF NOT EXISTS candidates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    position VARCHAR(100) NOT NULL,
    bio TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Votes table
CREATE TABLE IF NOT EXISTS votes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    voter_id INT NOT NULL,
    candidate_id INT NOT NULL,
    voted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (voter_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (candidate_id) REFERENCES candidates(id) ON DELETE CASCADE,
    UNIQUE KEY unique_vote (voter_id)
);

-- Create indexes for performance
CREATE INDEX idx_candidate_votes ON votes(candidate_id);
CREATE INDEX idx_user_voted ON users(has_voted);

-- Seed: Admin account (password: admin123)
-- Hash was generated using: password_hash('admin123', PASSWORD_BCRYPT)
INSERT INTO users (full_name, email, password, is_admin) 
VALUES ('Admin', 'admin@vote.com', '$2y$10$K2oH8s8YZvlUlE9x0nZ9LuV6pDCbJFmqL1nKm3pL9eK8qZ7r8C3rS', 1)
ON DUPLICATE KEY UPDATE id=id;
