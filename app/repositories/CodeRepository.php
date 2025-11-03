<?php
include_once __DIR__ . '/BaseRepository.php';
include_once __DIR__ . '/../models/Code.php';

class CodeRepository extends BaseRepository {

    public function create(Code $code) {
        $stmt = $this->pdo->prepare("INSERT INTO kod (kod_nazwa, ubranieID, rozmiarID, status) VALUES (:kod_nazwa, :ubranieID, :rozmiarID, :status)");
        $stmt->bindValue(':kod_nazwa', $code->getNazwaKod());
        $stmt->bindValue(':ubranieID', $code->getUbranieID());
        $stmt->bindValue(':rozmiarID', $code->getRozmiarID());
        $stmt->bindValue(':status', $code->getStatus());
        return $stmt->execute() ? $this->pdo->lastInsertId() : false;
    }

    public function findByNazwa($kod_nazwa) {
        $stmt = $this->pdo->prepare("SELECT k.kod_nazwa, u.nazwa_ubrania, r.nazwa_rozmiaru, k.ubranieID, k.rozmiarID FROM kod k
         JOIN ubranie u ON k.ubranieID = u.id_ubranie JOIN rozmiar r ON k.rozmiarID = r.id_rozmiar WHERE k.kod_nazwa = :kod_nazwa");
        $stmt->bindValue(':kod_nazwa', $kod_nazwa);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return array(
                'id_ubrania' => $row['ubranieID'],
                'nazwa_ubrania' => $row['nazwa_ubrania'],
                'id_rozmiar' => $row['rozmiarID'],
                'nazwa_rozmiaru' => $row['nazwa_rozmiaru'],
            );
        }
        return null;
    }

    public function findKodByNazwa($kod_nazwa) {
        $stmt = $this->pdo->prepare('SELECT id_kod FROM kod WHERE kod_nazwa = :kod_nazwa');
        $stmt->bindValue(':kod_nazwa', $kod_nazwa);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Code');
        return $stmt->fetch();
    }
}

