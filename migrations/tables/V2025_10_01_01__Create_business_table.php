<?php
$queries = [
    "CREATE TABLE IF NOT EXISTS business (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        registration_number VARCHAR(100) UNIQUE,
        type VARCHAR(100),
        address VARCHAR(255),
        city VARCHAR(100),
        province VARCHAR(100),
        country VARCHAR(100) DEFAULT 'Vietnam',
        phone VARCHAR(20),
        email VARCHAR(100),
        website VARCHAR(255),
        owner_name VARCHAR(100),
        owner_contact VARCHAR(100),
        industry VARCHAR(100),
        description TEXT,
        founded_date DATE,
        employee_count INT,
        status ENUM('active','inactive','suspended') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        deleted_at TIMESTAMP NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
];

return $queries;
