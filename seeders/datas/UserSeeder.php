<?php

function runUserSeeder($pdo) {
    $users = [
        [
            "img" => "https://via.placeholder.com/40",
            "lastName" => "Đoàn",
            "middleName" => "Văn",
            "firstName" => "Phước",
            "gender" => 1,
            "birthDate" => "2000-05-12",
            "phone" => "0912345678",
            "email" => "doanvanphuoc@gmail.com",
            "password" => "12345",
            "userCatalogueName" => "Khách hàng VIP"
        ],
        [
            "img" => "https://via.placeholder.com/40",
            "lastName" => "Hồ",
            "middleName" => "Ngọc",
            "firstName" => "Hân",
            "gender" => 0,
            "birthDate" => "1998-09-20",
            "phone" => "0987654321",
            "email" => "hongochan@gmail.com",
            "password" => "12345", 
            "userCatalogueName" => "Khách hàng thường"
        ]
    ];

    $sql = "INSERT INTO users 
        (img, last_name, middle_name, first_name, gender, birth_date, phone, email, password, user_catalogue_name) 
        VALUES 
        (:img, :lastName, :middleName, :firstName, :gender, :birthDate, :phone, :email, :password, :userCatalogueName)";

    $stmt = $pdo->prepare($sql);

    foreach ($users as $user) {
        $user['password'] = password_hash($user['password'], PASSWORD_BCRYPT);
        $stmt->execute($user);
    }

    echo "UserSeeder đã chèn dữ liệu mẫu thành công!\n";
}
