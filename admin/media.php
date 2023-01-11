<?php
/**
 * media
 * @package EMLOG
 * @link https://www.emlog.net
 */

/**
 * @var string $action
 * @var object $CACHE
 */

require_once 'globals.php';

$DB = Database::getInstance();

$Media_Model = new Media_Model();
$MediaSortModel = new MediaSort_Model();

if (empty($action)) {
	$sid = Input::getIntVar('sid');
	$page = Input::getIntVar('page', 1);
	$uid = User::haveEditPermission() ? null : UID;
	$page_count = 24;
	$page_url = $sid ? "media.php?sid=$sid&page=" : "media.php?page=";
	$medias = $Media_Model->getMedias($page, $page_count, $uid, $sid);
	$count = $Media_Model->getMediaCount($uid, $sid);
	$page = pagination($count, $page_count, $page, $page_url);

	$sorts = $MediaSortModel->getSorts();

	include View::getAdmView('header');
	require_once(View::getAdmView('media'));
	include View::getAdmView('footer');
	View::output();
}

if ($action === 'lib') {
	$page = Input::getIntVar('page', 1);
	$perpage_count = 48;
	$medias = $Media_Model->getMedias($page, $perpage_count);
	$count = $Media_Model->getMediaCount();
	$pageurl = pagination($count, $perpage_count, $page, "media.php?page=");
	require_once(View::getAdmView('media_lib'));
	View::output();
}

if ($action === 'upload') {
	$sid = Input::getIntVar('sid');
	$editor = isset($_GET['editor']) ? 1 : 0; // Whether the upload is from the Markdown editor
	$attach = isset($_FILES['file']) ? $_FILES['file'] : '';
	if ($editor) {
		$attach = isset($_FILES['editormd-image-file']) ? $_FILES['editormd-image-file'] : '';
	}

	if (!$attach || $attach['error'] === 4) {
		if ($editor) {
			echo json_encode(['success' => 0, 'message' => 'upload error']);
		} else {
			header("HTTP/1.0 400 Bad Request");
			echo "upload error";
		}
		exit;
	}

	$ret = '';

	addAction('upload_media', 'upload2local');
	doOnceAction('upload_media', $attach, $ret);

	if (empty($ret['success'])) {
		if ($editor) {
			echo json_encode($ret);
		} else {
			header("HTTP/1.0 400 Bad Request");
			echo $ret['message'];
		}
		exit;
	}

	$aid = $Media_Model->addMedia($ret['file_info'], $sid);
	if ($editor) {
		echo json_encode($ret);
	} else {
		echo 'success';
	}
}

if ($action === 'delete') {
	LoginAuth::checkToken();
	$aid = Input::getIntVar('aid');
	$Media_Model->deleteMedia($aid);
	emDirect("media.php?active_del=1");
}

if ($action === 'operate_media') {
	$operate = Input::postStrVar('operate');
	$sort = Input::postIntVar('sort');
	$aids = isset($_POST['aids']) ? array_map('intval', $_POST['aids']) : array();

	LoginAuth::checkToken();
	switch ($operate) {
		case 'del':
			foreach ($aids as $value) {
				$Media_Model->deleteMedia($value);
			}
			emDirect("media.php?active_del=1");
			break;
		case 'move':
			foreach ($aids as $id) {
				$Media_Model->updateMedia(['sortid' => $sort], $id);
			}
			emDirect("media.php?active_mov=1");
			break;
	}
}

if ($action === 'add_media_sort') {
	if (!User::isAdmin()) {
		emMsg(lang('no_permission'), './');
	}
	$sortname = Input::postStrVar('sortname');
	if (empty($sortname)) {
		emDirect("./media.php?error_a=1");
	}

	$MediaSortModel->addSort($sortname);
	emDirect("./media.php?active_add=1");
}

if ($action === 'update_media_sort') {
	if (!User::isAdmin()) {
		emMsg(lang('no_permission'), './');
	}
	$sortname = Input::postStrVar('sortname');
	$id = isset($_POST['id']) ? (int)$_POST['id'] : '';

	if (empty($sortname)) {
		emDirect("./media.php?error_a=1");
	}

	$MediaSortModel->updateSort(["sortname" => $sortname], $id);
	emDirect("./media.php?active_edit=1");
}

if ($action === 'del_media_sort') {
	if (!User::isAdmin()) {
		emMsg(lang('no_permission'), './');
	}
	$id = Input::getIntVar('id');

	LoginAuth::checkToken();

	$MediaSortModel->deleteSort($id);
	emDirect("./media.php?active_del=1");
}
