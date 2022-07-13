<?php

/** Error reporting */
/* error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
 */
if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

session_start();
/** Include PHPExcel */
require_once dirname(__FILE__) . '/../../config/phpexcel/PHPExcel.php';
require_once '../../config/db.koneksi.php';
require_once '../../config/db.function.php';
require_once '../../config/library.php';
if(!pengguna()){
	header('Content-type: application/json');
	echo json_encode(array('success'=>false, 'pesan'=>"Tidak dapat memproses data, Silahkan login ulang !", 'url'=>'../index.php'));
	mysql_close();
	exit();
}

foreach (glob("../xls/Buku Penerimaan*.*") as $filename) {
    unlink($filename);
}


$peran = cekLogin();
	
$kepala = $pengurus = $nipk = $nipp = "";
$id_sub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
$id_sumber = isset($_POST['id_sum']) ? $_POST['id_sum'] : '';
$ta = isset($_POST['ta']) ? $_POST['ta'] : date('Y');
$tglawal = isset($_POST['tglawal']) ? $_POST['tglawal'] : '';
$tglakhir = isset($_POST['tglakhir']) ? $_POST['tglakhir'] : '';
$sumber = isset($_POST['sumber']) ? $_POST['sumber'] : '';

$t1 = $tglawal;
$t2 = $tglakhir;
$tot_bar = "";
$tot_har = "";

$tglawal = balikTanggal($tglawal);
$tglakhir = balikTanggal($tglakhir);


if($ta!="") $a = " AND ta = '$ta'"; else $a = "";

/* if($id_sub!="") $b = " AND uuid_skpd = '$id_sub'";
else $b = " AND MD5(uuid_skpd) = '$_SESSION[uidunit]'";
	
if($id_sub!="") $b2 = " AND uuid_sub2_unit = '$id_sub'";
else $b2 = " AND MD5(uuid_sub2_unit) = '$_SESSION[uidunit]'"; */

if($id_sub!="") { $b = " AND uuid_skpd = '$id_sub'"; $b2 = " AND uuid_sub2_unit = '$id_sub'"; }
else { $b = " AND MD5(uuid_skpd) = '$_SESSION[uidunit]'"; $b2 = " AND MD5(uuid_sub2_unit) = '$_SESSION[uidunit]'"; }
	
/* if($id_sub!="") $b2 = " AND uuid_sub2_unit = '$id_sub'";
else $b2 = " AND MD5(uuid_sub2_unit) = '$_SESSION[uidunit]'"; */
 



// if($id_sub!=""){ $d = " AND d.uuid_skpd = '$id_sub'"; $b = " AND d.uuid_sub2_unit = '$id_sub'"; }
// else{ $d = " AND MD5(d.uuid_skpd) = '$_SESSION[uidunit]'"; $b = " AND MD5(d.uuid_sub2_unit) = '$_SESSION[uidunit]'";	}

	
if($_SESSION['peran_id']==MD5('1')) $b .= "";
else{
	if($_SESSION['level']==MD5('a')) $b .= " AND MD5(CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit)) = '$_SESSION[peserta]'";
	elseif($_SESSION['level']==MD5('b')) $b .= "  AND MD5(CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit, kd_sub)) = '$_SESSION[peserta]'";
}	

if($tglawal!="" and $tglakhir!="") $c = "AND DATE_FORMAT(tgl_transaksi, '%Y-%m-%d') BETWEEN CAST('$tglawal' AS DATE) AND CAST('$tglakhir' AS DATE)"; else $c = "";
if($id_sumber!="") $d = " AND id_sumber_dana = '$id_sumber'"; else $d = "";

$where = " $a $b $c $d";
	 
