<?php
date_default_timezone_set('Asia/Jakarta');
require_once "config/db.koneksi.php";
require_once "config/db.function.php";


if(isset($_REQUEST['oper'])) $oper = $_REQUEST['oper']; else $oper = "";
if(isset($_REQUEST['ta'])) $post_ta = $_REQUEST['ta']; else $oper = "";
if(isset($_REQUEST['id_sumber'])) $post_id_sumber = $_REQUEST['id_sumber']; else $post_id_sumber = "";
//$handle = $_FILES["files"]["tmp_name"];
//echo $handle;
$datime = date('Y-m-d H:i:s');

$username = mysql_real_escape_string($_POST["uname"]);
$password = mysql_real_escape_string($_POST["password"]);

switch ($oper) {
    case 'cek_login2':
		$query = mysql_query("SELECT * FROM ref_pengelola WHERE username='$username' and password='$password' AND state = 0");
		$ketemu=mysql_num_rows($query);
		$r = mysql_fetch_assoc($query);
		if($ketemu > 0){
			if($r['lokasi_key']!="")
				echo json_encode(array('success'=>true, 'url'=>"cek_login.php?oper=cek_key&uname=$r[username]&password=$r[password]", 'lokasi'=>$r['lokasi_key']));
			else
				echo json_encode(array('success'=>true, 'url'=>"index.php?aktiv", 'lokasi'=>''));
		}else{
			echo json_encode(array('success'=>false, 'url'=>"index.php"));
		}
		
		break;
	case 'cek_login':
		$cookie_name = md5("key_SIMDATrans");
		$aktif = isset($_POST['aktif']) ? $_POST['aktif'] : '';
		//$c_val = $aktif='ya' ? $_COOKIE[$cookie_name] : $_POST['kunci'];
		//if($aktif=='ya') $c_val = $_COOKIE[$cookie_name]; else $c_val = md5("mda".$_POST['kunci']."2016");
		
		$query = "SELECT id_pengelola, username, id_akses, uuid_skpd FROM ref_pengelola WHERE username='$username' and password='$password' AND state = 0 AND soft_delete = 0";
		$login=mysql_query($query);
		$ketemu=mysql_num_rows($login);
		$r=mysql_fetch_array($login);
		
		
		
		if ($ketemu > 0){
			
			// if($r['id_akses']==1){
					//$query2 = mysql_query("SELECT id_pengelola FROM ref_pengelola WHERE id_pengelola = '$r[id_pengelola]' 
											//AND MD5(CONCAT('mda',serial_key,'2016')) = '$c_val'");
					//$ada = mysql_num_rows($query2);
					//if($ada >= 0){
						$q3 = mysql_fetch_assoc(mysql_query("SELECT nm_sub2_unit, kd_urusan, kd_bidang, kd_unit, kd_sub, kd_sub2, uuid_sub2_unit 
												FROM ref_sub2_unit WHERE uuid_sub2_unit = '$r[uuid_skpd]'"));
						session_start();
						if($post_ta != "" && $post_id_sumber != ""){
						$_SESSION['nm_sub2_unit'] = $q3['nm_sub2_unit'];
						$_SESSION['sesi_ta'] = $post_ta;
						$_SESSION['sesi_sd'] = $post_id_sumber;
						}
					//	$_SESSION['namauser'] = $r['nama_pengelola'];
						$_SESSION['kd_unit'] = $q3['kd_unit'];
						$_SESSION['kd_sub'] = $q3['kd_sub'];
						$_SESSION['kd_sub2'] = $q3['kd_sub2'];
						
						$_SESSION['username'] = $r['username'];
					//	$_SESSION['passuser'] = $r['password'];
						$_SESSION['idpengguna']= md5($r['id_pengelola']);
						$_SESSION['peran_id']= md5($r['id_akses']);
					//	$_SESSION['idsub2unit']= md5($r['id_sub2_unit']);
						$_SESSION['uidunit_plain']= $r['uuid_skpd'] ;
						$_SESSION['uidunit']= md5($r['uuid_skpd']);
						
						
						if($q3['kd_sub'] == 1 && $q3['kd_sub2'] == 1){
							//UPT
							$op = mysql_fetch_assoc(mysql_query("SELECT * FROM ref_sub2_unit WHERE kd_unit = '$q3[kd_unit]' AND kd_sub = 1 AND kd_sub2 = 1"));
							$_SESSION['jenis'] = "OPD";
							$_SESSION['id_opd'] = $op['uuid_sub2_unit'];
						} else if($q3['kd_sub'] > 1 && $q3['kd_sub2'] == 1){
							//UPT
							$op1 = mysql_fetch_assoc(mysql_query("SELECT * FROM ref_sub2_unit WHERE kd_unit = '$q3[kd_unit]' AND kd_sub = 1 AND kd_sub2 = 1"));
							$op2 = mysql_fetch_assoc(mysql_query("SELECT * FROM ref_sub2_unit WHERE kd_unit = '$q3[kd_unit]' AND kd_sub = '$q3[kd_sub]' AND kd_sub2 = 1"));
							$_SESSION['jenis'] = "UPT";
							$_SESSION['id_opd'] = $op1['uuid_sub2_unit'];
							$_SESSION['id_upt'] = $op2['uuid_sub2_unit'];
						} else if($q3['kd_sub'] > 1 && $q3['kd_sub2'] > 1){
							//UPT
							$op1 = mysql_fetch_assoc(mysql_query("SELECT * FROM ref_sub2_unit WHERE kd_unit = '$q3[kd_unit]' AND kd_sub = 1 AND kd_sub2 = 1"));
							$op2 = mysql_fetch_assoc(mysql_query("SELECT * FROM ref_sub2_unit WHERE kd_unit = '$q3[kd_unit]' AND kd_sub = '$q3[kd_sub]' AND kd_sub2 = 1"));
							$_SESSION['jenis'] = "UPT";
							$_SESSION['id_opd'] = $op1['uuid_sub2_unit'];
							$_SESSION['id_upt'] = $op2['uuid_sub2_unit'];
							$op = mysql_fetch_assoc(mysql_query("SELECT * FROM ref_sub2_unit WHERE kd_unit = '$q3[kd_unit]' AND kd_sub = '$q3[kd_sub]' AND kd_sub2 = '$q3[kd_sub2]'"));
							$_SESSION['jenis'] = "UPB";
							$_SESSION['id_upb'] = $op['uuid_sub2_unit'];
						} else {
							$_SESSION['jenis'] = "";
						}
						
						$_SESSION['kode_sub']= $q3['kd_urusan'].'.'.$q3['kd_bidang'].'.'.$q3['kd_unit'] ;
						
						$_SESSION['kode_skpd']= $q3['kd_urusan'].'.'.$q3['kd_bidang'].'.'.$q3['kd_unit'].'.'.$q3['kd_sub'].'.'.$q3['kd_sub2'] ;
						
						if($r['id_akses']==1 or $r['id_akses']==7) $_SESSION['level'] = MD5('');
						else{
							if($q3['kd_sub']==1 or $q3['uuid_sub2_unit'] == 'cfa58008-5543-11e6-a2df-000476f4fa98'){
								$_SESSION['level'] = MD5('a'); 
								$_SESSION['peserta'] = MD5($q3['kd_urusan'].'.'.$q3['kd_bidang'].'.'.$q3['kd_unit']);
							} else {
								if($q3['kd_sub2']==1){
									$_SESSION['level'] = MD5('b');
									$_SESSION['peserta'] = MD5($q3['kd_urusan'].'.'.$q3['kd_bidang'].'.'.$q3['kd_unit'].'.'.$q3['kd_sub']);
								}else $_SESSION['level'] = MD5('c');
							}
						}
						//setcookie($cookie_name, $c_val, time() + (86400 * 365), "/");
						catatKegiatan($datime, 'Login', '', '');
						//echo json_encode(array('success'=>false, 'url'=>"index.php", 'pesan'=>$c_val));
						echo json_encode(array('success'=>true, 'url'=>"admin/media.php?module=home"));
						
					/* }else{
						setcookie($cookie_name, '', time() - (86400 * 365), "/");
						if($aktif=='ya') $pesan = "Akun belum diaktifkan!"; else $pesan = "File Aktivasi salah !";
						echo json_encode(array('success'=>false, 'url'=>"index.php", 'pesan'=>$pesan));
					} */
			// } else {
				// echo json_encode(array('success'=>false, 'url'=>"index.php", 'pesan'=>"Maaf Aplikasi SIMBAPER Sedang Persiapan Entri 2020 !"));
			// }
		}else{
			echo json_encode(array('success'=>false, 'url'=>"index.php", 'pesan'=>"Username atau password salah!"));
		}
		break;
}		

mysql_close();

?>