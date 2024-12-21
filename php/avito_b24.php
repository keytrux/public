<?php

define('BASE_URL', 'https://XXXX.bitrix24.ru/rest/3423/');
define('LEAD_GET_URL', BASE_URL . 'XXXX/crm.lead.get.json?ID=');

$input = file_get_contents('php://input');
writeToLog('$input ' . $input);
if (empty($input)) {
    writeToLog('Получен пустой вход');
}

$data = file_get_contents('php://input');
parse_str($data, $dataArray);
writeToLog('$dataArray ' . print_r($dataArray, true));

if ($data === null) {
    writeToLog('Некорректный JSON формат1');
    die(json_encode(['error' => 'Некорректный JSON формат']));
}

$idLead = $dataArray['data']['FIELDS']['ID'] ?? null;

try {
    $leadData = fetchLeadData($idLead);
    $title = $leadData['TITLE'];
    $cutTo = " - Авито";
    $position = strpos($title, $cutTo);
    if ($position !== false) {
        $name = substr($title, 0, $position);
    } else {
        writeToLog('Слово не найдено');
    }
    echo "<pre>"; print_r($leadData); echo "</pre>";
}
catch (Exception $e) {
    Logg::log($e->getMessage(), "Исключение");
    echo "Произошла ошибка: " . $e->getMessage();
}

function fetchLeadData($idLead) {
    $url = LEAD_GET_URL . $idLead;
    $leadData = curl($url);
    if (!isset($leadData['result'])) {
        Logg::log('Ошибка при получении данных лида', "Ошибка");
        echo "<pre>"; print_r($leadData); echo "</pre>";
        die('Ошибка при получении данных лида');
    }
    return $leadData['result'];
}


function curl($url, $data = null, $method = 'GET') {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    if ($method === 'POST' && $data !== null) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
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

?>