$skpd = mysql_fetch_assoc(mysql_query("SELECT d.nm_sub2_unit, kd_sub, uuid_sub2_unit FROM ref_sub2_unit d WHERE d.kd_sub IS NOT NULL $b2"));
// echo "SELECT d.nm_sub2_unit, kd_sub, uuid_sub2_unit FROM ref_sub2_unit d WHERE d.kd_sub IS NOT NULL $b2";
if($skpd['kd_sub']==1 || $skpd['uuid_sub2_unit'] == 'cfa58008-5543-11e6-a2df-000476f4fa98'){
	$in = "(8,10)"; $kep = 8; $txtPengurus = "Pengurus Barang";
}else{
	$in = "(1,2)"; $kep = 1;  $txtPengurus = "Pembantu Pengurus Barang";
}

	$clause = mysql_query(" SELECT id_barang, id_transaksi, id_transaksi_detail, kode, jml_in AS jml_masuk, harga AS harga_masuk, tgl_transaksi AS tgl_terima, nm_sub2_unit AS unit FROM kartu_stok u
						 LEFT JOIN ref_sub2_unit s 
						 ON s.uuid_sub2_unit = u.uuid_skpd 
						 WHERE kode != 'a' AND kode != 'm' AND soft_delete = 0 AND jml_in > 0 $where ");
											 
if($b!=""){				
	$pejabat = mysql_query("SELECT nama_pejabat, nip, id_jabatan FROM pejabat u WHERE id_jabatan IN $in $b");
	// echo "SELECT nama_pejabat, nip, id_jabatan FROM pejabat u WHERE id_jabatan IN $in $b";
	while($t = mysql_fetch_assoc($pejabat)){
		if($t['id_jabatan']==$kep){ $kepala = $t['nama_pejabat']; $nipk = $t['nip']; }
		else{ $pengurus = $t['nama_pejabat']; $nipp = $t['nip']; }
		
	}				
}else{
	$kepala = ".......................";
	$pengurus = ".......................";
	$nipk = $nipp = ".......................";
}
	
	
// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

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
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(4);							 
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(13);	
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(3);	
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(17);	
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);	
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(13);	
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(16);	
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);	
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);	
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(12);	
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);	
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(13);	
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(7);
// $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(7);
//border
$BStyle = array(
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  )
);


//STYLE
/* 			
$objPHPExcel->getActiveSheet()->getStyle('D6:E6')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('B6:B7')->getAlignment()->setWrapText(true); */
$objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A8:M11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A8:M11')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);	
$objPHPExcel->getActiveSheet()->getStyle('G8:J11')->getAlignment()->setWrapText(true);

// Add HEADER
$objPHPExcel->getActiveSheet()
            ->setCellValue('A1', 'BUKU PENERIMAAN BARANG PERSEDIAAN')
			->mergeCells('A1:M1');
$objPHPExcel->getActiveSheet()
            ->setCellValue('A3', 'SKPD/Unit Kerja')
            ->setCellValue('C3', ": $skpd[nm_sub2_unit] ")
            ->setCellValue('A4', 'Periode')
            ->setCellValue('C4', ": $t1 s/d $t2")
			->setCellValue('A5', 'Kabupaten')
            ->setCellValue('C5', ': Karanganyar')
			->setCellValue('A6', 'Sumber Dana')
            ->setCellValue('C6', ": $sumber");	
$objPHPExcel->getActiveSheet()
            ->setCellValue('A8', 'NO')
            ->mergeCells('A8:A10')
            ->setCellValue('B8', "TGL TERIMA")
			->mergeCells('B8:C10')
            ->setCellValue('D8', "DARI")
			->mergeCells('D8:D10')
            ->setCellValue('E8', "DOKUMEN FAKTUR")
			->mergeCells('E8:F8')
			->setCellValue('E9', "NOMOR")
			->mergeCells('E9:E10')
			->setCellValue('F9', "TANGGAL")
			->mergeCells('F9:F10')
			->setCellValue('G8', "NAMA BARANG")
			->mergeCells('G8:G10')
			->setCellValue('H8', "BANYAKNYA")
			->mergeCells('H8:H10')
			->setCellValue('I8', "HARGA SATUAN (Rp.)")
			->mergeCells('I8:I10')
			->setCellValue('J8', "JUMLAH HARGA (Rp.)")
			->mergeCells('J8:J10')
			->setCellValue('K8', "BUKTI PENERIMAAN")
			->mergeCells('K8:L8')
			->setCellValue('K9', "B.A PENERIMAAN")
			->mergeCells('K9:L9')
			->setCellValue('K10', "NOMOR")
			->setCellValue('L10', "TANGGAL")
			// ->setCellValue('M7', "DIPERGUNAKAN PADA UNIT")
			// ->mergeCells('M7:M9')
			->setCellValue('M8', "KET")
			->mergeCells('M8:M10');	

$objPHPExcel->getActiveSheet()
            ->setCellValue('A11', '1')
            ->setCellValue('B11', '2')
			->mergeCells('B11:C11')
            ->setCellValue('D11', '3')
            ->setCellValue('E11', '4')
            ->setCellValue('F11', '5')
            ->setCellValue('G11', '6')
            ->setCellValue('H11', '7')
            ->setCellValue('I11', '8')
            ->setCellValue('J11', '9')
            ->setCellValue('K11', '10')
            ->setCellValue('L11', '11')
            ->setCellValue('M11', '12');
            // ->setCellValue('N10', '13');

