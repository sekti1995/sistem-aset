<?php

/** Error reporting */
/* error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE); */

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

foreach (glob("../xls/Laporan Rekapitulasi Kabupaten*.*") as $filename) {
    unlink($filename);
}


$peran = cekLogin();
	
//$kepala = $pengurus = $nipk = $nipp = "";
//$id_sub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
$basket = isset($_POST['basket']) ? $_POST['basket'] : '';
$bulan = isset($_POST['bulan']) ? $_POST['bulan'] : '';
$smstr = isset($_POST['smstr']) ? $_POST['smstr'] : '';
$ta = isset($_POST['ta']) ? $_POST['ta'] : '';

if($bulan!=''){ $label = "Bulan"; $periode = "$bulan $ta"; }
elseif($smstr!=''){ $label = "Semester"; $periode = $smstr; }
else{ $label = "Tahun"; $periode = $ta; }  
/* if($id_sub!=""){
	$b = " AND uuid_sub2_unit = '$id_sub'";
	$b1 = " AND d.uuid_skpd = '$id_sub'";
}else{ $b = " AND MD5(uuid_sub2_unit) = '$_SESSION[uidunit]'";
		$b1 = " AND MD5(d.uuid_skpd) = '$_SESSION[uidunit]'"; }	
$skpd = mysql_fetch_assoc(mysql_query("SELECT nm_sub2_unit, kd_sub FROM ref_sub2_unit WHERE kd_sub IS NOT NULL $b"));
if($skpd['kd_sub']==1){
	$in = "(8,10)"; $kep = 8; $txtPengurus = "Pengurus Barang";
}else{
	$in = "(9,11)"; $kep = 9;  $txtPengurus = "Pembantu Pengurus Barang";
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
} */
	
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
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);	
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);	
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
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
$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A5:D6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

// Add HEADER
$objPHPExcel->getActiveSheet()
            ->setCellValue('A1', 'LAPORAN REKAPITULASI PERSEDIAAN KABUPATEN')
			->mergeCells('A1:D1');
$objPHPExcel->getActiveSheet()
            ->setCellValue('A3', "$label : $periode");
$objPHPExcel->getActiveSheet()
            ->setCellValue('A5', 'NO')
			->setCellValue('B5', "KODE SKPD")
            ->setCellValue('C5', "NAMA SKPD")
            ->setCellValue('D5', "NILAI PERSEDIAAN");	

$objPHPExcel->getActiveSheet()
            ->setCellValue('A6', '1')
            ->setCellValue('B6', '2')
            ->setCellValue('C6', '3')
            ->setCellValue('D6', '4');

$row = 7; $no = 1; $total = 0;
foreach($basket AS $d){
	$nilai = preg_replace("/[^0-9]/","", $d['nilai']);
	$total += $nilai;
	$objPHPExcel->getActiveSheet()
            ->setCellValue('A'.$row, "$no")
            ->setCellValue('B'.$row, "$d[kode_skpd]")
            ->setCellValue('C'.$row, "$d[nama_skpd]")
            ->setCellValue('D'.$row, "$nilai");
	
	$row++;	$no++;	
}

$objPHPExcel->getActiveSheet()
            ->setCellValue('B'.$row, "JUMLAH TOTAL")
			->mergeCells("B$row:C$row")
            ->setCellValue('D'.$row, "$total");
    
//$objPHPExcel->getActiveSheet()->getStyle("K9:K$row")->getAlignment()->setWrapText(true);
//$objPHPExcel->getActiveSheet()->getStyle("M9:M$row")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("A7:A$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//$objPHPExcel->getActiveSheet()->getStyle("D7:G$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//$objPHPExcel->getActiveSheet()->getStyle("A9:N$row")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);				
$objPHPExcel->getActiveSheet()->getStyle("D7:D$row")->getNumberFormat()->setFormatCode("#,##0.00");			
//tulis border
//$row--;
$objPHPExcel->getActiveSheet()->getStyle("A5:D$row")->applyFromArray($BStyle);
$objPHPExcel->getActiveSheet()->getStyle("A6:D6")->applyFromArray($BTStyle);


//tulis footer
/* $row+=2; $date = tgl_indo(date('Y-m-d'));
$objPHPExcel->getActiveSheet()
            ->setCellValue("I$row", "Karanganyar, $date");
$row++;
$objPHPExcel->getActiveSheet()
            ->setCellValue("C$row", 'Kepala SKPD/Unit Kerja')
            ->setCellValue("I$row", "$txtPengurus");
$row+=3;
$objPHPExcel->getActiveSheet()
            ->setCellValue("C$row", "( $kepala )")
            ->setCellValue("I$row", "( $pengurus )");
$row++;
$objPHPExcel->getActiveSheet()
            ->setCellValue("C$row", "NIP $nipk")
            ->setCellValue("I$row", "NIP $nipp"); */
/* $row++;			
$objPHPExcel->getActiveSheet()->setCellValue("F$row", 'BUPATI KARANGANYAR,');
$objPHPExcel->getActiveSheet()->getStyle("F$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$row+=3;
$objPHPExcel->getActiveSheet()->setCellValue("F$row", 'JULIYATMONO');
$objPHPExcel->getActiveSheet()->getStyle("F$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
 */
			
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Laporan Rekapitulasi Kabupaten');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Laporan Rekapitulasi Kabupaten.xlsx"');
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
$objWriter->save('../xls/Laporan Rekapitulasi Kabupaten.xlsx');
$response = array( 'success' => true, 'url' => './xls/Laporan Rekapitulasi Kabupaten.xlsx' );
header('Content-type: application/json');
// and in the end you respond back to javascript the file location
echo json_encode($response);

mysql_close();
exit;
