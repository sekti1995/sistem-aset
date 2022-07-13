<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";
	$items = array();
	$uuid_skpd = isset($_POST['uuid_skpd']) ? $_POST['uuid_skpd'] : $_SESSION["uidunit_plain"];
	
	$sp = mysql_fetch_assoc(mysql_query("SELECT tgl_sp_out FROM sp_out 
					WHERE uuid_skpd = '$uuid_skpd'
					AND status <> 0 AND soft_delete = 0 ORDER BY tgl_sp_out DESC LIMIT 1"));
		
		//$row["tgl_max"] = $sp["tgl_sp_out"];
		if($sp["tgl_sp_out"] == '0000-00-00'){
			$row['tgl_max'] = '00-00-0000';
		} else if($sp["tgl_sp_out"] == ''){
			$row['tgl_max'] = '00-00-0000';
		} else {
			$row['tgl_max'] = balikTanggalIndo($sp["tgl_sp_out"]);
		}
		
		array_push($items, $row);
	
	echo json_encode($items);
	mysql_close();
	
?>