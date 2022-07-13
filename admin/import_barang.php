<?php

date_default_timezone_set('Asia/Jakarta');
error_reporting(E_ALL); ini_set('display_errors', 'on'); 
 
include_once "../config/db.koneksi.php";
include_once "../config/db.function.php";
include_once "../config/library.php";
session_start();
$no = 1;
if(isset($_REQUEST['jenis_barang']))$jenis_barang = $_REQUEST['jenis_barang'];

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
						break;
					}else{					
						move_uploaded_file($_FILES['file_awal']['tmp_name'], $uploadfile);
						$result = array(); $items = array(); $r = 0; $ttotal = 0; $invalid = "";
						$handle = fopen($uploadfile, "r"); //Membuka file dan membacanya
						//$content = file_get_contents($uploadfile);
						$delimiter = detectDelimiter($uploadfile);
						//unlink($uploadfile); break;
						fgets($handle); // read the first line and ignore it
						
						$max_no = mysql_fetch_assoc(mysql_query("SELECT MAX(kd_sub2) as nomor FROM ref_barang WHERE id_jenis = '$jenis_barang' "));
						$nomor_brg = $max_no['nomor']+1;
						while (($data = fgetcsv($handle, 500000, $delimiter)) !== FALSE) {
							
							$data[3] = str_replace(".","",$data[3]);
							$data[3] = str_replace(",",".",$data[3]);
							
							if($no>= 2 && $data[0] != ""){
								 
								$q = mysql_query("INSERT INTO ref_barang VALUES (UUID(), '$jenis_barang', '$nomor_brg', '$data[0]', '$data[1]', '$data[3]', '$data[5]', '0', NOW(), '', 0, '087927b2-c651-11e5-a016-000476f4fa98')") or die(mysql_error());
								
							}
							$no++;
							
							
						}
						
						if($invalid!="") $txtValid = "Ada beberapa invalid data!<br>"; else $txtValid = "";
						
						echo json_encode(array( 'success'=>true, 
												'pesan'=>"Data Persediaan Berhasil di Import !",
												'data'=>$result,
												'error'=>$invalid));
						unlink($uploadfile);
					}
	 			}else {
					echo json_encode(array('success'=>false, 'pesan'=>"File tidak sesuai format !"));
					break;
				}
 			}



mysql_close();
?>
