<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('memory_limit', '1536M');
ini_set('max_execution_time', '43200'); 
error_reporting(E_WARNING);
$start = microtime(true);
$mysqli = mysqli_connect("DATA", "DATA", "DATA", "DATA");
if (!$mysqli) exit;

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
    print_r($headers['x-ratelimit-remaining']); echo ", ";
    curl_close($curl);
    if ($headers['x-ratelimit-remaining'] <= 10)
    {
        sleep(3); //задержка 3 секунды
    }
    return json_decode($result);
}

$array_main = array();
$id_main = array();
$personal_phone_main = array();
$loyality_card_main = array();
$discount_main = array();

$main_user = $mysqli->query("SELECT ID, PERSONAL_PHONE FROM b_user LIMIT 200 OFFSET 1100");
while($row = $main_user->fetch_assoc()) {
    $id_main[] = $row['ID'];
    $personal_phone_main[] = $row['PERSONAL_PHONE'];
}



$ph = array();
foreach ($personal_phone_main as $phone_ms) {
    if(!empty($phone_ms))
    {
        if (substr($phone_ms, 0, 1) == '7' || substr($phone_ms, 0, 2) == '+7') {
            $newPhone_ms = substr_replace($phone_ms, '8', 0, 1); // заменяем первый символ на '8'
            $ph[] = $newPhone_ms;
        }
        elseif (substr($phone_ms, 0, 1) == '8') {
            $newPhone_ms = $phone_ms;
            $ph[] = $newPhone_ms;
        }
        $json_phone = api_ms("https://api.moysklad.ru/api/remap/1.2/entity/counterparty?filter=phone=" . $newPhone_ms, 'GET');
        if(!empty($json_phone->rows[0]->discountCardNumber))
        {
            $discount = "";

            //echo "<pre>"; print_r($json_phone->rows[0]); echo "</pre>";

            echo "count discount: " . count($json_phone->rows[0]->discounts);

            
            if (!empty($json_phone->rows[0]->discounts)) 
            {
                for($i = 0; $i < count($json_phone->rows[0]->discounts); $i++)
                {
                    $json_meta = api_ms($json_phone->rows[0]->discounts[$i]->discount->meta->href, 'GET');

                    if(!empty($json_phone->rows[0]->discounts[$i]->accumulationDiscount) && $json_phone->rows[0]->discounts[$i]->accumulationDiscount > $discount && 
                        $json_phone->rows[0]->discounts[$i]->accumulationDiscount > $json_phone->rows[0]->discounts[$i]->personalDiscount)
                    {
                        if($json_meta->name == "Индивидуальная скидка" || $json_meta->name == "Юридические лиц" || $json_meta->name == "Физические лица")
                        {
                            $discount = $json_phone->rows[0]->discounts[$i]->accumulationDiscount;
                        }
                    }
                    elseif(!empty($json_phone->rows[0]->discounts[$i]->personalDiscount) && $json_phone->rows[0]->discounts[$i]->personalDiscount > $discount &&
                            $json_phone->rows[0]->discounts[$i]->personalDiscount > $json_phone->rows[0]->discounts[$i]->accumulationDiscount)
                    {
                        if($json_meta->name == "Индивидуальная скидка" || $json_meta->name == "Юридические лиц" || $json_meta->name == "Физические лица")
                        {
                            $discount = $json_phone->rows[0]->discounts[$i]->personalDiscount;
                        }
                    }
                }
            }
            $loyality_card[] = $json_phone->rows[0]->discountCardNumber;
            $discount_main[] = $discount;
        }
        else 
        {
            $loyality_card[] = "";
            $discount_main[] = "";
        }
        
    }
    else 
    {
        $ph[] = "";
        $loyality_card[] = "";
        $discount_main[] = "";
    }
}

$array_main = array(
    'id' => $id_main,
    'personal_phone' => $ph,
    'loyality_card' => $loyality_card,
    'discount_main' => $discount_main
);



echo "count: " . count($array_main['id']);

echo "<pre>"; print_r($array_main); echo "</pre>";



//for($i = 0; $i < count($array_main['id']); $i++)
//{
    ////$upd_main = $mysqli->query("UPDATE b_uts_user SET `UF_LOYALITY_CARD` =  '" . $array_main['loyality_card'][$i] . "' WHERE VALUE_ID = " . $array_main['id'][$i]);
//}

//echo "<pre>"; print_r($array_main['id'][0]); echo "</pre>";











// $phone = $mysqli->query("SELECT ID, PERSONAL_PHONE FROM b_user LIMIT 10 OFFSET 279 ");
// //$array = array();
// $id = array();
// $personal_phone = array();
// $loyality_card = array();
// while($row = $phone->fetch_assoc()) {
//     //if($row['ID'] != "7832")
//     //{
//         $id[] = $row['ID'];
//         $personal_phone[] = $row['PERSONAL_PHONE'];
       
//         //echo "ID: " . $row['ID'] . " PERSONAL_PHONE: " . $row['PERSONAL_PHONE'] . "<br>";
//         //break;
//     //}
// }


// $result = $mysqli->query("SELECT VALUE_ID, UF_LOYALITY_CARD, UF_DISCOUNT FROM `b_uts_user`");

// while($row = $result->fetch_assoc()) {
//     if($row['VALUE_ID'] == "7832")
//     {
//         //echo "VALUE_ID: " . $row['VALUE_ID'] . " - UF_LOYALITY_CARD: " . $row['UF_LOYALITY_CARD'] . " UF_DISCOUNT: " . $row['UF_DISCOUNT'] . "<br>";
//       //  break;
//     }
    
// }

// $ph = array();
// foreach ($personal_phone as $phone_ms) {
//     if(!empty($phone_ms))
//     {
//         if (substr($phone_ms, 0, 1) == '7' || substr($phone_ms, 0, 2) == '+7') {
//             $newPhone_ms = substr_replace($phone_ms, '8', 0, 1); // заменяем первый символ на '8'
//             $ph[] = $newPhone_ms;
//         }
//         elseif (substr($phone_ms, 0, 1) == '8') {
//             $newPhone_ms = $phone_ms;
//             $ph[] = $newPhone_ms;
//         }
//         $json_phone = api_ms("https://api.moysklad.ru/api/remap/1.2/entity/counterparty?filter=phone=" . $newPhone_ms, 'GET');
//         $loyality_card[] = $json_phone->rows[0]->discountCardNumber;
//     }
//     else 
//     {
//         $loyality_card[] = "";
//         $ph[] = "";
//     }
// }
// $array = array(
//     'id' => $id,
//     'personal_phone' => $personal_phone,
//     'loyality_card' => $loyality_card
// );
// echo count($array['id']);
// echo "<pre>"; print_r($array); echo "</pre>";
// //echo "<pre>"; print_r($id); echo "</pre>";
// //echo "<pre>"; print_r($ph); echo "</pre>";
// //echo "<pre>"; print_r($personal_phone); echo "</pre>";
// echo "<pre>"; print_r($loyality_card); echo "</pre>";


echo "Время выполнения скрипта: " . round(microtime(true) - $start, 4) . " сек.";
?>