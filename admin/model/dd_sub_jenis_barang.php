<?php

	require_once "../../config/db.koneksi.php";

	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$offset = ($page-1)*$rows;
	$result = array();
	$clause = "SELECT * FROM ref_sub_jenis s
						LEFT JOIN ref_jenis j ON s.id_jenis = j.id_jenis ORDER BY s.id_jenis, id_sub_jenis";
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$rs = mysql_query("$clause LIMIT $rows OFFSET $offset ");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		$row['id']=$row['id_sub_jenis'];
		array_push($items, $row);
	}
	$result["rows"] = $items;
	echo json_encode($result);
	mysql_close();
?>