<div id="p" class="easyui-panel" title="Ruang File " 
        style="width:auto;height:300px;padding:10px;background:#fafafa;"
		 data-options="footer:'#ft'">

<!-- Form Data -->		 
<div class="ftitle">Data Kegiatan <span id="t_jenis"></span></div>
<form id="fmf" method="post" enctype="multipart/form-data">
	<input type="hidden" id="id_jenis" name="id_jenis"/>
	<div class="fitem">
		<label>Urusan</label>: 
		<input class="easyui-combobox" style="width:300px;" id="id_urusan" name="id_urusan" required="true"/>
		<script>
		$('#id_urusan').combobox({
			url:'./model/cb_urusan.php',
			valueField:'id',
			textField:'text',
			filter: function(q, row){
				var opts = $(this).combobox('options');
				return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
			},
			onSelect: function(rec){
				$('#id_bidang').combobox('clear');
				$('#id_unit').combobox('clear');
				$('#id_sub_unit').combobox('clear');
				$('#id_bidang').combobox('reload','./model/cb_bidang.php?id_urusan='+rec.id);
			} 
		});
		</script>
	</div>
	<div class="fitem">
		<label>Bidang</label>: 
		<input class="easyui-combobox" style="width:300px;" id="id_bidang" name="id_bidang" required="true"/>
		<script>
		$('#id_bidang').combobox({
			url:'./model/cb_bidang.php',
			valueField:'id',
			textField:'text',
			filter: function(q, row){
				var opts = $(this).combobox('options');
				return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
			},
			onSelect: function(rec){
				$('#id_unit').combobox('clear');
				$('#id_sub_unit').combobox('clear');
				$('#id_unit').combobox('reload','./model/cb_unit.php?id_bidang='+rec.id);
			}
		});
		</script>
	</div>
	<div class="fitem">
		<label>Unit / Perangkat Daerah</label>: 
		<input class="easyui-combobox" style="width:300px;" id="id_unit" name="id_unit" required="true"/>
		<script>
		$('#id_unit').combobox({
			url:'./model/cb_unit.php',
			valueField:'id',
			textField:'text',
			filter: function(q, row){
				var opts = $(this).combobox('options');
				return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
			},
			onSelect: function(rec){
				$('#id_sub_unit').combobox('clear');
				$('#id_sub_unit').combobox('reload','./model/cb_sub_unit.php?id_unit='+rec.id);
			}
		});
		</script>
	</div>
	<div class="fitem">
		<label>Sub Unit</label>: 
		<input class="easyui-combobox" style="width:300px;" id="id_sub_unit" name="id_sub_unit" required="true"/>
		<script>
		$('#id_sub_unit').combobox({
			url:'./model/cb_sub_unit.php',
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
		<input class="easyui-filebox" name="file_export" id="file_export" data-options="prompt:'File Hasil Export...', buttonText:'PIlih File'" style="width:300px" required="true">
	</div>
</form>
<div style="text-align: center; width: auto; margin-top: 20px;">
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-save" onclick="saveFile()" style="width:150px">Simpan</a>	
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#fmf').form('clear')" style="width:150px">Batal</a>
</div>
</div>

<!-- TABEL DATA -->
 <table id="dg" class="easyui-datagrid" style="width:auto;height:300px;">
<thead>
<tr>
<th field="nama_file" width="30">Nama File</th> 
<th field="Nm_Sub_Unit" width="35">Sub Unit</th> 
<th field="status" width="15" align="center">Status</th> 
<th field="time_upload" width="20" align="center">Waktu Upload</th> 
<th field="time_download" width="20" align="center">Waktu Download</th> 
<th field="time_respon" width="20" align="center">Waktu Respon</th> 
</tr>
</thead>
</table>
<div id="toolbar" style="padding-right:15px; padding-left:15px;">
	<div style="float: left;">
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
	
	<div style="float: right;">
	<?php if($peran==md5('1')){ ?>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="responFile()">Respon</a>
	<?php } ?>
	</div>
	<div style="clear:both"></div>
</div>


<div id="dlg" class="easyui-dialog" style="width:500px;height:280px;padding:10px 20px"
closed="true" buttons="#dlg-buttons"> 
<form id="fm" method="post">
 <table cellpadding="5">
<tr>
<td>Status :</td>
<td><input class="easyui-combobox" id="status" name="state" style="width:150px;" data-options="
		valueField: 'id',
		textField: 'text',
		data: [{
			id: '1',
			text: 'Proses'
		},{
			id: '2',
			text: 'Sukses'
		},{
			id: '3',
			text: 'Gagal'
		}]" required="true"/>
	<script>
	$('#status').combobox({
		onSelect: function(rec){
			if(rec.id==3) $('#uraian').textbox('readonly',false);
			else{
				$('#uraian').textbox('setValue', '');
				$('#uraian').textbox('readonly',true);
			}	
		}
	});
	</script>	
</td>
</tr>
<tr>
<td>Uraian :</td>
<td>
<input class="easyui-textbox" name="uraian" id="uraian" data-options="multiline:true" style="width:300px; height:100px" readonly></td>
</tr>
</table>
</form>
</div>
<div id="dlg-buttons">
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveRespon()" style="width:90px">Save</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
</div>

<script type="text/javascript">
$dataLoader = { imgUrl: 'images/ajaxloader.gif' };
var urlu;
function responFile(){
	var row = $('#dg').datagrid('getSelected'); 
	if (row){
		$('#dlg').dialog('open').dialog('setTitle','Respon');
		$('#fm').form('clear');
		//$('#fm').form('load',row);
		urlu = './aksi.php?module=upload_file&oper=respon&id='+row.id;
		$('#uraian').textbox('readonly',true);
	}else{
		$.messager.show({ title: 'Error',	msg: 'Pilih Data file yang ingin direspon!' }); 
	}
}
function saveRespon(){
	$('#fm').form('submit',{
		url: urlu,
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
			var result = eval('('+result+')');
			if (result.success==false){
				$.messager.show({ title: 'Error', msg: result.pesan });
			} else {
				$.messager.show({ title: 'Sukses',	msg: result.pesan }); 
			}
			$('#dlg').dialog('close');
			$('#dg').datagrid('reload');
		}
	});
}

function saveFile(){
	$('#fmf').form('submit',{
		url: './aksi.php?module=upload_file&oper=add',
		mimeType:"multipart/form-data",
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
			//alert(result);
			var result = eval('('+result+')');
			if (result.success==false){
				$.messager.show({ title: 'Error', msg: result.pesan	});
			} else {
				$.messager.show({ title: 'Sukses', msg: result.pesan }); 
			}
			$('#dg').datagrid('reload');	
		}
	});
}

