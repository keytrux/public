<?
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Карта");
global $USER;
if(!$USER->IsAuthorized()) header("Location: /personal/");
$rsUser = CUser::GetByID($USER->GetID());
$arUser = $rsUser->Fetch();
$text = "725745625814520468?";

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

    return json_decode($result);
}
echo 'f' . $arResult["arUser"]["UF_LOYALITY_CARD"];
$json = api_ms("https://api.moysklad.ru/api/remap/1.2/entity/counterparty?search=" . 'DK0000001', 'GET');


	echo 'Номер ДК ' .$json->rows[0]->discountCardNumber . '<br>';
	if(!empty($json->rows[0]->discounts[0]->accumulationDiscount))
		{
			echo 'Скидка ' . $json->rows[0]->discounts[0]->accumulationDiscount . '%';
		}
		elseif(!empty($json->rows[0]->discounts[0]->personalDiscount))
		{
			echo 'Скидка ' . $json->rows[0]->discounts[0]->personalDiscount . '%';
		}

?>

<!--
<script src="JsBarcode.all.js"></script>
<div style="text-align: center;">
	<svg id="generationCode128" class="barcode" jsbarcode-format="CODE128" jsbarcode-value="<?=$text; ?>" jsbarcode-textmargin="0" jsbarcode-fontoptions="0"></svg>
</div>
<script>JsBarcode("#generationCode128").init();</script>-->
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>