<?php
/**
 * Tags Management
 * @copyright (c) Emlog All Rights Reserved
 * $Id$
 */

require_once 'globals.php';
require_once EMLOG_ROOT.'/model/class.tag.php';

$emTag = new emTag();

if($action == '')
{
	$tags = $emTag->getTag();
	include getViews('header');
	require_once getViews('tag');
	include getViews('footer');
	cleanPage();
}

if ($action== "mod_tag")
{
	$tagId = isset($_GET['tid']) ? intval($_GET['tid']) : '';
	$tag = $emTag->getOneTag($tagId);
	extract($tag);
	include getViews('header');
	require_once getViews('tagedit');
	include getViews('footer');cleanPage();
}

//Update Tags
if($action=='update_tag')
{
	$tagName = isset($_POST['tagname']) ? addslashes($_POST['tagname']) : '';
	$tagId = isset($_POST['tid']) ? intval($_POST['tid']) : '';
	$emTag->updateTagName($tagId, $tagName);
	$CACHE->updateCache(array('tags', 'logtags'));
	header("Location: ./tag.php?active_edit=true");
}

//Bulk Delete Tags
if($action== 'dell_all_tag')
{
	$tags = isset($_POST['tag']) ? $_POST['tag'] : '';
	if(!$tags)
	{
		header("Location: ./tag.php?error_a=true");
		exit;
	}
	foreach($tags as $key=>$value)
	{
		$emTag->deleteTag($key);
	}
	$CACHE->updateCache(array('tags', 'logtags'));
	header("Location: ./tag.php?active_del=true");
}
