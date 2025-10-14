<?php

$queries = [
    "ALTER TABLE users CHANGE COLUMN user_catalogue_name catalogue_id INT;",

    "ALTER TABLE users ADD COLUMN is_verified TINYINT(1) DEFAULT 0 AFTER catalogue_id;",

    "ALTER TABLE users 
        ADD CONSTRAINT fk_users_catalogue 
        FOREIGN KEY (catalogue_id) REFERENCES user_catalogues(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE;"
];

return $queries;
