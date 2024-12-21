<?php

use Roistat\AmoCRM_Wrap\AmoCRM;
use Roistat\AmoCRM_Wrap\Lead;
use Roistat\AmoCRM_Wrap\Token;

require_once 'Request.php';
require_once 'Log.php';
require_once 'Response.php';
require_once 'NewAmoWrap/autoload.php';
class Webhook
{
    public function run($incomingRequest) {
        $request = new Request();
        $log = new Log();
        $logDir = __DIR__. '/log/log.log';
        $logData = [];
        $logData['request'] = $incomingRequest;
        if (!$request->validated($incomingRequest)) {
            $log->write($logData, $request->getError(), $logDir);
            return Response::json(Response::FAIL, $request->getError());
        }
        $authData = array(
            'client_id'     => 'XXXX',
            'client_secret' => 'XXXX',
            'redirect_uri'  => 'https://XXXX/index.php',
            'domain'        => 'XXXX'
        );

        if (isset($incomingRequest['type']))
        {
            switch ($incomingRequest['type']) {
                case 'source':
                    sleep(1);
                    $amo = new AmoCRM($authData['domain'], new Token($authData));
                    $lead = new Lead($incomingRequest['leads']['add'][0]['id']);
                    if (strlen($lead->getCustomFieldValueInStr(586165)) == 0 && !strpos($lead->getName(), 'Спросить где нас нашли')) {
                        $lead->setCustomFieldValue(586165, null);
                        $lead->setName($lead->getName() . ' - Спросить где нас нашли');
                        $lead->save();
                    }
                    $logData['lead'] = $lead;
                    $log->write($logData, 'lead is update', $logDir);
                break;

                case 'tg':
                    $logDir = __DIR__. '/log/log_tg.log';

                    $messageText = $incomingRequest['message']['add'][0]['text'] ?? null;
                    $elementId = $incomingRequest['message']['add'][0]['element_id'] ?? null;

                    if ($messageText && preg_match('/start roistat_(\d+)/', trim($messageText), $matches)) {
                        $visitNumber = $matches[1];
                        $log->write($visitNumber, 'visit found', $logDir);
                        try {
                            $amo = new AmoCRM($authData['domain'], new Token($authData));
                            $lead = new Lead($elementId);

                            if (empty($lead->getCustomFieldValueInStr(586165))) {
                                $lead->setCustomFieldValue(586165, $visitNumber);
                                $lead->save();
                                $logData['lead'] = $lead;
                                $log->write($logData, 'lead is update', $logDir);
                            } else {
                                $logData['lead'] = $lead;
                                $log->write($logData, 'Поле уже заполнено', $logDir);
                            }
                        }
                        catch (Exception $e) {
                            $timestamp = date('Y-m-d H:i:s');
                            $trace = $e->getTraceAsString();
                            $logMessage = sprintf(
                                "[%s] Код ошибки: %s. Сообщение: %s. Стек вызовов: %s. Дополнительные данные: %s",
                                $timestamp,
                                $e->getCode(),
                                $e->getMessage(),
                                $trace
                            );

                            $log->write($logMessage, 'Ошибка при работе с AmoCRM: ', $logDir);
                        }
                    }
                break;
            }
        }
        return Response::json(Response::SUCCESS, 'lead is update');
    }
}