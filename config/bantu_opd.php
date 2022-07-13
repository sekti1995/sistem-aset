<?php
	session_start();
	require_once "db.koneksi.php";

	$q = mysql_query("SELECT * FROM msk WHERE p = '0' GROUP BY j ");
	while($r = mysql_fetch_assoc($q)){
		
		$j = mysql_fetch_assoc(mysql_query(" SELECT * FROM ref_sub2_unit WHERE nm_sub2_unit = '$r[j]' "));
		echo $r["j"]." || ".$j["uuid_sub2_unit"]." == <br>";
		
		
		// mysql_query("UPDATE msk SET p =  '$j[uuid_sub2_unit]' WHERE  j =  '$r[j]'");
		
	}
	
?>