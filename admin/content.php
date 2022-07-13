<?php
$peran = cekLogin();
if (isset($_GET['module'])){
$module=$_GET['module'];
}else $module ="";
if (isset($_GET['jenis'])){
$jenis=$_GET['jenis'];
}else $jenis ="";
if (isset($_GET['act'])){
$act=$_GET['act'];
}else $act="";
$open = "buka";

$uidunit = isset($_SESSION['uidunit']) ? $_SESSION['uidunit'] : '';
$file = "";
$content = mysql_query("SELECT nama_file FROM ref_akses_menu a, ref_menu m 
			WHERE m.nama_file <> '' AND a.uuid_menu = m.uuid_menu AND MD5(a.id_akses) = '$peran' AND link_menu = '$module'
			UNION ALL 
			SELECT nama_file FROM ref_menu a1, ref_akses_menu2 m1
			WHERE a1.uuid_menu = m1.uuid_menu AND MD5( m1.uuid_skpd ) = '$_SESSION[uidunit]' AND link_menu = '$module'");
$c = mysql_fetch_assoc($content);
$file=$c['nama_file'];

if($file!=""){
	include "modul/$file"; 
}elseif($module=='home'){
	include "modul/mod_blank.php";
}elseif($module=='forum'){
	include "modul/mod_forum.php";
}elseif($module=='keluar_barang_reklas'){
	include "modul/mod_keluar_barang_reklas.php";
}elseif($module=='pengumuman'){
	include "modul/mod_pengumuman.php";
}elseif($module=='posting'){
	include "modul/mod_posting.php";
}elseif($module=='lap_mutasi_jenis_bulan'){
	include "modul/mod_lap_mutasi_jenis_bulan.php";
}elseif($module=='buku_keluar_kegiatan'){
	include "modul/mod_buku_keluar_kegiatan.php";
}elseif($module=='lap_mutasi_trib'){
	include "modul/mod_lap_mutasi_trib.php";
}elseif($module=='normalisasi'){
	include "modul/mod_normalisasi.php";
} elseif($module=='cut_off'){
	include "modul/mod_cut_off.php";
} elseif($module=='cek_penyaluran'){
	include "modul/mod_cek_penyaluran.php";
} elseif($module=='hapus_data'){
	include "modul/mod_hapus_data.php";
} else echo "<p><b>MODUL BELUM ADA</b></p>";

mysql_close();

/* 
// Bagian Admin Home
if ($module=='home'){
include "modul/mod_blank.php";
}

// Bagian Ubah Password
elseif ($module=='ganti_password' && ( $peran==md5('1') || $peran==md5('2') || $peran==md5('3') )){
include "modul/mod_ganti_password.php";
}
// Bagian User
elseif ($module=='kelola_hak_akses' && $peran==md5('1')){
include "modul/mod_kelola_hak_akses.php";
}
// Bagian User
elseif ($module=='user' && $peran==md5('1')){
include "modul/mod_user.php";
}
// Bagian Bidang
elseif ($module=='bidang' && $peran==md5('1')){
include "modul/mod_bidang.php";
}
// Bagian Buat Key Aplikasi
elseif ($module=='key_aplikasi' && $peran==md5('1')){
include "modul/mod_key_aplikasi.php";
}
// Bagian Unit Organisasi
elseif ($module=='unit' && $peran==md5('1')){
include "modul/mod_unit.php";
}
// Bagian Sub Unit Organisasi
elseif ($module=='sub_unit' && $peran==md5('1')){
include "modul/mod_sub_unit.php";
}
// Bagian Sub2 Unit Organisasi
elseif ($module=='sub2_unit' && $peran==md5('1')){
include "modul/mod_sub2_unit.php";
}
// Bagian Gudang
elseif ($module=='gudang' && ($peran==md5('1') || $peran==md5('3'))){
include "modul/mod_gudang.php";
}
// Bagian Tahun Anggaran
elseif ($module=='tahun_anggaran' && $peran==md5('1')){
include "modul/mod_tahun_anggaran.php";
}
// Bagian Satuan Barang
elseif ($module=='satuan_barang' && $peran==md5('1')){
include "modul/mod_satuan_barang.php";
}
// Bagian Jenis Barang
elseif ($module=='jenis_barang' && $peran==md5('1')){
include "modul/mod_jenis_barang.php";
}
// Bagian Sub Jenis Barang
elseif ($module=='sub_jenis_barang' && $peran==md5('1')){
include "modul/mod_sub_jenis_barang.php";
}
// Bagian Kelompok Barang
elseif ($module=='kelompok_barang' && $peran==md5('1')){
include "modul/mod_kelompok_barang.php";
}
// Bagian Barang
elseif ($module=='barang' && ($peran==md5('1') || $peran==md5('3'))){
include "modul/mod_barang.php";
}
// Bagian Golongan Pejabat
elseif ($module=='pejabat' && ($peran==md5('1') || $peran==md5('3'))){
include "modul/mod_pejabat.php";
}
// Bagian Golongan Pejabat
elseif ($module=='golongan_pejabat' && $peran==md5('1')){
include "modul/mod_golongan_pejabat.php";
}
// Bagian Jabatan Pejabat
elseif ($module=='jabatan_pejabat' && $peran==md5('1')){
include "modul/mod_jabatan_pejabat.php";
}
// Bagian Pengadaan
elseif ($module=='pengadaan' && ($peran==md5('1') || $peran==md5('3'))){
include "modul/mod_pengadaan.php";
}
// Bagian Pemeriksaan
elseif ($module=='pemeriksaan' && ($peran==md5('1') || $peran==md5('3'))){
include "modul/mod_pemeriksaan.php";
}
// Bagian Penerimaan
elseif ($module=='penerimaan' && ($peran==md5('1') || $peran==md5('3'))){
include "modul/mod_penerimaan.php";
}
// Bagian Daftar Pengadaan
elseif ($module=='daftar_pengadaan' && ($peran==md5('1') || $peran==md5('3'))){
include "modul/mod_daftar_pengadaan.php";
}
// Bagian Buku Penerimaan
elseif ($module=='buku_penerimaan' && ($peran==md5('1') || $peran==md5('3'))){
include "modul/mod_buku_penerimaan.php";
}
// Bagian Kartu Barang
elseif ($module=='kartu_barang' && ($peran==md5('1') || $peran==md5('3'))){
include "modul/mod_kartu_barang.php";
}
// Bagian Buku Barang
elseif ($module=='buku_barang' && ($peran==md5('1') || $peran==md5('3'))){
include "modul/mod_buku_barang.php";
}
// Bagian Perintah Keluar
elseif ($module=='perintah_keluar' && ($peran==md5('1') || $peran==md5('3'))){
include "modul/mod_perintah_keluar.php";
}
// Bagian Keluar Barang
elseif ($module=='keluar_barang' && ($peran==md5('1') || $peran==md5('3'))){
include "modul/mod_keluar_barang.php";
}
// Bagian Mutasi Gudang
elseif ($module=='mutasi_gudang' && ($peran==md5('1') || $peran==md5('3'))){
include "modul/mod_mutasi_gudang.php";
}
// Bagian Lap Mutasi Gudang
elseif ($module=='lap_mutasi_gudang' && ($peran==md5('1') || $peran==md5('3'))){
include "modul/mod_lap_mutasi_gudang.php";
}
// Bagian Buku Keluar
elseif ($module=='buku_keluar' && ($peran==md5('1') || $peran==md5('3'))){
include "modul/mod_buku_keluar.php";
}
// Bagian Stok Opname
elseif ($module=='stok_opname' && ($peran==md5('1') || $peran==md5('3'))){
include "modul/mod_stok_opname.php";
}
// Bagian Dafta Hitung Fisik
elseif ($module=='daftar_hitung_fisik' && ($peran==md5('1') || $peran==md5('3'))){
include "modul/mod_daftar_hitung_fisik.php";
}
// Usul Penghapusan Barang
elseif ($module=='usul_hapus' && ($peran==md5('1') || $peran==md5('3'))){
include "modul/mod_usul_hapus.php";
}
// Penghapusan Barang
elseif ($module=='hapus_barang' && ($peran==md5('1') || $peran==md5('3'))){
include "modul/mod_hapus_barang.php";
}
// Bagian Kartu Persediaan
elseif ($module=='kartu_persediaan' && ($peran==md5('1') || $peran==md5('3'))){
include "modul/mod_kartu_persediaan.php";
}
// Bagian Lap Mutasi Bulan
elseif ($module=='lap_mutasi_bulan' && ($peran==md5('1') || $peran==md5('3'))){
include "modul/mod_lap_mutasi_bulan.php";
}
// Bagian Lap Mutasi Semester
elseif ($module=='lap_mutasi_smstr' && ($peran==md5('1') || $peran==md5('3'))){
include "modul/mod_lap_mutasi_smstr.php";
}
// Bagian Lap Mutasi Tahunan
elseif ($module=='lap_mutasi_tahun' && ($peran==md5('1') || $peran==md5('3'))){
include "modul/mod_lap_mutasi_tahun.php";
}
// Bagian Penilaian persediaan
elseif ($module=='nilai_persediaan' && ($peran==md5('1') || $peran==md5('3'))){
include "modul/mod_nilai_persediaan.php";
}
// Bagian Monitoring Pengguna
elseif ($module=='monitoring_pengguna' && $peran==md5('1')){
include "modul/mod_monitoring_pengguna.php";
}
// Bagian Catatan Pengguna
elseif ($module=='catatan_pengguna' && $peran==md5('1')){
include "modul/mod_catatan_pengguna.php";
}
// Apabila modul tidak ditemukan
else{
  echo "<p><b>MODUL BELUM ADA</b></p>";
} */
?> 
