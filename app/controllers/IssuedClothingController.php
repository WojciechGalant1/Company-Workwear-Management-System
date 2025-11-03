<?php
include_once __DIR__ . '/BaseController.php';
include_once __DIR__ . '/../models/IssuedClothing.php';
include_once __DIR__ . '/../repositories/IssuedClothingRepository.php';

class IssuedClothingController extends BaseController {
    private $repository;

    public function __construct(PDO $pdo) {
        parent::__construct($pdo);
        $this->repository = new IssuedClothingRepository($pdo);
    }

    public function create(IssuedClothing $wydaneUbrania) {
        return $this->repository->create($wydaneUbrania);
    }
     
    public function getUbraniaByWydanieId($id_wydania) {
        return $this->repository->getUbraniaByWydanieId($id_wydania);
    }

    public function getUbraniaByWydanieIdTermin($id_wydania) {
        return $this->repository->getUbraniaByWydanieIdTermin($id_wydania);
    }

    public function updateStatus($id, $newStatus) {
        return $this->repository->updateStatus($id, $newStatus);
    }

    public function destroyStatus($id) {
        return $this->repository->destroyStatus($id);
    }

    public function deleteWydaneUbranieStatus($id) {
        return $this->repository->deleteWydaneUbranieStatus($id);
    }

    public function getUbraniaPoTerminie() {
        return $this->repository->getUbraniaPoTerminie();
    }

    public function getWydaneUbraniaWithDetails() {
        return $this->repository->getWydaneUbraniaWithDetails();
    }
    
    public function getUbraniaById($id) {
        return $this->repository->getUbraniaById($id);
    }
    
    
    
}

