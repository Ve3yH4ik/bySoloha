<?php
// Получаем данные о группе
global $db;
$group_id = $_GET['group_id'];

// Запрос к товарам OpenCart
$products = $db->query("SELECT product_id, name FROM oc_product")->fetchAll(PDO::FETCH_ASSOC);

// Сохранение подгруппы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $option_id = $_POST['option_id'];
    $option_value_id = $_POST['option_value_id'];
    $link = $_POST['link'];

    $stmt = $db->prepare("INSERT INTO custom_sets (group_id, option_id, option_value_id, link) VALUES (?, ?, ?, ?)");
    $stmt->execute([$group_id, $option_id, $option_value_id, $link]);
}
?>

<form method="post">
    <select name="product_id" id="product_id">
        <?php foreach ($products as $product): ?>
            <option value="<?= $product['product_id'] ?>"><?= htmlspecialchars($product['name']) ?></option>
        <?php endforeach; ?>
    </select>

    <!-- JavaScript для загрузки опций -->
    <select name="option_id" id="options_select">
        <!-- Опции загружаются через JS -->
    </select>

    <select name="option_value_id" id="option_values_select">
        <!-- Значения загружаются через JS -->
    </select>

    <input type="text" name="link" placeholder="Ссылка">
    <button type="submit">Добавить подгруппу</button>
</form>