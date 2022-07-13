<?php

require_once "../config/db.koneksi.php";
error_reporting(E_ALL); ini_set('display_errors', 'On'); $q = mysql_query("SELECT * FROM ref_sub2_unit WHERE LEFT( nm_sub2_unit, 2 ) =  'SD'");
$no = 1;
while($row = mysql_fetch_assoc($q)){
	
	
	$gud = mysql_query("INSERT INTO ref_gudang VALUES(UUID(), 'Gudang SD', '$row[nm_sub2_unit]', '$row[uuid_sub2_unit]' )");
	if($gud){
			echo $no." ".$row['nm_sub2_unit']." SUKSES<br>";
	} else {
			echo $no." ".$row['nm_sub2_unit']." GAGAL<br>";
	}
	
	
	$no++;
}
mysql_close();
?>
