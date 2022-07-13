<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";
	
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$idsub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
	$ta = isset($_POST['ta']) ? $_POST['ta'] : '';
	$nomor = isset($_POST['nomor']) ? $_POST['nomor'] : '';
	$tanggal = isset($_POST['tanggal']) ? $_POST['tanggal']!="" ? balikTanggal($_POST['tanggal']) : '' : '';
	$untuk = isset($_POST['untuk']) ? $_POST['untuk'] : '';
	
	if($_SESSION['level']==md5('a')) $id_sub = " AND MD5(CONCAT_WS('.',r.kd_urusan,r.kd_bidang,r.kd_unit)) = '$_SESSION[peserta]'";
	elseif($_SESSION['level']==md5('b')) $id_sub = " AND MD5(CONCAT_WS('.',r.kd_urusan,r.kd_bidang,r.kd_unit,r.kd_sub)) = '$_SESSION[peserta]'";
	elseif($_SESSION['level']==md5('c')) $id_sub = " AND MD5(u.uuid_sub2_unit) = '$_SESSION[uidunit]'";
	else $id_sub = "";
	
	
	if($idsub!="") $a = " AND k.uuid_unit = '$idsub'";
	else $a = " AND MD5(k.uuid_unit) = '$_SESSION[uidunit]'";
	if($ta!="") $b = " AND k.ta = '$ta'";
	else $b = "";
	if($nomor!="") $c = " AND no_ba_out LIKE '%$nomor%' ";
	else $c = "";
	if($tanggal!="") $d = " AND DATE_FORMAT(tgl_ba_out, '%Y-%m-%d')  = '$tanggal' ";
	else $d = "";
	if($untuk!="") $e = " AND k.uuid_subunit = '$untuk'";
	else $e = "";
	
	$where = "$a $b $c $d $e";
	$offset = ($page-1)*$rows;
	$result = array();
	$clause = "SELECT u.nm_sub2_unit AS nm_skpd, k.ta, no_ba_out AS nomor, DATE_FORMAT(tgl_ba_out, '%d-%m-%Y') AS tanggal, 
				k.uuid_skpd AS id_sub, k.id_keluar AS id, k.uuid_untuk AS id_subt, r.nm_sub2_unit AS nm_sub, id_terima_keluar AS idt, status as stat,
				status, DATE_FORMAT(tgl_terima, '%d-%m-%Y') AS tgl_terima, nama_pengelola AS penerima, t.id_gudang
				FROM keluar k
				LEFT JOIN terima_keluar t ON k.id_keluar = t.id_keluar
				LEFT JOIN ref_sub2_unit u ON k.uuid_skpd = u.uuid_sub2_unit
				LEFT JOIN ref_sub2_unit r ON k.uuid_untuk = r.uuid_sub2_unit
				LEFT JOIN ref_pengelola p ON p.id_pengelola = t.creator_id
				WHERE k.soft_delete=0 AND k.jenis_out = 's' $id_sub
				ORDER BY status, tgl_ba_out DESC";
				
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$rs = mysql_query("$clause LIMIT $rows OFFSET $offset ");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		if($row['status']==0) $row['status'] = "<span style='color:red; font-weight:bold;'>Belum diterima</span>";
		elseif($row['status']==2) $row['status'] = "<span style='color:green; font-weight:bold;'>Selesai</span>";
		array_push($items, $row);
	}
	$result["rows"] = $items;
	echo json_encode($result);
	mysql_close();
?>