CREATE DATABASE phpblog;
USE phpblog;
-- Table 1: Categories (to organize posts)
CREATE TABLE categories (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(50) UNIQUE NOT NULL,
 description TEXT,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- Table 2: Posts (the blog articles)
CREATE TABLE posts (
 id INT AUTO_INCREMENT PRIMARY KEY,
 title VARCHAR(200) NOT NULL,
 content TEXT NOT NULL,
 image VARCHAR(255),
 category_id INT NOT NULL,
 views INT DEFAULT 0,
 status ENUM('draft', 'published') DEFAULT 'draft',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE
CURRENT_TIMESTAMP,
 FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE
CASCADE
);
-- Table 3: Comments (user feedback on posts)
CREATE TABLE comments (
 id INT AUTO_INCREMENT PRIMARY KEY,
 post_id INT NOT NULL,
 name VARCHAR(100) NOT NULL,
 email VARCHAR(100) NOT NULL,
 comment TEXT NOT NULL,
 status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
);