<?php
include_once __DIR__ . '/BaseController.php';
include_once __DIR__ . '/../models/Clothing.php';
include_once __DIR__ . '/../repositories/ClothingRepository.php';

class ClothingController extends BaseController {
    private $repository;

    public function __construct(PDO $pdo) {
        parent::__construct($pdo);
        $this->repository = new ClothingRepository($pdo);
    }

    public function create(Clothing $ubranie) {
        return $this->repository->create($ubranie);
    }

    public function firstOrCreate(Clothing $ubranie) {
        $existing = $this->findByName($ubranie->getNazwaUbrania());
        if ($existing) {
            return $existing->getIdUbranie();
        }
        return $this->create($ubranie);
    }

    public function findByName($nazwa) {
        return $this->repository->findByName($nazwa);
    }

    public function searchByName($query) {
        return $this->repository->searchByName($query);
    }

    public function getAllWithRozmiary() {
        return $this->repository->getAllWithRozmiary();
    }

    public function getAllUnique() {
        return $this->repository->getAllUnique();
    }

    public function getRozmiaryByUbranieId($ubranieId) {
        return $this->repository->getRozmiaryByUbranieId($ubranieId);
    }
}
?>

