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


foreach (glob("../xls/Kartu Barang*.*") as $filename) {
    unlink($filename);
}


$peran = cekLogin();
	
$kepala = $penyimpan = $nipk = $nipp = "";
/* $id_sub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
$thn = isset($_POST['thn']) ? $_POST['thn'] : '';
$bln = isset($_POST['bln']) ? str_pad($_POST['bln'],2,'0', STR_PAD_LEFT) : '';
$id_bar = isset($_POST['id_bar']) ? $_POST['id_bar'] : '';
 */
$idsub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
$ta = isset($_POST['ta']) ? $_POST['ta'] : '';
$dari_tgl = isset($_POST['dari_tgl']) ? $_POST['dari_tgl']!="" ? balikTanggal($_POST['dari_tgl']) : '' : '';
$sampai_tgl = isset($_POST['sampai_tgl']) ? $_POST['sampai_tgl']!="" ? balikTanggal($_POST['sampai_tgl']) : '' : '';
$barang = isset($_POST['barang']) ? $_POST['barang'] : '';
$dari_gud = isset($_POST['dari_gud']) ? $_POST['dari_gud'] : '';
$ke_gud = isset($_POST['ke_gud']) ? $_POST['ke_gud'] : '';

$kepala = $pengurus = $nipk = $nipp = "";
if($idsub!=""){
	$a = " AND m.uuid_skpd = '$idsub'";
	$a1 = " AND d.uuid_sub2_unit = '$idsub'";
}else{
	$a = " AND MD5(m.uuid_skpd) = '$_SESSION[uidunit]'";
	$a1 = " AND MD5(d.uuid_sub2_unit) = '$_SESSION[uidunit]'";
}
if($ta!="") $b = " AND m.ta = '$ta'";
else $b = "";
if($dari_tgl!="" && $sampai_tgl) $c = " AND DATE_FORMAT(tgl_ba_mutasi, '%Y-%m-%d') BETWEEN '$dari_tgl' AND '$sampai_tgl'";
else $c = "";
if($barang!="") $d = " AND d.id_barang = '$barang'";
else $d = "";
if($dari_gud!="") $e = " AND gudang_asal = '$dari_gud'";
else $e = "";
if($ke_gud!="") $f = " AND gudang_tujuan = '$ke_gud'";
else $f = "";

$where = "$a $b $c $d $e $f";
$data = mysql_query("SELECT nm_sub2_unit AS nama_unit, m.ta, no_ba_mutasi AS nomor, tgl_ba_mutasi AS tanggal, 
				IFNULL(nama_barang_kegiatan, nama_barang) barang, d.jml_barang AS jumlah,
				(SELECT nama_gudang FROM ref_gudang WHERE gudang_asal = id_gudang) AS dari_gud,
				(SELECT nama_gudang FROM ref_gudang WHERE gudang_tujuan = id_gudang) AS ke_gud
				FROM mutasi m
				LEFT JOIN mutasi_detail d ON  m.id_mutasi = d.id_mutasi
				LEFT JOIN ref_sub2_unit u
				ON m.uuid_skpd = u.uuid_sub2_unit
				LEFT JOIN ref_barang b ON b.id_barang = d.id_barang
				LEFT JOIN ref_barang_kegiatan bk ON bk.id_barang_kegiatan = d.id_barang
				WHERE m.soft_delete=0 AND d.soft_delete=0 $where");
$skpd = mysql_fetch_assoc(mysql_query("SELECT d.nm_sub2_unit, kd_sub, uuid_sub2_unit FROM ref_sub2_unit d WHERE d.kd_sub IS NOT NULL $a1"));
if($skpd['kd_sub']==1 || $skpd['uuid_sub2_unit'] == 'cfa58008-5543-11e6-a2df-000476f4fa98'){
	$in = "(8,10)"; $kep = 8; $txtPengurus = "Pengurus Barang";
}else{
	$in = "(9,11)"; $kep = 9;  $txtPengurus = "Pembantu Pengurus Barang";
}
if($a!=""){				
	$pejabat = mysql_query("SELECT nama_pejabat, nip, id_jabatan FROM pejabat m WHERE id_jabatan IN $in $a");
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
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);							 
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(21);	
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(8);	
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(13);	
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(14);	
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(21);	
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(18);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(18);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(16);
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
$objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A3:I4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A3:I4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);	
//$objPHPExcel->getActiveSheet()->getStyle('G7:J9')->getAlignment()->setWrapText(true);

