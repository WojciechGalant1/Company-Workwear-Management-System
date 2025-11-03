<?php
include_once __DIR__ . '/database/Database.php'; 
include_once __DIR__ . '/../controllers/BaseController.php';
include_once __DIR__ . '/../controllers/WarehouseController.php';
include_once __DIR__ . '/../controllers/ClothingController.php';
include_once __DIR__ . '/../controllers/SizeController.php';
include_once __DIR__ . '/../controllers/OrderHistoryController.php';
include_once __DIR__ . '/../controllers/OrderDetailsController.php';
include_once __DIR__ . '/../controllers/EmployeeController.php';
include_once __DIR__ . '/../controllers/UserController.php';
include_once __DIR__ . '/../controllers/IssueController.php';
include_once __DIR__ . '/../controllers/IssuedClothingController.php';
include_once __DIR__ . '/../controllers/CodeController.php';

class ServiceContainer {
    private static $instance = null;
    private $pdo;
    private $controllers = [];
    
    private function __construct() {
        $db = new Database();
        $this->pdo = $db->getPdo();
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getPdo() {
        return $this->pdo;
    }
    
    public function getController($controllerName) {
        if (!isset($this->controllers[$controllerName])) {
            $this->controllers[$controllerName] = $this->createController($controllerName);
        }
        return $this->controllers[$controllerName];
    }
    
    private function createController($controllerName) {
        switch ($controllerName) {
            case 'WarehouseController':
                return new WarehouseController($this->pdo);
            case 'ClothingController':
                return new ClothingController($this->pdo);
            case 'SizeController':
                return new SizeController($this->pdo);
            case 'OrderHistoryController':
                return new OrderHistoryController($this->pdo);
            case 'OrderDetailsController':
                return new OrderDetailsController($this->pdo);
            case 'EmployeeController':
                return new EmployeeController($this->pdo);
            case 'UserController':
                return new UserController($this->pdo);
            case 'IssueController':
                return new IssueController($this->pdo);
            case 'IssuedClothingController':
                return new IssuedClothingController($this->pdo);
            case 'CodeController':
                return new CodeController($this->pdo);
            default:
                throw new Exception("Controller $controllerName not found");
        }
    }
}
