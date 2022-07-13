<?php
/** Error reporting */
/* error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE); */

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

//cek session
session_start();
/* if (!isset($_SESSION['username'])) {
	header('Content-type: application/json');
	echo json_encode(array('success'=>false, 'pesan'=>"Tidak dapat memproses data, Silahkan login ulang !", 'url'=>'../index.php'));
	exit();
} */

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

foreach (glob("../xls/Buku Barang*.*") as $filename) {
    unlink($filename);
}


$kepala = $pengurus = $nipk = $nipp = "";
$basket = isset($_POST['basket']) ? $_POST['basket'] : '';
$id_sub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
$id_sumber = isset($_POST['id_sumber']) ? $_POST['id_sumber'] : '';
$ta = isset($_POST['thn']) ? $_POST['thn'] : '';
$bln = isset($_POST['bln']) ? str_pad($_POST['bln'],2,'0', STR_PAD_LEFT) : '';

	if($ta!=""){ 
		$am = " AND d.ta = '$ta'"; 
		$at = " AND td.ta = '$ta'"; 
		$aj = " AND DATE_FORMAT(tgl_adjust, '%Y') = '$ta'"; 
		$ak = " AND t.ta = '$ta'"; 
	}else{ $am = $at = $aj = $ak = ""; }
	if($_SESSION['level']==md5('c')){
		$b = " AND MD5(m.uuid_sub2_unit) = '$_SESSION[uidunit]'";
		$bm = " AND MD5(m.uuid_skpd) = '$_SESSION[uidunit]'";
		$bt = " AND MD5(td.uuid_skpd) = '$_SESSION[uidunit]'";
		$bj = " AND MD5(ad.uuid_skpd) = '$_SESSION[uidunit]'";
		$bk = " AND MD5(k.uuid_skpd) = '$_SESSION[uidunit]'";
	}else{
		if($id_sub!=""){
			$b = " AND m.uuid_sub2_unit = '$id_sub'";
			$bm = " AND m.uuid_skpd = '$id_sub'";
			$bt = " AND td.uuid_skpd = '$id_sub'";
			$bj = " AND ad.uuid_skpd = '$id_sub'";
			$bk = " AND k.uuid_skpd = '$id_sub'";
		}else{ $b = $bm = $bt = $bj = $bk = ""; }
	}	
	if($bln!="" AND $bln!="00") $c = " AND DATE_FORMAT(tgl_transaksi, '%m') = '$bln'"; else $c = "";
	if($id_sumber!="") $d = "AND k.id_sumber_dana = '$id_sumber'";
	else $d = "";
		
	
$items = array();
$skpd = mysql_fetch_assoc(mysql_query("SELECT m.nm_sub2_unit, kd_sub FROM ref_sub2_unit m WHERE m.kd_sub IS NOT NULL $b"));
if($skpd['kd_sub']==1 || $data['uuid_skpd'] == 'cfa58008-5543-11e6-a2df-000476f4fa98'){
	$in = "(8,10)"; $kep = 8; $txtPengurus = "Pengurus Barang";
}else{
	$in = "(9,11)"; $kep = 9;  $txtPengurus = "Pembantu Pengurus Barang";
}

	
if($bm!=""){				
	$pejabat = mysql_query("SELECT nama_pejabat, nip, id_jabatan FROM pejabat m WHERE id_jabatan IN $in $bm");
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
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10.5);	
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12.2);	
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(8.7);	
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);	
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(8.9);	
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(13.6);	
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(9.1);	
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(7.9);	
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(13.1);	
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(12.1);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(8.9);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(13.9);
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(6.2);
$objPHPExcel->getActiveSheet()->getRowDimension(8)->setRowHeight(46);
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

