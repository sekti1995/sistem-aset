<?php
require_once "../config/db.koneksi.php";


$uploadfile   = 'user_kel.csv';
$jenis   = 'acc55ca1-b20d-11e6-bc95-6cae8b5ed6e0';
				
$handle = fopen($uploadfile, "r"); //Membuka file dan membacanya
//$content = file_get_contents($uploadfile);
//unlink($uploadfile); break;
//fgets($handle); // read the first line and ignore it
$no = 1;
while (($data = fgetcsv($handle, 10000, ";")) !== FALSE) {
	//echo "$data[0] | $data[1] | $data[2] <br>";
	$ins = mysql_query("INSERT INTO ref_pengelola VALUES(UUID(),'$data[1]', 0, 0, '-', '-', '-', '-', '$data[0]', '2016', '$data[2]', MD5('$data[2]'), 4, 0, '', '', NOW(), NULL, 0, '087927b2-c651-11e5-a016-000476f4fa98')") or mysql_error();
	if($ins){
		echo "<b style='color:green'>SUKSES</b><br>";
	} else {
		echo $data[0]." ".$data[1]." ".$data[2]." ".md5($data[2])." <b style='color:red'>GAGAL</b><br>";
	}
}
/* 
$unit = mysql_query("SELECT * FROM  `ref_sub2_unit` WHERE  `kd_urusan` =1 AND  `kd_bidang` =1 AND  `kd_unit` =1 AND  `kd_sub` =11");
while($u = mysql_fetch_assoc($unit)){
	echo "$u[uuid_sub2_unit] | $u[nm_sub2_unit]<br>";
	mysql_query("INSERT INTO ref_pengelola VALUES(UUID(),'$u[nm_sub2_unit]', 0, 0, '-', '-', '-', '$u[uuid_sub2_unit]', '2016', )");
}	 */

mysql_close();
?>