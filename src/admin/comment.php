<?php
/**
 * Comment Management
 * @copyright (c) Emlog All Rights Reserved
 * $Id$
 */

require_once 'globals.php';
require_once EMLOG_ROOT.'/model/class.comment.php';

$emComment = new emComment();

//Load the comment management page
if($action == '')
{
	$blogId = isset($_GET['gid']) ? intval($_GET['gid']) : null;
	$hide = isset($_GET['hide']) ? addslashes($_GET['hide']) : '';
	$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

	$addUrl_1 = $blogId ? "gid={$blogId}&" : '';
	$addUrl_2 = $hide ? "hide=$hide&" : '';
	$addUrl = $addUrl_1.$addUrl_2;

	$comment = $emComment->getComments(1, $blogId, $hide, $page);
	$cmnum = $emComment->getCommentNum($blogId, $hide);
	$hideCommNum = $emComment->getCommentNum($blogId, 'y');
	$pageurl =  pagination($cmnum, ADMIN_PERPAGE_NUM, $page, "comment.php?{$addUrl}page");

	include getViews('header');
	require_once(getViews('comment'));
	include getViews('footer');
	cleanPage();
}
//Comments Operations
if ($action== 'del')
{
	$id = isset($_GET['id']) ? intval($_GET['id']) : '';
	$emComment->delComment($id);
	$CACHE->updateCache(array('sta','comment'));
	header("Location: ./comment.php?active_del=true");
}
if($action=='hide')
{
	$id = isset($_GET['id']) ? intval($_GET['id']) : '';
	$emComment->hideComment($id);
	$CACHE->updateCache(array('sta','comment'));
	header("Location: ./comment.php?active_hide=true");
}
if($action=='show')
{
	$id = isset($_GET['id']) ? intval($_GET['id']) : '';
	$emComment->showComment($id);
	$CACHE->updateCache(array('sta','comment'));
	header("Location: ./comment.php?active_show=true");
}
if($action== 'admin_all_coms')
{
	$operate = isset($_POST['operate']) ? $_POST['operate'] : '';
	$comments = isset($_POST['com']) ? array_map('intval', $_POST['com']) : array();

	if($operate == '')
	{
		header("Location: ./comment.php?error_b=true");
		exit;
	}
	if($comments == '')
	{
		header("Location: ./comment.php?error_a=true");
		exit;
	}
	if($operate == 'del')
	{
		$emComment->batchComment('delcom', $comments);
		$CACHE->updateCache(array('sta','comment'));
		header("Location: ./comment.php?active_del=true");
	}
	if($operate == 'hide')
	{
		$emComment->batchComment('hidecom', $comments);
		$CACHE->updateCache(array('sta','comment'));
		header("Location: ./comment.php?active_hide=true");
	}
	if($operate == 'pub')
	{
		$emComment->batchComment('showcom', $comments);
		$CACHE->updateCache(array('sta', 'comment'));
		header("Location: ./comment.php?active_show=true");
	}
}

//Reply Operations
if ($action== 'reply_comment')
{
	include getViews('header');
	$commentId = isset($_GET['cid']) ? intval($_GET['cid']) : '';
	$commentArray = $emComment->getOneComment($commentId);
	extract($commentArray);

	require_once(getViews('comment_reply'));
	include getViews('footer');
	cleanPage();
}
if($action=='doreply')
{
	$flg = isset($_GET['flg']) ? intval($_GET['flg']) : 0;
	$reply = isset($_POST['reply']) ? addslashes($_POST['reply']) : '';
	$commentId = isset($_REQUEST['cid']) ? intval($_REQUEST['cid']) : '';

	if(!$flg)
	{
	    if(isset($_POST['pub_it'])) {
	        $emComment->showComment($commentId);
	        $CACHE->updateCache('sta');
	    }
		$emComment->replyComment($commentId, $reply);
		$CACHE->updateCache('comment');
		doAction('comment_reply', $commentId, $reply);
		header("Location: ./comment.php?active_rep=true");
	}else{
		$reply = isset($_POST["reply$commentId"]) ? addslashes($_POST["reply$commentId"]) : '';
		$emComment->replyComment($commentId, $reply);
		$CACHE->updateCache('comment');
		doAction('comment_reply', $commentId, $reply);
		echo "<span>".$lang['blog_reply'].": $reply</span>";
	}
}
