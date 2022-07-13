<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";
	
	$peran = cekLogin();
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10000;
	$idsub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
	$ta = isset($_POST['ta']) ? $_POST['ta'] : '';
	$nomor = isset($_POST['nomor']) ? $_POST['nomor'] : '';
	$tanggal = isset($_POST['tanggal']) ? $_POST['tanggal']!="" ? balikTanggal($_POST['tanggal']) : '' : '';
	$untuk = isset($_POST['untuk']) ? $_POST['untuk'] : '';
	if($_SESSION['uidunit_plain'] == 'cfa4f56a-5543-11e6-a2df-000476f4fa98') {
		$id_sub = "";
	} else {
		if($_SESSION['level']==md5('a')) $id_sub = " AND MD5(CONCAT_WS('.',u.kd_urusan,u.kd_bidang,u.kd_unit)) = '$_SESSION[peserta]'";
		elseif($_SESSION['level']==md5('b')) $id_sub = " AND MD5(CONCAT_WS('.',u.kd_urusan,u.kd_bidang,u.kd_unit,u.kd_sub)) = '$_SESSION[peserta]'";
		elseif($_SESSION['level']==md5('c')) $id_sub = " AND MD5(u.uuid_sub2_unit) = '$_SESSION[uidunit]'";
		else $id_sub = "";
	}
	
	if($idsub!="") $a = " AND k.uuid_skpd = '$idsub'";
	else{
		IF($peran!=md5('1')) $a = " AND MD5(k.uuid_skpd) = '$_SESSION[uidunit]'";
		else $a = "";
	}
	if($_SESSION['uidunit_plain'] == 'cfa4f56a-5543-11e6-a2df-000476f4fa98') {$a = " AND k.uuid_skpd = '$idsub'";}
	
	if($ta!="") $b = " AND k.ta = '$ta'";
	else $b = "";
	if($nomor!="") $c = " AND no_ba_out LIKE '%$nomor%' ";
	else $c = "";
	if($tanggal!="") $d = " AND DATE_FORMAT(tgl_ba_out, '%Y-%m-%d')  = '$tanggal' ";
	else $d = "";
	if($untuk!="") $e = " AND k.uuid_untuk = '$untuk'";
	else $e = "";
	
	$where = "$a $b $c $d $e";
	$offset = ($page-1)*$rows;
	$result = array();
	$clause = "SELECT u.nm_sub2_unit AS nm_skpd, k.ta, k.jenis_out, no_ba_out AS nomor, DATE_FORMAT(tgl_ba_out, '%d-%m-%Y') AS tanggal, 
				k.uuid_skpd AS id_sub, id_keluar AS id, k.uuid_untuk AS id_untuk, 
				IFNULL(r.nm_sub2_unit, peruntukan) AS untuk, id_sp_out AS dasar_keluar, status, jenis_out AS jenis
				FROM keluar k
				LEFT JOIN ref_sub2_unit u ON k.uuid_skpd = u.uuid_sub2_unit
				LEFT JOIN ref_sub2_unit r ON k.uuid_untuk = r.uuid_sub2_unit
				WHERE soft_delete=0 $where $id_sub";
				
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$rs = mysql_query("$clause LIMIT $rows OFFSET $offset ");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		array_push($items, $row);
	}
	$result["rows"] = $items;
	echo json_encode($result);
	mysql_close();
?>