 <div class="dtabel" style="width:100%;height:100%;background:white">	
 <table id="dgfull"class="easyui-datagrid"
	url="./model/dump.php" fit="true" showFooter="true"
	toolbar="#toolbar" title="BACKUP DATABASE"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
<th field="id_pengguna" width="80" align="center">ID PENGGUNA</th>
<th field="username" width="80" align="center">USERNAME</th>
<th field="file" width="60" align="center">FILE</th>
<th field="aplikasi" width="50" align="center">APLIKASI</th>
<th field="timestamp" width="40" align="center">TGL BACKUP</th>
<th field="download" width="30" halign="center" align="center">UNDUH</th>
</tr>
</thead>
</table>
</div>
<div id="toolbar"> 
	<div  >
		<table cellpadding="5" width='100%'> 
		<tr>
			<td> 
			</td> 
			<td>
				<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="dumpDB()" style="width:140px;float:right" id="btnProses">Backup Database</a>
			</td>
		</tr>	
		</table> 
	</div>
	<div style="clear:both"></div>
</div>
 
<script type="text/javascript">
function dumpDB(){
	$.ajax({
		type: "POST",
		url: './dump.php',
		success: function(data){
			$.messager.show({
			title: 'Konfirmasi',
			msg: data
			});	
			$('#dgfull').datagrid('reload');			
		}
	});	
}
function unduhDB(url){
	window.location.href = url;
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
