<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";
	
	$peran = cekLogin();
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$pengguna = isset($_POST['pengguna']) ? $_POST['pengguna'] : '';
	$m = isset($_POST['mulai']) ? $_POST['mulai'] : date('d-m-Y');
	$s = isset($_POST['sampai']) ? $_POST['sampai'] : date('d-m-Y');
	
	if($m!="") $mu = balikTanggal($m); else $mu = "";
	if($s!="") $se = balikTanggal($s); else $se = "";
	
	if($pengguna!="") $a = "AND id_pengelola = '$pengguna'";
	else $a = "";
	if($mu!="" && $se!=""){
		$c = "AND DATE_FORMAT(date_log, '%Y-%m-%d') BETWEEN '$mu' AND '$se'";
	}else{
		$c = "";
	}
	
	$where = "$a $c";
	$offset = ($page-1)*$rows;
	$result = array();
	$clause = "SELECT DATE_FORMAT(date_log, '%d-%m-%Y %h:%i:%s') AS waktu, CONCAT_WS(' ',nama_pengelola,'@',ip_aktor) AS pengguna, 
				if(nama_menu<>'',nama_menu, modul) AS modul, nm_sub2_unit AS unit, aksi 
				FROM log 
				LEFT JOIN ref_pengelola ON aktor = id_pengelola
				LEFT JOIN ref_menu ON link_menu = modul
				LEFT JOIN ref_sub2_unit ON uuid_skpd = uuid_sub2_unit
				WHERE date_log IS NOT NULL $where
				ORDER BY date_log DESC";
				
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$items = array(); 
	while($row = mysql_fetch_assoc($rs)){
		if($row['aksi']=='add') $row['aksi'] = "Tambah";
		elseif($row['aksi']=='edit') $row['aksi'] = "Ubah";
		elseif($row['aksi']=='del') $row['aksi'] = "Hapus";
		elseif($row['aksi']=='up') $row['aksi'] = "Upload";
		elseif($row['aksi']=='save') $row['aksi'] = "Simpan";
		elseif($row['aksi']=='tolak') $row['aksi'] = "Tolak";
		else $row['aksi'] = "";
		array_push($items, $row);
	}
	$result["rows"] = $items;
	echo json_encode($result);
	mysql_close();
?>