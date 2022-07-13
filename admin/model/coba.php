<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";
	
	
	$cuk = "X";
	
	$peran = cekLogin(); 
	$id_sub = isset($_POST['id_sub']) ? $_POST['id_sub'] : 'cfa4f7e0-5543-11e6-a2df-000476f4fa98';
	$id_sum = isset($_POST['id_sum']) ? $_POST['id_sum'] : '28';
	$tglawal = isset($_POST['tglawal']) ? $_POST['tglawal'] : '01-05-2020';
	$tglakhir = isset($_POST['tglakhir']) ? $_POST['tglakhir'] : '31-06-2020';
	$ta = isset($_POST['ta']) ? $_POST['ta'] : date('Y');
	$smstr = isset($_POST['smstr']) ? $_POST['smstr'] : '';
	$akses = isset($_POST['akses']) ? $_POST['akses'] : '3';
	
	$tglawal = balikTanggal($tglawal);
	$tglakhir = balikTanggal($tglakhir);
	
	$kode = mysql_fetch_assoc(mysql_query("SELECT * FROM ref_sub2_unit WHERE uuid_sub2_unit = '$id_sub' "));
	
	if($akses == 2){
		$e1 = "	AND t4.kd_urusan = '$kode[kd_urusan]'
				AND t4.kd_bidang = '$kode[kd_bidang]'
				AND t4.kd_unit = '$kode[kd_unit]' ";
		$filto = "	AND kd_urusan = '$kode[kd_urusan]'
				AND kd_bidang = '$kode[kd_bidang]'
				AND kd_unit = '$kode[kd_unit]' ";
				
	} else if($akses == 3){
		$e1 = "	AND t4.kd_urusan = '$kode[kd_urusan]'
				AND t4.kd_bidang = '$kode[kd_bidang]'
				AND t4.kd_unit = '$kode[kd_unit]'
				AND t4.kd_sub = '$kode[kd_sub]' ";
		$filto = "	AND kd_urusan = '$kode[kd_urusan]'
				AND kd_bidang = '$kode[kd_bidang]'
				AND kd_unit = '$kode[kd_unit]'
				AND kd_sub = '$kode[kd_sub]'";
				
	} else if($akses == 4){
		$e1 = "	AND t4.kd_urusan = '$kode[kd_urusan]'
				AND t4.kd_bidang = '$kode[kd_bidang]'
				AND t4.kd_unit = '$kode[kd_unit]'
				AND t4.kd_sub = '$kode[kd_sub]'
				AND t4.kd_sub2 = '$kode[kd_sub2]' ";
		$filto = "	AND kd_urusan = '$kode[kd_urusan]'
				AND kd_bidang = '$kode[kd_bidang]'
				AND kd_unit = '$kode[kd_unit]'
				AND kd_sub = '$kode[kd_sub]'
				AND kd_sub2 = '$kode[kd_sub2]' ";
	} else if($akses == 5){
		$e1 = "	AND t1.uuid_skpd = '$id_sub' ";
		$filto = "	AND uuid_skpd = '$id_sub' ";
	} else {
		$id_sub = $_SESSION["uidunit_plain"];
		$e1 = "	AND t1.uuid_skpd = '$_SESSION[uidunit_plain]' ";
		$filto = "	AND uuid_skpd = '$_SESSION[uidunit_plain]' ";
	}
	
	if($id_sub!=""){
		$wh = "WHERE uuid_sub2_unit = '$id_sub'";
		$sub = "AND uuid_skpd = '$id_sub'";
	}else{
		$wh = "WHERE MD5(uuid_sub2_unit) = '$_SESSION[uidunit]'";
		$sub = "AND MD5(uuid_skpd) = '$_SESSION[uidunit]'";
	}

	if($id_sum!=""){
		$idsum = " ";
	}else{
		$idsum = "";
	}
	if($ta=="") $ta = date('Y');
	
	
	$qsd = mysql_query("SELECT * FROM ref_sumber_dana");
	$jml_sd = mysql_num_rows($qsd);
	
	$bl = " AND DATE_FORMAT(t1.tgl_transaksi, '%Y-%m-%d') BETWEEN '$tglawal' AND '$tglakhir'";
	$bl_lalu = " AND DATE_FORMAT(t1.tgl_transaksi, '%Y-%m-%d') < '$tglawal' ";
	
	
	$clause = "SELECT id_jenis, nama_jenis AS nama
				FROM ref_jenis j 
				WHERE kd_sub <> 0
				ORDER BY j.kd_kel, j.kd_sub";
	$rs = mysql_query($clause);
	
	$a1 = array();
	$a2 = array();
	$a3 = array();
	$a4 = array();
	$a5 = array();
	$a6 = array();
	$a7 = array();
	$a8 = array();
	// echo $filto;
	while($row = mysql_fetch_assoc($rs)){
		$t = 0;
		
				// echo "<Br>".$row["nama"]."<br>";
		
			$opd = mysql_query("SELECT * FROM ref_sub2_unit WHERE uuid_sub2_unit IS NOT NULL $filto LIMIT 1 ");
			while($ropd = mysql_fetch_assoc($opd)){
				// echo "<Br>".$ropd["nm_sub2_unit"]."<br>";
				$qsd = mysql_query("SELECT * FROM ref_sumber_dana WHERE id_sumber >= 28 AND id_sumber <= 35 ORDER BY id_sumber ASC");
				while($rrr=mysql_fetch_assoc($qsd)){
				$a1 = array();
					// echo "<br>".$rrr["nama_sumber"]." ";
					$in_lalu = mysql_fetch_assoc(mysql_query("SELECT SUM(t1.jml_in*t1.harga) AS ttl 
								FROM kartu_stok t1 LEFT JOIN ref_barang t2
								ON t1.id_barang = t2.id_barang
								LEFT JOIN ref_jenis t3
								ON t2.id_jenis = t3.id_jenis
							WHERE 
								t1.id_sumber_dana = '$rrr[id_sumber]' AND t3.id_jenis = '$row[id_jenis]' AND jml_in > 0 AND t1.soft_delete = '0' AND t1.uuid_skpd = '$ropd[uuid_sub2_unit]' $bl_lalu "));
								
					
					$out_lalu = mysql_fetch_assoc(mysql_query("SELECT SUM(jml_out*harga) AS ttl 
								FROM kartu_stok t1 LEFT JOIN ref_barang t2
								ON t1.id_barang = t2.id_barang
								LEFT JOIN ref_jenis t3
								ON t2.id_jenis = t3.id_jenis
								LEFT JOIN ref_sub2_unit t4 ON t1.uuid_skpd = t4.uuid_sub2_unit
							WHERE 
								t1.id_sumber_dana = '$rrr[id_sumber]' AND t3.id_jenis = '$row[id_jenis]' AND jml_out > 0 AND t1.soft_delete = '0' AND t1.uuid_skpd = '$ropd[uuid_sub2_unit]' $bl_lalu "));
					
				
				
					$in = mysql_fetch_assoc(mysql_query("SELECT SUM(t1.jml_in*t1.harga) AS ttl 
								FROM kartu_stok t1 LEFT JOIN ref_barang t2
								ON t1.id_barang = t2.id_barang
								LEFT JOIN ref_jenis t3
								ON t2.id_jenis = t3.id_jenis
								LEFT JOIN ref_sub2_unit t4 ON t1.uuid_skpd = t4.uuid_sub2_unit
							WHERE 
								t1.id_sumber_dana = '$rrr[id_sumber]' AND t3.id_jenis = '$rowz[id_jenis]' AND jml_in > 0 AND t1.soft_delete = '0' AND t1.uuid_skpd = '$ropd[uuid_sub2_unit]' $bl"));
				
				
					$out = mysql_fetch_assoc(mysql_query("SELECT SUM(jml_out*harga) AS ttl 
								FROM kartu_stok t1 LEFT JOIN ref_barang t2
								ON t1.id_barang = t2.id_barang
								LEFT JOIN ref_jenis t3
								ON t2.id_jenis = t3.id_jenis
								LEFT JOIN ref_sub2_unit t4 ON t1.uuid_skpd = t4.uuid_sub2_unit
							WHERE 
								t1.id_sumber_dana = '$rrr[id_sumber]' AND t3.id_jenis = '$rowz[id_jenis]' AND jml_out > 0 AND t1.soft_delete = '0' AND t1.uuid_skpd = '$ropd[uuid_sub2_unit]' $bl"));
				
				
				
					$sisa_lalu = $in_lalu["ttl"]-$out_lalu["ttl"] ;
					// $row["saldo_awal"] = $sisa_lalu;
					$saldo = $in["ttl"]-$out["ttl"]+$sisa_lalu;
					
					$row["sd"] = $rrr["nama_sumber"];
					$row["opd"] = $ropd["nm_sub2_unit"];
					$row["id_sumber"] = $rrr["id_sumber"];
					if($rrr["id_sumber"] == 28){
						$row["C"] = $sisa_lalu;
						$row["K"] = $sisa_lalu;
						$row["S"] = $sisa_lalu;
						$row["AA"] = $sisa_lalu;
					}else if($rrr["id_sumber"] == 30){
						$row["D"] = $sisa_lalu;
						$row["L"] = $sisa_lalu;
						$row["T"] = $sisa_lalu;
						$row["AB"] = $sisa_lalu;
					}else if($rrr["id_sumber"] == 31){
						$row["E"] = $sisa_lalu;
						$row["M"] = $sisa_lalu;
						$row["U"] = $sisa_lalu;
						$row["AC"] = $sisa_lalu;
					}else if($rrr["id_sumber"] == 32){
						$row["F"] = $sisa_lalu;
						$row["N"] = $sisa_lalu;
						$row["V"] = $sisa_lalu;
						$row["AD"] = $sisa_lalu;
					}else if($rrr["id_sumber"] == 33){
						$row["G"] = $sisa_lalu;
						$row["O"] = $sisa_lalu;
						$row["W"] = $sisa_lalu;
						$row["AE"] = $sisa_lalu;
					}else if($rrr["id_sumber"] == 34){
						$row["H"] = $sisa_lalu;
						$row["P"] = $sisa_lalu;
						$row["X"] = $sisa_lalu;
						$row["AF"] = $sisa_lalu;
					}else if($rrr["id_sumber"] == 35){
						$row["I"] = $sisa_lalu;
						$row["Q"] = $sisa_lalu;
						$row["Y"] = $sisa_lalu;
						$row["AG"] = $sisa_lalu;
					}
					
					// echo $row["saldo_awal"]." | ".$in["ttl"]." | ".$out["ttl"]." | ".$saldo." ";
					
					
					// $t += $sisa_lalu;
					
					array_push($a1, $row);
				
				}
				foreach($a1 AS $r){
					// print_r($r);
					echo "<br>";
					ECHO $r["C"];
					// if((float)$r[2] <= 0){
						// $r[2] = " -";
					// }
					
					// if($aaa != $r[0]){
						// $aaa = $r[0];
						
						// $barisnya++;
						// $i = 2;
						 
						// $objPHPExcel->getActiveSheet()
							// ->setCellValue($arr_cell[$i].$barisnya, $r[2]);
					// } else {
						// $objPHPExcel->getActiveSheet()
							// ->setCellValue($arr_cell[$i].$barisnya, $r[2]);
					// }
					$i++;
				}
			}
			
	}
	
	$aaa = "a";
	// echo "<table border=1>
	// <tr>
	// <td>No Rek</td>
	// <td>Jenis Persediaan</td>
	// <td colspan=".$jml_sd.">Saldo Awal</td>
	// <td colspan=".$jml_sd.">Penambahan</td>
	// <td colspan=".$jml_sd.">Pengurangan</td>
	// <td colspan=".$jml_sd.">Saldo Akhir</td>
	// </tr>
	// <tr>";
	// foreach($a1 AS $r){
		// if($aaa != $r[0]){
			// $aaa = $r[0];
			// echo "</tr><tr><td>".$r[0]." ".$r[1]."<br>".$r[2]."</td>";
		// } else {
			// echo "<td>".$r[0]." ".$r[1]."<br>".$r[2]."</td>";
		// }
	// }