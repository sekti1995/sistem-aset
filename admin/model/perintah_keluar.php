<?php
session_start();
require_once "../../config/db.koneksi.php";
require_once "../../config/db.function.php";
require_once "../../config/library.php";

$peran = cekLogin();

$tglnow = date("Y-m-d");

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
$idunit = isset($_POST['id_unit']) ? $_POST['id_unit'] : '';
$idsub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
$ta = isset($_POST['ta']) ? $_POST['ta'] : '';
$nomor = isset($_POST['nomor']) ? $_POST['nomor'] : '';
$tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] != "" ? balikTanggal($_POST['tanggal']) : '' : '';
$penyedia = isset($_POST['penyedia']) ? $_POST['penyedia'] : '';
$kontrak = isset($_POST['kontrak']) ? $_POST['kontrak'] : '';
if ($peran != md5('1')) $id_sub = " AND MD5(o.uuid_skpd) = '$_SESSION[uidunit]'";
else $id_sub = "";
if ($_SESSION['uidunit_plain'] == 'cfa4f56a-5543-11e6-a2df-000476f4fa98') {
	$id_sub = "";
}

$th = date("Y");

if ($idunit != "") $a = " AND o.uuid_skpd = '$idunit'";
else $a = "";
if ($ta != "") $b = " AND o.ta = '$ta'";
else $b = " AND o.ta = '$th'  ";
if ($nomor != "") $c = " AND no_sp_out LIKE '%$nomor%' ";
else $c = "";
if ($tanggal != "") $d = " AND DATE_FORMAT(tgl_sp_out, '%Y-%m-%d')  = '$tanggal' ";
else $d = "";
if ($idsub != "") $e = " AND o.uuid_untuk = '$idsub'";
else $e = "";

$where = "$a $b $c $d ";
$offset = ($page - 1) * $rows;
$result = array();

$clause = "SELECT u.nm_sub2_unit AS skpd, o.ta, no_sp_out AS no_surat, 
					IF(tgl_sp_out='000-00-00', '', DATE_FORMAT(tgl_sp_out, '%d-%m-%Y')) AS tgl_surat, 
					no_spb AS no_sp, DATE_FORMAT(tgl_spb, '%d-%m-%Y') AS tgl_sp, o.status, 
					 o.peruntukan AS subunit, o.stat_untuk AS vjenis,
					uuid_skpd AS id_sub, o.id_surat_minta AS idsur, o.id_sp_out AS idsp
				FROM sp_out o
				LEFT JOIN surat_minta s ON s.id_surat_minta = o.id_surat_minta
				LEFT JOIN ref_sub2_unit u ON o.uuid_skpd = u.uuid_sub2_unit
				WHERE id_sp_out IS NOT NULL $where $id_sub AND o.soft_delete = 0";

$rs = mysql_query($clause);
$r = mysql_num_rows($rs);
$result["total"] = $r;
$rs = mysql_query("$clause LIMIT $rows OFFSET $offset ");
$items = array();
while ($row = mysql_fetch_assoc($rs)) {
	$sp = mysql_fetch_assoc(mysql_query("SELECT tgl_sp_out FROM sp_out 
					WHERE uuid_skpd = '$row[id_sub]'
					AND status <> 0 AND id_sp_out != '$row[idsp]' AND soft_delete = 0 ORDER BY tgl_sp_out DESC LIMIT 1"));
	if ($sp["tgl_sp_out"] == '0000-00-00') {
		$row['tgl_sp_akhir'] = '00-00-0000';
	} else if ($sp["tgl_sp_out"] == '') {
		$row['tgl_sp_akhir'] = '00-00-0000';
	} else {
		$row['tgl_sp_akhir'] = balikTanggalIndo($sp["tgl_sp_out"]);
	}

	array_push($items, $row);
}
$result["rows"] = $items;
echo json_encode($result);
mysql_close();
