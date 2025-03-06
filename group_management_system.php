<?php
date_default_timezone_set('Europe/Kiev');

require_once 'db.php';
global $pdo;
class Database
{
    private PDO $connection;

    public function __construct(PDO $pdo)
    {
        $this->connection = $pdo;
    }

    public function getGroups(): array
    {
        $sql = "SELECT * FROM custom_groups ORDER BY created_at DESC";
        $statement = $this->connection->query($sql);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addGroup(string $name): void
    {
        $sql = "INSERT INTO custom_groups (name) VALUES (:name)";
        $statement = $this->connection->prepare($sql);
        $statement->execute(['name' => $name]);
    }
}

// Создаем объект базы данных
$database = new Database($pdo);

// Обработка данных из POST-запроса
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $database->addGroup($name);
    header('Location: groups.php');
    exit();
}

// Получение данных для отображения
$groups = $database->getGroups();
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
        <li><?= htmlspecialchars($group['name'], ENT_QUOTES, 'UTF-8') ?></li>
    <?php endforeach; ?>
</ul>