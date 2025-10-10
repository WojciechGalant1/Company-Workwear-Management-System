<?php
include_once __DIR__ . '/BaseController.php';
include_once __DIR__ . '/../models/StanMagazynu.php';
include_once __DIR__ . '/../models/HistoriaZamowien.php';
include_once __DIR__ . '/../models/SzczegolyZamowienia.php';
include_once __DIR__ . '/UbranieC.php';
include_once __DIR__ . '/RozmiarC.php';
include_once __DIR__ . '/UserC.php';
include_once __DIR__ . '/HistoriaZamowienC.php';
include_once __DIR__ . '/SzczegolyZamowieniaC.php';

class StanMagazynuC extends BaseController {

    public function __construct(PDO $pdo) {
        parent::__construct($pdo);
    }

    public function create(StanMagazynu $stanMagazynu) {
        $existingStan = $this->findByUbranieAndRozmiar($stanMagazynu->getIdUbrania(), $stanMagazynu->getIdRozmiaru());
        if ($existingStan) {
            return $this->increaseIlosc($existingStan['id'], $stanMagazynu->getIlosc());
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO stan_magazynu (id_ubrania, id_rozmiaru, ilosc, iloscMin) VALUES (:id_ubrania, :id_rozmiaru, :ilosc, :iloscMin)");
            $id_ubrania = $stanMagazynu->getIdUbrania();
            $id_rozmiaru = $stanMagazynu->getIdRozmiaru();
            $ilosc = $stanMagazynu->getIlosc();
            $iloscMin = $stanMagazynu->getIloscMin();
            $stmt->bindParam(':id_ubrania', $id_ubrania);
            $stmt->bindParam(':id_rozmiaru', $id_rozmiaru);
            $stmt->bindParam(':ilosc', $ilosc);
            $stmt->bindParam(':iloscMin', $iloscMin);
            return $stmt->execute();
        }
    }

