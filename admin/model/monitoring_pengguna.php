<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";
	
	$peran = cekLogin();
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$pengguna = isset($_POST['pengguna']) ? $_POST['pengguna'] : '';
	
	if($pengguna!="") $a = "AND id_pengelola = '$pengguna'";
	else $a = "";
	
	$where = "$a";
	$offset = ($page-1)*$rows;
	$result = array();
	$clause = "SELECT nama_pengelola AS pengguna, nm_sub2_unit AS unit, 
				IF(online>DATE_SUB(NOW(), INTERVAL 1441 SECOND), 'Online', 'Offline') AS status
				FROM ref_pengelola p
				LEFT JOIN ref_sub2_unit u 
				ON p.uuid_skpd = u.uuid_sub2_unit
				WHERE soft_delete = 0 $where ORDER BY status DESC";
				
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$items = array(); 
	while($row = mysql_fetch_assoc($rs)){
		if($row['status']=='Online') $row['status'] = "<label style='color:blue;'><b>".$row['status']."</b></label>";
		else $row['status'] = "<label style='color:red;'><b>".$row['status']."</b></label>";
		array_push($items, $row);
	}
	$result["rows"] = $items;
	echo json_encode($result);
	mysql_close();
?>