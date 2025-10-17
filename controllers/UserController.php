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
        'catalogue_id' => 'exact'
    ];

    public function getFilterFieldsConfig() {
        $pdo = Database::connect();
        $stmt = $pdo->query("SELECT id, name FROM user_catalogues ORDER BY name ASC");
        $catalogues = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $catalogueOptions = [];
        foreach ($catalogues as $catalogue) {
            $catalogueOptions[$catalogue['id']] = $catalogue['name'];
        }

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
                'name' => 'catalogue_id',
                'label' => 'Nhóm người dùng',
                'type' => 'select',
                'options' => $catalogueOptions
            ]
        ];
    }

    public function index($page = null, $limit = 10) {
        $page = $this->getPage();
        $limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? (int) $_GET['limit'] : $limit;

        // Xây dựng điều kiện lọc (nếu có)
        $filter = $this->buildFilterConditions($this->filterableFields);

        // JOIN với bảng user_catalogues để lấy thông tin danh mục
        $query = "
            SELECT 
                u.*, 
                uc.id AS catalogue_ref_id, 
                uc.name AS catalogue_name, 
                uc.description AS catalogue_description
            FROM users u
            LEFT JOIN user_catalogues uc ON u.catalogue_id = uc.id
            " . $filter['where'] . "
            ORDER BY u.created_at ASC
        ";

        // Phân trang
        $pagination = $this->basePagination($query, $filter['params'], $page, $limit);

        // Ánh xạ dữ liệu thành đối tượng User
        $users = array_map(fn($row) => new User($row), $pagination['data']);

        // Dùng fetchForeignKeyData để lấy thông tin chi tiết từ FK
        // foreach ($users as $user) {
        //     if (!empty($user->catalogue_id)) {
        //         $user->catalogue = $this->fetchForeignKeyData('user_catalogues', 'catalogue_id', $user->catalogue_id);
        //     } else {
        //         $user->catalogue = null;
        //     }
        // }

        // Trả kết quả
        return [
            'users' => $users,
            'pagination' => [
                'total' => $pagination['total'],
                'page' => $pagination['page'],
                'limit' => $pagination['limit'],
                'total_pages' => $pagination['total_pages'],
            ],
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

        // Duplicate checks
        if (!empty($data['email']) && User::findByEmail($data['email'])) {
            http_response_code(409);
            return [
                'error' => 'duplicate',
                'field' => 'email',
                'message' => 'Email đã tồn tại'
            ];
        }
        if (!empty($data['phone']) && User::findByPhone($data['phone'])) {
            http_response_code(409);
            return [
                'error' => 'duplicate',
                'field' => 'phone',
                'message' => 'Số điện thoại đã tồn tại'
            ];
        }
        $stmt = $pdo->prepare("
            INSERT INTO users (img, last_name, middle_name, first_name, gender, birth_date, phone, email, password, catalogue_id, created_at) 
            VALUES (:img, :last_name, :middle_name, :first_name, :gender, :birth_date, :phone, :email, :password, :catalogue_id, NOW())
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
            'catalogue_id' => $data['catalogue_id'] ?? null,
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
                catalogue_id = :catalogue_id,
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
            'catalogue_id' => $data['catalogue_id'] ?? null,
        ]);

        return $this->show($id);
    }

    public function destroy($id) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
