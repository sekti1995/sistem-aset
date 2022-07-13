<?php
date_default_timezone_set('Asia/Jakarta');
ob_start();
require_once "../config/library.php";
require_once "../config/db.koneksi.php";
require_once "../config/db.function.php";
error_reporting(E_ALL); ini_set('display_errors', 'On'); 
session_start();
$peran = cekLogin();
?>
<!DOCTYPE html
   PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>::: Sim-BaPer :::</title>
<link rel="shortcut icon" href="" />
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../css/jquery.timepicker.css" />
<link rel="stylesheet" type="text/css" href="../js/jsloader/style.css" />
<link rel="stylesheet" type="text/css" href="../js/jeui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="../js/jeui/themes/icon.css">
<script type="text/javascript" src="../js/jeui/jquery.min.js"></script>
<script type="text/javascript" src="../js/jeui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../js/jeui/datagrid-cellediting.js"></script>
<script type="text/javascript" src="../js/jeui/src/datagrid-detailview.js"></script>
<script src="../js/jquery.session.js" type="text/javascript"></script>
<script src="../js/jquery.timePicker.js" type="text/javascript"></script>
<script src="../js/autoNumeric.js" type="text/javascript"></script>
<script src="../js/jquery.md5.min.js" type="text/javascript"></script>
<script src="../js/tinymce2/tinymce.min.js" type="text/javascript"></script>
<script src="../js/jsloader/jquery-loader.js" type="text/javascript"></script>
<script src="../js/js.function.js" type="text/javascript"></script>
<script src="../js/accounting.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../js/validator.js"></script>
<script type="text/javascript">
	function oc(a)
	{
	  var o = {};
	  for(var i=0;i<a.length;i++)
	  {
		o[a[i]]='';
	  }
	  return o;
	}
	function load(){
		$('#load_screen').hide(); 
	}
	$(function() {
   // Hilangkan scrollbar dengan JQuery.
   // Jika JQuery gagal terpanggil, halaman masih bisa digeser dengan scrollbar seperti biasa
    $('html, body').css('overflow', 'hidden');

    function updateSize() {
        var winWidth  = $(window).width(),           // Ambil data lebar layar
            winHeight = $(window).height(),          // Ambil data tinggi layar
            wrapSum   = $('.box').siblings().length; // Hitung semua elemen .box (hasilnya: wrapSum=6)
		
		var hcontent = winHeight-60;
        // Set ukuran .box agar sama dengan ukuran layar
        $('#content').css({
            width:winWidth*95/100,
            height:hcontent
        });
		$('#hpanel').css({
            height:hcontent-49
        });
		
		$('#dld').css({
            left:winWidth/2
        });
		
		var hgrid = 125;
		var he = hcontent-hgrid;
		var rw = Math.floor(he/25);
		
		//console.log(rw);
		
		$('#dgfull').datagrid({
            pageSize: rw,
			pageList: [rw,100,400,800,1000,2000]
			
        });
        // Set lebar #wrap sebesar tiga kali lebar .box (tiga kali lebar layar) dan tinggi sebesar dua kali tinggi .box (dua kali tinggi layar)
        // Saya membaginya jumlahnya (wrapSum) menjadi dua dan tiga,
        // karena Saya ingin hanya ada tiga .box dalam satu baris dan dua .box dalam satu kolom
        /* $('#wrap').css({
            width:winWidth*(wrapSum/2),
            height:winHeight*(wrapSum/3)
        }); */
    }
    // Jalankan fungsi secara default
    updateSize();
	$dataLoader = { imgUrl: 'images/ajaxloader.gif' };
    $(window).resize(function() {
        // Saat ukuran layar diubah, jalankan fungsi kembali
        // untuk memastikan bahwa ukuran elemen akan terus ter-update/diperbaharui
        updateSize();
    });

    // Menambahkan class 'active' pada menu yang diklik
    $('ul#nav a').click(function() {
       $('ul#nav a.active').removeClass('active');
       $(this).addClass('active');
    });

});

</script>	
<style>
	.borderless{
	border: none;
	background: transparent;
}
</style>
</head>
<body onLoad='load()'>
<div class='load_screen' id='load_screen'><img src='../js/jeui/themes/default/images/loading.gif' /></div>
<div id="container" style="min-height: 650px;">

			<div id="menuAtas">
			<?php include_once "../config/header.php"; ?>
			</div>
			<div id="content">
			<?php include_once "content.php"; ?>
		  	</div>
</div>
</body>
</html>

