<?php


if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

session_start();
/** Include PHPExcel */
require_once dirname(__FILE__) . '/../../config/phpexcel/PHPExcel.php';
require_once '../../config/db.koneksi.php';
require_once '../../config/db.function.php';
require_once '../../config/library.php';

error_reporting(E_ALL); ini_set('display_errors', 'OFF'); 

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);


if(!pengguna()){
	header('Content-type: application/json');
	echo json_encode(array('success'=>false, 'pesan'=>"Tidak dapat memproses data, Silahkan login ulang !", 'url'=>'../index.php'));
	mysql_close();
	exit();
}

foreach (glob("../xls/Laporan Rekapitulasi per Jenis*.*") as $filename) {
    unlink($filename);
}


$peran = cekLogin();

	$cuk = "X";
	
	$peran = cekLogin(); 
	$id_sub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
	$id_sum = isset($_POST['id_sum']) ? $_POST['id_sum'] : '';
	$tglawal = isset($_POST['tglawal']) ? $_POST['tglawal'] : '';
	$tglakhir = isset($_POST['tglakhir']) ? $_POST['tglakhir'] : '';
	$ta = isset($_POST['ta']) ? $_POST['ta'] : date('Y');
	$smstr = isset($_POST['smstr']) ? $_POST['smstr'] : '';
	$akses = isset($_POST['akses']) ? $_POST['akses'] : '';
	
	$kepala = $pengurus = $nipk = $nipp = "";
	
	if($id_sub!=""){
		$b = " AND uuid_sub2_unit = '$id_sub'";
		$b1 = " AND d.uuid_skpd = '$id_sub'";
	}else{ $b = " AND MD5(uuid_sub2_unit) = '$_SESSION[uidunit]'";
			$b1 = " AND MD5(d.uuid_skpd) = '$_SESSION[uidunit]'"; }	
		
		
	$skpd = mysql_fetch_assoc(mysql_query("SELECT nm_sub2_unit, kd_sub, uuid_sub2_unit FROM ref_sub2_unit WHERE kd_sub IS NOT NULL $b "));
	
	
	if($skpd['kd_sub']==1 || $skpd['uuid_sub2_unit'] == 'cfa58008-5543-11e6-a2df-000476f4fa98'){
		$in = "(8,10)"; $kep = 8; $txtPengurus = "Pengurus Barang";
	}else{
		$in = "(9,11)"; $kep = 9;  $txtPengurus = "Pengurus Barang";
	}

	if($b1!=""){
		$pejabat = mysql_query("SELECT nama_pejabat, nip, id_jabatan FROM pejabat d WHERE id_jabatan IN $in $b1");
		while($t = mysql_fetch_assoc($pejabat)){
			if($t['id_jabatan']==$kep){ $kepala = $t['nama_pejabat']; $nipk = $t['nip']; }
			else{ $pengurus = $t['nama_pejabat']; $nipp = $t['nip']; }
		}				
	}else{
		$kepala = ".......................";
		$pengurus = ".......................";
		$nipk = $nipp = ".......................";
	}
	
	
	
	
	$t1 = $tglawal ;
	$t2 = $tglakhir ;
	
	$tglawal = balikTanggal($tglawal);
	$tglakhir = balikTanggal($tglakhir);
	
	$kode = mysql_fetch_assoc(mysql_query("SELECT * FROM ref_sub2_unit WHERE uuid_sub2_unit = '$id_sub' "));
	
	$limit = " ";
	$ta_lalu = $ta-1;
	$koder = "";
	if($akses == 2){
		$e1 = "	AND t4.kd_urusan = '$kode[kd_urusan]'
				AND t4.kd_bidang = '$kode[kd_bidang]'
				AND t4.kd_unit = '$kode[kd_unit]' ";
		$filto = "	AND kd_urusan = '$kode[kd_urusan]'
				AND kd_bidang = '$kode[kd_bidang]'
				AND kd_unit = '$kode[kd_unit]' AND kd_sub2 = 1";
		

		
		$koder = " AND t1.kode != 'r' AND t1.kode != 'os' AND ( t1.kode = 'a' OR t1.kode = 'i' OR t1.kode = 'ok' OR t1.kode = 's' OR t1.kode = 'd') ";
	} else if($akses == 3){
		$e1 = "	AND t4.kd_urusan = '$kode[kd_urusan]'
				AND t4.kd_bidang = '$kode[kd_bidang]'
				AND t4.kd_unit = '$kode[kd_unit]'
				AND t4.kd_sub = '$kode[kd_sub]' ";
		$filto = "	AND kd_urusan = '$kode[kd_urusan]'
				AND kd_bidang = '$kode[kd_bidang]'
				AND kd_unit = '$kode[kd_unit]'
				AND kd_sub = '$kode[kd_sub]'";
		
		$koder = " AND (t1.keterangan = 'r' OR t1.kode = 'a' OR t1.kode = 'i' OR t1.kode = 'ok' OR t1.kode = 's' OR t1.kode = 's' OR t1.kode = 'd') ";
				
	} else if($akses == 4){
		
		$koder = " ";
		
		$e1 = "	AND t4.kd_urusan = '$kode[kd_urusan]'
				AND t4.kd_bidang = '$kode[kd_bidang]'
				AND t4.kd_unit = '$kode[kd_unit]'
				AND t4.kd_sub = '$kode[kd_sub]'
				AND t4.kd_sub2 = '$kode[kd_sub2]' ";
		// $e1 = "	AND t1.uuid_skpd = '$id_sub' ";
		$filto = "	AND MD5(uuid_sub2_unit) = '$_SESSION[uidunit]' ";
	} else if($akses == 5){
		$koder = " ";
		$e1 = "	AND t1.uuid_skpd = '$id_sub' ";
		$filto = "	AND uuid_sub2_unit = '$id_sub' ";
	} else {
		$koder = " ";
		$id_sub = $_SESSION["uidunit_plain"];
		$e1 = "	AND t1.uuid_skpd = '$_SESSION[uidunit_plain]' ";
		$filto = "	AND uuid_sub2_unit = '$_SESSION[uidunit_plain]' ";
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
	$bl = " AND DATE_FORMAT(t1.tgl_transaksi, '%Y-%m-%d') BETWEEN CAST('$tglawal' AS DATE) AND CAST('$tglakhir' AS DATE)";
	$bl_lalu = " AND DATE_FORMAT(t1.tgl_transaksi, '%Y-%m-%d') < '$tglawal' ";
	
	
// Create new PHPExcel object
$objPHPExcel = PHPExcel_IOFactory::load("./lap.xlsx");
// Set document properties
$objPHPExcel->getProperties()->setCreator("SIMBAPER")
							 ->setLastModifiedBy("SIMBAPER")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document");

//pagesetup
$objPHPExcel->setActiveSheetIndex(0);							 
$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_FOLIO);
$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.75);
$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.25);
$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.25);
$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.75);

