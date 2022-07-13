 <div class="dtabel" style="width:100%;height:100%;background:white">	
 <table id="dgfull"class="easyui-datagrid"
	url="./model/lap_mutasi_gudang.php" fit="true"
	toolbar="#toolbar" pagination="false" title="Daftar Mutasi Gudang"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
<th field="nama_unit" width="100" align="left" halign="center">SKPD / Sub Unit</th>
<th field="ta" width="25" align="center" halign="center">TA</th>
<th field="tanggal" width="40" align="left" halign="center">Tanggal</th>
<th field="nomor" width="40" align="left" halign="center">Nomor</th>
<th field="barang" width="70" align="left" halign="center">Nama Barang</th>
<th field="dari_gud" width="50" align="left" halign="center">Dari Tempat</th>
<th field="ke_gud" width="50" align="left" halign="center">Ke Tempat</th>
<th field="jumlah" width="50" align="center" halign="center">Jumlah Barang</th>
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
closed="true" buttons="#dls-buttons" title="Pencarian Daftar Mutasi Gudang">
<div class="ftitle">Pencarian Mutasi Gudang</div>
<form id="fms" method="post">
<table cellpadding="5">
<?php if($_SESSION['level']!=md5('c')){ ?>
<tr>
<td>Nama Sub Unit</td>
<td>: 
<input class="easyui-combobox" style="width:250px;" id="id_unit_search" name="id_unit_search"/>
<script>
$('#id_unit_search').combobox({
    url:'./model/cb_sub2_unit.php',
    valueField:'id',
    textField:'text',
    filter: function(q, row){
		var opts = $(this).combobox('options');
		return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
	}
});
</script></td>
</tr>
<?php } ?>
<tr>
	<td>Dari Tangal</td>
	<td>: <input class="easyui-datebox" style="width:100px;" id="dari_search" name="dari_search"  data-options="formatter:myformatter,parser:myparser"/>
	</td>
</tr>
<tr>
	<td>Sampai</td>
	<td>: <input class="easyui-datebox" style="width:100px;" id="sampai_search" name="sampai_search"  data-options="formatter:myformatter,parser:myparser"/>
	</td>
</tr>
<tr>
	<td>Nama Barang</td>
	<td>: <input class="easyui-combobox" style="width:250px;" id="barang_search" name="barang_search"/>
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
	</script></td>
</tr>
<tr>
	<td>Dari Gudang</td>
	<td>: <input class="easyui-combobox" style="width:150px;" id="dari_gud_search" name="dari_gud_search"/>
	</td>
</tr>
<tr>
	<td>Ke Gudang</td>
	<td>: <input class="easyui-combobox" style="width:150px;" id="ke_gud_search" name="ke_gud_search"/>
	<script>
	$('#dari_gud_search, #ke_gud_search').combobox({
		url:'./model/cb_gudang.php',
		valueField:'id_gud',
		textField:'nama_gud',
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
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#fms').form('clear')" style="width:90px">Bersihkan</a>
</div>


<script type="text/javascript">
var dari_tgl; var sampai_tgl; var id_sub; var barang; var dari_gud; var ke_gud;
function doSearch(){
	dari_tgl = $('#dari_search').datebox('getValue');
	sampai_tgl = $('#sampai_search').datebox('getValue');
	barang = $('#barang_search').combobox('getValue');
	dari_gud = $('#dari_gud_search').combobox('getValue');
	ke_gud = $('#ke_gud_search').combobox('getValue');
	<?php if($_SESSION['level']!=md5('c')){ ?> id_sub = $('#id_unit_search').combobox('getValue'); <?php } ?>
	$('#dgfull').datagrid('load',{
		<?php if($_SESSION['level']!=md5('c')){ ?> id_sub: id_sub, <?php } ?>
		dari_tgl: dari_tgl,
		sampai_tgl: sampai_tgl,
		barang: barang,
		dari_gud: dari_gud,
		ke_gud: ke_gud
	});
	$('#dls').dialog('close');
}

/* function CetakDaftar(){
	var rw1 = $('#dgfull').datagrid('getSelected');
	if (rw1){
		$.post( "./print/daftar_hitung_fisik.php", { id : rw1.id, id_sub : rw1.id_sub, tanggal : rw1.tanggal })
		.done(function( data ) {
			//console.log(data);
			window.location.href = data.url;
		});
	}else $.messager.alert('Peringatan','Pilih Data Stok Opname yang akan dicetak !');
} */	

function CetakDaftar(){
	$.post( "./print/lap_mutasi_gudang.php", { 
		barang: barang, 
		dari_tgl : dari_tgl, 
		sampai_tgl: sampai_tgl,
		dari_gud: dari_gud,
		ke_gud: ke_gud, 
		<?php if($_SESSION['level']!=md5('c')){ ?> id_sub: id_sub <?php } ?> })
	.done(function( data ) {
		//console.log(data);
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
