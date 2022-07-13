<script src="../config/ckeditor/ckeditor.js" type="text/javascript"></script>
<div id="dlg" class="easyui-dialog" style="width:800px;height:500px;padding:5px 10px"
closed="true" buttons="#dlg-buttons" data-options="onClose:function(){$('#dld').dialog('close')}">
				 <form id='fm' method='POST' enctype='multipart/form-data'> 
					<input type='text' name='oper' id='oper'  hidden />
					<input type='text' name='id_pengumuman' id='id_pengumuman'  hidden />
					<table align='center' width='90%'>   				
						<tr>
							<td colspan='2'>  
								<input class='easyui-textbox' name='perihal' id='perihal' style='height:25px;width:100%' prompt='Perihal...' />
							</td>
						</tr>				
						<tr>
							<td colspan='2'>
								<br>
								<textarea name="isi" id="isi" ></textarea> 
								<input type='text' name='pengumuman' id='pengumuman' hidden />
								<script type="text/javascript"> 
								CKEDITOR.replace( 'isi', { 
								
								}); 
								</script>
							
							</td>
						</tr>
						<tr>
							<td width='250px'><br><input type='text' class='easyui-filebox' name='datafile' id='datafile' style='width:100%;height:25px' prompt='Lampiran...' /> </td>
							<td><br><i style='font-size:12px'>( Lampirkan file berupa gambar/ dokumen jika diperlukan )</i></td>
						</tr> 
					</table>	
				 </form> 
</div>
<div id="dlg-buttons">
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveData()" style="width:90px">Save</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
</div>

<script type="text/javascript"> 
function newPengumuman(){
	$('#dlg').dialog('open').dialog('setTitle','Tambah Pengumuman'); 
	$('#oper').val('add');
} 
function saveData(){  
		$("#pengumuman").val(CKEDITOR.instances.isi.getData());
		$('#fm').form('submit',{
		url: './aksi.php?module=pengumuman',
		onSubmit: function(){
		return $(this).form('validate');
		},
		success: function(result){
		//alert(result);
		var result = eval('('+result+')');
		if (result.success==false){
			$.messager.alert('Error',result.pesan);
		} else {
			$.messager.alert('Sukses',result.pesan);
		
			$('#fm').form('clear'); 
			$('#dg').datagrid('reload');	
			CKEDITOR.instances['isi'].setData(''); 
			$('#oper').val('add');			
			$('#dlg').dialog('close')	
		} 
		}
		});
}
function editPengumuman(){
	$('#dlg').dialog('open').dialog('setTitle','Tambah Pengumuman');  
	var row = $('#dg').datagrid('getSelected'); 
	 
	$('#perihal').textbox('setValue',row.perihal);
	CKEDITOR.instances['isi'].setData(row.isi);
	$("#pengumuman").val(row.isi); 
	$("#id_pengumuman").val(row.id_pengumuman); 
	$("#oper").val('edit'); 
}
	function destroyPengumuman(){
			var rw1 = $('#dg').datagrid('getSelected');
			var idtPD = rw1.id_pengumuman;
			if (rw1){
				$.messager.confirm('Peringatan','Apakah Anda yakin akan menghapus data ini?',function(r){
				if (r){
					$.ajax({
						type: "POST",
						url: './aksi.php?module=pengumuman&oper=del',
						data: {id_hapus :idtPD},
						success: function(data){
							$.messager.show({
							title: 'Konfirmasi',
							msg: data
							});	
							$('#dg').datagrid('reload');
						}
					});	
					}
				},'json');
			}else $.messager.alert('Peringatan','Pilih Badan Usaha yang akan dihapus dahulu !');	
	}
</script>
 <div class="dtabel" style="width:100%;height:100%;background:white">	
 <table id="dg" class="easyui-datagrid"
	url="./model/pengumuman.php" fit="true"
	toolbar="#toolbar" pagination="true" title="Input Pengumuman"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
<th field="perihal" width="200" align="left" halign="center">Perihal</th> 
<th field="tgl_entry" width="200" align="left" halign="center">Tgl Entry</th>  
</tr>
</thead>
</table>
</div>
<div id="toolbar">

<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newPengumuman()">New Pengumuman</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editPengumuman()">Edit Pengumuman</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyPengumuman()">Remove Pengumuman</a>
<div style="float: right; margin-right: 5px;">
	<a href="#" class="easyui-linkbutton" plain="true" iconCls="icon-search" onclick="viewSearch()">Pencarian</a>
</div>
</div>
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

