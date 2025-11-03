<?php
include_once __DIR__ . '/BaseController.php';
include_once __DIR__ . '/../models/Issue.php';
include_once __DIR__ . '/../repositories/IssueRepository.php';

class IssueController extends BaseController {
    private $repository;

    public function __construct(PDO $pdo) {
        parent::__construct($pdo);
        $this->repository = new IssueRepository($pdo);
    }
    
    public function create(Issue $wydania) {
        return $this->repository->create($wydania);
    }

    public function getWydaniaByPracownikId($pracownikId) {
        return $this->repository->getWydaniaByPracownikId($pracownikId);
    }

    public function getAllWydania() {
        return $this->repository->getAllWydania();
    }

    public function getDetailedWydania() {
        return $this->repository->getDetailedWydania();
    }

    public function deleteWydanie($id_wydania) {
        return $this->repository->deleteWydanie($id_wydania);
    }
}
?>

