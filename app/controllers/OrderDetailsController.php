<?php
include_once __DIR__ . '/BaseController.php';
include_once __DIR__ . '/../models/OrderDetails.php';
include_once __DIR__ . '/../repositories/OrderDetailsRepository.php';

class OrderDetailsController extends BaseController {
    private $repository;
    
    public function __construct(PDO $pdo) {
        parent::__construct($pdo);
        $this->repository = new OrderDetailsRepository($pdo);
    }

    public function create(OrderDetails $szczegol) {
        return $this->repository->create($szczegol);
    }

    public function getByZamowienieId($zamowienieId) {
        return $this->repository->getByZamowienieId($zamowienieId);
    }
}

?>

