 
 <div class="dtabel" style="width:100%;height:100%;background:white">	
 <table id="dgfull"class="easyui-datagrid"
	url="./model/lap_rekap_jenis.php" fit="true" showFooter="true"
	toolbar="#toolbar" title="Laporan Rekapitulasi Jenis Barang Per Bulan"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
<th field="nama" width="110" align="left" halign="center" >NAMA JENIS BARANG</th>
<th field="saldo_awal" width="110" align="center" >SISA PERIODE LALU</th>
<th field="masuk" width="110" align="center" >PENGADAAN PERIODE INI</th>
<th field="jml_masuk" width="110" align="center" >JML PERIODE INI</th>
<th field="keluar" width="110" align="center" >PEMAKAIAN PERIODE INI</th>
<th field="saldo_akhir" width="110" align="center" >SISA PERIODE INI</th>
</tr>
</tr>
</thead>
</table>
</div>
<div id="toolbar">
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="CetakDaftar()">Cetak Rekap</a>
<!--
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="CetakJenis()">Cetak Rekap OPD</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="CetakJenis2()">Cetak Rekap Kabupaten</a>
-->
<div style="float: right; margin-right: 5px;">
	<a href="#" class="easyui-linkbutton" plain="true" iconCls="icon-search" onclick="pencarian()">Pencarian</a>
</div>
</div>


<div id="dls" class="easyui-dialog" <?php if($_SESSION["jenis"] == "UPB"){ ?> 
		style="width:350px;height:250px;padding:10px 20px" 
	<?php } else { ?> 
		style="width:450px;height:350px;padding:10px 20px" 
	<?php }?>
closed="true" buttons="#dls-buttons" title="Pencarian Data Rekapitulasi Jenis Barang Per Bulan">
<div class="ftitle">Pencarian Data Rekapitulasi Jenis Barang Per Bulan</div>
<form id="fms" method="post">

<?php // if($peran==md5('1') or $peran==md5('7')){ ?>

	<div class="fitem" id='filt_cetak'>
		<label>Cetak Sebagai</label>: 
		<input class="easyui-combobox" style="width:180px;" id="id_akses" name="id_akses" required="true"/>
		<script>
		var id_akses;
		var opd;
		$('#id_akses').combobox({
			url:'./model/cb_akses2.php',
			valueField:'id',
			textField:'text',
			filter: function(q, row){
				var opts = $(this).combobox('options');
				return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
			},
			onSelect: function(rec){
				
				<?php if($_SESSION["jenis"] == ""){ ?>
					$('#id_unit').combobox('reload','./model/cb_opd.php');
					$('#id_unit').combobox('clear');
					$('#id_sub_unit').combobox('clear');
					$('#id_sub').combobox('clear');
					id_akses = rec.id;
					if(rec.id==2 || rec.id==5){
						$('#id_sub_unit').combobox('readonly', true);
						$('#id_sub').combobox('readonly', true);
					}else if(rec.id==3){
						$('#id_sub_unit').combobox('readonly', false);
						$('#id_sub').combobox('readonly', true);
					}else{
						$('#id_sub_unit').combobox('readonly', false);
						$('#id_sub').combobox('readonly', false);
					}
				<?php } ?>
			}
		});
		</script>
	</div>
	<div class="fitem" id='filt_opd'>
		<label>OPD</label>: 
		<input class="easyui-combobox" style="width:250px;" id="id_unit" name="id_unit" required="true"/>
		<script>
		$('#id_unit').combobox({
			url:'./model/cb_opd.php',
			valueField:'b',
			textField:'d',
			filter: function(q, row){
				var opts = $(this).combobox('options');
				return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
			},
			onSelect: function(rec){
				$('#id_sub_unit').combobox('reload','./model/cb_upt2.php?opd='+rec.c);
				console.log(rec.c)
				$('#id_sub_unit').combobox('clear');
				opd = rec.c;
				$('#id_sumber').combobox('clear');
				$('#id_sumber').combobox('reload', './model/cb_sumber_dana.php?cek&id='+rec.b+'&id_akses='+id_akses);
			} 
		});
		</script>
	</div>
	<div class="fitem" id='filt_upt'>
		<label>UPT</label>: 
		<input class="easyui-combobox" style="width:280px;" id="id_sub_unit" name="id_sub_unit" />
		<script>
		$('#id_sub_unit').combobox({
			url:'./model/cb_upt.php',
			valueField:'b',
			textField:'f',
			filter: function(q, row){
				var opts = $(this).combobox('options');
				return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
			},
			onSelect: function(rec){
				$('#id_sub').combobox('reload','./model/cb_upb.php?upt='+rec.e+'&opd='+opd);
				$('#id_sub').combobox('clear');
				$('#id_sumber').combobox('clear');
				$('#id_sumber').combobox('reload', './model/cb_sumber_dana.php?cek&id='+rec.b+'&id_akses='+id_akses);
			}
		});
		</script>
	</div>
	<div class="fitem" id='filt_sub_unit'>
		<label>Sub Unit 2</label>: 
		<input class="easyui-combobox" style="width:280px;" id="id_sub" name="id_sub" />
		<script>
		$('#id_sub').combobox({
			url:'./model/cb_upb.php',
			valueField:'b',
			textField:'h',
			filter: function(q, row){
				var opts = $(this).combobox('options');
				return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
			},
			onSelect: function(rec){
				$('#id_sumber').combobox('clear');
				$('#id_sumber').combobox('reload', './model/cb_sumber_dana.php?cek&id='+rec.b+'&id_akses='+id_akses);
			}
		});
		</script>
	</div>
