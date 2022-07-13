<?php
date_default_timezone_set('Asia/Jakarta');
include_once "../config/db.koneksi.php";
session_start();
/* 
	exec('mysqldump --user=root --password=2016.Sqlbaper --host=localhost simbaper_fifo_trial > /var/www/backup_db/'.$file_backup);
   */
	//mysql_query("SET CHARSET utf8");
	//mysql_query("SET NAMES 'utf8' COLLATE 'utf8_general_ci'");
	//Includes class
	require_once('lib_dump/FKMySQLDump.php');
	$date = date("Ymd-His");
	$file_backup = "db_".$date.".sql";
	//Creates a new instance of FKMySQLDump: it exports without compress and base-16 file
	$dumper = new MySQLDump('persedian_2022','db/'.$file_backup,false,false);
	$params = array(
		'skip_structure' => TRUE,
		//'skip_data' => TRUE,
	);
	//Make dump
	$dumper->doDump($params);
	$log = mysql_query("INSERT INTO db_backup VALUES(UUID(),'$_SESSION[idpengguna]','$_SESSION[username]','$file_backup','persedian_2022',NOW())");

	if($log) {
		echo "Backup Database Berhasil !";
    } else {
		echo "Backup Database Gagal !";
	}
mysql_close();
?>