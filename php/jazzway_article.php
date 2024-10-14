<?PHP
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

function api_ms($url, $type, $data = null) // для подключения к api мойсклад
{
	$user = "USER:PASSWORD";
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
    
    if ($headers['X-Ratelimit-Remaining'] <= 5)
    {
        sleep(3); // задержка 3 секунды
    }
	
    return json_decode($result);
}

$url_main_moysklad = "https://api.moysklad.ru/api/remap/1.2";
$url_product_moysklad = "/entity/product";
$get = 'GET'; $post = 'POST'; $put = 'PUT';
$limit = 1000; $offset = 0; $i = 1;

for ($offset = 0; $offset < $limit * $i; $offset += $limit) {
    $lim = "?limit=$limit"; //установка лимита
    $off = "&offset=$offset"; //установка смещения

    $json_moysklad = api_ms($url_main_moysklad . $url_product_moysklad . $lim . $off, $get);
    $size = $json_moysklad->meta->size;
    $i = $size / $limit;

    foreach ($json_moysklad->rows as $row)
    {
        $json_moysklad_id = api_ms($url_main_moysklad . $url_product_moysklad . '/' . $row->id, $get); //подключение к товарам по id
        $id_moysklad = $json_moysklad_id->id; //id Товара
        $art_moysklad = $json_moysklad_id->article; //артикул товара
        $name_product = $json_moysklad_id -> name; //имя продукта

        foreach ($json_moysklad_id->attributes as $attributes) //прохождение по атрибутам
        {
            $name_attribute = $attributes->name; //получение имени атрибута (например 'Полное наименование', 'Торговая марка.')
            if ($name_attribute == 'Торговая марка.')
            {
                $value = $attributes->value->name;  //получение значения атрибута у Торговой марки., нужен JAZZWAY
                
                if($value == 'JAZZWAY' && $art_moysklad[0]!= '.')
                {
                    echo "Название: " . $name_product . "<br>" .
                        "Артикул: " . $art_moysklad . "<br>";

                    $new_art_moysklad = "." . $art_moysklad;
                    echo "<b>Новый артикул: " . $new_art_moysklad . "</b><br><br>";

                    $data = ["article" => $new_art_moysklad]; //массив для изменения минимальной цены
                    $data_string = json_encode($data);

                    $json_post_article = api_ms($url_main_moysklad . $url_product_moysklad . '/' . $row->id, 'PUT', $data_string); //обновление минимальной цены
                }
            }
        }
    }
}

echo '<br><b>Скрипт был выполнен за ' . round(microtime(true) - $start, 2) . ' секунд</b>';
?>