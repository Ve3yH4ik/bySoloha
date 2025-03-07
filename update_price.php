<?php
require_once 'db.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Некорректный ID']);
    exit;
}

// Получаем данные связки
$stmt = $pdo->prepare("SELECT * FROM product_links WHERE id = :id");
$stmt->execute(['id' => (int)$data['id']]);
$link = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$link) {
    echo json_encode(['status' => 'error', 'message' => 'Связка не найдена']);
    exit;
}

// Получаем текущую цену товара-источника
$stmt = $pdo->prepare("
    SELECT pov.price FROM oc_product_option_value pov
    WHERE pov.product_id = :product_id AND pov.option_value_id = :option_value_id
");
$stmt->execute([
    'product_id' => $link['source_product_id'],
    'option_value_id' => $link['source_value_id']
]);
$source_price = $stmt->fetchColumn();

if ($source_price === false) {
    echo json_encode(['status' => 'error', 'message' => 'Не удалось получить цену источника']);
    exit;
}

// Обновляем цену у товара-получателя
$stmt = $pdo->prepare("
    UPDATE oc_product_option_value 
    SET price = :price 
    WHERE product_id = :product_id AND option_value_id = :option_value_id
");
$stmt->execute([
    'price' => $source_price,
    'product_id' => $link['target_product_id'],
    'option_value_id' => $link['target_value_id']
]);

echo json_encode(['status' => 'success', 'message' => 'Цена обновлена', 'price' => $source_price]);
?>