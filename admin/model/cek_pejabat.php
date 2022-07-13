<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";
	
	$id_sub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
	
	$kepala = $pengurus = $nipk = $nipp = "";
	
	
	if($_SESSION['peran_id']== md5("1")){
		$row["pengguna"] = "xxx";
		$row["pengurus"] = "xxx";
		$row["bendahara"] = "xxx";
	} else {
		$skpd1 = mysql_fetch_assoc(mysql_query("SELECT * FROM pejabat WHERE uuid_skpd = '$id_sub' AND id_jabatan = '1' "));
		$skpd2 = mysql_fetch_assoc(mysql_query("SELECT * FROM pejabat WHERE uuid_skpd = '$id_sub' AND id_jabatan = '2' "));
		$skpd3 = mysql_fetch_assoc(mysql_query("SELECT * FROM pejabat WHERE uuid_skpd = '$id_sub' AND id_jabatan = '3' "));

		$row["pengguna"] = $skpd1["nama_pejabat"];
		$row["pengurus"] = $skpd2["nama_pejabat"];
		$row["bendahara"] = $skpd3["nama_pejabat"];
	}

	$items = array();
	array_push($items, $row);
	echo json_encode($items);
	
?>