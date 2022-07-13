<?php
require_once '../../config/phpword/Autoloader.php';
session_start();
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
$penata = $pengurus = $nipk = $nipp = "";

$sp = mysql_query("SELECT no_sp_out, tgl_sp_out, IFNULL(u.nm_sub2_unit,s.peruntukan) AS untuk, no_spb, u2.kd_sub, s.uuid_skpd
						FROM sp_out s
						LEFT JOIN ref_sub2_unit u ON s.uuid_untuk = u.uuid_sub2_unit
						LEFT JOIN ref_sub2_unit u2 ON s.uuid_skpd = u2.uuid_sub2_unit
						LEFT JOIN surat_minta m ON m.id_surat_minta = s.id_surat_minta
						LEFT JOIN pejabat p ON  p.uuid_skpd = s.uuid_skpd AND p.id_jabatan = '8' 
						WHERE id_sp_out = '$id'");
$detail = mysql_query("SELECT nama_barang, jml_barang, harga_barang, s.keterangan, s.id_barang AS id_bar
						FROM sp_out_detail s
						LEFT JOIN ref_barang b ON b.id_barang = s.id_barang
						WHERE id_sp_out = '$id' AND s.soft_delete = 0");				
$data = mysql_fetch_assoc($sp);
$count = mysql_num_rows($detail);
$tglini = tgl_indo($data['tgl_sp_out']);


if($data['kd_sub']==1 || $data['uuid_skpd'] == 'cfa58008-5543-11e6-a2df-000476f4fa98'){
	$in = "(7,8)"; $asman = "Pengurus Barang";
}else{
	$in = "(7,9)"; $asman = "Pembantu Pengurus Barang";
}
/* $penatausaha = mysql_fetch_assoc(mysql_query("SELECT nama_pejabat, nip
						FROM pejabat p LEFT JOIN sp_out o ON p.uuid_skpd = o.uuid_skpd
						WHERE id_jabatan = '6' AND id_sp_out = '$id'")); */

if($data['uuid_skpd']!=""){				
	$pejabat = mysql_query("SELECT nama_pejabat, nip, id_jabatan FROM pejabat m WHERE id_jabatan IN $in 
							AND uuid_skpd = '$data[uuid_skpd]'");
	while($t = mysql_fetch_assoc($pejabat)){
		if($t['id_jabatan']==7){ $penata = $t['nama_pejabat']; $nipk = $t['nip']; }
		else{ $pengurus = $t['nama_pejabat']; $nipp = $t['nip']; }
	}				

}else{
	$penata = ".......................";
	$pengurus = ".......................";
	$nipk = $nipp = ".......................";
}
							
// Template processor instance creation
if($data['kd_sub'] != '1' && $data['uuid_skpd'] != 'cfa58008-5543-11e6-a2df-000476f4fa98'){
$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('../doc/Template SP Pengeluaran UPTD.docx');
} else {
$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('../doc/Template SP Pengeluaran SKPD.docx');
}

// Variables on different parts of document
//print_r($templateProcessor->getVariables());

$templateProcessor->setValue('NomorSP', htmlspecialchars($data['no_sp_out']));
$templateProcessor->setValue('UntukSKPD', htmlspecialchars($data['untuk']));
$templateProcessor->setValue('DasarKeluar', htmlspecialchars($data['no_spb']));
$templateProcessor->setValue('Tanggal', htmlspecialchars($tglini));
$templateProcessor->setValue('NamaPengguna', htmlspecialchars($pengurus));
$templateProcessor->setValue('NIPPengguna', htmlspecialchars($nipp));

$templateProcessor->setValue('NamaPengguna2', htmlspecialchars($penata));
$templateProcessor->setValue('NIPPengguna2', htmlspecialchars($nipk));

 // Simple table
$templateProcessor->cloneRow('No', $count);
$i = 1;
while($d = mysql_fetch_assoc($detail)){
	$jumlah = number_format($d['jml_barang'], 0, ',', '.');
	$harga = number_format($d['harga_barang'], 0, ',', '.');
	$total = number_format($d['jml_barang']*$d['harga_barang'], 0, ',', '.');
	
	$bar = mysql_fetch_assoc(mysql_query(" SELECT * FROM ref_barang_kegiatan a LEFT JOIN ref_satuan b ON a.id_satuan = b.id_satuan WHERE id_barang_kegiatan = '$d[id_bar]' "));
	if($d['nama_barang'] == ""){
		$d['nama_barang'] = $bar['nama_barang_kegiatan'];
		$d['simbol'] = $bar['simbol'];
	}
	
	$templateProcessor->setValue('No#'.$i, htmlspecialchars($i));
	$templateProcessor->setValue('NamaBarang#'.$i, htmlspecialchars($d['nama_barang']));
	$templateProcessor->setValue('Banyak#'.$i, htmlspecialchars($jumlah));
	$templateProcessor->setValue('Harga#'.$i, htmlspecialchars($harga));
	$templateProcessor->setValue('Jumlah#'.$i, htmlspecialchars($total));
	$templateProcessor->setValue('Ket#'.$i, htmlspecialchars($d['keterangan']));
	$i++;
}

$date_now = date("YmdHis");

//echo date('H:i:s'), ' Saving the result document...';
$templateProcessor->saveAs('../doc/SP Pengeluaran '.$date_now.'.docx');

$response = array( 'success' => true, 'url' => './doc/SP Pengeluaran '.$date_now.'.docx' );
header('Content-type: application/json');
echo json_encode($response);
mysql_close();