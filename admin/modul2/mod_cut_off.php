 <?php
	$bln = date("m");
	if($bln == "01"){
		$title_co = " CUT OFF TAHUN ".date("Y");
	} else {
		$title_co = "CUT OFF HANYA DAPAT DILAKUKAN SETIAP BULAN JANUARI";
		//echo "<script>$('#btn-cut-off').linkbutton('disable');</script>";
	}
 ?>
 <div class="dtabel" style="width:100%;height:100%;background:white">	
 <table id="dgfull"class="easyui-datagrid"
	url="./model/saldo_akhir.php" fit="true" showFooter="true"
	toolbar="#toolbar" title="<?php echo $title_co; ?>"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
<th field="nama_barang" width="150" align="left" halign="center" rowspan="2">NAMA BARANG</th>
<th field="satuan" width="50" align="left" halign="center" rowspan="2">SATUAN</th>
<th field="jml_in" width="50" align="left" halign="center" rowspan="2">i</th>
<th field="jml_out" width="50" align="left" halign="center" rowspan="2">o</th>
<th field="harga" width="70" align="right" halign="center" rowspan="2">HARGA SATUAN</th>
<th width="130" align="center" colspan="2">SISA TAHUN INI</th>
</tr>
<tr>
<th field="jumlah" width="50" align="center" halign="center">JML</th>
<th field="total" width="70" align="right" halign="center">JML HARGA</th>
</tr>
</thead>
</table>
</div>
<div id="toolbar"> 
	<div>
		<form id="fms" method="post">
		<table cellpadding="5" width='100%'>
		<?php if($_SESSION['level']!=md5('c')){ ?>
		<tr>
		<td>Nama Sub Unit : 
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
				$('#id_sumber').combobox('reload', './model/cb_sumber_dana.php?cek&id='+rec.id);
				$('#id_gudang').combobox('clear');
				$('#id_gudang').combobox('reload', './model/cb_gudang.php?cek&id='+rec.id);
			}
		});
		</script> 
		<?php } ?>
		&nbsp; 
		Sumber Dana : 
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
		&nbsp; 
		Tahun : <input class="easyui-combobox" style="width:60px;" id="thn" name="thn"  required="true"/>
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
			&nbsp; 
		Kelompok : <input class="easyui-combobox" style="width:160px;" id="id_kelompok" name="id_kelompok" required="true"/>
			<script>
			$('#id_kelompok').combobox({
				url:'./model/cb_kelompok.php',
				valueField:'id_kel',
				textField:'nama_kel',
				filter: function(q, row){
					var opts = $(this).combobox('options');
					return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
				}
			});
			</script>
			&nbsp; 
			<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-search" onclick="doSearch()" style="width:90px">Cari</a>
			</td>
			<td align='right'>
			<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="upDataAwal()" id='btn-cut-off' style="width:90px">CUT OFF</a>
			</td>
		</tr>	
		</table>
		</form> 
	</div>
	<div style="clear:both"></div>
</div>




<div id="dlg" class="easyui-dialog" style="width:370px;height:260px;padding:10px 20px"
closed="true" buttons="#dlg-buttons2">
<div class="ftitle">CUT OFF</div> 
<form id="fm2" method="post" enctype="multipart/form-data">
	<div class="fitem">
		<label>Gudang</label>: 
		<input class="easyui-combobox" style="width:150px;" id="id_gudang" name="id_gudang" required="true"/>
		<script>
		$('#id_gudang').combobox({
			url:'./model/cb_gudang.php',
			valueField:'id_gud',
			textField:'nama_gud',
			filter: function(q, row){
				var opts = $(this).combobox('options');
				return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
			}
		});
		</script>
	</div> 
	<div class="fitem">
		<label>Tanggal BA</label>: 
		<input class="easyui-datebox" type="text" name="tgl_ba" id="tgl_ba" data-options="formatter:myformatter,parser:myparser,required:true," style="width:100px;" validType="validDate">
	</div> 
	<div class="fitem">
		<label>Nomor BA</label>: 
		<input class="easyui-textbox" type="text" name="no_ba" id="no_ba" data-options="required:true" style="width:100px;">
	</div> 
</form>
</div>
<div id="dlg-buttons2">
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveCutOff()" id="saveForm" style="width:90px">Save</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
</div>


<script type="text/javascript">

function upDataAwal(){
	$('#dlg').dialog('open').dialog('setTitle','Cut Off');
	$('#tgl_ba').datebox('clear');
	$('#no_ba').textbox('clear');
}

var ta; var id_sub; var smstr = 'x' ; var smstrt; var id_sum;
function doSearch(){
	if($('#fms').form('validate')==false) return;
	ta = $('#thn').combobox('getValue'); 
	id_sum = $('#id_sumber').combobox('getValue'); 
	id_kelompok = $('#id_kelompok').combobox('getValue');
	id_gudang = $('#id_gudang').combobox('getValue');
	<?php if($_SESSION['level']!=md5('c')){ ?> id_sub = $('#id_sub').combobox('getValue'); <?php } ?>
	if(  smstr=='') ;
	<?php if($_SESSION['level']!=md5('c')){ ?>else if(id_sub==undefined || id_sub=='') $.messager.alert('Peringatan','Unit Kerja Belum dipilih !'); <?php } ?>
	else if(ta==undefined || ta=='') $.messager.alert('Peringatan','Tahun Belum dipilih !');
	else{
		$('#dgfull').datagrid('load',{
			<?php if($_SESSION['level']!=md5('c')){ ?> id_sub: id_sub, <?php } ?>
			ta: ta, 
			id_gudang: id_gudang,
			id_kelompok: id_kelompok,
			id_sum: id_sum
		});
	}	
}
function saveCutOff(){	
	var basket = $('#dgfull').datagrid('getData');
	console.log(basket);
	var thn = $('#thn').combobox('getValue');
	var id_kelompok = $('#id_kelompok').combobox('getValue');
	var id_gudang = $('#id_gudang').combobox('getValue');
	var id_sumber = $('#id_sumber').combobox('getValue');
	var tgl_ba = $('#tgl_ba').datebox('getValue');
	var no_ba = $('#no_ba').textbox('getValue');
	var id_sub = $('#id_sub').combobox('getValue');
	
		$.ajax({
			type: "POST",
			url: './cut_off.php',
			data: { basket: basket.rows, thn : thn, id_kelompok, id_kelompok, id_gudang, id_gudang, id_sumber, id_sumber, tgl_ba : tgl_ba, no_ba : no_ba, id_sub : id_sub },
			beforeSend: function() {
				$.loader.open($dataLoader);
			},
			complete: function(){
				$.loader.close($dataLoader);
			},
			success: function(data){
				console.log(data);
				var data = eval('('+data+')');
				if (data.success==false){
					$.messager.show({ title: 'Error', msg: data.pesan });
				} else {
					$.messager.show({ title: 'Sukses', msg: data.pesan });	
					$('#dgfull').datagrid('loadData', {"total":0,"rows":[],"footer":[]});
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
	width:140px;
	}
	.fitem input{
	width:160px;
	}
</style>	
