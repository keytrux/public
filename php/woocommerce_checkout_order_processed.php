<?php

function send_order($order_id) {
    if (mb_strlen($order_id) == 0 || ctype_space($order_id)) {
        return;
    }
    $order = wc_get_order($order_id);
    $inf_order = '';
    foreach ($order->get_items() as $item_id => $item) {
        $inf_order .= ' Название: ' . $item['name'] . ', Количество: ' . $item['qty'] . ', Цена: ' . $item['line_total'] . ' руб.;' . "\n";
    }
    $inf_order .= ' Сумма заказа: ' . $order->get_total() . ' руб.;' . "\n";
    $inf_order .= ' Способ оплаты: ' . $order->payment_method_title . ';' . "\n";
    $inf_order .= ' План.дата отгрузки: '. $_REQUEST['ce7ab86'] . "\n";
    if ($order->get_used_coupons()) {
        $inf_order .= 'Использованные купоны: ';
        $order_coupons = $order->get_used_coupons();
        foreach ($order_coupons as $coupon) {
            $inf_order .= $coupon . ', ' . "\n";
        }
    }
    $deliveryOrPickup = 1213879;
    $deliveryOrPickupString = 'Самовывоз';
    if ($_REQUEST['a4a72c1'] == 'da-a-hrefdelivery_price_info-classcart_option__delivery-fancybox-inline690-rub-a'  || $_REQUEST['a4a72c1'] == 'da-a-hrefdelivery_price_info-classcart_option__delivery-fancybox-inline790-rub-a') {
        $deliveryOrPickup = 1213877;
        $deliveryOrPickupString = 'Доставка';
    }
    $inf_order .= ' Доставка/самовывоз: '. $deliveryOrPickupString . "\n";

    if($deliveryOrPickupString == 'Доставка')
    {
        $inf_order .= ' Адрес доставки: '. $_REQUEST['eaa922a'] . "\n";

        $liftMethod = '';
        switch ($_REQUEST['d8044f8'])
        {
            case 'net-ya-sam-spushhus-k-mashine':
                $liftMethod = 'Нет. Я сам спущусь к машине';
                break;

            case 'trebuetsya-podyom-do-kvartiry-do-2-m':
                $liftMethod = 'Требуется подъём до квартиры (ДО 2 м) (690 руб.)';
                break;

            case 'trebuetsya-podyom-do-kvartiry-bolee-2-m':
                $liftMethod = 'Требуется подъём до квартиры (БОЛЕЕ 2 м) (990 руб.)';
                break;
        }

        $installationMethod = '';
        switch ($_REQUEST['d2e9676'])
        {
            case 'net-ya-sam-ustanovlyu':
                $installationMethod = 'Нет. Я сам установлю';
                break;

            case 'trebuetsya-podyom-i-ustanovka-do-2-m':
                $installationMethod = 'Требуется подъём и установка (ДО 2 м) (1 490 руб.)';
                break;

            case 'trebuetsya-podyom-i-ustanovka-bolee-2-m':
                $installationMethod = 'Требуется подъём и установка (БОЛЕЕ 2 м) (2 490 руб.)';
                break;
        }
        $inf_order .= 'Требуется подъем?: ' . $liftMethod . "\n";
        $inf_order .= 'Требуется подъём + установка?: ' . $installationMethod . "\n";
    }

    $disposalMethod = '';
    switch ($_REQUEST['d9f6a79']) {
        case 'net-ya-sam-utiliziruyu-elku':
            $disposalMethod = 'Нет. Я сам утилизирую елку';
            break;
        case 'trebuetsya-utilizaciya-dereva-do-2-m':
            $disposalMethod = 'Требуется утилизация дерева до 2 м (2490 руб)';
            break;
        case 'trebuetsya-utilizaciya-dereva-bolee-2-m':
            $disposalMethod = 'Требуется утилизация дерева более 2 м. (3490 руб)';
            break;
    }

    $inf_order .= ' Требуется утилизация в Январе?: '. $disposalMethod;
    $comment = $inf_order;
    $paymentMethod = 0;
    switch ($_REQUEST['payment_method']) {
        case 'cod':
            $paymentMethod = 1215325;
            break;
        case 'yookassa_epl':
            $paymentMethod = 1215321;
            break;
        case 'bacs':
            $paymentMethod = 1215491;
            break;
        case 'dolyamepayment':
            $paymentMethod = 1215323;
    }
    $data = array(
        'title'            => 'Заявка с корзины',
        'name'             => $order->billing_first_name,
        'email'            => $order->billing_email,
        'phone'            => $order->billing_phone,
        'comment'          => $comment,
        'fields' => array(
            '574517' => '{source}',
            'price' => (int)$order->get_total(),
            '567777' => strtotime($_REQUEST['ce7ab86']), // дата доставки, самовывоза,
            '579559' => $deliveryOrPickup, // доставка или самовывоз,
            '580043' => $paymentMethod, // способ оплаты,
            '660955' => '{visit}'
        )

    );

    file_get_contents("https://XXXX?" . http_build_query($data));

}

add_action('woocommerce_checkout_order_processed', 'send_order', 10, 1);