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

foreach (glob("../xls/Rekapitulasi Perencanaan*.*") as $filename) {
    unlink($filename);
}


$peran = cekLogin();
	
$kepala = $pengurus = $nipk = $nipp = "";
$id_sub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
$skpd = isset($_POST['skpd']) ? $_POST['skpd'] : '';
$basket = isset($_POST['basket']) ? $_POST['basket'] : '';
//$tglawal = isset($_POST['tglawal']) ? $_POST['tglawal'] : '';
//$tglakhir = isset($_POST['tglakhir']) ? $_POST['tglakhir'] : '';
$ta = isset($_POST['ta']) ? $_POST['ta'] : '';
$id_sum = isset($_POST['id_sum']) ? $_POST['id_sum'] : '';
$id_keg = isset($_POST['id_keg']) ? $_POST['id_keg'] : '';

if($id_sub!=""){
	$b = " AND uuid_sub2_unit = '$id_sub'";
	$b1 = " AND d.uuid_skpd = '$id_sub'";
}else{ $b = " AND MD5(uuid_sub2_unit) = '$_SESSION[uidunit]'";
		$b1 = " AND MD5(d.uuid_skpd) = '$_SESSION[uidunit]'"; }	
$skpd = mysql_fetch_assoc(mysql_query("SELECT nm_sub2_unit, kd_sub, uuid_sub2_unit FROM ref_sub2_unit WHERE kd_sub IS NOT NULL $b"));
if($skpd['kd_sub']==1 || $skpd['uuid_sub2_unit'] == 'cfa58008-5543-11e6-a2df-000476f4fa98'){
	$in = "(8,10)"; $kep = 8; $txtPengurus = "Pengurus Barang";
}else{
	$in = "(1,2)"; $kep = 1;  $txtPengurus = "Pengurus Barang";
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
$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_FOLIO);
$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.75);
$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.25);
$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.25);
$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.75);

