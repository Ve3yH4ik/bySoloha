<?php
require_once 'db.php';

$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
$option_id = isset($_GET['option_id']) ? (int)$_GET['option_id'] : 0;

if ($product_id === 0 || $option_id === 0) {
    echo json_encode([]);
    exit;
}

$stmt = $pdo->prepare("
    SELECT DISTINCT ov.option_value_id, ovd.name, pov.price
    FROM oc_product_option_value pov
    JOIN oc_option_value ov ON pov.option_value_id = ov.option_value_id
    JOIN oc_option_value_description ovd ON ov.option_value_id = ovd.option_value_id
    WHERE pov.product_id = :product_id
    AND pov.option_id = :option_id
    AND ovd.language_id = 1
");
$stmt->execute(['product_id' => $product_id, 'option_id' => $option_id]);
$values = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($values);
?>