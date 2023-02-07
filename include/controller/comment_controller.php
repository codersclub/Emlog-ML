<?php
/**
 * comment controller
 *
 * @package EMLOG
 * @link https://www.emlog.net
 */

class Comment_Controller {
	function addComment($params) {
		$name = isset($_POST['comname']) ? addslashes(trim($_POST['comname'])) : '';
		$content = isset($_POST['comment']) ? addslashes(trim($_POST['comment'])) : '';
		$mail = isset($_POST['commail']) ? addslashes(trim($_POST['commail'])) : '';
		$url = isset($_POST['comurl']) ? addslashes(trim($_POST['comurl'])) : '';
		$imgcode = isset($_POST['imgcode']) ? addslashes(strtoupper(trim($_POST['imgcode']))) : '';
		$blogId = isset($_POST['gid']) ? (int)$_POST['gid'] : -1;
		$pid = isset($_POST['pid']) ? (int)$_POST['pid'] : 0;
		$uid = 0;

		if (ISLOGIN === true) {
			$CACHE = Cache::getInstance();
			$user_cache = $CACHE->readCache('user');
			$name = addslashes($user_cache[UID]['name_orig']);
			$mail = addslashes($user_cache[UID]['mail']);
			$url = addslashes(BLOG_URL);
			$uid = UID;
		}

		if ($url && strncasecmp($url, 'http', 4)) {
			$url = 'https://' . $url;
		}

		doAction('comment_post');

		$Comment_Model = new Comment_Model();
		$Comment_Model->setCommentCookie($name, $mail, $url);
		if ($Comment_Model->isLogCanComment($blogId) === false) {
           emMsg(lang('comment_error_comment_disabled'));
		} elseif ($Comment_Model->isCommentExist($blogId, $name, $content) === true) {
           emMsg(lang('comment_error_content_exists'));
		} elseif (User::isVistor() && $Comment_Model->isCommentTooFast() === true) {
           emMsg(lang('comment_error_flood_control'));
		} elseif (empty($name)) {
           emMsg(lang('comment_error_name_enter'));
		} elseif (strlen($name) > 20) {
           emMsg(lang('comment_error_name_invalid'));
		} elseif ($mail != '' && !checkMail($mail)) {
           emMsg(lang('comment_error_email_invalid'));
		} elseif (ISLOGIN == false && $Comment_Model->isNameAndMailValid($name, $mail) === false) {
           emMsg(lang('comment_error_other_user'));
		} elseif (!empty($url) && preg_match("/^(http|https)\:\/\/[^<>'\"]*$/", $url) == false) {
           emMsg(lang('comment_error_url_invalid'),'javascript:history.back(-1);');
		} elseif (empty($content)) {
           emMsg(lang('comment_error_empty'));
		} elseif (strlen($content) > 60000) {
           emMsg(lang('comment_error_content_invalid'));
		} elseif (User::isVistor() && Option::get('comment_needchinese') == 'y' && !preg_match('/[\x{4e00}-\x{9fa5}]/iu', $content)) {
           emMsg(lang('comment_error_national_chars'));
		} elseif (ISLOGIN == false && Option::get('comment_code') == 'y' && session_start() && (empty($imgcode) || $imgcode !== $_SESSION['code'])) {
           emMsg(lang('comment_error_captcha_invalid'));
		}

		$_SESSION['code'] = null;
		notice::sendNewCommentMail($content, $blogId);
		$Comment_Model->addComment($uid, $name, $content, $mail, $url, $blogId, $pid);
	}
}
