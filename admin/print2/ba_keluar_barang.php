<?php

/** Error reporting */
/* error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
 */
if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/../../config/phpexcel/PHPExcel.php';
require_once '../../config/db.koneksi.php';
require_once '../../config/db.function.php';
require_once '../../config/library.php';


foreach (glob("../xls/BA Pengeluaran Barang*.*") as $filename) {
    unlink($filename);
}

$id = isset($_POST['id']) ? $_POST['id'] : '';

$kel = mysql_fetch_assoc(mysql_query("SELECT uuid_skpd AS id_sub, no_ba_out, tgl_ba_out, uuid_untuk, peruntukan, jenis_out,
				id_pejabat_pengguna, id_pejabat_penyimpan
				FROM keluar WHERE id_keluar = '$id'"));

$b = " AND uuid_sub2_unit = '$kel[id_sub]'";
$d = " AND uuid_skpd = '$kel[id_sub]'";
	
	
//$where = "$a $b $c";
$skpd = mysql_fetch_assoc(mysql_query("SELECT d.nm_sub2_unit, kd_sub, uuid_sub2_unit FROM ref_sub2_unit d WHERE d.kd_sub IS NOT NULL $b"));
if($skpd['kd_sub']==1 || $skpd['uuid_sub2_unit'] == 'cfa58008-5543-11e6-a2df-000476f4fa98'){
	$pen = 10; $kep = 8; $txtPengurus = "Pengurus Barang"; $txtPengguna = "Pengguna Barang";
}else{
	$pen = 11; $kep = 9;  $txtPengurus = "Pembantu Pengurus Barang"; $txtPengguna = "Kuasa Pengguna Barang";
}
$data = mysql_query("SELECT nama_barang, jml_barang, harga_barang, tgl_minta, tgl_terima, nama_satuan, d.id_barang AS id_bar
				FROM keluar_detail d
				LEFT JOIN ref_barang b ON d.id_barang = b.id_barang 
				LEFT JOIN ref_satuan s ON s.id_satuan = b.id_satuan 
				WHERE d.soft_delete=0 AND id_keluar = '$id'");

$guna = mysql_query("SELECT nama_pejabat, nip, nama_golongan, pangkat FROM pejabat 
						LEFT JOIN ref_golongan ON ref_golongan.id_golongan = pejabat.id_golongan
						WHERE id_jabatan = '$kep' $d");
if(mysql_num_rows($guna)!=0){
	$g = mysql_fetch_assoc($guna);
	$pengguna = $g['nama_pejabat']; 
	$nipp = $g['nip']; 
	$pangkatp = $g['pangkat']."/".$g['nama_golongan'];
}else{
	$pengguna = $nipp = $pangkatp = "...................";
}

$simp = mysql_query("SELECT nama_pejabat, nip, nama_golongan, pangkat FROM pejabat 
						LEFT JOIN ref_golongan ON ref_golongan.id_golongan = pejabat.id_golongan
						WHERE id_jabatan = '$pen' $d");
if(mysql_num_rows($simp)!=0){
	$s = mysql_fetch_assoc($simp);
	$pengurus = $s['nama_pejabat']; 
	$nipy = $s['nip'];
	$pangkaty = $s['pangkat']."/".$s['nama_golongan'];
}else{
	$pengurus = $nipy = $pangkaty = "...................";
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
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(21);	
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(23);	
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(19);	
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);	
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);	
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
$objPHPExcel->getActiveSheet()->getRowDimension(6)->setRowHeight(30);
//border
$BStyle = array(
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  )
);

$OStyle = array(
  'borders' => array(
    'outline' => array(
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
//$objPHPExcel->getActiveSheet()->getStyle('A4:I4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A4:I7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A4:I7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);	
$objPHPExcel->getActiveSheet()->getStyle('B5:I6')->getAlignment()->setWrapText(true);

// Add HEADER
$objPHPExcel->getActiveSheet()
            ->setCellValue('A1', 'SKPD/UNIT KERJA')
            ->setCellValue('C1', ": $skpd[nm_sub2_unit] ")
            ->setCellValue('A2', 'KABUPATEN')
            ->setCellValue('C2', ": KARANGANYAR")
			->setCellValue('G2', 'NOMOR')
            ->setCellValue('H2', ": $kel[no_ba_out]");
$objPHPExcel->getActiveSheet()
            ->setCellValue('A4', 'BUKTI PENGAMBILAN BARANG PERSEDIAAN DARI GUDANG/TEMPAT PENYIMPANAN')
			->mergeCells('A4:I4');
$objPHPExcel->getActiveSheet()
            ->setCellValue('A5', 'NO')
            ->mergeCells('A5:A6')
            ->setCellValue('B5', "Tanggal Penyerahan Barang \n Menurut Permintaan")
			->mergeCells('B5:C6')
            ->setCellValue('D5', "Barang diterima dari \n gudang / Tempat \n penyimpanan")
			->mergeCells('D5:D6')
            ->setCellValue('E5', "Nama dan Kode \n Barang")
			->mergeCells('E5:E6')
			->setCellValue('F5', "Satuan")
			->mergeCells('F5:F6')
			->setCellValue('G5', "Jumlah Barang")
			->mergeCells('G5:H5')
			->setCellValue('G6', "(angka)")
			->setCellValue('H6', "(huruf)")
			->setCellValue('I5', "Jumlah Harga (Rp)")
			->mergeCells('I5:I6');	

$objPHPExcel->getActiveSheet()
            ->setCellValue('A7', '1')
            ->setCellValue('B7', '2')
			->mergeCells('B7:C7')
            ->setCellValue('D7', '3')
            ->setCellValue('E7', '4')
            ->setCellValue('F7', '5')
            ->setCellValue('G7', '6')
            ->setCellValue('H7', '7')
            ->setCellValue('I7', '8');


$row = 8; $no = 1;		
while($d =mysql_fetch_assoc($data)){
	$harga = number_format($d['harga_barang'] * $d['jml_barang'], 0,'','');
	$tgl_minta = balikTanggalIndo($d['tgl_minta']);
	$tgl_terima = balikTanggalIndo($d['tgl_terima']);
	$huruf = terbilang( number_format($d['jml_barang'],6,',',''));
	$bar = mysql_fetch_assoc(mysql_query(" SELECT * FROM ref_barang_kegiatan a LEFT JOIN ref_satuan b ON a.id_satuan = b.id_satuan WHERE id_barang_kegiatan = '$d[id_bar]' "));
	if($d['nama_barang'] == ""){
		$d['nama_barang'] = $bar['nama_barang_kegiatan'];
		$d['simbol'] = $bar['simbol'];
	}
	
	$objPHPExcel->getActiveSheet()
            ->setCellValue('A'.$row, "$no")
            ->setCellValue('B'.$row, "$tgl_minta")
			->mergeCells("B$row:C$row")
            ->setCellValue('D'.$row, "$tgl_terima")
            ->setCellValue('E'.$row, "$d[nama_barang]")
            ->setCellValue('F'.$row, "$d[nama_satuan]")
            ->setCellValue('G'.$row, "$d[jml_barang]")
			->setCellValue('H'.$row, "$huruf")
            ->setCellValue('I'.$row, "$harga");
	
	$row++;	$no++;	
}
	
$objPHPExcel->getActiveSheet()->getStyle("H8:H$row")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("A8:A$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("G8:G$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("A8:I$row")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
//$objPHPExcel->getActiveSheet()->getStyle("H8:H$row")->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle("I8:I$row")->getNumberFormat()->setFormatCode("#,##0.00");	

			
//tulis border
$row--;
$objPHPExcel->getActiveSheet()->getStyle("A5:I$row")->applyFromArray($BStyle);
$objPHPExcel->getActiveSheet()->getStyle("A7:I7")->applyFromArray($BTStyle);


//tulis footer 
$row+=2; $hfrow = $row; 
$tgl_ba = strtotime($kel['tgl_ba_out']);
$tgl = date('d', $tgl_ba); $bln = getBulan(date('m', $tgl_ba)); $thn = date('Y', $tgl_ba);
	$objPHPExcel->getActiveSheet()
				->setCellValue("B$row", " SKPD/Unit Kerja . . . . . . . . . . . . . . .")
				->setCellValue("G$row", " Dibuat di. . . . . . . . . . . . . . . . . .");
	$row++;			
	$objPHPExcel->getActiveSheet()
				->setCellValue("B$row", " Tgl........ Bulan..........Tahun.........")
				->setCellValue("G$row", " Tgl $tgl Bulan $bln Tahun $thn");
	$row+=2;
	$objPHPExcel->getActiveSheet()
				->setCellValue("B$row", "Yang Menerima")
				->mergeCells("B$row:C$row")
				->setCellValue("G$row", " Yang Menyerahkan");
	$objPHPExcel->getActiveSheet()->getStyle("B$row:C$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$row++;
	$objPHPExcel->getActiveSheet()
				->setCellValue("E$row", " Mengetahui")
				->setCellValue("G$row", " $txtPengurus");
	$row++;
	$objPHPExcel->getActiveSheet()
				->setCellValue("B$row", " Tanda Tangan....................")
				->setCellValue("E$row", " An. $txtPengguna");
	$row++;
	$objPHPExcel->getActiveSheet()
				->setCellValue("B$row", " Nama....................")
				->setCellValue("G$row", " Tanda Tangan....................");
	$row++;
	$objPHPExcel->getActiveSheet()
				->setCellValue("B$row", " NIP....................")
				->setCellValue("E$row", " Tanda Tangan....................")
				->setCellValue("G$row", " Nama $pengurus");
	$row++;
	$objPHPExcel->getActiveSheet()
				->setCellValue("B$row", " Pangkat/Golongan....................")
				->setCellValue("E$row", " Nama $pengguna")
				->setCellValue("G$row", " NIP $nipy");
	$row++;
	$objPHPExcel->getActiveSheet()
				->setCellValue("E$row", " NIP $nipp")
				->setCellValue("G$row", " Pangkat/Golongan $pangkaty");
	$row++;
	$objPHPExcel->getActiveSheet()
				->setCellValue("E$row", " Pangkat/Golongan $pangkatp");

	//tulis border
	$objPHPExcel->getActiveSheet()->getStyle("B$hfrow:D$row")->applyFromArray($OStyle);
	$objPHPExcel->getActiveSheet()->getStyle("E$hfrow:F$row")->applyFromArray($OStyle);
	$objPHPExcel->getActiveSheet()->getStyle("G$hfrow:I$row")->applyFromArray($OStyle);
	
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('BA Pengeluaran Barang');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="BA Pengeluaran Barang.xlsx"');
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
$objWriter->save('../xls/BA Pengeluaran Barang '.$tgl_cetak_now.'.xlsx');
$response = array( 'success' => true, 'url' => './xls/BA Pengeluaran Barang '.$tgl_cetak_now.'.xlsx' );
header('Content-type: application/json');
// and in the end you respond back to javascript the file location
echo json_encode($response);

mysql_close();
exit;
