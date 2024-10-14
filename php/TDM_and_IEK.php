<?php
require_once 'Classes/PHPExcel.php';
require_once 'Classes/PHPMailer/PHPMailer.php';
require_once 'Classes/PHPMailer/SMTP.php';
require_once 'Classes/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$start = microtime(true); //счет выполнения времени скрипта
ini_set('allow_url_include', '1');
ini_set('allow_url_fopen', '1');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('memory_limit', '1536M');
ini_set('max_execution_time', '43200'); 
error_reporting(E_WARNING);
error_reporting(E_ALL);
error_reporting(E_WARNING);
error_reporting(0);
error_reporting(E_ERROR | E_WARNING | E_PARSE);

function api_ms($user, $url, $type, $data = null) // для подключения к api мойсклад
{
	// $user = "nosovav@npotamara:HcZCDw";
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_USERPWD, $user);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $type);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', "Accept-Encoding: gzip"));
    if (!empty($data)) 
    {
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_POST, true);
    }
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_ENCODING , 'gzip');
    
	$result = curl_exec($curl);

    curl_setopt($curl, CURLOPT_HEADER, 1);
    $res = curl_exec($curl);
    $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
    $header = substr($res, 0, $header_size);
    $headerLines = explode("\r\n", $header); // Создаем массив для заголовков
    $headers = array();
    for ($i = 0; $i < count($headerLines); $i++)  // Пропускаем первую строку, так как она содержит информацию о статусе ответа
    {
        $headerLine = $headerLines[$i];
        // Разделяем заголовок на имя и значение
        $headerParts = explode(": ", $headerLine);
        if (count($headerParts) === 2) 
        {
            $headerName = $headerParts[0];
            $headerValue = $headerParts[1];
            // Добавляем заголовок в массив
            $headers[$headerName] = $headerValue;
        }
    }
    curl_close($curl);
    
    if ($headers['x-ratelimit-remaining'] <= 5)
    {
        sleep(3); // задержка 3 секунды
    }
	
    return json_decode($result);
}

function api_tdm($url, $data = null) // для подключения к api тдм
{
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($curl, CURLOPT_POSTFIELDS, '{' . '"token":"TOKEN"' . $data . '}');
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_ENCODING , '');
    curl_setopt($curl, CURLOPT_MAXREDIRS , 10);
    curl_setopt($curl, CURLOPT_TIMEOUT , 0);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION , true);
    curl_setopt($curl, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1);
	$result = curl_exec($curl);
    curl_close($curl);
    return json_decode($result);
}

$user_moysklad = "USER:PASSWORD"; //логин:пароль
$url_main_moysklad = "https://api.moysklad.ru/api/remap/1.2";
$url_product_moysklad = "/entity/product";
$get = 'GET'; $post = 'POST'; $put = 'PUT';
$limit = 1000; $offset = 0; $i = 1;

$headerBarcode_iek = [ // столбцы для таблицы несовпадающих штрихкодов ИЭК
    '№',
    'Артикул',
    'Штрихкод в МС',
    'Штрихкод в ИЭК',
  ];

$header_price_iek = [ // столбцы для таблицы несовпадающей минимальной цены ИЭК
    'Номер',
    'Название товара',
    'Артикул',
    'Старая цена',
    'Новая цена'
  ];

$header_rrc_iek = [ // столбцы для таблицы несовпадающей розничной цены ИЭК
    'Номер',
    'Название товара',
    'Артикул',
    'Роз. цена МС',
    'Роз. цена ИЭК'
];

$header_noart_iek = // столбцы для таблицы несовпадающего артикула ИЭК
[
    'Название товара',
    'Артикул'
];

$headerBarcode = [ // столбцы для таблицы несовпадающих штрихкодов ТДМ
    '№',
    'Артикул',
    'Штрихкод в МС',
    'Штрихкод в ТДМ',
  ];

$header_noart_tdm = [ // столбцы для таблицы несовпадающего артикула ТДМ
    'Название товара',
    'Артикул'
];

