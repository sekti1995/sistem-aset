<?php

date_default_timezone_set('Asia/Jakarta');
error_reporting(E_ALL); ini_set('display_errors', 'on'); 
require_once "../config/db.koneksi.php";
include_once "../config/db.function.php";
include_once "../config/library.php";
session_start();

			if(isset($_POST['basket']))$basket = $_POST['basket'];
			if(isset($_POST['thn']))$thn = $_POST['thn'];
			if(isset($_POST['id_gudang']))$id_gudang = $_POST['id_gudang'];
			if(isset($_POST['id_kelompok']))$id_kelompok = $_POST['id_kelompok'];
			if(isset($_POST['id_sumber']))$id_sumber = $_POST['id_sumber'];
			if(isset($_POST['id_sub']))$uuid_skpd = $_POST['id_sub'];
			if(isset($_POST['no_ba']))$no_ba = $_POST['no_ba'];
			if(isset($_POST['tgl_ba']))$tgl_ba = balikTanggal($_POST['tgl_ba']);
			$datime = date("Y-m-d H:i:s");
			$pengguna = pengguna();
			
			$ta = '2019';
			$db_name = "simbaper_".$ta;
			
			$u = mysql_fetch_row(mysql_query("SELECT UUID()"));
			$uuid = $u[0];
			$cek = mysql_fetch_assoc(mysql_query(" SELECT COUNT(id_cut_off) AS jml FROM cut_off WHERE uuid_skpd = '' AND akhir_th = '$thn' AND awal_th = '$ta' "));
			if($cek['jml'] == 0 ){
				$q1 = mysql_query("INSERT INTO cut_off VALUES(UUID(), '$uuid_skpd', '$thn', '$ta', NOW(), '1' ) ");
				$q2 = mysql_query("INSERT INTO koneksi_opd VALUES('$uuid_skpd','$db_name') ");
				if($q2){
					$my['host'] = "localhost";
					$my['user'] = "root";
					$my['pass'] = "Sql.root.2018";
					$my['dbs'] = $db_name;
					$koneksi=mysql_connect($my['host'],$my['user'],$my['pass']);
					if (! $koneksi){ echo "Gagal Koneksi..!".mysql_error(); }
					mysql_select_db($my['dbs']);
					
					////////////////////////////////////////////////////////////////////////////////////////////////////
					
					/* mysql_query("INSERT INTO adjust (id_adjust, uuid_skpd, tgl_adjust, tgl_ba, no_ba, id_gudang, id_kelompok, id_sumber_dana, 
													status, create_date, creator_id)
											VALUES ('$uuid', '$uuid_skpd', '$tgl_ba', '$tgl_ba', '$no_ba', '$id_gudang', '$id_kelompok', 
												'$id_sumber', 'data_awal', '$datime', '$pengguna')");
					if(mysql_errno()==0) { */
						foreach($basket AS $val){
							//$harga = preg_replace("/[^0-9]/","", $val['harga']);	
							//$jumlah = preg_replace("/[^0-9]/","", $val['saldo']);
							//if($val['jumlah'] > 0){
							
							$harga = $val['harga'];	
							$jumlah = $val['jumlah'];

							$harga = str_replace(".","",$harga);
							$harga = str_replace(",",".",$harga);
							$jumlah = str_replace(".","",$jumlah);
							$jumlah = str_replace(",",".",$jumlah);	
							
							$u = mysql_fetch_row(mysql_query("SELECT UUID()"));
							$uuidet = $u[0];
							mysql_query("INSERT INTO adjust_detail (id_adjust_detail, id_adjust, uuid_skpd, id_barang, jumlah, harga, 
																	create_date, creator_id)
															VALUES ('$uuidet', '$uuid', '$uuid_skpd', '$val[id_barang]', '$jumlah', '$harga',
																	'$datime', '$jumlah')");
							mysql_query("INSERT INTO kartu_stok (id_stok, uuid_skpd, id_barang, id_kelompok, id_gudang, 
																id_sumber_dana,
																id_transaksi, id_transaksi_detail,
																tgl_transaksi, ta, jml_in, jml_out, harga, kode,
																create_date, soft_delete, creator_id)
														VALUES	(UUID(), '$uuid_skpd', '$val[id_barang]', '$id_kelompok', '$id_gudang', 
																'$id_sumber',
																'$uuid', '$uuidet',
																'$tgl_ba', '$ta', '$jumlah', 0, '$harga', 'a',
																'$datime', 0, '$pengguna')");
							}
							
						//}	
						
						if(mysql_errno()==0) echo json_encode(array('success'=>true, 'pesan'=>"Data telah berhasil dimasukkan !"));
						else echo json_encode(array('success'=>false, 'pesan'=>"Data tidak berhasil dimasukkan !", 'error'=>mysql_error())); 
					/* }else {
						if(mysql_errno()==1062){ 
							echo json_encode(array('success'=>false, 
													'pesan'=>"Data Awal Sudah Ada !", 
													'error'=>"nomor_sama"));
						}else echo json_encode(array('success'=>false, 'pesan'=>"Tidak berhasil memasukkan data !"));
					} */
					
					
				}
			}
	
mysql_close();
?>
