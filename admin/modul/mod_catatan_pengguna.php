 <div class="dtabel" style="width:100%;height:100%;background:white">	
 <table id="dgfull"class="easyui-datagrid"
	url="./model/catatan_pengguna.php" fit="true"
	toolbar="#toolbar" title="Catatan Pengguna"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
<th field="waktu" width="70" align="left" halign="center">WAKTU</th>
<th field="pengguna" width="70" align="left" halign="center">PENGGUNA</th>
<th field="unit" width="70" align="left" halign="center">Unit Kerja</th>
<th field="modul" width="70" align="left" halign="center">MODUL</th>
<th field="aksi" width="50" align="center" halign="center">AKSI</th>
</tr>
</thead>
</table>
</div>
<div id="toolbar">
<div style="float: left; margin-right: 5px;"></div>
<div style="float: right; margin-right: 5px;">
	<?php if($peran!=md5('3')){ ?> 
	<!--Unit : 
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
			$('#barang').combobox('clear');
			$('#gudang').combobox('clear');
			$('#barang').combobox('reload', './model/cb_barang.php?id='+rec.id);
			$('#gudang').combobox('reload', './model/cb_gudang.php?id='+rec.id);
		}
	});
	</script>-->
	<?php } ?>
	Pengguna : 
	<input class="easyui-combobox" style="width:100px;" id="pengguna" name="pengguna" />
	<script>
	$('#pengguna').combobox({
		url:'./model/cb_pengguna.php',
		valueField:'id',
		textField:'nama',
		filter: function(q, row){
			var opts = $(this).combobox('options');
			return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
		}
	});
	</script>
	Dari Tanggal :
	<input class="easyui-datebox" type="text" id="tgl_mulai" data-options="formatter:myformatter,parser:myparser" style="width:100px;">
	Sampai : 
	<input class="easyui-datebox" type="text" id="sampai" data-options="formatter:myformatter,parser:myparser" style="width:100px;">
	<a href="#" class="easyui-linkbutton" plain="true" iconCls="icon-search" onclick="doSearch()">Cari</a>
</div>
<div style="clear:both"></div>
</div>

<script type="text/javascript">
var pengguna; var id_sub;
function doSearch(){
	pengguna = $('#pengguna').combobox('getValue');
	$('#dgfull').datagrid('load',{
		mulai: $('#tgl_mulai').datebox('getValue'),
		sampai: $('#sampai').datebox('getValue'),
		pengguna: pengguna
	});
}

function CetakDaftar(){
	$.post( "./print/kartu_persediaan.php", { ta : ta, idgud: gudang, idbar : barang, id_sub : id_sub })
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
