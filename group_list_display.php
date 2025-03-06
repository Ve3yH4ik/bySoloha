<?php
require_once 'db.php';
global $db;

// Получение групп из базы данных
$query = $db->query("SELECT * FROM custom_groups ORDER BY created_at DESC");
$groups = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Группы</title>
</head>
<body>
<h1>Список групп</h1>
<ul>
    <?php foreach ($groups as $group): ?>
        <li><?= htmlspecialchars($group['name'], ENT_QUOTES, 'UTF-8') ?></li>
    <?php endforeach; ?>
</ul>
<a href="index.php">Назад</a> <!-- Ссылка на страницу добавления групп -->
</body>
</html>