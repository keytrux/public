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

$json = jsonApi()->api('GET', '/entity/product', '?limit=1');
$array_product = json_encode($json->rows);
$array_product = str_replace("\"", "\\\"", $array_product);
$product_size = $json->meta->size;

$json = jsonApi()->api('GET', '/entity/productfolder', '?filter=pathName=&order=name');
$array_group = json_encode($json->rows);
$array_group = str_replace("\"", "\\\"", $array_group);
	
$json = jsonApi()->api('GET', '/entity/productfolder', '?filter=pathName!=&order=name');
$array_podgroup = json_encode($json->rows);
$array_podgroup = str_replace("\"", "\\\"", $array_podgroup);
	

$timestamp = strtotime(date('H:i:s')) + 60*60;
$time = date('H:i:s', $timestamp);
file_put_contents('inside.txt', date('d.m.Y') . ' ' . $time . ' Вход от: ' . $fio . ' '. "\n\n", FILE_APPEND);

require 'iframe.html.php';