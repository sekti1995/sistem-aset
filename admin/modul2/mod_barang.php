<div id="hpanel" class="easyui-panel" title="Daftar Barang" 
        style="width:auto;padding:10px;background:#fafafa;">
 <table id="dg"class="easyui-datagrid" style="width:900px;height:350px"
	url="./model/dd_barang.php"
	toolbar="#toolbar" pagination="true"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
<th field="nama_jenis" width="150" align="left"  halign="center">Jenis Persediaan</th>
<th field="kd_sub2" width="50" align="center"  halign="center">Kode Barang</th>
<th field="nama_barang" width="150" align="left"  halign="center">Nama Barang</th>
<th field="nama_satuan" width="60" align="left"  halign="center">Nama Satuan</th>
<th field="harga_index" width="60" align="right"  halign="center">Index Harga</th>
<th field="keterangan" width="200" align="left"  halign="center">Spesifikasi</th>
</tr>
</thead>
</table>
<div id="toolbar">
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newBarang()">New Barang</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editBarang()">Edit Barang</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyBarang()">Remove Barang</a>
<!--
-->
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="cetakBarang()">Cetak Daftar Barang</a>
<div style="float: right; margin-right: 5px;">
	<a href="#" class="easyui-linkbutton" plain="true" iconCls="icon-search" onclick="javascript:$('#dls').dialog('open')">Pencarian</a>
</div>        
</div>
<div id="dlg" class="easyui-dialog" style="width:700px;height:400px;padding:10px 20px"
closed="true" buttons="#dlg-buttons">
<div class="ftitle">Informasi Barang</div>
<form id="fm" method="post">
<table cellpadding="5">
<tr>
<td>Jenis Barang</td>
<td>: 
<input class="easyui-combobox" style="width:350px;" id="id_jenis" name="id_jenis" required="true"/>
<script>
$('#id_jenis').combobox({
    url:'./model/cb_jenis.php',
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
<td>Kode Barang</td>
<td>: <input class="easyui-textbox" type="text" name="kd_sub2" id="kd_sub2" size="5"></input></td>
</tr>
<tr>
<td>Nama Barang</td>
<td>: <input class="easyui-textbox" type="text" name="nama_barang" id="nama_barang" data-options="required:true" size="35"></input></td>
</tr>
<td>Nama Satuan</td>
<td>: 
<input class="easyui-combobox" style="width:100px;" id="id_satuan" name="id_satuan" required="true"/>
<script>
$('#id_satuan').combobox({
    url:'./model/cb_satuan_barang.php',
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
<td>Index Harga</td>
<td>: <input class="easyui-textbox" type="text" name="harga_index" id="harga_index" data-options="required:true" size="10"></input></td>
</tr>
<tr>
<td>Spek / Merk / Ukuran</td>
<td>: <input class="easyui-textbox" type="text" name="keterangan"  style="width:250px;" ></input></td>
</tr>
<tr>
<td>Status</td>
<td>: <input type="checkbox" name="status" id="status">Belum Ada di Standard Index Biaya</input></td>
</tr>
</table>
</form>
</div>
<div id="dlg-buttons">
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveBarang()" style="width:90px">Save</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
</div>
</div>



<div id="dls" class="easyui-dialog" style="width:450px;height:240px;padding:10px 20px"
closed="true" buttons="#dls-buttons" title="Pencarian Data Barang">
<div class="ftitle">Pencarian Data Barang</div>
<form id="fms" method="post">
<table cellpadding="5">
<tr>
<td>Jenis</td>
<td>: 
<input class="easyui-combobox" style="width:250px;" id="jenis_search" name="jenis_search"/>
<script>
$('#jenis_search').combobox({
    url:'./model/cb_jenis.php',
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
	<td>Kode</td>
	<td>: <input class="easyui-textbox" type="text" name="kode_search" id="kode_search" size="5"></input></td>
</tr>
<tr>
	<td>Nama Barang</td>
	<td>: <input class="easyui-textbox" type="text" name="nama_search" id="nama_search" size="25"></input></td>
</tr>
</table>
</form>
</div>
<div id="dls-buttons">
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="doSearch()" style="width:90px">Cari</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dls').dialog('close')" style="width:90px">Batal</a>
</div>

<script type="text/javascript">

function cetakBarang(){
	$.loader.open($dataLoader);
	$.post( "./print/daftar_barang.php", { })
	.done(function( data ) {
		if(data.success==false) alert(data.pesan);
		window.location.href = data.url;
		$.loader.close($dataLoader);
	});
}

var urlu;
$(function(){
	$('#harga_index').textbox('textbox').bind('keyup',function(e){
		var $this = $(this);
		var num = $this.val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
		$this.val(num);
	});	
});


function doSearch(){
	$('#dg').datagrid('load',{
		jenis: $('#jenis_search').combobox('getValue'),
		nama: $('#nama_search').val(),
		kode: $('#kode_search').val()
	});
}

function newBarang(){
	$('#dlg').dialog('open').dialog('setTitle','Tambah Barang');
	//$('#fm').form('clear');
	$('#id_sub_unit').combobox('reload');
	urlu = './aksi.php?module=barang&oper=add'; 
}
function editBarang(){
	var row = $('#dg').datagrid('getSelected');
	if (row){
		$('#dlg').dialog('open').dialog('setTitle','Edit Barang');
		$('#fm').form('clear');
		$('#fm').form('load',row);
		urlu = './aksi.php?module=barang&oper=edit&id_ubah='+row.id;
	}
}
function saveBarang(){
	$('#fm').form('submit',{
		url: urlu,
		onSubmit: function(){
		return $(this).form('validate');
		},
		success: function(result){
			//console.log(result);
			var result = eval('('+result+')');
			if (result.success==false){
				if(result.error=='nomor_sama'){ 
					$.messager.show({ title: 'Error', msg: result.pesan });
					return;
				}else $.messager.show({ title: 'Error', msg: result.pesan });
			} else {
				$.messager.show({ title: 'Sukses', msg: result.pesan }); 
				$('#dg').datagrid('reload');
			}

			//$('#dlg').dialog('close');
		}
	});
}
function destroyBarang(){
	var rw1 = $('#dg').datagrid('getSelected');
	if (rw1){
		$.messager.confirm('Peringatan','Apakah Anda yakin akan menghapus Barang ini?',function(r){
		if (r){
			$.ajax({
				type: "POST",
				url: './aksi.php?module=barang&oper=del',
				data: { id_hapus: rw1.id },
				success: function(data){
					$.messager.show({ title: 'Konfirmasi', msg: data });	
					$('#dg').datagrid('reload');			
				}
			});	
			}
		},'json');
	}else $.messager.alert('Peringatan','Pilih Barang yang akan dihapus dahulu !');	
}
</script>
<style type="text/css">
	#fm{
	margin:0;
	padding:10px 30px;
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