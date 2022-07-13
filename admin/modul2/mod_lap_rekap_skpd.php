 <div class="dtabel" style="width:100%;height:100%;background:white">	
 <table id="dgfull">
<!--<thead>
<tr>
<th field="kode_skpd" width="50" align="left" halign="center" >Kode SKPD</th>
<th field="nama_skpd" width="110" align="left" halign="center" >Nama SKPD</th>
<th field="nilai" width="50" align="right" halign="center">Nilai Persediaan</th>
</tr>
</thead>-->
</table>
</div>
<div id="toolbar">
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="CetakDaftar()">Cetak Rekap</a>
<!--
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="CetakSKPD()">Cetak Rekap Per SKPD</a>
-->
<div style="float: right; margin-right: 5px;">
	<a href="#" class="easyui-linkbutton" plain="true" iconCls="icon-search" onclick="javascript:$('#dls').dialog('open')">Pencarian</a>
</div>
</div>


<div id="dls" class="easyui-dialog" style="width:450px;height:340px;padding:10px 20px"
closed="true" buttons="#dls-buttons" title="Pencarian Data Rekapitulasi per SKPD">
<div class="ftitle">Pencarian Data Rekapitulasi per SKPD</div>
<form id="fms" method="post">
<table cellpadding="5">
<tr>
<td>Sumber Dana</td>
<td>: 
<input class="easyui-combobox" style="width:200px;" id="id_sumber" name="id_sumber"/>
<script>
$('#id_sumber').combobox({
    url:'./model/cb_sumber_dana.php?all',
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
var conf = {
            options:{
                fitColumns:true,
				columns:[[
                    {field:'kode_skpd',title:'Kode SKPD',width:50, align:'left', halign:'center'},
                    {field:'nama_skpd',title:'Nama SKPD',width:110, align:'left', halign:'center'},
                    {field:'saldo_awal',title:'Saldo Awal',width:110, align:'left', halign:'center'},
                    {field:'masuk',title:'Pengadaan',width:110, align:'left', halign:'center'},
                    {field:'masuk2',title:'Pengadaan 4 Sept',width:110, align:'left', halign:'center'},
                    {field:'keluar',title:'Pengeluaran',width:110, align:'left', halign:'center'},
                    {field:'keluar2',title:'Pengeluaran 4 Sept',width:110, align:'left', halign:'center'},
                    {field:'sisa',title:'Saldo',width:50, align:'right', halign:'center'}
                ]],
				url:"./model/lap_rekap_skpd.php",
            },
            subgrid:{
                options:{
                    fitColumns:true,
					singleSelect:"true",
                    foreignField:'kode_skpd',
                    columns:[[
						{field:'kode_unit',title:'Kode Sub Unit',width:100, align:'left', halign:'center'},
						{field:'nama_unit',title:'Nama Sub Unit',width:200, align:'left', halign:'center'},
						{field:'nilai',title:'Nilai',width:50, align:'right', halign:'center'},
						{field: 'action', title: 'Cetak', width:15, align:'center', halign:'center',
							 formatter:function(value,row,index)
							 {
								var s = "<a href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-cancel'  onclick='CetakUPTD(this)' title='Cetak Per UPT'><img src='./images/print.gif'></img></a>";
								return s;
							}
						}
                    ]],
					url:'./model/lap_rekap_skpd_detail.php?bln='+bln+'&smstr='+smstr+'&ta='+ta+'&id_sum='+id_sum,
                },	
                subgrid:{
                    options:{
                        fitColumns:true,
						singleSelect:"true",
                        foreignField:'kode_unit',
                        columns:[[
							{field:'kode_sub',title:'Kode Sub2 Unit',width:100, align:'left', halign:'center'},
							{field:'nama_sub',title:'Nama Sub2 Unit',width:200, align:'left', halign:'center'},
							{field:'nilai',title:'Nilai',width:50, align:'right', halign:'center'}
                        ]],
						url:'./model/lap_rekap_skpd_detail2.php?bln='+bln+'&smstr='+smstr+'&ta='+ta+'&id_sum='+id_sum,
                    }
                }
            }
        };
$(function(){
	$('#dgfull').datagrid({
		fit:"true",
		showFooter:"true",
		toolbar:"#toolbar",
		title:"Laporan Rekapitulasi per SKPD",
		rownumbers:"true",
		fitColumns:"true",
		singleSelect:"true",
		/* view: detailview,
		detailFormatter:function(index,row){
			return '<div style="padding:2px"><table class="ddv"></table></div>';
		},
		onExpandRow: function(index,row){
			var ddv = $(this).datagrid('getRowDetail',index).find('table.ddv');
			ddv.datagrid({
				url:'./model/lap_rekap_skpd_detail.php?id='+row.kode_skpd+'&bln='+bln+'&smstr='+smstr+'&ta='+ta+'&id_sum='+id_sum,
				fitColumns:true,
				singleSelect:true,
				rownumbers:true,
				loadMsg:'',
				height:'auto',
				columns:[[
					{field:'kode_unit',title:'Kode Sub Unit',width:100, align:'left', halign:'center'},
					{field:'nama_unit',title:'Nama Sub Unit',width:200, align:'left', halign:'center'},
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
		} */
	}).datagrid('subgrid', conf);
});	

var ta; var bln; var blnt; var id_sum; var smstr;
function doSearch(){
	if($('#fms').form('validate')==false) return;
	ta = $('#thn').combobox('getValue');
	bln = $('#bln').combobox('getValue');
	smstr = $('#smstr').combobox('getValue');
	id_sum = $('#id_sumber').combobox('getValue');
	blnt = $('#bln').combobox('getText');
		$('#dgfull').datagrid('load',{
			ta: ta,
			bln: bln,
			id_sum: id_sum,
			smstr: smstr
		});
		
}

function CetakDaftar(){
	var basket = $('#dgfull').datagrid('getData');

	$.loader.open($dataLoader);
	$.post( "./print/lap_rekap_skpd.php", { basket : basket.rows, bulan : blnt, ta : ta, smstr: smstr, 
			})
	.done(function( data ) {
		if(data.success==false) alert(data.pesan);
		window.location.href = data.url;
		$.loader.close($dataLoader);
	});

}	

function CetakSKPD(){
	var btn =  $('#dgfull').datagrid('getSelected');
	if(btn){
		$.loader.open($dataLoader);
		$.post( "./print/lap_rekap_skpd_detail.php", 
			{ id : btn.uuid_skpd, 
			  kode : btn.kode_skpd, 
			  nama : btn.nama_skpd, 
			  id_sum : id_sum,
			  bulan : blnt, 
			  ta : ta,
			  smstr: smstr, 
			})
		.done(function( data ) {
			//console.log(data);
			if(data.success==false) alert(data.pesan);
			window.location.href = data.url;
			$.loader.close($dataLoader);
		});
	}else $.messager.alert('Peringatan','Pilih data SKPD dulu sebelum mencetak');

}	

function CetakUPTD(btn){
	var tr = $(btn).closest('tr.datagrid-row');
	var index = parseInt(tr.attr('datagrid-row-index'));
	var dg = tr.closest('div.datagrid-view').children('table');
	var row = dg.datagrid('getRows')[index];
	console.log(row)
	$.post( "./print/lap_rekap_skpd_detail2.php", 
		{ id : row.id, 
		  kode : row.kode_unit, 
		  nama : row.nama_unit, 
		  id_sum : id_sum,
		  bulan : blnt, 
		  ta : ta,
		  smstr: smstr, 
		})
	.done(function( data ) {
		//console.log(data);
		if(data.success==false) alert(data.pesan);
		window.location.href = data.url;
		$.loader.close($dataLoader);
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
