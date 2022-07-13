<?php
	require_once "../../config/db.koneksi.php";

	$clause = "SELECT * FROM ref_satuan";
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$rs = mysql_query("$clause");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		$row['id'] = $row['id_satuan'];
		$row['id_sat'] = $row['id_satuan'];
		$row['text'] = $row['nama_satuan'];
		$row['nama_sat'] = $row['simbol'];
		array_push($items, $row);
	}
	
	echo json_encode($items);
	mysql_close();
	
?>