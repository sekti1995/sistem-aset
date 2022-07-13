<div id="hpanel" class="easyui-panel" title="Daftar Jenis Persediaan" 
        style="width:auto;padding:10px;background:#fafafa;">
	<?php $h = 125; $i = 25; $r = 10; $hei = $h+($i*$r); ?>
 <table id="dg"class="easyui-datagrid" style="width:520px;height:<?php echo $hei; ?>px"
	url="./model/dd_jenis_barang.php"
	toolbar="#toolbar" pagination="true"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
	<th field="kd_jenis" width="10" align="left" halign="center">Kode Jenis</th>
	<th field="nama_jenis" width="30" align="left" halign="center">Nama Jenis</th>
</tr>
</thead>
</table>
<div id="toolbar">
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newJenis()">New Jenis</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editJenis()">Edit Jenis</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyJenis()">Remove Jenis</a>
</div>

<div id="dlg" class="easyui-dialog" style="width:440px;height:250px;padding:10px 20px"
closed="true" buttons="#dlg-buttons">
<div class="ftitle">Informasi Jenis Barang</div>
<form id="fm" method="post">
<table cellpadding="5">
<tr>
	<td>Kode</td>
	<td>: <input class="easyui-textbox" type="text" name="kd_kel" id="kd_kel" data-options="required:true" size="2"></input>
	<input class="easyui-textbox" type="text" name="kd_sub" id="kd_sub" data-options="required:true" size="2"></input></td>
</tr>
<tr>
	<td>Nama</td>
	<td>: <input class="easyui-textbox" type="text" name="nama_jenis" id="nama_jenis" data-options="required:true" size="25"></input></td>
</tr>
</table>
</form>
</div>
<div id="dlg-buttons">
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveJenis()" style="width:90px">Save</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
</div>
</div>
<script type="text/javascript">

$(function(){
		//pageSize="<?php echo $r; ?>" pageList="[<?php echo $r; ?>,100,400]"
        $('#dg').datagrid({
            pageSize:<?php echo $r; ?>,
			pageList:[<?php echo $r; ?>,100,400]
			
        });
});

var urlu;
function newJenis(){
	
	$('#dlg').dialog('open').dialog('setTitle','Tambah Jenis');
	$('#fm').form('clear');
	urlu = './aksi.php?module=jenis_barang&oper=add';
}
function editJenis(){
	
	var row = $('#dg').datagrid('getSelected');
	if (row){
		$('#dlg').dialog('open').dialog('setTitle','Edit Jenis');
		$('#fm').form('clear');
		$('#fm').form('load',row);
		//alert('Tes');
		urlu = './aksi.php?module=jenis_barang&oper=edit&id_ubah='+row.id;
	}
}
function saveJenis(){
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
function destroyJenis(){
	var rw1 = $('#dg').datagrid('getSelected');
	if (rw1){
		$.messager.confirm('Peringatan','Apakah Anda yakin akan menghapus Jenis Barang ini?',function(r){
		if (r){
			$.ajax({
				type: "POST",
				url: './aksi.php?module=jenis_barang&oper=del',
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