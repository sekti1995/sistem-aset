<?php

date_default_timezone_set('Asia/Jakarta');
error_reporting(E_ALL); ini_set('display_errors', 'on'); 
include_once "../config/db.koneksi.php";
include_once "../config/db.function.php";
include_once "../config/library.php";
session_start();

$no = 1;
$data2 = "xxx";
$data4 = "xxx";
$nomor = 1;
$nomor2 = 1;
$ta = '2017';
if(isset($_REQUEST['id_sub']))$uuid_skpd = $_REQUEST['id_sub'];
if(isset($_REQUEST['id_sumber_dana']))$id_sumber_dana = $_REQUEST['id_sumber_dana'];

$skpd = mysql_fetch_assoc(mysql_query("SELECT uuid_sub2_unit AS id, nm_sub2_unit AS text, 
			CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit) AS kode,
			CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit, kd_sub, kd_sub2) AS sub
			FROM ref_sub2_unit WHERE uuid_sub2_unit = '$uuid_skpd'"));
$kode = explode('.', $skpd['sub']); 
$kd1 = str_pad($kode[1],2,'0', STR_PAD_LEFT);
$kd2 = str_pad($kode[2],2,'0', STR_PAD_LEFT);
$kd3 = str_pad($kode[3],2,'0', STR_PAD_LEFT);
//$kd4 = str_pad($kode[4],2,'0', STR_PAD_LEFT);
$kd_skpd = $kode[0].".".$kd1.".".$kd2.".".$kd3;//.".".$kd4; 
	////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	////////////////////////////////////////////////////////////////////////////////////////////////////////


$gud = mysql_fetch_assoc(mysql_query("SELECT * FROM ref_gudang WHERE uuid_skpd = '$uuid_skpd' LIMIT 1"));

