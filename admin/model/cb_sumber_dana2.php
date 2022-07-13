<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	error_reporting(E_ALL); ini_set('display_errors', 'off'); 
	$peran = cekLogin();
	
	$id = isset($_GET['id']) ? $_GET['id'] : '';
	$id_akses = isset($_GET['id_akses']) ? $_GET['id_akses'] : '';
	$cek = isset($_GET['cek']) ? 'ya' : '';
	$all = isset($_GET['all']) ? 'ya' : '';
	$skpd = isset($_GET['skpd']) ? 'ya' : '';
	$kd = isset($_GET['kd']) ? $_GET['kd'] : '';
	$semua = isset($_GET['semua']) ? $_GET['semua'] : '';
	//$_SESSION['peserta']  = "";
	
	
	if($kd=="" && $peran!=MD5('1')) $kode = "WHERE MD5(CONCAT_WS('.',kd_urusan,kd_bidang, kd_unit)) = '$_SESSION[peserta]'";
	else $kode = "WHERE CONCAT_WS('.',kd_urusan,kd_bidang, kd_unit) = '$kd'";
	
	if($id_akses == 2 or $id_akses == 5){
		$o = mysql_fetch_assoc(mysql_query("SELECT * FROM ref_sub2_unit WHERE uuid_sub2_unit = '$id' "));
		$kode = "WHERE kd_unit = '$o[kd_unit]'";
	}
	
	if($id=='' || $id=='undefined'){
		$id_unit = $_SESSION['uidunit'] ; 
		$unit = "AND MD5(uuid_skpd)='$id_unit'";
		$sid = "MD5(uuid_sub2_unit)";
	}else{
		$id_unit = $id; 
		$unit = "AND uuid_skpd='$id_unit'";
		$sid = "uuid_sub2_unit";
	}
	$muncul = "sebagian";
	$qcek = mysql_query("SELECT $sid FROM ref_sub2_unit 
				WHERE MD5(CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit)) IN ('acb766c6067f046105f8b1263b9e5172','872ccd9c6dce18ce6ea4d5106540f089')");
	while($ck = mysql_fetch_row($qcek)){
		if($ck[0]==$id_unit){
			$muncul = "all";
		}
	}
	
	if($muncul=="all") $where = "";
	else $where = $where = " WHERE id_sumber IN ('27','28','29')";
	if($all=='ya') $where = "";
	
		$clause = "SELECT DISTINCT(id_sumber) AS id, nama_sumber AS text 
				FROM kartu_stok LEFT JOIN ref_sumber_dana ON id_sumber = id_sumber_dana 
				WHERE uuid_skpd IN (SELECT uuid_sub2_unit FROM ref_sub2_unit $kode)
				ORDER BY id_sumber_dana";
	
				
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$rs = mysql_query("$clause");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){ 
		if($_SESSION["sesi_sd"] == $row["id"]){
			$row['selected'] = true;
		} else {
			if($r==1) $row['selected'] = true;
		}
		
		
		array_push($items, $row);
	}
	if($semua != 'no'){
		$row['id'] = '';
		$row['text'] = 'Semua Sumber Dana';
	}
	array_push($items, $row);
	
	echo json_encode($items);
	mysql_close();
	
?>