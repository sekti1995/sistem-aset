<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/db.function.php";
	//$peran = cekLogin();
	$id_role = isset($_GET['id_role']) ? $_GET['id_role'] : '';
	$peserta = isset($_GET['peserta']) ? $_GET['peserta'] : '';
	$peserta2 = isset($_GET['peserta2']) ? $_GET['peserta2'] : '';
	$id_unit_sppb = isset($_GET['id_unit_sppb']) ? $_GET['id_unit_sppb'] : '';
	$skpd = isset($_GET['skpd']) ? 'yes' : 'no';
	
	if($_SESSION['peran_id']==MD5('1')){ $id_sub = ""; $unit = ""; }
	else{
		if($_SESSION['level']==MD5('a')){
			if($_SESSION['uidunit']==MD5('cfa58008-5543-11e6-a2df-000476f4fa98')) $id_sub = " AND MD5(uuid_sub2_unit) = '$_SESSION[uidunit]'";
			else $id_sub = " AND MD5(CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit)) = '$_SESSION[peserta]'";
		}elseif($_SESSION['level']==MD5('b')) $id_sub = "  AND MD5(CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit, kd_sub)) = '$_SESSION[peserta]'";
		else $id_sub = "  AND MD5(uuid_sub2_unit) = '$_SESSION[uidunit]'";
		$unit = isset($_GET['id_unit']) ? " AND MD5(uuid_sub2_unit) ='$_GET[id_unit]'" : '';
	}
	
	$all = isset($_GET['all']) ? "ya" : '';
	
	if($id_role==2 || $id_role==3) $sub = "AND (kd_sub = 1 OR uuid_sub2_unit = 'cfa58008-5543-11e6-a2df-000476f4fa98')";
	elseif($id_role==4){
		if($peserta!=''){
			$sub = "AND CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit) = (SELECT CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit) FROM ref_sub2_unit WHERE uuid_sub2_unit = '$peserta') AND kd_sub2 = 1 AND uuid_sub2_unit <> '$peserta' ";
		}elseif($peserta2!=''){
			$sub = "AND CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit, kd_sub) = (SELECT CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit, kd_sub) FROM ref_sub2_unit WHERE uuid_sub2_unit = '$peserta2') ";
		}else $sub = "";
	}else $sub = "";
	
	if($id_unit_sppb!='') $ids = "AND uuid_sub2_unit = '$id_unit_sppb'";
	else $ids = "";
	if($skpd=="yes") $sd = "AND (kd_sub = 1 OR uuid_sub2_unit = 'cfa58008-5543-11e6-a2df-000476f4fa98')"; else $sd = "";
	
	if($all=="ya") $where = "";
	else $where = "$unit $id_sub $sub $ids $sd";
	
	$clause = "SELECT uuid_sub2_unit AS id, nm_sub2_unit AS text, 
				CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit) AS kode,
				CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit, kd_sub, kd_sub2) AS sub
				FROM ref_sub2_unit WHERE kd_sub2 IS NOT NULL $where 
				ORDER BY kd_urusan, kd_bidang, kd_unit, kd_sub, kd_sub2";
	
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$rs = mysql_query("$clause");
	$items = array(); $c = 1;
	while($row = mysql_fetch_assoc($rs)){
		$que = mysql_fetch_assoc(mysql_query("SELECT uuid_sub2_unit AS ud, nm_sub2_unit AS nm FROM ref_sub2_unit 
											WHERE CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit) = '$row[kode]' LIMIT 1"));
		$row['idskpd'] = $que['ud'];
		$row['nmskpd'] = $que['nm'];
		
		if($row['id']==$row['idskpd'] OR $row['id']=='cfa58008-5543-11e6-a2df-000476f4fa98') $row['peran'] = '1';
		else $row['peran'] = '2';
		if($_SESSION['peran_id']!=MD5('1') AND $all=="" AND $c==1 ){ $row['selected'] = true; $c++; }
		elseif($unit!='') $row['selected'] = true;
		elseif($ids!='') $row['selected'] = true;
		if(isset($_GET['nots'])) $row['selected'] = false;
		if($r==1) $row['selected'] = true;
		
		$kode = explode('.', $row['sub']); 
		$kd1 = str_pad($kode[1],2,'0', STR_PAD_LEFT);
		$kd2 = str_pad($kode[2],2,'0', STR_PAD_LEFT);
		$kd3 = str_pad($kode[3],2,'0', STR_PAD_LEFT);
		//$kd4 = str_pad($kode[4],2,'0', STR_PAD_LEFT);
		$row['awal_dpa'] = $kode[0].".".$kd1.".".$kd2.".".$kd3;//.".".$kd4; 
		unset($row['sub']);
		array_push($items, $row);
	}
	
	echo json_encode($items);
	mysql_close();
	
?>