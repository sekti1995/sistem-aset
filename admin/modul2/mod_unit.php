<div id="hpanel" class="easyui-panel" title="Daftar Unit Organisasi" 
        style="width:auto;padding:10px;background:#fafafa;">
 <table id="dg"class="easyui-datagrid" style="width:820px;height:350px"
	url="./model/dd_unit.php"
	toolbar="#toolbar" pagination="true"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
<th field="nm_bidang" width="50" align="left">Nama Bidang</th>
<th field="kd_unit" width="10" align="center">Kode Unit</th>
<th field="nm_unit" width="70">Nama Unit Organisasi</th>
</tr>
</thead>
</table>
<div id="toolbar">
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newUnit()">New Unit</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editUnit()">Edit Unit</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyUnit()">Remove Unit</a>
        <div style="float: right;">
        <span>Cari :</span>
        <input id="cari" style="line-height:18px;border:1px solid #ccc">
        <a href="#" class="easyui-linkbutton" plain="true" onclick="doSearch()">Search</a>
        </div>
</div>
<div id="dlg" class="easyui-dialog" style="width:520px;height:300px;padding:10px 20px"
closed="true" buttons="#dlg-buttons">
<div class="ftitle">Informasi Unit Organisasi</div>
<form id="fm" method="post">
<table cellpadding="5">
<tr>
	<td>Bidang</td>
	<td>: 
	<input class="easyui-combobox" style="width:120px;" id="id_bidang" name="id_bidang" required="true"/>
	<script>
	$('#id_bidang').combobox({
		url:'./model/cb_bidang.php',
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
<td>Kode Unit</td>
<td>: <input class="easyui-textbox" type="text" name="kd_unit" id="kd_unit" data-options="required:true" size="2"></input></td>
</tr>
<tr>
<td>Nama Unit</td>
<td>: <input class="easyui-textbox" type="text" name="nm_unit" data-options="required:true"  size="40"></input></td>
</tr>
</table>
</form>
</div>
<div id="dlg-buttons">
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveUnit()" style="width:90px">Save</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
</div>
</div>
<script type="text/javascript">
var urlu;
function doSearch(){
        $('#dg').datagrid('load',{
            cari: $('#cari').val()
        });
}
function newUnit(){
	$('#dlg').dialog('open').dialog('setTitle','Tambah Unit Organisasi');
	$('#fm').form('clear');
	urlu = './aksi.php?module=unit&oper=add';
}
function editUnit(){
	var row = $('#dg').datagrid('getSelected');
	if (row){
		$('#dlg').dialog('open').dialog('setTitle','Edit Unit Organisasi');
		$('#fm').form('clear');
		$('#fm').form('load',row);
		urlu = './aksi.php?module=unit&oper=edit&id_ubah='+row.id;
	}
}
function saveUnit(){
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
					$('#Kd_Urusan').focus();	
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
function destroyUnit(){
	var rw1 = $('#dg').datagrid('getSelected');
	if (rw1){
		$.messager.confirm('Peringatan','Apakah Anda yakin akan menghapus Unit ini?',function(r){
		if (r){
			$.ajax({
				type: "POST",
				url: './aksi.php?module=unit&oper=del',
				data: { id_hapus : rw1.id },
				success: function(data){
					$.messager.show({ title: 'Konfirmasi', msg: data });	
					$('#dg').datagrid('reload');			
				}
			});	
			}
		},'json');
	}else $.messager.alert('Peringatan','Pilih Unit yang akan dihapus dahulu !');	
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