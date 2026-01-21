CREATE DATABASE IF NOT EXISTS technician_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE technician_store;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'technician') NOT NULL DEFAULT 'technician',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE technicians (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    name VARCHAR(150) NOT NULL,
    department VARCHAR(150) NOT NULL,
    phone VARCHAR(50),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_technicians_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE equipments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    category ENUM('Camera', 'Microphone', 'Tripod', 'Light', 'Memory Card', 'Battery', 'Others') DEFAULT 'Others',
    stock INT NOT NULL DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE daily_issues (
    id INT AUTO_INCREMENT PRIMARY KEY,
    issue_date DATE NOT NULL,
    technician_id INT NOT NULL,
    department VARCHAR(150) NOT NULL,
    equipment_id INT NOT NULL,
    quantity INT NOT NULL,
    issue_purpose VARCHAR(255) NOT NULL,
    return_date DATE NULL,
    status ENUM('pending', 'approved', 'rejected', 'returned') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_issue_technician FOREIGN KEY (technician_id) REFERENCES technicians(id) ON DELETE CASCADE,
    CONSTRAINT fk_issue_equipment FOREIGN KEY (equipment_id) REFERENCES equipments(id) ON DELETE CASCADE
);

CREATE TABLE issue_return_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    daily_issue_id INT NOT NULL,
    return_date DATE NOT NULL,
    updated_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_return_issue FOREIGN KEY (daily_issue_id) REFERENCES daily_issues(id) ON DELETE CASCADE,
    CONSTRAINT fk_return_user FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE CASCADE
);

INSERT INTO users (name, email, password, role)
VALUES
('Admin User', 'admin@tvchannel.local', '$2y$10$kjB5B.5dB85Xkr2vK9C3xu5Xz4dIv6XGZ9mR9yHVZJPumFxH3uD.u', 'admin');
