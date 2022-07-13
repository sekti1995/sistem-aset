<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";
	
	$peran = cekLogin();
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$sp = isset($_GET['sp']) ? $_GET['sp'] : '';
	$idsub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
	$ta = isset($_POST['ta']) ? $_POST['ta'] : '';
	$nomor_ba = isset($_POST['nomor']) ? $_POST['nomor'] : '';
	$nomor_sk = isset($_POST['nomor_sk']) ? $_POST['nomor_sk'] : '';
	$tanggal = isset($_POST['tanggal']) ? $_POST['tanggal']!="" ? balikTanggal($_POST['tanggal']) : '' : '';
	
	
	if($idsub!="") $a = " AND m.uuid_skpd = '$idsub'";
	else $a = " AND MD5(m.uuid_skpd) = '$_SESSION[uidunit]'";
	if($ta!="") $b = " AND ta = '$ta'";
	else $b = "";
	if($nomor_ba!="") $c = " AND no_ba_hapus LIKE '%$nomor_ba%' ";
	else $c = "";
	if($tanggal!="") $d = " AND DATE_FORMAT(tgl_ba_hapus, '%Y-%m-%d')  = '$tanggal' ";
	else $d = "";
	if($nomor_sk!="") $e = " AND no_ba_penunjukan LIKE '%$nomor_sk%' ";
	else $e = "";
	
	$where = "$a $b $c $d $e";
	$offset = ($page-1)*$rows;
	$result = array();
	$clause = "SELECT nm_sub2_unit AS nama_unit, ta, no_ba_hapus AS nomor_ba, tgl_ba_hapus AS tanggal,
				m.uuid_skpd AS id_sub, 
				no_ba_penunjukan AS nomor_sk, thn_ba_penunjukan AS tahun_sk,
				id_pejabat_ketua AS ketua, id_pejabat_sekretaris AS sekretaris, id_pejabat_anggota1 AS anggota1, 
				id_pejabat_anggota2 AS anggota2, 
				jabatan_ketua AS jab_ket, jabatan_sekretaris AS jab_sek, jabatan_anggota1 AS jab_ang1,
				jabatan_anggota2 AS jab_ang2, id_hapus_barang AS id
				FROM hapus_barang m
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