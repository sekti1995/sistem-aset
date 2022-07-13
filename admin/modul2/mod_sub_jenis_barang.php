<fieldset>
	<legend>
		Daftar Sub Jenis Barang
	</legend>
 <table id="dg"class="easyui-datagrid" style="width:520px;height:350px"
	url="./model/dd_sub_jenis_barang.php"
	toolbar="#toolbar" pagination="true"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
	<th field="id_sub_jenis" width="5" align="center">ID</th>
	<th field="nama_jenis" width="20" align="left" halign="center">Jenis Barang</th>
	<th field="nama_sub_jenis" width="20" align="left" halign="center">Nama Sub Jenis</th>
</tr>
</thead>
</table>
<div id="toolbar">
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newSubJenis()">New Sub Jenis</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editSubJenis()">Edit Sub Jenis</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroySubJenis()">Remove Sub Jenis</a>
</div>

<div id="dlg" class="easyui-dialog" style="width:460px;height:250px;padding:10px 20px"
closed="true" buttons="#dlg-buttons">
<div class="ftitle">Informasi Sub Jenis Barang</div>
<form id="fm" method="post">
<table cellpadding="5">
<tr>
	<td>Jenis Barang</td>
	<td>: <input class="easyui-combobox" name="id_jenis" id="id_jenis" data-options="required:true" size="25"/>
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
	</script>
	</td>
</tr>
<tr>
	<td>Nama Sub</td>
	<td>: <input class="easyui-textbox" type="text" name="nama_sub_jenis" id="nama_sub_jenis" data-options="required:true" size="25"></input></td>
</tr>
</table>
</form>
</div>
<div id="dlg-buttons">
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveSubJenis()" style="width:90px">Save</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
</div>
<script type="text/javascript">
var urlu;
function newSubJenis(){
	
	$('#dlg').dialog('open').dialog('setTitle','Tambah Sub Jenis');
	$('#fm').form('clear');
	urlu = './aksi.php?module=sub_jenis_barang&oper=add';
}
function editSubJenis(){
	
	var row = $('#dg').datagrid('getSelected');
	if (row){
		$('#dlg').dialog('open').dialog('setTitle','Edit Sub Jenis');
		$('#fm').form('clear');
		$('#fm').form('load',row);
		//alert('Tes');
		urlu = './aksi.php?module=sub_jenis_barang&oper=edit&id_ubah='+row.id;
	}
}
function saveSubJenis(){
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
function destroySubJenis(){
	var rw1 = $('#dg').datagrid('getSelected');
	if (rw1){
		$.messager.confirm('Peringatan','Apakah Anda yakin akan menghapus Sub Jenis Barang ini?',function(r){
		if (r){
			$.ajax({
				type: "POST",
				url: './aksi.php?module=sub_jenis_barang&oper=del',
				data: { id_hapus : rw1.id },
				success: function(data){
					$.messager.show({ title: 'Konfirmasi', msg: data });	
					$('#dg').datagrid('reload');			
				}
			});	
			}
		},'json');
	}else $.messager.alert('Peringatan','Pilih Sub Jenis yang akan dihapus dahulu !');	
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
	</fieldset>