$header_price_tdm = [ // столбцы для таблицы несовпадающей минимальной цены ТДМ
    'Номер',
    'Название товара',
    'Артикул',
    'Старая цена',
    'Новая цена'
];

$count_record_iek = 1; // счетчик товаров с несовпадающими мин. ценами ИЭК
$count_record_tdm = 1; // счетчик товаров с несовпадающими мин. ценами ТДМ

$count_xls = 1; // счетчик изменяемых товаров, несовпадающих с минимальной ценой ИЭК
$count_xls2 = 1; // счетчик изменяемых товаров, несовпадающих с розничной ценой ИЭК
$count_xls3 = 1; // счетчик изменяемых товаров, несовпадающих с минимальной ценой ТДМ
$count_barcode_iek = 1; // счетчик штрихкодов ИЭК
$count_barcode_tdm = 1; // счетчик штрихкодов ТДМ
$count_product = 0; //количество товаров

for($offset; $offset < $limit * $i; $offset+=$limit)
{
    $lim = "?limit=$limit"; //установка лимита
    $off = "&offset=$offset"; //установка смещения
    
    $json_moysklad = api_ms($user_moysklad, $url_main_moysklad . $url_product_moysklad .$lim .$off, $get, ""); //подключение к моему складу
    
    $size = $json_moysklad->meta->size; //кол-во товаров
	$i = $size / $limit; 
    
    foreach ($json_moysklad->rows as $row)
    {
        $json_moysklad_id = api_ms($user_moysklad, $url_main_moysklad . $url_product_moysklad . '/' . $row->id, $get, ""); //подключение к товарам по id
        $art_moysklad = $json_moysklad_id->article; //артикул товара
        $name_product = $json_moysklad_id -> name; //имя продукта
        $minPrice_json_value = $json_moysklad_id -> minPrice -> value; //минимальная цена товара
        $retail_price = $json_moysklad_id -> salePrices[0] -> value;

        foreach ($json_moysklad_id->attributes as $attributes) //прохождение по атрибутам
        {
            $name_attribute = $attributes->name; //получение имени атрибута (например 'Полное наименование', 'Торговая марка')
            if ($name_attribute == 'Торговая марка')
            {
                $value = $attributes->value;  //получение значения атрибута у Торговой марки, нужен ИЭК
                if($value == 'ИЭК' || $value == 'IEK')
                {   
                    $user_IEK = "USER:PASSWORD"; //логин:пароль
                    $url_main_IEK = "https://lk.iek.ru/api";
                    $url_product_IEK = "/products";
                    $entity = "&entity=roc,rrc,LogisticParameters";
                    $url_art = '?art=';
                    $json_IEK = api_ms($user_IEK, $url_main_IEK . $url_product_IEK . $url_art. $art_moysklad . $entity, $get, ""); //подключение к ИЭК
                    if ($json_IEK[0]->art == $art_moysklad)
                    {
                        $art_IEK = $json_IEK[0]->art; //получение артикула товара
                        $price = $json_IEK[0]->price; //получение цены товара
                        $roc_procent = $json_IEK[0]->roc; //получение процента у рекомендованной оптовой цены
                        $rrc_procent = $json_IEK[0]->rrc; //получение процента у рекомендованной розничной цены
                        $name_IEK = $json_IEK[0]->name; // получение названия товара
                        $barcode_IEK = $json_IEK[0]->LogisticParameters->individual->barcode; // получение названия товара
                        
                        if ($roc_procent != 0)
                        {
                            $roc_price = round($price * (100-$roc_procent), 0); //расчет рекомендованной оптовой цены
                            $rrc_price = round($price * (100-$rrc_procent), 0); //расчет рекомендованной розничной цены
 
                            if ($art_IEK == $art_moysklad) //если артикулы совпадают
                            {

                                if($barcode_IEK != $json_moysklad_id->barcodes[0]->code128 && !empty($barcode_IEK))
                                {
                                    echo '<b style = "color: red"> Несовпадающий артикул: </b><br>';
                                    echo '<br><b>Запись номер: ' . $count_record_iek . '</b><br>' .
                                    'Название товара в моем складе: ' . $name_product . '<br>' .
                                    'Название товара в ИЭК: ' . $name_IEK . '<br>' .
                                    'Штрихкод в МС: ' . $json_moysklad_id->barcodes[0]->code128 . '<br>' .
                                    'Штрихкод в ИЭК ' . $barcode_IEK . '<br>'; 

                                    $data_barcode_iek[] = //запись данных в массив для вывода в таблицу штрихкодов
                                    [
                                        [$count_barcode_iek, $art_moysklad, $json_moysklad_id->barcodes[0]->code128, $barcode_IEK]
                                        // номер записи, артикул в мс, штрихкод тдм, штрихкод в мс
                                    ];
                                    $count_barcode_iek++;
                                    $array_barcode_iek[] = array($id_moysklad, $barcode_IEK);
                                }


                                if ($roc_price != $minPrice_json_value) //если цены не совпадают
                                {
                                    echo '<br><b>Запись номер: ' . $count_record_iek . '</b><br>' .
                                    'Название товара в моем складе: ' . $name_product . '<br>' .
                                    'Название товара в ИЭК: ' . $name_IEK . '<br>' .
                                    'Мин. цена: ' . $minPrice_json_value . '<br>' .
                                    'Артикул в моем складе: ' . $art_moysklad . '<br>' .
                                    'Артикул в ИЭК: ' . $art_IEK . '<br>' .
                                    'Рекомендованная оптовая цена: ' .$roc_price . '<br>'; 
                                    $count_record_iek++;

                                    $price_before = $minPrice_json_value * 0.01;
                                    $price_after = $roc_price * 0.01;

                                    $data_mrc_iek[] = //запись данных в массив для вывода в таблицу
                                    [
                                        [$count_xls, $name_product, $art_moysklad, $price_before, $price_after]
                                    ];
                                    $count_xls++;
                                    $count_product++;

                                    $data = ["minPrice" => ["value" => $roc_price]]; //массив для изменения минимальной цены
                                    $data_string = json_encode($data);

                                    // $json_moysklad_post = api_ms($user_moysklad, $url_main_moysklad . $url_product_moysklad . '/' . $row->id, $put, $data_string); //обновление минимальной цены
                                }     
                                
                                if ($rrc_price > $retail_price)
                                {
                                    echo '<br><b>Запись номер: ' . $count_record_iek . '</b><br>' .
                                    'Название товара в моем складе: ' . $name_product . '<br>' .
                                    'Название товара в ИЭК: ' . $name_IEK . '<br>' .
                                    'Роз. цена: ' . $retail_price . '<br>' .
                                    'Артикул в моем складе: ' . $art_moysklad . '<br>' .
                                    'Артикул в ИЭК: ' . $art_IEK . '<br>' .
                                    'Рекомендованная розничная цена: ' .$rrc_price . '<br>';
                                    $count_record_iek++;
                                    
                                    $price_before2 = $retail_price * 0.01;
                                    $price_after2 = $rrc_price * 0.01;

                                    $data_excel_rrc[] = //запись данных в массив для вывода в таблицу
                                    [
                                        [$count_xls2, $name_product, $art_moysklad, $price_before2, $price_after2]
                                    ];
                                    $count_xls2++;
                                }
                            }
                        }  
                    }   
                    else 
                    {
                        echo '<b style = "color: red"> Несовпадающий артикул: </b><br>' .
                        'Название товара: ' . $name_product . '<br>' .
                        'Артикул: ' . $art_moysklad . '<br>';
                        $data_notart_iek[] =
                        [
                            [$name_product, $art_moysklad]
                        ];
                    }  
                }
                if($value == 'ТДМ' || $value == 'TDM')
                {
                    $url_main_tdm = "https://api3.tdme.ru"; // домен тдм

                    $json_TDM_barcode = api_tdm($url_main_tdm . "/price77/getPack", ', "articul"' . ':' . '"'. $art_moysklad . '"'); //подключение к ТДМ к баркодам
                    $json_TDM_mrc = api_tdm($url_main_tdm . "/price77/nomenclatureParametersExpect", ', "articul"' . ':' . '"'. $art_moysklad . '"'); //подключение к ТДМ 77 товару

                    if ($json_TDM_barcode->data[0]->articul == $art_moysklad && $json_TDM_mrc->data[0]->articul == $art_moysklad)
                    {
                        if ($json_moysklad_id->barcodes[0]->code128 != $json_TDM_barcode->data[0]->barcode && !empty($json_TDM_barcode->data[0]->barcode)) // если штрихкоды НЕ совпадают
                        {
                            $data_barcode[] = //запись данных в массив для вывода в таблицу штрихкодов
                            [
                                [$count_barcode_tdm, $art_moysklad, $json_moysklad_id->barcodes[0]->code128, $json_TDM_barcode->data[0]->barcode]
                                // номер записи, артикул в мс, штрихкод тдм, штрихкод в мс
                            ];
                            $count_barcode_tdm++;
                            $array_barcode[] = array($id_moysklad, $json_TDM_barcode->data[0]->barcode);
                        }

                        if ($json_TDM_mrc->data[0]->max_retail_price != $minPrice_json_value / 100 && $json_TDM_mrc->data[0]->max_retail_price > 0) //если цены не совпадают
                        {
                            echo '<br><b>Запись номер: ' . $count_record_tdm . '</b><br>' .
                            'Название товара в моем складе: ' . $name_product . '<br>' .
                            'Название товара в ТДМ: ' . $json_TDM_mrc->data[0]->title . '<br>' .
                            'Мин. цена: ' . $minPrice_json_value / 100 . '<br>' .
                            'Артикул в моем складе: ' . $art_moysklad . '<br>' .
                            'Артикул в ТДМ: ' . $json_TDM_mrc->data[0]->articul . '<br>' .
                            'Рекомендованная минимальная цена: ' .$json_TDM_mrc->data[0]->max_retail_price . '<br>'; 
                            $count_record_tdm++;
                            $count_product++;

                            $price_before = $minPrice_json_value * 0.01;
                            $price_after = $json_TDM_mrc->data[0]->max_retail_price;

                            $data_mrc_tdm[] = //запись данных в массив для вывода в таблицу несовпадающих цен
                            [
                                [$count_xls3, $name_product, $art_moysklad, $price_before, $price_after]
                                // номер записи, название товара, артикул мс, старая цена, новая цена
                            ];
                            $count_xls3++;
                            $data = ["minPrice" => ["value" => $price_after * 100]]; //массив для изменения минимальной цены
                            $data_string = json_encode($data);
                            
                            // $json_post_minPrice = api_ms($user_moysklad, "https://api.moysklad.ru/api/remap/1.2/entity/product/" . $id_moysklad, 'PUT', $data_string); //обновление минимальной цены
                        } 
                    }
                    else
                    {
                        echo '<b style = "color: red"> Несовпадающий артикул: </b><br>' .
                        'Название товара: ' . $name_product . '<br>' .
                        'Артикул: ' . $art_moysklad . '<br>';
                        $data_notart_tdm[] = //запись данных в массив для вывода в таблицу артикулов
                        [
                            [$name_product, $art_moysklad]
                        ];
                    }
                }
            }
        }
    }   
}

