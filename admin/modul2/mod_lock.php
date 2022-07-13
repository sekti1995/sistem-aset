 <div class="dtabel" style="width:100%;height:100%;background:white">	
 <table id="dgfull"class="easyui-datagrid"
	url="./model/lock.php" fit="true" showFooter="true"
	toolbar="#toolbar" title="Kunci Entri SIMBAPER"
	rownumbers="true" fitColumns="true" >
<thead>

<tr>
	<th field="ck" checkbox="true"></th>
	<th field="kd_urusan" width="20" align="left">KD URUSAN</th>
	<th field="kd_bidang" width="20" align="left">KD BIDANG</th>
	<th field="kd_unit" width="20" align="left">KD UNIT</th>
	<th field="nm_unit" width="150" align="left">OPD</th>
</tr>

</thead>
</table>
</div>
<div id="toolbar"> 
	<div  >
		<table cellpadding="5">
		<tr>
			<td>Entrian Dikunci Sampai Tanggal</td>
			<td>
				: <input class="easyui-datebox" style="width:100px;" id="tgl_awal" name="tgl_awal" required="true" data-options="formatter:myformatter,parser:myparser" />
			</td> 
			<td>Izinkan Entri Sampai Tanggal</td>
			<td>
				: <input class="easyui-datebox" style="width:100px;" id="tgl_akhir" name="tgl_akhir" required="true" data-options="formatter:myformatter,parser:myparser" /> 
			</td> 
			<td>
				<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="simpanLock()" style="width:90px" id="btnProses">SIMPAN</a>
			</td>
		</tr>	
		</table> 
		<h3> &nbsp; Kunci Entri <b style="color:#ef2222">KECUALI</b> :</h3>
	</div>
	<div style="clear:both"></div>
</div>
 


<script type="text/javascript">
$(function(){
	$.get( "./model/get_lock_date.php", function( data ) {
		var data = eval('('+data+')');
		console.log(data[0])
		$('#tgl_awal').datebox('setValue',data[0].tgl_awal);
		$('#tgl_akhir').datebox('setValue',data[0].tgl_akhir);
	});
	$('#dgfull').datagrid({
	onLoadSuccess:function(data){
		var rows = $(this).datagrid('getRows');
		for(i=0;i<rows.length;++i){
		console.log(rows[i]['ck'])
			if(rows[i]['ck']==1) $(this).datagrid('checkRow',i);
		}
	}
	});
	
});


function simpanLock(){
	
	var kd_skpd = $('#dgfull').datagrid('getSelections');
	var tgl_akhir = $('#tgl_akhir').datebox('getValue');
	var tgl_awal = $('#tgl_awal').datebox('getValue');  
			$.ajax({
				type: "POST",
				url: './aksi.php?module=lock&oper=add',
				data: { kd_skpd: kd_skpd, tgl_awal:tgl_awal, tgl_akhir:tgl_akhir },
				success: function(result){
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
