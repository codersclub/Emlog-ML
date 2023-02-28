<?php
/**
 * article and page model
 *
 * @package EMLOG
 * @link https://www.emlog.net
 */

class Log_Model {

	private $db;
	private $Parsedown;
	private $table;
	private $table_user;

	public function __construct() {
		$this->db = Database::getInstance();
		$this->table = DB_PREFIX . 'blog';
		$this->table_user = DB_PREFIX . 'user';
		$this->Parsedown = new Parsedown();
		$this->Parsedown->setBreaksEnabled(true); //automatic line wrapping
	}

	/**
	 * create article
	 */
	public function addlog($logData) {
		$kItem = $dItem = [];
		foreach ($logData as $key => $data) {
			$kItem[] = $key;
			$dItem[] = $data;
		}
		$field = implode(',', $kItem);
		$values = "'" . implode("','", $dItem) . "'";
		$this->db->query("INSERT INTO $this->table ($field) VALUES ($values)");
		return $this->db->insert_id();
	}

	/**
	 * update article
	 */
	public function updateLog($logData, $blogId, $uid = UID) {
		$author = User::haveEditPermission() ? '' : 'and author=' . $uid;
		$Item = [];
		foreach ($logData as $key => $data) {
			$Item[] = "$key='$data'";
		}
		$upStr = implode(',', $Item);
		$this->db->query("UPDATE $this->table SET $upStr WHERE gid=$blogId $author");
	}

	public function getCount($uid = UID) {
		$sql = sprintf("SELECT count(*) as num FROM $this->table WHERE author=%d AND type='%s'", $uid, 'blog');
		$res = $this->db->once_fetch_array($sql);
		return $res['num'];
	}

	/**
	 * Gets the number of articles for the specified condition
	 *
	 * @param int $spot (0: foreground, 1: background)
	 * @param string $hide
	 * @param string $condition
	 * @param string $type
	 * @return int
	 */
	public function getLogNum($hide = 'n', $condition = '', $type = 'blog', $spot = 0) {
		$hide_state = $hide ? "and hide='$hide'" : '';

		if ($spot == 0) {
			$now = time();
			$date_state = "and date<=$now";
			$author = '';
		} else {
			$date_state = '';
			$author = User::haveEditPermission() ? '' : 'and author=' . UID;
		}

		$data = $this->db->once_fetch_array("SELECT COUNT(*) AS total FROM $this->table WHERE type='$type' $date_state $hide_state $author $condition");
		return $data['total'];
	}

	public function getPostCountByUid($uid, $time = 0) {
		$date = '';
		if ($time) {
			$date = "and date > $time";
		}

		$data = $this->db->once_fetch_array("SELECT COUNT(*) AS total FROM $this->table WHERE type='blog' and author=$uid $date");
		return $data['total'];
	}

	public function getOneLogForAdmin($blogId) {
		$author = User::haveEditPermission() ? '' : 'AND author=' . UID;
		$sql = "SELECT * FROM $this->table WHERE gid=$blogId $author";
		$res = $this->db->query($sql);
		if ($this->db->affected_rows() < 1) {
			emMsg(lang('no_permission'), './');
		}
		$row = $this->db->fetch_array($res);
		if ($row) {
			$row['title'] = htmlspecialchars($row['title']);
			$row['content'] = htmlspecialchars($row['content']);
			$row['excerpt'] = htmlspecialchars($row['excerpt']);
			$row['password'] = htmlspecialchars($row['password']);
			$row['template'] = !empty($row['template']) ? htmlspecialchars(trim($row['template'])) : 'page';
			return $row;
		}
		return false;
	}

