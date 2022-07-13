<?php

	require_once "../../config/db.koneksi.php";
	$uuid_skpd = isset($_GET['uuid_skpd']) ? $_GET['uuid_skpd'] : '';
	$cek = isset($_GET['cek']) ? 'ya' : '';
	$kd_kegiatan = isset($_GET['kd_kegiatan']) ? $_GET['kd_kegiatan'] : '';
	$id = isset($_GET['id']) ? $_GET['id'] : '';

	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 1000;
	$offset = ($page-1)*$rows;
	$result = array();


	
	$clause = "SELECT DISTINCT id_sumber AS id, nama_sumber AS text,
	(SELECT DISTINCT id_sumber_dana FROM log_import WHERE id_sumber_dana=ref_sumber_dana.id_sumber 
	AND uuid_skpd='$id') sumber FROM ref_sumber_dana
	ORDER BY id_sumber";
	
	//print_r($clause);
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$rs = mysql_query("$clause LIMIT $rows OFFSET $offset ");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		$row['id'] = $row['id'];		
		$row['text'] = $row['text'];
		//$row['sumber'] = $row['sumber'];
		//if($r==1) $row['selected'] = true;
		array_push($items, $row);
	}
	$result["rows"] = $items;
	echo json_encode($items);
	mysql_close();
?>