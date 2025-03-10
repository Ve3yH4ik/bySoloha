<?php
require_once 'db.php';

header('Content-Type: application/json');

$stmt = $pdo->query("
    SELECT pos.id, pd.name AS source_product, od.name AS option, ovd.name AS value, pov.price
    FROM product_option_sets pos
    JOIN oc_product_description pd ON pos.source_product_id = pd.product_id AND pd.language_id = 1
    JOIN oc_option_description od ON pos.option_id = od.option_id AND od.language_id = 1
    JOIN oc_option_value_description ovd ON pos.value_id = ovd.option_value_id AND ovd.language_id = 1
    JOIN oc_product_option_value pov ON pos.source_product_id = pov.product_id AND pos.value_id = pov.option_value_id
");

$option_sets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Группируем наборы опций по товарам
$grouped_option_sets = [];
foreach ($option_sets as $option_set) {
    $source_key = "{$option_set['source_product']}";
    if (!isset($grouped_option_sets[$source_key])) {
        $grouped_option_sets[$source_key] = [
            'source_product' => $option_set['source_product'],
            'option_sets' => []
        ];
    }
    $grouped_option_sets[$source_key]['option_sets'][] = [
        'id' => $option_set['id'],
        'option' => $option_set['option'],
        'value' => $option_set['value'],
        'price' => $option_set['price']
    ];
}

echo json_encode(array_values($grouped_option_sets));
?>