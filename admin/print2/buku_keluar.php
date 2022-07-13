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

foreach (glob("../xls/Buku Pengeluaran*.*") as $filename) {
    unlink($filename);
}

$kepala = $pengurus = $nipk = $nipp = "";
$id_sub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
$id_sumber = isset($_POST['id_sumber']) ? $_POST['id_sumber'] : '';
$ta = isset($_POST['ta']) ? $_POST['ta'] : '';
$bln = isset($_POST['bln']) ? str_pad($_POST['bln'],2,'0', STR_PAD_LEFT) : '';

if($ta!="") $a = " AND d.ta = '$_POST[ta]'"; else $a = "";

if($id_sub!=""){ $d = " AND d.uuid_skpd = '$id_sub'"; $b = " AND d.uuid_sub2_unit = '$id_sub'"; }
else{ $d = " AND MD5(d.uuid_skpd) = '$_SESSION[uidunit]'"; $b = " AND MD5(d.uuid_sub2_unit) = '$_SESSION[uidunit]'";	}

if($bln!="" AND $bln!="00"){ 
	$c = " AND DATE_FORMAT(tgl_terima, '%m') = '$bln'"; 
	$bulan = getBulan($bln); 
}else{ $c = ""; $bulan = ""; }
if($id_sumber!="") $g = "AND d.id_sumber_dana = '$id_sumber'";
else $g = "";	
	
$where = "$a $d $c $g";
$skpd = mysql_fetch_assoc(mysql_query("SELECT d.nm_sub2_unit, kd_sub, uuid_sub2_unit FROM ref_sub2_unit d WHERE d.kd_sub IS NOT NULL $b"));
if($skpd['kd_sub']==1 || $skpd['uuid_sub2_unit'] == 'cfa58008-5543-11e6-a2df-000476f4fa98'){
	$in = "(8,10)"; $kep = 8; $txtPengurus = "Pengurus Barang";
}else{
	$in = "(9,11)"; $kep = 9;  $txtPengurus = "Pembantu Pengurus Barang";
}

$data = mysql_query("SELECT IFNULL(nama_barang_kegiatan,nama_barang) nama_barang, jml_barang, harga_barang,
				IF(jenis_out='s', u.nm_sub2_unit, peruntukan) AS untuk, tgl_ba_out, no_ba_out AS nomor, 
				tgl_terima, d.keterangan AS ket
				FROM keluar_detail d
				LEFT JOIN keluar k ON k.id_keluar = d.id_keluar
				LEFT JOIN ref_sub2_unit u ON k.uuid_untuk = u.uuid_sub2_unit
				LEFT JOIN ref_barang b ON d.id_barang = b.id_barang 
				LEFT JOIN ref_barang_kegiatan bk ON d.id_barang = bk.id_barang_kegiatan 
				WHERE d.soft_delete=0 $where");

if($d!=""){				
	$pejabat = mysql_query("SELECT nama_pejabat, nip, id_jabatan FROM pejabat d WHERE id_jabatan IN $in $d");
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
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(13);	
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(13);	
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);	
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(16);	
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);	
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(13);	
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(13);	
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);	
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(13);	
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(7);
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
$objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A7:K8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A7:K8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);	
$objPHPExcel->getActiveSheet()->getStyle('A7:K7')->getAlignment()->setWrapText(true);

// Add HEADER
$objPHPExcel->getActiveSheet()
            ->setCellValue('A1', 'BUKU PENGELUARAN BARANG PERSEDIAAN')
			->mergeCells('A1:K1');
$objPHPExcel->getActiveSheet()
            ->setCellValue('A3', 'SKPD/Unit Kerja')
            ->setCellValue('C3', ": $skpd[nm_sub2_unit] ")
            ->setCellValue('A4', 'Periode Bulan')
            ->setCellValue('C4', ": $bulan")
			->setCellValue('A5', 'Kabupaten')
            ->setCellValue('C5', ': Karanganyar');
$objPHPExcel->getActiveSheet()
            ->setCellValue('A7', 'NO')
            ->setCellValue('B7', "TANGGAL PENGELUARAN \n BARANG")
			->mergeCells('B7:C7')
            ->setCellValue('D7', "NOMOR")
			->setCellValue('E7', "NAMA\nBARANG")
			->setCellValue('F7', "BANYAKNYA")
			->setCellValue('G7', "HARGA\nSATUAN\n(Rp)")
			->setCellValue('H7', "JUMLAH\nHARGA\n(Rp)")
			->setCellValue('I7', "UNTUK")
			->setCellValue('J7', "TANGGAL\nPENYERAHAN")
			->setCellValue('K7', "KET");	

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
            ->setCellValue('J8', '9')
            ->setCellValue('K8', '10');

$row = 9; $no = 1; $jtot = 0;
while($d =mysql_fetch_assoc($data)){
	$harga = $d['harga_barang'];
	$total = $harga * $d['jml_barang'];
	$tgl_keluar = balikTanggalIndo($d['tgl_ba_out']);
	$tgl_terima = balikTanggalIndo($d['tgl_terima']);
	
	$objPHPExcel->getActiveSheet()
            ->setCellValue('A'.$row, "$no")
            ->setCellValue('B'.$row, "$tgl_keluar")
			->mergeCells("B$row:C$row")
            ->setCellValue('D'.$row, "$d[nomor]")
            ->setCellValue('E'.$row, "$d[nama_barang]")
            ->setCellValue('F'.$row, "$d[jml_barang]")
            ->setCellValue('G'.$row, "$d[harga_barang]")
			->setCellValue('H'.$row, "$total")
            ->setCellValue('I'.$row, "$d[untuk]")
            ->setCellValue('J'.$row, "$tgl_terima")
            ->setCellValue('K'.$row, "$d[ket]");
	
	$row++;	$no++; $jtot += $total;	
}

$objPHPExcel->getActiveSheet()
            ->setCellValue('B'.$row, "JUMLAH TOTAL")
			->mergeCells("B$row:C$row")
            ->setCellValue('H'.$row, "$jtot");
    
$objPHPExcel->getActiveSheet()->getStyle("I9:I$row")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("K9:K$row")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("A9:A$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("F9:F$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("A9:K$row")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);				
$objPHPExcel->getActiveSheet()->getStyle("F9:H$row")->getNumberFormat()->setFormatCode("#,##0.00");	
			
//tulis border
//$row--;
$objPHPExcel->getActiveSheet()->getStyle("A7:K$row")->applyFromArray($BStyle);
$objPHPExcel->getActiveSheet()->getStyle("A8:K8")->applyFromArray($BTStyle);


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
			
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Buku Pengeluaran');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Buku Pengeluaran.xlsx"');
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
$objWriter->save('../xls/Buku Pengeluaran '.$tgl_cetak_now.'.xlsx');
$response = array( 'success' => true, 'url' => './xls/Buku Pengeluaran '.$tgl_cetak_now.'.xlsx' );
header('Content-type: application/json');
// and in the end you respond back to javascript the file location
echo json_encode($response);

mysql_close();
exit;
