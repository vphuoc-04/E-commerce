<?php
$queries = [
    "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        last_name VARCHAR(50) NOT NULL,
        middle_name VARCHAR(50) NULL,
        first_name VARCHAR(50) NOT NULL,
        gender TINYINT(1) NOT NULL,
        birth_date DATE NULL,
        phone VARCHAR(20) NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NULL,
        img VARCHAR(255) NULL,
        user_catalogue_name VARCHAR(100) NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;"
];

return $queries;
