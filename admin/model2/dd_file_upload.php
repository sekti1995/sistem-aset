<?php
	session_start();
	require_once "../../config/db.koneksi.php";
	require_once "../../config/library.php";

	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 8;
	$jenis = isset($_GET['jenis']) ? $_GET['jenis'] : '';
	$tahun = isset($_POST['tahun']) ? $_POST['tahun'] : '';
	if($tahun!='') $th = "AND tahun = '$_POST[tahun]'"; else $th = "";
	if($_SESSION['peran_id']!=md5('1')) $id = "AND MD5(id_sub_unit) = '$_SESSION[idsubunit]'"; else $id = '';
	
	
	$offset = ($page-1)*$rows;
	$result = array();
	$clause = "SELECT *, CONCAT_WS('.', id_sub_unit, id_upload_file) AS id
				FROM upload_file 
				LEFT JOIN Ref_Sub_Unit ON CONCAT_WS('.', Kd_Urusan, Kd_Bidang, Kd_Unit, Kd_Sub) = id_sub_unit
				WHERE jenis_kegiatan = '$jenis' $th $id";
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$rs = mysql_query("$clause LIMIT $rows OFFSET $offset ");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		if($row['state']==0) $row['status'] = 'Belum Direspon'; 
		elseif($row['state']==1) $row['status'] = 'Proses';
		elseif($row['state']==2) $row['status'] = 'Sukses';
		elseif($row['state']==3) $row['status'] = 'Gagal';
		//$row['download'] = "<a href='#' onClick='downKey($row[id])'>Aktivasi.key</a>";
		if($_SESSION['peran_id']==md5('1'))
		$row['nama_file'] = "<a href='aksi.php?module=down_file&id=$row[id]'>$row[nama_upload_file]</a>";
		else $row['nama_file'] = $row['nama_upload_file'];
		$row['time_upload'] = date('d-m-Y H:i:s', strtotime($row['dt_upload']));
		$row['time_download'] = $row['dt_download']!='0000-00-00 00:00:00' ? date('d-m-Y H:i:s', strtotime($row['dt_download'])) : '';
		$row['time_respon'] = $row['dt_respon']!='0000-00-00 00:00:00' ? date('d-m-Y H:i:s', strtotime($row['dt_respon'])) : '';
		array_push($items, $row);
	}
	$result["rows"] = $items;
	echo json_encode($result);
	mysql_close();
?>