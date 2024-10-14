<?php
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

function API($init, $url, $type, $open_image_in_binary, $data_string) //функция для работы с API
{
    $user = "user:password"; //логин:пароль
    $curl = curl_init($init);
    if (!empty($url)) curl_setopt($curl, CURLOPT_URL, $url); //подключение к моемускладу ко всем продуктам
    curl_setopt($curl, CURLOPT_USERPWD, $user);
    if (!empty($type)) curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $type);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', "Accept-Encoding: gzip"));
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,TRUE);
    if (!empty($open_image_in_binary)) curl_setopt($curl, CURLOPT_FILE, $open_image_in_binary);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_ENCODING, 'gzip');
    if (!empty($data_string)) 
    {
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_POST, true);
    }
    $result = curl_exec($curl);
    curl_close($curl);
    return json_decode($result);
}
function error($json) //функция для поиска и вывода ошибок
{
    if (!empty($json->errors)) 
    {
        $array = array(false);
        foreach ($json->errors as $error) 
        {
            $array = array();
            $array[] = $error->code . ' ' . $error->error;
            $array_error = print_r($array, true);
            echo 'Код ошибки: ' . $array_error . '<br>';
        }
    }
}
function check_transparent($width, $height, $img) //функция для проверки прозрачности
{
    for($k = 0; $k < $width; $k++) {
        for($l = 0; $l < $height; $l++) {
            $rgba = imagecolorat($img, $k, $l);
            if(($rgba & 0x7F000000) >> 24) 
            {
                return true;
            }
        }
    }
    return false;
}
function resizepng_transparent($img, $img_new, $divider)
{
    $black = imagecolorallocate($img, 0, 0, 0);
    imagecolortransparent($img, $black);
    imagealphablending($img, false);
    imagesavealpha($img, true); // 4 строки для прозрачности
    imagepng($img, $img_new, 9); //сохранение фото
    $size = filesize($img_new);
    while($size > 512000) // пока фото больше 500кб сжимаем ещё
    {
        clearstatcache(); //очищение кэша
        $img = imagecreatefrompng($img_new); //получаем исходное изображение
        $width = imagesx($img); //ширина исходного изображения
        $height = imagesy($img);//высота исходного изображения
        $img = imagescale($img, $width / $divider, $height / $divider); //уменьшение ширины и высоты пропорционально
        $black = imagecolorallocate($img, 0, 0, 0);
        imagecolortransparent($img, $black);
        imagealphablending($img, false);
        imagesavealpha($img, true); // 4 строки для прозрачности
        imagepng($img, $img_new, 9); //сохранение фото 
        $size = filesize($img_new);
        
        echo 'filesize2: ' . $size . '<br>';
        echo 'width: ' . $width . ' height: ' . $height . '<br>';
    }
}
function resizepng_Nottransparent($img, $img_new, $divider)
{
    imagepng($img, $img_new, 9); //сохранение фото
    $size = filesize($img_new);
    while($size > 512000) // пока фото больше 500кб сжимаем ещё
    {
        clearstatcache(); //очищение кэша
        $img = imagecreatefrompng($img_new); //получаем исходное изображение
        $width = imagesx($img); //ширина исходного изображения
        $height = imagesy($img);//высота исходного изображения
        $img = imagescale($img, $width / $divider, $height / $divider); //уменьшение ширины и высоты пропорционально
        imagepng($img, $img_new, 9); //сохранение фото 
        $size = filesize($img_new);

        echo 'filesize2: ' . $size . '<br>';
        echo 'width: ' . $width . ' height: ' . $height . '<br>';
    }
}
    $s = 1;
    $limit = 100; //установка лимита
    $offset = 0; //установка смещения
    $count_product = 0; //количество товаров
    $num_tov = 0;
    $url_main = 'https://api.moysklad.ru/api/remap/1.2/entity/product/'; //главная ссылка
    $get = 'GET'; $post = 'POST'; $delete = 'DELETE';
    $count_image = 0; //для счета количества фото


    $json = API("", $url_main . $lim . $off, $get, "", ""); //запрос на получение товаров 
    //echo 'Запрос 1: <br>'; 
    error($json); //проверка на ошибки

    $size = $json->meta->size; //кол-во товаров

    for($offset; $offset < $limit * $s; $offset+=$limit)
    {
        $lim = "?limit=$limit"; //установка лимита
        $off = "&offset=$offset"; //установка смещения
       
        $json = API("", $url_main . $lim . $off . '&expand=images', $get, "", ""); //запрос на получение товаров 
        //echo 'Запрос 1: <br>'; 
        error($json); //проверка на ошибки

        $size = $json->meta->size; //кол-во товаров
	    $s = $size / $limit;
        foreach ($json->rows as $row)
        {
            $count_product++; //подсчет количества товара
            $number_off = $offset + $num_tov;
            $num_tov++;
            echo '<br>'. $number_off. ': ' . $row->id . '<br>';
            $link_image = $row->id . "/images";
            
            //$json_image = API("", $url_main . $link_image, $get, "", ""); //запрос к фото товара по id
            if ($row->images->meta->size > 0)
            {
                foreach ($row->images->rows as $row2)
                {
                    $link_image_row = $row2->meta->href;
                    $sizeimage = $row2->size; //размер фото
                    $filename = $row2->filename;
                    
                    while ($sizeimage > '512000')
                    {
                        $fl = $row2->meta->downloadHref;
                        $image_location = 'saved_images/' . $filename; //место сохранения фото
                        $open_image_in_binary = fopen($image_location, 'wb');
                        $json_save_image = API($fl,"", "", $open_image_in_binary, "");
                        $type_image = pathinfo($image_location, PATHINFO_EXTENSION); //получение расширения файла
                        echo  '<b>имя файла: </b>' . $filename . '<b> size: </b>' . $sizeimage . '<br>';
                        $count_image++;
                        $link_for_delete[] = $link_image_row;
                        $lip[] =  $link_image;
                        $download_link[] = $fl;
                        if($type_image == 'png')
                        {
                            $img = imagecreatefrompng($image_location); //получаем исходное изображение
                            $width = imagesx($img); //ширина исходного изображения
                            $height = imagesy($img);//высота исходного изображения
                            if (($width <= 800) && ($height <= 800)) //если изображение слишком маленькое то не уменьшаем его в два раза
                            {
                                if (check_transparent($width, $height, $img)) //если фон прозрачный
                                {
                                    echo 'прозрачный<br>';
                                    $img_new = 'compressed/' . $filename;
                                    resizepng_transparent($img, $img_new, 1.1);
                                }
                                else //если фон не прозрачный
                                {
                                    echo 'непрозрачный<br>';
                                    $img_new = 'compressed/' . $filename;
                                    resizepng_Nottransparent($img, $img_new, 1.1);
                                }
                            }
                            else
                            {
                                $img = imagescale($img, $width / 2, $height / 2); //уменьшение ширины и высоты пропорционально
                                if (check_transparent($width, $height, $img)) //если фон прозрачный
                                {
                                    echo 'прозрачный<br>';
                                    $img_new = 'compressed/' . $filename;
                                    resizepng_transparent($img, $img_new, 1.5);
                                }
                                else //если фон не прозрачный
                                {
                                    echo 'непрозрачный<br>';
                                    $img_new = 'compressed/' . $filename;
                                    resizepng_Nottransparent($img, $img_new, 1.5);
                                }
                            }
                        }
                        if($type_image == 'jpg')
                        {
                            $img = imagecreatefromjpeg($image_location); //получаем исходное изображение
                            $width = imagesx($img); //ширина исходного изображения
                            $height = imagesy($img);//высота исходного изображения
                            $img = imagescale($img, $width / 1.1, $height / 1.1);
                            $img_new = 'compressed/' . $filename; //куда сохранить и с каким именем
                            imagejpeg($img, $img_new, 50); //сохранение фото 
                            $size = filesize($img_new);
                            while($size > 512000) // пока фото больше 500кб сжимаем ещё
                            {
                                clearstatcache(); //очищение кэша
                                $img = imagecreatefromjpeg($img_new); //получаем исходное изображение
                                $width = imagesx($img); //ширина исходного изображения
                                $height = imagesy($img);//высота исходного изображения
                                $img = imagescale($img, $width / 1.1, $height / 1.1);
                                $img_new = 'compressed/' . $filename; //куда сохранить и с каким именем
                                imagejpeg($img, $img_new, 50); //сохранение фото 
                                $size = filesize($img_new);
                                echo 'filesize2: ' . $size . '<br>';
                            }
                        }
                        while ($filename = $row2->filename)
                        {
                            // //удаление старого фото
                            $json_delete_image = API("", $link_image_row, $delete, "", ""); //запрос на удаления старого фото
                            // echo 'Запрос 5: <br>';
                            error($json_delete_image); //проверка на ошибки
                                
                            $imagedata = file_get_contents($img_new); //получение фото из папки
                            $base64 = base64_encode($imagedata); //кодировка фото в формат base64
                            $data = ["filename" => $filename,
                                    "content" => $base64]; //массив обновляемых данных, имя файла и закодированное в base64 фото
                            $data_string = json_encode($data); //кодировка в json формат

                            //добавление нового фото
                            $json_post_image = API("", $url_main .$url_product. $link_image, $post, "", $data_string); //запрос на добавление нового фото
                            // echo 'Запрос 6: <br>';
                            error($json_post_image); //проверка на ошибки
                            break;
                        }
                        break;
                    }
                }
            }
        }
        $count_product = $count_product;
        $count_image = $count_image; 
        $num_tov = 0;  
    }
    $count_image = $count_image; 
echo '<h1>Количество фото: ' . $count_image .  '</h1>';
echo '<h1>Количество товаров: ' . $count_product .   '</h1>';
echo 'Скрипт был выполнен за ' . round(microtime(true) - $start, 2) . ' секунд';
?>