<?php
	require_once "../../config/db.koneksi.php";

	$kel = isset($_GET['kel']) ? $_GET['kel'] : '';
	
	if($kel!='') $k = "AND kd_kel = '$kel'";
	else $k = "";
	
	$clause = "SELECT id_jenis AS id, nama_jenis AS text FROM ref_jenis WHERE kd_sub <> 0 $k ORDER BY kd_kel, kd_sub";
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$rs = mysql_query("$clause");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		array_push($items, $row);
	}
	
	echo json_encode($items);
	mysql_close();
	
?>