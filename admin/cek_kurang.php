<?php

date_default_timezone_set('Asia/Jakarta');
error_reporting(E_ALL); ini_set('display_errors', 'on'); 
require_once "../config/db.koneksi.php";
include_once "../config/db.function.php";
include_once "../config/library.php";
session_start();

$q = mysql_query("SELECT * FROM import_tmp_out WHERE uuid_skpd = 'dfecd96b-3adb-11e7-b26e-000476f4fa98' AND smt = '1' AND ta = '2018' AND id_sumber_dana = '28' ORDER BY k17, k14 ASC");
						while($r = mysql_fetch_assoc($q)){
							$cek_in = mysql_fetch_assoc(mysql_query("SELECT SUM(format(k7,2,'de_DE')) AS jml_in FROM import_tmp_in WHERE k8 = '$r[k20]' AND k4 <= '$r[k17]' AND uuid_skpd = 'dfecd96b-3adb-11e7-b26e-000476f4fa98' AND smt = '1' AND ta = '2018' AND id_sumber_dana = '28' "));
							$cek_out = mysql_fetch_assoc(mysql_query("SELECT SUM(format(k19,2,'de_DE')) AS jml_out FROM import_tmp_out WHERE k20 = '$r[k20]' AND k17 <= '$r[k17]' AND k14 <= '$r[k14]' AND  uuid_skpd = 'dfecd96b-3adb-11e7-b26e-000476f4fa98' AND smt = '1' AND ta = '2018' AND id_sumber_dana = '28' "));
							
							if($cek_in['jml_in'] < $cek_out['jml_out']){
								$kurang = $cek_out['jml_out']-$cek_in['jml_in'];
								if($kurang > $r['k19']){
									$kurang = $r['k19'];
								}
							} else {
								$kurang = 0;
							}
							//$upd = mysql_query("UPDATE import_tmp_out SET kurang = '$kurang' WHERE id = '$r[id]'");
							if($kurang>0){
							echo $cek_in['jml_in'] ." - ". $cek_out['jml_out']. " " .$r['k20']." ".$r['k21']." ".$kurang."<br>";
							}
						}

mysql_close();
?>
