
<?php
ini_set('memory_limit', '1536M');
ini_set('max_execution_time', '43200'); 
$contextKey = $_GET['contextKey'];
require_once 'lib.php';
$employee = vendorApi()->context($contextKey);
$uid = $employee->uid;
$fio = $employee->shortFio;
$accountId = $employee->accountId;

// if ($employee->id != "c1f62a09-6bdf-11ee-0a80-0c31002b5200") exit("Обслуживание! Зайдите позже.");

$app = AppInstance::loadApp($accountId);

$timestamp = strtotime(date('H:i:s')) + 60*60;
$time = date('H:i:s', $timestamp);
	
file_put_contents('inside.txt', date('d.m.Y') . ' ' . $time . ' Вход от: ' . $fio . ' '. "\n\n", FILE_APPEND);


require 'iframe.html.php';