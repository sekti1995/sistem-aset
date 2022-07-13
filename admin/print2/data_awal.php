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

foreach (glob("../xls/Data Awal Persediaan*.*") as $filename) {
    unlink($filename);
}
$versi = isset($_POST['versi']) ? $_POST['versi'] : '';
	
$data = mysql_query("SELECT CONCAT_WS('.', kd_kel, kd_sub, kd_sub2) AS kode, nama_barang, simbol, id_barang
						FROM ref_barang b, ref_jenis j, ref_satuan s WHERE soft_delete = 0 AND b.id_jenis = j.id_jenis 
						AND s.id_satuan = b.id_satuan ORDER BY kd_kel, kd_sub, kd_sub2");

	
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



$objPHPExcel->getActiveSheet()
            ->setCellValue('A1', 'NO')
            ->setCellValue('B1', 'KODE BARANG')
            ->setCellValue('C1', 'NAMA BARANG')
            ->setCellValue('D1', 'SATUAN')
            ->setCellValue('E1', 'SALDO AWAL')
            ->setCellValue('F1', 'HARGA')
            ->setCellValue('G1', 'TOTAL');
			

$row = 2;
while($d =mysql_fetch_assoc($data)){
	$objPHPExcel->getActiveSheet()
				->setCellValue('A'.$row, "$d[id_barang]")
				->setCellValue('B'.$row, "$d[kode]")
				->setCellValue('C'.$row, "$d[nama_barang]")
				->setCellValue('D'.$row, "$d[simbol]");
	
	$row++;
}
	
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Data Awal Persediaan');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="Data Awal Persediaan.csv"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
if($versi=='baru') $objWriter->setDelimiter(';');  // Define delimiter
//$objWriter->save('php://output');
$objWriter->save('../xls/Data Awal Persediaan.csv');
$response = array( 'success' => true, 'url' => './xls/Data Awal Persediaan.csv' );
header('Content-type: application/json');
// and in the end you respond back to javascript the file location
echo json_encode($response);

mysql_close();
exit;
