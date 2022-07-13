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

foreach (glob("../xls/Laporan Rekapitulasi per Jenis*.*") as $filename) {
    unlink($filename);
}


$peran = cekLogin();
	
//$kepala = $pengurus = $nipk = $nipp = "";
$id = isset($_POST['id']) ? $_POST['id'] : '';
$idj = isset($_POST['id_jen']) ? $_POST['id_jen'] : '';
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

$txtPengurus = "Pengurus Barang"; $kepala = ""; $nipk = "";		
$pejabat = mysql_query("SELECT nama_pejabat, nip, id_jabatan FROM pejabat d WHERE id_jabatan IN (8,10) AND uuid_skpd = '$id'");
while($t = mysql_fetch_assoc($pejabat)){
	if($t['id_jabatan']==8){ $kepala = $t['nama_pejabat']; $nipk = $t['nip']; }
	else{ $pengurus = $t['nama_pejabat']; $nipp = $t['nip']; }
}				
	
$jenis = mysql_fetch_row(mysql_query("SELECT nama_jenis FROM ref_jenis WHERE id_jenis = '$idj'"));
$skpd = mysql_fetch_row(mysql_query("SELECT nm_sub2_unit FROM ref_sub2_unit WHERE uuid_sub2_unit = '$id'"));

$nilai = mysql_query("SELECT SUM(k.jml_in-k.jml_out) AS jml, k.harga, k.id_barang AS id_bar,
						IFNULL(rb.nama_barang, bk.nama_barang_kegiatan) AS nama_bar,
						IF(ISNULL(bk.id_barang_kegiatan), 
							CONCAT_WS('.', j.kd_kel, j.kd_sub, rb.kd_sub2),
							CONCAT_WS('.', j1.kd_kel, j1.kd_sub, bk.kode)) kode_bar,
						IF(ISNULL(bk.id_barang_kegiatan), 'a', 'b') stat
					FROM kartu_stok k
					LEFT JOIN ref_barang rb ON rb.id_barang = k.id_barang
					LEFT JOIN ref_barang_kegiatan bk ON bk.id_barang_kegiatan = k.id_barang
					LEFT JOIN ref_jenis j ON j.id_jenis = rb.id_jenis
					LEFT JOIN ref_jenis j1 ON j1.id_jenis = bk.id_jenis
					WHERE k.id_barang IN ( 
						SELECT b.id_barang FROM ref_barang b 
							WHERE id_jenis = '$idj' 
						UNION ALL
						SELECT g.id_barang_kegiatan FROM ref_barang_kegiatan g 
							WHERE id_jenis = '$idj' 
							
					)
					AND k.soft_delete = 0 AND k.uuid_skpd = '$id' $idsum
					AND DATE_FORMAT(tgl_transaksi, '%Y-%m') <= '$thnblnharga'
					GROUP BY k.id_barang, k.harga
					HAVING jml <> 0
					ORDER BY stat, j.kd_kel, j.kd_sub, rb.kd_sub2, bk.kode");
$items = array();			
while($n = mysql_fetch_assoc($nilai)){
	$nilaiUPT = $n['jml']*$n['harga'];
	$n['saldo'] = $n['jml'];
	$n['nilai'] = $nilaiUPT;
	array_push($items, $n);
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
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);	
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(45);	
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(17);
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
$objPHPExcel->getActiveSheet()->getStyle('A6:E7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

// Add HEADER
$objPHPExcel->getActiveSheet()
            ->setCellValue('A1', 'LAPORAN REKAPITULASI PERSEDIAAN PER JENIS')
			->mergeCells('A1:D1');
$objPHPExcel->getActiveSheet()
            ->setCellValue('A3', "SKPD : $skpd[0]")
            ->setCellValue('A4', "JENIS BARANG : $jenis[0]")
            ->setCellValue('A5', "$label : $periode");
$objPHPExcel->getActiveSheet()
            ->setCellValue('A6', 'NO')
			->setCellValue('B6', "KODE BARANG")
            ->setCellValue('C6', "NAMA BARANG")
            ->setCellValue('D6', "SALDO")	
            ->setCellValue('E6', "NILAI");	

$objPHPExcel->getActiveSheet()
            ->setCellValue('A7', '1')
            ->setCellValue('B7', '2')
            ->setCellValue('C7', '3')
            ->setCellValue('D7', '4')
            ->setCellValue('E7', '5');

$row = 8; $no = 1; $total = 0;
foreach($items AS $d){
	$total += $d['nilai'];
	$objPHPExcel->getActiveSheet()
            ->setCellValue('A'.$row, "$no")
            ->setCellValue('B'.$row, "$d[kode_bar]")
            ->setCellValue('C'.$row, "$d[nama_bar]")
            ->setCellValue('D'.$row, "$d[saldo]")
            ->setCellValue('E'.$row, "$d[nilai]");
	
	$row++;	$no++;	
}

$objPHPExcel->getActiveSheet()
            ->setCellValue('B'.$row, "JUMLAH TOTAL")
			->mergeCells("B$row:D$row")
            ->setCellValue('E'.$row, "$total");
    
//$objPHPExcel->getActiveSheet()->getStyle("K9:K$row")->getAlignment()->setWrapText(true);
//$objPHPExcel->getActiveSheet()->getStyle("M9:M$row")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("A8:A$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("D8:D$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//$objPHPExcel->getActiveSheet()->getStyle("D7:G$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//$objPHPExcel->getActiveSheet()->getStyle("A9:N$row")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);				
$objPHPExcel->getActiveSheet()->getStyle("D8:D$row")->getNumberFormat()->setFormatCode("#,##0.00");			
$objPHPExcel->getActiveSheet()->getStyle("E8:E$row")->getNumberFormat()->setFormatCode("#,##0.00");			
//tulis border
//$row--;
$objPHPExcel->getActiveSheet()->getStyle("A6:E$row")->applyFromArray($BStyle);
$objPHPExcel->getActiveSheet()->getStyle("A7:E7")->applyFromArray($BTStyle);


//tulis footer
$row+=2; $date = tgl_indo(date('Y-m-d'));
$objPHPExcel->getActiveSheet()
            ->setCellValue("D$row", "Karanganyar, $date");
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
$objPHPExcel->getActiveSheet()->setTitle('Laporan Rekapitulasi per Jenis');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Laporan Rekapitulasi per Jenis.xlsx"');
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
$objWriter->save('../xls/Laporan Rekapitulasi per Jenis.xlsx');
$response = array( 'success' => true, 'url' => './xls/Laporan Rekapitulasi per Jenis.xlsx' );
header('Content-type: application/json');
// and in the end you respond back to javascript the file location
echo json_encode($response);

mysql_close();
exit;
