<?php
	require_once "../../config/db.koneksi.php";
 
	$ta = isset($_GET['ta']) ? $_GET['ta'] : '';
	$uuid_skpd = isset($_GET['uuid_skpd']) ? $_GET['uuid_skpd'] : '';
	
	$clause = "SELECT * FROM masuk WHERE ta = '$ta' AND soft_delete = '0' AND uuid_skpd = '$uuid_skpd'";
				
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$rs = mysql_query("$clause");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		$row['id'] = $row['id_masuk'];
		$row['text'] = $row['nama_pengadaan'].' | BA '.$row['no_ba_penerimaan'];
		array_push($items, $row);
	}
	
	echo json_encode($items);
	mysql_close();
	
?>