<?php
	session_start();
	require_once "db.koneksi.php";

	$q = mysql_query("SELECT * FROM masuk_detail_2018 t1 LEFT JOIN masuk_2018 t2 ON t1.id_masuk = t2.id_masuk WHERE t1.uuid_skpd = 'cfa57cd1-5543-11e6-a2df-000476f4fa98' AND t1.soft_delete = '0'");
	while($r = mysql_fetch_assoc($q)){
		
		$brg = mysql_fetch_assoc(mysql_query(" SELECT * FROM ref_barang_2018 WHERE id_barang = '$r[id_barang]' "));
		
		$brg_new = mysql_fetch_assoc(mysql_query(" SELECT * FROM ref_barang WHERE nama_barang = '$brg[nama_barang]' "));
		
		// $masuk1 = mysql_query(" INSERT INTO masuk_detail VALUES (
														// '$r[id_masuk_detail]',
														// '$r[id_masuk]',
														// 'cfa57cd1-5543-11e6-a2df-000476f4fa98',
														// '$r[ta]',
														// '$r[id_kelompok]',
														// '$r[id_rek]',
														// '$r[id_subrek]',
														// '$brg_new[id_barang]',
														// '$r[jml_masuk]',
														// '$r[harga_masuk]',
														// '$r[keterangan]',
														// '$r[tahun]',
														// '$r[create_date]',
														// '$r[update_date]',
														// '$r[soft_delete]',
														// '27-08-2019 233'
														// ) ");
		
		
		// $masuk2 = mysql_query(" INSERT INTO kartu_stok VALUES (
														// UUID(),
														// 'cfa57cd1-5543-11e6-a2df-000476f4fa98',
														// '$brg_new[id_barang]',
														// '$r[id_kelompok]',
														// '28',
														// '907',
														// '$r[id_masuk]',
														// '$r[id_masuk_detail]',
														// '$r[tgl_pembayaran]',
														// '$r[ta]',
														// '$r[jml_masuk]',
														// '0',
														// '$r[harga_masuk]',
														// '',
														// 'i',
														// NOW(),
														// '',
														// '0',
														// '27-08-2019 233'
														// ) ");
		if($masuk1 && $masuk2){
			echo "OK<br>";
		} else {
			echo "GAGAL<br>";
		}
	}
	
?>