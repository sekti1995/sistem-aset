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

foreach (glob("../xls/Buku Penerimaan*.*") as $filename) {
    unlink($filename);
}


$peran = cekLogin();
	
$kepala = $pengurus = $nipk = $nipp = "";
$id_sub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
$id_sumber = isset($_POST['id_sum']) ? $_POST['id_sum'] : '';
$ta = isset($_POST['ta']) ? $_POST['ta'] : '';
$bln = isset($_POST['bln']) ? $_POST['bln'] : '';

if($ta=="") $ta = date('Y');

if($id_sub!=""){ $d = " AND u.uuid_skpd = '$id_sub'"; $b = " AND d.uuid_sub2_unit = '$id_sub'"; }
else{ $d = " AND MD5(u.uuid_skpd) = '$_SESSION[uidunit]'"; $b = " AND MD5(d.uuid_sub2_unit) = '$_SESSION[uidunit]'";	}

if($bln!=""){ 
	$bln = str_pad($bln,2,'0', STR_PAD_LEFT);
}else{ 
	$bln = date('m');
}

$a = " AND u.ta = '$ta'";
$c = " AND DATE_FORMAT(tgl_terima, '%m') = '$bln'"; 
$bulan = getBulan($bln); 
	
if($id_sumber!=""){ 
	$e = " AND id_sumber = '$id_sumber'"; 
}else $e = ""; 
	
	
$where = "$a $d $c $e";
$skpd = mysql_fetch_assoc(mysql_query("SELECT d.nm_sub2_unit, kd_sub, uuid_sub2_unit FROM ref_sub2_unit d WHERE d.kd_sub IS NOT NULL $b"));
if($skpd['kd_sub']==1 || $skpd['uuid_sub2_unit'] == 'cfa58008-5543-11e6-a2df-000476f4fa98'){
	$in = "(8,10)"; $kep = 8; $txtPengurus = "Pengurus Barang";
}else{
	$in = "(9,11)"; $kep = 9;  $txtPengurus = "Pembantu Pengurus Barang";
}

$data = mysql_query("SELECT tgl_terima, dari, no_dok, tgl_dok, no_ba, tgl_ba, jml_masuk, harga_masuk, nm_sub2_unit AS unit, 
							IFNULL(nama_barang, nama_barang_kegiatan) AS nama_barang
				FROM ( SELECT
						id_barang,
						jml_masuk,
						harga_masuk,
						d.uuid_skpd,
						tgl_penerimaan     AS tgl_terima,
						tgl_penerimaan     AS tgl_ba,
						no_ba_penerimaan   AS no_ba,
						tgl_dok_penerimaan AS tgl_dok,
						no_dok_penerimaan  AS no_dok,
						nama_penyedia      AS dari,
						m.id_sumber,
						m.ta
					  FROM masuk_detail d
						LEFT JOIN masuk m
						  ON m.id_masuk = d.id_masuk
					  WHERE d.soft_delete = 0
						  AND m.status_proses = 3 
					  UNION SELECT
							  id_barang,
							  jml_barang          AS jml_masuk,
							  harga_barang        AS harga_masuk,
							  td.uuid_skpd,
							  tgl_terima,
							  tgl_ba_out          AS tgl_ba,
							  no_ba_out           AS no_ba,
							  ''                  AS tgl_dok,
							  ''                  AS no_dok,
							  nm_sub2_unit        AS dari,
							  td.id_sumber_dana   AS id_sumber,
							  t.ta
							FROM terima_keluar_detail td
							  LEFT JOIN terima_keluar t
								ON td.id_terima_keluar = t.id_terima_keluar
							  LEFT JOIN keluar k
								ON k.id_keluar = t.id_keluar
							  LEFT JOIN ref_sub2_unit s2
								ON s2.uuid_sub2_unit = k.uuid_skpd
							WHERE t.soft_delete = 0
				) AS u
			  LEFT JOIN ref_sub2_unit s
				ON s.uuid_sub2_unit = u.uuid_skpd
			  LEFT JOIN ref_barang b
				ON b.id_barang = u.id_barang
			  LEFT JOIN ref_barang_kegiatan bk
				ON bk.id_barang_kegiatan = u.id_barang
			  WHERE u.id_barang IS NOT NULL $where");
if($d!=""){				
	$pejabat = mysql_query("SELECT nama_pejabat, nip, id_jabatan FROM pejabat u WHERE id_jabatan IN $in $d");
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
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(3);	
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(17);	
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);	
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(13);	
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(16);	
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);	
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);	
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(12);	
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);	
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(13);	
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(7);
//border
$BStyle = array(
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  )
);


//STYLE
/* 			
$objPHPExcel->getActiveSheet()->getStyle('D6:E6')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('B6:B7')->getAlignment()->setWrapText(true); */
$objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A7:M10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A7:M10')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);	
$objPHPExcel->getActiveSheet()->getStyle('G7:J9')->getAlignment()->setWrapText(true);

