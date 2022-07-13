<?php
	session_start();
	require_once "../../config/db.koneksi.php";

	$clause = "SELECT * FROM ref_tahun";
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$rs = mysql_query("$clause");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		if(isset($_SESSION["sesi_ta"])){
			if($_SESSION["sesi_ta"] == $row["tahun"]){
				$row['selected'] = true;
			}
		}
		$row['id'] = $row['tahun'];
		$row['text'] = $row['tahun'];
		array_push($items, $row);
	}
	
	echo json_encode($items);
	mysql_close();
	
?>