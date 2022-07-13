<div id="hpanel" class="easyui-panel" title="Daftar Pejabat" 
        style="width:auto;padding:10px;background:#fafafa;">
 <table id="dg"class="easyui-datagrid" style="width:1100px;height:350px"
	url="./model/dd_pejabat.php"
	toolbar="#toolbar" pagination="true"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
<th field="nm_sub2_unit" width="200">Nama Sub Unit</th>
<th field="ta" width="40" align="center">TA</th>
<th field="nama_pejabat" width="200">Nama Pejabat</th>
<th field="nama_jabatan" width="150">Nama Jabatan</th>
<th field="nama_golongan" width="150">Nama Golongan</th>
<th field="nip" width="200">NIP</th>
</tr>
</thead>
</table>
<div id="toolbar">
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newPejabat()">New Pejabat</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editPejabat()">Edit Pejabat</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyPejabat()">Remove Pejabat</a>
        <div style="float: right;">
        <span>Cari :</span>
        <input id="cari" style="line-height:18px;border:1px solid #ccc">
        <a href="#" class="easyui-linkbutton" plain="true" onclick="doSearch()">Search</a>
        </div>
<?php //echo "=====> ".$_SESSION['peran_id']."----".$_SESSION['uidunit']."----level".$_SESSION['level']."----peserta".$_SESSION['peserta']."----".$_SESSION['kode_sub'].$_SESSION['kode_skpd'];?>

</div>
<div id="dlg" class="easyui-dialog" style="width:700px;height:500px;padding:10px 20px"
closed="true" buttons="#dlg-buttons">
<div class="ftitle">Informasi Pejabat</div>
<form id="fm" method="post">
<table cellpadding="5">
<tr>
<td>Nama Sub Unit</td>
<td>: 
<input class="easyui-combobox" style="width:350px;" id="id_sub2" name="id_sub2" <?php if($peran!=md5('1')) echo "readonly"; ?> required="true" />
<script>
$('#id_sub2').combobox({
    url:'./model/cb_sub2_unit.php?id_unit=<?php echo $_SESSION['uidunit'] ?>',
    valueField:'id',
    textField:'text',
    filter: function(q, row){
		var opts = $(this).combobox('options');
		return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
	}
});
</script></td>
</tr>
<td>TA</td>
<td>: <input id="ta" name="ta" class="easyui-textbox" required="true">
		<script>
	$('#ta').combobox({
		url:'./model/cb_tahun.php',
		valueField:'id',
		textField:'text',
		filter: function(q, row){
			var opts = $(this).combobox('options');
			return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
		}
	});
	</script></input></td>
</tr>
<td>Nama Pejabat</td>
<td>: <input class="easyui-textbox" type="text" name="nama_pejabat" id="nama_pejabat" data-options="required:true" size="35"></input></td>
</tr>
<td>Nama Jabatan</td>
<td>: 
<input class="easyui-combobox" style="width:250px;" id="id_jabatan" name="id_jabatan" required="true"/>
<script>
$('#id_jabatan').combobox({
    url:'./model/cb_jabatan.php',
    valueField:'id',
    textField:'text',
    filter: function(q, row){
		var opts = $(this).combobox('options');
		return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
	}
});
</script></td>
</tr>
<td>Nama Golongan</td>
<td>: 
<input class="easyui-combobox" style="width:250px;" id="id_golongan" name="id_golongan" required="true"/>
<script>
$('#id_golongan').combobox({
    url:'./model/cb_golongan.php',
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
<td>NIP</td>
<td>: <input class="easyui-textbox" type="text" name="nip" data-options="required:false"></input></td>
</tr>
</table>
</form>
</div>
<div id="dlg-buttons">
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="savePejabat()" style="width:90px">Save</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
</div>
</div>
<script type="text/javascript">
var urlu
function doSearch(){
        $('#dg').datagrid('load',{
            cari: $('#cari').val()
        });
}
function newPejabat(){
	$('#dlg').dialog('open').dialog('setTitle','Tambah Pejabat');
	$('#fm').form('clear');
	$('#id_sub2').combobox('reload');	
	urlu = './aksi.php?module=pejabat&oper=add';
}
function editPejabat(){
	var row = $('#dg').datagrid('getSelected');
	if (row){
		$('#dlg').dialog('open').dialog('setTitle','Edit Pejabat');
		$('#fm').form('clear');
		$('#fm').form('load',row);
		urlu = './aksi.php?module=pejabat&oper=edit&id_ubah='+row.id_pejabat;
	}
}
function savePejabat(){
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
function destroyPejabat(){
	var rw1 = $('#dg').datagrid('getSelected');
	if (rw1){
		$.messager.confirm('Peringatan','Apakah Anda yakin akan menghapus Pejabat ini?',function(r){
		if (r){
			$.ajax({
				type: "POST",
				url: './aksi.php?module=pejabat&oper=del',
				data: { id_hapus: rw1.id_pejabat },
				success: function(data){
					$.messager.show({ title: 'Konfirmasi', msg: data });	
					$('#dg').datagrid('reload');			
				}
			});	
			}
		},'json');
	}else $.messager.alert('Peringatan','Pilih Pejabat yang akan dihapus dahulu !');	
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