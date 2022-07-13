 <div class="dtabel" style="width:100%;height:100%;background:white">	
 <table id="dgfull"class="easyui-datagrid"
	url="./model/lap_rekap_jenis.php" fit="true" showFooter="true"
	toolbar="#toolbar" title="Laporan Rekapitulasi Jenis Barang"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
<th field="nama" width="110" align="left" halign="center">NAMA JENIS BARANG</th>
<th field="saldo" width="50" align="center" halign="center">SALDO AKUMULATIF</th>
<th field="nilai" width="70" align="right" halign="center">NILAI AKUMULATIF</th>
</tr>
</thead>
</table>
</div>
<div id="toolbar">
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="CetakDaftar()">Cetak Rekap</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="CetakJenis()">Cetak Rekap Per Jenis</a>
<div style="float: right; margin-right: 5px;">
	<a href="#" class="easyui-linkbutton" plain="true" iconCls="icon-search" onclick="javascript:$('#dls').dialog('open')">Pencarian</a>
</div>
</div>


<div id="dls" class="easyui-dialog" style="width:450px;height:340px;padding:10px 20px"
closed="true" buttons="#dls-buttons" title="Pencarian Data Rekapitulasi Barang">
<div class="ftitle">Pencarian Data Rekapitulasi Jenis Barang</div>
<form id="fms" method="post">
<table cellpadding="5">
<?php if($peran==md5('1')){ ?>
<tr>
<td>Nama Sub Unit</td>
<td>: 
<input class="easyui-combobox" style="width:250px;" id="id_sub" name="id_sub" required="true"/>
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
		$('#id_sumber').combobox('reload', './model/cb_sumber_dana.php?skpd&kd='+rec.kode);
	}
});
</script></td>
</tr>
<?php } ?>
<tr>
<td>Sumber Dana</td>
<td>: 
<input class="easyui-combobox" style="width:200px;" id="id_sumber" name="id_sumber"/>
<script>
$('#id_sumber').combobox({
    url:'./model/cb_sumber_dana.php?skpd',
    valueField:'id',
    textField:'text',
    filter: function(q, row){
		var opts = $(this).combobox('options');
		return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
	}
});
</script></td>
</tr>
<tr>
	<td>Tahun</td>
	<td>: <input class="easyui-combobox" style="width:60px;" id="thn" name="thn"/>
	<script>
	$('#thn').combobox({
		url:'./model/cb_tahun.php',
		valueField:'id',
		textField:'text',
		filter: function(q, row){
			var opts = $(this).combobox('options');
			return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
		}
	});
	</script></td>
</tr>
<tr>
	<td>Semester</td>
	<td>:  <input class="easyui-combobox" style="width:90px;" id="smstr" name="smstr"/>
	<script>
	$('#smstr').combobox({
		valueField:'id',
		textField:'text',
		data:  [{id: '1', text: 'Satu'},
				{id: '2', text: 'Dua'}],
		filter: function(q, row){
			var opts = $(this).combobox('options');
			return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
		},
		onSelect: function(rec){
			$('#bln').combobox('clear');
			$('#bln').combobox('reload', './model/cb_bulan.php?smstr='+rec.id);
		}
	});
	</script>
	</td>
</tr>
<tr>
	<td>Periode Bulan</td>
	<td>: <input class="easyui-combobox" style="width:90px;" id="bln" name="bln"/>
	<script>
	$('#bln').combobox({
		url:'./model/cb_bulan.php',
		valueField:'id',
		textField:'text',
		filter: function(q, row){
			var opts = $(this).combobox('options');
			return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
		}
	});
	</script></td>
</tr>	
</table>
</form>
</div>
<div id="dls-buttons">
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="doSearch()" style="width:90px">Cari</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dls').dialog('close')" style="width:90px">Batal</a>
</div>


<script type="text/javascript">
var ta; var id_sub; var bln; var blnt; var id_sum; var smstr;
$(function(){
	$('#dgfull').datagrid({
		view: detailview,
		detailFormatter:function(index,row){
			return '<div style="padding:2px"><table class="ddv"></table></div>';
		},
		onExpandRow: function(index,row){
			var ddv = $(this).datagrid('getRowDetail',index).find('table.ddv');
			var foo = $(this).datagrid('getFooterRows');
			ddv.datagrid({
				url:'./model/lap_rekap_jenis_detail.php?idj='+row.id_jenis+'&ids='+foo[0].ids+'&bln='+bln+'&smstr='+smstr+'&ta='+ta+'&id_sum='+id_sum,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				loadMsg:'',
				height:'auto',
				columns:[[
					{field:'kode_bar',title:'Kode Barang',width:100, align:'left', halign:'center'},
					{field:'nama_bar',title:'Nama Barang',width:200, align:'left', halign:'center'},
					{field:'saldo',title:'Saldo',width:50, align:'center', halign:'center'},
					{field:'nilai',title:'Nilai',width:50, align:'right', halign:'center'}
				]],
				onResize:function(){
					$('#dgfull').datagrid('fixDetailRowHeight',index);
				},
				onLoadSuccess:function(){
					setTimeout(function(){
						$('#dgfull').datagrid('fixDetailRowHeight',index);
					},0);
				}
			});
			$('#dgfull').datagrid('fixDetailRowHeight',index);
		}
	});
});	

function doSearch(){
	if($('#fms').form('validate')==false) return;
	ta = $('#thn').combobox('getValue');
	bln = $('#bln').combobox('getValue');
	smstr = $('#smstr').combobox('getValue');
	id_sum = $('#id_sumber').combobox('getValue');
	blnt = $('#bln').combobox('getText');
	<?php if($_SESSION['level']!=md5('c')){ ?> id_sub = $('#id_sub').combobox('getValue'); <?php } ?>
		$('#dgfull').datagrid('load',{
			<?php if($_SESSION['level']!=md5('c')){ ?> id_sub: id_sub, <?php } ?>
			ta: ta,
			bln: bln,
			id_sum: id_sum,
			smstr: smstr
		});
		
}

function CetakDaftar(){
	var basket = $('#dgfull').datagrid('getData');
		$.loader.open($dataLoader);
		$.post( "./print/lap_rekap_jenis.php", { basket : basket.rows, bulan : blnt, ta : ta, smstr: smstr, 
				id_sum : id_sum,
				<?php if($peran==md5('1')){ ?> id_sub: id_sub <?php } ?> })
		.done(function( data ) {
			if(data.success==false) alert(data.pesan);
			window.location.href = data.url;
			$.loader.close($dataLoader);
		});
	
}	

function CetakJenis(){
	var btn =  $('#dgfull').datagrid('getSelected');
	if(btn){
		$.loader.open($dataLoader);
		$.post( "./print/lap_rekap_jenis_detail.php", 
			{ id : id_sub, 
			  id_jen : btn.id_jenis, 
			  id_sum : id_sum,
			  bulan : blnt, 
			  ta : ta,
			  smstr: smstr 
			})
		.done(function( data ) {
			//console.log(data);
			if(data.success==false) alert(data.pesan);
			window.location.href = data.url;
			$.loader.close($dataLoader);
		});
	}else $.messager.alert('Peringatan','Pilih data SKPD dulu sebelum mencetak');

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
