-- ====================================================
-- Basic Inventory Tracker Database Schema
-- Generated: June 24, 2025
-- Database Engine: MySQL
-- ====================================================

-- Create database
CREATE DATABASE IF NOT EXISTS inventory_tracker;
USE inventory_tracker;

-- ====================================================
-- Table: users
-- Purpose: Store user authentication and profile data
-- ====================================================
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ====================================================
-- Table: categories
-- Purpose: Store item categories
-- ====================================================
CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ====================================================
-- Table: items
-- Purpose: Store inventory items with relationships
-- ====================================================
CREATE TABLE items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    quantity INT NOT NULL DEFAULT 0,
    category_id BIGINT UNSIGNED NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    added_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Foreign Key Constraints
    CONSTRAINT fk_items_category_id 
        FOREIGN KEY (category_id) 
        REFERENCES categories(id) 
        ON DELETE SET NULL,
    
    CONSTRAINT fk_items_user_id 
        FOREIGN KEY (user_id) 
        REFERENCES users(id) 
        ON DELETE CASCADE
);

-- ====================================================
-- Indexes for better performance
-- ====================================================
CREATE INDEX idx_items_category_id ON items(category_id);
CREATE INDEX idx_items_user_id ON items(user_id);
CREATE INDEX idx_items_quantity ON items(quantity);
CREATE INDEX idx_items_added_date ON items(added_date);
CREATE INDEX idx_categories_name ON categories(name);
CREATE INDEX idx_users_email ON users(email);

-- ====================================================
-- Sample Data
-- ====================================================

-- Insert sample users (passwords are hashed using Laravel's Hash::make())
-- Default password for all users: "password123"
INSERT INTO users (name, email, password, created_at, updated_at) VALUES
('John Doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW()),
('Jane Smith', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW()),
('Mike Johnson', 'mike@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW());

-- Insert sample categories
INSERT INTO categories (name, description, created_at, updated_at) VALUES
('Electronics', 'Electronic devices and accessories', NOW(), NOW()),
('Furniture', 'Office and home furniture items', NOW(), NOW()),
('Stationery', 'Office supplies and stationery items', NOW(), NOW()),
('Food', 'Food items and beverages', NOW(), NOW()),
('Tools', 'Hardware tools and equipment', NOW(), NOW());

-- Insert sample items
INSERT INTO items (name, description, quantity, category_id, user_id, added_date, created_at, updated_at) VALUES
-- Electronics (category_id = 1)
('MacBook Pro', 'High-performance laptop for professional work', 12, 1, 1, '2025-06-20', NOW(), NOW()),
('Wireless Mouse', 'Bluetooth mouse for computers', 2, 1, 1, '2025-06-18', NOW(), NOW()),
('USB-C Hub', 'Multi-port USB-C hub with HDMI', 8, 1, 2, '2025-06-15', NOW(), NOW()),
('Wireless Keyboard', 'Mechanical wireless keyboard', 5, 1, 2, '2025-06-12', NOW(), NOW()),

-- Furniture (category_id = 2)
('Office Chair', 'Ergonomic office chair with lumbar support', 8, 2, 1, '2025-06-15', NOW(), NOW()),
('Standing Desk', 'Height-adjustable standing desk', 3, 2, 3, '2025-06-10', NOW(), NOW()),
('Filing Cabinet', '4-drawer metal filing cabinet', 6, 2, 2, '2025-06-05', NOW(), NOW()),

-- Stationery (category_id = 3)
('Printer Paper', 'A4 white paper pack (500 sheets)', 50, 3, 1, '2025-06-08', NOW(), NOW()),
('Ballpoint Pens', 'Blue ink ballpoint pens (pack of 10)', 25, 3, 2, '2025-06-12', NOW(), NOW()),
('Notebooks', 'Spiral-bound notebooks (A5 size)', 3, 3, 3, '2025-06-14', NOW(), NOW()),
('Sticky Notes', 'Colorful sticky notes variety pack', 15, 3, 1, '2025-06-16', NOW(), NOW()),

-- Food (category_id = 4)
('Coffee Beans', 'Premium arabica coffee beans (1kg)', 0, 4, 2, '2025-06-10', NOW(), NOW()),
('Tea Bags', 'Assorted tea bags (50 count)', 4, 4, 3, '2025-06-11', NOW(), NOW()),
('Instant Noodles', 'Cup noodles variety pack', 1, 4, 1, '2025-06-13', NOW(), NOW()),

-- Tools (category_id = 5)
('Screwdriver Set', 'Multi-head screwdriver set', 7, 5, 3, '2025-06-07', NOW(), NOW()),
('Measuring Tape', '5-meter measuring tape', 4, 5, 2, '2025-06-09', NOW(), NOW());

-- ====================================================
-- Useful Views for Laravel Application
-- ====================================================

-- View: Items with category and user information
CREATE VIEW items_with_details AS
SELECT 
    i.id,
    i.name,
    i.description,
    i.quantity,
    i.added_date,
    i.created_at,
    i.updated_at,
    c.name AS category_name,
    c.id AS category_id,
    u.name AS user_name,
    u.id AS user_id,
    CASE 
        WHEN i.quantity = 0 THEN 'Out of Stock'
        WHEN i.quantity <= 5 THEN 'Low Stock'
        ELSE 'In Stock'
    END AS stock_status
FROM items i
LEFT JOIN categories c ON i.category_id = c.id
LEFT JOIN users u ON i.user_id = u.id;

-- View: Category summary with item counts
CREATE VIEW category_summary AS
SELECT 
    c.id,
    c.name,
    c.description,
    COUNT(i.id) AS item_count,
    SUM(i.quantity) AS total_quantity,
    c.created_at,
    c.updated_at
FROM categories c
LEFT JOIN items i ON c.id = i.category_id
GROUP BY c.id, c.name, c.description, c.created_at, c.updated_at;

-- View: Low stock items (quantity <= 5)
CREATE VIEW low_stock_items AS
SELECT 
    i.id,
    i.name,
    i.description,
    i.quantity,
    c.name AS category_name,
    u.name AS user_name
FROM items i
LEFT JOIN categories c ON i.category_id = c.id
LEFT JOIN users u ON i.user_id = u.id
WHERE i.quantity <= 5
ORDER BY i.quantity ASC;

-- ====================================================
-- Sample Queries for Testing
-- ====================================================

-- Check all tables and data
-- SELECT 'Users' as table_name; SELECT * FROM users;
-- SELECT 'Categories' as table_name; SELECT * FROM categories;
-- SELECT 'Items' as table_name; SELECT * FROM items;

-- Test views
-- SELECT * FROM items_with_details;
-- SELECT * FROM category_summary;
-- SELECT * FROM low_stock_items;

-- ====================================================
-- Database Statistics
-- ====================================================
/*
Database Summary:
- Tables: 3 (users, categories, items)
- Sample Users: 3
- Sample Categories: 5
- Sample Items: 15
- Views: 3 (for easier querying)
- Indexes: 6 (for performance)

Password for all sample users: "password123"
User accounts:
- john@example.com
- jane@example.com  
- mike@example.com
*/
