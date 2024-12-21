<?php
require_once 'Logg.php';
require_once 'b24.php';

Logg::log($_REQUEST, "REQUEST");
try
{
    $idDeal = $_REQUEST['data']['FIELDS']['ID'];

    $bitrix = new Bitrix('XXX.bitrix24.ru', 'XXXX', 'X');

    $requestData = $_REQUEST['data'];
    $leadId = $requestData['FIELDS']['ID'];
    if (isset($leadId)) {
        $lead = $bitrix->getLead($leadId);
        if (isset($lead['result'])) {
            $leadResult = $lead['result'];
            Logg::log($leadResult, "leadResult");
        }
        if (isset($leadResult['IM'])) {
            $rawToken = explode('|', $leadResult["IM"][0]["VALUE"]);
            unset($rawToken[0]);
            $token = implode("|", $rawToken);

            $chat = $bitrix->getLineChat($token);
            Logg::log($chat, "chat");

            if (isset($chat['result']['ID']))
            {
                $id = $chat['result']['ID'];
                $messages = $bitrix->getMessagesFromChat('chat' . $id);
                if (isset($messages['result']))
                {
                    $messagesResult = $messages['result'];
                    Logg::log($messagesResult, "messagesResult");
                    foreach ($messagesResult['messages'] as $message)
                    {
                        preg_match('/Номер моей заявки: (\d*)/', $message['text'], $matchesWA);
                        if (isset($matchesWA[1])) {
                            $visit = $matchesWA[1];
                            break;
                        }
                    }

                    if (isset($visit))
                    {
                        $field = [
                            'UF_CRM_1732174262' => $visit
                        ];
                        Logg::log($field, "field");
                        $val = $bitrix->updateLead($leadId, $field);
                    }
                }
            }
        }
    }
}
catch (Exception $e) {
    Logg::log($e->getMessage(), "Исключение");
    echo "Произошла ошибка: " . $e->getMessage();
}

?>