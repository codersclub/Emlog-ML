<?php
/**
 * Data Backup
 * @copyright (c) Emlog All Rights Reserved
 * $Id$
 */

require_once 'globals.php';

if($action == ''){
	$retval = glob('../content/backup/*.sql');
	$bakfiles = $retval ? $retval : array();
	$timezone = Option::get('timezone');
	$tables = array('attachment', 'blog', 'comment', 'options', 'reply', 'sort', 'link','tag','trackback','twitter','user');
	$defname = 'emlog_'. gmdate('Ymd', time() + $timezone * 3600) . '_' . substr(md5(gmdate('YmdHis', time() + $timezone * 3600)),0,18);
	doAction('data_prebakup');

	include View::getView('header');
	require_once(View::getView('data'));
	include View::getView('footer');
	View::output();
}

if($action == 'bakstart'){
	$bakfname = isset($_POST['bakfname']) ? $_POST['bakfname'] : '';
	$table_box = isset($_POST['table_box']) ? array_map('addslashes', $_POST['table_box']) : array();
	$bakplace = isset($_POST['bakplace']) ? $_POST['bakplace'] : 'local';

	$timezone = Option::get('timezone');

	if(!preg_match("/^[a-zA-Z0-9_]+$/",$bakfname)){
		emDirect("./data.php?error_b=true");
	}
	$filename = '../content/backup/'.$bakfname.'.sql';

	$sqldump = '';
	foreach($table_box as $table){
		$sqldump .= dataBak($table);
	}
	if(trim($sqldump)){
		$dumpfile = '#version:emlog '. Option::EMLOG_VERSION . "\n";
		$dumpfile .= '#date:' . gmdate('Y-m-d H:i', time() + $timezone * 3600) . "\n";
		$dumpfile .= '#tableprefix:' . DB_PREFIX . "\n";
		$dumpfile .= $sqldump;
		$dumpfile .= "\n#the end of backup";
		if($bakplace == 'local'){
			header('Content-Type: text/x-sql');
			header('Expires: '. gmdate('D, d M Y H:i:s', time() + $timezone * 3600) . ' GMT');
			header('Content-Disposition: attachment; filename=emlog_'. gmdate('Ymd', time() + $timezone * 3600).'.sql');
			if (preg_match("/MSIE ([0-9].[0-9]{1,2})/", $_SERVER['HTTP_USER_AGENT'])){
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
			} else {
				header('Pragma: no-cache');
				header('Last-Modified: '. gmdate('D, d M Y H:i:s', time() + $timezone * 3600) . ' GMT');
			}
			echo $dumpfile;
		} else {
			@$fp = fopen($filename, 'w+');
			if ($fp)
			{
				@flock($fp, 3);
				if(@!fwrite($fp, $dumpfile))
				{
					@fclose($fp);
					emMsg($lang['backup_directory_not_writable']);
				}else{
					emDirect("./data.php?active_backup=true");
				}
			}else{
				emMsg($lang['backup_create_file_error']);
			}
		}
	}else{
		emMsg($lang['backup_empty']);
	}
}

//Import Backup Data
if ($action == 'renewdata'){
	$sqlfile = isset($_GET['sqlfile']) ? $_GET['sqlfile'] : '';
	if (!file_exists($sqlfile)){
		emMsg($lang['file_not_exists']);
	}

	if (getFileSuffix($sqlfile) !== 'sql'){
		emMsg($lang['backup_extension_invalid']);
	}
	checkSqlFileInfo($sqlfile);
	bakindata($sqlfile);
	$CACHE->updateCache();
	emDirect("./data.php?active_import=true");
}

//Import local backup files
if ($action == 'import'){
	$sqlfile = isset($_FILES['sqlfile']) ? $_FILES['sqlfile'] : '';
	if (!$sqlfile) {
		emMsg($lang['backup_illegal_info']);
	}
	if (getFileSuffix($sqlfile['name']) != 'sql') {
		emMsg($lang['backup_extension_invalid']);
	}
	if ($sqlfile['error'] == 1){
		emMsg($lang['attachment_exceed_system_limit'].ini_get('upload_max_filesize'));
	}elseif ($sqlfile['error'] > 1){
		emMsg($lang['backup_sql_error'].$sqlfile['error']);
	}
	checkSqlFileInfo($sqlfile['tmp_name']);
	bakindata($sqlfile['tmp_name']);
	$CACHE->updateCache();
	emDirect("./data.php?active_import=true");
}

