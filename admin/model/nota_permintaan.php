<?php
session_start();
require_once "../../config/db.koneksi.php";
require_once "../../config/db.function.php";
require_once "../../config/library.php";

$peran = cekLogin();
$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
$idsub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
$ta = isset($_POST['ta']) ? $_POST['ta'] : '';
$nomor = isset($_POST['nomor']) ? $_POST['nomor'] : '';
$tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] != "" ? balikTanggal($_POST['tanggal']) : '' : '';
$penyedia = isset($_POST['penyedia']) ? $_POST['penyedia'] : '';
$kontrak = isset($_POST['kontrak']) ? $_POST['kontrak'] : '';
if ($_SESSION['level'] == md5('a')) $id_sub = " AND MD5(CONCAT_WS('.',u.kd_urusan,u.kd_bidang,u.kd_unit)) = '$_SESSION[peserta]'";
elseif ($_SESSION['level'] == md5('b')) $id_sub = " AND MD5(CONCAT_WS('.',u.kd_urusan,u.kd_bidang,u.kd_unit,u.kd_sub)) = '$_SESSION[peserta]'";
elseif ($_SESSION['level'] == md5('c')) $id_sub = " AND MD5(u.uuid_sub2_unit) = '$_SESSION[uidunit]'";
else $id_sub = "";


if ($idsub != "") $a = " AND o.unit_peminta = '$idsub'";
else $a = "";
if ($ta != "") $b = " AND o.ta = '$ta'";
else $b = "";
if ($nomor != "") $c = " AND no_nota LIKE '%$nomor%' ";
else $c = "";
if ($tanggal != "") $d = " AND DATE_FORMAT(tgl_nota, '%Y-%m-%d')  = '$tanggal' ";
else $d = "";

$where = "$a $b $c $d";
$offset = ($page - 1) * $rows;
$result = array();
$clause = "SELECT u.nm_sub2_unit AS unit_kerja, o.ta, no_nota AS nomor, tgl_nota AS tanggal,
				o.unit_peminta AS id_sub, nama_pengelola AS petugas, id_nota_minta AS id, status,
				IFNULL(u2.nm_sub2_unit, peruntukan) AS txtuntuk, 
				stat_untuk AS vjenis, unit_dituju AS iduntuk
				FROM nota_minta o
				LEFT JOIN ref_sub2_unit u ON o.unit_peminta = u.uuid_sub2_unit
				LEFT JOIN ref_sub2_unit u2 ON o.unit_dituju = u2.uuid_sub2_unit
				LEFT JOIN ref_pengelola p ON o.creator_id = p.id_pengelola
				WHERE o.soft_delete=0 $where $id_sub
				ORDER BY tgl_nota DESC";

$rs = mysql_query($clause);
$r = mysql_num_rows($rs);
$result["total"] = $r;
$rs = mysql_query("$clause LIMIT $rows OFFSET $offset ");
$items = array();
while ($row = mysql_fetch_assoc($rs)) {

	// tambahan //
	$sp = mysql_fetch_assoc(mysql_query("SELECT tgl_sp_out FROM sp_out 
					WHERE uuid_skpd = '$row[id_sub]'
					AND status <> 0 AND soft_delete = 0 ORDER BY tgl_sp_out DESC LIMIT 1"));

	//$row["tgl_max"] = $sp["tgl_sp_out"];
	if ($sp["tgl_sp_out"] == '0000-00-00') {
		$row['tgl_max'] = '00-00-0000';
	} else if ($sp["tgl_sp_out"] == '') {
		$row['tgl_max'] = '00-00-0000';
	} else {
		$row['tgl_max'] = balikTanggalIndo($sp["tgl_sp_out"]);
	}

	if ($row['vjenis'] == 0) {
		$row['ket'] = "Permintaan dari " . $row['txtuntuk'];
		$row['jenis'] = "sendiri";
	} elseif ($row['vjenis'] == 1) {
		$row['ket'] = "Permintaan kepada " . $row['txtuntuk'];
		$row['jenis'] = "skpd";
	}
	$row['tanggal'] = balikTanggalIndo($row['tanggal']);
	array_push($items, $row);
}
$result["rows"] = $items;
echo json_encode($result);
mysql_close();