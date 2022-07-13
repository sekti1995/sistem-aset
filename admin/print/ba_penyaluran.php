<?php
session_start();
require_once '../../config/phpword/Autoloader.php';
\PhpOffice\PhpWord\Autoloader::register();
require_once '../../config/db.koneksi.php';
require_once '../../config/library.php';
// if(!pengguna()){
	// header('Content-type: application/json');
	// echo json_encode(array('success'=>false, 'pesan'=>"Tidak dapat memproses data, Silahkan login ulang !", 'url'=>'../index.php'));
	// mysql_close();
	// exit();
// }

// foreach (glob("../xls/Laporan Penyaluran Barang*.*") as $filename) {
    // unlink($filename);
// }
	error_reporting(E_ALL); ini_set('display_errors', 'off'); 


// $peran = cekLogin();

$id = isset($_POST['id']) ? $_POST['id'] : '';
$pengirim = isset($_POST['pengirim']) ? $_POST['pengirim'] : '';
$penerima = isset($_POST['penerima']) ? $_POST['penerima'] : '';
$id_pengirim = isset($_POST['id_pengirim']) ? $_POST['id_pengirim'] : '';
$id_penerima = isset($_POST['id_penerima']) ? $_POST['id_penerima'] : '';
$tgl_terima = isset($_POST['tgl_terima']) ? $_POST['tgl_terima'] : '';
$id_gudang = isset($_POST['id_gudang']) ? $_POST['id_gudang'] : '';
$nomor = isset($_POST['nomor']) ? $_POST['nomor'] : '';
$ta = isset($_POST['ta']) ? $_POST['ta'] : '';

$tgl_terima = balikTanggal($tgl_terima);
$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('../lampiran/Template BA Penyaluran.docx');

$skpd1 = mysql_fetch_assoc(mysql_query("SELECT nm_sub2_unit, kd_sub, uuid_sub2_unit FROM ref_sub2_unit WHERE uuid_sub2_unit = '$id_pengirim' "));
if($skpd1['kd_sub']==1){
	$in = "(8,10)"; $kep = 8; $txtPengurus = "Pengurus Barang";
}else{
	$in = "(9,11)"; $kep = 9;  $txtPengurus = "Bendahara Pengeluaran Barang";
}
$b1 = " AND d.uuid_skpd = '$id_pengirim'";

	
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


$skpd2 = mysql_fetch_assoc(mysql_query("SELECT nm_sub2_unit, kd_sub, uuid_sub2_unit FROM ref_sub2_unit WHERE uuid_sub2_unit = '$id_penerima' "));
if($skpd2['kd_sub']==1){
	$in = "(8,10)"; $kep = 8; $txtPengurus2= "Pengurus Barang";
}else{
	$in = "(9,11)"; $kep = 9;  $txtPengurus2 = "Bendahara Pengeluaran Barang";
}
$b2 = " AND d.uuid_skpd = '$id_pengirim'";

if($b2!=""){				
	$pejabat = mysql_query("SELECT nama_pejabat, nip, id_jabatan FROM pejabat d WHERE id_jabatan IN $in $b2");
	while($t = mysql_fetch_assoc($pejabat)){
		if($t['id_jabatan']==$kep){ $kepala2 = $t['nama_pejabat']; $nipk2 = $t['nip']; }
		else{ $pengurus2 = $t['nama_pejabat']; $nipp2 = $t['nip']; }
	}				
}else{
	$kepala2 = ".......................";
	$pengurus2 = ".......................";
	$nipk2 = $nipp2 = ".......................";
}



$skpd2 = mysql_fetch_assoc(mysql_query("SELECT nm_sub2_unit, kd_sub, uuid_sub2_unit FROM ref_sub2_unit WHERE uuid_sub2_unit = '$id_penerima' "));

$tgl_ba = strtotime($tgl_terima);
$tgl = date('d',$tgl_ba);
$hari = getHari(date('N', $tgl_ba));
$bulan = getBulan(date('m', $tgl_ba));
$tglini = tgl_indo(date('Y-m-d'));

$templateProcessor->setValue('NomorBA', htmlspecialchars($nomor));
$templateProcessor->setValue('HariBA', htmlspecialchars($hari));
$templateProcessor->setValue('TanggalBA', htmlspecialchars($tgl));
$templateProcessor->setValue('BulanBA', htmlspecialchars($bulan));
$templateProcessor->setValue('Tahun', htmlspecialchars($ta));

$templateProcessor->setValue('Nama1', htmlspecialchars($pengurus)); 
$templateProcessor->setValue('NIP1', htmlspecialchars($nipk)); 
$templateProcessor->setValue('Gol1', htmlspecialchars($gol['nama_golongan'])); 

$templateProcessor->setValue('NamaPengurus', htmlspecialchars($pengurus)); 
$templateProcessor->setValue('NIPPengurus', htmlspecialchars($nipk)); 
$templateProcessor->setValue('PangkatPengurus', htmlspecialchars($gol['nama_golongan'])); 
$templateProcessor->setValue('NamaPengguna', htmlspecialchars($tgl_terima)); 

$g = mysql_fetch_assoc(mysql_query("SELECT * FROM ref_gudang WHERE id_gudang = '$id_gudang' "));
	
$clause = mysql_query("SELECT nama_barang AS nama_bar, k.id_barang AS id_bar, FORMAT(jml_barang, 0,'de_DE') AS jumlah, jml_barang,
			simbol AS nama_sat, b.id_satuan AS id_sat, FORMAT(harga_barang, 0,'de_DE') AS harga, 
			id_keluar_detail AS id, (harga_barang*jml_barang) AS jmlhrg_asli,
			harga_barang AS harga_asli,	FORMAT((harga_barang*jml_barang), 0,'de_DE') AS jmlhrg
			FROM keluar_detail k
			LEFT JOIN ref_barang b ON k.id_barang = b.id_barang 
			LEFT JOIN ref_satuan s ON b.id_satuan = s.id_satuan 
			WHERE id_keluar = '$id' AND k.soft_delete=0");

// $rs = mysql_query("$clause");
$count = mysql_num_rows($clause);
$templateProcessor->cloneRow('No', $count);
$brs = 8; $no = 1; $i = 1; $jtot = 0;
while($row = mysql_fetch_assoc($clause)){
	
	
	$jumlah = number_format($row['jml_barang'], 0, ',', '.');
	$harga_masuk = number_format($row['harga'], 2, ',', '.');
	$templateProcessor->setValue('No#'.$i, htmlspecialchars($no));
	$templateProcessor->setValue('NamaBarang#'.$i, htmlspecialchars($row['nama_bar']));
	$templateProcessor->setValue('HargaBarang#'.$i, htmlspecialchars($harga_masuk));
	$templateProcessor->setValue('Jumlah#'.$i, htmlspecialchars($jumlah));
	$templateProcessor->setValue('Satuan#'.$i, htmlspecialchars($row['nama_sat']));
	$templateProcessor->setValue('Ket#'.$i, "");
	
	$no++;
	$i++;
}




$date = date("YmdHis");



$templateProcessor->saveAs('../doc/BA Penyaluran '.$date.'.docx');

$response = array( 'success' => true, 'url' => './doc/BA Penyaluran '.$date.'.docx' );
header('Content-type: application/json');
echo json_encode($response);
mysql_close();
?>