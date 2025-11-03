<?php
include_once __DIR__ . '/BaseController.php';
include_once __DIR__ . '/../models/Warehouse.php';
include_once __DIR__ . '/../models/OrderHistory.php';
include_once __DIR__ . '/../models/OrderDetails.php';
include_once __DIR__ . '/../helpers/LocalizationHelper.php';
include_once __DIR__ . '/../repositories/WarehouseRepository.php';
include_once __DIR__ . '/ClothingController.php';
include_once __DIR__ . '/SizeController.php';
include_once __DIR__ . '/UserController.php';
include_once __DIR__ . '/OrderHistoryController.php';
include_once __DIR__ . '/OrderDetailsController.php';

class WarehouseController extends BaseController {
    private $repository;

    public function __construct(PDO $pdo) {
        parent::__construct($pdo);
        $this->repository = new WarehouseRepository($pdo);
    }

    public function create(Warehouse $stanMagazynu) {
        $existingStan = $this->repository->findByUbranieAndRozmiar($stanMagazynu->getIdUbrania(), $stanMagazynu->getIdRozmiaru());
        if ($existingStan) {
            return $this->repository->increaseIlosc($existingStan['id'], $stanMagazynu->getIlosc());
        } else {
            return $this->repository->create($stanMagazynu);
        }
    }

    public function readAll() {
        return $this->repository->readAll();
    }

    public function getIlosc($id_ubrania, $id_rozmiaru) {
        return $this->repository->getIlosc($id_ubrania, $id_rozmiaru);
    }

    public function findByUbranieAndRozmiar($id_ubrania, $id_rozmiaru) {
        return $this->repository->findByUbranieAndRozmiar($id_ubrania, $id_rozmiaru);
    }

    public function findByUbranieAndRozmiarByName($nazwaUbrania, $nazwaRozmiaru) {
        return $this->repository->findByUbranieAndRozmiarByName($nazwaUbrania, $nazwaRozmiaru);
    }

    public function updateIlosc($id_ubrania, $id_rozmiaru, $ilosc, $anulowanie = false) {
        return $this->repository->updateIlosc($id_ubrania, $id_rozmiaru, $ilosc, $anulowanie);
    }
    
    public function increaseIlosc($id, $ilosc) {
        return $this->repository->increaseIlosc($id, $ilosc);
    }

    public function checkIlosc() {
        return $this->repository->checkIlosc();
    }
    

    public function updateStanMagazynu($id, $nazwa, $rozmiar, $ilosc, $iloscMin, $uwagi, $currentUserId = null) {
        try {
            $ubranieC = new ClothingController($this->pdo);
            $rozmiarC = new SizeController($this->pdo);
    
            $existingUbranie = $ubranieC->findByName($nazwa);
            $idUbrania = $existingUbranie ? $existingUbranie->getIdUbranie() : $ubranieC->create(new Clothing($nazwa));
    
            if (!$this->repository->update($id, $idUbrania, null, null, null)) {
                return array('status' => 'error', 'message' => LocalizationHelper::translate('warehouse_update_clothing_error'));
            }
    
            $existingRozmiar = $rozmiarC->findByName($rozmiar);
            $idRozmiaru = $existingRozmiar ? $existingRozmiar->getIdRozmiar() : $rozmiarC->create(new Size($rozmiar));
    
            if (!$this->repository->update($id, null, $idRozmiaru, null, null)) {
                return array('status' => 'error', 'message' => LocalizationHelper::translate('warehouse_update_size_error'));
            }
    
            $oldIlosc = $this->repository->getIloscById($id);
            $iloscDiff = $ilosc - $oldIlosc;

            if (!$this->repository->update($id, null, null, $ilosc, $iloscMin)) {
                return array('status' => 'error', 'message' => 'Blad podczas aktualizacji ilości.');
            }
    
            if ($iloscDiff !== 0) {
                $this->addHistoriaZamowien($idUbrania, $idRozmiaru, $iloscDiff, $uwagi, $currentUserId);
            }
            
            return array('status' => 'success', 'message' => 'Stan magazynu zostal zaktualizowany.');
        } catch (Exception $e) {
            return array('status' => 'error', 'message' => $e->getMessage());
        }
    }
    
    private function addHistoriaZamowien($idUbrania, $idRozmiaru, $iloscDiff, $uwagi, $currentUserId = null) {
        $historiaZamowienC = new OrderHistoryController($this->pdo);
        $szczegolyZamowieniaC = new OrderDetailsController($this->pdo);

        $userId = $currentUserId !== null ? $currentUserId : (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null);
        if (!$userId) {
            throw new Exception("Brak zalogowanego użytkownika.");
        }

        $zamowienie = new OrderHistory(new DateTime(), $userId, $uwagi, 2);

        if (!$historiaZamowienC->create($zamowienie)) {
            throw new Exception("Nie udało się zapisać historii zamówienia.");
        }

        $zamowienieId = $historiaZamowienC->getLastInsertId();
        if (!$zamowienieId) {
            throw new Exception("Nie udało się pobrać ID ostatniego zamówienia.");
        }

        $szczegol = new OrderDetails($zamowienieId, $idUbrania, $idRozmiaru, $iloscDiff, 0, "-", 0);

        if (!$szczegolyZamowieniaC->create($szczegol)) {
            throw new Exception("Nie udało się zapisać szczegółów zamówienia.");
        }
    }
}
?>

