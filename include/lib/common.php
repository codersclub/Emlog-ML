<?php
/**
 * Common function library
 * @package EMLOG
 * @link https://www.emlog.net
 */

function emAutoload($class) {
	$class = strtolower($class);

	load_language($class);

	if (file_exists(EMLOG_ROOT . '/include/model/' . $class . '.php')) {
		require_once(EMLOG_ROOT . '/include/model/' . $class . '.php');
	} elseif (file_exists(EMLOG_ROOT . '/include/lib/' . $class . '.php')) {
		require_once(EMLOG_ROOT . '/include/lib/' . $class . '.php');
	} elseif (file_exists(EMLOG_ROOT . '/include/controller/' . $class . '.php')) {
		require_once(EMLOG_ROOT . '/include/controller/' . $class . '.php');
	} elseif (file_exists(EMLOG_ROOT . '/include/service/' . $class . '.php')) {
		require_once(EMLOG_ROOT . '/include/service/' . $class . '.php');
	}
}

/**
 * Convert HTML Code
 */
function htmlClean($content, $nl2br = true) {
	$content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
	if ($nl2br) {
		$content = nl2br($content);
	}
	$content = str_replace('  ', '&nbsp;&nbsp;', $content);
	$content = str_replace("\t", '&nbsp;&nbsp;&nbsp;&nbsp;', $content);
	return $content;
}

if (!function_exists('getIp')) {
	function getIp() {
		$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$list = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
			$ip = $list[0];
		}
		if (!ip2long($ip)) {
			$ip = '';
		}
		return $ip;
	}
}

/**
 * Get site URL (Only for the root directory script, currently used only for home ajax request)
 */
function getBlogUrl() {
	$phpself = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '';
	if (preg_match("/^.*\//", $phpself, $matches)) {
		return 'http://' . $_SERVER['HTTP_HOST'] . $matches[0];
	} else {
		return BLOG_URL;
	}
}

/**
 * Get the currently visited base url
 */
function realUrl() {
	static $real_url = NULL;

	if ($real_url !== NULL) {
		return $real_url;
	}

	$emlog_path = EMLOG_ROOT . DIRECTORY_SEPARATOR;
	$script_path = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME);
	$script_path = str_replace('\\', '/', $script_path);
	$path_element = explode('/', $script_path);

	$this_match = '';
	$best_match = '';

	$current_deep = 0;
	$max_deep = count($path_element);

	while ($current_deep < $max_deep) {
		$this_match .= $path_element[$current_deep] . DIRECTORY_SEPARATOR;

		if (substr($emlog_path, strlen($this_match) * (-1)) === $this_match) {
			$best_match = $this_match;
		}

		$current_deep++;
	}

	$best_match = str_replace(DIRECTORY_SEPARATOR, '/', $best_match);
	$real_url = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
	$real_url .= $_SERVER["SERVER_NAME"];
	$real_url .= in_array($_SERVER['SERVER_PORT'], array(80, 443)) ? '' : ':' . $_SERVER['SERVER_PORT'];
	$real_url .= $best_match;

	return $real_url;
}

/**
 * Check plugin
 */
function checkPlugin($plugin) {
	if (is_string($plugin) && preg_match("/^[\w\-\/]+\.php$/", $plugin) && file_exists(EMLOG_ROOT . '/content/plugins/' . $plugin)) {
		return true;
	}

	return false;
}

/**
 * Verify email address format
 */
function checkMail($email) {
	if (preg_match("/^[\w\.\-]+@\w+([\.\-]\w+)*\.\w+$/", $email) && strlen($email) <= 60) {
		return true;
	}

	return false;
}

/**
 * Substring encoded as utf8
 *
 * @param string $strings Preprocessed string
 * @param int $start Start position, eg:0
 * @param int $length Length
 */
function subString($strings, $start, $length) {
	if (function_exists('mb_substr') && function_exists('mb_strlen')) {
		$sub_str = mb_substr($strings, $start, $length, 'utf8');
		return mb_strlen($sub_str, 'utf8') < mb_strlen($strings, 'utf8') ? $sub_str . '...' : $sub_str;
	}
	$str = substr($strings, $start, $length);
	$char = 0;
	for ($i = 0, $iMax = strlen($str); $i < $iMax; $i++) {
		if (ord($str[$i]) >= 128)
			$char++;
	}
	$str2 = substr($strings, $start, $length + 1);
	$str3 = substr($strings, $start, $length + 2);
	if ($char % 3 == 1) {
		if ($length <= strlen($strings)) {
			$str3 = $str3 .= '...';
		}
		return $str3;
	}
	if ($char % 3 == 2) {
		if ($length <= strlen($strings)) {
			$str2 = $str2 .= '...';
		}
		return $str2;
	}
	if ($char % 3 == 0) {
		if ($length <= strlen($strings)) {
			$str = $str .= '...';
		}
		return $str;
	}
}

/**
 * Extract plain text from html content
 *
 * @param string $data
 * @param int $len
 */
