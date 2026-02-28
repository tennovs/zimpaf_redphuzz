-- Create the database
CREATE DATABASE IF NOT EXISTS simple_db;
USE simple_db;

-- 1. Create the 'users' table
CREATE TABLE IF NOT EXISTS users (
    user_id int NOT NULL AUTO_INCREMENT,
    username varchar(50) NOT NULL,
    email varchar(100) DEFAULT NULL,
    role varchar(100) DEFAULT NULL,
    age int DEFAULT NULL,
    income decimal(10,2) DEFAULT NULL,
    PRIMARY KEY (user_id),
    UNIQUE KEY (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 2. Create the 'orders' table
CREATE TABLE IF NOT EXISTS orders (
    order_id int NOT NULL AUTO_INCREMENT,
    user_id int NOT NULL,
    product_name varchar(100) NOT NULL,
    order_date timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    quantity int NOT NULL DEFAULT '1',
    cashier varchar(50) DEFAULT NULL,
    PRIMARY KEY (order_id),
    KEY (user_id),
    KEY (order_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 3. Insert complete user data
INSERT INTO users (user_id, username, email, role, age, income) VALUES
(1, 'alice',   'alice@example.com',   'cashier',        28, 35000.00),
(2, 'bob',     'bob@example.com',     'stock_keeper',   34, 32000.00),
(3, 'charlie', 'charlie@example.com', 'cashier',        22, 31000.00),
(4, 'dave',    'dave@example.com',    'branch_manager', 45, 55000.00);

-- 4. Insert specific order data
-- Using the exact values from your 'select' output
INSERT INTO orders (order_id, user_id, product_name, order_date, quantity, cashier) VALUES
(2, 1, '495.23',   '2025-09-22 11:00:00', 1, 'alice'),
(3, 3, 'Keyboard', '2025-09-22 12:30:00', 1, 'charlie'),
(4, 3, 'Monitor',  '2025-09-22 14:00:00', 1, 'charlie');
