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

foreach (glob("../xls/Rekapitulasi Persediaan*.*") as $filename) {
    unlink($filename);
}


$peran = cekLogin();
	
$kepala = $pengurus = $nipk = $nipp = "";
$id_sub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
$skpd = isset($_POST['skpd']) ? $_POST['skpd'] : '';
$basket = isset($_POST['basket']) ? $_POST['basket'] : '';
$ta = isset($_POST['ta']) ? $_POST['ta'] : '';
$sd = isset($_POST['sd']) ? $_POST['sd'] : '';

if($id_sub!=""){
	$b = " AND uuid_sub2_unit = '$id_sub'";
	$b1 = " AND d.uuid_skpd = '$id_sub'";
}else{ $b = " AND MD5(uuid_sub2_unit) = '$_SESSION[uidunit]'";
		$b1 = " AND MD5(d.uuid_skpd) = '$_SESSION[uidunit]'"; }	
$skpd = mysql_fetch_assoc(mysql_query("SELECT nm_sub2_unit, kd_sub, uuid_sub2_unit FROM ref_sub2_unit WHERE kd_sub IS NOT NULL $b"));
if($skpd['kd_sub']==1 || $skpd['uuid_sub2_unit'] == 'cfa58008-5543-11e6-a2df-000476f4fa98'){
	$in = "(8,10)"; $kep = 8; $txtPengurus = "Pengurus Barang";
}else{
	$in = "(9,11)"; $kep = 9;  $txtPengurus = "Pembantu Pengurus Barang";
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
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(13);	
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(7);	
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(13);	
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(7);	
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(13);	
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(7);	
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(13);	
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(7);	
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(13);	
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(7);	
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(13);
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
            ->setCellValue('A1', 'REKAPITULASI PERSEDIAAN BARANG PAKAI HABIS')
			->mergeCells('A1:O1');
$objPHPExcel->getActiveSheet()
            ->setCellValue('A3', 'SKPD/Unit Kerja')
            ->setCellValue('C3', ": $skpd[nm_sub2_unit] ")
            ->setCellValue('A4', 'Tahun')
            ->setCellValue('C4', ": $ta")
            ->setCellValue('E3', "Sumber Dana")
            ->setCellValue('F3', ": $sd");
$objPHPExcel->getActiveSheet()
            ->setCellValue('A6', 'NO')
			->mergeCells('A6:A7')
			->setCellValue('B6', "NAMA BARANG")
			->mergeCells('B6:C7')
            ->setCellValue('D6', "SATUAN")
			->mergeCells('D6:D7')
            ->setCellValue('E6', "HARGA\nSATUAN")
			->mergeCells('E6:E7')
			->setCellValue('F6', "SISA TAHUN LALU")
			->mergeCells('F6:G6')
			->setCellValue('F7', "JML")
			->setCellValue('G7', "JML HARGA")
			->setCellValue('H6', "PENGADAAN TAHUN INI")
			->mergeCells('H6:I6')
			->setCellValue('H7', "JML")
			->setCellValue('I7', "JML HARGA")
			->setCellValue('J6', "JML TAHUN INI")
			->mergeCells('J6:K6')
			->setCellValue('J7', "JML")
			->setCellValue('K7', "JML HARGA")
			->setCellValue('L6', "PEMAKAIAN TAHUN INI")
			->mergeCells('L6:M6')
			->setCellValue('L7', "JML")
			->setCellValue('M7', "JML HARGA")
			->setCellValue('N6', "SISA TAHUN INI")
			->mergeCells('N6:O6')
			->setCellValue('N7', "JML")
			->setCellValue('O7', "JML HARGA");	

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
            ->setCellValue('K8', '10')
            ->setCellValue('L8', '11')
            ->setCellValue('M8', '12')
            ->setCellValue('N8', '13')
            ->setCellValue('O8', '14');
 
$row = 9; $no = 1; $ttotal = 0; $kode = ""; $jml = count($basket); $j=0;
		$totalJenis = 0;
		$total1 = 0;
		$total2 = 0;
		$total3 = 0;
		$total4 = 0;
		$total5 = 0;
		$total6 = 0;
		$total7 = 0;
		$total8 = 0;
		$total9 = 0;
		$sf = '='; $sg = '='; $sh = '='; $si = '='; $sj = '='; $sk = '='; $sl = '='; $sm = '='; $sn = '='; $so = '=';
foreach($basket AS $d){
	$harga =  str_replace(',','.',str_replace('.','',$d['harga']));
	$jml_lalu =  str_replace(',','.',str_replace('.','',$d['jml_lalu']));
	$tot_lalu =  str_replace(',','.',str_replace('.','',$d['tot_lalu']));
	$jml_in =  str_replace(',','.',str_replace('.','',$d['jml_in']));
	$tot_in =  str_replace(',','.',str_replace('.','',$d['tot_in']));
	$jml_ini =  str_replace(',','.',str_replace('.','',$d['jml_ini']));
	$tot_ini =  str_replace(',','.',str_replace('.','',$d['tot_ini']));
	$jml_out =  str_replace(',','.',str_replace('.','',$d['jml_out']));
	$tot_out =  str_replace(',','.',str_replace('.','',$d['tot_out']));
	$jumlah =  str_replace(',','.',str_replace('.','',$d['jumlah']));
	$total =  str_replace(',','.',str_replace('.','',$d['total']));
	
	
	if($kode!=$d['kode_bar']){
		if($no!=1){
			$s = $row-1;
			$objPHPExcel->getActiveSheet()
				->mergeCells("A$row:E$row")
				->setCellValue('A'.$row, "SUBTOTAL")
				->setCellValue('F'.$row, "=sum(F$j:F$s)")
				->setCellValue('G'.$row, "=sum(G$j:G$s)")
				->setCellValue('H'.$row, "=sum(H$j:H$s)")
				->setCellValue('I'.$row, "=sum(I$j:I$s)")
				->setCellValue('J'.$row, "=sum(J$j:J$s)")
				->setCellValue('K'.$row, "=sum(K$j:K$s)")
				->setCellValue('L'.$row, "=sum(L$j:L$s)")
				->setCellValue('M'.$row, "=sum(M$j:M$s)")
				->setCellValue('N'.$row, "=sum(N$j:N$s)")
				->setCellValue('O'.$row, "=sum(O$j:O$s)");
				$sf .= '+F'.$row; 
				$sg .= '+G'.$row; 
				$sh .= '+H'.$row; 
				$si .= '+I'.$row; 
				$sj .= '+J'.$row; 
				$sk .= '+K'.$row; 
				$sl .= '+L'.$row; 
				$sm .= '+M'.$row; 
				$sn .= '+N'.$row; 
				$so .= '+O'.$row;
			$row++;
		}
		$totalJenis = 0;
		$total1 = 0;
		$total2 = 0;
		$total3 = 0;
		$total4 = 0;
		$total5 = 0;
		$total6 = 0;
		$total7 = 0;
		$total8 = 0;
		$total9 = 0;
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, "$d[nama_jenis]")->mergeCells("B$row:C$row");
		$row++;	
		$j= $row;
	}
	$totalJenis += $total;
	$total1 += $jml_lalu;
	$total2 += $tot_lalu;
	$total3 += $jml_in;
	$total4 += $tot_in;
	$total5 += $jml_ini;
	$total6 += $tot_ini;
	$total7 += $tot_out;
	$total8 += $jml_out;
	$total9 += $jumlah;
	
	$objPHPExcel->getActiveSheet()
            ->setCellValue('A'.$row, "$no")
            ->setCellValue('B'.$row, "$d[nama_barang]")
			->mergeCells("B$row:C$row")
            ->setCellValue('D'.$row, "$d[satuan]")
            ->setCellValue('E'.$row, "$harga")
            ->setCellValue('F'.$row, "$jml_lalu")
            ->setCellValue('G'.$row, "$tot_lalu")
            ->setCellValue('H'.$row, "$jml_in")
			->setCellValue('I'.$row, "$tot_in")
            ->setCellValue('J'.$row, "$jml_ini")
            ->setCellValue('K'.$row, "$tot_ini")
			->setCellValue('L'.$row, "$jml_out")
            ->setCellValue('M'.$row, "$tot_out")
			->setCellValue('N'.$row, "$jumlah")
            ->setCellValue('O'.$row, "$total");
	$kode = $d['kode_bar'];
	$row++;	
	if($jml==$no){
		$s = $row-1;
		$objPHPExcel->getActiveSheet()
			->mergeCells("A$row:E$row")
			->setCellValue('A'.$row, "SUBTOTAL")
			->setCellValue('F'.$row, "=sum(F$j:F$s)")
			->setCellValue('G'.$row, "=sum(G$j:G$s)")
			->setCellValue('H'.$row, "=sum(H$j:H$s)")
			->setCellValue('I'.$row, "=sum(I$j:I$s)")
			->setCellValue('J'.$row, "=sum(J$j:J$s)")
			->setCellValue('K'.$row, "=sum(K$j:K$s)")
			->setCellValue('L'.$row, "=sum(L$j:L$s)")
			->setCellValue('M'.$row, "=sum(M$j:M$s)")
			->setCellValue('N'.$row, "=sum(N$j:N$s)")
			->setCellValue('O'.$row, "=sum(O$j:O$s)");
				$sf .= '+F'.$row; 
				$sg .= '+G'.$row; 
				$sh .= '+H'.$row; 
				$si .= '+I'.$row; 
				$sj .= '+J'.$row; 
				$sk .= '+K'.$row; 
				$sl .= '+L'.$row; 
				$sm .= '+M'.$row; 
				$sn .= '+N'.$row; 
				$so .= '+O'.$row;
		$totalJenis = 0;
		$total1 = 0;
		$total2 = 0;
		$total3 = 0;
		$total4 = 0;
		$total5 = 0;
		$total6 = 0;
		$total7 = 0;
		$total8 = 0;
		$total9 = 0;
		$row++;
	}	
	$no++;	
	$total = str_replace('.','',$total);
	$total = str_replace(',','.',$total);
	$ttotal += $total;	
}

