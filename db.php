<?php
date_default_timezone_set('Europe/Kiev');
// Настройки базы данных
$host = 'localhost';
$db = 'expcarry_expcarry';
$user = 'root';
$password = '';

// Подключение к базе данных
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}


