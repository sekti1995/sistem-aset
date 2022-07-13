<?php
session_start();
require_once '../../config/phpword/Autoloader.php';
\PhpOffice\PhpWord\Autoloader::register();
require_once '../../config/db.koneksi.php';
require_once '../../config/library.php';

$id = isset($_POST['id']) ? $_POST['id'] : '';
 
	
$kepala = $pengurus = $nipk = $nipp = $golongank = "";
$id_sub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
$basket = isset($_POST['basket']) ? $_POST['basket'] : '';
$no_ba = isset($_POST['no_ba']) ? $_POST['no_ba'] : '';
$ta = isset($_POST['ta']) ? $_POST['ta'] : '';
$id_masuk = isset($_POST['id_masuk']) ? $_POST['id_masuk'] : '';

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
	$pejabat = mysql_query("SELECT nama_pejabat, nip, id_jabatan, id_golongan FROM pejabat d WHERE id_jabatan IN $in $b1");
	while($t = mysql_fetch_assoc($pejabat)){
		if($t['id_jabatan']==$kep){ $kepala = $t['nama_pejabat']; $nipk = $t['nip']; $golongank = $t['id_golongan']; }
		else{ $pengurus = $t['nama_pejabat']; $nipp = $t['nip']; }
	}
} else {
	$kepala = ".......................";
	$pengurus = ".......................";
	$nipk = $nipp = ".......................";
}

$gol = mysql_fetch_assoc(mysql_query("SELECT * FROM ref_golongan WHERE id_golongan = '$golongank'"));

$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('../lampiran/Template BA Pemeriksaan.docx');

$tgll = $_POST['tgl_pemeriksaan'];

$_POST['tgl_pemeriksaan'] = balikTanggal($_POST['tgl_pemeriksaan']);
	
$tgl_ba = strtotime($_POST['tgl_pemeriksaan']);
$tgl = date('d',$tgl_ba);
$hari = getHari(date('N', $tgl_ba));
$bulan = getBulan(date('m', $tgl_ba));
$tglini = tgl_indo(date('Y-m-d'));

