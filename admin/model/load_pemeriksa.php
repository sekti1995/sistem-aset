<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";
	$id_sub = $_POST["id_sub"];
	$tanggal = balikTanggal($_POST["tanggal"]);
	$nomor = $_POST["nomor"];
	$get = mysql_fetch_assoc(mysql_query("SELECT * FROM so WHERE uuid_skpd = '$id_sub' AND tgl_so = '$tanggal' AND no_so = '$nomor'"));
	$q = mysql_fetch_assoc(mysql_query("SELECT * FROM pemeriksa_so WHERE id_so = '$get[id_so]'"));
	 
	
	echo json_encode(array( 
							'nama1'=>$q['nama1'],
							'nama2'=>$q['nama2'],
							'nama3'=>$q['nama3'],
							'nama4'=>$q['nama4'],
							'nama5'=>$q['nama5'],
							'nama6'=>$q['nama6'],
							'nip1'=>$q['nip1'],
							'nip2'=>$q['nip2'],
							'nip3'=>$q['nip3'],
							'nip4'=>$q['nip4'],
							'nip5'=>$q['nip5'],
							'nip6'=>$q['nip6'],
							'gol1'=>$q['gol1'],
							'gol2'=>$q['gol2'],
							'gol3'=>$q['gol3'],
							'gol4'=>$q['gol4'],
							'gol5'=>$q['gol5'],
							'gol6'=>$q['gol6']
						   ));
?>