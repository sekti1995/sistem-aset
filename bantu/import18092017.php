<?php

require_once "../config/db.koneksi.php";
error_reporting(E_ALL); ini_set('display_errors', 'On'); 

$uploadfile   = 'tes.csv';
$jenis   = '5d999690-579c-11e6-a2df-000476f4fa98';
				
$handle = fopen($uploadfile, "r"); //Membuka file dan membacanya
//$content = file_get_contents($uploadfile);
//unlink($uploadfile); break;
//fgets($handle); // read the first line and ignore it
$no = 1;
while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
	
	mysql_query("INSERT INTO 'masuk'('id_masuk', 'uuid_skpd', 'kd_skpd', 'kd_prog', 'id_prog', 'kd_keg', 'kd_rek_1', 'kd_rek_2', 'kd_rek_3', 'kd_rek_4', 'kd_rek_5', 'no_rinc', 'ta', 'nama_pengadaan', 'nama_penyedia', 'tgl_pengadaan', 'no_kontrak', 'tgl_pembayaran', 'no_pembayaran', 'id_sumber', 'tgl_pemeriksaan', 'no_ba_pemeriksaan', 'tgl_penerimaan', 'no_ba_penerimaan', 'no_dok_penerimaan', 'tgl_dok_penerimaan', 'id_gudang', 'status_proses', 'create_date', 'update_date', 'soft_delete', 'creator_id') VALUES (UUID(),'','','','','','','','','','','','2017','','','$data[1]' , '-','$data[2]' ,'$data[3]','',[value-21],[value-22],[value-23],[value-24],[value-25],[value-26],[value-27],[value-28],[value-29],[value-30],[value-31],[value-32])");
	
	/* $q = mysql_query("INSERT INTO ref_barang_copy VALUES (UUID(), '$jenis', '$no', '$data[1]', '$data[2]', 
				'$data[3]', '', NOW(), '', 0, '087927b2-c651-11e5-a016-000476f4fa98')") or die(mysql_error()); */
	/* if($q){
		echo "$data[0] | $data[1] | $data[2] | $data[3] = ";
		echo "Sukses <br>";
	} else {
		echo "$data[0] | $data[1] | $data[2] | $data[3] = ";
		echo "<b style='color:red'>Gagal</b> <br>";
	} */
		echo "$data[8] | $data[9] ";
$no++;
}
mysql_close();
?>
