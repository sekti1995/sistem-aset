<?php

date_default_timezone_set('Asia/Jakarta');
error_reporting(E_ALL); ini_set('display_errors', 'on'); 
require_once "../config/db.koneksi.php";
session_start();


if(isset($_REQUEST['aksi']))$aksi = $_REQUEST['aksi'];
if(isset($_REQUEST['id_sub']))$uuid_skpd = $_REQUEST['id_sub'];
if(isset($_REQUEST['smt']))$smt = $_REQUEST['smt'];
if(isset($_REQUEST['ta']))$ta = $_REQUEST['ta'];
if(isset($_REQUEST['id_sumber_dana']))$id_sumber_dana = $_REQUEST['id_sumber_dana'];

if($aksi == "in"){
	$q = mysql_fetch_assoc(mysql_query("	SELECT 
												status
											FROM 
												import_tmp_in
											WHERE 
												uuid_skpd = '$uuid_skpd' AND
												id_sumber_dana = '$id_sumber_dana' AND
												smt = '$smt' AND
												ta = '$ta'
											LIMIT 1 "));
} else {
	$q = mysql_fetch_assoc(mysql_query("	SELECT 
												status
											FROM 
												import_tmp_out
											WHERE 
												uuid_skpd = '$uuid_skpd' AND
												id_sumber_dana = '$id_sumber_dana' AND
												smt = '$smt' AND
												ta = '$ta'
											LIMIT 1 "));
}
echo json_encode(array('aksi'=>$aksi, 'status'=>$q['status']));

mysql_close();
?>
