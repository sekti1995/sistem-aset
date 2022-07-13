<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";
	
	$peran = cekLogin();
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$idsub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
	$ta = isset($_POST['ta']) ? $_POST['ta'] : '';
	$nomor = isset($_POST['nomor']) ? $_POST['nomor'] : '';
	$tanggal = isset($_POST['tanggal']) ? $_POST['tanggal']!="" ? balikTanggal($_POST['tanggal']) : '' : '';
	$darigud = isset($_POST['dari_gud']) ? $_POST['dari_gud'] : '';
	$kegud = isset($_POST['ke_gud']) ? $_POST['ke_gud'] : '';
	
	
	
	if($_SESSION['peran_id']==MD5('1')) $a = "";
	else{
		/* if($_SESSION['level']==MD5('a')) $a = " AND MD5(CONCAT_WS('.', u.kd_urusan, u.kd_bidang, u.kd_unit)) = '$_SESSION[peserta]'";
		elseif($_SESSION['level']==MD5('b')) $a = "  AND MD5(CONCAT_WS('.', u.kd_urusan, u.kd_bidang, u.kd_unit, u.kd_sub)) = '$_SESSION[peserta]'";
		else */ $a = "AND MD5(m.uuid_skpd) = '$_SESSION[uidunit]'";
	}
	if($idsub!="") $a .= " AND m.uuid_skpd = '$idsub'";
	
	
	if($ta!="") $b = " AND m.ta = '$ta'";
	else $b = "";
	if($nomor!="") $c = " AND no_ba_mutasi LIKE '%$nomor%' ";
	else $c = "";
	if($tanggal!="") $d = " AND DATE_FORMAT(tgl_ba_mutasi, '%Y-%m-%d')  = '$tanggal' ";
	else $d = "";
	if($darigud!="") $e = " AND gudang_asal = '$darigud'";
	else $e = "";
	if($kegud!="") $f = " AND gudang_tujuan = '$kegud'";
	else $f = "";
	
	$where = "$a $b $c $d $e $f";
	$offset = ($page-1)*$rows;
	$result = array();
	$clause = "SELECT nm_sub2_unit AS nama_unit, m.ta, no_ba_mutasi AS nomor, tgl_ba_mutasi AS tanggal,
				m.uuid_skpd AS id_sub, id_mutasi AS id, id_pejabat_pengguna AS id_pengguna,
				id_pejabat_pengurus AS id_pengurus, gudang_asal AS dari_gud, gudang_tujuan AS ke_gud,
				(SELECT nama_gudang FROM ref_gudang WHERE id_gudang = gudang_asal) AS dari,
				(SELECT nama_gudang FROM ref_gudang WHERE id_gudang = gudang_tujuan) AS ke
				FROM mutasi m
				LEFT JOIN ref_sub2_unit u ON m.uuid_skpd = uuid_sub2_unit
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