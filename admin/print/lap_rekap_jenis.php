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

// error_reporting(E_ALL); ini_set('display_errors', 'ON'); 

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

	$cuk = "X";
	
	$peran = cekLogin(); 
	$id_sub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
	$id_sum = isset($_POST['id_sum']) ? $_POST['id_sum'] : '';
	$tglawal = isset($_POST['tglawal']) ? $_POST['tglawal'] : '';
	$tglakhir = isset($_POST['tglakhir']) ? $_POST['tglakhir'] : '';
	$ta = isset($_POST['ta']) ? $_POST['ta'] : date('Y');
	$smstr = isset($_POST['smstr']) ? $_POST['smstr'] : '';
	$akses = isset($_POST['akses']) ? $_POST['akses'] : '';
		
	if($id_sub!=""){
		$b = " AND uuid_sub2_unit = '$id_sub'";
		$b1 = " AND d.uuid_skpd = '$id_sub'";
	}else{ $b = " AND MD5(uuid_sub2_unit) = '$_SESSION[uidunit]'";
			$b1 = " AND MD5(d.uuid_skpd) = '$_SESSION[uidunit]'"; }	
		
		
	$skpd = mysql_fetch_assoc(mysql_query("SELECT nm_sub2_unit, kd_sub, uuid_sub2_unit FROM ref_sub2_unit WHERE kd_sub IS NOT NULL $b "));
	// echo "SELECT nm_sub2_unit, kd_sub, uuid_sub2_unit FROM ref_sub2_unit WHERE d.kd_sub IS NOT NULL $b";
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
	$t1 = $tglawal ;
	$t2 = $tglakhir ;
	
	$tglawal = balikTanggal($tglawal);
	$tglakhir = balikTanggal($tglakhir);
	
	$kode = mysql_fetch_assoc(mysql_query("SELECT * FROM ref_sub2_unit WHERE uuid_sub2_unit = '$id_sub' "));
	
	
	$ta_lalu = $ta-1;
	
	if($akses == 2){
		$e1 = "	AND t4.kd_urusan = '$kode[kd_urusan]'
				AND t4.kd_bidang = '$kode[kd_bidang]'
				AND t4.kd_unit = '$kode[kd_unit]' ";
		$koder = " AND t1.kode != 'r' AND t1.kode != 'os' AND ( t1.kode = 'a' OR t1.kode = 'i' OR t1.kode = 'ok' OR t1.kode = 's' OR t1.kode = 'd') ";
				
	} else if($akses == 3){
		$e1 = "	AND t4.kd_urusan = '$kode[kd_urusan]'
				AND t4.kd_bidang = '$kode[kd_bidang]'
				AND t4.kd_unit = '$kode[kd_unit]'
				AND t4.kd_sub = '$kode[kd_sub]' ";
		$koder = " AND (t1.keterangan = 'r' OR t1.kode = 'a' OR t1.kode = 'i' OR t1.kode = 'ok' OR t1.kode = 's' OR t1.kode = 's' OR t1.kode = 'd') ";
				
	} else if($akses == 4){
		// $e1 = "	AND t4.kd_urusan = '$kode[kd_urusan]'
				// AND t4.kd_bidang = '$kode[kd_bidang]'
				// AND t4.kd_unit = '$kode[kd_unit]'
				// AND t4.kd_sub = '$kode[kd_sub]'
				// AND t4.kd_sub2 = '$kode[kd_sub2]' ";
		if($id_sub == ""){
			$e1 = "	AND t1.uuid_skpd = '$_SESSION[uidunit_plain]' ";
		} else {
			$e1 = "	AND t1.uuid_skpd = '$id_sub' ";
		}
		$koder = "";
	} else if($akses == 5){
		$e1 = "	AND t1.uuid_skpd = '$id_sub' ";
		$koder = "";
	} else {
		$id_sub = $_SESSION["uidunit_plain"];
		$e1 = "	AND t1.uuid_skpd = '$_SESSION[uidunit_plain]' ";
		$koder = "";
	}
	
	if($id_sub!=""){
		$wh = "WHERE uuid_sub2_unit = '$id_sub'";
		$sub = "AND uuid_skpd = '$id_sub'";
	}else{
		$wh = "WHERE MD5(uuid_sub2_unit) = '$_SESSION[uidunit]'";
		$sub = "AND MD5(uuid_skpd) = '$_SESSION[uidunit]'";
	}

	if($id_sum!=""){
		$idsum = " ";
	}else{
		$idsum = "";
	}
	if($ta=="") $ta = date('Y');
	$bl = " AND DATE_FORMAT(t1.tgl_transaksi, '%Y-%m-%d') BETWEEN '$tglawal' AND '$tglakhir'";
	$bl_lalu = " AND DATE_FORMAT(t1.tgl_transaksi, '%Y-%m-%d') < '$tglawal' ";
	
	
