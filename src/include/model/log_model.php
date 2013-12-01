<?php
/**
 * Blog page management
 *
 * @copyright (c) Emlog All Rights Reserved
 */

class Log_Model {

	private $db;

	function __construct() {
		$this->db = MySql::getInstance();
	}

	/**
	 * Add blog post
	 *
	 * @param array $logData
	 * @return int
	 */
	function addlog($logData) {
		$kItem = array();
		$dItem = array();
		foreach ($logData as $key => $data) {
			$kItem[] = $key;
			$dItem[] = $data;
		}
		$field = implode(',', $kItem);
		$values = "'" . implode("','", $dItem) . "'";
		$this->db->query("INSERT INTO " . DB_PREFIX . "blog ($field) VALUES ($values)");
		$logid = $this->db->insert_id();
		return $logid;
	}

	/**
	 * Update blog post
	 *
	 * @param array $logData
	 * @param int $blogId
	 */
	function updateLog($logData, $blogId) {
		$author = ROLE == ROLE_ADMIN ? '' : 'and author=' . UID;
		$Item = array();
		foreach ($logData as $key => $data) {
			$Item[] = "$key='$data'";
		}
		$upStr = implode(',', $Item);
		$this->db->query("UPDATE " . DB_PREFIX . "blog SET $upStr WHERE gid=$blogId $author");
	}

	/**
	 * Get a number of posts with specified conditions
	 *
	 * @param int $spot 0: foreground, 1: background
	 * @param string $hide
	 * @param string $condition
	 * @param string $type
	 * @return int
	 */
	function getLogNum($hide = 'n', $condition = '', $type = 'blog', $spot = 0) {
		$hide_state = $hide ? "and hide='$hide'" : '';

		if ($spot == 0) {
			$author = '';
		}else {
			$author = ROLE == ROLE_ADMIN ? '' : 'and author=' . UID;
		}

		$res = $this->db->query("SELECT gid FROM " . DB_PREFIX . "blog WHERE type='$type' $hide_state $author $condition");
		$LogNum = $this->db->num_rows($res);
		return $LogNum;
	}

	/**
	 * Get a single post in the background
	 */
	function getOneLogForAdmin($blogId) {
		global $lang;
		$timezone = Option::get('timezone');
		$author = ROLE == ROLE_ADMIN ? '' : 'AND author=' . UID;
		$sql = "SELECT * FROM " . DB_PREFIX . "blog WHERE gid=$blogId $author";
		$res = $this->db->query($sql);
		if ($this->db->affected_rows() < 1) {
			emMsg($lang['access_disabled'], './');
		}
		$row = $this->db->fetch_array($res);
		if ($row) {
			$row['date'] = $row['date'] + $timezone * 3600;
			$row['title'] = htmlspecialchars($row['title']);
			$row['content'] = htmlspecialchars($row['content']);
			$row['excerpt'] = htmlspecialchars($row['excerpt']);
			$row['password'] = htmlspecialchars($row['password']);
			$logData = $row;
			return $logData;
		} else {
			return false;
		}
	}

	/**
	 * Get a single post for the frontend
	 */
	function getOneLogForHome($blogId) {
		$sql = "SELECT * FROM " . DB_PREFIX . "blog WHERE gid=$blogId AND hide='n'";
		$res = $this->db->query($sql);
		$row = $this->db->fetch_array($res);
		if ($row) {
			$logData = array(
				'log_title' => htmlspecialchars($row['title']),
				'timestamp' => $row['date'],
				'date' => $row['date'] + Option::get('timezone') * 3600,
				'logid' => intval($row['gid']),
				'sortid' => intval($row['sortid']),
				'type' => $row['type'],
				'author' => $row['author'],
				'log_content' => rmBreak($row['content']),
				'views' => intval($row['views']),
				'comnum' => intval($row['comnum']),
				'top' => $row['top'],
				'attnum' => intval($row['attnum']),
				'allow_remark' => Option::get('iscomment') == 'y' ? $row['allow_remark'] : 'n',
				'password' => $row['password']
				);
			return $logData;
		} else {
			return false;
		}
	}

