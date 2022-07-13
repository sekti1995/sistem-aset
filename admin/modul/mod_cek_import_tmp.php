 <div class="dtabel" style="width:100%;height:100%;background:white">	
 <table id="dgfull"class="easyui-datagrid"
	url="./model/cek_import_tmp.php" fit="true" showFooter="true"
	toolbar="#toolbar" title="Cek Import Barang Masuk"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
	<th field="k1" width="70" align="center" rowspan='2'>Terima Tgl</th>
	<th field="k2" width="50" align="left" rowspan='2'>Dari</th>
	<th width="70" align="center" colspan='2'>Dokumen/Faktur</th>
	<th width="70" align="center" colspan='2'>Dasar Penerimaan</th>
	<th field="k7" width="40" align="right" rowspan='2'>Banyaknya</th>
	<th field="k8" width="150" align="left" rowspan='2'>ID Barang</th>
	<th field="k9" width="120" align="left" rowspan='2'>Nama Barang</th>
	<th field="k10" width="70" align="right" rowspan='2'>Harga Satuan</th>
	<th field="harga_index" width="70" align="right" rowspan='2'>Harga Index</th>
	<th width="70" align="center" colspan='2'>Buku Penerimaan</th>
	<th field="k13" width="50" align="left" rowspan='2'>Ket</th>
	<th field="status" width="70" align="left" rowspan='2'>Status</th>
</tr>
<tr>
	<th field="k3" width="30" align="left" >No</th>
	<th field="k4" width="70" align="left" >Tgl</th>
	<th field="k5" width="70" align="left" >Jenis</th>
	<th field="k6" width="30" align="left" >No</th>
	<th field="k11" width="30" align="left" >No</th>
	<th field="k12" width="70" align="left" >Tgl</th>
</tr>
</thead>
</table>
</div>
<div id="toolbar"> 
	<div>
		<form id="fm" method="post" enctype="multipart/form-data">
			<input type="hidden" name="aksi" id="aksi" value="in" />
			<table cellpadding="5" width="100%">
			<tr>
			<td>Nama Sub Unit : 
				<input class="easyui-combobox" style="width:250px;" id="id_sub" name="id_sub"  required="true"/>
				<script>
				$('#id_sub').combobox({
					url:'./model/cb_sub2_unit.php',
					valueField:'id',
					textField:'text',
					filter: function(q, row){
						var opts = $(this).combobox('options');
						return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
					}
				});
				</script>
				&nbsp; 
				Sumber Dana : 
				<input class="easyui-combobox" style="width:160px;" id="id_sumber_dana" name="id_sumber_dana"  required="true"/>
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
				&nbsp; 
				SMT : 
				<input class="easyui-combobox" style="width:50px;" id="smt" name="smt"  required="true"/>
				<script>
				$('#smt').combobox({
					valueField:'id',
					textField:'text',
					panelHeight:"auto",
					data:[{"id":"1","text":"1"},{"id":"2","text":"2"}],
					filter: function(q, row){
						var opts = $(this).combobox('options');
						return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
					}
				});
				</script>
				&nbsp; 
				TA : 
				<input class="easyui-combobox" style="width:80px;" id="ta" name="ta"  required="true"/>
				<script>
				$('#ta').combobox({
					url:'./model/cb_tahun.php',
					valueField:'id',
					textField:'text',
					panelHeight:"auto",
					filter: function(q, row){
						var opts = $(this).combobox('options');
						return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
					}
				});
				</script>
				&nbsp; 
				<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-search" onclick="cari()" id="cari" style="width:90px">Cari</a>
			</td>
			<td align="right">
				<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="approve()" id="btn-save" style="width:90px">Terima</a> 
				&nbsp;
				<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-cancel" onclick="tolak()" id="btn-tolak" style="width:90px">Tolak</a>
			</td>
			</tr>			
			</table> 
		</form>
	</div>
	<div style="clear:both"></div>
</div>

<script type="text/javascript">
$('#dgfull').datagrid({
	rowStyler: function(index,row){
		if (row.hrg == 1){
			return 'background-color:#ffee00;color:red;'; // return inline style
			// the function can return predefined css class and inline style
			// return {class:'r1', style:{'color:#fff'}};	
		}
	}
});
function cari(){
	var id_sub = $("#id_sub").combobox('getValue');
	var id_sumber_dana = $("#id_sumber_dana").combobox('getValue');
	var smt = $("#smt").combobox('getValue');
	var ta = $("#ta").combobox('getValue');
	cekStatTmp();
	console.log(id_sub)
	console.log(id_sumber_dana)
	console.log(smt)
	console.log(ta)
	$('#dgfull').datagrid('load',{
									id_sub:id_sub,
									id_sumber_dana:id_sumber_dana,
									smt:smt,
									ta:ta
								 });
}

function cekStatTmp(){
	var id_sub = $("#id_sub").combobox('getValue');
	var id_sumber_dana = $("#id_sumber_dana").combobox('getValue');
	var smt = $("#smt").combobox('getValue');
	var ta = $("#ta").combobox('getValue');
	var aksi = $("#aksi").val();
			$.ajax({
				type: "POST",
				url: './cek_stat_import.php',
				data: { id_sub:id_sub, id_sumber_dana:id_sumber_dana, smt:smt, ta:ta, aksi:aksi },
				success: function(data){
					var data = eval('('+data+')');
					if(data.status == '*'){
						$('#btn-save').linkbutton('disable');
						$('#btn-tolak').linkbutton('disable');
					} else if(data.status == 'x'){
						$('#btn-save').linkbutton('disable');
						$('#btn-tolak').linkbutton('disable');
					} else if(data.status == '0'){
						$('#btn-save').linkbutton('enable');
						$('#btn-tolak').linkbutton('enable');
					}	
				}
			});	
}

function approve(){
	//alert('asd');
	$('#fm').form('submit',{
		url: './approve.php',
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

function tolak(){
	$('#fm').form('submit',{
		url: './aksi.php?module=tolak_import_tmp&oper=in',
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