	/**
	 * get single article
	 */
	public function getOneLogForHome($blogId) {
		$sql = "SELECT * FROM $this->table WHERE gid=$blogId AND hide='n' AND checked='y'";
		$res = $this->db->query($sql);
		$row = $this->db->fetch_array($res);

		if (!$row) {
			return false;
		}

		return [
			'log_title'    => htmlspecialchars($row['title']),
			'timestamp'    => $row['date'],
			'date'         => $row['date'],
			'logid'        => (int)$row['gid'],
			'sortid'       => (int)$row['sortid'],
			'type'         => $row['type'],
			'author'       => $row['author'],
			'log_cover'    => $row['cover'] ? getFileUrl($row['cover']) : '',
			'log_content'  => $this->Parsedown->text($row['content']),
			'views'        => (int)$row['views'],
			'comnum'       => (int)$row['comnum'],
			'top'          => $row['top'],
			'sortop'       => $row['sortop'],
			'attnum'       => (int)$row['attnum'],
			'allow_remark' => Option::get('iscomment') == 'y' ? $row['allow_remark'] : 'n',
			'password'     => $row['password'],
			'template'     => $row['template'],
			'link'         => $row['link'],
		];
	}

	public function getLogsForAdmin($condition = '', $hide_state = '', $page = 1, $type = 'blog') {
		$perpage_num = Option::get('admin_perpage_num');
		$start_limit = !empty($page) ? ($page - 1) * $perpage_num : 0;
/*vot*/		$author = User::haveEditPermission() ? '' : 'AND author=' . UID;
/*vot*/		$hide_state = $hide_state ? "AND hide='$hide_state'" : '';
		$limit = "LIMIT $start_limit, " . $perpage_num;
		$sql = "SELECT * FROM $this->table WHERE type='$type' $author $hide_state $condition $limit";
		$res = $this->db->query($sql);
		$logs = [];
		while ($row = $this->db->fetch_array($res)) {
			$row['timestamp'] = $row['date'];
			$row['date'] = date("Y-m-d H:i", $row['date']);
			$row['title'] = !empty($row['title']) ? htmlspecialchars($row['title']) : lang('no_title');
			$logs[] = $row;
		}
		return $logs;
	}

	public function getLogsForHome($condition = '', $page = 1, $perPageNum = 10) {
		$start_limit = !empty($page) ? ($page - 1) * $perPageNum : 0;
		$limit = $perPageNum ? "LIMIT $start_limit, $perPageNum" : '';
		$now = time();
		$sql = "SELECT * FROM $this->table WHERE type='blog' and hide='n' and checked='y' and date<= $now $condition $limit";
		$res = $this->db->query($sql);
		$logs = [];
		while ($row = $this->db->fetch_array($res)) {
			$row['log_title'] = htmlspecialchars(trim($row['title']));
			$row['log_cover'] = $row['cover'] ? getFileUrl($row['cover']) : '';
			$row['log_url'] = Url::log($row['gid']);
			$row['logid'] = $row['gid'];
			$cookiePassword = isset($_COOKIE['em_logpwd_' . $row['gid']]) ? addslashes(trim($_COOKIE['em_logpwd_' . $row['gid']])) : '';
			if (!empty($row['password']) && $cookiePassword != $row['password']) {
				$row['excerpt'] = '<p>[' . lang('post_protected_by_password_click_title') . ']</p>';
			}

			$row['log_description'] = $this->Parsedown->text(empty($row['excerpt']) ? $row['content'] : $row['excerpt']);
			$row['attachment'] = '';
			$row['tag'] = '';
			$row['tbcount'] = 0;
			$logs[] = $row;
		}
		return $logs;
	}

	/**
	 * get rss article list
	 */
	public function getLogsForRss($perPageNum = 10) {
		if ($perPageNum <= 0) {
			return [];
		}
		$now = time();
		$date_state = "and date<=$now";
		$sql = "SELECT * FROM $this->table t1 LEFT JOIN $this->table_user t2 ON t1.author=t2.uid WHERE t1.hide='n' and t1.checked='y' and t1.type='blog' $date_state ORDER BY t1.date DESC limit 0," . $perPageNum;
		$result = $this->db->query($sql);
		$d = [];
		while ($re = $this->db->fetch_array($result)) {
			$re['id'] = $re['gid'];
			$re['title'] = htmlspecialchars($re['title']);
			$re['content'] = $this->Parsedown->text($re['content']);
			if (!empty($re['password'])) {
				$re['content'] = '<p>' . lang('article_encrypted') . ']</p>';
			} elseif (Option::get('rss_output_fulltext') == 'n') {
				if (!empty($re['excerpt'])) {
					$re['content'] = $re['excerpt'];
				} else {
					$re['content'] = extractHtmlData($re['content'], 330);
				}
				$re['content'] .= ' <a href="' . Url::log($re['id']) . '">' . lang('read_more') . '</a>';
			}
			$d[] = $re;
		}
		return $d;
	}


