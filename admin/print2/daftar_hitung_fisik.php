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


foreach (glob("../xls/Daftar Hitung Fisik*.*") as $filename) {
    unlink($filename);
}


$peran = cekLogin();
	
$kepala = $pengurus = $nipk = $nipp = "";
$id = isset($_POST['id']) ? $_POST['id'] : '';
$tanggal = isset($_POST['tanggal']) ? balikTanggalIndo($_POST['tanggal']) : '';
$id_sub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';	
$jenis = isset($_POST['jenis']) ? $_POST['jenis'] : '';	
	
$skpd = mysql_fetch_assoc(mysql_query("SELECT d.nm_sub2_unit, kd_sub, uuid_sub2_unit FROM ref_sub2_unit d WHERE d.uuid_sub2_unit = '$id_sub'"));
if($skpd['kd_sub']==1 || $skpd['uuid_sub2_unit'] == 'cfa58008-5543-11e6-a2df-000476f4fa98'){
	$in = "(8,10)"; $kep = 8; $txtPengurus = "Pengurus Barang";
}else{
	$in = "(9,11)"; $kep = 9;  $txtPengurus = "Pembantu Pengurus Barang";
}
if($jenis=='all'){
	$data = mysql_query("SELECT SUM(jml_komp) AS jml_admin, SUM(jml_fisik) AS jml_so, harga_komp AS hrgsat_admin, harga_fisik AS hrgsat_so, 
						s.id_barang AS id_bar, IFNULL(nama_barang_kegiatan,nama_barang) nama_bar, 
						IFNULL(t1.nama_satuan, t.nama_satuan) sat_admin, IFNULL(t1.nama_satuan, t.nama_satuan) sat_so, id_so AS id, id_so_detail AS id_det, IF(ISNULL(id_barang_kegiatan), 'a', 'b') stat
						FROM so_detail s 
						LEFT JOIN ref_barang b ON b.id_barang=s.id_barang
						LEFT JOIN ref_barang_kegiatan bk ON bk.id_barang_kegiatan=s.id_barang
						LEFT JOIN ref_satuan t ON t.id_satuan=b.id_satuan
						LEFT JOIN ref_satuan t1 ON t1.id_satuan=bk.id_satuan
						LEFT JOIN ref_jenis j ON j.id_jenis = b.id_jenis 
						WHERE s.soft_delete=0 AND id_so = '$id'
						GROUP BY s.id_barang, harga_komp, harga_fisik
						ORDER BY stat, j.kd_kel, j.kd_sub, b.kd_sub2");
}elseif($jenis=='gudang'){
	$data = mysql_query("SELECT SUM(jml_komp) AS jml_admin, SUM(jml_fisik) AS jml_so, harga_komp AS hrgsat_admin, harga_fisik AS hrgsat_so, 
						s.id_barang AS id_bar, IFNULL(nama_barang_kegiatan,nama_barang) nama_bar, s.id_gudang, nama_gudang,
						IFNULL(t1.nama_satuan, t.nama_satuan) sat_admin, IFNULL(t1.nama_satuan, t.nama_satuan) sat_so, id_so AS id, id_so_detail AS id_det, IF(ISNULL(id_barang_kegiatan), 'a', 'b') stat
						FROM so_detail s 
						LEFT JOIN ref_barang b ON b.id_barang=s.id_barang
						LEFT JOIN ref_barang_kegiatan bk ON bk.id_barang_kegiatan=s.id_barang
						LEFT JOIN ref_satuan t ON t.id_satuan=b.id_satuan
						LEFT JOIN ref_satuan t1 ON t1.id_satuan=bk.id_satuan
						LEFT JOIN ref_jenis j ON j.id_jenis = b.id_jenis 
						LEFT JOIN ref_gudang g ON g.id_gudang = s.id_gudang 
						WHERE s.soft_delete=0 AND id_so = '$id'
						GROUP BY s.id_gudang, s.id_barang, harga_komp, harga_fisik
						ORDER BY s.id_gudang, stat, j.kd_kel, j.kd_sub, b.kd_sub2");
}else{
	$data = mysql_query("SELECT SUM(jml_komp) AS jml_admin, SUM(jml_fisik) AS jml_so, harga_komp AS hrgsat_admin, harga_fisik AS hrgsat_so, 
						s.id_barang AS id_bar, IFNULL(nama_barang_kegiatan,nama_barang) nama_bar, s.id_sumber_dana, nama_sumber,
						IFNULL(t1.nama_satuan, t.nama_satuan) sat_admin, IFNULL(t1.nama_satuan, t.nama_satuan) sat_so, id_so AS id, id_so_detail AS id_det, IF(ISNULL(id_barang_kegiatan), 'a', 'b') stat
						FROM so_detail s 
						LEFT JOIN ref_barang b ON b.id_barang=s.id_barang
						LEFT JOIN ref_barang_kegiatan bk ON bk.id_barang_kegiatan=s.id_barang
						LEFT JOIN ref_satuan t ON t.id_satuan=b.id_satuan
						LEFT JOIN ref_satuan t1 ON t1.id_satuan=bk.id_satuan
						LEFT JOIN ref_jenis j ON j.id_jenis = b.id_jenis 
						LEFT JOIN ref_sumber_dana d ON d.id_sumber = s.id_sumber_dana 
						WHERE s.soft_delete=0 AND id_so = '$id'
						GROUP BY s.id_sumber_dana, s.id_barang, harga_komp, harga_fisik
						ORDER BY s.id_sumber_dana, stat, j.kd_kel, j.kd_sub, b.kd_sub2");
}					
if($id_sub!=""){				
	$pejabat = mysql_query("SELECT nama_pejabat, nip, id_jabatan FROM pejabat d WHERE id_jabatan IN $in 
								AND d.uuid_skpd = '$id_sub'");
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
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(8);	
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(13);	
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(13);	
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(13);	
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(13);	
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(13);	
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(13);	
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(13);	
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(13);
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
$objPHPExcel->getActiveSheet()->getStyle('A1:L3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A8:L12')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A8:L10')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);	
$objPHPExcel->getActiveSheet()->getStyle('F10:K10')->getAlignment()->setWrapText(true);

// Add HEADER
$objPHPExcel->getActiveSheet()
            ->setCellValue('A1', 'DAFTAR HASIL PERHITUNGAN FISIK ATAS BARANG PERSEDIAAN / STOCK OPNAME')
			->mergeCells('A1:L1')
            ->setCellValue('A2', 'DILINGKUNGAN PEMERINTAH KABUPATEN KARANGANYAR')
			->mergeCells('A2:L2');

if($jenis=="gudang"){
	$objPHPExcel->getActiveSheet()
				->setCellValue('A3', 'PER GUDANG')
				->mergeCells('A3:L3');

}elseif($jenis=="sumber"){
	$objPHPExcel->getActiveSheet()
				->setCellValue('A3', 'PER SUMBER DANA')
				->mergeCells('A3:L3');
}			
$objPHPExcel->getActiveSheet()
            ->setCellValue('A5', 'SKPD')
            ->setCellValue('C5', ": $skpd[nm_sub2_unit] ")
            ->setCellValue('A6', 'PER TANGGAL')
            ->setCellValue('C6', ": $tanggal")
			->setCellValue('A7', 'KABUPATEN')
            ->setCellValue('C7', ': Karanganyar');
$objPHPExcel->getActiveSheet()
            ->setCellValue('A8', 'NO')
            ->mergeCells('A8:A11')
            ->setCellValue('B8', "NAMA BARANG")
			->mergeCells('B8:C11')
            ->setCellValue('D8', "JUMLAH PERSEDIAAN PERTANGGAL PERHITUNGAN")
			->mergeCells('D8:K8')
            ->setCellValue('D9', "MENURUT ADMINISTRASI")
			->mergeCells('D9:G9')
            ->setCellValue('D10', "BARANG")
			->mergeCells('D10:E10')
            ->setCellValue('D11', "JUMLAH")
            ->setCellValue('E11', "SATUAN")
			->setCellValue('F10', "BARANG")
			->mergeCells('F10:G10')
            ->setCellValue('F11', "SATUAN\n(Rp)")
            ->setCellValue('G11', "JUMLAH\n(Rp)")
			->setCellValue('H9', "MENURUT OPNAME")
			->mergeCells('H9:K9')
            ->setCellValue('H10', "BARANG")
			->mergeCells('H10:I10')
            ->setCellValue('H11', "JUMLAH")
            ->setCellValue('I11', "SATUAN")
			->setCellValue('J10', "BARANG")
			->mergeCells('J10:K10')
            ->setCellValue('J11', "SATUAN\n(Rp)")
            ->setCellValue('K11', "JUMLAH\n(Rp)")
			->setCellValue('L8', "KET")
			->mergeCells('L8:L11');	

$objPHPExcel->getActiveSheet()
            ->setCellValue('A12', '1')
            ->setCellValue('B12', '2')
			->mergeCells('B12:C12')
            ->setCellValue('D12', '3')
            ->setCellValue('E12', '4')
            ->setCellValue('F12', '5')
            ->setCellValue('G12', '6')
            ->setCellValue('H12', '7')
            ->setCellValue('I12', '8')
            ->setCellValue('J12', '9')
            ->setCellValue('K12', '10');

$row = 13; $no = 1; $id_gudang = ""; $id_sumber = "";
while($d =mysql_fetch_assoc($data)){
	$totadmin = $d['jml_admin']*$d['hrgsat_admin'];
	$totso = $d['jml_so']*$d['hrgsat_so'];
	$jml_admin =$d['jml_admin'];
	$hrgsat_admin =$d['hrgsat_admin'];
	$hrgtot_admin =$totadmin;
	$jml_so =$d['jml_so'];
	$hrgsat_so =$d['hrgsat_so'];
	$hrgtot_so =$totso;
	
	if($jenis=="gudang"){
		if($id_gudang!=$d['id_gudang']){
			$objPHPExcel->getActiveSheet()
						->setCellValue('B'.$row, "$d[nama_gudang]")
						->mergeCells("B$row:C$row");
			$row++; $no = 1;			
		}
		$id_gudang = $d['id_gudang'];
	}elseif($jenis=="sumber"){
		if($id_sumber!=$d['id_sumber_dana']){
			$objPHPExcel->getActiveSheet()
						->setCellValue('B'.$row, "$d[nama_sumber]")
						->mergeCells("B$row:C$row");
			$row++; $no = 1;			
		}
		$id_sumber = $d['id_sumber_dana'];
	}
	$objPHPExcel->getActiveSheet()
            ->setCellValue('A'.$row, "$no")
            ->setCellValue('B'.$row, "$d[nama_bar]")
			->mergeCells("B$row:C$row")
            ->setCellValue('D'.$row, "$jml_admin")
            ->setCellValue('E'.$row, "$d[sat_admin]")
            ->setCellValue('F'.$row, "$hrgsat_admin")
            ->setCellValue('G'.$row, "$hrgtot_admin")
			->setCellValue('H'.$row, "$jml_so")
            ->setCellValue('I'.$row, "$d[sat_so]")
            ->setCellValue('J'.$row, "$hrgsat_so")
            ->setCellValue('K'.$row, "$hrgtot_so")
            ->setCellValue('L'.$row, "");
	
	$row++;	$no++;
	
}

//$objPHPExcel->getActiveSheet()->getStyle("I12:I$row")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("A13:A$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("D13:E$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("H13:I$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//$objPHPExcel->getActiveSheet()->getStyle("A12:K$row")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("D13:D$row")->getNumberFormat()->setFormatCode("#,##0.00");
$objPHPExcel->getActiveSheet()->getStyle("F13:H$row")->getNumberFormat()->setFormatCode("#,##0.00");
$objPHPExcel->getActiveSheet()->getStyle("J13:K$row")->getNumberFormat()->setFormatCode("#,##0.00");
			
//tulis border
$row--;
$objPHPExcel->getActiveSheet()->getStyle("A8:L$row")->applyFromArray($BStyle);
$objPHPExcel->getActiveSheet()->getStyle("A12:L12")->applyFromArray($BTStyle);


//tulis footer
$row+=2; $date = tgl_indo(date('Y-m-d'));
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
/* $row++;			
$objPHPExcel->getActiveSheet()->setCellValue("G$row", 'BUPATI KARANGANYAR,');
$objPHPExcel->getActiveSheet()->getStyle("G$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$row+=3;
$objPHPExcel->getActiveSheet()->setCellValue("G$row", 'JULIYATMONO');
$objPHPExcel->getActiveSheet()->getStyle("G$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
 */
			
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Daftar Hitung Fisik');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Daftar Hitung Fisik.xlsx"');
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
$objWriter->save('../xls/Daftar Hitung Fisik '.$tgl_cetak_now.'.xlsx');
$response = array( 'success' => true, 'url' => './xls/Daftar Hitung Fisik '.$tgl_cetak_now.'.xlsx' );
header('Content-type: application/json');
// and in the end you respond back to javascript the file location
echo json_encode($response);

mysql_close();
exit;
