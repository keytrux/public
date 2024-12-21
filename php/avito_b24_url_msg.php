<?php
require_once 'Amo.php';

$amo = new Amo('XXX', 'XXXX');


$input = file_get_contents('php://input');
writeToLog('$input ' . $input);

if (empty($input)) {
    writeToLog('Получен пустой вход');
}

$data = file_get_contents('php://input');
parse_str($data, $dataArray);
writeToLog('$dataArray ' . print_r($dataArray, true));

$messageText = $dataArray['message']['add'][0]['text'] ?? null;
$leadId = $dataArray['message']['add'][0]['element_id'] ?? null;

if ($messageText !== null && strpos($messageText, 'Ссылка') !== false) {
    // Регулярное выражение для извлечения URL
    $pattern = '/\[(.*?)\]\((https?:\/\/[^\s)]+)\)/';
    
    if (preg_match($pattern, $messageText, $matches)) {
        $linkText = $matches[1]; // Текст ссылки (название)
        $url = $matches[2]; // URL 930089

        // $result = $amo->updateLeadUrl($leadId, $url); // upd lead amoCRM

        // Вывод результата
        echo "Текст ссылки: " . $linkText . PHP_EOL;
        echo "Извлеченная ссылка: " . $url . PHP_EOL;
        writeToLog("Текст ссылки: " . $linkText . PHP_EOL);
        writeToLog("Извлеченная ссылка: " . $url . PHP_EOL);
    } 
    else {
        echo "Ссылка не найдена в тексте.";
        writeToLog("Ссылка не найдена в тексте.");
    }
} 
else {
    echo "Сообщение пустое или не содержит слова 'Ссылка'.";
    writeToLog("Сообщение пустое или не содержит слова 'Ссылка'.");
}


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
