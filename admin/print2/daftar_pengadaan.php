<?php

/** Error reporting */
/* error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
 */
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

foreach (glob("../xls/Daftar Pengadaan*.*") as $filename) {
    unlink($filename);
}


$kepala = $pengurus = $nipk = $nipp = "";
$id_sub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
$id_sumber = isset($_POST['id_sum']) ? $_POST['id_sum'] : '';
$tgl_awal = isset($_POST['tgl_awal']) ? balikTanggal($_POST['tgl_awal']) : '';
$tgl_akhir = isset($_POST['tgl_akhir']) ? balikTanggal($_POST['tgl_akhir']) : '';
$ta = isset($_POST['ta']) ? $_POST['ta'] : '';

	if($tgl_awal != '0000-00-00' && $tgl_akhir != '0000-00-00' ){
		$ft = " AND (m.tgl_pembayaran BETWEEN '$tgl_awal' AND '$tgl_akhir') ";  	
	} else {
		$ft = "";
	}
	
if($ta=="") $ta =  date('Y');

$a = " AND d.ta = '$ta'";
if($id_sub!=""){ $d = " AND d.uuid_skpd = '$id_sub'"; $b = " AND d.uuid_sub2_unit = '$id_sub'"; }
else{ $d = " AND MD5(d.uuid_skpd) = '$_SESSION[uidunit]'"; $b = " AND MD5(d.uuid_sub2_unit) = '$_SESSION[uidunit]'";	}

if($id_sumber!="") $c = " AND m.id_sumber = '$id_sumber'"; else $c = "";

$where = "$a $d $c";
$skpd = mysql_fetch_assoc(mysql_query("SELECT d.nm_sub2_unit, kd_sub, uuid_sub2_unit FROM ref_sub2_unit d WHERE d.kd_sub IS NOT NULL $b"));
if($skpd['kd_sub']==1 || $skpd['uuid_sub2_unit'] == 'cfa58008-5543-11e6-a2df-000476f4fa98'){
	$in = "(8,10)"; $kep = 8; $txtPengurus = "Pengurus Barang mmkmkmkm";
}else{
	$in = "(9,11)"; $kep = 9;  $txtPengurus = "Pembantu Pengurus Barang";
}

$data = mysql_query("SELECT IF(d.id_kelompok<3, nama_barang, nama_barang_kegiatan) AS nama_barang, jml_masuk, harga_masuk, 
				nm_sub2_unit AS unit, tgl_pengadaan, no_kontrak, 
				tgl_pembayaran, no_pembayaran AS no_dpa, d.keterangan AS ket
				FROM masuk_detail d
				LEFT JOIN ref_sub2_unit u ON u.uuid_sub2_unit = d.uuid_skpd
				LEFT JOIN masuk m ON m.id_masuk = d.id_masuk
				LEFT JOIN ref_barang b ON d.id_barang = b.id_barang 
				LEFT JOIN ref_barang_kegiatan bk ON d.id_barang = bk.id_barang_kegiatan 
				WHERE d.soft_delete=0 $where $ft");
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
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);	
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(13);	
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);	
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(13);	
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);	
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);	
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);	
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(18);	
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(23);	
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(5);	
//$objPHPExcel->getActiveSheet()->getRowDimension(6)->setRowHeight(30);
$objPHPExcel->getActiveSheet()->getRowDimension(7)->setRowHeight(45);

//border
$BStyle = array(
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  )
);


