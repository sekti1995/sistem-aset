<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";
	
	$peran = cekLogin(); 
	
	$id_sub = isset($_POST['id_sub']) ? $_POST['id_sub'] : 'xxx';
	$id_sumber_dana = isset($_POST['id_sumber_dana']) ? $_POST['id_sumber_dana'] : 'xxx';
	$smt = isset($_POST['smt']) ? $_POST['smt'] : 'xxx';
	$ta = isset($_POST['ta']) ? $_POST['ta'] : 'xxx';
	
	
	$result = array(); $ttotal1 = 0; $ttotal2 = 0; $footer = array();
	
	$clause = " SELECT 
					*, k10 as kk10, format(k10,2,'de_DE') as k10, format(k7,2,'de_DE') as k7 
				FROM 
					import_tmp_in 
				WHERE 
					uuid_skpd = '$id_sub' AND
					id_sumber_dana = '$id_sumber_dana' AND
					smt = '$smt' AND
					ta = '$ta'
			  ";
				
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$items = array(); $ttotal = 0; $footer = array();
	while($row = mysql_fetch_assoc($rs)){
		$hrg = mysql_fetch_assoc(mysql_query("SELECT harga_index as hi, format(harga_index,2,'de_DE') as harga_index FROM ref_barang WHERE id_barang = '$row[k8]'"));
		$row['harga_index'] = $hrg['harga_index'];
		
		if((int)$row['k10'] > (int)$hrg['harga_index']){
			$row['hrg'] = "1";
		} else {
			$row['hrg'] = "0";
		}
		
			$row['hi'] = $hrg['hi'];
		
		if($row['status'] == '*'){
			$row['status'] = "Diterima";
		} else if($row['status'] == 'x'){
			$row['status'] = "Ditolak";
		} else {
			$row['status'] = "Belum Diperiksa";
		}
		
		if($row['k8'] == ""){
			$row['k9'] = "<b style='color:#ef2222'>ID BARANG KOSONG</b>";
		} else if(strlen($row['k8']) != 36){
			$row['k9'] = "<b style='color:#ef2222'>ID BARANG SALAH</b>";
		}
		
		array_push($items, $row);
	}
	$result["rows"] = $items;  
	
	$tot = mysql_fetch_assoc(mysql_query("  SELECT 
												format(SUM(k7*k10),2,'de_DE') as sk10, format(SUM(k7),2,'de_DE') as sk7 
											FROM 
												import_tmp_in 
											WHERE 
												uuid_skpd = '$id_sub' AND
												id_sumber_dana = '$id_sumber_dana' AND
												smt = '$smt' AND
												ta = '$ta'"));
	$foot["k1"] = "<b>TOTAL</b>";
	$foot["k7"] = "<b>".$tot["sk7"]."</b>";
	$foot["k10"] = "<b>".$tot["sk10"]."</b>";
	
	array_push($footer, $foot);
	$result["footer"] = $footer;
	
	echo json_encode($result);
	mysql_close();
?>