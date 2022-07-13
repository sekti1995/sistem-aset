 <div class="dtabel" style="width:100%;height:100%;background:white">	
 <table id="dgfull"class="easyui-datagrid"
	url="./model/lap_daftar_hitung_fisik.php" fit="true"
	toolbar="#toolbar" pagination="true" title="Daftar Hasil Penghitungan Fisik / Stok Opname"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
<th field="nama_unit" width="100" align="left" halign="center">SKPD / Sub Unit</th>
<th field="ta" width="50" align="center" halign="center">TA</th>
<th field="nomor" width="50" align="left" halign="center">Nomor</th>
<th field="tanggal" width="50" align="left" halign="center">Tanggal</th>
</tr>
</thead>
</table>
</div>
<div id="toolbar">
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="CetakBA()">Cetak BA Stock Opname</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="CetakDaftar('all')">Cetak Excel</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="CetakDaftar('gudang')">Cetak Excel Per Gudang</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="CetakDaftar('sumber')">Cetak Excel Per Sumber Dana</a>
<div style="float: right; margin-right: 5px;">
	<a href="#" class="easyui-linkbutton" plain="true" iconCls="icon-search" onclick="javascript:$('#dls').dialog('open')">Pencarian</a>
</div>
</div>

<div id="dls" class="easyui-dialog" style="width:450px;height:340px;padding:10px 20px"
closed="true" buttons="#dls-buttons" title="Pencarian Data Daftar Hitung Fisik">
<div class="ftitle">Pencarian Data Daftar Hitung Fisik</div>
<form id="fms" method="post">
<table cellpadding="5">
<?php if($_SESSION['level']!=md5('c')){ ?>
<tr>
<td>Nama Sub Unit</td>
<td>: 
<input class="easyui-combobox" style="width:250px;" id="id_sub" name="id_sub"/>
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
	<td>Nomor</td>
	<td>: <input class="easyui-textbox" style="width:100px;" id="nomor" name="nomor" />
	</td>
</tr>
<tr>
	<td>Tanggal</td>
	<td>: <input class="easyui-datebox" style="width:100px;" id="tanggal" name="tanggal"  data-options="formatter:myformatter,parser:myparser"/>
	</td>
</tr>	
</table>
</form>
</div>
<div id="dls-buttons">
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="doSearch()" style="width:90px">Cari</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dls').dialog('close')" style="width:90px">Batal</a>
</div>



<script type="text/javascript">
var tanggal; var id_sub; var nomor;
function doSearch(){
	tanggal = $('#tanggal').datebox('getValue');
	nomor = $('#nomor').textbox('getValue');
	<?php if($_SESSION['level']!=md5('c')){ ?>  id_sub = $('#id_sub').combobox('getValue'); <?php } ?>
	$('#dgfull').datagrid('load',{
		<?php if($peran!=md5('3')){ ?> id_sub: id_sub, <?php } ?>
		tanggal: tanggal,
		nomor: nomor
	});
}

function CetakDaftar(jenis){
	var rw1 = $('#dgfull').datagrid('getSelected');
	if (rw1){
		$.loader.open($dataLoader);
		$.post( "./print/daftar_hitung_fisik.php", { id : rw1.id, id_sub : rw1.id_sub, tanggal : rw1.tanggal, jenis : jenis })
		.done(function( data ) {
		//	console.log(data);
			window.location.href = data.url;
			$.loader.close($dataLoader);
		});
	}else $.messager.alert('Peringatan','Pilih Data Stok Opname yang akan dicetak !');
}	
	
function CetakBA(){
	var rw1 = $('#dgfull').datagrid('getSelected');
	console.log( "id "+rw1.id+" idsub "+rw1.id_sub+" tanggal "+rw1.tanggal)
	if (rw1){
		$.loader.open($dataLoader);
		$.post( "./print/ba_so.php", { id : rw1.id, id_sub : rw1.id_sub, tanggal : rw1.tanggal })
		.done(function( data ) {
		 console.log(data);
			window.location.href = data.url;
			$.loader.close($dataLoader);
		});
	}else $.messager.alert('Peringatan','Pilih Data Stok Opname yang akan dicetak !');
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
