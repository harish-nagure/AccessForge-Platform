CREATE DATABASE IF NOT EXISTS datasheet;

USE datasheet;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE credit_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    name_on_card VARCHAR(255) NOT NULL,
    credit_no TEXT NOT NULL,
    cvv INT(4) NOT NULL,
    expiration_date DATE NOT NULL,
    issuer VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

INSERT INTO users (name, email, password, role) VALUES
('John Doe', 'john.doe@example.com', 'hashed_password_1', 'user'),
('Jane Smith', 'jane.smith@example.com', 'hashed_password_2', 'user'),
('Admin User', 'admin@example.com', 'hashed_password_3', 'admin');

INSERT INTO credit_details (user_id, name_on_card, credit_no, cvv, expiration_date, issuer) VALUES
(1, 'John Doe', '1234-5678-9012-3456', 123, '2025-12-31', 'Bank of America'),
(2, 'Jane Smith', '2345-6789-0123-4567', 456, '2026-01-31', 'Chase Bank');
