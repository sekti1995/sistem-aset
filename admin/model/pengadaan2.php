<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";
	error_reporting(E_ALL); ini_set('display_errors', 'on'); 

	
	$peran = cekLogin();
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10000;
	$sp = isset($_GET['sp']) ? $_GET['sp'] : '';
	$idsub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
	$ta = isset($_POST['ta']) ? $_POST['ta'] : date("Y");
	$nama = isset($_POST['nama']) ? $_POST['nama'] : '';
	$awal = isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : date('d-m-Y');
	$akhir = isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : date('d-m-Y');
	$penyedia = isset($_POST['penyedia']) ? $_POST['penyedia'] : '';
	$kontrak = isset($_POST['kontrak']) ? $_POST['kontrak'] : '';
	$status = isset($_POST['status']) ? $_POST['status'] : '';
	
	$cek_kunci = mysql_num_rows(mysql_query("SELECT * FROM kunci_entri_skpd WHERE kd_skpd = '$_SESSION[kode_sub]' "));
	if($cek_kunci>0){
		$kunci = 1;
	} else {
		$kunci = 0;
	}
	
	$tgl_kunci = mysql_fetch_assoc(mysql_query("SELECT * FROM kunci_entri WHERE id_kunci = '1' "));
	
	
	if($_SESSION['level']==md5('a')) $id_sub = " AND MD5(CONCAT_WS('.',u.kd_urusan,u.kd_bidang,u.kd_unit)) = '$_SESSION[peserta]'";
	elseif($_SESSION['level']==md5('b')) $id_sub = " AND MD5(CONCAT_WS('.',u.kd_urusan,u.kd_bidang,u.kd_unit,u.kd_sub)) = '$_SESSION[peserta]'";
	elseif($_SESSION['level']==md5('c')) $id_sub = " AND MD5(u.uuid_sub2_unit) = '$_SESSION[uidunit]'";
	else $id_sub = "";
	
	if($idsub!="") $a = " AND m.uuid_skpd = '$idsub'";
	else $a = "";
	if($ta!="") $b = " AND m.ta = '$ta'";
	else $b = "";
	if($nama!="") $c = " AND nama_pengadaan LIKE '%$nama%' ";
	else $c = "";
	if($awal!="" && $akhir!="") $d = " AND DATE_FORMAT(tgl_pengadaan, '%Y-%m-%d') BETWEEN '".balikTanggal($awal)."' AND '".balikTanggal($akhir)."' ";
	else $d = "";
	if($penyedia!="") $e = " AND nama_penyedia LIKE '%$penyedia%' ";
	else $e = "";
	if($kontrak!="") $f = " AND no_kontrak LIKE '%$kontrak%' ";
	else $f = "";
	if($sp!="") $g = " AND status_proses >= '$sp' ";
	else $g = "";
	if($status!="") $h = " AND status_proses = '$status' ";
	else $h = "";
	
	$where = "$a $b $c $d $e $f $g $h";
	$offset = ($page-1)*$rows;
	$result = array();
	/* $clause = "SELECT m.id_masuk, nm_sub2_unit AS unit_kerja, ta, nama_pengadaan, tgl_pengadaan, nama_penyedia, no_kontrak, uuid_skpd AS id_sub, 
				kd_prog, id_prog, kd_keg, kd_rek_1, kd_rek_2, kd_rek_3, kd_rek_4, kd_rek_5, no_rinc, id_sumber, id_gudang AS id_gud,
				tgl_pembayaran, no_pembayaran, id_masuk AS id, status_proses AS sp, tgl_pemeriksaan, no_ba_pemeriksaan,
				IF(status_proses = 1, 'Pengadaan', IF(status_proses = 2, 'Pemeriksaan', IF(status_proses = 3, 'Penerimaan',''))) AS stat,
				no_ba_penerimaan, tgl_penerimaan, no_dok_penerimaan, tgl_dok_penerimaan, status_proses AS sp,
				CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit, kd_sub, kd_sub2) AS kd_awal, kd_skpd
				FROM masuk m
				LEFT JOIN ref_sub2_unit u
				ON uuid_skpd = uuid_sub2_unit
				WHERE soft_delete=0 $where $id_sub
				ORDER BY tgl_pengadaan ASC "; */
	$clause = "SELECT m.id_masuk,d.id_barang AS id_bar, nm_sub2_unit AS unit_kerja, m.ta, nama_pengadaan, tgl_pengadaan, nama_penyedia, no_kontrak, m.uuid_skpd AS id_sub, 
	kd_prog, id_prog, kd_keg, kd_rek_1, kd_rek_2, kd_rek_3, kd_rek_4, kd_rek_5, no_rinc, id_sumber, id_gudang AS id_gud, 
	tgl_pembayaran, no_pembayaran, m.id_masuk AS id, status_proses AS sp,
	 tgl_pemeriksaan, no_ba_pemeriksaan,
	  IF(status_proses = 1, 'Pengadaan', IF(status_proses = 2, 'Pemeriksaan', IF(status_proses = 3, 'Penerimaan',''))) AS stat, no_ba_penerimaan, 
	  tgl_penerimaan, no_dok_penerimaan, tgl_dok_penerimaan, status_proses AS sp, CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit, kd_sub, kd_sub2) AS kd_awal,
	   kd_skpd FROM masuk m 
	   LEFT JOIN ref_sub2_unit u ON uuid_skpd = uuid_sub2_unit
	   LEFT JOIN masuk_detail d ON m.id_masuk = d.id_masuk  WHERE m.soft_delete=0 $where $id_sub
	   GROUP BY id_masuk
	ORDER BY tgl_pengadaan ASC ";
	//echo($clause);
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$rs = mysql_query("$clause LIMIT $rows OFFSET $offset ");
	$items = array(); 
	$ttotal = 0; 
	$footer = array();
	while($row = mysql_fetch_assoc($rs)){
		$clauseT = "SELECT ifnull(sum(jml_masuk*harga_masuk),0) as nilai_kontrak from masuk_detail where id_masuk = '$row[id_masuk]'";
		$rsT = mysql_query($clauseT);
		$rowT = mysql_fetch_assoc($rsT);
		
		$clauseT2 = "SELECT * from masuk_detail where id_masuk = '$row[id_masuk]'";
		$rsT2 = mysql_query($clauseT2);
		$rowT2 = mysql_fetch_assoc($rsT2);

        $clauseT3 = "SELECT DISTINCT id_sumber_dana FROM log_import WHERE  uuid_skpd='$row[id_sub]' AND nm_kegiatan LIKE '$row[nama_pengadaan]'
       AND id_barang='$row[id_bar]' ";
       //echo($clauseT3);
		$rsT3 = mysql_query($clauseT3);
		$rowT3 = mysql_fetch_assoc($rsT3);
		
		if($kunci == 1){
			if(strtotime($row['tgl_penerimaan']) >= strtotime($tgl_kunci["tgl_mulai"]) && strtotime($row['tgl_penerimaan']) <= strtotime($tgl_kunci["tgl_sampai"])){
				$row["lock"] = "0";
			} else {
				$row["lock"] = "1";
			}
		} else {
			$row["lock"] = "0";
		}
		
		$row['id_bar'] = $row['id_bar'];
		$row["tgl_kunci_sampai"] = balikTanggalIndo($tgl_kunci["tgl_sampai"]);
		$row['nilai_kontrak'] = number_format($rowT['nilai_kontrak'], 2, ',', '.');
		$row['tanggal'] = balikTanggalIndo($row['tgl_pengadaan']);
		$row['tgl_pengadaan'] = balikTanggalIndo($row['tgl_pengadaan']);
		$row['tgl_pembayaran'] = balikTanggalIndo($row['tgl_pembayaran']);
		$row['tgl_pemeriksaan'] = balikTanggalIndo($row['tgl_pemeriksaan']);
		$row['tgl_penerimaan'] = balikTanggalIndo($row['tgl_penerimaan']);
		$row['tgl_dok_penerimaan'] = balikTanggalIndo($row['tgl_dok_penerimaan']);
		$row['id_kelompok'] = $rowT2['id_kelompok'];
		$row['id_rekening'] = $rowT2['id_rek'];
		$row['id_sub_rekening'] = $rowT2['id_subrek'];
        $row['id2'] = is_null($rowT3['id_sumber_dana']) ? '' : $rowT3['id_sumber_dana'];
		//$harga = is_null($r2['harga']) ? 0 : $r2['harga'];
		$kode = explode('.', $row['kd_awal']); 
		$kd1 = str_pad($kode[1],2,'0', STR_PAD_LEFT);
		$kd2 = str_pad($kode[2],2,'0', STR_PAD_LEFT);
		$kd3 = str_pad($kode[3],2,'0', STR_PAD_LEFT);
		//$kd4 = str_pad($kode[4],2,'0', STR_PAD_LEFT);
		$row['kd_awal'] = $kode[0].".".$kd1.".".$kd2.".".$kd3;//.".".$kd4; 
		$row['id_gudang'] = $row['id_gud'];
		$ttotal += $rowT['nilai_kontrak'];
		array_push($items, $row);
	}
	$result["rows"] = $items;
	
	$foot['no_kontrak'] = 'TOTAL'; 
	$foot['nilai_kontrak'] = number_format($ttotal, 2, ',', '.'); 
	array_push($footer, $foot);
	$result["footer"] = $footer;
	
	echo json_encode($result);
	mysql_close();
?>