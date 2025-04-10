<?php
include_once __DIR__ . '/../database/Database.php';
include_once __DIR__ . '/../models/Pracownik.php';

class PracownikC extends Database {

    private $pdo; 

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->getPdo();
    }
    
    public function create(Pracownik $pracownik) {
        $stmt = $this->pdo->prepare("INSERT INTO pracownicy (imie, nazwisko, stanowisko, status) VALUES (:imie, :nazwisko, :stanowisko, :status)");
        $imie = $pracownik->getImie();
        $nazwisko = $pracownik->getNazwisko();
        $stanowisko = $pracownik->getStanowisko();
        $status = $pracownik->getStatus();

        $stmt->bindParam(':imie', $imie);
        $stmt->bindParam(':nazwisko', $nazwisko);
        $stmt->bindParam(':stanowisko', $stanowisko);
        $stmt->bindParam(':status', $status);
        return $stmt->execute();
    }

    public function update($id, $imie, $nazwisko, $stanowisko, $status) {
        $stmt = $this->pdo->prepare("UPDATE pracownicy SET imie = :imie, nazwisko = :nazwisko, stanowisko = :stanowisko, status = :status WHERE id_pracownik = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':imie', $imie);
        $stmt->bindParam(':nazwisko', $nazwisko);
        $stmt->bindParam(':stanowisko', $stanowisko);
        $stmt->bindParam(':status', $status);

        return $stmt->execute();
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM pracownicy");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM pracownicy WHERE id_pracownik = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function searchByName($query) {
        $stmt = $this->pdo->prepare('SELECT id_pracownik, imie, nazwisko, stanowisko, status FROM pracownicy WHERE CONCAT(imie, " ", nazwisko) LIKE :query AND status = 1  ORDER BY nazwisko, imie LIMIT 10');
        $query = "%$query%";
        $stmt->bindParam(':query', $query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByImieNazwisko($imieNazwisko) {
        $parts = explode(' ', $imieNazwisko);
        $imie = $parts[0];
        $nazwisko = isset($parts[1]) ? $parts[1] : '';
        $stmt = $this->pdo->prepare('SELECT id_pracownik FROM pracownicy WHERE imie = :imie AND nazwisko = :nazwisko');
        $stmt->bindParam(':imie', $imie);
        $stmt->bindParam(':nazwisko', $nazwisko);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
