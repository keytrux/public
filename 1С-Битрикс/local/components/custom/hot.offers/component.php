<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

// Параметры компонента
$arParams["IBLOCK_ID"] = intval($arParams["IBLOCK_ID"]);
$arParams["COUNT"] = intval($arParams["COUNT"]) ?: 8;

// Фильтр для выборки товаров
$arFilter = [
    "IBLOCK_ID" => $arParams["IBLOCK_ID"], // ID инфоблока
    "ACTIVE" => "Y", // Только активные товары
    "PROPERTY_hot_offers_VALUE" => "Y", // Товары, где свойство hot_offers равно "Y"
];

// Поля для выборки
$arSelect = [
    "ID",
    "NAME",
    "DETAIL_PAGE_URL",
    "PREVIEW_PICTURE",
    "PROPERTY_*", // Все свойства
    "PROPERTY_hot_offers_sale",
];

// Получаем товары
$rsProducts = CIBlockElement::GetList(
    ["RAND" => "ASC"], // Случайная сортировка
    $arFilter,
    false,
    ["nTopCount" => $arParams["COUNT"]], // Ограничиваем количество товаров
    $arSelect
);

$arProducts = [];
while ($arProduct = $rsProducts->GetNext()) {
    // Получаем свойства товара
    $rsProps = CIBlockElement::GetProperty(
        $arParams["IBLOCK_ID"],
        $arProduct["ID"],
        [],
        []
    );
    $productProps = [];
    while ($prop = $rsProps->Fetch()) {
        $productProps[$prop["CODE"]] = $prop;
    }

    // Получаем цену
    $rsPrice = CPrice::GetList(
        [],
        ["PRODUCT_ID" => $arProduct["ID"]],
        false,
        false,
        ["ID", "PRICE"]
    );
    $arPrice = $rsPrice->Fetch();
    $priceValue = $arPrice ? $arPrice["PRICE"] : null;

    // Если цена не найдена, используем свойства товара
    if (!$priceValue && isset($productProps["MINIMUM_PRICE"]["VALUE"])) {
        $priceValue = $productProps["MINIMUM_PRICE"]["VALUE"];
    }

    // Добавляем товар в массив
    $arProducts[] = [
        "ID" => $arProduct["ID"],
        "NAME" => $arProduct["NAME"],
        "DETAIL_PAGE_URL" => $arProduct["DETAIL_PAGE_URL"],
        "PREVIEW_PICTURE" => $arProduct["PREVIEW_PICTURE"],
        "PROPERTIES" => $productProps,
        "PRICE" => $priceValue,
        "IS_SALE" => $arProduct["PROPERTY_HOT_OFFERS_SALE_VALUE"] === "Y", // Проверяем свойство hot_offers_sale
    ];
}

// Передаем данные в шаблон
$arResult["ITEMS"] = $arProducts;
$this->IncludeComponentTemplate();
?>