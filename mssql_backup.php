<?php
//MSSQL��������ַ��ͨ��Ϊlocalhost  -  MSSQL Server address, usually is localhost
define('MSSQL_HOST', 'localhost');

//MSSQL�û���  -  MSSQL Login Username
//��Ҫ�ж�ȡmaster���Լ��������ݿ��Ȩ��  -  Need to have permission to read 'master' table and Backup Database
define('MSSQL_USER', 'sa');

//MSSQL���� - MSSQL Login Password
define('MSSQL_PASS', '');

//����Ŀ�ĵ�  -  Target directory to backup
define('TARGET_PATH', 'D:\MSSQL_BACKUP\\');

//ѹ�����·��������ΪWinRAR����7Zip  -  The path of compress tool, can use WinRAR or 7Zip
define('COMPRESS_TOOL_PATH', 'C:\Program Files\7zip\7z.exe');

/*  ��Ҫ���ĵ����ݵ���Ϊֹ��������֪�����ڸ�ʲô  */
/*  That's all you things you need to change, unless you know what you are doing  */

echo "MSSQL Backup tool\r\n";
if (PHP_SAPI != 'cli') die ('Shell only');
if (!extension_loaded('mssql')) die ("Need to enable php-mssql extension");

//���Ա���Ŀ¼��д��  -  Test is writable of backup directory
$temp_path = TARGET_PATH . 'test.tmp';
if (!@file_put_contents($temp_path, 'write test')) die ("Need write permission of directory " . TARGET_PATH);
unlink($temp_path);

$start_time = time();
$conn = mssql_connect(MSSQL_HOST, MSSQL_USER, MSSQL_PASS);
if (!$conn)	die('Connect to mssql failed');

//��ȡ���ݿ��б�  -  Get database list
$dblist = array();
$sys_dblist = array('master', 'model', 'msdb', 'Northwind', 'pubs', 'tempdb');
$query = mssql_query('SELECT name FROM master..sysdatabases');
while ($result = mssql_fetch_array($query)) {
	if (!in_array($result[0], $sys_dblist))
		$dblist[] = $result[0];
}

//�������ݿ�  -  Backup database
foreach ($dblist as $dbname) {
	echo "Backup {$dbname}...\r\n";
	$path = TARGET_PATH . $dbname . '_' . date('Ymd') . '.bak';
	$sql = 'BACKUP DATABASE '.$dbname.' TO DISK="'.$path.'"';
	mssql_query($sql);
}

//ѹ�������ļ�  -  Compress backup file
echo "Compress backup...\r\n";
$backup_path = TARGET_PATH . '*.bak';
$target_file = TARGET_PATH . 'mssql_' . date('Ymd') . '.zip';
$command = '"' . COMPRESS_TOOL_PATH . '" a ' . $target_file . ' ' . $backup_path;
`$command`;

//ɾ����ʱ�ļ�  -  Delete temp file
echo "Delete temp file...\r\n";
$command = 'del ' . TARGET_PATH . '*.bak';
`$command`;

//ɾ���ɵı����ļ�  -  Delete old backup files
echo "Delete old backup...\r\n";
$backup_filelist = glob(TARGET_PATH . '*.rar');
foreach ($backup_filelist as $each_file) {
	if ($start_time - fileatime($each_file) >= 2678400) {
		echo "Delete {$each_file}\r\n";
		unlink($each_file);
	}
}

//����MSSQL���ݼ�¼  -  Clean MSSQL Backup record
echo "Clean MSSQL backup record...\r\n";
mssql_select_db('msdb', $conn);
$del_date = date('m/d/Y', $now + 86400);
$sql = "sp_delete_backuphistory '{$del_date}'";
mssql_query($sql);

//�ܺ�ʱ  -  Total cost time
echo "All done, cost " . (time() - $start_time) . " seconds";
?>
