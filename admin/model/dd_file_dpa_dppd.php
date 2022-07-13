<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/library.php";

	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 8;
	$jenis = isset($_GET['jenis']) ? $_GET['jenis'] : '';
	$tahun = isset($_POST['tahun']) ? $_POST['tahun'] : '';
	if($tahun!='') $th = "AND tahun = '$_POST[tahun]'"; else $th = "";
	
	
	$offset = ($page-1)*$rows;
	$result = array();
	$clause = "SELECT *, id_file_dpa AS id
				FROM file_dpa";
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$rs = mysql_query("$clause LIMIT $rows OFFSET $offset ");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		//$row['download'] = "<a href='#' onClick='downKey($row[id])'>Aktivasi.key</a>";
		if($_SESSION['peran_id']!=md5('1'))
		$row['nama_file'] = "<a href='aksi.php?module=file_dpa_dppd&oper=down_dpa&id=$row[id]'>$row[nama_file_dpa]</a>";
		else $row['nama_file'] = $row['nama_file_dpa'];
		$row['time_upload'] = date('d-m-Y H:i:s', strtotime($row['dt_upload']));
		array_push($items, $row);
	}
	$result["rows"] = $items;
	echo json_encode($result);
	mysql_close();
?>