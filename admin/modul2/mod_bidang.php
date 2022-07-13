<div id="hpanel" class="easyui-panel" title="Daftar Bidang" 
        style="width:auto;padding:10px;background:#fafafa;">
 <table id="dg"class="easyui-datagrid" style="width:520px;height:350px"
	url="./model/dd_bidang.php"
	toolbar="#toolbar" pagination="true"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
	<th field="nm_urusan" width="15" align="left">Urusan</th>
	<th field="kd_bidang" width="10" align="center">Kode Bidang </th>
	<th field="nm_bidang" width="25" align="left">Nama Bidang</th>
</tr>
</thead>
</table>
<div id="toolbar">
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newBidang()">New Bidang</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editBidang()">Edit Bidang</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyBidang()">Remove Bidang</a>
</div>

<div id="dlg" class="easyui-dialog" style="width:420px;height:300px;padding:10px 20px"
closed="true" buttons="#dlg-buttons">
<div class="ftitle">Informasi Bidang </div>
<form id="fm" method="post">
<table cellpadding="5">
<tr>
	<td>Urusan</td>
	<td>: 
	<input class="easyui-combobox" style="width:120px;" id="kd_urusan" name="kd_urusan" required="true"/>
	<script>
	$('#kd_urusan').combobox({
		url:'./model/cb_urusan.php',
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
	<td>Kode</td>
	<td>: <input class="easyui-textbox" type="text" name="kd_bidang" id="kd_bidang" data-options="required:true" size="3"></input></td>
</tr>
<tr>
	<td>Nama</td>
	<td>: <input class="easyui-textbox" type="text" name="nm_bidang" id="nm_bidang" data-options="required:true" size="25"></input></td>
</tr>
</table>
</form>
</div>
<div id="dlg-buttons">
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveBidang()" style="width:90px">Save</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
</div>
</div>
<script type="text/javascript">
var urlu;
function newBidang(){
	
	$('#dlg').dialog('open').dialog('setTitle','Tambah Bidang');
	$('#fm').form('clear');
	urlu = './aksi.php?module=bidang&oper=add';
}
function editBidang(){
	
	var row = $('#dg').datagrid('getSelected');
	if (row){
		$('#dlg').dialog('open').dialog('setTitle','Edit Bidang');
		$('#fm').form('clear');
		$('#fm').form('load',row);
		//alert('Tes');
		urlu = './aksi.php?module=bidang&oper=edit&id_ubah='+row.id;
	}
}
function saveBidang(){
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
function destroyBidang(){
	var rw1 = $('#dg').datagrid('getSelected');
	if (rw1){
		$.messager.confirm('Peringatan','Apakah Anda yakin akan menghapus Bidang  ini?',function(r){
		if (r){
			$.ajax({
				type: "POST",
				url: './aksi.php?module=bidang&oper=del',
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