<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";
	$peran = cekLogin();
	
	$id_sub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
	$ta = isset($_POST['thn']) ? $_POST['thn'] : date('Y');
	$id_sumber = isset($_POST['id_sumber']) ? $_POST['id_sumber'] : '';
	$bln = isset($_REQUEST['bln']) ? str_pad($_REQUEST['bln'],2,'0', STR_PAD_LEFT) : str_pad(date('m'),2,'0', STR_PAD_LEFT);
	
	
	if($ta!=""){ 
		$a = " AND kd.ta = '$ta'"; 
		$am = " AND mk.ta = '$ta'"; 
		$a1 = " AND k1.ta = '$ta'"; 
		$a2 = " AND k2.ta = '$ta'"; 
		$a3 = " AND k3.ta = '$ta'"; 
	}else{ $a = $am = $a1 = $a2 = $a3 = ""; }
	if($_SESSION['level']==md5('c')){
		$bs = " AND MD5(kd.uuid_skpd) = '$_SESSION[uidunit]'";
		$bm = " AND MD5(mk.uuid_skpd) = '$_SESSION[uidunit]'";
		$b1 = " AND MD5(k1.uuid_skpd) = '$_SESSION[uidunit]'";
		$b2 = " AND MD5(k2.uuid_skpd) = '$_SESSION[uidunit]'";
		$b3 = " AND MD5(k3.uuid_skpd) = '$_SESSION[uidunit]'";
	}else{
		//if($id_sub!=""){
			$bs = " AND kd.uuid_skpd = '$id_sub'";
			$bm = " AND mk.uuid_skpd = '$id_sub'";
			$b1 = " AND k1.uuid_skpd = '$id_sub'";
			$b2 = " AND k2.uuid_skpd = '$id_sub'";
			$b3 = " AND k3.uuid_skpd = '$id_sub'";
		//}else{ $bm = $bk = ""; }
	}
	if($bln!="" AND $bln!="00"){
		$c = " AND DATE_FORMAT(tgl_terima, '%m') = '$bln'"; 
		$cm = " AND DATE_FORMAT(mk.tgl_transaksi, '%m') < '$bln'"; 
		$c1 = " AND DATE_FORMAT(k1.tgl_transaksi, '%m') < '$bln'"; 
		$c2 = " AND DATE_FORMAT(k2.tgl_transaksi, '%m') = '$bln'"; 
		$c3 = " AND DATE_FORMAT(k3.tgl_transaksi, '%m') < '$bln'"; 
	}else $c = $cm = $c1 = $c2 = $c3 = "";
	if($id_sumber!=""){
		$d = "AND kd.id_sumber_dana = '$id_sumber'";
		$dm = "AND mk.id_sumber_dana = '$id_sumber'";
		$d1 = "AND k1.id_sumber_dana = '$id_sumber'";
		$d2 = "AND k2.id_sumber_dana = '$id_sumber'";
		$d3 = "AND k3.id_sumber_dana = '$id_sumber'";
	}else $d = $dm = $d1 = $d2 = $d3 = "";
		
	
	$result = array(); $items = array(); $id_terima = "";
	
	$rs = mysql_query("SELECT a.id_barang, jml_in, saldo, harga, stat, IF(b.id_barang IS NOT NULL, 1, 2) AS j, 
							tgl_transaksi, IFNULL(b.nama_barang, bk.nama_barang_kegiatan) AS nama_barang,
							id_transaksi_detail, IFNULL(b.keterangan, bk.keterangan) AS merk, tahun, 
							IFNULL(tgl_pemeriksaan, tgl_so) tgl_pemeriksaan, 
							IFNULL(no_ba_pemeriksaan, no_so) no_ba_pemeriksaan, tgl_pengadaan, no_kontrak
						FROM (
						   SELECT k1.id_barang, 0 AS jml_in, 0 AS harga, SUM(jml_in-jml_out) AS saldo, 'a' AS stat, 
						    k1.tgl_transaksi, k1.create_date, k1.id_transaksi_detail, k1.id_transaksi
							FROM kartu_stok k1 WHERE k1.soft_delete = 0 $a1 $b1 $c1 $d1 GROUP BY k1.id_barang
						   UNION ALL
						   SELECT k2.id_barang, k2.jml_in, harga, (SELECT IFNULL(SUM(jml_in-jml_out), 0) 
						    FROM kartu_stok k3 WHERE k3.id_barang = k2.id_barang AND k3.uuid_skpd = k2.uuid_skpd 
							AND k3.soft_delete = 0 AND k3.kode <> 'm' $a3 $b3 $c3 $d3) AS saldo, 
							'b' AS stat, k2.tgl_transaksi, k2.create_date, k2.id_transaksi_detail, k2.id_transaksi
						   FROM kartu_stok k2 WHERE k2.soft_delete = 0 $a2 $b2 $c2 $d2 
								AND k2.jml_in <> 0 AND k2.kode <> 'm'
						) AS a
						LEFT JOIN masuk_detail md ON md.id_masuk_detail = a.id_transaksi_detail 
						LEFT JOIN masuk m ON m.id_masuk = a.id_transaksi 
						LEFT JOIN so o ON o.id_so = a.id_transaksi 
						LEFT JOIN ref_barang b ON b.id_barang = a.id_barang 
						LEFT JOIN ref_barang_kegiatan bk ON bk.id_barang_kegiatan = a.id_barang
						LEFT JOIN ref_jenis s1 ON s1.id_jenis = b.id_jenis
						LEFT JOIN ref_jenis s2 ON s2.id_jenis = bk.id_jenis
						ORDER BY j, s1.kd_kel, s2.kd_kel, s1.kd_sub, s2.kd_sub, b.kd_sub2, bk.kode, a.stat, a.tgl_transaksi, a.create_date");
	$r = mysql_num_rows($rs);
	$result["total"] = $r; $id_barang = "";					
	while($b = mysql_fetch_assoc($rs)){
		$row['nama_barang'] = $b['nama_barang'];
		if($id_barang!=$b['id_barang']) $saldo = $b['saldo'];
		if($b['stat']=='b'){ // BARANG MASUK
			$row['jml_keluar'] = '';
			$row['tgl_keluar'] = '';
			$row['kepada'] = '';
			$row['tglno_surat'] = '';
			
			$row['jml_terima'] = number_format($b['jml_in'], 0, ',', '.');
			$row['tgl_terima'] = balikTanggalIndo($b['tgl_transaksi']);
			$row['tgl_periksa'] = balikTanggalIndo($b['tgl_pemeriksaan']);
			$row['no_periksa'] = $b['no_ba_pemeriksaan'];
			$row['hrg_terima'] = number_format($b['harga'], 0, ',', '.');
			$row['hrg_spk'] = " $b[no_kontrak] / ".balikTanggalIndo($b['tgl_pengadaan'])."\n ".number_format($b['harga'], 0, ',', '.');
			$row['merk'] = $b['merk'];
			$row['tahun'] = $b['tahun'];
		
			$keluar = mysql_query("SELECT jml_barang AS jml_keluar, tgl_terima, tgl_ba_out AS tgl_surat, 
										no_ba_out AS no_surat, IF(jenis_out='s', nm_sub2_unit, peruntukan) AS kepada 
						FROM keluar_detail kd 
						LEFT JOIN keluar k ON k.id_keluar = kd.id_keluar
						LEFT JOIN ref_sub2_unit s ON k.uuid_untuk = s.uuid_sub2_unit
						WHERE kd.id_barang = '$b[id_barang]' $a $bs $c $d
						AND kd.soft_delete = 0 AND id_terima_detail = '$b[id_transaksi_detail]'");
			$numros = mysql_num_rows($keluar);
			if($numros!=0){
				$no = 1;
				while($k = mysql_fetch_assoc($keluar)){
					$row['jml_keluar'] = $k['jml_keluar'];
					$row['tgl_keluar'] = balikTanggalIndo($k['tgl_terima']);
					$row['kepada'] = $k['kepada'];
					$tgl_surat = balikTanggalIndo($k['tgl_surat']);
					if($k['tgl_surat']!='' && $k['no_surat']!='') $row['tglno_surat'] = "$tgl_surat / $k[no_surat]";
					else $row['tglno_surat'] = "$tgl_surat $k[no_surat]";
					if($no!=1){
						$row['jml_terima'] = "";
						$row['tgl_terima'] = "";
						$row['tgl_periksa'] = "";
						$row['no_periksa'] = "";
						$row['hrg_terima'] = "";
						$row['hrg_spk'] = "";
						$row['merk'] = "";
						$row['tahun'] = "";
						$b['jml_in'] = 0;
					}
					//$row['ket'] = "$saldo + $b[jml_in] - $k[jml_keluar]";
					$saldo = $saldo + $b['jml_in'] - $k['jml_keluar'];
					//$row['ket'] = $saldo == 0 ? "" : "sisa $saldo";
					$row['ket'] = $saldo == 0 ? "" : "";
					
					if($no!=$numros) array_push($items, $row);
					$no++;
				}
			}else{
				//$row['ket'] = "$saldo + $b[jml_in]";
				$saldo = $saldo + $b['jml_in'];
				//$row['ket'] = $saldo == 0 ? "" : "sisa $saldo";
				$row['ket'] = $saldo == 0 ? "" : "";
			}
			
			array_push($items, $row);
		}else{
			$row['tgl_terima'] = "sisa bln lalu ".number_format($b['saldo'], 0, ',', '.');
			$keluar = mysql_query("SELECT jml_barang AS jml_keluar, tgl_terima, tgl_ba_out AS tgl_surat, 
										no_ba_out AS no_surat, IF(jenis_out='s', nm_sub2_unit, peruntukan) AS kepada 
						FROM keluar_detail kd 
						LEFT JOIN keluar k ON k.id_keluar = kd.id_keluar
						LEFT JOIN ref_sub2_unit s ON k.uuid_untuk = s.uuid_sub2_unit
						WHERE kd.id_barang = '$b[id_barang]' $a $bs $c $d
						AND kd.soft_delete = 0 AND id_terima_detail IN (SELECT id_transaksi_detail FROM kartu_stok mk 
						WHERE mk.soft_delete = 0 $am $bm $cm $dm AND jml_in <> 0)");
			$numros = mysql_num_rows($keluar);
			
			if($numros==0){
				$keluar = mysql_query("SELECT jml_out AS jml_keluar, tgl_transaksi AS tgl_terima, 
							'Stok Opname' AS kepada, tgl_so AS tgl_surat, no_so AS no_surat
						FROM kartu_stok kd 
						LEFT JOIN so o ON o.id_so = kd.id_transaksi AND kd.harga = '$b[harga]'
						WHERE kd.id_barang = '$b[id_barang]' AND jml_out <> 0
						AND kd.soft_delete = 0 AND kd.kode = 's'");
				$numros = mysql_num_rows($keluar);
			}	
			if($numros!=0){
				$row['jml_terima'] = "";
				$row['tgl_periksa'] = "";
				$row['no_periksa'] = "";
				$row['hrg_terima'] = "";
				$row['hrg_spk'] = "";
				$row['merk'] = "";
				$row['tahun'] = "";
				$no = 1;
				while($k = mysql_fetch_assoc($keluar)){
					$row['jml_keluar'] = $k['jml_keluar'];
					$row['tgl_keluar'] = balikTanggalIndo($k['tgl_terima']);
					$row['kepada'] = $k['kepada'];
					$tgl_surat = balikTanggalIndo($k['tgl_surat']);
					if($k['tgl_surat']!='' && $k['no_surat']!='') $row['tglno_surat'] = "$tgl_surat / $k[no_surat]";
					else $row['tglno_surat'] = "$tgl_surat $k[no_surat]";
					
					//$row['ket'] = "$saldo + $b[jml_in] - $k[jml_keluar]";
					$saldo = $saldo + $b['jml_in'] - $k['jml_keluar'];
					//$row['ket'] = $saldo == 0 ? "" : "sisa $saldo";
					$row['ket'] = $saldo == 0 ? "" : " ";
					if($no!=1) $row['tgl_terima'] = "";
					if($no!=$numros) array_push($items, $row);
					$no++;
				}	
				array_push($items, $row);
			}
			//array_push($items, $row);
		}	
		$id_barang = $b['id_barang'];
		
		
	}
	
	$result["rows"] = $items;
	echo json_encode($result);
	mysql_close();
?>