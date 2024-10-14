<?php
AddEventHandler("sale", "OnOrderNewSendEmail", "bxModifySaleMails");
function bxModifySaleMails($orderID, &$eventName, &$arFields){
	if (CModule::IncludeModule("sale") && CModule::IncludeModule("iblock")){
		global $DB;
		$order = \Bitrix\Sale\Order::load($orderID);
		$paymentId = $order->getPaymentSystemId();
		$deliveryId = $order->getDeliverySystemId();
		$basketArray = $order->getBasket();
		$propertyCollection = $order->getPropertyCollection();
		$arrayProperty = $propertyCollection->getArray();
		
		/*Список заказа*/
		$strCustomOrderList = "<table style='width: 100%'>";
		$strCustomOrderList .= "<tr><td>Код</td><td>Название</td><td>К-во</td><td>Цена</td><td>Сумма</td><td>Валюта</td></tr>";
		$itogo = 0;
		foreach($basketArray as $basketItem)
		{
			$quantity = $basketItem->getField('QUANTITY');
			$price = $basketItem->getField('PRICE') * 1;
			$cod = ""; $cod2 = "";
			$res = CIBlockElement::GetProperty($basketItem->getField('CATALOG_XML_ID'), $basketItem->getField('PRODUCT_ID'), "SORT", "ASC", array("CODE" => "kod_tovars"));
			if ($ob = $res->GetNext())
			{
				$cod2 = $ob['VALUE'];
				for($i = strlen($cod2); $i < 8; $i++)
				{
					$cod .= "0";
				}
			}
			
			if ($price > 0)
			{
				$summ = $basketItem->getField('PRICE') * $basketItem->getField('QUANTITY');
				$itogo += $summ;
			}
			else
			{
				$price = 'Под заказ';
				$summ = 'Под заказ';
				$itogo += 0;
			}
			$strCustomOrderList .= "<tr><td>" . $cod . $cod2 . "</td><td><a href='" . $basketItem->getField('DETAIL_PAGE_URL') . "'>" . $basketItem->getField('NAME') . "</a></td><td>" . round($basketItem->getField('QUANTITY'), 2) . "</td><td>" . $price . "</td><td>" . $summ . "</td><td>" . $basketItem->getField('CURRENCY') . "</td></tr>";
		}
		if ($itogo == 0)
		{
			$itogo = 'Под заказ';
		}
		$strCustomOrderList .= "<tr><td colspan='4'>Итого</td><td>" . $itogo . "</td><td>" . $order->getField('CURRENCY') . "</td></tr>";
		$strCustomOrderList .= "</table>";
		$arFields["CUSTOM_ORDER_LIST"] = $strCustomOrderList;
		
		/*Поолучаем все поля из заказа*/
		$arFields["KONTAKTY"] = "";
		foreach($arrayProperty['properties'] as $item)
		{
			$arFields["KONTAKTY"] .= $item['NAME'] . ":";
			foreach($item['VALUE'] as $value)
			{
				$arFields["KONTAKTY"] .= " " . $value;
			}
			$arFields["KONTAKTY"] .= "<br>";
			if ($item["IS_PHONE"] == "Y") $phone = $value;
		}
		/*Служба доставки*/
		$arrayDelivery = \Bitrix\Sale\Delivery\Services\Manager::getById($deliveryId[0]);
		switch($arrayDelivery['ID'])
		{
			case 2:
				$res_1 = $DB->query("SELECT * FROM `b_sale_order_delivery` WHERE `ACCOUNT_NUMBER` = '" . $order->getField('ACCOUNT_NUMBER') . "/2'");
				$row_1 = $res_1->Fetch();
				$res_2 = $DB->query("SELECT * FROM `b_sale_order_delivery_es` WHERE `SHIPMENT_ID` = " . $row_1['ID']);
				$row_2 = $res_2->Fetch();
				if (!empty($row_2['VALUE']))
				{
					$storeID = $row_2['VALUE'];
					$arrayCatalogStore = CCatalogStore::GetList(array("ID" => "ASC"), array("ID" => $storeID));
					if ($store = $arrayCatalogStore->Fetch())
					{
						$store_title = $store['TITLE'];
						$store_address = $store['ADDRESS'];
						$store_schedule = $store['SCHEDULE'];
						$store_phone = $store['PHONE'];
						$store_email = $store['EMAIL'];
						$arFields["STORE_EMAIL"] = $store_email;
						if ($storeID == 1)
						{
							$style = 'color: #007161';
							$storeKassa = '<br/><span style="color: #ff1513">Касса работает с 8:00 до 15:30, перерыв с 12:00 до 12:45.</span>';
						}
						else
						{
							$style = '';
							$storeKassa = '';
						}
					}
				}
				$arFields["CUSTOM_ORDER_STORE"] = "<p>" . $arrayDelivery['NAME'] . "<br/><span style='" . $style . "'>" . $store_title . " (" . $store_address . ")</span>" . $storeKassa . "<br />" . $store_schedule . "<br />" . $store_phone . "</p>";
				$arFields["INFO"] = '<p>Ваш заказ сформирован и отправлен в магазин. Вы можете забрать заказ в любой удобный для Вас рабочий день. Если заказанный Вами товар отсутствует в выбранном Вами магазине, Вам перезвонят в первый рабочий день после получения заказа и сообщат о сроках его поставки.</p>
				<p>Итоговая стоимость рассчитывается и может отличаться в момент оплаты заказа в магазине.</p>
				<p>Скидка оформляется при оплате товара в магазинах розничной сети "Тамара".</p>
				<p><b>ВАЖНО</b> Скидка за заказ на сайте не суммируется с действующей системой скидок по дисконтным картам и сезонными скидками.</p>
				<p>В случае наличия у клиента дисконтной карты - большая скидка исключает меньшую.</p>';
				$message_sms = 'Вами оформлен заказ. Номер заказа:' . $order->getField('ACCOUNT_NUMBER');
				break;
			case 3:
				$storeID = 1;
				$storeKassa = '<br/><span style="color: #ff1513">Касса работает с 8:00 до 15:30, перерыв с 12:00 до 12:45.</span>';
				$arFields["CUSTOM_ORDER_STORE"] = "<p><span style='color: #007161'>" . $arrayDelivery['NAME'] ."</span>" . $storeKassa . "<br/>" . $arrayDelivery['DESCRIPTION'] . "</p>";
				$arFields["INFO"] = '<p>Менеджер свяжется с Вами и уточнит время доставки вашего заказа.</p>';
				$message_sms = 'Вами оформлен заказ. Номер заказа:' . $order->getField('ACCOUNT_NUMBER');
				break;
			case 5:
				$storeID = 1;
				$full_price = $arrayDelivery['CONFIG']['MAIN']['PRICE'] . " " . $arrayDelivery['CONFIG']['MAIN']['CURRENCY'];
				$arFields["CUSTOM_ORDER_STORE"] = "<p><span style='color: #007161'>" . $arrayDelivery['NAME'] . "</span><br/>Стоимость: <i>" . $full_price . "</i><br/>Адрес доставки: " . $delivery_adres . "</p>";
				$arFields["INFO"] = '<p>Менеджер свяжется с Вами и уточнит время доставки вашего заказа.</p>';
				$message_sms = 'Вами оформлен заказ. Номер заказа:' . $order->getField('ACCOUNT_NUMBER');
				break;
			default:
				$storeID = NULL;
				$price = $arrayDelivery['CONFIG']['MAIN']['PRICE'];
				$full_price = $arrayDelivery['CONFIG']['MAIN']['PRICE'] . " " . $arrayDelivery['CONFIG']['MAIN']['CURRENCY'];
				$arFields["CUSTOM_ORDER_STORE"] = "<p>" . $arrayDelivery['NAME'] . "<br/>" . $arrayDelivery['DESCRIPTION'] . (!empty($price) ? "<br/>Стоимость: <i>" . $full_price . "</i>": "") . "</p>";
				$arFields["INFO"] = '<p>Менеджер свяжется с Вами и уточнит время доставки вашего заказа.</p>';
				$message_sms = 'Вами оформлен заказ. Номер заказа:' . $order->getField('ACCOUNT_NUMBER');
			break;
			
		}
		/*Платежная система*/
		$arrayPay = \Bitrix\Sale\PaySystem\Manager::getById($paymentId[0]);
		$strCustomOrderPay = $arrayPay['NAME'];
		$arFields["CUSTOM_ORDER_PAY"] = $strCustomOrderPay;
		
		$arFields["COMMENT"] = !empty($order->getField('USER_DESCRIPTION')) ? "<p><b>Комментарий к заказу:</b><br/>" . $order->getField('USER_DESCRIPTION') . "</p>": "";
		$arFields["PRICE"] = $order->getField('PRICE') . ' ' . $order->getField('CURRENCY');
		
		/*Fix*/
		$DB->query("UPDATE `b_sale_order_delivery` SET `COMPANY_ID` = '" . $storeID . "', `RESPONSIBLE_ID` = '1' WHERE `ACCOUNT_NUMBER` = '" . $order->getField('ACCOUNT_NUMBER') . "/2'");
		$DB->query("UPDATE `b_sale_order_payment` SET `COMPANY_ID` = '" . $storeID . "', `RESPONSIBLE_ID` = '1' WHERE `ACCOUNT_NUMBER` = '" . $order->getField('ACCOUNT_NUMBER') . "/1'");
		$DB->query("UPDATE `b_sale_order` SET `STORE_ID` = '" . $storeID . "', `COMPANY_ID` = '" . $storeID . "', `RESPONSIBLE_ID` = '1' WHERE `ACCOUNT_NUMBER` = '" . $order->getField('ACCOUNT_NUMBER') . "'");
		
		/*Рассылка СМС сообщений*/
		if (include_once $_SERVER['DOCUMENT_ROOT'] . "/shop/smsc_api.php"){
			if (strlen($phone) == 11) send_sms($phone, $message_sms, 0, 0, 0, 0);
		}
	}
}