//STYLE
$objPHPExcel->getActiveSheet()->getStyle('A4:L8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A6:L8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);				
$objPHPExcel->getActiveSheet()->getStyle('H7:J7')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('K6:K7')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('D6:E6')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('B6:B7')->getAlignment()->setWrapText(true);

// Add HEADER
$objPHPExcel->getActiveSheet()
            ->setCellValue('A1', 'SKPD/UNIT KERJA')
            ->setCellValue('C1', ": $skpd[nm_sub2_unit] ")
            ->setCellValue('A2', 'KABUPATEN')
            ->setCellValue('C2', ': Karanganyar');			
$objPHPExcel->getActiveSheet()
            ->setCellValue('A4', 'DAFTAR PENGADAAN BARANG PERSEDIAAN')
			->mergeCells('A4:L4');
$objPHPExcel->getActiveSheet()
            ->setCellValue('A5', "DARI TANGGAL 1 JANUARI S/D JUNI TAHUN $ta (SEMESTER I) DAN DARI TANGGAL 1 JULI S/D DESEMBER TAHUN $ta (SEMESTER II)")
			->mergeCells('A5:L5');
$objPHPExcel->getActiveSheet()
            ->setCellValue('A6', 'NO')
            ->mergeCells('A6:A7')
            ->setCellValue('B6', "JENIS/NAMA BARANG\nYANG DIBELI")
			->mergeCells('B6:C7')
            ->setCellValue('D6', "SPK/PERJANJIAN\nKONTRAK")
			->mergeCells('D6:E6')
            ->setCellValue('F6', 'DPA/SPM/KUITANSI')
			->mergeCells('F6:G6')
			->setCellValue('H6', 'JUMLAH')
			->mergeCells('H6:J6')
			->setCellValue('K6', "DIPERGUNAKAN\nPADA UNIT")
			->mergeCells('K6:K7')
			->setCellValue('L6', "KET.")
			->mergeCells('L6:L7');	

$objPHPExcel->getActiveSheet()
            ->setCellValue('D7', 'TGL')
            ->setCellValue('E7', 'NOMOR')
			->setCellValue('F7', 'TGL')
            ->setCellValue('G7', 'NOMOR')
            ->setCellValue('H7', "BANYAKNYA\nBARANG")
            ->setCellValue('I7', "HARGA\nSATUAN\n(Rp.)")
            ->setCellValue('J7', "JUMLAH\nHARGA (Rp.)");

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
            ->setCellValue('L8', '11');

$row = 9; $no = 1; 

while($d =mysql_fetch_assoc($data)){
	$harga = $d['harga_masuk'];
	$total = $harga * $d['jml_masuk'];
	$tgl_kontrak = balikTanggalIndo($d['tgl_pengadaan']);
	$tgl_dpa = balikTanggalIndo($d['tgl_pembayaran']);
	$jml_barang = number_format($d['jml_masuk'], 0, ',', '.')." ";
	$hrg_barang = number_format($harga, 0, ',', '.');
	$tot_harga = number_format($d['harga_masuk'], 0, ',', '.');
	
	$objPHPExcel->getActiveSheet()
            ->setCellValue('A'.$row, "$no")
            ->setCellValue('B'.$row, "$d[nama_barang]")
			->mergeCells("B$row:C$row")
            ->setCellValue('D'.$row, "$tgl_kontrak")
            ->setCellValue('E'.$row, "$d[no_kontrak]")
            ->setCellValue('F'.$row, "$tgl_dpa")
            ->setCellValue('G'.$row, "$d[no_dpa]")
            ->setCellValue('H'.$row, "$d[jml_masuk]")
            ->setCellValue('I'.$row, "$harga")
            ->setCellValue('J'.$row, "$total")
            ->setCellValue('K'.$row, "$d[unit]")
            ->setCellValue('L'.$row, "$d[ket]");
	
	$row++;	$no++;	

}
$trow = $row-1;
$objPHPExcel->getActiveSheet()
            ->setCellValue('A'.$row, "JUMLAH TOTAL")
			->mergeCells("A$row:I$row")
			->setCellValue('J'.$row, "=sum(J9:J$trow)")
			->setCellValue('K'.$row, "")
			->setCellValue('L'.$row, "");
			$row++;

$objPHPExcel->getActiveSheet()->getStyle("K9:K$row")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("A9:A$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("A9:J$row")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);				
$objPHPExcel->getActiveSheet()->getStyle("H9:J$row")->getNumberFormat()->setFormatCode("#,##0.00");	

			
//tulis border
$row--;
$objPHPExcel->getActiveSheet()->getStyle("A6:L$row")->applyFromArray($BStyle);


//tulis footer
$row+=3; $date = tgl_indo(date('Y-m-d'));
$objPHPExcel->getActiveSheet()
            ->setCellValue("J$row", "Karanganyar, $date");
$row++;
$objPHPExcel->getActiveSheet()
            ->setCellValue("C$row", 'Kepala SKPD/Unit Kerja')
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
$objPHPExcel->getActiveSheet()->setTitle('Daftar Pengadaan');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Daftar Pengadadaan.xlsx"');
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
$objWriter->save('../xls/Daftar Pengadaan '.$tgl_cetak_now.'.xlsx');
$response = array( 'success' => true, 'url' => './xls/Daftar Pengadaan '.$tgl_cetak_now.'.xlsx' );
header('Content-type: application/json');
// and in the end you respond back to javascript the file location
echo json_encode($response);

mysql_close();
exit;
