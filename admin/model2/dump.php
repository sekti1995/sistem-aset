<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";
	
	$peran = cekLogin(); 
	
	$result = array();
	
	$clause = "SELECT * FROM db_backup ORDER BY timestamp DESC LIMIT 50";
				
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$items = array(); $ttotal = 0; $footer = array();
	while($row = mysql_fetch_assoc($rs)){ 
		
		$row['timestamp'] = balikTanggalIndo($row['timestamp']);
		$row['url'] = "../../backup_db/".$row['file'];
		$row['download'] = "<span onClick='unduhDB(".'"'.$row['url'].'"'.")'><b style='color:#2233ef;cursor:pointer'>Unduh</b></span>";
		
		array_push($items, $row);
	}
	$result["rows"] = $items;  
	
	echo json_encode($result);
	mysql_close();
?>