	/**
	 * Get a list of all pages
	 */
	function getAllPageList() {
		$sql = "SELECT * FROM $this->table WHERE type='page'";
		$res = $this->db->query($sql);
		$pages = [];
		while ($row = $this->db->fetch_array($res)) {
			$row['date'] = date("Y-m-d H:i", $row['date']);
			$row['title'] = !empty($row['title']) ? htmlspecialchars($row['title']) : lang('no_title');
			$pages[] = $row;
		}
		return $pages;
	}

	/**
	 * delete article
	 */
	function deleteLog($blogId) {
		$author = User::haveEditPermission() ? '' : 'AND author=' . UID;
		$this->db->query("DELETE FROM $this->table WHERE gid=$blogId $author");
		if ($this->db->affected_rows() < 1) {
			emMsg(lang('no_permission'), './');
		}
		// comment
		$this->db->query("DELETE FROM " . DB_PREFIX . "comment where gid=$blogId");
		// tag
		$this->db->query("UPDATE " . DB_PREFIX . "tag SET gid= REPLACE(gid,',$blogId,',',') WHERE gid LIKE '%" . $blogId . "%' ");
		$this->db->query("DELETE FROM " . DB_PREFIX . "tag WHERE gid=',' ");
	}

	/**
	 * Hide/Show the post by ID
	 *
	 * @param int $blogId
	 * @param string $state
	 */
	function hideSwitch($blogId, $state) {
		$author = User::haveEditPermission() ? '' : 'and author=' . UID;
		$this->db->query("UPDATE $this->table SET hide='$state' WHERE gid=$blogId $author");
		$this->db->query("UPDATE " . DB_PREFIX . "comment SET hide='$state' WHERE gid=$blogId");
		$Comment_Model = new Comment_Model();
		$Comment_Model->updateCommentNum($blogId);
	}

	/**
	 * Audit/Reject the post author
	 *
	 * @param int $blogId
	 * @param string $state
	 */
	function checkSwitch($blogId, $state) {
		$this->db->query("UPDATE $this->table SET checked='$state' WHERE gid=$blogId");
		$state = $state == 'y' ? 'n' : 'y';
		$this->db->query("UPDATE " . DB_PREFIX . "comment SET hide='$state' WHERE gid=$blogId");
		$Comment_Model = new Comment_Model();
		$Comment_Model->updateCommentNum($blogId);
	}

	/**
	 * Update the post view count
	 *
	 * @param int $blogId
	 */
	function updateViewCount($blogId) {
		$this->db->query("UPDATE $this->table SET views=views+1 WHERE gid=$blogId");
	}

	public function isRepeatPost($title, $time) {
/*vot*/		$sql = "SELECT gid FROM $this->table WHERE title='$title' AND date='$time' LIMIT 1";
		$res = $this->db->query($sql);
		$row = $this->db->fetch_array($res);
		return isset($row['gid']) ? (int)$row['gid'] : false;
	}

