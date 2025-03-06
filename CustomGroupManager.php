<?php
require_once 'db.php';
global $db;

// Ваш код получения групп
$query = $db->query("SELECT * FROM custom_groups ORDER BY created_at DESC");
$groups = $query->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];

    $stmt = $db->prepare("INSERT INTO custom_groups (name) VALUES (?)");
    $stmt->execute([$name]);

    header('Location: groups.php');
    exit();
}
?>
<!-- HTML -->
<form method="post">
    <label>
        <input type="text" name="name" placeholder="Название группы" required>
    </label>
    <button type="submit">Добавить группу</button>
</form>

<ul>
    <?php foreach ($groups as $group): ?>
        <li><?= htmlspecialchars($group['name']) ?></li>
    <?php endforeach; ?>
</ul>