//cell size
// $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);							 
// $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(70);	
// $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);	
// $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
// $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
// $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
// $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
//border
$BStyle = array(
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  )
);

$BTStyle = array(
  'borders' => array(
    'bottom' => array(
      'style' => PHPExcel_Style_Border::BORDER_MEDIUM
    )
  )
);

$BoldStyle = array(
	'font' => array(
		'bold' => true
	)
);

//STYLE

$objPHPExcel->getActiveSheet()->getStyle('A6:D7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);	

// Add HEADER
$objPHPExcel->getActiveSheet()
            ->setCellValue('A1', 'REKONSILIASI PERSEDIAAN  TAHUN 2020')
            ->setCellValue('A2', $skpd["nm_sub2_unit"])
            ->setCellValue('A3', 'PERIODE')
            ->setCellValue('B3', ": $t1 s/d $t2");
			
	$arr_cell = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "AA", "AB", "AC", "AD", "AE", "AF", "AG", "AH", "AI", "AJ", "AK", "AL", "AM", "AN", "AO", "AP", "AQ", "AR", "AS", "AT", "AU", "AV", "AW", "AX", "AY", "AZ");
$rowz = 8; $no = 1; 
$jtot1 = 0;
$jtot2 = 0;
$jtot3 = 0;
$jtot4 = 0;
$jtot5 = 0;
$clause = "SELECT id_jenis, kd_kel, kd_sub, nama_jenis AS nama
			FROM ref_jenis j 
			ORDER BY j.kd_kel, j.kd_sub ASC";
$rs = mysql_query($clause);

