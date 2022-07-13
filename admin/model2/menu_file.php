<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	
	$cek = isset($_POST['cek']) ? $_POST['cek'] : '';
	
	$r = mysql_fetch_assoc(mysql_query("SELECT * FROM jenis_kegiatan WHERE nama_menu = '$_POST[jenis]' "));
	$nama = $r['nama_jenis_kegiatan'];
	$id = $r['id_jenis_kegiatan'];
	
	if($cek!=""){
		$r = mysql_fetch_assoc(mysql_query("SELECT * FROM pegawai WHERE MD5(id_pegawai) = '$_SESSION[idpengguna]' "));
		$id_sub = $r['id_sub_unit'];
		$id_unit = $r['id_unit'];
		$i = explode('.',$id_unit);
		$id_bid = $i[0].'.'.$i[1];
		$id_ur = $i[0];
		
		echo json_encode(array('id'=>$id, 'nama'=>$nama, 'id_sub'=>$id_sub, 'id_unit'=>$id_unit, 'id_bid'=>$id_bid, 'id_ur'=>$id_ur));
	
	}else echo json_encode(array('id'=>$id, 'nama'=>"$nama"));
	
	mysql_close();
?>	