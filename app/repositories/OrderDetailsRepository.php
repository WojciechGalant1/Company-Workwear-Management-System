<?php
include_once __DIR__ . '/BaseRepository.php';
include_once __DIR__ . '/../models/OrderDetails.php';

class OrderDetailsRepository extends BaseRepository {

    public function create(OrderDetails $orderDetails) {
        $stmt = $this->pdo->prepare("INSERT INTO szczegoly_zamowienia (zamowienie_id, id_ubrania, id_rozmiaru, ilosc, iloscMin, firma, sz_kodID) VALUES (:zamowienie_id, :id_ubrania, :id_rozmiaru, :ilosc, :iloscMin, :firma, :sz_kodID)");
        $stmt->bindValue(':zamowienie_id', $orderDetails->getZamowienieId());
        $stmt->bindValue(':id_ubrania', $orderDetails->getIdUbrania());
        $stmt->bindValue(':id_rozmiaru', $orderDetails->getIdRozmiaru());
        $stmt->bindValue(':ilosc', $orderDetails->getIlosc());
        $stmt->bindValue(':iloscMin', $orderDetails->getIloscMin());
        $stmt->bindValue(':firma', $orderDetails->getFirma());
        $stmt->bindValue(':sz_kodID', $orderDetails->getSzKodID());
        return $stmt->execute();
    }

    public function getByZamowienieId($zamowienieId) {
        $stmt = $this->pdo->prepare("SELECT zamowienie_id, id_ubrania, id_rozmiaru, ilosc, iloscMin FROM szczegoly_zamowienia WHERE zamowienie_id = :zamowienie_id");
        $stmt->bindValue(':zamowienie_id', $zamowienieId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

