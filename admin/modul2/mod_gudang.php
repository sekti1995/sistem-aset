<div id="hpanel" class="easyui-panel" title="Daftar Tempat Penyimpanan" 
        style="width:auto;padding:10px;background:#fafafa;">
<table id="dg"class="easyui-datagrid" style="width:730px;height:350px"
	url="./model/dd_gudang.php"
	toolbar="#toolbar" pagination="true"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
	<th field="nama_gudang" width="30" align="left" halign="center">Nama Tempat</th>
	<th field="lokasi" width="20" align="left" halign="center">Lokasi</th>
	<th field="nm_sub2_unit" width="50" align="left" halign="center">SKPD / Unit Kerja</th>
</tr>
</thead>
</table>
<div id="toolbar">
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newGudang()">New Tempat</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editGudang()">Edit Tempat</a>
	<!--<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyGudang()">Remove Tempat</a>-->
</div>

<div id="dlg" class="easyui-dialog" style="width:500px;height:300px;padding:10px 20px"
closed="true" buttons="#dlg-buttons">
<div class="ftitle">Informasi Tempat</div>
<form id="fm" method="post">
<table cellpadding="5">
<tr>
<td>SKPD/Unit Kerja</td>
<td>: 
<input class="easyui-combobox" style="width:250px;" id="id_sub2_unit" name="id_sub2_unit" <?php if($peran!=md5('1')) echo 'readonly'; ?> required="true"/>
<script>
$('#id_sub2_unit').combobox({
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
	<td>Nama Tempat</td>
	<td>: <input class="easyui-textbox" type="text" name="nama_gudang" id="nama_gudang" data-options="required:true" size="25"></input></td>
</tr>
<tr>
	<td>Lokasi</td>
	<td>: <input class="easyui-textbox" type="text" name="lokasi" id="lokasi" data-options="required:true" size="25"></input></td>
</tr>
</table>
<input type="hidden" id="uid" name="uid"/>
</form>
</div>
<div id="dlg-buttons">
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveGudang()" style="width:90px">Save</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
</div>
</div>
<script type="text/javascript">
var urlu;
function newGudang(){
	
	$('#dlg').dialog('open').dialog('setTitle','Tambah Tempat Penyimpanan');
	$('#fm').form('clear');
	$('#id_sub2_unit').combobox('reload');
	urlu = './aksi.php?module=gudang&oper=add';
}
function editGudang(){
	
	var row = $('#dg').datagrid('getSelected');
	if (row){
		$('#dlg').dialog('open').dialog('setTitle','Edit Tempat Penyimpanan');
		$('#fm').form('clear');
		$('#fm').form('load',row);
		//alert('Tes');
		urlu = './aksi.php?module=gudang&oper=edit&id_ubah='+row.id;
	}
}
function saveGudang(){
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
					$('#tahun').focus();	
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
function destroyGudang(){
	var rw1 = $('#dg').datagrid('getSelected');
	if (rw1){
		$.messager.confirm('Peringatan','Apakah Anda yakin akan menghapus Data Penyimpanan ini?',function(r){
		if (r){
			$.ajax({
				type: "POST",
				url: './aksi.php?module=gudang&oper=del',
				data: { id_hapus : rw1.id },
				success: function(data){
					$.messager.show({ title: 'Konfirmasi', msg: data });	
					$('#dg').datagrid('reload');			
				}
			});	
			}
		},'json');
	}else $.messager.alert('Peringatan','Pilih Data Tempat Penyimpanan yang akan dihapus dahulu !');	
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