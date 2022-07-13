<?php

date_default_timezone_set('Asia/Jakarta');
error_reporting(E_ALL); ini_set('display_errors', 'on'); 
require_once "../config/db.koneksi.php";
include_once "../config/db.function.php";
include_once "../config/library.php";
session_start();
//$uploadfile   = 'tes2.csv';
				
//$handle = fopen($uploadfile, "r"); //Membuka file dan membacanya
//$content = file_get_contents($uploadfile);
//unlink($uploadfile); break;
//fgets($handle); // read the first line and ignore it
$no = 1;
$data2 = "xxx";
$data4 = "xxx";
$data17 = "xxx";
$data18 = "xxx";
$nomor = 1;
$nomor2 = 1;
$upload_date = date("Y-m-d H:i:s");
$pengguna = pengguna();

if(isset($_REQUEST['id_sub']))$uuid_skpd = $_REQUEST['id_sub'];
//if(isset($_REQUEST['smt']))$smt = $_REQUEST['smt'];
if(isset($_REQUEST['ta']))$ta = $_REQUEST['ta'];
if(isset($_REQUEST['id_sumber_dana']))$id_sumber_dana = $_REQUEST['id_sumber_dana'];


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
				if ($type != "php") {
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
							//echo($data);
							//print_r($data);
							$data[5] = str_replace(".","",$data[5]);
							$data[5] = str_replace(",",".",$data[5]);
							
							/* $q = mysql_query("INSERT INTO log_import(id_rencana, uuid_skpd, nm_kegiatan, nm_barang, satuan, harga, tgl_rencana, id_sumber_dana, ta, 
								create_date, creator_id) VALUES  (UUID(), '$uuid_skpd', '$data[0]', '$data[1]','$data[2]', '$data[3]','$data[4]','$id_sumber_dana','$ta','$upload_date','$pengguna')") or die(mysql_error()); */
							
							/* $q = mysql_query("INSERT INTO log_import(id_rencana, uuid_skpd, nm_kegiatan, id_barang,nm_barang,id_satuan, harga,jumlah_barang,id_subrek, id_sumber_dana, ta, 
								create_date, creator_id,status) VALUES  (UUID(), '$uuid_skpd', '$data[0]', '$data[1]','$data[2]', '$data[3]','$data[4]','$data[5]','$data[6]','$id_sumber_dana','$ta','$upload_date','$pengguna','0')") or die(mysql_error()); */
							
							//dengan kode kegiatan
							$q = mysql_query("INSERT INTO log_import(id_rencana, uuid_skpd,nm_kegiatan, kd_kegiatan, id_barang,nm_barang,id_satuan,harga,jumlah_barang, id_subrek, id_sumber_dana, ta, 
							create_date, creator_id,status,jumlah_barang_isi) VALUES  (UUID(), '$uuid_skpd', '$data[0]', '$data[1]','$data[2]', '$data[3]','$data[4]','$data[5]','$data[6]','$data[7]','$id_sumber_dana','$ta','$upload_date','$pengguna','0','0')") or die(mysql_error());

							$datatot="";
							$dataskpd="";
							$tot=mysql_query("SELECT uuid_skpd,kd_kegiatan,id_barang, COUNT(id_barang) tot FROM log_import
							WHERE uuid_skpd='$uuid_skpd'
							GROUP BY kd_kegiatan,id_barang
							HAVING COUNT(id_barang)>1
							ORDER BY id_barang");
							while ($tot1 = mysql_fetch_assoc($tot)) {
								$datatot .= "$tot1[tot]";
								$dataskpd .= "$tot1[uuid_skpd]";
								
								
								
							}

							$datajen="";
							$jen=mysql_query("SELECT DISTINCT id_subrek FROM log_import  WHERE uuid_skpd='$uuid_skpd'
							AND id_subrek NOT IN (SELECT DISTINCT id_jenis FROM ref_barang WHERE id_jenis=log_import.id_subrek AND id_barang=log_import.id_barang )");
							while ($jen1 = mysql_fetch_assoc($jen)) {
								$datajen .= "$jen1[id_subrek]";
								
								
								
							}

							//print_r($datatot);
							

							/* if($no>= 11 && $data[0] != ""){
								 
								$q = mysql_query("INSERT INTO log_import(id_rencana, uuid_skpd, nm_kegiatan, nm_barang, satuan, harga, tgl_rencana, id_sumber_dana, ta, 
								create_date, creator_id) VALUES  (UUID(), '$uuid_skpd', '$data[0]', '$data[1]','$data[2]', '$data[3]','$data[4]','$id_sumber_dana','$ta','$upload_date','$pengguna')") or die(mysql_error());
								
							} */
										/* $insert_out = mysql_query("INSERT INTO import_tmp_out(id, uuid_skpd, smt, ta, id_sumber_dana, k14, k15, k16, k17, k18, k19, k20, k21, k22, k23, k24, k25, kurang, upload_date, status) VALUES (UUID(), '$uuid_skpd', '$smt', '$ta', '$id_sumber_dana', '', '$data[16]', '$data[17]', '$data[26]', '$data[19]', '$data[20]', '$data[21]', '$data[22]', '$data[23]', '$data[25]', '$data[18]', '$data[27]', '0', '$upload_date', '0' )"); */
									// }
								// }
								
							//}
							$no++;
							
							
						
					
					}

					print_r($datajen);
							if($invalid!="" ) $txtValid = "Ada beberapa invalid data!<br>"; else $txtValid = "";
						
							if($datatot>1 || $datajen!='' || $datajen!=null ){
								echo json_encode(array( 'success'=>false, 
														'pesan'=>"Data Persediaan Tidak Berhasil di Import, Ada Data Double atau Jenis Barang yang Salah",
														'data'=>$result,
														'error'=>$invalid));
								unlink($uploadfile);
								$log_import = mysql_query("DELETE FROM log_import WHERE uuid_skpd='$uuid_skpd' and status='0' ");
								}else{
									echo json_encode(array( 'success'=>true, 
												'pesan'=>"Data Persediaan Berhasil di Import !",
												'data'=>$result,
												'error'=>$invalid));
									unlink($uploadfile);
									$log_import = mysql_query("UPDATE log_import SET status='1', hasil ='Data Persediaan Berhasil di Import' WHERE uuid_skpd='$uuid_skpd'");
								}
							}
						 }else {
							echo json_encode(array('success'=>false, 'pesan'=>"File tidak sesuai format !"));
							//unlink($uploadfile);
							$log_import = mysql_query("UPDATE log_import SET hasil ='File tidak sesuai format ' WHERE uuid_skpd='$uuid_skpd'");
							false;
						}
 			}



mysql_close();
?>
