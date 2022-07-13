<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";
	$peran = cekLogin();
	
	//$peran = $_SESSION['peran_id'];
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$nota = isset($_POST['id_penjualan']) ? strval($_POST['id_penjualan']) : '';
	$m = isset($_POST['mulai']) ? strval($_POST['mulai']) : date('Y-m-d');
	$s = isset($_POST['akhir']) ? strval($_POST['akhir']) : date('Y-m-d');
	$nama = isset($_POST['barang']) ? $_POST['barang'] : '';
	$kategori = isset($_POST['kategori']) ? $_POST['kategori'] : '';
	$gudang = isset($_POST['gudang']) ? $_POST['gudang'] : '';
	$sales = isset($_POST['sales']) ? $_POST['sales'] : '';
	$jenis = isset($_GET['jenis']) ? $_GET['jenis'] : '';
	//bila pelaporan
	if($peran==md5('8')){
		$tabel_field = "seleksi_penjualan.id_seleksi ";
		$order_tabel_field = "RIGHT(seleksi_penjualan.id_seleksi,6) ";
		$tabel = ",seleksi_penjualan";
		$kondisi = " penjualan.id_penjualan=seleksi_penjualan.id_penjualan AND ";
	}else{
		$tabel_field = "penjualan.id_penjualan";
		$order_tabel_field = "RIGHT(penjualan.id_penjualan,6)";
		$tabel = "";
		$kondisi ="";
	}
	$where="";
	if($m!="") $mulai = balikTanggal($m); else $mulai = mulai;
	if($s!="") $akhir = balikTanggal($s); else $akhir = date('Y-m-d');
	if ($nota!='') $c=" AND $tabel_field like '%".$nota."%'";
	else $c='';
	if ($mulai!='' AND $akhir!='') $d="AND DATE_FORMAT (penjualan.tgl_jual,'%Y-%m-%d') BETWEEN '".$mulai."' AND '".$akhir."'";
	else $d='';
	if ($nama!='') $e=" AND stok_bahan.nama_stok_bahan LIKE '%".$nama."%'";
	else $e='';
	if ($kategori!='') $g=" AND stok_bahan.kategori_bahan = '".$kategori."'";
	else $g='';
	if($jenis=="cash") $f = "AND penjualan.jenis_penjualan = 1"; else $f = "AND  penjualan.jenis_penjualan <> 1";
	if($peran!=md5('1') && $peran!=md5('8') && $peran!=md5('6') ) $h = " AND SUBSTR(SUBSTRING_INDEX( SUBSTRING_INDEX( penjualan.id_penjualan,  '-', 3 ) ,  '-', 1 ), 8) = '$_SESSION[iduser]'";
	else $h = "";
	if ($sales!='') $j=" AND penjualan.id_sales = '".$sales."'";
	else $j='';
	if ($gudang!='') $k=" AND admin.id_gudang = '".$gudang."'";
	else $k='';
	
	$where="$c $d $e $f $g $h $j $k";
	$result = array();
	$clause = "SELECT nama_stok_bahan, jumlah, customer.alamat, telpon, hp,
		detail_penjualan.harga, $tabel_field as id_penjualan, penjualan.tgl_jual 
		FROM stok_bahan, penjualan, detail_penjualan, admin, customer $tabel
		WHERE $kondisi penjualan.id_penjualan = detail_penjualan.id_penjualan
		AND stok_bahan.id_stok_bahan = detail_penjualan.id_produk
		AND SUBSTR(SUBSTRING_INDEX( SUBSTRING_INDEX( penjualan.id_penjualan,  '-', 3 ) ,  '-', 1 ), 8) = admin.id 
		AND customer.id_customer = penjualan.id_customer
		".$where." ORDER BY penjualan.tgl_jual, $order_tabel_field ASC";
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$rs = mysql_query("$clause");
	$items = array();
	$total=0; $totjumlah = 0;
	while($row = mysql_fetch_assoc($rs)){
		$row['tgl_jual']= tgl_indo($row['tgl_jual']);
		$row['bayar']= $row['jumlah']*($row['harga']-$row['diskon']);
		$total=$row['bayar']+$total;
		$totjumlah=$row['jumlah']+$totjumlah;
		$row['harga'] = number_format($row['harga'],0,',','.');
		$row['diskon'] = number_format($row['diskon'],0,',','.');
		$row['jumlah'] = number_format($row['jumlah'],0,',','.');
		$row['bayar'] = number_format($row['bayar'],0,',','.');
		
		if($row['telpon']!='' && $row['hp']!='') $row['telepon'] = "$row[telpon] / $row[hp]";
		else $row['telepon'] = "$row[telpon]$row[hp]";
		array_push($items, $row);
	}
	$result["rows"] = $items;
	$item2 = array();
	$row2['petugas'] = "TOTAL";
	$row2['bayar'] = number_format($total,0,',','.');
	$row2['jumlah'] = number_format($totjumlah,0,',','.');
	array_push($item2, $row2);
	$result["footer"]= $item2;
	//$result["footer"]= array(array("bayar" => "total", "total"=> total));
	echo json_encode($result);
	mysql_close();
?>