$jenis   = '5d999690-579c-11e6-a2df-000476f4fa98';
$id_kelompok   = '1';
if($gud['id_gudang'] == ""){
	$ux = mysql_fetch_row(mysql_query("SELECT UUID()"));
	$uuidx = $ux[0];
	$insert_gud = mysql_query("INSERT INTO ref_gudang VALUES('$uuidx','Gudang Umum', '', '$uuid_skpd')");
	$id_gudang = $uuidx;
} else {
	$id_gudang = $gud['id_gudang'];
}

			//upload file data awal persediaan
			if ( ! empty($_FILES['file_awal']['name'])) {
				$uploadfile   = './berkas/'.basename($_FILES['file_awal']['name']);
				$name   = $_FILES['file_awal']['name'];
				//filter
				
				$csv_mimetypes = array('text/csv',
										'text/plain',
										'application/csv',
										'text/comma-separated-values',
										'application/excel',
										'application/vnd.ms-excel',
										'application/vnd-ms-excel',
										'application/vnd.msexcel',
										'text/anytext',
										'application/octet-stream',
										'application/txt',
									);
				$type = pathinfo($_FILES['file_awal']['name'] ,PATHINFO_EXTENSION);					
				if (in_array($_FILES['file_awal']['type'], $csv_mimetypes) && ($type == 'csv')) {
					//cek if nama sudah ada
					$actual_name = pathinfo($name, PATHINFO_FILENAME);
					$original_name = $actual_name;
					$extension = pathinfo($name, PATHINFO_EXTENSION);

					$i = 1;
					while(file_exists('./berkas/'.$actual_name.".".$extension))
					{           
						$actual_name = (string)$original_name.$i;
						$name = $actual_name.".".$extension;
						$i++;
					}
					
					$uploadfile = './berkas/'.$name;
					if (file_exists($uploadfile)) {					
						echo json_encode(array('success'=>false, 'pesan'=>"Nama File sudah ada, ganti nama lain !"));
						false;
					}else{					
						move_uploaded_file($_FILES['file_awal']['tmp_name'], $uploadfile);
						$result = array(); $items = array(); $r = 0; $ttotal = 0; $invalid = "";
						$handle = fopen($uploadfile, "r"); //Membuka file dan membacanya
						//$content = file_get_contents($uploadfile);
						$delimiter = detectDelimiter($uploadfile);
						//unlink($uploadfile); break;
						fgets($handle); // read the first line and ignore it
						while (($data = fgetcsv($handle, 500000, $delimiter)) !== FALSE) {
							/* $data[1] = substr($data[1],-10);
							$data[4] = substr($data[4],-10);
							$data[12] = substr($data[12],-10);
							$data[15] = substr($data[15],-10);
							$data[17] = substr($data[17],-10);
							$data[24] = substr($data[24],-10);
							 
							if($data[1] != ""){
								$ex1 = explode("-",$data[1]);
								$tgl = $ex1[0];
								$bln = $ex1[1];
								$thn = $ex1[2];
								$data[1] = $thn.'-'.$bln.'-'.$tgl;
							}
							
							//echo $data[1];
							
							if($data[4] != ""){
								$ex1 = explode("-",$data[4]);
								$tgl = $ex1[0];
								$bln = $ex1[1];
								$thn = $ex1[2];
								$data[4] = $thn.'-'.$bln.'-'.$tgl;
							}
							
							if($data[12] != ""){
								$ex1 = explode("-",$data[12]);
								$tgl = $ex1[0];
								$bln = $ex1[1];
								$thn = $ex1[2];
								$data[12] = $thn.'-'.$bln.'-'.$tgl;
							}
							
							if($data[15] != ""){
								$ex1 = explode("-",$data[15]);
								$tgl = $ex1[0];
								$bln = $ex1[1];
								$thn = $ex1[2];
								$data[15] = $thn.'-'.$bln.'-'.$tgl;
							}
							
							if($data[17] != ""){
								$ex1 = explode("-",$data[17]);
								$tgl = $ex1[0];
								$bln = $ex1[1];
								$thn = $ex1[2];
								$data[17] = $thn.'-'.$bln.'-'.$tgl;
							}
							
							if($data[24] != ""){
								$ex1 = explode("-",$data[24]);
								$tgl = $ex1[0];
								$bln = $ex1[1];
								$thn = $ex1[2];
								$data[24] = $thn.'-'.$bln.'-'.$tgl;
							} */
								
							if($no >= 11 && $data[0] != ""){
								
								if($data[8] == ""){
									$barr = mysql_fetch_assoc(mysql_query("SELECT id_barang FROM ref_barang WHERE nama_barang = '$data[9]'"));
									$data[8] = $barr['id_barang'];
								}
								
								if($data[20] == ""){
									$barr = mysql_fetch_assoc(mysql_query("SELECT id_barang FROM ref_barang WHERE nama_barang = '$data[21]'"));
									$data[20] = $barr['id_barang'];
								}
								
								
								$u1 = mysql_fetch_row(mysql_query("SELECT UUID()"));
								$uuid1 = $u1[0];
								
								
								$u3 = mysql_fetch_row(mysql_query("SELECT UUID()"));
								$uuid3 = $u3[0];
								
								$u3 = mysql_fetch_row(mysql_query("SELECT UUID()"));
								$uuid3 = $u3[0];
								
								
								$u5 = mysql_fetch_row(mysql_query("SELECT UUID()"));
								$uuid5 = $u5[0];
								
								
								$u7 = mysql_fetch_row(mysql_query("SELECT UUID()"));
								$uuid7 = $u7[0];
								
								
								$u9 = mysql_fetch_row(mysql_query("SELECT UUID()"));
								$uuid9 = $u9[0];
								
								
								$u10 = mysql_fetch_row(mysql_query("SELECT UUID()"));
								$uuid10 = $u10[0];
								
								$data[7] = str_replace('.','',$data[7]);
								$data[10] = str_replace('.','',$data[10]);
								$data[19] = str_replace('.','',$data[19]);
								$data[22] = str_replace('.','',$data[22]);
								
								
								if($data2 == $data[2] && $data4 == $data[4]){
								
								} else {
									
									
									$u = mysql_fetch_row(mysql_query("SELECT UUID()"));
									$uuid = $u[0];
									$u2 = mysql_fetch_row(mysql_query("SELECT UUID()"));
									$uuid2 = $u2[0];
									$u4 = mysql_fetch_row(mysql_query("SELECT UUID()"));
									$uuid4 = $u4[0];
									$u6 = mysql_fetch_row(mysql_query("SELECT UUID()"));
									$uuid6 = $u6[0];
									$u8 = mysql_fetch_row(mysql_query("SELECT UUID()"));
									$uuid8 = $u8[0];
									//echo "INSERT <br>";
									
									if($data[9] != ""){
										
										$t2 = substr($data[1],5,2);
										if($t2 =='01'){
											$br = "I";
										} else if($t2 =='02'){
											$br = "II";
										} else if($t2 =='03'){
											$br = "III";
										} else if($t2 =='04'){
											$br = "VI";
										} else if($t2 =='05'){
											$br = "V";
										} else if($t2 =='06'){
											$br = "VI";
										} else if($t2 =='07'){
											$br = "VII";
										} else if($t2 =='08'){
											$br = "VIII";
										} else if($t2 =='09'){
											$br = "IX";
										} else if($t2 =='10'){
											$br = "X";
										} else if($t2 =='11'){
											$br = "XI";
										} else if($t2 =='12'){
											$br = "XII";
										}
										
										$no_pembayaran = $nomor.'/BYR/'.$kd_skpd.'/'.$br.'/'.$ta;
										$no_ba_pemeriksaan = $nomor.'/PKS/'.$kd_skpd.'/'.$br.'/'.$ta;
										$no_ba_penerimaan = $nomor.'/TRM/'.$kd_skpd.'/'.$br.'/'.$ta;
										$no_dok_penerimaan = $nomor.'/DKN/'.$kd_skpd.'/'.$br.'/'.$ta;
									
										//MASUK ------------------------------------------------------------------------------------------------------------------------
										//MASUK ------------------------------------------------------------------------------------------------------------------------
										$q1 = mysql_query("INSERT INTO masuk 
										(id_masuk, uuid_skpd, kd_skpd, kd_prog, id_prog, kd_keg, kd_rek_1, kd_rek_2, kd_rek_3, kd_rek_4, kd_rek_5, no_rinc, 
										ta, nama_pengadaan, nama_penyedia, tgl_pengadaan, no_kontrak, tgl_pembayaran, no_pembayaran, id_sumber, tgl_pemeriksaan, 
										no_ba_pemeriksaan, tgl_penerimaan, no_ba_penerimaan, no_dok_penerimaan, tgl_dok_penerimaan, id_gudang, status_proses, 
										create_date, update_date, soft_delete, creator_id) 
										VALUES 
										('$uuid','$uuid_skpd','$kd_skpd','','','','','','','','','',
										'2017','-','$data[2]','$data[1]' , '-', '$data[4]' , '$no_pembayaran', '$id_sumber_dana', '$data[4]', 
										'$no_ba_pemeriksaan', '$data[1]', '$no_ba_penerimaan', '$no_dok_penerimaan', '$data[12]', '$id_gudang', '3', 
										NOW(), '', 0,'')");
										$nomor++;
									}
									
									if($data[21] != ""){
																				
										$t2 = substr($data[17],5,2);
										if($t2 =='01'){
											$br = "I";
										} else if($t2 =='02'){
											$br = "II";
										} else if($t2 =='03'){
											$br = "III";
										} else if($t2 =='04'){
											$br = "VI";
										} else if($t2 =='05'){
											$br = "V";
										} else if($t2 =='06'){
											$br = "VI";
										} else if($t2 =='07'){
											$br = "VII";
										} else if($t2 =='08'){
											$br = "VIII";
										} else if($t2 =='09'){
											$br = "IX";
										} else if($t2 =='10'){
											$br = "X";
										} else if($t2 =='11'){
											$br = "XI";
										} else if($t2 =='12'){
											$br = "XII";
										}
										$nomor_nota = $nomor2.'/NP/'.$kd_skpd.'/'.$br.'/'.$ta;
										$no_spb = $nomor2.'/NS/'.$kd_skpd.'/'.$br.'/'.$ta;
										$no_surat = $nomor2.'/SPPB/'.$kd_skpd.'/'.$br.'/'.$ta;
										$nomor_keluar = $nomor2.'/KLR/'.$kd_skpd.'/'.$br.'/'.$ta; 
										//NOTA ------------------------------------------------------------------------------------------------------------------------
										//NOTA ------------------------------------------------------------------------------------------------------------------------
										$q4 = mysql_query("INSERT INTO nota_minta
										(id_nota_minta, unit_peminta, ta, no_nota, tgl_nota, stat_untuk, 
										unit_dituju, peruntukan, status, 
										create_date, update_date, soft_delete, creator_id) 
										VALUES 
										('$uuid2', '$uuid_skpd', '2017', '$nomor_nota', '$data[17]', '0', 
										'', '$data[18]', '3', 
										NOW(), '', 0, '')");
										
										//SURAT ------------------------------------------------------------------------------------------------------------------------
										//SURAT ------------------------------------------------------------------------------------------------------------------------
										$q6 = mysql_query("INSERT INTO surat_minta
										(id_surat_minta, unit_peminta, stat_untuk, unit_dituju, peruntukan, ta, 
										id_nota_minta, no_spb, tgl_spb, status, 
										create_date, update_date, soft_delete, creator_id) 
										VALUES 
										('$uuid4', '$uuid_skpd', 0, '0', '$data[18]', '2017', 
										'$uuid2' ,'$no_spb', '$data[17]', '3', 
										NOW(), '', 0, '')");
										
										//SP OUT ------------------------------------------------------------------------------------------------------------------------
										//SP OUT ------------------------------------------------------------------------------------------------------------------------
										$q8 = mysql_query("INSERT INTO sp_out
										(id_sp_out, id_surat_minta, uuid_skpd, ta, 
										no_sp_out, tgl_sp_out, stat_untuk, uuid_untuk, peruntukan, keterangan, status, 
										create_date, update_date, soft_delete, creator_id) 
										VALUES 
										('$uuid6', '$uuid4', '$uuid_skpd', '2017', 
										'$no_surat', '$data[17]', '0', '', '$data[18]', '$data[25]', '3', 
										NOW(), '', '0', '')");
										
										//KELUAR ------------------------------------------------------------------------------------------------------------------------
										//KELUAR ------------------------------------------------------------------------------------------------------------------------
										$q10 = mysql_query("INSERT INTO keluar
										(id_keluar, uuid_skpd, ta, id_sp_out, no_ba_out, tgl_ba_out, jenis_out, 
										uuid_untuk, peruntukan, no_reklas, tgl_reklas, id_pejabat_pengguna, id_pejabat_penyimpan, keterangan, status, 
										create_date, update_date, soft_delete, creator_id) 
										VALUES 
										('$uuid8', '$uuid_skpd', '2017', '$uuid6', '$nomor_keluar', '$data[17]', 'k', 
										'', '$data[18]', '', '', '', '', '$data[25]', '0', 
										NOW(), '', '0', '')");
									
										$nomor2++;
									}
									
								}
								
								
								$data2 = $data[2];
								$data4 = $data[4];
								
								//MASUK DETAIL ------------------------------------------------------------------------------------------------------------------------
								//MASUK DETAIL ------------------------------------------------------------------------------------------------------------------------
								
								if($data[9] != ""){
									
									$q2 = mysql_query("INSERT INTO masuk_detail 
									(id_masuk_detail, id_masuk, uuid_skpd, ta, id_kelompok,
									id_barang, jml_masuk, harga_masuk, keterangan, tahun, 
									create_date, update_date, soft_delete, creator_id) 
									VALUES 
									('$uuid1', '$uuid' ,'$uuid_skpd' ,'2017','$id_kelompok' ,
									'$data[8]' ,'$data[7]' ,'$data[10]' ,'$data[13]' ,'2017' ,
									NOW() ,'' ,'0' ,'')");
									
									
									//KARTU STOK ------------------------------------------------------------------------------------------------------------------------
									//KARTU STOK ------------------------------------------------------------------------------------------------------------------------
									$q3 = mysql_query("INSERT INTO kartu_stok
									(id_stok, uuid_skpd, id_barang, id_kelompok, id_sumber_dana, id_gudang, id_transaksi, id_transaksi_detail, tgl_transaksi, ta, 
									jml_in, jml_out, harga, keterangan, kode, 
									create_date, update_date, soft_delete, creator_id) 
									VALUES 
									('$uuid9', '$uuid_skpd', '$data[8]', '$id_kelompok', '$id_sumber_dana', '$id_gudang', '$uuid', '$uuid1', '$data[1]', '2017', 
									'$data[7]', '0', '$data[10]', '$data[13]', 'i', 
									NOW(), '', '0', '')");
								
								}
								
								if($data[21] != ""){ 
									//NOTA DETAIL ------------------------------------------------------------------------------------------------------------------------
									//NOTA DETAIL ------------------------------------------------------------------------------------------------------------------------
									
									$q5 = mysql_query("INSERT INTO nota_minta_detail
									(id_nota_minta_detail, id_nota_minta, uuid_skpd, ta, 
									id_barang, jumlah, ket, 
									create_date, update_date, soft_delete, creator_id) 
									VALUES 
									('$uuid3', '$uuid2', '$uuid_skpd' ,'2017', 
									'$data[20]', '$data[19]', '$data[25]', 
									NOW(), '' ,0 ,'')");
									
									
									//SURAT DETAIL ------------------------------------------------------------------------------------------------------------------------
									//SURAT DETAIL ------------------------------------------------------------------------------------------------------------------------
									
									$q7 = mysql_query("INSERT INTO surat_minta_detail
									(id_surat_minta_detail, id_surat_minta, uuid_skpd, ta, 
									id_barang, jumlah, 
									create_date, update_date, soft_delete, creator_id) 
									VALUES 
									('$uuid5', '$uuid4', '$uuid_skpd', '2017', 
									'$data[20]', '$data[19]', 
									NOW(), '', '0', '')");
									
									
									//SP OUT DETAIL ------------------------------------------------------------------------------------------------------------------------
									//SP OUT DETAIL ------------------------------------------------------------------------------------------------------------------------
										
									$q9 = mysql_query("INSERT INTO sp_out_detail
									(id_sp_out_detail, id_sp_out, uuid_skpd, ta, 
									id_barang, jml_barang, harga_barang, keterangan, 
									create_date, update_date, soft_delete, creator_id) 
									VALUES 
									('$uuid7', '$uuid6', '$uuid_skpd', '2017', 
									'$data[20]', '$data[19]', '$data[22]', '$data[25]', 
									NOW(), '', '0', '')");
									
									
									//KELUAR DETAIL ------------------------------------------------------------------------------------------------------------------------
									//KELUAR DETAIL ------------------------------------------------------------------------------------------------------------------------
										
									$q11 = mysql_query("INSERT INTO keluar_detail
									(id_keluar_detail, id_keluar, id_terima_detail, uuid_skpd, ta, 
									tgl_minta, tgl_terima, id_gudang, id_kelompok, id_sumber_dana, id_barang, jml_barang, harga_barang, keterangan, 
									create_date, update_date, soft_delete, creator_id) 
									VALUES 
									('$uuid10', '$uuid8', '', '$uuid_skpd', '2017', 
									'$data[17]', '$data[15]', '$id_gudang', '$id_kelompok', '$id_sumber_dana', '$data[20]', '$data[19]', '$data[22]', '$data[25]', 
									NOW(), '', '0', '')");
									
								}
								
								//echo $no." ".$data2." ".$data4." :: $data[2] :: $data[4] <br>";
								
							}
							$no++;
							
							
						}
						
						if($invalid!="") $txtValid = "Ada beberapa invalid data!<br>"; else $txtValid = "";
						
						echo json_encode(array( 'success'=>true, 
												'pesan'=>"Data Persediaan Berhasil di Import !",
												'data'=>$result,
												'error'=>$invalid));
						unlink($uploadfile);
						$log_import = mysql_query("INSERT INTO log_import VALUES (UUID(),'$uuid_skpd',NOW(),'Data Persediaan Berhasil di Import !')");
					}
	 			}else {
					echo json_encode(array('success'=>false, 'pesan'=>"File tidak sesuai format !"));
					//unlink($uploadfile);
					$log_import = mysql_query("INSERT INTO log_import VALUES (UUID(),'$uuid_skpd',NOW(),'File tidak sesuai format !')");
					false;
				}
 			}



mysql_close();
?>
