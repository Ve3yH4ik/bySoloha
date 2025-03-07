<?php
require_once 'db.php';

header('Content-Type: application/json');

$stmt = $pdo->query("
    SELECT pl.id, 
           spd.name AS source_product, sod.name AS source_option, svd.name AS source_value, pov.price AS source_price,
           tpd.name AS target_product, tod.name AS target_option, tvd.name AS target_value, tpov.price AS target_price
    FROM product_links pl
    JOIN oc_product_description spd ON pl.source_product_id = spd.product_id AND spd.language_id = 1
    JOIN oc_option_description sod ON pl.source_option_id = sod.option_id AND sod.language_id = 1
    JOIN oc_option_value_description svd ON pl.source_value_id = svd.option_value_id AND svd.language_id = 1
    JOIN oc_product_option_value pov ON pl.source_product_id = pov.product_id AND pl.source_value_id = pov.option_value_id

    JOIN oc_product_description tpd ON pl.target_product_id = tpd.product_id AND tpd.language_id = 1
    JOIN oc_option_description tod ON pl.target_option_id = tod.option_id AND tod.language_id = 1
    JOIN oc_option_value_description tvd ON pl.target_value_id = tvd.option_value_id AND tvd.language_id = 1
    JOIN oc_product_option_value tpov ON pl.target_product_id = tpov.product_id AND pl.target_value_id = tpov.option_value_id
");

$links = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Группируем товары-получатели по источникам
$grouped_links = [];
foreach ($links as $link) {
    $source_key = "{$link['source_product']} - {$link['source_option']} - {$link['source_value']}";
    if (!isset($grouped_links[$source_key])) {
        $grouped_links[$source_key] = [
            'source_product' => $link['source_product'],
            'source_option' => $link['source_option'],
            'source_value' => $link['source_value'],
            'source_price' => $link['source_price'],
            'targets' => []
        ];
    }
    $grouped_links[$source_key]['targets'][] = [
        'id' => $link['id'],
        'target_product' => $link['target_product'],
        'target_option' => $link['target_option'],
        'target_value' => $link['target_value'],
        'target_price' => $link['target_price']
    ];
}

echo json_encode(array_values($grouped_links));
?>