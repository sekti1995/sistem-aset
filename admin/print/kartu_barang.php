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

foreach (glob("../xls/Kartu Barang*.*") as $filename) {
    unlink($filename);
}
	
$kepala = $pengurus = $nipk = $nipp = "";
$id_sub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
$id_sumber = isset($_POST['id_sumber']) ? $_POST['id_sumber'] : '';
$thn = isset($_POST['thn']) ? $_POST['thn'] : '';
$bln = isset($_POST['bln']) ? str_pad($_POST['bln'],2,'0', STR_PAD_LEFT) : '';
$id_bar = isset($_POST['id_bar']) ? $_POST['id_bar'] : '';
$sumber = isset($_POST['sumber']) ? $_POST['sumber'] : '';


if($thn!="") $a = " AND ta <= '$thn'";
else $a = "";

if($id_sub==""){
	$b = " AND MD5(uuid_skpd) = '$_SESSION[uidunit]'";
	$b1 = " AND MD5(uuid_sub2_unit) = '$_SESSION[uidunit]'";
}else{
	$b = " AND uuid_skpd = '$id_sub'";
	$b1 = " AND uuid_sub2_unit = '$id_sub'";
}
if($bln!="" AND $bln!="00") $c = " AND DATE_FORMAT(tgl_transaksi, '%m') = '$bln'"; 
else $c = "";	

if($id_sumber!="") $d = " AND id_sumber_dana = '$id_sumber'"; 
else $d = "";

$skpd = mysql_fetch_assoc(mysql_query("SELECT m.nm_sub2_unit, kd_sub, uuid_sub2_unit FROM ref_sub2_unit m WHERE m.kd_sub IS NOT NULL $b1"));
if($skpd['kd_sub']==1 || $skpd['uuid_sub2_unit'] == 'cfa58008-5543-11e6-a2df-000476f4fa98'){
	$in = "(8,10)"; $kep = 8; $txtPengurus = "Pengurus Barang";
}else{
	$in = "(1,2)"; $kep = 1;  $txtPengurus = "Pembantu Pengurus Barang";
}

