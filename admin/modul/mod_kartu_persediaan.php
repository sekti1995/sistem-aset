 <div class="dtabel" style="width:100%;height:100%;background:white">	
 <table id="dgfull"class="easyui-datagrid"
	url="./model/lap_kartu_persediaan.php" fit="true"
	toolbar="#toolbar" title="Kartu Persediaan Barang"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
<th field="tanggal" width="60" align="left" halign="center"  rowspan="2">TANGGAL TRANSAKSI</th>
<th field="notgl_surat" width="100" align="left" halign="center"  rowspan="2">NO/TGL SURAT</th>
<th field="uraian" width="200" align="left" halign="center"  rowspan="2">URAIAN</th>

<th width="100" align="center" colspan="3">Barang - Barang</th> 
<th field="hrg_masuk" width="100" align="right" halign="center"  rowspan="2">Harga Satuan</th> 
<th width="100" align="center" colspan="3">Jumlah Harga </th> 
<th field="ket" width="100" align="center" halign="center"  rowspan="2">Ket</th> 
</tr>
<tr>
<th field="jml_masuk" width="50" align="center" halign="center">Masuk</th>
<th field="jml_keluar" width="60" align="center" halign="center">Keluar</th>
<th field="saldo" width="60" align="center" halign="center">Sisa</th>
<th field="bertambah" width="60" align="right" halign="center">Bertambah</th>
<th field="berkurang" width="60" align="right" halign="center">Berkurang</th>
<th field="sisa" width="60" align="right" halign="center">Sisa</th>
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


<div id="dls" class="easyui-dialog" style="width:450px;height:380px;padding:10px 20px"
closed="true" buttons="#dls-buttons"title="Pencarian Data Kartu Persediaan">
<div class="ftitle">Pencarian Data Kartu Persediaan</div>
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
		$('#gudang_search').combobox('clear');
		$('#gudang_search').combobox('reload', './model/cb_gudang.php?cek&id='+rec.id);
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
	<td>Nama Barang</td>
	<td>: <input class="easyui-combobox" style="width:230px;" id="barang_search" name="barang_search"  required="true"/>
	<script>
	$('#barang_search').combobox({
		url:'./model/cb_barang_ada.php?cek',
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
	<td>Tempat</td>
	<td>: <input class="easyui-combobox" style="width:200px;" id="gudang_search" name="gudang_search"  required="true"/>
	<script>
	$('#gudang_search').combobox({
		url:'./model/cb_gudang.php?cek',
		valueField:'id_gud',
		textField:'nama_gud',
		filter: function(q, row){
			var opts = $(this).combobox('options');
			return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
		}
	});
	</script>
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
<tr>
	<td>Tahun</td>
	<td>: <input class="easyui-combobox" style="width:60px;" id="thn" name="thn" required="true" />
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
var ta; var gudang; var barang; var id_sub; var sumber; var bln; var nmsumber;
function doSearch(){
	if($('#fms').form('validate')==false) return;
	<?php if($_SESSION['level']!=md5('c')){ ?> id_sub = $('#id_sub').combobox('getValue'), <?php } ?>
	ta = $('#thn').combobox('getValue');
	var tglawal = $('#tglawal').datebox('getValue');
	var tglakhir = $('#tglakhir').datebox('getValue');
	gudang = $('#gudang_search').combobox('getValue');
	barang = $('#barang_search').combobox('getValue');
	sumber = $('#id_sumber').combobox('getValue');
	nmsumber = $('#id_sumber').combobox('getText');
	$('#dgfull').datagrid('load',{
		ta: ta, 
		tglawal: tglawal,
		tglakhir: tglakhir,
		idgud: gudang,
		idbar: barang,
		idsum: sumber
	});
}

function CetakDaftar(){
	var basket = $('#dgfull').datagrid('getData');
	$.loader.open($dataLoader);
	$.post( "./print/kartu_persediaan.php", { ta : ta, idgud: gudang, idbar : barang, idsum: sumber, basket:basket,
			<?php if($_SESSION['level']!=md5('c')){ ?> id_sub : id_sub, <?php } ?> sumber: nmsumber })
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
