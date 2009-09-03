<?php
/**
 * Model: Comments Management
 * @copyright (c) Emlog All Rights Reserved
 * @version emlog-3.3.0
 * $Id$
 */

class emComment {

	var $db;

	function emComment($dbhandle)
	{
		$this->db = $dbhandle;
	}

	/**
	 * Get comments by condition
	 *
	 * @param int $spot //0: foreground 1: Background
	 * @param int $blogId
	 * @param string $hide
	 * @param int $page
	 * @return array
	 */
	function getComments($spot = 0, $blogId = null, $hide = null, $page = null)
	{
		$andQuery = '1=1';
		$andQuery .= $blogId ? " and a.gid=$blogId" : '';
		$andQuery .= $hide ? " and a.hide='$hide'" : '';
		$condition = '';
		if($page)
		{
			$startId = ($page - 1) *ADMIN_PERPAGE_NUM;
			$condition = "LIMIT $startId, ".ADMIN_PERPAGE_NUM;
		}
		if($spot == 0)
		{
			$sql = "SELECT * FROM ".DB_PREFIX."comment as a where $andQuery ORDER BY a.cid DESC $condition";
		}else{
			$andQuery .= ROLE != 'admin' ? ' and b.author='.UID : '';
			$sql = "SELECT *,a.hide,a.date FROM ".DB_PREFIX."comment as a, ".DB_PREFIX."blog as b where $andQuery and a.gid=b.gid ORDER BY a.cid DESC $condition";
		}
		$ret = $this->db->query($sql);
		$comments = array();
		while($row = $this->db->fetch_array($ret))
		{
			$row['cname'] = htmlspecialchars($row['poster']);
			$row['mail'] = htmlspecialchars($row['mail']);
			$row['url'] = htmlspecialchars($row['url']);
			$row['content'] = htmlClean($row['comment']);
			$row['date'] = smartyDate($row['date']);
			$row['reply'] = htmlClean($row['reply']);
			//$row['hide'];
			//$row['title'];
			//$row['gid'];
			$comments[] = $row;
		}
		return $comments;
	}
	function getOneComment($commentId)
	{
		$sql = "select * from ".DB_PREFIX."comment where cid=$commentId";
		$res = $this->db->query($sql);
		$commentArray = $this->db->fetch_array($res);
		$commentArray['comment'] = htmlClean(trim($commentArray['comment']));
		$commentArray['reply'] = htmlClean(trim($commentArray['reply']));
		$commentArray['poster'] = trim($commentArray['poster']);
		$commentArray['date'] = date("Y-m-d H:i",$commentArray['date']);
		return $commentArray;
	}
	/**
	 * Count the post comments
	 *
	 * @param int $blogId
	 * @return int $comNum
	 */
	function getCommentNum($blogId = null, $hide = null)
	{
		$comNum = '';
		$andQuery = '1=1';
		$andQuery .= $blogId ? " and a.gid=$blogId" : '';
		$andQuery .= $hide ? " and a.hide='$hide'" : '';
		if (ROLE == 'admin')
		{
			$sql = "SELECT a.cid FROM ".DB_PREFIX."comment as a where $andQuery";
		}else {
			$sql = "SELECT a.cid FROM ".DB_PREFIX."comment as a, ".DB_PREFIX."blog as b where $andQuery and a.gid=b.gid and b.author=".UID;
		}
		$res = $this->db->query($sql);
		$comNum = $this->db->num_rows($res);
		return $comNum;
	}
	/**
	 * Delete the comment by ID
	 *
	 * @param int $commentId
	 */
	function delComment($commentId)
	{
		$row = $this->db->once_fetch_array("SELECT gid,hide FROM ".DB_PREFIX."comment WHERE cid=$commentId");
		$this->db->query("DELETE FROM ".DB_PREFIX."comment where cid=$commentId");
		$blogId = intval($row['gid']);
		if($row['hide'] == 'n')
		{
			$this->db->query("UPDATE ".DB_PREFIX."blog SET comnum=comnum-1 WHERE gid=$blogId");
		}
	}
	/**
	 * Hide the comment by ID
	 *
	 * @param int $commentId
	 */
	function hideComment($commentId)
	{
		$row = $this->db->once_fetch_array("SELECT gid,hide FROM ".DB_PREFIX."comment WHERE cid=$commentId");
		$blogId = intval($row['gid']);
		$isHide = $row['hide'];
		if($isHide == 'n')
		{
			$this->db->query("UPDATE ".DB_PREFIX."blog SET comnum=comnum-1 WHERE gid=$blogId");
		}
		$this->db->query("UPDATE ".DB_PREFIX."comment SET hide='y' WHERE cid=$commentId");
	}
	/**
	 * Show the comment by ID
	 *
	 * @param int $commentId
	 */
	function showComment($commentId)
	{
		$row = $this->db->once_fetch_array("SELECT gid,hide FROM ".DB_PREFIX."comment WHERE cid=$commentId");
		$blogId = intval($row['gid']);
		$isHide = $row['hide'];
		if($isHide == 'y')
		{
			$this->db->query("UPDATE ".DB_PREFIX."blog SET comnum=comnum+1 WHERE gid=$blogId");
		}
		$this->db->query("UPDATE ".DB_PREFIX."comment SET hide='n' WHERE cid=$commentId");
	}

