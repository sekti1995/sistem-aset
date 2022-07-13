<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	require_once "../../config/library.php";
	
	$peran = cekLogin(); 
	$id_sub = isset($_POST['id_sub']) ? $_POST['id_sub'] : 'xxx';
	$petugas = isset($_POST['petugas']) ? $_POST['petugas'] : 'xxx';
	$exe = isset($_POST['exe']) ? $_POST['exe'] : '';
	
	$result = array();
	
	$clause = " SELECT 
					t1.id_barang, 
					t1.harga_barang AS harga1, 
					t2.harga AS harga2, 
					t1.jml_barang AS keluar1, 
					t2.jml_out AS keluar2, 
					t1.soft_delete AS soft_delete1, 
					t2.soft_delete AS soft_delete2,
					t2.id_stok
				FROM 
					keluar_detail t1 LEFT JOIN kartu_stok t2 
					ON t2.id_transaksi_detail = t1.id_keluar_detail 
				WHERE 
					t1.uuid_skpd = '$id_sub' AND 
					t1.soft_delete <> t2.soft_delete ";
				
				
				
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$items = array(); $ttotal = 0; $footer = array();
	while($row = mysql_fetch_assoc($rs)){ 
		$brg = mysql_fetch_assoc(mysql_query(" SELECT * FROM ref_barang WHERE id_barang = '$row[id_barang]' "));
		$row['nama_barang'] = $brg['nama_barang'];
		
			if($row['soft_delete2'] != $row['soft_delete1']){
				if($exe == '1'){
					mysql_query("UPDATE kartu_stok SET soft_delete = '$row[soft_delete1]', creator_id = 'NORMALISASI-COMPARE $petugas' WHERE id_stok = '$row[id_stok]' ");
				}
			}
		array_push($items, $row);
	}
	$result["rows"] = $items;  
	
	
	
	echo json_encode($result);
	mysql_close();
?>