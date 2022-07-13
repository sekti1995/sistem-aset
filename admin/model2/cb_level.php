<?php
	require_once "../../config/db.koneksi.php";
	$tingkat = isset($_GET['tingkat']) ? $_GET['tingkat'] : '';
	
	$clause = "SELECT * FROM ref_role ORDER BY id_role";
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$rs = mysql_query("$clause");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		$row['id'] = $row['id_role'];
		$row['text'] = $row['nama_role'];
		array_push($items, $row);
	}
	
	echo json_encode($items);
	mysql_close();
	
?>