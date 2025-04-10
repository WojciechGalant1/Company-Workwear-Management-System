<?php
include_once __DIR__ . '/../database/Database.php';
include_once __DIR__ . '/../models/User.php';

class UserC extends Database{

    private $pdo; 

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->getPdo();
    }
  
    public function getUserById($userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM uzytkownicy WHERE id = :id");
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
