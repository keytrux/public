<?php
ini_set('memory_limit', '1536M');
ini_set('max_execution_time', '43200'); 
$accountId = $_POST['accountId']; // id аккаунта МоегоСклада
$fio = $_POST['fio'];
$method = $_POST['method']; // метод для switch case
$name = $_POST['name']; // имя документа
$type_post = $_POST['type_post']; // тип документа для метода POST
$type = $_POST['type']; // тип документа для поиска
$assortmentId = $_POST['assortmentId']; // id позиции товара
$id = $_POST['id']; // id документа
$id_position = $_POST['id_position']; // id позиции
$dateStart = $_POST['dateStart']; // дата начала периода
$dateEnd = $_POST['dateEnd']; // дата конца периода
$number = $_POST['number']; 
$code = $_POST['code'];
$date = $_POST['date'];
$length = $_POST['length'];

$country_now = $_POST['country_now'];
$country_code = $_POST['country_code'];
$country_description = $_POST['country_description'];
$country_externalCode = $_POST['country_externalCode'];
$country_id = $_POST['country_id'];
$country_meta_href = $_POST['country_meta_href'];
$country_meta_mediaType = $_POST['country_meta_mediaType'];
$country_metadataHref = $_POST['country_metadataHref'];
$country_type = $_POST['country_type'];
$country_uuidHref = $_POST['country_uuidHref'];
$country_updated = $_POST['country_updated'];
$country_name = $_POST['country_name'];

require_once 'lib.php';

// if (empty($accountId)) {
// 	echo json_encode(false, "Аккаунт не найден!");
// 	exit;
// }

$app = AppInstance::loadApp($accountId);