function extractHtmlData($data, $len) {
	$data = subString(strip_tags($data), 0, $len + 30);
	$search = array(
		"/([\r\n])[\s]+/", // Remove whitespace characters
		"/&(quot|#34);/i", // Replace HTML entities
		"/&(amp|#38);/i",
		"/&(lt|#60);/i",
		"/&(gt|#62);/i",
		"/&(nbsp|#160);/i",
		"/&(iexcl|#161);/i",
		"/&(cent|#162);/i",
		"/&(pound|#163);/i",
		"/&(copy|#169);/i",
		"/\"/i",
	);
	$replace = array(" ", "\"", "&", " ", " ", "", chr(161), chr(162), chr(163), chr(169), "");
	$data = trim(subString(preg_replace($search, $replace, $data), 0, $len));
	return $data;
}

/**
 * Convert file size unit
 *
 * @param string $fileSize //File Size kb
 */
function changeFileSize($fileSize) {
	if ($fileSize >= 1073741824) {
		$fileSize = round($fileSize / 1073741824, 2) . ' GB';
	} elseif ($fileSize >= 1048576) {
		$fileSize = round($fileSize / 1048576, 2) . ' MB';
	} elseif ($fileSize >= 1024) {
		$fileSize = round($fileSize / 1024, 2) . ' KB';
	} else {
		$fileSize .= lang('_bytes');
	}
	return $fileSize;
}

/**
 * Get the file name suffix
 */
function getFileSuffix($fileName) {
	return strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
}

/**
 * Convert relative path to full URL, eg: ../content/uploadfile/xxx.jpeg
 * @param $filePath
 * @return string
 */
function getFileUrl($filePath) {
	if (!stristr($filePath, 'http')) {
		return BLOG_URL . substr($filePath, 3);
	}
	return $filePath;
}

/**
 * Remove the url parameter
 */
function rmUrlParams($url) {
	$urlInfo = explode("?", $url);
	if (empty($urlInfo[0])) {
		return $url;
	}
	return $urlInfo[0];
}

/**
 * Check if the file is an image, based on the file name extension
 */
function isImage($mimetype) {
	if (strpos($mimetype, "image") !== false) {
		return true;
	}
	return false;
}

/**
 * Check is this a video based on the file name suffix
 */
function isVideo($fileName) {
	$suffix = getFileSuffix($fileName);
	return $suffix === 'mp4';
}

/**
 * Determine whether to compress the package according to the file name suffix
 */
function isZip($fileName) {
	$suffix = getFileSuffix($fileName);
	if (in_array($suffix, ['zip', 'rar'])) {
		return true;
	}
	return false;
}

/**
 * Pagination Function
 *
 * @param int $count The total number of entries
 * @param int $perlogs The number of articles per page
 * @param int $page The current page number
 * @param string $url Page address
 * @return string
 */
function pagination($count, $perlogs, $page, $url, $anchor = '') {
	$pnums = @ceil($count / $perlogs);
	$re = '';
	$urlHome = preg_replace("|[\?&/][^\./\?&=]*page[=/\-]|", "", $url);
	for ($i = $page - 5; $i <= $page + 5 && $i <= $pnums; $i++) {
		if ($i <= 0) {
			continue;
		}
		if ($i == $page) {
			$re .= " <span>$i</span> ";
		} elseif ($i == 1) {
			$re .= " <a href=\"$urlHome$anchor\">$i</a> ";
		} else {
			$re .= " <a href=\"$url$i$anchor\">$i</a> ";
		}
	}
	if ($page > 6)
		$re = "<a href=\"{$urlHome}$anchor\" title=\"" . lang('first_page') . "\">&laquo;</a><em> ... </em>$re";
	if ($page + 5 < $pnums)
		$re .= "<em> ... </em> <a href=\"$url$pnums$anchor\" title=\"" . lang('last_page') . "\">&raquo;</a>";
	if ($pnums <= 1)
		$re = '';
	return $re;
}

/**
 * 该函数在插件中调用,挂载插件函数到预留的钩子上
 */
function addAction($hook, $actionFunc) {
	// 通过全局变量来存储挂载点上挂载的插件函数
	global $emHooks;
	if (!isset($emHooks[$hook]) || !in_array($actionFunc, $emHooks[$hook])) {
		$emHooks[$hook][] = $actionFunc;
	}
	return true;
}

/**
 * 挂载执行方式1（插入式挂载）：执行挂在钩子上的函数,支持多参数 eg:doAction('post_comment', $author, $email, $url, $comment);
 * eg：在挂载点插入扩展内容
 */
function doAction($hook) {
	global $emHooks;
	$args = array_slice(func_get_args(), 1);
	if (isset($emHooks[$hook])) {
		foreach ($emHooks[$hook] as $function) {
			call_user_func_array($function, $args);
		}
	}
}

/**
 * 挂载执行方式2（单次接管式挂载）：执行挂在钩子上的第一个函数,仅执行行一次，接收输入input，且会修改传入的变量$ret
 * eg：接管文件上传函数，将上传本地改为上传云端
 */
function doOnceAction($hook, $input, &$ret) {
	global $emHooks;
	$args = [$input, &$ret];
	$func = !empty($emHooks[$hook][0]) ? $emHooks[$hook][0] : '';
	if ($func) {
		call_user_func_array($func, $args);
	}
}

/**
 * 挂载执行方式3（轮流接管式挂载）：执行挂在钩子上的所有函数，上一个执行结果作为下一个的输入，且会修改传入的变量$ret
 * eg：不同插件对文章内容进行不同的修改替换。
 */
