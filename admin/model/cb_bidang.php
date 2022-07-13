<?php
	require_once "../../config/db.koneksi.php";

	$urusan = isset($_GET['id_urusan']) ? "WHERE kd_urusan ='$_GET[id_urusan]'" : '';
	
	$clause = "SELECT *, CONCAT_WS('.', kd_urusan, kd_bidang) AS id_bidang 
				FROM ref_bidang $urusan";
				
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$rs = mysql_query("$clause");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		$row['id'] = $row['id_bidang'];
		$row['text'] = $row['nm_bidang'];
		array_push($items, $row);
	}
	
	echo json_encode($items);
	mysql_close();
	
?>