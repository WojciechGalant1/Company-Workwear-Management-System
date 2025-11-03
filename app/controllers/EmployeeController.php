<?php
include_once __DIR__ . '/BaseController.php';
include_once __DIR__ . '/../models/Employee.php';
include_once __DIR__ . '/../repositories/EmployeeRepository.php';

class EmployeeController extends BaseController {
    private $repository;

    public function __construct(PDO $pdo) {
        parent::__construct($pdo);
        $this->repository = new EmployeeRepository($pdo);
    }
    
    public function create(Employee $pracownik) {
        return $this->repository->create($pracownik);
    }

    public function update($id, $imie, $nazwisko, $stanowisko, $status) {
        return $this->repository->update($id, $imie, $nazwisko, $stanowisko, $status);
    }

    public function getAll() {
        return $this->repository->getAll();
    }

    public function getById($id) {
        return $this->repository->getById($id);
    }

    public function searchByName($query) {
        return $this->repository->searchByName($query);
    }

    public function findByImieNazwisko($imieNazwisko) {
        return $this->repository->findByImieNazwisko($imieNazwisko);
    }
}
?>