$a1 = array();
$a2 = array();
$a3 = array();
$a4 = array();
$a5 = array();
$a6 = array();
$a7 = array();
$a8 = array();
$barisnya = 7;
$ttl1 = 0;
$ttt = "x";
$yyy = "x";
$tj1 = 0; $tj2 = 0; $tj3 = 0; $tj4 = 0; $tj5 = 0; $tj6 = 0; $tj7 = 0; $tj8 = 0; $tj9 = 0; $tj10 = 0; $tj11 = 0; $tj12 = 0; $tj13 = 0; $tj14 = 0; $tj15 = 0; $tj16 = 0; $tj17 = 0; $tj18 = 0; $tj19 = 0; $tj20 = 0; $tj21 = 0; $tj22 = 0; $tj23 = 0; $tj24 = 0; $tj25 = 0; $tj26 = 0; $tj27 = 0; $tj28 = 0; $tj29 = 0; $tj30 = 0; $tj31 = 0; $tj32 = 0; $tj33 = 0; $tj34 = 0; $tj35 = 0;
$qopd = mysql_query("SELECT * FROM ref_sub2_unit WHERE uuid_sub2_unit IS NOT NULL $filto $limit ");
$jopd = mysql_num_rows($qopd);
$asd = array(); 
$sum1 = ""; $sum2 = ""; $sum3 = ""; $sum4 = ""; $sum5 = ""; $sum6 = ""; $sum7 = ""; $sum8 = ""; $sum9 = ""; $sum10 = ""; $sum11 = ""; $sum12 = ""; $sum13 = ""; $sum14 = ""; $sum15 = ""; $sum16 = ""; $sum17 = ""; $sum18 = ""; $sum19 = ""; $sum20 = ""; $sum21 = ""; $sum22 = ""; $sum23 = ""; $sum24 = ""; $sum25 = ""; $sum26 = ""; $sum27 = ""; $sum28 = ""; $sum29 = ""; $sum30 = ""; $sum31 = ""; $sum32 = ""; $sum33 = ""; $sum34 = ""; $sum35 = "";
$kolomnya = "7";
$totsum = "";
while($rowz = mysql_fetch_assoc($rs)){
	$tj = 0;
	$t = 0;
	if($yyy != $rowz["nama"]){
		$yyy = $rowz["nama"];
		
		$b2 = $barisnya+$jopd;
		$b3 = $barisnya+1;
		
		
		$objPHPExcel->getActiveSheet()
			->setCellValue("A".$barisnya, " ".$rowz["kd_kel"].".".$rowz["kd_sub"])
			->setCellValue("B".$barisnya, $rowz["nama"]);
			if($rowz["kd_sub"] == 0){
				$objPHPExcel->getActiveSheet()->getStyle("B".$barisnya)->applyFromArray($BoldStyle);
			}		
			$objPHPExcel->getActiveSheet()
					->setCellValue("C".$barisnya, "=SUM(C".$b3.":C".$b2.")")
					->setCellValue("D".$barisnya, "=SUM(D".$b3.":D".$b2.")")
					->setCellValue("E".$barisnya, "=SUM(E".$b3.":E".$b2.")")
					->setCellValue("F".$barisnya, "=SUM(F".$b3.":F".$b2.")")
					->setCellValue("G".$barisnya, "=SUM(G".$b3.":G".$b2.")")
					->setCellValue("H".$barisnya, "=SUM(H".$b3.":H".$b2.")")
					->setCellValue("I".$barisnya, "=SUM(I".$b3.":I".$b2.")")
					->setCellValue("J".$barisnya, "=SUM(J".$b3.":J".$b2.")")
					->setCellValue("K".$barisnya, "=SUM(K".$b3.":K".$b2.")")
					->setCellValue("L".$barisnya, "=SUM(L".$b3.":L".$b2.")")
					->setCellValue("M".$barisnya, "=SUM(M".$b3.":M".$b2.")")
					->setCellValue("N".$barisnya, "=SUM(N".$b3.":N".$b2.")")
					->setCellValue("O".$barisnya, "=SUM(O".$b3.":O".$b2.")")
					->setCellValue("P".$barisnya, "=SUM(P".$b3.":P".$b2.")")
					->setCellValue("Q".$barisnya, "=SUM(Q".$b3.":Q".$b2.")")
					->setCellValue("R".$barisnya, "=SUM(R".$b3.":R".$b2.")")
					->setCellValue("S".$barisnya, "=SUM(S".$b3.":S".$b2.")")
					->setCellValue("T".$barisnya, "=SUM(T".$b3.":T".$b2.")")
					->setCellValue("U".$barisnya, "=SUM(U".$b3.":U".$b2.")")
					->setCellValue("V".$barisnya, "=SUM(V".$b3.":V".$b2.")")
					->setCellValue("W".$barisnya, "=SUM(W".$b3.":W".$b2.")")
					->setCellValue("X".$barisnya, "=SUM(X".$b3.":X".$b2.")")
					->setCellValue("Y".$barisnya, "=SUM(Y".$b3.":Y".$b2.")")
					->setCellValue("Z".$barisnya, "=SUM(Z".$b3.":Z".$b2.")")
					->setCellValue("AA".$barisnya, "=C".$b3."+K".$b2."-S".$b2."")
					->setCellValue("AB".$barisnya, "=D".$b3."+L".$b2."-T".$b2."")
					->setCellValue("AC".$barisnya, "=E".$b3."+M".$b2."-U".$b2."")
					->setCellValue("AD".$barisnya, "=F".$b3."+N".$b2."-V".$b2."")
					->setCellValue("AE".$barisnya, "=G".$b3."+O".$b2."-W".$b2."")
					->setCellValue("AF".$barisnya, "=H".$b3."+P".$b2."-X".$b2."")
					->setCellValue("AG".$barisnya, "=I".$b3."+Q".$b2."-Y".$b2."")
					->setCellValue("AH".$barisnya, "=J".$b3."+R".$b2."-Z".$b2."")
					->setCellValue("AI".$barisnya, "=SUM(AI".$b3.":AI".$b2.")")
					->setCellValue("AJ".$barisnya, "=SUM(AJ".$b3.":AJ".$b2.")")
					->setCellValue("AK".$barisnya, "=SUM(AK".$b3.":AK".$b2.")");
		$barisnya++;
		
	}
	
	if($rowz["kd_sub"] == 0){
		// $objPHPExcel->getActiveSheet()
			// ->setCellValue("C".$barisnya, $sum1);
		// $barisnya++;
		// $objPHPExcel->getActiveSheet()
				// ->setCellValue("C".$tj2, "=SUM(C".$tj1.":C".$barisnya.")")
				// ->setCellValue("D".$tj2, "=SUM(D".$tj1.":D".$barisnya.")");
		// $tj1 = $barisnya;
		// $tj2 =  $barisnya-1;
					
					
		// array_push($asd, $bar);
		
	} else {
		$b4 = $barisnya-1;
		$sum1 .= "C".$b4.",";
		$sum2 .= "D".$b4.",";
		$sum3 .= "E".$b4.",";
		$sum4 .= "F".$b4.",";
		$sum5 .= "G".$b4.",";
		$sum6 .= "H".$b4.",";
		$sum7 .= "I".$b4.",";
		$sum8 .= "J".$b4.",";
		$sum9 .= "K".$b4.",";
		$sum10 .= "L".$b4.",";
		$sum11 .= "M".$b4.",";
		$sum12 .= "N".$b4.",";
		$sum13 .= "O".$b4.",";
		$sum14 .= "P".$b4.",";
		$sum15 .= "Q".$b4.",";
		$sum16 .= "R".$b4.",";
		$sum17 .= "S".$b4.",";
		$sum18 .= "T".$b4.",";
		$sum19 .= "U".$b4.",";
		$sum20 .= "V".$b4.",";
		$sum21 .= "W".$b4.",";
		$sum22 .= "X".$b4.",";
		$sum23 .= "Y".$b4.",";
		$sum24 .= "Z".$b4.",";
		$sum25 .= "AA".$b4.",";
		$sum26 .= "AB".$b4.",";
		$sum27 .= "AC".$b4.",";
		$sum28 .= "AD".$b4.",";
		$sum29 .= "AE".$b4.",";
		$sum30 .= "AF".$b4.",";
		$sum31 .= "AG".$b4.",";
		$sum32 .= "AH".$b4.",";
		$sum33 .= "AI".$b4.",";
		$sum34 .= "AJ".$b4.",";
		$sum35 .= "AK".$b4.",";
		
		
		
	$opd = mysql_query("SELECT * FROM ref_sub2_unit WHERE uuid_sub2_unit IS NOT NULL $filto $limit");
	while($ropd = mysql_fetch_assoc($opd)){		
		if($akses == 2){
			$s2 = " ";
		} else if($akses == 3){
			$s2 = " AND t4.kd_sub2 = '$ropd[kd_sub2]' ";
		} else if($akses == 4){
			$s2 = " AND t4.kd_sub2 = '$ropd[kd_sub2]' ";
		} else if($akses == 5){
			$s2 = " ";
		} else {
			$s2 = " ";
		}
		
		$qsd = mysql_query("SELECT * FROM ref_sumber_dana WHERE id_sumber >= 28 AND id_sumber <= 35 ORDER BY id_sumber ASC");
		while($rrr=mysql_fetch_assoc($qsd)){
			
			$in_lalu = mysql_fetch_assoc(mysql_query("SELECT SUM(t1.jml_in*t1.harga) AS ttl 
						FROM kartu_stok t1 LEFT JOIN ref_barang t2
						ON t1.id_barang = t2.id_barang
						LEFT JOIN ref_jenis t3
						ON t2.id_jenis = t3.id_jenis
						LEFT JOIN ref_sub2_unit t4 ON t1.uuid_skpd = t4.uuid_sub2_unit
					WHERE 
						t1.id_sumber_dana = '$rrr[id_sumber]' AND t3.id_jenis = '$rowz[id_jenis]' AND jml_in > 0 AND t1.soft_delete = '0' AND t4.kd_unit = '$ropd[kd_unit]' AND t4.kd_sub = '$ropd[kd_sub]' $s2 $bl_lalu $koder "));
						
			
			$out_lalu = mysql_fetch_assoc(mysql_query("SELECT SUM(jml_out*harga) AS ttl 
						FROM kartu_stok t1 LEFT JOIN ref_barang t2
						ON t1.id_barang = t2.id_barang
						LEFT JOIN ref_jenis t3
						ON t2.id_jenis = t3.id_jenis
						LEFT JOIN ref_sub2_unit t4 ON t1.uuid_skpd = t4.uuid_sub2_unit
					WHERE 
						t1.id_sumber_dana = '$rrr[id_sumber]' AND t3.id_jenis = '$rowz[id_jenis]' AND jml_out > 0 AND t1.soft_delete = '0' AND t4.kd_unit = '$ropd[kd_unit]' AND t4.kd_sub = '$ropd[kd_sub]' $s2 $bl_lalu $koder "));
						
			$in = mysql_fetch_assoc(mysql_query("SELECT SUM(t1.jml_in*t1.harga) AS ttl 
						FROM kartu_stok t1 LEFT JOIN ref_barang t2
						ON t1.id_barang = t2.id_barang
						LEFT JOIN ref_jenis t3
						ON t2.id_jenis = t3.id_jenis
						LEFT JOIN ref_sub2_unit t4 ON t1.uuid_skpd = t4.uuid_sub2_unit
					WHERE 
						t1.id_sumber_dana = '$rrr[id_sumber]' AND t3.id_jenis = '$rowz[id_jenis]' AND jml_in > 0 AND t1.soft_delete = '0' AND t4.kd_unit = '$ropd[kd_unit]' AND t4.kd_sub = '$ropd[kd_sub]' $s2 $bl $koder "));
		
		
			$out = mysql_fetch_assoc(mysql_query("SELECT SUM(jml_out*harga) AS ttl 
						FROM kartu_stok t1 LEFT JOIN ref_barang t2
						ON t1.id_barang = t2.id_barang
						LEFT JOIN ref_jenis t3
						ON t2.id_jenis = t3.id_jenis
						LEFT JOIN ref_sub2_unit t4 ON t1.uuid_skpd = t4.uuid_sub2_unit
					WHERE 
						t1.id_sumber_dana = '$rrr[id_sumber]' AND t3.id_jenis = '$rowz[id_jenis]' AND jml_out > 0 AND t1.soft_delete = '0' AND t4.kd_unit = '$ropd[kd_unit]' AND t4.kd_sub = '$ropd[kd_sub]' $s2 $bl $koder"));
		
		
		
			$sisa_lalu = $in_lalu["ttl"]-$out_lalu["ttl"] ;
			// $rowz["saldo_awal"] = $sisa_lalu;
			$saldo = $in["ttl"]-$out["ttl"]+$sisa_lalu;
			$rowz["j"] = $rowz["nama"];
			$rowz["sd"] = $rrr["nama_sumber"];
			$rowz["opd"] = $ropd["nm_sub2_unit"];
			$rowz["id_sumber"] = $rrr["id_sumber"];
			
			if((float)$sisa_lalu <= 0 or (float)$sisa_lalu == ""){
				$sisa_lalu = " -";
			}
			if((float)$in["ttl"] <= 0 or (float)$in["ttl"] == ""){
				$in["ttl"] = " -";
			}
			if((float)$out["ttl"] <= 0 or (float)$out["ttl"] == ""){
				$out["ttl"] = " -";
			}
			if((float)$saldo <= 0 or (float)$saldo == ""){
				$saldo = " -";
			}
			
			if($rrr["id_sumber"] == 28){
				$rowz["C"] = $sisa_lalu;
				$rowz["K"] = $in["ttl"];
				$rowz["S"] = $out["ttl"];
				$rowz["AA"] = $saldo;
			}else if($rrr["id_sumber"] == 30){
				$rowz["D"] = $sisa_lalu;
				$rowz["L"] = $in["ttl"];
				$rowz["T"] = $out["ttl"];
				$rowz["AB"] = $saldo;
			}else if($rrr["id_sumber"] == 31){
				$rowz["E"] = $sisa_lalu;
				$rowz["M"] = $in["ttl"];
				$rowz["U"] = $out["ttl"];
				$rowz["AC"] = $saldo;
			}else if($rrr["id_sumber"] == 32){
				$rowz["F"] = $sisa_lalu;
				$rowz["N"] = $in["ttl"];
				$rowz["V"] = $out["ttl"];
				$rowz["AD"] = $saldo;
			}else if($rrr["id_sumber"] == 33){
				$rowz["G"] = $sisa_lalu;
				$rowz["O"] = $in["ttl"];
				$rowz["W"] = $out["ttl"];
				$rowz["AE"] = $saldo;
			}else if($rrr["id_sumber"] == 34){
				$rowz["H"] = $sisa_lalu;
				$rowz["P"] = $in["ttl"];
				$rowz["X"] = $out["ttl"];
				$rowz["AF"] = $saldo;
			}else if($rrr["id_sumber"] == 35){
				$rowz["I"] = $sisa_lalu;
				$rowz["Q"] = $in["ttl"];
				$rowz["Y"] = $out["ttl"];
				$rowz["AG"] = $saldo;
			}
			$ttl1 += $sisa_lalu;
			$rowz["ttl1"] = $ttl1;
			array_push($a1, $rowz);
		
		}
		$i = 2;
		$xxx = "x";
		foreach($a1 AS $r){
			if($xxx != $ropd["nm_sub2_unit"]){
				$xxx = $ropd["nm_sub2_unit"];
				$objPHPExcel->getActiveSheet()
					->setCellValue("B".$barisnya, " - ".$ropd["nm_sub2_unit"]);
			}
				$tot1 = "=SUM(C".$barisnya.":I".$barisnya.")";
				$tot2 = "=SUM(K".$barisnya.":Q".$barisnya.")";
				$tot3 = "=SUM(S".$barisnya.":Y".$barisnya.")";
				$tot4 = "=SUM(AA".$barisnya.":AG".$barisnya.")";
				$tot5 = "=SUM(J".$barisnya.",K".$barisnya.",N".$barisnya.")";
				$objPHPExcel->getActiveSheet()
					->setCellValue("J".$barisnya, $tot1)
					->setCellValue("R".$barisnya, $tot2)
					->setCellValue("Z".$barisnya, $tot3)
					->setCellValue("AH".$barisnya, $tot4)
					->setCellValue("AI".$barisnya, "=Z".$barisnya)
					->setCellValue("AJ".$barisnya, $tot5)
					->setCellValue("AK".$barisnya, "=AI".$barisnya."-AJ".$barisnya);
				
				
			if($r["id_sumber"] == 28){
				
				$objPHPExcel->getActiveSheet()
					->setCellValue("C".$barisnya, $r["C"])
					->setCellValue("K".$barisnya, $r["K"])
					->setCellValue("S".$barisnya, $r["S"])
					->setCellValue("AA".$barisnya, $r["AA"]);
					
			} else if($r["id_sumber"] == 30){
				
				$objPHPExcel->getActiveSheet()
					->setCellValue("D".$barisnya, $r["D"])
					->setCellValue("L".$barisnya, $r["L"])
					->setCellValue("T".$barisnya, $r["T"])
					->setCellValue("AB".$barisnya, $r["AB"]); 
			} else if($r["id_sumber"] == 31){
				$objPHPExcel->getActiveSheet()
					->setCellValue("E".$barisnya, $r["E"])
					->setCellValue("M".$barisnya, $r["M"])
					->setCellValue("U".$barisnya, $r["U"])
					->setCellValue("AC".$barisnya, $r["AC"]); 
			}else if($r["id_sumber"] == 32){
				$objPHPExcel->getActiveSheet()
					->setCellValue("F".$barisnya, $r["F"])
					->setCellValue("N".$barisnya, $r["N"])
					->setCellValue("V".$barisnya, $r["V"])
					->setCellValue("AD".$barisnya, $r["AD"]); 
			}else if($r["id_sumber"] == 33){
				$objPHPExcel->getActiveSheet()
					->setCellValue("G".$barisnya, $r["G"])
					->setCellValue("O".$barisnya, $r["O"])
					->setCellValue("W".$barisnya, $r["W"])
					->setCellValue("AE".$barisnya, $r["AE"]); 
			}else if($r["id_sumber"] == 34){
				$objPHPExcel->getActiveSheet()
					->setCellValue("H".$barisnya, $r["H"])
					->setCellValue("P".$barisnya, $r["P"])
					->setCellValue("X".$barisnya, $r["X"])
					->setCellValue("AF".$barisnya, $r["AF"]); 
			}else if($r["id_sumber"] == 35){
				$objPHPExcel->getActiveSheet()
					->setCellValue("I".$barisnya, $r["I"])
					->setCellValue("Q".$barisnya, $r["Q"])
					->setCellValue("Y".$barisnya, $r["Y"])
					->setCellValue("AG".$barisnya, $r["AG"]); 
			}
		}
		
		$barisnya++;
		$a1 = array();
		
	}
	
		
		
	}
	if($rowz["kd_sub"] == 0){
		if(strlen($sum1) >1){
			$sum1 = substr($sum1,0,-1);
			$sum2 = substr($sum2,0,-1);
			$sum3 = substr($sum3,0,-1);
			$sum4 = substr($sum4,0,-1);
			$sum5 = substr($sum5,0,-1);
			$sum6 = substr($sum6,0,-1);
			$sum7 = substr($sum7,0,-1);
			$sum8 = substr($sum8,0,-1);
			$sum9 = substr($sum9,0,-1);
			$sum10 = substr($sum10,0,-1);
			$sum11 = substr($sum11,0,-1);
			$sum12 = substr($sum12,0,-1);
			$sum13 = substr($sum13,0,-1);
			$sum14 = substr($sum14,0,-1);
			$sum15 = substr($sum15,0,-1);
			$sum16 = substr($sum16,0,-1);
			$sum17 = substr($sum17,0,-1);
			$sum18 = substr($sum18,0,-1);
			$sum19 = substr($sum19,0,-1);
			$sum20 = substr($sum20,0,-1);
			$sum21 = substr($sum21,0,-1);
			$sum22 = substr($sum22,0,-1);
			$sum23 = substr($sum23,0,-1);
			$sum24 = substr($sum24,0,-1);
			$sum25 = substr($sum25,0,-1);
			$sum26 = substr($sum26,0,-1);
			$sum27 = substr($sum27,0,-1);
			$sum28 = substr($sum28,0,-1);
			$sum29 = substr($sum29,0,-1);
			$sum30 = substr($sum30,0,-1);
			$sum31 = substr($sum31,0,-1);
			$sum32 = substr($sum32,0,-1);
			$sum33 = substr($sum33,0,-1);
			$sum34 = substr($sum34,0,-1);
			$sum35 = substr($sum35,0,-1);
	
			$objPHPExcel->getActiveSheet()
				->setCellValue("C".$kolomnya, "=SUM(".$sum1.")")
				->setCellValue("D".$kolomnya, "=SUM(".$sum2.")")
				->setCellValue("E".$kolomnya, "=SUM(".$sum3.")")
				->setCellValue("F".$kolomnya, "=SUM(".$sum4.")")
				->setCellValue("G".$kolomnya, "=SUM(".$sum5.")")
				->setCellValue("H".$kolomnya, "=SUM(".$sum6.")")
				->setCellValue("I".$kolomnya, "=SUM(".$sum7.")")
				->setCellValue("J".$kolomnya, "=SUM(".$sum8.")")
				->setCellValue("K".$kolomnya, "=SUM(".$sum9.")")
				->setCellValue("L".$kolomnya, "=SUM(".$sum10.")")
				->setCellValue("M".$kolomnya, "=SUM(".$sum11.")")
				->setCellValue("N".$kolomnya, "=SUM(".$sum12.")")
				->setCellValue("O".$kolomnya, "=SUM(".$sum13.")")
				->setCellValue("P".$kolomnya, "=SUM(".$sum14.")")
				->setCellValue("Q".$kolomnya, "=SUM(".$sum15.")")
				->setCellValue("R".$kolomnya, "=SUM(".$sum16.")")
				->setCellValue("S".$kolomnya, "=SUM(".$sum17.")")
				->setCellValue("T".$kolomnya, "=SUM(".$sum18.")")
				->setCellValue("U".$kolomnya, "=SUM(".$sum19.")")
				->setCellValue("V".$kolomnya, "=SUM(".$sum20.")")
				->setCellValue("W".$kolomnya, "=SUM(".$sum21.")")
				->setCellValue("X".$kolomnya, "=SUM(".$sum22.")")
				->setCellValue("Y".$kolomnya, "=SUM(".$sum23.")")
				->setCellValue("Z".$kolomnya, "=SUM(".$sum24.")")
				->setCellValue("AA".$kolomnya, "=SUM(".$sum25.")")
				->setCellValue("AB".$kolomnya, "=SUM(".$sum26.")")
				->setCellValue("AC".$kolomnya, "=SUM(".$sum27.")")
				->setCellValue("AD".$kolomnya, "=SUM(".$sum28.")")
				->setCellValue("AE".$kolomnya, "=SUM(".$sum29.")")
				->setCellValue("AF".$kolomnya, "=SUM(".$sum30.")")
				->setCellValue("AG".$kolomnya, "=SUM(".$sum31.")")
				->setCellValue("AH".$kolomnya, "=SUM(".$sum32.")")
				->setCellValue("AI".$kolomnya, "=SUM(".$sum33.")")
				->setCellValue("AJ".$kolomnya, "=SUM(".$sum34.")")
				->setCellValue("AK".$kolomnya, "=SUM(".$sum35.")");
				$sum1 = "";
				$sum2 = "";
				$sum3 = "";
				$sum4 = "";
				$sum5 = "";
				$sum6 = "";
				$sum7 = "";
				$sum8 = "";
				$sum9 = "";
				$sum10 = "";
				$sum11 = "";
				$sum12 = "";
				$sum13 = "";
				$sum14 = "";
				$sum15 = "";
				$sum16 = "";
				$sum17 = "";
				$sum18 = "";
				$sum19 = "";
				$sum20 = "";
				$sum21 = "";
				$sum22 = "";
				$sum23 = "";
				$sum24 = "";
				$sum25 = "";
				$sum26 = "";
				$sum27 = "";
				$sum28 = "";
				$sum29 = "";
				$sum30 = "";
				$sum31 = "";
				$sum32 = "";
				$sum33 = "";
				$sum34 = "";
				$sum35 = "";
		}
		
		$bbb = $barisnya-1;
			
			
		$b44 = $barisnya-1;
		if($b44 == $kolomnya){
			
		} else {
			$kolomnya = $b44;
		}
		
		
		
		$totsum .= $b44.",";
		
		
		// $barisnya++;
		// $objPHPExcel->getActiveSheet()
				// ->setCellValue("C".$tj2, "=SUM(C".$tj1.":C".$barisnya.")")
				// ->setCellValue("D".$tj2, "=SUM(D".$tj1.":D".$barisnya.")");
		// $tj1 = $barisnya;
		// $tj2 =  $barisnya-1;
					
					
		// array_push($asd, $bar);
		
	}
}
	$objPHPExcel->getActiveSheet()
		->setCellValue("C".$kolomnya, "=SUM(".$sum1.")")
		->setCellValue("D".$kolomnya, "=SUM(".$sum2.")")
		->setCellValue("E".$kolomnya, "=SUM(".$sum3.")")
		->setCellValue("F".$kolomnya, "=SUM(".$sum4.")")
		->setCellValue("G".$kolomnya, "=SUM(".$sum5.")")
		->setCellValue("H".$kolomnya, "=SUM(".$sum6.")")
		->setCellValue("I".$kolomnya, "=SUM(".$sum7.")")
		->setCellValue("J".$kolomnya, "=SUM(".$sum8.")")
		->setCellValue("K".$kolomnya, "=SUM(".$sum9.")")
		->setCellValue("L".$kolomnya, "=SUM(".$sum10.")")
		->setCellValue("M".$kolomnya, "=SUM(".$sum11.")")
		->setCellValue("N".$kolomnya, "=SUM(".$sum12.")")
		->setCellValue("O".$kolomnya, "=SUM(".$sum13.")")
		->setCellValue("P".$kolomnya, "=SUM(".$sum14.")")
		->setCellValue("Q".$kolomnya, "=SUM(".$sum15.")")
		->setCellValue("R".$kolomnya, "=SUM(".$sum16.")")
		->setCellValue("S".$kolomnya, "=SUM(".$sum17.")")
		->setCellValue("T".$kolomnya, "=SUM(".$sum18.")")
		->setCellValue("U".$kolomnya, "=SUM(".$sum19.")")
		->setCellValue("V".$kolomnya, "=SUM(".$sum20.")")
		->setCellValue("W".$kolomnya, "=SUM(".$sum21.")")
		->setCellValue("X".$kolomnya, "=SUM(".$sum22.")")
		->setCellValue("Y".$kolomnya, "=SUM(".$sum23.")")
		->setCellValue("Z".$kolomnya, "=SUM(".$sum24.")")
		->setCellValue("AA".$kolomnya, "=SUM(".$sum25.")")
		->setCellValue("AB".$kolomnya, "=SUM(".$sum26.")")
		->setCellValue("AC".$kolomnya, "=SUM(".$sum27.")")
		->setCellValue("AD".$kolomnya, "=SUM(".$sum28.")")
		->setCellValue("AE".$kolomnya, "=SUM(".$sum29.")")
		->setCellValue("AF".$kolomnya, "=SUM(".$sum30.")")
		->setCellValue("AG".$kolomnya, "=SUM(".$sum31.")")
		->setCellValue("AH".$kolomnya, "=SUM(".$sum32.")")
		->setCellValue("AI".$kolomnya, "=SUM(".$sum33.")")
		->setCellValue("AJ".$kolomnya, "=SUM(".$sum34.")")
		->setCellValue("AK".$kolomnya, "=SUM(".$sum35.")");
		
	$b = $barisnya-1;
	
	$totsum = substr($totsum, 0, -1);
	
	$smm1 = "";
	$smm2 = "";
	$smm3 = "";
	$smm4 = "";
	$smm5 = "";
	$smm6 = "";
	$smm7 = "";
	$smm8 = "";
	$smm9 = "";
	$smm10 = "";
	$smm11 = "";
	$smm12 = "";
	$smm13 = "";
	$smm14 = "";
	$smm15 = "";
	$smm16 = "";
	$smm17 = "";
	$smm18 = "";
	$smm19 = "";
	$smm20 = "";
	$smm21 = "";
	$smm22 = "";
	$smm23 = "";
	$smm24 = "";
	$smm25 = "";
	$smm26 = "";
	$smm27 = "";
	$smm28 = "";
	$smm29 = "";
	$smm30 = "";
	$smm31 = "";
	$smm32 = "";
	$smm33 = "";
	$smm34 = "";
	$smm35 = "";
	$ex = explode(",",$totsum);
	foreach($ex AS $b4){
		$smm1 .= "C".$b4.",";
		$smm2 .= "D".$b4.",";
		$smm3 .= "E".$b4.",";
		$smm4 .= "F".$b4.",";
		$smm5 .= "G".$b4.",";
		$smm6 .= "H".$b4.",";
		$smm7 .= "I".$b4.",";
		$smm8 .= "J".$b4.",";
		$smm9 .= "K".$b4.",";
		$smm10 .= "L".$b4.",";
		$smm11 .= "M".$b4.",";
		$smm12 .= "N".$b4.",";
		$smm13 .= "O".$b4.",";
		$smm14 .= "P".$b4.",";
		$smm15 .= "Q".$b4.",";
		$smm16 .= "R".$b4.",";
		$smm17 .= "S".$b4.",";
		$smm18 .= "T".$b4.",";
		$smm19 .= "U".$b4.",";
		$smm20 .= "V".$b4.",";
		$smm21 .= "W".$b4.",";
		$smm22 .= "X".$b4.",";
		$smm23 .= "Y".$b4.",";
		$smm24 .= "Z".$b4.",";
		$smm25 .= "AA".$b4.",";
		$smm26 .= "AB".$b4.",";
		$smm27 .= "AC".$b4.",";
		$smm28 .= "AD".$b4.",";
		$smm29 .= "AE".$b4.",";
		$smm30 .= "AF".$b4.",";
		$smm31 .= "AG".$b4.",";
		$smm32 .= "AH".$b4.",";
		$smm33 .= "AI".$b4.",";
		$smm34 .= "AJ".$b4.",";
		$smm35 .= "AK".$b4.",";
		
	}
	$smm1 = substr($smm1,0,-1);
	$smm2 = substr($smm2,0,-1);
	$smm3 = substr($smm3,0,-1);
	$smm4 = substr($smm4,0,-1);
	$smm5 = substr($smm5,0,-1);
	$smm6 = substr($smm6,0,-1);
	$smm7 = substr($smm7,0,-1);
	$smm8 = substr($smm8,0,-1);
	$smm9 = substr($smm9,0,-1);
	$smm10 = substr($smm10,0,-1);
	$smm11 = substr($smm11,0,-1);
	$smm12 = substr($smm12,0,-1);
	$smm13 = substr($smm13,0,-1);
	$smm14 = substr($smm14,0,-1);
	$smm15 = substr($smm15,0,-1);
	$smm16 = substr($smm16,0,-1);
	$smm17 = substr($smm17,0,-1);
	$smm18 = substr($smm18,0,-1);
	$smm19 = substr($smm19,0,-1);
	$smm20 = substr($smm20,0,-1);
	$smm21 = substr($smm21,0,-1);
	$smm22 = substr($smm22,0,-1);
	$smm23 = substr($smm23,0,-1);
	$smm24 = substr($smm24,0,-1);
	$smm25 = substr($smm25,0,-1);
	$smm26 = substr($smm26,0,-1);
	$smm27 = substr($smm27,0,-1);
	$smm28 = substr($smm28,0,-1);
	$smm29 = substr($smm29,0,-1);
	$smm30 = substr($smm30,0,-1);
	$smm31 = substr($smm31,0,-1);
	$smm32 = substr($smm32,0,-1);
	$smm33 = substr($smm33,0,-1);
	$smm34 = substr($smm34,0,-1);
	$smm35 = substr($smm35,0,-1);
	

	$objPHPExcel->getActiveSheet()
		->setCellValue("B".$barisnya, "TOTAL")
		->setCellValue("C".$barisnya, "=SUM(".$smm1.")")
		->setCellValue("D".$barisnya, "=SUM(".$smm2.")")
		->setCellValue("E".$barisnya, "=SUM(".$smm3.")")
		->setCellValue("F".$barisnya, "=SUM(".$smm4.")")
		->setCellValue("G".$barisnya, "=SUM(".$smm5.")")
		->setCellValue("H".$barisnya, "=SUM(".$smm6.")")
		->setCellValue("I".$barisnya, "=SUM(".$smm7.")")
		->setCellValue("J".$barisnya, "=SUM(".$smm8.")")
		->setCellValue("K".$barisnya, "=SUM(".$smm9.")")
		->setCellValue("L".$barisnya, "=SUM(".$smm10.")")
		->setCellValue("M".$barisnya, "=SUM(".$smm11.")")
		->setCellValue("N".$barisnya, "=SUM(".$smm12.")")
		->setCellValue("O".$barisnya, "=SUM(".$smm13.")")
		->setCellValue("P".$barisnya, "=SUM(".$smm14.")")
		->setCellValue("Q".$barisnya, "=SUM(".$smm15.")")
		->setCellValue("R".$barisnya, "=SUM(".$smm16.")")
		->setCellValue("S".$barisnya, "=SUM(".$smm17.")")
		->setCellValue("T".$barisnya, "=SUM(".$smm18.")")
		->setCellValue("U".$barisnya, "=SUM(".$smm19.")")
		->setCellValue("V".$barisnya, "=SUM(".$smm20.")")
		->setCellValue("W".$barisnya, "=SUM(".$smm21.")")
		->setCellValue("X".$barisnya, "=SUM(".$smm22.")")
		->setCellValue("Y".$barisnya, "=SUM(".$smm23.")")
		->setCellValue("Z".$barisnya, "=SUM(".$smm24.")")
		->setCellValue("AA".$barisnya, "=C$barisnya+K$barisnya-S$barisnya")
		->setCellValue("AB".$barisnya, "=D$barisnya+L$barisnya-T$barisnya")
		->setCellValue("AC".$barisnya, "=E$barisnya+M$barisnya-U$barisnya")
		->setCellValue("AD".$barisnya, "=F$barisnya+N$barisnya-V$barisnya")
		->setCellValue("AE".$barisnya, "=G$barisnya+O$barisnya-W$barisnya")
		->setCellValue("AF".$barisnya, "=H$barisnya+P$barisnya-X$barisnya")
		->setCellValue("AG".$barisnya, "=I$barisnya+Q$barisnya-Y$barisnya")
		->setCellValue("AH".$barisnya, "=J$barisnya+R$barisnya-Z$barisnya")
		->setCellValue("AI".$barisnya, "=SUM(".$smm33.")")
		->setCellValue("AJ".$barisnya, "=SUM(".$smm34.")")
		->setCellValue("AK".$barisnya, "=SUM(".$smm35.")");

		$objPHPExcel->getActiveSheet()->getStyle("B".$barisnya.":"."AK".$barisnya)->applyFromArray($BoldStyle);
			
//tulis footer
$skpd1 = mysql_fetch_assoc(mysql_query("SELECT * FROM pejabat WHERE uuid_skpd = '$id_sub' AND id_jabatan = '1' "));
$skpd2 = mysql_fetch_assoc(mysql_query("SELECT * FROM pejabat WHERE uuid_skpd = '$id_sub' AND id_jabatan = '2' "));
$skpd3 = mysql_fetch_assoc(mysql_query("SELECT * FROM pejabat WHERE uuid_skpd = '$id_sub' AND id_jabatan = '3' "));

$rr["pengguna"] = $skpd1["nama_pejabat"];
$rr["pengurus"] = $skpd2["nama_pejabat"];
$rr["bendahara"] = $skpd3["nama_pejabat"];

$row = $barisnya+2; 
$date = tgl_indo(date('Y-m-d'));
$objPHPExcel->getActiveSheet()
            ->setCellValue("F$row", "Karanganyar, $date");
$row++;
$objPHPExcel->getActiveSheet()
            ->setCellValue("B$row", "Kepala ".$skpd['nm_sub2_unit'])
            ->setCellValue("D$row", "Pengurus Barang")
            ->setCellValue("F$row", "Bendahara Pengeluaran");
$row+=4;
$objPHPExcel->getActiveSheet()
            ->setCellValue("B$row", "$rr[pengguna]")
            ->setCellValue("D$row", "$rr[pengurus]")
            ->setCellValue("F$row", "$rr[bendahara]");
$row++;
$objPHPExcel->getActiveSheet()
            ->setCellValue("B$row", "NIP $skpd1[nip]")
            ->setCellValue("D$row", "NIP $skpd2[nip]")
            ->setCellValue("F$row", "NIP $skpd3[nip]");
					
	
$objPHPExcel->getActiveSheet()->getStyle("C7:AK$barisnya")->getNumberFormat()->setFormatCode("#,##0.00");			
//tulis border

$objPHPExcel->getActiveSheet()->getStyle("A6:AK$barisnya")->applyFromArray($BStyle);
$objPHPExcel->getActiveSheet()->getStyle("A6:AK6")->applyFromArray($BTStyle);
	
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Laporan Rekapitulasi per Jenis');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Laporan Rekapitulasi per Barang.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->setPreCalculateFormulas(true);
//$objWriter->save('php://output');
$objWriter->save('../xls/Laporan Rekapitulasi per Jenis.xlsx');
$response = array( 'success' => true, 'url' => './xls/Laporan Rekapitulasi per Jenis.xlsx' );
header('Content-type: application/json');
// and in the end you respond back to javascript the file location
echo json_encode($response);

mysql_close();
exit;
