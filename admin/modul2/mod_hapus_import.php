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
<div id="toolbar" style="padding:10px"> 
	<a href="javascript:void(0)" plain="false" class="easyui-linkbutton c6" iconCls="icon-remove" onclick="hapusImport()" id="hapus" style="width:120px">Hapus Data</a>
</div>

<script type="text/javascript">
function hapusImport(){
	var rw1 = $('#dgfull').datagrid('getSelected');
	//console.log(rw1)
	if (rw1){ 
			$.messager.confirm('Peringatan','Apakah Anda yakin akan Menghapus Data ini?',function(r){
			if (r){
				$.ajax({
					type: "POST",
					url: './aksi.php?module=hapus_import&oper=del',
					data: { uuid_skpd: rw1.uuid_skpd, timestamp : rw1.timestamp},
					success: function(data){
						console.log(data)
						var data = eval('('+data+')');
						
						if (data.success==false){
							$.messager.show({ title: 'Error', msg: data.pesan });
						} else {
							$.messager.show({ title: 'Sukses', msg: data.pesan }); 
							$('#dgfull').datagrid('reload');
						} 
					}
				});	
				}
			},'json'); 
	}else $.messager.alert('Peringatan','Pilih Data Pengeluaran Barang yang akan dihapus dahulu !');
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
