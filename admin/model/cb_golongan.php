<?php
	require_once "../../config/db.koneksi.php";

	$clause = "SELECT * FROM ref_golongan";
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$rs = mysql_query("$clause");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		$row['id'] = $row['id_golongan'];
		$row['text'] = $row['nama_golongan'];
		array_push($items, $row);
	}
	
	echo json_encode($items);
	mysql_close();
	
?>