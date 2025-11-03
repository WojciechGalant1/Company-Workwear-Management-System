<?php
include_once __DIR__ . '/BaseController.php';
include_once __DIR__ . '/../models/Code.php';
include_once __DIR__ . '/../repositories/CodeRepository.php';

class CodeController extends BaseController {
    private $repository;

    public function __construct(PDO $pdo) {
        parent::__construct($pdo);
        $this->repository = new CodeRepository($pdo);
    }
    
    public function create(Code $kod) {
        return $this->repository->create($kod);
    }

    public function findByNazwa($kod_nazwa) {
        return $this->repository->findByNazwa($kod_nazwa);
    }

    public function findKodByNazwa($kod_nazwa) {
        return $this->repository->findKodByNazwa($kod_nazwa);
    }
}
?>

