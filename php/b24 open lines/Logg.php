<?php

class Logg
{

    public static function log($data, string $description = "")
    {
        $logStatus = true;
        if (!$logStatus) {
            return;
        }

        $logDir = __DIR__ . "/log";
        $path = $logDir . "/integration.log";

        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }

        if (!file_exists($path) || filesize($path) > 100 * 1024 * 1024) {
            file_put_contents($path, '');
        }

        $logMessage = '############' . PHP_EOL;
        if (!empty($description)) {
            $logMessage .= $description . PHP_EOL;
        }
        $logMessage .= date('Y-m-d H:i:s') . PHP_EOL;

        if (is_array($data) || is_object($data)) {
            $logMessage .= print_r($data, true);
        } else {
            $logMessage .= $data;
        }

        $logMessage .= PHP_EOL . '############' . PHP_EOL . PHP_EOL;

        file_put_contents($path, $logMessage, FILE_APPEND);
    }
}