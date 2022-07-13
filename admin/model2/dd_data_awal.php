<?php
	session_start();
	require_once "../../config/db.koneksi.php";

	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10000;
	
	$id = $_SESSION['uidunit'];
	
	$offset = ($page-1)*$rows;
	$result = array();
	$clause = "SELECT * FROM adjust_detail d, adjust a WHERE d.id_adjust = a.id_adjust AND a.id_adjust = '$id'";
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$rs = mysql_query("$clause LIMIT $rows OFFSET $offset ");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		$row['id']=$row['id_satuan'];
		array_push($items, $row);
	}
	$result["rows"] = $items;
	echo json_encode($result);
	mysql_close();
?>