CREATE DATABASE crop_management;

USE crop_management;

CREATE TABLE crops (
    id INT AUTO_INCREMENT PRIMARY KEY,
    crop_name VARCHAR(255) NOT NULL,
    planting_date VARCHAR(255),
    growth_stage VARCHAR(255),
    farm_size VARCHAR(255),
    yield_prediction DECIMAL(10, 2)
);