if (!empty($array_barcode))
{
	foreach ($array_barcode as $barcode) 
    {
        $array1 = array();
        $data = array(
            "barcodes" => array(
                array("code128" => $barcode[1])
            )
        );
        $data_string = json_encode($data);
        // $json_post_barcode = api_ms("https://api.moysklad.ru/api/remap/1.2/entity/product/", $barcode[0], "PUT", $data_string); // отправка запроса POST
    }
}
if (!empty($array_barcode_iek))
{
	foreach ($array_barcode_iek as $barcode) 
    {
        $array1 = array();
        $data = array(
            "barcodes" => array(
                array("code128" => $barcode[1])
            )
        );
        $data_string = json_encode($data);
        // $json_post_barcode = api_ms("https://api.moysklad.ru/api/remap/1.2/entity/product/", $barcode[0], "PUT", $data_string); // отправка запроса POST
    }
}

if (!empty($data_barcode_iek))
{
    $document = new \PHPExcel();
    $worksheet = $document->setActiveSheetIndex(0); // Выбираем первый лист в документе
    $worksheet->fromArray($headerBarcode_iek); // добавление шапки
    $worksheet->getColumnDimension('A')->setAutoSize(true); //установка автоматической ширины столбца
    $worksheet->getColumnDimension('C')->setAutoSize(true); //установка автоматической ширины столбца
    $worksheet->getColumnDimension('D')->setAutoSize(true); //установка автоматической ширины столбца
    foreach ($data_barcode_iek as $rowNum => $rowData) 
    {
        $worksheet->getColumnDimension('B')->setAutoSize(true); //установка автоматической ширины столбца
        $worksheet->fromArray($rowData, null, 'A' . ($rowNum + 2)); //запись данных из массива со второй строки
    }

    $writer = \PHPExcel_IOFactory::createWriter($document, 'Excel5'); //создание книги
    $writer->save('Reports/Несовпадение штрихкодов ИЭК ' . date('d.m.Y') .'.xlsx'); //сохранение файла
}
else
{
    echo '<br>Массив несовпадающих штрихкодов пустой <br>';
}

