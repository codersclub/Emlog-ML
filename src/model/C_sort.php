<?php
/**
 * Model: Blog Categories
 * @copyright (c) Emlog All Rights Reserved
 * @version emlog-3.3.0
 * $Id$
 */

class emSort {

	var $db;

	function emSort($dbhandle)
	{
		$this->db = $dbhandle;
	}

	function getSorts()
	{
		$res = $this->db->query("SELECT * FROM ".DB_PREFIX."sort ORDER BY taxis ASC");
		$sorts = array();
		while($row = $this->db->fetch_array($res))
		{
			$row['sortname'] = htmlspecialchars($row['sortname']);
			$sorts[] = $row;
		}
		return $sorts;
	}

	function updateSort($sortData, $sid)
	{
		$Item = array();
		foreach ($sortData as $key => $data)
		{
			$Item[] = "$key='$data'";
		}
		$upStr = implode(',', $Item);
		$this->db->query("update ".DB_PREFIX."sort set $upStr where sid=$sid");
	}

	function addSort($name)
	{
		$sql="insert into ".DB_PREFIX."sort (sortname) values('$name')";
		$this->db->query($sql);
	}
	
	function deleteSort($sid)
	{
		$this->db->query("update ".DB_PREFIX."blog set sortid=-1 where sortid=$sid");
		$this->db->query("DELETE FROM ".DB_PREFIX."sort where sid=$sid");
	}

	function getSortName($sid)
	{
		global $lang;
		if($sid > 0)
		{
			$res = $this->dbhd->query("SELECT sortname FROM $this->sortTable where sid=$sid");
			$row = $this->dbhd->fetch_array($res);
			$sortName = htmlspecialchars($row['sortname']);
		}else {
			$sortName = $lang['unclassified'];
		}
		return $sortName;
	}
}
