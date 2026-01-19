CREATE DATABASE IF NOT EXISTS starnews CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE starnews;

CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    body TEXT NOT NULL,
    author VARCHAR(120) NOT NULL,
    category VARCHAR(80) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO news (title, body, author, category)
VALUES
('স্টার নিউজে স্বাগতম', 'এটি একটি নমুনা সংবাদ। অ্যাডমিন পেজ থেকে নতুন সংবাদ যোগ করতে পারবেন।', 'স্টার রিপোর্টার', 'সাধারণ');