function GetURLParameter(sParam){
	var sPageURL = window.location.search.substring(1);
	var sURLVariables = sPageURL.split('&');
	for (var i = 0; i < sURLVariables.length; i++) 
	{
		var sParameterName = sURLVariables[i].split('=');
		if (sParameterName[0] == sParam) 
		{
			return sParameterName[1];
		}
	}
}
var jenis = GetURLParameter('jenis');
$(function(){
	$.post("./model/menu_file.php", {jenis: jenis <?php if($peran!=md5('1')) echo " ,cek:'user'"; ?>}, function(result){
        var result = eval('('+result+')');
		$("#id_jenis").val(result.id);
		$("#t_jenis").html(result.nama);
		urls = "./model/dd_file_upload.php?jenis="+result.id;
		$('#dg').datagrid({
			singleSelect:true,
			fitColumns:true,
			pagination:true,
			rownumbers:true,
			toolbar:'#toolbar',
			url:urls
		});
		<?php if($peran!=md5('1')){ ?>
		$('#id_urusan').combobox('setValue', result.id_ur);
		$('#id_bidang').combobox('setValue', result.id_bid);
		$('#id_unit').combobox('setValue', result.id_unit);
		$('#id_sub_unit').combobox('setValue', result.id_sub);
		
		$("#id_urusan,#id_bidang,#id_unit,#id_sub_unit").each(function(){
			$(this).combobox('readonly', true);
			$(this).combobox('textbox').css('background-color','#EEEEEE');
		});
		<?php } ?>
    });
});	


function doCari(){
	$('#dg').datagrid('load',{
		tahun: $('#tahun_cari').combobox('getValue')
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
	width:200px;
	}
	.fitem input{
	width:160px;
	}
	.mc{
    background-color: #EEEEEE;
  }
</style>	
</div>