<?php

date_default_timezone_set('Asia/Jakarta');
error_reporting(E_ALL); ini_set('display_errors', 'on'); 
require_once "../config/db.koneksi.php";
include_once "../config/db.function.php";
include_once "../config/library.php";
session_start();


if(isset($_REQUEST['aksi']))$aksi = $_REQUEST['aksi'];
if(isset($_REQUEST['id_sub']))$uuid_skpd = $_REQUEST['id_sub'];
if(isset($_REQUEST['smt']))$smt = $_REQUEST['smt'];
if(isset($_REQUEST['ta']))$ta = $_REQUEST['ta'];
if(isset($_REQUEST['id_sumber_dana']))$id_sumber_dana = $_REQUEST['id_sumber_dana'];


$no = 1;
$data2 = "xxx";
$data4 = "xxx";
$data17 = "xxx";
$data18 = "xxx";
$nomor = 1;
$nomor2 = 1;

$skpd = mysql_fetch_assoc(mysql_query(" SELECT uuid_sub2_unit AS id, nm_sub2_unit AS text, 
										CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit) AS kode,
										CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit, kd_sub, kd_sub2) AS sub
										FROM ref_sub2_unit WHERE uuid_sub2_unit = '$uuid_skpd'"));
$kode = explode('.', $skpd['sub']); 
$kd1 = str_pad($kode[1],2,'0', STR_PAD_LEFT);
$kd2 = str_pad($kode[2],2,'0', STR_PAD_LEFT);
$kd3 = str_pad($kode[3],2,'0', STR_PAD_LEFT);

$kd_skpd = $kode[0].".".$kd1.".".$kd2.".".$kd3;

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


