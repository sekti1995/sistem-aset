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
					*, format(k22,2,'de_DE') as k22, format(k19,2,'de_DE') as k19, format(kurang,2,'de_DE') as kurang 
				FROM 
					import_tmp_out
				WHERE 
					uuid_skpd = '$id_sub' AND
					id_sumber_dana = '$id_sumber_dana' AND
					smt = '$smt' AND
					ta = '$ta' ";
				
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$items = array(); $ttotal = 0; $footer = array();
	while($row = mysql_fetch_assoc($rs)){
		if($row['status'] == '*'){
			$row['status'] = "Diterima";
		} else if($row['status'] == 'x'){
			$row['status'] = "Ditolak";
		} else {
			$row['status'] = "Belum Diperiksa";
		}
		
		
		
		$k21r = str_replace(" ","", $row['k21']);
		$cek_in = mysql_fetch_assoc(mysql_query("  SELECT * FROM import_tmp_in WHERE REPLACE(k9, ' ','') = '$k21r' AND uuid_skpd = '$id_sub' " ));
		
		if($cek_in['k8'] != $row['k20']){
			//$row['k20'] = "<b style='color:#ef2222'>".$cek_in['a']." ".$cek_in['id']." = ".$row['id']." " .$row['k20']."<b style='color:#ef2222'>";
			$row['k20'] = "<b style='color:#ef2222'>ID BARANG MASUK & KELUAR BEDA<b style='color:#ef2222'>";
		} else {
		
			if($row['k20'] == ""){
				$row['k21'] = "<b style='color:#ef2222'>ID BARANG KOSONG</b>";
			} else if(strlen($row['k20']) != 36){
				$row['k21'] = "<b style='color:#ef2222'>ID BARANG SALAH</b>";
			}
		
		}
		
		array_push($items, $row);
	}
	$result["rows"] = $items;  
	
	$tot = mysql_fetch_assoc(mysql_query("  SELECT 
												format(SUM(k19*k22),2,'de_DE') as sk22, format(SUM(k19),2,'de_DE') as sk19, format(SUM(kurang),2,'de_DE') as skurang 
											FROM 
												import_tmp_out
											WHERE 
												uuid_skpd = '$id_sub' AND
												id_sumber_dana = '$id_sumber_dana' AND
												smt = '$smt' AND
												ta = '$ta' "));
	$foot["k15"] = "<b>TOTAL</b>";
	$foot["k19"] = "<b>".$tot["sk19"]."</b>";
	$foot["k22"] = "<b>".$tot["sk22"]."</b>";
	$foot["kurang"] = "<b>".$tot["skurang"]."</b>";
	
	array_push($footer, $foot);
	$result["footer"] = $footer;
	
	echo json_encode($result);
	mysql_close();
?>