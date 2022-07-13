<?php
session_start();

require_once "../../config/db.koneksi.php";
require_once "../../config/db.function.php";

$peran = cekLogin();
$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
$cari = isset($_POST['cari']) ? mysql_real_escape_string($_POST['cari']) : '';
if ($peran != md5('1')) $id_sub = " WHERE MD5(g.uuid_skpd) = '$_SESSION[uidunit]' ";
else $id_sub = "";
$where = " where nama_gudang like '%$cari%' or lokasi like '%$cari%'";
$offset = ($page - 1) * $rows;
$result = array();
$clause = "SELECT * , uuid_skpd AS id_sub2_unit, uuid_skpd AS uid
						FROM ref_gudang g 
						LEFT JOIN ref_sub2_unit s
						ON g.uuid_skpd = s.uuid_sub2_unit $id_sub
						" . $where . "
						ORDER BY s.kd_urusan, s.kd_bidang, s.kd_unit, s.kd_sub, s.kd_sub2";
$rs = mysql_query($clause);
$r = mysql_num_rows($rs);
$result["total"] = $r;
$rs = mysql_query("$clause LIMIT $rows OFFSET $offset ");
$items = array();
while ($row = mysql_fetch_assoc($rs)) {
	$row['id'] = $row['id_gudang'];
	array_push($items, $row);
}
$result["rows"] = $items;
echo json_encode($result);
mysql_close();