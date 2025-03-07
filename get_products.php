<?php
require_once 'db.php';

header('Content-Type: application/json');

$stmt = $pdo->query("SELECT product_id, name FROM oc_product_description WHERE language_id = 1 ORDER BY name");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($products);
?>