	/**
	 * Get a post list for the background
	 *
	 * @param string $condition
	 * @param string $hide_state
	 * @param int $page
	 * @param string $type
	 * @return array
	 */
	function getLogsForAdmin($condition = '', $hide_state = '', $page = 1, $type = 'blog') {
	        global $lang;
		$timezone = Option::get('timezone');
		$perpage_num = Option::get('admin_perpage_num');
		$start_limit = !empty($page) ? ($page - 1) * $perpage_num : 0;
		$author = ROLE == ROLE_ADMIN ? '' : 'and author=' . UID;
		$hide_state = $hide_state ? "and hide='$hide_state'" : '';
		$limit = "LIMIT $start_limit, " . $perpage_num;
		$sql = "SELECT * FROM " . DB_PREFIX . "blog WHERE type='$type' $author $hide_state $condition $limit";
		$res = $this->db->query($sql);
		$logs = array();
		while ($row = $this->db->fetch_array($res)) {
			$row['date']	= gmdate("Y-m-d H:i", $row['date'] + $timezone * 3600);
			$row['title'] 	= !empty($row['title']) ? htmlspecialchars($row['title']) : $lang['no_title'];
			//$row['gid'] 	= $row['gid'];
			//$row['comnum'] 	= $row['comnum'];
			//$row['top'] 	= $row['top'];
			//$row['attnum'] 	= $row['attnum'];
			$logs[] = $row;
		}
		return $logs;
	}

	/**
	 * Get a post list for the frontend
	 *
	 * @param string $condition
	 * @param int $page
	 * @param int $perPageNum
	 * @return array
	 */
	function getLogsForHome($condition = '', $page = 1, $perPageNum) {
		global $lang;
		$timezone = Option::get('timezone');
		$start_limit = !empty($page) ? ($page - 1) * $perPageNum : 0;
		$limit = $perPageNum ? "LIMIT $start_limit, $perPageNum" : '';
		$sql = "SELECT * FROM " . DB_PREFIX . "blog WHERE type='blog' and hide='n' and checked='y' $condition $limit";
		$res = $this->db->query($sql);
		$logs = array();
		while ($row = $this->db->fetch_array($res)) {
			$row['date'] += $timezone * 3600;
			$row['log_title'] = htmlspecialchars(trim($row['title']));
			$row['log_url'] = Url::log($row['gid']);
			$row['logid'] = $row['gid'];
			$cookiePassword = isset($_COOKIE['em_logpwd_' . $row['gid']]) ? addslashes(trim($_COOKIE['em_logpwd_' . $row['gid']])) : '';
			if (!empty($row['password']) && $cookiePassword != $row['password']) {
                $row['excerpt'] = "<p>[{$lang['blog_password_protected_info']}]</p>";
			} else {
				if (!empty($row['excerpt'])) {
                    $row['excerpt'] .= '<p class="readmore"><a href="' . Url::log($row['logid']) . '">'.$lang['read_more'].'&gt;&gt;</a></p>';
				}
			}
			$row['log_description'] = empty($row['excerpt']) ? breakLog($row['content'], $row['gid']) : $row['excerpt'];
			$row['attachment'] = '';
			$row['tag'] = '';
            $row['tbcount'] = 0;//兼容未删除引用的模板
			$logs[] = $row;
		}
		return $logs;
	}

	/**
	 * Get a list of all pages
	 *
	 */
	function getAllPageList() {
		$sql = "SELECT * FROM " . DB_PREFIX . "blog WHERE type='page'";
		$res = $this->db->query($sql);
		$pages = array();
		while ($row = $this->db->fetch_array($res)) {
			$row['date']	= gmdate("Y-m-d H:i", $row['date'] + Option::get('timezone') * 3600);
			$row['title'] 	= !empty($row['title']) ? htmlspecialchars($row['title']) : $lang['no_title'];
			//$row['gid'] 	= $row['gid'];
			//$row['comnum'] 	= $row['comnum'];
			//$row['top'] 	= $row['top'];
			//$row['attnum'] 	= $row['attnum'];
			$pages[] = $row;
		}
		return $pages;
	}

