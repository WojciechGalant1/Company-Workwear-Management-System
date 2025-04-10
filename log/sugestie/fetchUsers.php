<?php

include_once '../../database/User.php';
include_once '../../database/Database.php';

header('Content-Type: application/json');

try {
    $database = new Database();
    $pdo = $database->getPdo();

    $query = isset($_GET['query']) ? $_GET['query'] : '';
    $query = '%' . str_replace(' ', '%', $query) . '%';

    $stmt = $pdo->prepare('SELECT name, surname FROM users WHERE CONCAT(name, " ", surname) LIKE :query AND status = 1');
    $stmt->execute(['query' => $query]);

    $users = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $users[] = $row;
    }

    echo json_encode($users);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Connection failed: ' . $e->getMessage()]);
}
?>