<?php
session_start();
//if(!isset($_SESSION['namauser'])){
 //   die("Anda belum login, Bila login klik di <a href=../index.php>sini</a>");//jika belum login jangan lanjut..
//}
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	$n=1;
	$tot=0;
	$q = mysql_query("SELECT * FROM kartu_stok WHERE uuid_skpd = 'aa2bc917-ac4d-11ea-8b6f-aeec9527008b' AND kode = 'i' AND soft_delete = 0");
	while($r = mysql_fetch_assoc($q)){
		$j = mysql_fetch_assoc(mysql_query("SELECT id_masuk_detail, (jml_masuk*harga_masuk) AS jum FROM masuk_detail WHERE uuid_skpd = '$r[uuid_skpd]' AND id_masuk_detail = '$r[id_transaksi_detail]' "));
		$tot += $j["jum"];
		echo $n." ".$r["id_transaksi_detail"] . " | " . $j["id_masuk_detail"] . " | " . $j["jum"]."<br>"; $n++;
	}
	
	echo $tot;
	mysql_close();
?>