// Create new PHPExcel object
$objPHPExcel = PHPExcel_IOFactory::load("./lap.xlsx");
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
// $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);							 
// $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(70);	
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);	
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
// $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
// $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
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
// $objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
// $objPHPExcel->getActiveSheet()->getStyle('A2:J2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
// $objPHPExcel->getActiveSheet()->getStyle('A6:D7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A6:D7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);	
//$objPHPExcel->getActiveSheet()->getStyle('A6:J8')->getAlignment()->setWrapText(true);






// Add HEADER
$thn = explode('-', $t1);

$objPHPExcel->getActiveSheet()
            ->setCellValue('A1', 'REKONSILIASI PERSEDIAAN  TAHUN '.$thn[2])
            ->setCellValue('A2', $skpd["nm_sub2_unit"])
            ->setCellValue('A3', 'PERIODE')
            ->setCellValue('B3', ": $t1 s/d $t2");
			
$row = 8; $no = 1; 
$jtot1 = 0;
$jtot2 = 0;
$jtot3 = 0;
$jtot4 = 0;
$jtot5 = 0;
// $clause = "SELECT id_jenis, nama_jenis AS nama
			// FROM ref_jenis j 
			// WHERE kd_sub <> 0
			// ORDER BY j.kd_kel, j.kd_sub ASC";
$clause = "SELECT id_jenis, nama_jenis AS nama, kd_kel, kd_sub
			FROM ref_jenis j 
			ORDER BY j.kd_kel, j.kd_sub ASC";
$rs = mysql_query($clause);

$a1 = array();
$a2 = array();
$a3 = array();
$a4 = array();
$a5 = array();
$a6 = array();
$a7 = array();
$a8 = array();

