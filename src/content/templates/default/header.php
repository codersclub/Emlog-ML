<?php
/*
Template Name:默认模板
Description:这是emlog的默认模板，简洁明快 ……
Author:emlog开发小组
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
<meta name="generator" content="emlog" />
<title><?php echo $blogtitle; ?></title>
<link rel="alternate" type="application/rss+xml" title="RSS"  href="<?php echo BLOG_URL; ?>rss.php">
<link href="<?php echo TPL_PATH; ?>main.css" rel="stylesheet" type="text/css" />
<script src="./lang/<?php echo EMLOG_LANGUAGE; ?>.js" type="text/javascript"></script>
<script src="<?php echo BLOG_URL; ?>lib/js/common_tpl.js" type="text/javascript"></script>
<?php doAction('index_head'); ?>
</head>
<body>
<div class="main">
	<div class="header">
		<ul>
			<li id="title"><h1><a href="./"><?php echo $blogname; ?></a></h1></li>
			<li id="tagline"><?php echo $bloginfo; ?></li>
		</ul>
		<ul id="menus">
			<li class="menus1"><a href="./"><? echo $lang['home']; ?></a></li>
			<?php foreach ($navibar as $key => $val):
			if ($val['hide'] == 'y'){continue;}
			if (empty($val['url'])){$val['url'] = './?post='.$key;}
			?>
			<li class="menus2"><a href="<?php echo $val['url']; ?>" target="<?php echo $val['is_blank']; ?>"><?php echo $val['title']; ?></a></li>
			<?php endforeach;?>
			<?php doAction('navbar', '<li class="menus2">', '</li>'); ?>
			<?php if(ROLE == 'admin' || ROLE == 'writer'): ?>
			<li class="menus2"><a href="./admin/write_log.php"><? echo $lang['post_add']; ?></a></li>
			<li class="menus2"><a href="./admin/"><? echo $lang['admin_center'];?></a></li>
			<li class="menus2"><a href="./admin/?action=logout"><? echo $lang['logout'];?></a></li>
			<?php else: ?>
			<li class="menus2"><a href="./admin/"><? echo $lang['login'];?></a></li>
			<?php endif; ?>
		</ul>
		<div class="clear"></div>
	</div>
	<!--header end-->