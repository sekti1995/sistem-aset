<?php
require_once '../../config/phpword/Autoloader.php';
\PhpOffice\PhpWord\Autoloader::register();
require_once '../../config/db.koneksi.php';
require_once '../../config/library.php';

$id = isset($_POST['id']) ? $_POST['id'] : '';

$hapus = mysql_query("SELECT h.ta, no_ba_hapus, tgl_ba_hapus, nm_sub2_unit, no_ba_penunjukan, thn_ba_penunjukan,
				id_pejabat_ketua, id_pejabat_sekretaris, id_pejabat_anggota1, id_pejabat_anggota2,
				jabatan_ketua, jabatan_sekretaris, jabatan_anggota1, jabatan_anggota2
				FROM hapus_barang h
				LEFT JOIN ref_sub2_unit u ON h.uuid_skpd = u.uuid_sub2_unit
				WHERE id_hapus_barang = '$id'");
$detail = mysql_query("SELECT nama_barang, jml_barang, harga_barang, baik, ringan, berat, kadaluarsa, h.keterangan
						FROM hapus_barang_detail h
						LEFT JOIN ref_barang b ON b.id_barang = h.id_barang
						WHERE id_hapus_barang = '$id' AND h.soft_delete = 0");				
$data = mysql_fetch_assoc($hapus);
$count = mysql_num_rows($detail);	
$tgl_ba = strtotime($data['tgl_ba_hapus']);
$tgl = date('d', $tgl_ba);
$hari = getHari(date('N', $tgl_ba));
$bulan = getBulan(date('m', $tgl_ba));
$tglini = tgl_indo($tgl_ba);

// Template processor instance creation
$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('../doc/Template BA Penghapusan.docx');

// Variables on different parts of document
//print_r($templateProcessor->getVariables());

$templateProcessor->setValue('Tahun', htmlspecialchars($data['ta']));
$templateProcessor->setValue('NomorBA', htmlspecialchars($data['no_ba_hapus']));
$templateProcessor->setValue('HariBA', htmlspecialchars($hari));
$templateProcessor->setValue('TanggalBA', htmlspecialchars($tgl));
$templateProcessor->setValue('BulanBA', htmlspecialchars($bulan));
$templateProcessor->setValue('NamaSKPD', htmlspecialchars($data['nm_sub2_unit']));
$templateProcessor->setValue('NomorSK', htmlspecialchars($data['no_ba_penunjukan']));
$templateProcessor->setValue('TahunSK', htmlspecialchars($data['thn_ba_penunjukan']));
$templateProcessor->setValue('Tanggal', htmlspecialchars($tglini));
$templateProcessor->setValue('NamaKetua', htmlspecialchars($data['id_pejabat_ketua']));
$templateProcessor->setValue('NamaSekretaris', htmlspecialchars($data['id_pejabat_sekretaris']));
$templateProcessor->setValue('NamaAnggota1', htmlspecialchars($data['id_pejabat_anggota1']));
$templateProcessor->setValue('NamaAnggota2', htmlspecialchars($data['id_pejabat_anggota2']));
$templateProcessor->setValue('JabDinKetua', htmlspecialchars($data['jabatan_ketua']));
$templateProcessor->setValue('JabDinSekretaris', htmlspecialchars($data['jabatan_sekretaris']));
$templateProcessor->setValue('JabDinAnggota1', htmlspecialchars($data['jabatan_anggota1']));
$templateProcessor->setValue('JabDinAnggota2', htmlspecialchars($data['jabatan_anggota2']));

 // Simple table
$templateProcessor->cloneRow('No', $count);
$i = 1;
while($d = mysql_fetch_assoc($detail)){
	$jumlah = number_format($d['jml_barang'], 0, ',', '.');
	$harga = number_format($d['harga_barang'], 0, ',', '.');
	$total = number_format($d['jml_barang']*$d['harga_barang'], 0, ',', '.');
	$baik = number_format($d['baik'], 0, ',', '.');
	$ringan = number_format($d['ringan'], 0, ',', '.');
	$berat = number_format($d['berat'], 0, ',', '.');
	$kadaluarsa = number_format($d['kadaluarsa'], 0, ',', '.');
	
	$templateProcessor->setValue('No#'.$i, htmlspecialchars($i));
	$templateProcessor->setValue('NamaBarang#'.$i, htmlspecialchars($d['nama_barang']));
	$templateProcessor->setValue('Jumlah#'.$i, htmlspecialchars($jumlah));
	$templateProcessor->setValue('Harga#'.$i, htmlspecialchars($harga));
	$templateProcessor->setValue('Total#'.$i, htmlspecialchars($total));
	$templateProcessor->setValue('Baik#'.$i, htmlspecialchars($baik));
	$templateProcessor->setValue('RusRing#'.$i, htmlspecialchars($ringan));
	$templateProcessor->setValue('RusBer#'.$i, htmlspecialchars($berat));
	$templateProcessor->setValue('Kadaluarsa#'.$i, htmlspecialchars($kadaluarsa));
	$templateProcessor->setValue('Ket#'.$i, htmlspecialchars($d['keterangan']));
	$i++;
}

//echo date('H:i:s'), ' Saving the result document...';
$templateProcessor->saveAs('../doc/BA Penghapusan.docx');

$response = array( 'success' => true, 'url' => './doc/BA Penghapusan.docx' );
header('Content-type: application/json');
echo json_encode($response);
mysql_close();