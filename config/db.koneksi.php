<?php
error_reporting(E_ALL); ini_set('display_errors', 'off'); 
$my['host'] = "localhost";
$my['user'] = "root";
$my['pass'] = "";
$my['dbs'] = "test"; 

$koneksi=mysql_connect($my['host'],$my['user'],$my['pass']);
if (! $koneksi){
  echo "Gagal Koneksi..!".mysql_error();
  }
mysql_select_db($my['dbs'])
or die ("Database Tidak Ada".mysql_error());
$vdb = mysql_fetch_row(mysql_query("SELECT * FROM ver_db"));
$vui = mysql_fetch_row(mysql_query("SELECT * FROM ver_ui"));
$ver_db = $vdb[0]; 
$ver_ui = $vui[0]; 
$tgl_cetak_now = date("YmdHis");
?>
