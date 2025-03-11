<?php
require_once 'db.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Некорректный ID']);
    exit;
}

// Удаляем указанный набор опций
$stmt = $pdo->prepare("DELETE FROM product_option_sets WHERE id = :id");
$stmt->execute(['id' => (int)$data['id']]);

echo json_encode(['status' => 'success', 'message' => 'Набор опций удален']);
?>