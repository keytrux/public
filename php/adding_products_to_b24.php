<?php
require_once 'Logg.php';

define('BASE_URL', 'https://XXXXX.bitrix24.ru/rest/1/');
define('LEAD_GET_URL', BASE_URL . 'XXXX/crm.lead.get.json?ID=');
define('PRODUCT_LIST_URL', BASE_URL . 'XXXX/crm.product.list');
define('PRODUCT_ADD_URL', BASE_URL . 'XXXX/crm.product.add');
define('LEAD_PRODUCT_ADD_URL', BASE_URL . 'XXXX/crm.lead.productrows.set');

$data = file_get_contents('php://input');
parse_str($data, $dataArray);
Logg::log($dataArray, "Данные");

$idLead = $dataArray['data']['FIELDS']['ID'] ?? null;

if (!$idLead) {
    Logg::log('Не указан ID лида', "Ошибка");
    die('Ошибка: Не указан ID лида');
}

$newProduct = [];

function curl($url, $data = null, $method = 'GET') {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    if ($method === 'POST' && $data !== null) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

function fetchLeadData($idLead) {
    $url = LEAD_GET_URL . $idLead;
    $leadData = curl($url);
    if (!isset($leadData['result'])) {
        Logg::log('Ошибка при получении данных лида', "Ошибка");
        die('Ошибка при получении данных лида');
    }
    Logg::log($leadData, "Данные лида");
    if ($leadData['result']['TITLE'] !== 'Новый лид от Tilda') {
        Logg::log('Неожиданное название лида', "Ошибка");
        die('Неожиданное название лида');
    }
    return $leadData['result'];
}

function parseOrderDetails($comment) {
    $position = strpos($comment, "Состав заказа:");
    if ($position === false) {
        Logg::log('Состав заказа не найден', "Ошибка");
        die('Состав заказа не найден');
    }

    $orderDetails = trim(substr($comment, $position + strlen("Состав заказа:")));
    preg_match_all('/(.*?)\((.*?)\)\s*—\s*(\d+)\s*x\s*(\d+)\s*=\s*(\d+)/', $orderDetails, $matches, PREG_SET_ORDER);

    $productArray = [];
    foreach ($matches as $match) {
        $productArray[] = [
            'name' => trim($match[1]),
            'price' => (int) $match[3],
            'quantity' => (int) $match[4]
        ];
    }
    return $productArray;
}

function getProductByName($name) {
    $query = http_build_query(['filter' => ['NAME' => $name]]);
    $url = PRODUCT_LIST_URL . '?' . $query;
    $productData = curl($url);
    return $productData['result'] ?? [];
}

function createProduct($product) {
    $data = [
        'fields' => [
            'NAME' => $product['name'],
            'CURRENCY_ID' => 'RUB',
            'PRICE' => $product['price'],
            'SORT' => 500
        ]
    ];
    curl(PRODUCT_ADD_URL, $data, 'POST');
}

function addProductsToLead($idLead, $products) {
    $data = ['id' => $idLead, 'rows' => $products];
    $response = curl(LEAD_PRODUCT_ADD_URL, $data, 'POST');
    Logg::log($response, "Ответ при добавлении товаров в лид");
}

try {
    $leadData = fetchLeadData($idLead);
    $comment = $leadData['COMMENTS'];
    $productArray = parseOrderDetails($comment);

    $newProduct = [];
    foreach ($productArray as $product) {
        $existingProduct = getProductByName($product['name']);
        if (empty($existingProduct)) {
            createProduct($product);
            $existingProduct = getProductByName($product['name']);
        }
        if (!empty($existingProduct)) {
            $newProduct[] = [
                'PRODUCT_ID' => $existingProduct[0]['ID'],
                'PRICE' => $existingProduct[0]['PRICE'],
                'QUANTITY' => $product['quantity']
            ];
        }
    }

    addProductsToLead($idLead, $newProduct);
    echo "Товары успешно добавлены.";
} catch (Exception $e) {
    Logg::log($e->getMessage(), "Исключение");
    echo "Произошла ошибка: " . $e->getMessage();
}
?>