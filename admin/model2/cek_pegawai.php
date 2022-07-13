<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	
	$r = mysql_fetch_assoc(mysql_query("SELECT * FROM pegawai WHERE MD5(id_pegawai) = '$_SESSION[idpengguna]' "));
	$id_sub = $r['id_sub_unit'];
	$id_unit = $r['id_unit'];
	$i = explode('.',$id_unit);
	$id_bid = $i[0].'.'.$i[1];
	$id_ur = $i[0];
	
	echo json_encode(array('id_sub'=>$id_sub, 'id_unit'=>$id_unit, 'id_bid'=>$id_bid, 'id_ur'=>$id_ur));
	mysql_close();
?>	