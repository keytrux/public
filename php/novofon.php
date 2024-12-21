<?php
//require_once __DIR__ . '/Roistat.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
ini_set('display_errors', 0);
header('Content-Type: application/json; charset=utf-8');

const ROISTAT_PROJECT = '268717';
const ROISTAT_KEY     = '15678458e7ad5e248ded8d6c5a8a0ac7';

function writeToLog($message) {
    $logDir = __DIR__ . '/log'; // Используем __DIR__ для текущей директории
    $logFile = $logDir . '/application_novofon.log';

    if (!is_dir($logDir)) {
        mkdir($logDir, 0777, true); // Создаем папку для логов, если она не существует
    }

    $currentDateTime = date('d.m.Y H:i:s');
    $logMessage = $currentDateTime . " - " . $message . PHP_EOL;

    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

writeToLog('$_REQUEST 1: ' . json_encode($_REQUEST, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
writeToLog('$_GET 1: ' . json_encode($_GET, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

if (!isset($_GET['virtual_phone_number']) || !isset($_GET['contact_phone_number'])) {
    writeToLog('error: not valid request');
    die('error: not valid request');
}

$caller  = isset($_GET['contact_phone_number']) ? ($_GET['contact_phone_number']) : null;
$callee = isset($_GET['virtual_phone_number'])  ? $_GET['virtual_phone_number'] : null;
$date = isset($_GET['notification_time'])  ? $_GET['notification_time'] : null;
$duration = isset($_GET['talk_time_duration']) ? $_GET['talk_time_duration'] : null;
$file_url = isset($_GET['record_file_links']) ? $_GET['record_file_links'] : null;
$total_time_duration = isset($_GET['total_time_duration']) ? $_GET['total_time_duration'] : null;
$talk_time_duration = isset($_GET['talk_time_duration']) ? $_GET['talk_time_duration'] : null;
$marker = isset($_GET['source']) ? $_GET['source'] : null;
$call_status = isset($_GET['call_status']) ? $_GET['call_status'] : null;
$status = "";
if ($call_status == 'received')
{
    $status = "ANSWER";
}
else
{
    $status = "NOANSWER";
}
$arr = [
    'callee'   => $callee,
    'caller'   => $caller,
    'date'     => date('c'),
    'status'   => $status,
    'marker'   => $marker,
    'duration' => $duration,
    'file_url' => $file_url,
    'total_time_duration' => $total_time_duration,
    'talk_time_duration' => $talk_time_duration,
];

writeToLog('$arr: ' . json_encode($arr, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));