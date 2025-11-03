<?php
include_once __DIR__ . '/BaseController.php';
include_once __DIR__ . '/../models/OrderHistory.php';
include_once __DIR__ . '/../repositories/OrderHistoryRepository.php';
include_once __DIR__ . '/ClothingController.php';
include_once __DIR__ . '/SizeController.php';
include_once __DIR__ . '/WarehouseController.php';

class OrderHistoryController extends BaseController {
    private $repository;

    public function __construct(PDO $pdo) {
        parent::__construct($pdo);
        $this->repository = new OrderHistoryRepository($pdo);
    }

    public function create(OrderHistory $zamowienie) {
        return $this->repository->create($zamowienie);
    }
    
    public function getLastInsertId() {
        return $this->repository->getLastInsertId();
    }

    public function getAll() {
        return $this->repository->getAll();
    }

    public function dodajDoMagazynu(OrderHistory $zamowienie) {
        $szczegolyZamowieniaC = new OrderDetailsController($this->pdo);
        $szczegoly = $szczegolyZamowieniaC->getByZamowienieId($zamowienie->getId());

        foreach ($szczegoly as $szczegolData) {
            $ubranieC = new ClothingController($this->pdo);
            $rozmiarC = new SizeController($this->pdo);
            $stanMagazynuC = new WarehouseController($this->pdo);

            $idUbrania = $szczegolData['id_ubrania'];
            $idRozmiaru = $szczegolData['id_rozmiaru'];
            $ilosc = $szczegolData['ilosc'];
            $iloscMin = $szczegolData['iloscMin'];

            $stanMagazynu = new Warehouse($idUbrania, $idRozmiaru, $ilosc, $iloscMin);
            if (!$stanMagazynuC->create($stanMagazynu)) {
                return false;
            }
        }
        return true;
    }
}

