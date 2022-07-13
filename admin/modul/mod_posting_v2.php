 <div class="dtabel" style="width:100%;height:100%;background:white">	
 <table id="dgfull"class="easyui-datagrid"
	url="./model/lap_mutasi_trib.php" fit="true" showFooter="true"
	toolbar="#toolbar" title="Proses Laporan FIFO"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<!--
<tr>
<th field="opd" width="70" align="center">OPD</th>
<th field="tgl_proses" width="70" align="center">Tanggal Proses</th>
<th field="tgl_awal" width="70" align="center" halign="center">Tanggal Awal</th>
<th field="tgl_akhir" width="70" align="center" halign="center">Tanggal Akhir</th>
</tr>
-->
</thead>
</table>
</div>
<div id="toolbar"> 
	<div  >
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
		</script></td> 
		<?php } ?>  
			<td>Tanggal Awal</td>
			<td>
				: <input class="easyui-datebox" style="width:100px;" id="tgl_awal" name="tgl_awal" required="true" data-options="formatter:myformatter,parser:myparser" />
			</td> 
			<td>Tanggal Akhir</td>
			<td>
				: <input class="easyui-datebox" style="width:100px;" id="tgl_akhir" name="tgl_akhir" required="true" data-options="formatter:myformatter,parser:myparser" /> 
			</td> 
			<td>Barang</td>
			<td> 
				<input class="easyui-combobox" style="width:230px;" id="id_barang" name="id_barang" required="true"/>
				<script>
				$('#id_barang').combobox({
					url:'./model/cb_barang.php',
					valueField:'id_bar',
					textField:'nama_bar',
					filter: function(q, row){
						var opts = $(this).combobox('options');
						return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
					}
				});
				</script>
			</td> 
			<td>
				<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="posting()" style="width:90px" id="btnProses">Proses</a>
				<a href="javascript:void(0)" class="easyui-linkbutton c6" style="width:180px;display:none" id="btnLoading">Sedang Memproses...</a>
			</td>
		</tr>	
		</table> 
	</div>
	<div style="clear:both"></div>
</div>
 


<script type="text/javascript">
function posting(){
	$('#btnLoading').show();
	$('#btnProses').hide();
	var uuid_skpd = $('#id_sub').combobox('getValue');
	var id_barang = $('#id_barang').combobox('getValue');
	var tgl_akhir = $('#tgl_akhir').datebox('getValue');
	var tgl_awal = $('#tgl_awal').datebox('getValue');  
			$.ajax({
				type: "POST",
				url: './aksi.php?module=posting_barang&oper=add',
				data: { uuid_skpd: uuid_skpd, tgl_awal:tgl_awal, tgl_akhir:tgl_akhir, id_barang:id_barang },
				success: function(data){
					$('#btnLoading').hide();
					$('#btnProses').show();
					console.log(data)		
					$.messager.alert('Sukses','Posting Harga Selesai !' );
				}
			});	 
}

function myformatter(date){
	var y = date.getFullYear();
	var m = date.getMonth()+1;
	var d = date.getDate();
	return (d<10?('0'+d):d)+'-'+(m<10?('0'+m):m)+'-'+y;
}
function myparser(s){
	if (!s) return new Date();
	var ss = (s.split('-'));
	var y = parseInt(ss[0],10);
	var m = parseInt(ss[1],10);
	var d = parseInt(ss[2],10);
	if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
		return new Date(d,m-1,y);
	} else {
		return new Date();
	}
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
