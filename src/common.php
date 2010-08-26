<?php
/**
 * Front-end global item loading
 * @copyright (c) Emlog All Rights Reserved
 * $Id$
 */

require_once 'init.php';

//Read Cache
$log_cache_tags = $CACHE->readCache('logtags');
$log_cache_sort = $CACHE->readCache('logsort');
$log_cache_atts = $CACHE->readCache('logatts');
$newLogs_cache = $CACHE->readCache('newlog');
$newtws_cache = $CACHE->readCache('newtw');
$tag_cache = $CACHE->readCache('tags');
$sort_cache = $CACHE->readCache('sort');
$com_cache = $CACHE->readCache('comment');
$link_cache = $CACHE->readCache('link');
$user_cache = $CACHE->readCache('user');
$dang_cache = $CACHE->readCache('record');
$sta_cache = $CACHE->readCache('sta');

//Navigation bar
if(empty($navibar)) {
	$navibar = 'a:0:{}';
}
$navibar = unserialize(@$navibar);
$curpage = CURPAGE_HOME;


$calendar_url = isset($_GET['record']) ? DYNAMIC_BLOGURL.'?action=cal&record='.intval($_GET['record']) : DYNAMIC_BLOGURL.'?action=cal' ;
if ($action == 'cal') {
    require_once EMLOG_ROOT.'/lib/class.calendar.php';
    Calendar::generate();
}
