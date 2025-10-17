<?php
require_once __DIR__ . '/../configs/database.php';

require_once "UserCatalogue.php"; // Thêm model danh mục người dùng

class User {
    public ?int $id;
    public ?string $img;
    public ?string $lastName;
    public ?string $middleName;
    public ?string $firstName;
    public ?string $gender;
    public ?string $birthDate;
    public ?string $phone;
    public ?string $email;
    public ?string $password;
    public ?int $catalogueId;
    public ?UserCatalogue $catalogue;
    public ?bool $isVerified;
    public ?DateTime $createdAt;
    public ?DateTime $updatedAt;

    public function __construct(array $data = []) {
        $this->id          = $data['id'] ?? null;
        $this->img         = $data['img'] ?? null;
        $this->lastName    = $data['last_name'] ?? null;
        $this->middleName  = $data['middle_name'] ?? null;
        $this->firstName   = $data['first_name'] ?? null;
        $this->gender      = $data['gender'] ?? null;
        $this->birthDate   = $data['birth_date'] ?? null;
        $this->phone       = $data['phone'] ?? null;
        $this->email       = $data['email'] ?? null;
        $this->password    = $data['password'] ?? null;
        $this->isVerified  = isset($data['is_verified']) ? (bool)$data['is_verified'] : null;
        $this->catalogueId = $data['catalogue_id'] ?? null;

        $this->catalogue = null;
        if (isset($data['catalogue_ref_id']) || isset($data['catalogue_name'])) {
            $this->catalogue = new UserCatalogue([
                'id'          => $data['catalogue_ref_id'] ?? null,
                'name'        => $data['catalogue_name'] ?? null,
                'description' => $data['catalogue_description'] ?? null,
            ]);
        }

        $this->createdAt = isset($data['created_at']) ? new DateTime($data['created_at']) : null;
        $this->updatedAt = isset($data['updated_at']) ? new DateTime($data['updated_at']) : null;
    }

    public static function findByEmail($email) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new User($row);
        }
        return null;
    }

    public static function findByPhone($phone) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE phone = :phone LIMIT 1");
        $stmt->execute(['phone' => $phone]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new User($row);
        }
        return null;
    }

    public static function storeRefreshToken($userId, $refreshToken, $expiresAt) {
        $pdo = Database::connect(); 

        $stmt = $pdo->prepare("DELETE FROM user_tokens WHERE user_id = ?");
        $stmt->execute([$userId]);


        $stmt = $pdo->prepare("INSERT INTO user_tokens (user_id, refresh_token, expires_at) VALUES (?, ?, ?)");
        return $stmt->execute([$userId, $refreshToken, $expiresAt]);
    }

    public static function findByRefreshToken($refreshToken) {
        $pdo = Database::connect();

        $stmt = $pdo->prepare("
            SELECT u.*, ut.expires_at 
            FROM users u 
            JOIN user_tokens ut ON u.id = ut.user_id 
            WHERE ut.refresh_token = ?
            LIMIT 1
        ");
        $stmt->execute([$refreshToken]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        $pdo = Database::connect();
        
        $stmt = $pdo->prepare("
            INSERT INTO users (
                first_name, middle_name, last_name, email, password, 
                gender, birth_date, phone, catalogue_id, 
                is_verified, created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0, NOW(), NOW())
        ");
        
        $result = $stmt->execute([
            $data['first_name'],
            $data['middle_name'],
            $data['last_name'],
            $data['email'],
            $data['password'],
            $data['gender'],
            $data['birth_date'],
            $data['phone'],
            $data['catalogue_id']
        ]);
        
        if ($result) {
            return $pdo->lastInsertId();
        }
        return false;
    }

    public static function updateVerificationStatus($userId, $isVerified = true) {
        $pdo = Database::connect();
        
        $stmt = $pdo->prepare("
            UPDATE users 
            SET is_verified = ?, updated_at = NOW() 
            WHERE id = ?
        ");
        
        return $stmt->execute([$isVerified ? 1 : 0, $userId]);
    }

    public static function isEmailVerified($email) {
        $pdo = Database::connect();
        
        $stmt = $pdo->prepare("
            SELECT is_verified FROM users 
            WHERE email = ? 
            LIMIT 1
        ");
        $stmt->execute([$email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ? (bool)$result['is_verified'] : false;
    }

    public static function findById($userId) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new User($row);
        }
        return null;
    }

}