while($rowz = mysql_fetch_assoc($rs)){
		$t = 0;
		$tot_cell_x1 = 0;
		$tot_cell_x2 = 0;
		$qsd = mysql_query("SELECT * FROM ref_sumber_dana WHERE id_sumber >= 28 AND id_sumber <= 35 ORDER BY id_sumber ASC");
		while($rrr=mysql_fetch_assoc($qsd)){
			
			if($rowz['kd_sub'] == '0'){
				$clause2 = "SELECT id_jenis, nama_jenis AS nama, kd_kel, kd_sub
							FROM ref_jenis j 
							WHERE kd_kel = '$rowz[kd_kel]'
							ORDER BY j.kd_kel, j.kd_sub ASC";
				$rs2 = mysql_query($clause2);
				
				$tot_cell_x1 = 0;
				while($rowz2 = mysql_fetch_assoc($rs2)){
					$qsd2 = mysql_query("SELECT * FROM ref_sumber_dana WHERE id_sumber >= 28 AND id_sumber <= 35 ORDER BY id_sumber ASC");
					// $jumlah = mysql_num_rows($qsd2);
					
					// buat variabel penampung dinamis
					// for($i = 1; $i <= $jumlah; $i++) {     
						// ${'total' . $i}; 
					// } 
					
					// $no_mulai = 1;
					// $tot_cell_x2 = 0;
					// while($rrr2=mysql_fetch_assoc($qsd2)){
						$in_lalu = mysql_fetch_assoc(mysql_query("SELECT SUM(t1.jml_in*t1.harga) AS ttl 
									FROM kartu_stok t1 LEFT JOIN ref_barang t2
									ON t1.id_barang = t2.id_barang
									LEFT JOIN ref_jenis t3
									ON t2.id_jenis = t3.id_jenis
									LEFT JOIN ref_sub2_unit t4 ON t1.uuid_skpd = t4.uuid_sub2_unit
								WHERE 
									t1.id_sumber_dana = '$rrr[id_sumber]' AND t3.id_jenis = '$rowz2[id_jenis]' AND t3.kd_kel = '$rowz2[kd_kel]' AND jml_in > 0 AND t1.soft_delete = '0' $e1 $bl_lalu $koder"));
									
						
						$out_lalu = mysql_fetch_assoc(mysql_query("SELECT SUM(jml_out*harga) AS ttl 
									FROM kartu_stok t1 LEFT JOIN ref_barang t2
									ON t1.id_barang = t2.id_barang
									LEFT JOIN ref_jenis t3
									ON t2.id_jenis = t3.id_jenis
									LEFT JOIN ref_sub2_unit t4 ON t1.uuid_skpd = t4.uuid_sub2_unit
								WHERE 
									t1.id_sumber_dana = '$rrr[id_sumber]' AND t3.id_jenis = '$rowz2[id_jenis]' AND t3.kd_kel = '$rowz2[kd_kel]' AND jml_out > 0 AND t1.soft_delete = '0'  $e1 $bl_lalu $koder"));
						
						$sisa_lalu = $in_lalu["ttl"]-$out_lalu["ttl"] ;
						// $rowz["saldo_awal"] = $sisa_lalu;
						
						// $t += $sisa_lalu;
						// $tot_cell_x2 += $sisa_lalu;
						// array_push($a1, array($rowz["nama"], "SA ".$rrr["nama_sumber"], $sisa_lalu));
					// }
					$tot_cell_x1 += $sisa_lalu;
					// $tot_cell_x1 += $tot_cell_x2;
				}
				
				$t += $tot_cell_x1;
				$rowz["saldo_awal"] = $tot_cell_x1;
				array_push($a1, array($rowz["nama"], "SA ".$rrr["nama_sumber"], $tot_cell_x1));
			}else{ // ASLI
				$in_lalu = mysql_fetch_assoc(mysql_query("SELECT SUM(t1.jml_in*t1.harga) AS ttl 
							FROM kartu_stok t1 LEFT JOIN ref_barang t2
							ON t1.id_barang = t2.id_barang
							LEFT JOIN ref_jenis t3
							ON t2.id_jenis = t3.id_jenis
							LEFT JOIN ref_sub2_unit t4 ON t1.uuid_skpd = t4.uuid_sub2_unit
						WHERE 
							t1.id_sumber_dana = '$rrr[id_sumber]' AND t3.id_jenis = '$rowz[id_jenis]' AND jml_in > 0 AND t1.soft_delete = '0' $e1 $bl_lalu $koder"));
							
				
				$out_lalu = mysql_fetch_assoc(mysql_query("SELECT SUM(jml_out*harga) AS ttl 
							FROM kartu_stok t1 LEFT JOIN ref_barang t2
							ON t1.id_barang = t2.id_barang
							LEFT JOIN ref_jenis t3
							ON t2.id_jenis = t3.id_jenis
							LEFT JOIN ref_sub2_unit t4 ON t1.uuid_skpd = t4.uuid_sub2_unit
						WHERE 
							t1.id_sumber_dana = '$rrr[id_sumber]' AND t3.id_jenis = '$rowz[id_jenis]' AND jml_out > 0 AND t1.soft_delete = '0'  $e1 $bl_lalu $koder"));
				
			
			
				$sisa_lalu = $in_lalu["ttl"]-$out_lalu["ttl"] ;
				$rowz["saldo_awal"] = $sisa_lalu;
				
				$t += $sisa_lalu;
				
				array_push($a1, array($rowz["nama"], "SA ".$rrr["nama_sumber"], $sisa_lalu));
			}
		} 
		
		array_push($a1, array($rowz["nama"], "T-SA ", (float)$t));
		
		$t = 0;
		$qsd = mysql_query("SELECT * FROM ref_sumber_dana WHERE id_sumber >= 28 AND id_sumber <= 35 ORDER BY id_sumber ASC");
		while($rrr=mysql_fetch_assoc($qsd)){
			if($rowz['kd_sub'] == '0'){
				$clause2 = "SELECT id_jenis, nama_jenis AS nama, kd_kel, kd_sub
							FROM ref_jenis j 
							WHERE kd_kel = '$rowz[kd_kel]'
							ORDER BY j.kd_kel, j.kd_sub ASC";
				$rs2 = mysql_query($clause2);
				
				$tot_cell_x1 = 0;
				while($rowz2 = mysql_fetch_assoc($rs2)){
					$in = mysql_fetch_assoc(mysql_query("SELECT SUM(t1.jml_in*t1.harga) AS ttl 
							FROM kartu_stok t1 LEFT JOIN ref_barang t2
							ON t1.id_barang = t2.id_barang
							LEFT JOIN ref_jenis t3
							ON t2.id_jenis = t3.id_jenis
							LEFT JOIN ref_sub2_unit t4 ON t1.uuid_skpd = t4.uuid_sub2_unit
						WHERE 
							t1.id_sumber_dana = '$rrr[id_sumber]' AND t3.id_jenis = '$rowz2[id_jenis]' AND t3.kd_kel = '$rowz2[kd_kel]' AND jml_in > 0 AND t1.soft_delete = '0' $e1 $bl $koder"));
					$tot_cell_x1 += $in["ttl"];
				}
				
				$t += $tot_cell_x1;
				array_push($a1, array($rowz["nama"], "M ".$rrr["nama_sumber"], (float)$tot_cell_x1));
			}else{ // ASLI
				$in = mysql_fetch_assoc(mysql_query("SELECT SUM(t1.jml_in*t1.harga) AS ttl 
							FROM kartu_stok t1 LEFT JOIN ref_barang t2
							ON t1.id_barang = t2.id_barang
							LEFT JOIN ref_jenis t3
							ON t2.id_jenis = t3.id_jenis
							LEFT JOIN ref_sub2_unit t4 ON t1.uuid_skpd = t4.uuid_sub2_unit
						WHERE 
							t1.id_sumber_dana = '$rrr[id_sumber]' AND t3.id_jenis = '$rowz[id_jenis]' AND jml_in > 0 AND t1.soft_delete = '0' $e1 $bl $koder"));
				$t += $in["ttl"];
				array_push($a1, array($rowz["nama"], "M ".$rrr["nama_sumber"], (float)$in["ttl"]));
			}
		}
		array_push($a1, array($rowz["nama"], "T-SA ", (float)$t));
		
		$t = 0;
		$qsd = mysql_query("SELECT * FROM ref_sumber_dana WHERE id_sumber >= 28 AND id_sumber <= 35 ORDER BY id_sumber ASC");
		while($rrr=mysql_fetch_assoc($qsd)){
			if($rowz['kd_sub'] == '0'){
				$clause2 = "SELECT id_jenis, nama_jenis AS nama, kd_kel, kd_sub
							FROM ref_jenis j 
							WHERE kd_kel = '$rowz[kd_kel]'
							ORDER BY j.kd_kel, j.kd_sub ASC";
				$rs2 = mysql_query($clause2);
				
				$tot_cell_x1 = 0;
				while($rowz2 = mysql_fetch_assoc($rs2)){
					$out = mysql_fetch_assoc(mysql_query("SELECT SUM(jml_out*harga) AS ttl 
							FROM kartu_stok t1 LEFT JOIN ref_barang t2
							ON t1.id_barang = t2.id_barang
							LEFT JOIN ref_jenis t3
							ON t2.id_jenis = t3.id_jenis
							LEFT JOIN ref_sub2_unit t4 ON t1.uuid_skpd = t4.uuid_sub2_unit
						WHERE 
							t1.id_sumber_dana = '$rrr[id_sumber]' AND t3.id_jenis = '$rowz2[id_jenis]' AND t3.kd_kel = '$rowz2[kd_kel]' AND jml_out > 0 AND t1.soft_delete = '0' $e1 $bl $koder"));
			
					$tot_cell_x1 += $out["ttl"];
				}
				
				$t += $tot_cell_x1;
				array_push($a1, array($rowz["nama"], "K ".$rrr["nama_sumber"], (float)$tot_cell_x1));
			}else{ // ASLI
				$out = mysql_fetch_assoc(mysql_query("SELECT SUM(jml_out*harga) AS ttl 
							FROM kartu_stok t1 LEFT JOIN ref_barang t2
							ON t1.id_barang = t2.id_barang
							LEFT JOIN ref_jenis t3
							ON t2.id_jenis = t3.id_jenis
							LEFT JOIN ref_sub2_unit t4 ON t1.uuid_skpd = t4.uuid_sub2_unit
						WHERE 
							t1.id_sumber_dana = '$rrr[id_sumber]' AND t3.id_jenis = '$rowz[id_jenis]' AND jml_out > 0 AND t1.soft_delete = '0' $e1 $bl $koder"));
			
				$t += $out["ttl"];
				array_push($a1, array($rowz["nama"], "K ".$rrr["nama_sumber"], (float)$out["ttl"]));
			}
		}
		array_push($a1, array($rowz["nama"], "T-SA ", (float)$t));
		
		$t = 0;
		$qsd = mysql_query("SELECT * FROM ref_sumber_dana WHERE id_sumber >= 28 AND id_sumber <= 35 ORDER BY id_sumber ASC");
		while($rrr=mysql_fetch_assoc($qsd)){
			if($rowz['kd_sub'] == '0'){
				$clause2 = "SELECT id_jenis, nama_jenis AS nama, kd_kel, kd_sub
							FROM ref_jenis j 
							WHERE kd_kel = '$rowz[kd_kel]'
							ORDER BY j.kd_kel, j.kd_sub ASC";
				$rs2 = mysql_query($clause2);
				
				$tot_cell_x1 = 0;
				while($rowz2 = mysql_fetch_assoc($rs2)){
					$in_lalu = mysql_fetch_assoc(mysql_query("SELECT SUM(t1.jml_in*t1.harga) AS ttl 
								FROM kartu_stok t1 LEFT JOIN ref_barang t2
								ON t1.id_barang = t2.id_barang
								LEFT JOIN ref_jenis t3
								ON t2.id_jenis = t3.id_jenis
								LEFT JOIN ref_sub2_unit t4 ON t1.uuid_skpd = t4.uuid_sub2_unit
							WHERE 
								t1.id_sumber_dana = '$rrr[id_sumber]' AND t3.id_jenis = '$rowz2[id_jenis]' AND jml_in > 0 AND t1.soft_delete = '0' $e1 $bl_lalu $koder"));
								
					
					$out_lalu = mysql_fetch_assoc(mysql_query("SELECT SUM(jml_out*harga) AS ttl 
								FROM kartu_stok t1 LEFT JOIN ref_barang t2
								ON t1.id_barang = t2.id_barang
								LEFT JOIN ref_jenis t3
								ON t2.id_jenis = t3.id_jenis
								LEFT JOIN ref_sub2_unit t4 ON t1.uuid_skpd = t4.uuid_sub2_unit
							WHERE 
								t1.id_sumber_dana = '$rrr[id_sumber]' AND t3.id_jenis = '$rowz2[id_jenis]' AND jml_out > 0 AND t1.soft_delete = '0'  $e1 $bl_lalu $koder"));
					
				
				
					$sisa_lalu = $in_lalu["ttl"]-$out_lalu["ttl"] ;
				
					$in = mysql_fetch_assoc(mysql_query("SELECT SUM(t1.jml_in*t1.harga) AS ttl 
								FROM kartu_stok t1 LEFT JOIN ref_barang t2
								ON t1.id_barang = t2.id_barang
								LEFT JOIN ref_jenis t3
								ON t2.id_jenis = t3.id_jenis
								LEFT JOIN ref_sub2_unit t4 ON t1.uuid_skpd = t4.uuid_sub2_unit
							WHERE 
								t1.id_sumber_dana = '$rrr[id_sumber]' AND t3.id_jenis = '$rowz2[id_jenis]' AND jml_in > 0 AND t1.soft_delete = '0' $e1 $bl $koder"));
					$out = mysql_fetch_assoc(mysql_query("SELECT SUM(jml_out*harga) AS ttl 
								FROM kartu_stok t1 LEFT JOIN ref_barang t2
								ON t1.id_barang = t2.id_barang
								LEFT JOIN ref_jenis t3
								ON t2.id_jenis = t3.id_jenis
								LEFT JOIN ref_sub2_unit t4 ON t1.uuid_skpd = t4.uuid_sub2_unit
							WHERE 
								t1.id_sumber_dana = '$rrr[id_sumber]' AND t3.id_jenis = '$rowz2[id_jenis]' AND jml_out > 0 AND t1.soft_delete = '0' $e1 $bl $koder"));
					$saldo = $in["ttl"]-$out["ttl"]+$sisa_lalu;
					$tot_cell_x1 += $saldo;
				}
				
				$t += $tot_cell_x1;
				array_push($a1, array($rowz["nama"], "S ".$rrr["nama_sumber"], $tot_cell_x1));
			}else{ // ASLI
				$in_lalu = mysql_fetch_assoc(mysql_query("SELECT SUM(t1.jml_in*t1.harga) AS ttl 
							FROM kartu_stok t1 LEFT JOIN ref_barang t2
							ON t1.id_barang = t2.id_barang
							LEFT JOIN ref_jenis t3
							ON t2.id_jenis = t3.id_jenis
							LEFT JOIN ref_sub2_unit t4 ON t1.uuid_skpd = t4.uuid_sub2_unit
						WHERE 
							t1.id_sumber_dana = '$rrr[id_sumber]' AND t3.id_jenis = '$rowz[id_jenis]' AND jml_in > 0 AND t1.soft_delete = '0' $e1 $bl_lalu $koder"));
							
				
				$out_lalu = mysql_fetch_assoc(mysql_query("SELECT SUM(jml_out*harga) AS ttl 
							FROM kartu_stok t1 LEFT JOIN ref_barang t2
							ON t1.id_barang = t2.id_barang
							LEFT JOIN ref_jenis t3
							ON t2.id_jenis = t3.id_jenis
							LEFT JOIN ref_sub2_unit t4 ON t1.uuid_skpd = t4.uuid_sub2_unit
						WHERE 
							t1.id_sumber_dana = '$rrr[id_sumber]' AND t3.id_jenis = '$rowz[id_jenis]' AND jml_out > 0 AND t1.soft_delete = '0'  $e1 $bl_lalu $koder"));
				
			
			
				$sisa_lalu = $in_lalu["ttl"]-$out_lalu["ttl"] ;
			
				$in = mysql_fetch_assoc(mysql_query("SELECT SUM(t1.jml_in*t1.harga) AS ttl 
							FROM kartu_stok t1 LEFT JOIN ref_barang t2
							ON t1.id_barang = t2.id_barang
							LEFT JOIN ref_jenis t3
							ON t2.id_jenis = t3.id_jenis
							LEFT JOIN ref_sub2_unit t4 ON t1.uuid_skpd = t4.uuid_sub2_unit
						WHERE 
							t1.id_sumber_dana = '$rrr[id_sumber]' AND t3.id_jenis = '$rowz[id_jenis]' AND jml_in > 0 AND t1.soft_delete = '0' $e1 $bl $koder"));
				$out = mysql_fetch_assoc(mysql_query("SELECT SUM(jml_out*harga) AS ttl 
							FROM kartu_stok t1 LEFT JOIN ref_barang t2
							ON t1.id_barang = t2.id_barang
							LEFT JOIN ref_jenis t3
							ON t2.id_jenis = t3.id_jenis
							LEFT JOIN ref_sub2_unit t4 ON t1.uuid_skpd = t4.uuid_sub2_unit
						WHERE 
							t1.id_sumber_dana = '$rrr[id_sumber]' AND t3.id_jenis = '$rowz[id_jenis]' AND jml_out > 0 AND t1.soft_delete = '0' $e1 $bl $koder"));
				$saldo = $in["ttl"]-$out["ttl"]+$sisa_lalu;
				$t += $saldo;
				array_push($a1, array($rowz["nama"], "S ".$rrr["nama_sumber"], $saldo));
			}
		}
		array_push($a1, array($rowz["nama"], "T-SA ", (float)$t));
}

	$arr_cell = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "AA", "AB", "AC", "AD", "AE", "AF", "AG", "AH", "AI", "AJ", "AK", "AL", "AM", "AN", "AO", "AP", "AQ", "AR", "AS", "AT", "AU", "AV", "AW", "AX", "AY", "AZ");
	
	$cellnya = "C";
	$barisnya = 6;
	$i = 2;
	foreach($a1 AS $r){
		
		if((float)$r[2] <= 0){
			$r[2] = " -";
		}
		
		if($aaa != $r[0]){
			$aaa = $r[0];
			
			$barisnya++;
			$i = 2;
			 
			$objPHPExcel->getActiveSheet()
				->setCellValue($arr_cell[$i].$barisnya, $r[2]);
		} else {
			if($r[1] == 'SA APBD Tingkat 2'){	// UNTUK CASE NAMA REF JENIS SAMA
				$aaa = $r[0];
				$barisnya++;
				$i = 2;
				 
				$objPHPExcel->getActiveSheet()
					->setCellValue($arr_cell[$i].$barisnya, $r[2]);
			}else{
				$objPHPExcel->getActiveSheet()
					->setCellValue($arr_cell[$i].$barisnya, $r[2]);
			}
			
			if($arr_cell[$i] == 'AK'){
				continue;
			}
		}
		
		// echo "isi = ".$r[0]."<br>";
		// echo "isi 1 = ".$r[1]."<br>";
		// echo "isi 2= ".$r[2]."<br>";
		// echo "baris = ".$arr_cell[$i]." ".$barisnya."<br>";
		// echo "------------------------------------<br>";
		$i++;
	}
	$br = 7;
	$qsd = mysql_query("SELECT kd_kel, kd_sub, id_jenis, nama_jenis AS nama
			FROM ref_jenis j 
			ORDER BY j.kd_kel, j.kd_sub ASC");
	while($rrr=mysql_fetch_assoc($qsd)){
			$objPHPExcel->getActiveSheet()
				->setCellValue("A".$br, " ".$rrr["kd_kel"].".".$rrr["kd_sub"])
				->setCellValue("B".$br, $rrr["nama"]);
			
			if($rrr["kd_sub"] == '0'){
				$objPHPExcel->getActiveSheet()->getStyle("A".$br)->getFont()->setBold( true );
				$objPHPExcel->getActiveSheet()->getStyle("B".$br)->getFont()->setBold( true );
			}
				
			$objPHPExcel->getActiveSheet()
						->setCellValue("AI".$br, "=Z".$br);
			
			$objPHPExcel->getActiveSheet()
						->setCellValue("AJ".$br, "=SUM(J".$br.",K".$br.",N".$br.")");
						
			$objPHPExcel->getActiveSheet()
						->setCellValue("AK".$br, "=AI".$br."-AJ".$br);
		$br++;
	}
	
	// $barisnya++;
	
	// UNTUK HITUNG TOTAL
	$br_tot = $barisnya+1;
	$arr_cell2 = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "AA", "AB", "AC", "AD", "AE", "AF", "AG", "AH", "AI", "AJ", "AK");
	// $objPHPExcel->getActiveSheet()
			// ->setCellValue('F'.$baris, "KABUPATEN KARANGANYAR")
			// ->mergeCells("A$baris:H$baris");
	
	$objPHPExcel->getActiveSheet()
						->setCellValue("B".$br_tot, "TOTAL");
	$objPHPExcel->getActiveSheet()->getStyle("B".$br_tot)->getFont()->setBold( true );
	
	foreach($arr_cell2 AS $cell){		
		if($cell == 'AI' || $cell == 'AJ' || $cell == 'AK'){
			$objPHPExcel->getActiveSheet()
						->setCellValue($cell.$br_tot, "=SUM(".$cell."7:".$cell.$barisnya.")");
		}else{
			// menghitung ulang total karena jenis induk tidak ikut tertotal
			$qsd3 = mysql_query("SELECT kd_kel, kd_sub, id_jenis, nama_jenis AS nama
					FROM ref_jenis j 
					ORDER BY j.kd_kel, j.kd_sub ASC");
			
			$bar = 7; $totcol = 0;
			while($rrr3=mysql_fetch_assoc($qsd3)){
				if($rrr3["kd_sub"] == '0'){
					$totcol += $objPHPExcel->getActiveSheet()->getCell($cell.$bar)->getValue();
				}
				$bar++;
			}
			
			$objPHPExcel->getActiveSheet()
							->setCellValue($cell.$br_tot, $totcol);
		}
		
		$objPHPExcel->getActiveSheet()->getStyle($cell.$br_tot)->getFont()->setBold( true );
	}
	
	// $objPHPExcel->getActiveSheet()
						// ->setCellValue("AI".$br_tot, "=SUM(AI7:AI".$barisnya.")");
	// $objPHPExcel->getActiveSheet()
				// ->setCellValue("AJ".$br_tot, "=SUM(AJ7:AJ".$barisnya.")");
	// $objPHPExcel->getActiveSheet()
				// ->setCellValue("AK".$br_tot, "=SUM(AK7:AK".$barisnya.")");
				
// $objPHPExcel->getActiveSheet()
            // ->setCellValue('B'.$row, "JUMLAH TOTAL")
			// ->mergeCells("B$row:C$row")
            // ->setCellValue('C'.$row, "$jtot1")
            // ->setCellValue('D'.$row, "$jtot2")
            // ->setCellValue('E'.$row, "$jtot3")
            // ->setCellValue('F'.$row, "$jtot4")
            // ->setCellValue('G'.$row, "$jtot5");
    
//$objPHPExcel->getActiveSheet()->getStyle("K9:K$row")->getAlignment()->setWrapText(true);
//$objPHPExcel->getActiveSheet()->getStyle("M9:M$row")->getAlignment()->setWrapText(true);
// $objPHPExcel->getActiveSheet()->getStyle("A8:A$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
// $objPHPExcel->getActiveSheet()->getStyle("C8:C$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//$objPHPExcel->getActiveSheet()->getStyle("A9:N$row")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("C7:AK$br_tot")->getNumberFormat()->setFormatCode("#,##0.00");
//tulis border

$objPHPExcel->getActiveSheet()->getStyle("A6:AK$br_tot")->applyFromArray($BStyle);
$objPHPExcel->getActiveSheet()->getStyle("A6:AK6")->applyFromArray($BTStyle);


//tulis footer
	
$skpd1 = mysql_fetch_assoc(mysql_query("SELECT * FROM pejabat WHERE uuid_skpd = '$id_sub' AND id_jabatan = '1' "));
$skpd2 = mysql_fetch_assoc(mysql_query("SELECT * FROM pejabat WHERE uuid_skpd = '$id_sub' AND id_jabatan = '2' "));
$skpd3 = mysql_fetch_assoc(mysql_query("SELECT * FROM pejabat WHERE uuid_skpd = '$id_sub' AND id_jabatan = '3' "));

$rr["pengguna"] = $skpd1["nama_pejabat"];
$rr["pengurus"] = $skpd2["nama_pejabat"];
$rr["bendahara"] = $skpd3["nama_pejabat"];

$row = $barisnya+3; 
$date = tgl_indo(date('Y-m-d'));
$objPHPExcel->getActiveSheet()
            ->setCellValue("F$row", "Karanganyar, $date");
$row++;
$objPHPExcel->getActiveSheet()
            ->setCellValue("B$row", "Kepala ".$skpd['nm_sub2_unit'])
            ->setCellValue("D$row", "Pengurus Barang")
            ->setCellValue("F$row", "Bendahara Pengeluaran");
$row+=4;
$objPHPExcel->getActiveSheet()
            ->setCellValue("B$row", "$rr[pengguna]")
            ->setCellValue("D$row", "$rr[pengurus]")
            ->setCellValue("F$row", "$rr[bendahara]");
$row++;
$objPHPExcel->getActiveSheet()
            ->setCellValue("B$row", "NIP $skpd1[nip]")
            ->setCellValue("D$row", "NIP $skpd2[nip]")
            ->setCellValue("F$row", "NIP $skpd3[nip]");
/* $row++;			
$objPHPExcel->getActiveSheet()->setCellValue("F$row", 'BUPATI KARANGANYAR,');
$objPHPExcel->getActiveSheet()->getStyle("F$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$row+=3;
$objPHPExcel->getActiveSheet()->setCellValue("F$row", 'JULIYATMONO');
$objPHPExcel->getActiveSheet()->getStyle("F$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
 */
			
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Laporan Rekapitulasi per Jenis');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client???s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Laporan Rekapitulasi per Barang.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->setPreCalculateFormulas(true);
//$objWriter->save('php://output');
$objWriter->save('../xls/Laporan Rekapitulasi per Jenis.xlsx');
$response = array( 'success' => true, 'url' => './xls/Laporan Rekapitulasi per Jenis.xlsx' );
header('Content-type: application/json');
// and in the end you respond back to javascript the file location
echo json_encode($response);

mysql_close();
exit;