	/**
	 * Make Link to the nearest posts
	 *
	 * @param int $date unix Timestamp
	 * @return array
	 */
	function neighborLog($date) {
		$now = time();
		$date_state = "and date<=$now";
		$neighborlog = [];
		$neighborlog['nextLog'] = $this->db->once_fetch_array("SELECT title,gid FROM $this->table WHERE date < $date and hide = 'n' and checked='y' and type='blog' $date_state ORDER BY date DESC LIMIT 1");
		$neighborlog['prevLog'] = $this->db->once_fetch_array("SELECT title,gid FROM $this->table WHERE date > $date and hide = 'n' and checked='y' and type='blog' $date_state ORDER BY date LIMIT 1");
		if ($neighborlog['nextLog']) {
			$neighborlog['nextLog']['title'] = htmlspecialchars($neighborlog['nextLog']['title']);
		}
		if ($neighborlog['prevLog']) {
			$neighborlog['prevLog']['title'] = htmlspecialchars($neighborlog['prevLog']['title']);
		}
		return $neighborlog;
	}

	/**
	 * Get Random Post
	 */
	function getRandLog($num) {
		global $CACHE;
		$now = time();
		$date_state = "and date<=$now";
		$sta_cache = $CACHE->readCache('sta');
		$lognum = $sta_cache['lognum'];
		$start = $lognum > $num ? mt_rand(0, $lognum - $num) : 0;
		$sql = "SELECT gid,title FROM $this->table WHERE hide='n' and checked='y' and type='blog' $date_state LIMIT $start, $num";
		$res = $this->db->query($sql);
		$logs = [];
		while ($row = $this->db->fetch_array($res)) {
			$row['gid'] = (int)$row['gid'];
			$row['title'] = htmlspecialchars($row['title']);
			$logs[] = $row;
		}
		return $logs;
	}

	/**
	 * Get Hot Posts
	 */
	function getHotLog($num) {
		$now = time();
		$date_state = "and date<=$now";
		$sql = "SELECT gid,title FROM $this->table WHERE hide='n' and checked='y' and type='blog' $date_state ORDER BY views DESC, comnum DESC LIMIT 0, $num";
		$res = $this->db->query($sql);
		$logs = [];
		while ($row = $this->db->fetch_array($res)) {
			$row['gid'] = (int)$row['gid'];
			$row['title'] = htmlspecialchars($row['title']);
			$logs[] = $row;
		}
		return $logs;
	}

	/**
	 * Process Post alias, Prevent alias duplicated
	 */
	function checkAlias($alias, $logalias_cache, $logid) {
		static $i = 2;
		$key = array_search($alias, $logalias_cache);
		if (false !== $key && $key != $logid) {
			if ($i == 2) {
				$alias .= '-' . $i;
			} else {
				$alias = preg_replace("|(.*)-([\d]+)|", "$1-{$i}", $alias);
			}
			$i++;
			return $this->checkAlias($alias, $logalias_cache, $logid);
		}
		return $alias;
	}

	/**
	 * Encrypted Post access authentication
	 */
	function authPassword($postPwd, $cookiePwd, $logPwd, $logid) {
		$url = BLOG_URL;
		$pwd = $cookiePwd ?: $postPwd;
		if ($pwd !== addslashes($logPwd)) {
			if (view::isTplExist('pw')) {
				include view::getView('pw');
			} else {
				$page_pass = lang('page_password_enter');
				$submit_pass = lang('submit_password');
				$back = lang('back_home');
				echo <<<EOT
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name=renderer  content=webkit>
<title>{$page_pass}</title>
<link rel="stylesheet" type="text/css" href="{$url}admin/views/css/bootstrap.min.css">
</head>
<body class="text-center">
	<form action="" method="post" class="form-signin" style="width: 100%;max-width: 330px;padding: 15px;margin: 0 auto;">
          <input type="password" id="logpwd" name="logpwd" class="form-control" placeholder="{$page_pass}" required autofocus>
          <button class="btn btn-lg btn-primary btn-block mt-2" type="submit">{$submit_pass}"></button>
          <p class="mt-5 mb-3 text-muted"><a href="{$url}">{$back}</a></p>
    </form>
</body>
</html>
EOT;
			}
			if ($cookiePwd) {
				setcookie('em_logpwd_' . $logid, ' ', time() - 31536000);
			}
			exit;
		}

		setcookie('em_logpwd_' . $logid, $logPwd);
	}
}
