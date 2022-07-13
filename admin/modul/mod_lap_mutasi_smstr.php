 <div class="dtabel" style="width:100%;height:100%;background:white">	
 <table id="dgfull"class="easyui-datagrid"
	url="./model/lap_mutasi_smstr.php" fit="true" showFooter="true"
	toolbar="#toolbar" title="Laporan Semesteran Mutasi Barang Persediaan"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
<th field="nama_barang" width="110" align="left" halign="center" rowspan="2">JENIS / NAMA BARANG</th>
<th field="saldo_awal" width="80" align="center" halign="center" rowspan="2">SALDO AWAL</th>
<th width="130" align="center" colspan="2">MUTASI</th>
<th width="180" align="center" colspan="3">SALDO AKHIR KOMULATIF</th>
<th field="ket" width="30" align="center" halign="center"  rowspan="2">KET</th>
</tr>
<tr>
<th field="jml_masuk" width="70" align="center">TAMBAH</th>
<th field="jml_keluar" width="70" align="center" halign="center">DIGUNAKAN</th>
<th field="saldo_akhir" width="70" align="center" halign="center">SALDO</th>
<th field="harga" width="70" align="right" halign="center">HARGA</th>
<th field="total" width="40" align="right" halign="center">TOTAL</th>
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
closed="true" buttons="#dls-buttons" title="Pencarian Data Laporan Mutasi Semester">
<div class="ftitle">Pencarian Data Laporan Mutasi Semester</div>
<form id="fms" method="post">
<table cellpadding="5">
<?php if($_SESSION['level']!=md5('c')){ ?>
<tr>
<td>Nama Sub Unit</td>
<td>: 
<input class="easyui-combobox" style="width:250px;" id="id_sub" name="id_sub"  required="true"/>
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
	<td>Semester</td>
	<td>: <input class="easyui-switchbutton" id="smstr" name="smstr" data-options="onText: '1', offText: '2'">
	</td>
</tr>
<tr>
	<td>Tahun</td>
	<td>: <input class="easyui-combobox" style="width:60px;" id="thn" name="thn" required="true"/>
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
</table>
</form>
</div>
<div id="dls-buttons">
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="doSearch()" style="width:90px">Cari</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dls').dialog('close')" style="width:90px">Batal</a>
</div>


<script type="text/javascript">
var ta; var id_sub; var smstr = 1; var id_sum;
$(function(){
	$('#smstr').switchbutton({
		checked : true,
		onChange: function(checked){
			if(checked==true) smstr = 1;
			else smstr = 2;
		}
	});
});

function doSearch(){
	if($('#fms').form('validate')==false) return;
	ta = $('#thn').combobox('getValue');
	id_sum = $('#id_sumber').combobox('getValue');
	<?php if($_SESSION['level']!=md5('c')){ ?>  id_sub = $('#id_sub').combobox('getValue'); <?php } ?>
	
	if(ta==undefined || ta=='') $.messager.alert('Peringatan','Tahun Belum dipilih !');
	<?php if($_SESSION['level']!=md5('c')){ ?>else if(id_sub==undefined || id_sub=='') $.messager.alert('Peringatan','Unit Kerja Belum dipilih !'); <?php } ?>
	else{		
		$('#dgfull').datagrid('load',{
			<?php if($_SESSION['level']!=md5('c')){ ?>  id_sub: id_sub, <?php } ?>
			ta: ta,
			smstr: smstr,
			id_sum: id_sum
		});
	}	
}

function CetakDaftar(){
	var basket = $('#dgfull').datagrid('getData');
	
	if(ta==undefined || ta=='') $.messager.alert('Peringatan','Tahun Belum dipilih !');
	<?php if($_SESSION['level']!=md5('c')){ ?>else if(id_sub==undefined || id_sub=='') $.messager.alert('Peringatan','Unit Kerja Belum dipilih !'); <?php } ?>
	else{		
		$.loader.open($dataLoader);
		$.post( "./print/lap_mutasi_smstr.php", { basket : basket.rows, smstr : smstr, ta:ta,
						<?php if($_SESSION['level']!=md5('c')){ ?>  id_sub: id_sub<?php } ?> })
		.done(function( data ) {
			if(data.success==false) alert(data.pesan);
			window.location.href = data.url;
			$.loader.close($dataLoader);
		});
	}	
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