<?php //} ?>
	<div class="fitem" hidden>
		<label>Sumber Dana</label>: 
		<input class="easyui-combobox" style="width:200px;" id="id_sumber" name="id_sumber"/>
		<script>
		$('#id_sumber').combobox({
			url:'./model/cb_sumber_dana.php?cek',
			valueField:'id',
			textField:'text',
			filter: function(q, row){
				var opts = $(this).combobox('options');
				return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
			}
		});
		</script>
	</div>
	<div class="fitem">
		<label>Tanggal Awal</label>: <input class="easyui-datebox" data-options="formatter:myformatter,parser:myparser,required:true" style="width:90px;" id="tglawal" name="tglawal" required="true"/>
	</div> 
	<div class="fitem">
		<label>Tanggal Akhir</label>: <input class="easyui-datebox" style="width:90px;" id="tglakhir" name="tglakhir" data-options="formatter:myformatter,parser:myparser,required:true" required="true"/>
	</div> 
	<div class="fitem">
		<label>Tahun</label>: 
		<input class="easyui-combobox" style="width:60px;" id="thn" name="thn"  required="true"/>
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
		</script>
	</div> 
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
		// view: detailview,
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

function pencarian(){
	$('#dls').dialog('open');
	// var cont = <?php echo $_SESSION['jenis']; ?>;
	// console.log(cont);
	<?php 
	if($_SESSION["jenis"] == "OPD"){
	?>
		// var idopd = '<?php echo $_SESSION['kd_unit']; ?>';
		// $('#id_akses').combobox('setValue', 2);
		// $('#id_unit').combobox('setValue', idopd);
		
		// $('#id_sub_unit').combobox('reload','./model/cb_upt.php?opd='+idopd);
		
		var idopd = '<?php echo $_SESSION['kd_unit']; ?>';
		$('#id_akses').combobox('setValue', 2);
		$('#id_akses').combobox('readonly', true);
		// console.log(idopd)
		$('#id_unit').combobox('reload','./model/cb_upt.php?opd='+idopd+'&jenis=opd');
		
	<?php
	} else if($_SESSION["jenis"] == "UPT"){
	?>
		$('#id_unit').combobox('readonly', true);
		$('#id_sub_unit').combobox('readonly', true);
		var idopd = '<?php echo $_SESSION['kd_unit']; ?>';
		var idupt = '<?php echo $_SESSION['kd_sub']; ?>';
		$('#id_sub').combobox('reload','./model/cb_upb.php?opd='+idopd+'&upt='+idupt);
		
		$('#id_akses').combobox('setValue', 3);
		$('#id_unit').combobox('setValue', '<?php echo $_SESSION['id_opd']; ?>');
		$('#id_sub_unit').combobox('setValue', '<?php echo $_SESSION['id_upt']; ?>');
		
	<?php
	} else if($_SESSION["jenis"] == "UPB"){
	?>
		$('#id_unit').combobox('readonly', true);
		$('#id_sub_unit').combobox('readonly', true);
		$('#id_sub').combobox('readonly', true);
		$('#id_akses').combobox('setValue', 4);
		$('#id_unit').combobox('setValue', '<?php echo $_SESSION['id_opd']; ?>');
		$('#id_sub_unit').combobox('setValue', '<?php echo $_SESSION['id_upt']; ?>');
		$('#id_sub').combobox('setValue', '<?php echo $_SESSION['id_upb']; ?>');
		
		$('#filt_cetak').hide();
		$('#filt_opd').hide();
		$('#filt_upt').hide();
		$('#filt_sub_unit').hide();
	<?php
	}
	?>
}

