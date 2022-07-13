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

foreach (glob("../xls/Laporan Penyaluran Barang*.*") as $filename) {
    unlink($filename);
}


$peran = cekLogin();

$id = isset($_POST['id']) ? $_POST['id'] : '';
$pengirim = isset($_POST['pengirim']) ? $_POST['pengirim'] : '';
$penerima = isset($_POST['penerima']) ? $_POST['penerima'] : '';
$id_pengirim = isset($_POST['id_pengirim']) ? $_POST['id_pengirim'] : '';
$id_penerima = isset($_POST['id_penerima']) ? $_POST['id_penerima'] : '';
$tgl_terima = isset($_POST['tgl_terima']) ? $_POST['tgl_terima'] : '';
$id_gudang = isset($_POST['id_gudang']) ? $_POST['id_gudang'] : '';


$skpd1 = mysql_fetch_assoc(mysql_query("SELECT nm_sub2_unit, kd_sub, uuid_sub2_unit FROM ref_sub2_unit WHERE uuid_sub2_unit = '$id_pengirim' "));
if($skpd1['kd_sub']==1){
	$in = "(8,10)"; $kep = 8; $txtPengurus = "Pengurus Barang";
}else{
	$in = "(9,11)"; $kep = 9;  $txtPengurus = "Bendahara Pengeluaran Barang";
}
$b1 = " AND d.uuid_skpd = '$id_pengirim'";

	
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


$skpd2 = mysql_fetch_assoc(mysql_query("SELECT nm_sub2_unit, kd_sub, uuid_sub2_unit FROM ref_sub2_unit WHERE uuid_sub2_unit = '$id_penerima' "));
if($skpd2['kd_sub']==1){
	$in = "(8,10)"; $kep = 8; $txtPengurus2= "Pengurus Barang";
}else{
	$in = "(9,11)"; $kep = 9;  $txtPengurus2 = "Bendahara Pengeluaran Barang";
}
$b2 = " AND d.uuid_skpd = '$id_pengirim'";

if($b2!=""){				
	$pejabat = mysql_query("SELECT nama_pejabat, nip, id_jabatan FROM pejabat d WHERE id_jabatan IN $in $b2");
	while($t = mysql_fetch_assoc($pejabat)){
		if($t['id_jabatan']==$kep){ $kepala2 = $t['nama_pejabat']; $nipk2 = $t['nip']; }
		else{ $pengurus2 = $t['nama_pejabat']; $nipp2 = $t['nip']; }
	}				
}else{
	$kepala2 = ".......................";
	$pengurus2 = ".......................";
	$nipk2 = $nipp2 = ".......................";
}



$skpd2 = mysql_fetch_assoc(mysql_query("SELECT nm_sub2_unit, kd_sub, uuid_sub2_unit FROM ref_sub2_unit WHERE uuid_sub2_unit = '$id_penerima' "));

// Create new PHPExcel object
$objPHPExcel = PHPExcel_IOFactory::load("./laporan_penyaluran.xlsx");

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
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(70);	
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);	
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
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
$objPHPExcel->getActiveSheet()->getStyle('A6:D7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A6:D7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);	
//$objPHPExcel->getActiveSheet()->getStyle('A6:J8')->getAlignment()->setWrapText(true);

$g = mysql_fetch_assoc(mysql_query("SELECT * FROM ref_gudang WHERE id_gudang = '$id_gudang' "));
	
$objPHPExcel->getActiveSheet()
		->setCellValue("B3", "$pengirim")
		->setCellValue("B4", "$penerima")
		->setCellValue("D3", "$tgl_terima")
		->setCellValue("D4", "$g[nama_gudang]");

$clause = "SELECT nama_barang AS nama_bar, k.id_barang AS id_bar, FORMAT(jml_barang, 0,'de_DE') AS jumlah, jml_barang,
			simbol AS nama_sat, b.id_satuan AS id_sat, FORMAT(harga_barang, 0,'de_DE') AS harga, 
			id_keluar_detail AS id, (harga_barang*jml_barang) AS jmlhrg_asli,
			harga_barang AS harga_asli,	FORMAT((harga_barang*jml_barang), 0,'de_DE') AS jmlhrg
			FROM keluar_detail k
			LEFT JOIN ref_barang b ON k.id_barang = b.id_barang 
			LEFT JOIN ref_satuan s ON b.id_satuan = s.id_satuan 
			WHERE id_keluar = '$id' AND k.soft_delete=0";

$rs = mysql_query("$clause");
$brs = 8; $no = 1; $jtot = 0;
while($row = mysql_fetch_assoc($rs)){
	
	$objPHPExcel->getActiveSheet()
            ->setCellValue('A'.$brs, "$no")
            ->setCellValue('B'.$brs, "$row[nama_bar]")
			->setCellValue('C'.$brs, "$row[harga]")
            ->setCellValue('D'.$brs, "$row[jml_barang]")
            ->setCellValue('E'.$brs, "$row[nama_sat]")
            ->setCellValue('F'.$brs, "$row[jmlhrg_asli]");
	$jtot += $row["jmlhrg_asli"];
	$brs++;	$no++;	
}

$objPHPExcel->getActiveSheet()
		->setCellValue('E'.$brs, "TOTAL")
		->setCellValue('F'.$brs, $jtot);
$row = $brs;
//$objPHPExcel->getActiveSheet()->getStyle("K9:K$row")->getAlignment()->setWrapText(true);
//$objPHPExcel->getActiveSheet()->getStyle("M9:M$row")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("A8:A$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("C8:C$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//$objPHPExcel->getActiveSheet()->getStyle("A9:N$row")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);				
$objPHPExcel->getActiveSheet()->getStyle("C8:F$row")->getNumberFormat()->setFormatCode("#,##0.00");			
//tulis border

$objPHPExcel->getActiveSheet()->getStyle("A6:F$row")->applyFromArray($BStyle);
$objPHPExcel->getActiveSheet()->getStyle("A7:F7")->applyFromArray($BTStyle);


//tulis footer
$row+=2; $date = tgl_indo(date('Y-m-d'));
$objPHPExcel->getActiveSheet()
            ->setCellValue("K$row", "Karanganyar, $date");
$row++;
$objPHPExcel->getActiveSheet()
            ->setCellValue("C$row", $txtPengurus2)
            ->setCellValue("E$row", $txtPengurus);
$row++;
$objPHPExcel->getActiveSheet()
            ->setCellValue("C$row", $pengirim)
            ->setCellValue("E$row", $penerima);
$row+=5;
$objPHPExcel->getActiveSheet()
            ->setCellValue("C$row", "( $kepala )")
            ->setCellValue("E$row", "( $pengurus )");
$row++;
$objPHPExcel->getActiveSheet()
            ->setCellValue("C$row", "NIP $nipk")
            ->setCellValue("E$row", "NIP $nipp");

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Laporan Penyaluran Barang');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Laporan Penyaluran Barang.xlsx"');
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
$objWriter->save('../xls/Laporan Penyaluran Barang.xlsx');
$response = array( 'success' => true, 'url' => './xls/Laporan Penyaluran Barang.xlsx' );
header('Content-type: application/json');
// and in the end you respond back to javascript the file location
echo json_encode($response);

mysql_close();
exit;
