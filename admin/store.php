<?php
/**
 * store
 * @package EMLOG
 * @link https://www.emlog.net
 */

/**
 * @var string $action
 * @var object $CACHE
 */

require_once 'globals.php';

$Store_Model = new Store_Model();

if (empty($action)) {
	$tag = Input::getStrVar('tag');
	$page = Input::getIntVar('page', 1);
	$keyword = Input::getStrVar('keyword');
	$author_id = Input::getStrVar('author_id');

	$r = $Store_Model->getTemplates($tag, $keyword, $page, $author_id);
	$templates = $r['templates'];
	$count = $r['count'];
	$sub_title = lang('template') . ' ' . ($tag === 'free' ? lang('free_zone') : lang('paid_zone'));

	$subPage = '';
	foreach ($_GET as $key => $val) {
		$subPage .= $key != 'page' ? "&$key=$val" : '';
	}

	$pageurl = pagination($count, 30, $page, "store.php?{$subPage}&page=");

	include View::getAdmView('header');
	require_once(View::getAdmView('store_tpl'));
	include View::getAdmView('footer');
	View::output();
}

if ($action === 'plu') {
	$tag = Input::getStrVar('tag');
	$page = Input::getIntVar('page', 1);
	$keyword = Input::getStrVar('keyword');
	$author_id = Input::getStrVar('author_id');

	$r = $Store_Model->getPlugins($tag, $keyword, $page, $author_id);
	$plugins = $r['plugins'];
	$count = $r['count'];
	$sub_title = lang('plugin') . ' ' . ($tag === 'free' ? lang('free_zone') : lang('paid_zone'));	

	$subPage = '';
	foreach ($_GET as $key => $val) {
		$subPage .= $key != 'page' ? "&$key=$val" : '';
	}
	$pageurl = pagination($count, 50, $page, "store.php?{$subPage}&page=");

	include View::getAdmView('header');
	require_once(View::getAdmView('store_plu'));
	include View::getAdmView('footer');
	View::output();
}

if ($action === 'mine') {
	$addons = $Store_Model->getMyAddon();
/*vot*/	$sub_title = ('my_apps');

	include View::getAdmView('header');
	require_once(View::getAdmView('store_mine'));
	include View::getAdmView('footer');
	View::output();
}

if ($action === 'error') {
	$keyword = '';
	$sub_title = '';

	include View::getAdmView('header');
	require_once(View::getAdmView('store_tpl'));
	include View::getAdmView('footer');
	View::output();
}

if ($action === 'install') {
	$source = isset($_GET['source']) ? trim($_GET['source']) : '';
	$source_type = isset($_GET['type']) ? trim($_GET['type']) : '';

	if (!Register::isRegLocal()) {
		exit('您的emlog pro尚未注册，<a href="auth.php">去注册</a>');
	}

	if (empty($source)) {
		exit('安装失败');
	}

	$temp_file = emFetchFile('https://emlog.io/' . $source);
	if (!$temp_file) {
		exit('安装失败，无法下载安装包');
	}

	if ($source_type == 'tpl') {
		$unzip_path = '../content/templates/';
		$store_path = './store.php?';
		$suc_url = 'template.php';
	} else {
		$unzip_path = '../content/plugins/';
		$store_path = './store.php?action=plu&';
		$suc_url = 'plugin.php';
	}

	$ret = emUnZip($temp_file, $unzip_path, $source_type);
	@unlink($temp_file);
	switch ($ret) {
		case 0:
			exit('安装成功 <a href="' . $suc_url . '">去查看</a>');
		case 1:
		case 2:
			exit('安装失败，请检查content下目录是否可写');
		case 3:
			exit('安装失败，请安装php的Zip扩展');
		default:
			exit('安装失败，不是有效的安装包');
	}
}
