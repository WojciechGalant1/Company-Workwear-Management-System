<?php
include_once __DIR__ . '/BaseController.php';
include_once __DIR__ . '/../models/User.php';

class UserController extends BaseController{

    public function __construct(PDO $pdo) {
        parent::__construct($pdo);
    }
  
    public function getUserById($userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM uzytkownicy WHERE id = :id");
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>

