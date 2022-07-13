<div id="hpanel" class="easyui-panel" title="Daftar SKPD/Unit Kerja" 
        style="width:auto;padding:10px;background:#fafafa;">
<table id="dg"class="easyui-datagrid" style="width:820px;height:350px"
	url="./model/dd_sub2_unit.php"
	toolbar="#toolbar" pagination="true"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
<th field="nm_sub_unit" width="40">Nama SKPD/Unit Kerja</th>
<th field="kd_sub2" width="10" align="center">Kode SKPD/Unit Kerja</th>
<th field="nm_sub2_unit" width="40">Nama SKPD/Unit Kerja</th>
</tr>
</thead>
</table>
<div id="toolbar">
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newSub2Unit()">New SKPD/Unit Kerja</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editSub2Unit()">Edit SKPD/Unit Kerja</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroySub2Unit()">Remove SKPD/Unit Kerja</a>
        <div style="float: right;">
        <span>Cari :</span>
        <input id="cari" style="line-height:18px;border:1px solid #ccc">
        <a href="#" class="easyui-linkbutton" plain="true" onclick="doSearch()">Search</a>
        </div>

</div>
<div id="dlg" class="easyui-dialog" style="width:550px;height:300px;padding:10px 20px"
closed="true" buttons="#dlg-buttons">
<div class="ftitle">Informasi SKPD/Unit Kerja</div>
<form id="fm" method="post">
<table cellpadding="5">
<tr>
<td>Nama Sub Unit</td>
<td>: 
<input class="easyui-combobox" style="width:250px;" id="id_sub_unit" name="id_sub_unit" required="true"/>
<script>
$('#id_sub_unit').combobox({
    url:'./model/cb_sub_unit.php',
    valueField:'id',
    textField:'text',
    filter: function(q, row){
		var opts = $(this).combobox('options');
		return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
	}
});
</script></td>
</tr>
<td>Kode SKPD/Unit Kerja</td>
<td>: <input class="easyui-textbox" type="text" name="kd_sub2" id="kd_sub2" data-options="required:true" size="2"></input></td>
</tr>
<tr>
<td>Nama SKPD/Unit Kerja</td>
<td>: <input class="easyui-textbox" style="width:250px" type="text" name="nm_sub2_unit" data-options="required:true"></input></td>
</tr>
</table>
</form>
</div>
<div id="dlg-buttons">
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveSub2Unit()" style="width:90px">Save</a>
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
function newSub2Unit(){
	$('#dlg').dialog('open').dialog('setTitle','Tambah SKPD/Unit Kerja');
	$('#fm').form('clear');
	urlu = './aksi.php?module=sub2_unit&oper=add';
}
function editSub2Unit(){
	var row = $('#dg').datagrid('getSelected');
	if (row){
		$('#dlg').dialog('open').dialog('setTitle','Edit SKPD/Unit Kerja');
		$('#fm').form('clear');
		$('#fm').form('load',row);
		urlu = './aksi.php?module=sub2_unit&oper=edit&id_ubah='+row.id_sub2;
	}
}
function saveSub2Unit(){
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

			//$('#dlg').dialog('close');
		}
	});
}
function destroySub2Unit(){
	var rw1 = $('#dg').datagrid('getSelected');
	if (rw1){
		$.messager.confirm('Peringatan','Apakah Anda yakin akan menghapus SKPD/Unit Kerja ini?',function(r){
		if (r){
			$.ajax({
				type: "POST",
				url: './aksi.php?module=sub2_unit&oper=del',
				data: { id_hapus: rw1.id_sub2 },
				success: function(data){
					$.messager.show({ title: 'Konfirmasi', msg: data });	
					$('#dg').datagrid('reload');			
				}
			});	
			}
		},'json');
	}else $.messager.alert('Peringatan','Pilih SKPD/Unit Kerja yang akan dihapus dahulu !');	
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