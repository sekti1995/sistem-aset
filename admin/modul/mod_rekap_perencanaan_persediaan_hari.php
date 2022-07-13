<div class="dtabel" style="width:100%;height:100%;background:white">	
 <table id="dgfull"class="easyui-datagrid"
	url="./model/rekap_perencanaan_persediaan_hari.php" fit="true" showFooter="true"
	toolbar="#toolbar"  title="Laporan Data Perencanaan Barang"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
<th field="nm_barang" width="150" align="left"  halign="center">Nama Barang</th>
<th field="nama_satuan" width="30" align="left"  halign="center">Satuan</th>
<th field="harga" width="40" align="right"  halign="center">Harga Satuan</th>
<th field="jumlah_barang" width="60" align="left"  halign="center">Jumlah Satuan Barang</th>
<th field="jumlah_barang_isi" width="60" align="left"  halign="center">Jumlah Satuan Pengadaan</th>
<th field="harga_pengadaan" width="60" align="left"  halign="center">Harga Satuan Pengadaan</th>
<th field="sisa" width="40" align="right" halign="center">Sisa Satuan</th>
<th field="harga" width="40" align="right" halign="center">Sisa Harga Satuan</th>
</tr>
</thead>
</table>
</div>
<div id="toolbar"> 
	<div>
		<form id="fm" method="post" enctype="multipart/form-data">
			<table cellpadding="5">
			
			<div id="toolbar">
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="CetakDaftar()">Cetak Excel</a>
			<div style="float: right; margin-right: 5px;">
				<a href="#" class="easyui-linkbutton" plain="true" iconCls="icon-search" onclick="javascript:$('#dls').dialog('open')">Pencarian</a>
			</div>
			</div>
			<hr>
			</table> 
		</form>
	</div>
	<div style="clear:both"></div>
</div>


<div id="dls" class="easyui-dialog" style="width:450px;height:340px;padding:10px 20px"
closed="true" buttons="#dls-buttons" title="Pencarian Data Perencanaan">
<div class="ftitle">Pencarian Data Sisa Perencanaan Barang</div>
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
		$('#id_sumber').combobox('clear');
		$('#id_sumber').combobox('reload', './model/cb_sumber_dana.php?cek&id='+rec.id);
		$('#id_kegiatan').combobox('clear');
		$('#id_kegiatan').combobox('reload', './model/cb_import_keg1.php?cek&id='+rec.id);
		$('#gudang_search').combobox('clear');
		$('#gudang_search').combobox('reload', './model/cb_gudang.php?cek&id='+rec.id);
	}
});
</script></td>
</tr>
<?php } ?>
<tr>
<td>Kegiatan</td>
<td>: 
<input class="easyui-combobox" style="width:200px;" id="id_kegiatan" name="id_kegiatan"/>
<script>
$('#id_kegiatan').combobox({
    url:'./model/cb_import_keg1.php?cek',
    valueField:'kd_kegiatan',
    textField:'nm_kegiatan',
    filter: function(q, row){
		var opts = $(this).combobox('options');
		return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
	}
});
</script></td>
</tr>
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
<!-- <tr>
	<td>Tanggal Awal</td>
	<td>: <input class="easyui-datebox" data-options="formatter:myformatter,parser:myparser,required:true" style="width:90px;" id="tglawal" name="tglawal" required="true"/>
	</td>
</tr>
<tr>
	<td>Tanggal Akhir</td>
	<td>: <input class="easyui-datebox" style="width:90px;" id="tglakhir" name="tglakhir" data-options="formatter:myformatter,parser:myparser,required:true" required="true"/>
	</td>
</tr> -->
<tr>
	<td>Tahun</td>
	<td>: <input class="easyui-combobox" style="width:60px;" id="thn" name="thn"  required="true"/>
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
var ta; var id_sub; var id_sum; var id_keg;
function doSearch(){
	if($('#fms').form('validate')==false) return;
	ta = $('#thn').combobox('getValue');
	// bln = $('#bln').combobox('getValue');
	id_sum = $('#id_sumber').combobox('getValue');
	id_keg = $('#id_kegiatan').combobox('getValue');
	// blnt = $('#bln').combobox('getText');
	//var tglawal = $('#tglawal').datebox('getValue');
	//var tglakhir = $('#tglakhir').datebox('getValue');
	<?php if($_SESSION['level']!=md5('c')){ ?> id_sub = $('#id_sub').combobox('getValue'); <?php } ?>
	if(ta==undefined || ta=='') $.messager.alert('Peringatan','Tahun Belum dipilih !');
	<?php if($_SESSION['level']!=md5('c')){ ?>else if(id_sub==undefined || id_sub=='') $.messager.alert('Peringatan','Unit Kerja Belum dipilih !'); <?php } ?>
	
	else{
		$('#dgfull').datagrid('load',{
			<?php if($_SESSION['level']!=md5('c')){ ?> id_sub: id_sub, <?php } ?>
			ta: ta,
			id_sum: id_sum,
			id_keg: id_keg
		});
	}	
}

function CetakDaftar(){
	var basket = $('#dgfull').datagrid('getData');
	var ta = $('#thn').combobox('getValue');
	var id_sum = $('#id_sumber').combobox('getText');
	var id_keg = $('#id_kegiatan').combobox('getText');
	if(ta==undefined || ta=='') $.messager.alert('Peringatan','Tahun Belum dipilih !');
	<?php if($_SESSION['level']!=md5('c')){ ?>else if(id_sub==undefined || id_sub=='') $.messager.alert('Peringatan','Unit Kerja Belum dipilih !'); <?php } ?>
	
	else{
		$.loader.open($dataLoader);
		$.post( "./print/lap_perencanaan.php", { basket : basket.rows, id_sum : id_sum,id_keg : id_keg, ta:ta,
				<?php if($_SESSION['level']!=md5('c')){ ?> id_sub: id_sub, <?php } ?> })
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
