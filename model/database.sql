CREATE DATABASE farm_management;

USE farm_management;

CREATE TABLE crops (
    id INT AUTO_INCREMENT PRIMARY KEY,
    crop_name VARCHAR(255) NOT NULL,
    planting_date VARCHAR(255),
    growth_stage VARCHAR(255),
    farm_size VARCHAR(255),
    yield_prediction DECIMAL(10, 2)
);
-- Create table for inventory items
CREATE TABLE inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_type ENUM('seed', 'fertilizer', 'pesticide') NOT NULL,
    item_name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    unit VARCHAR(50) NOT NULL,
    low_inventory_threshold INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create table for inventory usage reports
CREATE TABLE inventory_usage (
    id INT AUTO_INCREMENT PRIMARY KEY,
    inventory_id INT NOT NULL,
    quantity_used INT NOT NULL,
    usage_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (inventory_id) REFERENCES inventory(id)
);

-- Create table for low inventory notifications
CREATE TABLE inventory_notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    inventory_id INT NOT NULL,
    notification_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    message VARCHAR(255) NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (inventory_id) REFERENCES inventory(id)
);

-- Create the financial_transactions table
CREATE TABLE financial_transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    transaction_type ENUM('expense', 'revenue') NOT NULL,
    description VARCHAR(255) NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    category VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create the budgets table
CREATE TABLE budgets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    budget_name VARCHAR(255) NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);



