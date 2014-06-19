<?php if(!defined('EMLOG_ROOT')) {exit('error!');} ?>
<!DOCTYPE html>
<html dir="ltr" lang="<?=EMLOG_LANGUAGE?>">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--vot--><meta http-equiv="Content-Language" content="<?=EMLOG_LANGUAGE?>" />
<meta name="author" content="emlog" />
<meta name="robots" content="noindex, nofollow">
<meta http-equiv="X-UA-Compatible" content="IE=8" />
<!--vot--><title><?=lang('admin_center')?> - <?php echo Option::get('blogname'); ?></title>
<link href="./views/style/<?php echo Option::get('admin_style');?>/style.css?v=<?php echo Option::EMLOG_VERSION; ?>" type=text/css rel=stylesheet>
<link href="./views/css/css-main.css?v=<?php echo Option::EMLOG_VERSION; ?>" type=text/css rel=stylesheet>
<!--vot--><script src="<?=BLOG_URL?>lang/<?=EMLOG_LANGUAGE?>/lang_js.js?v=<?php echo Option::EMLOG_VERSION; ?>"></script>
<!--vot--><script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script type="text/javascript" src="../include/lib/js/jquery/plugin-cookie.js?v=<?php echo Option::EMLOG_VERSION; ?>"></script>
<script type="text/javascript" src="./views/js/common.js?v=<?php echo Option::EMLOG_VERSION; ?>"></script>
<?php doAction('adm_head');?>
</head>
<body>
<div id="mainpage">
<div id="header">
    <div id="header_left"></div>
<!--vot--><div id="header_logo"><a href="./" title="<?=lang('return_to_admin_center')?>">emlog</a></div>
    <div id="header_title">
<!--vot--><a href="../" target="_blank" title="<?=lang('to_site_new_window')?>">
    <?php 
    $blog_name = Option::get('blogname');
/*vot*/ echo empty($blog_name) ? lang('to_site') : subString($blog_name, 0, 24);
    ?>
    </a>
    </div>
    <div id="header_right"></div>
    <div id="header_menu">
    <a href="./blogger.php" title="<?php echo subString($user_cache[UID]['name'], 0, 12) ?>">
        <img src="<?php echo empty($user_cache[UID]['avatar']) ? './views/images/avatar.jpg' : '../' . $user_cache[UID]['avatar'] ?>" align="top" width="20" height="20" />
    </a><span>|</span>
    <?php if (ROLE == ROLE_ADMIN):?>
<!--vot--><a href="configure.php"><?=lang('settings')?></a><span>|</span>
	<?php endif;?>
<!--vot--><a href="./?action=logout"><?=lang('logout')?></a>
    </div>
</div>
<div id="side">
	<div id="sidebartop"></div>
    <div id="log_mg">
<!--vot--><li class="sidebarsubmenu" id="menu_wt"><a href="write_log.php"><span class="ico16"></span><?=lang('post_write')?></a></li>
		<li class="sidebarsubmenu" id="menu_draft">
<!--vot-->    	<a href="admin_log.php?pid=draft"><?=lang('draft')?><span id="dfnum">
		<?php 
		if (ROLE == ROLE_ADMIN){
			echo $sta_cache['draftnum'] == 0 ? '' : '('.$sta_cache['draftnum'].')'; 
		}else{
			echo $sta_cache[UID]['draftnum'] == 0 ? '' : '('.$sta_cache[UID]['draftnum'].')';
		}
		?>
		</span></a></li>
<!--vot--><li class="sidebarsubmenu" id="menu_log"><a href="admin_log.php"><?=lang('posts')?></a></li>
        <?php
        $checknum = $sta_cache['checknum'];
		if (ROLE == ROLE_ADMIN && $checknum > 0):
		$n = $checknum > 999 ? '...' : $checknum;
		?>
		<div class="notice_number"><a href="./admin_log.php?checked=n" title="<?php echo $checknum; ?> <?=lang('posts_pending')?>"><?php echo $n; ?></a></div>
		<?php endif; ?>
		<?php if (ROLE == ROLE_ADMIN):?>
