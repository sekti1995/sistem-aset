 <div class="dtabel" style="width:100%;height:100%;background:white">	
 <table id="dgfull"class="easyui-datagrid"
	url="./model/log_hapus.php" fit="true" showFooter="true"
	toolbar="#toolbar" title="Import Data Persediaan"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
<th field="opd" width="70" >OPD</th>
<th field="smt" width="70" align="center">SMT</th>
<th field="ta" width="70" align="center" halign="center">TA</th>
<th field="data" width="40" align="right" halign="center">DATA</th>
<th field="timestamp" width="40" align="right" halign="center">TGL HAPUS</th>
</tr>
</thead>
</table>
</div>
<div id="toolbar" style="padding:10px"> 
	OPD : 
				<input class="easyui-combobox" style="width:225px;" id="id_sub" name="id_sub"  required="true"/>
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
					}
				});
				</script>
	&nbsp;
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
	&nbsp;
	TA :
				<input class="easyui-combobox" style="width:100px;" id="ta" name="ta"  required="true"/>
				<script>
				$('#ta').combobox({
					url:'./model/cb_ta.php',
					valueField:'id',
					textField:'text',
					filter: function(q, row){
						var opts = $(this).combobox('options');
						return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
					},
					onSelect: function(rec){ 
					}
				});
				</script>
	&nbsp;
	&nbsp;
	DATA :
				<input class="easyui-combobox" style="width:300px;" id="data" name="data"  required="true" prompt="Pilih Data Yang Akan Dihapus" />
				<script>
				$('#data').combobox({
					valueField:'id',
					textField:'text',
					data: [
						{"id":1,"text":"SALDO AWAL & PENGADAAN & PENGELUARAN"},
						{"id":2,"text":"PENGADAAN & PENGELUARAN"},
						{"id":3,"text":"SALDO AWAL"},
						{"id":4,"text":"PENGADAAN"},
						{"id":5,"text":"PENGELUARAN"}
					]
				});
				</script>
	<a href="javascript:void(0)" plain="false" class="easyui-linkbutton c6" iconCls="icon-remove" onclick="hapusImport()" id="hapus" style="width:120px">Hapus Data</a>
</div>

<script type="text/javascript">
function hapusImport(){
	var id_sub = $('#id_sub').combobox('getValue');
	var smt = $('#smt').combobox('getValue');
	var ta = $('#ta').combobox('getValue');
	var data = $('#data').combobox('getValue');
	
	//console.log(rw1)
	if (id_sub != "" && smt != "" && ta != "" && data != ""){ 
			$.messager.confirm('Peringatan','Data Persediaan Akan <b style="color:#ef2222">TERHAPUS PERMANEN</b><br>Anda yakin akan Menghapus Data ini ?',function(r){
			if (r){
				$.ajax({
					type: "POST",
					url: './hapus_import.php?module=hapus_import&oper=del',
					data: { uuid_skpd: id_sub, smt : smt, ta : ta, data : data},
					success: function(data){
						//console.log(data)
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
