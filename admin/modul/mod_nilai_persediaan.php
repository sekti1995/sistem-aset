 <div class="dtabel" style="width:100%;height:100%;background:white">	
 <table id="dgfull"class="easyui-datagrid"
	url="./model/lap_nilai_persediaan.php" fit="true"
	toolbar="#toolbar" title="Penilaian Beban Persediaan"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
<th field="tanggal" width="50" align="left" halign="center">TANGGAL</th>
<th field="masuk" width="80" align="center" halign="center">PENGADAAN</th>
<th field="keluar" width="80" align="center" halign="center">PENGELUARAN</th>
<th field="sisa" width="80" align="center" halign="center">JUMLAH</th>
</tr>
</thead>
</table>
</div>
<div id="toolbar">
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="CetakDaftar()">Cetak Excel</a>
<div style="float: right; margin-right: 5px;">
	<?php if($peran!=md5('3')){ ?> 
	Unit : 
	<input class="easyui-combobox" style="width:300px;" id="id_sub" name="id_sub"/>
	<script>
	$('#id_sub').combobox({
		url:'./model/cb_sub_unit.php',
		valueField:'id',
		textField:'text',
		filter: function(q, row){
			var opts = $(this).combobox('options');
			return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
		},
		onSelect: function(rec){
			$('#barang_search').combobox('reload', './model/cb_barang.php?id='+rec.id);
		}
	});
	</script>
	<?php } ?>
	Nama Barang : 
	<input class="easyui-combobox" style="width:130px;" id="barang_search" name="barang_search" />
	<script>
	$('#barang_search').combobox({
		url:'./model/cb_barang.php',
		valueField:'id_bar',
		textField:'nama_bar',
		filter: function(q, row){
			var opts = $(this).combobox('options');
			return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
		}
	});
	</script>
	Periode Bulan : 
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
	Tahun : 
	<input class="easyui-combobox" style="width:60px;" id="thn" name="thn" />
	<script>
	$('#thn').combobox({
		url:'./model/cb_tahun.php',
		valueField:'id',
		textField:'text',
		filter: function(q, row){
			var opts = $(this).combobox('options');
			return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
		}
	});
	</script>
	<a href="#" class="easyui-linkbutton" plain="true" iconCls="icon-search" onclick="doSearch()">Cari</a>
</div>
</div>

<script type="text/javascript">
var thn; var id_sub; var bln; var id_bar;
function doSearch(){
	thn = $('#thn').combobox('getValue');
	bln = $('#bln').combobox('getValue');
	id_bar = $('#barang_search').combobox('getValue');
	<?php if($peran!=md5('3')){ ?> id_sub = $('#id_sub').combobox('getValue'); <?php } ?>
	$('#dgfull').datagrid('load',{
		<?php if($peran!=md5('3')){ ?> id_sub: id_sub, <?php } ?>
		id_bar: id_bar,
		thn: thn,
		bln: bln
	});
}

function CetakDaftar(){
	$.post( "./print/kartu_barang.php", { id_bar: id_bar, thn : thn, bln: bln, <?php if($peran!=md5('3')){ ?> id_sub: id_sub <?php } ?> })
	.done(function( data ) {
		window.location.href = data.url;
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
