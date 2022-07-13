<?php
date_default_timezone_set('Asia/Jakarta');
include_once "../config/db.koneksi.php";
include_once "../config/db.function.php";
include_once "../config/library.php";
error_reporting(E_ALL);
ini_set('display_errors', 'off');
session_start();

$nama_pengadaan = "";
if (isset($_REQUEST['module'])) $module = $_REQUEST['module'];
else $module = "";
if (isset($_REQUEST['oper'])) $oper = $_REQUEST['oper'];
else $oper = "";
$dat = date('Y-m-d');
$time = date("H:i:s");
$datime = date('Y-m-d H:i:s');
$pengguna = pengguna();



if ($pengguna != '') {
	catatKegiatan($datime, $module, '', $oper);
	if ($module == 'ganti_password') {
		$pass_lama = salt($_POST['pass_lama']);
		$pass_baru = salt($_POST['pass_baru']);
		$pass_baru2 = salt($_POST['pass_baru2']);
		$query = "SELECT * FROM ref_pengelola WHERE md5(id_pengelola)='$_SESSION[idpengguna]' AND state = 0";
		$login = mysql_query($query);
		$r = mysql_fetch_array($login);

		if ($pass_lama == $r['password']) {
			$text = "UPDATE ref_pengelola SET password='$pass_baru' WHERE md5(id_pengelola)='$_SESSION[idpengguna]'";
			$q = mysql_query($text);
			if ($q) {
				echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil disimpan !"));
			} else {
				echo json_encode(array('success' => true, 'pesan' => "Gagal merubah password !"));
			}
		} else {
			echo json_encode(array('success' => false, 'pesan' => "Password lama salah!"));
		}
	} elseif ($module == 'posting') {
		if (isset($_REQUEST['uuid_skpd'])) $uuid_skpd = $_REQUEST['uuid_skpd'];
		if (isset($_REQUEST['tgl_awal'])) $tgl_awal = $_REQUEST['tgl_awal'];
		if (isset($_REQUEST['tgl_akhir'])) $tgl_akhir = $_REQUEST['tgl_akhir'];
		$tgl_awal = balikTanggal($tgl_awal);
		$tgl_akhir = balikTanggal($tgl_akhir);

		switch ($oper) {
			case 'add':
				$delks = mysql_query("DELETE FROM kartu_stok WHERE uuid_skpd = '$uuid_skpd' AND kode = 'ok' AND (tgl_transaksi BETWEEN '$tgl_awal' AND '$tgl_akhir')");
				$array_bar2 = "";
				$p0 = mysql_query("SELECT * FROM surat_minta WHERE  soft_delete = '0' AND unit_peminta = '$uuid_skpd' AND (tgl_spb BETWEEN '$tgl_awal' AND '$tgl_akhir') ORDER BY tgl_spb ASC");
				$jmlll = mysql_num_rows($p0);
				while ($r0 = mysql_fetch_assoc($p0)) {
					//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
					//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
					//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
					//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
					//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
					//$sp = mysql_fetch_assoc(mysql_query("SELECT * FROM sp_out WHERE id_surat_minta = '$r0[id_surat_minta]' "));
					//mysql_query("UPDATE sp_out SET no_sp_out = '$no_surat', tgl_sp_out = '$tgl_surat', status = '1'
					//	WHERE id_sp_out = '$id_sp'");
					//PERINTAH KELUAR
					//PERINTAH KELUAR
					//PERINTAH KELUAR
					//PERINTAH KELUAR

					$array_bar = "";
					$array_jml = "";
					$p1 = mysql_query("SELECT * FROM surat_minta_detail where soft_delete = '0' AND id_surat_minta = '$r0[id_surat_minta]' ORDER BY create_date ASC");
					while ($r1 = mysql_fetch_assoc($p1)) {
						$jumlah = number_format($r1["jumlah"], 0, ',', '.');
						$jumlah = str_replace(".", "", $jumlah);
						$array_bar .= "$r1[id_barang],";
						$array_jml .= "$jumlah,";
					}
					$array_jml_sm = $array_jml;
					if ($array_bar != "") {

						$r2 = mysql_fetch_assoc(mysql_query("SELECT * FROM sp_out WHERE soft_delete = '0' AND id_surat_minta = '$r0[id_surat_minta]'"));
						$rk = mysql_fetch_assoc(mysql_query("SELECT * FROM keluar WHERE soft_delete = '0' AND id_sp_out = '$r2[id_sp_out]'"));
						$delsp = mysql_query("DELETE FROM sp_out_detail WHERE id_sp_out = '$r2[id_sp_out]'");
						$r3 = mysql_query("CALL ambil_harga_insert_sp_out_detail('$array_bar', '$array_jml', '$r2[tgl_sp_out]', '$uuid_skpd', '$r2[id_sp_out]', '$r2[id_sp_out]', '$r2[ta]', '$datime', '$pengguna')");


						//echo "CALL ambil_harga_insert_sp_out_detail('$array_bar', '$array_jml', '$r2[tgl_sp_out]', '$uuid_skpd', '$r2[id_sp_out]', '$r2[id_sp_out]', '$r2[ta]', '$datime', '$pengguna')";

					}
					mysql_query("UPDATE surat_minta SET status=1 WHERE id_surat_minta = '$r0[id_surat_minta]'");

					///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

					//KELUAR BARANG
					//KELUAR BARANG
					//KELUAR BARANG
					//KELUAR BARANG


					$u = mysql_fetch_row(mysql_query("SELECT UUID()"));
					$uuid = $u[0];
					$array_bar = "";
					$array_jml = "";
					$array_tgl = "";
					$array_gud = "";
					$array_sum = "";

					$brg = mysql_query("SELECT * FROM keluar_detail sod LEFT JOIN keluar sp ON sod.id_keluar = sp.id_keluar WHERE sp.soft_delete = '0' AND sod.soft_delete = '0' AND sod.id_keluar = '$rk[id_keluar]' AND sp.uuid_skpd = '$uuid_skpd' AND (sp.tgl_ba_out BETWEEN '$tgl_awal' AND '$tgl_akhir')");
					//$jml_brg = mysql_num_rows($brg);

					while ($val = mysql_fetch_assoc($brg)) {

						//$gud = mysql_fetch_assoc(mysql_query("SELECT * FROM keluar"));

						$jumlah = number_format($val["jml_barang"], 0, ',', '.');
						$jumlah = str_replace(".", "", $jumlah);
						$tgl_terima = balikTanggal($val['tgl_terima']);
						$tgl_minta = balikTanggal($val['tgl_minta']);
						if ($val["jenis_out"] == 'r') $tgl_minta = $tgl_terima;
						$kode = "o" . $val["jenis_out"];

						$array_bar .= "$val[id_barang],";
						$array_jml .= "$jumlah,";
						$array_tgl .= "$tgl_terima,";
						$array_gud .= "$val[id_gudang],";
						$array_sum .= "$val[id_sumber_dana],";
					}

					$array_bar2 .= $array_gud . ' :: ';
					$del2 = mysql_query("DELETE FROM keluar_detail WHERE id_keluar = '$rk[id_keluar]'");

					if ($array_bar != "") {
						mysql_query("CALL ambil_harga_insert_keluar_detail('$array_bar', '$array_jml', '$array_tgl', 
											'$array_gud', '$array_sum', '$uuid_skpd', '$rk[id_keluar]', '$rk[ta]', '$datime', 
											'$pengguna', '$tgl_minta', '$kode')");
					}
					if (mysql_errno() != 0) echo mysql_error();
					//if($dasar_keluar!=""){
					mysql_query("UPDATE sp_out o LEFT JOIN surat_minta m ON o.id_surat_minta = m.id_surat_minta
									SET o.status = 3, m.status = 3 WHERE id_sp_out = '$rk[id_sp_out]'");

					//mysql_query("INSERT INTO log_fifo VALUES (UUID(),'$uuid_skpd',NOW(),'$tgl_awal','$tgl_akhir','$pengguna')");
					//}

					///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				}


				if (mysql_errno() == 0) echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil ditambahkan !"));
				else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menambahkan data ! ", 'kode' => mysql_errno()));
				break;
		}
	} elseif ($module == 'posting_barang') {
		if (isset($_REQUEST['uuid_skpd'])) $uuid_skpd = $_REQUEST['uuid_skpd'];
		if (isset($_REQUEST['id_barang'])) $id_barang = $_REQUEST['id_barang'];
		if (isset($_REQUEST['tgl_awal'])) $tgl_awal = $_REQUEST['tgl_awal'];
		if (isset($_REQUEST['tgl_akhir'])) $tgl_akhir = $_REQUEST['tgl_akhir'];
		$tgl_awal = balikTanggal($tgl_awal);
		$tgl_akhir = balikTanggal($tgl_akhir);

		switch ($oper) {
			case 'add':
				$delks = mysql_query("DELETE FROM kartu_stok WHERE uuid_skpd = '$uuid_skpd' AND kode = 'ok' AND (tgl_transaksi BETWEEN '$tgl_awal' AND '$tgl_akhir') AND id_barang = '$id_barang' ");
				$array_bar2 = "";
				$p0 = mysql_query("SELECT * FROM 
								surat_minta_detail t1 LEFT JOIN surat_minta t2 ON t1.id_surat_minta = t2.id_surat_minta
							   WHERE  
							    t1.soft_delete = '0' AND t2.unit_peminta = '$uuid_skpd' AND (t2.tgl_spb BETWEEN '$tgl_awal' AND '$tgl_akhir') ORDER BY t2.tgl_spb ASC");
				$jmlll = mysql_num_rows($p0);
				while ($r0 = mysql_fetch_assoc($p0)) {
					//$sp = mysql_fetch_assoc(mysql_query("SELECT * FROM sp_out WHERE id_surat_minta = '$r0[id_surat_minta]' "));
					//mysql_query("UPDATE sp_out SET no_sp_out = '$no_surat', tgl_sp_out = '$tgl_surat', status = '1'
					//	WHERE id_sp_out = '$id_sp'");
					//PERINTAH KELUAR
					//PERINTAH KELUAR
					//PERINTAH KELUAR
					//PERINTAH KELUAR

					$array_bar = "";
					$array_jml = "";
					$p1 = mysql_query("SELECT * FROM surat_minta_detail where soft_delete = '0' AND id_surat_minta = '$r0[id_surat_minta]' AND id_barang = '$id_barang' ORDER BY create_date ASC");
					while ($r1 = mysql_fetch_assoc($p1)) {
						$jumlah = number_format($r1["jumlah"], 0, ',', '.');
						$jumlah = str_replace(".", "", $jumlah);
						$array_bar .= "$r1[id_barang],";
						$array_jml .= "$jumlah,";
					}
					$array_jml_sm = $array_jml;
					if ($array_bar != "") {

						$r2 = mysql_fetch_assoc(mysql_query("SELECT * FROM sp_out WHERE soft_delete = '0' AND id_surat_minta = '$r0[id_surat_minta]'"));
						$rk = mysql_fetch_assoc(mysql_query("SELECT * FROM keluar WHERE soft_delete = '0' AND id_sp_out = '$r2[id_sp_out]'"));
						$delsp = mysql_query("DELETE FROM sp_out_detail WHERE id_sp_out = '$r2[id_sp_out]' AND id_barang = '$id_barang'");
						$r3 = mysql_query("CALL ambil_harga_insert_sp_out_detail('$array_bar', '$array_jml', '$r2[tgl_sp_out]', '$uuid_skpd', '$r2[id_sp_out]', '$r2[id_sp_out]', '$r2[ta]', '$datime', '$pengguna')");


						//echo "CALL ambil_harga_insert_sp_out_detail('$array_bar', '$array_jml', '$r2[tgl_sp_out]', '$uuid_skpd', '$r2[id_sp_out]', '$r2[id_sp_out]', '$r2[ta]', '$datime', '$pengguna')";

					}
					mysql_query("UPDATE surat_minta SET status=1 WHERE id_surat_minta = '$r0[id_surat_minta]'");

					///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

					//KELUAR BARANG
					//KELUAR BARANG
					//KELUAR BARANG
					//KELUAR BARANG


					$u = mysql_fetch_row(mysql_query("SELECT UUID()"));
					$uuid = $u[0];
					$array_bar = "";
					$array_jml = "";
					$array_tgl = "";
					$array_gud = "";
					$array_sum = "";

					$brg = mysql_query("SELECT * FROM keluar_detail sod LEFT JOIN keluar sp ON sod.id_keluar = sp.id_keluar WHERE sp.soft_delete = '0' AND sod.soft_delete = '0' AND sod.id_keluar = '$rk[id_keluar]' AND sod.id_barang = '$id_barang' AND sp.uuid_skpd = '$uuid_skpd' AND (sp.tgl_ba_out BETWEEN '$tgl_awal' AND '$tgl_akhir')");
					//$jml_brg = mysql_num_rows($brg);

					while ($val = mysql_fetch_assoc($brg)) {

						//$gud = mysql_fetch_assoc(mysql_query("SELECT * FROM keluar"));

						$jumlah = number_format($val["jml_barang"], 0, ',', '.');
						$jumlah = str_replace(".", "", $jumlah);
						$tgl_terima = balikTanggal($val['tgl_terima']);
						$tgl_minta = balikTanggal($val['tgl_minta']);
						if ($val["jenis_out"] == 'r') $tgl_minta = $tgl_terima;
						$kode = "o" . $val["jenis_out"];

						$array_bar .= "$val[id_barang],";
						$array_jml .= "$jumlah,";
						$array_tgl .= "$tgl_terima,";
						$array_gud .= "$val[id_gudang],";
						$array_sum .= "$val[id_sumber_dana],";
					}

					$array_bar2 .= $array_gud . ' :: ';
					$del2 = mysql_query("DELETE FROM keluar_detail WHERE id_keluar = '$rk[id_keluar]' AND id_barang = '$id_barang'");

					if ($array_bar != "") {
						mysql_query("CALL ambil_harga_insert_keluar_detail('$array_bar', '$array_jml', '$array_tgl', 
											'$array_gud', '$array_sum', '$uuid_skpd', '$rk[id_keluar]', '$rk[ta]', '$datime', 
											'$pengguna', '$tgl_minta', '$kode')");
					}
					if (mysql_errno() != 0) echo mysql_error();
					//if($dasar_keluar!=""){
					mysql_query("UPDATE sp_out o LEFT JOIN surat_minta m ON o.id_surat_minta = m.id_surat_minta
									SET o.status = 3, m.status = 3 WHERE id_sp_out = '$rk[id_sp_out]'");

					//mysql_query("INSERT INTO log_fifo VALUES (UUID(),'$uuid_skpd',NOW(),'$tgl_awal','$tgl_akhir','$pengguna')");
					//}

					///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				}


				if (mysql_errno() == 0) echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil ditambahkan !"));
				else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menambahkan data ! ", 'kode' => mysql_errno()));
				break;
		}
	} elseif ($module == 'key_aplikasi') {
		if (isset($_REQUEST['basket'])) $basket = $_REQUEST['basket'];

		switch ($oper) {
			case 'add':
				$jadi = "";
				$key = $basket['id_pengelola'] . $basket['id_sub2_unit'] . $basket['username'];
				$keyGen = substr(cekStokNya($key), 0, -1);

				$q = mysql_query("UPDATE ref_pengelola SET serial_key = '$keyGen' WHERE id_pengelola = '$basket[id_pengelola]'");

				if ($q) {
					echo json_encode(array('success' => true, 'pesan' => "Key telah berhasil dibuat !"));
				} else {
					echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil membuat key !"));
				}
				break;
		}
	} elseif ($module == 'down_key') {
		if (isset($_REQUEST['id'])) $id = $_REQUEST['id'];

		//header("Content-type: text/plain");
		header("Content-Disposition: attachment; filename=Aktivasi.key");

		$q = mysql_fetch_assoc(mysql_query("SELECT serial_key FROM ref_pengelola WHERE id_pengelola = '$id'"));
		$content = $q['serial_key'];
		/* $content = "@echo off
mkdir \"c:\SIMDA_Trans\key\"
echo $q[serial_key]>\"c:\SIMDA_Trans\key\Aktivasi.key\"
@pause"; */
		print $content;


		// AWAL AKSI UNTUK MASTER DATA //
	} elseif ($module == 'hapus_import') {
		if (isset($_REQUEST['uuid_skpd'])) $uuid_skpd = $_REQUEST['uuid_skpd'];
		if (isset($_REQUEST['timestamp'])) $timestamp = $_REQUEST['timestamp'];
		$timestamp = substr($timestamp, 0, 10);
		switch ($oper) {
			case 'del':
				//$q1 = mysql_query("DELETE FROM masuk WHERE uuid_skpd = '$uuid_skpd' AND LEFT(create_date,10) = '$timestamp' ");
				//$q2 = mysql_query("DELETE FROM masuk_detail WHERE uuid_skpd = '$uuid_skpd' AND LEFT(create_date,10) = '$timestamp' ");

				$q3 = mysql_query("DELETE FROM nota_minta WHERE unit_peminta = '$uuid_skpd' AND LEFT(create_date,10) = '$timestamp' ");
				$q4 = mysql_query("DELETE FROM nota_minta_detail WHERE uuid_skpd = '$uuid_skpd' AND LEFT(create_date,10) = '$timestamp' ");

				$q5 = mysql_query("DELETE FROM surat_minta WHERE unit_peminta = '$uuid_skpd' AND LEFT(create_date,10) = '$timestamp' ");
				$q6 = mysql_query("DELETE FROM surat_minta_detail WHERE uuid_skpd = '$uuid_skpd' AND LEFT(create_date,10) = '$timestamp' ");

				$q7 = mysql_query("DELETE FROM sp_out WHERE uuid_skpd = '$uuid_skpd' AND LEFT(create_date,10) = '$timestamp' ");
				$q8 = mysql_query("DELETE FROM sp_out_detail WHERE uuid_skpd = '$uuid_skpd' AND LEFT(create_date,10) = '$timestamp' ");

				$q9 = mysql_query("DELETE FROM keluar WHERE uuid_skpd = '$uuid_skpd' AND LEFT(create_date,10) = '$timestamp' ");
				$q10 = mysql_query("DELETE FROM keluar_detail WHERE uuid_skpd = '$uuid_skpd' AND LEFT(create_date,10) = '$timestamp' ");

				$q11 = mysql_query("DELETE FROM kartu_stok WHERE uuid_skpd = '$uuid_skpd' AND LEFT(create_date,10) = '$timestamp' ");

				$q12 = mysql_query("DELETE FROM log_import WHERE uuid_skpd = '$uuid_skpd' AND LEFT(timestamp,10) = '$timestamp' ");

				if ($q1 && $q2 && $q3 && $q4 && $q5 && $q6 && $q7 && $q8 && $q9 && $q10 && $q11 && $q12) {
					echo json_encode(array('success' => true, 'pesan' => "Data Berhasil Dihapus !"));
				} else {
					echo json_encode(array('success' => false, 'pesan' => "Tidak Berhasil Menghapus Data !"));
				}
				break;
		}
	}
	/* elseif ($module=='lock'){
   if(isset($_REQUEST['kd_skpd']))$kd_skpd = $_REQUEST['kd_skpd'];
   if(isset($_REQUEST['tgl_awal']))$tgl_awal = $_REQUEST['tgl_awal'];
   if(isset($_REQUEST['tgl_akhir']))$tgl_akhir = $_REQUEST['tgl_akhir'];
   	$tgl_awal = balikTanggal($tgl_awal);
   	$tgl_akhir = balikTanggal($tgl_akhir);
	
	switch ($oper) {
        case 'add':
		    $q=mysql_query("UPDATE kunci_entri SET tgl_mulai = '$tgl_awal', tgl_sampai = '$tgl_akhir' WHERE id_kunci = '1' ");
			$q2=mysql_query("DELETE FROM kunci_entri_skpd");
			if($q2){
				foreach($kd_skpd as $skpd){
					$q3=mysql_query("INSERT INTO kunci_entri_skpd VALUES (UUID(),'$skpd[kd_skpd]')");
				}
			}
			
			
			if($q) {
				echo json_encode(array('success'=>true, 'pesan'=>"Entri Data Telah Dikunci !"));
			}else {
				echo json_encode(array('success'=>false, 'pesan'=>"Tidak berhasil mengunci Entri Data !"));
			}
            break;
	}
	
} */ elseif ($module == 'lock') {
		if (isset($_REQUEST['kd_skpd'])) $kd_skpd = $_REQUEST['kd_skpd'];
		if (isset($_REQUEST['tgl_awal'])) $tgl_awal = $_REQUEST['tgl_awal'];
		if (isset($_REQUEST['tgl_akhir'])) $tgl_akhir = $_REQUEST['tgl_akhir'];
		$tgl_awal = balikTanggal($tgl_awal);
		$tgl_akhir = balikTanggal($tgl_akhir);

		switch ($oper) {
			case 'add':
				$q = mysql_query("UPDATE kunci_entri SET tgl_mulai = '$tgl_awal', tgl_sampai = '$tgl_akhir' WHERE id_kunci = '1' ");
				$q2 = mysql_query("DELETE FROM kunci_entri_skpd ");
				if ($q) {
					foreach ($kd_skpd as $skpd) {
						$q3 = mysql_query("INSERT INTO kunci_entri_skpd VALUES (UUID(),'$skpd[kd_skpd]', '$tgl_awal', '$tgl_akhir', NOW())");
					}
				}


				if ($q) {
					echo json_encode(array('success' => true, 'pesan' => "Entri Data Telah Dikunci !"));
				} else {
					echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil mengunci Entri Data !"));
				}
				break;
		}
	} elseif ($module == 'unit') {
		if (isset($_REQUEST['id_bidang'])) $id_bidang = $_REQUEST['id_bidang'];
		if (isset($_REQUEST['kd_unit'])) $kd_unit = $_REQUEST['kd_unit'];
		if (isset($_REQUEST['nm_unit'])) $nm_unit = $_REQUEST['nm_unit'];

		$ei = explode('.', $id_bidang);
		$kd_urusan = $ei[0];
		$kd_bidang = $ei[1];

		switch ($oper) {
			case 'add':
				$q = mysql_query("INSERT INTO ref_unit (kd_urusan, kd_bidang, kd_unit, nm_unit) 
							VALUES('$kd_urusan', '$kd_bidang', '$kd_unit','$nm_unit')");
				if ($q) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil dimasukkan !"));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Kombinasi Kode Unit Sudah Ada !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil memasukkan data !"));
				}
				break;
			case 'edit':
				$text = "UPDATE ref_unit SET kd_urusan = '$kd_urusan',
										kd_bidang='$kd_bidang',
										kd_unit='$kd_unit',
										nm_unit='$nm_unit'
										WHERE CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit) = '$_GET[id_ubah]'";
				$q = mysql_query($text);
				if ($q) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil diubah !"));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Kombinasi Kode Unit Sudah Ada !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil mengubah data !"));
				}
				break;
			case 'del':
				$q = mysql_query("DELETE FROM ref_unit WHERE CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit) = '$_POST[id_hapus]'");
				if ($q) {
					echo "Data berhasil dihapus !";
				} else {
					echo "Data tidak berhasil dihapus !";
				}
				break;
		}
	} elseif ($module == 'sub_unit') {
		if (isset($_REQUEST['id_unit'])) $id_unit = $_REQUEST['id_unit'];
		if (isset($_REQUEST['kd_sub'])) $kd_sub = $_REQUEST['kd_sub'];
		if (isset($_REQUEST['nm_sub_unit'])) $nm_sub_unit = $_REQUEST['nm_sub_unit'];
		if (isset($id_unit)) $kd = explode('.', $id_unit);

		switch ($oper) {
			case 'add':
				$q = mysql_query("INSERT INTO ref_sub_unit (kd_urusan, kd_bidang, kd_unit, kd_sub, nm_sub_unit) 
							VALUES('$kd[0]', '$kd[1]', '$kd[2]', '$kd_sub', '$nm_sub_unit')");
				if ($q) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil dimasukkan !"));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Kode Sub Unit Sudah Ada di Unit ini !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil memasukkan data !"));
				}
				break;
			case 'edit':
				$text = "UPDATE ref_sub_unit SET kd_urusan = '$kd[0]',
											kd_bidang='$kd[1]',
											kd_unit='$kd[2]',
											kd_sub='$kd_sub',
											nm_sub_unit='$nm_sub_unit'
											WHERE CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit, kd_sub) = '$_GET[id_ubah]'";
				$q = mysql_query($text);
				if ($q) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil diubah !"));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Kode Sub Unit Sudah Ada di Unit ini !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil mengubah data !"));
				}
				break;
			case 'del':
				$q = mysql_query("DELETE FROM ref_sub_unit WHERE CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit, kd_sub) = '$_POST[id_hapus]'");
				if ($q) {
					echo "Data berhasil dihapus !";
				} else {
					echo "Data tidak berhasil dihapus !";
				}
				break;
		}
	} elseif ($module == 'sub2_unit') {
		if (isset($_REQUEST['id_sub_unit'])) $id_sub_unit = $_REQUEST['id_sub_unit'];
		if (isset($_REQUEST['kd_sub2'])) $kd_sub2 = $_REQUEST['kd_sub2'];
		if (isset($_REQUEST['nm_sub2_unit'])) $nm_sub2_unit = $_REQUEST['nm_sub2_unit'];
		if (isset($id_sub_unit)) $kd = explode('.', $id_sub_unit);

		switch ($oper) {
			case 'add':
				$q = mysql_query("INSERT INTO ref_sub2_unit (uuid_sub2_unit, kd_urusan, kd_bidang, kd_unit, kd_sub, kd_sub2, nm_sub2_unit) 
							VALUES(UUID(), '$kd[0]', '$kd[1]', '$kd[2]', '$kd[3]', '$kd_sub2', '$nm_sub2_unit')");
				if ($q) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil dimasukkan !"));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Kode Sub Unit Sudah Ada di Unit ini !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil memasukkan data !"));
				}
				break;
			case 'edit':
				$text = "UPDATE ref_sub2_unit SET kd_urusan = '$kd[0]',
											kd_bidang='$kd[1]',
											kd_unit='$kd[2]',
											kd_sub='$kd[3]',
											kd_sub2='$kd_sub2',
											nm_sub2_unit='$nm_sub2_unit'
											WHERE CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit, kd_sub, kd_sub2) = '$_GET[id_ubah]'";
				$q = mysql_query($text);
				if ($q) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil diubah !"));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Kode Sub Unit Sudah Ada di Unit ini !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil mengubah data !"));
				}
				break;
			case 'del':
				$q = mysql_query("DELETE FROM ref_sub2_unit WHERE CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit, kd_sub, kd_sub2) = '$_POST[id_hapus]'");
				if ($q) {
					echo "Data berhasil dihapus !";
				} else {
					echo "Data tidak berhasil dihapus !";
				}
				break;
		}
	} elseif ($module == 'tahun_anggaran') {
		if (isset($_REQUEST['tahun'])) $nama = $_REQUEST['tahun'];

		switch ($oper) {
			case 'add':
				$q = mysql_query("INSERT INTO ref_tahun (tahun) VALUES('$nama')");
				if ($q) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil dimasukkan !"));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Tahun Sudah Ada !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil memasukkan data !"));
				}
				break;
			case 'edit':
				$text = "UPDATE ref_tahun SET tahun = '$nama' WHERE tahun = '$_GET[id_ubah]'";
				$q = mysql_query($text);
				if ($q) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil diubah !"));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Tahun Sudah Ada !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil mengubah data !"));
				}
				break;
			case 'del':
				$q = mysql_query("DELETE FROM ref_tahun WHERE tahun = '$_POST[id_hapus]'");
				if ($q) {
					echo "Data berhasil dihapus !";
				} else {
					echo "Data tidak berhasil dihapus !";
				}
				break;
		}
	} elseif ($module == 'gudang') {
		if (isset($_REQUEST['id_sub2_unit'])) $uid_skpd = $_REQUEST['id_sub2_unit'];
		if (isset($_REQUEST['nama_gudang'])) $nama_gudang = $_REQUEST['nama_gudang'];
		if (isset($_REQUEST['lokasi'])) $lokasi = $_REQUEST['lokasi'];
		if (isset($id_sub2_unit)) $kd = explode('.', $id_sub2_unit);

		switch ($oper) {
			case 'add':
				$q = mysql_query("INSERT INTO ref_gudang (id_gudang, nama_gudang, lokasi, uuid_skpd) 
							VALUES(UUID(),'$nama_gudang','$lokasi','$uid_skpd')");
				if ($q) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil dimasukkan !"));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Kode Sub Unit Sudah Ada di Unit ini !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil memasukkan data !"));
				}
				break;
			case 'edit':
				$cek = mysql_query("SELECT * FROM kartu_stok WHERE id_gudang = '$_GET[id_ubah]'");
				if ($cek) {
					echo json_encode(array('success' => false, 'pesan' => "Tidak bisa mengubah data yang sudah ada transaksinya.!"));
				} else {

					$text = "UPDATE ref_gudang SET nama_gudang = '$nama_gudang',
												lokasi = '$lokasi',
												uuid_skpd = '$uid_skpd'
												WHERE id_gudang = '$_GET[id_ubah]'";
					$q = mysql_query($text);
					if ($q) {
						echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil diubah !"));
					} else {
						if (mysql_errno() == 1062) {
							echo json_encode(array(
								'success' => false,
								'pesan' => "Kode Sub Unit Sudah Ada di Unit ini !",
								'error' => "nomor_sama"
							));
						} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil mengubah data !"));
					}
				}
				break;
			case 'del':
				$cek_data = mysql_query("SELECT * FROM kartu_stok WHERE id_gudang = '$_POST[id_hapus]'");
				if ($cek_data) {
					echo "Tidak bisa menghapus data yang sudah ada transaksinya.!";
				} else {

					$cekStok = mysql_query("SELECT SUM(jml_in-jml_out) AS saldo FROM kartu_stok WHERE id_gudang = '$_POST[id_hapus]'");
					if ($cekStok['saldo'] > 0) {
						echo "Tidak bisa menghapus data, masih ada barang di dalam gudang !";
					} else {
						$q = mysql_query("DELETE FROM ref_gudang WHERE id_gudang = '$_POST[id_hapus]'");
						if ($q) {
							echo "Data berhasil dihapus !";
						} else {
							echo "Data tidak berhasil dihapus !";
						}
					}
				}
				break;
		}
	} elseif ($module == 'barang') {
		if (isset($_REQUEST['id_jenis'])) $id_jenis = $_REQUEST['id_jenis'];
		if (isset($_REQUEST['kd_sub2'])) $kd_sub2 = $_REQUEST['kd_sub2'];
		if (isset($_REQUEST['ta'])) $ta = $_REQUEST['ta'];
		if (isset($_REQUEST['nama_barang'])) $nama_barang = $_REQUEST['nama_barang'];
		if (isset($_REQUEST['id_satuan'])) $id_satuan = $_REQUEST['id_satuan'];
		if (isset($_REQUEST['keterangan'])) $keterangan = $_REQUEST['keterangan'];
		if (isset($_REQUEST['jumlah_terkecil'])) $jumlah_terkecil = $_REQUEST['jumlah_terkecil'];
		if (isset($_REQUEST['satuan_terkecil'])) $satuan_terkecil = $_REQUEST['satuan_terkecil'];
		if (isset($_REQUEST['harga_terkecil'])) $harga_terkecil = $_REQUEST['harga_terkecil'];
		if (isset($_REQUEST['harga_index'])) $harga_index = preg_replace("/[^0-9]/", "", $_REQUEST['harga_index']);
		if (isset($_REQUEST['status'])) $status = 1;
		else $status = 0;

		switch ($oper) {
			case 'add':

				$sel = mysql_fetch_assoc(mysql_query("SELECT max(kd_sub2) as kdmax FROM ref_barang WHERE id_jenis = '$id_jenis'"));
				if ($kd_sub2 == '') {
					$kd_sub2 = $sel['kdmax'] + 1;
				}
				$u = mysql_fetch_row(mysql_query("SELECT UUID()"));
				$uuid = $u[0];
				$cek = mysql_query("SELECT id_barang_kegiatan FROM ref_barang_kegiatan WHERE id_barang_kegiatan = '$uuid'");
				if (mysql_num_rows($cek) == 0) {
					$q = mysql_query("INSERT INTO ref_barang (id_barang, 
														id_jenis, 
														kd_sub2, 
														nama_barang,
														id_satuan, 
														harga_index, 
														keterangan, 
														status,
														create_date, 
														creator_id,
														soft_delete) 
												VALUES ('$uuid',
														'$id_jenis',
														'$kd_sub2',
														'$nama_barang',
														'$id_satuan',
														'$harga_index',
														'$keterangan',
														'$status',
														NOW(),
														'$pengguna',
														'0')");
					if ($q) {
						echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil dimasukkan !"));
					} else {
						if (mysql_errno() == 1062) {
							echo json_encode(array(
								'success' => false,
								'pesan' => "Kode Barang Sudah Ada !",
								'error' => "nomor_sama"
							));
						} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil memasukkan data !"));
					}
				} else {
					echo json_encode(array(
						'success' => false,
						'pesan' => "Nomor Unique Sudah ada, lakukan simpan lagi !",
						'error' => "nomor_sama"
					));
				}
				break;
			case 'edit':
				$text = "UPDATE ref_barang SET id_jenis = '$id_jenis', 
											kd_sub2='$kd_sub2',
											nama_barang='$nama_barang',
											id_satuan='$id_satuan',
											harga_index='$harga_index',
											keterangan='$keterangan',
											status='$status',
											jumlah_terkecil='$jumlah_terkecil',
											satuan_terkecil='$satuan_terkecil',
											harga_terkecil='$harga_terkecil',
											update_date=NOW()
											WHERE id_barang = '$_GET[id_ubah]'";
				$q = mysql_query($text);
				if ($q) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil diubah !"));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Kode Barang Sudah Ada di Unit ini !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil mengubah data !"));
				}
				break;
			case 'del':
				$q = mysql_query("UPDATE ref_barang SET soft_delete = '1' WHERE id_barang = '$_POST[id_hapus]'");
				if ($q) {
					echo "Data berhasil dihapus !";
				} else {
					echo "Data tidak berhasil dihapus !";
				}
				break;
		}
	} elseif ($module == 'barang_kegiatan') {
		if (isset($_REQUEST['id_jenis'])) $id_jenis = $_REQUEST['id_jenis'];
		if (isset($_REQUEST['kode'])) $kode = $_REQUEST['kode'];
		if (isset($_REQUEST['id_unit'])) $id_unit = $_REQUEST['id_unit'];
		if (isset($_REQUEST['ta'])) $ta = $_REQUEST['ta'];
		if (isset($_REQUEST['nama_barang'])) $nama_barang = $_REQUEST['nama_barang'];
		if (isset($_REQUEST['id_satuan'])) $id_satuan = $_REQUEST['id_satuan'];
		if (isset($_REQUEST['keterangan'])) $keterangan = $_REQUEST['keterangan'];

		switch ($oper) {
			case 'add':
				$u = mysql_fetch_row(mysql_query("SELECT UUID()"));
				$uuid = $u[0];
				$cek = mysql_query("SELECT id_barang FROM ref_barang WHERE id_barang = '$uuid'");
				if (mysql_num_rows($cek) == 0) {
					$q = mysql_query("INSERT INTO ref_barang_kegiatan (id_barang_kegiatan, 
																uuid_skpd,
																ta,
																id_jenis, 
																kode, 
																nama_barang_kegiatan,
																id_satuan, 
																keterangan, 
																create_date, 
																creator_id,
																soft_delete) 
														VALUES ('$uuid',
																'$id_unit',
																'$ta',
																'$id_jenis',
																'$kode',
																'$nama_barang',
																'$id_satuan',
																'$keterangan',
																NOW(),
																'$pengguna',
																'0')");
					if ($q) {
						echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil dimasukkan !"));
					} else {
						if (mysql_errno() == 1062) {
							echo json_encode(array(
								'success' => false,
								'pesan' => "Kode Barang Sudah Ada !",
								'error' => "nomor_sama"
							));
						} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil memasukkan data !"));
					}
				} else {
					echo json_encode(array(
						'success' => false,
						'pesan' => "Nomor Unique Sudah ada, lakukan simpan lagi !",
						'error' => "nomor_sama"
					));
				}
				break;
			case 'edit':
				$text = "UPDATE ref_barang_kegiatan SET uuid_skpd = '$id_unit',
													ta = '$ta',
													id_jenis = '$id_jenis', 
													kode='$kode',
													nama_barang_kegiatan='$nama_barang',
													id_satuan='$id_satuan',
													keterangan='$keterangan',
													update_date=NOW()
													WHERE id_barang_kegiatan = '$_GET[id_ubah]'";
				$q = mysql_query($text);
				if ($q) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil diubah !"));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Kode Barang Sudah Ada di Unit ini !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil mengubah data !"));
				}
				break;
			case 'del':
				$q = mysql_query("UPDATE ref_barang_kegiatan SET soft_delete = '1' WHERE id_barang_kegiatan = '$_POST[id_hapus]'");
				if ($q) {
					echo "Data berhasil dihapus !";
				} else {
					echo "Data tidak berhasil dihapus !";
				}
				break;
		}
	} elseif ($module == 'satuan_barang') {
		if (isset($_REQUEST['nama_satuan'])) $nama = $_REQUEST['nama_satuan'];
		if (isset($_REQUEST['simbol'])) $simbol = $_REQUEST['simbol'];

		switch ($oper) {
			case 'add':
				$q = mysql_query("INSERT INTO ref_satuan (nama_satuan, simbol) VALUES('$nama', '$simbol')");
				if ($q) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil dimasukkan !"));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Tahun Sudah Ada !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil memasukkan data !"));
				}
				break;
			case 'edit':
				$text = "UPDATE ref_satuan SET nama_satuan = '$nama', simbol ='$simbol' WHERE id_satuan = '$_GET[id_ubah]'";
				$q = mysql_query($text);
				if ($q) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil diubah !$_GET[id_ubah]"));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Satuan Sudah Ada !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil mengubah data !"));
				}
				break;
			case 'del':
				$q = mysql_query("DELETE FROM ref_satuan WHERE id_satuan = '$_POST[id_hapus]'");
				if ($q) {
					echo "Data berhasil dihapus !$_POST[id_hapus]";
				} else {
					echo "Data tidak berhasil dihapus !";
				}
				break;
		}
	} elseif ($module == 'pejabat') {
		if (isset($_REQUEST['id_sub2'])) $uid_skpd = $_REQUEST['id_sub2'];
		if (isset($_REQUEST['ta'])) $ta = $_REQUEST['ta'];
		if (isset($_REQUEST['nama_pejabat'])) $nama_pejabat = $_REQUEST['nama_pejabat'];
		if (isset($_REQUEST['id_jabatan'])) $id_jabatan = $_REQUEST['id_jabatan'];
		if (isset($_REQUEST['id_golongan'])) $id_golongan = $_REQUEST['id_golongan'];
		if (isset($_REQUEST['nip'])) $nip = $_REQUEST['nip'];

		switch ($oper) {
			case 'add':

				$q = mysql_query("INSERT INTO pejabat (uuid_skpd, ta ,nama_pejabat,id_jabatan,id_golongan, nip) 
							VALUES('$uid_skpd','$ta','$nama_pejabat','$id_jabatan','$id_golongan','$nip')");
				if ($q) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil dimasukkan !"));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Kode Barang Sudah Ada !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil memasukkan data !"));
				}
				break;
			case 'edit':
				$text = "UPDATE pejabat SET 	uuid_skpd='$uid_skpd',
											ta='$ta',
											nama_pejabat='$nama_pejabat',
											id_jabatan='$id_jabatan',
											id_golongan='$id_golongan',
											nip='$nip'
											WHERE id_pejabat = '$_GET[id_ubah]'";
				$q = mysql_query($text);
				if ($q) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil diubah !"));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Kode Barang Sudah Ada di Unit ini !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil mengubah data !"));
				}
				break;
			case 'del':
				$q = mysql_query("DELETE FROM pejabat WHERE id_pejabat = '$_POST[id_hapus]'");
				if ($q) {
					echo "Data berhasil dihapus !";
				} else {
					echo "Data tidak berhasil dihapus !";
				}
				break;
		}
	} elseif ($module == 'perencanaan') {
		if (isset($_REQUEST['uuid_skpd'])) $uid_skpd = $_REQUEST['uuid_skpd'];
		if (isset($_REQUEST['ta'])) $ta = $_REQUEST['ta'];
		if (isset($_REQUEST['nm_kegiatan'])) $nm_kegiatan = $_REQUEST['nm_kegiatan'];
		if (isset($_REQUEST['nm_barang'])) $nm_barang = $_REQUEST['nm_barang'];
		if (isset($_REQUEST['id_satuan'])) $id_satuan = $_REQUEST['id_satuan'];
		if (isset($_REQUEST['jumlah_barang'])) $jumlah_barang = $_REQUEST['jumlah_barang'];
		if (isset($_REQUEST['jumlah_barang_isi'])) $jumlah_barang_isi = $_REQUEST['jumlah_barang_isi'];
		if (isset($_REQUEST['harga'])) $harga = $_REQUEST['harga'];
		if (isset($_REQUEST['id_sub_unit'])) $id_sub_unit = $_REQUEST['id_sub_unit'];
		if (isset($_REQUEST['id_kegiatan'])) $id_kegiatan = $_REQUEST['id_kegiatan'];
		if (isset($_REQUEST['nama_kegiatan'])) $nama_kegiatan = $_REQUEST['nama_kegiatan'];
		if (isset($_REQUEST['id_barang'])) $id_barang = $_REQUEST['id_barang'];
		if (isset($_REQUEST['nama_barang'])) $nama_barang = $_REQUEST['nama_barang'];
		if (isset($_REQUEST['id_sat_barang'])) $id_sat_barang = $_REQUEST['id_sat_barang'];
		if (isset($_REQUEST['id_jenis_barang'])) $jenis_barang = $_REQUEST['id_jenis_barang'];
		if (isset($_REQUEST['id_sumber'])) $id_sumber = $_REQUEST['id_sumber'];
		if (isset($_REQUEST['jumlah_sat_barang'])) $jumlah_sat_barang = $_REQUEST['jumlah_sat_barang'];
		if (isset($_REQUEST['harga_sat'])) $harga_sat = $_REQUEST['harga_sat'];

		switch ($oper) {
			case 'add':
				$q = mysql_query("INSERT INTO log_import (id_rencana,uuid_skpd,kd_kegiatan,nm_kegiatan,id_barang,nm_barang,id_satuan,jumlah_barang,harga,id_subrek,id_sumber_dana,ta,create_date,
				creator_id,STATUS,hasil,jumlah_barang_isi) 
							VALUES(UUID(), '$id_sub_unit', '$id_kegiatan', '$nama_kegiatan', '$id_barang', '$nama_barang', '$id_sat_barang', '$jumlah_sat_barang','$harga_sat','$jenis_barang',' $id_sumber','2022','$datime','$pengguna','1','Data Persediaan Berhasil di Import','0')");
				/* echo"INSERT INTO log_import (id_rencana,uuid_skpd,kd_kegiatan,nm_kegiatan,id_barang,nm_barang,id_satuan,jumlah_barang,harga,id_subrek,id_sumber_dana,ta,create_date,
				creator_id,STATUS,hasil,jumlah_barang_isi) 
							VALUES(UUID(), '$id_sub_unit', '$id_kegiatan', '$nama_kegiatan', '$id_barang', '$nama_barang', '$id_sat_barang', '$jumlah_sat_barang','$harga_sat','$jenis_barang',' $id_sumber','2022','$datime','$pengguna','1','Data Persediaan Berhasil di Import','0')"; */
				//$q = mysql_query($text);
				if ($q) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil Disimpan !"));
				} else {
				 echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil Disimpan !"));
				}
				break;
			case 'edit':
				//print_r($jumlah_barang);
				//print_r($jumlah_barang_isi);

				if($jumlah_barang < $jumlah_barang_isi){
					$text = " ";
				}else{
				$text = "UPDATE log_import SET 	
											nm_kegiatan='$nm_kegiatan',
											nm_barang='$nm_barang',
											jumlah_barang='$jumlah_barang'
											WHERE id_rencana = '$_GET[id_ubah]'";
				}
				$q = mysql_query($text);
				if ($q) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil diubah !"));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Jumlah Satuan Tidak boleh kurang dadi Jumlah Sisa Satuan !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil mengubah data !,Jumlah Satuan Tidak boleh kurang dari Jumlah Sisa Satuan"));
				}
				break;
			case 'del':
				//$q = mysql_query("UPDATE log_import SET status='1' WHERE id_rencana = '$_POST[id_hapus]'");
				$z = mysql_query("INSERT INTO log_import_backup
				SELECT* FROM log_import WHERE id_rencana='$_POST[id_hapus]'");
				$q = mysql_query("DELETE FROM log_import WHERE id_rencana = '$_POST[id_hapus]'");
				if ($q) {
					echo "Data berhasil dihapus !";
				} else {
					echo "Data tidak berhasil dihapus !";
				}
				break;
		}
	} elseif ($module == 'bidang') {
		if (isset($_REQUEST['kd_urusan'])) $kd_urusan = $_REQUEST['kd_urusan'];
		if (isset($_REQUEST['kd_bidang'])) $kd_bidang = $_REQUEST['kd_bidang'];
		if (isset($_REQUEST['nm_bidang'])) $nm_bidang = $_REQUEST['nm_bidang'];
		switch ($oper) {
			case 'add':
				$q = mysql_query("INSERT INTO ref_bidang (kd_urusan, kd_bidang, nm_bidang) VALUES('$kd_urusan','$kd_bidang','$nm_bidang')");
				if ($q) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil dimasukkan !"));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Bidang Sudah Ada !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil memasukkan data !"));
				}
				break;
			case 'edit':
				$text = "UPDATE ref_bidang SET nm_bidang = '$nm_bidang' WHERE CONCAT_WS('.', kd_urusan,  kd_bidang) = '$_GET[id_ubah]'";
				$q = mysql_query($text);
				if ($q) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil diubah !"));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Bidang Sudah Ada !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil mengubah data !"));
				}
				break;
			case 'del':
				$q = mysql_query("DELETE FROM ref_bidang WHERE CONCAT_WS('.', kd_urusan,  kd_bidang) = '$_POST[id_hapus]'");
				if ($q) {
					echo "Data berhasil dihapus !";
				} else {
					echo "Data tidak berhasil dihapus !";
				}
				break;
		}
	} elseif ($module == 'jenis_barang') {
		if (isset($_REQUEST['kd_kel'])) $kd_kel = $_REQUEST['kd_kel'];
		if (isset($_REQUEST['kd_sub'])) $kd_sub = $_REQUEST['kd_sub'];
		if (isset($_REQUEST['nama_jenis'])) $nama = $_REQUEST['nama_jenis'];

		switch ($oper) {
			case 'add':
				$q = mysql_query("INSERT INTO ref_jenis (id_jenis,kd_kel, kd_sub, nama_jenis) VALUES('$id_jenis','$kd_kel', '$kd_sub', '$nama')");
				if ($q) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil dimasukkan !"));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Jenis Barang Sudah Ada !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil memasukkan data !"));
				}
				break;
			case 'edit':
				$text = "UPDATE ref_jenis SET 	kd_kel = '$kd_kel', 
											kd_sub = '$kd_sub', 
											nama_jenis = '$nama' 
											WHERE id_jenis = '$_GET[id_ubah]'";
				$q = mysql_query($text);
				if ($q) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil diubah !"));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Jenis Barang Sudah Ada !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil mengubah data !"));
				}
				break;
			case 'del':
				$q = mysql_query("DELETE FROM ref_jenis WHERE id_jenis = '$_POST[id_hapus]'");
				if ($q) {
					echo "Data berhasil dihapus !";
				} else {
					echo "Data tidak berhasil dihapus !";
				}
				break;
		}
	} elseif ($module == 'sub_jenis_barang') {
		if (isset($_REQUEST['id_jenis'])) $id_jenis = $_REQUEST['id_jenis'];
		if (isset($_REQUEST['nama_sub_jenis'])) $nama = $_REQUEST['nama_sub_jenis'];
		//if(isset($_REQUEST['simbol']))$simbol = $_REQUEST['simbol'];

		switch ($oper) {
			case 'add':
				$q = mysql_query("INSERT INTO ref_sub_jenis (id_jenis, nama_sub_jenis) VALUES('$id_jenis', '$nama')");
				if ($q) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil dimasukkan !"));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Sub Jenis Barang Sudah Ada !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil memasukkan data !"));
				}
				break;
			case 'edit':
				$text = "UPDATE ref_sub_jenis SET id_jenis = '$id_jenis', nama_sub_jenis = '$nama' WHERE id_sub_jenis = '$_GET[id_ubah]'";
				$q = mysql_query($text);
				if ($q) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil diubah !"));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Sub Jenis Barang Sudah Ada !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil mengubah data !"));
				}
				break;
			case 'del':
				$q = mysql_query("DELETE FROM ref_sub_jenis WHERE id_sub_jenis = '$_POST[id_hapus]'");
				if ($q) {
					echo "Data berhasil dihapus !$_POST[id_hapus]";
				} else {
					echo "Data tidak berhasil dihapus !";
				}
				break;
		}
	} elseif ($module == 'kelompok_barang') {
		if (isset($_REQUEST['nama_kelompok'])) $nama = $_REQUEST['nama_kelompok'];


		switch ($oper) {
			case 'add':
				$q = mysql_query("INSERT INTO ref_kelompok (nama_kelompok) VALUES('$nama')");
				if ($q) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil dimasukkan !"));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Jenis Barang Sudah Ada !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil memasukkan data !"));
				}
				break;
			case 'edit':
				$text = "UPDATE ref_kelompok SET nama_kelompok = '$nama' WHERE id_kelompok = '$_GET[id_ubah]'";
				$q = mysql_query($text);
				if ($q) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil diubah !"));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Jenis Barang Sudah Ada !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil mengubah data !"));
				}
				break;
			case 'del':
				$q = mysql_query("DELETE FROM ref_kelompok WHERE id_kelompok = '$_POST[id_hapus]'");
				if ($q) {
					echo "Data berhasil dihapus !$_POST[id_hapus]";
				} else {
					echo "Data tidak berhasil dihapus !";
				}
				break;
		}
	} elseif ($module == 'golongan_jabatan') {
		if (isset($_REQUEST['nama_golongan'])) $nama_golongan = $_REQUEST['nama_golongan'];
		if (isset($_REQUEST['pangkat'])) $pangkat = $_REQUEST['pangkat'];

		switch ($oper) {
			case 'add':
				$q = mysql_query("INSERT INTO ref_golongan (nama_golongan, pangkat) VALUES('$nama_golongan', '$pangkat')");
				if ($q) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil dimasukkan !"));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Golongan Sudah Ada !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil memasukkan data !"));
				}
				break;
			case 'edit':
				$text = "UPDATE ref_golongan SET pangkat = '$pangkat', nama_golongan ='$nama_golongan' WHERE id_golongan = '$_GET[id_ubah]'";
				$q = mysql_query($text);
				if ($q) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil diubah !"));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Golongan Sudah Ada !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil mengubah data !"));
				}
				break;
			case 'del':
				$q = mysql_query("DELETE FROM ref_golongan WHERE id_golongan = '$_POST[id_hapus]'");
				if ($q) {
					echo "Data berhasil dihapus !";
				} else {
					echo "Data tidak berhasil dihapus !";
				}
				break;
		}
	} elseif ($module == 'jabatan_pejabat') {
		if (isset($_REQUEST['id_jabatan'])) $id_jabatan = $_REQUEST['id_jabatan'];
		if (isset($_REQUEST['nama_jabatan'])) $nama_jabatan = $_REQUEST['nama_jabatan'];

		switch ($oper) {
			case 'add':
				$q = mysql_query("INSERT INTO ref_jabatan (id_jabatan, nama_jabatan) VALUES('$id_jabatan', '$nama_jabatan')");
				if ($q) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil dimasukkan !"));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Jabatan Sudah Ada !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil memasukkan data !"));
				}
				break;
			case 'edit':
				$text = "UPDATE ref_jabatan SET id_jabatan = '$id_jabatan', nama_jabatan ='$nama_jabatan' WHERE id_jabatan= '$_GET[id_ubah]'";
				$q = mysql_query($text);
				if ($q) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil diubah !"));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Jabatan Sudah Ada !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil mengubah data !"));
				}
				break;
			case 'del':
				$q = mysql_query("DELETE FROM ref_jabatan WHERE id_jabatan = '$_POST[id_hapus]'");
				if ($q) {
					echo "Data berhasil dihapus !";
				} else {
					echo "Data tidak berhasil dihapus !";
				}
				break;
		}
	} elseif ($module == 'sumber_dana') {
		if (isset($_REQUEST['nama_sumber'])) $nama_sumber = $_REQUEST['nama_sumber'];

		switch ($oper) {
			case 'add':
				$q = mysql_query("INSERT INTO ref_sumber_dana (nama_sumber) VALUES('$nama_sumber')");
				if ($q) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil dimasukkan !"));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "ID Sumber Dana Sudah Ada !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil memasukkan data !"));
				}
				break;
			case 'edit':
				$text = "UPDATE ref_sumber_dana SET nama_sumber ='$nama_sumber' WHERE id_sumber= '$_GET[id_ubah]'";
				$q = mysql_query($text);
				if ($q) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil diubah !"));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "ID Sumber Dana Sudah Ada !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil mengubah data !"));
				}
				break;
			case 'del':
				$q = mysql_query("DELETE FROM ref_sumber_dana WHERE id_sumber = '$_POST[id_hapus]'");
				if ($q) {
					echo "Data berhasil dihapus !";
				} else {
					echo "Data tidak berhasil dihapus !";
				}
				break;
		}
	} elseif ($module == 'kelola_hak_akses') {
		if (isset($_REQUEST['menu'])) $menu = $_REQUEST['menu'];
		if (isset($_REQUEST['form'])) $form = $_REQUEST['form'];

		switch ($oper) {
			case 'add':
				$id_role = $form['id_role'];
				$q = mysql_query("INSERT INTO ref_akses (id_role, nama_akses) 
							VALUES('$id_role', '$form[nama_akses]')");
				$id_akses = mysql_insert_id();
				if (mysql_errno() == 0) {
					foreach ($menu as $ke => $me) {
						foreach ($me as $m) {
							mysql_query("INSERT INTO ref_akses_menu (uuid_hak_akses, id_akses, uuid_menu)
											VALUES	(UUID(), '$id_akses', '$m')");
						}
					}
					if (mysql_errno() == 0) {
						echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil diubah !"));
					} else {
						echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil mengubah data !", 'kode' => mysql_errno()));
					}
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Kombinasi Kode Sudah Ada !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil memasukkan data !"));
				}
				break;
			case 'edit':
				$text = "UPDATE ref_akses SET nama_akses = '$form[nama_akses]',
									  id_role = '$form[id_role]'
									WHERE id_akses = '$_GET[id_ubah]'";
									
				$q = mysql_query($text);
				if (mysql_errno() == 0) {
					mysql_query("DELETE FROM ref_akses_menu WHERE id_akses = '$_GET[id_ubah]'");

					foreach ($menu as $ke => $me) {
						foreach ($me as $m) {
							mysql_query("INSERT INTO ref_akses_menu (uuid_hak_akses, id_akses, uuid_menu)
											VALUES	(UUID(), '$_GET[id_ubah]', '$m')");
						}
					}


					if (mysql_errno() == 0) {
						echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil diubah !"));
					} else {
						echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil mengubah data !", 'kode' => mysql_errno()));
					}
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Kombinasi Kode Unit Sudah Ada !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil mengubah data !"));
				}

				break;
		}
	} elseif ($module == 'user') {
		if (isset($_REQUEST['id_sub2_unit'])) $uid_skpd = $_REQUEST['id_sub2_unit'];
		if (isset($_REQUEST['nama_pengelola'])) $nama_pengelola = $_REQUEST['nama_pengelola'];
		if (isset($_REQUEST['id_jabatan']))	$id_jabatan = $_REQUEST['id_jabatan'];
		else $id_jabatan = 0;
		if (isset($_REQUEST['id_golongan']))	$id_golongan = $_REQUEST['id_golongan'];
		else $id_golongan = 0;
		if (isset($_REQUEST['ta'])) $ta = $_REQUEST['ta'];
		if (isset($_REQUEST['alamat'])) $alamat = $_REQUEST['alamat'];
		if (isset($_REQUEST['nip'])) $nip = $_REQUEST['nip'];
		if (isset($_REQUEST['id_unit'])) $id_unit = $_REQUEST['id_unit'];
		if (isset($_REQUEST['id_sub_unit'])) $id_sub_unit = $_REQUEST['id_sub_unit'];
		if (isset($_REQUEST['id_sub2_unit'])) $id_sub2_unit = $_REQUEST['id_sub2_unit'];
		if (isset($_REQUEST['telpon'])) $telpon = $_REQUEST['telpon'];
		if (isset($_REQUEST['email'])) $email = $_REQUEST['email'];
		if (isset($_REQUEST['state'])) $state = 0;
		else $state = 1;
		if (isset($_REQUEST['id_akses'])) $id_akses = $_REQUEST['id_akses'];
		if (isset($_REQUEST['username'])) $username = $_REQUEST['username'];
		if (isset($_REQUEST['password'])) $password = $_REQUEST['password'];
		else $password = "";

		switch ($oper) {
			case 'add':
				if ($id_akses != 1) {
					$s = mysql_num_rows(mysql_query("SELECT uuid_skpd FROM ref_pengelola WHERE uuid_skpd = '$uid_skpd' AND id_akses = '$id_akses'
												AND state = 0 AND soft_delete = 0"));
					if ($s < 2) {
						$text = "INSERT INTO ref_pengelola ( id_pengelola, nama_pengelola, id_golongan, id_jabatan, nip, alamat, telpon, email, 
														uuid_skpd, ta, username, password, id_akses, state, create_date, creator_id)
													VALUES (UUID(), '$nama_pengelola', '$id_golongan', '$id_jabatan', 
														'$nip', '$alamat', '$telpon', '$email', '$uid_skpd', '$ta', 
														'$username', '$password', '$id_akses', '$state', '$datime', '$pengguna')";

						$q = mysql_query($text);
						if ($q) {
							echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil dimasukkan ! "));
						} else {
							echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil memasukkan data !"));
						}
					} else {
						echo json_encode(array(
							'success' => false,
							'pesan' => "User untuk Sub Unit ini Sudah Ada !",
							'error' => "nomor_sama"
						));
					}
				} else {
					$text = "INSERT INTO ref_pengelola ( id_pengelola, nama_pengelola, id_golongan, id_jabatan, nip, alamat, telpon, email, 
													uuid_skpd, ta, username, password, id_akses, state, create_date, creator_id)
												VALUES (UUID(), '$nama_pengelola', '$id_golongan', '$id_jabatan', 
													'$nip', '$alamat', '$telpon', '$email', '$uid_skpd', '$ta', 
													'$username', '$password', '$id_akses', '$state', '$datime', '$pengguna')";

					$q = mysql_query($text);
					if ($q) {
						echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil dimasukkan ! "));
					} else {
						echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil memasukkan data !"));
					}
				}

				break;
			case 'edit':

				$text = "UPDATE ref_pengelola SET 
						nama_pengelola = '$nama_pengelola', 
						id_golongan = '$id_golongan', 
						id_jabatan = '$id_jabatan', 
						nip = '$nip', 
						alamat = '$alamat',
						telpon = '$telpon', 
						email = '$email', 
						uuid_skpd= '$uid_skpd',
						ta = '$ta', 
						username = '$username', 
						password = '$password',
						id_akses = '$id_akses',
						state = '$state',
						update_date = '$datime'
						WHERE id_pengelola = '$_GET[id_ubah]'";

				$q = mysql_query($text);
				if ($q) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil diubah!"));
				} else {
					echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil mengubah data !"));
				}

				break;
			case 'del':
				$q = mysql_query("UPDATE ref_pengelola SET soft_delete ='1' WHERE id_pengelola = '$_POST[id_hapus]'");
				if ($q) {
					echo "Data berhasil dihapus !";
				} else {
					echo "Data tidak berhasil dihapus !";
				}
				break;
		}
	} elseif ($module == 'data_awal') {

		switch ($oper) {
			case 'up':

				//upload file data awal persediaan
				if (!empty($_FILES['file_awal']['name'])) {
					$uploadfile   = './berkas/' . basename($_FILES['file_awal']['name']);
					$name   = $_FILES['file_awal']['name'];
					//filter

					$csv_mimetypes = array(
						'text/csv',
						'text/plain',
						'application/csv',
						'text/comma-separated-values',
						'application/excel',
						'application/vnd.ms-excel',
						'application/vnd-ms-excel',
						'application/vnd.msexcel',
						'text/anytext',
						'application/octet-stream',
						'application/txt',
					);
					$type = pathinfo($_FILES['file_awal']['name'], PATHINFO_EXTENSION);
					if (in_array($_FILES['file_awal']['type'], $csv_mimetypes) && ($_FILES['file_awal']['size'] < 6000000) && ($type == 'csv')) {
						//cek if nama sudah ada
						$actual_name = pathinfo($name, PATHINFO_FILENAME);
						$original_name = $actual_name;
						$extension = pathinfo($name, PATHINFO_EXTENSION);

						$i = 1;
						while (file_exists('./berkas/' . $actual_name . "." . $extension)) {
							$actual_name = (string)$original_name . $i;
							$name = $actual_name . "." . $extension;
							$i++;
						}

						$uploadfile = './berkas/' . $name;
						if (file_exists($uploadfile)) {
							echo json_encode(array('success' => false, 'pesan' => "Nama File sudah ada, ganti nama lain !"));
							break;
						} else {
							move_uploaded_file($_FILES['file_awal']['tmp_name'], $uploadfile);
							$result = array();
							$items = array();
							$r = 0;
							$ttotal = 0;
							$invalid = "";
							$handle = fopen($uploadfile, "r"); //Membuka file dan membacanya
							//$content = file_get_contents($uploadfile);
							$delimiter = detectDelimiter($uploadfile);
							//unlink($uploadfile); break;
							fgets($handle); // read the first line and ignore it
							while (($data = fgetcsv($handle, 10000, $delimiter)) !== FALSE) {
								//$saldo = preg_replace("/[^0-9]/","", $data[4]);
								//$harga = preg_replace("/[^0-9]/","", $data[5]);
								$data[4] = str_replace(",", ".", $data[4]);
								$data[5] = str_replace(",", ".", $data[5]);
								$saldo = $data[4];
								$harga = $data[5];

								if ($saldo != 0) {
									$ada = mysql_query("SELECT id_barang FROM ref_barang WHERE id_barang = '$data[0]' AND soft_delete = 0");
									if (mysql_num_rows($ada) > 0) {
										//if($_POST['id_kelompok']==3 OR $_POST['id_kelompok']==4) $data[4] = 1;
										$total = $saldo * $harga;
										$row['id_bar'] = $data[0];
										$row['kode'] = $data[1];
										$row['nama_bar'] = $data[2];
										$row['satuan'] = $data[3];
										$row['saldo'] = number_format($saldo, 6, ',', '.');
										$row['harga'] = number_format($harga, 6, ',', '.');
										$row['total'] = number_format($total, 6, ',', '.');
										$ttotal += $total;
										array_push($items, $row);
										$r++;
									} else $invalid .= $data[2];
								}
							}
							$result["total"] = $r;
							$result["rows"] = $items;
							$result["total"] = number_format($ttotal, 6, ',', '.');
							if ($invalid != "") $txtValid = "Ada beberapa invalid data!<br>";
							else $txtValid = "";

							echo json_encode(array(
								'success' => true,
								'pesan' => "Data persediaan telah berhasil dikonfirmasi!<br>
													$txtValid
													Lakukan pengecekan data yang tertampil, Lalu lakukan proses simpan!",
								'data' => $result,
								'error' => $invalid
							));
							unlink($uploadfile);
						}
					} else {
						echo json_encode(array('success' => false, 'pesan' => "File tidak sesuai format !"));
						//unlink($uploadfile);
						break;
					}
				}
				break;

			case 'save':
				if (isset($_REQUEST['tgl_awal'])) $tgl_awal = balikTanggal($_REQUEST['tgl_awal']);
				if (isset($_REQUEST['tgl_ba'])) $tgl_ba = balikTanggal($_REQUEST['tgl_ba']);
				if (isset($_REQUEST['no_ba'])) $no_ba = $_REQUEST['no_ba'];
				if (isset($_REQUEST['basket'])) $basket = $_REQUEST['basket'];
				if (isset($_REQUEST['uid_skpd'])) $uid_skpd = $_REQUEST['uid_skpd'];
				if (isset($_REQUEST['id_gudang'])) $id_gudang = $_REQUEST['id_gudang'];
				if (isset($_REQUEST['id_kelompok'])) $id_kelompok = $_REQUEST['id_kelompok'];
				if (isset($_REQUEST['id_sumber'])) $id_sumber = $_REQUEST['id_sumber'];
				$ta = date('Y', strtotime($tgl_awal));

				if ($_SESSION['level'] == md5('c')) {
					$u = mysql_fetch_assoc(mysql_query("SELECT uuid_sub2_unit FROM ref_sub2_unit WHERE MD5(uuid_sub2_unit) = '$uid_skpd'"));
					$uuid_skpd = $u['uuid_sub2_unit'];
				} else $uuid_skpd = $uid_skpd;
				$ada = mysql_query("SELECT id_adjust FROM adjust WHERE uuid_skpd = '$uuid_skpd' AND id_gudang = '$id_gudang' 
									AND id_kelompok = '$id_kelompok' AND id_sumber_dana = '$id_sumber' AND status = 'data_awal' 
									AND soft_delete = 0");
				if (mysql_num_rows($ada) > 0) {
					$a = mysql_fetch_assoc($ada);
					mysql_query("UPDATE adjust a LEFT JOIN adjust_detail d ON a.id_adjust = d.id_adjust
											 LEFT JOIN kartu_stok k ON k.id_transaksi = a.id_adjust
							SET a.soft_delete = 1, d.soft_delete = 1, k.soft_delete = 1
							WHERE a.id_adjust = '$a[id_adjust]'");
				}

				$u = mysql_fetch_row(mysql_query("SELECT UUID()"));
				$uuid = $u[0];
				mysql_query("INSERT INTO adjust (id_adjust, uuid_skpd, tgl_adjust, tgl_ba, no_ba, id_gudang, id_kelompok, id_sumber_dana, 
											status, create_date, creator_id)
									VALUES ('$uuid', '$uuid_skpd', '$tgl_awal', '$tgl_ba', '$no_ba', '$id_gudang', '$id_kelompok', 
											'$id_sumber', 'data_awal', '$datime', '$pengguna')");
				if (mysql_errno() == 0) {
					foreach ($basket as $val) {
						//$harga = preg_replace("/[^0-9]/","", $val['harga']);	
						//$jumlah = preg_replace("/[^0-9]/","", $val['saldo']);
						$harga1 = $val['harga'];
						$jumlah1 = $val['saldo'];

						$harga = str_replace(".", "", $harga1);
						$harga = str_replace(",", ".", $harga);
						$jumlah = str_replace(".", "", $jumlah1);
						$jumlah = str_replace(",", ".", $jumlah);

						$u = mysql_fetch_row(mysql_query("SELECT UUID()"));
						$uuidet = $u[0];
						mysql_query("INSERT INTO adjust_detail (id_adjust_detail, id_adjust, uuid_skpd, id_barang, jumlah, harga, 
															create_date, creator_id)
													VALUES ('$uuidet', '$uuid', '$uuid_skpd', '$val[id_bar]', '$jumlah', '$harga',
															'$datime', '$pengguna')");
						mysql_query("INSERT INTO kartu_stok (id_stok, uuid_skpd, id_barang, id_kelompok, id_gudang, id_sumber_dana,
														id_transaksi, id_transaksi_detail,
														tgl_transaksi, ta, jml_in, jml_out, harga, kode,
														create_date, soft_delete, creator_id)
												VALUES	(UUID(), '$uuid_skpd', '$val[id_bar]', '$id_kelompok', '$id_gudang', '$id_sumber',
														'$uuid', '$uuidet',
														'$tgl_awal', '$ta', '$jumlah', 0, '$harga', 'a',
														'$datime', 0, '$pengguna')");
					}

					if (mysql_errno() == 0) echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil dimasukkan !"));
					else echo json_encode(array('success' => false, 'pesan' => "Data tidak berhasil dimasukkan !", 'error' => mysql_error()));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Data Awal Sudah Ada !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil memasukkan data !"));
				}

				break;
		}
	} elseif ($module == 'pengadaan') {
		//if(isset($_REQUEST['id_sub']))$uid_skpd = $_REQUEST['id_sub'];
		if (isset($_REQUEST['form'])) $form = $_REQUEST['form'];
		if (isset($_REQUEST['ubahform'])) $ubahform = $_REQUEST['ubahform'];
		if (isset($_REQUEST['basket']['rows'])) $basket = $_REQUEST['basket']['rows'];
		else $basket = array();
		if (isset($_REQUEST['basrinci'])) $basrinci = $_REQUEST['basrinci'];
		else $basrinci = array();
		if (isset($form['id_sub'])) $uid_skpd = $form['id_sub'];
		if (isset($form['nama_pengadaan'])) $nama_pengadaan = $form['nama_pengadaan'];
		if (isset($form['id_sumber'])) $id_sumber = $form['id_sumber'];
		if (isset($form['ta'])) $ta = $form['ta'];
		if (isset($form['nama_penyedia'])) $nama_penyedia = $form['nama_penyedia'];
		if (isset($form['no_kontrak'])) $no_kontrak = $form['no_kontrak'];
		if (isset($form['tgl_pengadaan'])) $tgl_pengadaan = balikTanggal($form['tgl_pengadaan']);
		if (isset($form['kd_awal'])) $kd_skpd = $form['kd_awal'];
		if (isset($form['kd_prog'])) $kd_prog = $form['kd_prog'];
		if (isset($form['id_prog'])) $id_prog = $form['id_prog'];
		if (isset($form['kd_keg'])) $kd_keg = $form['kd_keg'];
		if (isset($form['kd_rek_1'])) $kd_rek_1 = $form['kd_rek_1'];
		if (isset($form['kd_rek_2'])) $kd_rek_2 = $form['kd_rek_2'];
		if (isset($form['kd_rek_3'])) $kd_rek_3 = $form['kd_rek_3'];
		if (isset($form['kd_rek_4'])) $kd_rek_4 = $form['kd_rek_4'];
		if (isset($form['kd_rek_5'])) $kd_rek_5 = $form['kd_rek_5'];
		if (isset($form['no_rinc'])) $no_rinc = $form['no_rinc'];
		if (isset($form['id_gud'])) $id_gud = $form['id_gud'];
		if (isset($form['no_pembayaran'])) $no_pembayaran = $form['no_pembayaran'];
		if (isset($form['tgl_pembayaran'])) $tgl_pembayaran = balikTanggal($form['tgl_pembayaran']);
		if (isset($form['sp'])) $sp = $form['sp'];
		if (isset($form['tgl_penerimaan'])) $tgl_penerimaan = balikTanggal($form['tgl_penerimaan']);

		$qq = mysql_fetch_assoc(mysql_query("SELECT CONCAT_WS('.',kd_urusan, kd_bidang, kd_unit) AS kds FROM ref_sub2_unit WHERE nm_sub2_unit = '$_SESSION[nm_sub2_unit]' "));
		$lock = mysql_query("SELECT * FROM kunci_entri_skpd WHERE kd_skpd = '$qq[kds]' ");
		//$lock = mysql_query("SELECT * FROM kunci_entri_skpd WHERE kd_skpd = '$_SESSION[kode_sub]' ");
		$jumlock = mysql_num_rows($lock);
		if ($jumlock > 0) {
			$gl = mysql_fetch_assoc($lock);

			$tga = strtotime($tanggal);
			$tgb = strtotime($gl["kunci_sampai"]);
			$gl["kunci_sampai"] = balikTanggalIndo($gl["kunci_sampai"]);
			if ($tga <= $tgb) {
				// $allow = 0;
				$allow = 1;
			} else {
				$allow = 1;
			}
		} else {
			$allow = 1;
			$gl["kunci_sampai"] = "";
		}

		if ($allow == 1) {
			switch ($oper) {
				case 'add':
					// $pscek = "";
					// foreach($basket AS $cval){
					// $cid_bar = $cval['id_bar'];
					// $cid_kel = $cval['id_kel'];
					// if($cid_kel==1 OR $cid_kel==2){ $cekkel1 = 3; $cekkel2 = 4; } else { $cekkel1 = 1; $cekkel2 = 2; }
					// $qcek = mysql_fetch_assoc(mysql_query("SELECT SUM(jml_in-jml_out) AS jml FROM kartu_stok k
					// WHERE k.id_barang = '$cid_bar' AND k.uuid_skpd = '$uid_skpd'
					// AND (k.id_kelompok = '$cekkel1' OR k.id_kelompok = '$cekkel2')
					// AND k.soft_delete = 0"));
					// if($qcek['jml']!=0){
					// $pscek = "Error";
					// }
					// }	
					// if($pscek!=""){
					// echo json_encode(array('success'=>false, 'pesan'=>"Tidak berhasil memasukkan data, Kelompok Barang Salah!"));
					// break;
					// }


					/* print_r($basket);
			print_r($basrinci);
			foreach($basket AS $val){
				if(isset($basrinci[$val['idbas']])){
					echo $val['idbas'];
				}
			}
			break; */



					$ceknota = mysql_fetch_assoc(mysql_query("SELECT id_masuk, no_pembayaran FROM masuk WHERE uuid_skpd = '$uid_skpd' AND no_pembayaran = '$no_pembayaran' AND soft_delete = 0"));

					if ($ceknota["no_pembayaran"] == $no_pembayaran) {

						$totm = mysql_fetch_assoc(mysql_query("SELECT SUM(jml_in*harga) AS total FROM kartu_stok WHERE id_transaksi = '$ceknota[id_masuk]' AND soft_delete = 0"));
						$totm["total"] = number_format($totm['total'], 2, ',', '.');
						echo json_encode(array('success' => false, 'pesan' => "Nota <b>$no_pembayaran</b> Sudah Dientri dengan Nilai <b>$totm[total]</b> !"));
						break;
					}



					$u = mysql_fetch_row(mysql_query("SELECT UUID()"));
					$uuid = $u[0];
					mysql_query("INSERT INTO masuk ( id_masuk, uuid_skpd, kd_skpd,
												kd_prog, id_prog, kd_keg, kd_rek_1, kd_rek_2, kd_rek_3, kd_rek_4, 
												kd_rek_5, no_rinc, ta, 
												nama_pengadaan, nama_penyedia, tgl_pengadaan, no_kontrak,
												tgl_pembayaran, no_pembayaran, id_sumber, status_proses,
												create_date, 
												creator_id,
												soft_delete) 
										VALUES ('$uuid', '$uid_skpd', '$kd_skpd',
												'$kd_prog', '$id_prog', '$kd_keg', '$kd_rek_1', '$kd_rek_2', '$kd_rek_3', '$kd_rek_4',
												'$kd_rek_5', '$no_rinc', '$ta',
												'$nama_pengadaan', '$nama_penyedia', '$tgl_pengadaan', '$no_kontrak',
												'$tgl_pembayaran', '$no_pembayaran', '$id_sumber', '1',
												'$datime',
												'$pengguna',
												'0')");

					if (mysql_errno() == 0) {
						foreach ($basket as $val) {
							$harga = preg_replace("/[^0-9]/", "", $val['harga_asli']);
							$jumlah = preg_replace("/[^0-9]/", "", $val['jumlah']);
							$hrgsat = $harga / $jumlah;
							$ud = mysql_fetch_row(mysql_query("SELECT UUID()"));
							$uuidet = $ud[0];
							mysql_query("INSERT INTO masuk_detail ( id_masuk_detail, id_masuk, uuid_skpd,
															ta, id_kelompok,
															id_barang, jml_masuk, 
															harga_masuk, keterangan, tahun,
															create_date, 
															creator_id,
															soft_delete)
													VALUES( '$uuidet', '$uuid', '$uid_skpd', 
															'$ta', '$val[id_kel]',
															'$val[id_bar]', '$jumlah', 
															'$hrgsat', '$val[ket]', '$val[tahun]',
															'$datime',
															'$pengguna',
															'0')");

							if (isset($basrinci[$val['idbas']])) {
								$rincian = $basrinci[$val['idbas']]['rows'];
								foreach ($rincian as $rin) {
									$hrgrin = preg_replace("/[^0-9]/", "", $rin['harga_asli']);
									$jmlrin = preg_replace("/[^0-9]/", "", $rin['jumlah']);
									$hrg1 = $hrgrin / $jmlrin;

									mysql_query("INSERT INTO masuk_detail_rinci(id_masuk_detail_rinci, id_masuk_detail, 
																		id_barang, jumlah, harga, 
																		create_date, creator_id)
																VALUES (UUID(), '$uuidet', '$rin[id_bar]', 
																		'$jmlrin', '$hrg1',
																		'$datime', '$pengguna')");
								}
							}
						}

						echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil dimasukkan !"));
					} else {
						if (mysql_errno() == 1062) {
							echo json_encode(array(
								'success' => false,
								'pesan' => "Kode Barang Sudah Ada !",
								'error' => "nomor_sama"
							));
						} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil memasukkan data !"));
					}
					break;
					//EDIT RINCIAN BARANG BELUM BISA DIGUNAKAN KARENA MASIH BANYAK BUG
				case 'edit':
					if ($ubahform != '') {
						$dataubah = "";
						$uidubah = "";
						$form = explode("||", $ubahform);
						foreach ($form as $field) {
							$f = explode('::', $field);
							$v = explode('|', $field);
							if ($f[0] == 'id_sub') {
								$id_sub_ganti = $v[1];
								$kdg = explode('.', $id_sub_ganti);
								$uidubah = "uuid_skpd = '$id_sub_ganti'";
								//$dataubah .= "kd_urusan = '$kdg[0]', kd_bidang = '$kdg[1]', kd_unit = '$kdg[2]', kd_sub = '$kdg[3]', uuid_skpd = '$uid_skpd', ";
								$dataubah .= "uuid_skpd = '$uid_skpd', ";
							} elseif ($f[0] == 'ta') {
								$ta_ganti = $v[1];
								$dataubah .= "ta = '$ta_ganti', ";
							}
						}

						if ($dataubah != "") {
							$dataubah = substr($dataubah, 0, -2);
							mysql_query("UPDATE masuk_detail SET $dataubah , update_date = '$datime' WHERE id_masuk = '$_GET[id_ubah]'");
							if ($uidubah != "") mysql_query("UPDATE kartu_stok SET $uidubah WHERE id_transaksi = '$_GET[id_ubah]'");
						}

						mysql_query("UPDATE masuk SET uuid_skpd = '$uid_skpd', kd_skpd = '$kd_skpd',
												kd_prog = '$kd_prog', id_prog = '$id_prog', kd_keg = '$kd_keg', kd_rek_1 = '$kd_rek_1',
												kd_rek_2 = '$kd_rek_2', kd_rek_3 = '$kd_rek_3', kd_rek_4 = '$kd_rek_4', 
												kd_rek_5 = '$kd_rek_5', no_rinc = '$no_rinc', ta = '$ta', 
												nama_pengadaan = '$nama_pengadaan', nama_penyedia = '$nama_penyedia',
												tgl_pengadaan = '$tgl_pengadaan', no_kontrak = '$no_kontrak',
												tgl_pembayaran = '$tgl_pembayaran', no_pembayaran = '$no_pembayaran',
												id_sumber = '$id_sumber', update_date = '$datime'  
									WHERE id_masuk = '$_GET[id_ubah]'");
					}



					$datser = mysql_query("SELECT d.id_barang AS id_bar, jml_masuk AS jumlah, tahun, harga_masuk AS harga, 
									d.id_kelompok AS id_kel, d.keterangan AS ket, id_masuk_detail AS id, harga_masuk AS harga_asli
									FROM masuk_detail d
									WHERE id_masuk = '$_GET[id_ubah]' AND d.soft_delete=0");
					while ($dat = mysql_fetch_assoc($datser)) {
						if ($dat['id_kel'] == 3 || $dat['id_kel'] == 4) {
							$sub = mysql_query("SELECT m.id_barang AS id_bar, jumlah, harga, m.id_masuk_detail_rinci AS id
										FROM masuk_detail_rinci m
										WHERE id_masuk_detail = '$dat[id]' AND m.soft_delete = 0");
							$rinci = array();
							while ($r = mysql_fetch_assoc($sub)) {
								$rinci[$r['id']] = $r;
							}
							$dat['rinci'] = $rinci;
						}
						$server[$dat['id']] = $dat;
					}

					$datacek = array();
					$edit = array();
					$add = array();
					$del = array();
					$cek = "";
					$addrinci = array();
					$delrinci = array();
					$delrinciall = array();
					foreach ($basket as $key => $val) {
						if (isset($val['id'])) { //data lama
							$da = $server[$val['id']];
							if ($val['id'] == $da['id']) { //data lama masih ada
								$isi = "";
								$isi2 = "";
								$kubah = "";
								$jml = preg_replace("/[^0-9]/", "", $val['jumlah']);
								$hrg = preg_replace("/[^0-9]/", "", $val['harga_asli']);
								$hrgs = $hrg / $jml;
								if ($da['id_bar'] != $val['id_bar']) {
									$isi .= "id_barang = '$val[id_bar]', ";
									$isi2 .= "id_barang = '$val[id_bar]', ";
									$kubah = "ya";
								}
								if ($da['harga'] != $hrgs) {
									$isi .= "harga_masuk = '$hrgs', ";
									$isi2 .= "harga = '$hrgs', ";
								}
								if ($da['jumlah'] != $jml) {
									$isi .= "jml_masuk = '$jml', ";
									$isi2 .= "jml_in = '$jml', ";
								}
								if ($da['tahun'] != $val['tahun']) $isi .= "tahun = '$val[tahun]', ";
								if ($da['ket'] != $val['ket']) $isi .= "keterangan = '$val[ket]', ";
								/* if($val['id_kel']==3 || $val['id_kel']==4){
							if($da['id_kel']!=$val['id_kel']){ 
								if($da['id_kel']==1 || $da['id_kel']==2){
									$view = $basrinci[$key]['rows'];
									foreach($view AS $ke => $vi){
										$bs = $view[$ke];
										$bs['idd'] = $val['id'];
										array_push($addrinci, $bs);
									}	
								}
								$isi .= "id_kelompok = '$val[id_kel]', "; $kubah = "ya";
							}else{
								$ser = $da['rinci'];
								$view = $basrinci[$key]['rows'];
								$editrin = array();
								
								foreach($view AS $ke => $vi){
									//print_r($vi);
									if(isset($vi['id'])){
										$se = $ser[$vi['id']];
										if($se['id']==$vi['id']){
											$isir = "";
											$jmlr = preg_replace("/[^0-9]/","", $vi['jumlah']);
											$hrgr = preg_replace("/[^0-9]/","", $vi['harga_asli']);
											$hrgsr = $hrgr/$jmlr;
											
											if($vi['id_bar']!=$se['id_bar']) $isir .= "id_barang = '$vi[id_bar]', ";
											if($jmlr!=$se['jumlah']) $isir .= "jumlah = '$jmlr', ";
											if($hrgsr!=$se['harga']) $isir .= "harga = '$hrgsr', ";
											
											if($isir!=""){
												$edr['id'] = $vi['id'];
												$edr['isi'] = $isir;
												array_push($editrin, $edr);
											}
											
										}
										unset($ser[$vi['id']]);
									}else{
										$bs = $view[$ke];
										$bs['idd'] = $val['id'];
										array_push($addrinci, $bs);
									}
								}
								if($ser) $delrinci[$val['id']] = $ser;
								
							}
						}else{ */
								if ($da['id_kel'] != $val['id_kel']) {
									/* if($da['id_kel']==3 || $da['id_kel']==4){
									$dr['idd'] = $val['id'];
									unset($dr['idr']);
									array_push($delrinciall, $dr);
								} */
									$isi .= "id_kelompok = '$val[id_kel]', ";
									$kubah = 'ya';
								}

								//}

								/* if($kubah=="ya"){
							$dc['id_bar'] = $val['id_bar'];
							$dc['id_kel'] = $val['id_kel'];
							$datacek[$dc['id_bar']] = $dc;
						} */

								if ($isi != "") {
									$ed['id'] = $val['id'];
									$ed['isi'] = $isi;
									$ed['isi2'] = $isi2;
									//if(isset($editrin) && !empty($editrin)) $ed['rinc'] = $editrin; else unset($ed['rinc']);
									array_push($edit, $ed);
								}/* elseif(isset($editrin) && !empty($editrin)){
							$ed['id'] = $val['id'];
							$ed['isi'] = "";
							$ed['isi2'] = "";
							$ed['rinc'] = $editrin;
							array_push($edit, $ed);
						} */
							} else {
								array_push($del, $da['id']);
							}
							unset($server[$val['id']]);
						} else { //data baru
							array_push($add, $basket[$key]);
							//$dc['id_bar'] = $val['id_bar'];
							//$dc['id_kel'] = $val['id_kel'];
							//$datacek[$dc['id_bar']] = $dc;
						}
					}
					if ($server) $del = $server;

					//CEK DATA SEBELUM DIUBAH DI DATABASE
					/* $pscek = "";
			foreach($datacek AS $cval){
				$cid_bar = $cval['id_bar'];
				$cid_kel = $cval['id_kel'];
				if($cid_kel==1 OR $cid_kel==2){ $cekkel1 = 3; $cekkel2 = 4; } else { $cekkel1 = 1; $cekkel2 = 2; }
				$qcek = mysql_fetch_assoc(mysql_query("SELECT SUM(jml_in-jml_out) AS jml FROM kartu_stok k
							WHERE k.id_barang = '$cid_bar' AND k.uuid_skpd = '$uid_skpd'
								AND (k.id_kelompok = '$cekkel1' OR k.id_kelompok = '$cekkel2')
								AND k.soft_delete = 0"));
				if($qcek['jml']!=0){
					$pscek = "Error";
				}else{
					if($cid_kel==3 OR $cid_kel==4){
						$qcekk = mysql_fetch_assoc(mysql_query("SELECT SUM(jml_in-jml_out) AS jml FROM kartu_stok k
							WHERE k.id_barang = '$cid_bar' AND k.uuid_skpd = '$uid_skpd'
								AND k.id_kelompok = '$cid_kel' AND k.soft_delete = 0"));
						if($qcekk['jml']!=0){
							$pscek = "update";
						}else{
							$pscek = "insert";
						}
					}else{
						$pscek = "insert";
					}
				}
			}
			
			if($pscek=="Error"){
				echo json_encode(array('success'=>false, 'pesan'=>"Tidak Bisa Memasukkan Data Barang yang berbeda kelompok!"));
				break;
			}
			 */
					foreach ($edit as $e) {
						if ($e['isi'] != '') mysql_query("UPDATE masuk_detail SET $e[isi] update_date = '$datime' WHERE id_masuk_detail = '$e[id]' AND id_masuk = '$_GET[id_ubah]'");
						if ($e['isi2'] != '') mysql_query("UPDATE kartu_stok SET $e[isi2] update_date = '$datime' WHERE id_transaksi_detail = '$e[id]' AND id_transaksi = '$_GET[id_ubah]'");
						/* if(isset($e['rinc'])){
					foreach($e['rinc'] AS $err){
						mysql_query("UPDATE masuk_detail_rinci SET $err[isi] update_date = '$datime' WHERE id_masuk_detail_rinci = '$err[id]' AND id_masuk_detail = '$e[id]'");
					}
				} */
					}

					foreach ($add as $val) {
						$hrg = preg_replace("/[^0-9]/", "", $val['harga_asli']);
						$jml = preg_replace("/[^0-9]/", "", $val['jumlah']);
						$hrgs = $hrg / $jml;
						$ude = mysql_fetch_row(mysql_query("SELECT UUID()"));
						$uuidet = $ude[0];
						mysql_query("INSERT INTO masuk_detail ( id_masuk_detail, id_masuk, uuid_skpd,
														ta, tahun, id_kelompok, 
														id_barang, jml_masuk,  
														harga_masuk, keterangan,
														create_date, 
														creator_id,
														soft_delete)
												VALUES( '$uuidet', '$_GET[id_ubah]', '$uid_skpd',
														'$ta', '$val[tahun]', '$val[id_kel]', 
														'$val[id_bar]', '$jml', 
														'$hrgs', '$val[ket]',
														'$datime',
														'$pengguna',
														'0')");
						//print_r($basrinci);										
						/* if(isset($basrinci[$val['idbas']])){
					$rincian = $basrinci[$val['idbas']]['rows'];
					
					foreach($rincian AS $rin){
						$hrgrin = preg_replace("/[^0-9]/","", $rin['harga_asli']);	
						$jmlrin = preg_replace("/[^0-9]/","", $rin['jumlah']);	
						$hrg1 = $hrgrin/$jmlrin;
						
						mysql_query("INSERT INTO masuk_detail_rinci(id_masuk_detail_rinci, id_masuk_detail, 
																	id_barang, jumlah, harga, 
																	create_date, creator_id)
															VALUES (UUID(), '$uuidet', '$rin[id_bar]', 
																	'$jmlrin', '$hrg1',
																	'$datime', '$pengguna')");
					}
				
				} */

						if ($sp == "3") {

							mysql_query("INSERT INTO kartu_stok (id_stok, uuid_skpd, id_barang, id_kelompok, id_gudang, 
														id_sumber_dana, id_transaksi, id_transaksi_detail,
														tgl_transaksi, ta, jml_in, jml_out, harga, kode,
														create_date, soft_delete, creator_id)
												VALUES	(UUID(), '$uid_skpd', '$val[id_bar]', '$val[id_kel]', '$id_gud', 
														'$id_sumber', '$_GET[id_ubah]', '$uuidet',
														'$tgl_penerimaan', '$ta', '$jml', 0, '$hrgs', 'i',
														'$datime', 0, '$pengguna')");
						}
					}

					foreach ($del as $id => $vl) {
						mysql_query("UPDATE masuk_detail SET soft_delete = '1' WHERE id_masuk_detail = '$id' AND id_masuk = '$_GET[id_ubah]'");
						mysql_query("UPDATE masuk_detail_rinci SET soft_delete = '1' WHERE id_masuk_detail = '$id'");
						mysql_query("UPDATE kartu_stok SET soft_delete = '1' WHERE id_transaksi_detail = '$id' AND id_transaksi = '$_GET[id_ubah]'");
					}


					/* foreach($addrinci as $ar){
				$hrrin = preg_replace("/[^0-9]/","", $ar['harga_asli']);	
				$jmrin = preg_replace("/[^0-9]/","", $ar['jumlah']);	
				$hrg2 = $hrrin/$jmrin;
				
				mysql_query("INSERT INTO masuk_detail_rinci(id_masuk_detail_rinci, id_masuk_detail, 
															id_barang, jumlah, harga, 
															create_date, creator_id)
													VALUES (UUID(), '$ar[idd]', '$ar[id_bar]', 
															'$jmrin', '$hrg2',
															'$datime', '$pengguna')");
			} */

					/* foreach($delrinci as $kd => $dn){
				foreach($dn as $dit){
					mysql_query("UPDATE masuk_detail_rinci SET soft_delete = '1' WHERE id_masuk_detail = '$kd' AND id_masuk_detail_rinci = '$dit[id]'");
				}
			}
			
			foreach($delrinciall as $da){
				mysql_query("UPDATE masuk_detail_rinci SET soft_delete = '1' WHERE id_masuk_detail = '$da[idd]'");
			}
			 */
					if (mysql_errno() == 0) {
						echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil diubah !"));
					} else {
						if (mysql_errno() == 1062) {
							echo json_encode(array(
								'success' => false,
								'pesan' => "Kode Barang Sudah Ada di Unit ini !",
								'error' => "nomor_sama"
							));
						} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil mengubah data ! " . mysql_errno()));
					}
					break;
				case 'del':
					//$databarmas = mysql_query("SELECT m.id_masuk_detail, (m.jml_masuk*m.harga_masuk) AS total, m.uuid_skpd, m.id_barang,
					//IF((m.id_kelompok=3) OR (m.id_kelompok=4), 
					//IF((SELECT SUM(jml_in-jml_out) FROM kartu_stok k 
					//WHERE k.id_barang = m.id_barang AND k.uuid_skpd = m.uuid_skpd 
					//AND (k.id_kelompok = 3 OR k.id_kelompok = 4) AND k.soft_delete = 0 
					//AND m.id_masuk_detail <> k.id_transaksi_detail )>0, 'update', 'delete')
					//, 'delete') AS aksi
					//FROM masuk_detail m
					//WHERE m.id_masuk = '$_POST[id_hapus]' AND m.soft_delete = 0 ");
					$databarmas = mysql_query("SELECT m.id_masuk_detail, (m.jml_masuk*m.harga_masuk) AS total, m.uuid_skpd, m.id_barang,
									  'delete' AS aksi
									FROM masuk_detail m
									WHERE m.id_masuk = '$_POST[id_hapus]' AND m.soft_delete = 0 ");
					while ($dbm = mysql_fetch_assoc($databarmas)) {
						//if($dbm['aksi']=='delete'){
						mysql_query("UPDATE kartu_stok SET soft_delete = '1' WHERE id_transaksi = '$_POST[id_hapus]' AND id_transaksi_detail = '$dbm[id_masuk_detail]'");
						//}elseif($dbm['aksi']=='update'){
						//	mysql_query("UPDATE kartu_stok SET harga = harga - '$dbm[total]' WHERE id_barang = '$dbm[id_barang]' 
						//				AND uuid_skpd = '$dbm[uuid_skpd]' AND jml_in = 1 ORDER BY tgl_transaksi LIMIT 1");
						//mysql_query("UPDATE kartu_stok SET soft_delete = '1' WHERE id_transaksi = '$_POST[id_hapus]'");			
						//}
					}
					mysql_query("UPDATE masuk SET soft_delete = '1' WHERE id_masuk = '$_POST[id_hapus]'");
					mysql_query("UPDATE masuk_detail d LEFT JOIN masuk_detail_rinci r ON r.id_masuk_detail = d.id_masuk_detail
						SET d.soft_delete = '1', r.soft_delete = '1' WHERE d.id_masuk = '$_POST[id_hapus]'");
					if (mysql_errno() == 0) {
						echo "Data berhasil dihapus !";
					} else {
						echo "Data tidak berhasil dihapus !";
					}
					break;
			}
		} else {
			echo json_encode(array('success' => false, 'pesan' => " Entri Anda Dikunci s/d " . $gl["kunci_sampai"]));
		}
	} elseif ($module == 'pengadaan_baru') {
		//if(isset($_REQUEST['id_sub']))$uid_skpd = $_REQUEST['id_sub'];
		if (isset($_REQUEST['form'])) $form = $_REQUEST['form'];
		if (isset($_REQUEST['ubahform'])) $ubahform = $_REQUEST['ubahform'];
		if (isset($_REQUEST['basket']['rows'])) $basket = $_REQUEST['basket']['rows'];
		else $basket = array();
		if (isset($_REQUEST['basrinci'])) $basrinci = $_REQUEST['basrinci'];
		else $basrinci = array();
		if (isset($form['id_sub'])) $uid_skpd = $form['id_sub'];
		if (isset($form['nama_pengadaan'])) $nama_pengadaan = $form['nama_pengadaan'];
		if (isset($form['id_sumber'])) $id_sumber = $form['id_sumber'];
		if (isset($form['ta'])) $ta = $form['ta'];
		if (isset($form['nama_penyedia'])) $nama_penyedia = $form['nama_penyedia'];
		if (isset($form['no_kontrak'])) $no_kontrak = $form['no_kontrak'];
		if (isset($form['tgl_pengadaan'])) $tgl_pengadaan = balikTanggal($form['tgl_pengadaan']);
		if (isset($form['kd_awal'])) $kd_skpd = $form['kd_awal'];
		if (isset($form['kd_prog'])) $kd_prog = $form['kd_prog'];
		if (isset($form['id_prog'])) $id_prog = $form['id_prog'];
		if (isset($form['kd_keg'])) $kd_keg = $form['kd_keg'];
		if (isset($form['kd_rek_1'])) $kd_rek_1 = $form['kd_rek_1'];
		if (isset($form['kd_rek_2'])) $kd_rek_2 = $form['kd_rek_2'];
		if (isset($form['kd_rek_3'])) $kd_rek_3 = $form['kd_rek_3'];
		if (isset($form['kd_rek_4'])) $kd_rek_4 = $form['kd_rek_4'];
		if (isset($form['kd_rek_5'])) $kd_rek_5 = $form['kd_rek_5'];
		if (isset($form['no_rinc'])) $no_rinc = $form['no_rinc'];
		if (isset($form['id_gud'])) $id_gud = $form['id_gud'];
		if (isset($form['no_pembayaran'])) $no_pembayaran = $form['no_pembayaran'];
		if (isset($form['tgl_pembayaran'])) $tgl_pembayaran = balikTanggal($form['tgl_pembayaran']);
		if (isset($form['sp'])) $sp = $form['sp'];
		if (isset($form['tgl_penerimaan'])) $tgl_penerimaan = balikTanggal($form['tgl_penerimaan']);

		if (isset($form['tgl_pemeriksaan'])) $tgl_pemeriksaan = balikTanggal($form['tgl_pemeriksaan']);
		if (isset($form['no_ba_pemeriksaan'])) $no_ba_pemeriksaan = $form['no_ba_pemeriksaan'];

		if (isset($form['tgl_penerimaan'])) $tgl_penerimaan = balikTanggal($form['tgl_penerimaan']);
		if (isset($form['no_ba_penerimaan'])) $no_ba_penerimaan = $form['no_ba_penerimaan'];
		if (isset($form['tgl_dok_penerimaan'])) $tgl_dok_penerimaan = balikTanggal($form['tgl_dok_penerimaan']);
		if (isset($form['no_dok_penerimaan'])) $no_dok_penerimaan = $form['no_dok_penerimaan'];
		if (isset($form['id_gudang'])) $id_gudang = $form['id_gudang'];
		if (isset($form['id_masuk'])) $id_masuk = $form['id_masuk'];
		if (isset($form['id_kelompok'])) $id_kelompok = $form['id_kelompok'];
		if (isset($form['id_rekening'])) $id_rekening = $form['id_rekening'];
		if (isset($form['id_sub_rekening'])) $id_sub_rekening = $form['id_sub_rekening'];
		//if (isset($form['nama_pengadaan'])) $nama_pengadaan = $form['nama_pengadaan'];


		$qq = mysql_fetch_assoc(mysql_query("SELECT CONCAT_WS('.',kd_urusan, kd_bidang, kd_unit) AS kds FROM ref_sub2_unit WHERE nm_sub2_unit = '$_SESSION[nm_sub2_unit]' "));
		$lock = mysql_query("SELECT * FROM kunci_entri_skpd WHERE kd_skpd = '$qq[kds]' ");
		$jumlock = mysql_num_rows($lock);
		if ($jumlock > 0) {
			$gl = mysql_fetch_assoc($lock);

			if ($oper == "del") {
				$tgl_pembayaran = balikTanggal($_POST["tgl_pembayaran"]);
			} else if ($oper == "edit") {
				$tgl_pembayaran = balikTanggal($form['tgl_pembayaran']);
			}
			$tga = strtotime($tgl_pembayaran);
			$tgb = strtotime($gl["kunci_sampai"]);
			$gl["kunci_sampai"] = balikTanggalIndo($gl["kunci_sampai"]);
			if ($tga <= $tgb) {
				// $allow = 0;
				$allow = 1;
			} else {
				$allow = 1;
			}
		} else {
			$allow = 1;
			$gl["kunci_sampai"] = "";
		}

		if ($allow == 1) {

			$nama_pengadaan = mysql_escape_string($nama_pengadaan);

			switch ($oper) {
				case 'add':
					// $pscek = "";
					// foreach($basket AS $cval){
					// $cid_bar = $cval['id_bar'];
					// $cid_kel = $cval['id_kel'];
					// $cid_kel = $id_kelompok;
					// if($cid_kel==1 OR $cid_kel==2){ $cekkel1 = 3; $cekkel2 = 4; } else { $cekkel1 = 1; $cekkel2 = 2; }
					// $qcek = mysql_fetch_assoc(mysql_query("SELECT SUM(jml_in-jml_out) AS jml FROM kartu_stok k
					// WHERE k.id_barang = '$cid_bar' AND k.uuid_skpd = '$uid_skpd'
					// AND (k.id_kelompok = '$cekkel1' OR k.id_kelompok = '$cekkel2')
					// AND k.soft_delete = 0"));
					// if($qcek['jml']!=0){
					// $pscek = "Error";
					// }
					// }	
					// if($pscek!=""){
					// echo json_encode(array('success'=>false, 'pesan'=>"Tidak berhasil memasukkan data, Kelompok Barang Salah!"));
					// break;
					// }

					/* print_r($basket);
			print_r($basrinci);
			foreach($basket AS $val){
				if(isset($basrinci[$val['idbas']])){
					echo $val['idbas'];
				}
			}
			break; */

					$cektgl = mysql_fetch_assoc(mysql_query("SELECT MAX(tgl_penerimaan) AS tgl FROM masuk WHERE uuid_skpd = '$uid_skpd' AND soft_delete = 0"));
					$ct = balikTanggalIndo($cektgl["tgl"]);
					if ($tgl_penerimaan < $cektgl["tgl"]) {
						echo json_encode(array('success' => false, 'pesan' => "Entri Tanggal tidak bisa mundur, Terakhir Tanggal $ct "));
						break;
					}

					$cektgl2 = mysql_fetch_assoc(mysql_query("SELECT MAX(tgl_ba_out) AS tgl FROM keluar WHERE uuid_skpd = '$uid_skpd' AND soft_delete = 0"));
					$ct2 = balikTanggalIndo($cektgl2["tgl"]);
					if ($tgl_penerimaan < $cektgl2["tgl"]) {
						echo json_encode(array('success' => false, 'pesan' => "Entri Tanggal tidak bisa mundur dari Tanggal Keluar, Terakhir Keluar Tanggal $ct2 "));
						break;
					}


					$ceknota = mysql_fetch_assoc(mysql_query("SELECT id_masuk, no_pembayaran FROM masuk WHERE uuid_skpd = '$uid_skpd' AND no_pembayaran = '$no_pembayaran' AND soft_delete = 0"));

					if ($ceknota["no_pembayaran"] == $no_pembayaran) {

						$totm = mysql_fetch_assoc(mysql_query("SELECT SUM(jml_in*harga) AS total FROM kartu_stok WHERE id_transaksi = '$ceknota[id_masuk]' AND soft_delete = 0"));
						$totm["total"] = number_format($totm['total'], 2, ',', '.');
						echo json_encode(array('success' => false, 'pesan' => "Nota <b>$no_pembayaran</b> Sudah Dientri dengan Nilai <b>$totm[total]</b> !"));
						break;
					}

					$u = mysql_fetch_row(mysql_query("SELECT UUID()"));
					$uuid = $u[0];
					mysql_query("INSERT INTO masuk ( id_masuk, uuid_skpd, kd_skpd,
												kd_prog, id_prog, kd_keg, kd_rek_1, kd_rek_2, kd_rek_3, kd_rek_4, 
												kd_rek_5, no_rinc, ta, 
												nama_pengadaan, nama_penyedia, tgl_pengadaan, no_kontrak,
												tgl_pembayaran, no_pembayaran, id_sumber, status_proses, tgl_pemeriksaan, no_ba_pemeriksaan, 
												tgl_penerimaan, no_ba_penerimaan, tgl_dok_penerimaan, no_dok_penerimaan, id_gudang,
												create_date, 
												creator_id,
												soft_delete) 
										VALUES ('$uuid', '$uid_skpd', '$kd_skpd',
												'$kd_prog', '$id_prog', '$kd_keg', '$kd_rek_1', '$kd_rek_2', '$kd_rek_3', '$kd_rek_4',
												'$kd_rek_5', '$no_rinc', '$ta',
												'$nama_pengadaan', '$nama_penyedia', '$tgl_pengadaan', '$no_kontrak',
												'$tgl_pembayaran', '$no_pembayaran', '$id_sumber', '3', '$tgl_pemeriksaan', '$no_ba_pemeriksaan',
												'$tgl_penerimaan', '$no_ba_penerimaan', '$tgl_dok_penerimaan', '$no_dok_penerimaan', '$id_gudang',
												'$datime',
												'$pengguna',
												'0')");

					if (mysql_errno() == 0) {
						$urut = 1;
						foreach ($basket as $val) {
							/* $harga = preg_replace("/[^0-9]/","", $val['harga_asli']);	
					$jumlah = preg_replace("/[^0-9]/","", $val['jumlah']);	 */
							$harga = $val['harga_asli'];
							$jumlah = $val['jumlah'];


							//$harga = str_replace(".","",$harga1);
							$jumlah = str_replace(".", "", $jumlah);
							$jumlah = str_replace(",", ".", $jumlah);

							$hrgsat = $harga / $jumlah;
							$ud = mysql_fetch_row(mysql_query("SELECT UUID()"));
							$uuidet = $ud[0];
							mysql_query("INSERT INTO masuk_detail ( id_masuk_detail, id_masuk, uuid_skpd,
															ta, id_kelompok, id_rek, id_subrek,
															id_barang, jml_masuk, 
															harga_masuk, keterangan, tahun,
															create_date, 
															creator_id,
															soft_delete)
													VALUES( '$uuidet', '$uuid', '$uid_skpd', 
															'$ta', '$id_kelompok', '$id_rekening', '$id_sub_rekening',
															'$val[id_bar]', '$jumlah', 
															'$hrgsat', '$val[ket]', '$val[tahun]',
															'$datime',
															'$pengguna',
															'0')");
							mysql_query("INSERT INTO kartu_stok (id_stok, uuid_skpd, id_barang, id_kelompok, id_gudang, 
																id_sumber_dana, id_transaksi, id_transaksi_detail,
																tgl_transaksi, ta, jml_in, jml_out, harga, kode,
																create_date, soft_delete, creator_id, urut)
														VALUES	(UUID(), '$uid_skpd', '$val[id_bar]', '$id_kelompok','$id_gudang', 
																'$id_sumber', '$uuid', '$uuidet',
																'$tgl_penerimaan', '$ta', '$jumlah', 0, '$hrgsat', 'i',
																'$datime', 0, '$pengguna', '$urut')");
							/* 	mysql_query("INSERT INTO kartu_stok (id_stok, uuid_skpd, id_barang, id_kelompok, id_gudang, 
																id_sumber_dana, id_transaksi, id_transaksi_detail,
																tgl_transaksi, ta, jml_in, jml_out, harga, kode,
																create_date, soft_delete, creator_id)
														VALUES	(UUID(), '$uid_skpd', '$val[id_bar]', '$id_kelompok','$id_gudang', 
																'$id_sumber', '$uuid', '$uuidet',
																'$tgl_penerimaan', '$ta', '$jumlah', 0, '$hrgsat', 'i',
																'$datime', 0, '$pengguna')"); */


							if (isset($basrinci[$val['idbas']])) {
								$rincian = $basrinci[$val['idbas']]['rows'];
								foreach ($rincian as $rin) {
									/* $hrgrin = preg_replace("/[^0-9]/","", $rin['harga_asli']);	
							$jmlrin = preg_replace("/[^0-9]/","", $rin['jumlah']);	 */

									$hrgrin = $rin['harga_asli'];
									$jmlrin = $rin['jumlah'];
									$jmlrin = str_replace(".", "", $jmlrin);
									$jmlrin = str_replace(",", ".", $jmlrin);
									$hrg1 = $hrgrin / $jmlrin;
									$tgl_detail = balikTanggal($rin['tgl_detail']);

									mysql_query("INSERT INTO masuk_detail_rinci(id_masuk_detail_rinci, id_masuk_detail, 
																		id_barang, jumlah, harga, 
																		create_date, creator_id, tgl_masuk_rinci)
																VALUES (UUID(), '$uuidet', '$rin[id_bar]', 
																		'$jmlrin', '$hrg1',
																		'$datime', '$pengguna', '$tgl_detail')");
								}
							}
							mysql_query("UPDATE log_import SET jumlah_barang_isi
							WHERE uuid_skpd='$uid_skpd' AND nm_kegiatan='$nama_pengadaan'");
							$urut++;
						}

						echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil dimasukkan ! "));
					} else {
						if (mysql_errno() == 1062) {
							echo json_encode(array(
								'success' => false,
								'pesan' => "Kode Barang Sudah Ada !",
								'error' => "nomor_sama"
							));
						} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil memasukkan data !"));
					}


					break;

				case 'edit':
					if (isset($_REQUEST['form'])) $form = $_REQUEST['form'];
					if (isset($form['id_masuk'])) $form = $form['id_masuk'];

					$e = mysql_query("UPDATE masuk SET uuid_skpd='$uid_skpd', kd_skpd='$kd_skpd', kd_prog='$kd_prog', id_prog='$id_prog', kd_keg='$kd_keg', kd_rek_1='$kd_rek_1', kd_rek_2='$kd_rek_2', kd_rek_3='$kd_rek_3', kd_rek_4='$kd_rek_4', kd_rek_5='$kd_rek_5', no_rinc='$no_rinc', ta='$ta', nama_pengadaan='$nama_pengadaan', nama_penyedia='$nama_penyedia', tgl_pengadaan='$tgl_pengadaan', no_kontrak='$no_kontrak', tgl_pembayaran='$tgl_pembayaran', no_pembayaran='$no_pembayaran', id_sumber='$id_sumber', tgl_pemeriksaan='$tgl_pemeriksaan', no_ba_pemeriksaan='$no_ba_pemeriksaan', tgl_penerimaan='$tgl_penerimaan', no_ba_penerimaan='$no_ba_penerimaan', no_dok_penerimaan='$no_dok_penerimaan', tgl_dok_penerimaan='$tgl_dok_penerimaan', id_gudang='$id_gudang', update_date=NOW(),  creator_id='$pengguna' WHERE id_masuk = '$id_masuk'");

					if (mysql_errno() == 0) {
						$sel = mysql_query("SELECT id_masuk_detail FROM masuk_detail WHERE id_masuk = '$id_masuk' ");
						while ($rsel = mysql_fetch_assoc($sel)) {
							$del2 = mysql_query("DELETE FROM masuk_detail_rinci WHERE id_masuk_detail = '$sel[id_masuk_detail]'");
						}
						$del1 = mysql_query("DELETE FROM kartu_stok WHERE id_transaksi = '$id_masuk'");
						$del3 = mysql_query("DELETE FROM masuk_detail WHERE id_masuk = '$id_masuk'");

						foreach ($basket as $val) {
							/* $harga = preg_replace("/[^0-9]/","", $val['harga_asli']);	
					$jumlah = preg_replace("/[^0-9]/","", $val['jumlah']);	 */

							$harga = $val['harga_asli'];
							$jumlah = $val['jumlah'];
							$jumlah = str_replace(".", "", $jumlah);
							$jumlah = str_replace(",", ".", $jumlah);
							$hrgsat = $harga / $jumlah;
							$ud = mysql_fetch_row(mysql_query("SELECT UUID()"));
							$uuidet = $ud[0];

							mysql_query("INSERT INTO masuk_detail ( id_masuk_detail, id_masuk, uuid_skpd,
															ta, id_kelompok,
															id_rek, id_subrek,
															id_barang, jml_masuk, 
															harga_masuk, keterangan, tahun,
															create_date, creator_id, soft_delete)
													VALUES( '$uuidet', '$id_masuk', '$uid_skpd', 
															'$ta', '$id_kelompok',
															'$id_rekening', '$id_sub_rekening',
															'$val[id_bar]', '$jumlah', 
															'$hrgsat', '$val[ket]', '$val[tahun]',
															'$datime',
															'$pengguna',
															'0')") or die(mysql_error());
							mysql_query("INSERT INTO kartu_stok (id_stok, uuid_skpd, id_barang, id_kelompok, id_gudang, 
															id_sumber_dana, id_transaksi, id_transaksi_detail,
															tgl_transaksi, ta, jml_in, jml_out, harga, kode,
															create_date, soft_delete, creator_id)
													VALUES	(UUID(), '$uid_skpd', '$val[id_bar]', '$id_kelompok','$id_gudang', 
															'$id_sumber', '$id_masuk', '$uuidet',
															'$tgl_penerimaan', '$ta', '$jumlah', 0, '$hrgsat', 'i',
															'$datime', 0, '$pengguna')");


							if (isset($basrinci[$val['idbas']])) {
								$rincian = $basrinci[$val['idbas']]['rows'];
								foreach ($rincian as $rin) {
									/* $hrgrin = preg_replace("/[^0-9]/","", $rin['harga_asli']);	
							$jmlrin = preg_replace("/[^0-9]/","", $rin['jumlah']); */
									$hrgrin = $rin['harga_asli'];
									$jmlrin = $rin['jumlah'];
									$hrg1 = $hrgrin / $jmlrin;
									$tgl_detail = balikTanggal($rin['tgl_detail']);

									mysql_query("INSERT INTO masuk_detail_rinci(id_masuk_detail_rinci, id_masuk_detail, 
																		id_barang, jumlah, harga, 
																		create_date, creator_id, tgl_masuk_rinci)
																VALUES (UUID(), '$uuidet', '$rin[id_bar]', 
																		'$jmlrin', '$hrg1',
																		'$datime', '$pengguna', '$tgl_detail')");
								}
							}
						}

						echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil dimasukkan !"));
					} else {
						if (mysql_errno() == 1062) {
							echo json_encode(array(
								'success' => false,
								'pesan' => "Kode Barang Sudah Ada !",
								'error' => "nomor_sama"
							));
						} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil memasukkan data !"));
					}
					break;
				case 'del':
					//$databarmas = mysql_query("SELECT m.id_masuk_detail, (m.jml_masuk*m.harga_masuk) AS total, m.uuid_skpd, m.id_barang,
					//IF((m.id_kelompok=3) OR (m.id_kelompok=4), 
					//IF((SELECT SUM(jml_in-jml_out) FROM kartu_stok k 
					//WHERE k.id_barang = m.id_barang AND k.uuid_skpd = m.uuid_skpd 
					//AND (k.id_kelompok = 3 OR k.id_kelompok = 4) AND k.soft_delete = 0 
					//AND m.id_masuk_detail <> k.id_transaksi_detail )>0, 'update', 'delete')
					//, 'delete') AS aksi
					//FROM masuk_detail m
					//WHERE m.id_masuk = '$_POST[id_hapus]' AND m.soft_delete = 0 ");
					$databarmas = mysql_query("SELECT m.id_masuk_detail, (m.jml_masuk*m.harga_masuk) AS total, m.uuid_skpd, m.id_barang,
									  'delete' AS aksi
									FROM masuk_detail m
									WHERE m.id_masuk = '$_POST[id_hapus]' AND m.soft_delete = 0 ");
					while ($dbm = mysql_fetch_assoc($databarmas)) {
						//if($dbm['aksi']=='delete'){
						mysql_query("UPDATE kartu_stok SET soft_delete = '1' WHERE id_transaksi = '$_POST[id_hapus]' AND id_transaksi_detail = '$dbm[id_masuk_detail]'");
						//}elseif($dbm['aksi']=='update'){
						//	mysql_query("UPDATE kartu_stok SET harga = harga - '$dbm[total]' WHERE id_barang = '$dbm[id_barang]' 
						//				AND uuid_skpd = '$dbm[uuid_skpd]' AND jml_in = 1 ORDER BY tgl_transaksi LIMIT 1");
						//mysql_query("UPDATE kartu_stok SET soft_delete = '1' WHERE id_transaksi = '$_POST[id_hapus]'");			
						//}
					}
					mysql_query("UPDATE masuk SET soft_delete = '1' WHERE id_masuk = '$_POST[id_hapus]'");
					mysql_query("UPDATE masuk_detail d LEFT JOIN masuk_detail_rinci r ON r.id_masuk_detail = d.id_masuk_detail
						SET d.soft_delete = '1', r.soft_delete = '1' WHERE d.id_masuk = '$_POST[id_hapus]'");
					if (mysql_errno() == 0) {

						mysql_query(" INSERT INTO log_del VALUES(UUID(), NOW(), '2019', '$pengguna', '', 'masuk', '$_POST[id_hapus]', 'hapus', '', '') ");

						echo "Data berhasil dihapus !";
					} else {
						echo "Data tidak berhasil dihapus !";
					}
					break;
				case 'softdel':
					mysql_query("UPDATE kartu_stok SET soft_delete = '1' WHERE id_transaksi = '$_POST[id_hapus]'");
					mysql_query("UPDATE masuk SET soft_delete = '1' WHERE id_masuk = '$_POST[id_hapus]'");
					mysql_query("UPDATE masuk_detail d LEFT JOIN masuk_detail_rinci r ON r.id_masuk_detail = d.id_masuk_detail
						SET d.soft_delete = '1', r.soft_delete = '1' WHERE d.id_masuk = '$_POST[id_hapus]'");
					if (mysql_errno() == 0) {
						echo "Data berhasil dihapus !";

						mysql_query(" INSERT INTO log_del VALUES(UUID(), NOW(), '2019', '$pengguna', '', 'masuk', '$_POST[id_hapus]', 'hapus', '', '') ");
					} else {
						echo "Data tidak berhasil dihapus !";
					}
					break;
			}
		} else {
			echo json_encode(array('success' => false, 'pesan' => " Entri Anda Dikunci s/d " . $gl["kunci_sampai"] . " | " . $qq[kds]));
		}
	}elseif ($module == 'pengadaan_baru2') {
		//if(isset($_REQUEST['id_sub']))$uid_skpd = $_REQUEST['id_sub'];
		if (isset($_REQUEST['form'])) $form = $_REQUEST['form'];
		if (isset($_REQUEST['ubahform'])) $ubahform = $_REQUEST['ubahform'];
		if (isset($_REQUEST['basket']['rows'])) $basket = $_REQUEST['basket']['rows'];
		else $basket = array();
		if (isset($_REQUEST['basrinci'])) $basrinci = $_REQUEST['basrinci'];
		else $basrinci = array();
		if (isset($form['id_sub'])) $uid_skpd = $form['id_sub'];
		if (isset($form['nama_pengadaan'])) $nama_pengadaan = $form['nama_pengadaan'];
		if (isset($form['id_kegiatan'])) $nama_pengadaan1 = $form['id_kegiatan'];
		if (isset($form['id_sumber'])) $id_sumber = $form['id_sumber'];
		if (isset($form['ta'])) $ta = $form['ta'];
		if (isset($form['nama_penyedia'])) $nama_penyedia = $form['nama_penyedia'];
		if (isset($form['no_kontrak'])) $no_kontrak = $form['no_kontrak'];
		if (isset($form['tgl_pengadaan'])) $tgl_pengadaan = balikTanggal($form['tgl_pengadaan']);
		if (isset($form['kd_awal'])) $kd_skpd = $form['kd_awal'];
		if (isset($form['kd_prog'])) $kd_prog = $form['kd_prog'];
		if (isset($form['id_prog'])) $id_prog = $form['id_prog'];
		if (isset($form['kd_keg'])) $kd_keg = $form['kd_keg'];
		if (isset($form['kd_rek_1'])) $kd_rek_1 = $form['kd_rek_1'];
		if (isset($form['kd_rek_2'])) $kd_rek_2 = $form['kd_rek_2'];
		if (isset($form['kd_rek_3'])) $kd_rek_3 = $form['kd_rek_3'];
		if (isset($form['kd_rek_4'])) $kd_rek_4 = $form['kd_rek_4'];
		if (isset($form['kd_rek_5'])) $kd_rek_5 = $form['kd_rek_5'];
		if (isset($form['no_rinc'])) $no_rinc = $form['no_rinc'];
		if (isset($form['id_gud'])) $id_gud = $form['id_gud'];
		if (isset($form['no_pembayaran'])) $no_pembayaran = $form['no_pembayaran'];
		if (isset($form['tgl_pembayaran'])) $tgl_pembayaran = balikTanggal($form['tgl_pembayaran']);
		if (isset($form['sp'])) $sp = $form['sp'];
		if (isset($form['tgl_penerimaan'])) $tgl_penerimaan = balikTanggal($form['tgl_penerimaan']);

		if (isset($form['tgl_pemeriksaan'])) $tgl_pemeriksaan = balikTanggal($form['tgl_pemeriksaan']);
		if (isset($form['no_ba_pemeriksaan'])) $no_ba_pemeriksaan = $form['no_ba_pemeriksaan'];

		if (isset($form['tgl_penerimaan'])) $tgl_penerimaan = balikTanggal($form['tgl_penerimaan']);
		if (isset($form['no_ba_penerimaan'])) $no_ba_penerimaan = $form['no_ba_penerimaan'];
		if (isset($form['tgl_dok_penerimaan'])) $tgl_dok_penerimaan = balikTanggal($form['tgl_dok_penerimaan']);
		if (isset($form['no_dok_penerimaan'])) $no_dok_penerimaan = $form['no_dok_penerimaan'];
		if (isset($form['id_gudang'])) $id_gudang = $form['id_gudang'];
		if (isset($form['id_masuk'])) $id_masuk = $form['id_masuk'];
		if (isset($form['id_kelompok'])) $id_kelompok = $form['id_kelompok'];
		if (isset($form['id_rekening'])) $id_rekening = $form['id_rekening'];
		if (isset($form['id_sub_rekening'])) $id_sub_rekening = $form['id_sub_rekening'];


		$qq = mysql_fetch_assoc(mysql_query("SELECT CONCAT_WS('.',kd_urusan, kd_bidang, kd_unit) AS kds FROM ref_sub2_unit WHERE nm_sub2_unit = '$_SESSION[nm_sub2_unit]' "));
		$lock = mysql_query("SELECT * FROM kunci_entri_skpd WHERE kd_skpd = '$qq[kds]' ");
		$jumlock = mysql_num_rows($lock);
		if ($jumlock > 0) {
			$gl = mysql_fetch_assoc($lock);

			if ($oper == "del") {
				$tgl_pembayaran = balikTanggal($_POST["tgl_pembayaran"]);
			} else if ($oper == "edit") {
				$tgl_pembayaran = balikTanggal($form['tgl_pembayaran']);
			}
			$tga = strtotime($tgl_pembayaran);
			$tgb = strtotime($gl["kunci_sampai"]);
			$gl["kunci_sampai"] = balikTanggalIndo($gl["kunci_sampai"]);
			if ($tga <= $tgb) {
				// $allow = 0;
				$allow = 1;
			} else {
				$allow = 1;
			}
		} else {
			$allow = 1;
			$gl["kunci_sampai"] = "";
		}

		if ($allow == 1) {

			$nama_pengadaan = mysql_escape_string($nama_pengadaan);

			switch ($oper) {
				case 'add':
					// $pscek = "";
					// foreach($basket AS $cval){
					// $cid_bar = $cval['id_bar'];
					// $cid_kel = $cval['id_kel'];
					// $cid_kel = $id_kelompok;
					// if($cid_kel==1 OR $cid_kel==2){ $cekkel1 = 3; $cekkel2 = 4; } else { $cekkel1 = 1; $cekkel2 = 2; }
					// $qcek = mysql_fetch_assoc(mysql_query("SELECT SUM(jml_in-jml_out) AS jml FROM kartu_stok k
					// WHERE k.id_barang = '$cid_bar' AND k.uuid_skpd = '$uid_skpd'
					// AND (k.id_kelompok = '$cekkel1' OR k.id_kelompok = '$cekkel2')
					// AND k.soft_delete = 0"));
					// if($qcek['jml']!=0){
					// $pscek = "Error";
					// }
					// }	
					// if($pscek!=""){
					// echo json_encode(array('success'=>false, 'pesan'=>"Tidak berhasil memasukkan data, Kelompok Barang Salah!"));
					// break;
					// }

					/* print_r($basket);
			print_r($basrinci);
			foreach($basket AS $val){
				if(isset($basrinci[$val['idbas']])){
					echo $val['idbas'];
				}
			}
			break; */

					$cektgl = mysql_fetch_assoc(mysql_query("SELECT MAX(tgl_penerimaan) AS tgl FROM masuk WHERE uuid_skpd = '$uid_skpd' AND soft_delete = 0"));
					$ct = balikTanggalIndo($cektgl["tgl"]);
					if ($tgl_penerimaan < $cektgl["tgl"]) {
						echo json_encode(array('success' => false, 'pesan' => "Entri Tanggal tidak bisa mundur, Terakhir Tanggal $ct "));
						break;
					}

					$cektgl2 = mysql_fetch_assoc(mysql_query("SELECT MAX(tgl_ba_out) AS tgl FROM keluar WHERE uuid_skpd = '$uid_skpd' AND soft_delete = 0"));
					$ct2 = balikTanggalIndo($cektgl2["tgl"]);
					if ($tgl_penerimaan < $cektgl2["tgl"]) {
						echo json_encode(array('success' => false, 'pesan' => "Entri Tanggal tidak bisa mundur dari Tanggal Keluar, Terakhir Keluar Tanggal $ct2 "));
						break;
					}


					$ceknota = mysql_fetch_assoc(mysql_query("SELECT id_masuk, no_pembayaran FROM masuk WHERE uuid_skpd = '$uid_skpd' AND no_pembayaran = '$no_pembayaran' AND soft_delete = 0"));

					if ($ceknota["no_pembayaran"] == $no_pembayaran) {

						$totm = mysql_fetch_assoc(mysql_query("SELECT SUM(jml_in*harga) AS total FROM kartu_stok WHERE id_transaksi = '$ceknota[id_masuk]' AND soft_delete = 0"));
						$totm["total"] = number_format($totm['total'], 2, ',', '.');
						echo json_encode(array('success' => false, 'pesan' => "Nota <b>$no_pembayaran</b> Sudah Dientri dengan Nilai <b>$totm[total]</b> !"));
						break;
					}

					$u = mysql_fetch_row(mysql_query("SELECT UUID()"));
					$uuid = $u[0];
					mysql_query("INSERT INTO masuk ( id_masuk, uuid_skpd, kd_skpd,
												kd_prog, id_prog, kd_keg, kd_rek_1, kd_rek_2, kd_rek_3, kd_rek_4, 
												kd_rek_5, no_rinc, ta, 
												nama_pengadaan, nama_penyedia, tgl_pengadaan, no_kontrak,
												tgl_pembayaran, no_pembayaran, id_sumber, status_proses, tgl_pemeriksaan, no_ba_pemeriksaan, 
												tgl_penerimaan, no_ba_penerimaan, tgl_dok_penerimaan, no_dok_penerimaan, id_gudang,
												create_date, 
												creator_id,
												soft_delete) 
										VALUES ('$uuid', '$uid_skpd', '$kd_skpd',
												'$kd_prog', '$id_prog', '$kd_keg', '$kd_rek_1', '$kd_rek_2', '$kd_rek_3', '$kd_rek_4',
												'$kd_rek_5', '$no_rinc', '$ta',
												'$nama_pengadaan', '$nama_penyedia', '$tgl_pengadaan', '$no_kontrak',
												'$tgl_pembayaran', '$no_pembayaran', '$id_sumber', '3', '$tgl_pemeriksaan', '$no_ba_pemeriksaan',
												'$tgl_penerimaan', '$no_ba_penerimaan', '$tgl_dok_penerimaan', '$no_dok_penerimaan', '$id_gudang',
												'$datime',
												'$pengguna',
												'0')");

					if (mysql_errno() == 0) {
						$urut = 1;
						foreach ($basket as $val) {
							/* $harga = preg_replace("/[^0-9]/","", $val['harga_asli']);	
					$jumlah = preg_replace("/[^0-9]/","", $val['jumlah']);	 */
							$harga = $val['harga_asli'];
							$jumlah = $val['jumlah'];


							//$harga = str_replace(".","",$harga1);
							$jumlah = str_replace(".", "", $jumlah);
							$jumlah = str_replace(",", ".", $jumlah);

							$hrgsat = $harga / $jumlah;
							$ud = mysql_fetch_row(mysql_query("SELECT UUID()"));
							$uuidet = $ud[0];
							mysql_query("INSERT INTO masuk_detail ( id_masuk_detail, id_masuk, uuid_skpd,
															ta, id_kelompok, id_rek, id_subrek,
															id_barang, jml_masuk, 
															harga_masuk, keterangan, tahun,
															create_date, 
															creator_id,
															soft_delete)
													VALUES( '$uuidet', '$uuid', '$uid_skpd', 
															'$ta', '$id_kelompok', '$id_rekening', '$id_sub_rekening',
															'$val[id_bar]', '$jumlah', 
															'$hrgsat', '$val[ket]', '$val[tahun]',
															'$datime',
															'$pengguna',
															'0')");
							mysql_query("INSERT INTO kartu_stok (id_stok, uuid_skpd, id_barang, id_kelompok, id_gudang, 
																id_sumber_dana, id_transaksi, id_transaksi_detail,
																tgl_transaksi, ta, jml_in, jml_out, harga, kode,
																create_date, soft_delete, creator_id, urut)
														VALUES	(UUID(), '$uid_skpd', '$val[id_bar]', '$id_kelompok','$id_gudang', 
																'$id_sumber', '$uuid', '$uuidet',
																'$tgl_penerimaan', '$ta', '$jumlah', 0, '$hrgsat', 'i',
																'$datime', 0, '$pengguna', '$urut')");
							/* 	mysql_query("INSERT INTO kartu_stok (id_stok, uuid_skpd, id_barang, id_kelompok, id_gudang, 
																id_sumber_dana, id_transaksi, id_transaksi_detail,
																tgl_transaksi, ta, jml_in, jml_out, harga, kode,
																create_date, soft_delete, creator_id)
														VALUES	(UUID(), '$uid_skpd', '$val[id_bar]', '$id_kelompok','$id_gudang', 
																'$id_sumber', '$uuid', '$uuidet',
																'$tgl_penerimaan', '$ta', '$jumlah', 0, '$hrgsat', 'i',
																'$datime', 0, '$pengguna')"); */


							if (isset($basrinci[$val['idbas']])) {
								$rincian = $basrinci[$val['idbas']]['rows'];
								foreach ($rincian as $rin) {
									/* $hrgrin = preg_replace("/[^0-9]/","", $rin['harga_asli']);	
							$jmlrin = preg_replace("/[^0-9]/","", $rin['jumlah']);	 */

									$hrgrin = $rin['harga_asli'];
									$jmlrin = $rin['jumlah'];
									$jmlrin = str_replace(".", "", $jmlrin);
									$jmlrin = str_replace(",", ".", $jmlrin);
									$hrg1 = $hrgrin / $jmlrin;
									$tgl_detail = balikTanggal($rin['tgl_detail']);

									mysql_query("INSERT INTO masuk_detail_rinci(id_masuk_detail_rinci, id_masuk_detail, 
																		id_barang, jumlah, harga, 
																		create_date, creator_id, tgl_masuk_rinci)
																VALUES (UUID(), '$uuidet', '$rin[id_bar]', 
																		'$jmlrin', '$hrg1',
																		'$datime', '$pengguna', '$tgl_detail')");
								}
							}
							mysql_query("UPDATE log_import SET jumlah_barang_isi=jumlah_barang_isi+'$jumlah'
							WHERE uuid_skpd='$uid_skpd' AND kd_kegiatan='$nama_pengadaan1' AND id_barang='$val[id_bar]' and status='1'");
							$urut++;
						}

						echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil dimasukkan ! "));
					} else {
						if (mysql_errno() == 1062) {
							echo json_encode(array(
								'success' => false,
								'pesan' => "Kode Barang Sudah Ada !",
								'error' => "nomor_sama"
							));
						} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil memasukkan data !"));
					}


					break;

				case 'edit':
					if (isset($_REQUEST['form'])) $form = $_REQUEST['form'];
					if (isset($form['id_masuk'])) $form = $form['id_masuk'];
					if (isset($form['id_kegiatan'])) $nama_pengadaan1 = $form['id_kegiatan'];

					$e = mysql_query("UPDATE masuk SET uuid_skpd='$uid_skpd', kd_skpd='$kd_skpd', kd_prog='$kd_prog', id_prog='$id_prog', kd_keg='$kd_keg', kd_rek_1='$kd_rek_1', kd_rek_2='$kd_rek_2', kd_rek_3='$kd_rek_3', kd_rek_4='$kd_rek_4', kd_rek_5='$kd_rek_5', no_rinc='$no_rinc', ta='$ta', nama_pengadaan='$nama_pengadaan', nama_penyedia='$nama_penyedia', tgl_pengadaan='$tgl_pengadaan', no_kontrak='$no_kontrak', tgl_pembayaran='$tgl_pembayaran', no_pembayaran='$no_pembayaran', id_sumber='$id_sumber', tgl_pemeriksaan='$tgl_pemeriksaan', no_ba_pemeriksaan='$no_ba_pemeriksaan', tgl_penerimaan='$tgl_penerimaan', no_ba_penerimaan='$no_ba_penerimaan', no_dok_penerimaan='$no_dok_penerimaan', tgl_dok_penerimaan='$tgl_dok_penerimaan', id_gudang='$id_gudang', update_date=NOW(),  creator_id='$pengguna' WHERE id_masuk = '$id_masuk'");

					if (mysql_errno() == 0) {
						$sel = mysql_query("SELECT id_masuk_detail FROM masuk_detail WHERE id_masuk = '$id_masuk' ");
						while ($rsel = mysql_fetch_assoc($sel)) {
							$del2 = mysql_query("DELETE FROM masuk_detail_rinci WHERE id_masuk_detail = '$sel[id_masuk_detail]'");
						}
						$del1 = mysql_query("DELETE FROM kartu_stok WHERE id_transaksi = '$id_masuk'");
						$del3 = mysql_query("DELETE FROM masuk_detail WHERE id_masuk = '$id_masuk'");

						foreach ($basket as $val) {
							/* $harga = preg_replace("/[^0-9]/","", $val['harga_asli']);	
					$jumlah = preg_replace("/[^0-9]/","", $val['jumlah']);	 */

							$harga = $val['harga_asli'];
							$jumlah = $val['jumlah'];
							$jumlah = str_replace(".", "", $jumlah);
							$jumlah = str_replace(",", ".", $jumlah);
							$hrgsat = $harga / $jumlah;
							$ud = mysql_fetch_row(mysql_query("SELECT UUID()"));
							$uuidet = $ud[0];

							mysql_query("INSERT INTO masuk_detail ( id_masuk_detail, id_masuk, uuid_skpd,
															ta, id_kelompok,
															id_rek, id_subrek,
															id_barang, jml_masuk, 
															harga_masuk, keterangan, tahun,
															create_date, creator_id, soft_delete)
													VALUES( '$uuidet', '$id_masuk', '$uid_skpd', 
															'$ta', '$id_kelompok',
															'$id_rekening', '$id_sub_rekening',
															'$val[id_bar]', '$jumlah', 
															'$hrgsat', '$val[ket]', '$val[tahun]',
															'$datime',
															'$pengguna',
															'0')") or die(mysql_error());
							mysql_query("INSERT INTO kartu_stok (id_stok, uuid_skpd, id_barang, id_kelompok, id_gudang, 
															id_sumber_dana, id_transaksi, id_transaksi_detail,
															tgl_transaksi, ta, jml_in, jml_out, harga, kode,
															create_date, soft_delete, creator_id)
													VALUES	(UUID(), '$uid_skpd', '$val[id_bar]', '$id_kelompok','$id_gudang', 
															'$id_sumber', '$id_masuk', '$uuidet',
															'$tgl_penerimaan', '$ta', '$jumlah', 0, '$hrgsat', 'i',
															'$datime', 0, '$pengguna')");
							mysql_query("UPDATE log_import SET jumlah_barang_isi='$jumlah'
							WHERE uuid_skpd='$uid_skpd' AND nm_kegiatan LIKE '%$nama_pengadaan%' AND id_barang='$val[id_bar]' and status='1'");
							$urut++;
							/* echo"UPDATE log_import SET jumlah_barang_isi=jumlah_barang_isi+'$jumlah'
							WHERE uuid_skpd='$uid_skpd' AND nm_kegiatan LIKE '%$nama_pengadaan%'  AND id_barang='$val[id_bar]' and status='1'"; */


							if (isset($basrinci[$val['idbas']])) {
								$rincian = $basrinci[$val['idbas']]['rows'];
								foreach ($rincian as $rin) {
									/* $hrgrin = preg_replace("/[^0-9]/","", $rin['harga_asli']);	
							$jmlrin = preg_replace("/[^0-9]/","", $rin['jumlah']); */
									$hrgrin = $rin['harga_asli'];
									$jmlrin = $rin['jumlah'];
									$hrg1 = $hrgrin / $jmlrin;
									$tgl_detail = balikTanggal($rin['tgl_detail']);

									mysql_query("INSERT INTO masuk_detail_rinci(id_masuk_detail_rinci, id_masuk_detail, 
																		id_barang, jumlah, harga, 
																		create_date, creator_id, tgl_masuk_rinci)
																VALUES (UUID(), '$uuidet', '$rin[id_bar]', 
																		'$jmlrin', '$hrg1',
																		'$datime', '$pengguna', '$tgl_detail')");
								}
							}
							
						}

						

						echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil dimasukkan !"));
					} else {
						if (mysql_errno() == 1062) {
							echo json_encode(array(
								'success' => false,
								'pesan' => "Kode Barang Sudah Ada !",
								'error' => "nomor_sama"
							));
						} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil memasukkan data !"));
					}
					break;
				case 'del':
					//$databarmas = mysql_query("SELECT m.id_masuk_detail, (m.jml_masuk*m.harga_masuk) AS total, m.uuid_skpd, m.id_barang,
					//IF((m.id_kelompok=3) OR (m.id_kelompok=4), 
					//IF((SELECT SUM(jml_in-jml_out) FROM kartu_stok k 
					//WHERE k.id_barang = m.id_barang AND k.uuid_skpd = m.uuid_skpd 
					//AND (k.id_kelompok = 3 OR k.id_kelompok = 4) AND k.soft_delete = 0 
					//AND m.id_masuk_detail <> k.id_transaksi_detail )>0, 'update', 'delete')
					//, 'delete') AS aksi
					//FROM masuk_detail m
					//WHERE m.id_masuk = '$_POST[id_hapus]' AND m.soft_delete = 0 ");
					$databarmas = mysql_query("SELECT m.id_masuk_detail, (m.jml_masuk*m.harga_masuk) AS total, m.uuid_skpd, m.id_barang,
									  'delete' AS aksi
									FROM masuk_detail m
									WHERE m.id_masuk = '$_POST[id_hapus]' AND m.soft_delete = 0 ");
					while ($dbm = mysql_fetch_assoc($databarmas)) {
						//if($dbm['aksi']=='delete'){
						mysql_query("UPDATE kartu_stok SET soft_delete = '1' WHERE id_transaksi = '$_POST[id_hapus]' AND id_transaksi_detail = '$dbm[id_masuk_detail]'");
						//}elseif($dbm['aksi']=='update'){
						//	mysql_query("UPDATE kartu_stok SET harga = harga - '$dbm[total]' WHERE id_barang = '$dbm[id_barang]' 
						//				AND uuid_skpd = '$dbm[uuid_skpd]' AND jml_in = 1 ORDER BY tgl_transaksi LIMIT 1");
						//mysql_query("UPDATE kartu_stok SET soft_delete = '1' WHERE id_transaksi = '$_POST[id_hapus]'");			
						//}
					}
					$datalog = mysql_query("SELECT m.uuid_skpd,m.id_barang,n.nama_pengadaan
									FROM masuk_detail m
									inner join masuk n on m.uuid_skpd=n.uuid_skpd and m.id_masuk=n.id_masuk
									WHERE m.id_masuk = '$_POST[id_hapus]' AND m.soft_delete = 0 ");
					while ($dblog = mysql_fetch_assoc($datalog)) {
						mysql_query("UPDATE log_import SET jumlah_barang_isi= 0
					WHERE uuid_skpd='$dblog[uuid_skpd]' AND nm_kegiatan like '%$dblog[nm_pengadaan]%' AND id_barang='$dblog[id_barang]'");
					
					}
					mysql_query("UPDATE masuk SET soft_delete = '1' WHERE id_masuk = '$_POST[id_hapus]'");
					mysql_query("UPDATE masuk_detail d LEFT JOIN masuk_detail_rinci r ON r.id_masuk_detail = d.id_masuk_detail
						SET d.soft_delete = '1', r.soft_delete = '1' WHERE d.id_masuk = '$_POST[id_hapus]'");
					mysql_query("UPDATE log_import SET jumlah_barang_isi=0
					WHERE uuid_skpd='$uid_skpd' AND nm_kegiatan LIKE '%$nama_pengadaan%' AND id_barang='$val[id_bar]' and status='1'");
					
					if (mysql_errno() == 0) {

						mysql_query(" INSERT INTO log_del VALUES(UUID(), NOW(), '2022', '$pengguna', '', 'masuk', '$_POST[id_hapus]', 'hapus', '', '') ");
						

						echo "Data berhasil dihapus !";
					} else {
						echo "Data tidak berhasil dihapus !";
					}
					break;
				case 'softdel':
					$datalog = mysql_query("SELECT m.uuid_skpd,m.id_barang,n.nama_pengadaan
										FROM masuk_detail m
										inner join masuk n on m.uuid_skpd=n.uuid_skpd and m.id_masuk=n.id_masuk
										WHERE m.id_masuk = '$_POST[id_hapus]' AND m.soft_delete = 0  ");
					while ($dblog = mysql_fetch_assoc($datalog)) {
						mysql_query("UPDATE log_import SET jumlah_barang_isi= 0
					WHERE uuid_skpd='$dblog[uuid_skpd]' AND nm_kegiatan like '%$dblog[nm_pengadaan]%' AND id_barang='$dblog[id_barang]'");
					
					}
					mysql_query("UPDATE kartu_stok SET soft_delete = '1' WHERE id_transaksi = '$_POST[id_hapus]'");
					mysql_query("UPDATE masuk SET soft_delete = '1' WHERE id_masuk = '$_POST[id_hapus]'");
					mysql_query("UPDATE masuk_detail d LEFT JOIN masuk_detail_rinci r ON r.id_masuk_detail = d.id_masuk_detail
						SET d.soft_delete = '1', r.soft_delete = '1' WHERE d.id_masuk = '$_POST[id_hapus]'");
					if (mysql_errno() == 0) {
						echo "Data berhasil dihapus !";

						mysql_query(" INSERT INTO log_del VALUES(UUID(), NOW(), '2019', '$pengguna', '', 'masuk', '$_POST[id_hapus]', 'hapus', '', '') ");
					} else {
						echo "Data tidak berhasil dihapus !";
					}
					break;
			}
		} else {
			echo json_encode(array('success' => false, 'pesan' => " Entri Anda Dikunci s/d " . $gl["kunci_sampai"] . " | " . $qq[kds]));
		}
	} elseif ($module == "pemeriksaan") {
		if (isset($_REQUEST['form'])) $form = $_REQUEST['form'];
		if (isset($form['tgl_pemeriksaan'])) $tgl_pemeriksaan = balikTanggal($form['tgl_pemeriksaan']);
		if (isset($form['no_ba_pemeriksaan'])) $no_pemeriksaan = $form['no_ba_pemeriksaan'];
		if (isset($form['sp'])) $sp = $form['sp'];

		switch ($oper) {
			case 'edit':
				if ($sp == '1') $sta = "status_proses = '2',";
				else $sta = "";
				mysql_query("UPDATE masuk SET tgl_pemeriksaan = '$tgl_pemeriksaan', no_ba_pemeriksaan = '$no_pemeriksaan', $sta
							update_date = '$datime'
							WHERE id_masuk = '$_GET[id_ubah]'");

				if (mysql_errno() == 0) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil diubah !"));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Kode Barang Sudah Ada di Unit ini !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil mengubah data ! ", 'kode' => mysql_errno()));
				}
				break;

			case 'del':
				mysql_query("UPDATE masuk SET tgl_pemeriksaan = NULL, no_ba_pemeriksaan = '', status_proses = '1',
							update_date = '$datime'
							WHERE id_masuk = '$_POST[id_hapus]'");

				if (mysql_errno() == 0) {
					echo "Data telah berhasil dibatalkan !";
				} else {
					echo "Tidak berhasil membatalkan data !";
				}

				break;
		}
	} elseif ($module == "penerimaan") {
		if (isset($_REQUEST['form'])) $form = $_REQUEST['form'];
		if (isset($form['tgl_penerimaan'])) $tgl_penerimaan = balikTanggal($form['tgl_penerimaan']);
		if (isset($form['no_ba_penerimaan'])) $no_ba_penerimaan = $form['no_ba_penerimaan'];
		if (isset($form['tgl_dok_penerimaan'])) $tgl_dok_penerimaan = balikTanggal($form['tgl_dok_penerimaan']);
		if (isset($form['no_dok_penerimaan'])) $no_dok_penerimaan = $form['no_dok_penerimaan'];
		if (isset($form['id_gud'])) $id_gud = $form['id_gud'];
		if (isset($form['ta'])) $ta = $form['ta'];
		if (isset($form['sp'])) $sp = $form['sp'];

		switch ($oper) {
			case 'edit':
				$cek = mysql_num_rows(mysql_query("SELECT m.id_barang, (SELECT MAX(tgl_transaksi) FROM kartu_stok k 
												WHERE k.id_barang = m.id_barang AND m.uuid_skpd = k.uuid_skpd 
												AND jml_out <> 0 AND k.soft_delete = 0) AS tgl_keluar
												FROM masuk_detail m
												WHERE id_masuk = '$_GET[id_ubah]'
												HAVING tgl_keluar > '$tgl_penerimaan'"));
				if ($cek > 0) {
					echo json_encode(array(
						'success' => false,
						'pesan' => "Tidak dapat mengubah Data pengadaan ini, Barang sudah dikeluarkan !",
						'error' => "barang_keluar"
					));
					break;
				}

				mysql_query("UPDATE masuk SET 	tgl_penerimaan = '$tgl_penerimaan', 
											no_ba_penerimaan = '$no_ba_penerimaan', 
											tgl_dok_penerimaan = '$tgl_dok_penerimaan', 
											no_dok_penerimaan = '$no_dok_penerimaan', 
											id_gudang = '$id_gud', 
											status_proses = '3',
											update_date = '$datime'
							WHERE id_masuk = '$_GET[id_ubah]'");
				//UBAH TGL_PENERIMAAN TRIGGER  up_tgl_terima_masuk				
				if ($sp != 3) {
					$mas = mysql_fetch_assoc(mysql_query("SELECT id_sumber FROM masuk WHERE id_masuk = '$_GET[id_ubah]'"));
					$det = mysql_query("SELECT * FROM masuk_detail WHERE id_masuk = '$_GET[id_ubah]'");
					while ($d = mysql_fetch_assoc($det)) {
						mysql_query("INSERT INTO kartu_stok (id_stok, uuid_skpd, id_barang, id_kelompok, id_gudang, 
														id_sumber_dana, id_transaksi, id_transaksi_detail,
														tgl_transaksi, ta, jml_in, jml_out, harga, kode,
														create_date, soft_delete, creator_id)
												VALUES	(UUID(), '$d[uuid_skpd]', '$d[id_barang]', '$d[id_kelompok]','$id_gud', 
														'$mas[id_sumber]', '$_GET[id_ubah]', '$d[id_masuk_detail]',
														'$tgl_penerimaan', '$ta', '$d[jml_masuk]', 0, '$d[harga_masuk]', 'i',
														'$datime', 0, '$pengguna')");
					}
				} else {
					mysql_query("UPDATE kartu_stok SET id_gudang = '$id_gud', tgl_transaksi = '$tgl_penerimaan' WHERE id_transaksi = '$_GET[id_ubah]' AND soft_delete = 0");
				}

				if (mysql_errno() == 0) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil diubah !"));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Kode Barang Sudah Ada di Unit ini !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil mengubah data ! ", 'kode' => mysql_errno()));
				}
				break;

			case 'del':
				mysql_query("UPDATE masuk SET tgl_penerimaan = NULL, no_ba_penerimaan = '', tgl_dok_penerimaan = NULL, no_dok_penerimaan = '',
							status_proses = '2',
							update_date = '$datime'
							WHERE id_masuk = '$_POST[id_hapus]'");
				mysql_query("UPDATE kartu_stok SET soft_delete = 1 WHERE id_transaksi = '$_POST[id_hapus]' ");

				if (mysql_errno() == 0) {
					echo "Data telah berhasil dibatalkan !";
				} else {
					echo "Tidak berhasil membatalkan data !";
				}

				break;
		}
	} elseif ($module == "nota_minta") {
		if (isset($_REQUEST['form'])) $form = $_REQUEST['form'];
		if (isset($_REQUEST['basket'])) $basket = $_REQUEST['basket'];
		if (isset($_REQUEST['ubahform'])) $ubahform = $_REQUEST['ubahform'];
		if (isset($_REQUEST['tanggal'])) $tgl = $_REQUEST['tanggal'];
		if (isset($form['id_sub'])) $id_sub = $form['id_sub'];
		if (isset($form['ta'])) $ta = $form['ta'];
		if (isset($form['vjenis'])) $vjenis = $form['vjenis'];
		if (isset($form['iduntuk'])) $iduntuk = $form['iduntuk'];
		if (isset($form['txtuntuk'])) $txtuntuk = $form['txtuntuk'];
		if (isset($form['tanggal'])) $tanggal = balikTanggal($form['tanggal']);
		if (isset($form['nomor'])) $nomor = $form['nomor'];


		$qq = mysql_fetch_assoc(mysql_query("SELECT CONCAT_WS('.',kd_urusan, kd_bidang, kd_unit) AS kds FROM ref_sub2_unit WHERE nm_sub2_unit = '$_SESSION[nm_sub2_unit]' "));
		$lock = mysql_query("SELECT * FROM kunci_entri_skpd WHERE kd_skpd = '$qq[kds]' ");
		//$lock = mysql_query("SELECT * FROM kunci_entri_skpd WHERE kd_skpd = '$_SESSION[kode_sub]' ");
		$jumlock = mysql_num_rows($lock);
		if ($jumlock > 0) {
			$gl = mysql_fetch_assoc($lock);

			$tga = strtotime($tanggal);

			if ($tga == "") {
				$tga = strtotime($tgl);
			}

			$tgb = strtotime($gl["kunci_sampai"]);
			$gl["kunci_sampai"] = balikTanggalIndo($gl["kunci_sampai"]);
			if ($tga <= $tgb) {
				// $allow = 0;
				$allow = 1;
			} else {
				$allow = 1;
			}
		} else {
			$allow = 1;
			$gl["kunci_sampai"] = "";
		}


		if (isset($vjenis)) {
			if ($vjenis == 0) $iduntuk = "";
			elseif ($vjenis == 1) $txtuntuk = "";
		}

		if ($allow == 1) {

			switch ($oper) {
				case 'add':


					$ceknota = mysql_fetch_assoc(mysql_query("SELECT no_nota FROM nota_minta WHERE unit_peminta = '$id_sub' AND no_nota = '$nomor' AND soft_delete = 0"));

					if ($ceknota["no_nota"] == $nomor) {

						echo json_encode(array('success' => false, 'pesan' => "Nota <b>$nomor</b> Sudah Dientri !"));
						break;
					}


					$u = mysql_fetch_row(mysql_query("SELECT UUID()"));
					$uuid = $u[0];
					mysql_query("INSERT INTO nota_minta(id_nota_minta, unit_peminta, ta, no_nota, tgl_nota, 
												stat_untuk, unit_dituju, peruntukan,
												create_date, 
												creator_id,
												soft_delete)
										VALUES ('$uuid', '$id_sub', '$ta', '$nomor', '$tanggal', 
												'$vjenis', '$iduntuk', '$txtuntuk',
												'$datime',
												'$pengguna',
												'0')");
					if (mysql_errno() == 0) {
						foreach ($basket as $val) {
							$jumlah = preg_replace("/[^0-9]/", "", $val['jumlah']);
							mysql_query("INSERT INTO nota_minta_detail ( id_nota_minta_detail, id_nota_minta, uuid_skpd,
															ta, id_barang, jumlah, ket,
															create_date, 
															creator_id,
															soft_delete)
													VALUES( UUID(), '$uuid', '$id_sub',
															'$ta', '$val[id_bar]', '$jumlah', '$val[ket]',
															'$datime',
															'$pengguna',
															'0')");
						}

						if (mysql_errno() == 0) echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil ditambahkan !"));
						else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menambahkan data ! ", 'kode' => mysql_errno()));
					} else {
						if (mysql_errno() == 1062) {
							echo json_encode(array(
								'success' => false,
								'pesan' => "Nota Permintaan sudah ada !",
								'error' => "nomor_sama"
							));
						} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menambahkan data ! ", 'kode' => mysql_errno()));
					}

					break;
				case 'edit':
					if ($ubahform != '') {
						$dataubah = "";
						$id_sub_ganti = "";
						$form = explode("||", $ubahform);
						foreach ($form as $field) {
							$f = explode('::', $field);
							$v = explode('|', $field);
							if ($f[0] == 'id_sub') {
								$id_sub_ganti = $v[1];
								//$kdg = explode('.',$id_sub_ganti);
								$dataubah .= "uuid_skpd = '$id_sub_ganti', ";
							} elseif ($f[0] == 'ta') {
								$ta_ganti = $v[1];
								$dataubah .= "ta = '$ta_ganti', ";
							}
						}

						if ($dataubah != "") {
							$dataubah = substr($dataubah, 0, -2);
							mysql_query("UPDATE nota_minta_detail SET $dataubah , update_date = '$datime' WHERE id_nota_minta = '$_GET[id_ubah]'");
						}

						mysql_query("UPDATE nota_minta SET unit_peminta = '$id_sub', ta = '$ta', no_nota = '$nomor', 
												tgl_nota = '$tanggal', stat_untuk='$vjenis', unit_dituju = '$iduntuk',
												peruntukan='$txtuntuk', update_date = '$datime'  
									WHERE id_nota_minta = '$_GET[id_ubah]'");
					}



					$datser = mysql_query("SELECT o.id_barang AS id_bar, jumlah, ket, id_nota_minta_detail AS id
									FROM nota_minta_detail o
									WHERE id_nota_minta = '$_GET[id_ubah]' AND o.soft_delete=0");
					//Ambil Nama Field
					$fi = mysql_num_fields($datser);
					for ($i = 0; $i < $fi; $i++) {
						$lab[$i] = mysql_field_name($datser, $i);
					}

					$edit = array();
					$add = array();
					$del = array();
					while ($da = mysql_fetch_assoc($datser)) {
						$cek = "";
						foreach ($basket as $key => $val) {
							if (isset($val['id'])) { //data lama 
								if ($val['id'] == $da['id']) { //data lama masih ada
									$isi = "";
									for ($i = 0; $i < $fi; $i++) { //ulang per nama field
										$label = $lab[$i];
										if ($da[$label] != $val[$label]) { //data lama yang diubah
											if ($label == 'id_bar') $isi .= "id_barang = '$val[$label]', ";
											elseif ($label == 'jumlah') $isi .= "jumlah = '" . preg_replace("/[^0-9]/", "", $val[$label]) . "', ";
											elseif ($label == 'ket') $isi .= "ket = '$val[$label]', ";
										}
									}

									if ($isi != "") {
										$ed['id'] = $val['id'];
										$ed['isi'] = substr($isi, 0, -2);
										array_push($edit, $ed);
									}
									unset($basket[$key]);
									$cek = 'ada';
								}
							}
						}
						if ($cek == "") array_push($del, $da['id']); //data lama dihapus
					}

					$add = $basket; //data baru sisa hasil pengecekan

					foreach ($edit as $e) {
						//echo $e['isi'];
						mysql_query("UPDATE nota_minta_detail SET $e[isi] , update_date = '$datime' WHERE id_nota_minta_detail = '$e[id]'");
					}

					foreach ($add as $val) {
						$jml = preg_replace("/[^0-9]/", "", $val['jumlah']);
						mysql_query("INSERT INTO nota_minta_detail ( id_nota_minta_detail, id_nota_minta, uuid_skpd,
															ta, id_barang, jumlah, ket,
															create_date, 
															creator_id,
															soft_delete)
													VALUES( UUID(), '$_GET[id_ubah]', '$id_sub',
															'$ta', '$val[id_bar]', '$jml', '$val[ket]', 
															'$datime',
															'$pengguna',
															'0')");
					}

					foreach ($del as $id) {
						mysql_query("UPDATE nota_minta_detail SET soft_delete = '1' WHERE id_nota_minta_detail = '$id' AND id_nota_minta = '$_GET[id_ubah]'");
					}


					if (mysql_errno() == 0) {
						echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil diubah !"));
					} else {
						if (mysql_errno() == 1062) {
							echo json_encode(array(
								'success' => false,
								'pesan' => "Kode Barang Sudah Ada di Unit ini !",
								'error' => "nomor_sama"
							));
						} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil mengubah data ! " . mysql_errno()));
					}


					break;
				case 'del':
					mysql_query("UPDATE nota_minta SET soft_delete = '1' WHERE id_nota_minta = '$_POST[id_hapus]'");
					mysql_query("UPDATE nota_minta_detail SET soft_delete = '1' WHERE id_nota_minta = '$_POST[id_hapus]'");
					if (mysql_errno() == 0) {
						echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil dihapuskan !"));
					} else {
						echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menghapus data ! ", 'kode' => mysql_errno()));
					}

					break;
			}
		} else {
			echo json_encode(array('success' => false, 'pesan' => " Entri Anda Dikunci s/d " . $gl["kunci_sampai"]));
		}
	} elseif ($module == "nota_minta_baru") {
		if (isset($_REQUEST['form'])) $form = $_REQUEST['form'];
		if (isset($_REQUEST['basket'])) $basket = $_REQUEST['basket'];
		if (isset($_REQUEST['ubahform'])) $ubahform = $_REQUEST['ubahform'];
		if (isset($form['id_sub'])) $id_sub = $form['id_sub'];
		if (isset($form['ta'])) $ta = $form['ta'];
		if (isset($form['vjenis'])) $vjenis = $form['vjenis'];
		if (isset($form['iduntuk'])) $iduntuk = $form['iduntuk'];
		if (isset($form['txtuntuk'])) $txtuntuk = $form['txtuntuk'];
		if (isset($form['tanggal'])) $tanggal = balikTanggal($form['tanggal']);
		if (isset($form['nomor'])) $nomor = $form['nomor'];


		if (isset($form['no_spb'])) $no_spb = $form['no_spb'];
		if (isset($form['tanggal_spb'])) $tanggal_spb = balikTanggal($form['tanggal_spb']);

		if (isset($form['no_surat'])) $no_surat = $form['no_surat'];
		if (isset($form['tgl_surat'])) $tgl_surat = balikTanggal($form['tgl_surat']);

		if ($vjenis == 0) $iduntuk = "";
		elseif ($vjenis == 1) $txtuntuk = "";


		$qq = mysql_fetch_assoc(mysql_query("SELECT CONCAT_WS('.',kd_urusan, kd_bidang, kd_unit) AS kds FROM ref_sub2_unit WHERE nm_sub2_unit = '$_SESSION[nm_sub2_unit]' "));
		$lock = mysql_query("SELECT * FROM kunci_entri_skpd WHERE kd_skpd = '$qq[kds]' ");
		//$lock = mysql_query("SELECT * FROM kunci_entri_skpd WHERE kd_skpd = '$_SESSION[kode_sub]' ");
		$jumlock = mysql_num_rows($lock);
		if ($jumlock > 0) {
			$gl = mysql_fetch_assoc($lock);

			$tga = strtotime($tanggal_spb);
			$tgb = strtotime($gl["kunci_sampai"]);
			$gl["kunci_sampai"] = balikTanggalIndo($gl["kunci_sampai"]);
			if ($tga <= $tgb) {
				// $allow = 0;
				$allow = 1;
			} else {
				$allow = 1;
			}
		} else {
			$allow = 1;
			$gl["kunci_sampai"] = "";
		}

		if ($allow == 1) {
			switch ($oper) {
				case 'add':
					$u = mysql_fetch_row(mysql_query("SELECT UUID()"));
					$uuid = $u[0];
					$u2 = mysql_fetch_row(mysql_query("SELECT UUID()"));
					$uuid2 = $u2[0];
					$u3 = mysql_fetch_row(mysql_query("SELECT UUID()"));
					$uuid3 = $u3[0];




					$ceknota = mysql_fetch_assoc(mysql_query("SELECT no_nota FROM nota_minta WHERE unit_peminta = '$id_sub' AND no_nota = '$nomor' AND soft_delete = 0"));
					if ($ceknota["no_nota"] == $nomor) {

						echo json_encode(array('success' => false, 'pesan' => "Nota <b>$nomor</b> Sudah Dientri !"));
						break;
					}




					mysql_query("INSERT INTO nota_minta(id_nota_minta, unit_peminta, ta, no_nota, tgl_nota, 
												stat_untuk, unit_dituju, peruntukan, status,
												create_date, 
												creator_id,
												soft_delete)
										VALUES ('$uuid', '$id_sub', '$ta', '$nomor', '$tanggal', 
												'$vjenis', '$iduntuk', '$txtuntuk', '1',
												'$datime',
												'$pengguna',
												'0')");
					if (mysql_errno() == 0) {
						if ($vjenis == 0) {
							$skpd_sp = $id_sub;
							$iduntuk_sp = "";
							$txtuntuk_sp = $txtuntuk;
						} else {
							/* $skpd_sp = $iduntuk; 
					$iduntuk_sp = $id_sub; 
					$txtuntuk_sp = $txtuntuk;  */
							$skpd_sp = $id_sub;
							$iduntuk_sp = "";
							$txtuntuk_sp = $txtuntuk;
						}
						$rrr = mysql_query("INSERT INTO sp_out(id_sp_out, id_surat_minta, uuid_skpd, ta, stat_untuk, uuid_untuk, peruntukan,
											no_sp_out, tgl_sp_out, status,
											create_date, 
											creator_id,
											soft_delete)
									VALUES ('$uuid3', '$uuid2', '$skpd_sp', '$ta', '$vjenis', '$iduntuk_sp', '$txtuntuk_sp', 
											'$no_surat', '$tgl_surat', '1',
											'$datime',
											'$pengguna',
											'0')") or die(mysql_error());
						mysql_query("INSERT INTO surat_minta (id_surat_minta,
												 unit_peminta,
												 stat_untuk,
												 unit_dituju,
												 peruntukan,
												 ta,
												 id_nota_minta,
												 no_spb,
												 tgl_spb,
												 status,
												 create_date,
												 creator_id,
												 soft_delete)
										VALUES ('$uuid2',
												'$id_sub',
												'$vjenis',
												'$iduntuk',
												'$txtuntuk',
												'$ta',
												'$uuid',
												'$no_spb',
												'$tanggal_spb',
												'1',
												'$datime',
												'$pengguna',
												'0')");
						$values = "";
						$array_bar = "";
						$array_jml = "";
						foreach ($basket as $val) {
							//$harga = preg_replace("/[^0-9]/","", $val['harga']);
							//$jumlah = preg_replace("/[^0-9]/","", $val['jmlkeluar']); 
							$harga = $val['harga'];
							$jumlah = $val['jmlkeluar'];


							$harga = str_replace('.', '', $harga);
							$jumlah = str_replace('.', '', $jumlah);
							$jumlah = str_replace(',', '.', $jumlah);


							$array_bar .= "$val[id_bar],";
							$array_jml .= "$jumlah,";


							mysql_query("INSERT INTO nota_minta_detail ( id_nota_minta_detail, id_nota_minta, uuid_skpd,
															ta, id_barang, jumlah, ket,
															create_date, 
															creator_id,
															soft_delete)
													VALUES( UUID(), '$uuid', '$id_sub',
															'$ta', '$val[id_bar]', '$jumlah', '$val[ket]',
															'$datime',
															'$pengguna',
															'0')");

							/* $values = substr($values, 0, -2);
					mysql_query("INSERT INTO sp_out_detail ( id_sp_out_detail, id_sp_out, uuid_skpd,
															ta, id_barang, jml_barang, harga_barang, 
															create_date, creator_id, soft_delete)
													VALUES 
															(UUID(), '$uuid3', '$id_sub', 
															'$ta', '$val[id_bar]', '$jumlah', '$harga', 
															'$datime','$pengguna', '0')"); */


							mysql_query("INSERT INTO surat_minta_detail (id_surat_minta_detail, id_surat_minta, uuid_skpd, ta, id_barang, jumlah,
																create_date, creator_id)
														  VALUES(UUID(), '$uuid2', '$id_sub', '$ta', '$val[id_bar]', '$jumlah',
																'$datime', '$pengguna')");
							/* 
					mysql_query("INSERT INTO surat_minta_detail (id_surat_minta_detail, id_surat_minta, uuid_skpd, ta, id_barang, jumlah,
																create_date, creator_id)
									SELECT UUID(), '$uuid2', uuid_skpd, ta, id_barang, jumlah, '$datime', '$pengguna' 
									FROM nota_minta_detail WHERE id_nota_minta = '$uuid' 
									AND soft_delete = 0");   */
						}

						if ($array_bar != "") {
							//$values = substr($values, 0, -2);
							mysql_query("CALL ambil_harga_insert_sp_out_detail ('$array_bar', '$array_jml', '$tgl_surat', '$id_sub', '$uuid2', '$uuid3', '$ta', '$datime', '$pengguna')");
							/* mysql_query("INSERT INTO sp_out_detail ( id_sp_out_detail, id_sp_out, uuid_skpd,
															ta, id_barang, jml_barang, harga_barang, 
															create_date, 
															creator_id,
															soft_delete)
													VALUES $values"); */
						}
						//$jumsur="";
						//$jumbar="";
						/* $cekjum = mysql_query("SELECT c.jumlah as jml_surat,SUM(b.jml_barang) jml_barang FROM sp_out a
						INNER JOIN sp_out_detail b ON a.uuid_skpd=b.uuid_skpd AND a.id_sp_out=b.id_sp_out
						INNER JOIN surat_minta_detail c ON a.uuid_skpd=c.uuid_skpd AND a.id_surat_minta=c.id_surat_minta
						WHERE a.id_sp_out='$uuid3'");
						while ($cekjum1 = mysql_fetch_assoc($cekjum)) {
						$jumsur .= "$cekjum1[jml_surat]";
						$jumbar .= "$cekjum1[jml_barang]";
						
						} */
						
						//if ($jumsur != $jumbar) {
						/* mysql_query("UPDATE sp_out_detail
						SET jml_barang=jml_barang+($jumsur-$jumbar)
						WHERE id_sp_out='$uuid3' AND uuid_skpd='$skpd_sp'
						ORDER BY  id_sp_out ASC LIMIT 1"); */	
						
						/* mysql_query("INSERT INTO sp_out_detail ( id_sp_out_detail, id_sp_out, uuid_skpd,ta, id_barang, jml_barang, harga_barang, create_date, creator_id, soft_delete)
						SELECT UUID(),id_sp_out, uuid_skpd,ta, id_barang, $jumsur-$jumbar, harga_barang , '$datime', creator_id, soft_delete FROM sp_out_detail
						WHERE id_sp_out='$uuid3' AND uuid_skpd='$skpd_sp'
						ORDER BY  id_sp_out ASC LIMIT 1");
					
						} */
						mysql_query("UPDATE surat_minta SET status=1 WHERE id_surat_minta = '$uuid2'");
					}
					if ($rrr) {
						if (mysql_errno() == 0) echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil ditambahkan !"));
						else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menambahkan data ! ", 'kode' => mysql_errno()));
					} else {
						if (mysql_errno() == 1062) {
							echo json_encode(array(
								'success' => false,
								'pesan' => "Nota Permintaan sudah ada !",
								'error' => "nomor_sama"
							));
						} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menambahkan data ! ", 'kode' => mysql_errno()));
					}

					break;
				case 'edit':
					if ($ubahform != '') {
						$dataubah = "";
						$id_sub_ganti = "";
						$form = explode("||", $ubahform);
						foreach ($form as $field) {
							$f = explode('::', $field);
							$v = explode('|', $field);
							if ($f[0] == 'id_sub') {
								$id_sub_ganti = $v[1];
								//$kdg = explode('.',$id_sub_ganti);
								$dataubah .= "uuid_skpd = '$id_sub_ganti', ";
							} elseif ($f[0] == 'ta') {
								$ta_ganti = $v[1];
								$dataubah .= "ta = '$ta_ganti', ";
							}
						}

						if ($dataubah != "") {
							$dataubah = substr($dataubah, 0, -2);
							mysql_query("UPDATE nota_minta_detail SET $dataubah , update_date = '$datime' WHERE id_nota_minta = '$_GET[id_ubah]'");
						}

						mysql_query("UPDATE nota_minta SET unit_peminta = '$id_sub', ta = '$ta', no_nota = '$nomor', 
												tgl_nota = '$tanggal', stat_untuk='$vjenis', unit_dituju = '$iduntuk',
												peruntukan='$txtuntuk', update_date = '$datime'  
									WHERE id_nota_minta = '$_GET[id_ubah]'");
					}



					$datser = mysql_query("SELECT o.id_barang AS id_bar, jumlah, ket, id_nota_minta_detail AS id
									FROM nota_minta_detail o
									WHERE id_nota_minta = '$_GET[id_ubah]' AND o.soft_delete=0");
					//Ambil Nama Field
					$fi = mysql_num_fields($datser);
					for ($i = 0; $i < $fi; $i++) {
						$lab[$i] = mysql_field_name($datser, $i);
					}

					$edit = array();
					$add = array();
					$del = array();
					while ($da = mysql_fetch_assoc($datser)) {
						$cek = "";
						foreach ($basket as $key => $val) {
							if (isset($val['id'])) { //data lama 
								if ($val['id'] == $da['id']) { //data lama masih ada
									$isi = "";
									for ($i = 0; $i < $fi; $i++) { //ulang per nama field
										$label = $lab[$i];
										if ($da[$label] != $val[$label]) { //data lama yang diubah
											if ($label == 'id_bar') $isi .= "id_barang = '$val[$label]', ";
											elseif ($label == 'jumlah') $isi .= "jumlah = '" . preg_replace("/[^0-9]/", "", $val[$label]) . "', ";
											elseif ($label == 'ket') $isi .= "ket = '$val[$label]', ";
										}
									}

									if ($isi != "") {
										$ed['id'] = $val['id'];
										$ed['isi'] = substr($isi, 0, -2);
										array_push($edit, $ed);
									}
									unset($basket[$key]);
									$cek = 'ada';
								}
							}
						}
						if ($cek == "") array_push($del, $da['id']); //data lama dihapus
					}

					$add = $basket; //data baru sisa hasil pengecekan

					foreach ($edit as $e) {
						//echo $e['isi'];
						mysql_query("UPDATE nota_minta_detail SET $e[isi] , update_date = '$datime' WHERE id_nota_minta_detail = '$e[id]'");
					}

					foreach ($add as $val) {
						$jml = preg_replace("/[^0-9]/", "", $val['jumlah']);
						mysql_query("INSERT INTO nota_minta_detail ( id_nota_minta_detail, id_nota_minta, uuid_skpd,
															ta, id_barang, jumlah, ket,
															create_date, 
															creator_id,
															soft_delete)
													VALUES( UUID(), '$_GET[id_ubah]', '$id_sub',
															'$ta', '$val[id_bar]', '$jml', '$val[ket]', 
															'$datime',
															'$pengguna',
															'0')");
					}

					foreach ($del as $id) {
						mysql_query("UPDATE nota_minta_detail SET soft_delete = '1' WHERE id_nota_minta_detail = '$id' AND id_nota_minta = '$_GET[id_ubah]'");
					}


					if (mysql_errno() == 0) {
						echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil diubah !"));
					} else {
						if (mysql_errno() == 1062) {
							echo json_encode(array(
								'success' => false,
								'pesan' => "Kode Barang Sudah Ada di Unit ini !",
								'error' => "nomor_sama"
							));
						} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil mengubah data ! " . mysql_errno()));
					}


					break;
				case 'del':
					mysql_query("UPDATE nota_minta SET soft_delete = '1' WHERE id_nota_minta = '$_POST[id_hapus]'");
					mysql_query("UPDATE nota_minta_detail SET soft_delete = '1' WHERE id_nota_minta = '$_POST[id_hapus]'");
					if (mysql_errno() == 0) {
						echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil dihapuskan !"));
					} else {
						echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menghapus data ! ", 'kode' => mysql_errno()));
					}

					break;
			}
		} else {
			echo json_encode(array('success' => false, 'pesan' => " Entri Anda Dikunci s/d " . $gl["kunci_sampai"] . " | " . $qq["kds"]));
		}
	} elseif ($module == "nota_minta_baru2") {
		if (isset($_REQUEST['form'])) $form = $_REQUEST['form'];
		if (isset($_REQUEST['basket'])) $basket = $_REQUEST['basket'];
		if (isset($_REQUEST['ubahform'])) $ubahform = $_REQUEST['ubahform'];
		if (isset($form['id_sub'])) $id_sub = $form['id_sub'];
		if (isset($form['ta'])) $ta = $form['ta'];
		if (isset($form['vjenis'])) $vjenis = $form['vjenis'];
		if (isset($form['iduntuk'])) $iduntuk = $form['iduntuk'];
		if (isset($form['txtuntuk'])) $txtuntuk = $form['txtuntuk'];
		if (isset($form['tanggal'])) $tanggal = balikTanggal($form['tanggal']);
		if (isset($form['nomor'])) $nomor = $form['nomor'];


		if (isset($form['no_spb'])) $no_spb = $form['no_spb'];
		if (isset($form['tanggal_spb'])) $tanggal_spb = balikTanggal($form['tanggal_spb']);

		if (isset($form['no_surat'])) $no_surat = $form['no_surat'];
		if (isset($form['tgl_surat'])) $tgl_surat = balikTanggal($form['tgl_surat']);

		if ($vjenis == 0) $iduntuk = "";
		elseif ($vjenis == 1) $txtuntuk = "";


		$qq = mysql_fetch_assoc(mysql_query("SELECT CONCAT_WS('.',kd_urusan, kd_bidang, kd_unit) AS kds FROM ref_sub2_unit WHERE nm_sub2_unit = '$_SESSION[nm_sub2_unit]' "));
		$lock = mysql_query("SELECT * FROM kunci_entri_skpd WHERE kd_skpd = '$qq[kds]' ");
		//$lock = mysql_query("SELECT * FROM kunci_entri_skpd WHERE kd_skpd = '$_SESSION[kode_sub]' ");
		$jumlock = mysql_num_rows($lock);
		if ($jumlock > 0) {
			$gl = mysql_fetch_assoc($lock);

			$tga = strtotime($tanggal_spb);
			$tgb = strtotime($gl["kunci_sampai"]);
			$gl["kunci_sampai"] = balikTanggalIndo($gl["kunci_sampai"]);
			if ($tga <= $tgb) {
				// $allow = 0;
				$allow = 1;
			} else {
				$allow = 1;
			}
		} else {
			$allow = 1;
			$gl["kunci_sampai"] = "";
		}

		if ($allow == 1) {
			switch ($oper) {
				case 'add':
					$u = mysql_fetch_row(mysql_query("SELECT UUID()"));
					$uuid = $u[0];
					$u2 = mysql_fetch_row(mysql_query("SELECT UUID()"));
					$uuid2 = $u2[0];
					$u3 = mysql_fetch_row(mysql_query("SELECT UUID()"));
					$uuid3 = $u3[0];




					$ceknota = mysql_fetch_assoc(mysql_query("SELECT no_nota FROM nota_minta WHERE unit_peminta = '$id_sub' AND no_nota = '$nomor' AND soft_delete = 0"));
					if ($ceknota["no_nota"] == $nomor) {

						echo json_encode(array('success' => false, 'pesan' => "Nota <b>$nomor</b> Sudah Dientri !"));
						break;
					}




					mysql_query("INSERT INTO nota_minta(id_nota_minta, unit_peminta, ta, no_nota, tgl_nota, 
												stat_untuk, unit_dituju, peruntukan, status,
												create_date, 
												creator_id,
												soft_delete)
										VALUES ('$uuid', '$id_sub', '$ta', '$nomor', '$tanggal', 
												'$vjenis', '$iduntuk', '$txtuntuk', '1',
												'$datime',
												'$pengguna',
												'0')");
					if (mysql_errno() == 0) {
						if ($vjenis == 0) {
							$skpd_sp = $id_sub;
							$iduntuk_sp = "";
							$txtuntuk_sp = $txtuntuk;
						} else {
							/* $skpd_sp = $iduntuk; 
					$iduntuk_sp = $id_sub; 
					$txtuntuk_sp = $txtuntuk;  */
							$skpd_sp = $id_sub;
							$iduntuk_sp = "";
							$txtuntuk_sp = $txtuntuk;
						}
						$rrr = mysql_query("INSERT INTO sp_out(id_sp_out, id_surat_minta, uuid_skpd, ta, stat_untuk, uuid_untuk, peruntukan,
											no_sp_out, tgl_sp_out, status,
											create_date, 
											creator_id,
											soft_delete)
									VALUES ('$uuid3', '$uuid2', '$skpd_sp', '$ta', '$vjenis', '$iduntuk_sp', '$txtuntuk_sp', 
											'$no_surat', '$tgl_surat', '1',
											'$datime',
											'$pengguna',
											'0')") or die(mysql_error());
						mysql_query("INSERT INTO surat_minta (id_surat_minta,
												 unit_peminta,
												 stat_untuk,
												 unit_dituju,
												 peruntukan,
												 ta,
												 id_nota_minta,
												 no_spb,
												 tgl_spb,
												 status,
												 create_date,
												 creator_id,
												 soft_delete)
										VALUES ('$uuid2',
												'$id_sub',
												'$vjenis',
												'$iduntuk',
												'$txtuntuk',
												'$ta',
												'$uuid',
												'$no_spb',
												'$tanggal_spb',
												'1',
												'$datime',
												'$pengguna',
												'0')");
						$values = "";
						$array_bar = "";
						$array_jml = "";
						foreach ($basket as $val) {
							//$harga = preg_replace("/[^0-9]/","", $val['harga']);
							//$jumlah = preg_replace("/[^0-9]/","", $val['jmlkeluar']); 
							$harga = $val['harga'];
							$jumlah = $val['jmlkeluar'];


							$harga = str_replace('.', '', $harga);
							$jumlah = str_replace('.', '', $jumlah);
							$jumlah = str_replace(',', '.', $jumlah);


							$array_bar .= "$val[id_bar],";
							$array_jml .= "$jumlah,";


							mysql_query("INSERT INTO nota_minta_detail ( id_nota_minta_detail, id_nota_minta, uuid_skpd,
															ta, id_barang, jumlah, ket,
															create_date, 
															creator_id,
															soft_delete)
													VALUES( UUID(), '$uuid', '$id_sub',
															'$ta', '$val[id_bar]', '$jumlah', '$val[ket]',
															'$datime',
															'$pengguna',
															'0')");

							/* $values = substr($values, 0, -2);
					mysql_query("INSERT INTO sp_out_detail ( id_sp_out_detail, id_sp_out, uuid_skpd,
															ta, id_barang, jml_barang, harga_barang, 
															create_date, creator_id, soft_delete)
													VALUES 
															(UUID(), '$uuid3', '$id_sub', 
															'$ta', '$val[id_bar]', '$jumlah', '$harga', 
															'$datime','$pengguna', '0')"); */


							mysql_query("INSERT INTO surat_minta_detail (id_surat_minta_detail, id_surat_minta, uuid_skpd, ta, id_barang, jumlah,
																create_date, creator_id)
														  VALUES(UUID(), '$uuid2', '$id_sub', '$ta', '$val[id_bar]', '$jumlah',
																'$datime', '$pengguna')");
							/* 
					mysql_query("INSERT INTO surat_minta_detail (id_surat_minta_detail, id_surat_minta, uuid_skpd, ta, id_barang, jumlah,
																create_date, creator_id)
									SELECT UUID(), '$uuid2', uuid_skpd, ta, id_barang, jumlah, '$datime', '$pengguna' 
									FROM nota_minta_detail WHERE id_nota_minta = '$uuid' 
									AND soft_delete = 0");   */
						}

						if ($array_bar != "") {
							//$values = substr($values, 0, -2);
							mysql_query("CALL ambil_harga_insert_sp_out_detail ('$array_bar', '$array_jml', '$tgl_surat', '$id_sub', '$uuid2', '$uuid3', '$ta', '$datime', '$pengguna')");
							/* mysql_query("INSERT INTO sp_out_detail ( id_sp_out_detail, id_sp_out, uuid_skpd,
															ta, id_barang, jml_barang, harga_barang, 
															create_date, 
															creator_id,
															soft_delete)
													VALUES $values"); */
						}
						//$jumsur="";
						//$jumbar="";
						/* $cekjum = mysql_query("SELECT c.jumlah as jml_surat,SUM(b.jml_barang) jml_barang FROM sp_out a
						INNER JOIN sp_out_detail b ON a.uuid_skpd=b.uuid_skpd AND a.id_sp_out=b.id_sp_out
						INNER JOIN surat_minta_detail c ON a.uuid_skpd=c.uuid_skpd AND a.id_surat_minta=c.id_surat_minta
						WHERE a.id_sp_out='$uuid3'");
						while ($cekjum1 = mysql_fetch_assoc($cekjum)) {
						$jumsur .= "$cekjum1[jml_surat]";
						$jumbar .= "$cekjum1[jml_barang]";
						
						} */
						
						//if ($jumsur != $jumbar) {
						/* mysql_query("UPDATE sp_out_detail
						SET jml_barang=jml_barang+($jumsur-$jumbar)
						WHERE id_sp_out='$uuid3' AND uuid_skpd='$skpd_sp'
						ORDER BY  id_sp_out ASC LIMIT 1"); */	
						
						/* mysql_query("INSERT INTO sp_out_detail ( id_sp_out_detail, id_sp_out, uuid_skpd,ta, id_barang, jml_barang, harga_barang, create_date, creator_id, soft_delete)
						SELECT UUID(),id_sp_out, uuid_skpd,ta, id_barang, $jumsur-$jumbar, harga_barang , '$datime', creator_id, soft_delete FROM sp_out_detail
						WHERE id_sp_out='$uuid3' AND uuid_skpd='$skpd_sp'
						ORDER BY  id_sp_out ASC LIMIT 1");
					
						} */


						mysql_query("UPDATE surat_minta SET status=1 WHERE id_surat_minta = '$uuid2'");
					}
					if ($rrr) {
						if (mysql_errno() == 0) echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil ditambahkan !"));
						else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menambahkan data ! ", 'kode' => mysql_errno()));
					} else {
						if (mysql_errno() == 1062) {
							echo json_encode(array(
								'success' => false,
								'pesan' => "Nota Permintaan sudah ada !",
								'error' => "nomor_sama"
							));
						} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menambahkan data ! ", 'kode' => mysql_errno()));
					}

					break;
				case 'edit':
					if ($ubahform != '') {
						$dataubah = "";
						$id_sub_ganti = "";
						$form = explode("||", $ubahform);
						foreach ($form as $field) {
							$f = explode('::', $field);
							$v = explode('|', $field);
							if ($f[0] == 'id_sub') {
								$id_sub_ganti = $v[1];
								//$kdg = explode('.',$id_sub_ganti);
								$dataubah .= "uuid_skpd = '$id_sub_ganti', ";
							} elseif ($f[0] == 'ta') {
								$ta_ganti = $v[1];
								$dataubah .= "ta = '$ta_ganti', ";
							}
						}

						if ($dataubah != "") {
							$dataubah = substr($dataubah, 0, -2);
							mysql_query("UPDATE nota_minta_detail SET $dataubah , update_date = '$datime' WHERE id_nota_minta = '$_GET[id_ubah]'");
						}

						mysql_query("UPDATE nota_minta SET unit_peminta = '$id_sub', ta = '$ta', no_nota = '$nomor', 
												tgl_nota = '$tanggal', stat_untuk='$vjenis', unit_dituju = '$iduntuk',
												peruntukan='$txtuntuk', update_date = '$datime'  
									WHERE id_nota_minta = '$_GET[id_ubah]'");
					}



					$datser = mysql_query("SELECT o.id_barang AS id_bar, jumlah, ket, id_nota_minta_detail AS id
									FROM nota_minta_detail o
									WHERE id_nota_minta = '$_GET[id_ubah]' AND o.soft_delete=0");
					//Ambil Nama Field
					$fi = mysql_num_fields($datser);
					for ($i = 0; $i < $fi; $i++) {
						$lab[$i] = mysql_field_name($datser, $i);
					}

					$edit = array();
					$add = array();
					$del = array();
					while ($da = mysql_fetch_assoc($datser)) {
						$cek = "";
						foreach ($basket as $key => $val) {
							if (isset($val['id'])) { //data lama 
								if ($val['id'] == $da['id']) { //data lama masih ada
									$isi = "";
									for ($i = 0; $i < $fi; $i++) { //ulang per nama field
										$label = $lab[$i];
										if ($da[$label] != $val[$label]) { //data lama yang diubah
											if ($label == 'id_bar') $isi .= "id_barang = '$val[$label]', ";
											elseif ($label == 'jumlah') $isi .= "jumlah = '" . preg_replace("/[^0-9]/", "", $val[$label]) . "', ";
											elseif ($label == 'ket') $isi .= "ket = '$val[$label]', ";
										}
									}

									if ($isi != "") {
										$ed['id'] = $val['id'];
										$ed['isi'] = substr($isi, 0, -2);
										array_push($edit, $ed);
									}
									unset($basket[$key]);
									$cek = 'ada';
								}
							}
						}
						if ($cek == "") array_push($del, $da['id']); //data lama dihapus
					}

					$add = $basket; //data baru sisa hasil pengecekan

					foreach ($edit as $e) {
						//echo $e['isi'];
						mysql_query("UPDATE nota_minta_detail SET $e[isi] , update_date = '$datime' WHERE id_nota_minta_detail = '$e[id]'");
					}

					foreach ($add as $val) {
						$jml = preg_replace("/[^0-9]/", "", $val['jumlah']);
						mysql_query("INSERT INTO nota_minta_detail ( id_nota_minta_detail, id_nota_minta, uuid_skpd,
															ta, id_barang, jumlah, ket,
															create_date, 
															creator_id,
															soft_delete)
													VALUES( UUID(), '$_GET[id_ubah]', '$id_sub',
															'$ta', '$val[id_bar]', '$jml', '$val[ket]', 
															'$datime',
															'$pengguna',
															'0')");
					}

					foreach ($del as $id) {
						mysql_query("UPDATE nota_minta_detail SET soft_delete = '1' WHERE id_nota_minta_detail = '$id' AND id_nota_minta = '$_GET[id_ubah]'");
					}


					if (mysql_errno() == 0) {
						echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil diubah !"));
					} else {
						if (mysql_errno() == 1062) {
							echo json_encode(array(
								'success' => false,
								'pesan' => "Kode Barang Sudah Ada di Unit ini !",
								'error' => "nomor_sama"
							));
						} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil mengubah data ! " . mysql_errno()));
					}


					break;
				case 'del':
					mysql_query("UPDATE nota_minta SET soft_delete = '1' WHERE id_nota_minta = '$_POST[id_hapus]'");
					mysql_query("UPDATE nota_minta_detail SET soft_delete = '1' WHERE id_nota_minta = '$_POST[id_hapus]'");
					if (mysql_errno() == 0) {
						echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil dihapuskan !"));
					} else {
						echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menghapus data ! ", 'kode' => mysql_errno()));
					}

					break;
			}
		} else {
			echo json_encode(array('success' => false, 'pesan' => " Entri Anda Dikunci s/d " . $gl["kunci_sampai"] . " | " . $qq["kds"]));
		}
	} elseif ($module == "surat_minta") {
		if (isset($_REQUEST['form'])) $form = $_REQUEST['form'];
		if (isset($_REQUEST['ubahform'])) $ubahform = $_REQUEST['ubahform'];
		if (isset($form['id_sub'])) $id_sub = $form['id_sub'];
		if (isset($form['ta'])) $ta = $form['ta'];
		if (isset($form['id_nota'])) $id_nota = $form['id_nota'];
		if (isset($form['no_spb'])) $no_spb = $form['no_spb'];
		if (isset($form['tanggal'])) $tanggal = balikTanggal($form['tanggal']);
		if (isset($form['vjenis'])) $vjenis = $form['vjenis'];
		if (isset($form['iduntuk'])) $iduntuk = $form['iduntuk'];
		if (isset($form['txtuntuk'])) $txtuntuk = $form['txtuntuk'];


		switch ($oper) {
			case 'add':
				$u = mysql_fetch_row(mysql_query("SELECT UUID()"));
				$uuid = $u[0];

				mysql_query("INSERT INTO surat_minta (id_surat_minta,
												 unit_peminta,
												 stat_untuk,
												 unit_dituju,
												 peruntukan,
												 ta,
												 id_nota_minta,
												 no_spb,
												 tgl_spb,
												 create_date,
												 creator_id,
												 soft_delete)
										VALUES ('$uuid',
												'$id_sub',
												'$vjenis',
												'$iduntuk',
												'$txtuntuk',
												'$ta',
												'$id_nota',
												'$no_spb',
												'$tanggal',
												'$datime',
												'$pengguna',
												'0')");
				if (mysql_errno() == 0) {
					if ($vjenis == 0) {
						$skpd_sp = $id_sub;
						$iduntuk_sp = "";
						$txtuntuk_sp = $txtuntuk;
					} else {
						$skpd_sp = $iduntuk;
						$iduntuk_sp = $id_sub;
						$dsub2 = mysql_query("SELECT nm_sub2_unit FROM ref_sub2_unit WHERE uuid_sub2_unit = '$iduntuk_sp'");
						$sub2 = mysql_fetch_assoc($dsub2);
						$txtuntuk_sp = $sub2['nm_sub2_unit'];
					}
					mysql_query("INSERT INTO sp_out (id_sp_out, id_surat_minta, uuid_skpd, ta, stat_untuk, uuid_untuk, peruntukan,
											create_date, 
											creator_id,
											soft_delete)
									VALUES (UUID(), '$uuid', '$skpd_sp', '$ta', '$vjenis', '$iduntuk_sp', '$txtuntuk_sp', 
											'$datime',
											'$pengguna',
											'0')");
					mysql_query("INSERT INTO surat_minta_detail (id_surat_minta_detail, id_surat_minta, uuid_skpd, ta, id_barang, jumlah,
															create_date, creator_id)
								SELECT UUID(), '$uuid', uuid_skpd, ta, id_barang, jumlah, '$datime', '$pengguna' 
								FROM nota_minta_detail WHERE id_nota_minta = '$id_nota' 
								AND soft_delete = 0");
					mysql_query("UPDATE nota_minta SET status = 1 WHERE id_nota_minta = '$id_nota'");

					if (mysql_errno() == 0) echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil ditambahkan !"));
					else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menambahkan data ! ", 'kode' => mysql_errno()));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Surat Permintaan sudah ada !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menambahkan data ! ", 'kode' => mysql_errno()));
				}

				break;
			case 'edit':
				$idn_ganti = "";
				if ($ubahform != '') {
					$dataubah = "";
					$idn_lama = "";
					$form = explode("||", $ubahform);
					foreach ($form as $field) {
						$f = explode('::', $field);
						$v = explode('|', $field);
						if ($f[0] == 'id_sub') {
							$id_sub_ganti = $v[1];
							//$kdg = explode('.',$id_sub_ganti);
							$dataubah .= "unit_peminta = '$id_sub_ganti', ";
						} elseif ($f[0] == 'ta') {
							$ta_ganti = $v[1];
							$dataubah .= "ta = '$ta_ganti', ";
						} elseif ($f[0] == 'id_nota') {
							$idn_lama = $v[0];
							$idn_ganti = $v[1];
						}
					}

					if ($dataubah != "") {
						$dataubah = substr($dataubah, 0, -2);
						mysql_query("UPDATE surat_minta_detail SET $dataubah , update_date='$datime' WHERE id_surat_minta = '$_GET[id_ubah]'");
					}

					mysql_query("UPDATE surat_minta SET unit_peminta = '$id_sub',
													stat_untuk = '$vjenis',
													unit_dituju = '$iduntuk',
													peruntukan = '$txtuntuk',
													ta = '$ta', 
													id_nota_minta = '$id_nota', 
													no_spb = '$no_spb', 
													tgl_spb = '$tanggal', 
													update_date = '$datime'  
									WHERE id_surat_minta = '$_GET[id_ubah]'");
					mysql_query("UPDATE sp_out SET 	uuid_skpd = '$id_sub', 
												ta = '$ta', 
												stat_untuk = '$vjenis', 
												uuid_untuk = '$iduntuk', 
												peruntukan = '$txtuntuk', 
												update_date = '$datime'
									WHERE id_surat_minta = '$_GET[id_ubah]'");
				}

				if ($idn_ganti != "") {
					mysql_query("UPDATE surat_minta_detail SET soft_delete = 1 WHERE id_surat_minta = '$_GET[id_ubah]'");
					mysql_query("INSERT INTO surat_minta_detail (id_surat_minta_detail, id_surat_minta, uuid_skpd, ta, id_barang, jumlah,
															create_date, creator_id)
								SELECT UUID(), '$_GET[id_ubah]', uuid_skpd, ta, id_barang, jumlah, '$datime', '$pengguna' 
								FROM nota_minta_detail WHERE id_nota_minta = '$idn_ganti'");
					mysql_query("UPDATE nota_minta SET status = 1 WHERE id_nota_minta = '$idn_ganti'");
					mysql_query("UPDATE nota_minta SET status = 0 WHERE id_nota_minta = '$idn_lama'");
				}


				if (mysql_errno() == 0) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil diubah !"));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Kode Barang Sudah Ada di Unit ini !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil mengubah data ! " . mysql_errno()));
				}


				break;
			case 'del':
				mysql_query("UPDATE surat_minta s LEFT JOIN nota_minta n ON n.id_nota_minta = s.id_nota_minta 
											  LEFT JOIN sp_out o ON o.id_surat_minta = s.id_surat_minta
							SET s.soft_delete = '1', n.status = '0', o.soft_delete = '1'
						WHERE s.id_surat_minta = '$_POST[id_hapus]'");
				mysql_query("UPDATE surat_minta_detail SET soft_delete = '1' WHERE id_surat_minta = '$_POST[id_hapus]'");
				if (mysql_errno() == 0) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil dihapuskan !"));
				} else {
					echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menghapus data ! ", 'kode' => mysql_errno()));
				}

				break;
		}
	} elseif ($module == "perintah_keluar") {
		if (isset($_REQUEST['form'])) $form = $_REQUEST['form'];
		if (isset($_REQUEST['basket'])) $basket = $_REQUEST['basket'];
		if (isset($_REQUEST['ubahform'])) $ubahform = $_REQUEST['ubahform'];
		if (isset($_REQUEST['id_unit'])) $id_unit = $_REQUEST['id_unit'];
		if (isset($_REQUEST['id_minta'])) $id_minta = $_REQUEST['id_minta'];
		if (isset($_REQUEST['id_sp'])) $id_sp = $_REQUEST['id_sp'];
		if (isset($form['id_sub'])) $id_sub = $form['id_sub'];
		if (isset($form['ta'])) $ta = $form['ta'];
		if (isset($form['no_surat'])) $no_surat = $form['no_surat'];
		if (isset($form['tgl_surat'])) $tgl_surat = balikTanggal($form['tgl_surat']);


		$qq = mysql_fetch_assoc(mysql_query("SELECT CONCAT_WS('.',kd_urusan, kd_bidang, kd_unit) AS kds FROM ref_sub2_unit WHERE nm_sub2_unit = '$_SESSION[nm_sub2_unit]' "));
		$lock = mysql_query("SELECT * FROM kunci_entri_skpd WHERE kd_skpd = '$qq[kds]' ");
		$jumlock = mysql_num_rows($lock);
		if ($jumlock > 0) {
			$gl = mysql_fetch_assoc($lock);

			if ($oper == "del") {
				$tgl_surat = balikTanggal($_POST["tgl_surat"]);
			} else if ($oper == "edit") {
				$tgl_surat = balikTanggal($form['tgl_surat']);
			}
			$tga = strtotime($tgl_surat);
			$tgb = strtotime($gl["kunci_sampai"]);
			$gl["kunci_sampai"] = balikTanggalIndo($gl["kunci_sampai"]);
			if ($tga <= $tgb) {
				// $allow = 0;
				$allow = 1;
			} else {
				$allow = 1;
			}
		} else {
			$allow = 1;
			$gl["kunci_sampai"] = "";
		}

		if ($allow == 1) {

			switch ($oper) {
				case 'add':
					mysql_query("UPDATE sp_out SET no_sp_out = '$no_surat', tgl_sp_out = '$tgl_surat', status = '1'
							WHERE id_sp_out = '$id_sp'");
					if (mysql_errno() == 0) {
						$array_bar = "";
						$array_jml = "";
						foreach ($basket as $val) {
							$jumlah = preg_replace("/[^0-9]/", "", $val['jmlkeluar']);
							$array_bar .= "$val[id_bar],";
							$array_jml .= "$jumlah,";


							//$harga = preg_replace("/[^0-9]/","", $val['harga']);	
							//$values .= "(UUID(), '$id_sp', '$id_unit', '$ta', '$val[id_bar]', '$jumlah', '$harga', '$datime','$pengguna', '0'), ";

						}
						if ($array_bar != "") {
							//$values = substr($values, 0, -2);
							mysql_query("CALL ambil_harga_insert_sp_out_detail('$array_bar', '$array_jml', '$tgl_surat', '$id_unit', '$id_minta', '$id_sp', '$ta', '$datime', '$pengguna')");
							/* mysql_query("INSERT INTO sp_out_detail ( id_sp_out_detail, id_sp_out, uuid_skpd,
															ta, id_barang, jml_barang, harga_barang, 
															create_date, 
															creator_id,
															soft_delete)
													VALUES $values"); */
						}
						mysql_query("UPDATE surat_minta SET status=1 WHERE id_surat_minta = '$id_minta'");

						if (mysql_errno() == 0) echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil ditambahkan !"));
						else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menambahkan data ! ", 'kode' => mysql_errno()));
					} else {
						if (mysql_errno() == 1062) {
							echo json_encode(array(
								'success' => false,
								'pesan' => "Surat Perintah sudah ada !",
								'error' => "nomor_sama"
							));
						} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menambahkan data ! ", 'kode' => mysql_errno()));
					}

					break;

				case 'tolak':
					/* mysql_query("UPDATE surat_minta SET status = 2 WHERE id_surat_minta = '$_POST[id_tolak]'");
			mysql_query("UPDATE sp_out s, sp_out_detail d SET s.status = 2, d.soft_delete = 1 
							WHERE s.id_surat_minta = '$_POST[id_tolak]' AND s.id_sp_out = d.id_sp_out"); */
					mysql_query("UPDATE sp_out s, sp_out_detail d SET s.status = 2, d.soft_delete = 1 
							WHERE s.id_sp_out = '$_POST[id_tolak]' AND s.id_sp_out = d.id_sp_out");
					if (mysql_errno() == 0) {
						echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil dihapuskan !"));
					} else {
						echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menghapus data ! ", 'kode' => mysql_errno()));
					}
					break;

				case 'edit':
					if ($ubahform != '') {
						mysql_query("UPDATE sp_out SET no_sp_out = '$no_surat', tgl_sp_out = '$tgl_surat', update_date = '$datime'  
									WHERE id_sp_out = '$_GET[id_ubah]'");
					}


					$datser = mysql_query("SELECT o.id_barang AS id_bar, jml_barang AS jmlkeluar, 
									id_sp_out_detail AS id
									FROM sp_out_detail o
									WHERE id_sp_out = '$_GET[id_ubah]' AND o.soft_delete=0");

					$edit = array();
					$add = array();
					$del = array();
					$array_bar = "";
					$array_jml = "";
					while ($da = mysql_fetch_assoc($datser)) {
						$cek = "";
						foreach ($basket as $key => $val) {
							if (isset($val['id'])) { //data lama 
								if ($val['id'] == $da['id']) { //data lama masih ada
									$isi = "";
									$jmlkeluar = preg_replace("/[^0-9]/", "", $val['jmlkeluar']);
									//$harga = preg_replace("/[^0-9]/","", $val['harga']);

									if ($val['id_bar'] != $da['id_bar']) $isi .= "id_barang = '$val[id_bar]', ";
									elseif ($jmlkeluar != $da['jmlkeluar']) $isi .= "jml_barang = '$jmlkeluar', ";
									//elseif($harga!=$da['harga']) $isi .= "harga_barang = '$harga', ";


									if ($isi != "") {
										$ed['id'] = $val['id'];
										$ed['isi'] = substr($isi, 0, -2);
										array_push($edit, $ed);
										$array_bar .= "$val[id_bar],";
										$array_jml .= "$jmlkeluar,";
										mysql_query("UPDATE sp_out_detail SET soft_delete = '1' WHERE id_sp_out_detail = '$val[id]'");
										//print_r($isi);
									}
									unset($basket[$key]);
									$cek = 'ada';
								}
							}
						}
						if ($cek == "") array_push($del, $da['id']); //data lama dihapus
					}
					$add = $basket;
					foreach ($add as $val) {
						$jumlah = preg_replace("/[^0-9]/", "", $val['jmlkeluar']);
						$array_bar .= "$val[id_bar],";
						$array_jml .= "$jumlah,";
					}

					if ($array_bar != "") {
						mysql_query("CALL ambil_harga_insert_sp_out_detail('$array_bar', '$array_jml', '$tgl_surat', '$id_unit', '$id_minta', '$_GET[id_ubah]', '$ta', '$datime', '$pengguna')");
					}

					/* foreach($edit as $e){
				mysql_query("UPDATE sp_out_detail SET $e[isi] , update_date = '$datime' WHERE id_sp_out_detail = '$e[id]'");
			}
			
			$values = "";
			foreach($add AS $val){
				//$harga = preg_replace("/[^0-9]/","", $val['harga']);	
				$jumlah = preg_replace("/[^0-9]/","", $val['jmlkeluar']); 
				$values .= "(UUID(),'$_GET[id_ubah]','$id_unit', '$ta', '$val[id_bar]', '$jumlah', '$harga', '$datime','$pengguna', '0'), ";
			}
			if($values!=""){
				$values = substr($values, 0, -2);
				mysql_query("INSERT INTO sp_out_detail ( id_sp_out_detail, id_sp_out, uuid_skpd,
														ta, id_barang, jml_barang,
														create_date, 
														creator_id,
														soft_delete)
												VALUES $values");
			} */


					foreach ($del as $id) {
						mysql_query("UPDATE sp_out_detail SET soft_delete = '1' WHERE id_sp_out_detail = '$id'");
					}


					if (mysql_errno() == 0) {
						echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil diubah !"));
					} else {
						if (mysql_errno() == 1062) {
							echo json_encode(array(
								'success' => false,
								'pesan' => "Kode Barang Sudah Ada di Unit ini !",
								'error' => "nomor_sama"
							));
						} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil mengubah data ! " . mysql_errno()));
					}


					break;
				case 'del':
					mysql_query("UPDATE sp_out s LEFT JOIN surat_minta u ON s.id_surat_minta = u.id_surat_minta
						SET s.status = '0', u.status = 0, s.no_sp_out = '', s.tgl_sp_out = ''
						WHERE id_sp_out = '$_POST[id_hapus]'");
					mysql_query("UPDATE sp_out_detail SET soft_delete = '1' WHERE id_sp_out = '$_POST[id_hapus]'");

					if (mysql_errno() == 0) {
						echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil dihapuskan !"));
					} else {
						echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menghapus data ! ", 'kode' => mysql_errno()));
					}

					break;
			}
		} else {
			echo json_encode(array('success' => false, 'pesan' => " Entri Anda Dikunci s/d " . $gl["kunci_sampai"]));
		}
	} elseif ($module == "keluar_barang") {
		if (isset($_REQUEST['form'])) $form = $_REQUEST['form'];
		if (isset($_REQUEST['basket'])) $basket = $_REQUEST['basket'];
		if (isset($_REQUEST['ubahform'])) $ubahform = $_REQUEST['ubahform'];
		if (isset($form['id_sub'])) $id_sub = $form['id_sub'];
		if (isset($form['ta'])) $ta = $form['ta'];
		if (isset($form['nomor'])) $nomor = $form['nomor'];
		if (isset($form['tanggal'])) $tanggal = balikTanggal($form['tanggal']);
		if (isset($form['dasar_keluar'])) $dasar_keluar = $form['dasar_keluar'];
		if (isset($form['id_untuk'])) $id_untuk = $form['id_untuk'];
		if (isset($form['txtuntuk'])) $peruntukan = $form['txtuntuk'];
		if (isset($form['jenis_out'])) $jenis_out = $form['jenis_out'];
		else $jenis_out = "";
		if (isset($form['tgl_minta'])) $tgl_minta = $form['tgl_minta'];
		if (isset($form['no_reklas'])) $no_reklas = $form['no_reklas'];
		else $no_reklas = "";
		if (isset($form['tgl_reklas'])) $tgl_reklas = balikTanggal($form['tgl_reklas']);
		else $tgl_reklas = "";

		if ($jenis_out == 's') $peruntukan = "";
		else $id_untuk = "";

		$kode = "o" . $jenis_out;


		$qq = mysql_fetch_assoc(mysql_query("SELECT CONCAT_WS('.',kd_urusan, kd_bidang, kd_unit) AS kds FROM ref_sub2_unit WHERE nm_sub2_unit = '$_SESSION[nm_sub2_unit]' "));
		$lock = mysql_query("SELECT * FROM kunci_entri_skpd WHERE kd_skpd = '$qq[kds]' ");
		$jumlock = mysql_num_rows($lock);
		if ($jumlock > 0) {
			$gl = mysql_fetch_assoc($lock);

			if ($oper == "del") {
				$tanggal = balikTanggal($_POST["tanggal"]);
			} else if ($oper == "edit") {
				$tanggal = balikTanggal($form['tanggal']);
			}
			$tga = strtotime($tanggal);
			$tgb = strtotime($gl["kunci_sampai"]);
			$gl["kunci_sampai"] = balikTanggalIndo($gl["kunci_sampai"]);
			if ($tga <= $tgb) {
				// $allow = 0;
				$allow = 1;
			} else {
				$allow = 1;
			}

			$ket = $tga . " s/d " . $tgb;
		} else {
			$allow = 1;
			$gl["kunci_sampai"] = "";
			$ket = "";
		}

		if ($allow == 1) {


			switch ($oper) {
				case 'add':
					$u = mysql_fetch_row(mysql_query("SELECT UUID()"));
					$uuid = $u[0];
					mysql_query("INSERT INTO keluar(id_keluar, uuid_skpd, ta, id_sp_out, no_ba_out, tgl_ba_out,
											jenis_out, uuid_untuk, peruntukan, no_reklas, tgl_reklas, 											
											create_date, 
											creator_id,
											soft_delete)
									VALUES ('$uuid', '$id_sub', '$ta', '$dasar_keluar', '$nomor', '$tanggal', 
											'$jenis_out', '$id_untuk', '$peruntukan', '$no_reklas', '$tgl_reklas',
											'$datime',
											'$pengguna',
											'0')");
					if (mysql_errno() == 0) {
						$array_bar = "";
						$array_jml = "";
						$array_tgl = "";
						$array_gud = "";
						$array_sum = "";
						foreach ($basket as $val) {
							//$jumlah = preg_replace("/[^0-9]/","", $val['jumlah']); 
							$jumlah = $val['jumlah'];

							$jumlah = str_replace(".", "", $jumlah);
							$jumlah = str_replace(",", ".", $jumlah);
							$tgl_terima = balikTanggal($tanggal);
							if ($jenis_out == 'r') $tgl_minta = $tgl_terima;



							$array_bar .= "$val[id_bar],";
							$array_jml .= "$jumlah,";
							$array_tgl .= "$tgl_terima,";
							$array_gud .= "$val[id_gud],";
							$array_sum .= "$val[id_sum],";


							/* $ude = mysql_fetch_row(mysql_query("SELECT UUID()"));
					$uuidet = $ude[0];
					$kel = mysql_fetch_assoc(mysql_query("SELECT SUM(jml_in-jml_out) AS jml, id_kelompok 
								FROM kartu_stok k WHERE k.id_barang = '$val[id_bar]' AND k.uuid_skpd = '$id_sub' 
								AND id_sumber_dana = '$val[id_sum]' AND k.soft_delete = 0 GROUP BY k.id_kelompok HAVING jml <> 0
								LIMIT 1"));
					
					mysql_query("INSERT INTO keluar_detail ( id_keluar_detail, id_keluar, uuid_skpd,
															ta, tgl_minta, tgl_terima, id_gudang, id_kelompok,
															id_sumber_dana, id_barang, jml_barang, harga_barang, 
															create_date, 
															creator_id,
															soft_delete)
													VALUES( '$uuidet', '$uuid', '$id_sub', 
															'$ta', '$tgl_minta', '$tgl_terima', '$val[id_gud]', '$kel[id_kelompok]',
															'$val[id_sum]', '$val[id_bar]', '$jumlah', '$hargasat', 
															'$datime',
															'$pengguna',
															'0')");
					
					mysql_query("INSERT INTO kartu_stok (id_stok, uuid_skpd, id_barang, id_kelompok, id_gudang, 
														id_sumber_dana, id_transaksi, id_transaksi_detail,
														tgl_transaksi, ta, jml_in, jml_out, harga, kode,
														create_date, soft_delete, creator_id)
												VALUES	(UUID(), '$id_sub', '$val[id_bar]', '$kel[id_kelompok]', '$val[id_gud]', 
														'$val[id_sum]', '$uuid', '$uuidet',
														'$tgl_terima', '$ta', 0, '$jumlah', '$hargasat', '$kode',
														'$datime', 0, '$pengguna')"); */
						}

						if ($array_bar != "") {
							mysql_query("CALL ambil_harga_insert_keluar_detail('$array_bar', '$array_jml', '$array_tgl', 
										'$array_gud', '$array_sum', '$id_sub', '$uuid', '$ta', '$datime', 
										'$pengguna', '$tgl_minta', '$kode')");
						}

						//$jumsur="";
						//$jumbar="";
						/* $cekjum = mysql_query("SELECT SUM(jml_keluar) jml_kel,SUM(jml_barang) jml_bar FROM (				
							SELECT DISTINCT b.jml_barang AS jml_barang,c.jml_barang AS jml_keluar FROM keluar a
							INNER JOIN keluar_detail b ON a.uuid_skpd=b.uuid_skpd AND a.id_keluar=b.id_keluar
							INNER JOIN sp_out_detail c ON a.uuid_skpd=c.uuid_skpd AND a.id_sp_out=c.id_sp_out
							WHERE a.id_keluar='$uuid'
							)X"); */
						/* $cekjum = mysql_query("SELECT SUM(jml_keluar) jml_kel,SUM(jml_barang) jml_bar FROM (
						SELECT  a.jml_barang AS jml_barang,0 AS jml_keluar FROM keluar_detail a
						WHERE a.id_keluar='$uuid' AND a.soft_delete='0'
						UNION ALL
						SELECT 0 jml_barang,a.jml_barang AS jml_keluar FROM sp_out_detail a
						INNER JOIN keluar b ON a.uuid_skpd=b.uuid_skpd AND a.id_sp_out=b.id_sp_out
						WHERE b.id_keluar='$uuid' AND a.soft_delete='0' AND b.soft_delete='0'
						)X");
						while ($cekjum1 = mysql_fetch_assoc($cekjum)) {
						$jumsur .= "$cekjum1[jml_kel]";
						$jumbar .= "$cekjum1[jml_bar]";
						
						} */
						
						//if ($jumsur != $jumbar) {
						/* mysql_query("UPDATE keluar_detail
						SET jml_barang=jml_barang+($jumsur-$jumbar)
						WHERE id_keluar='$uuid' AND uuid_skpd='$id_sub'
						ORDER BY  id_keluar ASC LIMIT 1");
						
						mysql_query("UPDATE kartu_stok
						SET jml_out=jml_out+($jumsur-$jumbar)
						WHERE id_transaksi='$uuid' AND uuid_skpd='$id_sub'
						ORDER BY  id_transaksi ASC LIMIT 1"); */

						/* mysql_query("INSERT INTO keluar_detail ( id_keluar_detail, id_keluar, id_terima_detail, uuid_skpd,ta, tgl_minta, tgl_terima, id_gudang, id_kelompok,
						id_sumber_dana, id_barang, jml_barang, harga_barang, create_date, creator_id, soft_delete)
						SELECT UUID(), id_keluar, id_terima_detail, uuid_skpd,ta, tgl_minta, '$tgl_terima', id_gudang, id_kelompok,
						id_sumber_dana, id_barang, $jumsur-$jumbar, harga_barang, CURRENT_TIMESTAMP(), creator_id, 0 FROM keluar_detail
						WHERE id_keluar='$uuid' AND uuid_skpd='$id_sub'
						ORDER BY  id_keluar ASC LIMIT 1");

						mysql_query("INSERT INTO kartu_stok (id_stok, uuid_skpd, id_barang, id_kelompok, id_gudang, id_sumber_dana, id_transaksi, id_transaksi_detail,
						tgl_transaksi, ta, jml_in, jml_out, harga, kode, create_date, soft_delete, creator_id)
						SELECT UUID(), uuid_skpd, id_barang, id_kelompok, id_gudang, id_sumber_dana, id_transaksi, id_transaksi_detail,
						'$tgl_terima', ta, 0,$jumsur-$jumbar, harga, kode, CURRENT_TIMESTAMP(), 0, creator_id FROM kartu_stok
						WHERE id_transaksi='$uuid' AND uuid_skpd='$id_sub'
						ORDER BY  id_transaksi ASC LIMIT 1");
						}  */

						if (mysql_errno() != 0) echo mysql_error();
						if ($dasar_keluar != "") {
							mysql_query("UPDATE sp_out o LEFT JOIN surat_minta m ON o.id_surat_minta = m.id_surat_minta
								SET o.status = 3, m.status = 3 WHERE id_sp_out = '$dasar_keluar'");
						}

						if (mysql_errno() == 0) echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil ditambahkan !"));
						else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menambahkan data ! ", 'kode' => mysql_errno()));
					} else {
						if (mysql_errno() == 1062) {
							echo json_encode(array(
								'success' => false,
								'pesan' => "Surat Perintah sudah ada !",
								'error' => "nomor_sama"
							));
						} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menambahkan data ! ", 'kode' => mysql_errno()));
					}

					break;
					/* case 'edit':
			if($ubahform!=''){
				$dasar_lama = ""; $dasar_baru = "";
				$form = explode("||", $ubahform);
				foreach($form as $field){
					$f = explode('::',$field);
					$v = explode('|',$field);
					if($f[0]=='dasar_keluar'){
						$dasar_lama = $v[0];
						$dasar_baru = $v[1];
					}
				}
				
				// if($dataubah!=""){
				//	$dataubah = substr($dataubah, 0, -2);
				//	mysql_query("UPDATE keluar_detail SET $dataubah , update_date = '$datime' WHERE id_keluar = '$_GET[id_ubah]'");
				//}
				
				mysql_query("UPDATE keluar SET 	no_ba_out = '$nomor', tgl_ba_out = '$tanggal', id_sp_out = '$dasar_keluar'
												update_date = '$datime'  
									WHERE id_keluar = '$_GET[id_ubah]'");

			}
			
			if($dasar_baru!=""){
				mysql_query("UPDATE keluar_detail SET soft_delete = '1' WHERE id_keluar = '$_GET[id_ubah]'");
				mysql_query("UPDATE kartu_stok SET soft_delete = '1' WHERE id_transaksi = '$_GET[id_ubah]'");
				mysql_query("UPDATE sp_out SET status = '0' WHERE id_sp_out = '$dasar_lama'");
				
				
				foreach($basket AS $val){
					$harga = preg_replace("/[^0-9]/","", $val['jmlhrg_asli']);	
					$jumlah = preg_replace("/[^0-9]/","", $val['jumlah']); 
					$hargasat = $harga / $jumlah;
					$tgl_minta = balikTanggal($val['tgl_minta']);
					$tgl_terima = balikTanggal($val['tgl_terima']);
					$ude = mysql_fetch_row(mysql_query("SELECT UUID()"));
					$uuidet = $ude[0];
					mysql_query("INSERT INTO keluar_detail ( id_keluar_detail, id_keluar, uuid_skpd,
															ta, tgl_minta, tgl_terima, id_gudang,
															id_barang, jml_barang, harga_barang, keterangan,
															create_date, 
															creator_id,
															soft_delete)
													VALUES( '$uuidet', '$_GET[id_ubah]', '$id_sub', 
															'$ta', '$tgl_minta', '$tgl_terima', '$val[id_gud]',
															'$val[id_bar]', '$jumlah', '$hargasat', '$val[ket]',
															'$datime',
															'$pengguna',
															'0')");
					$kel = mysql_fetch_assoc(mysql_query("SELECT SUM(jml_in-jml_out) AS jml, id_kelompok 
								FROM kartu_stok k WHERE k.id_barang = '$val[id_bar]' AND k.uuid_skpd = '$id_sub' 
								AND id_gudang = '$val[id_gud]' AND k.soft_delete = 0 GROUP BY k.id_kelompok HAVING jml <> 0
								LIMIT 1"));
					
					
					mysql_query("INSERT INTO kartu_stok (id_stok, uuid_skpd, id_barang, id_kelompok, id_gudang, 
														id_transaksi, id_transaksi_detail,
														tgl_transaksi, ta, jml_in, jml_out, harga, kode,
														create_date, soft_delete, creator_id)
												VALUES	(UUID(), '$id_sub', '$val[id_bar]', '$kel[id_kelompok]', '$val[id_gud]', 
														'$_GET[id_ubah]', '$uuidet',
														'$tgl_terima', '$ta', 0, '$jumlah', '$hargasat', 'o',
														'$datime', 0, '$pengguna')");
				}
				if($dasar_baru!="") mysql_query("UPDATE sp_out SET status = '1' WHERE id_sp_out = '$dasar_baru'");
				
			}else{
				$datser = mysql_query("SELECT k.id_barang AS id_bar, jml_barang AS jumlah, id_gudang AS id_gud, 
										DATE_FORMAT(tgl_minta, '%d-%m-%Y') AS tgl_minta, DATE_FORMAT(tgl_terima, '%d-%m-%Y') AS tgl_terima,
										harga_barang AS harga_asli, k.keterangan AS ket, id_keluar_detail AS id
										FROM keluar_detail k
										WHERE id_keluar = '$_GET[id_ubah]' AND k.soft_delete=0");
				
				$edit = array(); $add = array(); $del = array();
				while($da = mysql_fetch_assoc($datser)){
					$cek = "";
					foreach ($basket as $key => $val){
						if(isset($val['id'])){ //data lama 
							if($val['id']==$da['id']){ //data lama masih ada
								$isi = ""; $isi2 = "";
								$jml = preg_replace("/[^0-9]/","", $val['jumlah']);
								$harga = preg_replace("/[^0-9]/","", $val['jmlhrg_asli']);	
								$hargasat = $harga / $jml;
								
								if($da['id_bar']!=$val['id_bar']){ 
									$isi .= "id_barang = '$val[id_bar]', "; 
									$isi2 .= "id_barang = '$val[id_bar]', "; }
								if($da['jumlah']!=$jml){
									$isi .= "jml_barang = '$jml', ";
									$isi2 .= "jml_out = '$jml', "; }
								if($da['harga_asli']!=$hargasat){
									$isi .= "harga_barang = '$hrg', ";
									$isi2 .= "harga = '$hrg', "; }
								if($da['id_gud']!=$val['id_gud']) $isi .= "id_gudang = '$val[id_gud]', "; 
								if($da['tgl_minta']!=$val['tgl_minta']) $isi .= "tgl_minta = '$tglmin', ";
								if($da['tgl_terima']!=$val['tgl_terima']){
									$isi .= "tgl_terima = '$tglter', ";
									$isi2 .= "tgl_transaksi = '$tglter', "; }
										
								if($isi!=""){
									$ed['id'] = $val['id'];
									$ed['isi'] = $isi;
									$ed['isi2'] = $isi2;
									array_push($edit, $ed);
									unset($basket[$key]);
								}
								$cek = 'ada';
							}
						}
					}
					if($cek=="") array_push($del, $da['id']); //data lama dihapus
				}
				$add = $basket;
				
				foreach($edit as $e){
					mysql_query("UPDATE keluar_detail SET $e[isi] update_date = '$datime' WHERE id_keluar_detail = '$e[id]'");
					if($e['isi2']!='') mysql_query("UPDATE kartu_stok SET $e[isi2] update_date = '$datime' WHERE id_transaksi_detail = '$e[id]'");
				}
				
				foreach($add as $val){
					$harga = preg_replace("/[^0-9]/","", $val['jmlhrg_asli']);	
					$jumlah = preg_replace("/[^0-9]/","", $val['jumlah']); 
					$hargasat = $harga / $jumlah;
					$tgl_minta = balikTanggal($val['tgl_minta']);
					$tgl_terima = balikTanggal($val['tgl_terima']);
					$ude = mysql_fetch_row(mysql_query("SELECT UUID()"));
					$uuidet = $ude[0];
					
					$kel = mysql_fetch_assoc(mysql_query("SELECT SUM(jml_in-jml_out) AS jml, id_kelompok 
								FROM kartu_stok k WHERE k.id_barang = '$val[id_bar]' AND k.uuid_skpd = '$id_sub' 
								AND id_gudang = '$val[id_gud]' AND k.soft_delete = 0 GROUP BY k.id_kelompok HAVING jml <> 0
								LIMIT 1"));
					
					mysql_query("INSERT INTO keluar_detail ( id_keluar_detail, id_keluar, uuid_skpd,
															ta, tgl_minta, tgl_terima, id_gudang, id_kelompok,
															id_barang, jml_barang, harga_barang, keterangan,
															create_date, 
															creator_id,
															soft_delete)
													VALUES( '$uuidet', '$_GET[id_ubah]', '$id_sub', 
															'$ta', '$tgl_minta', '$tgl_terima', '$val[id_gud]', '$kel[id_kelompok]',
															'$val[id_bar]', '$jumlah', '$hargasat', '$val[ket]',
															'$datime',
															'$pengguna',
															'0')");
					
						mysql_query("INSERT INTO kartu_stok (id_stok, uuid_skpd, id_barang, id_kelompok, id_gudang, 
														id_transaksi, id_transaksi_detail,
														tgl_transaksi, ta, jml_in, jml_out, harga, kode,
														create_date, soft_delete, creator_id)
												VALUES	(UUID(), '$id_sub', '$val[id_bar]', '$kel[id_kelompok]', '$val[id_gud]', 
														'$_GET[id_ubah]', '$uuidet',
														'$tgl_terima', '$ta', 0, '$jumlah', '$hargasat', 'o',
														'$datime', 0, '$pengguna')");										
				}
				
				foreach($del as $id){
					mysql_query("UPDATE keluar_detail SET soft_delete = '1' WHERE id_keluar_detail = '$id' AND id_keluar = '$_GET[id_ubah]'");
					mysql_query("UPDATE kartu_stok SET soft_delete = '1' WHERE id_transaksi_detail = '$id' AND id_transaksi = '$_GET[id_ubah]'");
				}
			}
			
			if(mysql_errno()==0){
 				echo json_encode(array('success'=>true, 'pesan'=>"Data telah berhasil diubah !"));
            }else{
				if(mysql_errno()==1062){ 
					echo json_encode(array('success'=>false, 
											'pesan'=>"Kode Barang Sudah Ada di Unit ini !", 
											'error'=>"nomor_sama"));
				}else echo json_encode(array('success'=>false, 'pesan'=>"Tidak berhasil mengubah data ! ".mysql_errno()));
			}
			
		
		break; */
				case 'del':
					mysql_query("UPDATE keluar k LEFT JOIN sp_out s ON k.id_sp_out = s.id_sp_out
										 LEFT JOIN surat_minta m ON m.id_surat_minta = s.id_surat_minta
						SET k.soft_delete = 1, s.status = 1, m.status = 1 WHERE id_keluar = '$_POST[id_hapus]'");
					mysql_query("UPDATE keluar_detail SET soft_delete = '1' WHERE id_keluar = '$_POST[id_hapus]'");
					mysql_query("UPDATE kartu_stok SET soft_delete = '1', update_date = NOW() WHERE id_transaksi = '$_POST[id_hapus]'");
					if (mysql_errno() == 0) {

						mysql_query(" INSERT INTO log_del VALUES(UUID(), NOW(), '2019', '$pengguna', '', 'keluar', '$_POST[id_hapus]', 'hapus', '', '') ");

						echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil dihapuskan ! "));
					} else {
						echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menghapus data ! ", 'kode' => mysql_errno()));
					}
					break;
			}
		} else {
			echo json_encode(array('success' => false, 'pesan' => " Entri Anda Dikunci s/d " . $gl["kunci_sampai"]));
		}
	} elseif ($module == "mutasi_gudang") {
		if (isset($_REQUEST['form'])) $form = $_REQUEST['form'];
		if (isset($_REQUEST['basket'])) $basket = $_REQUEST['basket'];
		if (isset($_REQUEST['ubahform'])) $ubahform = $_REQUEST['ubahform'];
		if (isset($form['id_sub'])) $id_sub = $form['id_sub'];
		if (isset($form['ta'])) $ta = $form['ta'];
		if (isset($form['nomor'])) $nomor = $form['nomor'];
		if (isset($form['tanggal'])) $tanggal = balikTanggal($form['tanggal']);
		if (isset($form['id_pengurus'])) $id_pengurus = $form['id_pengurus'];
		else $id_pengurus = "";
		if (isset($form['id_pengguna'])) $id_pengguna = $form['id_pengguna'];
		else $id_pengguna = "";
		if (isset($form['dari_gud'])) $dari_gud = $form['dari_gud'];
		if (isset($form['ke_gud'])) $ke_gud = $form['ke_gud'];


		switch ($oper) {
			case 'add':
				$u = mysql_fetch_row(mysql_query("SELECT UUID()"));
				$uuid = $u[0];
				mysql_query("INSERT INTO mutasi(id_mutasi, uuid_skpd,
											ta, no_ba_mutasi, tgl_ba_mutasi, 
											gudang_asal, gudang_tujuan,
											id_pejabat_pengguna, id_pejabat_pengurus,
											create_date, 
											creator_id,
											soft_delete)
									VALUES ('$uuid', '$id_sub',
											'$ta', '$nomor', '$tanggal', 
											'$dari_gud', '$ke_gud',
											'$id_pengguna', '$id_pengurus',
											'$datime',
											'$pengguna',
											'0')");
				if (mysql_errno() == 0) {
					foreach ($basket as $val) {
						$harga = preg_replace("/[^0-9]/", "", $val['harga_asli']);
						$jumlah = preg_replace("/[^0-9]/", "", $val['jumlah']);
						$hargasat = $harga / $jumlah;
						$ude = mysql_fetch_row(mysql_query("SELECT UUID()"));
						$uuidet = $ude[0];
						mysql_query("INSERT INTO mutasi_detail ( id_mutasi_detail, id_mutasi, uuid_skpd, id_sumber_dana,
															ta,	id_barang, jml_barang, harga_barang, keterangan,
															create_date, 
															creator_id,
															soft_delete)
													VALUES( '$uuidet', '$uuid', '$id_sub', '$val[id_sum]',
															'$ta', '$val[id_bar]', '$jumlah', '$hargasat', '$val[ket]',
															'$datime',
															'$pengguna',
															'0')");

						$kel = mysql_fetch_assoc(mysql_query("SELECT SUM(jml_in-jml_out) AS jml, id_kelompok 
								FROM kartu_stok k WHERE k.id_barang = '$val[id_bar]' AND k.uuid_skpd = '$id_sub' 
								AND id_gudang = '$dari_gud' AND k.soft_delete = 0 GROUP BY k.id_kelompok HAVING jml <> 0
								LIMIT 1"));


						mysql_query("INSERT INTO kartu_stok (id_stok, uuid_skpd, id_barang, id_kelompok, id_gudang, 
														id_transaksi, id_transaksi_detail, id_sumber_dana,
														tgl_transaksi, ta, jml_in, jml_out, harga, kode,
														create_date, soft_delete, creator_id)
												VALUES	(UUID(), '$id_sub', '$val[id_bar]', '$kel[id_kelompok]', '$dari_gud', 
														'$uuid', '$uuidet', '$val[id_sum]',
														'$tanggal', '$ta', 0, '$jumlah', '$hargasat', 'm',
														'$datime', 0, '$pengguna')");
						mysql_query("INSERT INTO kartu_stok (id_stok, uuid_skpd, id_barang, id_kelompok, id_gudang, 
														id_transaksi, id_transaksi_detail, id_sumber_dana,
														tgl_transaksi, ta, jml_in, jml_out, harga, kode,
														create_date, soft_delete, creator_id)
												VALUES	(UUID(), '$id_sub', '$val[id_bar]', '$kel[id_kelompok]', '$ke_gud', 
														'$uuid', '$uuidet', '$val[id_sum]',
														'$tanggal', '$ta', '$jumlah', 0, '$hargasat', 'm',
														'$datime', 0, '$pengguna')");
					}

					if (mysql_errno() == 0) echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil ditambahkan !"));
					else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menambahkan data ! ", 'kode' => mysql_errno()));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Data Mutasi sudah ada !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menambahkan data ! ", 'kode' => mysql_errno()));
				}

				break;
			case 'edit':
				if ($ubahform != '') {
					$dataubah = "";
					$form = explode("||", $ubahform);
					foreach ($form as $field) {
						$f = explode('::', $field);
						$v = explode('|', $field);
						if ($f[0] == 'id_sub') {
							$id_sub_ganti = $v[1];
							$dataubah .= "uuid_skpd = '$id_sub_ganti', ";
						} elseif ($f[0] == 'ta') {
							$ta_ganti = $v[1];
							$dataubah .= "ta = '$ta_ganti', ";
						}
					}

					if ($dataubah != "") {
						$dataubah = substr($dataubah, 0, -2);
						mysql_query("UPDATE mutasi_detail SET $dataubah , update_date = '$datime' WHERE id_mutasi = '$_GET[id_ubah]'");
					}

					mysql_query("UPDATE mutasi SET ta = '$ta', no_ba_mutasi = '$nomor', tgl_ba_mutasi = '$tanggal', 
												id_pejabat_pengguna = '$id_pengguna', id_pejabat_pengurus = '$id_pengurus',
												gudang_asal = '$dari_gud', gudang_tujuan = '$ke_gud',
												update_date = '$datime'  
									WHERE id_mutasi = '$_GET[id_ubah]'");
				}



				$datser = mysql_query("SELECT k.id_barang AS id_bar, jml_barang AS jumlah, 
									harga_barang AS harga_asli, k.keterangan AS ket, id_mutasi_detail AS id
									FROM mutasi_detail k
									WHERE id_mutasi = '$_GET[id_ubah]' AND k.soft_delete=0");

				$edit = array();
				$add = array();
				$del = array();
				while ($da = mysql_fetch_assoc($datser)) {
					$cek = "";
					foreach ($basket as $key => $val) {
						if (isset($val['id'])) { //data lama 
							if ($val['id'] == $da['id']) { //data lama masih ada
								$isi = "";
								$isi2 = "";
								$isi3 = "";
								$jml = preg_replace("/[^0-9]/", "", $val['jumlah']);
								$hrg = number_format($val['harga_sat'], 2, '.', '');

								if ($da['id_bar'] != $val['id_bar']) {
									$isi .= "id_barang = '$val[id_bar]', ";
									$isi2 .= "id_barang = '$val[id_bar]', ";
									$isi3 .= "id_barang = '$val[id_bar]', ";
								}
								if ($da['jumlah'] != $jml) {
									$isi .= "jml_barang = '$jml', ";
									$isi2 .= "jml_in = '$jml', ";
									$isi2 .= "jml_out = '$jml', ";
								}
								if ($da['harga_asli'] != $hrg) {
									$isi .= "harga_barang = '$hrg', ";
									$isi2 .= "harga = '$hrg', ";
									$isi3 .= "harga = '$hrg', ";
								}

								if ($isi != "") {
									$ed['id'] = $val['id'];
									$ed['isi'] = $isi;
									$ed['isi2'] = $isi2;
									$ed['isi3'] = $isi3;
									array_push($edit, $ed);
									unset($basket[$key]);
								}
								$cek = 'ada';
							}
						} else { //data baru
							array_push($add, $basket[$key]);
							unset($basket[$key]);
						}
					}
					if ($cek == "") array_push($del, $da['id']); //data lama dihapus
				}

				foreach ($edit as $e) {
					mysql_query("UPDATE mutasi_detail SET $e[isi] update_date = '$datime' WHERE id_mutasi_detail = '$e[id]' AND id_mutasi = '$_GET[id_ubah]'");
					if ($e['isi2'] != '') mysql_query("UPDATE kartu_stok SET $e[isi2] update_date = '$datime' WHERE id_transaksi_detail = '$e[id]' AND id_transaksi = '$_GET[id_ubah]' AND jml_in <> 0");
					if ($e['isi3'] != '') mysql_query("UPDATE kartu_stok SET $e[isi3] update_date = '$datime' WHERE id_transaksi_detail = '$e[id]' AND id_transaksi = '$_GET[id_ubah]' AND jml_out <> 0");
				}

				foreach ($add as $val) {
					$harga = preg_replace("/[^0-9]/", "", $val['harga_asli']);
					$jumlah = preg_replace("/[^0-9]/", "", $val['jumlah']);
					$hargasat = $harga / $jumlah;
					$ude = mysql_fetch_row(mysql_query("SELECT UUID()"));
					$uuidet = $ude[0];

					mysql_query("INSERT INTO mutasi_detail ( id_mutasi_detail, id_mutasi, uuid_skpd,
															ta,	id_barang, jml_barang, harga_barang, keterangan,
															create_date, 
															creator_id,
															soft_delete)
													VALUES( '$uuidet', '$_GET[id_ubah]','$id_sub',
															'$ta', '$val[id_bar]', '$jumlah', '$hargasat', '$val[ket]',
															'$datime',
															'$pengguna',
															'0')");
					$kel = mysql_fetch_assoc(mysql_query("SELECT SUM(jml_in-jml_out) AS jml, id_kelompok 
								FROM kartu_stok k WHERE k.id_barang = '$val[id_bar]' AND k.uuid_skpd = '$id_sub' 
								AND k.soft_delete = 0 GROUP BY k.id_kelompok HAVING jml <> 0
								LIMIT 1"));


					mysql_query("INSERT INTO kartu_stok (id_stok, uuid_skpd, id_barang, id_kelompok, id_gudang, 
														id_transaksi, id_transaksi_detail,
														tgl_transaksi, ta, jml_in, jml_out, harga, kode,
														create_date, soft_delete, creator_id)
												VALUES	(UUID(), '$id_sub', '$val[id_bar]', '$kel[id_kelompok]', '$dari_gud', 
														'$_GET[id_ubah]', '$uuidet',
														'$tanggal', '$ta', 0, '$jumlah', '$hargasat', 'm',
														'$datime', 0, '$pengguna')");
					mysql_query("INSERT INTO kartu_stok (id_stok, uuid_skpd, id_barang, id_kelompok, id_gudang, 
														id_transaksi, id_transaksi_detail,
														tgl_transaksi, ta, jml_in, jml_out, harga, kode,
														create_date, soft_delete, creator_id)
												VALUES	(UUID(), '$id_sub', '$val[id_bar]', '$kel[id_kelompok]', '$ke_gud', 
														'$_GET[id_ubah]', '$uuidet',
														'$tanggal', '$ta', '$jumlah', 0, '$hargasat', 'm',
														'$datime', 0, '$pengguna')");
				}

				foreach ($del as $id) {
					mysql_query("UPDATE mutasi_detail SET soft_delete = '1' WHERE id_mutasi_detail = '$id' AND id_mutasi = '$_GET[id_ubah]'");
					mysql_query("UPDATE kartu_stok SET soft_delete = '1' WHERE id_transaksi_detail = '$id' AND id_transaksi = '$_GET[id_ubah]'");
				}


				if (mysql_errno() == 0) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil diubah !"));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Kode Barang Sudah Ada di Unit ini !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil mengubah data ! " . mysql_errno()));
				}


				break;
			case 'del':
				mysql_query("UPDATE mutasi SET soft_delete = '1' WHERE id_mutasi = '$_POST[id_hapus]'");
				mysql_query("UPDATE mutasi_detail SET soft_delete = '1' WHERE id_mutasi = '$_POST[id_hapus]'");
				mysql_query("UPDATE kartu_stok SET soft_delete = '1' WHERE id_transaksi = '$_POST[id_hapus]'");
				if (mysql_errno() == 0) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil dihapuskan !"));
				} else {
					echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menghapus data ! ", 'kode' => mysql_errno()));
				}


				break;
		}
	} elseif ($module == "konfirm_penyaluran") {
		if (isset($_REQUEST['form'])) $form = $_REQUEST['form'];
		if (isset($_REQUEST['ubahform'])) $ubahform = $_REQUEST['ubahform'];
		if (isset($form['id_subt'])) $id_sub = $form['id_subt'];
		if (isset($form['ta'])) $ta = $form['ta'];
		if (isset($form['nomor'])) $nomor = $form['nomor'];
		if (isset($form['tgl_terima'])) $tgl_terima = balikTanggal($form['tgl_terima']);
		if (isset($form['id_gudang'])) $id_gudang = $form['id_gudang'];
		if (isset($form['id'])) $id_keluar = $form['id'];

		switch ($oper) {
			case 'konfirm':


				$kode = mysql_fetch_assoc(mysql_query("SELECT * FROM ref_sub2_unit WHERE uuid_sub2_unit = '$id_sub' "));
				if ($kode["kd_sub2"] == 1) {
					$kode_ket = "r";
				} else {
					$kode_ket = "";
				}


				$u = mysql_fetch_row(mysql_query("SELECT UUID()"));
				$uuid = $u[0];
				mysql_query("INSERT INTO terima_keluar(id_terima_keluar, uuid_skpd, id_keluar, ta, tgl_terima, id_gudang, 
													create_date, 
													creator_id,
													soft_delete)
											VALUES ('$uuid', '$id_sub', '$id_keluar', '$ta', '$tgl_terima', '$id_gudang',
													'$datime',
													'$pengguna',
													'0')");
				if (mysql_errno() == 0) {

					$keldel = mysql_query("SELECT uuid_skpd, ta, id_kelompok, id_barang, id_sumber_dana,
										jml_barang, harga_barang
								FROM keluar_detail WHERE id_keluar = '$_GET[id]' ");
					while ($kd = mysql_fetch_assoc($keldel)) {
						$ut = mysql_fetch_row(mysql_query("SELECT UUID()"));
						$uuidet = $ut[0];
						mysql_query("INSERT INTO terima_keluar_detail (id_terima_keluar_detail, id_terima_keluar, uuid_skpd, ta, 
																	id_kelompok, id_barang, id_sumber_dana,
																	jml_barang, harga_barang,
																	create_date, creator_id)
															VALUES('$uuidet', '$uuid', '$id_sub', '$kd[ta]',
																	'$kd[id_kelompok]', '$kd[id_barang]', '$kd[id_sumber_dana]',
																	'$kd[jml_barang]', '$kd[harga_barang]',
																	'$datime', '$pengguna')");

						mysql_query("INSERT INTO kartu_stok (id_stok, uuid_skpd, id_barang, id_kelompok, id_gudang, 
														id_transaksi, id_transaksi_detail, id_sumber_dana,
														tgl_transaksi, ta, jml_in, jml_out, harga, keterangan, kode,
														create_date, soft_delete, creator_id)
												VALUES	(UUID(), '$id_sub', '$kd[id_barang]', '$kd[id_kelompok]', '$id_gudang', 
														'$uuid', '$uuidet', '$kd[id_sumber_dana]',
														'$tgl_terima', '$kd[ta]', '$kd[jml_barang]', 0, '$kd[harga_barang]', '$kode_ket', 'r',
														'$datime', 0, '$pengguna')");
					}


					mysql_query("UPDATE keluar SET status = 2 WHERE id_keluar = '$_GET[id]'");

					if (mysql_errno() == 0) echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil ditambahkan !"));
					else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menambahkan data ! ", 'kode' => mysql_errno()));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Nomor Penerimaan sudah ada !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menambahkan data ! ", 'kode' => mysql_errno()));
				}


				break;
		}
	} elseif ($module == "stok_opname") {
		if (isset($_REQUEST['form'])) $form = $_REQUEST['form'];
		if (isset($_REQUEST['basket'])) $basket = $_REQUEST['basket'];
		if (isset($_REQUEST['ubahform'])) $ubahform = $_REQUEST['ubahform'];
		if (isset($form['id_sub'])) $id_sub = $form['id_sub'];
		if (isset($form['nomor'])) $nomor = $form['nomor'];
		if (isset($form['tanggal'])) $tanggal = balikTanggal($form['tanggal']);

		if (isset($form['nama1'])) $nama1 = $form['nama1'];
		if (isset($form['nama2'])) $nama2 = $form['nama2'];
		if (isset($form['nama3'])) $nama3 = $form['nama3'];
		if (isset($form['nama4'])) $nama4 = $form['nama4'];
		if (isset($form['nama5'])) $nama5 = $form['nama5'];
		if (isset($form['nama6'])) $nama6 = $form['nama6'];
		if (isset($form['nip1'])) $nip1 = $form['nip1'];
		if (isset($form['nip2'])) $nip2 = $form['nip2'];
		if (isset($form['nip3'])) $nip3 = $form['nip3'];
		if (isset($form['nip4'])) $nip4 = $form['nip4'];
		if (isset($form['nip5'])) $nip5 = $form['nip5'];
		if (isset($form['nip6'])) $nip6 = $form['nip6'];
		if (isset($form['gol1'])) $gol1 = $form['gol1'];
		if (isset($form['gol2'])) $gol2 = $form['gol2'];
		if (isset($form['gol3'])) $gol3 = $form['gol3'];
		if (isset($form['gol4'])) $gol4 = $form['gol4'];
		if (isset($form['gol5'])) $gol5 = $form['gol5'];
		if (isset($form['gol6'])) $gol6 = $form['gol6'];

		$ta = date('Y', strtotime($tanggal));

		switch ($oper) {
			case 'add':
				$u = mysql_fetch_row(mysql_query("SELECT UUID()"));
				$uuid = $u[0];
				mysql_query("INSERT INTO pemeriksa_so VALUES ('$uuid','$nama1','$nama2','$nama3','$nama4','$nama5','$nama6','$nip1','$nip2','$nip3','$nip4','$nip5','$nip6','$gol1','$gol2','$gol3','$gol4','$gol5','$gol6','0')");
				mysql_query("INSERT INTO so(id_so, uuid_skpd, ta, no_so, tgl_so, 
										create_date, 
										creator_id,
										soft_delete)
								VALUES ('$uuid', '$id_sub', '$ta', '$nomor', '$tanggal', 
										'$datime',
										'$pengguna',
										'0')");
				if (mysql_errno() == 0) {
					$hrgtemp = $idbartemp = "";
					foreach ($basket as $val) {
						$harga_komp = preg_replace("/[^0-9-]/", "", $val['hrgsat_admin']);
						$harga_fisik = preg_replace("/[^0-9]/", "", $val['hrgsat_so']);
						$jml_komp = preg_replace("/[^0-9-]/", "", $val['jml_admin']);
						$jml_fisik = preg_replace("/[^0-9]/", "", $val['jml_so']);
						$slsh = $jml_komp - $jml_fisik;
						$hrgslsh = $harga_komp - $harga_fisik;

						$ut = mysql_fetch_row(mysql_query("SELECT UUID()"));
						$uuidet = $ut[0];

						$kel = mysql_fetch_assoc(mysql_query("SELECT SUM(jml_in-jml_out) AS jml, id_kelompok 
								FROM kartu_stok k WHERE k.id_barang = '$val[id_bar]' AND k.uuid_skpd = '$id_sub' 
								AND id_sumber_dana = '$val[id_sum]' AND k.soft_delete = 0 GROUP BY k.id_kelompok HAVING jml <> 0
								LIMIT 1"));

						mysql_query("INSERT INTO so_detail ( id_so_detail, id_so, uuid_skpd, id_gudang, id_sumber_dana,
														ta, id_barang, jml_komp, jml_fisik, harga_komp, harga_fisik, 
														keterangan,
														create_date, 
														creator_id,
														soft_delete)
												VALUES( '$uuidet', '$uuid', '$id_sub', '$val[id_gud]', '$val[id_sum]',
														'$ta', '$val[id_bar]', '$jml_komp', '$jml_fisik', '$harga_komp', '$harga_fisik',
														'',
														'$datime',
														'$pengguna',
														'0')");
						if ($slsh != 0 && $hrgslsh == 0) {
							//if($slsh>0){ $jml_in = 0; $jml_out = $slsh; }
							//else{ $jml_in = abs($slsh); $jml_out = 0; }

							$jml_in = 0;
							$jml_out = $slsh;

							mysql_query("INSERT INTO kartu_stok (id_stok, uuid_skpd, id_barang, id_kelompok, id_gudang, 
															id_transaksi, id_transaksi_detail, id_sumber_dana,
															tgl_transaksi, ta, jml_in, jml_out, harga, kode,
															create_date, soft_delete, creator_id)
													VALUES	(UUID(), '$id_sub', '$val[id_bar]', '$kel[id_kelompok]', '$val[id_gud]', 
															'$uuid', '$uuidet', '$val[id_sum]',
															'$tanggal', '$ta', '$jml_in', '$jml_out', '$harga_fisik', 's',
															'$datime', 0, '$pengguna')");
						} elseif ($hrgslsh != 0) {
							if ($hrgtemp != $harga_komp && $idbartemp != $val['id_bar']) {
								//Keluarkan data barang komputer
								mysql_query("INSERT INTO kartu_stok (id_stok, uuid_skpd, id_barang, id_kelompok, id_gudang, 
															id_transaksi, id_transaksi_detail, id_sumber_dana,
															tgl_transaksi, ta, jml_in, jml_out, harga, kode,
															create_date, soft_delete, creator_id)
													VALUES	(UUID(), '$id_sub', '$val[id_bar]', 
															'$kel[id_kelompok]', '$val[id_gud]', 
															'$uuid', '$uuidet', '$val[id_sum]',
															'$tanggal', '$ta', '0', '$jml_komp', 
															'$harga_komp', 's',
															'$datime', 0, '$pengguna')");
							}

							if ($jml_fisik != 0 && $harga_fisik != 0) {
								//Masukkan data barang opnamenya									
								mysql_query("INSERT INTO kartu_stok (id_stok, uuid_skpd, id_barang, id_kelompok, id_gudang, 
															id_transaksi, id_transaksi_detail, id_sumber_dana,
															tgl_transaksi, ta, jml_in, jml_out, harga, kode,
															create_date, soft_delete, creator_id)
													VALUES	(UUID(), '$id_sub', '$val[id_bar]', 
															'$kel[id_kelompok]', '$val[id_gud]', 
															'$uuid', '$uuidet', '$val[id_sum]',
															'$tanggal', '$ta', '$jml_fisik', '0', 
															'$harga_fisik', 's',
															'$datime', 0, '$pengguna')");
							}
						}
						$hrgtemp = $harga_komp;
						$idbartemp = $val['id_bar'];
					}

					if (mysql_errno() == 0) echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil ditambahkan !"));
					else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menambahkan data ! ", 'kode' => mysql_errno()));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Nomor Stok Opname sudah ada !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menambahkan data ! ", 'kode' => mysql_errno()));
				}

				break;
				/* case 'edit':
			if($ubahform!=''){
				$dataubah = "";
				$form = explode("||", $ubahform);
				foreach($form as $field){
					$f = explode('::',$field);
					$v = explode('|',$field);
					if($f[0]=='id_sub'){
						$id_sub_ganti = $v[1];
						$dataubah .= "uuid_skpd = '$id_sub_ganti', ";
					}elseif($f[0]=='tanggal'){
						$ta_ganti = date('Y', strtotime($v[1]));
						$dataubah .= "ta = '$ta_ganti', ";
					}
				}
				
				if($dataubah!=""){
					$dataubah = substr($dataubah, 0, -2);
					mysql_query("UPDATE so_detail SET $dataubah , update_date = '$datime' WHERE id_so = '$_GET[id_ubah]'");
				}
				
				mysql_query("UPDATE so SET uuid_skpd = '$id_sub', ta = '$ta', no_so = '$nomor', tgl_so = '$tanggal', 
												update_date = '$datime'  
									WHERE id_so = '$_GET[id_ubah]'");

			}
			
			
			
			$datser = mysql_query("SELECT jml_komp AS jml_admin, jml_fisik AS jml_so, harga_komp AS hrgsat_admin, 
									harga_fisik AS hrgsat_so, id_barang AS id_bar, id_so_detail AS id_det
									FROM so_detail
									WHERE id_so = '$_GET[id_ubah]' AND soft_delete=0");
			
			$edit = array(); $add = array(); $del = array();
			while($da = mysql_fetch_assoc($datser)){
				$cek = "";
				foreach ($basket as $key => $val){
					if(isset($val['id_det'])){ //data lama 
						if($val['id_det']==$da['id_det']){ //data lama masih ada
							$isi = "";
							preg_match('/-?[0-9]+/', $val['jml_admin'], $jml);
							$jml_admin = $jml[0];
							//$jml_admin = preg_replace("/[^0-9-]/","", $val['jml_admin']);
							$jml_so = preg_replace("/[^0-9]/","", $val['jml_so']);
							$hrgsat_admin = preg_replace("/[^0-9]/","", $val['hrgsat_admin']);
							$hrgsat_so = preg_replace("/[^0-9]/","", $val['hrgsat_so']);
							//$hrg = number_format($val['harga_sat'],2,'.','');
							
							if($da['id_bar']!=$val['id_bar']) $isi .= "id_barang = '$val[id_bar]', "; 
							if($da['jml_admin']!=$jml_admin) $isi .= "jml_komp = '$jml_admin', ";
							if($da['jml_so']!=$jml_so) $isi .= "jml_fisik = '$jml_so', ";
							if($da['hrgsat_admin']!=$hrgsat_admin) $isi .= "harga_komp = '$hrgsat_admin', ";
							if($da['hrgsat_so']!=$hrgsat_so) $isi .= "harga_fisik = '$hrgsat_so', ";
									
							if($isi!=""){
								$ed['id_det'] = $val['id_det'];
								$ed['isi'] = $isi;
								array_push($edit, $ed);
								unset($basket[$key]);
							}
							$cek = 'ada';
						}
					}else{ //data baru
						array_push($add, $basket[$key]);
						unset($basket[$key]);
					}
				}
				if($cek=="") array_push($del, $da['id_det']); //data lama dihapus
			}
			
			foreach($edit as $e){
				mysql_query("UPDATE so_detail SET $e[isi] update_date = '$datime' WHERE id_so_detail = '$e[id_det]'");
			}
			
			foreach($add as $val){
				$harga_komp = preg_replace("/[^0-9]/","", $val['hrgsat_admin']);	
				$harga_fisik = preg_replace("/[^0-9]/","", $val['hrgsat_so']);	
				$jml_komp = preg_replace("/[^0-9]/","", $val['jml_admin']); 
				$jml_fisik = preg_replace("/[^0-9]/","", $val['jml_so']); 
				
				mysql_query("INSERT INTO so_detail ( id_so_detail, id_so, uuid_skpd, 
													ta, id_barang, jml_komp, jml_fisik, harga_komp, harga_fisik, 
													keterangan,
													create_date, 
													creator_id,
													soft_delete)
											VALUES( UUID(), '$uid_skpd', '$_GET[id_ubah]',  
													'$ta', '$val[id_bar]', '$jml_komp', '$jml_fisik', '$harga_komp', '$harga_fisik',
													'$val[ket]',
													'$datime',
													'$pengguna',
													'0')");
													
			}
			
			foreach($del as $id){
				mysql_query("UPDATE so_detail SET soft_delete = '1' WHERE id_so_detail = '$id'");
			}
		
			
			if(mysql_errno()==0){
 				echo json_encode(array('success'=>true, 'pesan'=>"Data telah berhasil diubah !"));
            }else{
				if(mysql_errno()==1062){ 
					echo json_encode(array('success'=>false, 
											'pesan'=>"Kode Barang Sudah Ada di Unit ini !", 
											'error'=>"nomor_sama"));
				}else echo json_encode(array('success'=>false, 'pesan'=>"Tidak berhasil mengubah data ! ".mysql_errno()));
			}
			
		
		break; */
			case 'del':
				mysql_query("UPDATE so SET soft_delete = '1' WHERE id_so = '$_POST[id_hapus]'");
				mysql_query("UPDATE so_detail SET soft_delete = '1' WHERE id_so = '$_POST[id_hapus]'");
				mysql_query("UPDATE kartu_stok SET soft_delete = '1' WHERE id_transaksi = '$_POST[id_hapus]'");
				if (mysql_errno() == 0) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil dihapuskan !"));
				} else {
					echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menghapus data ! ", 'kode' => mysql_errno()));
				}


				break;
		}
	}elseif ($module == "stok_opname2") {
		if (isset($_REQUEST['form'])) $form = $_REQUEST['form'];
		if (isset($_REQUEST['basket'])) $basket = $_REQUEST['basket'];
		if (isset($_REQUEST['ubahform'])) $ubahform = $_REQUEST['ubahform'];
		if (isset($form['id_sub'])) $id_sub = $form['id_sub'];
		//if (isset($form['nomor'])) $nomor = addslashes($form['nomor']);
		if (isset($form['nomor'])) $nomor = $form['nomor'];
		if (isset($form['tanggal'])) $tanggal = balikTanggal($form['tanggal']);

		if (isset($form['nama1'])) $nama1 = $form['nama1'];
		if (isset($form['nama2'])) $nama2 = $form['nama2'];
		if (isset($form['nama3'])) $nama3 = $form['nama3'];
		if (isset($form['nama4'])) $nama4 = $form['nama4'];
		if (isset($form['nama5'])) $nama5 = $form['nama5'];
		if (isset($form['nama6'])) $nama6 = $form['nama6'];
		if (isset($form['nip1'])) $nip1 = $form['nip1'];
		if (isset($form['nip2'])) $nip2 = $form['nip2'];
		if (isset($form['nip3'])) $nip3 = $form['nip3'];
		if (isset($form['nip4'])) $nip4 = $form['nip4'];
		if (isset($form['nip5'])) $nip5 = $form['nip5'];
		if (isset($form['nip6'])) $nip6 = $form['nip6'];
		if (isset($form['gol1'])) $gol1 = $form['gol1'];
		if (isset($form['gol2'])) $gol2 = $form['gol2'];
		if (isset($form['gol3'])) $gol3 = $form['gol3'];
		if (isset($form['gol4'])) $gol4 = $form['gol4'];
		if (isset($form['gol5'])) $gol5 = $form['gol5'];
		if (isset($form['gol6'])) $gol6 = $form['gol6'];

		$ta = date('Y', strtotime($tanggal));
		
		switch ($oper) {
			case 'add':
				$u = mysql_fetch_row(mysql_query("SELECT UUID()"));
				$uuid = $u[0];
				/* $u2 = mysql_fetch_row(mysql_query("SELECT UUID()"));
				$uuid2 = $u2[0];
				$u3 = mysql_fetch_row(mysql_query("SELECT UUID()"));
				$uuid3 = $u3[0]; */
				mysql_query("INSERT INTO pemeriksa_so VALUES ('$uuid','$nama1','$nama2','$nama3','$nama4','$nama5','$nama6','$nip1','$nip2','$nip3','$nip4','$nip5','$nip6','$gol1','$gol2','$gol3','$gol4','$gol5','$gol6','0')");
				mysql_query("INSERT INTO so(id_so, uuid_skpd, ta, no_so, tgl_so, 
										create_date, 
										creator_id,
										soft_delete)
								VALUES ('$uuid', '$id_sub', '$ta', '$nomor', '$tanggal', 
										'$datime',
										'$pengguna',
										'0')");
				if (mysql_errno() == 0) {
					$hrgtemp = $idbartemp = "";
					foreach ($basket as $val) {
						$harga_komp = preg_replace("/[^0-9-]/", "", $val['hrgsat_admin']);
						$harga_fisik = preg_replace("/[^0-9]/", "", $val['hrgsat_so']);
						$jml_komp = preg_replace("/[^0-9-]/", "", $val['jml_admin']);
						$jml_fisik = preg_replace("/[^0-9]/", "", $val['jml_so']);
						$slsh = $jml_komp - $jml_fisik;
						$hrgslsh = $harga_komp - $harga_fisik;

						$ut = mysql_fetch_row(mysql_query("SELECT UUID()"));
						$uuidet = $ut[0];

						$kel = mysql_fetch_assoc(mysql_query("SELECT SUM(jml_in-jml_out) AS jml, id_kelompok 
								FROM kartu_stok k WHERE k.id_barang = '$val[id_bar]' AND k.uuid_skpd = '$id_sub' 
								AND id_sumber_dana = '$val[id_sum]' AND k.soft_delete = 0 GROUP BY k.id_kelompok HAVING jml <> 0
								LIMIT 1"));

						mysql_query("INSERT INTO so_detail ( id_so_detail, id_so, uuid_skpd, id_gudang, id_sumber_dana,
														ta, id_barang, jml_komp, jml_fisik, harga_komp, harga_fisik, 
														keterangan,
														create_date, 
														creator_id,
														soft_delete)
												VALUES( '$uuidet', '$uuid', '$id_sub', '$val[id_gud]', '$val[id_sum]',
														'$ta', '$val[id_bar]', '$jml_komp', '$jml_fisik', '$harga_komp', '$harga_fisik',
														'',
														'$datime',
														'$pengguna',
														'0')");
						
							
						
						
						if ($slsh != 0 && $hrgslsh == 0) {
							//if($slsh>0){ $jml_in = 0; $jml_out = $slsh; }
							//else{ $jml_in = abs($slsh); $jml_out = 0; }

							/* $jml_in = 0;
							$jml_out = $slsh;

							mysql_query("INSERT INTO kartu_stok (id_stok, uuid_skpd, id_barang, id_kelompok, id_gudang, 
															id_transaksi, id_transaksi_detail, id_sumber_dana,
															tgl_transaksi, ta, jml_in, jml_out, harga, kode,
															create_date, soft_delete, creator_id)
													VALUES	(UUID(), '$id_sub', '$val[id_bar]', '$kel[id_kelompok]', '$val[id_gud]', 
															'$uuid', '$uuidet', '$val[id_sum]',
															'$tanggal', '$ta', '$jml_in', '$jml_out', '$harga_fisik', 's',
															'$datime', 0, '$pengguna')"); */

							if($jml_komp >= $jml_fisik){
								mysql_query("CALL ambil_harga_insert_stok_plus('$val[id_bar],', '$jml_fisik,', '$tanggal,', 
												'$val[id_gud],', '$val[id_sum],', '$id_sub', '$uuid', '$ta', '$datime', 
												'$pengguna', '$tanggal', 's')");
								}else{
								mysql_query("CALL ambil_harga_insert_stok_minus('$val[id_bar],', '$jml_fisik,', '$tanggal,', 
									'$val[id_gud],', '$val[id_sum],', '$id_sub', '$uuid', '$ta', '$datime', 
									'$pengguna', '$tanggal', 's')");	
								}
															

						} elseif ($hrgslsh != 0) {
							if ($hrgtemp != $harga_komp && $idbartemp != $val['id_bar']) {
								//Keluarkan data barang komputer
								mysql_query("INSERT INTO kartu_stok (id_stok, uuid_skpd, id_barang, id_kelompok, id_gudang, 
															id_transaksi, id_transaksi_detail, id_sumber_dana,
															tgl_transaksi, ta, jml_in, jml_out, harga, kode,
															create_date, soft_delete, creator_id)
													VALUES	(UUID(), '$id_sub', '$val[id_bar]', 
															'$kel[id_kelompok]', '$val[id_gud]', 
															'$uuid', '$uuidet', '$val[id_sum]',
															'$tanggal', '$ta', '0', '$jml_komp', 
															'$harga_komp', 's',
															'$datime', 0, '$pengguna')");
							}

							if ($jml_fisik != 0 && $harga_fisik != 0) {
								//Masukkan data barang opnamenya									
								mysql_query("INSERT INTO kartu_stok (id_stok, uuid_skpd, id_barang, id_kelompok, id_gudang, 
															id_transaksi, id_transaksi_detail, id_sumber_dana,
															tgl_transaksi, ta, jml_in, jml_out, harga, kode,
															create_date, soft_delete, creator_id)
													VALUES	(UUID(), '$id_sub', '$val[id_bar]', 
															'$kel[id_kelompok]', '$val[id_gud]', 
															'$uuid', '$uuidet', '$val[id_sum]',
															'$tanggal', '$ta', '$jml_fisik', '0', 
															'$harga_fisik', 's',
															'$datime', 0, '$pengguna')");
							}
						}
						$hrgtemp = $harga_komp;
						$idbartemp = $val['id_bar'];
					}
					/* $values = "";
						$array_bar = "";
						$array_jml = "";
						foreach ($basket as $val) {
							$harga = $val['hrgsat_so'];
							$jumlah = $val['jml_admin'];


							$harga = str_replace('.', '', $harga);
							$jumlah = str_replace('.', '', $jumlah);
							$jumlah = str_replace(',', '.', $jumlah);


							$array_bar .= "$val[id_bar],";
							$array_jml .= "$jumlah,";
						}

						
							
							mysql_query("CALL ambil_harga_insert_stock ('$array_bar', '$array_jml', '$tanggal', '$id_sub', '$uuid2', '$uuid3', '$ta', '$datime', '$pengguna')"); */

					if (mysql_errno() == 0) echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil ditambahkan !"));
					else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menambahkan data ! ", 'kode' => mysql_errno()));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Nomor Stok Opname sudah ada !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menambahkan data ! ", 'kode' => mysql_errno()));
				}

				break;
				/* case 'edit':
			if($ubahform!=''){
				$dataubah = "";
				$form = explode("||", $ubahform);
				foreach($form as $field){
					$f = explode('::',$field);
					$v = explode('|',$field);
					if($f[0]=='id_sub'){
						$id_sub_ganti = $v[1];
						$dataubah .= "uuid_skpd = '$id_sub_ganti', ";
					}elseif($f[0]=='tanggal'){
						$ta_ganti = date('Y', strtotime($v[1]));
						$dataubah .= "ta = '$ta_ganti', ";
					}
				}
				
				if($dataubah!=""){
					$dataubah = substr($dataubah, 0, -2);
					mysql_query("UPDATE so_detail SET $dataubah , update_date = '$datime' WHERE id_so = '$_GET[id_ubah]'");
				}
				
				mysql_query("UPDATE so SET uuid_skpd = '$id_sub', ta = '$ta', no_so = '$nomor', tgl_so = '$tanggal', 
												update_date = '$datime'  
									WHERE id_so = '$_GET[id_ubah]'");

			}
			
			
			
			$datser = mysql_query("SELECT jml_komp AS jml_admin, jml_fisik AS jml_so, harga_komp AS hrgsat_admin, 
									harga_fisik AS hrgsat_so, id_barang AS id_bar, id_so_detail AS id_det
									FROM so_detail
									WHERE id_so = '$_GET[id_ubah]' AND soft_delete=0");
			
			$edit = array(); $add = array(); $del = array();
			while($da = mysql_fetch_assoc($datser)){
				$cek = "";
				foreach ($basket as $key => $val){
					if(isset($val['id_det'])){ //data lama 
						if($val['id_det']==$da['id_det']){ //data lama masih ada
							$isi = "";
							preg_match('/-?[0-9]+/', $val['jml_admin'], $jml);
							$jml_admin = $jml[0];
							//$jml_admin = preg_replace("/[^0-9-]/","", $val['jml_admin']);
							$jml_so = preg_replace("/[^0-9]/","", $val['jml_so']);
							$hrgsat_admin = preg_replace("/[^0-9]/","", $val['hrgsat_admin']);
							$hrgsat_so = preg_replace("/[^0-9]/","", $val['hrgsat_so']);
							//$hrg = number_format($val['harga_sat'],2,'.','');
							
							if($da['id_bar']!=$val['id_bar']) $isi .= "id_barang = '$val[id_bar]', "; 
							if($da['jml_admin']!=$jml_admin) $isi .= "jml_komp = '$jml_admin', ";
							if($da['jml_so']!=$jml_so) $isi .= "jml_fisik = '$jml_so', ";
							if($da['hrgsat_admin']!=$hrgsat_admin) $isi .= "harga_komp = '$hrgsat_admin', ";
							if($da['hrgsat_so']!=$hrgsat_so) $isi .= "harga_fisik = '$hrgsat_so', ";
									
							if($isi!=""){
								$ed['id_det'] = $val['id_det'];
								$ed['isi'] = $isi;
								array_push($edit, $ed);
								unset($basket[$key]);
							}
							$cek = 'ada';
						}
					}else{ //data baru
						array_push($add, $basket[$key]);
						unset($basket[$key]);
					}
				}
				if($cek=="") array_push($del, $da['id_det']); //data lama dihapus
			}
			
			foreach($edit as $e){
				mysql_query("UPDATE so_detail SET $e[isi] update_date = '$datime' WHERE id_so_detail = '$e[id_det]'");
			}
			
			foreach($add as $val){
				$harga_komp = preg_replace("/[^0-9]/","", $val['hrgsat_admin']);	
				$harga_fisik = preg_replace("/[^0-9]/","", $val['hrgsat_so']);	
				$jml_komp = preg_replace("/[^0-9]/","", $val['jml_admin']); 
				$jml_fisik = preg_replace("/[^0-9]/","", $val['jml_so']); 
				
				mysql_query("INSERT INTO so_detail ( id_so_detail, id_so, uuid_skpd, 
													ta, id_barang, jml_komp, jml_fisik, harga_komp, harga_fisik, 
													keterangan,
													create_date, 
													creator_id,
													soft_delete)
											VALUES( UUID(), '$uid_skpd', '$_GET[id_ubah]',  
													'$ta', '$val[id_bar]', '$jml_komp', '$jml_fisik', '$harga_komp', '$harga_fisik',
													'$val[ket]',
													'$datime',
													'$pengguna',
													'0')");
													
			}
			
			foreach($del as $id){
				mysql_query("UPDATE so_detail SET soft_delete = '1' WHERE id_so_detail = '$id'");
			}
		
			
			if(mysql_errno()==0){
 				echo json_encode(array('success'=>true, 'pesan'=>"Data telah berhasil diubah !"));
            }else{
				if(mysql_errno()==1062){ 
					echo json_encode(array('success'=>false, 
											'pesan'=>"Kode Barang Sudah Ada di Unit ini !", 
											'error'=>"nomor_sama"));
				}else echo json_encode(array('success'=>false, 'pesan'=>"Tidak berhasil mengubah data ! ".mysql_errno()));
			}
			
		
		break; */
			case 'del':
				mysql_query("UPDATE so SET soft_delete = '1' WHERE id_so = '$_POST[id_hapus]'");
				mysql_query("UPDATE so_detail SET soft_delete = '1' WHERE id_so = '$_POST[id_hapus]'");
				mysql_query("UPDATE kartu_stok SET soft_delete = '1' WHERE id_transaksi = '$_POST[id_hapus]'");
				if (mysql_errno() == 0) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil dihapuskan !"));
				} else {
					echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menghapus data ! ", 'kode' => mysql_errno()));
				}


				break;
		}
	} elseif ($module == "stok_opname3") {
		if (isset($_REQUEST['form'])) $form = $_REQUEST['form'];
		if (isset($_REQUEST['basket'])) $basket = $_REQUEST['basket'];
		if (isset($_REQUEST['ubahform'])) $ubahform = $_REQUEST['ubahform'];
		if (isset($form['id_sub'])) $id_sub = $form['id_sub'];
		if (isset($form['nomor'])) $nomor = $form['nomor'];
		if (isset($form['tanggal'])) $tanggal = balikTanggal($form['tanggal']);

		if (isset($form['nama1'])) $nama1 = $form['nama1'];
		if (isset($form['nama2'])) $nama2 = $form['nama2'];
		if (isset($form['nama3'])) $nama3 = $form['nama3'];
		if (isset($form['nama4'])) $nama4 = $form['nama4'];
		if (isset($form['nama5'])) $nama5 = $form['nama5'];
		if (isset($form['nama6'])) $nama6 = $form['nama6'];
		if (isset($form['nip1'])) $nip1 = $form['nip1'];
		if (isset($form['nip2'])) $nip2 = $form['nip2'];
		if (isset($form['nip3'])) $nip3 = $form['nip3'];
		if (isset($form['nip4'])) $nip4 = $form['nip4'];
		if (isset($form['nip5'])) $nip5 = $form['nip5'];
		if (isset($form['nip6'])) $nip6 = $form['nip6'];
		if (isset($form['gol1'])) $gol1 = $form['gol1'];
		if (isset($form['gol2'])) $gol2 = $form['gol2'];
		if (isset($form['gol3'])) $gol3 = $form['gol3'];
		if (isset($form['gol4'])) $gol4 = $form['gol4'];
		if (isset($form['gol5'])) $gol5 = $form['gol5'];
		if (isset($form['gol6'])) $gol6 = $form['gol6'];

		$ta = date('Y', strtotime($tanggal));

		switch ($oper) {
			case 'add':
				$u = mysql_fetch_row(mysql_query("SELECT UUID()"));
				$uuid = $u[0];
				/* $u2 = mysql_fetch_row(mysql_query("SELECT UUID()"));
				$uuid2 = $u2[0];
				$u3 = mysql_fetch_row(mysql_query("SELECT UUID()"));
				$uuid3 = $u3[0]; */
				mysql_query("INSERT INTO pemeriksa_so VALUES ('$uuid','$nama1','$nama2','$nama3','$nama4','$nama5','$nama6','$nip1','$nip2','$nip3','$nip4','$nip5','$nip6','$gol1','$gol2','$gol3','$gol4','$gol5','$gol6','0')");
				mysql_query("INSERT INTO so(id_so, uuid_skpd, ta, no_so, tgl_so, 
										create_date, 
										creator_id,
										soft_delete)
								VALUES ('$uuid', '$id_sub', '$ta', '$nomor', '$tanggal', 
										'$datime',
										'$pengguna',
										'0')");
				if (mysql_errno() == 0) {
					$hrgtemp = $idbartemp = "";
					foreach ($basket as $val) {
						$harga_komp = preg_replace("/[^0-9-]/", "", $val['hrgsat_admin']);
						$harga_fisik = preg_replace("/[^0-9]/", "", $val['hrgsat_so']);
						$jml_komp = preg_replace("/[^0-9-]/", "", $val['jml_admin']);
						$jml_fisik = preg_replace("/[^0-9]/", "", $val['jml_so']);
						$slsh = $jml_komp - $jml_fisik;
						$hrgslsh = $harga_komp - $harga_fisik;

						$ut = mysql_fetch_row(mysql_query("SELECT UUID()"));
						$uuidet = $ut[0];

						$kel = mysql_fetch_assoc(mysql_query("SELECT SUM(jml_in-jml_out) AS jml, id_kelompok 
								FROM kartu_stok k WHERE k.id_barang = '$val[id_bar]' AND k.uuid_skpd = '$id_sub' 
								AND id_sumber_dana = '$val[id_sum]' AND k.soft_delete = 0 GROUP BY k.id_kelompok HAVING jml <> 0
								LIMIT 1"));

						mysql_query("INSERT INTO so_detail ( id_so_detail, id_so, uuid_skpd, id_gudang, id_sumber_dana,
														ta, id_barang, jml_komp, jml_fisik, harga_komp, harga_fisik, 
														keterangan,
														create_date, 
														creator_id,
														soft_delete)
												VALUES( '$uuidet', '$uuid', '$id_sub', '$val[id_gud]', '$val[id_sum]',
														'$ta', '$val[id_bar]', '$jml_komp', '$jml_fisik', '$harga_komp', '$harga_fisik',
														'',
														'$datime',
														'$pengguna',
														'0')");
						
							
						
						
						if ($slsh != 0 && $hrgslsh == 0) {
							//if($slsh>0){ $jml_in = 0; $jml_out = $slsh; }
							//else{ $jml_in = abs($slsh); $jml_out = 0; }

							/* $jml_in = 0;
							$jml_out = $slsh;

							mysql_query("INSERT INTO kartu_stok_copy (id_stok, uuid_skpd, id_barang, id_kelompok, id_gudang, 
															id_transaksi, id_transaksi_detail, id_sumber_dana,
															tgl_transaksi, ta, jml_in, jml_out, harga, kode,
															create_date, soft_delete, creator_id)
													VALUES	(UUID(), '$id_sub', '$val[id_bar]', '$kel[id_kelompok]', '$val[id_gud]', 
															'$uuid', '$uuidet', '$val[id_sum]',
															'$tanggal', '$ta', '$jml_in', '$jml_out', '$harga_fisik', 's',
															'$datime', 0, '$pengguna')"); */
							
							
								/* $array_bar = "";
								$array_jml = "";
								$array_tgl = "";
								$array_gud = "";
								$array_sum = "";
								foreach ($basket as $val) {
									//$jumlah = preg_replace("/[^0-9]/","", $val['jumlah']); 
									$jumlah = $val['jml_so'];
		
									$jumlah = str_replace(".", "", $jumlah);
									$jumlah = str_replace(",", ".", $jumlah);
									$tgl_terima = balikTanggal($tanggal);
									if ($jenis_out == 'r') $tgl_minta = $tgl_terima;
		
		
		
									$array_bar .= "$val[id_bar],";
									$array_jml .= "$jumlah,";
									$array_tgl .= "$tgl_terima,";
									$array_gud .= "$val[id_gud],";
									$array_sum .= "$val[id_sum],";
								} */

								if($jml_komp >= $jml_fisik){
								mysql_query("CALL ambil_harga_insert_stok_plus('$val[id_bar],', '$jml_fisik,', '$tanggal,', 
												'$val[id_gud],', '$val[id_sum],', '$id_sub', '$uuid', '$ta', '$datime', 
												'$pengguna', '$tanggal', 's')");
								}else{
								mysql_query("CALL ambil_harga_insert_stok_minus('$val[id_bar],', '$jml_fisik,', '$tanggal,', 
									'$val[id_gud],', '$val[id_sum],', '$id_sub', '$uuid', '$ta', '$datime', 
									'$pengguna', '$tanggal', 's')");	
								}
															

						} elseif ($hrgslsh != 0) {
							if ($hrgtemp != $harga_komp && $idbartemp != $val['id_bar']) {
								//Keluarkan data barang komputer
								mysql_query("INSERT INTO kartu_stok (id_stok, uuid_skpd, id_barang, id_kelompok, id_gudang, 
															id_transaksi, id_transaksi_detail, id_sumber_dana,
															tgl_transaksi, ta, jml_in, jml_out, harga, kode,
															create_date, soft_delete, creator_id)
													VALUES	(UUID(), '$id_sub', '$val[id_bar]', 
															'$kel[id_kelompok]', '$val[id_gud]', 
															'$uuid', '$uuidet', '$val[id_sum]',
															'$tanggal', '$ta', '0', '$jml_komp', 
															'$harga_komp', 's',
															'$datime', 0, '$pengguna')");
							}

							if ($jml_fisik != 0 && $harga_fisik != 0) {
								//Masukkan data barang opnamenya									
								mysql_query("INSERT INTO kartu_stok (id_stok, uuid_skpd, id_barang, id_kelompok, id_gudang, 
															id_transaksi, id_transaksi_detail, id_sumber_dana,
															tgl_transaksi, ta, jml_in, jml_out, harga, kode,
															create_date, soft_delete, creator_id)
													VALUES	(UUID(), '$id_sub', '$val[id_bar]', 
															'$kel[id_kelompok]', '$val[id_gud]', 
															'$uuid', '$uuidet', '$val[id_sum]',
															'$tanggal', '$ta', '$jml_fisik', '0', 
															'$harga_fisik', 's',
															'$datime', 0, '$pengguna')");
							}
						}
						$hrgtemp = $harga_komp;
						$idbartemp = $val['id_bar'];
					}

					if (mysql_errno() == 0) echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil ditambahkan !"));
					else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menambahkan data ! ", 'kode' => mysql_errno())); 
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Nomor Stok Opname sudah ada !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menambahkan data ! ", 'kode' => mysql_errno())); 
				}

				break;
				/* case 'edit':
			if($ubahform!=''){
				$dataubah = "";
				$form = explode("||", $ubahform);
				foreach($form as $field){
					$f = explode('::',$field);
					$v = explode('|',$field);
					if($f[0]=='id_sub'){
						$id_sub_ganti = $v[1];
						$dataubah .= "uuid_skpd = '$id_sub_ganti', ";
					}elseif($f[0]=='tanggal'){
						$ta_ganti = date('Y', strtotime($v[1]));
						$dataubah .= "ta = '$ta_ganti', ";
					}
				}
				
				if($dataubah!=""){
					$dataubah = substr($dataubah, 0, -2);
					mysql_query("UPDATE so_detail SET $dataubah , update_date = '$datime' WHERE id_so = '$_GET[id_ubah]'");
				}
				
				mysql_query("UPDATE so SET uuid_skpd = '$id_sub', ta = '$ta', no_so = '$nomor', tgl_so = '$tanggal', 
												update_date = '$datime'  
									WHERE id_so = '$_GET[id_ubah]'");

			}
			
			
			
			$datser = mysql_query("SELECT jml_komp AS jml_admin, jml_fisik AS jml_so, harga_komp AS hrgsat_admin, 
									harga_fisik AS hrgsat_so, id_barang AS id_bar, id_so_detail AS id_det
									FROM so_detail
									WHERE id_so = '$_GET[id_ubah]' AND soft_delete=0");
			
			$edit = array(); $add = array(); $del = array();
			while($da = mysql_fetch_assoc($datser)){
				$cek = "";
				foreach ($basket as $key => $val){
					if(isset($val['id_det'])){ //data lama 
						if($val['id_det']==$da['id_det']){ //data lama masih ada
							$isi = "";
							preg_match('/-?[0-9]+/', $val['jml_admin'], $jml);
							$jml_admin = $jml[0];
							//$jml_admin = preg_replace("/[^0-9-]/","", $val['jml_admin']);
							$jml_so = preg_replace("/[^0-9]/","", $val['jml_so']);
							$hrgsat_admin = preg_replace("/[^0-9]/","", $val['hrgsat_admin']);
							$hrgsat_so = preg_replace("/[^0-9]/","", $val['hrgsat_so']);
							//$hrg = number_format($val['harga_sat'],2,'.','');
							
							if($da['id_bar']!=$val['id_bar']) $isi .= "id_barang = '$val[id_bar]', "; 
							if($da['jml_admin']!=$jml_admin) $isi .= "jml_komp = '$jml_admin', ";
							if($da['jml_so']!=$jml_so) $isi .= "jml_fisik = '$jml_so', ";
							if($da['hrgsat_admin']!=$hrgsat_admin) $isi .= "harga_komp = '$hrgsat_admin', ";
							if($da['hrgsat_so']!=$hrgsat_so) $isi .= "harga_fisik = '$hrgsat_so', ";
									
							if($isi!=""){
								$ed['id_det'] = $val['id_det'];
								$ed['isi'] = $isi;
								array_push($edit, $ed);
								unset($basket[$key]);
							}
							$cek = 'ada';
						}
					}else{ //data baru
						array_push($add, $basket[$key]);
						unset($basket[$key]);
					}
				}
				if($cek=="") array_push($del, $da['id_det']); //data lama dihapus
			}
			
			foreach($edit as $e){
				mysql_query("UPDATE so_detail SET $e[isi] update_date = '$datime' WHERE id_so_detail = '$e[id_det]'");
			}
			
			foreach($add as $val){
				$harga_komp = preg_replace("/[^0-9]/","", $val['hrgsat_admin']);	
				$harga_fisik = preg_replace("/[^0-9]/","", $val['hrgsat_so']);	
				$jml_komp = preg_replace("/[^0-9]/","", $val['jml_admin']); 
				$jml_fisik = preg_replace("/[^0-9]/","", $val['jml_so']); 
				
				mysql_query("INSERT INTO so_detail ( id_so_detail, id_so, uuid_skpd, 
													ta, id_barang, jml_komp, jml_fisik, harga_komp, harga_fisik, 
													keterangan,
													create_date, 
													creator_id,
													soft_delete)
											VALUES( UUID(), '$uid_skpd', '$_GET[id_ubah]',  
													'$ta', '$val[id_bar]', '$jml_komp', '$jml_fisik', '$harga_komp', '$harga_fisik',
													'$val[ket]',
													'$datime',
													'$pengguna',
													'0')");
													
			}
			
			foreach($del as $id){
				mysql_query("UPDATE so_detail SET soft_delete = '1' WHERE id_so_detail = '$id'");
			}
		
			
			if(mysql_errno()==0){
 				echo json_encode(array('success'=>true, 'pesan'=>"Data telah berhasil diubah !"));
            }else{
				if(mysql_errno()==1062){ 
					echo json_encode(array('success'=>false, 
											'pesan'=>"Kode Barang Sudah Ada di Unit ini !", 
											'error'=>"nomor_sama"));
				}else echo json_encode(array('success'=>false, 'pesan'=>"Tidak berhasil mengubah data ! ".mysql_errno()));
			}
			
		
		break; */
			case 'del':
				mysql_query("UPDATE so SET soft_delete = '1' WHERE id_so = '$_POST[id_hapus]'");
				mysql_query("UPDATE so_detail SET soft_delete = '1' WHERE id_so = '$_POST[id_hapus]'");
				mysql_query("UPDATE kartu_stok SET soft_delete = '1' WHERE id_transaksi = '$_POST[id_hapus]'");
				if (mysql_errno() == 0) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil dihapuskan !"));
				} else {
					echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menghapus data ! ", 'kode' => mysql_errno()));
				}


				break;
		}
	} elseif ($module == "usul_hapus") {
		if (isset($_REQUEST['form'])) $form = $_REQUEST['form'];
		if (isset($_REQUEST['basket'])) $basket = $_REQUEST['basket'];
		if (isset($_REQUEST['ubahform'])) $ubahform = $_REQUEST['ubahform'];
		if (isset($form['id_sub'])) $id_sub = $form['id_sub'];
		if (isset($form['nomor_ba'])) $nomor_ba = $form['nomor_ba'];
		if (isset($form['tanggal'])) $tanggal = balikTanggal($form['tanggal']);
		if (isset($form['ta'])) $ta = $form['ta'];
		if (isset($form['id_alasan'])) $id_alasan = $form['id_alasan'];
		if (isset($form['nomor_sk'])) $nomor_sk = $form['nomor_sk'];
		if (isset($form['tahun_sk'])) $tahun_sk = $form['tahun_sk'];
		if (isset($form['id_aksi'])) $id_aksi = $form['id_aksi'];
		if (isset($form['ketua'])) $ketua = $form['ketua'];
		if (isset($form['sekretaris'])) $sekretaris = $form['sekretaris'];
		if (isset($form['anggota'])) $anggota = $form['anggota'];
		if (isset($form['jab_ket'])) $jab_ket = $form['jab_ket'];
		if (isset($form['jab_sek'])) $jab_sek = $form['jab_sek'];
		if (isset($form['jab_ang'])) $jab_ang = $form['jab_ang'];

		$ta = date('Y', strtotime($tanggal));

		switch ($oper) {
			case 'add':
				$u = mysql_fetch_row(mysql_query("SELECT UUID()"));
				$uuid = $u[0];
				mysql_query("INSERT INTO usul_hapus(id_usul_hapus, uuid_skpd, 
												ta, no_ba_usulan, tgl_ba_usulan, 
												id_pejabat_ketua, id_pejabat_sekretaris, id_pejabat_anggota1,
												jabatan_ketua, jabatan_sekretaris, jabatan_anggota1,
												id_alasan_penghapusan, id_aksi_penghapusan,
												no_ba_penunjukan, thn_ba_penunjukan,
												create_date, 
												creator_id,
												soft_delete)
										VALUES ('$uuid', '$id_sub', 
												'$ta', '$nomor_ba', '$tanggal', 
												'$ketua', '$sekretaris', '$anggota', 
												'$jab_ket', '$jab_sek', '$jab_ang',
												'$id_alasan', '$id_aksi', 
												'$nomor_sk', '$tahun_sk', 
												'$datime',
												'$pengguna',
												'0')");
				if (mysql_errno() == 0) {
					$array_bar = "";
					$array_jml = "";
					$array_baik = "";
					$array_ringan = "";
					$array_berat = "";
					$array_kadalu = "";
					foreach ($basket as $val) {
						$jumlah = preg_replace("/[^0-9]/", "", $val['jumlah']);
						$baik = preg_replace("/[^0-9]/", "", $val['baik']);
						$ringan = preg_replace("/[^0-9]/", "", $val['ringan']);
						$berat = preg_replace("/[^0-9]/", "", $val['berat']);
						$kadaluarsa = preg_replace("/[^0-9]/", "", $val['kadaluarsa']);

						$array_bar .= "$val[id_bar],";
						$array_jml .= "$jumlah,";
						$array_baik .= "$baik,";
						$array_ringan .= "$ringan,";
						$array_berat .= "$berat,";
						$array_kadalu .= "$kadaluarsa,";


						/* mysql_query("INSERT INTO usul_hapus_detail ( id_usul_hapus_detail, id_usul_hapus, uuid_skpd, 
																ta, id_barang, jml_barang, harga_barang, 
																baik, ringan, berat, kadaluarsa,
																create_date, 
																creator_id,
																soft_delete)
														VALUES( UUID(), '$uuid', '$id_sub', 
																'$ta', '$val[id_bar]', '$jumlah', '$harga', 
																'$baik', '$ringan', '$berat', '$kadaluarsa', 
																'$datime',
																'$pengguna',
																'0')"); */
					}


					mysql_query("CALL ambil_harga_insert_usul_hapus('$array_bar', '$array_jml', '$array_baik', '$array_ringan', '$array_berat', '$array_kadalu', '$uuid', '$tanggal', '$id_sub', '$ta', '$datime', '$pengguna')");

					if (mysql_errno() == 0) echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil ditambahkan !"));
					else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menambahkan data ! ", 'kode' => mysql_errno()));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Nomor Stok Opname sudah ada !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menambahkan data ! ", 'kode' => mysql_errno()));
				}

				break;
			case 'edit':
				if ($ubahform != '') {
					$dataubah = "";
					$form = explode("||", $ubahform);
					foreach ($form as $field) {
						$f = explode('::', $field);
						$v = explode('|', $field);
						if ($f[0] == 'id_sub') {
							$id_sub_ganti = $v[1];
							$kdg = explode('.', $id_sub_ganti);
							$dataubah .= "uuid_skpd = '$id_sub_ganti', ";
						} elseif ($f[0] == 'tanggal') {
							$ta_ganti = date('Y', strtotime($v[1]));
							$dataubah .= "ta = '$ta_ganti', ";
						}
					}

					if ($dataubah != "") {
						$dataubah = substr($dataubah, 0, -2);
						mysql_query("UPDATE usul_hapus_detail SET $dataubah , update_date = '$datime' WHERE id_usul_hapus = '$_GET[id_ubah]'");
					}

					mysql_query("UPDATE usul_hapus SET uuid_skpd = '$id_sub',
												ta = '$ta', no_ba_usulan = '$nomor_ba', tgl_ba_usulan = '$tanggal', 
												id_pejabat_ketua = '$ketua', id_pejabat_sekretaris = '$sekretaris', 
												id_pejabat_anggota1 = '$anggota', jabatan_ketua = '$jab_ket', 
												jabatan_sekretaris = '$jab_sek', jabatan_anggota1 = '$jab_ang',
												id_alasan_penghapusan = '$id_alasan',
												id_aksi_penghapusan = '$id_aksi', no_ba_penunjukan = '$nomor_sk',
												thn_ba_penunjukan = '$tahun_sk',
												update_date = '$datime'  
									WHERE id_usul_hapus = '$_GET[id_ubah]'");
				}



				$datser = mysql_query("SELECT d.id_barang AS id_bar, SUM(jml_barang) AS jumlah, 
											SUM(baik) AS baik, SUM(ringan) AS ringan, SUM(berat) AS berat, 
											SUM(kadaluarsa) AS kadaluarsa, id_usul_hapus_detail AS id
									FROM usul_hapus_detail d
									WHERE id_usul_hapus = '$_GET[id_ubah]' AND d.soft_delete=0
									GROUP BY d.id_barang");

				$edit = array();
				$add = array();
				$del = array();
				$array_bar = $array_jml = $array_baik = $array_ringan = $array_berat = $array_kadalu = "";
				while ($da = mysql_fetch_assoc($datser)) {
					$cek = "";
					foreach ($basket as $key => $val) {
						if (isset($val['id'])) { //data lama 
							if ($val['id'] == $da['id']) { //data lama masih ada
								$isi = "";
								$jumlah = preg_replace("/[^0-9]/", "", $val['jumlah']);
								$baik = preg_replace("/[^0-9]/", "", $val['baik']);
								$ringan = preg_replace("/[^0-9]/", "", $val['ringan']);
								$berat = preg_replace("/[^0-9]/", "", $val['berat']);
								$kadaluarsa = preg_replace("/[^0-9]/", "", $val['kadaluarsa']);

								if ($da['id_bar'] != $val['id_bar']) $isi .= "id_barang = '$val[id_bar]', ";
								if ($da['jumlah'] != $jumlah) $isi .= "jml_barang = '$jumlah', ";
								if ($da['baik'] != $baik) $isi .= "baik = '$baik', ";
								if ($da['ringan'] != $ringan) $isi .= "ringan = '$ringan', ";
								if ($da['berat'] != $berat) $isi .= "berat = '$berat', ";
								if ($da['kadaluarsa'] != $kadaluarsa) $isi .= "kadaluarsa = '$kadaluarsa', ";

								if ($isi != "") {
									$ed['id'] = $val['id'];
									$ed['isi'] = $isi;
									array_push($edit, $ed);
									$array_bar .= "$val[id_bar],";
									$array_jml .= "$jumlah,";
									$array_baik = "$baik,";
									$array_ringan = "$ringan,";
									$array_berat = "$berat,";
									$array_kadalu = "$kadaluarsa,";
									mysql_query("UPDATE usul_hapus_detail SET soft_delete = '1' WHERE id_barang = '$da[id_bar]' AND id_usul_hapus = '$_GET[id_ubah]'");
								}
								unset($basket[$key]);
								$cek = 'ada';
							}
						} else { //data baru
							array_push($add, $basket[$key]);
							unset($basket[$key]);
						}
					}
					if ($cek == "") array_push($del, $da['id_bar']); //data lama dihapus
				}

				foreach ($add as $val) {
					$jumlah = preg_replace("/[^0-9]/", "", $val['jumlah']);
					$baik = preg_replace("/[^0-9]/", "", $val['baik']);
					$ringan = preg_replace("/[^0-9]/", "", $val['ringan']);
					$berat = preg_replace("/[^0-9]/", "", $val['berat']);
					$kadaluarsa = preg_replace("/[^0-9]/", "", $val['kadaluarsa']);
					$array_bar .= "$val[id_bar],";
					$array_jml .= "$jumlah,";
					$array_baik .= "$baik,";
					$array_ringan .= "$ringan,";
					$array_berat .= "$berat,";
					$array_kadalu .= "$kadaluarsa,";
				}

				if ($array_bar != "") {
					mysql_query("CALL ambil_harga_insert_usul_hapus('$array_bar', '$array_jml', '$array_baik', '$array_ringan', '$array_berat', '$array_kadalu', '$_GET[id_ubah]', '$tanggal', '$id_sub', '$ta', '$datime', '$pengguna')");
				}

				foreach ($del as $id) {
					mysql_query("UPDATE usul_hapus_detail SET soft_delete = '1' WHERE id_barang = '$id' AND id_usul_hapus = '$_GET[id_ubah]'");
				}


				/* foreach($edit as $e){
				mysql_query("UPDATE usul_hapus_detail SET $e[isi] update_date = '$datime' WHERE id_usul_hapus_detail = '$e[id]'");
			}
			
			foreach($add as $val){
				$jumlah = preg_replace("/[^0-9]/","", $val['jumlah']);
				$baik = preg_replace("/[^0-9]/","", $val['baik']);
				$ringan = preg_replace("/[^0-9]/","", $val['ringan']);
				$berat = preg_replace("/[^0-9]/","", $val['berat']);
				$kadaluarsa = preg_replace("/[^0-9]/","", $val['kadaluarsa']);
				$harga = preg_replace("/[^0-9]/","", $val['harga']);
				
				mysql_query("INSERT INTO usul_hapus_detail ( id_usul_hapus_detail, id_usul_hapus, uuid_skpd,
															ta, id_barang, jml_barang, harga_barang, 
															baik, ringan, berat, kadaluarsa,
															create_date, 
															creator_id,
															soft_delete)
													VALUES( UUID(), '$_GET[id_ubah]', '$id_sub', 
															'$ta', '$val[id_bar]', '$jumlah', '$harga', 
															'$baik', '$ringan', '$berat', '$kadaluarsa', 
															'$datime',
															'$pengguna',
															'0')");
													
			} */



				if (mysql_errno() == 0) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil diubah !"));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Kode Barang Sudah Ada di Unit ini !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil mengubah data ! " . mysql_errno()));
				}


				break;
			case 'del':
				mysql_query("UPDATE usul_hapus SET soft_delete = '1' WHERE id_usul_hapus = '$_POST[id_hapus]'");
				mysql_query("UPDATE usul_hapus_detail SET soft_delete = '1' WHERE id_usul_hapus = '$_POST[id_hapus]'");
				if (mysql_errno() == 0) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil dihapuskan !"));
				} else {
					echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menghapus data ! ", 'kode' => mysql_errno()));
				}


				break;
		}
	} elseif ($module == "informasi") {
		if (isset($_REQUEST['txt_informasi'])) $txt_informasi = $_REQUEST['txt_informasi'];

		$q = mysql_query("UPDATE informasi SET isi = '$txt_informasi'");
		if ($q) {
			echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil diubah !"));
		} else {
			echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil mengubah data !"));
		}
	} elseif ($module == "normalisasi") {
		if (isset($_REQUEST['id_stok'])) $id_stok = $_REQUEST['id_stok'];
		if (isset($_REQUEST['id_sumber_dana'])) $id_sumber_dana = $_REQUEST['id_sumber_dana'];
		if (isset($_REQUEST['jml_in'])) $jml_in = $_REQUEST['jml_in'];
		if (isset($_REQUEST['jml_out'])) $jml_out = $_REQUEST['jml_out'];
		if (isset($_REQUEST['harga'])) $harga = $_REQUEST['harga'];
		if (isset($_REQUEST['kode_stok'])) $kode_stok = $_REQUEST['kode_stok'];
		if (isset($_REQUEST['id_transaksi'])) $id_transaksi = $_REQUEST['id_transaksi'];
		if (isset($_REQUEST['soft_delete'])) $soft_delete = $_REQUEST['soft_delete'];
		if (isset($_REQUEST['id_transaksi_detail'])) $id_transaksi_detail = $_REQUEST['id_transaksi_detail'];
		if (isset($_REQUEST['ta_stok'])) $ta_stok = $_REQUEST['ta_stok'];
		if (isset($_REQUEST['tgl_transaksi'])) $tgl_transaksi = $_REQUEST['tgl_transaksi'];
		$tgl_transaksi = balikTanggal($tgl_transaksi);

		$jml_in = str_replace(".", "", $jml_in);
		$jml_in = str_replace(",", ".", $jml_in);

		$jml_out = str_replace(".", "", $jml_out);
		$jml_out = str_replace(",", ".", $jml_out);

		$harga = str_replace(".", "", $harga);
		$harga = str_replace(",", ".", $harga);
		$date = date("Ymd His");

		$q = mysql_query("UPDATE kartu_stok SET jml_in = '$jml_in', jml_out = '$jml_out', harga = '$harga',id_sumber_dana = '$id_sumber_dana',ta = '$ta_stok', tgl_transaksi = '$tgl_transaksi', soft_delete = '$soft_delete', creator_id = 'NORMALISASI $date' WHERE id_stok = '$id_stok' ");
		if ($kode_stok == 'ok') {
			$q = mysql_query("UPDATE keluar_detail SET jml_barang = '$jml_out', id_sumber_dana = '$id_sumber_dana', harga_barang = '$harga' ,ta = '$ta_stok', soft_delete = '$soft_delete' WHERE id_keluar_detail = '$id_transaksi_detail' ");
		} else if ($kode_stok == 'i') {
			$q = mysql_query("UPDATE masuk_detail SET jml_masuk = '$jml_in', harga_masuk = '$harga',ta = '$ta_stok', soft_delete = '$soft_delete' WHERE id_masuk_detail = '$id_transaksi_detail' ");
		} else if ($kode_stok == 'a') {
			$q = mysql_query("UPDATE adjust_detail SET jumlah = '$jml_in', harga = '$harga', soft_delete = '$soft_delete' WHERE id_adjust_detail = '$id_transaksi_detail' ");
		} else if ($kode_stok == 'r') {
			$q = mysql_query("UPDATE terima_keluar_detail SET jml_barang = '$jml_in', harga_barang = '$harga', soft_delete = '$soft_delete' WHERE id_terima_keluar_detail = '$id_transaksi_detail' ");
		}

		if ($q) {
			echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil diubah ! "));
		} else {
			echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil mengubah data !"));
		}
	} elseif ($module == "hapus_barang") {
		if (isset($_REQUEST['uid'])) $uid_skpd = $_REQUEST['uid'];
		if (isset($_REQUEST['form'])) $form = $_REQUEST['form'];
		if (isset($_REQUEST['basket'])) $basket = $_REQUEST['basket'];
		if (isset($_REQUEST['ubahform'])) $ubahform = $_REQUEST['ubahform'];
		if (isset($form['id_sub'])) $id_sub = $form['id_sub'];
		if (isset($form['nomor_ba2'])) $nomor_ba = $form['nomor_ba2'];
		if (isset($form['tanggal'])) $tanggal = balikTanggal($form['tanggal']);
		if (isset($form['ta'])) $ta = $form['ta'];
		if (isset($form['nomor_sk'])) $nomor_sk = $form['nomor_sk'];
		if (isset($form['tahun_sk'])) $tahun_sk = $form['tahun_sk'];
		if (isset($form['ketua'])) $ketua = $form['ketua'];
		if (isset($form['sekretaris'])) $sekretaris = $form['sekretaris'];
		if (isset($form['anggota1'])) $anggota1 = $form['anggota1'];
		if (isset($form['anggota2'])) $anggota2 = $form['anggota2'];
		if (isset($form['jab_ket'])) $jab_ket = $form['jab_ket'];
		if (isset($form['jab_sek'])) $jab_sek = $form['jab_sek'];
		if (isset($form['jab_ang1'])) $jab_ang1 = $form['jab_ang1'];
		if (isset($form['jab_ang2'])) $jab_ang2 = $form['jab_ang2'];


		switch ($oper) {
			case 'add':

				if (mysql_errno() == 0) {
					$array_bar = "";
					$array_jml = "";
					$array_baik = "";
					$array_ringan = "";
					$array_berat = "";
					$array_kadalu = "";
					$array_gudang = "";
					$array_sumber = "";
					foreach ($basket as $val) {
						$smd = " ,IFNULL((SELECT SUM(jml_barang) FROM sp_out_detail od, sp_out so WHERE od.id_sp_out = so.id_sp_out
							AND od.id_barang ='$val[id_bar]' AND so.uuid_skpd = '$id_sub'
							AND od.soft_delete = 0 AND so.soft_delete = 0 AND so.status = 1), 0) AS pesan";

						$r1 = mysql_fetch_assoc(mysql_query("SELECT IFNULL(SUM(jml_in-jml_out),0) AS saldo $smd
										FROM kartu_stok
										WHERE uuid_skpd = '$id_sub'
										AND id_barang = '$val[id_bar]' AND soft_delete = 0"));
						// $r2 = mysql_fetch_assoc(mysql_query("SELECT IFNULL(harga,0) AS harga FROM kartu_stok 
						// WHERE uuid_skpd = '$val[id_sub]' AND (jml_in <> 0 OR kode = 's')
						// AND id_barang = '$val[id_bar]' AND soft_delete = 0 AND kode <> 'm' AND tgl_transaksi <= '$tanggal'
						// ORDER BY tgl_transaksi DESC LIMIT 1"));
						if ($smd != '') $saldo = $r1['saldo'] - $r1['pesan'];
						if ($saldo <  $val['jumlah']) {
							echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menambahkan data ! Jumlah Stok $val[nama_bar] Tidak Cukup [ $saldo ] ! ", 'kode' => "00"));
							exit();
						}

						$jumlah = preg_replace("/[^0-9]/", "", $val['jumlah']);
						$baik = preg_replace("/[^0-9]/", "", $val['baik']);
						$ringan = preg_replace("/[^0-9]/", "", $val['ringan']);
						$berat = preg_replace("/[^0-9]/", "", $val['berat']);
						$kadaluarsa = preg_replace("/[^0-9]/", "", $val['kadaluarsa']);

						$array_bar .= "$val[id_bar],";
						$array_jml .= "$jumlah,";
						$array_baik .= "$baik,";
						$array_ringan .= "$ringan,";
						$array_berat .= "$berat,";
						$array_kadalu .= "$kadaluarsa,";
						$array_gudang .= "$val[id_gud],";
						$array_sumber .= "$val[id_sum],";
					}

					$u = mysql_fetch_row(mysql_query("SELECT UUID()"));
					$uuid = $u[0];
					mysql_query("INSERT INTO hapus_barang ( id_hapus_barang, uuid_skpd,
														ta, no_ba_hapus, tgl_ba_hapus, 
														id_pejabat_ketua, id_pejabat_sekretaris, id_pejabat_anggota1, id_pejabat_anggota2,
														jabatan_ketua, jabatan_sekretaris, jabatan_anggota1, jabatan_anggota2,
														no_ba_penunjukan, thn_ba_penunjukan,
														create_date, 
														creator_id,
														soft_delete)
												VALUES ('$uuid', '$id_sub', 
														'$ta', '$nomor_ba', '$tanggal', 
														'$ketua', '$sekretaris', '$anggota1', '$anggota2', 
														'$jab_ket', '$jab_sek', '$jab_ang1', '$jab_ang2',
														'$nomor_sk', '$tahun_sk', 
														'$datime',
														'$pengguna',
														'0')");


					mysql_query("CALL ambil_harga_insert_hapus_barang('$array_bar', '$array_jml', '$array_baik', '$array_ringan', '$array_berat', '$array_kadalu', '$array_sumber', '$array_gudang', '$uuid', '$tanggal', '$id_sub', '$ta', '$datime', '$pengguna')");

					/* foreach($basket AS $val){
					$jumlah = preg_replace("/[^0-9]/","", $val['jumlah']);	
					$harga = preg_replace("/[^0-9]/","", $val['harga']);	
					$baik = preg_replace("/[^0-9]/","", $val['baik']);	
					$ringan = preg_replace("/[^0-9]/","", $val['ringan']);	
					$berat = preg_replace("/[^0-9]/","", $val['berat']);	
					$kadaluarsa = preg_replace("/[^0-9]/","", $val['kadaluarsa']);	
					
					$u = mysql_fetch_row(mysql_query("SELECT UUID()"));
					$uuidet = $u[0];
					
					mysql_query("INSERT INTO hapus_barang_detail(id_hapus_barang_detail, id_hapus_barang, uuid_skpd,
																ta, id_barang, jml_barang, harga_barang, id_gudang,
																baik, ringan, berat, kadaluarsa, id_sumber_dana,
																create_date, 
																creator_id,
																soft_delete)
														VALUES( '$uuidet', '$uuid', '$id_sub', 
																'$ta', '$val[id_bar]', '$jumlah', '$harga', '$val[id_gud]',
																'$baik', '$ringan', '$berat', '$kadaluarsa', '$val[id_sum]',
																'$datime',
																'$pengguna',
																'0')");
					
					$kel = mysql_fetch_assoc(mysql_query("SELECT SUM(jml_in-jml_out) AS jml, id_kelompok 
								FROM kartu_stok k WHERE k.id_barang = '$val[id_bar]' AND k.uuid_skpd = '$id_sub' 
								AND k.id_sumber_dana = '$val[id_sum]' AND k.soft_delete = 0 GROUP BY k.id_kelompok HAVING jml <> 0
								LIMIT 1"));
					
																
					mysql_query("INSERT INTO kartu_stok (id_stok, uuid_skpd, id_barang, id_kelompok, id_gudang, 
														id_transaksi, id_transaksi_detail, id_sumber_dana,
														tgl_transaksi, ta, jml_in, jml_out, harga, kode,
														create_date, soft_delete, creator_id)
												VALUES	(UUID(), '$id_sub', '$val[id_bar]', '$kel[id_kelompok]', '$val[id_gud]', 
														'$uuid', '$uuidet', '$val[id_sum]',
														'$tanggal', '$ta', 0, '$jumlah', '$harga', 'd',
														'$datime', 0, '$pengguna')");
				
				} */

					if (mysql_errno() == 0) echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil ditambahkan !"));
					else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menambahkan data ! ", 'kode' => mysql_errno()));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Nomor Stok Opname sudah ada !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menambahkan data ! ", 'kode' => mysql_errno()));
				}

				break;
			case 'edit':
				if ($ubahform != '') {
					$dataubah = "";
					$form = explode("||", $ubahform);
					foreach ($form as $field) {
						$f = explode('::', $field);
						$v = explode('|', $field);
						if ($f[0] == 'id_sub') {
							$id_sub_ganti = $v[1];
							$dataubah .= "uuid_skpd = '$id_sub_ganti', ";
						} elseif ($f[0] == 'tanggal') {
							$ta_ganti = date('Y', strtotime($v[1]));
							$dataubah .= "ta = '$ta_ganti', ";
						}
					}

					if ($dataubah != "") {
						$dataubah = substr($dataubah, 0, -2);
						mysql_query("UPDATE hapus_barang_detail SET $dataubah ,update_date = '$datime' WHERE id_hapus_barang='$_GET[id_ubah]'");
					}

					mysql_query("UPDATE hapus_barang SET uuid_skpd = '$id_sub',
												ta = '$ta', no_ba_hapus = '$nomor_ba', tgl_ba_hapus = '$tanggal', 
												id_pejabat_ketua = '$ketua', id_pejabat_sekretaris = '$sekretaris', 
												id_pejabat_anggota1 = '$anggota1', id_pejabat_anggota2 = '$anggota2', 
												jabatan_ketua = '$jab_ket', jabatan_sekretaris = '$jab_sek', 
												jabatan_anggota1 = '$jab_ang1', jabatan_anggota2 = '$jab_ang2',
												no_ba_penunjukan = '$nomor_sk', thn_ba_penunjukan = '$tahun_sk',
												update_date = '$datime'  
									WHERE id_hapus_barang = '$_GET[id_ubah]'");
				}



				$datser = mysql_query("SELECT d.id_barang AS id_bar, SUM(jml_barang) AS jumlah, harga_barang AS harga, 
											SUM(baik) AS baik, SUM(ringan) AS ringan, SUM(berat) AS berat, 
											SUM(kadaluarsa) AS kadaluarsa, d.id_gudang AS id_gud, 
											d.id_sumber_dana AS id_sum, id_hapus_barang_detail AS id
									FROM hapus_barang_detail d
									WHERE id_hapus_barang = '$_GET[id_ubah]' AND d.soft_delete=0
									GROUP BY d.id_barang, d.id_gudang, d.id_sumber_dana");

				$edit = array();
				$add = array();
				$del = array();
				$array_bar = $array_jml = $array_baik = $array_ringan = $array_berat = $array_kadalu = $array_gudang = $array_sumber = "";
				while ($da = mysql_fetch_assoc($datser)) {
					$cek = "";
					foreach ($basket as $key => $val) {
						if (isset($val['id'])) { //data lama 
							if ($val['id'] == $da['id']) { //data lama masih ada
								$isi = "";
								$isi2 = "";
								$jumlah = preg_replace("/[^0-9]/", "", $val['jumlah']);
								$baik = preg_replace("/[^0-9]/", "", $val['baik']);
								$ringan = preg_replace("/[^0-9]/", "", $val['ringan']);
								$berat = preg_replace("/[^0-9]/", "", $val['berat']);
								$kadaluarsa = preg_replace("/[^0-9]/", "", $val['kadaluarsa']);
								$harga = preg_replace("/[^0-9]/", "", $val['harga']);

								if ($da['id_bar'] != $val['id_bar']) {
									$isi .= "id_barang = '$val[id_bar]', ";
									$isi2 .= $isi;
								}
								if ($da['jumlah'] != $jumlah) {
									$isi .= "jml_barang = '$jumlah', ";
									$isi2 .= "jml_out = '$jumlah', ";
								}
								if ($da['baik'] != $baik) $isi .= "baik = '$baik', ";
								if ($da['ringan'] != $ringan) $isi .= "ringan = '$ringan', ";
								if ($da['berat'] != $berat) $isi .= "berat = '$berat', ";
								if ($da['kadaluarsa'] != $kadaluarsa) $isi .= "kadaluarsa = '$kadaluarsa', ";
								if ($da['harga'] != $harga) {
									$isi .= "harga_barang = '$harga', ";
									$isi2 .= "harga = '$harga', ";
								}
								if ($da['id_gud'] != $val['id_gud']) {
									$isi .= "id_gudang = '$val[id_gud]', ";
									$isi2 .= "id_gudang = '$val[id_gud]', ";
								}
								if ($da['id_sum'] != $val['id_sum']) {
									$isi .= "id_sumber_dana = '$val[id_sum]', ";
									$isi2 .= "id_sumber_dana = '$val[id_sum]', ";
								}

								if ($isi != "") {
									$ed['id'] = $val['id'];
									$ed['isi'] = $isi;
									$ed['isi2'] = $isi2;
									array_push($edit, $ed);
									$array_bar .= "$val[id_bar],";
									$array_jml .= "$jumlah,";
									$array_baik = "$baik,";
									$array_ringan = "$ringan,";
									$array_berat = "$berat,";
									$array_kadalu = "$kadaluarsa,";
									$array_sumber = "$val[id_sum],";
									$array_gudang = "$val[id_gud],";
									mysql_query("UPDATE hapus_barang_detail SET soft_delete = '1' WHERE id_barang = '$da[id_bar]' AND id_gudang = '$da[id_gud]' AND id_sumber_dana = '$da[id_sum]' AND id_hapus_barang = '$_GET[id_ubah]'");
									mysql_query("UPDATE kartu_stok SET soft_delete = '1' WHERE id_barang = '$da[id_bar]' AND id_gudang = '$da[id_gud]' AND id_sumber_dana = '$da[id_sum]' AND id_transaksi = '$_GET[id_ubah]'");
								}
								unset($basket[$key]);
								$cek = 'ada';
							}
						} else { //data baru
							array_push($add, $basket[$key]);
							unset($basket[$key]);
						}
					}
					if ($cek == "") {
						$el['id_bar'] = $da['id_bar'];
						$el['id_sum'] = $da['id_sum'];
						$el['id_gud'] = $da['id_gud'];

						array_push($del, $el);
					}	//data lama dihapus
				}

				foreach ($add as $val) {
					$jumlah = preg_replace("/[^0-9]/", "", $val['jumlah']);
					$baik = preg_replace("/[^0-9]/", "", $val['baik']);
					$ringan = preg_replace("/[^0-9]/", "", $val['ringan']);
					$berat = preg_replace("/[^0-9]/", "", $val['berat']);
					$kadaluarsa = preg_replace("/[^0-9]/", "", $val['kadaluarsa']);
					$array_bar .= "$val[id_bar],";
					$array_jml .= "$jumlah,";
					$array_baik .= "$baik,";
					$array_ringan .= "$ringan,";
					$array_berat .= "$berat,";
					$array_kadalu .= "$kadaluarsa,";
					$array_sumber .= "$val[id_sum],";
					$array_gudang .= "$val[id_gud],";
				}

				if ($array_bar != "") {
					mysql_query("CALL ambil_harga_insert_hapus_barang('$array_bar', '$array_jml', '$array_baik', '$array_ringan', '$array_berat', '$array_kadalu', '$array_sumber', '$array_gudang', '$_GET[id_ubah]', '$tanggal', '$id_sub', '$ta', '$datime', '$pengguna')");
				}

				/* foreach($edit as $e){
				mysql_query("UPDATE hapus_barang_detail SET $e[isi] update_date = '$datime' WHERE id_hapus_barang_detail = '$e[id]'");
				if($e['isi2']!='') mysql_query("UPDATE kartu_stok SET $e[isi2] update_date = '$datime' WHERE id_transaksi_detail = '$e[id]'");
			}
			
			foreach($add as $val){
				$jumlah = preg_replace("/[^0-9]/","", $val['jumlah']);
				$baik = preg_replace("/[^0-9]/","", $val['baik']);
				$ringan = preg_replace("/[^0-9]/","", $val['ringan']);
				$berat = preg_replace("/[^0-9]/","", $val['berat']);
				$kadaluarsa = preg_replace("/[^0-9]/","", $val['kadaluarsa']);
				$harga = preg_replace("/[^0-9]/","", $val['harga']);
				
				$u = mysql_fetch_row(mysql_query("SELECT UUID()"));
				$uuidet = $u[0];
				mysql_query("INSERT INTO hapus_barang_detail(id_hapus_barang_detail, id_hapus_barang, uuid_skpd,
															ta, id_barang, jml_barang, harga_barang, id_sumber_dana,
															baik, ringan, berat, kadaluarsa,  id_gudang,
															create_date, 
															creator_id,
															soft_delete)
													VALUES( '$uuidet', '$_GET[id_ubah]','$id_sub',
															'$ta', '$val[id_bar]', '$jumlah', '$harga', '$val[id_sum]',
															'$baik', '$ringan', '$berat', '$kadaluarsa', '$val[id_gud]',
															'$datime',
															'$pengguna',
															'0')");
				
				$kel = mysql_fetch_assoc(mysql_query("SELECT SUM(jml_in-jml_out) AS jml, id_kelompok 
								FROM kartu_stok k WHERE k.id_barang = '$val[id_bar]' AND k.uuid_skpd = '$id_sub' 
								AND id_sumber_dana = '$val[id_sum]' AND k.soft_delete = 0 GROUP BY k.id_kelompok HAVING jml <> 0
								LIMIT 1"));
					
				
				mysql_query("INSERT INTO kartu_stok (id_stok, uuid_skpd, id_barang, id_kelompok, id_gudang, 
													id_transaksi, id_transaksi_detail, id_sumber_dana,
													tgl_transaksi, ta, jml_in, jml_out, harga, kode,
													create_date, soft_delete, creator_id)
											VALUES	(UUID(), '$id_sub', '$val[id_bar]', '$kel[id_kelompok]', '$val[id_gud]', 
													'$_GET[id_ubah]', '$uuidet', '$val[id_sum]',
													'$tanggal', '$ta', 0, '$jumlah', '$harga', 'd',
													'$datime', 0, '$pengguna')");											
			} */

				foreach ($del as $id) {
					mysql_query("UPDATE hapus_barang_detail SET soft_delete = '1' WHERE id_barang = '$id[id_bar]'
								AND id_sumber_dana = '$id[id_sum]' AND id_gudang = '$id[id_gud]'");
					mysql_query("UPDATE kartu_stok SET soft_delete = '1' WHERE id_barang = '$id[id_bar]'
								AND id_sumber_dana = '$id[id_sum]' AND id_gudang = '$id[id_gud]'");
				}


				if (mysql_errno() == 0) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil diubah !"));
				} else {
					if (mysql_errno() == 1062) {
						echo json_encode(array(
							'success' => false,
							'pesan' => "Kode Barang Sudah Ada di Unit ini !",
							'error' => "nomor_sama"
						));
					} else echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil mengubah data ! " . mysql_errno()));
				}


				break;
			case 'del':
				mysql_query("UPDATE hapus_barang SET soft_delete = '1' WHERE id_hapus_barang = '$_POST[id_hapus]'");
				mysql_query("UPDATE hapus_barang_detail SET soft_delete = '1' WHERE id_hapus_barang = '$_POST[id_hapus]'");
				mysql_query("UPDATE kartu_stok SET soft_delete = '1' WHERE id_transaksi = '$_POST[id_hapus]'");
				if (mysql_errno() == 0) {
					echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil dihapuskan !"));
				} else {
					echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menghapus data ! ", 'kode' => mysql_errno()));
				}
				break;
		}
	} elseif ($module == 'pengumuman') {
		if (isset($_REQUEST['perihal'])) $perihal = $_REQUEST['perihal'];
		if (isset($_REQUEST['id_pengumuman'])) $id_pengumuman = $_REQUEST['id_pengumuman'];
		if (isset($_REQUEST['pengumuman'])) $pengumuman = $_REQUEST['pengumuman'];
		if (isset($_REQUEST['datafile'])) $datafile = $_REQUEST['datafile'];
		if (isset($_REQUEST['oper'])) $oper = $_REQUEST['oper'];
		if (isset($_REQUEST['id_hapus'])) $id_hapus = $_REQUEST['id_hapus'];
		$tipe_file = array("jpg", "gif", "jpeg", "bmp", "png", "doc", "docx", "xls", "xlsx", "pdf", "");
		$date = date("YmdHis");
		if ($oper == 'add') {
			$filenyaName = $_FILES['datafile']['name'];
			$filenyaType = pathinfo($filenyaName, PATHINFO_EXTENSION);
			if (in_array($filenyaType, $tipe_file)) {
				$filenyaType = $filenyaType;
			} else {
				$filenyaType = "";
			}
			if ($filenyaType == '') {
				$filenya = '';
			} else {
				$filenya = $date . '.' . $filenyaType;
			}
			if (empty($_FILES['datafile']['name'])) {
				$filenyanya = "";
			} else {
				$filenyanya = $filenya;
				move_uploaded_file($_FILES['datafile']['tmp_name'], "images/file/" . $filenyanya);
			}
			$q = mysql_query("INSERT INTO pengumuman VALUES ('','','$perihal','$pengumuman','$filenyanya',NOW(),'0')");
			if ($q) {
				echo json_encode(array('success' => true, 'pesan' => "Pengumuman berhasil disimpan !"));
			} else {
				echo json_encode(array('success' => false, 'pesan' => "Pengumuman gagal disimpan!"));
			}
		} else if ($oper == 'edit') {
			$filenyaName = $_FILES['datafile']['name'];
			$filenyaType = pathinfo($filenyaName, PATHINFO_EXTENSION);
			if (in_array($filenyaType, $tipe_file)) {
				$filenyaType = $filenyaType;
			} else {
				$filenyaType = "";
			}
			if ($filenyaType == '') {
				$filenya = '';
			} else {
				$filenya = $date . '.' . $filenyaType;
			}
			if (empty($_FILES['datafile']['name'])) {
				$filenyanya = "";
			} else {
				$filenyanya = $filenya;
				move_uploaded_file($_FILES['datafile']['tmp_name'], "images/file/" . $filenyanya);
			}
			if ($filenyanya == "") {
				$q = mysql_query("UPDATE pengumuman SET 
													   perihal = '$perihal',
													   isi = '$pengumuman',
													   timestamp = NOW()
											WHERE id_pengumuman = '$id_pengumuman'
													   ");
			} else {
				$q = mysql_query("UPDATE pengumuman SET 
													   perihal = '$perihal',
													   isi = '$pengumuman',
													   file = '$filenyanya',
													   timestamp = NOW()
											WHERE id_pengumuman = '$id_pengumuman'
													   ");
			}
			if ($q) {
				echo json_encode(array('success' => true, 'pesan' => "Edit Pengumuman Berhasil !"));
			} else {
				echo json_encode(array('success' => false, 'pesan' => "Edit Pengumuman Gagal !"));
			}
		} else {

			$q = mysql_query("DELETE FROM pengumuman WHERE id_pengumuman = '$id_hapus'");
			if ($q) {
				echo "Pengumuman Berhasil Dihapus !";
			} else {
				echo "Pengumuman Gagal Dihapus !";
			}
		}
	} elseif ($module == 'forum') {
		if (isset($_REQUEST['id_sub'])) $id_sub = $_REQUEST['id_sub'];
		if (isset($_REQUEST['topik'])) $topik = $_REQUEST['topik'];
		if (isset($_REQUEST['isi'])) $isi = $_REQUEST['isi'];
		if (isset($_REQUEST['inc_files'])) $inc_files = $_REQUEST['inc_files'];
		if (isset($_REQUEST['id_forum'])) $id_forum = $_REQUEST['id_forum'];
		$date = date("His");

		switch ($oper) {
			case 'add':
				$ftype = array('jpg', 'png', 'gif', 'JPG', 'PNG', 'csv', 'xls', 'doc', 'docx', '');
				$fileName = $_FILES['inc_files']['name'];
				$fileType = pathinfo($fileName, PATHINFO_EXTENSION);
				if (in_array($fileType, $ftype)) {
					if ($fileType == '') {
						$file = '';
					} else {
						$file = $date . '.' . $fileType;
					}
					if (empty($_FILES['inc_files']['name'])) {
						$filenya = '';
					} else {
						$filenya = $file;
						move_uploaded_file($_FILES['inc_files']['tmp_name'], "lampiran/" . $filenya);
					}
					mysql_query("INSERT INTO forum VALUES (UUID(),'$id_sub','$_SESSION[idpengguna]','$topik','$isi','$filenya',NOW(),'0')");
					if (mysql_errno() == 0) {
						echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil disimpan !"));
					} else {
						echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menyimpan data ! "));
					}
				} else {
					echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menyimpan data ! "));
				}
				break;
			case 'balas':
				$ftype = array('jpg', 'png', 'gif', 'JPG', 'PNG', 'csv', 'xls', 'doc', 'docx', '');
				$fileName = $_FILES['inc_files']['name'];
				$fileType = pathinfo($fileName, PATHINFO_EXTENSION);
				if (in_array($fileType, $ftype)) {
					if ($fileType == '') {
						$file = '';
					} else {
						$file = $date . '.' . $fileType;
					}
					if (empty($_FILES['inc_files']['name'])) {
						$filenya = '';
					} else {
						$filenya = $file;
						move_uploaded_file($_FILES['inc_files']['tmp_name'], "lampiran/" . $filenya);
					}
					mysql_query("INSERT INTO forum_balas VALUES (UUID(),'$id_forum','$id_sub','$_SESSION[idpengguna]','','$isi','$filenya',NOW(),'0')");
					if (mysql_errno() == 0) {
						echo json_encode(array('success' => true, 'pesan' => "Data telah berhasil disimpan !"));
					} else {
						echo json_encode(array('success' => false, 'pesan' => "Tidak berhasil menyimpan data ! "));
					}
				} else {
					echo json_encode(array('success' => false, 'pesan' => "Format Lampiran tidak sesuai ! "));
				}
				break;
		}
	} else {
		echo json_encode(array('success' => false, 'pesan' => "Model tidak ada !"));
	}
} else {
	echo json_encode(array('success' => false, 'pesan' => "Tidak dapat memproses data, Silahkan login ulang !", 'url' => "../index.php"));
}
mysql_close();
