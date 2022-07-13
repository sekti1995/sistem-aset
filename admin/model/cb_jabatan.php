<?php
	session_start();
	require_once "../../config/db.koneksi.php";

	if($_SESSION['level']==MD5('a')) $a = "WHERE id_jabatan IN (7,8,10)";
	elseif($_SESSION['level']==MD5('b') || $_SESSION['level']==MD5('c')) $a = "WHERE id_jabatan IN (9,11)";
	else $a = "";
	
	$clause = "SELECT * FROM ref_jabatan ";
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$rs = mysql_query("$clause");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		$row['id'] = $row['id_jabatan'];
		$row['text'] = $row['nama_jabatan'];
		array_push($items, $row);
	}
	
	echo json_encode($items);
	mysql_close();
	
?>