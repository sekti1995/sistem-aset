<?php

function salt($kata){
	$kata = addslashes($kata);
	$res = preg_replace("/[^*;\"\'=<>]/", "", $kata);
	if(strlen($res)>0){
			echo "<script type=\"text/javascript\">
			alert(\"I told you .... Be Angel...!\");
			window.location.href=('../index.php');
			</script>";
			$kata = "";
			return $kata;
	}else{
			return $kata;
	}
}
//MEMBUAT NOMOR TRANSAKSI
function buatNomor($prefix,$pegawai){
	$jam = date("H:i:s");
	$tanggal = date("d");
	$depan = date("m");
	$tengah = date("Y");
	$filterDate = $prefix.$tengah.$depan.$pegawai ;
	if ($prefix=="J") $query=mysql_query("SELECT id_penjualan FROM penjualan 
										WHERE SUBSTRING_INDEX( SUBSTRING_INDEX( id_penjualan,  '-', 3 ) ,  '-', 1 ) =  '$filterDate'
										ORDER BY id_penjualan DESC LIMIT 1");	
	elseif ($prefix=="B") $query=mysql_query("SELECT id_pembelian FROM pembelian 
											WHERE SUBSTRING_INDEX( SUBSTRING_INDEX( id_pembelian,  '-', 3 ) ,  '-', 1 ) =  '$filterDate'
											ORDER BY id_pembelian DESC LIMIT 1");
	elseif ($prefix=="BKK" || $prefix=="BKM" || $prefix=="MK") $query=mysql_query("SELECT kode_transaksi FROM kas 
												WHERE SUBSTRING_INDEX( SUBSTRING_INDEX( kode_transaksi,  '-', 3 ) ,  '-', 1 ) =  '$filterDate'
												ORDER BY id_kas DESC LIMIT 1");
	elseif ($prefix=="MG") $query=mysql_query("SELECT id_mutasi	FROM mutasi_gudang
											WHERE SUBSTRING_INDEX( SUBSTRING_INDEX( id_mutasi,  '-', 3 ) ,  '-', 1 ) =  '$filterDate'
											ORDER BY id_mutasi DESC LIMIT 1");
	elseif ($prefix=="PO") $query=mysql_query("SELECT id_po_pembelian FROM po_pembelian 
											WHERE SUBSTRING_INDEX( SUBSTRING_INDEX( id_po_pembelian,  '-', 3 ) ,  '-', 1 ) =  '$filterDate'
											ORDER BY id_po_pembelian DESC LIMIT 1");
	elseif ($prefix=="AJ") $query=mysql_query("SELECT id_transaksi FROM tb_stok
											WHERE SUBSTRING_INDEX( SUBSTRING_INDEX( id_transaksi,  '-', 3 ) ,  '-', 1 ) =  '$filterDate'
											ORDER BY id_transaksi DESC LIMIT 1");
	elseif ($prefix=="RB") $query=mysql_query("SELECT id_retur_pembelian FROM retur_pembelian 
											WHERE SUBSTRING_INDEX( SUBSTRING_INDEX( id_retur_pembelian,  '-', 3 ) ,  '-', 1 ) =  '$filterDate'	
											ORDER BY id_retur_pembelian DESC LIMIT 1");
	elseif ($prefix=="KRB") $query=mysql_query("SELECT id_kembali_retur_pembelian FROM kembali_retur_pembelian 
									WHERE SUBSTRING_INDEX( SUBSTRING_INDEX( id_kembali_retur_pembelian,  '-', 3 ) ,  '-', 1 ) =  '$filterDate'
										ORDER BY id_kembali_retur_pembelian DESC LIMIT 1");
	elseif ($prefix=="KJ") $query=mysql_query("SELECT id_kirim_penjualan FROM kirim_penjualan 
											WHERE SUBSTRING_INDEX( SUBSTRING_INDEX( id_kirim_penjualan,  '-', 3 ) ,  '-', 1 ) =  '$filterDate'
											ORDER BY id_kirim_penjualan DESC LIMIT 1");
	$dataNomor = mysql_fetch_array($query);
	$nomor = substr($dataNomor[0],-6);
	if ($tanggal=="1" && $jam=="00:00:00") $nom = 1;
	elseif (empty($nomor)) $nom = 1;
	else $nom = $nomor+1;
	$num = str_pad($nom,6,'0', STR_PAD_LEFT);
	$nomor_jadi = $prefix.$tengah.$depan.$pegawai."-".$num;
return $nomor_jadi;
}
function sebulanLalu(){
	$days_ago = date('d-m-Y', strtotime('-1 months', strtotime(date("Y-m-d")))); 
	return $days_ago;
}
//MENEMUKAN NAMA VIA ID
function ambilNama($tabel, $field, $id){
    $namax = 'nama_'.$tabel;
	$query=mysql_query("SELECT $namax FROM $tabel WHERE $field='$id'");
	$nama=mysql_fetch_array($query);
	return $nama[0];
}
function indentasi($angka, $panjang){
	$jumlah=strlen($angka);
	$sisa = $panjang - $jumlah;
	$spasi = "";
	for ($i=0;$i<=$sisa;$i++){
	$spasi = $spasi." ";
	}
	$jadi = $spasi.$angka;
	return $jadi;
}
function spasinya($kolom, $karakter){
		$pKar1 = strlen($karakter);
		$sp1 = $kolom-$pKar1;
		$space = "";
		for($i=0; $i<=$sp1;$i++){
		$space = $space." ";
		}
		return $space;
}
function cariTengah($kolom, $karakter){
		$sp1= floor(($kolom-strlen($karakter))/2);
		$space = "";
		for($i=0; $i<$sp1;$i++){
		$space = $space." ";
		}
		return $space;
}
function cetakNota($data, $header){
	$myF = ambilFieldData("utility", "variabel", "item", "tipe_print");
	$myFontW = ambilFieldData("utility", "variabel", "item", "font size w");
	$myFontH = ambilFieldData("utility", "variabel", "item", "font size h");
 	$p = printer_open($myF);
	$var_magin_left = 5;
	printer_set_option($p, PRINTER_MODE, "RAW"); 
	printer_start_doc($p);
	printer_start_page($p);
	$font = printer_create_font("Arial", $myFontW, $myFontH, PRINTER_FW_NORMAL, false, false, false, 0);
	printer_select_font($p, $font);
	$row = 50;
	$spasi = 18;
	$col =90;
	$tengah = $util['tengah'];
	printer_draw_text($p, ".:: ".nama."  ::.",100,$row);
	printer_draw_text($p, alamat ,$var_magin_left,$row+=$spasi);
	printer_draw_text($p, "Kasir: ".$header[kasir] ,$var_magin_left, $row+=$spasi);
	printer_draw_text($p, $header['nomor']." - ".date("d/m/Y H:i:s") ,$var_magin_left, $row+=$spasi);
	printer_draw_text($p, "=================================", $var_magin_left, $row+=$spasi);
	$row = $row+=$spasi;
	//cetak isi nota
		if(!empty($data)){
			$nom = 1;
			$total = 0;
				//TULIS DATA 
			foreach ($data as $nilai){
				$jml = $nilai[2];
				$sp1 = spasinya(5,"$jml");
				$har = desim($nilai[3]);
				$sp2=spasinya(8,"$har");
				$dis = desim($nilai[4]);
				$sp3=spasinya(8,"$dis");
				$tott = desim(($nilai[3]-$nilai[4])*$nilai[2]);
				$sp4=spasinya(10,"$tott");
				printer_draw_text($p, $nilai[1], $var_magin_left, $row);
				printer_draw_text($p, $jml , $var_magin_left+$sp1, $row+=$spasi);
				printer_draw_text($p, $har, 1*$col+$sp2, $row);
				printer_draw_text($p, $dis, 2*$col+$sp3, $row);
				printer_draw_text($p, $tott, 3*$col+$sp4, $row);
				$total = $total + (($nilai[3]-$nilai[4])*$nilai[2]);
				$row +=$spasi;
				$nom++;
			}		
		$dtot = desim($total);
		$sp7= spasinya(10,"$dtot");
		$pilppn = ambilFieldData('utility','variabel','item','ppn');
				if($pilppn=="ya"){
					$ppn = desim($header['pajak']);
					$sp7p= spasinya(10,"$ppn");
					$to = desim($total+$header['pajak']);
					$sp7t= spasinya(10,"$to");
					printer_draw_text($p,"SUB  ", 2*$col, $row);
					printer_draw_text($p, $dtot, 3*$col+$sp7, $row);
					printer_draw_text($p,"PPN  ", 2*$col, $row +=$spasi);
					printer_draw_text($p, $ppn, 3*$col+$sp7p, $row);
					printer_draw_text($p,"TOTAL  ", 2*$col, $row +=$spasi);
					printer_draw_text($p, $to, 3*$col+$sp7t, $row);
				}else{
					printer_draw_text($p,"TOTAL  ", 2*$col, $row +=$spasi);
					printer_draw_text($p, $dtot, 3*$col+$sp7, $row);
				}
		printer_draw_text($p, "Bayar : ", 2*$col, $row +=$spasi); 
		$by = desim($header['bayar']);
		$sp5=spasinya(10,"$by");
		printer_draw_text($p, $by, 3*$col+$sp5, $row); 
		printer_draw_text($p, "Kembali : ", 2*$col, $row +=$spasi); 
		if($pilppn=="ya") $kb = desim($header['bayar']-($total+$header['pajak']));
		else $kb = desim($header['bayar']-$total);
		$sp6=spasinya(10,"$kb");
		printer_draw_text($p, $kb, 3*$col+$sp6, $row); 
		printer_draw_text($p, "=================================", $var_magin_left, $row+=$spasi);
		printer_draw_text($p, "Terima Kasih Atas Kunjungan Anda", 20, $row+=$spasi);
		}
		printer_delete_font($font);
		printer_end_page($p);
		printer_end_doc($p);
		printer_close($p);
}
function catatKegiatan($date, $modul, $event, $aksi){
	$year = date('Y');
	$db = $GLOBALS['ver_db'];
	$ui = $GLOBALS['ver_ui'];
	$pengguna = pengguna();
	$ip = $_SERVER['REMOTE_ADDR'];
	$query=mysql_query("INSERT INTO log VALUES(UUID(),'$date','$year','$pengguna','$ip','$modul','$event','$aksi','$db','$ui')");
}
function cetakBeli($data, $header){
	$myF = ambilFieldData("utility", "variabel", "item", "tipe_print");
	$myFontW = ambilFieldData("utility", "variabel", "item", "font size w");
	$myFontH = ambilFieldData("utility", "variabel", "item", "font size h");
 	$p = printer_open($myF);
	if($p){
	$var_magin_left = 5;
	printer_set_option($p, PRINTER_MODE, "RAW"); 
	printer_start_doc($p);
	printer_start_page($p);
	$font = printer_create_font("Arial", $myFontW, $myFontH, PRINTER_FW_NORMAL, false, false, false, 0);
	printer_select_font($p, $font);
	$row = 50;
	$spasi = 18;
	$col =90;
	printer_draw_text($p, ".:: ".nama."  ::.",100,$row);
	printer_draw_text($p, $header['nomor']." - ".date("d/m/Y H:i:s") ,$var_magin_left, $row+=$spasi);
	printer_draw_text($p, "Suplier : ".$header['suplier'] ,$var_magin_left, $row+=$spasi);
	printer_draw_text($p, "No Nota Sup. : ".$header['nota_suplier'] ,$var_magin_left, $row+=$spasi);
	printer_draw_text($p, "Lokasi Pembayaran : ".$header['mp'] ,$var_magin_left, $row+=$spasi);
	printer_draw_text($p, "Jatuh Tempo : ".$header['jt'] ,$var_magin_left, $row+=$spasi);
	printer_draw_text($p, "=================================", $var_magin_left, $row+=$spasi);
	$row = $row+=$spasi;
	//cetak isi nota
	if(!empty($data)){
			$nom = 1;
			$total = 0;
				//TULIS DATA 
			foreach ($data as $nilai){
				$jml = $nilai[2];
				$sp1 = spasinya(7,"$jml");
				$har = desim($nilai[3]);
				$sp2=spasinya(6,"$har");
				$tot = desim($nilai[4]);
				$sp3=spasinya(12,"$tot");
				printer_draw_text($p, $nilai[1], $var_magin_left, $row);
				printer_draw_text($p, $jml , $var_magin_left+$sp1, $row+=$spasi);
				printer_draw_text($p, $har, 2*$col+$sp2, $row);
				printer_draw_text($p, $tot, 3*$col+$sp4, $row);
				$total = $total + $nilai[4];
				$row +=$spasi;
				$nom++;
			}		
					$to = $total;
					$sub = desim($total);
					$sp11=spasinya(12,"$sub");
					printer_draw_text($p,"TOTAL  ", 2*$col, $row +=$spasi);
					printer_draw_text($p, $sub, 3*$col+$sp7, $row);
		}
		printer_draw_text($p, "=================================", $var_magin_left, $row+=$spasi);
		printer_delete_font($font);
		printer_end_page($p);
		printer_end_doc($p);
		printer_close($p);
	}
}
function cetakNotaIP($data, $header){
					$print = ambilFieldData('utility','variabel','item','tipe_print');
					$tgl = date("d/m/Y H:i:s");
					$handle = fopen('ctk', 'w');
					$condensed = Chr(27) . Chr(33) . Chr(4);
					$bold1 = Chr(27) . Chr(69);
					$bold0 = Chr(27) . Chr(70);
					$initialized = chr(27).chr(64);
					$condensed1 = chr(15);
					$condensed0 = chr(18);
					$Data  = $initialized;
					$Data .= $condensed1;
					$Data .= "=======================================\n";
					$Data .= "           :: ".nama." ::        \n";
					$Data .= "".alamat. "\n";
					$Data .= "          .:: ".telpon. " ::.      \n";
					$Data .= "$header[nomor] - $tgl \n";
					//cetak isi nota
					if(!empty($data)){
							$nom = 1;
							$total = 0;
							$sp1 =0;
							$sp2 =0;
							$sp3 =0;
							$sp4 =0;
							
							//TULIS DATA 
							foreach ($data as $nilai){
								$jml = $nilai[2];
								$sp1 = spasinya(3,"$jml");
								$har = desim($nilai[3]);
								$sp2=spasinya(7,"$har");
								$dis = desim($nilai[4]);
								$sp3=spasinya(6,"$dis");
								$tott = desim(($nilai[3]-$nilai[4])*$nilai[2]);
								$sp4= spasinya(12,"$tott");
								$Data .= "$nilai[1] \n";
								$Data .= "$sp1 $jml $sp2 $har $sp3 $dis $sp4 $tott \n";
								$total = $total + (($nilai[3]-$nilai[4])*$nilai[2]);
							}
						$by = desim($header['bayar']);						
						$ppn = desim($header['pajak']);
						$to = $total+$header['pajak'];
						$dtot = desim($to);
						$sub = desim($total);
						$kb = desim($header['bayar']-$to);
						$sp7= spasinya(14,"$sub");
						$sp8= spasinya(12,"$by");
						$sp9= spasinya(12,"$kb");
						$sp10= spasinya(14,"$ppn");
						$sp11= spasinya(12,"$dtot");
						if($pilppn=="ya"){
						$Data .= "                   Sub $sp7 $sub \n";
						$Data .= "                   Ppn $sp10 $ppn \n";
						$Data .= "                   Total $sp11 $dtot \n";
						}else{
						$Data .= "                   Total $sp11 $sub \n";
						}
						$Data .= "                   Bayar $sp8 $by \n";
						$Data .= "                 Kembali $sp9 $kb \n";
					}
					$Data .= "========================================\n";
					$Data .= "    Terimakasih atas kunjungan anda   \n";
					$Data .= "\n";
					$Data .= "\n";
					$Data .= "\n";
					$Data .= "\n";
					$Data .= "\n";
					$Data .= "\n";
					fwrite($handle, $Data);
					fclose($handle);
					copy("ctk" , "$print");  #Lakukan cetak
					//else echo "<script>alert('Printer tidak siap !')</script>";
					unlink(ctk);
}
function cetakBeliIP($data, $header){
					$print = ambilFieldData('utility','variabel','item','tipe_print');
					$tgl = date("d/m/Y H:i:s");
					$handle = fopen('ctk', 'w');
					$condensed = Chr(27) . Chr(33) . Chr(4);
					$bold1 = Chr(27) . Chr(69);
					$bold0 = Chr(27) . Chr(70);
					$initialized = chr(27).chr(64);
					$condensed1 = chr(15);
					$condensed0 = chr(18);
					$Data  = $initialized;
					$Data .= $condensed1;
					$Data .= "=======================================\n";
					$Data .= "$header[nomor] - $tgl \n";
					$Data .= "Suplier : $header[suplier] \n";
					$Data .= "No Nota Sup. : $header[nota_suplier] \n";
					$Data .= "Lokasi Pembayaran : $header[mp] \n";
					$Data .= "Jatuh Tempo : $header[jt] \n";
					//cetak isi nota
					if(!empty($data)){
							$nom = 1;
							$total = 0;
							$sp1 =0;
							$sp2 =0;
							$sp3 =0;
							$sp4 =0;
							
							//TULIS DATA 
							foreach ($data as $nilai){
								$jml = $nilai[2];
								$sp1 = spasinya(8,"$jml");
								$har = desim($nilai[3]);
								$sp2=spasinya(10,"$har");
								$tot = desim($nilai[4]);
								$sp3=spasinya(12,"$tot");
								$Data .= "$nilai[1] \n";
								$Data .= "$sp1 $jml $sp2 $har $sp3 $tot\n";
								$total = $total + $nilai[4];
							}
						$to = $total;
						$sub = desim($total);
						$sp11=spasinya(12,"$sub");
						$Data .= "                  Total $sp11 $sub \n";
					}
					$Data .= "========================================\n";
					$Data .= "\n";
					$Data .= "\n";
					$Data .= "\n";
					$Data .= "\n";
					$Data .= "\n";
					$Data .= "\n";
					fwrite($handle, $Data);
					fclose($handle);
					copy("ctk" , "$print");  #Lakukan cetak
					//else echo "<script>alert('Printer tidak siap !')</script>";
					unlink(ctk);
}
function cetakNotaJava($data, $header){
	echo  "applet.append(\"         :: ".nama." ::. \\n\");\n";
	echo  "applet.append(\" ".alamat." \\n\");\n";
	echo  "applet.append(\" Telp.".telpon." Kasir: $header[kasir]\\n\");\n";
	echo  "applet.append(\"".$header['nomor']." - ".date("d/m/Y H:i:s")."\\n\");\n";
	//cetak isi nota
		if(!empty($data)){
			$nom = 1;
			$total = 0;
			//TULIS DATA 
			foreach ($data as $nilai){
				echo  "applet.append(\"$nilai[1] \\n\");\n";
				$jml = $nilai[2];
				$sp1 = spasinya(2,"$jml");
				echo  "applet.append(\" $sp1 $jml \");\n";
				$har = desim($nilai[3]);
				$sp2=spasinya(8,"$har");
				echo  "applet.append(\"$sp2 $har \");\n";
				$dis = desim($nilai[4]);
				$sp3=spasinya(8,"$dis");
				echo  "applet.append(\"$sp3 $dis \");\n";
				$tott = desim(($nilai[3]-$nilai[4])*$nilai[2]);
				$sp3=spasinya(8,"$tott");
				echo  "applet.append(\"$sp3 $tott \\n\");\n";
				$total = $total + (($nilai[3]-$nilai[4])*$nilai[2]);
				$nom++;
			}		
			$sp4=spasinya(20,"TOTAL");
			echo  "applet.append(\"$sp4 TOTAL \");\n";
			$subt = desim($total);
			$sp5=spasinya(13,"$subt");
			echo  "applet.append(\"$sp5 $subt \\n\");\n";
		}
		$bText= "Bayar : ";
		$sp12= spasinya(20,"$bText");
		echo  "applet.append(\"$sp12 Bayar : \");\n"; 
		$bIsi= desim($header['bayar']);
		$sp13= spasinya(8,"$bIsi");
		echo  "applet.append(\"$sp13 $bIsi \\n\");\n"; 
		$kText= "Kembali : ";
		$sp14= spasinya(20,"$kText");
		echo  "applet.append(\"$sp14 Kembali : \");\n"; 
		$uIsi= desim($header['bayar']-$total);
		$sp15= spasinya(8,"$uIsi");
		echo  "applet.append(\"$sp15 $uIsi \\n\");\n"; 
		echo  "applet.append(\"  NPWP : ".NPWP." \\n\");\n";
		echo  "applet.append(\"  Harga sudah termasuk PPn \\n\");\n";
		echo  "applet.append(\"  Terima Kasih Atas Kunjungan Anda \");\n";
		echo  "applet.append(\" \\n\");\n"; 
		echo  "applet.append(\" \\n\");\n"; 
		echo  "applet.append(\" \\n\");\n"; 
		echo  "applet.append(\" \\n\");\n"; 
		echo  "applet.append(\" \\n\");\n"; 		
		echo  "applet.append(\" \\n\");\n"; 
		echo  "applet.append(\" \\n\");\n"; 		
		echo  "applet.append(\" \\n\");\n"; 	
}

//CEK stok
function cekStok($tabel1, $tabel2, $kode, $gudang){
	$query=mysql_query("SELECT SUM(jumlah) as jumlah, SUM(jumlah2) as jumlah2 FROM $tabel1, $tabel2 
	WHERE $tabel1.nomor = $tabel2.nomor_nota AND kode_barang = '$kode' 
	AND gudang = '$gudang'");
	$n=mysql_fetch_array($query);
	return $n;
	// gunakan ini untuk keluarannya
	// $n[jumlah];
	// $n[jumlah2];
}


//Ambil semua Data dalam tabel
function ambilData($que, $table){
	$dataBaris =array();
 	$query=mysql_query($que);
	$fields = mysql_list_fields('simbaper',$table);
	if($query){
    $jmlField = mysql_num_fields($query);
	$j =0;
    while ($data=mysql_fetch_array($query)){

		for ($i = 0; $i < $jmlField; $i++) {
	    	$namaField = mysql_field_name($fields, $i);
			$x[$namaField] = $data[$namaField];
		}
	$dataBaris[$j] = $x;
	$j++;
	}
	}
return $dataBaris;
}

//Ambil baris Data dalam tabel berdasarkan ID
function ambilBarisData($tabel, $primary, $id){
 	$query=mysql_query("SELECT * FROM $tabel WHERE $primary='$id'");
	$nama=mysql_fetch_array($query);
	return $nama;
}
function ambilFieldData($tabel, $field, $primary, $id){
 	$query=mysql_query("SELECT $field FROM $tabel WHERE $primary='$id'");
	$nama=mysql_fetch_array($query);
	return $nama[0];
}

//Ambil baris Data dalam tabel berdasarkan field
function ambilBarisDataField($tabel,$field, $id){
 	$query=mysql_query("SELECT $field FROM $tabel WHERE $field='$id'");
	$nama=mysql_fetch_array($query);
	return $nama[0];
}
//Bila data ada 
function samaData($tabel, $field, $id){
	$query=mysql_query("SELECT * FROM $tabel WHERE $field='$id'");
	$r= mysql_num_rows($query);
	return $r;
}

//Bila data meja terpakai 
function cekLokasi($tabel,$field1, $field2, $id1, $id2){
 	$query=mysql_query("SELECT * FROM $tabel WHERE $field1='$id1' AND $field2='$id2'");
	$r=mysql_num_rows($query);
	return $r;
}
function cekStokNya($val){
$mentah = $val;
$key = trim($mentah);
$teks=strrev($key);
$kata ="";
for($i=0; $i<strlen($key); $i++)
{
    $nil_ascii=ord($key[$i]);
    $encrypt=chr($nil_ascii+7);
	$bersih = preg_replace("/[^a-z, A-Z]/","", $encrypt);
	$kata = $kata.$bersih;
}
	$tambahkata ="";
	$jumlah=strlen($kata);
	if ($jumlah > 12) $potong = substr($kata,0, 12);
	else {
	$sisa = 12 - $jumlah;
		for ($j=1;$j<=$sisa;$j++){ 
		$tambahkata = $tambahkata."0";
		}
	}
    for($i=0;$i<($jumlah-1)/2;$i++)
    {
        $mis = $kata[$i];
        $kata[$i] = $kata[$jumlah-$i-1];
        $kata[$jumlah-$i-1] = $mis;
    }
	$jadi = "";
	$potong = $tambahkata.$kata;
    for($i=0;$i<=8;$i+=4)
    {
	$potongan = substr($potong,$i, 4);
	$jadi = $jadi.$potongan."-";
    }
return strtoupper($jadi);
}
function hitungSaldoX($kode_barang, $dari, $hingga){
	if ($hingga =="" OR !isset($hingga)) $hingga = date("Y-m-d");
	else $hingga = $hingga;
	if($dari == "" OR !isset($dari)){
		$awal = mulai;
	}else{
		$awal = $dari;
	}
	$que = mysql_query("SELECT sum( tb_stok.jml_stok ) AS saldo, 
						sum( (tb_stok.jml_stok * tb_stok.harga) ) AS nilai, 
						sum( CASE WHEN tb_stok.harga <> '0' AND  tb_stok.jml_stok > '0' THEN (tb_stok.jml_stok * tb_stok.harga) ELSE 0 END)/ 
						sum( CASE WHEN tb_stok.harga <> '0' AND  tb_stok.jml_stok > '0' THEN (tb_stok.jml_stok) ELSE 0 END)AS rata, 
						(SELECT sum(jml_stok) FROM tb_stok WHERE tb_stok.id_stok_bahan = stok_bahan.id_stok_bahan 
						AND jml_stok>='0'
						AND stok_bahan.id_stok_bahan='$kode_barang'
						AND DATE_FORMAT(tb_stok.tgl,'%Y-%m-%d') BETWEEN '$awal' AND '$hingga'
						) AS masuk,
						(SELECT sum(jml_stok) FROM tb_stok WHERE tb_stok.id_stok_bahan = stok_bahan.id_stok_bahan 
						AND jml_stok<'0'
						AND stok_bahan.id_stok_bahan='$kode_barang'
						AND DATE_FORMAT(tb_stok.tgl,'%Y-%m-%d') BETWEEN '$awal' AND '$hingga'
						) AS keluar
						FROM tb_stok, stok_bahan
						WHERE tb_stok.id_stok_bahan = stok_bahan.id_stok_bahan
						AND stok_bahan.id_stok_bahan='$kode_barang'
						AND DATE_FORMAT(tb_stok.tgl,'%Y-%m-%d') BETWEEN '$awal' AND '$hingga'
						GROUP BY tb_stok.id_stok_bahan");
	$data = mysql_fetch_assoc($que);
	$arraySaldo = array( saldoMasuk => number_format($data['masuk'],2,'.',''),
								 saldoKeluar =>number_format($data['keluar'],2,'.',''),
								 saldo => number_format($data['saldo'],2,'.',''),
								 rata2 => number_format($data['rata'],0,'.',''),
								 totalBeli =>number_format($data['nilai'],0,'.',''),
								 nilaiRata => number_format($data['rata'],0,'.','')
							  );
  return $arraySaldo;
}
function hitungSaldo($kode_barang, $dari, $hingga){
	if ($hingga =="" OR !isset($hingga)) $hingga = date("Y-m-d");
	else $hingga = $hingga;
	if($dari == "" OR !isset($dari)){
		$awal = mulai;
	}else{
		$awal = $dari;
	}
	$masuk =0;
	$keluar =0;
	$jml_m = 0; $jml_k = 0; $jml_s = 0;
	$harga_rata =0;
	$nilai_stok = 0;
	$query = mysql_query("SELECT *,
	IF(jml_stok >= '0', jml_stok, 0 )AS masuk,
	IF(jml_stok < '0', jml_stok, 0 )AS keluar
	FROM tb_stok WHERE id_stok_bahan='$kode_barang' AND DATE_FORMAT(tgl,'%Y-%m-%d') BETWEEN '$dari' AND '$hingga'");
		while ($data = mysql_fetch_array($query)){
			$masuk = $masuk + $data['masuk'];
			$keluar = $keluar + $data['keluar'];
			// barang masuk
			if (substr($data['id_transaksi'],0,1)=="B"){
				$jml_m = $data['jml_stok'];
				$jml_k = 0;
				$jml_s = $jml_s + $jml_m;
				$harga_satuan = $data['harga'];
					if ($jml_s>0){
					$nilai_stok = $nilai_stok + (($jml_m+$jml_k)*$harga_satuan);
					if($operRata==0){
					$nilai_stok = ($jml_s*$harga_satuan);
					}
					$harga_rata = $nilai_stok / $jml_s;
					}else{
					$nilai_stok = 0;
					$harga_rata = 0;
					}				
				}
			// produksi 
			elseif (substr($data['id_transaksi'],0,2)=="PR"){
				$ket=ambilFieldData("kas","keterangan","kode_transaksi",$data['id_transaksi']);
				$jml_m = $data['jml_stok'];
				if($jml_m >= 0){
					$jml_k = 0;
					$jml_s = $jml_s + $jml_m;
				}else{
					$jml_m = 0;
					$jml_k = $data['jml_stok'];
					$jml_s = $jml_s + $jml_k;
				}
				$harga_satuan = $data['harga'];
					if ($jml_s>0){
						$nilai_stok = $nilai_stok + (($jml_m+$jml_k)*$harga_satuan);
					//	$nilai_stok = $nilai_stok + ($jml_m*$harga_satuan);
						if($operRata==0){
						$nilai_stok = ($jml_s*$harga_satuan);
						}
						$harga_rata = $nilai_stok / $jml_s;
					}else{
						$nilai_stok = 0;
						$harga_rata = 0;
					}
				}				
			// barang keluar
			else{
				$jml_m = 0;
				$jml_k = $data['jml_stok'];
				$jml_s = $jml_s + $jml_k;
				$harga_satuan = $harga_rata;
					if ($jml_s>0){
					$nilai_stok = $nilai_stok + ($jml_k*$harga_satuan);
					$harga_rata = $nilai_stok / $jml_s;
					}else{
					$nilai_stok = 0;
					$harga_rata = 0;
					}
				}
		$operRata = $harga_rata;
		}
		$barisSaldoAwal = array(saldoMasuk => number_format($masuk,2,'.',''),
						saldoKeluar => number_format($keluar,2,'.',''),								
						saldo => number_format($jml_s,2,'.',''),
						rata2 => number_format($harga_rata,0,'.',''),
						totalBeli => number_format($nilai_stok,0,'.',''),						
						nilaiRata => number_format($harga_rata,0,'.',''));
		return $barisSaldoAwal;
}
function getMesin(){
	ob_start(); // Turn on output buffering
	system('vol > utilitas\mesin'); //Execute external program to display output
	//$mycom=ob_get_contents(); // Capture the output into a variable
	ob_clean(); // Clean (erase) the output buffer
	//$fh = fopen('utilitas\mesin', 'r') or die("can't open file");
	$mycom = file_get_contents('utilitas\mesin');
	$findme = "Serial Number is ";
	$pmac = strpos($mycom, $findme); // Find the position of Physical text
	$mac=substr($mycom,($pmac+16),25); // Get Physical Address
	$b = preg_replace("/[^0-9, A-Z]/","", $mac);
	$jd = substr($b,0,8);
	for($i=0;$i<=4;$i+=4)
    {
	$potongan = substr($jd,$i, 4);
	if ($i==4)$jadi = $jadi.$potongan;
	else $jadi = $jadi.$potongan."-";
    }
	if (file_exists("utilitas/mesin")) unlink("utilitas\mesin");
	return $jadi;
}
function aktifKan($val){
$mentah = $val;
$key = trim($mentah);
$teks=strrev($key);
$kata ="";
for($i=0; $i<strlen($key); $i++)
{
    $nil_ascii=ord($key[$i]);
    $encrypt=chr($nil_ascii+5);
	$bersih = preg_replace("/[^a-z, A-Z,0-9]/","", $encrypt);
	$kata = $kata.$bersih;
}
	$jumlah=strlen($kata);
    for($i=0;$i<($jumlah-1)/2;$i++)
    {
        $mis = $kata[$i];
        $kata[$i] = $kata[$jumlah-$i-1];
        $kata[$jumlah-$i-1] = $mis;
    }
	$potong = $kata;
    for($i=0;$i<=8;$i+=4)
    {
	$potongan = substr($potong,$i, 4);
	if ($i==8)$jadi = $jadi.$potongan;
	else $jadi = $jadi.$potongan."-";
    }
return $jadi;
}

function cekLogin(){
	if(isset($_SESSION['username'])) $uname = $_SESSION['username'];
	else $uname = "XXX";
	$login=mysql_query("SELECT * FROM ref_pengelola WHERE username='$uname'");
	$data= mysql_fetch_assoc($login);
	$ketemu=mysql_num_rows($login);
	if(isset($_SESSION['idpengguna'])) $idpengguna = $_SESSION['idpengguna'];
	else $idpengguna = "XXX";
	if(md5($data['id_pengelola'])!=$idpengguna){
			echo "<script type=\"text/javascript\">
			alert(\"Maaf anda tidak diijinkan masuk ke situs ini, silahkan login dengan benar!\");
			window.location.href=('../index.php');
			</script>";
	}
	if($ketemu==0){
		echo "<script type=\"text/javascript\">
		alert(\"Anda belum login, silahkan login!\");
		window.location.href=('../index.php');
		</script>";
	}
	if(md5($data['id_pengelola'])==$idpengguna && $ketemu!=0){
		mysql_query("UPDATE ref_pengelola SET online = NOW() WHERE md5(id_pengelola) = '$idpengguna'");
	}
	return md5($data['id_akses']);
}

function pengguna(){
	if(isset($_SESSION['idpengguna'])) $idpengguna = $_SESSION['idpengguna'];
	else $idpengguna = "XXX";
	$query = mysql_query("SELECT id_pengelola FROM ref_pengelola WHERE MD5(id_pengelola) = '$idpengguna'");
	$data = mysql_fetch_assoc($query);
	return $data['id_pengelola'];
}

?>