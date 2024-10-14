<?php
$contextKey = $_GET['contextKey'];
require_once 'lib.php';
$employee = vendorApi()->context($contextKey);
$uid = $employee->uid;
$fio = $employee->shortFio;
$account_id = $employee->accountId;

$app = AppInstance::loadApp($account_id);

//$stores = jsonApi()->api('GET', '/entity/store', '?filter=https://api.moysklad.ru/api/remap/1.2/entity/store/metadata/attributes/b52d0cb0-081f-11ed-0a80-0c2a000d2b83=true');
//$array_stores = json_encode($stores->rows);
//$array_stores = str_replace("\"", "\\\"", $array_stores);
//$organizations = jsonApi()->api('GET', '/entity/organization');
//$array_organizations = json_encode($organizations->rows);
//$array_organizations = str_replace("\"", "\\\"", $array_organizations);
/* $group_id = str_replace("https://api.moysklad.ru/api/remap/1.2/entity/group/", "", $employee->group->meta->href);
if ($group_id != "b7350911-83f2-11ec-0a80-00c50003aa8d") {
	exit("Приложение не доступно, ведуться работы отделом ИАО.");
} */

// if ($fio != "Носова В. В." && $fio !=  "Зайцев М. Н." && $fio !=  "Носов Л. Ю.") 
// {
// 	$timestamp = strtotime(date('H:i:s')) + 60*60;
// 	$time = date('H:i:s', $timestamp);
// 	file_put_contents('inside.txt', date('d.m.Y') . ' ' . $time . ' Попытка входа от: ' . $fio . ' '. "\n\n", FILE_APPEND);
// 	exit("Приложение не доступно, ведуться работы отделом ИАО. За сегодня ещё нет обновленных ценников");
// }
// else{

if ($employee->permissions->retaildemand->view == "ALL") {
	$stores = jsonApi()->api('GET', '/entity/store', '?filter=https://api.moysklad.ru/api/remap/1.2/entity/store/metadata/attributes/b52d0cb0-081f-11ed-0a80-0c2a000d2b83=true&order=name');
} else {
	$stores = jsonApi()->api('GET', '/entity/store', '?filter=group=' . $employee->group->meta->href . ';https://api.moysklad.ru/api/remap/1.2/entity/store/metadata/attributes/b52d0cb0-081f-11ed-0a80-0c2a000d2b83=true');
}
$array_stores = json_encode($stores->rows);
$array_stores = str_replace("\"", "\\\"", $array_stores);

$json = jsonApi()->api('GET', '/entity/uom/');
$array_uom = json_encode($json->rows);
$array_uom = str_replace("\"", "\\\"", $array_uom);

$json = jsonApi()->api('GET', '/entity/country/');
$array_country = json_encode($json->rows);
$array_country = str_replace("\"", "\\\"", $array_country);
$array_country = str_replace("'", "`", $array_country);

$json = jsonApi()->api('GET', '/entity/productfolder', '?filter=pathName=&order=name');
$array_group = json_encode($json->rows);
$array_group = str_replace("\"", "\\\"", $array_group);

$json = jsonApi()->api('GET', '/entity/productfolder', '?filter=pathName!=&order=name');
$array_podgroup = json_encode($json->rows);
$array_podgroup = str_replace("\"", "\\\"", $array_podgroup);

// $json = jsonApi()->api('GET', '/entity/enter?filter=group=' . $employee->group->meta->href . ';moment%3E' . date('Y-m-d', strtotime('-1 month')) . "&order=moment,desc");
// $array_oprih = json_encode($json->rows);
// $array_oprih = str_replace("\"", "\\\"", $array_oprih);
//file_put_contents("log.txt", date('Y-m-d H:i:s') . " " . $array_oprih . PHP_EOL, FILE_APPEND);
//echo "<pre>"; print_r($json); echo "</pre>";

$json = jsonApi()->api('GET', '/entity/move?filter=group=' . $employee->group->meta->href . ';moment%3E' . date('Y-m-d', strtotime('-1 month')) . "&order=moment,desc");
//$array_move = json_encode($json->rows);
$array_move = array();
foreach ($json->rows as $row) {
	$array_move[] = array($row->id,$row->name,$row->moment,$row->targetStore->meta->href);
}
$array_move = json_encode($array_move);
$array_move = str_replace("\"", "\\\"", $array_move);
//file_put_contents("log.txt", date('Y-m-d H:i:s') . " " . $array_move . PHP_EOL, FILE_APPEND);

require 'iframe.html.php';
// }
