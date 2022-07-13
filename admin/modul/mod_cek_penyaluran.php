<div class="dtabel" style="width:100%;height:100%;background:white">	
<table id="dgfull"class="easyui-datagrid"
	url="./model/cek_penyaluran.php" fit="true" showFooter="true"
	toolbar="#toolbar" title="Cek Penyaluran Barang"
	rownumbers="true" fitColumns="true" singleSelect="true" >
<thead>
<tr>
	<th field="penerima" width="120" align="left" halign="center" rowspan="2">PENERIMA</th>
	<th field="sumber_dana" width="60" align="left" halign="center" rowspan="2">SUMBER DANA</th>
	<th field="id_stok" width="120" align="left" halign="center" rowspan="2">ID STOK</th>
	<th field="nama_barang" width="100" align="left" halign="center" rowspan="2">NAMA BARANG</th>
	<th field="sat1" width="50" align="center" halign="center" rowspan="2">SATUAN</th>
	<th width="130" align="center" colspan="2">DISALURKAN</th>
	<th width="130" align="center" colspan="2">DITERIMA</th>
	<th field="selisih" width="40" align="left" halign="center" rowspan="2">SELISIH</th>
</tr>
<tr>

	<th field="jml1" width="50" align="right" halign="center">JML</th>
	<th field="harga1" width="70" align="right" halign="center">HARGA</th>
	
	<th field="jml2" width="50" align="right" halign="center">JML</th>
	<th field="harga2" width="70" align="right" halign="center">HARGA</th>

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


<div id="dls" class="easyui-dialog" style="width:450px;height:auto;padding:10px 20px"
closed="true" buttons="#dls-buttons" title="Pencarian Data Laporan Mutasi Barang">
<div class="ftitle">Pencarian Data Laporan Mutasi Barang</div>
<form id="fms" method="post">

<table cellpadding="5">

<tr>
	<td>Dari</td>
	<td>
		: 
		<input class="easyui-combobox" style="width:250px;" id="dari" name="dari" required="true"/>
		<script>
		$('#dari').combobox({
			url:'./model/cb_sub2_unit.php',
			valueField:'id',
			textField:'text',
			filter: function(q, row){
				var opts = $(this).combobox('options');
				return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
			},
			onSelect: function(rec){
			} 
		});
		</script>
	</td>
</tr>
<!--
<tr>
	<td>Disalurkan ke</td>
	<td>
		: 
		<input class="easyui-combobox" style="width:250px;" id="ke" name="ke" required="true"/>
		<script>
		$('#ke').combobox({
			url:'./model/cb_sub2_unit.php',
			valueField:'id',
			textField:'text',
			filter: function(q, row){
				var opts = $(this).combobox('options');
				return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
			},
			onSelect: function(rec){
			} 
		});
		</script>
	</td>
</tr>
-->
<tr>
<td>Sumber Dana</td>
<td>: 
<input class="easyui-combobox" style="width:200px;" id="id_sumber" name="id_sumber"/>
<script>
$('#id_sumber').combobox({
    url:'./model/cb_sumber_dana.php',
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
	<td>Tanggal Awal</td>
	<td>: <input class="easyui-datebox" data-options="formatter:myformatter,parser:myparser,required:true" style="width:90px;" id="tglawal" name="tglawal" required="true"/>
	</td>
</tr>
<tr>
	<td>Tanggal Akhir</td>
	<td>: <input class="easyui-datebox" style="width:90px;" id="tglakhir" name="tglakhir" data-options="formatter:myformatter,parser:myparser,required:true" required="true"/>
	</td>
</tr>
<!--
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
-->	
</table>
</form>
</div>
<div id="dls-buttons">
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="doSearch()" style="width:90px">Cari</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dls').dialog('close')" style="width:90px">Batal</a>
</div>


<script type="text/javascript">

function doSearch(){
	var dari = $('#dari').combobox('getValue');
	// var ke = $('#ke').combobox('getValue');
	var tglawal = $('#tglawal').datebox('getValue');
	var tglakhir = $('#tglakhir').datebox('getValue');
	var id_sum = $('#id_sumber').combobox('getValue');
	

	$('#dgfull').datagrid('load',{
		dari: dari,
		// ke: ke,
		tglawal: tglawal,
		tglakhir: tglakhir,
		id_sum: id_sum
	});
	
}

function CetakDaftar(){
	var basket = $('#dgfull').datagrid('getData');
	var sd = $('#id_sumber').combobox('getText');
	var tglawal = $('#tglawal').datebox('getValue');
	var tglakhir = $('#tglakhir').datebox('getValue');
	var ta = $('#thn').combobox('getValue');
	if(tglawal==undefined || tglawal=='') $.messager.alert('Peringatan','Tanggal Belum dipilih !');
	<?php if($_SESSION['level']!=md5('c')){ ?>else if(id_sub==undefined || id_sub=='') $.messager.alert('Peringatan','Unit Kerja Belum dipilih !'); <?php } ?>
	else if(ta==undefined || ta=='') $.messager.alert('Peringatan','Tahun Belum dipilih !');
	else{
		$.loader.open($dataLoader);
		$.post( "./print/rekap_persediaan_hari.php", { basket : basket.rows, tglawal : tglawal, tglakhir : tglakhir, sd:sd, ta:ta,
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
