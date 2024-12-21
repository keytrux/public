<?php

$input = file_get_contents('php://input');
writeToLog('$input ' . $input);

if (empty($input)) {
    writeToLog('Получен пустой вход');
}

$data = json_decode($input, true);
writeToLog('$data ' . print_r($data, true));

function writeToLog($message) {
    $logDir = __DIR__ . '/log';
    $logFile = $logDir . '/application.log';

    if (!is_dir($logDir)) {
        mkdir($logDir, 0777, true);
    }

    $currentDateTime = date('d.m.Y H:i:s');
    $logMessage = $currentDateTime . " - " . $message . PHP_EOL;

    file_put_contents($logFile, $logMessage, FILE_APPEND);
}
?>