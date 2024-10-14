<?php
ini_set('memory_limit', '1536M');
ini_set('max_execution_time', '43200'); 
$contextKey = $_GET['contextKey'];
require_once 'lib.php';
$employee = vendorApi()->context($contextKey);
$uid = $employee->uid;
$fio = $employee->shortFio;
$accountId = $employee->accountId;

//if ($employee->id != "87d7b9f9-b01c-11ec-0a80-0cf5000ce067") exit("Обслуживание! Зайдите позже.");

$app = AppInstance::loadApp($accountId);

if ($employee->permissions->retaildemand->view == "ALL") {
	$stores = jsonApi()->api('GET', '/entity/store', '');
	$viewProductCostAndProfit = 1;
} else {
	$stores = jsonApi()->api('GET', '/entity/store', '?filter=group=' . $employee->group->meta->href . '');
	$viewProductCostAndProfit = 0;
}

require 'iframe.html.php';