<?php
ini_set('display_errors', 0);

use App\Helpers\Response;
use App\Helpers\Logger;
use App\Planfix;

require_once __DIR__ . '/autoload.php';
require_once __DIR__ . '/config.php';

$planfix = new Planfix(PLANFIX_TOKEN);
$request = json_decode(file_get_contents('php://input'), true);

$data = file_get_contents('php://input');
parse_str($data, $dataArray);

Logger::log($request, "New task from Planfix");

if (empty($request)) {
    Response::error('Provided data is empty', 422);
}

$parsedData = json_decode($data, true);

$id = $parsedData["id"] ?? ""; // "id"
$desc = $parsedData["desc"] ?? ""; // "desc"

$visit = '';

if (strpos($desc, 'Ваш номер') !== false) {
    $visit = preg_replace('/[^0-9]/', '', $desc);
}

if (empty($visit)) {
    Response::success('Visit is empty');
}

Logger::log($visit, "Visit extracted:");

$planfix->setVisit($id, $visit);

Response::success('Task has been updated successfully');