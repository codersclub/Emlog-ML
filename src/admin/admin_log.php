<?php
/**
 * Posts Management
 * @copyright (c) Emlog All Rights Reserved
 * @version emlog-3.3.0
 * $Id$
 */

require_once('globals.php');
require_once(EMLOG_ROOT.'/model/C_blog.php');
require_once(EMLOG_ROOT.'/model/C_tag.php');
require_once(EMLOG_ROOT.'/model/C_user.php');

$emBlog = new emBlog($DB);

//Published/Drafts posts management page
if($action == '')
{
	$emTag = new emTag($DB);
	$emUser = new emUser($DB);

	$pid = isset($_GET['pid']) ? $_GET['pid'] : '';
	$tagId = isset($_GET['tagid']) ? intval($_GET['tagid']) : '';
	$sid = isset($_GET['sid']) ? intval($_GET['sid']) : '';
	$uid = isset($_GET['uid']) ? intval($_GET['uid']) : '';
	$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

	$sortView = (isset($_GET['sortView']) && $_GET['sortView'] == 'ASC') ?  'DESC' : 'ASC';
	$sortComm = (isset($_GET['sortComm']) && $_GET['sortComm'] == 'ASC') ?  'DESC' : 'ASC';
	$sortDate = (isset($_GET['sortDate']) && $_GET['sortDate'] == 'DESC') ?  'ASC' : 'DESC';

	$sqlSegment = '';
	if($tagId)
	{
		$blogIdStr = $emTag->getTagById($tagId);
		$sqlSegment = "and gid IN ($blogIdStr)";
	}elseif ($sid){
		$sqlSegment = "and sortid=$sid";
	}elseif ($uid){
		$sqlSegment = "and author=$uid";
	}
	$sqlSegment .= ' ORDER BY ';
	if(isset($_GET['sortView']))
	{
		$sqlSegment .= "views $sortView";
	}elseif(isset($_GET['sortComm'])){
		$sqlSegment .= "comnum $sortComm";
	}elseif(isset($_GET['sortDate'])){
		$sqlSegment .= "date $sortDate";
	}else {
		$sqlSegment .= 'top DESC,date DESC';
	}

	$hide_state = $pid ? 'y' : 'n';
	if($pid == 'draft')
	{
		$hide_state = 'y';
		$sorturl = '&pid=draft';
		$pwd = $lang['drafts'];
	}else{
		$hide_state = 'n';
		$sorturl = '';
		$pwd = $lang['published'];
	}

	$logNum = $emBlog->getLogNum($hide_state, $sqlSegment, 'blog', 1);
	$logs = $emBlog->getLogsForAdmin($sqlSegment, $hide_state, $page);
	$sorts = $sort_cache;
	$users = $user_cache;
	$tags = $emTag->getTag();

	$subPage = '';
	foreach ($_GET as $key=>$val)
	{
		$subPage .= $key != 'page' ? "&$key=$val" : '';
	}
	$pageurl =  pagination($logNum, ADMIN_PERPAGE_NUM, $page, "admin_log.php?{$subPage}&page");

	include getViews('header');
	require_once(getViews('admin_log'));
	include getViews('footer');cleanPage();
}

//Blog Operation
if($action == 'operate_log')
{
	$operate = isset($_POST['operate']) ? $_POST['operate'] : '';
	$pid = isset($_POST['pid']) ? $_POST['pid'] : '';
	$logs = isset($_POST['blog']) ? array_map('intval', $_POST['blog']) : array();
	$sort = isset($_POST['sort']) ? intval($_POST['sort']) : '';
	$author = isset($_POST['author']) ? intval($_POST['author']) : '';

	if($operate == '')
	{
		header("Location: ./admin_log.php?pid=$pid&error_b=true");
		exit;
	}
	if(empty($logs))
	{
		header("Location: ./admin_log.php?pid=$pid&error_a=true");
		exit;
	}

	switch ($operate)
	{
		case 'del':
			foreach($logs as $val)
			{
				$emBlog->deleteLog($val);
				doAction('del_log', $val);
			}
			$CACHE->mc_sta();
			$CACHE->mc_user();
			$CACHE->mc_record();
			$CACHE->mc_comment();
			$CACHE->mc_logtags();
			$CACHE->mc_logatts();
			$CACHE->mc_tags();
			$CACHE->mc_newlog();
			$CACHE->mc_logsort();
			$CACHE->mc_sort();
			if($pid == 'draft')
			{
				header("Location: ./admin_log.php?pid=draft&active_del=true");
			}else{
				header("Location: ./admin_log.php?active_del=true");
			}
			break;
		case 'top':
			foreach($logs as $val)
			{
				$emBlog->updateLog(array('top'=>'y'), $val);
			}
			header("Location: ./admin_log.php?active_up=true");
			break;
		case 'notop':
			foreach($logs as $val)
			{
				$emBlog->updateLog(array('top'=>'n'), $val);
			}
			header("Location: ./admin_log.php?active_down=true");
			break;
		case 'hide':
			foreach($logs as $val)
			{
				$emBlog->hideSwitch($val, 'y');
			}
			$CACHE->mc_sta();
			$CACHE->mc_user();
			$CACHE->mc_record();
			$CACHE->mc_logtags();
			$CACHE->mc_logatts();
			$CACHE->mc_newlog();
			$CACHE->mc_logsort();
			$CACHE->mc_sort();
			$CACHE->mc_tags();
			$CACHE->mc_comment();

			header("Location: ./admin_log.php?active_hide=true");
			break;
		case 'pub':
			foreach($logs as $val)
			{
				$emBlog->hideSwitch($val, 'n');
			}

			$CACHE->mc_sta();
			$CACHE->mc_user();
			$CACHE->mc_record();
			$CACHE->mc_logtags();
			$CACHE->mc_logatts();
			$CACHE->mc_newlog();
			$CACHE->mc_logsort();
			$CACHE->mc_sort();
			$CACHE->mc_tags();
			$CACHE->mc_comment();

			header("Location: ./admin_log.php?pid=draft&active_post=true");
			break;
		case 'move':
			foreach($logs as $val)
			{
				$emBlog->updateLog(array('sortid'=>$sort), $val);
			}
			$CACHE->mc_sort();
			$CACHE->mc_logsort();
			header("Location: ./admin_log.php?active_move=true");
			break;
		case 'change_author':
			if (ROLE != 'admin')
			{
				formMsg($lang['access_disabled'],'./', 0);
			}
			foreach($logs as $val)
			{
				$emBlog->updateLog(array('author'=>$author), $val);
			}
			$CACHE->mc_user();
			header("Location: ./admin_log.php?active_change_author=true");
			break;
	}
}

?>