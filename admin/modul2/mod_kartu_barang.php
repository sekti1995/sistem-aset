 <div class="dtabel" style="width:100%;height:100%;background:white">	
 <table id="dgfull"class="easyui-datagrid"
	url="./model/lap_kartu_barang.php" fit="true"
	toolbar="#toolbar" title="Kartu Barang Persediaan"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
<th field="tanggal" width="50" align="left" halign="center">TANGGAL</th>
<th field="masuk" width="80" align="center" halign="center">MASUK</th>
<th field="harga_masuk" width="80" align="center" halign="center">HARGA</th>
<th field="keluar" width="80" align="center" halign="center">KELUAR</th>
<th field="harga_keluar" width="80" align="center" halign="center">HARGA</th>
<th field="sisa" width="80" align="center" halign="center">SISA</th>
<th field="keterangan" width="80" align="left" halign="center">KETERANGAN</th>
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


<div id="dls" class="easyui-dialog" style="width:450px;height:340px;padding:10px 20px"
closed="true" buttons="#dls-buttons" title="Pencarian Data Kartu Barang">
<div class="ftitle">Pencarian Kartu Barang</div>
<form id="fms" method="post">
<table cellpadding="5">
<?php if($_SESSION['level']!=md5('c')){ ?>
<tr>
<td>Nama Sub Unit</td>
<td>: 
<input class="easyui-combobox" style="width:250px;" id="id_sub" name="id_sub" required="true"/>
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
		$('#barang_search').combobox('clear');
		$('#barang_search').combobox('reload', './model/cb_barang_ada.php?cek&id='+rec.id);
		$('#id_sumber').combobox('clear');
		$('#id_sumber').combobox('reload', './model/cb_sumber_dana.php?cek&id='+rec.id);
	}
});
</script></td>
</tr>
<?php } ?>
<tr>
<td>Sumber Dana</td>
<td>: 
<input class="easyui-combobox" style="width:200px;" id="id_sumber" name="id_sumber"/>
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
</script></td>
</tr>
<tr>
	<td>TA</td>
	<td>: <input class="easyui-combobox" style="width:60px;" id="thn" name="thn" />
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
	</script></td>
</tr>
<tr>
	<td>Nama Barang</td>
	<td>: <input class="easyui-combobox" style="width:230px;" id="barang_search" name="barang_search" required="true"/>
	<script>
	$('#barang_search').combobox({
		url:'./model/cb_barang_ada.php',
		valueField:'id_bar',
		textField:'nama_bar',
		filter: function(q, row){
			var opts = $(this).combobox('options');
			return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
		}
	});
	</script></td>
</tr>	
<tr>
	<td>Periode Bulan</td>
	<td>: <input class="easyui-combobox" style="width:90px;" id="bln" name="bln" />
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
	</script></td>
</tr>	
</table>
</form>
</div>
<div id="dls-buttons">
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="doSearch()" style="width:90px">Cari</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dls').dialog('close')" style="width:90px">Batal</a>
</div>

<script type="text/javascript">
var thn; var id_sub; var bln; var id_bar; var id_sumber;
function doSearch(){
	thn = $('#thn').combobox('getValue');
	bln = $('#bln').combobox('getValue');
	id_bar = $('#barang_search').combobox('getValue');
	id_sumber = $('#id_sumber').combobox('getValue');
	<?php if($_SESSION['level']!=md5('c')){ ?> id_sub = $('#id_sub').combobox('getValue'); <?php } ?>
	$('#dgfull').datagrid('load',{
		<?php if($_SESSION['level']!=md5('c')){ ?> id_sub: id_sub, <?php } ?>
		id_bar: id_bar,
		id_sumber: id_sumber,
		thn: thn,
		bln: bln
	});
}

function CetakDaftar(){
	$.loader.open($dataLoader);
	$.post( "./print/kartu_barang.php", { 
		id_bar: id_bar, thn : thn, bln: bln, id_sumber : id_sumber, <?php if($_SESSION['level']!=md5('c')){ ?> id_sub: id_sub <?php } ?> })
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
