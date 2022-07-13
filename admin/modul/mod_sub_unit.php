<div id="hpanel" class="easyui-panel" title="Daftar Sub Unit" 
        style="width:auto;padding:10px;background:#fafafa;">
 <table id="dg"class="easyui-datagrid" style="width:820px;height:350px"
	url="./model/dd_sub_unit.php"
	toolbar="#toolbar" pagination="true"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
<th field="nm_unit" width="40">Nama Unit</th>
<th field="kd_sub" width="10" align="center">Kode Sub Unit</th>
<th field="nm_sub_unit" width="40">Nama Sub Unit</th>
</tr>
</thead>
</table>
<div id="toolbar">
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newSubUnit()">New Sub Unit</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editSubUnit()">Edit Sub Unit</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroySubUnit()">Remove Sub Unit</a>
        <div style="float: right;">
        <span>Cari :</span>
        <input id="cari" style="line-height:18px;border:1px solid #ccc">
        <a href="#" class="easyui-linkbutton" plain="true" onclick="doSearch()">Search</a>
        </div>

</div>
<div id="dlg" class="easyui-dialog" style="width:500px;height:300px;padding:10px 20px"
closed="true" buttons="#dlg-buttons">
<div class="ftitle">Informasi Sub Unit</div>
<form id="fm" method="post">
<table cellpadding="5">
<tr>
<td>Nama Unit</td>
<td>: 
<input class="easyui-combobox" style="width:250px;" id="id_unit" name="id_unit" required="true"/>
<script>
$('#id_unit').combobox({
    url:'./model/cb_unit.php',
    valueField:'id',
    textField:'text',
    filter: function(q, row){
		var opts = $(this).combobox('options');
		return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
	}
});
</script></td>
</tr>
<td>Kode Sub Unit</td>
<td>: <input class="easyui-textbox" type="text" name="kd_sub" id="kd_sub" data-options="required:true" size="2"></input></td>
</tr>
<tr>
<td>Nama Sub Unit</td>
<td>: <input class="easyui-textbox" type="text" name="nm_sub_unit" data-options="required:true"></input></td>
</tr>
</table>
</form>
</div>
<div id="dlg-buttons">
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveSubUnit()" style="width:90px">Save</a>
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
function newSubUnit(){
	$('#dlg').dialog('open').dialog('setTitle','Tambah Sub Unit');
	$('#fm').form('clear');
	urlu = './aksi.php?module=sub_unit&oper=add';
}
function editSubUnit(){
	var row = $('#dg').datagrid('getSelected');
	if (row){
		$('#dlg').dialog('open').dialog('setTitle','Edit Sub Unit');
		$('#fm').form('clear');
		$('#fm').form('load',row);
		urlu = './aksi.php?module=sub_unit&oper=edit&id_ubah='+row.id_sub;
	}
}
function saveSubUnit(){
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
					$('#Kd_Sub').focus();	
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
function destroySubUnit(){
	var rw1 = $('#dg').datagrid('getSelected');
	if (rw1){
		$.messager.confirm('Peringatan','Apakah Anda yakin akan menghapus Sub Unit ini?',function(r){
		if (r){
			$.ajax({
				type: "POST",
				url: './aksi.php?module=sub_unit&oper=del',
				data: { id_hapus: rw1.id_sub },
				success: function(data){
					$.messager.show({ title: 'Konfirmasi', msg: data });	
					$('#dg').datagrid('reload');			
				}
			});	
			}
		},'json');
	}else $.messager.alert('Peringatan','Pilih Sub Unit yang akan dihapus dahulu !');	
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