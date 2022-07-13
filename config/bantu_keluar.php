<?php
	session_start();
	require_once "db.koneksi.php";
	
	
	
	$q = mysql_query("SELECT * FROM hapus_puskesmas");
	while($r=mysql_fetch_assoc($q)){
		
		// $d1 = mysql_query("DELETE FROM kartu_stok WHERE uuid_skpd = '$r[uuid_sub2_unit]' AND kode = 'i' ");
		// $d2 = mysql_query("DELETE FROM masuk WHERE uuid_skpd = '$r[uuid_sub2_unit]' ");
		// $d3 = mysql_query("DELETE FROM keluar WHERE uuid_skpd = '$r[uuid_sub2_unit]' ");
		// $d4 = mysql_query("DELETE FROM nota_minta WHERE unit_peminta = '$r[uuid_sub2_unit]' ");
		// $d5 = mysql_query("DELETE FROM surat_minta WHERE unit_peminta = '$r[uuid_sub2_unit]' ");
		// $d6 = mysql_query("DELETE FROM sp_out WHERE uuid_skpd = '$r[uuid_sub2_unit]' ");
		
		if($d1 && $d2 && $d3 && $d4 && $d5 && $d6){
			echo "SUKSES ".$r["uuid_sub2_unit"]." ".$r["nm_sub2_unit"]."<br>";
		} else {
			echo "GAGAL ".$r["uuid_sub2_unit"]." ".$r["nm_sub2_unit"]."<br>";
		}
	}
	
?>