<?php
$logFile = 'log.txt';

if (isset($_POST['log'])) {
    file_put_contents($logFile, $_POST['log'] . "\n", FILE_APPEND);
    echo json_encode(['status' => 'success']);
} elseif (isset($_GET['load'])) {
    if (file_exists($logFile)) {
        echo json_encode(['status' => 'success', 'log' => file_get_contents($logFile)]);
    } else {
        echo json_encode(['status' => 'success', 'log' => 'Лог обновления цен:\n']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Некорректный запрос']);
}
?>