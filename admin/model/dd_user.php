<?php
	require_once "../../config/db.koneksi.php";

	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$id_sub = isset($_POST['id_sub']) ? $_POST['id_sub'] : '';
	$nama = isset($_POST['nama']) ? $_POST['nama'] : '';
	
	if($id_sub!='') $a = "AND uuid_sub2_unit = '$id_sub'";
	else $a = "";
	if($nama!='') $b = "AND nama_pengelola LIKE '%$nama%'";
	else $b = "";
	
	$where = "$a $b";
	$offset = ($page-1)*$rows;
	$result = array();
	$clause = "SELECT 
				p1.id_pengelola, nama_pengelola ,p1.id_golongan, p1.id_jabatan, nip ,alamat, telpon, email, ta, username, password, p1.id_akses, state, serial_key, create_date, update_date, soft_delete, creator_id,
				r3.nm_sub2_unit,r2.nama_jabatan, r1.nama_golongan, uuid_skpd AS id_sub2_unit, id_role,
				CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit, 1, 1) AS id_unit,
				CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit, kd_sub, 1) AS id_sub_unit,
				id_pengelola AS id
				FROM ref_pengelola p1
				LEFT JOIN ref_sub2_unit r3 ON p1.uuid_skpd = r3.uuid_sub2_unit
				LEFT JOIN ref_jabatan r2 ON p1.id_jabatan=r2.id_jabatan
				LEFT JOIN ref_golongan r1 ON p1.id_golongan=r1.id_golongan
				LEFT JOIN ref_akses a ON a.id_akses=p1.id_akses
				WHERE p1.soft_delete =0 $where";
	$rs = mysql_query($clause);
	$r = mysql_num_rows($rs);
	$result["total"] = $r;
	$rs = mysql_query("$clause LIMIT $rows OFFSET $offset ");
	$items = array();
	while($row = mysql_fetch_assoc($rs)){
		$id_unit = mysql_fetch_assoc(mysql_query("SELECT uuid_sub2_unit AS id FROM ref_sub2_unit 
										WHERE CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit, kd_sub, kd_sub2) = '$row[id_unit]'"));
		$id_sub = mysql_fetch_assoc(mysql_query("SELECT uuid_sub2_unit AS id FROM ref_sub2_unit 
										WHERE CONCAT_WS('.', kd_urusan, kd_bidang, kd_unit, kd_sub, kd_sub2) = '$row[id_sub_unit]'"));
		$row['id_unit'] = $id_unit['id'];
		$row['id_sub_unit'] = $id_sub['id'];
		$row['id'] = $row['id_pengelola'];
		$row['password_lama'] = $row['password'];
		if($row['state']==0) $row['status'] = 'Aktif'; else $row['status'] = 'Non-Aktif';
		//$row['download'] = "<a href='#' onClick='downKey($row[id])'>Aktivasi.key</a>";
		if($row['serial_key']!='') $row['download'] = "<a href='aksi.php?module=down_key&id=$row[id]'>Aktivasi.key</a>";
		else $row['download'] = "";
		array_push($items, $row);
	}
	$result["rows"] = $items;
	echo json_encode($result);
	mysql_close();
?>