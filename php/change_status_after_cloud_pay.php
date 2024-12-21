<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$input = file_get_contents('php://input');
writeToLog('$input ' . $input);
if (empty($input)) {
    writeToLog('Получен пустой вход');
}

$data = json_decode($input, true);
writeToLog('$data ' . print_r($data, true));

if ($data === null) {
    writeToLog('Некорректный JSON формат1');
    die(json_encode(['error' => 'Некорректный JSON формат']));
}

$urlBase = 'https://XXX';
$key = 'key=XXX&project=XXX';

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

function curl($url, $data)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $response = curl_exec($ch); // Выполняем запрос и получаем ответ
    curl_close($ch); // Закрываем ресурс cURL
    return json_decode($response, true);
}

$phone = $data['phone'] ?? null;
$email = $data['email'] ?? null;
$price = $data['price'] ?? null;

$dataGet = [
    "filters" => [
        [
            "field" => "price",
            "operator" => "=",
            "value" => $price
        ],
        [
            "field" => "status",
            "operator" => "=",
            "value" => "0"
        ],
        [
            "field" => "creation_date",
            "operator" => ">",
            "value" => date('Y-m-d H:i:s', strtotime('today 00:00:00'))
        ],
    ],
    "limit" => 1,
    "offset" => 0,
    "period" => [
        "from" => date('Y-m-d H:i:s', strtotime('today 00:00:00')),
        "to" => date('Y-m-d H:i:s', strtotime('today 23:59:59'))
    ],
    "sort_field" => "creation_date",
    "sort_order" => "desc"
];

writeToLog('$dataGet before ' . json_encode($dataGet));

if ($phone !== null) {
    $dataGet['filters'][] = [
        "field" => "phone",
        "operator" => "=",
        "value" => $phone
    ];
}

if ($email !== null) {
    $dataGet['filters'][] = [
        "field" => "email",
        "operator" => "like",
        "value" => $email
    ];
}

writeToLog('$dataGet after ' . json_encode($dataGet));

$responseData = curl($urlBase . 'list?' . $key, $dataGet);

writeToLog('$responseData ' . print_r($responseData));

if ($responseData !== null && isset($responseData['leads'][0]['id']))
{
    $id = $responseData['leads'][0]['id'];

    $dataUpdate = [
        "id" => $id,
        "status" => "1",
        "paid_date" => (new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d\TH:i:sP'),
    ];

    $updateResponse = curl($urlBase . 'update?' . $key, $dataUpdate);

    if ($updateResponse !== null) {
        writeToLog('<pre>' . json_encode($updateResponse, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</pre>');
    }
    else {
        writeToLog('Ошибка: Некорректный JSON формат2.');
    }
}
else {
    writeToLog('Ошибка: Некорректные данные или ID не найден.');
}
?>