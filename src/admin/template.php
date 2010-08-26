<?php
/**
 * Template Management
 * @copyright (c) Emlog All Rights Reserved
 * $Id$
 */

require_once 'globals.php';

if($action == '')
{
	//Current template 
	$nonceTplData = @implode('', @file(TPLS_PATH.$nonce_templet.'/header.php'));
	preg_match("/Template Name:(.*)/i", $nonceTplData, $tplName);
	preg_match("/Author:(.*)/i", $nonceTplData, $tplAuthor);
	preg_match("/Description:(.*)/i", $nonceTplData, $tplDes);
	preg_match("/Author Url:(.*)/i", $nonceTplData, $tplUrl);
	$tplName = !empty($tplName[1]) ? trim($tplName[1]) : $nonce_templet;
	$tplDes = !empty($tplDes[1]) ? $tplDes[1] : '';
	if(isset($tplAuthor[1]))
	{
		$tplAuthor = !empty($tplUrl[1]) ? $lang['author'].": <a href=\"{$tplUrl[1]}\">{$tplAuthor[1]}</a>" : $lang['author'].": {$tplAuthor[1]}";
	}else{
		$tplAuthor = '';
	}
	//Template List
	$handle = @opendir(TPLS_PATH) OR die('emlog template path error!');
	$tpls = array();
	while ($file = @readdir($handle))
	{
		if(file_exists(TPLS_PATH.$file.'/header.php'))
		{
			$tplData = implode('', @file(TPLS_PATH.$file.'/header.php'));
			preg_match("/Template Name:([^\r\n]+)/i", $tplData, $name);
			preg_match("/Sidebar Amount:([^\r\n]+)/i", $tplData, $sidebar);
			$tplInfo['tplname'] = !empty($name[1]) ? trim($name[1]) : $file;
			$tplInfo['sidebar'] = !empty($sidebar[1]) ? intval($sidebar[1]) : 1;
			$tplInfo['tplfile'] = $file;

			$tpls[] = $tplInfo;
		}
	}
	closedir($handle);

	$tplnums = count($tpls);

	include getViews('header');
	require_once getViews('template');
	include getViews('footer');
	cleanPage();
}
//Using a template
if($action == 'usetpl')
{
	$tplName = isset($_GET['tpl']) ? addslashes($_GET['tpl']) : '';
	$tplSideNum = isset($_GET['side']) ? intval($_GET['side']) : '';

	updateOption('nonce_templet', $tplName);
	updateOption('tpl_sidenum', $tplSideNum);
	$CACHE->updateCache('options');
	header("Location: ./template.php?activated=true");
}
