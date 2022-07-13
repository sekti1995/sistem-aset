<?php
session_start();
require_once "../../config/db.koneksi.php";
require_once "../../config/db.function.php";
require_once "../../config/library.php";

$peran = cekLogin(); 
$id_sub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
$id_sum = isset($_POST['id_sum']) ? $_POST['id_sum'] : '';
$id_keg = isset($_POST['id_keg']) ? $_POST['id_keg'] : '';
$ta = isset($_POST['ta']) ? $_POST['ta'] : date('Y');

if($_SESSION['level']==md5('c')){
	$b1 = "  MD5(a.uuid_skpd) = '$_SESSION[uidunit]'";
}else{
	$b1 = " a.uuid_skpd = '$id_sub'";

}

if($id_keg!=""){
	$c1 = "AND a.kd_kegiatan = '$id_keg'";
}else $c1 = "";

if($id_sum!=""){
	$d1 = "AND a.id_sumber_dana = '$id_sum'";
}else $d1 =  "";
	
		$clause = "SELECT id_barang,nm_barang,c.nama_satuan,id_subrek,CONCAT_WS('.', d.kd_kel, d.kd_sub) AS kode_bar,d.nama_jenis,jumlah_barang,jumlah_barang_isi,jumlah_barang-jumlah_barang_isi AS sisa,harga,jumlah_barang_isi*harga AS harga_pengadaan FROM log_import a 
		LEFT JOIN ref_satuan c ON a.id_satuan=c.id_satuan 
		LEFT JOIN ref_jenis d ON a.id_subrek = d.id_jenis 
				WHERE $b1  $c1  $d1 and a.ta='$ta' 
				GROUP BY id_subrek,kode_bar,id_barang
				ORDER BY id_subrek,kode_bar,id_barang";

	//print_r($clause);
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	//$rs = mysql_query("$clause LIMIT $rows OFFSET $offset ");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		$row['nm_barang'] = $row['nm_barang'];		
		$row['nama_satuan'] = $row['nama_satuan'];		
		$row['jumlah_barang'] = number_format($row['jumlah_barang'], 0, ',', '.');
		$row['jumlah_barang_isi'] = number_format($row['jumlah_barang_isi'], 0, ',', '.');
		$row['harga'] = number_format($row['harga'], 0, ',', '.');
		$row['sisa'] = number_format($row['sisa'], 0, ',', '.');
		$row['harga_pengadaan'] = number_format($row['harga_pengadaan'], 0, ',', '.');
		array_push($items, $row);
	}
	$result["rows"] = $items;
	echo json_encode($items);
	mysql_close();

	
?>