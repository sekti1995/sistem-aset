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
	$id_aksi = isset($_POST['id_aksi']) ? $_POST['id_aksi'] : '';
	
	
	if($idsub!="") $a = " AND m.uuid_skpd = '$idsub'";
	else $a = " AND MD5(m.uuid_skpd) = '$_SESSION[uidunit]'";
	if($ta!="") $b = " AND ta = '$ta'";
	else $b = "";
	if($nomor_ba!="") $c = " AND no_ba_usulan LIKE '%$nomor_ba%' ";
	else $c = "";
	if($tanggal!="") $d = " AND DATE_FORMAT(tgl_ba_usulan, '%Y-%m-%d')  = '$tanggal' ";
	else $d = "";
	if($nomor_sk!="") $e = " AND no_ba_penunjukan LIKE '%$nomor_sk%' ";
	else $e = "";
	if($id_aksi!="") $f = " AND m.id_aksi_penghapusan = '$id_aksi' ";
	else $f = "";
	
	$where = "$a $b $c $d $e $f";
	$offset = ($page-1)*$rows;
	$result = array();
	$clause = "SELECT u.nm_sub2_unit AS nama_unit, 
						m.ta, 
						m.no_ba_usulan AS nomor_ba, 
						m.tgl_ba_usulan AS tanggal,
						m.uuid_skpd AS id_sub, 
						m.no_ba_penunjukan AS nomor_sk, 
						m.thn_ba_penunjukan AS tahun_sk, 
						a.nama_aksi_penghapusan AS tindak,
						m.id_aksi_penghapusan AS id_aksi, 
						m.id_alasan_penghapusan AS id_alasan,
						m.id_pejabat_ketua AS ketua, 
						m.id_pejabat_sekretaris AS sekretaris, 
						m.id_pejabat_anggota1 AS anggota, 
						m.jabatan_ketua AS jab_ket, 
						m.jabatan_sekretaris AS jab_sek, 
						m.jabatan_anggota1 AS jab_ang, 
						m.id_usul_hapus AS id
				FROM usul_hapus m
					LEFT JOIN ref_sub2_unit u ON m.uuid_skpd = u.uuid_sub2_unit
					LEFT JOIN ref_aksi_penghapusan a ON m.id_aksi_penghapusan = a.id_aksi_penghapusan
				WHERE m.soft_delete = 0 $where";
				
				// LEFT JOIN hapus_barang t2 ON m.no_ba_usulan = t2.no_ba_hapus AND m.tgl_ba_usulan = t2.tgl_ba_hapus 
				// WHERE m.soft_delete = 0  AND t2.id_hapus_barang IS NULL $where";
				
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