$row = 12; $no = 1;		
while($d =mysql_fetch_assoc($clause)){
		
		$barang = mysql_fetch_assoc(mysql_query("SELECT nama_barang FROM ref_barang WHERE id_barang = '$d[id_barang]' "));
		$d["nama_barang"] = $barang["nama_barang"];
		if($d["kode"] == "i"){
			$dari = mysql_fetch_assoc(mysql_query("SELECT * FROM masuk WHERE id_masuk = '$d[id_transaksi]' "));
			$d["dari"] = $dari["nama_pengadaan"];
			$d["no_dok"] = $dari["no_dok_penerimaan"];
			$d["tgl_dok"] = $dari["tgl_dok_penerimaan"];
			$d["no_ba"] = $dari["no_ba_penerimaan"];
			$d["tgl_ba"] = $dari["tgl_penerimaan"];
		}
		else if($d["kode"] == "r"){
			$dari = mysql_fetch_assoc(mysql_query("SELECT * FROM terima_keluar t1 
															LEFT JOIN keluar t2 ON t1.id_keluar = t2.id_keluar  
															LEFT JOIN ref_sub2_unit t3 ON t2.uuid_skpd = t3.uuid_sub2_unit
															WHERE id_terima_keluar = '$d[id_transaksi]' "));
			$d["dari"] = $dari["nm_sub2_unit"];
			$d["no_dok"] = "";
			$d["tgl_dok"] = "";
			$d["no_ba"] = $dari["no_ba_out"];
			$d["tgl_ba"] = $dari["tgl_ba_out"];
		}
		$harga = $d['harga_masuk'] * $d['jml_masuk'];
		$tot_bar += $d['jml_masuk'];
		$tot_har += $harga;
		$d['tgl_terima'] = balikTanggalIndo($d['tgl_terima']);
		$d['tgl_ba'] = balikTanggalIndo($d['tgl_ba']);
		$d['tgl_dok'] = balikTanggalIndo($d['tgl_dok']);
		
		
	// $harga = $d['harga_masuk'];
	$total = $harga * $d['jml_masuk'];
	// $tgl_ba = balikTanggalIndo($d['tgl_ba']);
	// $tgl_dok = balikTanggalIndo($d['tgl_dok']);
	
	$objPHPExcel->getActiveSheet()
            ->setCellValue('A'.$row, "$no")
            ->setCellValue('B'.$row, "$d[tgl_terima]")
			->mergeCells("B$row:C$row")
            ->setCellValue('D'.$row, "$d[dari]")
            ->setCellValue('E'.$row, "$d[no_dok]")
            ->setCellValue('F'.$row, "$d[tgl_dok]")
            ->setCellValue('G'.$row, "$d[nama_barang]")
			->setCellValue('H'.$row, "$d[jml_masuk]")
            ->setCellValue('I'.$row, "$d[harga_masuk]")
            ->setCellValue('J'.$row, "$harga")
            ->setCellValue('K'.$row, "$d[no_ba]")
            ->setCellValue('L'.$row, "$d[tgl_ba]")
            // ->setCellValue('M'.$row, "$d[unit]")
            ->setCellValue('M'.$row, "");
	
	$row++;	$no++;	
}
	
$objPHPExcel->getActiveSheet()->getStyle("D11:D$row")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("A11:A$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("H11:H$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("A11:M$row")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("H12:J$row")->getNumberFormat()->setFormatCode("#,##0.00");

			
//tulis border
$row--;
$objPHPExcel->getActiveSheet()->getStyle("A8:M$row")->applyFromArray($BStyle);


//tulis footer
$row+=3; $date = tgl_indo(date('Y-m-d'));
$objPHPExcel->getActiveSheet()
            ->setCellValue("J$row", "Karanganyar, $date");
$row++;
$objPHPExcel->getActiveSheet()
            ->setCellValue("C$row", "Kepala ".$skpd['nm_sub2_unit'])
            ->setCellValue("J$row", "$txtPengurus");
$row+=3;
$objPHPExcel->getActiveSheet()
            ->setCellValue("C$row", "( $kepala )")
            ->setCellValue("J$row", "( $pengurus )");
$row++;
$objPHPExcel->getActiveSheet()
            ->setCellValue("C$row", "NIP $nipk")
            ->setCellValue("J$row", "NIP $nipp");
/* $row++;			
$objPHPExcel->getActiveSheet()->setCellValue("G$row", 'BUPATI KARANGANYAR,');
$objPHPExcel->getActiveSheet()->getStyle("G$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$row+=3;
$objPHPExcel->getActiveSheet()->setCellValue("G$row", 'JULIYATMONO');
$objPHPExcel->getActiveSheet()->getStyle("G$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
 */
			
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Buku Penerimaan');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Buku Penerimaan.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//$objWriter->save('php://output');
$objWriter->save('../xls/Buku Penerimaan '.$tgl_cetak_now.'.xlsx');
$response = array( 'success' => true, 'url' => './xls/Buku Penerimaan '.$tgl_cetak_now.'.xlsx' );
header('Content-type: application/json');
// and in the end you respond back to javascript the file location
echo json_encode($response);

mysql_close();
exit;
