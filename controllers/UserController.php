<?php
require_once __DIR__ . '/../models/User.php';

include 'BaseController.php';

class UserController extends BaseController {
    public function index($page = 1, $limit = 10) {
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : $page;
        $limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? (int) $_GET['limit'] : $limit;
        $query = "SELECT * FROM users ORDER BY created_at DESC";
        $pagination = $this->basePagination($query, [], $page, $limit);
        $users = array_map(fn($row) => new User($row), $pagination['data']);

        return [
            'users' => $users,
            'pagination' => [
                'total'       => $pagination['total'],
                'page'        => $pagination['page'],
                'limit'       => $pagination['limit'],
                'total_pages' => $pagination['total_pages'],
            ]
        ];
    }

    public function show($id) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new User($row);
        }
        return null;
    }

    public function store(array $data) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("
            INSERT INTO users (img, last_name, middle_name, first_name, gender, birth_date, phone, email, password, user_catalogue_name, created_at) 
            VALUES (:img, :last_name, :middle_name, :first_name, :gender, :birth_date, :phone, :email, :password, :user_catalogue_name, NOW())
        ");
        $stmt->execute([
            'img'               => $data['img'] ?? null,
            'last_name'         => $data['last_name'] ?? null,
            'middle_name'       => $data['middle_name'] ?? null,
            'first_name'        => $data['first_name'] ?? null,
            'gender'            => $data['gender'] ?? null,
            'birth_date'        => $data['birth_date'] ?? null,
            'phone'             => $data['phone'] ?? null,
            'email'             => $data['email'],
            'password'          => password_hash($data['password'], PASSWORD_BCRYPT),
            'user_catalogue_name' => $data['user_catalogue_name'] ?? null,
        ]);

        return $this->show($pdo->lastInsertId());
    }

    public function update($id, array $data) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("
            UPDATE users SET 
                img = :img,
                last_name = :last_name,
                middle_name = :middle_name,
                first_name = :first_name,
                gender = :gender,
                birth_date = :birth_date,
                phone = :phone,
                email = :email,
                user_catalogue_name = :user_catalogue_name,
                updated_at = NOW()
            WHERE id = :id
        ");
        $stmt->execute([
            'id'                => $id,
            'img'               => $data['img'] ?? null,
            'last_name'         => $data['last_name'] ?? null,
            'middle_name'       => $data['middle_name'] ?? null,
            'first_name'        => $data['first_name'] ?? null,
            'gender'            => $data['gender'] ?? null,
            'birth_date'        => $data['birth_date'] ?? null,
            'phone'             => $data['phone'] ?? null,
            'email'             => $data['email'] ?? null,
            'user_catalogue_name' => $data['user_catalogue_name'] ?? null,
        ]);

        return $this->show($id);
    }

    public function destroy($id) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
