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

try{
    $amo = new AmoCRM($authData['domain'], new Token($authData));
    $contact = new Contact();

    $contact
        ->setName('Название')
        ->addPhone('телефон')
        ->addEmail("Емайл")
    ;

    $lead = new Lead();
    $lead
        ->addTag('Теги')
        ->setName("Название сделки")
        ->setCustomFieldValue('roistat', "Номер визита")
    ;

    $unsorted = new Unsorted();
    $result = $unsorted
        ->setFormId("ID форма")
        ->setFormName('Название формы')
        ->setPipelineId("ID воронки")
        ->setLead($lead)
        ->setContacts([ $contact ])
        ->save()
        ->addNote("Текст сообщения");
    ;
    
} catch (Exception $e) {
    debug($e);
    echo "Ошибка: {$e->getMessage()}";
}