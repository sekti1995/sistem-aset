 <div class="dtabel" style="width:100%;height:100%;background:white">	
 <table id="dgfull"class="easyui-datagrid"
	url="./model/daftar_pusk.php" fit="true" showFooter="true"
	toolbar="#toolbar" title="Kunci Entri SIMBAPER"
	rownumbers="true" fitColumns="true" >
<thead>

<tr>
	<th field="ck" checkbox="true"></th>
	<th field="kd_unit" width="20" align="left">KD UNIT</th>
	<th field="kd_sub" width="20" align="left">KD SUB</th>
	<th field="kd_sub2" width="20" align="left">KD SUB2</th>
	<th field="nm_sub2_unit" width="150" align="left">OPD</th>
</tr>

</thead>
</table>
</div>

<div id="toolbar"> 
	<div  >
		<table cellpadding="5">
		<tr>
			<td>
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
						{"id":5,"text":"PENGELUARAN"},
						{"id":6,"text":"DROPING"},
						{"id":7,"text":"SEMUA DATA"}
					]
				});
				</script>
			</td> 
			<td>
				<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="hapus()" style="width:90px" id="btnProses">HAPUS</a>
			</td>
		</tr>	
		</table> 
		<h3> &nbsp; Pilih Unit :</h3>
	</div>
	<div style="clear:both"></div>
</div>
 


<script type="text/javascript">

// $(function(){
	// $.get( "./model/get_lock_date.php", function( data ) {
		// var data = eval('('+data+')');
		// console.log(data[0])
		// $('#tgl_awal').datebox('setValue',data[0].tgl_awal);
		// $('#tgl_akhir').datebox('setValue',data[0].tgl_akhir);
	// });
	// $('#dgfull').datagrid({
	// onLoadSuccess:function(data){
		// var rows = $(this).datagrid('getRows');
		// for(i=0;i<rows.length;++i){
		// console.log(rows[i]['ck'])
			// if(rows[i]['ck']==1) $(this).datagrid('checkRow',i);
		// }
	// }
	// });
	
// });


function hapus(){
	
	var kd_skpd = $('#dgfull').datagrid('getSelections');
	var data = $('#data').combobox('getValue');
			$.ajax({
				type: "POST",
				url: './aksi.php?module=hapus_pusk&oper=add',
				data: { kd_skpd: kd_skpd, data: data },
				success: function(result){
					console.log(kd_skpd)
					var result = eval('('+result+')');
					if (result.success==false){
							$.messager.show({ title: 'Error', msg: result.pesan });
					} else {
						$.messager.show({ title: 'Sukses', msg: result.pesan }); 
						$('#dgfull').datagrid('reload');
					}
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
