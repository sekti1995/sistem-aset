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

foreach (glob("../xls/Kartu Persediaan*.*") as $filename) {
    unlink($filename);
}


$peran = cekLogin();
	
$kepala = $pengurus = $nipk = $nipp = "";
$id_sub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
$idgud = isset($_POST['idgud']) ? $_POST['idgud'] : '';
$idbar = isset($_POST['idbar']) ? $_POST['idbar'] : '';
$idsum = isset($_POST['idsum']) ? $_POST['idsum'] : '';
$basket = isset($_POST['basket']) ? $_POST['basket'] : '';
$ta = isset($_POST['ta']) ? $_POST['ta'] : '';
$sumber = isset($_POST['sumber']) ? $_POST['sumber'] : '';
  
$gud = mysql_fetch_assoc(mysql_query("SELECT * FROM ref_gudang WHERE id_gudang = '$idgud'"));

if($id_sub!=""){
	$b = " AND uuid_sub2_unit = '$id_sub'";
	$b1 = " AND d.uuid_skpd = '$id_sub'";
}else{ $b = " AND MD5(uuid_sub2_unit) = '$_SESSION[uidunit]'";
		$b1 = " AND MD5(d.uuid_skpd) = '$_SESSION[uidunit]'"; }	
$skpd = mysql_fetch_assoc(mysql_query("SELECT nm_sub2_unit, kd_sub, uuid_sub2_unit FROM ref_sub2_unit WHERE kd_sub IS NOT NULL $b"));
if($skpd['kd_sub']==1 || $skpd['uuid_sub2_unit'] == 'cfa58008-5543-11e6-a2df-000476f4fa98'){
	$in = "(8,10)"; $kep = 8; $txtPengurus = "Pengurus Barang";
}else{
	$in = "(1,2)"; $kep = 1;  $txtPengurus = "Pembantu Pengurus Barang";
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
	
$qbar = mysql_query("SELECT nama_barang, nama_satuan, keterangan 
						FROM ref_barang b
						LEFT JOIN ref_satuan s ON b.id_satuan = s.id_satuan
						WHERE id_barang = '$idbar'");
if(mysql_num_rows($qbar)==0){
	$qbar = mysql_query("SELECT nama_barang_kegiatan AS nama_barang, nama_satuan, keterangan 
						FROM ref_barang_kegiatan b
						LEFT JOIN ref_satuan s ON b.id_satuan = s.id_satuan
						WHERE id_barang_kegiatan = '$idbar'");
}
$bar = mysql_fetch_assoc($qbar);
$barang = $bar['nama_barang'];
$satuan = $bar['nama_satuan'];
$keterangan = $bar['keterangan'];	
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
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);	
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(19);	
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);	
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);	
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);	
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
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
$objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A8:L10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A8:L10')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);	
$objPHPExcel->getActiveSheet()->getStyle('A8:L9')->getAlignment()->setWrapText(true);

// Add HEADER
$objPHPExcel->getActiveSheet()
            ->setCellValue('A1', 'KARTU PERSEDIAAN BARANG')
			->mergeCells('A1:L1');
$objPHPExcel->getActiveSheet()
            ->setCellValue('A3', 'SKPD/UNIT KERJA')
            ->setCellValue('D3', ": $skpd[nm_sub2_unit]")
            ->setCellValue('A4', 'GUDANG/TEMPAT PENYIMPANAN')
            ->setCellValue('D4', ": $gud[nama_gudang]")
			->setCellValue('A5', 'NAMA BARANG')
            ->setCellValue('D5', ": $barang")
			->setCellValue('A6', 'SUMBER DANA')
            ->setCellValue('D6', ": $sumber")
			->setCellValue('H4', 'SATUAN')
            ->setCellValue('I4', ": $satuan")
			->setCellValue('H5', 'SPESIFIKASI')
            ->setCellValue('I5', ": ");
