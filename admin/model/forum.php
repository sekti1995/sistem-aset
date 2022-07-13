<?php
	require_once "../../config/db.koneksi.php";
 
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$search = isset($_POST['search']) ? mysql_real_escape_string($_POST['search']) : '';
	$offset = ($page-1)*$rows;
	$result = array();
	$clause = "SELECT * FROM forum WHERE soft_delete = '0' ";
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$rs = mysql_query("$clause LIMIT $rows OFFSET $offset ");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		if($row['ditujukan']=='0'){
			$row['to'] = 'SEMUA KECAMATAN';
		} else {
			$q1 = mysql_fetch_assoc(mysql_query("SELECT lokasi_nama FROM mst_lokasi WHERE lokasi_kode = '$row[ditujukan]'"));
			$row['to'] = $q1['lokasi_nama'];
		}
		array_push($items, $row);
	}
	$result["rows"] = $items;
	echo json_encode($result);
	mysql_close();
?>