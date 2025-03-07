<?php
require_once 'db.php';

header('Content-Type: application/json');

$logFile = 'log.txt';

function logUpdate($message) {
    global $logFile;
    file_put_contents($logFile, "[" . date("Y-m-d H:i:s") . "] " . $message . "\n", FILE_APPEND);
}

$stmt = $pdo->query("SELECT * FROM product_links");
$links = $stmt->fetchAll(PDO::FETCH_ASSOC);

$updatedCount = 0;

foreach ($links as $link) {
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
        logUpdate("Ошибка: Не удалось получить цену источника для ID: " . $link['id']);
        continue;
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

    logUpdate("Цена обновлена для связки ID " . $link['id'] . " | Новая цена: " . $source_price);
    $updatedCount++;
}

logUpdate("Обновлено $updatedCount связок.");

echo json_encode(['status' => 'success', 'message' => "Обновлено $updatedCount связок."]);
?>