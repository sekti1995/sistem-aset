<?php
//MySQL connection parameters
$dbhost = 'localhost';
$dbuser = 'dynamic';
$dbpsw = 'ppp';
$dbname = 'dynamic';

//Connects to mysql server
$connessione = @mysql_connect($dbhost,$dbuser,$dbpsw);

//Set encoding
mysql_query("SET CHARSET utf8");
mysql_query("SET NAMES 'utf8' COLLATE 'utf8_general_ci'");

//Includes class
require_once('FKMySQLDump.php');


//Creates a new instance of FKMySQLDump: it exports without compress and base-16 file
$dumper = new FKMySQLDump($dbname,'fk_dump.sql',false,false);

$params = array(
	//'skip_structure' => TRUE,
	'skip_data' => TRUE,
);

//Make dump
$dumper->doFKDump($params);

?>