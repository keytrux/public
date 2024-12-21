<?php
use Roistat\AmoCRM_Wrap\Token;
use Roistat\AmoCRM_Wrap\AmoCRM;
use Roistat\AmoCRM_Wrap\Lead;

require_once __DIR__ .'/autoload.php';

$authData = array(
    'client_id'     => '',
    'client_secret' => '',
    'redirect_uri'  => '',
    'domain'        => ''
);

try {
    $amo = new AmoCRM($authData['domain'], new Token($authData));
    $lead = new Lead();
    $lead
        ->setCustomFieldValue('roistat', 12345)
        ->setCustomFieldValue('fbclid', 'fbclid')
        ->setCustomFieldValue('from', 'from')
        ->setCustomFieldValue('openstat_source', 'openstat_source')
        ->setName('Test roistat Lead')
    ;
    $res = $lead->save();
    debug($res);

    $search = $amo->searchContacts('79999999999');
    debug($search);

} catch (Exception $e) {
    echo "Ошибка в получении токена: {$e->getMessage()}";
}