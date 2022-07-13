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
	//if($jabatan!="") 
	$j = " AND id_jabatan = '$jabatan'"; 
	//elseif($jabatan=="pengurus") $j = " AND pejabat.id_jabatan IN (10,11)";
	
	$clause = "SELECT id_jabatan AS id, nama_pejabat AS text, nip FROM pejabat 
				WHERE id_jabatan IS NOT NULL $w $j";
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