AddEventHandler("main", "OnBeforeUserUpdate", Array("MyClass", "OnBeforeUserUpdateHandler"));
class MyClass
{
	public static function OnBeforeUserUpdateHandler(&$arFields)
	{
		
		function api_ms3($url, $type, $data = null) // для подключения к api мойсклад
		{
			$user = "user:password";
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
			if ($headers['x-ratelimit-remaining'] <= 10)
			{
				sleep(3); //задержка 3 секунды
			}
			return json_decode($result);
		}
		if(isset($arFields["PERSONAL_PHONE"]) && strlen($arFields["PERSONAL_PHONE"]) > 0)
		{
			if(!empty($arFields["PERSONAL_PHONE"]))
			{
				$arFilter = array(
					"PERSONAL_PHONE" => $arFields["PERSONAL_PHONE"]
				);
				$rsUsers = CUser::GetList(($by="ID"), ($order="ASC"), $arFilter);
				if ($arUser = $rsUsers->Fetch()) 
				{
					if ($arUser["ID"] != $arFields["ID"]) 
					{
						if(isset($arFields["PERSONAL_PHONE"])) 
						{
							$arFields["PERSONAL_PHONE"] = "";
							global $APPLICATION;
							$APPLICATION->throwException("Номер телефона должен быть уникальным");
							return false;
						}
					}
				}
				$json_phone = api_ms3("https://api.moysklad.ru/api/remap/1.2/entity/counterparty?filter=phone=" . $arFields["PERSONAL_PHONE"], 'GET');
				$arFields["UF_LOYALITY_CARD"] = $json_phone->rows[0]->discountCardNumber;

				$str = "";
				foreach(str_split($json_phone->rows[0]->discountCardNumber) as $char)
				{
					if($char == "%")
					{
						$char = "%25";
					}
					elseif($char == "?")
					{
						$char = "%3F";
					}
					$str .= $char;
				}
				$discount = 0;
				$json = api_ms3("https://api.moysklad.ru/api/remap/1.2/entity/counterparty?filter=discountCardNumber=" . $str, 'GET');
				
				for($i = 0; $i < count($json->rows[0]->discounts); $i++)
				{
					$json_meta = api_ms3($json->rows[0]->discounts[$i]->discount->meta->href, 'GET');

					if(!empty($json->rows[0]->discounts[$i]->accumulationDiscount) && $json->rows[0]->discounts[$i]->accumulationDiscount > $discount && 
						$json->rows[0]->discounts[$i]->accumulationDiscount > $json->rows[0]->discounts[$i]->personalDiscount)
					{
						if($json_meta->name == "Индивидуальная скидка" || $json_meta->name == "Юридические лиц" || $json_meta->name == "Физические лица")
						{
							$discount = $json->rows[0]->discounts[$i]->accumulationDiscount;
						}
					}
					elseif(!empty($json->rows[0]->discounts[$i]->personalDiscount) && $json->rows[0]->discounts[$i]->personalDiscount > $discount &&
							$json->rows[0]->discounts[$i]->personalDiscount > $json->rows[0]->discounts[$i]->accumulationDiscount)
					{
						if($json_meta->name == "Индивидуальная скидка" || $json_meta->name == "Юридические лиц" || $json_meta->name == "Физические лица")
						{
							$discount = $json->rows[0]->discounts[$i]->personalDiscount;
						}
					}
				}
				$arFields["UF_DISCOUNT"] = $discount;
				global $USER;
				if($discount == 30)
				{
					$arGroups = $USER->GetUserGroupArray();
					$groupsToRemove = [22, 23, 24, 25, 26, 27, 28];
					foreach ($groupsToRemove as $groupID) {
						$key = array_search($groupID, $arGroups);
						if ($key !== false) {
							unset($arGroups[$key]);
						}
					}
					CUser::SetUserGroup($arFields["ID"], $arGroups);

					CUser::SetUserGroup($arFields["ID"], array_merge(CUser::GetUserGroup($arFields["ID"]), array(21)));
				}
				elseif($discount == 20)
				{
					$arGroups = $USER->GetUserGroupArray();
					$groupsToRemove = [21, 23, 24, 25, 26, 27, 28];
					foreach ($groupsToRemove as $groupID) {
						$key = array_search($groupID, $arGroups);
						if ($key !== false) {
							unset($arGroups[$key]);
						}
					}
					CUser::SetUserGroup($arFields["ID"], $arGroups);

					CUser::SetUserGroup($arFields["ID"], array_merge(CUser::GetUserGroup($arFields["ID"]), array(22)));
				}
				elseif($discount == 15)
				{
					$arGroups = $USER->GetUserGroupArray();
					$groupsToRemove = [21, 22, 24, 25, 26, 27, 28];
					foreach ($groupsToRemove as $groupID) {
						$key = array_search($groupID, $arGroups);
						if ($key !== false) {
							unset($arGroups[$key]);
						}
					}
					CUser::SetUserGroup($arFields["ID"], $arGroups);

					CUser::SetUserGroup($arFields["ID"], array_merge(CUser::GetUserGroup($arFields["ID"]), array(23)));
				}
				elseif($discount == 12)
				{
					$arGroups = $USER->GetUserGroupArray();
					$groupsToRemove = [21, 22, 23, 25, 26, 27, 28];
					foreach ($groupsToRemove as $groupID) {
						$key = array_search($groupID, $arGroups);
						if ($key !== false) {
							unset($arGroups[$key]);
						}
					}
					CUser::SetUserGroup($arFields["ID"], $arGroups);

					CUser::SetUserGroup($arFields["ID"], array_merge(CUser::GetUserGroup($arFields["ID"]), array(24)));
				}
				elseif($discount == 10)
				{
					$arGroups = $USER->GetUserGroupArray();
					$groupsToRemove = [21, 22, 23, 24, 26, 27, 28];
					foreach ($groupsToRemove as $groupID) {
						$key = array_search($groupID, $arGroups);
						if ($key !== false) {
							unset($arGroups[$key]);
						}
					}
					CUser::SetUserGroup($arFields["ID"], $arGroups);

					CUser::SetUserGroup($arFields["ID"], array_merge(CUser::GetUserGroup($arFields["ID"]), array(25)));
				}
				elseif($discount == 7)
				{
					$arGroups = $USER->GetUserGroupArray();
					$groupsToRemove = [21, 22, 23, 24, 25, 27, 28];
					foreach ($groupsToRemove as $groupID) {
						$key = array_search($groupID, $arGroups);
						if ($key !== false) {
							unset($arGroups[$key]);
						}
					}
					CUser::SetUserGroup($arFields["ID"], $arGroups);

					CUser::SetUserGroup($arFields["ID"], array_merge(CUser::GetUserGroup($arFields["ID"]), array(26)));
				}
				elseif($discount == 5)
				{
					$arGroups = $USER->GetUserGroupArray();
					$groupsToRemove = [21, 22, 23, 24, 25, 26, 28];
					foreach ($groupsToRemove as $groupID) {
						$key = array_search($groupID, $arGroups);
						if ($key !== false) {
							unset($arGroups[$key]);
						}
					}
					CUser::SetUserGroup($arFields["ID"], $arGroups);

					CUser::SetUserGroup($arFields["ID"], array_merge(CUser::GetUserGroup($arFields["ID"]), array(27)));
				}
				elseif($discount == 3)
				{
					$arGroups = $USER->GetUserGroupArray();
					$groupsToRemove = [21, 22, 23, 24, 25, 26, 27];
					foreach ($groupsToRemove as $groupID) {
						$key = array_search($groupID, $arGroups);
						if ($key !== false) {
							unset($arGroups[$key]);
						}
					}
					CUser::SetUserGroup($arFields["ID"], $arGroups);

					CUser::SetUserGroup($arFields["ID"], array_merge(CUser::GetUserGroup($arFields["ID"]), array(28)));
				}
				else
				{
					$arGroups = $USER->GetUserGroupArray();
					$groupsToRemove = [21, 22, 23, 24, 25, 26, 27, 28];
					foreach ($groupsToRemove as $groupID) {
						$key = array_search($groupID, $arGroups);
						if ($key !== false) {
							unset($arGroups[$key]);
						}
					}
					CUser::SetUserGroup($arFields["ID"], $arGroups);
				}
				//echo "phone";
			}
			return true;
		}
		elseif (isset($arFields["UF_LOYALITY_CARD"]) && strlen($arFields["UF_LOYALITY_CARD"]) > 0)
		{
			if(!empty($arFields["UF_LOYALITY_CARD"]))
			{
				$arFilter = array(
					"UF_LOYALITY_CARD" => $arFields["UF_LOYALITY_CARD"]
				);
				$rsUsers = CUser::GetList(($by="ID"), ($order="ASC"), $arFilter);
				if ($arUser = $rsUsers->Fetch()) 
				{
					if ($arUser["ID"] != $arFields["ID"]) 
					{
						if(isset($arFields["UF_DISCOUNT"]) || isset($arFields["UF_LOYALITY_CARD"])) 
						{
							$arFields["UF_DISCOUNT"] = "";
							$arFields["UF_LOYALITY_CARD"] = "";
							global $APPLICATION;
							$APPLICATION->throwException("Номер карты должен быть уникальным");
							return false;
						}
					}
				}
				$arFields["UF_DISCOUNT"] = "";
				global $USER;
				$arGroups = $USER->GetUserGroupArray();
				$groupsToRemove = [21, 22, 23, 24, 25, 26, 27, 28];
				foreach ($groupsToRemove as $groupID) {
					$key = array_search($groupID, $arGroups);
					if ($key !== false) {
						unset($arGroups[$key]);
					}
				}
				CUser::SetUserGroup($arFields["ID"], $arGroups);
	
				$str = "";
				foreach(str_split($arFields["UF_LOYALITY_CARD"]) as $char)
				{
					if($char == "%")
					{
						$char = "%25";
					}
					elseif($char == "?")
					{
						$char = "%3F";
					}
					$str .= $char;
				}
				$discount = 0;
				$json = api_ms3("https://api.moysklad.ru/api/remap/1.2/entity/counterparty?filter=discountCardNumber=" . $str, 'GET');
	
				for($i = 0; $i < count($json->rows[0]->discounts); $i++)
				{
				  $json_meta = api_ms3($json->rows[0]->discounts[$i]->discount->meta->href, 'GET');
	
				  if(!empty($json->rows[0]->discounts[$i]->accumulationDiscount) && $json->rows[0]->discounts[$i]->accumulationDiscount > $discount && 
					$json->rows[0]->discounts[$i]->accumulationDiscount > $json->rows[0]->discounts[$i]->personalDiscount)
				  {
					if($json_meta->name == "Индивидуальная скидка" || $json_meta->name == "Юридические лиц" || $json_meta->name == "Физические лица")
					{
						$discount = $json->rows[0]->discounts[$i]->accumulationDiscount;
					}
				  }
				  elseif(!empty($json->rows[0]->discounts[$i]->personalDiscount) && $json->rows[0]->discounts[$i]->personalDiscount > $discount &&
						$json->rows[0]->discounts[$i]->personalDiscount > $json->rows[0]->discounts[$i]->accumulationDiscount)
				  {
					if($json_meta->name == "Индивидуальная скидка" || $json_meta->name == "Юридические лиц" || $json_meta->name == "Физические лица")
					{
						$discount = $json->rows[0]->discounts[$i]->personalDiscount;
					}
				  }
				}
				//echo "loyal_card";
				$arFields["UF_DISCOUNT"] = $discount;
				global $USER;
			if($discount == 30)
			{
				$arGroups = $USER->GetUserGroupArray();
				$groupsToRemove = [22, 23, 24, 25, 26, 27, 28];
				foreach ($groupsToRemove as $groupID) {
					$key = array_search($groupID, $arGroups);
					if ($key !== false) {
						unset($arGroups[$key]);
					}
				}
				CUser::SetUserGroup($arFields["ID"], $arGroups);

				CUser::SetUserGroup($arFields["ID"], array_merge(CUser::GetUserGroup($arFields["ID"]), array(21)));
			}
			elseif($discount == 20)
			{
				$arGroups = $USER->GetUserGroupArray();
				$groupsToRemove = [21, 23, 24, 25, 26, 27, 28];
				foreach ($groupsToRemove as $groupID) {
					$key = array_search($groupID, $arGroups);
					if ($key !== false) {
						unset($arGroups[$key]);
					}
				}
				CUser::SetUserGroup($arFields["ID"], $arGroups);

				CUser::SetUserGroup($arFields["ID"], array_merge(CUser::GetUserGroup($arFields["ID"]), array(22)));
			}
			elseif($discount == 15)
			{
				$arGroups = $USER->GetUserGroupArray();
				$groupsToRemove = [21, 22, 24, 25, 26, 27, 28];
				foreach ($groupsToRemove as $groupID) {
					$key = array_search($groupID, $arGroups);
					if ($key !== false) {
						unset($arGroups[$key]);
					}
				}
				CUser::SetUserGroup($arFields["ID"], $arGroups);

				CUser::SetUserGroup($arFields["ID"], array_merge(CUser::GetUserGroup($arFields["ID"]), array(23)));
			}
			elseif($discount == 12)
			{
				$arGroups = $USER->GetUserGroupArray();
				$groupsToRemove = [21, 22, 23, 25, 26, 27, 28];
				foreach ($groupsToRemove as $groupID) {
					$key = array_search($groupID, $arGroups);
					if ($key !== false) {
						unset($arGroups[$key]);
					}
				}
				CUser::SetUserGroup($arFields["ID"], $arGroups);

				CUser::SetUserGroup($arFields["ID"], array_merge(CUser::GetUserGroup($arFields["ID"]), array(24)));
			}
			elseif($discount == 10)
			{
				$arGroups = $USER->GetUserGroupArray();
				$groupsToRemove = [21, 22, 23, 24, 26, 27, 28];
				foreach ($groupsToRemove as $groupID) {
					$key = array_search($groupID, $arGroups);
					if ($key !== false) {
						unset($arGroups[$key]);
					}
				}
				CUser::SetUserGroup($arFields["ID"], $arGroups);

				CUser::SetUserGroup($arFields["ID"], array_merge(CUser::GetUserGroup($arFields["ID"]), array(25)));
			}
			elseif($discount == 7)
			{
				$arGroups = $USER->GetUserGroupArray();
				$groupsToRemove = [21, 22, 23, 24, 25, 27, 28];
				foreach ($groupsToRemove as $groupID) {
					$key = array_search($groupID, $arGroups);
					if ($key !== false) {
						unset($arGroups[$key]);
					}
				}
				CUser::SetUserGroup($arFields["ID"], $arGroups);

				CUser::SetUserGroup($arFields["ID"], array_merge(CUser::GetUserGroup($arFields["ID"]), array(26)));
			}
			elseif($discount == 5)
			{
				$arGroups = $USER->GetUserGroupArray();
				$groupsToRemove = [21, 22, 23, 24, 25, 26, 28];
				foreach ($groupsToRemove as $groupID) {
					$key = array_search($groupID, $arGroups);
					if ($key !== false) {
						unset($arGroups[$key]);
					}
				}
				CUser::SetUserGroup($arFields["ID"], $arGroups);

				CUser::SetUserGroup($arFields["ID"], array_merge(CUser::GetUserGroup($arFields["ID"]), array(27)));
			}
			elseif($discount == 3)
			{
				$arGroups = $USER->GetUserGroupArray();
				$groupsToRemove = [21, 22, 23, 24, 25, 26, 27];
				foreach ($groupsToRemove as $groupID) {
					$key = array_search($groupID, $arGroups);
					if ($key !== false) {
						unset($arGroups[$key]);
					}
				}
				CUser::SetUserGroup($arFields["ID"], $arGroups);

				CUser::SetUserGroup($arFields["ID"], array_merge(CUser::GetUserGroup($arFields["ID"]), array(28)));
			}
			else
			{
				$arGroups = $USER->GetUserGroupArray();
				$groupsToRemove = [21, 22, 23, 24, 25, 26, 27, 28];
				foreach ($groupsToRemove as $groupID) {
					$key = array_search($groupID, $arGroups);
					if ($key !== false) {
						unset($arGroups[$key]);
					}
				}
				CUser::SetUserGroup($arFields["ID"], $arGroups);
			}
			}
			else {
				$arFields["UF_DISCOUNT"] = "";
			}
			return true;
		}
		else {
			$arFields["UF_DISCOUNT"] = "";
			global $USER;
				$arGroups = $USER->GetUserGroupArray();
				$groupsToRemove = [21, 22, 23, 24, 25, 26, 27, 28];
				foreach ($groupsToRemove as $groupID) {
					$key = array_search($groupID, $arGroups);
					if ($key !== false) {
						unset($arGroups[$key]);
					}
				}
				CUser::SetUserGroup($arFields["ID"], $arGroups);
		}
	  	return true;
  }
}