// Add HEADER
$objPHPExcel->getActiveSheet()
            ->setCellValue('A1', 'BUKU PENERIMAAN BARANG PERSEDIAAN')
			->mergeCells('A1:M1');
$objPHPExcel->getActiveSheet()
            ->setCellValue('A3', 'SKPD/Unit Kerja')
            ->setCellValue('C3', ": $skpd[nm_sub2_unit] ")
            ->setCellValue('A4', 'Periode Bulan')
            ->setCellValue('C4', ": $bulan")
			->setCellValue('A5', 'Kabupaten')
            ->setCellValue('C5', ': Karanganyar');
$objPHPExcel->getActiveSheet()
            ->setCellValue('A7', 'NO')
            ->mergeCells('A7:A9')
            ->setCellValue('B7', "TGL TERIMA")
			->mergeCells('B7:C9')
            ->setCellValue('D7', "DARI")
			->mergeCells('D7:D9')
            ->setCellValue('E7', "DOKUMEN FAKTUR")
			->mergeCells('E7:F7')
			->setCellValue('E8', "NOMOR")
			->mergeCells('E8:E9')
			->setCellValue('F8', "TANGGAL")
			->mergeCells('F8:F9')
			->setCellValue('G7', "NAMA BARANG")
			->mergeCells('G7:G9')
			->setCellValue('H7', "BANYAKNYA")
			->mergeCells('H7:H9')
			->setCellValue('I7', "HARGA SATUAN (Rp.)")
			->mergeCells('I7:I9')
			->setCellValue('J7', "JUMLAH HARGA (Rp.)")
			->mergeCells('J7:J9')
			->setCellValue('K7', "BUKTI PENERIMAAN")
			->mergeCells('K7:L7')
			->setCellValue('K8', "B.A PENERIMAAN")
			->mergeCells('K8:L8')
			->setCellValue('K9', "NOMOR")
			->setCellValue('L9', "TANGGAL")
			->setCellValue('M7', "KET")
			->mergeCells('M7:M9');	

$objPHPExcel->getActiveSheet()
            ->setCellValue('A10', '1')
            ->setCellValue('B10', '2')
			->mergeCells('B10:C10')
            ->setCellValue('D10', '3')
            ->setCellValue('E10', '4')
            ->setCellValue('F10', '5')
            ->setCellValue('G10', '6')
            ->setCellValue('H10', '7')
            ->setCellValue('I10', '8')
            ->setCellValue('J10', '9')
            ->setCellValue('K10', '10')
            ->setCellValue('L10', '11')
            ->setCellValue('M10', '12');

$row = 11; $no = 1;		
while($d =mysql_fetch_assoc($data)){
	$harga = $d['harga_masuk'];
	$total = $harga * $d['jml_masuk'];
	$tgl_ba = balikTanggalIndo($d['tgl_ba']);
	$tgl_dok = balikTanggalIndo($d['tgl_dok']);
	
	$objPHPExcel->getActiveSheet()
            ->setCellValue('A'.$row, "$no")
            ->setCellValue('B'.$row, "$tgl_ba")
			->mergeCells("B$row:C$row")
            ->setCellValue('D'.$row, "$d[dari]")
            ->setCellValue('E'.$row, "$d[no_dok]")
            ->setCellValue('F'.$row, "$tgl_dok")
            ->setCellValue('G'.$row, "$d[nama_barang]")
			->setCellValue('H'.$row, "$d[jml_masuk]")
            ->setCellValue('I'.$row, "$harga")
            ->setCellValue('J'.$row, "$total")
            ->setCellValue('K'.$row, "$d[no_ba]")
            ->setCellValue('L'.$row, "$tgl_ba")
            ->setCellValue('M'.$row, "");
	
	$row++;	$no++;	
}
	
$objPHPExcel->getActiveSheet()->getStyle("D11:D$row")->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("A11:A$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("H11:H$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("A11:M$row")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("H11:J$row")->getNumberFormat()->setFormatCode("#,##0.00");

			
//tulis border
$row--;
$objPHPExcel->getActiveSheet()->getStyle("A7:M$row")->applyFromArray($BStyle);


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
$objPHPExcel->getActiveSheet()->setTitle('Buku Penerimaan');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Buku Penerimaan.xlsx"');
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
$objWriter->save('../xls/Buku Penerimaan '.$tgl_cetak_now.'.xlsx');
$response = array( 'success' => true, 'url' => './xls/Buku Penerimaan '.$tgl_cetak_now.'.xlsx' );
header('Content-type: application/json');
// and in the end you respond back to javascript the file location
echo json_encode($response);

mysql_close();
exit;
