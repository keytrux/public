<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Личный кабинет");
?>
<script>
BXMobileApp.UI.Page.TopBar.title.show();
BXMobileApp.UI.Page.TopBar.title.setText('НПО Тамара');
BXMobileApp.UI.Page.TopBar.title.setDetailText('Дисконтная карта');
BXMobileApp.UI.Page.TopBar.title.setImage('<?=SITE_DIR?>MobileTamara/logo.png');

</script>

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
	$user = "nosovav@npotamara:HcZCDw";
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

$json1 = api_ms("https://api.moysklad.ru/api/remap/1.2/report/counterparty?filter=counterparty.phone=89027418708", 'GET');


$json = api_ms("https://api.moysklad.ru/api/remap/1.2/entity/counterparty/" . $json1->rows[0]->counterparty->id, 'GET');

echo 'Номер ДК ' . $json->discountCardNumber . '<br>';
echo 'Скидка ' . $json->discounts[0]->accumulationDiscount . '%';

?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>