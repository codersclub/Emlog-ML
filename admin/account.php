<?php
/**
 * Background global item loading
 * @package EMLOG (www.emlog.net)
 */

/**
 * @var string $action
 * @var object $CACHE
 */

require_once '../init.php';

$sta_cache = $CACHE->readCache('sta');
$user_cache = $CACHE->readCache('user');
$action = isset($_GET['action']) ? addslashes($_GET['action']) : '';
$admin_path_code = isset($_GET['s']) ? addslashes(htmlClean($_GET['s'])) : '';

if ($action == 'login') {
	if (defined('ADMIN_PATH_CODE') && $admin_path_code !== ADMIN_PATH_CODE) {
		show_404_page(true);
	}
	$username = isset($_POST['user']) ? addslashes(trim($_POST['user'])) : '';
	$password = isset($_POST['pw']) ? addslashes(trim($_POST['pw'])) : '';
	$ispersis = isset($_POST['ispersis']) ? (int)$_POST['ispersis'] : 0;
	$img_code = Option::get('login_code') == 'y' && isset($_POST['imgcode']) ? addslashes(trim(strtoupper($_POST['imgcode']))) : '';

	$uid = LoginAuth::checkUser($username, $password, $img_code);

	if ($uid > 0) {
		Register::isRegServer();
		$User_Model = new User_Model();
		$User_Model->updateUser(['ip'=>getIp()], $uid);
		LoginAuth::setAuthCookie($username, $ispersis);
		emDirect("./");
	} else {
		LoginAuth::loginPage($uid);
	}
}

if ($action == 'logout') {
	setcookie(AUTH_COOKIE_NAME, ' ', time() - 31536000, '/');
	emDirect("../");
}

if ($action == 'signup') {
	if (ISLOGIN === true) {
		emDirect("../admin");
	}
	include View::getAdmView('user_head');
	require_once View::getAdmView('register');
	View::output();
}

if ($action == 'reset') {
	if (ISLOGIN === true) {
		emDirect("../admin");
	}
	include View::getAdmView('user_head');
	require_once View::getAdmView('reset');
	View::output();
}

if ($action == 'reset') {
	if (ISLOGIN === true) {
		emDirect("../admin");
	}
	include View::getAdmView('user_head');
	require_once View::getAdmView('reset');
	View::output();
}

if ($action == 'reset_password') {
	if (ISLOGIN === true) {
		emDirect("../admin");
	}
	include View::getAdmView('user_head');
	require_once View::getAdmView('reset');
	View::output();
}

if ($action == 'send_auth_code') {
	$to_user = "xxxx@gmail.com";
/*vot*/	$title = lang('mail_test_header');
/*vot*/	$content = lang('mail_test_content');
	$sendmail_model = new SendMail();
	$ret = $sendmail_model->send($to_user, $title, $content);
	if($ret){
/*vot*/		echo lang('mail_send_ok');
	}else{
/*vot*/		echo lang('mail_send_error');
	}
}
