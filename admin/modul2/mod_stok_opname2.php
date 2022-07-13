 <div class="dtabel" style="width:100%;height:100%;background:white">	
	<div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'north', collapsible:false" style="height:90px;padding:10px" title="Input Stok Opname">
		<form id="fm" method="post">
		<!--<div class="fitem">
			<label>Sub Unit : </label>
			<input class="easyui-combobox" style="width:310px;" id="id_sub" name="id_sub" <?php if($peran==md5('3')) echo 'readonly'; ?> required="true"/>
			<label></label>
			<label>Nomor : </label>
			<input class="easyui-textbox" style="width:100px;" id="nomor" name="nomor" required="true"/>
			<label></label>
			<label>Tanggal : </label>
			<input class="easyui-datebox" style="width:100px;" id="tanggal" name="tanggal" required="true" data-options="formatter:myformatter,parser:myparser,required:true"/>
			<label></label>
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" plain="false" onclick="cariSO()" style="width:70px">Cari</a>
		</div>-->
		<div class="fitem">
			Sub Unit :
			<input class="easyui-combobox" style="width:310px;" id="id_sub" name="id_sub" <?php if($_SESSION['level']==md5('c')) echo 'readonly'; ?> required="true"/>
			Nomor :
			<input class="easyui-textbox" style="width:100px;" id="nomor" name="nomor" required="true"/>
			Tanggal :
			<input class="easyui-datebox" style="width:100px;" id="tanggal" name="tanggal" required="true" data-options="formatter:myformatter,parser:myparser,required:true" validType="validDate"/>
			<label></label>
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" plain="false" onclick="cariSO()" style="width:70px">Cari</a>
		</div>
		</form>
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
			id_sub = rec.id;
		}
		});
		</script>
	</div>
 <div data-options="region:'center'" style="height:100%;padding:0px">
 <table id="dgfull"class="easyui-datagrid" fit="true"
	toolbar="#toolbar" title="Input Barang" pagination="false" 
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
<th rowspan="3" data-options="field:'nama_gud',width:75, align:'left', halign:'center',formatter:function(value,row){
	return row.nama_gud; }">Tempat</th>
<th rowspan="3" data-options="field:'nama_bar',width:95, align:'left', halign:'center',formatter:function(value,row){
	return row.nama_bar; }">Nama Barang</th>
<th rowspan="3" data-options="field:'nama_sumber',width:95, align:'left', halign:'center',formatter:function(value,row){
	return row.nama_sumber; }">Sumber Dana</th>
<th colspan="4">Administrasi</th>
<th colspan="4">Opname</th>
<th field="ket" width="50" align="left" halign="center" rowspan="3" data-options="editor: {type:'textbox'}">Ket</th>
<th field="id_bar" width=80 align="left" halign="center" editor="textbox" hidden  rowspan="3">id_bar</th>
<th field="id_gud" width=80 align="left" halign="center" editor="textbox" hidden  rowspan="3">id_gud</th>
<th field="id_sum" width=80 align="left" halign="center" editor="textbox" hidden  rowspan="3">id_sum</th>
<th field="kd_kel" width=80 align="left" halign="center" editor="textbox" hidden  rowspan="3">kd_kel</th>
<th field="kd_sub" width=80 align="left" halign="center" editor="textbox" hidden  rowspan="3">kd_sub</th>
<th field="kd_sub2" width=80 align="left" halign="center" editor="textbox" hidden  rowspan="3">kd_sub2</th>
</tr>
<tr>
<th colspan="2">Barang</th>
<th colspan="2">Harga</th>
<th colspan="2">Barang</th>
<th colspan="2">Harga</th>
</tr>
<tr>
<th field="jml_admin" width="35" align="center" data-options="editor: {type:'textbox', options:{ readonly:'true'}}">Jumlah</th>
<th field="sat_admin" width="45" align="center" halign="center" data-options="editor: {type:'textbox', options:{ readonly:'true'}}">Satuan</th>
<th field="hrgsat_admin" width="45" align="right" halign="center" data-options="editor: {type:'textbox', options:{ readonly:'true'}}">Satuan(Rp)</th>
<th field="hrgtot_admin" width="45" align="right" halign="center" data-options="editor: {type:'textbox', options:{ readonly:'true'}}">Jumlah(Rp)</th>
<th field="jml_so" width="35" align="center" data-options="editor: {type:'textbox', options:{ required:'true'}}">Jumlah</th>
<th field="sat_so" width="45" align="center" halign="center" data-options="editor: {type:'textbox', options:{ readonly:'true'}}">Satuan</th>
<th field="hrgsat_so" width="45" align="right" halign="center" data-options="editor: {type:'textbox', options:{ readonly:'true'}}">Satuan(Rp)</th>
<th field="hrgtot_so" width="45" align="right" halign="center" data-options="editor: {type:'textbox', options:{ readonly:'true'}}">Jumlah(Rp)</th>
</tr>
</thead>
</table>
</div>
</div>
<div id="toolbar">
<!--<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="append()">Tambah</a>
<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="removeit()">Hapus</a>-->
<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-ok',plain:true" onclick="accept()">Setuju</a>
<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-undo',plain:true" onclick="reject()">Batal</a>
<div style="float: right; margin-right: 5px;">
	<a href="javascript:void(0)" id="simpanSO" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="saveSO()">Simpan SO</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="batalSO()">Bersihkan</a>
