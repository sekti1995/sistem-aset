<?php
	require_once "../../config/library.php";
	$smstr = isset($_GET['smstr']) ? $_GET['smstr'] : '';
	
	if($smstr==""){ $a = 1; $b = 12; }
	elseif($smstr==1){ $a = 1; $b = 6; }
	elseif($smstr==2){ $a = 7; $b = 12; }
	$items = array();
	for($i=$a; $i<=$b; $i++){
		$row['id'] = $i;
		$row['text'] = getBulan($i);
		array_push($items, $row);
	}
	
	echo json_encode($items);
	
?>