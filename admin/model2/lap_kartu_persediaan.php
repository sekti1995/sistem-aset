<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";
	
	$peran = cekLogin();
	$idgud = isset($_POST['idgud']) ? $_POST['idgud'] : '';
	$idbar = isset($_POST['idbar']) ? $_POST['idbar'] : '';
	$idsum = isset($_POST['idsum']) ? $_POST['idsum'] : '';
	$ta = isset($_POST['ta']) ? $_POST['ta'] : '';
	$bln = isset($_POST['bln']) ? str_pad($_POST['bln'],2,'0', STR_PAD_LEFT) : '';
	
	$a = " AND id_gudang = '$idgud'";
	$b = " AND id_barang = '$idbar'";
	if($ta!="") {$c = " AND ta = '$ta' ";$c2 = " AND ta <= '$ta' ";}
	else {$c = "";$c2="";}
	if($idsum!="") $d = " AND id_sumber_dana = '$idsum' ";
	else $d = "";
	if($bln!="" AND $bln!="00") {$e = " AND DATE_FORMAT(tgl_transaksi, '%m') = '$bln'";  $e2 = " AND DATE_FORMAT(tgl_transaksi, '%Y-%m') < '$ta-$bln'"; }
	else {$e = "";$e2="";}
	$items = array(); $saldo = 0; $sisa = 0; $sisa_saldo = 0;
	
	
	$where = "$a $b $c $d $e";
	$where2 = "$a $b $c2 $d $e2";
	$sq = mysql_query(" SELECT * FROM kartu_stok WHERE soft_delete = 0 $where2 ");
	$cek_jml = mysql_num_rows($sq);
	$nom=1;
	while($s = mysql_fetch_assoc($sq)){
		$row['tanggal'] = balikTanggalIndo($s['tgl_transaksi']);
		$sisa_saldo += $s['jml_in'] - $s['jml_out'];
		if($s['kode']=='i'){  
			$row['uraian'] = "Saldo Lalu";
			$row['jml_masuk'] = number_format($s['jml_in'], 0, ',', '.');
			$row['jml_keluar'] = number_format($s['jml_out'], 0, ',', '.');
			$row['hrg_masuk'] = number_format($s['harga'], 0, ',', '.');
		} else if($s['kode']=='ok'){ 
			$row['jml_masuk'] = number_format($s['jml_in'], 0, ',', '.');
			$row['jml_keluar'] = number_format($s['jml_out'], 0, ',', '.');
			$row['hrg_masuk'] = number_format($s['harga'], 0, ',', '.');
		} else if($s['kode']=='a'){
			$tglno = mysql_fetch_assoc(mysql_query("SELECT no_ba AS nomor, tgl_ba AS tgl FROM adjust WHERE id_adjust = '$s[id_transaksi]'"));
			$row['uraian'] = "Saldo Lalu";
			$row['jml_masuk'] = number_format($s['jml_in'], 0, ',', '.');
			$row['jml_keluar'] = number_format($s['jml_out'], 0, ',', '.');
			$row['hrg_masuk'] = number_format($s['harga'], 0, ',', '.');
			if($tglno!="") $row['notgl_surat'] = $tglno['nomor']." / ".balikTanggalIndo($tglno['tgl']);
			else $row['notgl_surat'] = '';
		}
		
		
		$bertambah = $row['jml_masuk']*$s['harga'];
		$row['bertambah'] = number_format($bertambah, 0, ',', '.');
		$berkurang = $row['jml_keluar']*$s['harga'];
		$row['berkurang'] = number_format($berkurang, 0, ',', '.');
		$sisa = $sisa + $bertambah - $berkurang;
		$row['sisa'] = number_format($sisa, 0, ',', '.');
		
		$row['saldo'] = $sisa_saldo;
		$row['jml_masuk'] = '0';
		$row['jml_keluar'] = '0';
		$row['bertambah'] = '0';
		$row['berkurang'] = '0';
		$row['notgl_surat'] = '';
		$bln_lalu = $bln-1;
		$ta_lalu = $ta-1;
		$cektgl = $ta.'-'.$bln_lalu.'-01';
		$tgl_terakhir = date('Y-m-t', strtotime($cektgl));
		
		if($bln == '01'){
			$row['uraian'] = "Saldo Bulan Desember $ta_lalu";
		} else if ($bln == '02'){
			$row['uraian'] = "Saldo Bulan Januari";
		}else if ($bln == '03'){
			$row['uraian'] = "Saldo Bulan Februari";
		}else if ($bln == '04'){
			$row['uraian'] = "Saldo Bulan Maret";
		}else if ($bln == '05'){
			$row['uraian'] = "Saldo Bulan April";
		}else if ($bln == '06'){
			$row['uraian'] = "Saldo Bulan Mei";
		}else if ($bln == '07'){
			$row['uraian'] = "Saldo Bulan Juni";
		}else if ($bln == '08'){
			$row['uraian'] = "Saldo Bulan Juli";
		}else if ($bln == '09'){
			$row['uraian'] = "Saldo Bulan Agustus";
		}else if ($bln == '10'){
			$row['uraian'] = "Saldo Bulan September";
		}else if ($bln == '11'){
			$row['uraian'] = "Saldo Bulan Oktober";
		}else if ($bln == '12'){
			$row['uraian'] = "Saldo Bulan November";
		}
		
		$row['tanggal'] = balikTanggalIndo($tgl_terakhir);
		
		if($cek_jml == $nom){
			array_push($items, $row);
		} 
	$nom++;
	}
	
	$result = array();
	$clause = "SELECT tgl_transaksi AS tanggal, jml_in AS jml_masuk, harga AS hrg_masuk, jml_out AS jml_keluar, 
				id_transaksi, id_transaksi_detail, kode
				FROM kartu_stok
				WHERE soft_delete = 0 $where
				ORDER BY tgl_transaksi ASC, create_date ASC";
				
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r; 
	$saldo = 0;
	$saldo = $saldo+$sisa_saldo;
	while($row = mysql_fetch_assoc($rs)){
		if($row['kode']=='i'){ 
			$tglno = mysql_fetch_assoc(mysql_query("SELECT no_dok_penerimaan AS nomor, tgl_dok_penerimaan AS tgl, nama_penyedia
													FROM masuk WHERE id_masuk = '$row[id_transaksi]'"));
			$row['uraian'] = "Pengadaan dari $tglno[nama_penyedia]";										
		}elseif(strpos($row['kode'],'o')!==false){
			$clause = mysql_query("SELECT no_ba_out AS nomor, tgl_ba_out AS tgl, 
									IF(jenis_out='s', nm_sub2_unit, peruntukan ) AS uraian 
									FROM keluar 
									LEFT JOIN ref_sub2_unit ON uuid_sub2_unit = uuid_untuk
									WHERE id_keluar = '$row[id_transaksi]'");
			$tglno = mysql_fetch_assoc($clause);
			if(strpos($row['kode'],'r')!==false) $row['uraian'] = "$tglno[uraian] ke Aset Tetap";
			else $row['uraian'] = "Penyaluran untuk $tglno[uraian]";
		}elseif($row['kode']=='d'){
			$tglno = mysql_fetch_assoc(mysql_query("SELECT no_ba_hapus AS nomor, tgl_ba_hapus AS tgl FROM hapus_barang
														WHERE id_hapus_barang = '$row[id_transaksi]'"));
			$row['uraian'] = "Penghapusan Barang";											
		}elseif($row['kode']=='m'){
			$tglno = mysql_fetch_assoc(mysql_query("SELECT m.no_ba_mutasi AS nomor, m.tgl_ba_mutasi AS tgl, g1.nama_gudang AS nm_asal,
													g2.nama_gudang AS nm_tujuan
													FROM mutasi m
													LEFT JOIN ref_gudang g1 ON g1.id_gudang = m.gudang_asal
													LEFT JOIN ref_gudang g2 ON g2.id_gudang = m.gudang_tujuan
													WHERE m.id_mutasi = '$row[id_transaksi]'"));
			if($row['jml_masuk']>0)	$row['uraian'] = "Mutasi Gudang dari $tglno[nm_asal]";
			else $row['uraian'] = "Mutasi Gudang ke $tglno[nm_tujuan]";
		}elseif($row['kode']=='r'){
			$tglno = mysql_fetch_assoc(mysql_query("SELECT no_ba_out AS nomor, tgl_terima AS tgl, nm_sub2_unit 
														FROM terima_keluar m
														LEFT JOIN keluar k ON k.id_keluar = m.id_keluar
														LEFT JOIN ref_sub2_unit s ON s.uuid_sub2_unit = k.uuid_skpd
														WHERE id_terima_keluar = '$row[id_transaksi]'"));
			$row['uraian'] = "Penyaluran dari $tglno[nm_sub2_unit]";											
		}elseif($row['kode']=='s'){
			$tglno = mysql_fetch_assoc(mysql_query("SELECT no_so AS nomor, tgl_so AS tgl 
														FROM so	WHERE id_so = '$row[id_transaksi]'"));
			$row['uraian'] = "Penyesuaian Stok dengan Fisik";											
		}elseif($row['kode']=='a'){
			$tglno = mysql_fetch_assoc(mysql_query("SELECT no_ba AS nomor, tgl_ba AS tgl FROM adjust 
												WHERE id_adjust = '$row[id_transaksi]'"));
			$row['uraian'] = "Data Awal ";									
		}else $tglno = "";
		//$tglno = mysql_fetch_assoc(mysql_query("$clause"));
		if($tglno!="") $row['notgl_surat'] = $tglno['nomor']." / ".balikTanggalIndo($tglno['tgl']);
		else $row['notgl_surat'] = '';
		$row['tanggal'] = balikTanggalIndo($row['tanggal']);
		//if($row['jml_masuk']==0){ $row['hrg_masuk'] = 0;  $row['tot_masuk'] = 0;  $row['jml_masuk'] = 0;}
		if($row['jml_masuk']==0){ $row['tot_masuk'] = 0;  $row['jml_masuk'] = 0;}
		else $row['tot_masuk'] = $row['jml_masuk'] * $row['hrg_masuk'];
		$saldo += $row['jml_masuk'] - $row['jml_keluar'];
		$bertambah = $row['jml_masuk']*$row['hrg_masuk'];
		$row['bertambah'] = number_format($bertambah, 0, ',', '.');
		$berkurang = $row['jml_keluar']*$row['hrg_masuk'];
		$row['berkurang'] = number_format($berkurang, 0, ',', '.');
		$sisa = $sisa + $bertambah - $berkurang;
		$row['sisa'] = number_format($sisa, 0, ',', '.');
		 
		
		$row['saldo'] = number_format($saldo, 0, ',', '.');
		$row['tot_masuk'] = number_format($row['tot_masuk'], 0, ',', '.');
		$row['hrg_masuk'] = number_format($row['hrg_masuk'], 0, ',', '.');
		$row['jml_masuk'] = number_format($row['jml_masuk'], 0, ',', '.');
		$row['jml_keluar'] = number_format($row['jml_keluar'], 0, ',', '.');
		
		//$row['uraian'] .= "  $row[kode]";
		
		array_push($items, $row);
	}
	$result["rows"] = $items;
	echo json_encode($result);
	mysql_close();
?>