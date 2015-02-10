<?php
/*
Template Name: Default template
Description: Default template, simple and elegant
Version:1.2
Author:emlog
Author Url:http://www.emlog.net
Sidebar Amount:1
*/
if(!defined('EMLOG_ROOT')) {exit('error!');}
require_once View::getView('module');
?>
<!DOCTYPE html>
<!--vot--><html dir="<?= EMLOG_LANGUAGE_DIR ?>" lang="<?=EMLOG_LANGUAGE?>">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $site_title; ?></title>
<meta name="keywords" content="<?php echo $site_key; ?>" />
<meta name="description" content="<?php echo $site_description; ?>" />
<meta name="generator" content="emlog" />
<link rel="alternate" type="application/rss+xml" title="RSS"  href="<?php echo BLOG_URL; ?>rss.php" />
<link href="<?php echo TEMPLATE_URL; ?>main.css" rel="stylesheet" type="text/css" />
<link href="<?php echo BLOG_URL; ?>admin/editor/plugins/code/prettify.css" rel="stylesheet" type="text/css" />
<!--vot--><script src="<?= BLOG_URL ?>lang/<?= EMLOG_LANGUAGE ?>/lang_js.js"></script>
<script src="<?php echo BLOG_URL; ?>admin/editor/plugins/code/prettify.js" type="text/javascript"></script>
<script src="<?php echo BLOG_URL; ?>include/lib/js/common_tpl.js" type="text/javascript"></script>
<?php doAction('index_head'); ?>
</head>
<body>
<div id="wrap">
  <div id="header">
    <h1><a href="<?php echo BLOG_URL; ?>"><?php echo $blogname; ?></a></h1>
    <h3><?php echo $bloginfo; ?></h3>
  </div>
  <div id="nav"><?php blog_navi();?></div>
<?php
//DEBUG
//<div id="debug">
//echo '<pre>';
//echo 'LANGLIST=';
//print_r($GLOBALS['LANGLIST']);
//echo '</pre>';
//</div>