if (!empty($data_mrc_iek)) // таблица с мин. ценами ИЭК
{

    $document = new \PHPExcel();
    $worksheet = $document->setActiveSheetIndex(0); // Выбираем первый лист в документе
    $worksheet->fromArray($header_price_iek); // добавление шапки
    $worksheet->getColumnDimension('A')->setAutoSize(true); //установка автоматической ширины столбца
    $worksheet->getColumnDimension('C')->setAutoSize(true); //установка автоматической ширины столбца
    $worksheet->getColumnDimension('D')->setAutoSize(true); //установка автоматической ширины столбца
    foreach ($data_mrc_iek as $rowNum => $rowData) 
    {
        $worksheet->getColumnDimension('B')->setAutoSize(true); //установка автоматической ширины столбца
        $worksheet->fromArray($rowData, null, 'A' . ($rowNum + 2)); //запись данных из массива со второй строки
    }

    $writer = \PHPExcel_IOFactory::createWriter($document, 'Excel5'); //создание книги
    $writer->save('Reports/Обновление минимальных цен ИЭК ' . date('d.m.Y') .'.xlsx'); //сохранение файла
}
else
{
    echo '<br>Массив изменяемых товаров с минимальной ценой пустой <br>';
}

if (!empty($data_excel_rrc)) // таблица с роз. ценами ИЭК
{
    $document = new \PHPExcel();
    $worksheet = $document->setActiveSheetIndex(0); // Выбираем первый лист в документе
    $worksheet->fromArray($header_rrc_iek); // добавление шапки
    $worksheet->getColumnDimension('A')->setAutoSize(true); //установка автоматической ширины столбца
    $worksheet->getColumnDimension('C')->setAutoSize(true); //установка автоматической ширины столбца
    $worksheet->getColumnDimension('D')->setAutoSize(true); //установка автоматической ширины столбца
    $worksheet->getColumnDimension('E')->setAutoSize(true); //установка автоматической ширины столбца
    foreach ($data_excel_rrc as $rowNum => $rowData) 
    {
        $worksheet->getColumnDimension('B')->setAutoSize(true); //установка автоматической ширины столбца
        $worksheet->fromArray($rowData, null, 'A' . ($rowNum + 2)); //запись данных из массива со второй строки
    }

    $writer = \PHPExcel_IOFactory::createWriter($document, 'Excel5'); //создание книги
    $writer->save('Reports/Несовпадение розничной цены ИЭК ' . date('d.m.Y') .'.xlsx'); //сохранение файла
}
else
{
    echo '<br>Массив розничной цены пустой <br>';
}

