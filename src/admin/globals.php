<?php
/**
 * Load Background Global items
 * @copyright (c) Emlog All Rights Reserved
 */

require_once '../init.php';

define('TEMPLATE_PATH', EMLOG_ROOT.'/admin/views/');//Background current template path

//Read the Cache
$sta_cache = $CACHE->readCache('sta');
$user_cache = $CACHE->readCache('user');
$action = isset($_GET['action']) ? addslashes($_GET['action']) : '';

//Login authentication
if ($action == 'login') {
	$username = isset($_POST['user']) ? addslashes(trim($_POST['user'])) : '';
	$password = isset($_POST['pw']) ? addslashes(trim($_POST['pw'])) : '';
	$ispersis = isset($_POST['ispersis']) ? intval($_POST['ispersis']) : false;
	$img_code = Option::get('login_code') == 'y' && isset($_POST['imgcode']) ? addslashes(trim(strtoupper($_POST['imgcode']))) : '';

	if (checkUser($username, $password, $img_code) === true) {
		setAuthCookie($username, $ispersis);
		emDirect("./");
	}else{
		loginPage();
	}
}

//Logout
if ($action == 'logout'){
	setcookie(AUTH_COOKIE_NAME, ' ', time() - 31536000, '/');
	emDirect("../");
}

if(ISLOGIN === false){
	loginpage();
}

$request_uri = strtolower(substr(basename($_SERVER['SCRIPT_NAME']), 0, -4));
if (ROLE == 'writer' && !in_array($request_uri, array('write_log','admin_log','twitter','attachment','blogger','comment','index','save_log','trackback'))){
	emMsg($lang['access_disabled'],'./');
}