//STYLE
/* 			
$objPHPExcel->getActiveSheet()->getStyle('D6:E6')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('B6:B7')->getAlignment()->setWrapText(true); */
$objPHPExcel->getActiveSheet()->getStyle('A1:N1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A6:N9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A6:N8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);	
$objPHPExcel->getActiveSheet()->getStyle('A6:N8')->getAlignment()->setWrapText(true);

// Add HEADER
$objPHPExcel->getActiveSheet()
            ->setCellValue('A1', 'BUKU BARANG PERSEDIAAN')
			->mergeCells('A1:N1');
$objPHPExcel->getActiveSheet()
            ->setCellValue('A3', 'SKPD/Unit Kerja')
            ->setCellValue('C3', ": $skpd[nm_sub2_unit] ")
            ->setCellValue('A4', 'Kabupaten')
            ->setCellValue('C4', ': Karanganyar');
$objPHPExcel->getActiveSheet()
            ->setCellValue('A6', 'NO')
			->mergeCells('A6:A8')
			->setCellValue('B6', "PENERIMAAN")
			->mergeCells('B6:I6')
            ->setCellValue('B7', "TANGGAL\nDITERIMA")
			->mergeCells('B7:B8')
            ->setCellValue('C7', "JENIS/NAMA\nBARANG")
			->mergeCells('C7:C8')
			->setCellValue('D7', "MERK/\nUKURAN")
			->mergeCells('D7:D8')
			->setCellValue('E7', "TAHUN\nPEMBUATAN")
			->mergeCells('E7:E8')
			->setCellValue('F7', "JUMLAH\nSATUAN/\nBARANG")
			->mergeCells('F7:F8')
			->setCellValue('G7', "TGL/NO\nKONTRAK/SP/\nSPK/HARGA\nSATUAN")
			->mergeCells('G7:G8')
			->setCellValue('H7', "BERITA ACARA\nPEMERIKSAAN")
			->mergeCells('H7:I7')
			->setCellValue('H8', "TANGGAL")
			->setCellValue('I8', "NOMOR")	
			->setCellValue('J6', "PENGELUARAN")
			->mergeCells('J6:N6')
			->setCellValue('J7', "TANGGAL\nDIKELUARKAN")
			->mergeCells('J7:J8')
			->setCellValue('K7', "DISERAHKAN\nKEPADA")
			->mergeCells('K7:K8')
			->setCellValue('L7', "JUMLAH\nSATUAN/\nBARANG")
			->mergeCells('L7:L8')
			->setCellValue('M7', "TGL/NO SURAT\nPENYERAHAN")
			->mergeCells('M7:M8')
			->setCellValue('N7', "KET")	
			->mergeCells('N7:N8');	

$objPHPExcel->getActiveSheet()
            ->setCellValue('A9', '1')
            ->setCellValue('B9', '2')
            ->setCellValue('C9', '3')
			->setCellValue('D9', '4')
            ->setCellValue('E9', '5')
            ->setCellValue('F9', '6')
            ->setCellValue('G9', '7')
            ->setCellValue('H9', '8')
            ->setCellValue('I9', '9')
            ->setCellValue('J9', '10')
            ->setCellValue('K9', '11')
            ->setCellValue('L9', '12')
            ->setCellValue('M9', '13')
            ->setCellValue('N9', '14');

$row = 10; $no = 1;
foreach($basket AS $d){
	$objPHPExcel->getActiveSheet()
            ->setCellValue('A'.$row, "$no")
            ->setCellValue('B'.$row, "$d[tgl_terima]")
			->setCellValue('C'.$row, "$d[nama_barang]")
			->setCellValue('D'.$row, "$d[merk]")
            ->setCellValue('E'.$row, "$d[tahun]")
            ->setCellValue('F'.$row, "$d[jml_terima]")
            ->setCellValue('G'.$row, "$d[hrg_spk]")
			->setCellValue('H'.$row, "$d[tgl_periksa]")
            ->setCellValue('I'.$row, "$d[no_periksa]")
            ->setCellValue('J'.$row, "$d[tgl_keluar]")
            ->setCellValue('K'.$row, "$d[kepada]")
            ->setCellValue('L'.$row, "$d[jml_keluar]")
            ->setCellValue('M'.$row, "$d[tglno_surat]")
            ->setCellValue('N'.$row, "$d[ket]");
	
	$row++;	$no++;	
}

/* $objPHPExcel->getActiveSheet()
            ->setCellValue('B'.$row, "JUMLAH TOTAL")
			->mergeCells("B$row:C$row")
            ->setCellValue('H'.$row, "$jtot"); */
    
$objPHPExcel->getActiveSheet()->getStyle("C10:C$row")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("G10:G$row")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("K10:K$row")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("M10:M$row")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("A10:A$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("F10:F$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("L10:L$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("A10:N$row")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);				
$objPHPExcel->getActiveSheet()->getStyle("F10:F$row")->getNumberFormat()->setFormatCode("#,##0.00");	
$objPHPExcel->getActiveSheet()->getStyle("L10:L$row")->getNumberFormat()->setFormatCode("#,##0.00");	
			
//tulis border
$row--;
$objPHPExcel->getActiveSheet()->getStyle("A6:N$row")->applyFromArray($BStyle);
$objPHPExcel->getActiveSheet()->getStyle("A9:N9")->applyFromArray($BTStyle);


//tulis footer
$row+=2; $date = tgl_indo(date('Y-m-d'));
$objPHPExcel->getActiveSheet()
            ->setCellValue("L$row", "Karanganyar, $date");
$row++;
$objPHPExcel->getActiveSheet()
            ->setCellValue("C$row", 'Kepala SKPD/Unit Kerja')
            ->setCellValue("L$row", "$txtPengurus");
$row+=3;
$objPHPExcel->getActiveSheet()
            ->setCellValue("C$row", "( $kepala )")
            ->setCellValue("L$row", "( $pengurus )");
$row++;
$objPHPExcel->getActiveSheet()
            ->setCellValue("C$row", "NIP $nipk")
            ->setCellValue("L$row", "NIP $nipp");

		
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Buku Barang');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Buku Barang.xlsx"');
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
$objWriter->save('../xls/Buku Barang '.$tgl_cetak_now.'.xlsx');
$response = array( 'success' => true, 'url' => './xls/Buku Barang '.$tgl_cetak_now.'.xlsx' );
header('Content-type: application/json');
// and in the end you respond back to javascript the file location
echo json_encode($response);
	
mysql_close();
exit;