if (!empty($data_notart_iek)) // таблица с отсут. арт. ИЭК
{
    $document1 = new \PHPExcel();
    $worksheet1 = $document1->setActiveSheetIndex(0); // Выбираем первый лист в документе
    $worksheet1->fromArray($header_noart_iek); // добавление шапки
    $worksheet1->getColumnDimension('B')->setAutoSize(true); //установка автоматической ширины столбца
    foreach ($data_notart_iek as $rowNum => $rowData) 
    {
        $worksheet1->getColumnDimension('A')->setAutoSize(true); //установка автоматической ширины столбца
        $worksheet1->fromArray($rowData, null, 'A' . ($rowNum + 2)); //запись данных из массива со второй строки
    }

    $writer = \PHPExcel_IOFactory::createWriter($document1, 'Excel5'); //создание книги
    $writer->save('Reports/Несовпадения артикула ИЭК ' . date('d.m.Y') .'.xlsx'); //сохранение файла
}
else
{
    echo '<br>Массив несовпадающих артикулов пустой <br>';
}

if (!empty($data_notart_tdm)) // таблица с отсут. арт. ТДМ
{
    $document1 = new \PHPExcel();
    $worksheet1 = $document1->setActiveSheetIndex(0); // Выбираем первый лист в документе
    $worksheet1->fromArray($header_noart_tdm); // добавление шапки
    $worksheet1->getColumnDimension('B')->setAutoSize(true); //установка автоматической ширины столбца
    foreach ($data_notart_tdm as $rowNum => $rowData) 
    {
        $worksheet1->getColumnDimension('A')->setAutoSize(true); //установка автоматической ширины столбца
        $worksheet1->fromArray($rowData, null, 'A' . ($rowNum + 2)); //запись данных из массива со второй строки
    }

    $writer = \PHPExcel_IOFactory::createWriter($document1, 'Excel5'); //создание книги
    $writer->save('Reports/Несовпадения артикула ТДМ ' . date('d.m.Y') .'.xlsx'); //сохранение файла
}
else
{
    echo '<br>Массив несовпадающих артикулов пустой <br>';
}

