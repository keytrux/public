<?php
class Logger
{
    private static $instance = null;
    private $logDir;

    private function __construct()
    {
        $this->logDir = __DIR__ . "/log/";
        if (!file_exists($this->logDir)) {
            mkdir($this->logDir, 0777, true);
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Logger();
        }
        return self::$instance;
    }

    private function getHash()
    {
        return uniqid();
    }

    public function log(string $message, string $logName, bool $isNeedDebug = true): void
    {
        if ($isNeedDebug) {
            $this->debug($message);
        }
        $hash = $this->getHash();
        $date = date('Y.m.d H:i:s');
        $memoryUsage = $this->_getMemoryUsageMessage();
        $fileName = $this->logDir . str_replace('.', '/', $logName) . '.log';
        $formattedMessage = "[{$date}]{$memoryUsage} {$hash} {$message}";

        $this->logToFileRaw($formattedMessage, $fileName);
    }

    private function logToFileRaw($message, $logFile)
    {
        static $logs = [];
        static $logCount = 0;
        $threshold = 100;

        $logs[$logFile][] = $message;

        if (++$logCount >= $threshold)
        {
            $this->flushLogs($logs);
            $logs = [];
            $logCount = 0;
        }
    }

    private function flushLogs(array $logs)
    {
        foreach ($logs as $logFile => $logMessages)
        {
            $this->writeLogToFile(implode("\n", $logMessages), $logFile);
        }
    }

    private function writeLogToFile($message, $logFile)
    {
        $dirName = dirname($logFile);
        if (!file_exists($dirName))
        {
            $oldMask = umask(0);
            mkdir($dirName, 0777, true);
            umask($oldMask);
        }

        $fh = fopen($logFile, 'a');
        fwrite($fh, "{$message}\n");
        fclose($fh);
    }

    private function debug($message)
    {

    }

    private function _getMemoryUsageMessage()
    {
        $memoryUsage = memory_get_usage();
        return "Memory Usage: " . $memoryUsage . " bytes";
    }

    public function flushRemainingLogs()
    {
        if (!empty($logs))
        {
            $this->flushLogs($logs);
            $logs = [];
        }
    }
}

function logMessage($message, $logName, $isNeedDebug = false)
{
    $logger = Logger::getInstance();
    $logger->log($message, $logName, $isNeedDebug);
}

register_shutdown_function(function()
{
    $logger = Logger::getInstance();
    $logger->flushRemainingLogs();
});
?>