<?php
	require_once "../../config/db.koneksi.php";
	require_once "../../config/library.php";
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	error_reporting(E_ALL); ini_set('display_errors', 'On'); 
	session_start();
	$peran = cekLogin();
	if($_SESSION['level']==md5('c')){
		echo '[{"id":"1","text":"Permintaan Dari Bidang"},{"id":"2","text":"Minta Kepada SKPD"},{"id":"3","text":"Barang Kegiatan"}]';
	} else if($_SESSION['level']==md5('a') || $peran==md5('1')){
		if($_SESSION['kode_skpd'] == '1.3.1.1.1' or $_SESSION['kode_skpd'] == '3.3.1.1.1' or $_SESSION['kode_skpd'] == '3.6.1.1.1' or $_SESSION['kode_skpd'] == '2.7.1.1.1' or $_SESSION['kode_skpd'] == '2.5.1.1.1' or $_SESSION['kode_skpd'] == '1.1.1.1.1' or $_SESSION['kode_skpd'] == '4.1.3.1.1'){
			echo '[{"id":"1","text":"Permintaan Dari Bidang"} ,{"id":"3","text":"Barang Kegiatan"}]';
		} else {
			echo '[{"id":"1","text":"Permintaan Dari Bidang","selected":true}]';
		}
	} else {
			echo '[{"id":"1","text":"Permintaan Dari Bidang","selected":true}]';
	}
	
?>