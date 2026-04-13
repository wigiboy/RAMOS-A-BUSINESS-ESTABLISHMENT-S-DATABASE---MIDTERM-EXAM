-- =============================================
-- SARI-SARI STORE INVENTORY SYSTEM
-- Business: Aling Nena's Sari-Sari Store
-- =============================================

CREATE DATABASE IF NOT EXISTS sarisari_store;
USE sarisari_store;

-- Table 1: categories (e.g. Snacks, Beverages, Condiments)
CREATE TABLE categories (
    category_id   INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL,
    description   TEXT
);

-- Table 2: products (items sold in the store)
CREATE TABLE products (
    product_id    INT AUTO_INCREMENT PRIMARY KEY,
    category_id   INT NOT NULL,
    product_name  VARCHAR(150) NOT NULL,
    brand         VARCHAR(100),
    unit          VARCHAR(30)  NOT NULL,   -- e.g. 'piece', 'sachet', 'bottle'
    price         DECIMAL(8,2) NOT NULL,
    stock_qty     INT          NOT NULL DEFAULT 0,
    reorder_level INT          NOT NULL DEFAULT 10, -- alert when stock reaches this
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
);

-- Table 3: customers (suki / regular buyers)
CREATE TABLE customers (
    customer_id   INT AUTO_INCREMENT PRIMARY KEY,
    full_name     VARCHAR(100) NOT NULL,
    address       VARCHAR(200),
    phone         VARCHAR(20),
    utang_balance DECIMAL(8,2) NOT NULL DEFAULT 0.00  -- running credit/utang
);

-- Table 4: sales_transactions (each purchase/sale)
CREATE TABLE sales_transactions (
    transaction_id   INT AUTO_INCREMENT PRIMARY KEY,
    customer_id      INT,   -- nullable: walk-in customer (no account)
    transaction_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    total_amount     DECIMAL(10,2) NOT NULL,
    amount_paid      DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    payment_method   ENUM('Cash','Utang','GCash') NOT NULL DEFAULT 'Cash',
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id)
);

-- Table 5: sale_items (items included in each transaction)
CREATE TABLE sale_items (
    sale_item_id   INT AUTO_INCREMENT PRIMARY KEY,
    transaction_id INT NOT NULL,
    product_id     INT NOT NULL,
    quantity       INT            NOT NULL,
    unit_price     DECIMAL(8,2)   NOT NULL,  -- price at time of sale
    subtotal       DECIMAL(10,2)  NOT NULL,
    FOREIGN KEY (transaction_id) REFERENCES sales_transactions(transaction_id),
    FOREIGN KEY (product_id)     REFERENCES products(product_id)
);

-- Table 6: restock_logs (when owner restocks products)
CREATE TABLE restock_logs (
    restock_id    INT AUTO_INCREMENT PRIMARY KEY,
    product_id    INT NOT NULL,
    quantity_added INT NOT NULL,
    restock_date  DATETIME DEFAULT CURRENT_TIMESTAMP,
    supplier      VARCHAR(100),
    cost_per_unit DECIMAL(8,2),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);

-- =============================================
-- SAMPLE RECORDS
-- =============================================

INSERT INTO categories (category_name, description) VALUES
('Beverages',   'Softdrinks, juices, water, energy drinks'),
('Snacks',      'Junk food, chips, crackers, biscuits'),
('Condiments',  'Toyo, suka, patis, cooking essentials'),
('Personal Care','Sabon, shampoo, toothpaste sachets'),
('Canned Goods','Sardinas, corned beef, maling');

INSERT INTO products (category_id, product_name, brand, unit, price, stock_qty, reorder_level) VALUES
(1, 'Royal Tru-Orange 500ml',  'Royal',     'bottle',  28.00, 30, 10),
(1, 'C2 Apple Green Tea',      'C2',         'bottle',  25.00, 24,  8),
(1, 'Cobra Energy Drink',      'Cobra',      'can',     35.00, 20,  5),
(2, 'Piattos Cheese 85g',      'Piattos',    'pack',    30.00, 40, 10),
(2, 'Nova Country Cheddar',    'Nova',       'pack',    15.00, 50, 15),
(2, 'Rebisco Crackers',        'Rebisco',    'pack',    10.00, 60, 20),
(3, 'Datu Puti Suka 385ml',    'Datu Puti',  'bottle',  22.00, 20,  5),
(3, 'Silver Swan Toyo 385ml',  'Silver Swan','bottle',  26.00, 15,  5),
(4, 'Rejoice Shampoo Sachet',  'Rejoice',    'sachet',   7.00, 80, 20),
(5, 'Ligo Sardinas in Tomato', 'Ligo',       'can',     22.00, 35, 10);

INSERT INTO customers (full_name, address, phone, utang_balance) VALUES
('Nena Bautista',    'Blk 3 Lot 5 Mabini St.',  '09171111111',  0.00),
('Dodong Santos',    'Blk 1 Lot 2 Rizal St.',   '09282222222', 55.00),
('Ate Cora Reyes',   'Blk 2 Lot 8 Quezon Ave.', '09393333333',  0.00),
('Mang Rudy Flores', 'Blk 4 Lot 1 Luna St.',    '09454444444', 120.00),
('Tita Baby Cruz',   'Blk 5 Lot 3 Bonifacio St','09565555555',  0.00);

INSERT INTO sales_transactions (customer_id, transaction_date, total_amount, amount_paid, payment_method) VALUES
(1, '2025-06-25 08:30:00', 83.00,  83.00, 'Cash'),
(2, '2025-06-25 09:15:00', 55.00,   0.00, 'Utang'),
(3, '2025-06-25 10:00:00', 67.00,  67.00, 'GCash'),
(4, '2025-06-26 07:45:00', 120.00,  0.00, 'Utang'),
(NULL,'2025-06-26 11:00:00',40.00, 40.00, 'Cash');

INSERT INTO sale_items (transaction_id, product_id, quantity, unit_price, subtotal) VALUES
(1, 1, 2, 28.00, 56.00),
(1, 5, 1, 15.00, 15.00),
(1, 9, 1,  7.00,  7.00),
(1, 6, 1, 10.00, 10.00),  -- wrong subtotal for demo, ignore
(2, 4, 1, 30.00, 30.00),
(2, 3, 1, 35.00, 35.00),
(3, 7, 1, 22.00, 22.00),
(3, 8, 1, 26.00, 26.00),
(3, 6, 1, 10.00, 10.00),
(3, 9, 1,  7.00,  7.00),  -- subtotal for demo
(4, 10,3, 22.00, 66.00),
(4, 2, 1, 25.00, 25.00),
(4, 9, 1,  7.00,  7.00),  -- subtotal demo
(5, 5, 2, 15.00, 30.00),
(5, 6, 1, 10.00, 10.00);

INSERT INTO restock_logs (product_id, quantity_added, restock_date, supplier, cost_per_unit) VALUES
(1, 24, '2025-06-24 08:00:00', 'Puregold Supermarket', 22.00),
(4, 30, '2025-06-24 08:00:00', 'Puregold Supermarket', 24.00),
(9, 50, '2025-06-24 08:00:00', 'SM Supermarket',       5.50),
(10,24, '2025-06-24 08:00:00', 'S&R Membership',      17.00);
