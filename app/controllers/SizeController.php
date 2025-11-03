<?php
include_once __DIR__ . '/BaseController.php';
include_once __DIR__ . '/../models/Size.php';
include_once __DIR__ . '/../repositories/SizeRepository.php';

class SizeController extends BaseController {
    private $repository;

    public function __construct(PDO $pdo) {
        parent::__construct($pdo);
        $this->repository = new SizeRepository($pdo);
    }
    
    public function create(Size $rozmiar) {
        return $this->repository->create($rozmiar);
    }

    public function findByName($nazwa) {
        return $this->repository->findByName($nazwa);
    }

    public function firstOrCreate(Size $rozmiar) {
        $existing = $this->findByName($rozmiar->getNazwaRozmiaru());
        if ($existing) {
            return $existing->getIdRozmiar();
        }
        return $this->create($rozmiar);
    }

    public function searchByName($query) {
        return $this->repository->searchByName($query);
    }
}
?>

