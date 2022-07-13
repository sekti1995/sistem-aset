 <div class="dtabel" style="width:100%;height:100%;background:white">	
 <table id="dgfull"class="easyui-datagrid"
	url="./model/compare.php" fit="true" showFooter="true"
	toolbar="#toolbar" title="NORMALISASI DATA PENGELUARAN"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
<th field="nama_barang" width="33" align="left" rowspan='2'>BARANG</th>
<th field="nama_barang" width="33" align="center" colspan='3'>KELUAR DETAIL</th>
<th field="nama_barang" width="33" align="center" colspan='3'>KARTU STOK</th>	
</tr>
<tr>
<th field="harga1" width="30" align="center">HARGA</th>
<th field="keluar1" width="30" align="center">KELUAR</th>
<th field="soft_delete1" width="30" align="center">SOFT DELETE</th>

<th field="harga2" width="30" align="center">HARGA</th>
<th field="keluar2" width="30" align="center">KELUAR</th>
<th field="soft_delete2" width="30" align="center">SOFT DELETE</th>
</tr>
</thead>
</table>
</div>
<div id="toolbar"> 
	<div  >
		<table cellpadding="5" width='100%'>
		<?php if($_SESSION['level']!=md5('c')){ ?>
		<tr>
		<td>Nama OPD : 
		<input class="easyui-combobox" style="width:300px;" id="id_sub" name="id_sub"  required="true"/>
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
		<?php } ?>
		&nbsp; &nbsp; 
		Petugas :
		<input class="easyui-textbox" style="width:120px;" id="petugas" name="petugas"  required="true"/>
		
				<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-search" onclick="cari()" style="width:90px" id="btnProses">Cari</a>
			</td> 
			<td>
				<a href="javascript:void(0)" id="btn-normal" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="normal()" style="width:120px;float:right" id="btnProses">NORMALKAN</a>
				<a href="javascript:void(0)" id="btn-loading" class="easyui-linkbutton c6" plain='true' style="width:120px;float:right" id="btnProses">Loading...</a>
			</td>
		</tr>	
		</table> 
	</div>
	<div style="clear:both"></div>
</div>


<script type="text/javascript">
$(function(){
	$("#btn-normal").show();
	$("#btn-loading").hide();
})
function normal(){
	$("#btn-normal").hide();
	$("#btn-loading").show();
	var id_sub = $('#id_sub').combobox('getValue');
	var petugas = $('#petugas').textbox('getValue');
	if(id_sub == "" || petugas == ""){
		alert('DATA TIDAK BOLEH KOSONG');
	} else {
		
		$.ajax({
			type: "POST",
			url: './model/compare.php',
			data: { id_sub : id_sub, petugas:petugas, exe:'1' },
			success: function(data){
				$("#btn-normal").show();
				$("#btn-loading").hide();
				$.messager.alert('Sukses','Data berhasil dinormalkan !');	
				$('#dgfull').datagrid('load',{
					id_sub: id_sub
				});			
			}
		});	
		
	}
}

function cari(){
	var id_sub = $('#id_sub').combobox('getValue');
	$('#dgfull').datagrid('load',{
		id_sub: id_sub
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
