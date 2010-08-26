<?php
/*
Template Name: Default Template
Description: This is the emlog the default template, a simple and neat ... ...
Author: emlog development team
Author Url:http://www.emlog.net
Sidebar Amount:1
*/
if(!defined('EMLOG_ROOT')) {exit('error!');}
require_once (getViews('module'));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="keywords" content="<?php echo $site_key; ?>" />
<meta name="description" content="<?php echo $bloginfo; ?>" />
<meta name="generator" content="emlog" />
<title><?php echo $blogtitle; ?></title>
<link rel="EditURI" type="application/rsd+xml" title="RSD" href="<?php echo BLOG_URL; ?>xmlrpc.php?rsd" />
<link rel="wlwmanifest" type="application/wlwmanifest+xml" href="<?php echo BLOG_URL; ?>wlwmanifest.xml" />
<link rel="alternate" type="application/rss+xml" title="RSS"  href="<?php echo BLOG_URL; ?>rss.php" />
<link href="<?php echo TEMPLATE_URL; ?>main.css" rel="stylesheet" type="text/css" />
<script src="<?php echo BLOG_URL; ?>lib/js/common_tpl.js" type="text/javascript"></script>
<?php doAction('index_head'); ?>
</head>
<body>
<div class="main">
	<div class="header">
		<ul>
			<li id="title"><h1><a href="<?php echo BLOG_URL; ?>"><?php echo $blogname; ?></a></h1></li>
			<li id="tagline"><?php echo $bloginfo; ?></li>
		</ul>
		<ul id="menus">
			<li class="<?php echo $curpage == CURPAGE_HOME ? 'current' : 'common';?>"><a href="<?php echo BLOG_URL; ?>"><? echo $lang['home']; ?></a></li>
			<?php if($istwitter == 'y'):?>
			<li class="<?php echo $curpage == CURPAGE_TW ? 'current' : 'common';?>"><a href="<?php echo BLOG_URL; ?>t/"><? echo $lang['twitters']; ?></a></li>
			<?php endif;?>
			<?php 
			foreach ($navibar as $key => $val):
			if ($val['hide'] == 'y'){continue;}
			if (empty($val['url'])){$val['url'] = BLOG_URL.'?post='.$key;}
			?>
			<li class="<?php echo isset($logid) && $key == $logid ? 'current' : 'common';?>"><a href="<?php echo $val['url']; ?>" target="<?php echo $val['is_blank']; ?>"><?php echo $val['title']; ?></a></li>
			<?php endforeach;?>
			<?php doAction('navbar', '<li class="common">', '</li>'); ?>
			<?php if(ROLE == 'admin' || ROLE == 'writer'): ?>
			<li class="common"><a href="<?php echo BLOG_URL; ?>admin/write_log.php"><? echo $lang['post_add']; ?></a></li>
			<li class="common"><a href="<?php echo BLOG_URL; ?>admin/"><? echo $lang['admin_center'];?></a></li>
			<li class="common"><a href="<?php echo BLOG_URL; ?>admin/?action=logout"><? echo $lang['logout'];?></a></li>
			<?php else: ?>
			<li class="common"><a href="<?php echo BLOG_URL; ?>admin/"><? echo $lang['login'];?></a></li>
			<?php endif; ?>
		</ul>
		<div class="clear"></div>
	</div>
	<!--header end-->