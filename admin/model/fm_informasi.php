<?php
	require_once "../../config/db.koneksi.php";
	
	$r = mysql_fetch_assoc(mysql_query("SELECT * FROM informasi"));
	$result = $r['isi'];
	
	echo json_encode($result);
	mysql_close();
?>	