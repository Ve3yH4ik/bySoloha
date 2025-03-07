<?php
require_once 'db.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Некорректный ID']);
    exit;
}

// Удаляем указанную связку
$stmt = $pdo->prepare("DELETE FROM product_links WHERE id = :id");
$stmt->execute(['id' => (int)$data['id']]);

echo json_encode(['status' => 'success', 'message' => 'Связка удалена']);
?>