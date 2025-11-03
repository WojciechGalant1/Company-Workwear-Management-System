<?php
include_once __DIR__ . '/BaseRepository.php';
include_once __DIR__ . '/../models/Size.php';

class SizeRepository extends BaseRepository {

    public function create(Size $size) {
        $stmt = $this->pdo->prepare("INSERT INTO rozmiar (nazwa_rozmiaru) VALUES (:nazwa_rozmiaru)");
        $nazwa_rozmiaru = $size->getNazwaRozmiaru();
        $stmt->bindParam(':nazwa_rozmiaru', $nazwa_rozmiaru);
        $stmt->execute();
        return $this->pdo->lastInsertId();
    }

    public function findByName($nazwa) {
        $stmt = $this->pdo->prepare("SELECT id_rozmiar, nazwa_rozmiaru FROM rozmiar WHERE nazwa_rozmiaru = :nazwa_rozmiaru");
        $stmt->bindParam(':nazwa_rozmiaru', $nazwa);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $size = new Size($result['nazwa_rozmiaru']);
            $size->setIdRozmiar($result['id_rozmiar']);
            return $size;
        }
        return null;
    }

    public function searchByName($query) {
        $stmt = $this->pdo->prepare('SELECT nazwa_rozmiaru AS rozmiar FROM rozmiar WHERE nazwa_rozmiaru LIKE :query LIMIT 10');
        $query = "%$query%";
        $stmt->bindParam(':query', $query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

