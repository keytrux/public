<?php
ini_set('display_errors', 'Off');

require_once 'src/Webhook.php';

$webhook = new Webhook();
echo $webhook->run($_REQUEST);