<?php

function runUserSeeder($pdo) {
    $users = [
        [
            "img" => "https://via.placeholder.com/40",
            "lastName" => "Đoàn",
            "middleName" => "Văn",
            "firstName" => "Phước",
            "gender" => 1,
            "birthDate" => "2004-09-24",
            "phone" => "0853404407",
            "email" => "vanphuoc240904@gmail.com",
            "password" => "12345",
            "catalogueId" => 1
        ],
    ];

    $sql = "INSERT INTO users 
        (img, last_name, middle_name, first_name, gender, birth_date, phone, email, password, catalogue_id) 
        VALUES 
        (:img, :lastName, :middleName, :firstName, :gender, :birthDate, :phone, :email, :password, :catalogueId)";

    $stmt = $pdo->prepare($sql);

    foreach ($users as $user) {
        $user['password'] = password_hash($user['password'], PASSWORD_BCRYPT);
        $stmt->execute($user);
    }

    echo "UserSeeder đã chèn dữ liệu mẫu thành công!\n";
}
