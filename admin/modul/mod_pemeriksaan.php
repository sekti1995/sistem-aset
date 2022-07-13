 <div class="dtabel" style="width:100%;height:100%;background:white">	
 <table id="dgfull"class="easyui-datagrid"
	url="./model/pengadaan.php" fit="true"
	toolbar="#toolbar" pagination="true" title="Input Pemeriksaan Barang Persediaan"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
<th field="unit_kerja" width="200" align="left" halign="center">Nama SKPD / Unit Kerja</th>
<th field="ta" width="40" align="center">TA</th>
<th field="nama_pengadaan" width="150" align="left" halign="center">Nama Pengadaan</th>
<th field="tanggal" width="80" align="left" halign="center">Tanggal</th>
<th field="nama_penyedia" width="200" align="left" halign="center">Nama Penyedia</th>
<th field="no_kontrak" width="100" align="left" halign="center">Nomor Kontrak</th>
<th field="stat" width="80" align="center" halign="center">Status</th>
</tr>
</thead>
</table>
</div>
<div id="toolbar">
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editPemeriksaan()">Pemeriksaan</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyPemeriksaan()">Batal Pemeriksaan</a>
<div style="float: right; margin-right: 5px;">
	<a href="#" class="easyui-linkbutton" plain="true" iconCls="icon-search" onclick="viewSearch()">Pencarian</a>
</div>
</div>
<div id="dlg" class="easyui-dialog" style="width:300px;height:200px;padding:5px 10px"
closed="true" buttons="#dlg-buttons">
<form id="fm" method="post">
<input type="hidden" name="sp" id="sp"/>
<table cellpadding="2" border=0>
<tr>
<td>No Pemeriksaan</td>
<td colspan="2">: <input class="easyui-textbox" type="text" name="no_ba_pemeriksaan" id="no_ba_pemeriksaan" data-options="required:true"  style="width:100px;" ></input></td>
</tr>
<tr>
<td>Tgl Pemeriksaan</td>
<td colspan="2">: <input class="easyui-datebox" type="text" name="tgl_pemeriksaan" id="tgl_pemeriksaan" data-options="formatter:myformatter,parser:myparser,required:true" validType="validDate" style="width:100px;"></td>
</tr>
</table>
</form>
</div>
<div id="dlg-buttons">
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="savePemeriksaan()" style="width:90px">Save</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
</div>


<div id="dls" class="easyui-dialog" style="width:450px;height:380px;padding:10px 20px"
closed="true" buttons="#dls-buttons">
<div class="ftitle">Pencarian Pemeriksaan</div>
<form id="fms" method="post">
<table cellpadding="5">
<?php if($_SESSION['level']!=md5('c')){ ?>
<tr>
<td>Nama Sub Unit</td>
<td colspan="3">: 
<input class="easyui-combobox" style="width:250px;" id="id_unit_search" name="id_unit_search"/>
<script>
$('#id_unit_search').combobox({
    url:'./model/cb_sub2_unit.php',
    valueField:'id',
    textField:'text',
    filter: function(q, row){
		var opts = $(this).combobox('options');
		return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
	}
});
</script></td>
</tr>
<?php } ?>
<tr>
	<td>TA</td>
	<td colspan="3">: <input class="easyui-textbox" type="text" name="ta_search" id="ta_search" size="5"></input></td>
</tr>
<tr>
	<td>Nama Pengadaan</td>
	<td colspan="3">: <input class="easyui-textbox" type="text" name="nama_search" id="nama_search" size="25"></input></td>
</tr>
<tr>
	<td>Periode Tanggal</td>
	<td>: <input class="easyui-datebox" type="text" name="tgl_awal_search" id="tgl_awal_search" data-options="formatter:myformatter,parser:myparser" style="width:100px;"></input></td>
	<td>Sampai</td>
	<td><input class="easyui-datebox" type="text" name="tgl_akhir_search" id="tgl_akhir_search" data-options="formatter:myformatter,parser:myparser" style="width:100px;"></input></td>
</tr>
<tr>
	<td>Nama Penyedia</td>
	<td colspan="3">: <input class="easyui-textbox" type="text" name="penyedia_search" id="penyedia_search" size="25"></input></td>
