<?php
	require_once "../../config/db.koneksi.php";
	
	$id = isset($_GET['id']) ? $_GET['id'] : '';
	
	$items = array();
	$h = mysql_fetch_assoc(mysql_query("SELECT uuid_menu AS id, nama_menu AS text FROM ref_menu WHERE id_menu = '$id'"));
	$rw['id'] = $h['id'];
	$rw['text'] = "<b>".$h['text']."</b>";
	$child = array(); $id_sub = 0;
	$sub = mysql_query("SELECT uuid_menu AS id, nama_menu AS text, id_sub2, id_sub FROM ref_menu WHERE id_menu = '$id' 
						AND id_sub <> 0 ORDER BY id_sub, id_sub2");
	while($s = mysql_fetch_assoc($sub)){
		$row = array();
		if($s['id_sub2']!=0){
			if($id_sub!=$s['id_sub']){
				$row['id'] = $s['id'];
				$row['text'] = $s['text'];
				array_push($child, $row);
			}
		}else{ 
			$row['id'] = $s['id'];
			$row['text'] = $s['text'];
			$sub2 = array();
			$ssub = mysql_query("SELECT uuid_menu AS id, nama_menu AS text 
					FROM ref_menu WHERE id_menu = '$id' 
					AND id_sub = '$s[id_sub]' AND id_sub2 <> 0 
					ORDER BY id_menu, id_sub, id_sub2");
			while($b = mysql_fetch_assoc($ssub)){
				$row1['id'] = $b['id'];
				$row1['text'] = $b['text'];
				array_push($sub2, $row1);
			}
			$row['children'] = $sub2;
			array_push($child, $row);
		}
		$id_sub = $s['id_sub'];
		
	}
	$rw['children'] = $child;
	array_push($items, $rw);

	echo json_encode($items);
	mysql_close();
	
?>