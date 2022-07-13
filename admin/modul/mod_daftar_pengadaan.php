 <div class="dtabel" style="width:100%;height:100%;background:white">	
 <table id="dgfull"class="easyui-datagrid"
	url="./model/lap_daftar_pengadaan.php" fit="true" showFooter="true"
	toolbar="#toolbar" title="Daftar Pengadaan Barang Persediaan"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
<th field="nama_barang" width="110" align="left" halign="center" rowspan="2">JENIS / NAMA BARANG</th>
<th width="100" align="center" colspan="2">SPK / PERJANJIAN KONTRAK</th>
<th width="130" align="center" colspan="2">DPA / SPM / KUITANSI</th>
<th width="200" align="center" colspan="3">JUMLAH BARANG</th>
<th field="unit" width="150" align="left" halign="center"  rowspan="2">UNIT</th>
<th field="ket" width="30" align="center" halign="center"  rowspan="2">KET</th>
</tr>
<tr>
<th field="tgl_kontrak" width="70" align="center">TANGGAL</th>
<th field="no_kontrak" width="70" align="left" halign="center">NOMOR</th>
<th field="tgl_dpa" width="70" align="center" halign="center">TANGGAL</th>
<th field="no_dpa" width="70" align="left" halign="center">NOMOR</th>
<th field="jml_barang" width="40" align="center" halign="center">JUMLAH</th>
<th field="hrg_barang" width="80" align="right" halign="center">HARGA (Rp.)</th>
<th field="tot_harga" width="80" align="right" halign="center">TOTAL HARGA (Rp.)</th>
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

<div id="dls" class="easyui-dialog" style="width:550px;height:340px;padding:10px 20px"
closed="true" buttons="#dls-buttons" title="Pencarian Data">
<div class="ftitle">Pencarian Daftar Pengadaan</div>
<form id="fms" method="post">
	<?php if($_SESSION['level']!=md5('c')){ ?> 
	<div class="fitem">
		<label>Unit </label>: 
		<input class="easyui-combobox" style="width:300px;" id="id_sub" name="id_sub"/>
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
		<label>Sumber </label>: 
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
		<label>Tahun Anggaran </label>: 
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
	<div class="fitem">
		<label>Tanggal Bayar Awal</label>: 
		<input class="easyui-datebox" style="width:100px;" id="tgl_awal" name="tgl_awal" data-options="formatter:myformatter,parser:myparser" />
	</div>
	<div class="fitem">
		<label>Tanggal Bayar Akhir</label>: 
		<input class="easyui-datebox" style="width:100px;" id="tgl_akhir" name="tgl_akhir" data-options="formatter:myformatter,parser:myparser" />
	</div>	
</form>
</div>
<div id="dls-buttons">
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="doSearch()" style="width:90px">Cari</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#fms').form('clear')" style="width:90px">Bersihkan</a>
</div>

<script type="text/javascript">
var ta; var id_sub; var id_sumber; var sumber;
function doSearch(){

	ta = $('#ta').combobox('getValue');
	id_sumber = $('#id_sumber').combobox('getValue');
	sumber = $('#id_sumber').combobox('getText');
	var tgl_awal = $('#tgl_awal').datebox('getValue');
	console.log(id_sumber)
	var tgl_akhir = $('#tgl_akhir').datebox('getValue');
	<?php if($_SESSION['level']!=md5('c')){ ?> id_sub = $('#id_sub').combobox('getValue'); <?php } ?>
	$('#dgfull').datagrid('load',{
		<?php if($_SESSION['level']!=md5('c')){ ?> id_sub: id_sub, <?php } ?>
		ta: ta, id_sumber : id_sumber, tgl_awal:tgl_awal, tgl_akhir:tgl_akhir
	});
}

function CetakDaftar(){
	var tgl_awal = $('#tgl_awal').datebox('getValue');
	var tgl_akhir = $('#tgl_akhir').datebox('getValue');
	$.loader.open($dataLoader);
	$.post( "./print/daftar_pengadaan.php", {  <?php if($_SESSION['level']!=md5('c')){ ?> id_sub: id_sub, <?php } ?>
												ta : ta, id_sum : id_sumber,tgl_awal:tgl_awal, tgl_akhir:tgl_akhir, sumber:sumber })
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
	width:120px;
	}
	.fitem input{
	width:160px;
	}
</style>	
