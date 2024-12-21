<?php
require_once 'Logg.php';
define('BASE_URL', 'https://XXXX.bitrix24.ru/rest/XXXX/');
define('DEAL_GET_URL', BASE_URL . 'XXXX/crm.deal.get.json?ID=');
define('DEAL_LIST_URL', BASE_URL . 'XXXX/crm.deal.list.json');
define('LEAD_LIST_URL', BASE_URL . 'XXXX/crm.lead.list.json');
define('DEAL_UPDATE_URL', BASE_URL . 'XXXX/crm.deal.update?ID=');

$data = file_get_contents('php://input');
parse_str($data, $dataArray);

Logg::log($dataArray, "Данные Callibri");


if (isset($dataArray['name'])){
    $name = $dataArray['name'];
}

if (isset($dataArray['phone'])){
    $phone = $dataArray['phone'];
}

if (isset($dataArray['email'])){
    $email = $dataArray['email'];
}

if (isset($dataArray['utm_term'])){
    $utm_term = $dataArray['utm_term'];
}

if (isset($dataArray['utm_source'])){
    $utm_source = $dataArray['utm_source'];
}

if (isset($dataArray['utm_medium'])){
    $utm_medium = $dataArray['utm_medium'];
}

if (isset($dataArray['utm_content'])){
    $utm_content = $dataArray['utm_content'];
}

if (isset($dataArray['utm_campaign'])){
    $utm_campaign = $dataArray['utm_campaign'];
}

if (isset($dataArray['metrika_client_id'])){
    $clientID = $dataArray['metrika_client_id'];
}

$fields = [
    'UF_CRM_1729262534535' => $clientID,
    'UTM_SOURCE' => $utm_source,
    'UTM_MEDIUM' => $utm_medium,
    'UTM_CAMPAIGN' => $utm_campaign,
    'UTM_CONTENT' => $utm_content,
    'UTM_TERM' => $utm_term,
];

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

function findDeal($filters) {
    $url = DEAL_LIST_URL;
    $params = [
        'order' => ['MOVED_TIME' => 'DESC'],
        'filter' => $filters,
        'select' => ['ID', 'TITLE', 'CONTACT_ID'],
        'limit' => 1
    ];

    $urlWithParams = $url . '?' . http_build_query($params);

    return curl($urlWithParams);
}

function findLead($filters) {
    $url = LEAD_LIST_URL;
    $params = [
        'order' => ['MOVED_TIME' => 'DESC'],
        'filter' => $filters,
        'select' => ['ID', 'TITLE', 'CONTACT_ID'],
        'limit' => 1
    ];

    $urlWithParams = $url . '?' . http_build_query($params);

    return curl($urlWithParams);
}

function findDealByID($idLead) {
    $url = DEAL_GET_URL . $idLead;
    $leadData = curl($url);
    if (!isset($leadData['result'])) {
        Logg::log('Ошибка при получении данных сделки', "Ошибка");
        die('Ошибка при получении данных сделки');
    }

    return $leadData['result'];
}

function formatPhoneNumber($phone) {
    $phone = preg_replace('/\D/', '', $phone);
    if (strlen($phone) === 11 && strpos($phone, '7') === 0) {
        return '7 ' . substr($phone, 1, 3) . ' ' . substr($phone, 4, 3) . '-' . substr($phone, 7, 2) . '-' . substr($phone, 9, 2);
    }
    return $phone;
}

function updateDeal($idDeal, $fields)
{
    $url = DEAL_UPDATE_URL . $idDeal;
    $data = [
        'fields' => $fields,
        'params' => ["REGISTER_SONET_EVENT" => "Y"],
    ];
    curl($url, $data, 'POST');
    Logg::log('Сделка успешно обновлена', "Успешно");
}

try
{
    $filters = [];
    if (!empty($phone)) {
        $formattedPhone = formatPhoneNumber($phone);

        $filters['TITLE'] = $formattedPhone . ' - Входящий звонок';

        $leads = findLead($filters);

        if (!empty($leads['result'])) {
            foreach ($leads['result'] as $lead) {
                $filter['LEAD_ID'] = $lead['ID'];
                $idLead = findDeal($filter);
                $deal = findDealByID($idLead['result'][0]['ID']);
                $updateResult = updateDeal($idLead['result'][0]['ID'], $fields);
                break;
            }
        } else {
            Logg::log('Лиды не найдены', "Ошибка");
        }
    }

    if (!empty($name)) {
        $filters['UF_CRM_1714386505'] = $name;
        $deals = findDeal($filters);

        if (!empty($deals['result'])) {
            foreach ($deals['result'] as $deal)
            {
                $updateResult = updateDeal($deal['ID'], $fields);
                break;
            }
        } else {
            Logg::log('Сделки не найдены', "Ошибка");
        }
    }
}
catch (Exception $e) {
    Logg::log($e->getMessage(), "Исключение");
    echo "Произошла ошибка: " . $e->getMessage();
}
?>