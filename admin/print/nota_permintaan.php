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


foreach (glob("../doc/Nota Permintaan*.*") as $filename) {
    unlink($filename);
}

foreach (glob("../doc/SP Pengeluaran*.*") as $filename) {
    unlink($filename);
}

foreach (glob("../doc/Surat Permintaan*.*") as $filename) {
    unlink($filename);
}

foreach (glob("../doc/BA Stock Opname*.*") as $filename) {
    unlink($filename);
}

$id = isset($_POST['id']) ? $_POST['id'] : '';

$nota = mysql_fetch_assoc(mysql_query("SELECT no_nota, tgl_nota, unit_peminta, kd_sub, uuid_sub2_unit FROM nota_minta 
							LEFT JOIN ref_sub2_unit ON uuid_sub2_unit = unit_peminta
							WHERE id_nota_minta = '$id'"));

if($nota['kd_sub'] == '1' || $nota['uuid_sub2_unit'] == 'cfa58008-5543-11e6-a2df-000476f4fa98'){
	$idjab = 2;
	$asman = "Pengurus Barang";
}else{
	$idjab = 2;
	$asman = "Pengurus Barang";
}

$pejabat = mysql_fetch_assoc(mysql_query("SELECT nama_pejabat, nip FROM pejabat WHERE uuid_skpd = '$nota[unit_peminta]' 
										AND id_jabatan = $idjab"));
$detail = mysql_query("SELECT nama_barang, jumlah, '' AS keterangan, simbol, s.id_barang as id_bar
						FROM nota_minta_detail s
						LEFT JOIN ref_barang b ON b.id_barang = s.id_barang
						LEFT JOIN ref_satuan t ON b.id_satuan = t.id_satuan
						WHERE id_nota_minta = '$id' AND s.soft_delete = 0");	
$count = mysql_num_rows($detail);
$tglini = tgl_indo($nota['tgl_nota']);

// Template processor instance creation
$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('../doc/Template Nota Permintaan.docx');

// Variables on different parts of document
//print_r($templateProcessor->getVariables());

$templateProcessor->setValue('NomorNota', htmlspecialchars($nota['no_nota']));
$templateProcessor->setValue('Tanggal', htmlspecialchars($tglini));
$templateProcessor->setValue('LabelAsman', htmlspecialchars($asman));
$templateProcessor->setValue('NamaPejabat', htmlspecialchars($pejabat['nama_pejabat']));
$templateProcessor->setValue('NIPPejabat', htmlspecialchars($pejabat['nip']));

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
$templateProcessor->saveAs('../doc/Nota Permintaan '.$tgl_cetak_now.'.docx');

$response = array( 'success' => true, 'url' => './doc/Nota Permintaan '.$tgl_cetak_now.'.docx' );
header('Content-type: application/json');
echo json_encode($response);
mysql_close();