//Bulk delete the backup files
if($action == 'dell_all_bak'){
	if(!isset($_POST['bak'])){
		emDirect("./data.php?error_a=true");
	}else{
		foreach($_POST['bak'] as $val){
			unlink($val);
		}
		emDirect("./data.php?active_del=true");
	}
}

//Update the cache
if ($action == 'Cache'){
	$CACHE->updateCache();
	emDirect("./data.php?active_mc=true");
}

/**
 * Check the backup file header information
 * 
 * @param file $sqlfile
 */
function checkSqlFileInfo($sqlfile) {
	// Read backup file information
	$fp = @fopen($sqlfile, 'r');
	if ($fp){
		$dumpinfo = array();
		$line = 0;
		while (!feof($fp)){
			$dumpinfo[] = fgets($fp, 4096);
			$line++;
			if ($line == 3) break;
		}
		fclose($fp);
		if (!empty($dumpinfo)){
			// Verify version
			if (preg_match('/#version:emlog '. Option::EMLOG_VERSION .'/', $dumpinfo[0]) === 0) {
				emMsg($lang['backup_format_invalid'] . Option::EMLOG_VERSION);
			}
			// Verify table prefix
			if (preg_match('/#tableprefix:'. DB_PREFIX .'/', $dumpinfo[2]) === 0) {
				emMsg($lang['backup_prefix_invalid'] . $dumpinfo[2]);
			}
		} else {
			emMsg($lang['backup_bad_format']);
		}
	} else {
		emMsg($lang['backup_not_readable']);
	}
}

/**
 * Import the backup file
 *
 * @param string $filename
 */
function bakindata($filename) {
	$DB = MySql::getInstance();
	$setchar = $DB->getMysqlVersion() > '4.1' ? "ALTER DATABASE `" . DB_NAME . "` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;" : '';
	$sql = file($filename);
	if(isset($sql[0]) && !empty($sql[0]) && checkBOM($sql[0])) {
	    $sql[0] = substr($sql[0], 3);
	}
	array_unshift($sql,$setchar);
	$query = '';
	foreach($sql as $value){
		$value = trim($value);
		if(!$value || $value[0]=='#'){
			continue;
		}
		if(preg_match("/\;$/i", $value)){
			$query .= $value;
			if(preg_match("/^CREATE/i", $query)){
				$query = preg_replace("/\DEFAULT CHARSET=([a-z0-9]+)/is",'',$query);
			}
			$DB->query($query);
			$query = '';
		} else{
			$query .= $value;
		}
	}
}

/**
 * Back up the database structure and all the data
 *
 * @param string $table Database table name
 * @return string
 */
function dataBak($table){
	$DB = MySql::getInstance();
	$sql = "DROP TABLE IF EXISTS $table;\n";
	$createtable = $DB->query("SHOW CREATE TABLE $table");
	$create = $DB->fetch_row($createtable);
	$sql .= $create[1].";\n\n";

	$rows = $DB->query("SELECT * FROM $table");
	$numfields = $DB->num_fields($rows);
	$numrows = $DB->num_rows($rows);
	while ($row = $DB->fetch_row($rows)){
		$comma = "";
		$sql .= "INSERT INTO $table VALUES(";
		for ($i = 0; $i < $numfields; $i++){
			$sql .= $comma."'".mysql_escape_string($row[$i])."'";
			$comma = ",";
		}
		$sql .= ");\n";
	}
	$sql .= "\n";
	return $sql;
}

/**
 * Check if the file contains BOM (byte-order mark)
 */
function checkBOM($contents) {
    $charset[1] = substr($contents, 0, 1);
    $charset[2] = substr($contents, 1, 1);
    $charset[3] = substr($contents, 2, 1);
    if (ord($charset[1]) == 239 && ord($charset[2]) == 187 && ord($charset[3]) == 191) {
        return true;
    } else {
        return false;
    }
}