if (!empty($data_mrc_tdm)) // таблица с мин. ценами ТДМ
{
    $document = new \PHPExcel();
    $worksheet = $document->setActiveSheetIndex(0); // Выбираем первый лист в документе
    $worksheet->fromArray($header_price_tdm); // добавление шапки
    $worksheet->getColumnDimension('A')->setAutoSize(true); //установка автоматической ширины столбца
    $worksheet->getColumnDimension('C')->setAutoSize(true); //установка автоматической ширины столбца
    $worksheet->getColumnDimension('D')->setAutoSize(true); //установка автоматической ширины столбца
    $worksheet->getColumnDimension('E')->setAutoSize(true); //установка автоматической ширины столбца
    foreach ($data_mrc_tdm as $rowNum => $rowData) 
    {
        $worksheet->getColumnDimension('B')->setAutoSize(true); //установка автоматической ширины столбца
        $worksheet->fromArray($rowData, null, 'A' . ($rowNum + 2)); //запись данных из массива со второй строки
    }

    $writer = \PHPExcel_IOFactory::createWriter($document, 'Excel5'); //создание книги
    $writer->save('Reports/Обновление минимальных цен ТДМ ' . date('d.m.Y') .'.xlsx'); //сохранение файла

    echo "Таблица создана: " . round(microtime(true) - $start, 2)." сек.<br>";
}
else
{
    echo '<br>Массив изменяемых товаров с минимальной ценой пустой <br>';
}