</div>
</div>

<script type="text/javascript">
$(function(){
	$('#dgfull').datagrid({
		url:"./model/stok_opname.php",
		singleSelect:true,
		showFooter:true,
		onClickCell: onClickCell,
		onEndEdit: onEndEdit,
		onBeginEdit: onBeginEdit,
		onBeforeEdit: onBeforeEdit,
		remoteSort:false,
		onLoadSuccess:function(data){
			if(cari=='ya'){
				if(data.id===null){
					$.messager.confirm('Konfirmasi', 'Data Stok Opname tidak ditemukan!<br> Ingin Mencatatat Data Stok Opname Baru ?', 
					function(r){
						if (!r){
							$('#dgfull').datagrid('loadData', {"total":0,"rows":[],"footer":[]});
							batalSO();
						}
					});
					$('#simpanSO').show();
				}else{ 
					$('#simpanSO').hide();
					urlu = './aksi.php?module=stok_opname&oper=edit&id_ubah='+data.id;
					//$.messager.show({ title: 'Error', msg: 'Data Stok Opname sudah ada !' });
				}	
				cari = undefined;
			}
		},
		autoLoad:false,
		onBeforeLoad:function(){
			var opts = $(this).datagrid('options');
			return opts.autoLoad;
		}
	});
	<?php if($_SESSION['level']==md5('c')){ ?> $('#id_sub').combobox('textbox').css('background-color','#EEEEEE'); <?php } ?>
});
var id_bar; var datser; var id_sub; var cari;
var format_options = {aSep:'.', aNeg:'', aDec: ',',aPad: false};
var urlu = './aksi.php?module=stok_opname&oper=add';

function cariSO(){
	if($('#fm').form('validate')==true){
		if(validasiCombo('fm')==false) return false;
		cari = 'ya'; var dd = [];
		$('#fm').form().find('[name]').each(function() {
			dd[this.name] = this.value;  
		});
		datser = dd;
		editIndex = undefined;
		$('#dgfull').datagrid('options').autoLoad = true;
		$('#dgfull').datagrid('load',{
			<?php if($_SESSION['level']!=md5('c')){ ?> id_sub: $('#id_sub').combobox('getValue'), <?php } ?>
			tanggal: $('#tanggal').datebox('getValue'),
			nomor: $('#nomor').val()
		});
	}
}

