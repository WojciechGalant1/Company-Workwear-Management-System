<?php
include_once __DIR__ . '/../database/Database.php';
include_once __DIR__ . '/../models/SzczegolyZamowienia.php';

class SzczegolyZamowieniaC extends Database {
    
    private $pdo; 

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->getPdo();
    }

    public function create(SzczegolyZamowienia $szczegol) {
        $stmt = $this->pdo->prepare("INSERT INTO szczegoly_zamowienia (zamowienie_id, id_ubrania, id_rozmiaru, ilosc, iloscMin, firma, sz_kodID) VALUES (:zamowienie_id, :id_ubrania, :id_rozmiaru, :ilosc, :iloscMin, :firma, :sz_kodID)");
        $stmt->bindValue(':zamowienie_id', $szczegol->getZamowienieId());
        $stmt->bindValue(':id_ubrania', $szczegol->getIdUbrania());
        $stmt->bindValue(':id_rozmiaru', $szczegol->getIdRozmiaru());
        $stmt->bindValue(':ilosc', $szczegol->getIlosc());
        $stmt->bindValue(':iloscMin', $szczegol->getIloscMin());
        $stmt->bindValue(':firma', $szczegol->getFirma());
        $stmt->bindValue(':sz_kodID', $szczegol->getSzKodID());

        return $stmt->execute();
    }

    public function getByZamowienieId($zamowienieId) {
        $stmt = $this->pdo->prepare("SELECT zamowienie_id, id_ubrania, id_rozmiaru, ilosc, iloscMin FROM szczegoly_zamowienia WHERE zamowienie_id = :zamowienie_id");
        $stmt->bindValue(':zamowienie_id', $zamowienieId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>
