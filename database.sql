CREATE DATABASE IF NOT EXISTS equipment_tracker;

USE equipment_tracker;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    full_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS equipment_issues (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(100) NOT NULL,
    equipment_name VARCHAR(150) NOT NULL,
    quantity INT NOT NULL,
    phone VARCHAR(30),
    unit_no VARCHAR(50),
    serial_no VARCHAR(50),
    issue_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Issued', 'Returned', 'Pending') DEFAULT 'Issued'
);

INSERT INTO users (username, password_hash, role, full_name) VALUES
('admin', '$2y$10$2Hj3jF5y5puw1K29mO2lP.pRZ3QnLKw1pK9b2lZbXy9m0lP1WRpCu', 'admin', 'Admin Manager'),
('user', '$2y$10$GQjZg76y4EM3eXkctkjxqO/5dh6B7mOBBX7X1wBkd9lC3F0a4k7HW', 'user', 'Viewer Account');

INSERT INTO equipment_issues (user_name, equipment_name, quantity, phone, unit_no, serial_no, status) VALUES
('Uzzol (It)', 'DP to DP Cable', 10, '01700000000', 'Unit-01', 'SN-8899', 'Issued'),
('Pervez (Main)', 'XLR Male to Female', 12, '01800000000', 'Unit-03', 'SN-7788', 'Pending');
