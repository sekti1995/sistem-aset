
<?php

date_default_timezone_set('Asia/Jakarta');
error_reporting(E_ALL); ini_set('display_errors', 'on'); 
require_once "../config/db.koneksi.php";
include_once "../config/db.function.php";
include_once "../config/library.php";
session_start();



$uuid_skpd = 'a69714ab-3091-11e7-9301-6cae8b5fc378';
$smt = 1;
$ta = 2020;
$id_sumber_dana = 28;

$rw = mysql_fetch_assoc(mysql_query("	SELECT 
									MIN(k17) as tgl_awal, MAX(k17) AS tgl_akhir
								FROM 
									import_tmp_out
								WHERE 
									uuid_skpd = '$uuid_skpd' AND
									id_sumber_dana = '$id_sumber_dana' AND
									smt = '$smt' AND
									ta = '$ta' AND
									status = '1' "));
		
		$tgl_awal = $rw["tgl_awal"];
		$tgl_akhir = $rw["tgl_akhir"];
				
				
				
				$array_bar2 = "";
				$p0 = mysql_query("SELECT * FROM surat_minta WHERE  soft_delete = '0' AND unit_peminta = '$uuid_skpd' AND (tgl_spb BETWEEN '$tgl_awal' AND '$tgl_akhir') ORDER BY tgl_spb ASC");
				
				// while($r0 = $p0->fetch(PDO::FETCH_ASSOC)){
				while($r0=mysql_fetch_assoc($p0)){
					
					$array_bar = "";
					$array_jml = "";
					$p1 = mysql_query("SELECT t1.* FROM surat_minta_detail t1 LEFT JOIN surat_minta t2 ON t1.id_surat_minta = t2.id_surat_minta WHERE t1.soft_delete = '0' AND t1.id_surat_minta = '$r0[id_surat_minta]' ORDER BY t2.tgl_spb ASC");
					
					// while($r1 = $p1->fetch(PDO::FETCH_ASSOC)){
					while($r1=mysql_fetch_assoc($p1)){
						$jumlah = number_format($r1["jumlah"], 0, ',', '.');
						$jumlah = str_replace(".","",$jumlah);
						$array_bar .= "$r1[id_barang],";
						$array_jml .= "$jumlah,"; 
					}	
					// echo $array_bar;
					$array_jml_sm = $array_jml;
					if($array_bar!=""){
						
						$r2 = mysql_fetch_assoc(mysql_query(" SELECT * FROM sp_out WHERE soft_delete = '0' AND id_surat_minta = '$r0[id_surat_minta]' "));
						
						// $qr2 = $DBcon->prepare(" SELECT * FROM sp_out WHERE soft_delete = '0' AND id_surat_minta = '$r0[id_surat_minta]' ");
						// $qr2->execute();
						// $r2 = $qr2->fetch(PDO::FETCH_ASSOC);
						
						// $qrk = $DBcon->prepare(" SELECT * FROM keluar WHERE soft_delete = '0' AND id_sp_out = '$r2[id_sp_out]' ");
						// $qrk->execute();
						// $rk = $qrk->fetch(PDO::FETCH_ASSOC);
				
						$rk = mysql_fetch_assoc(mysql_query(" SELECT * FROM keluar WHERE soft_delete = '0' AND id_sp_out = '$r2[id_sp_out]' "));
						
						
						
						
						
						echo $r0["id_surat_minta"]." 1<br>";
						echo $r2["id_sp_out"]." 2<br>";
						echo $rk["id_keluar"]." 3<br>";
						/* 
						$delsp = $DBcon->prepare(" DELETE FROM sp_out_detail WHERE id_sp_out = '$r2[id_sp_out]' ");
						$delsp->execute();
				 */
						/* $r3 = mysql_query(" CALL ambil_harga_insert_sp_out_detail('$array_bar', '$array_jml', '$r2[tgl_sp_out]', '$uuid_skpd', '$r2[id_sp_out]', '$r2[id_sp_out]', '$r2[ta]', '$datime', 'asd') ");
				 */
						
					}							
					// $qqqsm = mysql_query("UPDATE surat_minta SET status=1 WHERE id_surat_minta = '$r0[id_surat_minta]'");
					
					///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					
					//KELUAR BARANG
					//KELUAR BARANG
					//KELUAR BARANG
					//KELUAR BARANG
									
						$u = mysql_fetch_row(mysql_query(" SELECT UUID() "));
						// $pdo_queryu = $DBcon->prepare("SELECT UUID()");
						// $pdo_queryu->execute();
						// $u = $pdo_queryu->fetch(PDO::FETCH_NUM);
						$uuid = $u[0];
						
					
						$array_bar = "";
						$array_jml = "";	
						$array_tgl = "";	
						$array_gud = "";	
						$array_sum = "";	
						
						$brg = mysql_query("SELECT * FROM keluar_detail sod LEFT JOIN keluar sp ON sod.id_keluar = sp.id_keluar WHERE sp.soft_delete = '0' AND sod.soft_delete = '0' AND sod.id_keluar = '$rk[id_keluar]' AND sp.uuid_skpd = '$uuid_skpd' AND id_sumber_dana = '$id_sumber_dana' AND (sp.tgl_ba_out BETWEEN '$tgl_awal' AND '$tgl_akhir') ORDER BY sod.tgl_minta ASC");
						//$jml_brg = mysql_num_rows($brg);
						// $brg->execute();
					/* 	echo "<br><br>SELECT * FROM keluar_detail sod LEFT JOIN keluar sp ON sod.id_keluar = sp.id_keluar WHERE sp.soft_delete = '0' AND sod.soft_delete = '0' AND sod.id_keluar = '$rk[id_keluar]' AND sp.uuid_skpd = '$uuid_skpd' AND id_sumber_dana = '$id_sumber_dana' AND (sp.tgl_ba_out BETWEEN '$tgl_awal' AND '$tgl_akhir') ORDER BY sod.tgl_minta ASC"; */
						// while($val = $brg->fetch(PDO::FETCH_ASSOC)){
						while($val=mysql_fetch_assoc($brg)){
							
							print_r($val);
							//$gud = mysql_fetch_assoc(mysql_query("SELECT * FROM keluar"));
							
							$jumlah = number_format($val["jml_barang"], 0, ',', '.'); 
							$jumlah = str_replace(".","",$jumlah);
							$tgl_terima = balikTanggal($val['tgl_terima']);
							$tgl_minta = balikTanggal($val['tgl_minta']);
							if($val["jenis_out"]=='r') $tgl_minta = $tgl_terima;
							$kode = "o".$val["jenis_out"];
							
							$array_bar .= "$val[id_barang],";
							$array_jml .= "$jumlah,";
							$array_tgl .= "$tgl_terima,";
							$array_gud .= "$val[id_gudang],";
							$array_sum .= "$val[id_sumber_dana],";
							echo $array_bar;
						} 
						
						$array_bar2 .= $array_gud.' :: ';
						
						if($array_bar!=""){
							/* $del2 = mysql_query("DELETE FROM keluar_detail WHERE id_keluar = '$rk[id_keluar]' AND id_sumber_dana = '$id_sumber_dana' ");
							$qcalla = mysql_query("CALL ambil_harga_insert_keluar_detail('$array_bar', '$array_jml', '$array_tgl', 
												'$array_gud', '$array_sum', '$uuid_skpd', '$rk[id_keluar]', '$rk[ta]', NOW(), 
												'$pengguna', '$tgl_minta', '$kode')");
							 */
						}
						/* 
						$qcallb = mysql_query("UPDATE sp_out o LEFT JOIN surat_minta m ON o.id_surat_minta = m.id_surat_minta
										SET o.status = 3, m.status = 3 WHERE id_sp_out = '$rk[id_sp_out]'"); */
										
										
				
				
				
				}
?>