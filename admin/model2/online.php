<?php
	require_once "../../config/db.koneksi.php";
	
	$id = $_POST['id'];
	
	mysql_query("UPDATE ref_pengelola SET online = NOW() WHERE md5(id_pengelola) = '$id'");
	
	mysql_close();
?>