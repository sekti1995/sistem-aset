<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";
	
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$id_sub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
	$id_sumber = isset($_POST['id_sumber']) ? $_POST['id_sumber'] : '';
	$ta = isset($_POST['ta']) ? $_POST['ta'] : date('Y');
	$bln = isset($_POST['bln']) ? str_pad($_POST['bln'],2,'0', STR_PAD_LEFT) : str_pad(date('m'),2,'0', STR_PAD_LEFT);
	
	
	if($ta!="") $a = " AND u.ta = '$ta'"; else $a = "";
	if($id_sub!="") $b = " AND u.uuid_skpd = '$id_sub'";
	else $b = " AND MD5(u.uuid_skpd) = '$_SESSION[uidunit]'";	
	if($_SESSION['peran_id']==MD5('1')) $b .= "";
	else{
		if($_SESSION['level']==MD5('a')) $b .= " AND MD5(CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit)) = '$_SESSION[peserta]'";
		elseif($_SESSION['level']==MD5('b')) $b .= "  AND MD5(CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit, kd_sub)) = '$_SESSION[peserta]'";
	}	
	if($bln!="" and $bln!="00") $c = " AND DATE_FORMAT(tgl_terima, '%m') = '$bln'"; else $c = "";
	if($id_sumber!="") $d = " AND id_sumber = '$id_sumber'"; else $d = "";
	
	$where = " $a $b $c $d";
	$offset = ($page-1)*$rows;
	$result = array();
	/* $clause = "SELECT nama_barang, jml_masuk, harga_masuk, nm_sub2_unit AS unit, tgl_penerimaan AS tgl_ba, no_ba_penerimaan AS no_ba, 
				tgl_dok_penerimaan AS tgl_dok, no_dok_penerimaan AS no_dok, nama_penyedia AS dari
				FROM masuk_detail d
				LEFT JOIN ref_sub2_unit u ON d.uuid_skpd = uuid_sub2_unit
				LEFT JOIN masuk m ON m.id_masuk = d.id_masuk
				LEFT JOIN ref_barang b ON d.id_barang = b.id_barang 
				WHERE d.soft_delete=0 AND m.status_proses = 3 $where";
	
	$clause = "SELECT nama_barang, jml_in AS jml_masuk, harga AS harga_masuk, nm_sub2_unit AS unit, 
				DATE_FORMAT(tgl_transaksi, '%d-%m-%Y') AS tgl_terima, kode, id_transaksi
				FROM kartu_stok k
				LEFT JOIN ref_sub2_unit u ON k.uuid_skpd = uuid_sub2_unit
				LEFT JOIN ref_barang b ON k.id_barang = b.id_barang 
				WHERE k.soft_delete = 0 AND jml_in > 0 AND kode IN ('i','r') $where"; */
	
	$clause = "SELECT tgl_terima, dari, no_dok, tgl_dok, no_ba, tgl_ba, jml_masuk, harga_masuk, nm_sub2_unit AS unit, 
					IFNULL(nama_barang, nama_barang_kegiatan) AS nama_barang
				FROM ( SELECT
						id_barang,
						jml_masuk,
						harga_masuk,
						d.uuid_skpd,
						tgl_penerimaan     AS tgl_terima,
						tgl_penerimaan     AS tgl_ba,
						no_ba_penerimaan   AS no_ba,
						tgl_dok_penerimaan AS tgl_dok,
						no_dok_penerimaan  AS no_dok,
						nama_penyedia      AS dari,
						m.id_sumber,
						m.ta
					  FROM masuk_detail d
						LEFT JOIN masuk m
						  ON m.id_masuk = d.id_masuk
					  WHERE d.soft_delete = 0
						  AND m.status_proses = 3 
					  UNION SELECT
							  id_barang,
							  jml_barang          AS jml_masuk,
							  harga_barang        AS harga_masuk,
							  td.uuid_skpd,
							  tgl_terima,
							  tgl_ba_out          AS tgl_ba,
							  no_ba_out           AS no_ba,
							  ''                  AS tgl_dok,
							  ''                  AS no_dok,
							  nm_sub2_unit        AS dari,
							  td.id_sumber_dana   AS id_sumber,
							  t.ta
							FROM terima_keluar_detail td
							  LEFT JOIN terima_keluar t
								ON td.id_terima_keluar = t.id_terima_keluar
							  LEFT JOIN keluar k
								ON k.id_keluar = t.id_keluar
							  LEFT JOIN ref_sub2_unit s2
								ON s2.uuid_sub2_unit = k.uuid_skpd
							WHERE t.soft_delete = 0
				) AS u
			  LEFT JOIN ref_sub2_unit s
				ON s.uuid_sub2_unit = u.uuid_skpd
			  LEFT JOIN ref_barang b
				ON b.id_barang = u.id_barang
			  LEFT JOIN ref_barang_kegiatan bk
				ON bk.id_barang_kegiatan = u.id_barang
			  WHERE u.id_barang IS NOT NULL $where";
	
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	//$rs = mysql_query("$clause LIMIT $rows OFFSET $offset ");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		$harga = $row['harga_masuk'] * $row['jml_masuk'];
		$row['tgl_terima'] = balikTanggalIndo($row['tgl_terima']);
		$row['tgl_ba'] = balikTanggalIndo($row['tgl_ba']);
		$row['tgl_dok'] = balikTanggalIndo($row['tgl_dok']);
		$row['jml_barang'] = number_format($row['jml_masuk'], 0, ',', '.')." ";
		$row['hrg_barang'] = number_format($row['harga_masuk'], 0, ',', '.');
		$row['tot_harga'] = number_format($harga, 0, ',', '.');
		
		array_push($items, $row);
	}
	$result["rows"] = $items;
	echo json_encode($result);
	mysql_close();
?>