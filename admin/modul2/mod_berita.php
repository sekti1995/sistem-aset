<?php $peran = cekLogin(); ?>
<fieldset>
	<legend>
		Daftar Berita
	</legend>
 <table id="dg" class="easyui-datagrid" style="width:700px;height:400px" 
	url="./model/dd_berita.php"
	toolbar="#toolbar" pagination="true"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
<th field="nama_berita" width="95">Judul Berita</th> 
</tr>
</thead>
</table>
<div id="toolbar">
<?php if($peran==md5('1')){ ?>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newBerita()">New Berita</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editBerita()">Edit Berita</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyBerita()">Remove Berita</a>
<?php }else{ ?>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-view" plain="true" onclick="lihatBerita()">LIhat Berita</a>
<?php } ?>	
</div>
<div id="dlg" class="easyui-dialog" style="width:950px;height:580px;padding:10px 20px"
closed="true" buttons="#dlg-buttons"> 
<form id="fm" method="post" enctype="multipart/form-data">
 <table cellpadding="5">
<input type="hidden" name="id_berita"></input>
<tr>
<td>Judul :</td>
<td><input class="easyui-textbox" type="text" name="nama_berita" id="nama_berita" data-options="required:true" style='width:95%'></input></td>
</tr>
<tr>
<td>Deskripsi :</td>
<td> <textarea name="deskripsi_berita" id="deskripsi_berita" cols='60' rows='4'></textarea> </td>
</tr>
<tr>
<td>Gambar :</td>
<td>
<input class="easyui-filebox" name="gambar" data-options="prompt:'Pilih sebuah gambar ...'" style="width:350px"></td>
</tr>
</table>
</form>
</div>
<div id="dlg-buttons">
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveBerita()" style="width:90px">Save</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
</div>
<div id="dlv" class="easyui-dialog" style="width:950px;height:580px;padding:10px 20px"
closed="true">
<div id="judul_berita" style="width: auto; text-align:center; font-size: 18px;"></div>
<div id="isi_berita" style="width: auto;"></div>
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
function newBerita(){
	$('#dlg').dialog('open').dialog('setTitle','Tambah Berita');
	tinyMCE.activeEditor.setContent('');
	$('#fm').form('clear');
	urlu = './aksi.php?module=berita&oper=add';
}
function editBerita(){
	var row = $('#dg').datagrid('getSelected'); 
	if (row){
		$('#dlg').dialog('open').dialog('setTitle','Edit Berita');
		$('#fm').form('clear');
		tinyMCE.activeEditor.setContent(row.deskripsi_berita);
		$('#fm').form('load',row);
		urlu = './aksi.php?module=berita&oper=edit';
	}
}
function lihatBerita(){
	var row = $('#dg').datagrid('getSelected'); 
	if (row){
		$('#dlv').dialog('open').dialog('setTitle','Lihat Berita');
		$('#judul_berita').html(row.nama_berita);
		$('#isi_berita').html(row.deskripsi_berita);
	}
}
function saveBerita(){
	$('#fm').form('submit',{
		url: urlu,
		onSubmit: function(){
		return $(this).form('validate');
		},
		success: function(result){
			//alert(result);
			var result = eval('('+result+')');
			if (result.success==false){
				$.messager.show({
				title: 'Error',
				msg: result.pesan
				});
			} else {
				$.messager.show({
				title: 'Sukses',
				msg: result.pesan
				}); 
			}
			$('#dlg').dialog('close');
			$('#dg').datagrid('reload');	
		}
	});
}
function destroyBerita(){
		var rw1 = $('#dg').datagrid('getSelected');
    	var idtPD = rw1.id_berita;
		var idor = rw1.order_berita;
		if (rw1){
			$.messager.confirm('Peringatan','Apakah Anda yakin akan menghapus Berita ini?',function(r){
			if (r){
				$.ajax({
					type: "POST",
					url: './aksi.php?module=berita&oper=del',
					data: {id_hapus :idtPD, id_order : idor},
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
		}else $.messager.alert('Peringatan','Pilih Berita yang akan dihapus dahulu !');	
}

function doOrder(type){
	var row = $('#dg').datagrid('getSelected');
	if (row){	
		var id = row.order_berita;
		$.ajax({
			type: "POST",
			url: './aksi.php?module=order',
			data: { type: type, id : id, tabel : 'berita'},
			success: function(result){
				var result = eval('('+result+')');
				if (result.success==false){
					$.messager.show({ title: 'Error', msg: result.pesan });
				}else{
					$.messager.show({ title: 'Sukses', msg: result.pesan });
					$('#dg').datagrid('reload');	
				}
			}
		});	
	}else{
		$.messager.show({ title: 'Error',msg: "Pilih Berita UMKM dahulu !" });
	}
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

