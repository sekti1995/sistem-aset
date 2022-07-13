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

foreach (glob("../xls/Daftar Sisa Persediaan*.*") as $filename) {
    unlink($filename);
}


$peran = cekLogin();
	
$kepala = $pengurus = $nipk = $nipp = "";
$id_sub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
$skpd = isset($_POST['skpd']) ? $_POST['skpd'] : '';
$basket = isset($_POST['basket']) ? $_POST['basket'] : '';
$bulan = isset($_POST['bulan']) ? $_POST['bulan'] : '';

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
$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_FOLIO);
$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.75);
$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.25);
$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.25);
$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.75);

//cell size
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(4);							 
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(13);	
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(29);	
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(9);	
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(13);	
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(11);	
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
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
$objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A6:G7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A6:G6')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);	
$objPHPExcel->getActiveSheet()->getStyle('E6:F6')->getAlignment()->setWrapText(true);

// Add HEADER
$objPHPExcel->getActiveSheet()
            ->setCellValue('A1', 'DAFTAR SISA PERSEDIAAN BARANG')
			->mergeCells('A1:G1');
$objPHPExcel->getActiveSheet()
            ->setCellValue('A3', 'SKPD/Unit Kerja')
            ->setCellValue('C3', ": $skpd[nm_sub2_unit] ")
            ->setCellValue('A4', 'Bulan')
            ->setCellValue('C4', ": $bulan");
$objPHPExcel->getActiveSheet()
            ->setCellValue('A6', 'No')
			->setCellValue('B6', "Nama Barang")
			->mergeCells('B6:C6')
			->setCellValue('D6', "Satuan")
			->setCellValue('E6', "Harga\nSatuan")
			->setCellValue('F6', "Jumlah\nSatuan")
			->setCellValue('G6', "Harga Total");	

$objPHPExcel->getActiveSheet()
            ->setCellValue('A7', '1')
            ->setCellValue('B7', '2')
			->mergeCells('B7:C7')
            ->setCellValue('D7', '3')
			->setCellValue('E7', '4')
            ->setCellValue('F7', '5')
            ->setCellValue('G7', '6');

$row = 8; $no = 1;  $totalJenis = 0; $ttotal = 0; $kode = ""; $jml = count($basket);
foreach($basket AS $d){
	$jumlah = preg_replace("/[^0-9]/","", $d['jumlah']);
	$harga = preg_replace("/[^0-9]/","", $d['harga']);
	$total = preg_replace("/[^0-9]/","", $d['total']);
	/* if($kode!=$d['kode_bar']){
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
	$totalJenis += $total; */
	
	$objPHPExcel->getActiveSheet()
            ->setCellValue('A'.$row, "$no")
            ->setCellValue('B'.$row, "$d[nama_barang]")
			->mergeCells("B$row:C$row")
			->setCellValue('D'.$row, "$d[satuan]")
			->setCellValue('E'.$row, "$harga")
            ->setCellValue('F'.$row, "$jumlah")
            ->setCellValue('G'.$row, "$total");
	//$kode = $d['kode_bar'];
	$row++;	
	/* if($jml==$no){
		$objPHPExcel->getActiveSheet()
			->mergeCells("A$row:H$row")
			->setCellValue('A'.$row, "SUBTOTAL")
			->setCellValue('I'.$row, "$totalJenis");
		$totalJenis = 0;
		$row++;
	}	 */
	$no++;	
	$ttotal += $total;	
}

$objPHPExcel->getActiveSheet()
            ->setCellValue('A'.$row, "JUMLAH TOTAL")
			->mergeCells("A$row:F$row")
            ->setCellValue('G'.$row, "$ttotal");
    
//$objPHPExcel->getActiveSheet()->getStyle("K9:K$row")->getAlignment()->setWrapText(true);
//$objPHPExcel->getActiveSheet()->getStyle("M9:M$row")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("A8:A$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("F8:F$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//$objPHPExcel->getActiveSheet()->getStyle("A9:N$row")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);				
$objPHPExcel->getActiveSheet()->getStyle("E8:G$row")->getNumberFormat()->setFormatCode("#,##0.00");			
//tulis border
//$row--;
$objPHPExcel->getActiveSheet()->getStyle("A6:G$row")->applyFromArray($BStyle);
$objPHPExcel->getActiveSheet()->getStyle("A7:G7")->applyFromArray($BTStyle);


//tulis footer
$row+=2; $date = tgl_indo(date('Y-m-d'));
$objPHPExcel->getActiveSheet()
            ->setCellValue("E$row", "Karanganyar, $date");
$row++;
$objPHPExcel->getActiveSheet()
            ->setCellValue("B$row", 'Kepala SKPD/Unit Kerja')
            ->setCellValue("E$row", "$txtPengurus");
$row+=3;
$objPHPExcel->getActiveSheet()
            ->setCellValue("B$row", "( $kepala )")
            ->setCellValue("E$row", "( $pengurus )");
$row++;
$objPHPExcel->getActiveSheet()
            ->setCellValue("B$row", "NIP $nipk")
            ->setCellValue("E$row", "NIP $nipp");
/* $row++;			
$objPHPExcel->getActiveSheet()->setCellValue("F$row", 'BUPATI KARANGANYAR,');
$objPHPExcel->getActiveSheet()->getStyle("F$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$row+=3;
$objPHPExcel->getActiveSheet()->setCellValue("F$row", 'JULIYATMONO');
$objPHPExcel->getActiveSheet()->getStyle("F$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
 */
			
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Daftar Sisa Persediaan');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Daftar Sisa Persediaan.xlsx"');
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
$objWriter->save('../xls/Daftar Sisa Persediaan '.$tgl_cetak_now.'.xlsx');
$response = array( 'success' => true, 'url' => './xls/Daftar Sisa Persediaan '.$tgl_cetak_now.'.xlsx' );
header('Content-type: application/json');
// and in the end you respond back to javascript the file location
echo json_encode($response);

mysql_close();
exit;