	/**
	 * Delete Post
	 *
	 * @param int $blogId
	 */
	function deleteLog($blogId) {
		global $lang;
		$author = ROLE == ROLE_ADMIN ? '' : 'and author=' . UID;
		$this->db->query("DELETE FROM " . DB_PREFIX . "blog where gid=$blogId $author");
		if ($this->db->affected_rows() < 1) {
			emMsg($lang['access_disabled'], './');
		}
		// Comments
		$this->db->query("DELETE FROM " . DB_PREFIX . "comment where gid=$blogId");
		// Tags
		$this->db->query("UPDATE " . DB_PREFIX . "tag SET gid= REPLACE(gid,',$blogId,',',') WHERE gid LIKE '%" . $blogId . "%' ");
		$this->db->query("DELETE FROM " . DB_PREFIX . "tag WHERE gid=',' ");
		// Attachments
		$query = $this->db->query("select filepath from " . DB_PREFIX . "attachment where blogid=$blogId ");
		while ($attach = $this->db->fetch_array($query)) {
			if (file_exists($attach['filepath'])) {
				$fpath = str_replace('thum-', '', $attach['filepath']);
				if ($fpath != $attach['filepath']) {
					@unlink($fpath);
				}
				@unlink($attach['filepath']);
			}
		}
		$this->db->query("DELETE FROM " . DB_PREFIX . "attachment where blogid=$blogId");
	}

	/**
	 * Hide/show the post
	 *
	 * @param int $blogId
	 * @param string $state
	 */
	function hideSwitch($blogId, $state) {
        $author = ROLE == ROLE_ADMIN ? '' : 'and author=' . UID;
		$this->db->query("UPDATE " . DB_PREFIX . "blog SET hide='$state' WHERE gid=$blogId $author");
        if ($this->db->affected_rows() < 1) {
			emMsg('权限不足！', './');
		}
		$this->db->query("UPDATE " . DB_PREFIX . "comment SET hide='$state' WHERE gid=$blogId");
		$Comment_Model = new Comment_Model();
		$Comment_Model->updateCommentNum($blogId);
	}

    /**
	 * 审核/驳回作者文章
	 *
	 * @param int $blogId
	 * @param string $state
	 */
	function checkSwitch($blogId, $state) {
		$this->db->query("UPDATE " . DB_PREFIX . "blog SET checked='$state' WHERE gid=$blogId");
        $state = $state == 'y' ? 'n' : 'y';
		$this->db->query("UPDATE " . DB_PREFIX . "comment SET hide='$state' WHERE gid=$blogId");
		$Comment_Model = new Comment_Model();
		$Comment_Model->updateCommentNum($blogId);
	}

	/**
	 * Get the post release time
	 *
	 * @param int $timezone
	 * @param string $postDate
	 * @param string $oldDate
	 * @return date
	 */
	function postDate($timezone = 8, $postDate = null, $oldDate = null) {
		$timezone = Option::get('timezone');
		$localtime = time();
		$logDate = $oldDate ? $oldDate : $localtime;
		$unixPostDate = '';
		if ($postDate) {
			$unixPostDate = emStrtotime($postDate);
			if ($unixPostDate === false) {
				$unixPostDate = $logDate;
			}
		} else {
			return $localtime;
		}
		return $unixPostDate;
	}

	/**
	 * Update the number of views
	 *
	 * @param int $blogId
	 */
	function updateViewCount($blogId) {
		$this->db->query("UPDATE " . DB_PREFIX . "blog SET views=views+1 WHERE gid=$blogId");
	}

	/**
	 * Determine whether the post is repeated
	 */
	function isRepeatPost($title, $time) {
		$sql = "SELECT gid FROM " . DB_PREFIX . "blog WHERE title='$title' and date='$time' LIMIT 1";
		$res = $this->db->query($sql);
		$row = $this->db->fetch_array($res);
		return isset($row['gid']) ? (int)$row['gid'] : false;
	}

