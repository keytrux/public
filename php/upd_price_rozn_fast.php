<?php
require_once 'PHPMailer/PHPMailer.php';
require_once 'PHPMailer/SMTP.php';
require_once 'PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

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
$start = microtime(true);

function api($type, $url, $filter = "", $data = null) 
{
    $user = "USER:PASSWORD";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, "https://api.moysklad.ru/api/remap/1.2" . $url . $filter);
    curl_setopt($curl, CURLOPT_USERPWD, $user);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $type);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', "Accept-Encoding: gzip"));
    if (!empty($data)) curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
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
        if (count($headerParts) === 2) {
            $headerName = $headerParts[0];
            $headerValue = $headerParts[1];
            // Добавляем заголовок в массив
            $headers[$headerName] = $headerValue;
        }
    }
	// print_r($headers);
    curl_close($curl);
    
	// echo " " . $headers['X-RateLimit-Remaining'] . ";";
    if ($headers['X-RateLimit-Remaining'] <= 10)
    {
        sleep(3);
		// echo " malo<br>";
    }
	
    return json_decode($result);
}

$string = "<table><tr><th>Код</th><th>Название</th><th>Цена</th></tr>";

$array2 = array();

$json_stock = api("GET", "/report/stock/all/current", "");
$assortmentId = array();
for($i = 0; $i < count($json_stock); $i++)
{
	$json2 = api("GET", "/entity/product", "/" . $json_stock[$i]->assortmentId); //подключение к товару по id
    if ($json2->errors[0]->code) //для вывода ошибок
	{
		
		echo json_encode($json2->errors[0]);
        file_put_contents("log.txt", date('Y-m-d H:i:s') . " " . $method . " product-id ". $json2->errors[0]->error . " " . $json2->errors[0]->code . PHP_EOL, FILE_APPEND);
		exit;
	}

    foreach ($json2->attributes as $attrib) 
    {
        if ($attrib->id == "837f98bc-a515-11ec-0a80-0dbe000b4545" && $attrib->value == true) continue 2;
        //если товар не акционный то переходим дальше
    }
    foreach ($json2->salePrices as $cens)
    {
        if ($cens->priceType->id == "17888677-63bf-11ec-0a80-01780035d918") //поиск розничной цены
        {
            $cena_rozn = $cens->value; //запись розничной цены в переменную
        }
        elseif ($cens->priceType->id != "7afb2d43-8281-11ec-0a80-0e140042752e" & 
                $cens->priceType->id != "52557f2d-827c-11ec-0a80-09a40040f2f1" & $cens->value != $cena_rozn)
        //если это не старая цена и не 2-ая оптовая и не равна розничной цене
        {
            $array2[] = array($json2->id, $json2->code, $json2->name, $cena_rozn); //запись данных в массив
            $array_sort = array_unique($array2, SORT_REGULAR);
        }
    }
}

if (!empty($array_sort))
{
	foreach ($array_sort as $tovar) {
		$array1 = array();
		$string .= "<tr><td>" . $tovar[1] . "</td><td>" . $tovar[2] .  "</td><td align=\"right\">" . $tovar[3] * 0.01 . "</td></tr>";
		$array1['meta'] = array(
			"href" => "https://api.moysklad.ru/api/remap/1.2/entity/product/" . $tovar[0],
			"metadataHref" => "https://api.moysklad.ru/api/remap/1.2/entity/product/metadata",
			"type" => "product",
			"mediaType" => "application/json"
		);
		foreach ($json2->salePrices as $cens) {
			if (strpos($cens->priceType->name, "Ц ") !== false) 
			{
				$array1['salePrices'][] = array(
					"value" => $tovar[3],
					"priceType" => array(
						"meta" => array(
							"href" => $cens->priceType->meta->href,
							"type" => "pricetype",
							"mediaType" => "application/json"
						)
					)
				);    
			}
		}
		$array_cens_roz[] = $array1; //массив для отправки 
	}
	
	$json_post = api("POST", "/entity/product/", "", $array_cens_roz); // отправка запроса POST

	$json = api ("GET", "/entity/store");
	foreach ($json->rows as $sklad) {
		if (!$sklad->attributes) continue;
		foreach ($sklad->attributes as $attrib) {
			if ($attrib->id == "25347e79-adc1-11ed-0a80-044800048989") $email[] .= $attrib->value;
		}
	}
	$email[] .= 'mail'; 

	$string .= "</table>";
	echo $string;
	$string .= "<span style=\"font-size: 12px;\">сообщение отправлено автоматически</span>";

	$mail = new PHPMailer;
	$mail->CharSet = 'UTF-8';
		
	// Настройки SMTP
	$mail->isSMTP();
	$mail->SMTPAutoTLS = false;
	$mail->SMTPSecure = false;

	$mail->Host = 'host';
	$mail->Port = 'port';
	$mail->Username = 'user';
	$mail->Password = 'password';
		
	// От кого
	$mail->setFrom('mail', '');		
		
	//Кому
	foreach($email as $mail_all)
	{
		$mail->addAddress($mail_all);
	}
	// Тема письма
	$mail->Subject = 'Обновление цен ' . date('d.m.Y');

	// Тело письма
	$body = "<html><head></head><body>" . $string . "</body></html>";
		
	$mail->msgHTML($body);
		
	// $mail->SMTPDebug = SMTP::DEBUG_CONNECTION; // вывод ошибок с соединением
		
	echo ($mail->send())?'<h3>Письмо отправлено</h3>':'<h3>Ошибка. Письмо не отправлено!</h3>';
}

echo "Время выполнения скрипта: " . round(microtime(true) - $start, 2)." сек.";
?>