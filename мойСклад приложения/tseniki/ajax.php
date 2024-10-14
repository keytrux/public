<?php
$method = $_POST['method'];
$account_id = $_POST['account_id'];
$user_id = $_POST['user_id'];
$uid = $_POST['uid'];

$filter = $_POST['filter'];
$list = $_POST['list'];
$size = $_POST['size'];
$type = $_POST['type'];
$skidka = $_POST['skidka'];
$dateIzm = $_POST['dateIzm'];
$store = $_POST['store'];
$storeId = $_POST['storeId'];
require_once 'lib.php';
$filelog = "logs/" . $uid . ".txt";
$filelog_err = "logs/error.txt";

$rows_result = json_decode($_POST['rows_result']);
$rows_result_get = json_decode($_POST['rows_result_get']);

$href_result = $_POST['href_result'];

if (empty($account_id)) exit;

$app = AppInstance::loadApp($account_id);

switch ($method) {
	case "loadoprih":
		if (empty($filter)) {
			echo json_encode(array());
			break;
		}
		//file_put_contents($filelog, date('Y-m-d H:i:s') . " " . $method . $filter . PHP_EOL, FILE_APPEND);
		$json = jsonApi()->api("GET",  '/entity/move/', $filter);
		//file_put_contents($filelog, date('Y-m-d H:i:s') . " " . $method . json_encode($json) . PHP_EOL, FILE_APPEND);
		$dateOprih = new DateTimeImmutable($json->moment);
		$dateOprih  = $dateOprih->sub(new DateInterval('PT1S'));
		$moment = $dateOprih->format('Y-m-d H:i:s');
		$store = $json->targetStore->meta->href;
		//$array_tovar[] = array("Код", "Название", "Ед.изм", "Страна", "Штрихкод", "Остаток", "Цена");
		$json = jsonApi()->api("GET",  '/entity/move/', $filter . "/positions");
		foreach ($json->rows as $row) {
			$json1 = jsonApi()->api("GET", str_replace("https://api.moysklad.ru/api/remap/1.2", "", $row->assortment->meta->href));
			$code = $json1->code; $name = $json1->name; $barcode = $json1->barcodes[0]->code128; $uom = str_replace("https://api.moysklad.ru/api/remap/1.2/entity/uom/", "", $json1->uom->meta->href);
			$cena = $json1->salePrices; $country = $json1->country;
			$json1 = jsonApi()->api("GET",  '/report/stock/bystore', '?filter=product=' . $row->assortment->meta->href . ";store=" . $store . ";moment=" . rawurlencode($moment));	
			$array_tovar[] = array($code, $name, $uom, $country, $barcode, $json1->rows[0]->stockByStore[0]->stock, $cena);
		}


		echo json_encode($array_tovar);
		break;
	case "loaddate":
		if (empty($dateIzm)) {
			echo json_encode(array());
			break;
		}
		file_put_contents($filelog, date('Y-m-d H:i:s') . " 53 " . $method . " " . $dateIzm . PHP_EOL, FILE_APPEND);
		// $filter1 = rawurlencode("updated>=" . $dateIzm . " 00:00:00") . ";" . rawurlencode("updated<=" . $dateIzm . " 23:59:59");
		// $limit = 1000; $i = 1;
		// $rows = array();
		// $array = array();
		// for ($offset = 0; $offset < $limit * $i; $offset += $limit) {
		// 	$json = jsonApi()->api("GET", "/entity/assortment", "?filter=" . $filter1 . "&limit=" . $limit . "&offset=" . $offset);
		// 	if ($json->errors[0]->code) {
		// 		echo json_encode($json->errors[0]);
		// 		file_put_contents($filelog, date('Y-m-d H:i:s') . " " . $method . " assort ". $json->errors[0]->error . " " . $json->errors[0]->code . PHP_EOL, FILE_APPEND);
		// 		exit;
		// 	}
		// 	$size = $json->meta->size;
		// 	//echo "<pre>"; print_r($json); echo "</pre>";
		// 	if ($size == 0) break;
		// 	$i = $size / $limit;
		// 	foreach ($json->rows as $row) {
		// 		usleep(50000);
		// 		$json2 = jsonApi()->api("GET", "/entity/product", "/" . $row->id . "/audit");
		// 		if ($json2->errors[0]->code) {
		// 			echo json_encode($json2->errors[0]);
		// 			file_put_contents($filelog, date('Y-m-d H:i:s') . " " . $method . " audit ". $json2->errors[0]->error . " " . $json2->errors[0]->code . PHP_EOL, FILE_APPEND);
		// 			exit;
		// 		}
		// 		//echo "<pre>"; print_r($json2); echo "</pre>";
				
				
		// 		foreach ($json2->rows as $row2) {
		// 			//echo substr($row2->moment,0,10) . "<br>";
		// 			//exit;
		// 			if (substr($row2->moment,0,10) == $dateIzm) {
		// 				$cens = $row2->diff->{'Ц '.$store};
		// 				//echo "<pre>"; print_r($cens); echo "</pre>";
		// 				if (empty($cens)) continue;
		// 				$key = array_search($row->id, array_column($array, 0));
		// 				//echo "индекс " . $key . "<br>"; 
		// 				if ($key == false) {
		// 					$json3 = jsonApi()->api("GET", "/report/stock/bystore/current?filter=storeId=" . str_replace("stockStore=https://api.moysklad.ru/api/remap/1.2/entity/store/", "", $storeId) . ";assortmentId=" . $row->id);
		// 					if ($json3->errors[0]->code) {
		// 						echo json_encode($json3->errors[0]);
		// 						file_put_contents($filelog, date('Y-m-d H:i:s') . " " . $method . " stock ". $json3->errors[0]->error . " " . $json3->errors[0]->code . PHP_EOL, FILE_APPEND);
		// 						exit;
		// 					}
		// 					file_put_contents($filelog, date('Y-m-d H:i:s') . " " . $method . " ". $row->name . PHP_EOL, FILE_APPEND);
		// 					$array[] = array($row->id, $row->code, $row->name, str_replace("https://api.moysklad.ru/api/remap/1.2/entity/uom/", "", $row->uom->meta->href), $row->country, $row->barcodes[0]->code128, $cens->oldValue[0], $cens->newValue[0], $json3[0]->stock);
		// 				} else {
		// 					$array[$key][6] = $cens->oldValue;
		// 				}
		// 			}
		// 		}
		// 	}
			
			
		// }

		$filter1 = "entityType=product;eventType=update;" . rawurlencode("moment>=" . $dateIzm . " 00:00:00") . ";" . rawurlencode("moment<=" . $dateIzm . " 23:59:59");
		file_put_contents($filelog, date('Y-m-d H:i:s') . " 109 " . $method . " ". $filter1 . PHP_EOL, FILE_APPEND);
		
		$limit = 25; $i = 1;
		$rows = array();

		for ($offset = 0; $offset < $limit * $i; $offset += $limit) {
			
			$json = jsonApi()->api("GET", "/audit", "?filter=" . $filter1 . "&limit=" . $limit . "&offset=" . $offset);
			if(isset($json->errors[0]->code) && $json->errors[0]->code = '1073') // ошибка о превышении параллельных запросов
			{
				$array_result[] = $json->errors[0]->code;
				echo json_encode($array_result); // Возвращаем текущий результат
				exit; // Завершаем скрипт
			}
			// if(isset($json->errors[0]->code) && $json->errors[0]->code = '1049') // ошибка о превышении параллельных запросов
			// {
			// 	$array_result[] = $json->errors[0]->code;
			// 	echo json_encode($array_result); // Возвращаем текущий результат
			// 	exit; // Завершаем скрипт
			// }
			if ($json->errors[0]->code) {
				file_put_contents($filelog, date('Y-m-d H:i:s') . " 119 " . $method . " ". $json->errors[0]->code . " " . $json->errors[0]->error . PHP_EOL, FILE_APPEND);
				file_put_contents($filelog_err, date('Y-m-d H:i:s') . " " . $uid . " 120 " . $method . " ". $json->errors[0]->code . " " . $json->errors[0]->error . PHP_EOL, FILE_APPEND);
				echo json_encode($json->errors[0]);
				exit;
			}
			$size = $json->meta->size;
			file_put_contents($filelog, date('Y-m-d H:i:s') . " 123 " . $method . " ". $json->meta->size . PHP_EOL, FILE_APPEND);
			if ($size == 0) break;
			$i = $size / $limit;
			$rows[] = $json->rows;
			$item = [
				'rows' => $json->rows,
				'uid' => $json->$rows->uid,
			];
			$array_result[] = $item;
		}

		echo json_encode($rows);
		break;

		// $rows2 = array();
		// foreach ($rows as $row) {
		// 	foreach ($row as $r) {
		// 		if($r->uid != "nosovav@npotamara" && 
		// 			$r->uid != "nosov_leonid@npotamara" && 
		// 			$r->uid != "admin@npotamara" && 
		// 			$r->uid != "ganina_gi@npotamara") // проверка кто изменил товар, брать действия из аудита только этих людей, если это никто из них - пропуск действия
		// 		{
		// 			file_put_contents($filelog, date('Y-m-d H:i:s') . " 777 " . $method . " ". $r->uid . " " . PHP_EOL, FILE_APPEND);
		// 			continue;
		// 		} 
		// 		file_put_contents($filelog, date('Y-m-d H:i:s') . " 666 " . $method . " ". $r->uid . " " . PHP_EOL, FILE_APPEND);
		// 		$href = str_replace("https://api.moysklad.ru/api/remap/1.2", "", $r->events->meta->href);
				
		// 		$limit = 25; $i = 1; sleep(1);
		// 		for ($offset = 0; $offset < $limit * $i; $offset += $limit) {
		// 			$json = jsonApi()->api("GET", $href . "?filter=limit=" . $limit . "&offset=" . $offset);
		// 			if ($json->errors[0]->code) {
		// 				file_put_contents($filelog, date('Y-m-d H:i:s') . " 139 " . $method . " ". $json->errors[0]->code . " " . $json->errors[0]->error . PHP_EOL, FILE_APPEND);
		// 				file_put_contents($filelog_err, date('Y-m-d H:i:s') . " " . $uid . " 140 " . $method . " ". $json->errors[0]->code . " " . $json->errors[0]->error . PHP_EOL, FILE_APPEND);
		// 				echo json_encode($json->errors[0]);
		// 				exit;
		// 			}
		// 			$size = $json->meta->size;
		// 			file_put_contents($filelog, date('Y-m-d H:i:s') . " 142 " . $method . " ". $json->meta->size . PHP_EOL, FILE_APPEND);
		// 			if ($size == 0) break;
		// 			$i = $size / $limit;
		// 			$rows2[] = $json->rows;
		// 		}
		// 	}
		// }
		// $rows2 = $rows2;
		// /* echo json_encode($rows2);
		// break; */
		// // unset($rows);
		// $array = array();
		// foreach ($rows2 as $row) {
		// 	foreach ($row as $r) {
		// 		$diff = $r->diff; 
		// 		$entity = $r->entity;
		// 		$cens = $diff->{'Ц '.$store};
		// 		if (empty($cens)) continue;
		// 		$json = jsonApi()->api("GET", str_replace("https://api.moysklad.ru/api/remap/1.2", "", $entity->meta->href));
		// 		file_put_contents($filelog, date('Y-m-d H:i:s') . " 161 " . $method . " ". $json->id . PHP_EOL, FILE_APPEND);
		// 		if ($json->errors[0]->code == "1021") continue;
		// 		if ($json->errors[0]->code) {
		// 			file_put_contents($filelog, date('Y-m-d H:i:s') . " 167 " . $method . " ". $json->errors[0]->code . " " . $json->errors[0]->error . PHP_EOL, FILE_APPEND);
		// 			file_put_contents($filelog_err, date('Y-m-d H:i:s') . " " . $uid . " 168 " . $method . " ". $json->errors[0]->code . " " . $json->errors[0]->error . PHP_EOL, FILE_APPEND);
		// 			echo json_encode($json->errors[0]);
		// 			exit;
		// 		}
		// 		sleep(1);
				
		// 		$json2 = jsonApi()->api("GET", "/report/stock/bystore/current?filter=storeId=" . str_replace("stockStore=https://api.moysklad.ru/api/remap/1.2/entity/store/", "", $storeId) . ";assortmentId=" . $json->id);
		// 		if ($json2->errors[0]->code) {
		// 			file_put_contents($filelog, date('Y-m-d H:i:s') . " 176 " . $method . " ". $json2->errors[0]->code . " " . $json2->errors[0]->error . PHP_EOL, FILE_APPEND);
		// 			file_put_contents($filelog_err, date('Y-m-d H:i:s') . " " . $uid . " 177 " . $method . " ". $json2->errors[0]->code . " " . $json2->errors[0]->error . PHP_EOL, FILE_APPEND);
		// 			echo json_encode($json2->errors[0]);
		// 			exit;
		// 		}
		// 		$key = array_search($json->id, array_column($array, 0));
				
		// 		if ($key == false) {
		// 			file_put_contents($filelog, date('Y-m-d H:i:s') . " 184 " . $method . " ". count($array) . PHP_EOL, FILE_APPEND);
		// 			$array[] = array($json->id, $json->code, $json->name, str_replace("https://api.moysklad.ru/api/remap/1.2/entity/uom/", "", $json->uom->meta->href), $json->country, $json->barcodes[0]->code128, $cens->oldValue[0], $cens->newValue[0], $json2[0]->stock);
		// 		} else {
		// 			$array[$key][6] = $cens->oldValue;
		// 		}
		// 	}
		// }


		/* old
		$filter1 = "entityType=product;eventType=update;" . rawurlencode("moment>=" . $dateIzm . " 00:00:00") . ";" . rawurlencode("moment<=" . $dateIzm . " 23:59:59");
		file_put_contents($filelog, date('Y-m-d H:i:s') . " " . $method . " ". $filter1 . PHP_EOL, FILE_APPEND);
		
		$limit = 25; $i = 1;
		$rows = array();
		for ($offset = 0; $offset < $limit * $i; $offset += $limit) {
			$json = jsonApi()->api("GET", "/audit", "?filter=" . $filter1 . "&limit=" . $limit . "&offset=" . $offset);
			$size = $json->meta->size;
			//file_put_contents($filelog, date('Y-m-d H:i:s') . " " . $method . " ". $json->meta->size . PHP_EOL, FILE_APPEND);
			if ($size == 0) break;
			$i = $size / $limit;
			$rows[] = $json->rows;
		}

		$rows2 = array();
		foreach ($rows as $row) {
			foreach ($row as $r) {
				$href = str_replace("https://api.moysklad.ru/api/remap/1.2", "", $r->events->meta->href);
				$limit = 25; $i = 1;
				for ($offset = 0; $offset < $limit * $i; $offset += $limit) {
					$json = jsonApi()->api("GET", $href . "?filter=limit=" . $limit . "&offset=" . $offset);
					$size = $json->meta->size;
					//file_put_contents($filelog, date('Y-m-d H:i:s') . " " . $method . " ". $json->meta->size . PHP_EOL, FILE_APPEND);
					if ($size == 0) break;
					$i = $size / $limit;
					$rows2[] = $json->rows;
				}
			}
		}

		unset($rows);
		$array = array();
		foreach ($rows2 as $row) {
			foreach ($row as $r) {
				$diff = $r->diff;
				$entity = $r->entity;
				$cens = $diff->{'Ц '.$store};
				if (empty($cens)) continue;
				$json = jsonApi()->api("GET", str_replace("https://api.moysklad.ru/api/remap/1.2", "", $entity->meta->href));
				//file_put_contents($filelog, date('Y-m-d H:i:s') . " " . $method . " ". $json->id . PHP_EOL, FILE_APPEND);
				//$json2 = jsonApi()->api("GET", "/report/stock/bystore/current?filter=storeId=" . str_replace("stockStore=https://api.moysklad.ru/api/remap/1.2/entity/store/", "", $storeId) . ";assortmentId=" . $json->id);
				
				$key = array_search($json->id, array_column($array, 0));
				if ($key == false) {
					file_put_contents($filelog, date('Y-m-d H:i:s') . " " . $method . " ". count($array) . PHP_EOL, FILE_APPEND);
					$array[] = array($json->id, $json->code, $json->name, str_replace("https://api.moysklad.ru/api/remap/1.2/entity/uom/", "", $json->uom->meta->href), $json->country, $json->barcodes[0]->code128, $cens->oldValue[0], $cens->newValue[0]);//, $json2[0]->stock);
				} else {
					$array[$key][6] = $cens->oldValue;
				}
					
				
			}
		} */
		// file_put_contents($filelog, date('Y-m-d H:i:s') . " 247 " . $method . " ". count($array) . PHP_EOL, FILE_APPEND);
		// echo json_encode($array);
		// break;

	case "href_loaddate":
		$rows2 = array();
		// foreach ($rows_result as $row) {
		// 	foreach ($row as $r) {
				
				file_put_contents($filelog, date('Y-m-d H:i:s') . " 666 " . $method . " ". $rows_result->uid . " " . PHP_EOL, FILE_APPEND);
				$href = str_replace("https://api.moysklad.ru/api/remap/1.2", "", $rows_result->events->meta->href);
				
				$limit = 25; $i = 1; sleep(1);
				for ($offset = 0; $offset < $limit * $i; $offset += $limit) {
					$json = jsonApi()->api("GET", $href . "?filter=limit=" . $limit . "&offset=" . $offset);
					if(isset($json->errors[0]->code) && $json->errors[0]->code = '1073') // ошибка о превышении параллельных запросов
					{
						$array_result[] = $json->errors[0]->code;
						echo json_encode($array_result); // Возвращаем текущий результат
						exit; // Завершаем скрипт
					}
					// if(isset($json->errors[0]->code) && $json->errors[0]->code = '1049') // ошибка о превышении параллельных запросов
					// {
					// 	$array_result[] = $json->errors[0]->code;
					// 	echo json_encode($array_result); // Возвращаем текущий результат
					// 	exit; // Завершаем скрипт
					// }
					if ($json->errors[0]->code) {
						file_put_contents($filelog, date('Y-m-d H:i:s') . " 139 " . $method . " ". $json->errors[0]->code . " " . $json->errors[0]->error . PHP_EOL, FILE_APPEND);
						file_put_contents($filelog_err, date('Y-m-d H:i:s') . " " . $uid . " 140 " . $method . " ". $json->errors[0]->code . " " . $json->errors[0]->error . PHP_EOL, FILE_APPEND);
						echo json_encode($json->errors[0]);
						exit;
					}
					$size = $json->meta->size;
					file_put_contents($filelog, date('Y-m-d H:i:s') . " 142 " . $method . " ". $json->meta->size . PHP_EOL, FILE_APPEND);
					if ($size == 0) break;
					$i = $size / $limit;
					$rows2[] = $json->rows;
				}
		// 	}
		// }
		$rows2 = $rows2;
		echo json_encode($rows2);
		break;
	break;

	case "get_loaddate":
		$array = array();
		foreach ($rows_result_get as $row) {
		// 	foreach ($row as $r) {
				$diff = $row->diff; 
				$entity = $row->entity;
				$cens = $diff->{'Ц '.$store};
				if (empty($cens)) continue;
				$json = jsonApi()->api("GET", str_replace("https://api.moysklad.ru/api/remap/1.2", "", $entity->meta->href));
				file_put_contents($filelog, date('Y-m-d H:i:s') . " 161 " . $method . " ". $json->id . PHP_EOL, FILE_APPEND);
				if(isset($json->errors[0]->code) && $json->errors[0]->code = '1073') // ошибка о превышении параллельных запросов
				{
					$array_result[] = $json->errors[0]->code;
					echo json_encode($array_result); // Возвращаем текущий результат
					exit; // Завершаем скрипт
				}
				// if(isset($json->errors[0]->code) && $json->errors[0]->code = '1049') // ошибка о превышении параллельных запросов
				// {
				// 	$array_result[] = $json->errors[0]->code;
				// 	echo json_encode($array_result); // Возвращаем текущий результат
				// 	exit; // Завершаем скрипт
				// }
				if ($json->errors[0]->code == "1021") continue;
				if ($json->errors[0]->code) {
					file_put_contents($filelog, date('Y-m-d H:i:s') . " 167 " . $method . " ". $json->errors[0]->code . " " . $json->errors[0]->error . PHP_EOL, FILE_APPEND);
					file_put_contents($filelog_err, date('Y-m-d H:i:s') . " " . $uid . " 168 " . $method . " ". $json->errors[0]->code . " " . $json->errors[0]->error . PHP_EOL, FILE_APPEND);
					echo json_encode($json->errors[0]);
					exit;
				}
				sleep(1);
				
				$json2 = jsonApi()->api("GET", "/report/stock/bystore/current?filter=storeId=" . str_replace("stockStore=https://api.moysklad.ru/api/remap/1.2/entity/store/", "", $storeId) . ";assortmentId=" . $json->id);
				if ($json2->errors[0]->code) {
					file_put_contents($filelog, date('Y-m-d H:i:s') . " 176 " . $method . " ". $json2->errors[0]->code . " " . $json2->errors[0]->error . PHP_EOL, FILE_APPEND);
					file_put_contents($filelog_err, date('Y-m-d H:i:s') . " " . $uid . " 177 " . $method . " ". $json2->errors[0]->code . " " . $json2->errors[0]->error . PHP_EOL, FILE_APPEND);
					echo json_encode($json2->errors[0]);
					exit;
				}
				$key = array_search($json->id, array_column($array, 0));
				
				if ($key == false) {
					file_put_contents($filelog, date('Y-m-d H:i:s') . " 184 " . $method . " ". count($array) . PHP_EOL, FILE_APPEND);
					$array[] = array($json->id, $json->code, $json->name, str_replace("https://api.moysklad.ru/api/remap/1.2/entity/uom/", "", $json->uom->meta->href), $json->country, $json->barcodes[0]->code128, $cens->oldValue[0], $cens->newValue[0], $json2[0]->stock);
				} else {
					$array[$key][6] = $cens->oldValue;
				}
		// 	}
		
		}
		file_put_contents($filelog, date('Y-m-d H:i:s') . " 247 " . $method . " ". count($array) . PHP_EOL, FILE_APPEND);
		echo json_encode($array);
		
	break;
	case "load":
		if (empty($filter)) {
			echo json_encode(array());
			break;
		}
		$json = jsonApi()->api('GET', '/entity/assortment', '?filter=' . urlencode($filter));
		echo json_encode($json);
		break;
	case "pdf":
		$date = date('d.m.Y');
		file_put_contents($filelog, date('Y-m-d H:i:s') . " " . $method . " ". $list . PHP_EOL, FILE_APPEND);
		$list = json_decode($list);
		$style = '
			@page { margin: 20; }
			body{padding: 0; margin: 0; width: auto;} 
			table { border-collapse: collapse; border-spacing: 0; width: auto;} 
			.table td{width: auto; padding: 1;}
			table td {width: auto; padding: 0;}';
		file_put_contents($filelog, date('Y-m-d H:i:s') . " " . $method . " ". json_last_error_msg() . PHP_EOL, FILE_APPEND);
		$_size = 4;
		
		switch ($size) {
			case '1':
				$style .= 
					"div.block{width: 135px; padding: 0 3px; border: 1px solid #000; box-sizing: border-box; text-align: left;} 
					.code{width: 100%; text-align: right; font-size: 7px; line-height: 9px;} 
					.name{width: 100%; word-break: break-all; overflow-wrap: break-word; overflow: hidden; text-align: center; height: 35px; font-size: 12px; line-height: 9px;} 
					.cenatitleproc{font-size: 5px;} 
					.cenatitle{font-size: 10px;} 
					.cenaold{font-size: 10px; line-height: 10px; font-weight: 700; height: 10px; text-decoration: line-through; text-align: center;} 
					.cenanew{font-size: 14px; line-height: 14px; font-weight: 700; text-align: center; width: 0.9in; white-space: nowrap;} 
					.barcodeimg{height: 10px; text-align: center;} 
					.barcodeimg img{height: inherit;} 
					.barcodetext{text-align: center; height: 7px; line-height: 5px; font-size: 5px;} 
					.info{font-size: 6px; line-height: 4px;}";
				$_size = 5;
				break;
			case '2':
				$style .= 
					"div.block{width: 170px; height: auto; padding: 0 4px; border: 1px solid #000; box-sizing: border-box; text-align: left;} 
					.code{width: 100%; text-align: right; font-size: 9px; line-height: 9px;} 
					.name{width: 100%; word-break: break-all; overflow-wrap: break-word; overflow: hidden; text-align: center; height: 0.63in; font-size: 17px; line-height: 12px;} 
					.cenatitleproc{font-size: 7px;} 
					.cenatitle{font-size: 11px;} 
					.cenaold{font-size: 14px; line-height: 10px; font-weight: 700; height: 10px; text-decoration: line-through; text-align: center;} 
					.cenanew{font-size: 20px; line-height: 20px; font-weight: 700; text-align: center; width: 1.17in; white-space: nowrap;} 
					.barcodeimg{height: 20px; text-align: center;} 
					.barcodeimg img{height: inherit;} 
					.barcodetext{text-align: center; height: 10px; line-height: 8px; font-size: 8px;} 
					.info{font-size: 9px; line-height: 6px;}";
				$_size = 4;
				break;
			case '3':
				$style .= 
					"div.block{width: 345px; height: auto; padding: 0 5px; border: 1px solid #000; box-sizing: border-box; text-align: left;} 
					.code{width: 100%; text-align: right; font-size: 12px; line-height: 12px;} 
					.name{width: 100%; word-break: break-all; overflow-wrap: break-word; overflow: hidden; text-align: center; height: 0.79in; font-size: 32px; line-height: 28px;} 
					.cenatitleproc{font-size: 10px;} 
					.cenatitle{font-size: 14px;} 
					.cenaold{font-size: 20px; line-height: 14px; font-weight: 700; height: 16px; text-decoration: line-through; text-align: center;} 
					.cenanew{font-size: 36px; line-height: 36px; font-weight: 700; text-align: center; width: 2.6in;} 
					.barcodeimg{height: 30px; text-align: center;} 
					.barcodeimg img{height: inherit;} 
					.barcodetext{text-align: center; height: 12px; line-height: 10px; font-size: 10px;} 
					.info{font-size: 10px; line-height: 10px;}";
				$_size = 2;
				break;
			case '4':
				$style .= 
					"div.block{width: 690px; height: 5.35in; padding: 0 5px; border: 1px solid #000; box-sizing: border-box; text-align: left;} 
					.code{width: 100%; text-align: right; font-size: 14px; line-height: 14px;} 
					.name{width: 100%; word-break: break-all; overflow-wrap: break-word; overflow: hidden; text-align: center; height: 4in; font-size: 76px; line-height: 70px;} 
					.cenatitleproc{display: none; font-size: 10px;} 
					.cenatitle{display: none; font-size: 14px;} 
					.cenaold{display: none; font-size: 16px; line-height: 16px; height: 16px; text-decoration: line-through; text-align: center;} 
					.cenanew{display: none; font-size: 36px; line-height: 36px; font-weight: 700; text-align: center; width: 2.6in;} 
					.barcodeimg{height: 60px; text-align: center;} 
					.barcodeimg img{height: inherit;} 
					.barcodetext{text-align: center; height: 15px; line-height: 15px; font-size: 15px;} 
					.info{font-size: 14px; line-height: 14px;}";
				$_size = 1;
				break;
			case '5':
				$style .= "div.block{width: 170px; height: 1.57in; padding: 0 4px; border: 1px solid #000; box-sizing: border-box; text-align: left;} 
				.code{width: 100%; text-align: right; font-size: 9px; line-height: 9px;} 
				.name{width: 100%; word-break: break-all; overflow-wrap: break-word; overflow: hidden; text-align: center; height: 1in; font-size: 20px; line-height: 20px;} 
				.cenatitleproc{display: none; font-size: 7px;} 
				.cenatitle{display: none; font-size: 11px;} 
				.cenaold{display: none; font-size: 10px; line-height: 10px; height: 10px; text-decoration: line-through; text-align: center;} 
				.cenanew{display: none; font-size: 20px; line-height: 20px; font-weight: 700; text-align: center; width: 1.17in;} 
				.barcodeimg{height: 20px; text-align: center;} 
				.barcodeimg img{height: inherit;} 
				.barcodetext{text-align: center; height: 10px; line-height: 8px; font-size: 8px;} 
				.info{font-size: 9px; line-height: 6px;}";
				$_size = 4;
				break;
			case '6':
				$style .= "div.block{width: 345px; height: 2.24in; padding: 0 5px; border: 1px solid #000; box-sizing: border-box; text-align: left;} 
				.code{width: 100%; text-align: right; font-size: 12px; line-height: 12px;} 
				.name{width: 100%; word-break: break-all; overflow-wrap: break-word; overflow: hidden; text-align: center; height: 1.4in; font-size: 36px; line-height: 36px;} 
				.cenatitleproc{display: none; font-size: 10px;} 
				.cenatitle{display: none; font-size: 14px;} 
				.cenaold{display: none; font-size: 16px; line-height: 16px; height: 16px; text-decoration: line-through; text-align: center;} 
				.cenanew{display: none; font-size: 36px; line-height: 36px; font-weight: 700; text-align: center; width: 2.6in;} 
				.barcodeimg{height: 30px; text-align: center;} 
				.barcodeimg img{height: inherit;} 
				.barcodetext{text-align: center; height: 12px; line-height: 10px; font-size: 10px;} 
				.info{font-size: 10px; line-height: 10px;}";
				$_size = 2;
				break;
			case '7':
				$style .= 
					"div.block{width: 3.7in; height: 2.72in; padding: 0 5px; border: 1px solid #000; box-sizing: border-box; text-align: left;} 
					.code{width: 100%; text-align: right; font-size: 8px; line-height: 8px;} 
					.name{width: 100%; word-break: break-all; overflow-wrap: break-word; overflow: hidden; text-align: center; height: 0.7in; font-size: 32px; line-height: 23px;} 
					.cenatitleproc{font-size: 18px; width: 0.38in} 
					.cenatitle{font-size: 16px; width: 0.38in} 
					.cenaold{font-size: 34px; line-height: 40px; font-weight: 700; text-align: center; width: 2.6in;} 
					.cenanew{font-size: 34px; line-height: 40px; font-weight: 700; text-align: center; width: 2.6in;} 
					.barcodeimg{height: 30px; text-align: center;} 
					.barcodeimg img{height: inherit;} 
					.barcodetext{text-align: center; height: 12px; line-height: 10px; font-size: 10px;} 
					.info{font-size: 10px; line-height: 10px;}";
				$_size = 2;
				break;
		}
		if ($size != '7') {
			$sk_old_cena = 'style="width: 35%;"';
			$sk_new_cena = 'style="width: 24%;"';
			$no_sk_cena = 'style="width: 29%;"';
		}
		$html = '<table class="table">';
		$html .= '<tr>';
		foreach ($list as $i => $l) {
			switch ($type) {
				case '1':
					$cena = $l[7] - $l[7] * (float) $skidka / 100;
					$nametype = "Скидка " . $skidka . "%";
					break;
				case '2':
					$cena = (float) $skidka * 100;
					$nametype = "Акция";
					break;
			}
			
			$tr = false;
			if ($i != 0 && $i % $_size == 0) {
				$html .= '</tr><tr>';
				$tr = true;
			}
			$html .= '<td><div class="block">';
			$html .= '<div class="code">' . $l[1] . '</div>';
			$html .= '<div class="name">' . $l[8] . '</div>';
			file_put_contents($filelog, date('Y-m-d H:i:s') . " " . $method . " ". strval($l[8]) . PHP_EOL, FILE_APPEND);
			if ($skidka != 0) {
				$html .= '<table style = "width: 100%;"><tr><td class="cenatitleproc" ' . $sk_old_cena . '>Старая цена:</td><td class="cenaold" style="width: 60%;">' . number_format($l[7] / 100, 2, ',', ' ') . '</td><td class="cenatitleproc" style="text-align: right; width: 5%;">руб.</td></tr></table>';
				$html .= '<table style = "width: 100%;"><tr><td class="cenatitleproc" ' . $sk_new_cena . '>Новая цена:</td><td class="cenanew" style="width: 61%;">' . number_format($cena / 100, 2, ',', ' ') . '</td><td class="cenatitleproc" style="text-align: right; width: 5%;">руб.</td></tr></table>';
				//$html .= '<div><div class="cenatitle" style="width: 27%;">Старая цена:</div><div class="cenaold" style="width: 68%;">' . number_format($l[6] / 100, 2, ',', ' ') . '</div><div class="cenatitle" style="text-align: right; width: 4%;">руб.</div></div>';
				//$html .= '<div><div class="cenatitle" style="width: 25%;">Новая цена:</div><div class="cenanew" style="width: 70%;">' . number_format($cena / 100, 2, ',', ' ') . '</div><div class="cenatitle" style="text-align: right; width: 4%;">руб.</div></div>';
			} else {
				//$html .= '<div><div class="cenatitle" style="width: 27%;"></div><div class="cenaold" style="width: 68%;"></div><div class="cenatitle" style="text-align: right; width: 4%;"></div></div>';
				//$html .= '<div><div class="cenatitle" style="width: 25%;">Цена:</div><div class="cenanew" style="width: 70%;">' . number_format($l[6] / 100, 2, ',', ' ') . '</div><div class="cenatitle" style="text-align: right; width: 4%;">руб.</div></div>';
				//$html .= '<table><tr><td class="cenatitle" style="width: 30%;"></td><td class="cenaold" style="width: 65%;"></td><td class="cenatitle" style="text-align: right; width: 5%;"></td></tr></table>';
				$html .= '<table style = "width: 100%;"><tr><td class="cenatitle" ' . $no_sk_cena . '>Цена:</td><td class="cenanew">' . number_format($l[7] / 100, 2, ',', ' ') . '</td><td class="cenatitle" style="text-align: right; width: 5%;">руб.</td></tr></table>';
			}
			
			if ($l[5]) {
				$src = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . str_replace($_SERVER['DOCUMENT_ROOT'], "", __DIR__) . "/generator.php?text=" . $l[5];
				$img = '<img width="90%" src="' . $src . '" />';
				$barcode = $l[5];
			} else {
				$img = ''; $barcode = '';
			}
			$html .= '<div class="barcodeimg">' . $img . '</div>';
			$html .= '<div class="barcodetext">' . $barcode . '</div>';
			$html .= '<div style="height: 4px;"></div>';
			$html .= '<table style = "width: 100%;"><tr><td class="info" style="width: 30%;">' . $l[4] . '</td><td class="info" style="width: 40%;">Ед.изм.:' . $l[3] . '</td><td class="info" style="width: 30%; text-align: right;"">' . $date . '</td></tr></table>';
			//$html .= '<div><div class="info" style="width: 40%;">' . $l[4] . '</div><div class="info" style="width: 30%;">Ед.изм.:' . $l[3] . '</div><div class="info" style="width: 29%; text-align: right;">' . $date . '</div></div>';
			$html .= '</div></td>';
		}
		if ($tr) {
			$html .= '</tr>';
		}
		$html .= '</table>';
		
		$filename = "price.pdf";
		$dir = __DIR__ . "/files/" . $accountId;
		if (!is_dir($dir)) {
			mkdir($dir, 0777, true);
		}
		$file = $dir . "/" . $filename;
		include_once 'dompdf/autoload.inc.php';
		$dompdf = new Dompdf\Dompdf();
		$dompdf->set_option('isRemoteEnabled', TRUE);
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->loadHtml('<html><head><style>' . $style . '</style></head><body style="font-family: \'DejaVu Serif\'">' . $html . '</body></html>', 'UTF-8');
		$dompdf->render();
		$pdf = $dompdf->output(); 
		file_put_contents($file, $pdf);

		echo json_encode(array($_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . str_replace($_SERVER['DOCUMENT_ROOT'], "", $dir) . "" . $filename, $filename));
		break;
}
?>