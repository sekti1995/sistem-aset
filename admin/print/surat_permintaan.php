<?php
//error_reporting(E_ALL); ini_set('display_errors', 'On'); 

session_start();
require_once '../../config/phpword/Autoloader.php';
\PhpOffice\PhpWord\Autoloader::register();
require_once '../../config/db.koneksi.php';
require_once '../../config/db.function.php';
require_once '../../config/library.php';
if(!pengguna()){
	header('Content-type: application/json');
	echo json_encode(array('success'=>false, 'pesan'=>"Tidak dapat memproses data, Silahkan login ulang !", 'url'=>'../index.php'));
	mysql_close();
	exit();
}

$id = isset($_POST['id']) ? $_POST['id'] : '';
	
$nota = mysql_fetch_assoc(mysql_query("SELECT s.no_spb, s.tgl_spb, s.unit_peminta, b.nm_sub2_unit AS peminta, 
										b1.nm_sub2_unit AS dituju, peruntukan,
										stat_untuk, b.kd_sub, b.uuid_sub2_unit
										FROM surat_minta s 
										LEFT JOIN ref_sub2_unit b ON s.unit_peminta = b.uuid_sub2_unit
										LEFT JOIN ref_sub2_unit b1 ON s.unit_dituju = b1.uuid_sub2_unit
										WHERE id_surat_minta = '$id'"));
if($nota['kd_sub'] == '1' || $nota['uuid_sub2_unit'] == 'cfa58008-5543-11e6-a2df-000476f4fa98'){
	$idj = 10;
	$asman = "Pengurus Barang";
}else{
	$idj = 11;
	$asman = "Pengurus Barang Pembantu";
}										
if($nota['stat_untuk']==0){
	$dari = $nota['peruntukan'];
	$kepada = $nota['peminta'];
}else{
	$dari = $nota['peminta'];
	$kepada = $nota['dituju'];
}										
$pejabat = mysql_fetch_assoc(mysql_query("SELECT nama_pejabat, nip FROM pejabat WHERE uuid_skpd = '$nota[unit_peminta]' AND id_jabatan = $idj"));
$detail = mysql_query("SELECT nama_barang, jumlah, '' AS keterangan, simbol, s.id_barang AS id_bar
						FROM surat_minta_detail s
						LEFT JOIN ref_barang b ON b.id_barang = s.id_barang
						LEFT JOIN ref_satuan t ON b.id_satuan = t.id_satuan
						WHERE id_surat_minta = '$id' AND s.soft_delete = 0");	
$count = mysql_num_rows($detail);
$tglini = tgl_indo($nota['tgl_spb']);

// Template processor instance creation
$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('../doc/Template Surat Permintaan.docx');

// Variables on different parts of document
//print_r($templateProcessor->getVariables());

$templateProcessor->setValue('NomorSP', htmlspecialchars($nota['no_spb']));
$templateProcessor->setValue('Tanggal', htmlspecialchars($tglini));
$templateProcessor->setValue('LabelAsman', htmlspecialchars($asman));
$templateProcessor->setValue('NamaPengurus', htmlspecialchars($pejabat['nama_pejabat']));
$templateProcessor->setValue('NIPPengurus', htmlspecialchars($pejabat['nip']));
$templateProcessor->setValue('dari', htmlspecialchars($dari));
$templateProcessor->setValue('kepada', htmlspecialchars($kepada));

 // Simple table
$templateProcessor->cloneRow('No', $count);
$i = 1;
 while($d = mysql_fetch_assoc($detail)){
	$bar = mysql_fetch_assoc(mysql_query(" SELECT * FROM ref_barang_kegiatan a LEFT JOIN ref_satuan b ON a.id_satuan = b.id_satuan WHERE id_barang_kegiatan = '$d[id_bar]' "));
	if($d['nama_barang'] == ""){
		$d['nama_barang'] = $bar['nama_barang_kegiatan'];
		$d['simbol'] = $bar['simbol'];
	}
	
	$jumlah = number_format($d['jumlah'], 0, ',', '.');
	
	$templateProcessor->setValue('No#'.$i, htmlspecialchars($i));
	$templateProcessor->setValue('NamaBarang#'.$i, htmlspecialchars($d['nama_barang']));
	$templateProcessor->setValue('Jumlah#'.$i, htmlspecialchars($jumlah));
	$templateProcessor->setValue('Satuan#'.$i, htmlspecialchars($d['simbol']));
	$templateProcessor->setValue('Ket#'.$i, htmlspecialchars($d['keterangan']));
	$i++;
}

//echo date('H:i:s'), ' Saving the result document...';
$templateProcessor->saveAs('../doc/Surat Permintaan '.$tgl_cetak_now.'.docx');

$response = array( 'success' => true, 'url' => './doc/Surat Permintaan '.$tgl_cetak_now.'.docx' );
header('Content-type: application/json');
echo json_encode($response);
mysql_close();