function doSearch(){
	<?php //if($peran==md5('1') or $peran==md5('7')){ ?>
	
	var tglawal = $('#tglawal').datebox('getValue');
	var tglakhir = $('#tglakhir').datebox('getValue');
	if($('#fms').form('validate')==false) return;
	ta = $('#thn').combobox('getValue');
	id_sum = $('#id_sumber').combobox('getValue');
	
	ta = $('#thn').combobox('getValue'); 
	// nilai = $('#nilai').textbox('getValue');
	nilai = "";
	<?php if($_SESSION['level']!=md5('c')){ ?> 
	akses = $('#id_akses').combobox('getValue');
	id_sum = $('#id_sumber').combobox('getValue'); 
	id_sub_unit = $('#id_sub_unit').combobox('getValue');
	if(akses == 2 || akses == 5){
		id_sub = $('#id_unit').combobox('getValue');
	} else if(akses == 3){
		id_sub = $('#id_sub_unit').combobox('getValue');
	} else if(akses == 4){
		id_sub = $('#id_sub').combobox('getValue');
	}
	<?php } ?>
	<?php if($_SESSION['level']!=md5('c')){ ?> 
		$('#dgfull').datagrid('load',{
			<?php if($_SESSION['level']!=md5('c')){ ?> id_sub: id_sub, <?php } ?>
			ta: ta,
			tglawal: tglawal,
			tglakhir: tglakhir,
			<?php if($_SESSION['level']!=md5('c')){ ?> akses: akses, <?php } ?>
			
			id_sum: id_sum,
			smstr: ""
		});
	<?php } else { ?>
		$('#dgfull').datagrid('load',{
			<?php if($_SESSION['level']!=md5('c')){ ?> id_sub: id_sub, <?php } ?>
			ta: ta,
			tglawal: tglawal,
			tglakhir: tglakhir,
			smstr: ""
		});
		
	<?php } ?>	
}

function CetakDaftar(){
	akses = $('#id_akses').combobox('getValue');
	console.log(akses)
	if(akses == 2 || akses == 5){
		id_sub = $('#id_unit').combobox('getValue');
	} else if(akses == 3){
		id_sub = $('#id_sub_unit').combobox('getValue');
	} else if(akses == 4){
		id_sub = $('#id_sub').combobox('getValue');
	}
	var tglawal = $('#tglawal').datebox('getValue');
	var tglakhir = $('#tglakhir').datebox('getValue');
		$.loader.open($dataLoader);
	$.post( "./print/lap_rekap_jenis.php", { ta : ta, tglawal: tglawal, tglakhir: tglakhir, akses:akses, 
			<?php if($peran==md5('1')){ ?> id_sub: id_sub <?php } ?> })
	.done(function( data ) {
		console.log(data)
		if(data.success==false) alert(data.pesan);
		window.location.href = data.url;
		$.loader.close($dataLoader);
	});
	
}	

function CetakJenis(){
	akses = $('#id_akses').combobox('getValue');
	if(akses == 2 || akses == 5){
		id_sub = $('#id_unit').combobox('getValue');
	} else if(akses == 3){
		id_sub = $('#id_sub_unit').combobox('getValue');
	} else if(akses == 4){
		id_sub = $('#id_sub').combobox('getValue');
	}
	var tglawal = $('#tglawal').datebox('getValue');
	var tglakhir = $('#tglakhir').datebox('getValue');
		$.loader.open($dataLoader);
	$.post( "./print/lap_rekap_jenis_opd.php", { ta : ta, tglawal: tglawal, tglakhir: tglakhir, akses:akses, 
			<?php if($peran==md5('1')){ ?> id_sub: id_sub <?php } ?> })
	.done(function( data ) {
		console.log(data)
		if(data.success==false) alert(data.pesan);
		window.location.href = data.url;
		$.loader.close($dataLoader);
	});
	
}		

function CetakJenis2(){
	akses = $('#id_akses').combobox('getValue');
	if(akses == 2 || akses == 5){
		id_sub = $('#id_unit').combobox('getValue');
	} else if(akses == 3){
		id_sub = $('#id_sub_unit').combobox('getValue');
	} else if(akses == 4){
		id_sub = $('#id_sub').combobox('getValue');
	}
	var tglawal = $('#tglawal').datebox('getValue');
	var tglakhir = $('#tglakhir').datebox('getValue');
		$.loader.open($dataLoader);
	$.post( "./print/lap_rekap_jenis_kab.php", { ta : ta, tglawal: tglawal, tglakhir: tglakhir, akses:akses, 
			<?php if($peran==md5('1')){ ?> id_sub: id_sub <?php } ?> })
	.done(function( data ) {
		console.log(data)
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