$data = mysql_query("SELECT id_transaksi, harga, tgl_transaksi AS tanggal, jml_in AS masuk, jml_out AS keluar FROM kartu_stok 
				WHERE soft_delete = 0 AND kode <> 'm' $a $b $c $d
				AND id_barang = '$id_bar' ORDER BY tgl_transaksi, create_date,harga");
$pejabat = mysql_query("SELECT nama_pejabat, nip, id_jabatan FROM pejabat m WHERE id_jabatan IN $in $b");
while($t = mysql_fetch_assoc($pejabat)){
	if($t['id_jabatan']==$kep){ $kepala = $t['nama_pejabat']; $nipk = $t['nip']; }
	else{ $pengurus = $t['nama_pejabat']; $nipp = $t['nip']; }
}				
$qbar = mysql_query("SELECT nama_barang, nama_satuan, keterangan 
						FROM ref_barang b
						LEFT JOIN ref_satuan s ON b.id_satuan = s.id_satuan
						WHERE id_barang = '$id_bar'");
if(mysql_num_rows($qbar)==0){
	$qbar = mysql_query("SELECT nama_barang_kegiatan AS nama_barang, nama_satuan, keterangan 
						FROM ref_barang_kegiatan b
						LEFT JOIN ref_satuan s ON b.id_satuan = s.id_satuan
						WHERE id_barang_kegiatan = '$id_bar'");
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
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8);							 
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(13);	
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(16);	
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(24);	
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(24);	
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(24);	
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
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
$objPHPExcel->getActiveSheet()->getStyle('A10:M11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A10:M11')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);	
//$objPHPExcel->getActiveSheet()->getStyle('G7:J9')->getAlignment()->setWrapText(true);

// Add HEADER
$objPHPExcel->getActiveSheet()
            ->setCellValue('A1', 'KARTU BARANG PERSEDIAAN')
			->mergeCells('A1:G1');
$objPHPExcel->getActiveSheet()
            ->setCellValue('A3', 'SKPD/UNIT KERJA')
            ->setCellValue('C3', ": $skpd[nm_sub2_unit] ")
            ->setCellValue('A4', 'KABUPATEN')
            ->setCellValue('C4', ": KARANGANYAR")
			->setCellValue('A5', 'NAMA BARANG')
            ->setCellValue('C5', ": $barang")
			->setCellValue('A6', 'SATUAN')
            ->setCellValue('C6', ": $satuan")
			->setCellValue('A7', 'SPESIFIKASI')
            ->setCellValue('C7', ": $keterangan")
			->setCellValue('A8', 'Sumber Dana')
            ->setCellValue('C8', ": $sumber");
			
$objPHPExcel->getActiveSheet()
            ->setCellValue('A10', 'NO')
            ->setCellValue('B10', "TANGGAL")
			->mergeCells('B10:C10')
            ->setCellValue('D10', "MASUK")
			->setCellValue('E10', "KELUAR")
			->setCellValue('F10', "SISA")
			->setCellValue('G10', "KETERANGAN");	

$objPHPExcel->getActiveSheet()
            ->setCellValue('A11', '1')
            ->setCellValue('B11', '2')
			->mergeCells('B11:C11')
            ->setCellValue('D11', '3')
            ->setCellValue('E11', '4')
            ->setCellValue('F11', '5')
            ->setCellValue('G11', '6');

$row = 12; $no = 1; $sisa = 0;
while($d =mysql_fetch_assoc($data)){
	$sisa = $sisa + $d['masuk'] - $d['keluar'];
	$tanggal = balikTanggalIndo($d['tanggal']);
	$masuk = number_format($d['masuk'], 0, ',', '.');
	$keluar = number_format($d['keluar'], 0, ',', '.');
	$sisa = number_format($sisa, 0, ',', '.');
	$harga = number_format($d['harga'], 0, '', '');
	
	$masuk = str_replace(".","",$masuk);
	$keluar = str_replace(".","",$keluar);
	$sisa = str_replace(".","",$sisa);
	$harga = str_replace(".","",$harga);
	
	$objPHPExcel->getActiveSheet()
            ->setCellValue('A'.$row, "$no")
            ->setCellValue('B'.$row, "$tanggal")
			->mergeCells("B$row:C$row")
            ->setCellValue('D'.$row, "$masuk")
            ->setCellValue('E'.$row, "$keluar")
            ->setCellValue('F'.$row, "$sisa")
			//->setCellValue('G'.$row, "$harga"); 
            ->setCellValue('G'.$row, " ");
	
	$row++;	$no++;	
}
	
//$objPHPExcel->getActiveSheet()->getStyle("K9:K$row")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("A12:A$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("D12:F$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//$objPHPExcel->getActiveSheet()->getStyle("A11:J$row")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("D12:F$row")->getNumberFormat()->setFormatCode("#,##0.00");		

			
//tulis border
$row--;
$objPHPExcel->getActiveSheet()->getStyle("A10:G$row")->applyFromArray($BStyle);
$objPHPExcel->getActiveSheet()->getStyle("A10:G10")->applyFromArray($BTStyle);


//tulis footer
$row+=2; $date = tgl_indo(date('Y-m-d'));
$objPHPExcel->getActiveSheet()
            ->setCellValue("G$row", "Karanganyar, $date");
$row++;
$objPHPExcel->getActiveSheet()
            ->setCellValue("B$row", "$skpd[nm_sub2_unit]")
            ->setCellValue("G$row", "$txtPengurus");
$row+=3;
$objPHPExcel->getActiveSheet()
            ->setCellValue("B$row", "( $kepala )")
            ->setCellValue("G$row", "( $pengurus )");
$row++;
$objPHPExcel->getActiveSheet()
            ->setCellValue("B$row", "NIP $nipk")
            ->setCellValue("G$row", "NIP $nipp");
			
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Kartu Barang');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Kartu Barang.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=0');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//$objWriter->save('php://output');
$objWriter->save('../xls/Kartu Barang '.$tgl_cetak_now.'.xlsx');
$response = array( 'success' => true, 'url' => './xls/Kartu Barang '.$tgl_cetak_now.'.xlsx' );
header('Content-type: application/json');
// and in the end you respond back to javascript the file location
echo json_encode($response);

mysql_close();
exit;