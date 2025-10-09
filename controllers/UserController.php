<?php
require_once __DIR__ . '/../models/User.php';

include 'BaseController.php';

class UserController extends BaseController {
    protected $apiFileName = "UserApi.php";
    
    private $filterableFields = [
        'email' => 'like',
        'last_name' => 'like', 
        'first_name' => 'like',
        'phone' => 'like',
        'gender' => 'exact',
        'user_catalogue_name' => 'exact'
    ];

    public function getFilterFieldsConfig() {
        return [
            [
                'name' => 'email',
                'label' => 'Email',
                'type' => 'text',
                'placeholder' => 'Tìm theo email...'
            ],
            [
                'name' => 'last_name', 
                'label' => 'Họ',
                'type' => 'text',
                'placeholder' => 'Tìm theo họ...'
            ],
            [
                'name' => 'first_name',
                'label' => 'Tên',
                'type' => 'text', 
                'placeholder' => 'Tìm theo tên...'
            ],
            [
                'name' => 'phone',
                'label' => 'Số điện thoại',
                'type' => 'text',
                'placeholder' => 'Tìm theo SĐT...'
            ],
            [
                'name' => 'gender',
                'label' => 'Giới tính',
                'type' => 'select',
                'options' => [
                    '1' => 'Nam',
                    '2' => 'Nữ', 
                    '3' => 'Khác'
                ]
            ],
            [
                'name' => 'user_catalogue_name',
                'label' => 'Nhóm người dùng',
                'type' => 'select',
                'options' => [
                    'admin' => 'Quản trị viên',
                    'staff' => 'Nhân viên',
                    'customer' => 'Khách hàng'
                ]
            ]
        ];
    }

    public function index($page = null, $limit = 10) {
        $page = $this->getPage();
        $limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? (int) $_GET['limit'] : $limit;
        
        $filter = $this->buildFilterConditions($this->filterableFields);
        
        $query = "SELECT * FROM users" . $filter['where'] . " ORDER BY created_at DESC";
        $pagination = $this->basePagination($query, $filter['params'], $page, $limit);
        $users = array_map(fn($row) => new User($row), $pagination['data']);

        return [
            'users' => $users,
            'pagination' => $pagination,
            'filters' => [
                'current' => $this->getCurrentFilters(),
                'hasActive' => $this->hasActiveFilters()
            ]
        ];
    }

    public function buildUserApiUrl($page = null) {
        return $this->buildApiUrl('index', $page, array_keys($this->filterableFields));
    }

    public function buildUserFilterUrl() {
        return $this->buildCleanFilterUrl('users', array_keys($this->filterableFields), 1);
    }


    public function buildUserPageUrl($page) {
        return $this->buildPageUrl($page, array_keys($this->filterableFields));
    }

    public function buildUserClearFilterUrl() {
        return $this->buildClearFilterUrl();
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
