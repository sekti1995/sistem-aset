 <div class="dtabel" style="width:100%;height:100%;background:white">	
 <table id="dgfull"class="easyui-datagrid"
	url="./model/rekap_persediaan.php" fit="true" showFooter="true"
	toolbar="#toolbar" title="Rekapitulasi Persediaan Barang"
	rownumbers="true" fitColumns="true" singleSelect="true" >
<thead>
<tr>
<th field="nama_barang" width="150" align="left" halign="center" rowspan="2">NAMA BARANG</th>
<th field="satuan" width="50" align="left" halign="center" rowspan="2">SATUAN</th>
<th field="harga" width="70" align="right" halign="center" rowspan="2">HARGA SATUAN</th>
<th width="130" align="center" colspan="2">SISA BLN LALU</th>
<th width="130" align="center" colspan="2">PENGADAAN BLN INI</th>
<th width="130" align="center" colspan="2">JML BLN INI</th>
<th width="130" align="center" colspan="2">PEMAKAIAN BLN INI</th>
<th width="130" align="center" colspan="2">SISA BLN INI</th>
</tr>
<tr>
<th field="jml_lalu" width="50" align="center" halign="center">JML</th>
<th field="tot_lalu" width="70" align="right" halign="center">JML HARGA</th>
<th field="jml_in" width="50" align="center" halign="center">JML</th>
<th field="tot_in" width="70" align="right" halign="center">JML HARGA</th>
<th field="jml_ini" width="50" align="center" halign="center">JML</th>
<th field="tot_ini" width="70" align="right" halign="center">JML HARGA</th>
<th field="jml_out" width="50" align="center" halign="center">JML</th>
<th field="tot_out" width="70" align="right" halign="center">JML HARGA</th>
<th field="jumlah" width="50" align="center" halign="center">JML</th>
<th field="total" width="70" align="right" halign="center">JML HARGA</th>
</tr>
</thead>
</table>
</div>
<div id="toolbar">
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="CetakDaftar()">Cetak Excel</a>
<div style="float: right; margin-right: 5px;">
	<a href="#" class="easyui-linkbutton" plain="true" iconCls="icon-search" onclick="pencarian()">Pencarian</a>
</div>
</div>


<div id="dls" class="easyui-dialog" style="width:450px;height:auto;padding:10px 20px"
closed="true" buttons="#dls-buttons" title="Pencarian Data Laporan Mutasi Bulan">
<div class="ftitle">Pencarian Data Laporan Mutasi Bulan</div>
<form id="fms" method="post">

<table cellpadding="5">
<?php if($_SESSION['level']!=md5('c')){ ?>
<tr>
	<td>Cetak Sebagai</td>
	<td> :
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
</td>
</tr>
<tr>
	<td>OPD</td>
	<td> :
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
			$('#id_sub_unit').combobox('clear');
			opd = rec.c;
			$('#id_sumber').combobox('clear');
			$('#id_sumber').combobox('reload', './model/cb_sumber_dana.php?cek&id='+rec.b+'&id_akses='+id_akses);
		} 
	});
	</script>
	</td>
</tr>
<tr>
	<td>UPT</td> 
	<td> :
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
	</td>
</tr>
<tr>
	<td>Sub Unit 2</td>
	<td> :
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
	</td>
</tr>
<?php } ?>
<tr>
<td>Sumber Dana</td>
<td>: 
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
</td>
</tr>
<tr>
	<td>Periode Bulan</td>
	<td>: <input class="easyui-combobox" style="width:90px;" id="bln" name="bln" required="true"/>
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
<tr>
	<td>Tahun</td>
	<td>: <input class="easyui-combobox" style="width:60px;" id="thn" name="thn"  required="true"/>
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
</table>
</form>
</div>
<div id="dls-buttons">
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="doSearch()" style="width:90px">Cari</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dls').dialog('close')" style="width:90px">Batal</a>
</div>


<script type="text/javascript">
var ta; var id_sub; var bln; var blnt; var id_sum;
function pencarian(){
	$('#dls').dialog('open');
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
		
		// $('#id_unit').combobox('reload','./model/cb_upt.php?opd='+idopd);
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
	<?php
	}
	?>
}
function doSearch(){
	if($('#fms').form('validate')==false) return;
	ta = $('#thn').combobox('getValue');
	bln = $('#bln').combobox('getValue');
	blnt = $('#bln').combobox('getText');
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
			bln: bln,
			<?php if($_SESSION['level']!=md5('c')){ ?> akses: akses, <?php } ?>
			
			id_sum: id_sum,
			smstr: ""
		});
	<?php } else { ?>
		$('#dgfull').datagrid('load',{
			<?php if($_SESSION['level']!=md5('c')){ ?> id_sub: id_sub, <?php } ?>
			ta: ta,
			bln: bln,
			id_sum: id_sum
		});
		
	<?php } ?>	
	
	// if($('#fms').form('validate')==false) return;
	// ta = $('#thn').combobox('getValue');
	// bln = $('#bln').combobox('getValue');
	// id_sum = $('#id_sumber').combobox('getValue');
	// blnt = $('#bln').combobox('getText');
	// <?php if($_SESSION['level']!=md5('c')){ ?> id_sub = $('#id_sub').combobox('getValue'); <?php } ?>
	// if(bln==undefined || bln=='') $.messager.alert('Peringatan','Bulan Belum dipilih !');
	// <?php if($_SESSION['level']!=md5('c')){ ?>else if(id_sub==undefined || id_sub=='') $.messager.alert('Peringatan','Unit Kerja Belum dipilih !'); <?php } ?>
	// else if(ta==undefined || ta=='') $.messager.alert('Peringatan','Tahun Belum dipilih !');
	// else{
		// $('#dgfull').datagrid('load',{
			// <?php if($_SESSION['level']!=md5('c')){ ?> id_sub: id_sub, <?php } ?>
			// ta: ta,
			// bln: bln,
			// id_sum: id_sum
		// });
	// }	
}

function CetakDaftar(){
	var basket = $('#dgfull').datagrid('getData');
	var sd = $('#id_sumber').combobox('getText');
	if(bln==undefined || bln=='') $.messager.alert('Peringatan','Bulan Belum dipilih !');
	<?php if($_SESSION['level']!=md5('c')){ ?>else if(id_sub==undefined || id_sub=='') $.messager.alert('Peringatan','Unit Kerja Belum dipilih !'); <?php } ?>
	else if(ta==undefined || ta=='') $.messager.alert('Peringatan','Tahun Belum dipilih !');
	else{
		$.loader.open($dataLoader);
		$.post( "./print/rekap_persediaan.php", { basket : basket.rows, bulan : blnt, sd:sd,
				<?php if($_SESSION['level']!=md5('c')){ ?> id_sub: id_sub, <?php } ?> })
		.done(function( data ) {
			if(data.success==false) alert(data.pesan);
			window.location.href = data.url;
			$.loader.close($dataLoader);
		});
	}
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