switch ($method) {
    case "get_name": // получение наименований документов за период
        $filter = ($dateStart ? ";" . rawurlencode("moment>=" . $dateStart . " 00:00:00") : "") . ($dateEnd ? ";" . rawurlencode("moment<=" . $dateEnd . " 23:59:59") : "") . ";applicable=true";
        $json_name = jsonApi()->api('GET', "/entity/{$type}",  '?order=moment,desc&limit=1000&filter=' . $filter );
        $array_result = [];
        foreach ($json_name -> rows as $value)
        {
            $date = new DateTime($value->moment);
            $array_result[] = $value->name;
        }

        echo json_encode($array_result);
        break;
    break;

    case "get": // получение позийций выбранного документа
        $filter = ($dateStart ? ";" . rawurlencode("moment>=" . $dateStart . " 00:00:00") : "") . ($dateEnd ? ";" . rawurlencode("moment<=" . $dateEnd . " 23:59:59") : "") . ";applicable=true";
        $json_country = jsonApi()->api('GET', "/entity/{$type}", '?filter=name=' . $name . ';' . $filter . '&limit=1&expand=positions.assortment.country&order=moment,desc'); //запрос для получения развернутого массива с страной 
        $json_country_now = jsonApi()->api('GET', "/entity/{$type}", '?filter=name=' . $name . ';' . $filter . '&limit=1&expand=positions.country&order=moment,desc'); //запрос для получения развернутого массива с страной в оприходовании
        $json_get = jsonApi()->api('GET', "/entity/{$type}", '?filter=name=' . $name . ';' . $filter . '&limit=1&expand=positions.assortment&order=moment,desc'); // запрос для получения развернутого массива с позициями
        $id = $json_get->rows[0]->id; // id документа
        $size = $json_get->meta->size;

        $array_result = [];
        if($size > 0)
        {
            foreach ($json_get->rows[0]->positions->rows as $row1 => $row) {

                $item = [
                    'code' => $row->assortment->code,
                    'name' => $row->assortment->name,
                    'price' => round($row->price / 100, 3),
                    'gtd' => $row->gtd->name ?? '',
                    'country_now' => $json_country_now->rows[0]->positions->rows[$row1]->country->name ?? '',
                    'country' => $json_country->rows[0]->positions->rows[$row1]->assortment->country ?? '',
                    'product_id' => $row->assortment->meta->href,
                    'id_position' => $row->id,
                    'id_enter' => $id
                ];
                $array_result[] = $item; // Добавляем элемент в массив результатов
            }

            echo json_encode($array_result); // Возвращаем весь массив результатов в JSON формате
            break;
        }
        else
        {
            $array_result[] = 'Такого № нет!';
            echo json_encode($array_result);
            break;
        }
    break;

    case 'post': // поиск ГТД и обновление позиций
        $array_result = [];
        $limit = 1;  
        $offset = 0;
        $size_response = jsonApi()->api('GET', "/entity/{$type}", "?limit=1&filter=assortment=" . $assortmentId); // получение размера имеющихся документов по товару
        $size = $size_response->meta->size;

        if(isset($size_response->errors[0]->code) && $size_response->errors[0]->code = '1073') // ошибка о превышении параллельных запросов
        {
            $array_result[] = $size_response->errors[0]->code;
            echo json_encode($array_result); // Возвращаем текущий результат
            exit; // Завершаем скрипт
        }
        
        for ($offset; $offset < $size; $offset += $limit) 
        {
            $json_response = jsonApi()->api('GET', "/entity/{$type}", "?limit={$limit}&offset={$offset}&order=moment,desc&filter=assortment=" . $assortmentId . '&expand=positions');
    
            if(isset($json_response->errors[0]->code) && $json_response->errors[0]->code == '1073') // ошибка о превышении параллельных запросов
            {
                $array_result[] = $json_response->errors[0]->code;
                echo json_encode($array_result); // Возвращаем текущий результат 1073
                exit; // Завершаем скрипт
            }
            if (isset($json_response->rows) && is_array($json_response->rows)) { // если json не пустой
                foreach ($json_response->rows as $row) {
                    $positions = $row->positions->rows; // строка с позициями
                    $matching_positions = array_filter($positions, function($position) use ($assortmentId) { // поиск совпадающего id ассортимента
                        return $position->assortment->meta->href == $assortmentId;
                    });
                    
                    foreach ($matching_positions as $position) {
                        $gtd = $position->gtd->name ?? '';

                        if($gtd != '' && strpos(strtolower($gtd), '--') === false) // если нашли гтд
                        {
                            $array_gtd = array();
                            $array_gtd = array(
                                "gtd" => array(
                                    "name" => $gtd // Здесь добавляем значение gtd
                                )
                            );

                            $array_country = array();
                            $array_country = array(
                                "country" => array(
                                    "meta" => array(
                                        "href" => $country_meta_href,
                                        "metadataHref" => $country_metadataHref,
                                        "type" => $country_type,
                                        "mediaType" => $country_meta_mediaType,
                                        "uuidHref" => $country_uuidHref
                                    ),
                                    "id" => $country_id,
                                    "updated" => $country_updated,
                                    "name" => $country_name,
                                    "description" => $country_description,
                                    "code" => $country_code,
                                    "externalCode" => $country_externalCode
                            ));

                            $array_result[] = $gtd;
                            if(empty($country_now))
                            {
                                $array_result[] = $array_country['country']['name'];
                                $json = jsonApi()->api("PUT", "/entity/{$type_post}/" . $id . "/positions/" . $id_position, "", json_encode($array_country)); // отправка запроса PUT
                            }
                            else {
                                $array_result[] = $country_now;
                            }

                            $json = jsonApi()->api("PUT", "/entity/{$type_post}/" . $id . "/positions/" . $id_position, "", json_encode($array_gtd)); // отправка запроса PUT
                            echo json_encode($array_result);
                            exit; // Завершаем скрипт
                        }
                    }
                }
            }
            
        }
        if($gtd == '')
        {
            if($type == "enter" && $type_post == "supply")
            {
                $gtd = '----';

                $array_country = array();
                            $array_country = array(
                                "country" => array(
                                    "meta" => array(
                                        "href" => $country_meta_href,
                                        "metadataHref" => $country_metadataHref,
                                        "type" => $country_type,
                                        "mediaType" => $country_meta_mediaType,
                                        "uuidHref" => $country_uuidHref
                                    ),
                                    "id" => $country_id,
                                    "updated" => $country_updated,
                                    "name" => $country_name,
                                    "description" => $country_description,
                                    "code" => $country_code,
                                    "externalCode" => $country_externalCode
                            ));

                $array_result[] = $gtd;
                if(empty($country_now))
                {
                    $array_result[] = $array_country['country']['name'];
                    $json = jsonApi()->api("PUT", "/entity/{$type_post}/" . $id . "/positions/" . $id_position, "", json_encode($array_country)); // отправка запроса PUT
                }
                else {
                    $array_result[] = $country_now;
                }
            }
            elseif ($type == "supply" && $type_post == "enter") {
                $gtd = '----';

                $array_country = array();
                            $array_country = array(
                                "country" => array(
                                    "meta" => array(
                                        "href" => $country_meta_href,
                                        "metadataHref" => $country_metadataHref,
                                        "type" => $country_type,
                                        "mediaType" => $country_meta_mediaType,
                                        "uuidHref" => $country_uuidHref
                                    ),
                                    "id" => $country_id,
                                    "updated" => $country_updated,
                                    "name" => $country_name,
                                    "description" => $country_description,
                                    "code" => $country_code,
                                    "externalCode" => $country_externalCode
                            ));

                $array_result[] = $gtd;
                if(empty($country_now))
                {
                    $array_result[] = $array_country['country']['name'];
                    $json = jsonApi()->api("PUT", "/entity/{$type_post}/" . $id . "/positions/" . $id_position, "", json_encode($array_country)); // отправка запроса PUT
                }
                else {
                    $array_result[] = $country_now;
                }
            }
      
            $array_gtd = array();
                $array_gtd = array(
                    "gtd" => array(
                        "name" => $gtd // Здесь добавляем значение gtd
                    ));

            $json = jsonApi()->api("PUT", "/entity/{$type_post}/" . $id . "/positions/" . $id_position, "", json_encode($array_gtd)); // отправка запроса PUT
        }
        echo json_encode($array_result); 
        exit; // Завершаем скрипт

    case 'post_gtd': // проставление введенного ГТД
        $array_result = [];
        $gtd = $_POST['gtd'];
        $array_gtd = array();

        $array_gtd = array(
            "gtd" => array(
                "name" => $gtd // Здесь добавляем значение gtd
        ));

        $json = jsonApi()->api("PUT", "/entity/{$type_post}/" . $id . "/positions/" . $id_position, "", json_encode($array_gtd)); // отправка запроса PUT

        echo json_encode($array_gtd); 
        exit; // Завершаем скрипт
    break;

    case "search_product": // Поиск товара

        $limit = 100;
        $array_result = [];

        $json_product = jsonApi()->api('GET', "/entity/product", '?filter=code=' . $code . '&limit=1'); // запрос для получения развернутого массива товара

        $href = $json_product->rows[0]->meta->href; // ссылка на товар

        $json_enter_size = jsonApi()->api('GET', "/entity/enter", "?limit=1&order=moment,desc&filter=assortment=" . $href); // запрос к оприходованиям по товару

        $size_enter = $json_enter_size->meta->size;
        for ($offset = 0; $offset < $size_enter; $offset += $limit) 
        {
            $json_enter = jsonApi()->api('GET', "/entity/enter", "?limit=" . $limit ."&offset=" . $offset ."&order=moment,desc&filter=assortment=" . $href . '&expand=positions'); // запрос к оприходованиям по товару

            foreach ($json_enter->rows as $row) 
            {
                $positions = $row->positions->rows; // строка с позициями
                $matching_positions = array_filter($positions, function($position) use ($href) { // поиск совпадающего id ассортимента
                    return $position->assortment->meta->href == $href;
                });
    
                foreach ($matching_positions as $position) 
                {
                    $gtd = $position->gtd->name ?? '';
    
                    if($gtd != '' && strpos(strtolower($gtd), '--') === false) // если нашли гтд
                    {
                        $array_result[] = array("name" => $row->name,
                                                "gtd" => $gtd,
                                                "id_product" => $href,
                                                "id_document" => $row->id,
                                                "id_position" => $position->id,
                                                "type" => 'enter',
                                                "color" => 'green',
                                                "btn" => 'block');
                        break;
                    }
                    else 
                    {
                        $array_result[] = array("name" => $row->name,
                                                "gtd" => $gtd,
                                                "id_product" => $href,
                                                "id_document" => $row->id,
                                                "id_position" => $position->id,
                                                "type" => 'enter',
                                                "color" => 'red',
                                                "btn" => 'none');
                    }
                }
            }
        }
        
        $json_supply_size = jsonApi()->api('GET', "/entity/supply", "?limit=1&order=moment,desc&filter=assortment=" . $href); //запрос к приемкам по товару

        $size_supply = $json_supply_size->meta->size;

        for ($offset = 0; $offset < $size_supply; $offset += $limit) 
        {
            $json_supply = jsonApi()->api('GET', "/entity/supply", "?limit=" . $limit ."&offset=" . $offset . "&order=moment,desc&filter=assortment=" . $href. '&expand=positions'); //запрос к приемкам по товару
            foreach ($json_supply->rows as $row) 
            {
                $positions = $row->positions->rows; // строка с позициями
                $matching_positions = array_filter($positions, function($position) use ($href) { // поиск совпадающего id ассортимента
                    return $position->assortment->meta->href == $href;
                });

                foreach ($matching_positions as $position)
                {
                    $gtd = $position->gtd->name ?? '';

                    if($gtd != '' && strpos(strtolower($gtd), '--') === false) // если нашли гтд
                    {
                        $array_result[] = array("name" => $row->name,
                                                "gtd" => $gtd,
                                                "id_product" => $href,
                                                "id_document" => $row->id,
                                                "id_position" => $position->id,
                                                "type" => 'supply',
                                                "color" => 'green',
                                                "btn" => 'block');
                        break;
                    }
                    else 
                    {
                        $array_result[] = array("name" => $row->name,
                                                "gtd" => $gtd,
                                                "id_product" => $href,
                                                "id_document" => $row->id,
                                                "id_position" => $position->id,
                                                "type" => 'supply',
                                                "color" => 'red',
                                                "btn" => 'none');
                    }
                }
            }
        }

        echo json_encode($array_result);  
    break;

    case "get_table":
        $filter = ($dateStart ? ";" . rawurlencode("moment>=" . $dateStart . " 00:00:00") : "") . ($dateEnd ? ";" . rawurlencode("moment<=" . $dateEnd . " 23:59:59") : "") . ";applicable=true";
        $json = jsonApi()->api('GET', "/entity/{$type}",  '?order=moment,desc&limit=1000&filter=' . $filter );
        $array_result = [];
        if(isset($json->errors[0]->code) && $json->errors[0]->code = '1073') // ошибка о превышении параллельных запросов
        {
            $array_result[] = $json->errors[0]->code;
            echo json_encode($array_result); // Возвращаем текущий результат
            exit; // Завершаем скрипт
        }
        foreach ($json -> rows as $row)
        {
            $date = new DateTime($row->moment);
            $formattedDate = $date->format('d.m.Y');
            $array_result[] = array("date" => $formattedDate,
                                    "name" => $row->name
                                    );
        }

        echo json_encode($array_result);
    break;

    case "get_id_product": // получить все оприходования (за период)
        $json = jsonApi()->api('GET', "/entity/{$type}", '?order=moment,desc&limit=1&filter=name=' . $name . '&expand=positions.assortment'); //запрос для получения развернутого массива с страной 
        $json_country = jsonApi()->api('GET', "/entity/{$type}", '?order=moment,desc&limit=1&filter=name=' . $name . '&expand=positions.assortment.country'); //запрос для получения развернутого массива с страной в документе
        $json_country_now = jsonApi()->api('GET', "/entity/{$type}", '?order=moment,desc&limit=1&filter=name=' . $name . '&expand=positions.country'); //запрос для получения развернутого массива с страной 

        $id = $json->rows[0]->id;
        $array_result = [];
        if(isset($json->errors[0]->code) && $json->errors[0]->code = '1073') // ошибка о превышении параллельных запросов
        {
            $array_result[] = $json->errors[0]->code;
            echo json_encode($array_result); // Возвращаем текущий результат
            exit; // Завершаем скрипт
        }
        foreach ($json->rows[0]->positions->rows as $row1 => $row) {

            $item = [
                'type' => $type,
                'name' => $name,
                'product' => $json_country->rows[0]->positions->rows[$row1]->assortment->name,
                'code' => $json_country->rows[0]->positions->rows[$row1]->assortment->code,
                'date' => $date,
                'id' => $id,
                'id_position' => $row->id,
                'id_product' => $row->assortment->meta->href,
                'price' => round($row->price / 100, 3),
                'gtd' => $row->gtd->name ?? '',
                'country' => $json_country->rows[0]->positions->rows[$row1]->assortment->country ?? '',
                'country_now' => $json_country_now->rows[0]->positions->rows[$row1]->country->name ?? '',

            ];
            $array_result[] = $item; // Добавляем элемент в массив результатов
        }
        echo json_encode($array_result); // Возвращаем весь массив результатов в JSON формате
    break;

    case "post_table":
         
        $limit = 1;
        $array = [];
        $array_result = [];
        $size_response = jsonApi()->api('GET', "/entity/{$type}", "?limit=1&filter=assortment=" . $assortmentId); // получение размера имеющихся документов по товару
        $size = $size_response->meta->size;

        if(isset($size_response->errors[0]->code) && $size_response->errors[0]->code = '1073') // ошибка о превышении параллельных запросов
        {
            $array_result[] = $size_response->errors[0]->code;
            echo json_encode($array_result); // Возвращаем текущий результат
            exit; // Завершаем скрипт
        }

        for ($offset = 0; $offset < $size; $offset += $limit) 
        {
            $json_response = jsonApi()->api('GET', "/entity/{$type}", "?limit={$limit}&offset={$offset}&order=moment,desc&filter=assortment=" . $assortmentId . '&expand=positions');
            
            if(isset($json_response->errors[0]->code) && $json_response->errors[0]->code == '1073') // ошибка о превышении параллельных запросов
            {
                $array_result[] = $json_response->errors[0]->code;
                echo json_encode($array_result); // Возвращаем текущий результат 1073
                exit; // Завершаем скрипт
            }
            if (isset($json_response->rows) && is_array($json_response->rows)) { // если json не пустой
                foreach ($json_response->rows as $row) {
                    $positions = $row->positions->rows; // строка с позициями
                    $matching_positions = array_filter($positions, function($position) use ($assortmentId) { // поиск совпадающего id ассортимента
                        return $position->assortment->meta->href == $assortmentId;
                    });
                    
                    foreach ($matching_positions as $position) {
                        $gtd = $position->gtd->name ?? '';

                        if($gtd != '' && strpos(strtolower($gtd), '--') === false) // если нашли гтд
                        {
                            $array_gtd = array();
                            $array_gtd = array(
                                "gtd" => array(
                                    "name" => $gtd // Здесь добавляем значение gtd
                                )
                            );

                            $item = [
                                'type' => $type,
                                'length' => $length,
                                'name' => $name,
                                'date' => $date,
                                'id' => $id,
                                'id_position' => $id_position,
                                'id_product' => $assortmentId,
                                'gtd' => $gtd
                            ];
                            $array_result[] = $item; // Добавляем элемент в массив результатов

                            $array_country = array();
                            $array_country = array(
                                "country" => array(
                                    "meta" => array(
                                        "href" => $country_meta_href,
                                        "metadataHref" => $country_metadataHref,
                                        "type" => $country_type,
                                        "mediaType" => $country_meta_mediaType,
                                        "uuidHref" => $country_uuidHref
                                    ),
                                    "id" => $country_id,
                                    "updated" => $country_updated,
                                    "name" => $country_name,
                                    "description" => $country_description,
                                    "code" => $country_code,
                                    "externalCode" => $country_externalCode
                            ));

                            
                            $json = jsonApi()->api("PUT", "/entity/{$type_post}/" . $id . "/positions/" . $id_position, "", json_encode($array_gtd)); // отправка запроса PUT
                            
                            if(empty($country_now) && count($array_country) > 0)
                            {
                                $json = jsonApi()->api("PUT", "/entity/{$type_post}/" . $id . "/positions/" . $id_position, "", json_encode($array_country)); // отправка запроса PUT
                            }

                            echo json_encode($array_result);
                            exit; // Завершаем скрипт
                        }
                    }
                }
            }
            
        }
        if($gtd == '')
        {
            if($type == "enter" && $type_post == "supply")
            {
                $gtd = '----';
                $item = [
                    'type' => $type,
                    'length' => $length,
                    'name' => $name,
                    'date' => $date,
                    'id' => $id,
                    'id_position' => $id_position,
                    'id_product' => $assortmentId,
                    'gtd' => $gtd
                ];
                $array_result[] = $item; // Добавляем элемент в массив результатов

                $array_country = array();
                $array_country = array(
                    "country" => array(
                        "meta" => array(
                            "href" => $country_meta_href,
                            "metadataHref" => $country_metadataHref,
                            "type" => $country_type,
                            "mediaType" => $country_meta_mediaType,
                            "uuidHref" => $country_uuidHref
                        ),
                        "id" => $country_id,
                        "updated" => $country_updated,
                        "name" => $country_name,
                        "description" => $country_description,
                        "code" => $country_code,
                        "externalCode" => $country_externalCode
                ));
                
            }
            elseif ($type == "supply" && $type_post == "enter") {
                $gtd = '----';
                $item = [
                    'type' => $type,
                    'length' => $length,
                    'name' => $name,
                    'date' => $date,
                    'id' => $id,
                    'id_position' => $id_position,
                    'id_product' => $assortmentId,
                    'gtd' => $gtd
                ];
                $array_result[] = $item; // Добавляем элемент в массив результатов

                $array_country = array();
                $array_country = array(
                    "country" => array(
                        "meta" => array(
                            "href" => $country_meta_href,
                            "metadataHref" => $country_metadataHref,
                            "type" => $country_type,
                            "mediaType" => $country_meta_mediaType,
                            "uuidHref" => $country_uuidHref
                        ),
                        "id" => $country_id,
                        "updated" => $country_updated,
                        "name" => $country_name,
                        "description" => $country_description,
                        "code" => $country_code,
                        "externalCode" => $country_externalCode
                ));

            }
            
            $array_gtd = array();
            $array_gtd = array(
                "gtd" => array(
                    "name" => $gtd // Здесь добавляем значение gtd
                ));

            
            $json = jsonApi()->api("PUT", "/entity/{$type_post}/" . $id . "/positions/" . $id_position, "", json_encode($array_gtd)); // отправка запроса PUT
            if(empty($country_now) && count($array_country) > 0)
            {
                $json = jsonApi()->api("PUT", "/entity/{$type_post}/" . $id . "/positions/" . $id_position, "", json_encode($array_country)); // отправка запроса PUT
            }
        }

        echo json_encode($array_result); 
        exit; // Завершаем скрипт
    break;
}