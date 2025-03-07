<?php
require_once 'db.php';

$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;

if ($product_id === 0) {
    echo json_encode([]);
    exit;
}

$stmt = $pdo->prepare("
    SELECT DISTINCT o.option_id, od.name 
    FROM oc_product_option po
    JOIN oc_option o ON po.option_id = o.option_id
    JOIN oc_option_description od ON o.option_id = od.option_id
    WHERE po.product_id = :product_id AND od.language_id = 1
");
$stmt->execute(['product_id' => $product_id]);
$options = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($options);
?>