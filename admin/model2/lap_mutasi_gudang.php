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
	$dari_tgl = isset($_POST['dari_tgl']) ? $_POST['dari_tgl']!="" ? balikTanggal($_POST['dari_tgl']) : '' : '';
	$sampai_tgl = isset($_POST['sampai_tgl']) ? $_POST['sampai_tgl']!="" ? balikTanggal($_POST['sampai_tgl']) : '' : '';
	$barang = isset($_POST['barang']) ? $_POST['barang'] : '';
	$dari_gud = isset($_POST['dari_gud']) ? $_POST['dari_gud'] : '';
	$ke_gud = isset($_POST['ke_gud']) ? $_POST['ke_gud'] : '';
	
	
	if($idsub!="") $a = " AND m.uuid_skpd = '$idsub'";
	else $a = " AND MD5(m.uuid_skpd) = '$_SESSION[uidunit]'";
	if($ta!="") $b = " AND m.ta = '$ta'";
	else $b = "";
	if($dari_tgl!="" && $sampai_tgl) $c = " AND DATE_FORMAT(tgl_ba_mutasi, '%Y-%m-%d') BETWEEN '$dari_tgl' AND '$sampai_tgl'";
	else $c = "";
	if($barang!="") $d = " AND d.id_barang = '$barang'";
	else $d = "";
	if($dari_gud!="") $e = " AND gudang_asal = '$dari_gud'";
	else $e = "";
	if($ke_gud!="") $f = " AND gudang_tujuan = '$ke_gud'";
	else $f = "";
	
	$where = "$a $b $c $d $e $f";
	$offset = ($page-1)*$rows;
	$result = array();
	$clause = "SELECT nm_sub2_unit AS nama_unit, m.ta, no_ba_mutasi AS nomor, tgl_ba_mutasi AS tanggal, 
				IFNULL(nama_barang_kegiatan, nama_barang) barang, d.jml_barang AS jumlah,
				(SELECT nama_gudang FROM ref_gudang WHERE gudang_asal = id_gudang) AS dari_gud,
				(SELECT nama_gudang FROM ref_gudang WHERE gudang_tujuan = id_gudang) AS ke_gud
				FROM mutasi m
				LEFT JOIN mutasi_detail d ON  m.id_mutasi = d.id_mutasi
				LEFT JOIN ref_sub2_unit u
				ON m.uuid_skpd = u.uuid_sub2_unit
				LEFT JOIN ref_barang b ON b.id_barang = d.id_barang
				LEFT JOIN ref_barang_kegiatan bk ON bk.id_barang_kegiatan = d.id_barang
				WHERE m.soft_delete=0 AND d.soft_delete=0 $where";
				
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$rs = mysql_query("$clause LIMIT $rows OFFSET $offset ");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		$row['jumlah'] = number_format($row['jumlah'], 0, ',', '.');
		array_push($items, $row);
	}
	$result["rows"] = $items;
	echo json_encode($result);
	mysql_close();
?>