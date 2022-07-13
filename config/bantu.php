<?php
	session_start();
	require_once "db.koneksi.php";
	// SELECT *
	// FROM `kartu_stok`
	// WHERE `uuid_skpd` = '91283937-af57-11e9-b5e2-0e97cb36aab5'
	// LIMIT 0 , 30
	
	$unit = mysql_query("SELECT *  FROM ref_sub2_unit WHERE kd_unit = 2 AND kd_sub <> 1 AND kd_sub2 <> 1 ORDER BY kd_sub, kd_sub2 ASC");
	while($r = mysql_fetch_assoc($unit)){
		if(substr($r["nm_sub2_unit"],0,11) == "Gudang Umum"){
			echo "<br>".$r["uuid_sub2_unit"]." ".$r["nm_sub2_unit"];
			
			// $q1 = mysql_query("UPDATE kartu_stok SET soft_delete = 2 WHERE uuid_skpd = '$r[uuid_sub2_unit]' AND kode = 'os'");
			// if($q1){
				// echo " SUKSES";
			// } else {
				// echo " GAGAL";
			// }
			
			/* 
			$q1 = mysql_query("DELETE FROM kartu_stok WHERE uuid_skpd = '$r[uuid_sub2_unit]' ");
			$q2 = mysql_query("DELETE FROM terima_keluar WHERE uuid_skpd = '$r[uuid_sub2_unit]' ");
			$q3 = mysql_query("DELETE FROM terima_keluar_detail WHERE uuid_skpd = '$r[uuid_sub2_unit]' ");
			if($q1 && $q2 && $q3){
				echo " SUKSES";
			} else {
				echo " GAGAL";
			}
			 */
		}
	}
/* 	
	$unit = mysql_query("SELECT *  FROM ref_sub2_unit WHERE kd_unit = 2 AND kd_sub2 = 1 ORDER BY kd_sub, kd_sub2 ASC");
	while($r = mysql_fetch_assoc($unit)){
		if(substr($r["nm_sub2_unit"],0,9) == "Puskesmas"){
			echo "<br>".$r["uuid_sub2_unit"]." ".$r["nm_sub2_unit"];
			$q1 = mysql_query("UPDATE kartu_stok SET soft_delete = 2 WHERE uuid_skpd = '$r[uuid_sub2_unit]' AND kode = 'os'");
			if($q1){
				echo " SUKSES";
			} else {
				echo " GAGAL";
			}
		}
	} */
?>