<?php
/**
 * control pannel
 * @package EMLOG (www.emlog.net)
 */

/**
 * @var string $action
 * @var object $CACHE
 */

require_once 'globals.php';

if (empty($action)) {
	$avatar = empty($user_cache[UID]['avatar']) ? './views/images/avatar.svg' : '../' . $user_cache[UID]['avatar'];
	$name = $user_cache[UID]['name'];

	$serverapp = $_SERVER['SERVER_SOFTWARE'];
	$DB = Database::getInstance();
	$mysql_ver = $DB->getMysqlVersion();

	$max_execution_time = ini_get('max_execution_time') ?: '';
	$max_upload_size = ini_get('upload_max_filesize') ?: '';
	$php_ver = PHP_VERSION . ', ' . $max_execution_time . 's, ' . $max_upload_size;
	if (function_exists("curl_init")) {
		$c = curl_version();
		$php_ver .= ", curl" . $c['version'];
	}
	if (class_exists('ZipArchive', FALSE)) {
		$php_ver .= ', zip';
	}

	include View::getView('header');
	require_once(View::getView('index'));
	include View::getView('footer');
	View::output();
}

if ($action === 'get_news') {
	$emcurl = new EmCurl();
	$emcurl->request(OFFICIAL_SERVICE_HOST . 'services/messenger_pro.php');
	if ($emcurl->getHttpStatus() !== 200) {
		header('Content-Type: application/json; charset=UTF-8');
		exit('{"result":"fail"}');
	}
	$response = $emcurl->getRespone();
	header('Content-Type: application/json; charset=UTF-8');
	exit($response);
}