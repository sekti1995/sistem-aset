 <div class="dtabel" style="width:100%;height:100%;background:white">	
 <table id="dgfull"class="easyui-datagrid" 
	url="./model/dd_barang.php"
	toolbar="#toolbar" pagination="true"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
<th field="nama_jenis" width="150" align="left"  halign="center">Jenis Persediaan</th>
<th field="kd_sub2" width="50" align="center"  halign="center">Kode Barang</th>
<th field="nama_barang" width="150" align="left"  halign="center">Nama Barang</th>
<th field="nama_satuan" width="60" align="left"  halign="center">Nama Satuan</th>
<th field="harga_index" width="60" align="right"  halign="center">Index Harga</th>
<th field="keterangan" width="200" align="left"  halign="center">Spesifikasi</th>
</tr>
</thead>
</table>
</div>
<div id="toolbar"> 
	<div>
		<form id="fm" method="post" enctype="multipart/form-data">
			<table cellpadding="5">
			<tr>
				<td>Jenis Barang</td>
				<td>: 
					<input class="easyui-combobox" style="width:250px;height:25px" id="jenis_barang" name="jenis_barang"  required="true"/>
					<script>
					$('#jenis_barang').combobox({
						url:'./model/cb_jenis.php',
						valueField:'id',
						textField:'text',
						filter: function(q, row){
							var opts = $(this).combobox('options');
							return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
						} 
					});
					</script>
				</td> 
				<td>File Data Barang Persediaan</td>
				<td>
					: <input class="easyui-filebox" type="text" name="file_awal" id="file_awal" style="width:200px;height:25px"></input>
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
		url: './import_barang.php',
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