    public function readAll() {
        $stmt = $this->pdo->prepare("SELECT s.id, u.nazwa_ubrania, r.nazwa_rozmiaru,s.ilosc, s.iloscMin FROM stan_magazynu s
         JOIN ubranie u ON s.id_ubrania = u.id_ubranie JOIN rozmiar r ON s.id_rozmiaru = r.id_rozmiar");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getIlosc($id_ubrania, $id_rozmiaru) {
        $stmt = $this->pdo->prepare("SELECT ilosc FROM stan_magazynu WHERE id_ubrania = :id_ubrania AND id_rozmiaru = :id_rozmiaru");
        $stmt->bindParam(':id_ubrania', $id_ubrania);
        $stmt->bindParam(':id_rozmiaru', $id_rozmiaru);
        $stmt->execute();
        
        return (int)$stmt->fetchColumn();
    }

    public function findByUbranieAndRozmiar($id_ubrania, $id_rozmiaru) {
        $stmt = $this->pdo->prepare("SELECT id FROM stan_magazynu WHERE id_ubrania = :id_ubrania AND id_rozmiaru = :id_rozmiaru");
        $stmt->bindParam(':id_ubrania', $id_ubrania);
        $stmt->bindParam(':id_rozmiaru', $id_rozmiaru);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function findByUbranieAndRozmiarByName($nazwaUbrania, $nazwaRozmiaru) {
        $stmt = $this->pdo->prepare("SELECT id FROM stan_magazynu s JOIN ubranie u ON s.id_ubrania = u.id_ubranie
            JOIN rozmiar r ON s.id_rozmiaru = r.id_rozmiar WHERE u.nazwa_ubrania = :nazwaUbrania AND r.nazwa_rozmiaru = :nazwaRozmiaru");
        $stmt->bindParam(':nazwaUbrania', $nazwaUbrania);
        $stmt->bindParam(':nazwaRozmiaru', $nazwaRozmiaru);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }    

    public function updateIlosc($id_ubrania, $id_rozmiaru, $ilosc, $anulowanie = false) {
        $operation = $anulowanie ? "+" : "-";
        $stmt = $this->pdo->prepare("UPDATE stan_magazynu SET ilosc = ilosc $operation :ilosc WHERE id_ubrania = :id_ubrania AND id_rozmiaru = :id_rozmiaru");
        $stmt->bindParam(':id_ubrania', $id_ubrania);
        $stmt->bindParam(':id_rozmiaru', $id_rozmiaru);
        $stmt->bindParam(':ilosc', $ilosc);
        return $stmt->execute();
    }
    
    public function increaseIlosc($id, $ilosc) {
        $stmt = $this->pdo->prepare("UPDATE stan_magazynu SET ilosc = ilosc + :ilosc WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':ilosc', $ilosc);
        return $stmt->execute();
    }

    public function checkIlosc() {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM stan_magazynu WHERE ilosc < iloscMin");
        $stmt->execute();
        return $stmt->fetchColumn() > 0; 
    }
    

    public function updateStanMagazynu($id, $nazwa, $rozmiar, $ilosc, $iloscMin, $uwagi, $currentUserId = null) {
        try {
            //$this->pdo->beginTransaction();
            $ubranieC = new UbranieC($this->pdo);
            $rozmiarC = new RozmiarC($this->pdo);
    
            $existingUbranie = $ubranieC->findByName($nazwa);
            $idUbrania = $existingUbranie ? $existingUbranie->getIdUbranie() : $ubranieC->create(new Ubranie($nazwa));
    
            $stmt = $this->pdo->prepare("UPDATE stan_magazynu SET id_ubrania = :idUbrania WHERE id = :id");
            $stmt->bindParam(':idUbrania', $idUbrania, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            if (!$stmt->execute()) {
                $this->pdo->rollBack();
                return ['status' => 'error', 'message' => 'Błąd podczas aktualizacji nazwy ubrania.'];
            }
    
            $existingRozmiar = $rozmiarC->findByName($rozmiar);
            $idRozmiaru = $existingRozmiar ? $existingRozmiar->getIdRozmiar() : $rozmiarC->create(new Rozmiar($rozmiar));
    
            $stmt = $this->pdo->prepare("UPDATE stan_magazynu SET id_rozmiaru = :idRozmiaru WHERE id = :id");
            $stmt->bindParam(':idRozmiaru', $idRozmiaru, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            if (!$stmt->execute()) {
                $this->pdo->rollBack();
                return ['status' => 'error', 'message' => 'Błąd podczas aktualizacji nazwy rozmiaru.'];
            }
    
            $stmt = $this->pdo->prepare("SELECT ilosc FROM stan_magazynu WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $oldIlosc = $stmt->fetchColumn();
            $iloscDiff = $ilosc - $oldIlosc;

            $stmt = $this->pdo->prepare("UPDATE stan_magazynu SET ilosc = :ilosc, iloscMin = :iloscMin WHERE id = :id");
            $stmt->bindParam(':ilosc', $ilosc, PDO::PARAM_INT);
            $stmt->bindParam(':iloscMin', $iloscMin, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
            if ($stmt->execute()) {
                if ($iloscDiff !== 0) {
                    $this->addHistoriaZamowien($idUbrania, $idRozmiaru, $iloscDiff, $uwagi, $currentUserId);
                }
                $this->pdo->commit();
                return ['status' => 'success', 'message' => 'Stan magazynu został zaktualizowany.'];
            } else {
                $this->pdo->rollBack();
                return ['status' => 'error', 'message' => 'Błąd podczas aktualizacji ilości.'];
            }
        } catch (Exception $e) {
            // if ($this->pdo->inTransaction()) {
            //     $this->pdo->rollBack();
            // }
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
    
    private function addHistoriaZamowien($idUbrania, $idRozmiaru, $iloscDiff, $uwagi, $currentUserId = null) {
        $historiaZamowienC = new HistoriaZamowienC($this->pdo);
        $szczegolyZamowieniaC = new SzczegolyZamowieniaC($this->pdo);

        $userId = $currentUserId !== null ? $currentUserId : (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null);
        if (!$userId) {
            throw new Exception("Brak zalogowanego użytkownika.");
        }

        $zamowienie = new HistoriaZamowien(new DateTime(), $userId, $uwagi, 2);

        if (!$historiaZamowienC->create($zamowienie)) {
            throw new Exception("Nie udało się zapisać historii zamówienia.");
        }

        $zamowienieId = $historiaZamowienC->getLastInsertId();
        if (!$zamowienieId) {
            throw new Exception("Nie udało się pobrać ID ostatniego zamówienia.");
        }

        $szczegol = new SzczegolyZamowienia($zamowienieId, $idUbrania, $idRozmiaru, $iloscDiff, 0, "-", 0);

        if (!$szczegolyZamowieniaC->create($szczegol)) {
            throw new Exception("Nie udało się zapisać szczegółów zamówienia.");
        }
    }
}
?>