if($aksi == "in"){
	$update = mysql_query(" UPDATE
								import_tmp_in
							SET
								status = '1'
							WHERE 
								uuid_skpd = '$uuid_skpd' AND
								id_sumber_dana = '$id_sumber_dana' AND
								smt = '$smt' AND
								ta = '$ta' ");
	if($update){
		
		$q = mysql_query("	SELECT 
								* 
							FROM 
								import_tmp_in
							WHERE 
								uuid_skpd = '$uuid_skpd' AND
								id_sumber_dana = '$id_sumber_dana' AND
								smt = '$smt' AND
								ta = '$ta' AND
								status = '1' ");
		while($row=mysql_fetch_assoc($q)){
			
			// ====================================================== START INSERT ====================================================== //
			// ====================================================== START INSERT ====================================================== //
			
			
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

			 
			if($data2 == $row['k2'] && $data4 == $row['k4'] ){

			} else {
				
				
				$um1 = mysql_fetch_row(mysql_query("SELECT UUID()"));
				$uuidm1 = $um1[0];
				//echo "INSERT <br>";
				
				if($row['k9'] != ""){
					
					$t2 = substr($row['k1'],5,2);
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
					('$uuidm1','$uuid_skpd','$kd_skpd','','','','','','','','','',
					'$ta','-','$row[k2]','$row[k1]' , '-', '$row[k4]' , '$no_pembayaran', '$id_sumber_dana', '$row[k4]', 
					'$no_ba_pemeriksaan', '$row[k1]', '$no_ba_penerimaan', '$no_dok_penerimaan', '$row[k12]', '$id_gudang', '3', 
					NOW(), '', 0,'')");
					$nomor++;
				}
				
			}
			$data2 = $row['k2'];
			$data4 = $row['k4'];
			
			if($row['k9'] != ""){
				$um2 = mysql_fetch_row(mysql_query("SELECT UUID()"));
				$uuidm2 = $um2[0];

				$q2 = mysql_query("INSERT INTO masuk_detail 
				(id_masuk_detail, id_masuk, uuid_skpd, ta, id_kelompok,
				id_barang, jml_masuk, harga_masuk, keterangan, tahun, 
				create_date, update_date, soft_delete, creator_id) 
				VALUES 
				('$uuid1', '$uuidm1' ,'$uuid_skpd' ,'$ta','$id_kelompok' ,
				'$row[k8]' ,'$row[k7]' ,'$row[k10]' ,'$row[k13]' ,'$ta' ,
				NOW() ,'' ,'0' ,'')");


				//KARTU STOK ------------------------------------------------------------------------------------------------------------------------
				//KARTU STOK ------------------------------------------------------------------------------------------------------------------------
				$q3 = mysql_query("INSERT INTO kartu_stok
				(id_stok, uuid_skpd, id_barang, id_kelompok, id_sumber_dana, id_gudang, id_transaksi, id_transaksi_detail, tgl_transaksi, ta, 
				jml_in, jml_out, harga, keterangan, kode, 
				create_date, update_date, soft_delete, creator_id) 
				VALUES 
				(UUID(), '$uuid_skpd', '$row[k8]', '$id_kelompok', '$id_sumber_dana', '$id_gudang', '$uuidm1', '$uuidm2', '$row[k1]', '$ta', 
				'$row[k7]', '0', '$row[k10]', '$row[k13]', 'i', 
				NOW(), '', '0', '')");

			}
			
			// ====================================================== END INSERT ====================================================== //
			// ====================================================== END INSERT ====================================================== //
		
		} // END WHILE
		
		echo json_encode(array('success'=>true, 'pesan'=>"Data Pengadaan Berhasil di Approve !"));
		$update = mysql_query(" UPDATE
									import_tmp_in
								SET
									status = '*'
								WHERE 
									uuid_skpd = '$uuid_skpd' AND
									id_sumber_dana = '$id_sumber_dana' AND
									smt = '$smt' AND
									ta = '$ta' ");
	} else {
		echo json_encode(array('success'=>true, 'pesan'=>"Data Pengadaan Gagal di Approve !"));
	}
} else if ($aksi == "out"){
	$update = mysql_query(" UPDATE
								import_tmp_out
							SET 
								status = '1'
							WHERE 
								uuid_skpd = '$uuid_skpd' AND
								id_sumber_dana = '$id_sumber_dana' AND
								smt = '$smt' AND
								ta = '$ta' ");
	if($update){
		
		
		
		$q = mysql_query("	SELECT 
								* 
							FROM 
								import_tmp_out
							WHERE 
								uuid_skpd = '$uuid_skpd' AND
								id_sumber_dana = '$id_sumber_dana' AND
								smt = '$smt' AND
								ta = '$ta' AND
								k19 > 0 AND 
								status = '1' ");
		while($row=mysql_fetch_assoc($q)){
		
			// ====================================================== INSERT ====================================================== //
			// ====================================================== INSERT ====================================================== //
		
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

			if( $data17 == $row['k17'] && $data18 == $row['k18'] ){

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
				
				
				if($row['k21'] != ""){
							
					$t2 = substr($row['k17'],5,2);
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
					('$uuid2', '$uuid_skpd', '$ta', '$nomor_nota', '$row[k17]', '0', 
					'', '$row[k18]', '3', 
					NOW(), '', 0, '')");
					
					//SURAT ------------------------------------------------------------------------------------------------------------------------
					//SURAT ------------------------------------------------------------------------------------------------------------------------
					$q6 = mysql_query("INSERT INTO surat_minta
					(id_surat_minta, unit_peminta, stat_untuk, unit_dituju, peruntukan, ta, 
					id_nota_minta, no_spb, tgl_spb, status, 
					create_date, update_date, soft_delete, creator_id) 
					VALUES 
					('$uuid4', '$uuid_skpd', 0, '0', '$row[k18]', '$ta', 
					'$uuid2' ,'$no_spb', '$row[k17]', '3', 
					NOW(), '', 0, '')");
					
					//SP OUT ------------------------------------------------------------------------------------------------------------------------
					//SP OUT ------------------------------------------------------------------------------------------------------------------------
					$q8 = mysql_query("INSERT INTO sp_out
					(id_sp_out, id_surat_minta, uuid_skpd, ta, 
					no_sp_out, tgl_sp_out, stat_untuk, uuid_untuk, peruntukan, keterangan, status, 
					create_date, update_date, soft_delete, creator_id) 
					VALUES 
					('$uuid6', '$uuid4', '$uuid_skpd', '$ta', 
					'$no_surat', '$row[k17]', '0', '', '$row[k18]', '$row[k25]', '3', 
					NOW(), '', '0', '')");
					
					//KELUAR ------------------------------------------------------------------------------------------------------------------------
					//KELUAR ------------------------------------------------------------------------------------------------------------------------
					$q10 = mysql_query("INSERT INTO keluar
					(id_keluar, uuid_skpd, ta, id_sp_out, no_ba_out, tgl_ba_out, jenis_out, 
					uuid_untuk, peruntukan, no_reklas, tgl_reklas, id_pejabat_pengguna, id_pejabat_penyimpan, keterangan, status, 
					create_date, update_date, soft_delete, creator_id) 
					VALUES 
					('$uuid8', '$uuid_skpd', '$ta', '$uuid6', '$nomor_keluar', '$row[k17]', 'k', 
					'', '$row[k18]', '', '', '', '', '$row[k25]', '0', 
					NOW(), '', '0', '')");
				
					$nomor2++;
				}
				
			}

			$data17 = $row['k17'];
			$data18 = $row['k18'];


			if($row['k21'] != ""){
				//NOTA DETAIL ------------------------------------------------------------------------------------------------------------------------
				//NOTA DETAIL ------------------------------------------------------------------------------------------------------------------------
				
				$q5 = mysql_query("INSERT INTO nota_minta_detail
				(id_nota_minta_detail, id_nota_minta, uuid_skpd, ta, 
				id_barang, jumlah, ket, 
				create_date, update_date, soft_delete, creator_id) 
				VALUES 
				('$uuid3', '$uuid2', '$uuid_skpd' ,'$ta', 
				'$row[k20]', '$row[k19]', '$row[k25]', 
				NOW(), '' ,0 ,'')");
				
				
				//SURAT DETAIL ------------------------------------------------------------------------------------------------------------------------
				//SURAT DETAIL ------------------------------------------------------------------------------------------------------------------------
				
				$q7 = mysql_query("INSERT INTO surat_minta_detail
				(id_surat_minta_detail, id_surat_minta, uuid_skpd, ta, 
				id_barang, jumlah, 
				create_date, update_date, soft_delete, creator_id) 
				VALUES 
				('$uuid5', '$uuid4', '$uuid_skpd', '$ta', 
				'$row[k20]', '$row[k19]', 
				NOW(), '', '0', '')");
				
				
				//SP OUT DETAIL ------------------------------------------------------------------------------------------------------------------------
				//SP OUT DETAIL ------------------------------------------------------------------------------------------------------------------------
					
				$q9 = mysql_query("INSERT INTO sp_out_detail
				(id_sp_out_detail, id_sp_out, uuid_skpd, ta, 
				id_barang, jml_barang, harga_barang, keterangan, 
				create_date, update_date, soft_delete, creator_id) 
				VALUES 
				('$uuid7', '$uuid6', '$uuid_skpd', '$ta', 
				'$row[k20]', '$row[k19]', '$row[k22]', '$row[k25]', 
				NOW(), '', '0', '')");
				
				
				//KELUAR DETAIL ------------------------------------------------------------------------------------------------------------------------
				//KELUAR DETAIL ------------------------------------------------------------------------------------------------------------------------
					
				$q11 = mysql_query("INSERT INTO keluar_detail
				(id_keluar_detail, id_keluar, id_terima_detail, uuid_skpd, ta, 
				tgl_minta, tgl_terima, id_gudang, id_kelompok, id_sumber_dana, id_barang, jml_barang, harga_barang, keterangan, 
				create_date, update_date, soft_delete, creator_id) 
				VALUES 
				('$uuid10', '$uuid8', '', '$uuid_skpd', '$ta', 
				'$row[k17]', '$row[k15]', '$id_gudang', '$id_kelompok', '$id_sumber_dana', '$row[k20]', '$row[k19]', '$row[k22]', '$row[k25]', 
				NOW(), '', '0', '')");
/* 

				//KARTU STOK ------------------------------------------------------------------------------------------------------------------------
				//KARTU STOK ------------------------------------------------------------------------------------------------------------------------
				$q3 = mysql_query("INSERT INTO kartu_stok
				(id_stok, uuid_skpd, id_barang, id_kelompok, id_sumber_dana, id_gudang, id_transaksi, id_transaksi_detail, tgl_transaksi, ta, 
				jml_in, jml_out, harga, keterangan, kode, 
				create_date, update_date, soft_delete, creator_id) 
				VALUES 
				(UUID(), '$uuid_skpd', '$row[k20]', '$id_kelompok', '$id_sumber_dana', '$id_gudang', '$uuid8', '$uuid10', '$row[k15]', '$ta', 
				'0', '$row[k19]', '$row[k22]', '$row[k25]', 'ok', 
				NOW(), '', '0', '')"); */
				
			}

			// ====================================================== END INSERT ====================================================== //
			// ====================================================== END INSERT ====================================================== //
		
		} // END WHILE
		
		
		
		
		
				
				// / / / / / / / / / / / / / / / / / / / / / / / / POSTING
				// / / / / / / / / / / / / / / / / / / / / / / / / POSTING
				// / / / / / / / / / / / / / / / / / / / / / / / / POSTING
				// / / / / / / / / / / / / / / / / / / / / / / / / POSTING
				
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
						
						/* 
						$delsp = $DBcon->prepare(" DELETE FROM sp_out_detail WHERE id_sp_out = '$r2[id_sp_out]' ");
						$delsp->execute();
				 */
						$r3 = mysql_query(" CALL ambil_harga_insert_sp_out_detail('$array_bar', '$array_jml', '$r2[tgl_sp_out]', '$uuid_skpd', '$r2[id_sp_out]', '$r2[id_sp_out]', '$r2[ta]', '$datime', 'asd') ");
				
						
					}							
					$qqqsm = mysql_query("UPDATE surat_minta SET status=1 WHERE id_surat_minta = '$r0[id_surat_minta]'");
					
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
						
						// while($val = $brg->fetch(PDO::FETCH_ASSOC)){
						while($val=mysql_fetch_assoc($brg)){
							
							
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
							
						} 
						
						$array_bar2 .= $array_gud.' :: ';
						
						if($array_bar!=""){
							$del2 = mysql_query("DELETE FROM keluar_detail WHERE id_keluar = '$rk[id_keluar]' AND id_sumber_dana = '$id_sumber_dana' ");
							$qcalla = mysql_query("CALL ambil_harga_insert_keluar_detail('$array_bar', '$array_jml', '$array_tgl', 
												'$array_gud', '$array_sum', '$uuid_skpd', '$rk[id_keluar]', '$rk[ta]', NOW(), 
												'$pengguna', '$tgl_minta', '$kode')");
							
						}
						
						$qcallb = mysql_query("UPDATE sp_out o LEFT JOIN surat_minta m ON o.id_surat_minta = m.id_surat_minta
										SET o.status = 3, m.status = 3 WHERE id_sp_out = '$rk[id_sp_out]'");
										
										
				}
				 
				// / / / / / / / / / / / / / / / / / / END POSTING
				// / / / / / / / / / / / / / / / / / / END POSTING
				// / / / / / / / / / / / / / / / / / / END POSTING
				// / / / / / / / / / / / / / / / / / / END POSTING
				
				
				
			
		echo json_encode(array('success'=>true, 'pesan'=>"Data Pengeluaran Berhasil di Approve !"));
	/* 	$update = mysql_query(" UPDATE
									import_tmp_out
								SET
									status = '*'
								WHERE 
									uuid_skpd = '$uuid_skpd' AND
									id_sumber_dana = '$id_sumber_dana' AND
									smt = '$smt' AND
									ta = '$ta' "); */
	} else {
		echo json_encode(array('success'=>true, 'pesan'=>"Data Pengeluaran Gagal di Approve !"));
	}
} else {
	echo json_encode(array('success'=>false, 'pesan'=>"Aksi Tidak Dikenali !"));
}

					
mysql_close();
?>
