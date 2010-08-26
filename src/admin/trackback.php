<?php
/**
 * Trackbacks Management
 * @copyright (c) Emlog All Rights Reserved
 * $Id$
 */

require_once 'globals.php';
require_once EMLOG_ROOT.'/model/class.trackback.php';

$emTrackback = new emTrackback();

if($action == '')
{
	$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

	$trackback = $emTrackback->getTrackbacks($page, null, 1);
	$tbnum = $emTrackback->getTbNum();
	$pageurl =  pagination($tbnum, ADMIN_PERPAGE_NUM, $page, "./trackback.php?page");

	include getViews('header');
	require_once getViews('trackback');
	include getViews('footer');cleanPage();
}

//Delete Trackback
if($action == 'dell')
{
	$tbs = isset($_POST['tb']) ? array_map('intval', $_POST['tb']) : array();
	if(!$tbs)
	{
		header("Location: ./trackback.php?error_a=true");
		exit;
	}
	foreach($tbs as $value)
	{
		$emTrackback->deleteTrackback($value);
	}
	$CACHE->updateCache('sta');
	header("Location: ./trackback.php?active_del=true");
}
