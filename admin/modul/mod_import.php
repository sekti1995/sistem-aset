 <div class="dtabel" style="width:100%;height:100%;background:white">	
 <table id="dgfull"class="easyui-datagrid"
	url="./model/log_import.php" fit="true" showFooter="true"
	toolbar="#toolbar" title="Import Data Persediaan"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
<th field="nm_sub2_unit" width="70" >OPD</th>
<th field="timestamp" width="70" align="center">Tanggal Proses</th>
<th field="result" width="70" align="center" halign="center">Hasil</th>
<th field="total_pengadaan" width="40" align="right" halign="center">Total</th>
</tr>
</thead>
</table>
</div>
<div id="toolbar"> 
	<div>
		<form id="fm" method="post" enctype="multipart/form-data">
			<table cellpadding="5">
			<?php if($_SESSION['level']!=md5('c')){ ?>
			<tr>
			<td>Nama Sub Unit</td>
			<td>: 
				<input class="easyui-combobox" style="width:250px;" id="id_sub" name="id_sub"  required="true"/>
				<script>
				$('#id_sub').combobox({
					url:'./model/cb_sub2_unit.php',
					valueField:'id',
					textField:'text',
					filter: function(q, row){
						var opts = $(this).combobox('options');
						return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
					},
					onSelect: function(rec){
						$('#id_sumber').combobox('clear');
						$('#id_sumber').combobox('reload', './model/cb_sumber_dana.php?cek&id='+rec.id);
					}
				});
				</script>
			</td> 
			<td>Sumber Dana</td>
			<td>: 
				<input class="easyui-combobox" style="width:150px;" id="id_sumber_dana" name="id_sumber_dana"  required="true"/>
				<script>
				$('#id_sumber_dana').combobox({
					url:'./model/cb_sumber_dana.php',
					valueField:'id',
					textField:'text',
					filter: function(q, row){
						var opts = $(this).combobox('options');
						return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
					}
				});
				</script>
			</td> 
			<!--
			<td>Data</td>
			<td>: 
				<input class="easyui-combobox" style="width:150px;" id="data" name="data"  required="true"/>
				<script>
				$('#data').combobox({
					valueField:'id',
					textField:'text',
					data:[{"id":1,"text":"Pengadaan"},{"id":2,"text":"Pengeluaran"},{"id":3,"text":"Pengadaan & Pengeluaran"}]
				});
				</script>
			</td> 
			-->
			<?php } ?>  
				<td>File Persediaan</td>
				<td>
					: <input class="easyui-filebox" type="text" name="file_awal" id="file_awal" style="width:200px;"></input>
				</td> 
				<td>
					<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="importDataPersediaan()" id="saveCek" style="width:90px">Upload</a>
				</td> 
			</tr>	
			</table> 
		</form>
	</div>
	<div style="clear:both"></div>
</div>

<script type="text/javascript">
function importDataPersediaan(){
	//alert('asd');
	$('#fm').form('submit',{
		url: './import.php',
		onSubmit: function(){
			if($(this).form('validate')==true && validasiCombo('fm')==true){
				$.loader.open($dataLoader);
				return true;
			}else{
				return false;
			}		
		},
		success: function(result){
			$('#dgfull').datagrid('reload');
			$.loader.close($dataLoader);
			var result = eval('('+result+')');
			console.log(result);
			$.messager.alert('Sukses',result.pesan );
		}
	});
}
</script>
<style type="text/css">
	#fm{
	margin:0;
	padding:5px 10px;
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
