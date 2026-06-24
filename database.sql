-- Database: yassjokiin
-- Buat database jika belum ada
CREATE DATABASE IF NOT EXISTS yassjokiin;
USE yassjokiin;

-- Tabel Users untuk Login Admin/User
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Orders untuk data pesanan joki
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    game_name VARCHAR(100) NOT NULL,
    jockey_package VARCHAR(100) NOT NULL,
    target_rank VARCHAR(100) NOT NULL,
    price DECIMAL(12,2) NOT NULL,
    status ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending',
    signature LONGTEXT NOT NULL, -- Tanda tangan digital dalam bentuk base64
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Order Screenshots untuk menyimpan banyak screenshot (multiple upload)
CREATE TABLE IF NOT EXISTS order_screenshots (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

-- Insert admin default
-- Username: admin, Password: admin123
INSERT INTO users (username, password, role) VALUES 
('admin', '$2y$10$Uu9h9U.M.v.w2Wv863iCjO11Qc01sI9642C97j12A72G83m2s18.C', 'admin')
ON DUPLICATE KEY UPDATE id=id;