//cell size
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(4);							 
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(11);	
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(22);	
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(9);	
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);	
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);	
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);	
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);	
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);	
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);	
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
$objPHPExcel->getActiveSheet()->getStyle('A1:O1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A6:O8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A6:O8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);	
$objPHPExcel->getActiveSheet()->getStyle('A6:O8')->getAlignment()->setWrapText(true);

// Add HEADER
$objPHPExcel->getActiveSheet()
            ->setCellValue('A1', 'REKAPITULASI PERENCANAAN PERSEDIAAN BARANG')
			->mergeCells('A1:O1');
$objPHPExcel->getActiveSheet()
            ->setCellValue('A3', 'Unit Kerja')
            ->setCellValue('C3', ": $skpd[nm_sub2_unit] ")
            ->setCellValue('A4', 'Kegiatan')
            ->setCellValue('C4', ": $id_keg")
            ->setCellValue('E3', "Sumber Dana")
            ->setCellValue('F3', ": $id_sum")
            ->setCellValue('E4', "Tahun")
            ->setCellValue('F4', ": $ta");
$objPHPExcel->getActiveSheet()
            ->setCellValue('A6', 'NO')
			->mergeCells('A6:A7')
			->setCellValue('B6', "NAMA BARANG")
			->mergeCells('B6:C7')
            ->setCellValue('D6', "SATUAN")
			->mergeCells('D6:D7')
            ->setCellValue('E6', "JUMLAH SATUAN BARANG")
			->mergeCells('E6:E7')
			->setCellValue('F6', "HARGA\nSATUAN")
			->mergeCells('F6:F7')
			->setCellValue('G6', "JUMLAH SATUAN PENGADAAN")
			->mergeCells('G6:G7')
			->setCellValue('H6', "JUMLAH HARGA PENGADAAN")
			->mergeCells('H6:H7')
			->setCellValue('I6', "SISA SATUAN")
			->mergeCells('I6:I7')
			->setCellValue('J6', "SISA HARGA SATUAN")
			->mergeCells('J6:J7');

$objPHPExcel->getActiveSheet()
            ->setCellValue('A8', '1')
            ->setCellValue('B8', '2')
			->mergeCells('B8:C8')
            ->setCellValue('D8', '3')
            ->setCellValue('E8', '4')
            ->setCellValue('F8', '5')
			->mergeCells('F8:F8')
            ->setCellValue('G8', '6')
			->mergeCells('G8:G8')
            ->setCellValue('H8', '7')
			->mergeCells('H8:H8')
			->setCellValue('I8', '8')
			->mergeCells('I8:I8')
			->setCellValue('J8', '9')
			->mergeCells('J8:J8');
 
$row = 9; $no = 1; $ttotal = 0; $kode = ""; $jml = count($basket); $j=0;
		$totalJenis = 0;
		$total1 = 0;
		$total2 = 0;
		$total3 = 0;
		$total4 = 0;
		$total5 = 0;
		$se = '='; $sf = '='; $sg = '='; $sh = '='; $si = '='; $sj = '='; $sk = '='; $sl = '='; $sm = '='; $sn = '='; $so = '=';
foreach($basket AS $d){

	$harga =  str_replace(',','.',str_replace('.','',$d['harga']));
	$jml_bar =  str_replace(',','.',str_replace('.','',$d['jumlah_barang']));
	$jml_barisi =  str_replace(',','.',str_replace('.','',$d['jumlah_barang_isi']));
	$jml_harisi =  str_replace(',','.',str_replace('.','',$d['harga_pengadaan']));
	$sisa =  str_replace(',','.',str_replace('.','',$d['sisa']));
	//$total =  str_replace(',','.',str_replace('.','',$d['total']));
	
	
	
	if($kode!=$d['kode_bar']){
		if($no!=1){
			$s = $row-1;
			$objPHPExcel->getActiveSheet()
			->mergeCells("A$row:D$row")
			->setCellValue('A'.$row, "SUBTOTAL")
			->setCellValue('E'.$row, "=sum(E$j:E$s)")
			->setCellValue('F'.$row, "=sum(F$j:F$s)")
			->mergeCells("F$row:F$row")
			->setCellValue('G'.$row, "=sum(G$j:G$s)")
			->mergeCells("G$row:G$row")
			->setCellValue('H'.$row, "=sum(H$j:H$s)")
			->mergeCells("H$row:H$row")
			->setCellValue('I'.$row, "=sum(I$j:I$s)")
			->mergeCells("I$row:I$row")
			->setCellValue('J'.$row, "=sum(J$j:J$s)")
			->mergeCells("J$row:J$row");
			$se .= '+E'.$row; 
			$sf .= '+F'.$row; 
			$sg .= '+G'.$row; 
			$sh .= '+H'.$row; 
			$si .= '+I'.$row; 
			$sj .= '+J'.$row; 
			$row++;
		}
		$totalJenis = 0;
		$total1 = 0;
		$total2 = 0;
		$total3 = 0;
		$total4 = 0;
		$total5 = 0;
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, "$d[nama_jenis]")->mergeCells("B$row:C$row");
		$row++;	
		$j= $row;
	}
	$totalJenis += $harga;
	$total1 += $jml_bar;
	$total2 += $jml_barisi;
	$total3 += $sisa;
	$total4 += $jml_harisi;
	$total5 += $harga;
	
	$objPHPExcel->getActiveSheet()
            ->setCellValue('A'.$row, "$no")
            ->setCellValue('B'.$row, "$d[nm_barang]")
			->mergeCells("B$row:C$row")
            ->setCellValue('D'.$row, "$d[nama_satuan]")
            ->setCellValue('E'.$row, "$jml_bar")
            ->setCellValue('F'.$row, "$harga")
			->mergeCells("F$row:F$row")
            ->setCellValue('G'.$row, "$jml_barisi")
			->mergeCells("G$row:G$row")
			->setCellValue('H'.$row, "$jml_harisi")
			->mergeCells("H$row:H$row")
            ->setCellValue('I'.$row, "$sisa")
			->mergeCells("I$row:I$row")
			->setCellValue('J'.$row, "$harga")
			->mergeCells("J$row:J$row");
	$kode = $d['kode_bar'];
	$row++;	
	if($jml==$no){
		$s = $row-1;
		$objPHPExcel->getActiveSheet()
			->mergeCells("A$row:D$row")
			->setCellValue('A'.$row, "SUBTOTAL")
			->setCellValue('E'.$row, "=sum(E$j:E$s)")
			->setCellValue('F'.$row, "=sum(F$j:F$s)")
			->mergeCells("F$row:F$row")
			->setCellValue('G'.$row, "=sum(G$j:G$s)")
			->mergeCells("G$row:G$row")
			->setCellValue('H'.$row, "=sum(H$j:H$s)")
			->mergeCells("H$row:H$row")
			->setCellValue('I'.$row, "=sum(I$j:I$s)")
			->mergeCells("I$row:I$row")
			->setCellValue('J'.$row, "=sum(J$j:J$s)")
			->mergeCells("J$row:J$row")
			;
				$se .= '+E'.$row; 
				$sf .= '+F'.$row; 
				$sg .= '+G'.$row; 
				$sh .= '+H'.$row;
				$si .= '+I'.$row; 
				$sj .= '+J'.$row;  
				
		$totalJenis = 0;
		$total1 = 0;
		$total2 = 0;
		$total3 = 0;
		$total4 = 0;
		$total5 = 0;
		$row++;
	}	
	$no++;	
	$total = str_replace('.','',$total);
	$total = str_replace(',','.',$total);
	$ttotal += $total;	
}

