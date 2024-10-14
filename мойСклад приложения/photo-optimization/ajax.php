<?php
ini_set('memory_limit', '1536M');
ini_set('max_execution_time', '43200'); 
$accountId = $_POST['accountId']; // id аккаунта МоегоСклада
$method = $_POST['method']; // метод для switch case
$length = $_POST['length'];
$filter = $_POST['filter'];
$limit = $_POST['limit'];
$offset = $_POST['offset'];
$id = $_POST['id'];
$code = $_POST['code'];
$name = $_POST['name'];
$article = $_POST['article'];
$href = $_POST['href'];
$imageSize = $_POST['imageSize'];
$specifiedSize = $_POST['specifiedSize'];
$filename = $_POST['filename'];
$ImageHref = $_POST['ImageHref'];
$SearchSize = $_POST['SearchSize'];
$dateStart = $_POST['dateStart'];
$dateEnd = $_POST['dateEnd'];
$tiny = $_POST['tiny'];

require_once 'lib.php';

$app = AppInstance::loadApp($accountId);

switch ($method) {
    case "search": // получение наименований документов за период

        $arr = [];
        $array_result = [];

        $json = jsonApi()->api('GET', "/entity/product", "?expand=images" . "&limit=" . $limit . "&offset=" . $offset . "&filter=" . urlencode($filter));
        $size = $json->meta->size;
        $offs = $json->meta->offset;

        if(isset($json->errors[0]->code) && $json->errors[0]->code = '1073') // ошибка о превышении параллельных запросов
        {
            $array_result[] = $json->errors[0]->code;
            echo json_encode($array_result); // Возвращаем текущий результат
            exit; // Завершаем скрипт
        }

        foreach ($json->rows as $row) 
        {
            // Создаем временный массив для элемента
            $item = [
                'json_size' => $size,
                'id' => $row->id,
                'code' => $row->code,
                'name' => $row->name,
                'article' =>  $row->article ?? '',
                'images' => [] // Инициализируем массив для изображений
            ];
    
            foreach ($row->images->rows as $row2)
            {
                if($row2->size > $SearchSize && ($dateStart ? $row2->updated >= $dateStart : true) && ($dateEnd ? $row2->updated <= $dateEnd : true))
                {
                    // Добавляем информацию об изображении в массив 'images'
                    $item['images'][] = [
                        'updated' => $row2->updated,
                        'dateStart' => $dateStart,
                        'dateEnd' => $dateEnd,
                        'id' => $row->id,
                        'filename' => $row2->filename,
                        'size' => $row2->size,
                        'ImageHref' =>  $row2->meta->href,
                        'downloadHref' =>  $row2->meta->downloadHref,
                        'tiny' => $row2->tiny->href
                    ];
                }
            }
    
            // Добавляем элемент в массив результатов
            if (count($item['images']) > 0) $array_result[] = $item;
        }
        
        echo json_encode($array_result);

    break;

    case "post":

        function is_valid_png($filename) {
            $file_header = fopen($filename, 'rb');
            $header = fread($file_header, 8);
            fclose($file_header);
            return (substr($header, 0, 8) === "\x89PNG\r\n\x1A\n");
        }
        function is_valid_jpg($filename) {
            $file_header = fopen($filename, 'rb');
            $header = fread($file_header, 2);
            fclose($file_header);
            return (substr($header, 0, 2) === "\xFF\xD8");
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

        function resizepng_transparent($img, $img_new, $divider, $specifiedSize)
        {
            $black = imagecolorallocate($img, 0, 0, 0);
            imagecolortransparent($img, $black);
            imagealphablending($img, false);
            imagesavealpha($img, true); // 4 строки для прозрачности
            imagepng($img, $img_new, 9); //сохранение фото
            $size = filesize($img_new);
            while($size > (int)$specifiedSize) // пока фото больше 500кб сжимаем ещё
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
            }
        }

        function resizepng_Nottransparent($img, $img_new, $divider, $specifiedSize)
        {
            imagepng($img, $img_new, 9); //сохранение фото
            $size = filesize($img_new);
            while($size > (int)$specifiedSize) // пока фото больше 500кб сжимаем ещё
            {
                clearstatcache(); //очищение кэша
                $img = imagecreatefrompng($img_new); //получаем исходное изображение
                $width = imagesx($img); //ширина исходного изображения
                $height = imagesy($img);//высота исходного изображения
                $img = imagescale($img, $width / $divider, $height / $divider); //уменьшение ширины и высоты пропорционально
                imagepng($img, $img_new, 9); //сохранение фото 
                $size = filesize($img_new);
            }
        }
        
        $array_result = [];
        $startIndex = strpos($href, "/download");

        if ($startIndex !== false) {
            // Извлекаем подстроку начиная с "/download"
            $trimmedUrl = substr($href, $startIndex);
        } 

        $image_location = 'files/' . $accountId . '/' . date('d.m.Y') . '/saved/' . $filename;
        
        if (!is_dir('files/' . $accountId . '/' . date('d.m.Y') . '/saved')) {
            // Если папка не существует, создаем её
            mkdir('files/' . $accountId . '/' . date('d.m.Y') . '/saved', 0777, true); // второй параметр - права доступа, третий - рекурсивное создание
        }

        if (!is_dir('files/' . $accountId . '/' . date('d.m.Y') . '/compressed')) {
            // Если папка не существует, создаем её
            mkdir('files/' . $accountId . '/' . date('d.m.Y') . '/compressed', 0777, true); // второй параметр - права доступа, третий - рекурсивное создание
        }
        $startImageSize = intval($imageSize);
        while (intval($imageSize) > intval($specifiedSize))
        {
            $open_image_in_binary = fopen($image_location, 'wb');
            $json_save_image = jsonApi()->api('GET', $trimmedUrl, "", $open_image_in_binary);
            $type_image = pathinfo($image_location, PATHINFO_EXTENSION); //получение расширения файла
            if($type_image == 'png')
            {
                if (!is_valid_png($image_location)) 
                {
                    $item = [
                        'code' => $code,
                        'length' => $length,
                        'name' => $name . ' <b style = "color:red;">Фотография повреждена</b>',
                        'article' =>  $article,
                        'specifiedSize' => intval($startImageSize),
                        'imageSize' => $imageSize,
                        'tiny' => $tiny,
                        'imagePath' => $image_location
                    ];
                    $array_result[] = $item;
                    echo json_encode($array_result);
                    break 2;
                    die("Ошибка: Файл не является корректным PNG.");
                }
                $img = imagecreatefrompng($image_location); //получаем исходное изображение
                $width = imagesx($img); //ширина исходного изображения
                $height = imagesy($img);//высота исходного изображения
                if (($width <= 800) && ($height <= 800)) //если изображение слишком маленькое то не уменьшаем его в два раза
                {
                    if (check_transparent($width, $height, $img)) //если фон прозрачный
                    {
                        $img_new = 'files/' . $accountId . '/' . date('d.m.Y') . '/compressed/' . $filename;
                        resizepng_transparent($img, $img_new, 1.1, $specifiedSize);
                    }
                    else //если фон не прозрачный
                    {
                        $img_new = 'files/' . $accountId . '/' . date('d.m.Y') . '/compressed/' . $filename;
                        resizepng_Nottransparent($img, $img_new, 1.1, $specifiedSize);
                    }
                }
                else
                {
                    $img = imagescale($img, $width / 2, $height / 2); //уменьшение ширины и высоты пропорционально
                    if (check_transparent($width, $height, $img)) //если фон прозрачный
                    {
                        $img_new = 'files/' . $accountId . '/' . date('d.m.Y') . '/compressed/' . $filename;
                        resizepng_transparent($img, $img_new, 1.5, $specifiedSize);
                    }
                    else //если фон не прозрачный
                    {
                        $img_new = 'files/' . $accountId . '/' . date('d.m.Y') . '/compressed/' . $filename;
                        resizepng_Nottransparent($img, $img_new, 1.5, $specifiedSize);
                    }
                }
            }

            if ($type_image == 'jpg' || $type_image == 'jpeg') 
            {
                if (!is_valid_jpg($image_location))
                {
                    $item = [
                        'code' => $code,
                        'length' => $length,
                        'name' => $name . ' <b style = "color:red;">Фотография повреждена</b>',
                        'article' =>  $article,
                        'specifiedSize' => intval($startImageSize),
                        'imageSize' => $imageSize,
                        'tiny' => $tiny,
                        'imagePath' => $image_location
                    ];
                    $array_result[] = $item;
                    echo json_encode($array_result);
                    break 2;
                    die("Ошибка: Файл не является корректным JPG.");
                }
                // Параметры
                $scaleFactor = 1.1;
                $initialQuality = 75;
                $targetSize = intval($specifiedSize);
                
                // Получение исходного изображения
                $img = imagecreatefromjpeg($image_location);
                if (!$img) {
                    die('Не удалось загрузить изображение');
                }
            
                $img_new = 'files/' . $accountId . '/' . date('d.m.Y') . '/compressed/' . $filename;
                
                // Исходное сохранение с качеством
                imagejpeg($img, $img_new, $initialQuality);
                $size = filesize($img_new);
                
                // Цикл для уменьшения размера файла
                while ($size > intval($specifiedSize)) {
                    clearstatcache(); //очищение кэша
                    $width = imagesx($img);
                    $height = imagesy($img);
                    
                    // Уменьшение изображения
                    $img = imagescale($img, $width / $scaleFactor, $height / $scaleFactor);
                    imagejpeg($img, $img_new, $initialQuality);
                    $size = filesize($img_new);
                }
                // Очистка ресурсов
                imagedestroy($img);
            }

            $start = strpos($ImageHref, "/entity");

            if ($start !== false) 
            {
                // Извлекаем подстроку начиная с "/download"
                $trimUrl = substr($ImageHref, $start);
            } 
            // //удаление старого фото
            $json_delete_image = jsonApi()->api('DELETE', $trimUrl, ""); //запрос на удаления старого фото

            $img_new = 'files/' . $accountId . '/' . date('d.m.Y') . '/compressed/' . $filename; //куда сохранить и с каким именем 

            $imagedata = file_get_contents($img_new); //получение фото из папки
            $base64 = base64_encode($imagedata); //кодировка фото в формат base64

            $data = ["filename" => $filename,
                        "content" => $base64]; //массив обновляемых данных, имя файла и закодированное в base64 фото
            $data_string = json_encode($data); //кодировка в json формат

            //добавление нового фото
            $json_post_image = jsonApi()->api('POST', '/entity/product/', $id . "/images", "", $data_string); //запрос на добавление нового фото

            $imageSize = filesize($img_new);
            break;
        }

        $item = [
            'code' => $code,
            'length' => $length,
            'name' => $name,
            'article' =>  $article,
            'specifiedSize' => intval($startImageSize),
            'imageSize' => $imageSize,
            'tiny' => $tiny,
            'imagePath' => $image_location
        ];
        $array_result[] = $item;
        echo json_encode($array_result);
    break;

    case "create_zip":
        $images = json_decode($_POST['images']);
        $zipFileName = 'фото до сжатия_' . date('Y-m-d H:i:s') . '.zip';
        $zipFilePath = 'downloads/' . $accountId . '/' . $zipFileName;

        if (!is_dir('downloads/' . $accountId)) {
            // Если папка не существует, создаем её
            mkdir('downloads/' . $accountId, 0777, true); // второй параметр - права доступа, третий - рекурсивное создание
        }

        // Проверяем наличие и доступность директории для записи
        if (!is_writable('downloads/' . $accountId . '/' )) {
            echo json_encode(['success' => false, 'message' => 'Директория недоступна для записи.']);
            exit;
        }

        $zip = new ZipArchive();
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            echo json_encode(['success' => false, 'message' => 'Не удалось создать архив.']);
            exit;
        }

        foreach ($images as $imagePath) {
            $absPath = realpath($imagePath);
            if ($absPath !== false) {
                $zip->addFile($absPath, basename($absPath));
            } else {
                echo json_encode(['success' => false, 'message' => "Не удалось найти файл: $imagePath"]);
                exit;
            }
        }

        $zip->close();
        echo json_encode(['success' => true, 'zipFile' => $zipFilePath]);
    break;

}