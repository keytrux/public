<?php

function wpcf7_modify_this( $WPCF7_ContactForm ) {
    $name = null;
    $title = null;
    $email = null;
    $phone = null;
    switch ($_REQUEST['_wpcf7']) {
        case 9779:
            $phone = $_REQUEST['tel-645'];
            $title = 'Заказать звонок';
            break;
        case 3616:
            $phone = $_REQUEST['tel-201'];
            $title = 'Заказать звонок';
            break;
        case 5:
            $phone = $_REQUEST['tel-645'];
            $title = 'Закажите ...';
            break;

    }
    $data = array(
        'title'   => 'Заявка с формы "'. $title. '"', // Постоянное значение
        'name'    => $name, // Для поля с именем 'your-name'
        'email'   => $email, // Для поля с именем 'your-email'
        'phone'   => $phone, // Если значения нет
        'fields' => array(
            '574517' => '{source}',
        )
    );
    file_get_contents("https://XXXX?" . http_build_query($data));
}
add_action("wpcf7_before_send_mail", "wpcf7_modify_this");