<?php
require_once 'db.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['source_product_id'], $data['source_option_id'], $data['source_value_id'], $data['targets']) || !is_array($data['targets'])) {
    echo json_encode(['status' => 'error', 'message' => 'Некорректные данные']);
    exit;
}

$source_product_id = (int) $data['source_product_id'];
$source_option_id = (int) $data['source_option_id'];
$source_value_id = (int) $data['source_value_id'];

$pdo->beginTransaction();

try {
    foreach ($data['targets'] as $target) {
        if (!isset($target['target_product_id'], $target['target_option_id'], $target['target_value_id'])) {
            continue;
        }

        $target_product_id = (int) $target['target_product_id'];
        $target_option_id = (int) $target['target_option_id'];
        $target_value_id = (int) $target['target_value_id'];

        $stmt = $pdo->prepare("
            INSERT INTO product_links (source_product_id, source_option_id, source_value_id, target_product_id, target_option_id, target_value_id)
            VALUES (:source_product_id, :source_option_id, :source_value_id, :target_product_id, :target_option_id, :target_value_id)
        ");
        $stmt->execute([
            'source_product_id' => $source_product_id,
            'source_option_id' => $source_option_id,
            'source_value_id' => $source_value_id,
            'target_product_id' => $target_product_id,
            'target_option_id' => $target_option_id,
            'target_value_id' => $target_value_id
        ]);
    }

    $pdo->commit();
    echo json_encode(['status' => 'success', 'message' => 'Связки успешно добавлены']);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['status' => 'error', 'message' => 'Ошибка сохранения: ' . $e->getMessage()]);
}
?>