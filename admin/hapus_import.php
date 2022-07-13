<?php
date_default_timezone_set('Asia/Jakarta');
include_once "../config/db.koneksi.php";
include_once "../config/db.function.php";
include_once "../config/library.php";
error_reporting(E_ALL); ini_set('display_errors', 'On'); 
session_start();

if(isset($_REQUEST['module'])) $module = $_REQUEST['module']; else $module = "";
if(isset($_REQUEST['oper'])) $oper = $_REQUEST['oper']; else $oper = "";
$dat = date('Y-m-d');
$time= date("H:i:s");
$datime = date('Y-m-d H:i:s');
$pengguna = pengguna();

if($pengguna!=''){
	catatKegiatan($datime, $module, '', $oper);
if ($module=='hapus_import'){
   if(isset($_REQUEST['uuid_skpd']))$uuid_skpd = $_REQUEST['uuid_skpd'];
   if(isset($_REQUEST['smt']))$smt = $_REQUEST['smt'];
   if(isset($_REQUEST['ta']))$ta = $_REQUEST['ta'];
   if(isset($_REQUEST['data']))$data = $_REQUEST['data'];
   
		
	switch ($oper) {
        case 'del':
		
			if($smt==2){ $tglawal = $ta.'-07-01'; $tglakhir = $ta.'-12-31'; }
			else{ $tglawal = $ta.'-01-01'; $tglakhir = $ta.'-06-30'; }
			$ta_adjust = $ta-1;
			
			$c1 = " AND DATE_FORMAT(tgl_penerimaan, '%Y-%m-%d') BETWEEN '$tglawal' AND '$tglakhir'";
			$c2 = " AND DATE_FORMAT(tgl_nota, '%Y-%m-%d') BETWEEN '$tglawal' AND '$tglakhir'";
			$c3 = " AND DATE_FORMAT(tgl_spb, '%Y-%m-%d') BETWEEN '$tglawal' AND '$tglakhir'";
			$c4 = " AND DATE_FORMAT(tgl_sp_out, '%Y-%m-%d') BETWEEN '$tglawal' AND '$tglakhir'";
			$c5 = " AND DATE_FORMAT(tgl_ba_out, '%Y-%m-%d') BETWEEN '$tglawal' AND '$tglakhir'";
			$c6 = " AND DATE_FORMAT(tgl_transaksi, '%Y-%m-%d') BETWEEN '$tglawal' AND '$tglakhir'";
			
			if($data == 1){
				$txt_data = "SALDO AWAL & PENGADAAN & PENGELUARAN";
				
				$q0 = mysql_query(" SELECT * FROM adjust WHERE uuid_skpd = '$uuid_skpd' AND LEFT(tgl_ba,4) = '$ta_adjust' ");
				while($r0 = mysql_fetch_assoc($q0)){
					$d0 = mysql_query(" DELETE FROM adjust_detail WHERE id_adjust = '$r0[id_adjust]' ");
				}
					
				$q1 = mysql_query(" SELECT * FROM masuk WHERE uuid_skpd = '$uuid_skpd' $c1  ");
				while($r1 = mysql_fetch_assoc($q1)){
					$d1 = mysql_query(" DELETE FROM masuk_detail WHERE id_masuk = '$r1[id_masuk]' ");
				}
				
				$q2 = mysql_query(" SELECT * FROM nota_minta WHERE unit_peminta = '$uuid_skpd' $c2  ");
				while($r2 = mysql_fetch_assoc($q2)){
					$d2 = mysql_query(" DELETE FROM nota_minta_detail WHERE id_nota_minta = '$r2[id_nota_minta]' ");
				}
				
				$q3 = mysql_query(" SELECT * FROM surat_minta WHERE unit_peminta = '$uuid_skpd' $c3  ");
				while($r3 = mysql_fetch_assoc($q3)){
					$d3 = mysql_query(" DELETE FROM surat_minta_detail WHERE id_surat_minta = '$r3[id_surat_minta]' ");
				}
				
				$q4 = mysql_query(" SELECT * FROM sp_out WHERE uuid_skpd = '$uuid_skpd' $c4  ");
				while($r4 = mysql_fetch_assoc($q4)){
					$d4 = mysql_query(" DELETE FROM sp_out_detail WHERE id_sp_out = '$r4[id_sp_out]' ");
				}
				
				$q5 = mysql_query(" SELECT * FROM keluar WHERE uuid_skpd = '$uuid_skpd' $c5  ");
				while($r5 = mysql_fetch_assoc($q5)){
					$d5 = mysql_query(" DELETE FROM keluar_detail WHERE id_keluar = '$r5[id_keluar]' ");
				}
				
				
				$d6 = mysql_query(" DELETE FROM kartu_stok WHERE uuid_skpd = '$uuid_skpd' $c6 ") or die(mysql_error());
				$d7 = mysql_query(" DELETE FROM masuk WHERE uuid_skpd = '$uuid_skpd' $c1 ");
				$d8 = mysql_query(" DELETE FROM nota_minta WHERE unit_peminta = '$uuid_skpd' $c2  ");
				$d9 = mysql_query(" DELETE FROM surat_minta WHERE unit_peminta = '$uuid_skpd' $c3 ");
				$d10 = mysql_query(" DELETE FROM sp_out WHERE uuid_skpd = '$uuid_skpd' $c4 ");
				$d11 = mysql_query(" DELETE FROM keluar WHERE uuid_skpd = '$uuid_skpd' $c5 ");
				$d12 = mysql_query(" DELETE FROM adjust WHERE uuid_skpd = '$uuid_skpd' AND LEFT(tgl_ba,4) = '$ta_adjust' ");
				if($d6){
					echo json_encode(array('success'=>true, 'pesan'=>"Data Berhasil Dihapus ! "));
				}else{
					echo json_encode(array('success'=>false, 'pesan'=>"Tidak Berhasil Menghapus Data !"));
				} 
				
			} else if($data == 2){
			
				$txt_data = "PENGADAAN & PENGELUARAN";
					
				$q1 = mysql_query(" SELECT * FROM masuk WHERE uuid_skpd = '$uuid_skpd' $c1  ");
				while($r1 = mysql_fetch_assoc($q1)){
					$d1 = mysql_query(" DELETE FROM masuk_detail WHERE id_masuk = '$r1[id_masuk]' ");
				}
				
				$q2 = mysql_query(" SELECT * FROM nota_minta WHERE unit_peminta = '$uuid_skpd' $c2  ");
				while($r2 = mysql_fetch_assoc($q2)){
					$d2 = mysql_query(" DELETE FROM nota_minta_detail WHERE id_nota_minta = '$r2[id_nota_minta]' ");
				}
				
				$q3 = mysql_query(" SELECT * FROM surat_minta WHERE unit_peminta = '$uuid_skpd' $c3  ");
				while($r3 = mysql_fetch_assoc($q3)){
					$d3 = mysql_query(" DELETE FROM surat_minta_detail WHERE id_surat_minta = '$r3[id_surat_minta]' ");
				}
				
				$q4 = mysql_query(" SELECT * FROM sp_out WHERE uuid_skpd = '$uuid_skpd' $c4  ");
				while($r4 = mysql_fetch_assoc($q4)){
					$d4 = mysql_query(" DELETE FROM sp_out_detail WHERE id_sp_out = '$r4[id_sp_out]' ");
				}
				
				$q5 = mysql_query(" SELECT * FROM keluar WHERE uuid_skpd = '$uuid_skpd' $c5  ");
				while($r5 = mysql_fetch_assoc($q5)){
					$d5 = mysql_query(" DELETE FROM keluar_detail WHERE id_keluar = '$r5[id_keluar]' ");
				}
				
				
				$d6 = mysql_query(" DELETE FROM kartu_stok WHERE uuid_skpd = '$uuid_skpd' $c6 ") or die(mysql_error());
				$d7 = mysql_query(" DELETE FROM masuk WHERE uuid_skpd = '$uuid_skpd' $c1 ");
				$d8 = mysql_query(" DELETE FROM nota_minta WHERE unit_peminta = '$uuid_skpd' $c2  ");
				$d9 = mysql_query(" DELETE FROM surat_minta WHERE unit_peminta = '$uuid_skpd' $c3 ");
				$d10 = mysql_query(" DELETE FROM sp_out WHERE uuid_skpd = '$uuid_skpd' $c4 ");
				$d11 = mysql_query(" DELETE FROM keluar WHERE uuid_skpd = '$uuid_skpd' $c5 ");
				if($d6){
					echo json_encode(array('success'=>true, 'pesan'=>"Data Berhasil Dihapus ! "));
				}else{
					echo json_encode(array('success'=>false, 'pesan'=>"Tidak Berhasil Menghapus Data !"));
				} 
				
			} else if($data == 3){
			
				$txt_data = "SALDO AWAL";
				
				$q0 = mysql_query(" SELECT * FROM adjust WHERE uuid_skpd = '$uuid_skpd' AND LEFT(tgl_ba,4) = '$ta_adjust' ");
				while($r0 = mysql_fetch_assoc($q0)){
					$d0 = mysql_query(" DELETE FROM adjust_detail WHERE id_adjust = '$r0[id_adjust]' ");
				}
				$d6 = mysql_query(" DELETE FROM kartu_stok WHERE uuid_skpd = '$uuid_skpd' $c6 AND kode = 'a'") or die(mysql_error());
				$d12 = mysql_query(" DELETE FROM adjust WHERE uuid_skpd = '$uuid_skpd' AND LEFT(tgl_ba,4) = '$ta_adjust' ");
				if($d12){
					echo json_encode(array('success'=>true, 'pesan'=>"Data Berhasil Dihapus ! "));
				}else{
					echo json_encode(array('success'=>false, 'pesan'=>"Tidak Berhasil Menghapus Data !"));
				} 
			} else if($data == 4){
			
				$txt_data = "PENGADAAN";
					
				$q1 = mysql_query(" SELECT * FROM masuk WHERE uuid_skpd = '$uuid_skpd' $c1  ");
				while($r1 = mysql_fetch_assoc($q1)){
					$d1 = mysql_query(" DELETE FROM masuk_detail WHERE id_masuk = '$r1[id_masuk]' ");
				}
				$d6 = mysql_query(" DELETE FROM kartu_stok WHERE uuid_skpd = '$uuid_skpd' $c6 AND kode = 'i'") or die(mysql_error());
				$d7 = mysql_query(" DELETE FROM masuk WHERE uuid_skpd = '$uuid_skpd' $c1 ");
				if($d6){
					echo json_encode(array('success'=>true, 'pesan'=>"Data Berhasil Dihapus ! "));
				}else{
					echo json_encode(array('success'=>false, 'pesan'=>"Tidak Berhasil Menghapus Data !"));
				} 
			
			} else if($data == 5){
			
				$txt_data = "PENGELUARAN";
				
				$q2 = mysql_query(" SELECT * FROM nota_minta WHERE unit_peminta = '$uuid_skpd' $c2  ");
				while($r2 = mysql_fetch_assoc($q2)){
					$d2 = mysql_query(" DELETE FROM nota_minta_detail WHERE id_nota_minta = '$r2[id_nota_minta]' ");
				}
				
				$q3 = mysql_query(" SELECT * FROM surat_minta WHERE unit_peminta = '$uuid_skpd' $c3  ");
				while($r3 = mysql_fetch_assoc($q3)){
					$d3 = mysql_query(" DELETE FROM surat_minta_detail WHERE id_surat_minta = '$r3[id_surat_minta]' ");
				}
				
				$q4 = mysql_query(" SELECT * FROM sp_out WHERE uuid_skpd = '$uuid_skpd' $c4  ");
				while($r4 = mysql_fetch_assoc($q4)){
					$d4 = mysql_query(" DELETE FROM sp_out_detail WHERE id_sp_out = '$r4[id_sp_out]' ");
				}
				
				$q5 = mysql_query(" SELECT * FROM keluar WHERE uuid_skpd = '$uuid_skpd' $c5  ");
				while($r5 = mysql_fetch_assoc($q5)){
					$d5 = mysql_query(" DELETE FROM keluar_detail WHERE id_keluar = '$r5[id_keluar]' ");
				}
				$d6 = mysql_query(" DELETE FROM kartu_stok WHERE uuid_skpd = '$uuid_skpd' $c6 AND kode = 'ok'") or die(mysql_error());
				$d8 = mysql_query(" DELETE FROM nota_minta WHERE unit_peminta = '$uuid_skpd' $c2  ");
				$d9 = mysql_query(" DELETE FROM surat_minta WHERE unit_peminta = '$uuid_skpd' $c3 ");
				$d10 = mysql_query(" DELETE FROM sp_out WHERE uuid_skpd = '$uuid_skpd' $c4 ");
				$d11 = mysql_query(" DELETE FROM keluar WHERE uuid_skpd = '$uuid_skpd' $c5 ");
				if($d6){
					echo json_encode(array('success'=>true, 'pesan'=>"Data Berhasil Dihapus ! "));
				}else{
					echo json_encode(array('success'=>false, 'pesan'=>"Tidak Berhasil Menghapus Data !"));
				} 
			
			}
			
			$get = mysql_fetch_assoc(mysql_query("SELECT * FROM ref_sub2_unit WHERE uuid_sub2_unit = '$uuid_skpd' "));
			$loghapus = mysql_query(" INSERT INTO log_hapus VALUES('','$get[nm_sub2_unit]','$smt','$ta','$txt_data',NOW()) ");
			
			
			/* 
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
			 */
 			break;
	}
	
}
else{
	echo json_encode(array('success'=>false, 'pesan'=>"Model tidak ada !"));
}
}else{
	echo json_encode(array('success'=>false, 'pesan'=>"Tidak dapat memproses data, Silahkan login ulang !", 'url'=>"../index.php"));
}
mysql_close();
?>