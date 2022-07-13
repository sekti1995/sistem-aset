<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	
	$peran = cekLogin();
	$id = (isset($_GET['id'])&&$_GET['id']!='undefined') ? $_GET['id'] : '';
	$idn = (isset($_GET['idn'])&&$_GET['idn']!='undefined') ? $_GET['idn'] : '';
	//$jabatan = (isset($_GET['jbt'])&&$_GET['jbt']!='undefined') ? $_GET['jbt'] : '';
	
	if($id!="") $w = "AND unit_peminta = '$id'";
	else $w = " AND MD5(unit_peminta) = '$_SESSION[uidunit]'";
	
	if($idn!="") $g = "AND ( status = 0 OR id_nota_minta = '$idn')";
	else $g = " AND status = 0";
	
	$clause = "SELECT id_nota_minta AS id, no_nota AS text, stat_untuk AS vjenis, IFNULL(u.nm_sub2_unit, peruntukan) AS txtuntuk, 
				unit_dituju AS iduntuk
				FROM nota_minta 
				LEFT JOIN ref_sub2_unit u ON unit_dituju = u.uuid_sub2_unit
				WHERE soft_delete = 0 $g $w";
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$rs = mysql_query("$clause");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		array_push($items, $row);
	}
	
	echo json_encode($items);
	mysql_close();
	
?>