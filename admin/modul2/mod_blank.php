<div style='height:100%;overflow:auto'>
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
	<div style='margin:auto;width:90%;margin-top:1%;text-align:center'>
	
		<div class="alert alert-warning " style='text-align:justify'> 
			<center><span style='font-size:20pt;'>GRUP WHATSAPP SIMBAPER</span></center> <br>
			<hr>
			<div style='font-size:14pt'>Untuk membantu mengatasi kendala dalam proses entri pada aplikasi SIMBAPER silahkan masuk grup whatsapp dengan klik link berikut melalui android atau web whatsapp anda :<br><a href="https://chat.whatsapp.com/GxuxBrg28wAGPCJhniqog4" target="_blank">https://chat.whatsapp.com/GxuxBrg28wAGPCJhniqog4</a></div>
		</div>
	
	
	<?php
		$q1 = mysql_query("SELECT * FROM pengumuman ORDER BY timestamp DESC");
		while($row = mysql_fetch_assoc($q1)){
			$tggl = tgl_indo(substr($row['timestamp'],0,10));
			if($row['file'] == ""){
				$lampiran = " ";
			} else {
				$lampiran = "<hr><a href='images/file/".$row['file']."' target='_blank'><i class='icon-download-alt'></i> Unduh Lampiran</a> ";
			}  
	?>

		<div class="alert alert-info " style='text-align:justify'> 
			<center><span style='font-size:20pt;'><?php echo $row['perihal']; ?></span></center> 
			<center><span style='font-size:12pt;'><?php echo "Pesan dari Administrator pada tanggal ".$tggl; ?></span></center><br>
			<hr>
			<div style='font-size:14pt'><?php echo $row['isi']; ?> </div>
			
			<?php echo $lampiran; ?>
		</div>
	<?php
		} 
	?>
	 
	</div>
</div>