function doMultiAction($hook, $input, &$ret) {
	global $emHooks;
	$args = [$input, &$ret];
	if (isset($emHooks[$hook])) {
		foreach ($emHooks[$hook] as $function) {
			call_user_func_array($function, $args);
			$args = [&$ret, &$ret];
		}
	}
}

/**
 * Intercept the first len characters of the article content
 */
function subContent($content, $len, $clean = 0) {
	if ($clean) {
		$content = strip_tags($content);
	}
	return subString($content, 0, $len);
}

/**
 * Time transformation function
 *
 * @param $datetemp
 * @param $dstr
 * @return string
 */
function smartDate($datetemp, $dstr = 'Y-m-d H:i') {
	$sec = time() - $datetemp;
	$hover = floor($sec / 3600);
	if ($hover == 0) {
		$min = floor($sec / 60);
		if ($min == 0) {
			$op = $sec . lang('_sec_ago');
		} else {
			$op = $min . lang('_min_ago');
		}
	} elseif ($hover < 24) {
		$op = lang('about_') . $hover . lang('_hour_ago');
	} else {
		$op = date($dstr, $datetemp);
	}
	return $op;
}

/**
 * Generate a random string
 */
function getRandStr($length = 12, $special_chars = true) {
	$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	if ($special_chars) {
		$chars .= '!@#$%^&*()';
	}
	$randStr = '';
	for ($i = 0; $i < $length; $i++) {
		$randStr .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
	}
	return $randStr;
}

/**
 * Upload files to the current server
 * @param $attach array File information
 * @param $result array Upload result
 */
function upload2local($attach, &$result) {
	$fileName = $attach['name'];
	$errorNum = $attach['error'];
	$tmpFile = $attach['tmp_name'];
	$fileSize = $attach['size'];

	$isthum = Option::get('isthumbnail') === 'y';
	$fileName = Database::getInstance()->escape_string($fileName);
	$type = Option::getAttType();

	$ret = upload($fileName, $errorNum, $tmpFile, $fileSize, $type, $isthum);
	$success = 0;
	switch ($ret) {
		case '100':
			$message = lang('file_size_exceeds_system') . ini_get('upload_max_filesize') . lang('_limit');
			break;
		case '101':
		case '104':
			$message = lang('upload_failed_error_code') . $errorNum;
			break;
		case '102':
			$message = lang('file_type_not_supported');
			break;
		case '103':
			$r = changeFileSize(Option::getAttMaxSize());
			$message = lang('file_size_exceeds_') . $r . lang('_of_limit');
			break;
		case '105':
			$message = lang('upload_folder_unwritable');
			break;
		default:
			$message = lang('upload_ok');
			$success = 1;
			break;
	}

	$result = [
		'success'   => $success, // 1 success, 0 failure
		'message'   => $message,
		'url'       => $success ? getFileUrl($ret['file_path']) : '',
		'file_info' => $success ? $ret : [],
	];
}

/**
 * File Upload
 *
 * returned Array of indexes
 * mime_type File Type
 * size      File Size (in KB)
 * file_path File Path
 * width     Width
 * height    Height
 * Optional values (Only if the is an image and the system have to make a thjumbnail)
 * thum_file   Thumbnail path
 *
 * @param string $fileName File Name
 * @param string $errorNum Error code: $_FILES['error']
 * @param string $tmpFile Temporary File Uploaded
 * @param string $fileSize File Size KB
 * @param array $type Allowed to upload file types
 * @param boolean $is_thumbnail Whether to generate thumbnail
 * @return array File Data Index
 *
 */
function upload($fileName, $errorNum, $tmpFile, $fileSize, $type, $is_thumbnail = true) {
	if ($errorNum == 1) {
		return '100'; //File size exceeds the system limit
	} elseif ($errorNum > 1) {
		return '101'; //File upload failed
	}
	$extension = getFileSuffix($fileName);
	if (!in_array($extension, $type)) {
		return '102'; //Incorrect file type
	}
	if ($fileSize > Option::getAttMaxSize()) {
		return '103'; //File size exceeds the emlog limit
	}
	$file_info = [];
	$file_info['file_name'] = $fileName;
	$file_info['mime_type'] = get_mimetype($extension);
	$file_info['size'] = $fileSize;
	$file_info['width'] = 0;
	$file_info['height'] = 0;
	$uppath = Option::UPLOADFILE_PATH . gmdate('Ym') . '/';
	$fname = substr(md5($fileName), 0, 4) . time() . '.' . $extension;
	$attachpath = $uppath . $fname;
	$file_info['file_path'] = $attachpath;
	if (!is_dir(Option::UPLOADFILE_PATH)) {
		@umask(0);
		$ret = @mkdir(Option::UPLOADFILE_PATH, 0777);
		if ($ret === false) {
			return '104'; //Create the file upload directory failed
		}
	}
	if (!is_dir($uppath)) {
		@umask(0);
		$ret = @mkdir($uppath, 0777);
		if ($ret === false) {
			return '105'; //Upload failed. File upload directory (content/uploadfile) is not writable
		}
	}
	doAction('attach_upload', $tmpFile);

	// Generate thumbnail
	$thum = $uppath . 'thum-' . $fname;
	if ($is_thumbnail && resizeImage($tmpFile, $thum, Option::get('att_imgmaxw'), Option::get('att_imgmaxh'))) {
		$file_info['thum_file'] = $thum;
	}

	if (@is_uploaded_file($tmpFile) && @!move_uploaded_file($tmpFile, $attachpath)) {
		@unlink($tmpFile);
		return '105'; //Upload failed. File upload directory (content/uploadfile) is not writable
	}

	// Extract image width and height
	if (in_array($file_info['mime_type'], array('image/jpeg', 'image/png', 'image/gif', 'image/bmp'))) {
		$size = getimagesize($file_info['file_path']);
		if ($size) {
			$file_info['width'] = $size[0];
			$file_info['height'] = $size[1];
		}
	}
	return $file_info;
}

