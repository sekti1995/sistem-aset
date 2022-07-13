<div id="hpanel" class="easyui-panel" title="Daftar Barang Kegiatan" 
        style="width:auto;padding:10px;background:#fafafa;">
 <table id="dg"class="easyui-datagrid" style="width:900px;height:350px"
	url="./model/dd_barang_kegiatan.php"
	toolbar="#toolbar" pagination="true"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
<th field="nama_jenis" width="150" align="left"  halign="center">Jenis Persediaan</th>
<th field="kode" width="70" align="center"  halign="center">Kode Barang</th>
<th field="nama_barang" width="150" align="left"  halign="center">Nama Barang</th>
<th field="nama_satuan" width="70" align="left"  halign="center">Nama Satuan</th>
<th field="keterangan" width="200" align="left"  halign="center">Spesifikasi</th>
</tr>
</thead>
</table>
<div id="toolbar">
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newKegiatan()">New Barang</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editKegiatan()">Edit Barang</a>
<!--
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyKegiatan()">Remove Barang</a>
-->
<div style="float: right; margin-right: 5px;">
	<a href="#" class="easyui-linkbutton" plain="true" iconCls="icon-search" onclick="javascript:$('#dls').dialog('open')">Pencarian</a>
</div>        
</div>
<div id="dlg" class="easyui-dialog" style="width:700px;height:400px;padding:10px 20px"
closed="true" buttons="#dlg-buttons">
<div class="ftitle">Informasi Barang Kegiatan</div>
<form id="fm" method="post">
<table cellpadding="5">
<tr>
<td>SKPD/Unit Kerja</td>
<td>: 
<input class="easyui-combobox" style="width:250px;" id="id_unit" name="id_unit" <?php if($peran!=md5('1')) echo 'readonly'; ?> required="true"/>
<script>
$('#id_unit').combobox({
    url:'./model/cb_sub2_unit.php?id_unit=<?php echo $_SESSION['uidunit']; ?>',
    valueField:'id',
    textField:'text',
    filter: function(q, row){
		var opts = $(this).combobox('options');
		return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
	},
	onSelect: function(rec){
		$('#uid').val(rec.uid);
	}
});
</script></td>
</tr>
<tr>
<td>TA</td>
<td>: <input class="easyui-combobox" style="width:70px;" id="ta" name="ta" required="true"/>
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
</td>
</tr>
<tr>
<td>Jenis Barang</td>
<td>: 
<input class="easyui-combobox" style="width:350px;" id="id_jenis" name="id_jenis" required="true"/>
<script>
$('#id_jenis').combobox({
    url:'./model/cb_jenis.php?kel=3',
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
<td>: <input class="easyui-textbox" type="text" name="kode" id="kode" data-options="required:true" size="5"></input></td>
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
<td>Spek / Merk / Ukuran</td>
<td>: <input class="easyui-textbox" type="text" name="keterangan"  style="width:250px;" ></input></td>
</tr>
</table>
</form>
</div>
<div id="dlg-buttons">
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveKegiatan()" style="width:90px">Save</a>
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
    url:'./model/cb_jenis.php?kel=3',
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
var urlu;
function doSearch(){
	$('#dg').datagrid('load',{
		jenis: $('#jenis_search').combobox('getValue'),
		nama: $('#nama_search').val(),
		kode: $('#kode_search').val()
	});
}

function newKegiatan(){
	$('#dlg').dialog('open').dialog('setTitle','Tambah Barang Kegiatan');
	$('#fm').form('clear');
	$('#id_unit').combobox('reload');
	urlu = './aksi.php?module=barang_kegiatan&oper=add';
}
function editKegiatan(){
	var row = $('#dg').datagrid('getSelected');
	if (row){
		$('#dlg').dialog('open').dialog('setTitle','Edit Barang Kegiatan');
		$('#fm').form('clear');
		$('#fm').form('load',row);
		urlu = './aksi.php?module=barang_kegiatan&oper=edit&id_ubah='+row.id;
	}
}
function saveKegiatan(){
	$('#fm').form('submit',{
		url: urlu,
		onSubmit: function(){
		return $(this).form('validate');
		},
		success: function(result){
			console.log(result);
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

			$('#dlg').dialog('close');
		}
	});
}
function destroyKegiatan(){
	var rw1 = $('#dg').datagrid('getSelected');
	if (rw1){
		$.messager.confirm('Peringatan','Apakah Anda yakin akan menghapus Barang Kegiatan ini?',function(r){
		if (r){
			$.ajax({
				type: "POST",
				url: './aksi.php?module=barang_kegiatan&oper=del',
				data: { id_hapus: rw1.id },
				success: function(data){
					$.messager.show({ title: 'Konfirmasi', msg: data });	
					$('#dg').datagrid('reload');			
				}
			});	
			}
		},'json');
	}else $.messager.alert('Peringatan','Pilih Barang Kegiatan yang akan dihapus dahulu !');	
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