	/**
	 * Reply the comment
	 *
	 * @param int $commentId
	 * @param string $reply
	 */
	function replyComment($commentId, $reply)
	{
		$sql="UPDATE ".DB_PREFIX."comment SET reply='$reply' where cid=$commentId ";
		$this->db->query($sql);
	}

	/**
	 * Batch Comment Actions
	 *
	 * @param string $action
	 * @param array $comments
	 */
	function batchComment($action, $comments)
	{
		switch ($action)
		{
			case 'delcom':
				foreach($comments as $val)
				{
					$this->delComment($val);
				}
				break;
			case 'hidecom':
				foreach($comments as $val)
				{
					$this->hideComment($val);
				}
				break;
			case 'showcom':
				foreach($comments as $val)
				{
					$this->showComment($val);
				}
				break;
		}
	}

	/**
	 * Add a comment
	 *
	 * @param unknown_type $name
	 * @param unknown_type $content
	 * @param unknown_type $mail
	 * @param unknown_type $url
	 * @param unknown_type $imgcode
	 * @param unknown_type $comment_code
	 * @param unknown_type $ischkcomment
	 * @param unknown_type $localdate
	 * @param unknown_type $blogId
	 */
	function addComment($name, $content, $mail, $url, $imgcode, $comment_code, $ischkcomment, $localdate, $blogId)
	{
		global $lang;
		if( $comment_code == 'y' )
		{
			session_start();
		}
		if ($url && strncasecmp($url,'http://',7))//0 if they are equal
		{
			$url = 'http://'.$url;
		}
		$this->setCommentCookie($name,$mail,$url,$localdate);
		if($this->isLogCanComment($blogId) === false)
		{
			emMsg($lang['comment_error_disabled'],'javascript:history.back(-1);');
		}elseif ($this->isCommentExist($blogId, $name, $content) === true){
			emMsg($lang['comment_error_allready_exists'],'javascript:history.back(-1);');
		}elseif (preg_match("/['<>,#|;\/\$\\&\r\t()%@+?^]/",$name) || strlen($name) > 20 || strlen($name) == 0){
			emMsg($lang['comment_error_invalid_name'],'javascript:history.back(-1);');
		} elseif ($mail != '' && !checkMail($mail)) {
			emMsg($lang['comment_error_invalid_email'], 'javascript:history.back(-1);');
		} elseif (strlen($content) == '' || strlen($content) > 2000) {
			emMsg($lang['comment_error_invalid_content'],'javascript:history.back(-1);');
		} elseif ($imgcode == '' && $comment_code == 'y') {
			emMsg($lang['comment_error_nocode'],'javascript:history.back(-1);');
		} elseif ($comment_code == 'y' && $imgcode != $_SESSION['code']) {
			emMsg($lang['comment_error_invalid_code'],'javascript:history.back(-1);');
		} else {
			$ipaddr = getIp();
			$sql = "INSERT INTO ".DB_PREFIX."comment (date,poster,gid,comment,reply,mail,url,hide,ip) 
					VALUES ('$localdate','$name','$blogId','$content','','$mail','$url','$ischkcomment','$ipaddr')";
			$ret = $this->db->query($sql);
			if ($ischkcomment == 'n')
			{
				$this->db->query("UPDATE ".DB_PREFIX."blog SET comnum = comnum + 1 WHERE gid='$blogId'");
				return 0;
			} else {
				return 1;
			}
		}
	}

	function isCommentExist($blogId, $name, $content)
	{
		$query = $this->db->query("SELECT cid FROM ".DB_PREFIX."comment WHERE gid=$blogId AND poster='$name' AND comment='$content'");
		$result = $this->db->num_rows($query);
		if ($result > 0)
		{
			return true;
		}else {
			return false;
		}
	}

	function isLogCanComment($blogId)
	{
		$query = $this->db->query("SELECT allow_remark FROM ".DB_PREFIX."blog WHERE gid=$blogId");
		$show_remark = $this->db->fetch_array($query);
		if ($show_remark['allow_remark'] == 'n')
		{
			return false;
		}else {
			return true;
		}
	}

	function setCommentCookie($name,$mail,$url,$localdate)
	{
		$cookietime = $localdate + 31536000;
		setcookie('commentposter',$name,$cookietime);
		setcookie('postermail',$mail,$cookietime);
		setcookie('posterurl',$url,$cookietime);
	}

}

?>
