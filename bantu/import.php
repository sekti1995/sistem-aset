<?php

require_once "../config/db.koneksi.php";
error_reporting(E_ALL); ini_set('display_errors', 'On'); 

$uploadfile   = 'makanan.csv';
$jenis   = '5d99a66c-579c-11e6-a2df-000476f4fa98';
				
$handle = fopen($uploadfile, "r"); //Membuka file dan membacanya
//$content = file_get_contents($uploadfile);
//unlink($uploadfile); break;
//fgets($handle); // read the first line and ignore it
$no = 146;
while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
	
	$q = mysql_query("INSERT INTO ref_barang VALUES (UUID(), '$jenis', '$no', '$data[0]', '$data[1]', 
				     '$data[2]', '', '0', NOW(), '', 0, '087927b2-c651-11e5-a016-000476f4fa98')") or die(mysql_error());
	if($q){
		echo "$data[0] | $data[1] | $data[2] = ";
		echo "Sukses <br>";
	} else {
		echo "$data[0] | $data[1] | $data[2] = ";
		echo "<b style='color:red'>Gagal</b> <br>";
	}
$no++;
}
mysql_close();
?>
