<?php
require_once 'Logg.php';

define('BASE_URL', 'https://XXXX.bitrix24.ru/rest/XXXX/');
define('DEAL_GET_URL', BASE_URL . 'XXXX/crm.deal.get.json?ID=');
define('CONTACT_GET_URL', BASE_URL . 'XXXX/crm.contact.get?ID=');
define('DEAL_UPDATE_URL', BASE_URL . 'XXXX/crm.deal.update?ID=');

$data = file_get_contents('php://input');
parse_str($data, $dataArray);

$idLead = $dataArray['data']['FIELDS']['ID'] ?? null;

if (!$idLead) {
    Logg::log('Не указан ID лида', "Ошибка");
    die('Ошибка: Не указан ID лида');
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


function fetchLeadData($idLead) {
    $url = DEAL_GET_URL . $idLead;
    $leadData = curl($url);
    if (!isset($leadData['result'])) {
        Logg::log('Ошибка при получении данных лида', "Ошибка");
        die('Ошибка при получении данных лида');
    }
    if (strpos($leadData['result']['TITLE'], 'Веб-сайт') === false) {
        Logg::log('Заголовок не содержит "Веб-сайт"', "Ошибка");
        die('Заголовок не содержит "Веб-сайт"');
    }
    return $leadData['result'];
}

function fetchContactData($idContact)
{
    $url = CONTACT_GET_URL . $idContact;
    $contactData = curl($url);
    if (!isset($contactData['result'])) {
        Logg::log('Ошибка при получении данных контакта', "Ошибка");
        die('Ошибка при получении данных лида');
    }
    return $contactData['result'];
}

function fetchProxyData($name, $phones, $emails, $type)
{
    $urlBase = 'https://XXXX';
    $key = '?key=XXXX&project=XXXX';
    $currentDate = date('Y-m-d');

    $responseData = curl($urlBase . $key . '&period=' . $currentDate . '-' . $currentDate);

    $visit = '';
    if (isset($responseData['ProxyLeads'])) {
        $phoneNumbers = array_column($phones ?? [], 'VALUE');
        $cleanedPhoneNumbers = array_map('cleanPhoneNumber', $phoneNumbers);

        $emailAddress = array_map('strtolower', array_column($emails ?? [], 'VALUE'));
        switch ($type) {
            case 'web':
                foreach ($responseData['ProxyLeads'] as $request)
                {
                    if (strpos($request['title'], 'cupol365.ru')
                        && in_array($request['phone'], $cleanedPhoneNumbers)
                        && (empty($request['email']) || in_array($request['email'], $emailAddress))
                    )
                    {
                        $visit = $request['roistat'];
                    }
                }
                return $visit;
            break;

            case 'email':
                foreach ($responseData['ProxyLeads'] as $request)
                {
                    if (strtolower($request['name']) == strtolower($name)
                        && (empty($request['email']) || in_array($request['email'], $emailAddress))
                    )
                    {
                        $visit = $request['roistat'];
                    }
                }
                return $visit;
            break;

            case 'call':
                foreach ($responseData['ProxyLeads'] as $request)
                {
                    if (in_array($request['phone'], $cleanedPhoneNumbers))
                    {
                        $visit = $request['roistat'];
                    }
                }
                return $visit;
            break;
        }
    } else {
        echo "Массив заявок не найден.\n";
    }
    return $visit;
}

function cleanPhoneNumber($phone) {
    return preg_replace('/[\+\-\(\)\s]/', '', $phone);
}

function fetchUTMData($visit)
{
    $key = 'XXXX';
    $project = 'XXXX';

    $filter = http_build_query(
        ["filters" => [
            [
                "field" => "id",
                "operator" => "=",
                "value" => $visit
            ]
        ]]);

    $data = file_get_contents('https:XXXX?project='.$project.'&key='.$key . '&' . $filter);

    $result = json_decode($data, true);

    $clientID = $result['data'][0]['metrika_client_id'];
    $utm_source = $result['data'][0]['source']['utm_source'];
    $utm_medium = $result['data'][0]['source']['utm_medium'];
    $utm_campaign = $result['data'][0]['source']['utm_campaign'];
    $utm_content = $result['data'][0]['source']['utm_content'];
    $utm_term = $result['data'][0]['source']['utm_term'];

    $fields = [
        'UF_CRM_1729262534535' => $clientID,
        'UTM_SOURCE' => $utm_source,
        'UTM_MEDIUM' => $utm_medium,
        'UTM_CAMPAIGN' => $utm_campaign,
        'UTM_CONTENT' => $utm_content,
        'UTM_TERM' => $utm_term,
    ];
    return $fields;
}

function updateDeal($idDeal, $fields)
{
    $url = DEAL_UPDATE_URL . $idDeal;
    $data = [
        'fields' => $fields,
        'params' => ["REGISTER_SONET_EVENT" => "Y"],
    ];
    curl($url, $data, 'POST');
    echo "Сделка успешно обновлена.";
}

try {
    $leadData = fetchLeadData($idLead);

    // сделки с сайта
    if (strpos($leadData['TITLE'], 'Веб-сайт') !== false || strpos($leadData['TITLE'], 'Консультация') !== false || strpos($leadData['TITLE'], 'Свяжитесь с нами') !== false || strpos($leadData['TITLE'], 'сайт') !== false)
    {
        Logg::log($leadData, "Веб-сайт");

        $contactData = fetchContactData($leadData['CONTACT_ID']);

        $visit = fetchProxyData($contactData['NAME'], $contactData['PHONE'], $contactData['EMAIL'] ?? [], 'web');

        $utm = fetchUTMData($visit);

        $updateResult = updateDeal($idLead, $utm);
    }
    // сделки через ет callibri
    elseif (strpos($leadData['TITLE'], 'Электронная почта') !== false)
    {
        Logg::log($leadData, "Электронная почта");

        $contactData = fetchContactData($leadData['CONTACT_ID']);

        $visit = fetchProxyData($contactData['NAME'], [], $contactData['EMAIL'], 'email');

        $utm = fetchUTMData($visit);

        $updateResult = updateDeal($idLead, $utm);
    }
    // сделки через кт callibri
    elseif (strpos($leadData['SOURCE_DESCRIPTION'], 'Звонок') !== false)
    {
        Logg::log($leadData, "Звонок");

        $contactData = fetchContactData($leadData['CONTACT_ID']);

        $visit = fetchProxyData($contactData['NAME'], $contactData['PHONE'], [], 'call');

        $utm = fetchUTMData($visit);

        $updateResult = updateDeal($idLead, $utm);
    }
    // сделки с wazzup
    elseif (strpos($leadData['TITLE'], 'WhatsApp') !== false || strpos($leadData['TITLE'], 'Whatsapp') !== false)
    {
        Logg::log($leadData, "Whatsapp");

        $utm = fetchUTMData($leadData['UF_CRM_1727185102']);

        $updateResult = updateDeal($idLead, $utm);
    }
    else {
        Logg::log($leadData, "Ошибка не веб-сайт и не whatsapp");
    }

} catch (Exception $e) {
    Logg::log($e->getMessage(), "Исключение");
    echo "Произошла ошибка: " . $e->getMessage();
}

?>