if (!empty($data_barcode))
{
    $document = new \PHPExcel();
    $worksheet = $document->setActiveSheetIndex(0); // Выбираем первый лист в документе
    $worksheet->fromArray($headerBarcode); // добавление шапки
    $worksheet->getColumnDimension('A')->setAutoSize(true); //установка автоматической ширины столбца
    $worksheet->getColumnDimension('C')->setAutoSize(true); //установка автоматической ширины столбца
    $worksheet->getColumnDimension('D')->setAutoSize(true); //установка автоматической ширины столбца
    foreach ($data_barcode as $rowNum => $rowData) 
    {
        $worksheet->getColumnDimension('B')->setAutoSize(true); //установка автоматической ширины столбца
        $worksheet->fromArray($rowData, null, 'A' . ($rowNum + 2)); //запись данных из массива со второй строки
    }

    $writer = \PHPExcel_IOFactory::createWriter($document, 'Excel5'); //создание книги
    $writer->save('Reports/Несовпадение штрихкодов ТДМ ' . date('d.m.Y') .'.xlsx'); //сохранение файла
}
else
{
    echo '<br>Массив несовпадающих штрихкодов пустой <br>';
}
    
    $mail = new PHPMailer;
    $mail->CharSet = 'UTF-8';
     
    // Настройки SMTP
    $mail->isSMTP();
    $mail->SMTPAutoTLS = false;
    $mail->SMTPSecure = false;

    $mail->Host = 'HOST';
    $mail->Port = 'PORT';
    $mail->Username = 'USER';
    $mail->Password = 'PASSWORD';
     
    // От кого
    $mail->setFrom('MAIL', '');		
     
    // Кому
    $mail->addAddress('MAIL');
     
    // Тема письма
    $mail->Subject = 'Обновление минимальных цен ИЭК ' . date('d.m.Y');
     
    // Тело письма
    $body = '<p><strong>Обновление минимальных цен ИЭК ' . date('d.m.Y') . '</strong></p>';

    // прикрепление файла
    if (!empty($data_barcode_iek))
    {
        $mail->addAttachment('Reports/Несовпадение штрихкодов ИЭК ' . date('d.m.Y') .'.xlsx'); // Добавляем вложение
    }
    if (!empty($data_mrc_iek))
    {
        $mail->addAttachment('Reports/Обновление минимальных цен ИЭК ' . date('d.m.Y') .'.xlsx'); // Добавляем вложение
    }
    if (!empty($data_notart_iek))
    {
        $mail->addAttachment('Reports/Несовпадения артикула ИЭК ' . date('d.m.Y') .'.xlsx'); // Добавляем вложение
    }
    if (!empty($data_excel_rrc))
    {
        $mail->addAttachment('Reports/Несовпадение розничной цены ИЭК ' . date('d.m.Y') .'.xlsx'); // Добавляем вложение
    }

    $mail->msgHTML($body);
    
    // $mail->SMTPDebug = SMTP::DEBUG_CONNECTION; // вывод ошибок с соединением
    
    echo ($mail->send())?'<h3 style = "color: green">Письмо отправлено</h3>':'<h3 style = "color: red">Ошибка. Письмо не отправлено!</h3>';


    // Тема письма
    $mail->Subject = 'Обновление минимальных цен ТДМ ' . date('d.m.Y');

    // Тело письма
    $body = '<p><strong>Обновление минимальных цен ТДМ ' . date('d.m.Y') . '</strong></p>';

    $mail->clearAttachments();

    // прикрепление файла
    if (!empty($data_notart_tdm))
    {
        $mail->addAttachment('Reports/Несовпадения артикула ТДМ ' . date('d.m.Y') .'.xlsx'); // Добавляем вложение
    }
    if (!empty($data_mrc_tdm))
    {
        $mail->addAttachment('Reports/Обновление минимальных цен ТДМ ' . date('d.m.Y') .'.xlsx'); // Добавляем вложение
    }
    if (!empty($data_barcode))
    {
        $mail->addAttachment('Reports/Несовпадение штрихкодов ТДМ ' . date('d.m.Y') .'.xlsx'); // Добавляем вложение
    }

    $mail->msgHTML($body);
    
    // $mail->SMTPDebug = SMTP::DEBUG_CONNECTION; // вывод ошибок с соединением
    
    echo ($mail->send())?'<h3 style = "color: green">Письмо отправлено</h3>':'<h3 style = "color: red">Ошибка. Письмо не отправлено!</h3>';

$count_product = $count_product + 0;
echo '<h1>Количество измененных товаров ТДМ и ИЭК: ' . $count_product .   '</h1>';
echo 'Скрипт был выполнен за ' . round(microtime(true) - $start, 2) . ' секунд';
?>