</tr>
<tr>
	<td>No Kontrak</td>
	<td colspan="3">: <input class="easyui-textbox" type="text" name="kontrak_search" id="kontrak_search" size="25"></input></td>
</tr>
<tr>
	<td>Status</td>
	<td colspan="3">: <select class="easyui-combobox" name="status_search" id="status_search" style="width:100px;">
			<option value="1">Pengadaan</option>
			<option value="2">Pemeriksaan</option>
			<option value="3">Penerimaan</option>
		  </select>	
	</td>
</tr>
</table>
</form>
</div>
<div id="dls-buttons">
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="doSearch()" style="width:90px">Cari</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dls').dialog('close')" style="width:90px">Batal</a>
</div>

<script type="text/javascript">

$.extend($.fn.validatebox.defaults.rules, { 
	validDate: {  
		validator: function(value){  
			var date = myparser(value);
			var s = myformatter(date);
			return s==value; 
		},  
		message: 'Isilah dengan tanggal yang valid !'  
	}
}); 
var urlu; var id_bar; var id_sat; var id_gud; var id_kel; var datser;
var format_options = {aSep:'.', aNeg:'', aDec: ',',aPad: false};

function viewSearch(){
	$('#dls').dialog('open').dialog('setTitle','Pencarian Data Pengadaan');
	$('#fms').form('clear');
}	
function doSearch(){
	$('#dgfull').datagrid('load',{
		<?php if($_SESSION['level']!=md5('c')){ ?> id_sub: $('#id_unit_search').combobox('getValue'), <?php } ?>
		ta: $('#ta_search').val(),
		nama: $('#nama_search').val(),
		tgl_awal: $('#tgl_awal_search').datebox('getValue'),
		tgl_akhir: $('#tgl_akhir_search').datebox('getValue'),
		penyedia: $('#penyedia_search').val(), 
		kontrak: $('#kontrak_search').val(),
		status: $('#status_search').combobox('getValue')
	});
}

function editPemeriksaan(){
	var row = $('#dgfull').datagrid('getSelected');
	if (row){
		$('#dlg').dialog('open').dialog('setTitle','Pemeriksaan');
		$('#fm').form('clear');
		$('#fm').form('load',row);
		urlu = './aksi.php?module=pemeriksaan&oper=edit&id_ubah='+row.id;
	}else $.messager.alert('Peringatan','Pilih Data Pengadaan yang akan diperiksa !');	
}

function savePemeriksaan(){	
	if($('#fm').form('validate')==false){
		$.messager.show({ title: 'Error', msg: 'Data Pemeriksaan belum diisi' });
	}else{	
		var formData = {}; var ubah = '';
		$('#fm').form().find('[name]').each(function() {
			formData[this.name] = this.value;  
		});
	
		$.ajax({
			type: "POST",
			url: urlu,
			data: { form: formData },
			beforeSend: function() {
				$.loader.open($dataLoader);
			},
			complete: function(){
				$.loader.close($dataLoader);
			},
			success: function(result){
				console.log(result);
				var result = eval('('+result+')');
				if (result.success==false){
					$.messager.show({ title: 'Error', msg: result.pesan });
				} else {
					$.messager.show({ title: 'Sukses', msg: result.pesan }); 
					$('#dgfull').datagrid('reload');
				}
				$('#dlg').dialog('close');	
			}
		});
	}

}
function destroyPemeriksaan(){
	var rw1 = $('#dgfull').datagrid('getSelected');
	if (rw1){
		$.messager.confirm('Peringatan','Apakah Anda yakin akan menghapus Pemeriksaan ini?',function(r){
		if (r){
			if(rw1.sp=='3') alert("Tidak bisa Membatalkan pemeriksaan, Lakukan pembatalan penerimaan terlebih dahulu!");
			else{
				$.ajax({
					type: "POST",
					url: './aksi.php?module=pemeriksaan&oper=del',
					data: { id_hapus: rw1.id },
					success: function(data){
						$.messager.show({ title: 'Konfirmasi', msg: data });	
						$('#dgfull').datagrid('reload');			
					}
				});	
			}
			}
		},'json');
	}else $.messager.alert('Peringatan','Pilih Pemeriksaan yang akan dihapus dahulu !');	
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
