<?php

use \Firebase\JWT\JWT;

require_once 'jwt.lib.php';

if (!isset($dirRoot)) {
    $dirRoot = '';
}

//
//  Config
//

class AppConfig {

    var $appId = 'APP-ID';
    var $appUid = 'APP-UID';
    var $secretKey = 'SECRET-KEY';

    var $appBaseUrl = 'APP-BASE-URL';

    var $moyskladVendorApiEndpointUrl = 'https://apps-api.moysklad.ru/api/vendor/1.0';
    var $moyskladJsonApiEndpointUrl = 'https://api.moysklad.ru/api/remap/1.2';

    public function __construct(array $cfg)
    {
        foreach ($cfg as $k => $v) {
            $this->$k = $v;
        }
    }
}

$cfg = new AppConfig(require('config.php'));

function cfg(): AppConfig {
    return $GLOBALS['cfg'];
}

//
//  Vendor API 1.0
//

class VendorApi {

    function context(string $contextKey) {
        return $this->request('POST', '/context/' . $contextKey);
    }
	
	/*
    function updateAppStatus(string $appId, string $accountId, string $status) {
        return $this->request('PUT', "/apps/".$appId."/".$accountId."/status", "{\"status\": ".$status."}");
    }*/

    private function request(string $method, $path, $body = null) {
        return makeHttpRequest($method, cfg()->moyskladVendorApiEndpointUrl . $path, buildJWT(), $body);
    }

}

function makeHttpRequest(string $method, string $url, string $bearerToken, $body = null) {
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
	$authorization = "Authorization: Bearer " . $bearerToken;
	curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json", $authorization));
    curl_setopt($curl, CURLOPT_ENCODING , 'gzip');
	if ($body) curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($curl);
	curl_close($curl);
	return json_decode($result);
}

function makeHttpRequest2(string $method, string $url, string $bearerToken, $body = null) {
    $opts = $body ? array('http' =>
		array(
			'method'  => $method,
			'header'  => array('Authorization: Bearer ' . $bearerToken, "Content-type: application/json"),
			'content' => $body
		)
	) : array('http' =>
		array(
			'method'  => $method,
			'header'  => 'Authorization: Bearer ' . $bearerToken
		)
	);
    $context = stream_context_create($opts);
    $result = file_get_contents($url, false, $context);
    return json_decode($result);
}

$vendorApi = new VendorApi();

function vendorApi(): VendorApi {
    return $GLOBALS['vendorApi'];
}

function buildJWT() {
    $token = array(
        "sub" => cfg()->appUid,
        "iat" => time(),
        "exp" => time() + 300,
        "jti" => bin2hex(random_bytes(32))
    );
    return JWT::encode($token, cfg()->secretKey);
}


//
//  JSON API 1.2
//

class JsonApi {

    private $accessToken;

    function __construct(string $accessToken) {
        $this->accessToken = $accessToken;
    }

    function api(string $method, $entity, $filter = "", $body = null) {
        return makeHttpRequest($method, cfg()->moyskladJsonApiEndpointUrl . $entity . $filter, $this->accessToken, $body);
    }
}

function jsonApi(): JsonApi {
    if (!$GLOBALS['jsonApi']) {
        $GLOBALS['jsonApi'] = new JsonApi(AppInstance::get()->accessToken);
    }
    return $GLOBALS['jsonApi'];
}

//
//  Logging
//

function loginfo($name, $msg) {
    global $dirRoot;
    $logDir = $dirRoot . 'logs';
    @mkdir($logDir);
    file_put_contents($logDir . '/log.txt', date(DATE_W3C) . ' [' . $name . '] '. $msg . "\n", FILE_APPEND);
}

//
//  AppInstance state
//

$currentAppInstance = null;

class AppInstance {

    const UNKNOWN = 100;
    const SETTINGS_REQUIRED = 1;
    const ACTIVATED = 100;

    var $appId;
    var $accountId;
    var $infoMessage;
    //var $store;

    var $accessToken;

    var $status = AppInstance::UNKNOWN;

    static function get(): AppInstance {
        $app = $GLOBALS['currentAppInstance'];
        if (!$app) {
            throw new InvalidArgumentException("There is no current app instance context");
        }
        return $app;
    }

    public function __construct($appId, $accountId) {
        $this->appId = $appId;
        $this->accountId = $accountId;
    }

    function getStatusName() {
        switch ($this->status) {
            case self::SETTINGS_REQUIRED:
                return 'SettingsRequired';
            case self::ACTIVATED:
                return 'Activated';
        }
        return null;
    }

    function persist() {
        @mkdir('data');
        file_put_contents($this->filename(), serialize($this));
    }

    function delete() {
        @unlink($this->filename());
    }

    private function filename() {
        return self::buildFilename($this->appId, $this->accountId);
    }

    private static function buildFilename($appId, $accountId) {
        return $GLOBALS['dirRoot'] . "data/" . $appId . $accountId . ".app";
    }

    static function loadApp($accountId): AppInstance {
        return self::load(cfg()->appId, $accountId);
    }

    static function load($appId, $accountId): AppInstance {
        $data = @file_get_contents(self::buildFilename($appId, $accountId));
        if ($data === false) {
            $app = new AppInstance($appId, $accountId);
        } else {
            $app = unserialize($data);
        }
        $GLOBALS['currentAppInstance'] = $app;
        return $app;
    }

}