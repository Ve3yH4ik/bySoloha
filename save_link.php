<?php
require_once 'db.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['source_product_id'], $data['option_sets']) || !is_array($data['option_sets'])) {
    echo json_encode(['status' => 'error', 'message' => 'Некорректные данные']);
    exit;
}

$source_product_id = (int) $data['source_product_id'];

$pdo->beginTransaction();

try {
    foreach ($data['option_sets'] as $option_set) {
        if (!isset($option_set['option_id'], $option_set['value_id'])) {
            continue;
        }

        $option_id = (int) $option_set['option_id'];
        $value_id = (int) $option_set['value_id'];

        $stmt = $pdo->prepare("
            INSERT INTO product_option_sets (source_product_id, option_id, value_id)
            VALUES (:source_product_id, :option_id, :value_id)
        ");
        $stmt->execute([
            'source_product_id' => $source_product_id,
            'option_id' => $option_id,
            'value_id' => $value_id
        ]);
    }

    $pdo->commit();
    echo json_encode(['status' => 'success', 'message' => 'Наборы опций успешно добавлены']);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['status' => 'error', 'message' => 'Ошибка сохранения: ' . $e->getMessage()]);
}
?>