/**
 * Generate thumbnail image
 *
 * @param string $img Original image
 * @param string $thum_path Generate thumbnail path
 * @param int $max_w Maximum thumbnail width px
 * @param int $max_h Maximum thumbnail height px
 * @return unknown
 */
function resizeImage($img, $thum_path, $max_w, $max_h) {
	if (!in_array(getFileSuffix($thum_path), array('jpg', 'png', 'jpeg', 'gif'))) {
		return false;
	}
	if (!function_exists('ImageCreate')) {
		return false;
	}

	$size = chImageSize($img, $max_w, $max_h);
	$newwidth = $size['w'];
	$newheight = $size['h'];
	$w = $size['rc_w'];
	$h = $size['rc_h'];
	if ($w <= $max_w && $h <= $max_h) {
		return false;
	}
	return imageCropAndResize($img, $thum_path, 0, 0, 0, 0, $newwidth, $newheight, $w, $h);
}

/**
 * Image Crop & Resize
 *
 * @param string $src_image Original image
 * @param string $dst_path Cropped Image save path
 * @param int $dst_x New image coordinates x
 * @param int $dst_y New image coordinates y
 * @param int $src_x Original coordinates x
 * @param int $src_y Original coordinates y
 * @param int $dst_w New image width
 * @param int $dst_h New image height
 * @param int $src_w Original width
 * @param int $src_h Original height
 */
