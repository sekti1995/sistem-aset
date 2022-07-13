<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	
	$peran = cekLogin();
	$id = (isset($_GET['id'])&&$_GET['id']!='undefined') ? $_GET['id'] : '';
	$jabatan = isset($_GET['jbt']) ? $_GET['jbt'] : '';
	
	if($id!="") $w = "AND uuid_skpd = '$id'";
	else{
		if($peran!=md5('1')) $w = " AND MD5(uuid_skpd) = '$_SESSION[uidunit]'";
		else $w="";
	}
	if($jabatan=="pengguna") $j = " AND pejabat.id_jabatan IN (8,9)"; 
	elseif($jabatan=="pengurus") $j = " AND pejabat.id_jabatan IN (10,11)";
	
	$clause = "SELECT pejabat.id_jabatan AS id, nama_jabatan AS text, nama_pejabat AS nama, nip FROM pejabat, ref_jabatan 
				WHERE pejabat.id_jabatan = ref_jabatan.id_jabatan $w $j GROUP BY pejabat.id_jabatan ORDER BY pejabat.id_jabatan";
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$rs = mysql_query("$clause");
	$items = array(); $no = 0;
	while($row = mysql_fetch_assoc($rs)){
		if($no==0) $row['selected'] = true;
		array_push($items, $row);
		$no++;
	}
	
	echo json_encode($items);
	mysql_close();
	
?>