	/**
	 * Get neighbor posts
	 *
	 * @param int $date unix Timestamp
	 * @return array
	 */
	function neighborLog($date) {
		$neighborlog = array();
		$neighborlog['nextLog'] = $this->db->once_fetch_array("SELECT title,gid FROM " . DB_PREFIX . "blog WHERE date < $date and hide = 'n' and type='blog' ORDER BY date DESC LIMIT 1");
		$neighborlog['prevLog'] = $this->db->once_fetch_array("SELECT title,gid FROM " . DB_PREFIX . "blog WHERE date > $date and hide = 'n' and type='blog' ORDER BY date LIMIT 1");
		if ($neighborlog['nextLog']) {
			$neighborlog['nextLog']['title'] = htmlspecialchars($neighborlog['nextLog']['title']);
		}
		if ($neighborlog['prevLog']) {
			$neighborlog['prevLog']['title'] = htmlspecialchars($neighborlog['prevLog']['title']);
		}
		return $neighborlog;
	}

	/**
	 * Randomly get a specified number of posts
	 */
	function getRandLog($num) {
		$sql = "SELECT gid,title FROM " . DB_PREFIX . "blog WHERE hide='n' and checked='y' and type='blog' ORDER BY rand() LIMIT 0, $num";
		$res = $this->db->query($sql);
		$logs = array();
		while ($row = $this->db->fetch_array($res)) {
			$row['gid'] = intval($row['gid']);
			$row['title'] = htmlspecialchars($row['title']);
			$logs[] = $row;
		}
		return $logs;
	}

	/**
	 * Get popular articles
	 */
	function getHotLog($num) {
		$sql = "SELECT gid,title FROM " . DB_PREFIX . "blog WHERE hide='n' and checked='y' and type='blog' ORDER BY views DESC, comnum DESC LIMIT 0, $num";
		$res = $this->db->query($sql);
		$logs = array();
		while ($row = $this->db->fetch_array($res)) {
			$row['gid'] = intval($row['gid']);
			$row['title'] = htmlspecialchars($row['title']);
			$logs[] = $row;
		}
		return $logs;
	}

	/**
	 * Handling post aliases to prevent duplicate aliases
	 *
	 * @param string $alias
	 * @param array $logalias_cache
	 * @param int $logid
	 */
	function checkAlias($alias, $logalias_cache, $logid) {
		static $i=2;
		$key = array_search($alias, $logalias_cache);
		if (false !== $key && $key != $logid) {
			if($i == 2) {
				$alias .= '-'.$i;
			}else{
				$alias = preg_replace("|(.*)-([\d]+)|", "$1-{$i}", $alias);
			}
			$i++;
			return $this->checkAlias($alias, $logalias_cache, $logid);
		}
		return $alias;
	}

	/**
	 * Verify access to the encrypted post
	 *
	 * @param string $pwd
	 * @param string $pwd2
	 */
	function authPassword($postPwd, $cookiePwd, $logPwd, $logid) {
		global $lang;
		$url = BLOG_URL;
		$pwd = $cookiePwd ? $cookiePwd : $postPwd;
		if ($pwd !== addslashes($logPwd)) {
			echo <<<EOT
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>emlog message</title>
<style type="text/css">
<!--
body{background-color:#F7F7F7;font-family: Arial;font-size: 12px;line-height:150%;}
.main{background-color:#FFFFFF;margin-top:20px;font-size: 12px;color: #666666;width:580px;margin:10px 200px;padding:10px;list-style:none;border:#DFDFDF 1px solid;}
-->
</style>
</head>
<body>
<div class="main">
<form action="" method="post">
{$lang['blog_enter_password']}<br>
<input type="password" name="logpwd" /><input type="submit" value="{$lang['enter']}.." />
<br /><br /><a href="$url">&laquo;{$lang['back_home']}</a>
</form>
</div>
</body>
</html>
EOT;
			if ($cookiePwd) {
				setcookie('em_logpwd_' . $logid, ' ', time() - 31536000);
			}
			exit;
		} else {
			setcookie('em_logpwd_' . $logid, $logPwd);
		}
	}
}