// Add HEADER
$objPHPExcel->getActiveSheet()
            ->setCellValue('A1', 'DAFTAR MUTASI GUDANG')
			->mergeCells('A1:J1');

$objPHPExcel->getActiveSheet()
            ->setCellValue('A3', 'NO')
            ->setCellValue('B3', "SKPD / Sub Unit")
			->setCellValue('C3', "TA")
			->setCellValue('D3', "TANGGAL")
			->setCellValue('E3', "NOMOR")
			->setCellValue('F3', "NAMA BARANG")
			->setCellValue('G3', "DARI TEMPAT")
			->setCellValue('H3', "KE TEMPAT")
			->setCellValue('I3', "JUMLAH BARANG");	

$objPHPExcel->getActiveSheet()
            ->setCellValue('A4', '1')
            ->setCellValue('B4', '2')
			->setCellValue('C4', '3')
			->setCellValue('D4', '4')
            ->setCellValue('E4', '5')
            ->setCellValue('F4', '6')
            ->setCellValue('G4', '7')
            ->setCellValue('H4', '8')
            ->setCellValue('I4', '9');

$row = 5; $no = 1;
while($d =mysql_fetch_assoc($data)){
	$tanggal = balikTanggalIndo($d['tanggal']);
	$jumlah = number_format($d['jumlah'], 0, ',', '.');
	
	$objPHPExcel->getActiveSheet()
            ->setCellValue('A'.$row, "$no")
            ->setCellValue('B'.$row, "$d[nama_unit]")
			->setCellValue('C'.$row, "$d[ta]")
			->setCellValue('D'.$row, "$tanggal")
            ->setCellValue('E'.$row, "$d[nomor]")
            ->setCellValue('F'.$row, "$d[barang]")
            ->setCellValue('G'.$row, "$d[dari_gud]")
            ->setCellValue('H'.$row, "$d[ke_gud]")
            ->setCellValue('I'.$row, "$jumlah");
	
	$row++;	$no++;	
}
	
$objPHPExcel->getActiveSheet()->getStyle("B5:B$row")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("A5:A$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("C5:C$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("I5:I$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("A5:I$row")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);				
$objPHPExcel->getActiveSheet()->getStyle("I5:I$row")->getNumberFormat()->setFormatCode("#,##0.00");
			
//tulis border
$row--;
$objPHPExcel->getActiveSheet()->getStyle("A3:I$row")->applyFromArray($BStyle);
$objPHPExcel->getActiveSheet()->getStyle("A4:I4")->applyFromArray($BTStyle);


//tulis footer
$row+=2; $date = tgl_indo(date('Y-m-d'));
$objPHPExcel->getActiveSheet()
            ->setCellValue("G$row", "Karanganyar, $date");
$row++;
$objPHPExcel->getActiveSheet()
            ->setCellValue("B$row", 'Kepala SKPD/Unit Kerja')
            ->setCellValue("G$row", "$txtPengurus");
$row+=3;
$objPHPExcel->getActiveSheet()
            ->setCellValue("B$row", "( $kepala )")
            ->setCellValue("G$row", "( $pengurus )");
$row++;
$objPHPExcel->getActiveSheet()
            ->setCellValue("B$row", "NIP $nipk")
            ->setCellValue("G$row", "NIP $nipp");
/* $row++;			
$objPHPExcel->getActiveSheet()->setCellValue("F$row", 'BUPATI KARANGANYAR,');
$objPHPExcel->getActiveSheet()->getStyle("F$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$row+=3;
$objPHPExcel->getActiveSheet()->setCellValue("F$row", 'JULIYATMONO');
$objPHPExcel->getActiveSheet()->getStyle("F$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
 */
			
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Kartu Barang');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Kartu Barang.xlsx"');
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
$objWriter->save('../xls/Kartu Barang.xlsx');
$response = array( 'success' => true, 'url' => './xls/Kartu Barang.xlsx' );
header('Content-type: application/json');
// and in the end you respond back to javascript the file location
echo json_encode($response);

mysql_close();
exit;
