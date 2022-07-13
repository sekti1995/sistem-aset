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
		
		var hgrid = 100;
		var he = hcontent-hgrid;
		var rw = Math.floor(he/25);
		
		console.log(rw);
		
		$('#dg').datagrid({
            pageSize: rw,
			pageList: [rw,100,400]
			
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