AddEventHandler("main", "OnBeforeUserRegister", "MyOnBeforeUserRegister");
function MyOnBeforeUserRegister(&$arFields)
{
    // Ваш код для обработки события OnBeforeUserRegister
	if (isset($arFields["UF_LOYALITY_CARD"]) && strlen($arFields["UF_LOYALITY_CARD"]) <= 0)
	{
		return true;
	}
	else
	{
		$arFilter = array(
			"UF_LOYALITY_CARD" => $arFields["UF_LOYALITY_CARD"]
		);
		$rsUsers = CUser::GetList(($by="ID"), ($order="ASC"), $arFilter);
		if ($arUser = $rsUsers->Fetch()) 
		{
			if ($arUser["ID"] != $arFields["ID"]) 
			{
				if(isset($arFields["UF_DISCOUNT"]) || isset($arFields["UF_LOYALITY_CARD"])) 
				{
					$arFields["UF_DISCOUNT"] = "";
					$arFields["UF_LOYALITY_CARD"] = "";
					global $APPLICATION;
					$APPLICATION->throwException("Номер карты должен быть уникальным.");
					return false;
				}
			}
		}
   	}
	function api_ms2($url, $type, $data = null) // для подключения к api мойсклад
	{
		$user = "user:password";
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
		if ($headers['x-ratelimit-remaining'] <= 10)
		{
			sleep(3);
		}
		return json_decode($result);
	}
	$discount = 0;
	if(!empty($arFields["UF_LOYALITY_CARD"]))
	{
		$str = "";
		foreach(str_split($arFields["UF_LOYALITY_CARD"]) as $char)
		{
			if($char == "%")
			{
				$char = "%25";
			}
			elseif($char == "?")
			{
				$char = "%3F";
			}
			$str .= $char;
		}
	
		$json = api_ms2("https://api.moysklad.ru/api/remap/1.2/entity/counterparty?filter=discountCardNumber=" . $str, 'GET');
	
		for($i = 0; $i < count($json->rows[0]->discounts); $i++)
		{
			$json_meta = api_ms2($json->rows[0]->discounts[$i]->discount->meta->href, 'GET');

			if(!empty($json->rows[0]->discounts[$i]->accumulationDiscount) && $json->rows[0]->discounts[$i]->accumulationDiscount > $discount && 
			$json->rows[0]->discounts[$i]->accumulationDiscount > $json->rows[0]->discounts[$i]->personalDiscount)
			{
				if($json_meta->name == "Индивидуальная скидка" || $json_meta->name == "Юридические лиц" || $json_meta->name == "Физические лица")
				{
					$discount = $json->rows[0]->discounts[$i]->accumulationDiscount;
				}
			}
			elseif(!empty($json->rows[0]->discounts[$i]->personalDiscount) && $json->rows[0]->discounts[$i]->personalDiscount > $discount &&
				$json->rows[0]->discounts[$i]->personalDiscount > $json->rows[0]->discounts[$i]->accumulationDiscount)
			{
				if($json_meta->name == "Индивидуальная скидка" || $json_meta->name == "Юридические лиц" || $json_meta->name == "Физические лица")
				{
					$discount = $json->rows[0]->discounts[$i]->personalDiscount;
				}
			}
		}
		$arFilter = array(
            "UF_LOYALITY_CARD" => $arResult["UF_LOYALITY_CARD"]
        );
		$arFields["UF_DISCOUNT"] = $discount;

		if($discount == 30)
		{
			$arFields["GROUP_ID"][] = 21;
		}
		elseif($discount == 20)
		{
			$arFields["GROUP_ID"][] = 22;
		}
		elseif($discount == 15)
		{
			$arFields["GROUP_ID"][] = 23;
		}
		elseif($discount == 12)
		{
			$arFields["GROUP_ID"][] = 24;
		}
		elseif($discount == 10)
		{
			$arFields["GROUP_ID"][] = 25;
		}
		elseif($discount == 7)
		{
			$arFields["GROUP_ID"][] = 26;
		}
		elseif($discount == 5)
		{
			$arFields["GROUP_ID"][] = 27;
		}
		elseif($discount == 3)
		{
			$arFields["GROUP_ID"][] = 28;
		}
	}
	else
	{
		$arFields["UF_DISCOUNT"] = "";
	}

	 return true;
}

