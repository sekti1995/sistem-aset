<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";
	
	$peran = cekLogin();
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$idsub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
	$tanggal = isset($_POST['tanggal']) ? $_POST['tanggal']!="" ? balikTanggal($_POST['tanggal']) : '' : '';
	$nomor = isset($_POST['nomor']) ? $_POST['nomor'] : '';
		

	if($idsub!="") $a = " AND m.uuid_skpd = '$idsub'";
	else $a = " ";	
	if($_SESSION['peran_id']==MD5('1')) $a .= "";
	else{
		if($_SESSION['level']==MD5('a')) $a .= " AND MD5(CONCAT_WS('.', u.kd_urusan, u.kd_bidang, u.kd_unit)) = '$_SESSION[peserta]'";
		elseif($_SESSION['level']==MD5('b')) $a .= "  AND MD5(CONCAT_WS('.', u.kd_urusan, u.kd_bidang, u.kd_unit, u.kd_sub)) = '$_SESSION[peserta]'";
	}
	if($tanggal!="") $b = " AND DATE_FORMAT(tgl_so, '%Y-%m-%d')  = '$tanggal' ";
	else $b = " AND ta = '".date('Y')."'";
	if($nomor!="") $c = " AND no_so LIKE '%$nomor%' ";
	else $c = "";
	
	$where = "$a $b $c";
	$offset = ($page-1)*$rows;
	$result = array();
	$clause = "SELECT nm_sub2_unit AS nama_unit, ta, uuid_skpd AS id_sub, no_so AS nomor,
				tgl_so AS tanggal, id_so AS id
				FROM so m
				LEFT JOIN ref_sub2_unit u
				ON m.uuid_skpd = u.uuid_sub2_unit
				WHERE soft_delete=0 $where";
				
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$rs = mysql_query("$clause LIMIT $rows OFFSET $offset ");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		$row['tanggal'] = balikTanggalIndo($row['tanggal']);
		array_push($items, $row);
	}
	$result["rows"] = $items;
	echo json_encode($result);
	mysql_close();
?>