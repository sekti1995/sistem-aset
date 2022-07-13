<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";
	
	$peran = cekLogin();
	$id_sub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
	$id_sumber = isset($_POST['id_sumber']) ? $_POST['id_sumber'] : '';
	$thn = isset($_POST['thn']) ? $_POST['thn'] : '';
	$bln = isset($_POST['bln']) ? str_pad($_POST['bln'],2,'0', STR_PAD_LEFT) : '';
	$id_bar = isset($_POST['id_bar']) ? $_POST['id_bar'] : '';
	
	
	if($thn!="") $a = " AND ta <= '$thn'";
	else $a = "";
	
	if($id_sub=="")	$b = " AND MD5(uuid_skpd) = '$_SESSION[uidunit]'";
	else $b = " AND uuid_skpd = '$id_sub'";
	
	if($bln!="" AND $bln!="00") $c = " AND DATE_FORMAT(tgl_transaksi, '%m') = '$bln'"; 
	else $c = "";
	
	if($id_sumber!="") $d = " AND id_sumber_dana = '$id_sumber'"; 
	else $d = "";
	
	$result = array();
	$clause = "SELECT tgl_transaksi AS tanggal, jml_in AS masuk, jml_out AS keluar, harga FROM kartu_stok 
				WHERE soft_delete = 0 AND kode <> 'm' $a $b $c $d
				AND id_barang = '$id_bar' ORDER BY tgl_transaksi, create_date";
				
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$items = array(); $sisa = 0;
	while($row = mysql_fetch_assoc($rs)){
		$sisa = $sisa + $row['masuk'] - $row['keluar'];
		$row['tanggal'] = balikTanggalIndo($row['tanggal']);
		$row['masuk'] = number_format($row['masuk'], 0, ',', '.');
		$row['harga_masuk'] = number_format($row['harga'], 0, ',', '.');
		$row['keluar'] = number_format($row['keluar'], 0, ',', '.');
		$row['harga_keluar'] = number_format($row['harga'], 0, ',', '.');
		$row['sisa'] = number_format($sisa, 0, ',', '.');
		array_push($items, $row);
	}
	$result["rows"] = $items;
	echo json_encode($result);
	mysql_close();
?>