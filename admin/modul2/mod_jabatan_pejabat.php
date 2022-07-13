<div id="hpanel" class="easyui-panel" title="Daftar Jabatan Pejabat" 
        style="width:auto;padding:10px;background:#fafafa;">
 <table id="dg"class="easyui-datagrid" style="width:450px;height:350px"
	url="./model/dd_jabatan_pejabat.php"
	toolbar="#toolbar" pagination="true"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
	<th field="nama_jabatan" width="25" align="left" halign="center">Nama Jabatan</th>
</tr>
</thead>
</table>
<div id="toolbar">
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newJabatan()">New Jabatan</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editJabatan()">Edit Jabatan</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyJabatan()">Remove Jabatan</a>
</div>

<div id="dlg" class="easyui-dialog" style="width:360px;height:250px;padding:10px 20px"
closed="true" buttons="#dlg-buttons">
<div class="ftitle">Informasi Jabatan</div>
<form id="fm" method="post">
<table cellpadding="5">
<tr>
	<td>ID Jabatan</td>
	<td>: <input class="easyui-textbox" type="text" name="id_jabatan" id="id_jabatan" data-options="required:true" size="5"></input></td>
</tr>
<tr>
	<td>Jabatan</td>
	<td>: <input class="easyui-textbox" type="text" name="nama_jabatan" id="nama_jabatan" data-options="required:true" size="25"></input></td>
</tr>
</table>
</form>
</div>
<div id="dlg-buttons">
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveJabatan()" style="width:90px">Save</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
</div>
</div>
<script type="text/javascript">
var urlu;
function newJabatan(){
	//alert('Tes');
	$('#dlg').dialog('open').dialog('setTitle','Tambah Golongan');
	$('#fm').form('clear');
	urlu = './aksi.php?module=jabatan_pejabat&oper=add';
}
function editJabatan(){
	
	var row = $('#dg').datagrid('getSelected');
	if (row){
		$('#dlg').dialog('open').dialog('setTitle','Edit Golongan');
		$('#fm').form('clear');
		$('#fm').form('load',row);
		//alert('Tes');
		urlu = './aksi.php?module=jabatan_pejabat&oper=edit&id_ubah='+row.id;
	}
}
function saveJabatan(){
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
function destroyJabatan(){
	var rw1 = $('#dg').datagrid('getSelected');
	//alert("tes");
	if (rw1){
		$.messager.confirm('Peringatan','Apakah Anda yakin akan menghapus Golongan ini?',function(r){
		if (r){
			$.ajax({
				type: "POST",
				url: './aksi.php?module=jabatan_pejabat&oper=del',
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
