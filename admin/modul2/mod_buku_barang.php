 <div class="dtabel" style="width:100%;height:100%;background:white">	
 <table id="dgfull"class="easyui-datagrid"
	url="./model/lap_buku_barang.php" fit="true"
	toolbar="#toolbar" title="Buku Barang Persediaan"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
<th width="100" align="center" colspan="8">PENERIMAAN</th>
<th width="100" align="center" colspan="5">PENGELUARAN</th>
</tr>
<tr>
<th field="tgl_terima" width="60" align="left" halign="center" rowspan="2">TGL DITERIMA</th>
<th field="nama_barang" width="75" align="left" halign="center" rowspan="2">NAMA BARANG</th>
<th field="merk" width="70" align="left" halign="center" rowspan="2">MERK/UKURAN</th>
<th field="tahun" width="50" align="center" halign="center" rowspan="2">TAHUN</th>
<th field="jml_terima" width="50" align="center" halign="center" rowspan="2">JUMLAH</th>
<th field="hrg_terima" width="60" align="right" halign="center" rowspan="2">HARGA</th>
<th width="100" align="center" colspan="2">BARITA ACARA</th>
<th field="tgl_keluar" width="80" align="center" halign="center" rowspan="2">TGL DIKELUARKAN</th>
<th field="kepada" width="80" align="left" halign="center" rowspan="2">KEPADA</th>
<th field="jml_keluar" width="50" align="center" halign="center" rowspan="2">JUMLAH</th>
<th field="tglno_surat" width="100" align="left" halign="center" rowspan="2">TGL/NO SURAT</th>
<th field="ket" width="70" align="left" halign="center" rowspan="2">KETERANGAN</th>
</tr>
<tr>
<th field="tgl_periksa" width="55" align="left" halign="center">TANGGAL</th>
<th field="no_periksa" width="70" align="left" halign="center">NOMOR</th>
</tr>
</thead>
</table>
</div>
<div id="toolbar">
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="CetakDaftar()">Cetak Excel</a>
<div style="float: right; margin-right: 5px;">
	<a href="#" class="easyui-linkbutton" plain="true" iconCls="icon-search" onclick="javascript:$('#dls').dialog('open')">Pencarian</a>
</div>
</div>


<div id="dls" class="easyui-dialog" style="width:450px;height:300px;padding:10px 20px"
closed="true" buttons="#dls-buttons" title="Pencarian Data Buku Barang">
<div class="ftitle">Pencarian Buku Barang</div>
<form id="fms" method="post">
	<?php if($_SESSION['level']!=md5('c')){ ?> 
	<div class="fitem">
		<label>Unit</label>: 
		<input class="easyui-combobox" style="width:280px;" id="id_sub" name="id_sub"/>
		<script>
		$('#id_sub').combobox({
			url:'./model/cb_sub2_unit.php',
			valueField:'id',
			textField:'text',
			filter: function(q, row){
				var opts = $(this).combobox('options');
				return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
			},
			onSelect: function(rec){
				$('#id_sumber').combobox('clear');
				$('#id_sumber').combobox('reload', './model/cb_sumber_dana.php?cek&id='+rec.id);
			}
		});
		</script>
	</div>	
	<?php } ?>
	<div class="fitem">
		<label>Sumber</label>: 
		<input class="easyui-combobox" style="width:180px;" id="id_sumber" name="id_sumber"/>
		<script>
		$('#id_sumber').combobox({
			url:'./model/cb_sumber_dana.php?cek',
			valueField:'id',
			textField:'text',
			filter: function(q, row){
				var opts = $(this).combobox('options');
				return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
			}
		});
		</script>
	</div>
	<div class="fitem">
		<label>Periode Bulan</label>: 	
		<input class="easyui-combobox" style="width:90px;" id="bln" name="bln" />
		<script>
		$('#bln').combobox({
			url:'./model/cb_bulan.php',
			valueField:'id',
			textField:'text',
			filter: function(q, row){
				var opts = $(this).combobox('options');
				return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
			}
		});
		</script>
	</div>
	<div class="fitem">
		<label>Tahun Anggaran</label>: 	
		<input class="easyui-combobox" style="width:70px;" id="ta" name="ta" />
		<script>
		$('#ta').combobox({
			url:'./model/cb_ta.php',
			valueField:'id',
			textField:'text',
			filter: function(q, row){
				var opts = $(this).combobox('options');
				return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
			}
		});
		</script>
	</div>	
</form>	
</div>
<div id="dls-buttons">
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="doSearch()" style="width:90px">Cari</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dls').dialog('close')" style="width:90px">Batal</a>
</div>


<script type="text/javascript">
var thn; var id_sub; var bln; var id_sumber;
function doSearch(){
	thn = $('#ta').combobox('getValue');
	bln = $('#bln').combobox('getValue');
	id_sumber = $('#id_sumber').combobox('getValue');
	<?php if($_SESSION['level']!=md5('c')){ ?> id_sub = $('#id_sub').combobox('getValue'); <?php } ?>
	$('#dgfull').datagrid('load',{
		<?php if($_SESSION['level']!=md5('c')){ ?> id_sub: id_sub, <?php } ?>
		id_sumber: id_sumber,
		bln: bln,
		thn: thn
	});
}

function CetakDaftar(){
	var basket = $('#dgfull').datagrid('getData');
	$.loader.open($dataLoader);
	$.post( "./print/buku_barang.php", { basket : basket.rows, bulan : bln, 
			<?php if($_SESSION['level']!=md5('c')){ ?> id_sub: id_sub, <?php } ?> })
	.done(function( data ) {
		if(data.success==false) alert(data.pesan);
		window.location.href = data.url;
		$.loader.close($dataLoader);
	});
}	
	
function myformatter(date){
	var y = date.getFullYear();
	var m = date.getMonth()+1;
	var d = date.getDate();
	return (d<10?('0'+d):d)+'-'+(m<10?('0'+m):m)+'-'+y;
}
function myparser(s){
	if (!s) return new Date();
	var ss = (s.split('-'));
	var y = parseInt(ss[0],10);
	var m = parseInt(ss[1],10);
	var d = parseInt(ss[2],10);
	if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
		return new Date(d,m-1,y);
	} else {
		return new Date();
	}
}
</script>
<style type="text/css">
	#fm{
	margin:0;
	padding:5px 10px;
	}
	.ftitle{
	font-size:14px;
	font-weight:bold;
	padding:5px 0;
	margin-bottom:10px;
	border-bottom:1px solid #ccc;
	}
	.fitem{
	margin-bottom:5px;
	}
	.fitem label{
	display:inline-block;
	width:80px;
	}
	.fitem input{
	width:160px;
	}
</style>	
