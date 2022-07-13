<?php

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

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


foreach (glob("../xls/Laporan Mutasi Semesteran*.*") as $filename) {
    unlink($filename);
}


$peran = cekLogin();
	
$kepala = $pengurus = $nipk = $nipp = "";
$id_sub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
$basket = isset($_POST['basket']) ? $_POST['basket'] : '';
$smstr = isset($_POST['smstr']) ? $_POST['smstr'] : '';
$ta = isset($_POST['ta']) ? $_POST['ta'] : '';

if($id_sub!=""){
	$b = " AND uuid_sub2_unit = '$id_sub'";
	$b1 = " AND d.uuid_skpd = '$id_sub'";
}else{ $b = " AND MD5(uuid_sub2_unit) = '$_SESSION[uidunit]'";
		$b1 = " AND MD5(d.uuid_skpd) = '$_SESSION[uidunit]'"; }		
$skpd = mysql_fetch_assoc(mysql_query("SELECT nm_sub2_unit, kd_sub, uuid_sub2_unit FROM ref_sub2_unit WHERE kd_sub IS NOT NULL $b"));
if($skpd['kd_sub']==1 || $skpd['uuid_sub2_unit'] == 'cfa58008-5543-11e6-a2df-000476f4fa98'){
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
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);	
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);	
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);	
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);	
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);	
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);	
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
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
$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A6:J8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A6:J8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);	
$objPHPExcel->getActiveSheet()->getStyle('A6:J8')->getAlignment()->setWrapText(true);

// Add HEADER
$objPHPExcel->getActiveSheet()
            ->setCellValue('A1', 'LAPORAN SEMESTERAN MUTASI BARANG PERSEDIAAN')
			->mergeCells('A1:J1');
$objPHPExcel->getActiveSheet()
            ->setCellValue('A3', 'SKPD/Unit Kerja')
            ->setCellValue('C3', ": $skpd[nm_sub2_unit] ")
            ->setCellValue('A4', 'Semester')
            ->setCellValue('C4', ": $smstr");
$objPHPExcel->getActiveSheet()
            ->setCellValue('A6', 'NO')
			->mergeCells('A6:A7')
			->setCellValue('B6', "JENIS/NAMA BARANG")
			->mergeCells('B6:C7')
            ->setCellValue('D6', "SALDO AWAL\nSEMESTER $smstr")
			->mergeCells('D6:D7')
            ->setCellValue('E6', "MUTASI Semester $smstr")
			->mergeCells('E6:F6')
			->setCellValue('E7', "TAMBAH")
			->setCellValue('F7', "DIGUNAKAN")
			->setCellValue('G6', "SALDO AKHIR KOMULATIF")
			->mergeCells('G6:I6')
			->setCellValue('G7', "SALDO")
			->setCellValue('H7', "Harga Beli\nterakhir (Rp)")
			->setCellValue('I7', "Jumlah (Rp)")
			->setCellValue('J6', "KET")
			->mergeCells('J6:J7');	

$objPHPExcel->getActiveSheet()
            ->setCellValue('A8', '1')
            ->setCellValue('B8', '2')
			->mergeCells('B8:C8')
            ->setCellValue('D8', '3')
            ->setCellValue('E8', '4')
            ->setCellValue('F8', '5')
            ->setCellValue('G8', '6')
            ->setCellValue('H8', '7')
            ->setCellValue('I8', '8')
            ->setCellValue('J8', '9');

$row = 9; $no = 1; $totalJenis = 0; $ttotal = 0; $kode = ""; $jml = count($basket);
foreach($basket AS $d){
	$saldo_awal = preg_replace("/[^0-9]/","", $d['saldo_awal']);
	$jml_masuk = preg_replace("/[^0-9]/","", $d['jml_masuk']);
	$jml_keluar = preg_replace("/[^0-9]/","", $d['jml_keluar']);
	$saldo_akhir = preg_replace("/[^0-9]/","", $d['saldo_akhir']);
	$harga = preg_replace("/[^0-9]/","", $d['harga']);
	$total = preg_replace("/[^0-9]/","", $d['total']);
	if($kode!=$d['kode_bar']){
		if($no!=1){
			$objPHPExcel->getActiveSheet()
				->mergeCells("A$row:H$row")
				->setCellValue('A'.$row, "SUBTOTAL")
				->setCellValue('I'.$row, "$totalJenis");
			$row++;
		}
		$totalJenis = 0;
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, "$d[jenis]")->mergeCells("B$row:C$row");
		$row++;	
	}
	$totalJenis += $total;
	
	$objPHPExcel->getActiveSheet()
            ->setCellValue('A'.$row, "$no")
            ->setCellValue('B'.$row, "$d[nama_barang]")
			->mergeCells("B$row:C$row")
            ->setCellValue('D'.$row, "$saldo_awal")
            ->setCellValue('E'.$row, "$jml_masuk")
            ->setCellValue('F'.$row, "$jml_keluar")
            ->setCellValue('G'.$row, "$saldo_akhir")
			->setCellValue('H'.$row, "$harga")
            ->setCellValue('I'.$row, "$total")
            ->setCellValue('J'.$row, "");
	$kode = $d['kode_bar'];
	$row++;	
	if($jml==$no){
		$objPHPExcel->getActiveSheet()
			->mergeCells("A$row:H$row")
			->setCellValue('A'.$row, "SUBTOTAL")
			->setCellValue('I'.$row, "$totalJenis");
		$totalJenis = 0;
		$row++;
	}	
	$no++;	
	$ttotal += $total;
}

$objPHPExcel->getActiveSheet()
            ->setCellValue('A'.$row, "JUMLAH TOTAL")
			->mergeCells("A$row:H$row")
            ->setCellValue('I'.$row, "$ttotal");
   
//$objPHPExcel->getActiveSheet()->getStyle("K9:K$row")->getAlignment()->setWrapText(true);
//$objPHPExcel->getActiveSheet()->getStyle("M9:M$row")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("A9:A$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("D9:G$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//$objPHPExcel->getActiveSheet()->getStyle("A9:N$row")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);				
$objPHPExcel->getActiveSheet()->getStyle("D9:I$row")->getNumberFormat()->setFormatCode("#,##0.00");			

//tulis border
//$row--;
$objPHPExcel->getActiveSheet()->getStyle("A6:J$row")->applyFromArray($BStyle);
$objPHPExcel->getActiveSheet()->getStyle("A8:J8")->applyFromArray($BTStyle);


//tulis footer
$row+=2; $date = tgl_indo(date('Y-m-d'));
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
            ->setCellValue("I$row", "NIP $nipp");
/* $row++;			
$objPHPExcel->getActiveSheet()->setCellValue("F$row", 'BUPATI KARANGANYAR,');
$objPHPExcel->getActiveSheet()->getStyle("F$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$row+=3;
$objPHPExcel->getActiveSheet()->setCellValue("F$row", 'JULIYATMONO');
$objPHPExcel->getActiveSheet()->getStyle("F$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
 */
			
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Laporan Mutasi Semesteran');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Laporan Mutasi Semesteran.xlsx"');
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
$objWriter->save('../xls/Laporan Mutasi Semesteran.xlsx');
$response = array( 'success' => true, 'url' => './xls/Laporan Mutasi Semesteran.xlsx' );
header('Content-type: application/json');
// and in the end you respond back to javascript the file location
echo json_encode($response);

mysql_close();
exit;
