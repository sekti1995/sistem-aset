<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	$peran = cekLogin();
	error_reporting(E_ALL); ini_set('display_errors', 'on'); 
	
	$id = isset($_GET['id']) ? $_GET['id'] : '';
	$id_sbr = isset($_GET['idsbr']) ? $_GET['idsbr'] : '';
	$cek = isset($_GET['cek']) ? 'ya' : '';


	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 1000;
	$offset = ($page-1)*$rows;
	$result = array();

	if($id=='' || $id=='undefined'){
		$id_unit = $_SESSION['uidunit'] ; 
		$unit = "MD5(a.uuid_skpd)='$id_unit'";
		$sid = "MD5(uuid_sub2_unit)";
	}else{
		$id_unit = $id; 
		$unit = "a.uuid_skpd='$id_unit'";
		$sid = "uuid_sub2_unit";
	}


	
	$clause = "SELECT distinct a.uuid_skpd,a.kd_kegiatan ,a.nm_kegiatan  FROM log_import a
	 WHERE $unit ";
	
	// print_r($clause);
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$rs = mysql_query("$clause LIMIT $rows OFFSET $offset ");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		$row['kd_kegiatan'] = $row['kd_kegiatan'];
		$row['nm_kegiatan'] = $row['nm_kegiatan'];
		$row['uuid_skpd'] = $row['uuid_skpd'];
		//if($r==1) $row['selected'] = true;
		array_push($items, $row);
	}
	$result["rows"] = $items;
	echo json_encode($items);
	mysql_close();
?>