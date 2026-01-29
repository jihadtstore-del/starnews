CREATE DATABASE IF NOT EXISTS tech_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE tech_store;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    phone VARCHAR(50),
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'store', 'user') NOT NULL DEFAULT 'user',
    created_at DATETIME NOT NULL
);

CREATE TABLE equipment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    category VARCHAR(150),
    brand_default VARCHAR(150),
    model_default VARCHAR(150),
    created_at DATETIME NOT NULL,
    INDEX idx_equipment_name (name)
);

CREATE TABLE locations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL
);

CREATE TABLE stock_in (
    id INT AUTO_INCREMENT PRIMARY KEY,
    equipment_id INT NOT NULL,
    brand VARCHAR(150) NOT NULL,
    model VARCHAR(150) NOT NULL,
    qty INT NOT NULL,
    serial_no VARCHAR(150),
    stock_in_date DATE NOT NULL,
    remarks VARCHAR(255),
    created_by INT NOT NULL,
    created_at DATETIME NOT NULL,
    INDEX idx_stock_in_equipment (equipment_id),
    CONSTRAINT fk_stock_in_equipment FOREIGN KEY (equipment_id) REFERENCES equipment(id),
    CONSTRAINT fk_stock_in_created_by FOREIGN KEY (created_by) REFERENCES users(id)
);

CREATE TABLE issues (
    id INT AUTO_INCREMENT PRIMARY KEY,
    equipment_id INT NOT NULL,
    user_id INT NOT NULL,
    qty INT NOT NULL,
    unit_no VARCHAR(150) NOT NULL,
    serial_no VARCHAR(150),
    phone VARCHAR(50) NOT NULL,
    location_id INT NOT NULL,
    issue_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    return_date DATETIME NULL,
    status ENUM('ISSUED', 'RETURNED') NOT NULL DEFAULT 'ISSUED',
    remarks VARCHAR(255),
    created_by INT NOT NULL,
    created_at DATETIME NOT NULL,
    INDEX idx_issues_equipment (equipment_id),
    INDEX idx_issues_user (user_id),
    INDEX idx_issues_status (status),
    INDEX idx_issues_issue_date (issue_date),
    CONSTRAINT fk_issues_equipment FOREIGN KEY (equipment_id) REFERENCES equipment(id),
    CONSTRAINT fk_issues_user FOREIGN KEY (user_id) REFERENCES users(id),
    CONSTRAINT fk_issues_location FOREIGN KEY (location_id) REFERENCES locations(id),
    CONSTRAINT fk_issues_created_by FOREIGN KEY (created_by) REFERENCES users(id)
);

INSERT INTO users (name, email, phone, password_hash, role, created_at)
VALUES ('Admin User', 'admin@example.com', '0000000000', '$2y$12$goYn6tiseisIEzJ6ZLm5.OKSZZusIK/Topq8FBOitn8CX4G1zEfKq', 'admin', NOW());
