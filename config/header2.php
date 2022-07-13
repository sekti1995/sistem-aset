<?php
$peran = cekLogin();
?>
<div style="margin-left: 20px;">
	<a href="#" class="easyui-menubutton" data-options="menu:'#file',iconCls:'icon-file'">File</a>
		<div id="file" style="width:160px;">
			<div  onclick="location.href='media.php?module=ganti_password'">Ganti Password</div>
			<?php if($peran==md5('1')){ ?>
			<div  onclick="location.href='media.php?module=user'">Pengguna Aplikasi</div>
			<div  onclick="location.href='media.php?module=key_aplikasi'">Buat Key Aplikasi</div>
			<?php } ?>
			<div  onclick="location.href='media.php?module=pejabat'">Data Pejabat</div>
			<?php if($peran==md5('1')){ ?>
			<div  onclick="location.href='media.php?module=golongan_pejabat'">Golongan Pejabat</div>
			<div  onclick="location.href='media.php?module=jabatan_pejabat'">Jabatan Pejabat</div>
			<div  onclick="location.href='media.php?module=kelompok_barang'">Kelompok Barang</div>
			<div  onclick="location.href='media.php?module=satuan_barang'">Satuan Barang</div>
			<div  onclick="location.href='media.php?module=jenis_barang'">Jenis Persediaan</div>
			<?php } ?>
			<div  onclick="location.href='media.php?module=barang'">Data Barang</div>
			<?php if($peran==md5('1')){ ?>
			<div  onclick="location.href='media.php?module=bidang'">Bidang</div>
			<div  onclick="location.href='media.php?module=unit'">Unit</div>
			<div  onclick="location.href='media.php?module=sub_unit'">Sub Unit</div>
			<div  onclick="location.href='media.php?module=sub2_unit'">Sub2 Unit</div>
			<?php } ?>
			<div  onclick="location.href='media.php?module=gudang'">Tempat Penyimpanan</div>
			<?php if($peran==md5('1')){ ?>
			<div  onclick="location.href='media.php?module=tahun_anggaran'">Tahun Anggaran</div>
			<div  onclick="location.href='media.php?module=setting_aplikasi'">Setting Aplikasi</div>
			<?php } ?>
			<div  onclick="location.href='../logout.php'" data-options="iconCls:'icon-logout'">Keluar</div>
		</div>
<?php //if($peran==md5('1')){ ?>	
	<a href="#" class="easyui-menubutton" data-options="menu:'#pengadaan',iconCls:'icon-beli'">Pengadaan</a>
		<div id="pengadaan" style="width:190px;">
			<div  onclick="location.href='media.php?module=pengadaan'" >Pengadaan Barang</div>
			<div  onclick="location.href='media.php?module=pemeriksaan'">Pemeriksaan Barang</div>
			<div  onclick="location.href='media.php?module=penerimaan'">Penerimaan Barang</div>
			<div  onclick="location.href='media.php?module=daftar_pengadaan'">Daftar Pengadaan Barang</div>
			<div  onclick="location.href='media.php?module=buku_penerimaan'">Buku Penerimaan Barang</div>
		</div>
<?php //} ?>		
	<a href="#" class="easyui-menubutton" data-options="menu:'#tatausaha',iconCls:'icon-akutansi'">Penatausahaan</a>
		<div id="tatausaha" style="width:200px;">
			<div  onclick="location.href='media.php?module=perintah_keluar'">Perintah Pengeluaran Barang</div>
			<div  onclick="location.href='media.php?module=keluar_barang'">Pengeluaran Barang</div>
			<div  onclick="location.href='media.php?module=buku_keluar'">Buku Pengeluaran Barang</div>
			<div  onclick="location.href='media.php?module=mutasi_gudang'">Mutasi Tempat Barang</div>
			<div  onclick="location.href='media.php?module=lap_mutasi_gudang'">Daftar Lap Mutasi Tempat</div>
			<div  onclick="location.href='media.php?module=kartu_barang'">Kartu Barang</div>
			<div  onclick="location.href='media.php?module=buku_barang'">Buku Barang</div>

		</div>
	<a href="#" class="easyui-menubutton" data-options="menu:'#opname',iconCls:'icon-gudang'">Stok Opname</a>
		<div id="opname" style="width:210px;">
			<div  onclick="location.href='media.php?module=stok_opname'">Stok Opname</div>
			<!--<div  onclick="location.href='media.php?module=tindak_lanjut_so'">Tindak Lanjut</div>-->
			<div  onclick="location.href='media.php?module=daftar_hitung_fisik'">Cetak Daftar Perhitungan Fisik</div>
		</div>
	<a href="#" class="easyui-menubutton" data-options="menu:'#penghapusan',iconCls:'icon-gudang'">Penghapusan</a>
		<div id="penghapusan" style="width:200px;">
			<div  onclick="location.href='media.php?module=usul_hapus'">Usulan Penghapusan Barang</div>
			<div  onclick="location.href='media.php?module=hapus_barang'">Penghapusan Barang</div>
		</div>
	<a href="#" class="easyui-menubutton" data-options="menu:'#laporan',iconCls:'icon-report'">Laporan</a>
		<div id="laporan" style="width:230px;">
			<div  onclick="location.href='media.php?module=kartu_persediaan'">Kartu Persediaan Barang</div>
			<div  onclick="location.href='media.php?module=lap_mutasi_bulan'">laporan Mutasi Barang Per Bulan</div>
			<div  onclick="location.href='media.php?module=lap_mutasi_smstr'">laporan Mutasi Barang Per Semester</div>
			<div  onclick="location.href='media.php?module=lap_mutasi_tahun'">laporan Mutasi Barang Per Tahun</div>
			<!--<div  onclick="location.href='media.php?module=posting_jurnal'">Posting Jurnal Persediaan</div>
			<div  onclick="location.href='media.php?module=nilai_persediaan'">Penilaian Persediaan</div>
			<div  onclick="location.href='media.php?module=laporan_nilai_persediaan'">Laporan Penilaian Persediaan</div>-->
		</div>
	<?php if($peran==md5('1')){ ?>	
	<a href="#" class="easyui-menubutton" data-options="menu:'#utility',iconCls:'icon-setting'">Utility</a>
		<div id="utility" style="width:170px;">
			<div  onclick="location.href='media.php?module=monitoring_pengguna'">Monitoring Pengguna</div>
			<div  onclick="location.href='media.php?module=catatan_pengguna'">Catatan Pengguna</div>
		</div>
	<?php } ?>	
</div>