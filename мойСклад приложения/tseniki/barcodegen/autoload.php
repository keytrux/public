<?php
$packageFolder = __DIR__ . '/packages';
spl_autoload_register(function ($className) use ($packageFolder) {
    $tryFolders = array();
    $splits = explode('\\', $className);

    $c = count($splits);
    if ($c > 0 && $splits[0] === 'BarcodeBakery') {
        if ($c > 1) {
            if ($splits[1] === 'Common') {
                 $tryFolders = array('barcode-common', 'gs1ai');
            } else {
                $tryFolders = array_filter(scandir($packageFolder), function ($f) {
                    if ($f !== '.' && $f !== '..' && $f !== 'barcode-common' && $f !== 'gs1ai') {
                        return true;
                    }
                    return false;
                });
            }
        }
    }

    if (count($tryFolders) > 0) {
        $file = implode('/', array_slice($splits, 2)) . '.php';
        foreach ($tryFolders as $folder) {
            $fullpath = $packageFolder . '/' . $folder . '/src/' . $file;
			//echo $fullpath . "<br>";
            if (file_exists($fullpath)) {
                include $fullpath;
                break;
            }
        }
    }
});
