<?php

$input = file_get_contents('php://input');
writeToLog('$input ' . $input);

if (empty($input)) {
    writeToLog('Получен пустой вход');
}

$data = json_decode($input, true);
writeToLog('$data ' . print_r($data, true));

$type = $data['form_response']['hidden']['type'];

if ($type == 'каталог')
{
    $visit = $data['form_response']['hidden']['visit'];
    $name = $data['form_response']['answers'][0]['text'];
    $email = $data['form_response']['answers'][1]['email'];
    $phone = $data['form_response']['answers'][2]['phone_number'];
    $utm_campaign = $data['form_response']['hidden']['utm_campaign'];
    $utm_content = $data['form_response']['hidden']['utm_content'];
    $utm_medium = $data['form_response']['hidden']['utm_medium'];
    $utm_source = $data['form_response']['hidden']['utm_source'];
    $utm_term = $data['form_response']['hidden']['utm_term'];

    $data = [
        'title'   => 'Заявка с формы TypeForm "Каталог"', // Название сделки
        'name'    => $name, // Имя клиента
        'email'   => $email, // Email клиента
        'phone'   => $phone, // Номер телефона клиента
        'fields' => [
            'utm_campaign' => $utm_campaign,
            'utm_content' => $utm_content,
            'utm_medium' => $utm_medium,
            'utm_source' => $utm_source,
            'utm_term' => $utm_term
        ]
    ];   
}
else 
{
    $visit = $data['form_response']['hidden']['visit'];
    $name = $data['form_response']['answers'][7]['text'];
    $email = $data['form_response']['answers'][8]['email'];
    $phone = $data['form_response']['answers'][9]['phone_number'];

    $utm_campaign = $data['form_response']['hidden']['utm_campaign'];
    $utm_content = $data['form_response']['hidden']['utm_content'];
    $utm_medium = $data['form_response']['hidden']['utm_medium'];
    $utm_source = $data['form_response']['hidden']['utm_source'];
    $utm_term = $data['form_response']['hidden']['utm_term'];
    
    $type = $data['form_response']['answers'][0]['choice']['label'];
    $where = $data['form_response']['answers'][1]['choice']['label'];
    $type_second = $data['form_response']['answers'][2]['choice']['label'];
    $finishing = $data['form_response']['answers'][3]['choice']['label'];
    
    $attractions = $data['form_response']['answers'][4]['choices']['labels'];
    $attractionArray = [];
    foreach ($attractions as $attraction) 
    {
        $attractionArray[] = $attraction;
    }
    $attraction = implode(', ', $attractionArray);
    
    $heating = $data['form_response']['answers'][5]['choice']['label'];
    $when = $data['form_response']['answers'][6]['choice']['label'];
    
    $data = [
        'title'   => 'Заявка с формы TypeForm', // Название сделки
        'name'    => $name, // Имя клиента
        'email'   => $email, // Email клиента
        'phone'   => $phone, // Номер телефона клиента
        'fields' => [
            'utm_campaign' => $utm_campaign,
            'utm_content' => $utm_content,
            'utm_medium' => $utm_medium,
            'utm_source' => $utm_source,
            'utm_term' => $utm_term,
            'type' => 'Какой тип бассейна Вы хотите? - ' . $type,
            'where' => 'Где будет расположен бассейн? - ' . $where,
            'type_second' => 'Какой Вы хотите тип бассейна? - ' . $type_second,
            'finishing' => 'Какая будет отделка бассейна? - ' . $finishing,
            'attraction' => 'Нужны ли аттракционы? - ' . $attraction,
            'heating' => 'Будет ли подогрев бассейна? - ' . $heating,
            'when' => 'Когда планируется строительство? - ' . $when
        ]
    ];  
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