<?php $peran = cekLogin(); ?>
<fieldset>
	<legend>
		Daftar Undangan
	</legend>
 <table id="dg" class="easyui-datagrid" style="width:700px;height:400px" 
	url="./model/dd_undangan.php"
	toolbar="#toolbar" pagination="true"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
<th field="nama_undangan" width="95">Judul Undangan</th> 
</tr>
</thead>
</table>
<div id="toolbar">
<?php if($peran==md5('1')){ ?>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newUndangan()">New Undangan</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editUndangan()">Edit Undangan</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyUndangan()">Remove Undangan</a>
<?php }else{ ?>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-view" plain="true" onclick="lihatUndangan()">LIhat Berita</a>
<?php } ?>	
</div>
<div id="dlg" class="easyui-dialog" style="width:950px;height:580px;padding:10px 20px"
closed="true" buttons="#dlg-buttons"> 
<form id="fm" method="post" enctype="multipart/form-data">
 <table cellpadding="5">
<input type="hidden" name="id_undangan"></input>
<tr>
<td>Judul :</td>
<td><input class="easyui-textbox" type="text" name="nama_undangan" id="nama_undangan" data-options="required:true" style='width:95%'></input></td>
</tr>
<tr>
<td>Deskripsi :</td>
<td> <textarea name="deskripsi_undangan" id="deskripsi_undangan" cols='60' rows='4'></textarea> </td>
</tr>
</table>
</form>
</div>
<div id="dlg-buttons">
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveUndangan()" style="width:90px">Save</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
</div>
<div id="dlv" class="easyui-dialog" style="width:950px;height:580px;padding:10px 20px"
closed="true">
<div id="isi_undangan" style="width: auto;"></div>
</div>
<script type="text/javascript">
tinymce.init({selector: "textarea",width:"700px",height:"200px", theme: "modern",
					plugins: [
						 "charmap","advlist autolink link image lists charmap print preview hr anchor pagebreak",
						 "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
						 "table contextmenu directionality emoticons paste textcolor filemanager code "
				   ],
				   toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
				   toolbar2: "| filemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
				   image_advtab: true 
				});
var urlu;
function newUndangan(){
	$('#dlg').dialog('open').dialog('setTitle','Tambah Undangan');
	tinyMCE.activeEditor.setContent('');
	$('#fm').form('clear');
	urlu = './aksi.php?module=undangan&oper=add';
}
function editUndangan(){
	var row = $('#dg').datagrid('getSelected'); 
	if (row){
		$('#dlg').dialog('open').dialog('setTitle','Edit Undangan');
		$('#fm').form('clear');
		tinyMCE.activeEditor.setContent(row.deskripsi_undangan);
		$('#fm').form('load',row);
		urlu = './aksi.php?module=undangan&oper=edit';
	}
}
function lihatUndangan(){
	var row = $('#dg').datagrid('getSelected'); 
	if (row){
		$('#dlv').dialog('open').dialog('setTitle','Lihat Undangan');
		$('#isi_undangan').html(row.deskripsi_undangan);
	}
}
function saveUndangan(){
	$('#fm').form('submit',{
		url: urlu,
		onSubmit: function(){
		return $(this).form('validate');
		},
		success: function(result){
			var result = eval('('+result+')');
			if (result.success==false){
				$.messager.show({ title: 'Error', msg: result.pesan	});
			} else {
				$.messager.show({ title: 'Sukses', msg: result.pesan }); 
			}
			$('#dlg').dialog('close');
			$('#dg').datagrid('reload');	
		}
	});
}
function destroyUndangan(){
		var rw1 = $('#dg').datagrid('getSelected');
    	if (rw1){
			var idtPD = rw1.id_undangan;
			$.messager.confirm('Peringatan','Apakah Anda yakin akan menghapus Undangan ini?',function(r){
			if (r){
				$.ajax({
					type: "POST",
					url: './aksi.php?module=undangan&oper=del',
					data: {id_hapus :idtPD },
					success: function(data){
						$.messager.show({ title: 'Konfirmasi', msg: data });	
						$('#dg').datagrid('reload');			
					}
				});	
				}
			},'json');
		}else $.messager.alert('Peringatan','Pilih Undangan yang akan dihapus dahulu !');	
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

