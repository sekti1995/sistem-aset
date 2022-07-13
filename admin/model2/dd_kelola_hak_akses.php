<?php
	require_once "../../config/db.koneksi.php";

	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	
	$offset = ($page-1)*$rows;
	$result = array();
	$clause = "SELECT id_akses AS id, nama_akses, id_role FROM ref_akses ORDER BY id_akses";
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$rs = mysql_query("$clause LIMIT $rows OFFSET $offset ");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		$menu = mysql_query("SELECT a.uuid_menu AS id, m.id_menu FROM ref_akses_menu a, ref_menu m 
							WHERE a.id_akses = '$row[id]' 
							AND a.uuid_menu = m.uuid_menu
							AND CONCAT_WS(  '.', id_sub, id_sub2 ) <> '0.0' AND id_sub2 <> 0");
		$akses = array();
		while($m = mysql_fetch_assoc($menu)){
			if(!isset($akses[$m['id_menu']])) $akses[$m['id_menu']] = array($m['id']);
			else array_push($akses[$m['id_menu']], $m['id']);
		}
		$row['akses'] = $akses;
		array_push($items, $row);
	}
	$result["rows"] = $items;
	echo json_encode($result);
	mysql_close();
?>