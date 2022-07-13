<div id="hpanel" class="easyui-panel" title="Daftar Golongan Pejabat" 
        style="width:auto;padding:10px;background:#fafafa;">
 <table id="dg"class="easyui-datagrid" style="width:520px;height:350px"
	url="./model/dd_golongan_pejabat.php"
	toolbar="#toolbar" pagination="true"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
	<th field="nama_golongan" width="35" halign="center" align="left">Nama Golongan</th>
	<th field="pangkat" width="15" align="center">Pangkat</th>
</tr>
</thead>
</table>
<div id="toolbar">
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newGolongan()">New Golongan</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editGolongan()">Edit Golongan</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyGolongan()">Remove Golongan</a>
</div>

<div id="dlg" class="easyui-dialog" style="width:420px;height:250px;padding:10px 20px"
closed="true" buttons="#dlg-buttons">
<div class="ftitle">Informasi Golongan</div>
<form id="fm" method="post">
<table cellpadding="5">
<tr>
	<td>Golongan</td>
	<td>: <input class="easyui-textbox" type="text" name="nama_golongan" id="nama_golongan" data-options="required:true" size="25"></input></td>
</tr>
<tr>
	<td>Pangkat</td>
	<td>: <input class="easyui-textbox" type="text" name="pangkat" id="pangkat" data-options="required:true" size="25"></input></td>
</tr>
</table>
</form>
</div>
<div id="dlg-buttons">
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveGolongan()" style="width:90px">Save</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
</div>
</div>
<script type="text/javascript">
var urlu;
function newGolongan(){
	
	$('#dlg').dialog('open').dialog('setTitle','Tambah Golongan');
	$('#fm').form('clear');
	urlu = './aksi.php?module=golongan_jabatan&oper=add';
}
function editGolongan(){
	
	var row = $('#dg').datagrid('getSelected');
	if (row){
		$('#dlg').dialog('open').dialog('setTitle','Edit Golongan');
		$('#fm').form('clear');
		$('#fm').form('load',row);
		//alert('Tes');
		urlu = './aksi.php?module=golongan_jabatan&oper=edit&id_ubah='+row.id;
	}
}
function saveGolongan(){
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
function destroyGolongan(){
	var rw1 = $('#dg').datagrid('getSelected');
	//alert("tes");
	if (rw1){
		$.messager.confirm('Peringatan','Apakah Anda yakin akan menghapus Golongan ini?',function(r){
		if (r){
			$.ajax({
				type: "POST",
				url: './aksi.php?module=golongan_jabatan&oper=del',
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