<?php
include_once __DIR__ . '/BaseController.php';
include_once __DIR__ . '/../models/User.php';
include_once __DIR__ . '/../repositories/UserRepository.php';

class UserController extends BaseController {
    private $repository;

    public function __construct(PDO $pdo) {
        parent::__construct($pdo);
        $this->repository = new UserRepository($pdo);
    }
  
    public function getUserById($userId) {
        return $this->repository->getUserById($userId);
    }
}
?>

