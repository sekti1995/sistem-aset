<div id="hpanel" class="easyui-panel" title="Ganti Password" 
        style="width:auto;padding:10px;background:#fafafa;">
	<form id="fmj" method="post">
		<table>
		<tr>
		<td>Password Lama</td>
		<td>: <input class="easyui-validatebox" type="password" name="pass_lama" id="pass_lama" size="18px" required="true"></input></td>
		<td><input class="easyui-validatebox" type="hidden" name="pass_lamaa" id="pass_lamaa" size="18px" required="true"></input></td>
		</tr>
		<tr>
		<td>Password Baru </td>
		<td>: <input class="easyui-validatebox" type="password" id="pass_baru" name="pass_baru" size="18px" required="true" /></td>
		<td><input class="easyui-validatebox" type="hidden" id="pass_baruu" name="pass_baruu" size="18px" required="true" /></td>
		</tr>
		<tr>
		<td>Password Baru Sekali Lagi </td>
		<td>: <input class="easyui-validatebox" type="password" name="pass_baru2" id="pass_baru2" size="18px" required="true"></input></td>
		<td><input class="easyui-validatebox" type="hidden" name="pass_baruu2" id="pass_baruu2" size="18px" required="true"></input></td>
		</tr>
		</table></br>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="savePass()" style="width:90px">Simpan</a>
</form>
</div>
<script type="text/javascript">
function savePass(){
	var pass_lama = $('#pass_lama').val();
	var pass_lamaa = $('#pass_lamaa').val();
	var pass_baru = $('#pass_baru').val();
	var pass_baruu = $('#pass_baruu').val();
	var pass_baru2 = $('#pass_baru2').val();
	var pass_baruu2 = $('#pass_baruu2').val();
	if(pass_lama!=pass_lamaa){
		$('#pass_lama').val($.MD5($('#pass_lama').val()));
		$('#pass_lamaa').val($.MD5($('#pass_lama').val()));
	}
	if(pass_baru!=pass_baruu){
		$('#pass_baru').val($.MD5($('#pass_baru').val()));
		$('#pass_baruu').val($.MD5($('#pass_baru').val()));
	}
	if(pass_baru2!=pass_baruu2){
		$('#pass_baru2').val($.MD5($('#pass_baru2').val()));
		$('#pass_baruu2').val($.MD5($('#pass_baru2').val()));
	}
	if(pass_baru!=pass_baru2){
		alert('Password Baru tidak sama!');
		$('#pass_baru').val('');
		$('#pass_baru2').val('');
	}else{
		$('#fmj').form('submit',{
			url: './aksi.php?module=ganti_password',
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
					$('#pass_lama').val('');
				} else {
					$.messager.show({
					title: 'Sukses',
					msg: result.pesan
					}); 
					$('#fmj').form('clear');
				}
			}
		});
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