<!--vot--><li class="sidebarsubmenu" id="menu_tag"><a href="tag.php"><?=lang('tags')?></a></li>
<!--vot--><li class="sidebarsubmenu" id="menu_sort"><a href="sort.php"><?=lang('categories')?></a></li>
    	<?php endif;?>
<!--vot--><li class="sidebarsubmenu" id="menu_cm"><a href="comment.php"><?=lang('comments')?></a> </li>
   		<?php
		$hidecmnum = ROLE == ROLE_ADMIN ? $sta_cache['hidecomnum'] : $sta_cache[UID]['hidecommentnum'];
		if ($hidecmnum > 0):
		$n = $hidecmnum > 999 ? '...' : $hidecmnum;
		?>
		<div class="notice_number"><a href="./comment.php?hide=y" title="<?php echo $hidecmnum; ?><?=lang('comments_pending')?>"><?php echo $n; ?></a></div>
		<?php endif; ?>
		<?php if (ROLE == ROLE_ADMIN):?>
<!--vot--><li class="sidebarsubmenu" id="menu_tw"><a href="twitter.php"><?=lang('twitters')?></a></li>
<!--vot--><li class="sidebarsubmenu" id="menu_widget"><a href="widgets.php" ><?=lang('sidebar')?></a></li>
<!--vot--><li class="sidebarsubmenu" id="menu_navbar"><a href="navbar.php" ><?=lang('navigation')?></a></li>
<!--vot--><li class="sidebarsubmenu" id="menu_page"><a href="page.php"><?=lang('pages')?></a></li>
<!--vot--><li class="sidebarsubmenu" id="menu_link"><a href="link.php"><?=lang('links')?></a></li>
<!--vot--><li class="sidebarsubmenu" id="menu_user"><a href="user.php"><?=lang('users')?></a></li>
<!--vot--><li class="sidebarsubmenu" id="menu_data"><a href="data.php"><?=lang('data')?></a></li>
<!--vot--><li class="sidebarsubmenu" id="menu_plug"><a href="plugin.php"><?=lang('plugins')?></a></li>
<!--vot--><li class="sidebarsubmenu" id="menu_tpl"><a href="template.php"><?=lang('templates')?></a></li>
<!--vot--><li class="sidebarsubmenu" id="menu_store"><a href="store.php"><?=lang('applications')?></a></li>
        <?php if (!empty($emHooks['adm_sidebar_ext'])): ?>
<!--vot--><li class="sidebarsubmenu" id="menu_ext"><a class="menu_ext_minus"><?=lang('extensions')?></a></li>
        <?php endif;?>
		<?php endif;?>
    </div>
    <?php if (ROLE == ROLE_ADMIN):?>
    <div id="extend_mg">
		<?php doAction('adm_sidebar_ext'); ?>
    </div>
    <?php endif;?>
	<div id="sidebarBottom"></div>
</div>
<div id="container">
<?php doAction('adm_main_top'); ?>
<script>
<!--Sidebar Toggle-->
$("#extend_mg").css('display', $.cookie('em_extend_mg') ? $.cookie('em_extend_mg') : '');
if ($.cookie('em_extend_ext')) {
	$("#menu_ext a").removeClass().addClass($.cookie('em_extend_ext'));
}
$("#menu_ext").toggle(
	  function () {
		displayToggle('extend_mg', 1)
		exClass = $(this).find("a").attr("class") == "menu_ext_plus" ? "menu_ext_minus" : "menu_ext_plus";
		$(this).find("a").removeClass().addClass(exClass);
		$.cookie('em_extend_ext', exClass, {expires:365});
	  },
	  function () {
		displayToggle('extend_mg', 1)
		exClass = $(this).find("a").attr("class") == "menu_ext_plus" ? "menu_ext_minus" : "menu_ext_plus";
		$(this).find("a").removeClass().addClass(exClass);
		$.cookie('em_extend_ext', exClass, {expires:365});
	  }
);
</script>