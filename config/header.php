	<div style="margin-left: 20px;">
<a href="#" class="easyui-linkbutton" data-options="plain:'true',iconCls:'icon-home'" 
    onclick="location.href='media.php?module=home'">Home</a>
<?php 
$menu = 1; $idsub = 0; $sub2 = "";
$uidunit = isset($_SESSION['uidunit']) ? $_SESSION['uidunit'] : '';
$header = mysql_query("SELECT id_menu, id_sub, id_sub2, nama_menu, link_menu, icon_menu
						FROM ref_menu a, ref_akses_menu m
						WHERE a.uuid_menu = m.uuid_menu AND MD5( m.id_akses ) = '$peran' 
						UNION ALL 
						SELECT id_menu, id_sub, id_sub2, nama_menu, link_menu, icon_menu
						FROM ref_menu a1, ref_akses_menu2 m1
						WHERE a1.uuid_menu = m1.uuid_menu
						AND MD5( m1.uuid_skpd ) = '$_SESSION[uidunit]'
						ORDER BY id_menu, id_sub, id_sub2 ASC");
while($h = mysql_fetch_assoc($header)){
	if($menu!=$h['id_menu']){
		if($sub2=='ya'){ echo "</div></div>"; $sub2 = ""; }
		echo "</div>"; $idsub = 0;
	}else{
		if($sub2=='ya' && $idsub!=$h['id_sub']){ echo "</div></div>"; $sub2 = ""; }
	}
	
	if($h['id_sub']==0 && $h['id_sub2']==0){ 
		$menu = $h['id_menu']; 
		if($menu == 1) $widt = 160;
		elseif($menu == 2) $widt = 260;
		elseif($menu == 3) $widt = 230;
		elseif($menu == 4) $widt = 200;
		elseif($menu == 5) $widt = 200;
		elseif($menu == 6) $widt = 260;
		elseif($menu == 7) $widt = 180;
		else $widt = 190;
		?>
		<a href="#" class="easyui-menubutton" data-options="menu:'#<?php echo $h['link_menu']; ?>',iconCls:'<?php echo $h['icon_menu']; ?>'"><?php echo $h['nama_menu']; ?></a>
			<div id="<?php echo $h['link_menu']; ?>" style="width:<?php echo $widt;?>px;">
	<?php }elseif($h['id_sub2']==0){ $idsub = $h['id_sub']; $sub2 = 'ya'; ?>
		<div>
			<span><?php echo $h['nama_menu'] ?></span>
			<div>
	<?php }else{ ?>
		<div  onclick="location.href='media.php?module=<?php echo $h['link_menu']; ?>'"><?php echo $h['nama_menu']; ?></div>	
	<?php } 
}
?>
</div>
<!--<a href="#" class="easyui-linkbutton" data-options="plain:'true',iconCls:'icon-file'" 
	onclick="location.href='berkas/modul_simbaper.pdf'">Modul</a>
<a href="#" class="easyui-linkbutton" data-options="plain:'true',iconCls:'icon-file'" 
    onclick="location.href='media.php?module=forum'">Forum</a>-->
	<!--
<a href="#" class="easyui-linkbutton" data-options="plain:'true',iconCls:'icon-file'" 
    onclick="location.href='media.php?module=keluar_barang_reklas'">Barang Keluar Reklas</a>
-->
<?php
	if($peran==md5('1')){
?>
	<!--<a href="#" class="easyui-linkbutton" data-options="plain:'true',iconCls:'icon-file'" 
    onclick="location.href='media.php?module=pengumuman'">Pengumuman</a>-->
<?php	
	}
?>
</div>