function batalSO(){
	<?php if($_SESSION['level']!=md5('c')){ ?> $('#id_sub').combobox('clear'); <?php } ?>
	$('#nomor').textbox('clear');
	$('#tanggal').datebox('clear');
	$('#dgfull').datagrid('load',{
		<?php if($_SESSION['level']!=md5('c')){ ?> id_sub: '', <?php } ?>
		tanggal: '',
		nomor: ''
	});
	urlu = './aksi.php?module=stok_opname&oper=add';
	datser = undefined;
	editIndex = undefined;
}
function saveSO(){
	$.loader.open($dataLoader);
	var basket = $('#dgfull').datagrid('getData');
	var ceki; var beda;
	$.each(basket.rows, function(i,lab){
		if(!lab['jml_so']){ ceki = i; return false; }
		else if(!lab['hrgsat_so']){ ceki = i; return false; }
		if(lab['jml_so']!=lab['jml_admin'] || lab['hrgsat_admin']!=lab['hrgsat_so']) beda = 'ya';
	});
	
	if($('#fm').form('validate')==false){
		$.loader.close($dataLoader);
		$.messager.show({ title: 'Error', msg: 'Data Pengeluaran Barang belum diisi' });
	}else if(validasiCombo('fm')==false){
		$.loader.close($dataLoader);
		return;
	}else if(editIndex!=undefined){
		$.loader.close($dataLoader);
		$.messager.show({ title: 'Error', msg: 'Setujui dulu perubahan data barang!' }); 
	}else if(basket.total==0){
		$.loader.close($dataLoader);
		$.messager.show({ title: 'Error', msg: 'Data Barang belum diisi' }); 
	}else if(ceki!=undefined){
		$.loader.close($dataLoader);
		$('#dgfull').datagrid('selectRow', ceki).datagrid('beginEdit', ceki); 
		$.messager.alert('Peringatan','Field dengan warna merah harus diisi !');
		editIndex = ceki;
	}else if(beda=='ya' && myparser(basket.tgl_akhir)>myparser($('#tanggal').datebox('getValue'))){
		$.loader.close($dataLoader);
		$.messager.alert('Peringatan','Tidak bisa membuat Stok Opname, ada transaksi barang yang terjadi setelah tanggal ini !');
	}else{	
		var formData = {}; var ubah = '';
		$('#fm').form().find('[name]').each(function() {
			formData[this.name] = this.value;  
		});
		if(datser!=undefined){ //jika edit
			$.each( formData, function( i, l ){
				if(formData[i]!=datser[i]) ubah += i+'::'+datser[i]+'|'+formData[i]+'||';
			});
		}
		
		$.ajax({
			type: "POST",
			url: urlu,
			data: { form: formData, basket: basket.rows, ubahform : ubah },
			complete: function(){
				$.loader.close($dataLoader);
			},		
			success: function(result){
				var result = eval('('+result+')');
				if (result.success==false){
					if(result.error=='nomor_sama'){ 
						$.messager.show({ title: 'Error', msg: result.pesan });
						return;
					}else $.messager.show({ title: 'Error', msg: result.pesan });
				} else {
					$.messager.show({ title: 'Sukses', msg: result.pesan }); 
					batalSO();
					//else $('#dgfull').datagrid('reload');
				}
			}
		});
	}
}

	var editIndex = undefined;
	function endEditing(){
		if (editIndex == undefined){return true}
		if ($('#dgfull').datagrid('validateRow', editIndex)){
			var ed = $('#dgfull').datagrid('getEditors', editIndex); // get the editor
			var barang = $(ed[12].target).val();
			var gudang = $(ed[13].target).val();
			var sumber = $(ed[14].target).val();
			var harga = $(ed[9].target).val();

			var sama = "";
			var dgfull = $('#dgfull').datagrid('getData');
			$.each(dgfull.rows, function(i,lab){
				if(lab['id_bar']==barang && lab['hrgsat_so']==harga && lab['id_gud']==gudang && lab['id_sum']==sumber && i!=editIndex) sama = 'ya';
			});
			if(validasiCombo2(ed)==false) return false;
			if(sama=='ya'){
				$.messager.show({ title: 'Error', msg: "Barang dengan spesifikasi ini Sudah ada dalam daftar!" }); 
				return false;
			}else{
				$('#dgfull').datagrid('endEdit', editIndex);
				editIndex = undefined;
				var basket = $('#dgfull').datagrid('getData');
				$('#dgfull').datagrid('loadData', basket);
				$('#dgfull').datagrid('sort', {
					sortName: 'kd_kel,kd_sub,kd_sub2',
					sortOrder: 'asc,asc,asc'
				});
				return true;
			}	
		} else {
			$.messager.alert('Peringatan','Field dengan warna merah harus diisi !');
			return false;
		}
	}
	function onClickCell(index, field){
		if (editIndex != index){
			if (endEditing()){
				$('#dgfull').datagrid('selectRow', index)
						.datagrid('beginEdit', index);
				var ed = $('#dgfull').datagrid('getEditor', {index:index,field:field});
				if (ed){
					($(ed.target).data('textbox') ? $(ed.target).textbox('textbox') : $(ed.target)).focus();
				}
				editIndex = index;
			} else {
				setTimeout(function(){
					$('#dgfull').datagrid('selectRow', editIndex);
				},0);
			}
		}
	}
	function onBeginEdit(rowIndex){
		var editors = $('#dgfull').datagrid('getEditors', rowIndex);
		var gudang = $(editors[0].target);
		var barang = $(editors[1].target);
		var sumber = $(editors[2].target);
		var satmin = $(editors[4].target);
		var satop = $(editors[8].target);
		var fbar = $(editors[12].target);
		var fgud = $(editors[13].target);
		var fsum = $(editors[14].target);
		
		var kdKel = $(editors[15].target);
		var kdSub = $(editors[16].target);
		var kdSub2 = $(editors[17].target);
		
		var jmlmin = $(editors[3].target);
		var hrgmin = $(editors[5].target);
		var totmin = $(editors[6].target);
		
		var jumlah = $(editors[7].target);
		var harga = $(editors[9].target);
		var hargatot = $(editors[10].target);
		
		jumlah.textbox('textbox').bind('keyup',function(e){
			var $this = $(this);
			var num = $this.val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
			$this.val(num);
			var jml = num.replace(/[^0-9,]/g,'').replace(",",".");
			var hrg = harga.textbox('getValue').replace(/[^0-9,]/g,'').replace(",",".");
			var tot = hrg*jml;
			var tots = accounting.formatMoney(tot, '', 0, '.', ',');
			hargatot.textbox('setValue', tots);
		});
		harga.textbox('textbox').bind('keyup',function(e){
			var $this = $(this);
			var num = $this.val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
			$this.val(num);
			var hrg = num.replace(/[^0-9,]/g,'').replace(",",".");
			var jml = jumlah.textbox('getValue').replace(/[^0-9,]/g,'').replace(",",".");
			var tot = hrg*jml;
			var tots = accounting.formatMoney(tot, '', 0, '.', ',');
			hargatot.textbox('setValue', tots);
		});
		
		//var a = barang.combobox('getValue');
		//var b = gudang.combobox('getValue');
		//var c = sumber.combobox('getValue');
		var a = fbar.textbox('getValue');
		var b = fgud.textbox('getValue');
		var c = fsum.textbox('getValue');
		
		barang.combobox({
			onSelect: function(rec){
				satmin.textbox('setValue', rec.simbol);
				satop.textbox('setValue', rec.simbol);
				fbar.textbox('setValue', rec.id_bar);
				
				kdKel.textbox('setValue', rec.kd_kel);
				kdSub.textbox('setValue', rec.kd_sub);
				kdSub2.textbox('setValue', rec.kd_sub2);
				
				var idg = gudang.combobox('getValue');
				var ids = sumber.combobox('getValue');
				if(idg != "" && ids!=''){
					$.post( "./model/cek_stok.php", { id_sub : $('#id_sub').combobox('getValue'), id_sum : ids,
													  id_bar : rec.id_bar, jenis : 'so', id_gud : idg })
					.done(function( data ) {
						jmlmin.textbox('setValue', data.saldo);
						hrgmin.textbox('setValue', data.harga);
						totmin.textbox('setValue', data.total);
					});
				}
			}
		}).combobox('setValue',a);
		gudang.combobox({
			onSelect: function(rec){
				fgud.textbox('setValue', rec.id_gud);
				var idb = barang.combobox('getValue')
				var ids = sumber.combobox('getValue')
				if(idb!='' && ids!=''){
					$.post( "./model/cek_stok.php", { id_sub : $('#id_sub').combobox('getValue'), id_sum : ids,
													  id_bar : idb, jenis : 'so', id_gud : rec.id_gud })
					.done(function( data ) {
						jmlmin.textbox('setValue', data.saldo);
						hrgmin.textbox('setValue', data.harga);
						totmin.textbox('setValue', data.total);
					});
				}
			},
			onLoadSuccess: function(){
				var g = fgud.textbox('getValue')
				if(g==undefined || g=="") fgud.textbox('setValue', gudang.combobox('getValue'));
			}
		}).combobox('setValue',b);
		sumber.combobox({
			onSelect: function(rec){
				fsum.textbox('setValue', rec.id);
				var idb = barang.combobox('getValue')
				var idg = gudang.combobox('getValue')
				if(idb!='' && idg!=''){
					$.post( "./model/cek_stok.php", { id_sub : $('#id_sub').combobox('getValue'), id_sum : rec.id,
													  id_bar : idb, jenis : 'so', id_gud : idg })
					.done(function( data ) {
						jmlmin.textbox('setValue', data.saldo);
						hrgmin.textbox('setValue', data.harga);
						totmin.textbox('setValue', data.total);
					});
				}
			},
			onLoadSuccess: function(){
				var s = fsum.textbox('getValue')
				if(s==undefined || s=="") fsum.textbox('setValue', sumber.combobox('getValue'));
			}
		}).combobox('setValue',c);
		satmin.textbox('textbox').css('background-color','#EEEEEE');
		satop.textbox('textbox').css('background-color','#EEEEEE');
		jmlmin.textbox('textbox').css('background-color','#EEEEEE');
		hrgmin.textbox('textbox').css('background-color','#EEEEEE');
		totmin.textbox('textbox').css('background-color','#EEEEEE');
		harga.textbox('textbox').css('background-color','#EEEEEE');
		hargatot.textbox('textbox').css('background-color','#EEEEEE');

	}
	
	function onBeforeEdit(row){
		var comgud = $(this).datagrid('getColumnOption','nama_gud');
		var combar = $(this).datagrid('getColumnOption','nama_bar');
		var comsum = $(this).datagrid('getColumnOption','nama_sumber');
		//console.log(comgud;
		combar.editor = {
			type: 'combobox',
			options:{
				valueField:'id_bar',
				textField:'nama_bar',
				method:'get',
				url:'./model/cb_barang_ada.php?id='+id_sub,
				required:true,
				filter: function(q, row){
					var opts = $(this).combobox('options');
					return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
				}
			}
		}
		comgud.editor = {
			type: 'combobox',
			options:{
				valueField:'id_gud',
				textField:'nama_gud',
				method:'get',
				url:'./model/cb_gudang.php?cek&id='+id_sub,
				required:true,
				filter: function(q, row){
					var opts = $(this).combobox('options');
					return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
				}
			}
		}
		
		comsum.editor = {
			type: 'combobox',
			options:{
				valueField:'id',
				textField:'text',
				method:'get',
				url:'./model/cb_sumber_dana.php?cek&id='+id_sub,
				required:true,
				filter: function(q, row){
					var opts = $(this).combobox('options');
					return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
				}
			}
		}
		
	}
	
	function onEndEdit(index, row){
		var ed = $(this).datagrid('getEditor', {
			index: index,
			field: 'nama_bar'
		});
		var ed1 = $(this).datagrid('getEditor', {
			index: index,
			field: 'nama_gud'
		});
		var ed2 = $(this).datagrid('getEditor', {
			index: index,
			field: 'nama_sumber'
		});
		
		row.nama_bar = $(ed.target).combobox('getText');
		row.nama_gud = $(ed1.target).combobox('getText');
		row.nama_sumber = $(ed2.target).combobox('getText');
		
	}
	function append(){
		if(datser==undefined) $.messager.alert('Peringatan','Isilah data nomor dan tanggal dahulu, Lalu klik tombol cari !');
		else{
			if (endEditing()){
				$('#dgfull').datagrid('appendRow',{status:'P'});
				editIndex = $('#dgfull').datagrid('getRows').length-1;
				$('#dgfull').datagrid('selectRow', editIndex)
						.datagrid('beginEdit', editIndex);
			}
		}
	}
	function removeit(){
		if (editIndex == undefined){return}
		$('#dgfull').datagrid('cancelEdit', editIndex)
				.datagrid('deleteRow', editIndex);
		editIndex = undefined;
	}
	function accept(){
		var rows = $('#dgfull').datagrid('getChanges');
		if (endEditing()){
			$('#dgfull').datagrid('acceptChanges');
		}
	}
	function reject(){
		$('#dgfull').datagrid('rejectChanges');
		editIndex = undefined;
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
	width:100px;
	}
	.fitem2{
	margin-bottom:5px;
	}
	.fitem2 label{
	display:inline-block;
	width:60px;
	}
	.fitem input{
	width:160px;
	}
</style>	
