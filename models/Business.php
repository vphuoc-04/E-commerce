<?php
require_once __DIR__ . '/../configs/database.php';

class Business {
    public ?int $id;
    public ?string $name;
    public ?string $registrationNumber;
    public ?string $type;
    public ?string $address;
    public ?string $city;
    public ?string $province;
    public ?string $country;
    public ?string $phone;
    public ?string $email;
    public ?string $website;
    public ?string $ownerName;
    public ?string $ownerContact;
    public ?string $industry;
    public ?string $description;
    public ?string $foundedDate;
    public ?int $employeeCount;
    public ?string $status;
    public ?DateTime $createdAt;
    public ?DateTime $updatedAt;
    public ?DateTime $deletedAt;

    public function __construct(array $data = []) {
        $this->id                = $data['id'] ?? null;
        $this->name              = $data['name'] ?? null;
        $this->registrationNumber = $data['registration_number'] ?? null;
        $this->type              = $data['type'] ?? null;
        $this->address           = $data['address'] ?? null;
        $this->city              = $data['city'] ?? null;
        $this->province          = $data['province'] ?? null;
        $this->country           = $data['country'] ?? 'Vietnam';
        $this->phone             = $data['phone'] ?? null;
        $this->email             = $data['email'] ?? null;
        $this->website           = $data['website'] ?? null;
        $this->ownerName         = $data['owner_name'] ?? null;
        $this->ownerContact      = $data['owner_contact'] ?? null;
        $this->industry          = $data['industry'] ?? null;
        $this->description       = $data['description'] ?? null;
        $this->foundedDate       = $data['founded_date'] ?? null;
        $this->employeeCount     = $data['employee_count'] ?? null;
        $this->status            = $data['status'] ?? 'active';
        $this->createdAt         = isset($data['created_at']) ? new DateTime($data['created_at']) : null;
        $this->updatedAt         = isset($data['updated_at']) ? new DateTime($data['updated_at']) : null;
        $this->deletedAt         = isset($data['deleted_at']) ? new DateTime($data['deleted_at']) : null;
    }

    public static function findById($id) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM business WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Business($row);
        }
        return null;
    }
}