$objPHPExcel->getActiveSheet()
            ->setCellValue('A8', 'No')
            ->mergeCells('A8:A9')
            ->setCellValue('B8', "Tanggal")
			->mergeCells('B8:B9')
            ->setCellValue('C8', "No./Tgl Surat Dasar\nPenerimaan/\nPengeluaran")
			->mergeCells('C8:C9')
            ->setCellValue('D8', "Uraian")
			->mergeCells('D8:D9')
            ->setCellValue('E8', "Barang - Barang")
			->mergeCells('E8:G8')
            ->setCellValue('E9', "Masuk")
            ->setCellValue('F9', "Keluar")
            ->setCellValue('G9', "Sisa")
            ->setCellValue('H8', "Harga Satuan")
			->mergeCells('H8:H9')
            ->setCellValue('I8', "Jumlah Harga")
            ->setCellValue('I9', "Bertambah")
            ->setCellValue('J9', "Berkurang")
            ->setCellValue('K9', "Sisa")
			->mergeCells('I8:K8')
            ->setCellValue('L8', "Ket.")
			->mergeCells('L8:L9');	

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
            ->setCellValue('L10', '12');

$row = 11; $no = 1; $saldo = 0; $sisa = 0;
foreach($basket['rows'] AS $d){
	$objPHPExcel->getActiveSheet()
            ->setCellValue('A'.$row, "$no")
            ->setCellValue('B'.$row, "$d[tanggal]")
            ->setCellValue('C'.$row, "$d[notgl_surat]")
			->setCellValue('D'.$row, "$d[uraian]")
            ->setCellValue('E'.$row, "$d[jml_masuk]")
            ->setCellValue('F'.$row, "$d[jml_keluar]")
            ->setCellValue('G'.$row, "$d[saldo]")
            ->setCellValue('H'.$row, "$d[hrg_masuk]")
            ->setCellValue('I'.$row, "$d[bertambah]")
            ->setCellValue('J'.$row, "$d[berkurang]")
            ->setCellValue('K'.$row, "$d[sisa]")
            ->setCellValue('L'.$row, " ");
	
	$row++;	$no++;	
}
	
$objPHPExcel->getActiveSheet()->getStyle("D11:D$row")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("A11:A$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("E11:G$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("A11:C$row")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);				
$objPHPExcel->getActiveSheet()->getStyle("E11:L$row")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);				
$objPHPExcel->getActiveSheet()->getStyle("E11:L$row")->getNumberFormat()->setFormatCode("#,##0.00");
			
//tulis border
$row--;
$objPHPExcel->getActiveSheet()->getStyle("A8:L$row")->applyFromArray($BStyle);
$objPHPExcel->getActiveSheet()->getStyle("A10:L10")->applyFromArray($BTStyle);


//tulis footer
$row+=2; $date = tgl_indo(date('Y-m-d'));
$objPHPExcel->getActiveSheet()
            ->setCellValue("J$row", "Karanganyar, $date");
$row++;
$objPHPExcel->getActiveSheet()
            ->setCellValue("C$row", "Kepala ".$skpd[nm_sub2_unit])
            ->setCellValue("J$row", "$txtPengurus");
$row+=3;
$objPHPExcel->getActiveSheet()
            ->setCellValue("C$row", "( $kepala )")
            ->setCellValue("J$row", "( $pengurus )");
$row++;
$objPHPExcel->getActiveSheet()
            ->setCellValue("C$row", "NIP $nipk")
            ->setCellValue("J$row", "NIP $nipp");

			
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Kartu Persediaan');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Kartu Persediaan.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0


$now = date("YmdHis");

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//$objWriter->save('php://output');
$objWriter->save('../xls/Kartu Persediaan '.$now.'.xlsx');
$response = array( 'success' => true, 'url' => './xls/Kartu Persediaan '.$now.'.xlsx' );
header('Content-type: application/json');
// and in the end you respond back to javascript the file location
echo json_encode($response);

mysql_close();
exit;
