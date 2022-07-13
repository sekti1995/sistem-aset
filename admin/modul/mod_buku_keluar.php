 <div class="dtabel" style="width:100%;height:100%;background:white">	
 <table id="dgfull"class="easyui-datagrid"
	url="./model/lap_buku_keluar.php" fit="true" showFooter="true"
	toolbar="#toolbar" title="Buku Pengeluaran Barang Persediaan"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
<th field="tgl_keluar" width="50" align="left" halign="center">TGL KELUAR</th>
<th field="nomor" width="50" align="left" halign="center">Nomor</th>
<th field="nama_barang" width="80" align="left" halign="center">NAMA BARANG</th>
<th field="jml_barang" width="50" align="center" halign="center">BANYAKNYA</th>
<th field="hrg_barang" width="70" align="right" halign="center">HARGA SATUAN (Rp.)</th>
<th field="tot_harga" width="70" align="right" halign="center">JUMLAH HARGA (Rp.)</th>
<th field="untuk" width="90" align="left" halign="center">UNTUK</th>
<th field="tgl_serah" width="50" align="left" halign="center">TGL PENYERAHAN</th>
<th field="ket" width="20" align="left" halign="center">KET</th>
</tr>
</thead>
</table>
</div>
<div id="toolbar">
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="cetakDaftar()">Cetak Excel</a>
<div style="float: right; margin-right: 5px;">
	<a href="#" class="easyui-linkbutton" plain="true" iconCls="icon-search" onclick="javascript:$('#dls').dialog('open')">Pencarian</a>
</div>
</div>



<div id="dls" class="easyui-dialog" style="width:450px;height:300px;padding:10px 20px"
closed="true" buttons="#dls-buttons" title="Pencarian Data Buku Pengeluaran">
<div class="ftitle">Pencarian Data Buku Pengeluaran</div>
<form id="fms" method="post">
	<?php if($_SESSION['level']!=md5('c')){ ?> 
	<div class="fitem">
		<label>Unit</label>: 
		<input class="easyui-combobox" style="width:280px;" id="id_sub" name="id_sub"/>
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
	</div>
	<div class="fitem">
		<label>Untuk</label>: 
		<input class="easyui-combobox" style="width:280px;" id="untuk" name="untuk"/>
		<script>
		$('#untuk').combobox({
			url:'./model/cb_sub2_unit.php',
			valueField:'id',
			textField:'text',
			filter: function(q, row){
				var opts = $(this).combobox('options');
				return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
			},
			onSelect: function(rec){
			}
		});
		</script>
	</div>	
	<?php } ?>
	<div class="fitem">
		<label>Sumber</label>: 
		<input class="easyui-combobox" style="width:180px;" id="id_sumber" name="id_sumber"/>
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
		<label>Tanggal Awal</label>: 	
		<input class="easyui-datebox" data-options="formatter:myformatter,parser:myparser,required:true" style="width:90px;" id="tglawal" name="tglawal" required="true"/>
	</div>
	<div class="fitem">
		<label>Tanggal Akhir</label>: 	
		<input class="easyui-datebox" style="width:90px;" id="tglakhir" name="tglakhir" data-options="formatter:myformatter,parser:myparser,required:true" required="true"/>
	</div>
	<div class="fitem">
		<label>Tahun Anggaran</label>: 	
		<input class="easyui-combobox" style="width:70px;" id="ta" name="ta" />
		<script>
		$('#ta').combobox({
			url:'./model/cb_ta.php',
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
var ta; var id_sub; var bln; var id_sumber; var untuk; var tglawal; var tglakhir; var sumber;
function doSearch(){
	ta = $('#ta').combobox('getValue');
	tglawal = $('#tglawal').datebox('getValue');
	tglakhir = $('#tglakhir').datebox('getValue');
	id_sumber = $('#id_sumber').combobox('getValue');
	sumber = $('#id_sumber').combobox('getText');
	<?php if($_SESSION['level']!=md5('c')){ ?> id_sub = $('#id_sub').combobox('getValue'); untuk = $('#untuk').combobox('getValue'); <?php } ?>
	$('#dgfull').datagrid('load',{
		<?php if($_SESSION['level']!=md5('c')){ ?> id_sub: id_sub, untuk: untuk, <?php } ?>
		ta: ta,
		tglawal: tglawal,
		tglakhir: tglakhir,
		id_sumber : id_sumber
	});
}

function cetakDaftar(){
	$.loader.open($dataLoader);
	// console.log(ta);
	// console.log(tglawal);
	// console.log(tglakhir);
	// console.log(id_sumber);
	// console.log(id_sub);
	ta = $('#ta').combobox('getValue');
	tglawal = $('#tglawal').datebox('getValue');
	tglakhir = $('#tglakhir').datebox('getValue');
	id_sumber = $('#id_sumber').combobox('getValue');
	$.post( "./print/buku_keluar.php", { 
		ta : ta, tglawal: tglawal, tglakhir: tglakhir, id_sumber: id_sumber, sumber: sumber,<?php if($_SESSION['level']!=md5('c')){ ?> id_sub: id_sub <?php } ?>
	})
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
