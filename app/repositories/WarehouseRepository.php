<?php
include_once __DIR__ . '/BaseRepository.php';
include_once __DIR__ . '/../models/Warehouse.php';

class WarehouseRepository extends BaseRepository {

    public function create(Warehouse $warehouse) {
        $stmt = $this->pdo->prepare("INSERT INTO stan_magazynu (id_ubrania, id_rozmiaru, ilosc, iloscMin) VALUES (:id_ubrania, :id_rozmiaru, :ilosc, :iloscMin)");
        $id_ubrania = $warehouse->getIdUbrania();
        $id_rozmiaru = $warehouse->getIdRozmiaru();
        $ilosc = $warehouse->getIlosc();
        $iloscMin = $warehouse->getIloscMin();
        $stmt->bindParam(':id_ubrania', $id_ubrania);
        $stmt->bindParam(':id_rozmiaru', $id_rozmiaru);
        $stmt->bindParam(':ilosc', $ilosc);
        $stmt->bindParam(':iloscMin', $iloscMin);
        return $stmt->execute();
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

    public function update($id, $idUbrania = null, $idRozmiaru = null, $ilosc = null, $iloscMin = null) {
        $updates = array();
        $params = array(':id' => $id);
        
        if ($idUbrania !== null) {
            $updates[] = 'id_ubrania = :idUbrania';
            $params[':idUbrania'] = $idUbrania;
        }
        if ($idRozmiaru !== null) {
            $updates[] = 'id_rozmiaru = :idRozmiaru';
            $params[':idRozmiaru'] = $idRozmiaru;
        }
        if ($ilosc !== null) {
            $updates[] = 'ilosc = :ilosc';
            $params[':ilosc'] = $ilosc;
        }
        if ($iloscMin !== null) {
            $updates[] = 'iloscMin = :iloscMin';
            $params[':iloscMin'] = $iloscMin;
        }
        
        if (empty($updates)) {
            return false;
        }
        
        $sql = "UPDATE stan_magazynu SET " . implode(', ', $updates) . " WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_INT);
        }
        
        return $stmt->execute();
    }

    public function getIloscById($id) {
        $stmt = $this->pdo->prepare("SELECT ilosc FROM stan_magazynu WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}

