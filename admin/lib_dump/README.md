MySQL-Dump-with-Foreign-keys
============================
Simple class to dump your databse with foreign keys.

No `exec` `passthru` etc needed.

Example usage:

```
<?php
//MySQL connection parameters
$dbhost = 'localhost';
$dbuser = 'dbuser';
$dbpsw = 'pass';
$dbname = 'dbname';

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
    //'skip_structure' => TRUE, // if set true = only data is in result
    //'skip_data' => TRUE, // if set true = only structure and FKs are in result
);

//Make dump
$dumper->doFKDump($params);

?>
```
