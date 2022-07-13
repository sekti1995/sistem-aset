<?php
	session_start();
	require_once "db.koneksi.php";

	
	$q = mysql_query("SELECT * FROM kartu_stok t1 LEFT JOIN ref_sub2_unit t2 ON t1.uuid_skpd = t2.uuid_sub2_unit WHERE t2.kd_unit = 1 AND t2.kd_sub2 > 1 AND t2.kd_sub <> 21 GROUP BY t1.uuid_skpd");
	while($r = mysql_fetch_assoc($q)){
		
		// $ks = mysql_query(" UPDATE kartu_stok SET soft_delete = 2, update_date = NOW() WHERE uuid_skpd = '$r[uuid_skpd]' AND id_sumber_dana = '28' ");
		// $ad = mysql_query(" UPDATE adjust_detail SET soft_delete = 2, update_date = NOW() WHERE uuid_skpd = '$r[uuid_skpd]' AND id_adjust_detail = '$ks[id_transaksi_detail]' ");
		// $jns = mysql_fetch_assoc(mysql_query(" SELECT * FROM ref_jenis WHERE REPLACE(LOWER('$r[h]'),' ','') = REPLACE(LOWER(nama_jenis),' ','') "));
		// $opd = mysql_fetch_assoc(mysql_query(" SELECT * FROM ref_sub2_unit WHERE REPLACE(LOWER('$r[a]'),' ','') = REPLACE(LOWER(nm_sub2_unit),' ','') "));
		// $gud = mysql_fetch_assoc(mysql_query(" SELECT * FROM ref_gudang WHERE uuid_skpd = '$opd[uuid_sub2_unit]' "));
		
		echo $r["nm_sub2_unit"]."<br>";
	
	}
	
?>



