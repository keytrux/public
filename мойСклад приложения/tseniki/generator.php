<?php
$url = 'barcodegen';
require __DIR__ . '/' . $url . '/autoload.php';

use BarcodeBakery\Common\BCGColor;
use BarcodeBakery\Common\BCGDrawing;
use BarcodeBakery\Common\BCGFontFile;
use BarcodeBakery\Barcode\BCGcode128;

$font = new BCGFontFile(__DIR__ . '/' . $url . '/font/Arial.ttf', 0);
$text = isset($_GET['text']) ? $_GET['text'] : '123456789';
$colorBlack = new BCGColor(0, 0, 0);
$colorWhite = new BCGColor(255, 255, 255);
$drawException = null;
$barcode = null;
try {
    $code = new BCGcode128();
    $code->setScale(2);
    $code->setThickness(30);
    $code->setForegroundColor($colorBlack);
    $code->setBackgroundColor($colorWhite);
    $code->setFont($font);
    $code->setStart(null);
    $code->setTilde(true);
    $code->parse($text);
    $barcode = $code;
} catch (Exception $exception) {
    $drawException = $exception;
}

$drawing = new BCGDrawing($barcode, $colorWhite);
if ($drawException) {
    $drawing->drawException($drawException);
}
header('Content-Type: image/png');
header('Content-Disposition: inline; filename="barcode.png"');
$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
?>