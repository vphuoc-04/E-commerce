<?php
$queries = [
    "CREATE TABLE IF NOT EXISTS  user_catalogues (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description TEXT NULL,
        added_by INT NULL,
        updated_by INT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        CONSTRAINT fk_user_catalogues_added_by FOREIGN KEY (added_by) REFERENCES users(id) ON DELETE SET NULL,
        CONSTRAINT fk_user_catalogues_updated_by FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
];

return $queries;
