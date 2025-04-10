<?php
include_once __DIR__ . '/../database/Database.php';
include_once __DIR__ . '/../models/Rozmiar.php';

class RozmiarC extends Database {

    private $pdo; 

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->getPdo();
    }
    
    public function create(Rozmiar $rozmiar) {
        $stmt = $this->pdo->prepare("INSERT INTO rozmiar (nazwa_rozmiaru) VALUES (:nazwa_rozmiaru)");
        $nazwa_rozmiaru = $rozmiar->getNazwaRozmiaru();
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
            $rozmiar = new Rozmiar($result['nazwa_rozmiaru']);
            $rozmiar->setIdRozmiar($result['id_rozmiar']);
            return $rozmiar;
        }
        return null;
    }

    public function firstOrCreate(Rozmiar $rozmiar) {
        $rozmiar->setNazwaRozmiaru($rozmiar->getNazwaRozmiaru());
        $existing = $this->findByName($rozmiar->getNazwaRozmiaru());
        if ($existing) {
            return $existing->getIdRozmiar();
        }
        return $this->create($rozmiar);
    }

    public function searchByName($query) {
        $stmt = $this->pdo->prepare('SELECT nazwa_rozmiaru AS rozmiar FROM rozmiar WHERE nazwa_rozmiaru LIKE :query LIMIT 10');
        $query = "%$query%";
        $stmt->bindParam(':query', $query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>
