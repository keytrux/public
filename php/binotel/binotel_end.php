<?php
ini_set('display_errors', 0);
header('Content-Type: application/json; charset=utf-8');
echo json_encode(['status' => 'success']);
require_once __DIR__ . '/logger.php';
if (!isset($_REQUEST['callDetails']) || !is_array($_REQUEST['callDetails']) || !isset($_REQUEST['callDetails']['callType']) || !isset($_REQUEST['callDetails']['callID']) || !isset($_REQUEST['callDetails']['pbxNumberData']['number'])) {
    exit();
}
require_once __DIR__ . '/BinotelApi.php';
require_once __DIR__ . '/configs.php';

$marker = null;
$phone = isset($_REQUEST['callDetails']['externalNumber']) ? $_REQUEST['callDetails']['externalNumber'] : '';

$getCallData = [];
$title = 'Звонок без источника';

if (isset($_REQUEST['callDetails']['getCallData']) && is_array($_REQUEST['callDetails']['getCallData']))
{
    $getCallData = $_REQUEST['callDetails']['getCallData'];
    $title = 'Обратный звонок от ' . $phone;
}
else if (isset($_REQUEST['callDetails']['callTrackingData']) && is_array($_REQUEST['callDetails']['callTrackingData']))
{
    $getCallData = $_REQUEST['callDetails']['callTrackingData'];
    $title = 'Звонок от ' . $phone;
}

$marker = !empty($getCallData['utm_source']) ? $getCallData['utm_source'] : null;


$method = 'project/phone-call';
$body = [
    'caller' => $phone,
    'callee' => $_REQUEST['callDetails']['pbxNumberData']['number'],
    'date' => date('c', $_REQUEST['callDetails']['startTime']),
    'save_to_crm' => '0',
    'status' => 'ANSWER',
];

if (!empty($marker))
{
    $body['marker'] = $marker;
}


$link_voice = null;
$api = new Binotel(BINOTEL_KEY, BINOTEL_SECRET);
$api->disableSSLChecks();
$binotelResp = $api->sendRequest('stats/call-record', [
    'callID' => $_REQUEST['callDetails']['callID']
]);

$lastStatus = null;
if (isset($binotelResp['status']) && $binotelResp['status'] === 'success')
{
    $link_voice = $binotelResp['url'];
    $lastStatus = 'ANSWER';
}
else if ($binotelResp === null)
{
    $lastStatus = isset($_REQUEST['callDetails']['disposition']) ? $_REQUEST['callDetails']['disposition'] : 'NOANSWER';
}
else
{
    $lastStatus = 'NOANSWER';
}

$method = 'project/calltracking/call/update';
$duration = ((int)($_REQUEST['callDetails']['waitsec'] ?? 0)) + ((int)($_REQUEST['callDetails']['billsec'] ?? 0));
$body = [
    'id' => $res['phoneCall']['id'],
    'duration' => $duration,
    'answer_duration' => (int)($_REQUEST['callDetails']['waitsec'] ?? 0),
    'status' => $lastStatus,
];

if (!empty($link_voice))
{
    $body['link'] = $link_voice;
}