$detail = mysql_query("SELECT nama_barang, jml_masuk, harga_masuk, s.keterangan, simbol, s.id_barang as id_bar
						FROM masuk_detail s
						LEFT JOIN ref_barang b ON b.id_barang = s.id_barang
						LEFT JOIN ref_satuan t ON b.id_satuan = t.id_satuan
						WHERE id_masuk = '$id_masuk' AND s.soft_delete = 0");	
$count = mysql_num_rows($detail);
$templateProcessor->cloneRow('No', $count);





foreach (glob("../doc/BA*.*") as $filename) {
    unlink($filename);
}
// Template processor instance creation

// Variables on different parts of document
//print_r($templateProcessor->getVariables());

$templateProcessor->setValue('NomorBA', htmlspecialchars($_POST['no_ba']));
$templateProcessor->setValue('HariBA', htmlspecialchars($hari));
$templateProcessor->setValue('TanggalBA', htmlspecialchars($tgl));
$templateProcessor->setValue('BulanBA', htmlspecialchars($bulan));
$templateProcessor->setValue('Tahun', htmlspecialchars($_POST['ta']));

$templateProcessor->setValue('Nama1', htmlspecialchars($pengurus)); 
$templateProcessor->setValue('NIP1', htmlspecialchars($nipk)); 
$templateProcessor->setValue('Gol1', htmlspecialchars($gol['nama_golongan'])); 

$templateProcessor->setValue('NamaPengurus', htmlspecialchars($pengurus)); 
$templateProcessor->setValue('NIPPengurus', htmlspecialchars($nipk)); 
$templateProcessor->setValue('PangkatPengurus', htmlspecialchars($gol['nama_golongan'])); 

$templateProcessor->setValue('NamaPengguna', htmlspecialchars($tgll)); 

$i=1;
while($d = mysql_fetch_assoc($detail)){
	 
	 
	$jumlah = number_format($d['jml_masuk'], 0, ',', '.');
	$harga_masuk = number_format($d['harga_masuk'], 2, ',', '.');
	$templateProcessor->setValue('No#'.$i, htmlspecialchars($i));
	$templateProcessor->setValue('NamaBarang#'.$i, htmlspecialchars($d['nama_barang']));
	$templateProcessor->setValue('HargaBarang#'.$i, htmlspecialchars($harga_masuk));
	$templateProcessor->setValue('Jumlah#'.$i, htmlspecialchars($jumlah));
	$templateProcessor->setValue('Satuan#'.$i, htmlspecialchars($d['simbol']));
	$templateProcessor->setValue('Ket#'.$i, htmlspecialchars($d['keterangan']));
	$i++;
}


// $templateProcessor->setValue('NomorBA', htmlspecialchars($data['no_so']));
// $templateProcessor->setValue('HariBA', htmlspecialchars($hari));
// $templateProcessor->setValue('TanggalBA', htmlspecialchars($tgl));
// $templateProcessor->setValue('BulanBA', htmlspecialchars($bulan));
// $templateProcessor->setValue('NamaSKPD', htmlspecialchars($skpd['nm_sub2_unit'])); 
// $templateProcessor->setValue('TitlePengurus', htmlspecialchars($txtPengurus)); 
// $templateProcessor->setValue('NamaPengguna', htmlspecialchars($kepala)); 
// $templateProcessor->setValue('NIPPengguna', htmlspecialchars($nipk)); 
// $templateProcessor->setValue('PangkatPengguna', htmlspecialchars($gol['nama_golongan'])); 
// $templateProcessor->setValue('NamaPengurus', htmlspecialchars($pengurus)); 
// $templateProcessor->setValue('NIPPengurus', htmlspecialchars($nipp)); 

// $templateProcessor->setValue('Nama1', htmlspecialchars($pemeriksa['nama1'])); 
// $templateProcessor->setValue('Nama2', htmlspecialchars($pemeriksa['nama2'])); 
// $templateProcessor->setValue('Nama3', htmlspecialchars($pemeriksa['nama3'])); 
// $templateProcessor->setValue('Nama4', htmlspecialchars($pemeriksa['nama4'])); 
// $templateProcessor->setValue('Nama5', htmlspecialchars($pemeriksa['nama5'])); 
// $templateProcessor->setValue('Nama6', htmlspecialchars($pemeriksa['nama6'])); 

// $templateProcessor->setValue('NIP1', htmlspecialchars($pemeriksa['nip1'])); 
// $templateProcessor->setValue('NIP2', htmlspecialchars($pemeriksa['nip2'])); 
// $templateProcessor->setValue('NIP3', htmlspecialchars($pemeriksa['nip3'])); 
// $templateProcessor->setValue('NIP4', htmlspecialchars($pemeriksa['nip4'])); 
// $templateProcessor->setValue('NIP5', htmlspecialchars($pemeriksa['nip5'])); 
// $templateProcessor->setValue('NIP6', htmlspecialchars($pemeriksa['nip6'])); 

// $templateProcessor->setValue('Gol1', htmlspecialchars($gol1['nama_golongan']));  
// $templateProcessor->setValue('Gol2', htmlspecialchars($gol2['nama_golongan']));  
// $templateProcessor->setValue('Gol3', htmlspecialchars($gol3['nama_golongan']));
// $templateProcessor->setValue('Gol4', htmlspecialchars($gol4['nama_golongan']));  
// $templateProcessor->setValue('Gol5', htmlspecialchars($gol5['nama_golongan']));  
// $templateProcessor->setValue('Gol6', htmlspecialchars($gol6['nama_golongan']));  

// Simple table
 
$date = date("YmdHis");
//echo date('H:i:s'), ' Saving the result document...';
$templateProcessor->saveAs('../doc/BA Pemeriksaan '.$date.'.docx');

$response = array( 'success' => true, 'url' => './doc/BA Pemeriksaan '.$date.'.docx' );
header('Content-type: application/json');
echo json_encode($response);
mysql_close();