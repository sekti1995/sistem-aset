<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	
	$peran = cekLogin();
	$id = (isset($_GET['id'])&&$_GET['id']!='undefined') ? $_GET['id'] : '';
	$jabatan = (isset($_GET['jbt'])&&$_GET['jbt']!='undefined') ? $_GET['jbt'] : '';
	
	if($id!="") $w = "AND uuid_skpd = '$id'";
	else{
		if($peran!=md5('1')) $w = " AND MD5(uuid_skpd) = '$_SESSION[uidunit]'";
		else $w="";
	}
	if($jabatan!="") $j = " AND id_jabatan = '$jabatan'"; else $j = "";
	
	$clause = "SELECT * FROM pejabat WHERE id_pejabat IS NOT NULL $w $j";
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$rs = mysql_query("$clause");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		$row['id'] = $row['id_pejabat'];
		$row['text'] = $row['nama_pejabat'];
		array_push($items, $row);
	}
	
	echo json_encode($items);
	mysql_close();
	
?>