<?php $peran = cekLogin(); ?>
<fieldset>
	<legend>
		Papan Informasi
	</legend>
<?php if($peran==md5('1')){ ?>
	<form id="fIn" method="post" enctype="multipart/form-data">
		<textarea name="txt_informasi" id="txt_informasi" cols='60' rows='4'></textarea>
		<input type="hidden" name="oper" value="informasi">
		</form><br>
		<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveInformasi()" style="width:90px">Save</a>	
<?php }else{ ?>
<div id="isi_informasi" style="width: auto; font-size: 16px;"></div>
<?php } ?>
<script type="text/javascript">
tinymce.init({selector: "textarea",width:"850px",height:"270px", theme: "modern",
					plugins: [
						 "charmap","advlist autolink link image lists charmap print preview hr anchor pagebreak",
						 "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
						 "table contextmenu directionality emoticons paste textcolor filemanager code "
				   ],
				   toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
				   toolbar2: "| filemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
				   image_advtab: true 
				});

$(function() {
	var u = './model/fm_informasi.php';
	$.post(u, function(data) {
		<?php if($peran==md5('1')){ ?>
		$(tinymce.get('txt_informasi').getBody()).html(data);
		<?php }else{ ?>
		$('#isi_informasi').html(data);
		<?php } ?>
	}, "json");	
});

$dataLoader = { imgUrl: 'images/ajaxloader.gif' };
function saveInformasi(){
	$('#fIn').form('submit',{
		url: './aksi.php?module=informasi',
		onSubmit: function(){
		return $(this).form('validate');
		},
		beforeSend: function() {
			$.loader.open($dataLoader);
		},
		complete: function(){
			 $.loader.close($dataLoader);
		},
		success: function(result){
			alert(result);
			var result = eval('('+result+')');
			if (result.success==false){ 
				$.messager.show({ title: 'Error', msg: result.pesan	});
			} else {
				$.messager.show({ title: 'Sukses', msg: result.pesan }); 
			}	
		}
	});	
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