$objPHPExcel->getActiveSheet()
            ->setCellValue('A'.$row, "JUMLAH TOTAL")
			->mergeCells("A$row:D$row")
			->setCellValue('E'.$row, "$se")
			->setCellValue('F'.$row, "$sf")
			->mergeCells("F$row:F$row")
			->setCellValue('G'.$row, "$sg")
			->mergeCells("G$row:G$row")
			->setCellValue('H'.$row, "$sg")
			->mergeCells("H$row:H$row")
			->setCellValue('I'.$row, "$sg")
			->mergeCells("I$row:I$row")
			->setCellValue('J'.$row, "$sg")
			->mergeCells("J$row:J$row");
    
$objPHPExcel->getActiveSheet()->getStyle("A9:A$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("F9:F$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("G9:G$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("H9:H$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("E9:J$row")->getNumberFormat()->setFormatCode("#,##0.00");			

//tulis border
//$row--;
$objPHPExcel->getActiveSheet()->getStyle("A6:J$row")->applyFromArray($BStyle);
$objPHPExcel->getActiveSheet()->getStyle("A8:J8")->applyFromArray($BTStyle);


//tulis footer
$row+=2; $date = tgl_indo(date('Y-m-d'));
$objPHPExcel->getActiveSheet()
            ->setCellValue("I$row", "Karanganyar, $date");
$row++;
$objPHPExcel->getActiveSheet()
            ->setCellValue("C$row", "Kepala ".$skpd['nm_sub2_unit'])
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
$objPHPExcel->getActiveSheet()->setTitle('Rekapitulasi Perencanaan');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Rekapitulasi Perencanaan.xlsx"');
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
$objWriter->save('../xls/Rekapitulasi Perencanaan '.$tgl_cetak_now.'.xlsx');
$response = array( 'success' => true, 'url' => './xls/Rekapitulasi Perencanaan '.$tgl_cetak_now.'.xlsx' );
header('Content-type: application/json');
// and in the end you respond back to javascript the file location
echo json_encode($response);

mysql_close();
exit;
?>
