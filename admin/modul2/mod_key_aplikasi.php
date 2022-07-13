<div id="hpanel" class="easyui-panel" title="Daftar Buat Key Aplikasi" 
        style="width:auto;padding:10px;background:#fafafa;">
 <table id="dg"class="easyui-datagrid" style="width:820px;height:350px"
	url="./model/dd_user.php"
	toolbar="#toolbar" pagination="true"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
<th field="nama_pengelola" width="10">Nama User</th>
<th field="serial_key" width="20" align="center">Key Aplikasi</th>
<th field="download" width="20" align="center">Download</th>
</tr>
</thead>
</table>
<div id="toolbar">
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newKey()">Buat Key</a>
<div style="float: right; margin-right: 5px;">
	<a href="#" class="easyui-linkbutton" plain="true" iconCls="icon-search" onclick="javascript:$('#dls').dialog('open')">Pencarian</a>
</div>
</div>
</div>

<div id="dls" class="easyui-dialog" style="width:450px;height:240px;padding:10px 20px"
closed="true" buttons="#dls-buttons" title="Pencarian Data Pengguna">
<div class="ftitle">Pencarian Data Key Aplikasi</div>
<form id="fms" method="post">
<table cellpadding="5">
<tr>
	<td>Nama User</td>
	<td>: <input class="easyui-textbox" type="text" name="nama_search" id="nama_search" size="25"></input></td>
</tr>
<tr>
<td>Nama SKPD</td>
<td>: 
<input class="easyui-combobox" style="width:250px;" id="id_unit_search" name="id_unit_search"/>
<script>
$('#id_unit_search').combobox({
    url:'./model/cb_sub2_unit.php',
    valueField:'id',
    textField:'text',
    filter: function(q, row){
		var opts = $(this).combobox('options');
		return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
	}
});
</script></td>
</tr>
</table>
</form>
</div>
<div id="dls-buttons">
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="doSearch()" style="width:90px">Cari</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dls').dialog('close')" style="width:90px">Batal</a>
</div>


<script type="text/javascript">
function doSearch(){
	$('#dg').datagrid('load',{
		id_sub: $('#id_unit_search').combobox('getValue'),
		nama: $('#nama_search').val()
	});
}

function newKey(){
	var dg = $('#dg').datagrid('getSelected');
	if (dg){
		$.messager.confirm('Peringatan','Buat Key untuk User ini?',function(r){
		if (r){
			$.ajax({
				type: "POST",
				url: './aksi.php?module=key_aplikasi&oper=add',
				data: { basket : dg },
				success: function(result){
					var result = eval('('+result+')');
					if (result.success==false) $.messager.show({ title: 'Error', msg: result.pesan });	
					else $.messager.show({ title: 'Success', msg: result.pesan });	
					$('#dg').datagrid('reload');			
				}
			});	
			}
		},'json');
	}else $.messager.alert('Peringatan','Pilih User yang akan dibuat key !');	
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