$objPHPExcel->getActiveSheet()
            ->setCellValue('A'.$row, "JUMLAH TOTAL")
			->mergeCells("A$row:E$row")
			->setCellValue('F'.$row, "$sf")
			->setCellValue('G'.$row, "$sg")
			->setCellValue('H'.$row, "$sh")
			->setCellValue('I'.$row, "$si")
			->setCellValue('J'.$row, "$sj")
			->setCellValue('K'.$row, "$sk")
			->setCellValue('L'.$row, "$sl")
			->setCellValue('M'.$row, "$sm")
			->setCellValue('N'.$row, "$sn")
			->setCellValue('O'.$row, "$so");
    
$objPHPExcel->getActiveSheet()->getStyle("A9:A$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("F9:F$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("H9:H$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("J9:J$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("L9:L$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("N9:N$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("E9:O$row")->getNumberFormat()->setFormatCode("#,##0.00");			

//tulis border
//$row--;
$objPHPExcel->getActiveSheet()->getStyle("A6:O$row")->applyFromArray($BStyle);
$objPHPExcel->getActiveSheet()->getStyle("A8:O8")->applyFromArray($BTStyle);


//tulis footer
$row+=2; $date = tgl_indo(date('Y-m-d'));
$objPHPExcel->getActiveSheet()
            ->setCellValue("K$row", "Karanganyar, $date");
$row++;
$objPHPExcel->getActiveSheet()
            ->setCellValue("C$row", "Kepala ".$skpd[nm_sub2_unit])
            ->setCellValue("K$row", "$txtPengurus");
$row+=3;
$objPHPExcel->getActiveSheet()
            ->setCellValue("C$row", "( $kepala )")
            ->setCellValue("K$row", "( $pengurus )");
$row++;
$objPHPExcel->getActiveSheet()
            ->setCellValue("C$row", "NIP $nipk")
            ->setCellValue("K$row", "NIP $nipp");
			
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Rekapitulasi Persediaan');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Rekapitulasi Persediaan.xlsx"');
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
$objWriter->save('../xls/Rekapitulasi Persediaan '.$tgl_cetak_now.'.xlsx');
$response = array( 'success' => true, 'url' => './xls/Rekapitulasi Persediaan '.$tgl_cetak_now.'.xlsx' );
header('Content-type: application/json');
// and in the end you respond back to javascript the file location
echo json_encode($response);

mysql_close();
exit;
