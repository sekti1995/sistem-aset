<div id="p" class="easyui-panel" title="File DPA & DPPD" 
        style="width:auto;height:600px;padding:10px;background:#fafafa;"
		 data-options="footer:'#ft'">

 <table id="dg" class="easyui-datagrid" style="width:700px;height:350px;">
<thead>
<tr>
<th field="nama_file" width="30">Nama File</th>
<th field="tahun" width="10" align="center">Tahun</th>
<th field="time_upload" width="20" align="center">Waktu Upload</th>
</tr>
</thead>
</table>
<div id="toolbar">
	<div style="float: right;">
		<input class="easyui-combobox" style="width:200px;" id="tahun_cari" name="tahun_cari" data-options=" prompt:'Pilih Tahun Anggaran...'"/>
			<script>
			$('#tahun_cari').combobox({
				url:'./model/cb_tahun.php',
				valueField:'id',
				textField:'text',
				filter: function(q, row){
					var opts = $(this).combobox('options');
					return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
				}
			});
			</script>
	<a href="#" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="doCari()">Cari</a>
	</div>
	
	<div style="float: left;">
	<?php if($peran==md5('1')){ ?>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="tambahFile()">Tambah</a>
	<?php } ?>
	</div>
	<div style="clear:both"></div>
</div>
<div id="dlg" class="easyui-dialog" style="width:450px;height:200px;padding:10px 20px"
closed="true" buttons="#dlg-buttons"> 
<form id="fmf" method="post" enctype="multipart/form-data">
	<div class="ftitle">Tambah File </div>
	<div class="fitem">
		<label>Tahun Anggaran</label>: 
		<input class="easyui-combobox" style="width:100px;" id="tahun" name="tahun" required="true"/>
		<script>
		$('#tahun').combobox({
			url:'./model/cb_tahun.php',
			valueField:'id',
			textField:'text',
			filter: function(q, row){
				var opts = $(this).combobox('options');
				return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
			}
		});
		</script>
	</div>
	<div class="fitem">
		<label>Nama File</label>: 
		<input class="easyui-filebox" name="file_dpa" id="file_dpa" data-options="prompt:'File DPA & DPPD...', buttonText:'PIlih File'" style="width:200px" required="true">
	</div>
</form>
</div>
<div id="dlg-buttons">
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveFile()" style="width:90px">Save</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
</div>

<script>
var urlu;
function tambahFile(){
	$('#dlg').dialog('open').dialog('setTitle','Tambah File');
	$('#fm').form('clear');
	urlu = './aksi.php?module=file_dpa_dppd&oper=add';
}

function saveFile(){
	$('#fmf').form('submit',{
		url: urlu,
		mimeType:"multipart/form-data",
		onSubmit: function(){
		return $(this).form('validate');
		},
		success: function(result){
			var result = eval('('+result+')');
			if (result.success==false){
				$.messager.show({ title: 'Error', msg: result.pesan });
			} else {
				$.messager.show({ title: 'Sukses', msg: result.pesan }); 
			}
			$('#dlg').dialog('close');
			$('#dg').datagrid('reload');	
		}
	});
}

$(function(){
		urls = "./model/dd_file_dpa_dppd.php";
		$('#dg').datagrid({
			singleSelect:true,
			fitColumns:true,
			pagination:true,
			rownumbers:true,
			toolbar:'#toolbar',
			url:urls
		});
});	

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
	margin-bottom:10px;
	}
	.fitem label{
	display:inline-block;
	width:120px;
	}
	.fitem input{
	width:160px;
	}
</style>	
</div>