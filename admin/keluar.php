<?php
date_default_timezone_set('Asia/Jakarta');
//error_reporting(E_ALL); ini_set('display_errors', 'On'); 
include_once "../config/db.koneksi.php";
include_once "../config/db.function.php";
include_once "../config/library.php";
session_start();

if(isset($_REQUEST['module'])) $module = $_REQUEST['module']; else $module = "";
if(isset($_REQUEST['oper'])) $oper = $_REQUEST['oper']; else $oper = "";
$dat = date('Y-m-d');
$time= date("H:i:s");
$datime = date('Y-m-d H:i:s');
$pengguna = pengguna($DBcon);

if($pengguna!=''){
	catatKegiatan($datime, $module, '', $oper, $DBcon);
if($module=='ganti_password'){ // ::PDO
	$pass_lama=$_POST['pass_lama'];
	$pass_baru=$_POST['pass_baru'];
	$pass_baru2=$_POST['pass_baru2'];
	
	$pdo_query = $DBcon->prepare(" SELECT * FROM ref_pengelola WHERE md5(id_pengelola) = ? AND state = ? ");
	$pdo_query->execute(array($_SESSION["idpengguna"], 0));
	$r = $pdo_query->fetch(PDO::FETCH_ASSOC);
	
	if ($pass_lama==$r['password']){
		
		$pdo_query = $DBcon->prepare(" UPDATE ref_pengelola SET password = ? WHERE md5(id_pengelola) = ? ");
		$pdo_query->execute(array($pass_baru, $_SESSION["idpengguna"]));
		
		if($pdo_query) {
			echo json_encode(array('success'=>true, 'pesan'=>"Data telah berhasil disimpan !"));
		} else {
			echo json_encode(array('success'=>true, 'pesan'=>"Gagal merubah password !"));
		}
		
	}else{
		echo json_encode(array('success'=>false, 'pesan'=>"Password lama salah!"));
	}

} elseif ($module=="nota_minta_baru"){
	if(isset($_REQUEST['form']))$form = $_REQUEST['form'];
	if(isset($_REQUEST['basket']))$basket = $_REQUEST['basket'];
	if(isset($_REQUEST['ubahform']))$ubahform = $_REQUEST['ubahform'];
	if(isset($form['id_sub']))$id_sub = $form['id_sub'];
	if(isset($form['ta']))$ta = $form['ta'];
	if(isset($form['vjenis']))$vjenis = $form['vjenis'];
	if(isset($form['iduntuk']))$iduntuk = $form['iduntuk'];
	if(isset($form['txtuntuk']))$txtuntuk = $form['txtuntuk'];
	if(isset($form['tanggal']))$tanggal = balikTanggal($form['tanggal']);
	if(isset($form['nomor']))$nomor = $form['nomor'];
	if(isset($form['id_sumber']))$id_sumber = $form['id_sumber'];
	
	
	if(isset($form['no_spb']))$no_spb = $form['no_spb'];
	if(isset($form['tanggal_spb']))$tanggal_spb = balikTanggal($form['tanggal_spb']);
	
	if(isset($form['no_surat']))$no_surat = $form['no_surat'];
	if(isset($form['tgl_surat']))$tgl_surat = balikTanggal($form['tgl_surat']);
	
	if($vjenis==0) $iduntuk = "";
	elseif($vjenis==1) $txtuntuk = "";
	$jenis_out = 'ok';
	switch ($oper) {
        case 'add':
		
				$pdo_query = $DBcon->prepare(" SELECT UUID() ");
				$pdo_query->execute();
				$u = $pdo_query->fetch(PDO::FETCH_NUM);
				$uuid = $u[0];
			
				$pdo_query2 = $DBcon->prepare(" SELECT UUID() ");
				$pdo_query2->execute();
				$u2 = $pdo_query2->fetch(PDO::FETCH_NUM);
				$uuid2 = $u2[0];
			
				$pdo_query3 = $DBcon->prepare(" SELECT UUID() ");
				$pdo_query3->execute();
				$u3 = $pdo_query3->fetch(PDO::FETCH_NUM);
				$uuid3 = $u3[0];
				
				$pdo_queryk = $DBcon->prepare(" SELECT UUID() ");
				$pdo_queryk->execute();
				$uk = $pdo_queryk->fetch(PDO::FETCH_NUM);
				$uuidk = $uk[0];
			
			try {
				$DBcon->beginTransaction();
				
				$pdo_query = $DBcon->prepare("INSERT INTO nota_minta(id_nota_minta, unit_peminta, ta, no_nota, tgl_nota, 
												stat_untuk, unit_dituju, peruntukan, status,
												create_date, 
												creator_id,
												soft_delete)
										VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
				$pdo_query->execute(array($uuid, $id_sub, $ta, $nomor, $tanggal,  $vjenis, $iduntuk, $txtuntuk, 1, $datime, $pengguna, 0));
				
				
				if($vjenis==0){ 
					$skpd_sp = $id_sub; 
					$iduntuk_sp = ""; 
					$txtuntuk_sp = $txtuntuk; 
				} else{ 
					/* $skpd_sp = $iduntuk; 
					$iduntuk_sp = $id_sub; 
					$txtuntuk_sp = $txtuntuk;  */
					$skpd_sp = $id_sub; 
					$iduntuk_sp = ""; 
					$txtuntuk_sp = $txtuntuk;
				}
											
				$pdo_query = $DBcon->prepare("INSERT INTO surat_minta (id_surat_minta,
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
										VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
				$pdo_query->execute(array($uuid2, $id_sub, $vjenis, $iduntuk, $txtuntuk, $ta, $uuid, $no_spb, $tanggal_spb, 1, $datime, $pengguna, 0));
				
				$pdo_query = $DBcon->prepare("INSERT INTO sp_out(id_sp_out, id_surat_minta, uuid_skpd, ta, stat_untuk, uuid_untuk, peruntukan,
											no_sp_out, tgl_sp_out, status,
											create_date, 
											creator_id,
											soft_delete)
									VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
				$pdo_query->execute(array($uuid3, $uuid2, $skpd_sp, $ta, $vjenis, $iduntuk_sp, $txtuntuk_sp,  $no_surat, $tgl_surat, 1, $datime, $pengguna, 0));
				$peruntukan = $txtuntuk_sp;
				$id_untuk = $iduntuk_sp;
				if($jenis_out=='s') $peruntukan = "";
				else $id_untuk = "";
				$kode = "o".$jenis_out;
				$pdo_query = $DBcon->prepare("INSERT INTO keluar(id_keluar, uuid_skpd, ta, id_sp_out, no_ba_out, tgl_ba_out,
												jenis_out, uuid_untuk, peruntukan, no_reklas, tgl_reklas, 											
												create_date, 
												creator_id,
												soft_delete)
										VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
				$pdo_query->execute(array($uuidk, $id_sub, $ta, $uuid3, $no_surat, $tgl_surat, $jenis_out, $id_untuk, $peruntukan, '', '', $datime, $pengguna, 0));
				
				$values = "";
				$array_bar = "";
				$array_jml = "";
				$array_tgl = "";
				$array_gud = "";
				$array_sum = "";
				foreach($basket AS $val){
					//$harga = preg_replace("/[^0-9]/","", $val['harga']);
					//$jumlah = preg_replace("/[^0-9]/","", $val['jmlkeluar']); 
					$harga = $val['harga'] ;	
					$jumlah = $val['jmlkeluar'] ;
					 
					
					$harga = str_replace('.','',$harga);
					$jumlah = str_replace('.','',$jumlah);
					$jumlah = str_replace(',','.',$jumlah);
					
					
					$array_bar .= "$val[id_bar],";
					$array_jml .= "$jumlah,";
					$array_tgl .= "$tgl_surat,";
					$array_gud .= "0,";
					$array_sum .= "$id_sumber,";
					 
					
					$pdo_query = $DBcon->prepare("INSERT INTO nota_minta_detail ( id_nota_minta_detail, id_nota_minta, uuid_skpd,
															ta, id_barang, jumlah, ket,
															create_date, 
															creator_id,
															soft_delete)
													VALUES( UUID(), ?,?,?,?,?,?,?,?,?)");
															
					$pdo_query->execute(array($uuid, $id_sub, $ta, $val['id_bar'], $jumlah, $val['ket'], $datime, $pengguna, 0));
					
					$pdo_query = $DBcon->prepare("INSERT INTO surat_minta_detail (id_surat_minta_detail, id_surat_minta, uuid_skpd, ta, id_barang, jumlah,
																create_date, creator_id)
														  VALUES(UUID(), ?,?,?,?,?,?,?)");
																
					$pdo_query->execute(array($uuid2, $id_sub, $ta, $val['id_bar'], $jumlah, $datime, $pengguna));
					
				}
				
				if($array_bar!=""){
					//$values = substr($values, 0, -2);
					$pdo_query = $DBcon->prepare("CALL ambil_harga_insert_sp_out_detail (?,?,?,?,?,?,?,?,?)");
					$pdo_query->execute(array($array_bar, $array_jml, $tgl_surat, $id_sub, $uuid2, $uuid3, $ta, $datime, $pengguna));

					$pdo_query = $DBcon->prepare("CALL ambil_harga_insert_keluar_detail(?,?,?,?,?,?,?,?,?,?,?,?)");
					$pdo_query->execute(array($array_bar, $array_jml, $array_tgl, $array_gud, $array_sum, $id_sub, $uuidk, $ta, $datime, $pengguna, $tanggal_spb, $kode));
				}			
				
				/* 
				$pdo_query = $DBcon->prepare("UPDATE surat_minta SET status=1 WHERE id_surat_minta = ?");
				$pdo_query->execute(array($uuid2));
				 */
				 
				$pdo_query = $DBcon->prepare("UPDATE sp_out o LEFT JOIN surat_minta m ON o.id_surat_minta = m.id_surat_minta
								SET o.status = 3, m.status = 3 WHERE id_sp_out = '$uuid3'");
				$pdo_query->execute();
				
			
				$DBcon->commit();
				echo json_encode(array('success'=>true, 'pesan'=>"Data berhasil dimasukkan !"));
			
			} catch (PDOException $es) {
				$DBcon->rollback();
				echo json_encode(array('success'=>false, 'pesan'=>"Tidak berhasil memasukkan data !", 'error'=>$es->getMessage() ));
			}	
			
		break;
		case 'edit':
			if($ubahform!=''){
				$dataubah = ""; $id_sub_ganti = "";
				$form = explode("||", $ubahform);
				foreach($form as $field){
					$f = explode('::',$field);
					$v = explode('|',$field);
					if($f[0]=='id_sub'){
						$id_sub_ganti = $v[1];
						//$kdg = explode('.',$id_sub_ganti);
						$dataubah .= "uuid_skpd = '$id_sub_ganti', ";
					}elseif($f[0]=='ta'){
						$ta_ganti = $v[1];
						$dataubah .= "ta = '$ta_ganti', ";
					}
				}
				
				if($dataubah!=""){
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
			for($i=0; $i<$fi;$i++){
				$lab[$i] = mysql_field_name($datser, $i); 
			}
			
			$edit = array(); $add = array(); $del = array();
			while($da = mysql_fetch_assoc($datser)){
				$cek = "";
				foreach ($basket as $key => $val){
					if(isset($val['id'])){ //data lama 
						if($val['id']==$da['id']){ //data lama masih ada
							$isi = "";
							for($i=0; $i<$fi;$i++){ //ulang per nama field
								$label = $lab[$i];
								if($da[$label] != $val[$label]){ //data lama yang diubah
									if($label=='id_bar') $isi .= "id_barang = '$val[$label]', ";
									elseif($label=='jumlah') $isi .= "jumlah = '".preg_replace("/[^0-9]/","", $val[$label])."', ";
									elseif($label=='ket') $isi .= "ket = '$val[$label]', ";
								}
							}
							
							if($isi!=""){
								$ed['id'] = $val['id'];
								$ed['isi'] = substr($isi, 0, -2);
								array_push($edit, $ed);
							}
							unset($basket[$key]);
							$cek = 'ada';
						}
					}
				}
				if($cek=="") array_push($del, $da['id']); //data lama dihapus
			}
			
			$add = $basket; //data baru sisa hasil pengecekan
			
			foreach($edit as $e){
				//echo $e['isi'];
				mysql_query("UPDATE nota_minta_detail SET $e[isi] , update_date = '$datime' WHERE id_nota_minta_detail = '$e[id]'");
			}
			
			foreach($add as $val){
				$jml = preg_replace("/[^0-9]/","", $val['jumlah']);
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
			
			foreach($del as $id){
				mysql_query("UPDATE nota_minta_detail SET soft_delete = '1' WHERE id_nota_minta_detail = '$id' AND id_nota_minta = '$_GET[id_ubah]'");
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
			
		
		break;
		case 'del':
			mysql_query("UPDATE nota_minta SET soft_delete = '1' WHERE id_nota_minta = '$_POST[id_hapus]'");
			mysql_query("UPDATE nota_minta_detail SET soft_delete = '1' WHERE id_nota_minta = '$_POST[id_hapus]'");
			if(mysql_errno()==0){
				echo json_encode(array('success'=>true, 'pesan'=>"Data telah berhasil dihapuskan !"));
            }else{
				echo json_encode(array('success'=>false, 'pesan'=>"Tidak berhasil menghapus data ! ", 'kode'=>mysql_errno()));
			}
 			
		break;
		
	}
	
} else{
	echo json_encode(array('success'=>false, 'pesan'=>"Model tidak ada !"));
}
}else{
	echo json_encode(array('success'=>false, 'pesan'=>"Tidak dapat memproses data, Silahkan login ulang !", 'url'=>"../index.php"));
}
//mysql_close();
?>