function imageCropAndResize($src_image, $dst_path, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h) {
	if (function_exists('imagecreatefromstring')) {
		$src_img = imagecreatefromstring(file_get_contents($src_image));
	} else {
		return false;
	}

	if (function_exists('imagecopyresampled')) {
		$new_img = imagecreatetruecolor($dst_w, $dst_h);
		imagecopyresampled($new_img, $src_img, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
	} elseif (function_exists('imagecopyresized')) {
		$new_img = imagecreate($dst_w, $dst_h);
		imagecopyresized($new_img, $src_img, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
	} else {
		return false;
	}

	switch (getFileSuffix($dst_path)) {
		case 'png':
			if (function_exists('imagepng') && imagepng($new_img, $dst_path)) {
				ImageDestroy($new_img);
				return true;
			}
			return false;
		case 'jpg':
		default:
			if (function_exists('imagejpeg') && imagejpeg($new_img, $dst_path)) {
				ImageDestroy($new_img);
				return true;
			}
			return false;
		case 'gif':
			if (function_exists('imagegif') && imagegif($new_img, $dst_path)) {
				ImageDestroy($new_img);
				return true;
			}
			return false;
	}
}

/**
 * Proportional image zoom size
 *
 * @param string $img Image Path
 * @param int $max_w Max zoom Width
 * @param int $max_h Maximum zoom Height
 * @return array
 */
function chImageSize($img, $max_w, $max_h) {
	$size = @getimagesize($img);
	if (!$size) {
		return [];
	}
	$w = $size[0];
	$h = $size[1];
	//Calculate zoom ratio
	@$w_ratio = $max_w / $w;
	@$h_ratio = $max_h / $h;
	//Verify the Image width and height
	if (($w <= $max_w) && ($h <= $max_h)) {
		$tn['w'] = $w;
		$tn['h'] = $h;
	} else if (($w_ratio * $h) < $max_h) {
		$tn['h'] = ceil($w_ratio * $h);
		$tn['w'] = $max_w;
	} else {
		$tn['w'] = ceil($h_ratio * $w);
		$tn['h'] = $max_h;
	}
	$tn['rc_w'] = $w;
	$tn['rc_h'] = $h;
	return $tn;
}

/**
 * Get Gravatar Avatar
 */
if (!function_exists('getGravatar')) {
	function getGravatar($email, $s = 40) {
		$hash = md5($email);
//vot		$gravatar_url = "//cravatar.cn/avatar/$hash?s=$s";
/*vot*/		$gravatar_url = "//www.gravatar.com/avatar/$hash?s=$s";
		doOnceAction('get_Gravatar', $email, $gravatar_url);

		return $gravatar_url;
	}
}

/**
 * Gets a number of days of the specified month
 */
function getMonthDayNum($month, $year) {
	$month = (int)$month;
	$year = (int)$year;

	$months_map = array(1 => 31, 3 => 31, 4 => 30, 5 => 31, 6 => 30, 7 => 31, 8 => 31, 9 => 30, 10 => 31, 11 => 30, 12 => 31);
	if (array_key_exists($month, $months_map)) {
		return $months_map[$month];
	}

	if ($year % 100 === 0) {
		if ($year % 400 === 0) {
			return 29;
		}
		return 28;
	}

	if ($year % 4 === 0) {
		return 29;
	}

	return 28;
}

/**
 * Extract zip
 * @param type $zipfile Original Zip File
 * @param type $path Extract to the directory
 * @param type $type
 * @return int
 */
function emUnZip($zipfile, $path, $type = 'tpl') {
	if (!class_exists('ZipArchive', FALSE)) {
		return 3;//zip Module problem
	}
	$zip = new ZipArchive();
	if (@$zip->open($zipfile) !== TRUE) {
		return 2;//File permissions problem
	}
	$r = explode('/', $zip->getNameIndex(0), 2);
	$dir = isset($r[0]) ? $r[0] . '/' : '';
	switch ($type) {
		case 'tpl':
			$re = $zip->getFromName($dir . 'header.php');
			if (false === $re) {
				return -2;
			}
			break;
		case 'plugin':
			$plugin_name = substr($dir, 0, -1);
			$re = $zip->getFromName($dir . $plugin_name . '.php');
			if (false === $re) {
				return -1;
			}
			break;
		case 'backup':
			$sql_name = substr($dir, 0, -1);
			if (getFileSuffix($sql_name) != 'sql') {
				return -3;
			}
			break;
		case 'update':
			break;
	}
	if (true === @$zip->extractTo($path)) {
		$zip->close();
		return 0;
	}

	return 1; //File permissions problem
}

/**
 * Zip compression
 */
function emZip($orig_fname, $content) {
	if (!class_exists('ZipArchive', FALSE)) {
		return false;
	}
	$zip = new ZipArchive();
	$tempzip = EMLOG_ROOT . '/content/cache/emtemp.zip';
	$res = $zip->open($tempzip, ZipArchive::CREATE);
	if ($res === TRUE) {
		$zip->addFromString($orig_fname, $content);
		$zip->close();
		$zip_content = file_get_contents($tempzip);
		unlink($tempzip);
		return $zip_content;
	}

	return false;
}

/**
 * Download remote files
 * @param string $source file url
 * @return string Temporary file path
 */
function emFetchFile($source) {
	$temp_file = tempnam(EMLOG_ROOT . '/content/cache/', 'tmp_');
	$wh = fopen($temp_file, 'w+b');

	$r = parse_url($source);
	if (isset($r['host']) && sha1($r['host']) !== '1ca2f71c0b27a1c6dbbf1583dc4d4e422b0683ac') {
		return FALSE;
	}

	$ctx_opt = set_ctx_option();
	$ctx = stream_context_create($ctx_opt);
	$rh = fopen($source, 'rb', false, $ctx);

	if (!$rh || !$wh) {
		return FALSE;
	}

	while (!feof($rh)) {
		if (fwrite($wh, fread($rh, 4096)) === FALSE) {
			return FALSE;
		}
	}
	fclose($rh);
	fclose($wh);
	return $temp_file;
}

/**
 * Download remote files
 * @param string $source file url
 * @return string Temporary file path
 */
function emDownFile($source) {
	$ctx_opt = set_ctx_option();
	$context = stream_context_create($ctx_opt);
	$content = file_get_contents($source, false, $context);
	if ($content === false) {
		return false;
	}

	$temp_file = tempnam(EMLOG_ROOT . '/content/cache/', 'tmp_');
	if ($temp_file === false) {
		emMsg('emDownFile：Failed to create temporary file.');
	}
	$ret = file_put_contents($temp_file, $content);
	if ($ret === false) {
		emMsg('emDownFile：Failed to write temporary file.');
	}

	return $temp_file;
}

function set_ctx_option() {
	$data = http_build_query(['emkey' => Option::get('emkey')]);
	return [
		'http' => [
			'timeout' => 120,
			'method'  => 'POST',
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n"
				. "Content-Length: " . strlen($data) . "\r\n"
				. "Referer: " . BLOG_URL . "\r\n"
				. "User-Agent: emlog " . Option::EMLOG_VERSION . "\r\n",
			'content' => $data
		],
		"ssl"  => [
			"verify_peer"      => false,
			"verify_peer_name" => false,
		]
	];
}

/**
 * Deleting a file or directory
 */
function emDeleteFile($file) {
	if (empty($file)) {
		return false;
	}
	if (@is_file($file)) {
		return @unlink($file);
	}
	$ret = true;
	if ($handle = @opendir($file)) {
		while ($filename = @readdir($handle)) {
			if ($filename == '.' || $filename == '..') {
				continue;
			}
			if (!emDeleteFile($file . '/' . $filename)) {
				$ret = false;
			}
		}
	} else {
		$ret = false;
	}
	@closedir($handle);
	if (file_exists($file) && !rmdir($file)) {
		$ret = false;
	}
	return $ret;
}

/**
 * Page Redirection
 */
function emDirect($directUrl) {
	header("Location: $directUrl");
	exit;
}

/**
 * Display system info
 *
 * @param string $msg Message
 * @param string $url Return Address
 * @param boolean $isAutoGo Whether or not auto-return true false
 */
function emMsg($msg, $url = 'javascript:history.back(-1);', $isAutoGo = false) {
	if ($msg == '404') {
		header("HTTP/1.1 404 Not Found");
		$msg = lang('404_description');
	}
	$lang = LANG;
	$dir = LANG_DIR;
	$title = lang('prompt');
	echo <<<EOT
<!doctype html>
<html lang="$lang" dir="$dir">
<head>
    <meta charset="utf-8">
EOT;
	if ($isAutoGo) {
		echo "<meta http-equiv=\"refresh\" content=\"2;url=$url\" />";
	}
	echo <<<EOT
<title>$title</title>
<style>
body {
    background-color:#4e73df;
    font-family: Arial;
    font-size: 12px;
    line-height:150%;
}
.main {
    background-color:#FFFFFF;
    font-size: 12px;
    color: #666666;
    width:650px;
    margin:60px auto 0px;
    border-radius: 10px;
    padding:10px;
    list-style:none;
    border:#DFDFDF 1px solid;
}
.main p {
    line-height: 18px;
    margin: 10px 20px;
}
a {
	color: #333333;
}
</style>
</head>
<body>
<div class="main">
<p>$msg</p>
EOT;
	if ($url != 'none') {
		echo '<p><a href="' . $url . '">&larr; ' . lang('click_return') . '</a></p>';
	}
	echo <<<EOT
</div>
</body>
</html>
EOT;
	exit;
}

function show_404_page($show_404_only = false) {
	if ($show_404_only) {
		header("HTTP/1.1 404 Not Found");
		exit;
	}

	if (is_file(TEMPLATE_PATH . '404.php')) {
		header("HTTP/1.1 404 Not Found");
		include View::getView('404');
		exit;
	}

	emMsg('404', BLOG_URL);
}

/**
 * hmac Encryption
 *
 * @param unknown_type $algo hash Algorithm md5
 * @param unknown_type $data User name and expiration date
 * @param unknown_type $key
 * @return unknown
 */
if (!function_exists('hash_hmac')) {
	function hash_hmac($algo, $data, $key) {
		$packs = array('md5' => 'H32', 'sha1' => 'H40');

		if (!isset($packs[$algo])) {
			return false;
		}

		$pack = $packs[$algo];

		if (strlen($key) > 64) {
			$key = pack($pack, $algo($key));
		} elseif (strlen($key) < 64) {
			$key = str_pad($key, 64, chr(0));
		}

		$ipad = (substr($key, 0, 64) ^ str_repeat(chr(0x36), 64));
		$opad = (substr($key, 0, 64) ^ str_repeat(chr(0x5C), 64));

		return $algo($opad . pack($pack, $algo($ipad . $data)));
	}
}

/**
 * Get the MIME type based on the file extension
 * @param string $extension
 * @return string
 */
function get_mimetype($extension) {
	$ct['htm'] = 'text/html';
	$ct['html'] = 'text/html';
	$ct['txt'] = 'text/plain';
	$ct['asc'] = 'text/plain';
	$ct['bmp'] = 'image/bmp';
	$ct['gif'] = 'image/gif';
	$ct['jpeg'] = 'image/jpeg';
	$ct['jpg'] = 'image/jpeg';
	$ct['jpe'] = 'image/jpeg';
	$ct['png'] = 'image/png';
	$ct['ico'] = 'image/vnd.microsoft.icon';
	$ct['mpeg'] = 'video/mpeg';
	$ct['mpg'] = 'video/mpeg';
	$ct['mpe'] = 'video/mpeg';
	$ct['qt'] = 'video/quicktime';
	$ct['mov'] = 'video/quicktime';
	$ct['avi'] = 'video/x-msvideo';
	$ct['wmv'] = 'video/x-ms-wmv';
	$ct['mp2'] = 'audio/mpeg';
	$ct['mp3'] = 'audio/mpeg';
	$ct['rm'] = 'audio/x-pn-realaudio';
	$ct['ram'] = 'audio/x-pn-realaudio';
	$ct['rpm'] = 'audio/x-pn-realaudio-plugin';
	$ct['ra'] = 'audio/x-realaudio';
	$ct['wav'] = 'audio/x-wav';
	$ct['css'] = 'text/css';
	$ct['zip'] = 'application/zip';
	$ct['pdf'] = 'application/pdf';
	$ct['doc'] = 'application/msword';
	$ct['bin'] = 'application/octet-stream';
	$ct['exe'] = 'application/octet-stream';
	$ct['class'] = 'application/octet-stream';
	$ct['dll'] = 'application/octet-stream';
	$ct['xls'] = 'application/vnd.ms-excel';
	$ct['ppt'] = 'application/vnd.ms-powerpoint';
	$ct['wbxml'] = 'application/vnd.wap.wbxml';
	$ct['wmlc'] = 'application/vnd.wap.wmlc';
	$ct['wmlsc'] = 'application/vnd.wap.wmlscriptc';
	$ct['dvi'] = 'application/x-dvi';
	$ct['spl'] = 'application/x-futuresplash';
	$ct['gtar'] = 'application/x-gtar';
	$ct['gzip'] = 'application/x-gzip';
	$ct['js'] = 'application/x-javascript';
	$ct['swf'] = 'application/x-shockwave-flash';
	$ct['tar'] = 'application/x-tar';
	$ct['xhtml'] = 'application/xhtml+xml';
	$ct['au'] = 'audio/basic';
	$ct['snd'] = 'audio/basic';
	$ct['midi'] = 'audio/midi';
	$ct['mid'] = 'audio/midi';
	$ct['m3u'] = 'audio/x-mpegurl';
	$ct['tiff'] = 'image/tiff';
	$ct['tif'] = 'image/tiff';
	$ct['rtf'] = 'text/rtf';
	$ct['wml'] = 'text/vnd.wap.wml';
	$ct['wmls'] = 'text/vnd.wap.wmlscript';
	$ct['xsl'] = 'text/xml';
	$ct['xml'] = 'text/xml';

	return isset($ct[strtolower($extension)]) ? $ct[strtolower($extension)] : 'text/html';
}

/**
 * Convert a string to a time zone independent UNIX timestamp
 */
function emStrtotime($timeStr) {
	if (!$timeStr) {
		return false;
	}

	$timezone = Option::get('timezone');

	$unixPostDate = strtotime($timeStr);
	if (!$unixPostDate) {
		return false;
	}

	$serverTimeZone = date_default_timezone_get();
	if (empty($serverTimeZone) || $serverTimeZone == 'UTC') {
		$unixPostDate -= (int)$timezone * 3600;
	} elseif ($serverTimeZone) {
		/*
		 * 如果服务器配置默认了时区，那么PHP将会把传入的时间识别为时区当地时间
		 * 但是我们传入的时间实际是blog配置的时区的当地时间，并不是服务器时区的当地时间
		 * 因此，我们需要将strtotime得到的时间去掉/加上两个时区的时差，得到utc时间
		 */
		$offset = getTimeZoneOffset($serverTimeZone);
		// 首先减去/加上本地时区配置的时差
		$unixPostDate -= (int)$timezone * 3600;
		// 再减去/加上服务器时区与utc的时差，得到utc时间
		$unixPostDate -= $offset;
	}
	return $unixPostDate;
}

function em_v() {
	if (mt_rand(1, 5) !== 5) {
		return true;
	}
	$a = sha1_file(EMLOG_ROOT . '/include/lib/emcurl.php');
	if ($a !== 'e84862f865a6bc46a797c8e1d1c63ec8ecd8064d') {
		exit;
	}
}

/**
 * Load jQuery
 */
function emLoadJQuery() {
	static $isJQueryLoaded = false;
	if (!$isJQueryLoaded) {
		global $emHooks;
		if (!isset($emHooks['index_head'])) {
			$emHooks['index_head'] = array();
		}
		array_unshift($emHooks['index_head'], 'loadJQuery');
		$isJQueryLoaded = true;

		function loadJQuery() {
			echo '<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>';
		}
	}
}

/**
 * Calculate time zone difference
 * @param string $remote_tz Remote time zone
 * @param string $origin_tz Original time zone
 *
 * @throws Exception
 */
function getTimeZoneOffset($remote_tz, $origin_tz = 'UTC') {
	if (($origin_tz === null) && !is_string($origin_tz = date_default_timezone_get())) {
		return false; // A UTC timestamp was returned -- bail out!
	}
	$origin_dtz = new DateTimeZone($origin_tz);
	$remote_dtz = new DateTimeZone($remote_tz);
	$origin_dt = new DateTime('now', $origin_dtz);
	$remote_dt = new DateTime('now', $remote_dtz);
	return $origin_dtz->getOffset($origin_dt) - $remote_dtz->getOffset($remote_dt);
}

/**
 * Upload the cut pictures (cover and avatar)
 */
function uploadCropImg() {
	$attach = isset($_FILES['image']) ? $_FILES['image'] : '';
	if (!$attach || $attach['error'] === 4) {
		echo "error";
		exit;
	}

	$ret = '';
	upload2local($attach, $ret);
	if (empty($ret['success'])) {
		echo "error";
		exit;
	}
	return $ret;
}

//------------------------------------------------------------------
// Functions added by Valery Votintsev (vot) at codersclub.org

/**
 * Unix Style Dir Name
 *
 * @param string $file //original path
 * @param boolean $remove_drive //If need to remove the Windows-like drive, i.e. C:\windows\system32\...
 * @return unix style path
 * @author Valery Votintsev, codersclub.org
 */
function udir($file = '', $remove_drive = false) {
	$file = str_replace('\\', '/', $file);
	if ($remove_drive) {
		$file = preg_replace("/^\w:/", '', $file);
	}
	return $file;
}


/**
 * Load Language File
 *
 * @param string $model //Language File Name
 * @return none
 * @author Valery Votintsev, codersclub.org
 */
function load_language($model = '') {
	global $LANGUAGE;
	global $LANGLIST;

	$model = strtolower($model);
	$model = str_replace(array('_controller', '_model'), '', $model);

	if (!isset($LANGUAGE)) {
		$LANGUAGE = array();
	}
	if (!isset($LANGLIST)) {
		$LANGLIST = array();
	}

	if ($model && !isset($LANGLIST[$model])) {
		$file = EMLOG_ROOT . '/lang/' . LANG . '/lang_' . $model . '.php';

		if (is_file($file)) {
			$lang = array();
			$ok = @require_once $file;

			// Language file must contain $lang = array(...);
			$LANGUAGE = array_merge($LANGUAGE, $lang);
			unset($lang);
			$LANGLIST[$model] = 1;
		}
	}
}

/**
 * Return Language Variable
 *
 * @param string $key //Language Keyword
 * @return string //Language Value
 * @author Valery Votintsev, codersclub.org
 */
function lang($key = '') {
	global $LANGUAGE;
	return isset($LANGUAGE[$key]) ? $LANGUAGE[$key] : '{' . $key . '}';
}

/**
 * Return the date/time formatted
 *
 * @param integer $date Source date
 * @param boolean $show_time Show time or not
 * @return string Formatted date
 * @author Valery Votintsev, codersclub.org
 */
function emdate($date = 0, $show_time = 0) {
	$format = $show_time ? 'date_time_format' : 'date_format';

	return gmdate(lang($format), $date);
}

/**
 * Show debug info
 * @param $data
 * @param string $name
 */
function dump($data, $name = '') {
	$buf = var_export($data, true);

	$buf = str_replace('\\r', '', $buf);
	$buf = preg_replace('/\=\>\s*\n\s*array/s', '=> array', $buf);

	echo '<pre>';

	if ($name) {
		echo $name, '=';
	}

	echo $buf;
	echo "</pre>\n";
}

if (!function_exists('split')) {
	function split($str, $delimiter) {
		return preg_split($str, $delimiter);
	}
}

em_v();

if (!function_exists('get_os')) {
	function get_os($user_agent) {
		if (false !== stripos($user_agent, "win")) {
			$os = 'Windows';
		} else if (false !== stripos($user_agent, "mac")) {
			$os = 'MAC';
		} else if (false !== stripos($user_agent, "linux")) {
			$os = 'Linux';
		} else if (false !== stripos($user_agent, "unix")) {
			$os = 'Unix';
		} else if (false !== stripos($user_agent, "bsd")) {
			$os = 'BSD';
		} else {
			$os = 'unknown';
		}
		return $os;
	}
}

if (!function_exists('get_browse')) {
	function get_browse($user_agent) {
		if (false !== stripos($user_agent, "MSIE")) {
			$br = 'MSIE';
		} else if (false !== stripos($user_agent, "Edg")) {
			$br = 'Edge';
		} else if (false !== stripos($user_agent, "Firefox")) {
			$br = 'Firefox';
		} else if (false !== stripos($user_agent, "Chrome")) {
			$br = 'Chrome';
		} else if (false !== stripos($user_agent, "Safari")) {
			$br = 'Safari';
		} else if (false !== stripos($user_agent, "Opera")) {
			$br = 'Opera';
		} else {
			$br = 'unknown';
		}
		return $br;
	}
}

function backtrace() {
	$raw = debug_backtrace();

	echo '<div><b>BackTrace:</b>', "\n";
	echo '<table border="1" cellPadding="4">', "\n";
	echo '<tr>', "\n";
	echo '<th>File</th>', "\n";
	echo '<th>Line</th>', "\n";
	echo '<th>Function</th>', "\n";
	echo '<th>Args</th>', "\n";
	echo '</tr>', "\n";

	foreach ($raw as $entry) {
		$args = '';

//DEBUG
//echo '<pre>';
//echo 'entry: ';
//print_r($entry);
//echo '</pre>';

		if ($entry['function'] != 'backtrace') {
			echo '<tr>', "\n";
			echo '<td>', $entry['file'], '</td>', "\n";
			echo '<td>', $entry['line'], '</td>', "\n";
			echo '<td>', $entry['function'], '</td>', "\n";

			foreach ($entry['args'] as $a) {
				if (!empty($args)) {
					$args .= ', ';
				}
				switch (gettype($a)) {
					case 'integer':
					case 'double':
						$args .= $a;
						break;
					case 'string':
						$a = htmlspecialchars(substr($a, 0, 64)) . ((strlen($a) > 64) ? '...' : '');
						$args .= "\"$a\"";
						break;
					case 'array':
						$args .= 'Array(' . count($a) . ')';
						break;
					case 'object':
						$args .= 'Object(' . get_class($a) . ')';
						break;
					case 'resource':
//            $args .= 'Resource('.strstr($a, '#').')';
						$args .= $a;
						break;
					case 'boolean':
						$args .= $a ? 'True' : 'False';
						break;
					case 'NULL':
						$args .= 'Null';
						break;
					default:
						$args .= 'Unknown';
				}
			}
			if (!$args) $args = '&nbsp;';
			echo '<td>', $args, '</td>', "\n";
			echo '</tr>', "\n";
		}
	}

	echo '</table>', "\n";
}

// Removes parameter '$key' from '$sourceURL' query string (if present)
function removeParam($key, $sourceURL) {
	$url = parse_url($sourceURL);
	if (!isset($url['query'])) return $sourceURL;
	parse_str($url['query'], $query_data);
	if (!isset($query_data[$key])) return $sourceURL;
	unset($query_data[$key]);
	$url['query'] = http_build_query($query_data);
	return build_url($url);
}

function build_url($parsed_url) {
	$scheme = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
	$host = isset($parsed_url['host']) ? $parsed_url['host'] : '';
	$port = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
	$user = isset($parsed_url['user']) ? $parsed_url['user'] : '';
	$pass = isset($parsed_url['pass']) ? ':' . $parsed_url['pass'] : '';
	$pass = ($user || $pass) ? "$pass@" : '';
	$path = isset($parsed_url['path']) ? $parsed_url['path'] : '';
	$query = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
	$query = ($query == '?') ? '' : $query;
	$fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';
	return "$scheme$user$pass$host$port$path$query$fragment";
}