AddEventHandler("main", "OnAfterUserAuthorize", "OnAfterUserAuthorizeHandler");
function OnAfterUserAuthorizeHandler(&$arUser)
{
	function api_ms4($url, $type, $data = null) // для подключения к api мойсклад
	{
		$user = "user:password";
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
		if ($headers['x-ratelimit-remaining'] <= 10)
		{
			sleep(3); //задержка 3 секунды
		}
		return json_decode($result);
	}	
	global $USER;
	$arResult['USER'] = \Bitrix\Main\UserTable::getRow([
		'select' => ['ID', 'NAME', 'LAST_NAME', 'UF_LOYALITY_CARD'],
		'filter' => ['ID' => $USER->GetID()],
	]);
	
	if(!empty($arUser['user_fields']['PERSONAL_PHONE']))
	{
		$json_phone = api_ms4("https://api.moysklad.ru/api/remap/1.2/entity/counterparty?filter=phone=" . $arUser['user_fields']['PERSONAL_PHONE'], 'GET');
		$loyality_card = $json_phone->rows[0]->discountCardNumber;

		$str = "";
		foreach(str_split($loyality_card) as $char)
		{
			if($char == "%")
			{
				$char = "%25";
			}
			elseif($char == "?")
			{
				$char = "%3F";
			}
			$str .= $char;
		}
		$discount = 0;
		$json = api_ms4("https://api.moysklad.ru/api/remap/1.2/entity/counterparty?filter=discountCardNumber=" . $str, 'GET');

		for($i = 0; $i < count($json->rows[0]->discounts); $i++)
		{
			$json_meta = api_ms4($json->rows[0]->discounts[$i]->discount->meta->href, 'GET');

			if(!empty($json->rows[0]->discounts[$i]->accumulationDiscount) && $json->rows[0]->discounts[$i]->accumulationDiscount > $discount && 
				$json->rows[0]->discounts[$i]->accumulationDiscount > $json->rows[0]->discounts[$i]->personalDiscount)
			{
				if($json_meta->name == "Индивидуальная скидка" || $json_meta->name == "Юридические лиц" || $json_meta->name == "Физические лица")
				{
					$discount = $json->rows[0]->discounts[$i]->accumulationDiscount;
				}
			}
			elseif(!empty($json->rows[0]->discounts[$i]->personalDiscount) && $json->rows[0]->discounts[$i]->personalDiscount > $discount &&
					$json->rows[0]->discounts[$i]->personalDiscount > $json->rows[0]->discounts[$i]->accumulationDiscount)
			{
				if($json_meta->name == "Индивидуальная скидка" || $json_meta->name == "Юридические лиц" || $json_meta->name == "Физические лица")
				{
					$discount = $json->rows[0]->discounts[$i]->personalDiscount;
				}
			}
		}
		if($discount == 30)
		{
			$arGroups = $USER->GetUserGroupArray();
			$groupsToRemove = [22, 23, 24, 25, 26, 27, 28];
			foreach ($groupsToRemove as $groupID) {
				$key = array_search($groupID, $arGroups);
				if ($key !== false) {
					unset($arGroups[$key]);
				}
			}
			CUser::SetUserGroup($USER->GetID(), $arGroups);

			CUser::SetUserGroup($USER->GetID(), array_merge(CUser::GetUserGroup($USER->GetID()), array(21)));
		}
		elseif($discount == 20)
		{
			$arGroups = $USER->GetUserGroupArray();
			$groupsToRemove = [21, 23, 24, 25, 26, 27, 28];
			foreach ($groupsToRemove as $groupID) {
				$key = array_search($groupID, $arGroups);
				if ($key !== false) {
					unset($arGroups[$key]);
				}
			}
			CUser::SetUserGroup($USER->GetID(), $arGroups);

			CUser::SetUserGroup($USER->GetID(), array_merge(CUser::GetUserGroup($USER->GetID()), array(22)));
		}
		elseif($discount == 15)
		{
			$arGroups = $USER->GetUserGroupArray();
			$groupsToRemove = [21, 22, 24, 25, 26, 27, 28];
			foreach ($groupsToRemove as $groupID) {
				$key = array_search($groupID, $arGroups);
				if ($key !== false) {
					unset($arGroups[$key]);
				}
			}
			CUser::SetUserGroup($USER->GetID(), $arGroups);

			CUser::SetUserGroup($USER->GetID(), array_merge(CUser::GetUserGroup($USER->GetID()), array(23)));
		}
		elseif($discount == 12)
		{
			$arGroups = $USER->GetUserGroupArray();
			$groupsToRemove = [21, 22, 23, 25, 26, 27, 28];
			foreach ($groupsToRemove as $groupID) {
				$key = array_search($groupID, $arGroups);
				if ($key !== false) {
					unset($arGroups[$key]);
				}
			}
			CUser::SetUserGroup($USER->GetID(), $arGroups);

			CUser::SetUserGroup($USER->GetID(), array_merge(CUser::GetUserGroup($USER->GetID()), array(24)));
		}
		elseif($discount == 10)
		{
			$arGroups = $USER->GetUserGroupArray();
			$groupsToRemove = [21, 22, 23, 24, 26, 27, 28];
			foreach ($groupsToRemove as $groupID) {
				$key = array_search($groupID, $arGroups);
				if ($key !== false) {
					unset($arGroups[$key]);
				}
			}
			CUser::SetUserGroup($USER->GetID(), $arGroups);

			CUser::SetUserGroup($USER->GetID(), array_merge(CUser::GetUserGroup($USER->GetID()), array(25)));
		}
		elseif($discount == 7)
		{
			$arGroups = $USER->GetUserGroupArray();
			$groupsToRemove = [21, 22, 23, 24, 25, 27, 28];
			foreach ($groupsToRemove as $groupID) {
				$key = array_search($groupID, $arGroups);
				if ($key !== false) {
					unset($arGroups[$key]);
				}
			}
			CUser::SetUserGroup($USER->GetID(), $arGroups);

			CUser::SetUserGroup($USER->GetID(), array_merge(CUser::GetUserGroup($USER->GetID()), array(26)));
		}
		elseif($discount == 5)
		{
			$arGroups = $USER->GetUserGroupArray();
			$groupsToRemove = [21, 22, 23, 24, 25, 26, 28];
			foreach ($groupsToRemove as $groupID) {
				$key = array_search($groupID, $arGroups);
				if ($key !== false) {
					unset($arGroups[$key]);
				}
			}
			CUser::SetUserGroup($USER->GetID(), $arGroups);

			CUser::SetUserGroup($USER->GetID(), array_merge(CUser::GetUserGroup($USER->GetID()), array(27)));
		}
		elseif($discount == 3)
		{
			$arGroups = $USER->GetUserGroupArray();
			$groupsToRemove = [21, 22, 23, 24, 25, 26, 27];
			foreach ($groupsToRemove as $groupID) {
				$key = array_search($groupID, $arGroups);
				if ($key !== false) {
					unset($arGroups[$key]);
				}
			}
			CUser::SetUserGroup($USER->GetID(), $arGroups);

			CUser::SetUserGroup($USER->GetID(), array_merge(CUser::GetUserGroup($USER->GetID()), array(28)));
		}
		else
		{
			$arGroups = $USER->GetUserGroupArray();
			$groupsToRemove = [21, 22, 23, 24, 25, 26, 27, 28];
			foreach ($groupsToRemove as $groupID) {
				$key = array_search($groupID, $arGroups);
				if ($key !== false) {
					unset($arGroups[$key]);
				}
			}
			CUser::SetUserGroup($USER->GetID(), $arGroups);
		}
		$userFields = array('UF_LOYALITY_CARD' => $loyality_card, 'UF_DISCOUNT' => $discount);
    	$USER->Update($USER->GetID(), $userFields);
	}
	elseif (!empty($arResult['USER']['UF_LOYALITY_CARD']))
	{
		$str = "";
		foreach(str_split($arResult['USER']['UF_LOYALITY_CARD']) as $char)
		{
			if($char == "%")
			{
				$char = "%25";
			}
			elseif($char == "?")
			{
				$char = "%3F";
			}
			$str .= $char;
		}
		$discount = 0;
		$json = api_ms4("https://api.moysklad.ru/api/remap/1.2/entity/counterparty?filter=discountCardNumber=" . $str, 'GET');

		for($i = 0; $i < count($json->rows[0]->discounts); $i++)
		{
			$json_meta = api_ms4($json->rows[0]->discounts[$i]->discount->meta->href, 'GET');

			if(!empty($json->rows[0]->discounts[$i]->accumulationDiscount) && $json->rows[0]->discounts[$i]->accumulationDiscount > $discount && 
				$json->rows[0]->discounts[$i]->accumulationDiscount > $json->rows[0]->discounts[$i]->personalDiscount)
		    {
				if($json_meta->name == "Индивидуальная скидка" || $json_meta->name == "Юридические лиц" || $json_meta->name == "Физические лица")
				{
					$discount = $json->rows[0]->discounts[$i]->accumulationDiscount;
				}
			}
			elseif(!empty($json->rows[0]->discounts[$i]->personalDiscount) && $json->rows[0]->discounts[$i]->personalDiscount > $discount &&
					$json->rows[0]->discounts[$i]->personalDiscount > $json->rows[0]->discounts[$i]->accumulationDiscount)
			{
				if($json_meta->name == "Индивидуальная скидка" || $json_meta->name == "Юридические лиц" || $json_meta->name == "Физические лица")
				{
					$discount = $json->rows[0]->discounts[$i]->personalDiscount;
				}
			}
		}
		if($discount == 30)
		{
			$arGroups = $USER->GetUserGroupArray();
			$groupsToRemove = [22, 23, 24, 25, 26, 27, 28];
			foreach ($groupsToRemove as $groupID) {
				$key = array_search($groupID, $arGroups);
				if ($key !== false) {
					unset($arGroups[$key]);
				}
			}
			CUser::SetUserGroup($USER->GetID(), $arGroups);

			CUser::SetUserGroup($USER->GetID(), array_merge(CUser::GetUserGroup($USER->GetID()), array(21)));
		}
		elseif($discount == 20)
		{
			$arGroups = $USER->GetUserGroupArray();
			$groupsToRemove = [21, 23, 24, 25, 26, 27, 28];
			foreach ($groupsToRemove as $groupID) {
				$key = array_search($groupID, $arGroups);
				if ($key !== false) {
					unset($arGroups[$key]);
				}
			}
			CUser::SetUserGroup($USER->GetID(), $arGroups);

			CUser::SetUserGroup($USER->GetID(), array_merge(CUser::GetUserGroup($USER->GetID()), array(22)));
		}
		elseif($discount == 15)
		{
			$arGroups = $USER->GetUserGroupArray();
			$groupsToRemove = [21, 22, 24, 25, 26, 27, 28];
			foreach ($groupsToRemove as $groupID) {
				$key = array_search($groupID, $arGroups);
				if ($key !== false) {
					unset($arGroups[$key]);
				}
			}
			CUser::SetUserGroup($USER->GetID(), $arGroups);

			CUser::SetUserGroup($USER->GetID(), array_merge(CUser::GetUserGroup($USER->GetID()), array(23)));
		}
		elseif($discount == 12)
		{
			$arGroups = $USER->GetUserGroupArray();
			$groupsToRemove = [21, 22, 23, 25, 26, 27, 28];
			foreach ($groupsToRemove as $groupID) {
				$key = array_search($groupID, $arGroups);
				if ($key !== false) {
					unset($arGroups[$key]);
				}
			}
			CUser::SetUserGroup($USER->GetID(), $arGroups);

			CUser::SetUserGroup($USER->GetID(), array_merge(CUser::GetUserGroup($USER->GetID()), array(24)));
		}
		elseif($discount == 10)
		{
			$arGroups = $USER->GetUserGroupArray();
			$groupsToRemove = [21, 22, 23, 24, 26, 27, 28];
			foreach ($groupsToRemove as $groupID) {
				$key = array_search($groupID, $arGroups);
				if ($key !== false) {
					unset($arGroups[$key]);
				}
			}
			CUser::SetUserGroup($USER->GetID(), $arGroups);

			CUser::SetUserGroup($USER->GetID(), array_merge(CUser::GetUserGroup($USER->GetID()), array(25)));
		}
		elseif($discount == 7)
		{
			$arGroups = $USER->GetUserGroupArray();
			$groupsToRemove = [21, 22, 23, 24, 25, 27, 28];
			foreach ($groupsToRemove as $groupID) {
				$key = array_search($groupID, $arGroups);
				if ($key !== false) {
					unset($arGroups[$key]);
				}
			}
			CUser::SetUserGroup($USER->GetID(), $arGroups);

			CUser::SetUserGroup($USER->GetID(), array_merge(CUser::GetUserGroup($USER->GetID()), array(26)));
		}
		elseif($discount == 5)
		{
			$arGroups = $USER->GetUserGroupArray();
			$groupsToRemove = [21, 22, 23, 24, 25, 26, 28];
			foreach ($groupsToRemove as $groupID) {
				$key = array_search($groupID, $arGroups);
				if ($key !== false) {
					unset($arGroups[$key]);
				}
			}
			CUser::SetUserGroup($USER->GetID(), $arGroups);

			CUser::SetUserGroup($USER->GetID(), array_merge(CUser::GetUserGroup($USER->GetID()), array(27)));
		}
		elseif($discount == 3)
		{
			$arGroups = $USER->GetUserGroupArray();
			$groupsToRemove = [21, 22, 23, 24, 25, 26, 27];
			foreach ($groupsToRemove as $groupID) {
				$key = array_search($groupID, $arGroups);
				if ($key !== false) {
					unset($arGroups[$key]);
				}
			}
			CUser::SetUserGroup($USER->GetID(), $arGroups);

			CUser::SetUserGroup($USER->GetID(), array_merge(CUser::GetUserGroup($USER->GetID()), array(28)));
		}
		else
		{
			$arGroups = $USER->GetUserGroupArray();
			$groupsToRemove = [21, 22, 23, 24, 25, 26, 27, 28];
			foreach ($groupsToRemove as $groupID) {
				$key = array_search($groupID, $arGroups);
				if ($key !== false) {
					unset($arGroups[$key]);
				}
			}
			CUser::SetUserGroup($USER->GetID(), $arGroups);
		}
		$userFields = array('UF_DISCOUNT' => $discount);
    	$USER->Update($USER->GetID(), $userFields);
		
	}
	else 
	{
		$discount = "";
		$arGroups = $USER->GetUserGroupArray();
		$groupsToRemove = [21, 22, 23, 24, 25, 26, 27, 28];
		foreach ($groupsToRemove as $groupID) {
			$key = array_search($groupID, $arGroups);
			if ($key !== false) {
				unset($arGroups[$key]);
			}
		}
		CUser::SetUserGroup($USER->GetID(), $arGroups);
		$userFields = array('UF_DISCOUNT' => $discount);
		$USER->Update($USER->GetID(), $userFields);
	}
}

?>
