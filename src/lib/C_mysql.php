<?php
/**
 * 数据库操作类
 * @copyright (c) Emlog All Rights Reserved
 * @version emlog-3.3.0
 * $Id$
 */


/**
 * MYSQL数据操方法封装类
 * 
 */

class MySql {

	var $queryCount = 0;
	var $conn;
	var $result;

	function MySql($dbHost = '', $dbUser = '', $dbPass = '', $dbName = '')
	{
		global $lang;
		if (!function_exists('mysql_connect'))
		{
			emMsg($lang['mysql_not_supported']);
		}
		if (!$this->conn = @mysql_connect($dbHost, $dbUser, $dbPass))
		{
			emMsg($lang['db_connect_error']);
		}
		if ($this->getMysqlVersion() >'4.1')
		{
			mysql_query("SET NAMES 'utf8'");
		}

		@mysql_select_db($dbName, $this->conn) OR emMsg($lang['db_not_found']);
	}

	/**
	 * 关闭数据库连接
	 *
	 * @return boolean
	 */
	function close()
	{
		return mysql_close($this->conn);
	}

	/**
	 * 发送查询语句
	 *
	 * @param string $sql
	 * @return boolean
	 */
	function query($sql)
	{
		$this->result = @ mysql_query($sql,$this->conn);
		$this->queryCount++;
		if (!$this->result)
		{
			emMsg($lang['sql_statement_error'].": $sql <br />".$this->geterror());
		} else {
			return $this->result;
		}
	}

	/**
	 * 从结果集中取得一行作为关联数组/数字索引数组
	 *
	 * @param resource $query
	 * @return array
	 */
	function fetch_array($query)
	{
		return mysql_fetch_array($query);
	}

	function once_fetch_array($sql)
	{
		$this->result = $this->query($sql);
		return $this->fetch_array($this->result);
	}
	
	/**
	 * 从结果集中取得一行作为数字索引数组
	 *
	 * @param resource $query
	 * @return integer
	 */
	function fetch_row($query)
	{
		return mysql_fetch_row($query);
	}

	/**
	 * 取得行的数目
	 *
	 * @param resource $query
	 * @return integer
	 */
	function num_rows($query)
	{
		return mysql_num_rows($query);
	}

	/**
	 * 取得结果集中字段的数目
	 *
	 * @param resource $query
	 * @return integer
	 */
	function num_fields($query)
	{
		return mysql_num_fields($query);
	}
	/**
	 * 取得上一步 INSERT 操作产生的 ID 
	 *
	 * @return integer
	 */
	function insert_id()
	{
		return mysql_insert_id($this->conn);
	}

	/**
	 * 获取mysql错误
	 *
	 * @return unknown
	 */
	function geterror()
	{
		return mysql_error();
	}
	
	/**
	 * Get number of affected rows in previous MySQL operation
	 *
	 * @return int
	 */
	function affected_rows()
	{
		return mysql_affected_rows();
	}

	/**
	 * 取得数据库版本信息
	 *
	 * @return string
	 */
	function getMysqlVersion()
	{
		return mysql_get_server_info();
	}
}

?>