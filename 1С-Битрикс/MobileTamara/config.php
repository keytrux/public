<?
header("Content-Type: application/x-javascript");
$hash = "12345678";
$config = array("appmap" =>
	array("main" => "/MobileTamara/",
		"left" => "/MobileTamara/menu.php",
		"settings" => "/MobileTamara/settings.php",
		"hash" => substr($hash, rand(1, strlen($hash)))
	)
);
echo json_encode($config);