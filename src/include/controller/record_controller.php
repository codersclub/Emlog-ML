<?php
/**
 * View blog archive
 *
 * @copyright (c) Emlog All Rights Reserved
 */

class Record_Controller {

	/**
	 * Frontend archive post list
	 */
	function display($params) {
		global $lang;
		$Log_Model = new Log_Model();
		$options_cache = Option::getAll();
		extract($options_cache);
//Navigation bar
if(empty($navibar)) {
	$navibar = 'a:0:{}';
}
		$curpage = CURPAGE_HOME;

		$page = isset($params[4]) && $params[4] == 'page' ? abs(intval($params[5])) : 1;
		$record = isset($params[1]) && $params[1] == 'record' ? intval($params[2]) : '' ;

		$GLOBALS['record'] = $record;//for sidebar calendar

		$start_limit = ($page - 1) * $index_lognum;
		$pageurl = '';

		//page meta
		$site_title = $record . ' - ' . $site_title;

		if (preg_match("/^([\d]{4})([\d]{2})$/", $record, $match)) {
			$days = getMonthDayNum($match[2], $match[1]);
			$record_stime = emStrtotime($record . '01');
			$record_etime = $record_stime + 3600 * 24 * $days;
		} else {
			$record_stime = emStrtotime($record);
			$record_etime = $record_stime + 3600 * 24;
		}
		$sqlSegment = "and date>=$record_stime and date<$record_etime order by top desc ,date desc";
		$lognum = $Log_Model->getLogNum('n', $sqlSegment);
		$pageurl .= Url::record($record, 'page');

		$logs = $Log_Model->getLogsForHome($sqlSegment, $page, $index_lognum);
		$page_url = pagination($lognum, $index_lognum, $page, $pageurl);

		include View::getView('header');
		include View::getView('log_list');
	}
}
