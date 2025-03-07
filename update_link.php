<?php
require_once 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id']) || !isset($data['source_product_id']) || !isset($data['source_option_id']) ||
    !isset($data['source_value_id']) || !isset($data['target_product_id']) ||
    !isset($data['target_option_id']) || !isset($data['target_value_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Некорректные данные']);
    exit;
}

$stmt = $pdo->prepare("
    UPDATE product_links 
    SET source_product_id = :source_product_id, source_option_id = :source_option_id, source_value_id = :source_value_id,
        target_product_id = :target_product_id, target_option_id = :target_option_id, target_value_id = :target_value_id
    WHERE id = :id
");
$data['id'] = (int)$data['id'];
$stmt->execute($data);

echo json_encode(['status' => 'success']);
?>