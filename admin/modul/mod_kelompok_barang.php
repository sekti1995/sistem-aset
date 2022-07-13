<div id="hpanel" class="easyui-panel" title="Daftar Kelompok Barang" 
        style="width:auto;padding:10px;background:#fafafa;">
 <table id="dg"class="easyui-datagrid" style="width:520px;height:350px"
	url="./model/dd_kelompok_barang.php"
	toolbar="#toolbar" pagination="true"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
	<th field="id_kelompok" width="10" align="center">Kode Kelompok</th>
	<th field="nama_kelompok" width="26" align="left" halign="center">Nama Kelompok</th>
</tr>
</thead>
</table>
<div id="toolbar">
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newKelompok()">New Kelompok</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editKelompok()">Edit Kelompok</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyKelompok()">Remove Kelompok</a>
</div>

<div id="dlg" class="easyui-dialog" style="width:360px;height:250px;padding:10px 20px"
closed="true" buttons="#dlg-buttons">
<div class="ftitle">Informasi Kelompok Barang</div>
<form id="fm" method="post">
<table cellpadding="5">
<tr>
	<td>Nama</td>
	<td>: <input class="easyui-textbox" type="text" name="nama_kelompok" id="nama_kelompok" data-options="required:true" size="25"></input></td>
</tr>
</table>
</form>
</div>
<div id="dlg-buttons">
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveKelompok()" style="width:90px">Save</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
</div>
</div>
<script type="text/javascript">
var urlu;
function newKelompok(){
	
	$('#dlg').dialog('open').dialog('setTitle','Tambah Kelompok');
	$('#fm').form('clear');
	urlu = './aksi.php?module=kelompok_barang&oper=add';
}
function editKelompok(){
	
	var row = $('#dg').datagrid('getSelected');
	if (row){
		$('#dlg').dialog('open').dialog('setTitle','Edit Kelompok');
		$('#fm').form('clear');
		$('#fm').form('load',row);
		//alert('Tes');
		urlu = './aksi.php?module=kelompok_barang&oper=edit&id_ubah='+row.id;
	}
}
function saveKelompok(){
	$('#fm').form('submit',{
		url: urlu,
		onSubmit: function(){
		return $(this).form('validate');
		},
		success: function(result){
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
function destroyKelompok(){
	var rw1 = $('#dg').datagrid('getSelected');
	if (rw1){
		$.messager.confirm('Peringatan','Apakah Anda yakin akan menghapus Kelompok Barang ini?',function(r){
		if (r){
			$.ajax({
				type: "POST",
				url: './aksi.php?module=kelompok_barang&oper=del',
				data: { id_hapus : rw1.id },
				success: function(data){
					$.messager.show({ title: 'Konfirmasi', msg: data });	
					$('#dg').datagrid('reload');			
				}
			});	
			}
		},'json');
	}else $.messager.alert('Peringatan','Pilih Tahun yang akan dihapus dahulu !');	
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