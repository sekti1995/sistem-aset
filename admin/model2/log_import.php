<?php
	session_start();
	require_once "../../config/db.koneksi.php";

	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 1000;
	$offset = ($page-1)*$rows;
	$result = array(); 
	if($_SESSION['level'] == MD5('')){
		$filter =  " ";
	} else {
		$filter =  " WHERE a.uuid_skpd = '$_SESSION[uidunit_plain]' ";
	} 
	$clause = "SELECT * FROM log_import a LEFT JOIN ref_sub2_unit b ON a.uuid_skpd = b.uuid_sub2_unit $filter ORDER BY timestamp DESC";
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$rs = mysql_query("$clause LIMIT $rows OFFSET $offset ");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		$timestamp = substr($row['timestamp'],0,10);
		$tot = mysql_fetch_assoc(mysql_query("SELECT SUM(jml_in*harga) AS total_pengadaan FROM kartu_stok WHERE kode = 'i' AND uuid_skpd = '$row[uuid_skpd]' AND LEFT(create_date,10) = '$timestamp' "));
		
		$row['total_pengadaan'] = number_format($tot['total_pengadaan'], 0, ',', '.');
		if($row['result'] == 'Data Persediaan Berhasil di Import !'){
		} 
			array_push($items, $row);
	}
	$result["rows"] = $items;
	echo json_encode($result);
	mysql_close();
?>