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

foreach (glob("../xls/Laporan Rekapitulasi per SKPD*.*") as $filename) {
    unlink($filename);
}


$peran = cekLogin();
	
//$kepala = $pengurus = $nipk = $nipp = "";
$id = isset($_POST['id']) ? $_POST['id'] : '';
$kode = isset($_POST['kode']) ? $_POST['kode'] : '';
$nama = isset($_POST['nama']) ? $_POST['nama'] : '';
$id_sum = isset($_POST['id_sum']) ? $_POST['id_sum'] : '';
$bulan = isset($_POST['bulan']) ? $_POST['bulan'] : '';
$smstr = isset($_POST['smstr']) ? $_POST['smstr'] : '';
$ta = isset($_POST['ta']) ? $_POST['ta'] : date('Y');

if($ta=="") $ta = date('Y');

if($bulan!=""){
	$bln = str_pad($bulan,2,'0', STR_PAD_LEFT);
	$thnblnharga = $ta."-".$bln; 
	$label = "Bulan"; 
	$periode = "$bulan $ta";
}else{
	if($smstr!=""){
		if($smstr==1) $thnblnharga = $ta."-06"; 
		elseif($smstr==2) $thnblnharga = $ta."-12"; 
		$label = "Semester"; 
		$periode = $smstr; 
	}else{ 
		$thnblnharga = $ta."-12";
		$label = "Tahun";
		$periode = $ta;
	}
}

if($id_sum!='') $idsum = "AND id_sumber_dana = '$id_sum'";
else $idsum = "";

//$skpd = mysql_fetch_assoc(mysql_query("SELECT nm_sub2_unit, CONCAT_WS('.',kd_urusan,kd_bidang, kd_unit) AS kode
//									FROM ref_sub2_unit WHERE uuid_sub2_unit = '$id'"));

$txtPengurus = "Pengurus Barang";			
$pejabat = mysql_query("SELECT nama_pejabat, nip, id_jabatan FROM pejabat d WHERE id_jabatan IN (8,10) AND uuid_skpd = '$id'");
while($t = mysql_fetch_assoc($pejabat)){
	if($t['id_jabatan']==8){ $kepala = $t['nama_pejabat']; $nipk = $t['nip']; }
	else{ $pengurus = $t['nama_pejabat']; $nipp = $t['nip']; }
}				
	
$clause = "SELECT uuid_sub2_unit AS id, nm_sub2_unit AS nama_unit, 
			CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit, kd_sub) AS kode_unit
			FROM ref_sub2_unit 
			WHERE CONCAT_WS('.',kd_urusan,kd_bidang, kd_unit) = '$kode' AND kd_sub2 = 1
			ORDER BY kd_urusan, kd_bidang, kd_unit, kd_sub, kd_sub2";
$rs = mysql_query($clause);
$items = array(); //$totalPers = 0;
while($row = mysql_fetch_assoc($rs)){
	if($row['id']!="cfa58119-5543-11e6-a2df-000476f4fa98" && $row['id']!="cfa57ef4-5543-11e6-a2df-000476f4fa98"){
		$sel = "(SELECT uuid_sub2_unit FROM ref_sub2_unit WHERE CONCAT_WS('.',kd_urusan,kd_bidang, kd_unit,kd_sub) = '$row[kode_unit]')";
		$ids = "AND uuid_skpd IN $sel";
		$nilaiPers = 0;
		$saldo = mysql_query("SELECT SUM(jml_in-jml_out) AS saldo, harga, id_barang, uuid_skpd FROM kartu_stok 
											WHERE DATE_FORMAT(tgl_transaksi, '%Y-%m') <= '$thnblnharga'
											AND soft_delete = 0 $ids $idsum GROUP BY id_barang, uuid_skpd, harga");
		while($s = mysql_fetch_assoc($saldo)){
			$harga = mysql_fetch_row(mysql_query("SELECT harga FROM kartu_stok 
											WHERE id_barang = '$s[id_barang]' AND DATE_FORMAT(tgl_transaksi, '%Y-%m') <= '$thnblnharga'
											AND soft_delete = 0 AND uuid_skpd = '$s[uuid_skpd]' $idsum AND jml_in <> 0 AND kode<>'m' 
											ORDER BY tgl_transaksi DESC, create_date DESC LIMIT 1"));
			$nilaiBar = $s['saldo']*$s['harga'];		
			$nilaiPers += $nilaiBar;
		}
		//$totalPers += $nilaiPers;
		$row['nilai'] = $nilaiPers;
		
		array_push($items, $row);
	}	
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
            ->setCellValue('A1', 'LAPORAN REKAPITULASI PERSEDIAAN PER SKPD')
			->mergeCells('A1:D1');
$objPHPExcel->getActiveSheet()
            ->setCellValue('A3', "SKPD : $nama")
            ->setCellValue('A4', "$label : $periode");
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
foreach($items AS $d){
	$total += $d['nilai'];
	$objPHPExcel->getActiveSheet()
            ->setCellValue('A'.$row, "$no")
            ->setCellValue('B'.$row, "$d[kode_unit]")
            ->setCellValue('C'.$row, "$d[nama_unit]")
            ->setCellValue('D'.$row, "$d[nilai]");
	
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
$row+=2; $date = tgl_indo(date('Y-m-d'));
$objPHPExcel->getActiveSheet()
            ->setCellValue("D$row", "Wonogiri, $date");
$row++;
$objPHPExcel->getActiveSheet()
            ->setCellValue("B$row", 'Kepala SKPD/Unit Kerja')
            ->setCellValue("D$row", "$txtPengurus");
$row+=3;
$objPHPExcel->getActiveSheet()
            ->setCellValue("B$row", "( $kepala )")
            ->setCellValue("D$row", "( $pengurus )");
$row++;
$objPHPExcel->getActiveSheet()
            ->setCellValue("B$row", "NIP $nipk")
            ->setCellValue("D$row", "NIP $nipp");

			
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Laporan Rekapitulasi per SKPD');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Laporan Rekapitulasi per SKPD.xlsx"');
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
$objWriter->save('../xls/Laporan Rekapitulasi per SKPD.xlsx');
$response = array( 'success' => true, 'url' => './xls/Laporan Rekapitulasi per SKPD.xlsx' );
header('Content-type: application/json');
// and in the end you respond back to javascript the file location
echo json_encode($response);

mysql_close();
exit;
