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
$sumber = isset($_POST['sumber']) ? $_POST['sumber'] : '';
$data = "";

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
// echo "SELECT m.nm_sub2_unit, kd_sub FROM ref_sub2_unit m WHERE m.kd_sub IS NOT NULL $b";


if($skpd['kd_sub']==1 || $data['uuid_skpd'] == 'cfa58008-5543-11e6-a2df-000476f4fa98'){
	$in = "(8,10)"; $kep = 8; $txtPengurus = "Pengurus Barang";
}else{
	$in = "(1,2)"; $kep = 1;  $txtPengurus = "Pembantu Pengurus Barang";
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
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12.1);	
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15.9);	
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(18.1);	
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
$objPHPExcel->getActiveSheet()->getStyle('A7:N10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A7:N9')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);	
$objPHPExcel->getActiveSheet()->getStyle('A7:N9')->getAlignment()->setWrapText(true);

// Add HEADER
$objPHPExcel->getActiveSheet()
            ->setCellValue('A1', 'BUKU BARANG PERSEDIAAN')
			->mergeCells('A1:N1');
$objPHPExcel->getActiveSheet()
            ->setCellValue('A3', 'SKPD/Unit Kerja')
            ->setCellValue('C3', ": $skpd[nm_sub2_unit] ")
            ->setCellValue('A4', 'Kabupaten')
            ->setCellValue('C4', ': Karanganyar')
			->setCellValue('A5', 'Sumber Dana')
            ->setCellValue('C5', ": $sumber");
$objPHPExcel->getActiveSheet()
            ->setCellValue('A7', 'NO')
			->mergeCells('A7:A9')
			->setCellValue('B7', "PENERIMAAN")
			->mergeCells('B7:I7')
            ->setCellValue('B8', "TANGGAL\nDITERIMA")
			->mergeCells('B8:B9')
            ->setCellValue('C8', "JENIS/NAMA\nBARANG")
			->mergeCells('C8:C9')
			->setCellValue('D8', "MERK/\nUKURAN")
			->mergeCells('D8:D9')
			->setCellValue('E8', "TAHUN\nPEMBUATAN")
			->mergeCells('E8:E9')
			->setCellValue('F8', "JUMLAH\nSATUAN/\nBARANG")
			->mergeCells('F8:F9')
			->setCellValue('G8', "TGL/NO\nKONTRAK/SP/\nSPK/HARGA\nSATUAN")
			->mergeCells('G8:G9')
			->setCellValue('H8', "BERITA ACARA\nPEMERIKSAAN")
			->mergeCells('H8:I8')
			->setCellValue('H9', "TANGGAL")
			->setCellValue('I9', "NOMOR")	
			->setCellValue('J7', "PENGELUARAN")
			->mergeCells('J7:N7')
			->setCellValue('J8', "TANGGAL\nDIKELUARKAN")
			->mergeCells('J8:J9')
			->setCellValue('K8', "DISERAHKAN\nKEPADA")
			->mergeCells('K8:K9')
			->setCellValue('L8', "JUMLAH\nSATUAN/\nBARANG")
			->mergeCells('L8:L9')
			->setCellValue('M8', "TGL/NO SURAT\nPENYERAHAN")
			->mergeCells('M8:M9')
			->setCellValue('N8', "KET")	
			->mergeCells('N8:N9');	

$objPHPExcel->getActiveSheet()
            ->setCellValue('A10', '1')
            ->setCellValue('B10', '2')
            ->setCellValue('C10', '3')
			->setCellValue('D10', '4')
            ->setCellValue('E10', '5')
            ->setCellValue('F10', '6')
            ->setCellValue('G10', '7')
            ->setCellValue('H10', '8')
            ->setCellValue('I10', '9')
            ->setCellValue('J10', '10')
            ->setCellValue('K10', '11')
            ->setCellValue('L10', '12')
            ->setCellValue('M10', '13')
            ->setCellValue('N10', '14');

$row = 11; $no = 1;
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
    
$objPHPExcel->getActiveSheet()->getStyle("C11:C$row")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("G11:G$row")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("K11:K$row")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("M11:M$row")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("A11:A$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("F11:F$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("L11:L$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("A11:N$row")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);				
$objPHPExcel->getActiveSheet()->getStyle("F11:F$row")->getNumberFormat()->setFormatCode("#,##0.00");	
$objPHPExcel->getActiveSheet()->getStyle("L11:L$row")->getNumberFormat()->setFormatCode("#,##0.00");	
			
//tulis border
$row--;
$objPHPExcel->getActiveSheet()->getStyle("A7:N$row")->applyFromArray($BStyle);
$objPHPExcel->getActiveSheet()->getStyle("A10:N10")->applyFromArray($BTStyle);


//tulis footer
$row+=3; $date = tgl_indo(date('Y-m-d'));
$objPHPExcel->getActiveSheet()
            ->setCellValue("L$row", "Karanganyar, $date");
$row++;
$objPHPExcel->getActiveSheet()
            ->setCellValue("C$row", "$skpd[nm_sub2_unit]")
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
