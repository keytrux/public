<?php
$accountId = $_POST['accountId'];
$method = $_POST['method'];
$dateStart = $_POST['dateStart'];
$dateEnd = $_POST['dateEnd'];
$stores = !empty($_POST['stores']) ? json_decode($_POST['stores'], true) : array();
$options = !empty($_POST['options']) ? json_decode($_POST['options'], true) : array();
$result = !empty($_POST['result']) ? json_decode($_POST['result'], true) : array();
$file_log = date('YmdHis') . '_log.txt';
//file_put_contents($file_log, date('Y-m-d H:i:s'). " " . $method . " " . count($stores) . PHP_EOL, FILE_APPEND);
require_once 'lib.php';

if (empty($accountId)) {
	echo json_encode(false, "Аккаунт не найден!");
	exit;
}

$app = AppInstance::loadApp($accountId);

switch ($method) {
	case "get":  // Отчет по продажам по магазинам.
		$array = array();
		$store = "";
		$store_url = "https://api.moysklad.ru/api/remap/1.2/entity/store/";
		foreach ($stores as $id) {
			$store .= "store=" . $store_url . $id . ";";
		}
		if (strlen($store) > 0) $store = substr($store, 0, -1);
		
		$filter = $store . ($dateStart ? ";" . rawurlencode("moment>=" . $dateStart . " 00:00:00") : "") . ($dateEnd ? ";" . rawurlencode("moment<=" . $dateEnd . " 23:59:59") : "") . ";applicable=true";
		
		$rows = array();
		if (in_array("Отгрузка", $options)) {
			$limit = 1000; $size = 1;
			for ($offset = 0; $offset < $limit * $size; $offset += $limit) {
				$json = jsonApi()->api('GET', '/entity/demand', "?filter=" . $filter . "&limit=" . $limit . "&offset=" . $offset);
				$size = $json->meta->size / $limit;
				$rows[] = $json->rows;
				
			}
		}
		$array[] = $rows;
		//file_put_contents($file_log, date('Y-m-d H:i:s') . " " . $method . " Считаем отгрузки ". count($rows) . " " . count($array) . PHP_EOL, FILE_APPEND);
		
		$rows = array();
		$limit = 1000; $size = 1;
		for ($offset = 0; $offset < $limit * $size; $offset += $limit) {
			$json = jsonApi()->api('GET', '/entity/retaildemand', "?filter=" . $filter . "&limit=" . $limit . "&offset=" . $offset);
			$size = $json->meta->size / $limit;
			$rows[] = $json->rows;
			
		}
		$array[] = $rows;
		//file_put_contents($file_log,  date('Y-m-d H:i:s') . " " . $method . " Считаем продажи ". count($rows) . " " . count($array) . PHP_EOL, FILE_APPEND);
		$rows = array();
		$limit = 1000; $size = 1;
		for ($offset = 0; $offset < $limit * $size; $offset += $limit) {
			$json = jsonApi()->api('GET', '/entity/retailsalesreturn', "?filter=" . $filter . "&limit=" . $limit . "&offset=" . $offset);
			$size = $json->meta->size / $limit;
			$rows[] = $json->rows;
			
		}
		$array[] = $rows;
		//file_put_contents($file_log,  date('Y-m-d H:i:s') . " " . $method . " Считаем возвраты продаж ". count($rows) . " " . count($array) . PHP_EOL, FILE_APPEND);
		$rows = array();
		if (in_array("Себестоимость", $options)) {
			foreach ($stores as $id) {
				$rows2 = array();
				$store = "store=" . $store_url . $id;
				$filter2 = $store . ($dateStart ? "&" . "momentFrom=" . rawurlencode($dateStart . " 00:00:00") : "") . ($dateEnd ? "&" . "momentTo=" . rawurlencode($dateEnd . " 23:59:59") : "");
				$limit = 1000; $size = 1;
				for ($offset = 0; $offset < $limit * $size; $offset += $limit) {
					$json = jsonApi()->api('GET', '/report/profit/byemployee', "?filter=" . $filter2 . "&limit=" . $limit . "&offset=" . $offset);
					$size = $json->meta->size / $limit;
					$rows2[] = $json->rows;
					
				}
				$rows[] = array($id, $rows2);
			}
		}
		$array[] = $rows;
		//file_put_contents($file_log,  date('Y-m-d H:i:s') . " " . $method . " Считаем себестоимость ". count($rows) . " " . count($array) . PHP_EOL, FILE_APPEND);
		echo json_encode($array);
		break;
	
	case "get2":   // Отчет по продажам со средним чеком и нарастающимм итогом.
		$array = array();
		$store_url = "https://api.moysklad.ru/api/remap/1.2/entity/store/";
		foreach ($stores as $id) {
			$store = "store=" . $store_url . $id;
			$filter = $store . ($dateStart ? ";" . rawurlencode("moment>=" . $dateStart . " 00:00:00") : "") . ($dateEnd ? ";" . rawurlencode("moment<=" . $dateEnd . " 23:59:59") : "") . ";applicable=true";
			
			$rows = array();
			$limit = 1000; $size = 1;
			for ($offset = 0; $offset < $limit * $size; $offset += $limit) {
				$json = jsonApi()->api("GET", "/entity/retaildemand", "?filter=" . $filter . "&limit=" . $limit . "&offset=" . $offset . "&order=created");
				$size = $json->meta->size / $limit;
				$rows[] = $json->rows;
				
			}
			$array[] = $rows;
			//file_put_contents($file_log, date('Y-m-d H:i:s') . " " . $method . " Считаем продажи ". count($rows) . " " . count($array) . PHP_EOL, FILE_APPEND);
			$rows = array();
			$limit = 1000; $size = 1;
			for ($offset = 0; $offset < $limit * $size; $offset += $limit) {
				$json = jsonApi()->api("GET", "/entity/retailsalesreturn", "?filter=" . $filter . "&limit=" . $limit . "&offset=" . $offset . "&order=created");
				$size = $json->meta->size / $limit;
				$rows[] = $json->rows;
				
			}
			$array[] = $rows;
			//file_put_contents($file_log, date('Y-m-d H:i:s') . " " . $method . " Считаем возвраты ". count($rows) . " " . count($array) . PHP_EOL, FILE_APPEND);
			$rows = array();
			$limit = 1000; $size = 1;
			for ($offset = 0; $offset < $limit * $size; $offset += $limit) {
				$json = jsonApi()->api("GET", "/entity/demand", "?filter=" . $filter . "&limit=" . $limit . "&offset=" . $offset . "&order=created");
				$size = $json->meta->size / $limit;
				$rows[] = $json->rows;
				
			}
			$array[] = $rows;
			//file_put_contents($file_log, date('Y-m-d H:i:s') . " " . $method . " Считаем отгрузки ". count($rows) . " " . count($array) . PHP_EOL, FILE_APPEND);
		}
		echo json_encode($array);
		break;
	case "download":
		$filename = date("YmdHis") . ".xlsx";
		$dir = __DIR__ . "/files/" . $accountId;
		if (!is_dir($dir)) {
			mkdir($dir, 0777, true);
		}
		$file = $dir . "/" . $filename;
		require_once __DIR__ . '/PHPExcel-1.8/Classes/PHPExcel.php';
		require_once __DIR__ . '/PHPExcel-1.8/Classes/PHPExcel/Writer/Excel2007.php';
		$xls = new PHPExcel();
		$xls->setActiveSheetIndex(0);
		$sheet = $xls->getActiveSheet();
		$sheet->setTitle('1');
		$letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";	
		$sheet->setCellValueExplicit($letters[0] . "1", "Название", PHPExcel_Cell_DataType::TYPE_STRING);
		foreach ($options as $i => $option) {
			$sheet->setCellValueExplicit($letters[$i + 1] . "1", $option, PHPExcel_Cell_DataType::TYPE_STRING);
		}
		foreach ($result as $i => $res) {
			$key = array_search($res['id'], array_column($stores, 'id'));
			$sheet->setCellValueExplicit($letters[0] . ($i + 2), $key !== false ? $stores[$key]['name'] : $res['id'], PHPExcel_Cell_DataType::TYPE_STRING);
			foreach ($options as $i2 => $option) {
				$value = 0;
				switch ($option) {
					case "Отгрузка": $value = $res['otgruzki'] / 100; break;
					case "Продажи общие": $value = $res['prodaj'] / 100; break;
					case "Продажи по наличке": $value = ($res['prodajCash'] - $res['vozvratCash']) / 100; break;
					case "Продажи по карте": $value = ($res['prodajNoCash'] - $res['vozvratNoCash']) / 100; break;
					case "Возврат общий": $value = $res['vozvrat'] / 100; break;
					case "Возврат по наличке": $value = $res['vozvratCash'] / 100; break;
					case "Возврат по карте": $value = $res['vozvratNoCash'] / 100; break;
					case "Себестоимость": $value = $res['seb'] / 100; break;
					case "Сумма": $value = ($res['otgruzki'] + $res['prodaj'] - $res['vozvrat']) / 100; break;
				}
				$sheet->setCellValueExplicit($letters[$i2 + 1] . ($i + 2), $value, PHPExcel_Cell_DataType::TYPE_NUMERIC);
			}
		}
		$objWriter = new PHPExcel_Writer_Excel2007($xls);
		$objWriter->save($file);
		echo json_encode(array($_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . str_replace($_SERVER['DOCUMENT_ROOT'], "", $dir) . "/" . $filename, $filename));
		break;
	case "download2":
		$filename = date("YmdHis") . ".xlsx";
		$dir = __DIR__ . "/files/" . $accountId;
		if (!is_dir($dir)) {
			mkdir($dir, 0777, true);
		}
		$file = $dir . "/" . $filename;
		require_once __DIR__ . '/PHPExcel-1.8/Classes/PHPExcel.php';
		require_once __DIR__ . '/PHPExcel-1.8/Classes/PHPExcel/Writer/Excel2007.php';
		$xls = new PHPExcel();
		$xls->setActiveSheetIndex(0);
		$sheet = $xls->getActiveSheet();
		$sheet->setTitle('1');
		$letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		foreach ($result as $i => $res) {
			$key = array_search($res[0], array_column($stores, 'id'));
			$sheet->setCellValueExplicit($letters[0] . "1", $key !== false ? $stores[$key]['name'] : $res[0], PHPExcel_Cell_DataType::TYPE_STRING);
			$sheet->setCellValueExplicit($letters[0] . "2", "Дата", PHPExcel_Cell_DataType::TYPE_STRING);
			$sheet->setCellValueExplicit($letters[1] . "2", "Реализовано за день", PHPExcel_Cell_DataType::TYPE_STRING);
			$sheet->setCellValueExplicit($letters[2] . "2", "Реализовано всего", PHPExcel_Cell_DataType::TYPE_STRING);
			$sheet->setCellValueExplicit($letters[3] . "2", "Средняя реализация за день", PHPExcel_Cell_DataType::TYPE_STRING);
			$sheet->setCellValueExplicit($letters[4] . "2", "Кол-во накладных за день", PHPExcel_Cell_DataType::TYPE_STRING);
			$sheet->setCellValueExplicit($letters[5] . "2", "Кол-во накладных всего", PHPExcel_Cell_DataType::TYPE_STRING);
			$sheet->setCellValueExplicit($letters[6] . "2", "Среднее кол-во накладных", PHPExcel_Cell_DataType::TYPE_STRING);
			$sheet->setCellValueExplicit($letters[7] . "2", "Средняя сумма накладной за день", PHPExcel_Cell_DataType::TYPE_STRING);
			$sum = 0; $kol = 0;
			for ($i2 = 1; $i2 < count($res); $i2++) {
				$sum += $res[$i2][1] - $res[$i2][3] + $res[$i2][5];
				$kol += $res[$i2][2] - $res[$i2][4] + $res[$i2][6];
				$sheet->setCellValueExplicit($letters[0] . ($i2 + 2), $res[$i2][0], PHPExcel_Cell_DataType::TYPE_STRING);
				$sheet->setCellValueExplicit($letters[1] . ($i2 + 2), ($res[$i2][1] - $res[$i2][3] + $res[$i2][5]) / 100, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$sheet->setCellValueExplicit($letters[2] . ($i2 + 2), $sum / 100, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$sheet->setCellValueExplicit($letters[3] . ($i2 + 2), $sum / $i2 / 100, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$sheet->setCellValueExplicit($letters[4] . ($i2 + 2), $res[$i2][2] - $res[$i2][4] + $res[$i2][6], PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$sheet->setCellValueExplicit($letters[5] . ($i2 + 2), $kol, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$sheet->setCellValueExplicit($letters[6] . ($i2 + 2), $kol / $i2, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$sheet->setCellValueExplicit($letters[7] . ($i2 + 2), ($res[$i2][1] - $res[$i2][3] + $res[$i2][5]) / ($res[$i2][2] - $res[$i2][4] + $res[$i2][6]) / 100, PHPExcel_Cell_DataType::TYPE_NUMERIC);
			}
		}
		$objWriter = new PHPExcel_Writer_Excel2007($xls);
		$objWriter->save($file);
		echo json_encode(array($_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . str_replace($_SERVER['DOCUMENT_ROOT'], "", $dir) . "/" . $filename, $filename));
		break;
}
?>