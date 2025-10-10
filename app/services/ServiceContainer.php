<?php
include_once __DIR__ . '/../database/Database.php';
include_once __DIR__ . '/../controllers/BaseController.php';
include_once __DIR__ . '/../controllers/StanMagazynuC.php';
include_once __DIR__ . '/../controllers/UbranieC.php';
include_once __DIR__ . '/../controllers/RozmiarC.php';
include_once __DIR__ . '/../controllers/HistoriaZamowienC.php';
include_once __DIR__ . '/../controllers/SzczegolyZamowieniaC.php';
include_once __DIR__ . '/../controllers/PracownikC.php';
include_once __DIR__ . '/../controllers/UserC.php';
include_once __DIR__ . '/../controllers/WydaniaC.php';
include_once __DIR__ . '/../controllers/WydaneUbraniaC.php';
include_once __DIR__ . '/../controllers/KodC.php';

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
            case 'StanMagazynuC':
                return new StanMagazynuC($this->pdo);
            case 'UbranieC':
                return new UbranieC($this->pdo);
            case 'RozmiarC':
                return new RozmiarC($this->pdo);
            case 'HistoriaZamowienC':
                return new HistoriaZamowienC($this->pdo);
            case 'SzczegolyZamowieniaC':
                return new SzczegolyZamowieniaC($this->pdo);
            case 'PracownikC':
                return new PracownikC($this->pdo);
            case 'UserC':
                return new UserC($this->pdo);
            case 'WydaniaC':
                return new WydaniaC($this->pdo);
            case 'WydaneUbraniaC':
                return new WydaneUbraniaC($this->pdo);
            case 'KodC':
                return new KodC($this->pdo);
            default:
                throw new Exception("Controller $controllerName not found");
        }
    }
}
