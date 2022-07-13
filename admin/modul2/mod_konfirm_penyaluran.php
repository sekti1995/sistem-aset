 <div class="dtabel" style="width:100%;height:100%;background:white">	
 <table id="dgfull"class="easyui-datagrid"
	url="./model/konfirm_penyaluran.php" fit="true"
	toolbar="#toolbar" pagination="true" title="Konfirmasi Penyaluran Barang"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
<th field="nm_sub" width="160" align="left" halign="center">Nama Sub2/Unit Kerja</th>
<th field="ta" width="40" align="center">TA</th>
<th field="nomor" width="60" align="left" halign="center">Nomor</th>
<th field="tanggal" width="60" align="center" halign="center">Tanggal</th>
<th field="nm_skpd" width="160" align="left" halign="center">Dari SKPD</th>
<th field="status" width="60" align="left" halign="center">Status</th>
<th field="tgl_terima" width="60" align="center" halign="center">Tgl Diterima</th>
<th field="penerima" width="60" align="left" halign="center">Penerima</th>
</tr>
</thead>
</table>
</div>
<div id="toolbar">

<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="konfirmasi()">Terima Barang</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-browse" plain="true" onclick="lihatTerima()">Lihat Penerimaan</a>
<div style="float: right; margin-right: 5px;">
	<a href="#" class="easyui-linkbutton" plain="true" iconCls="icon-search" onclick="viewSearch()">Pencarian</a>
</div>
</div>
<div id="dlg" class="easyui-dialog" style="width:780px;height:450px;padding:5px 10px"
closed="true" buttons="#dlg-buttons">
<form id="fm" method="post">
<input type="hidden" name="id" id="id"/>
<table cellpadding="2" border=0>
<tr>
<td width="110px">Unit/Sub Unit</td>
<td colspan="3">: 
<input class="easyui-combobox" style="width:310px;" id="id_subt" name="id_subt" readonly required="true"/>
<script>
$('#id_subt').combobox({
    url:'./model/cb_sub2_unit.php',
    valueField:'id',
    textField:'text',
    filter: function(q, row){
		var opts = $(this).combobox('options');
		return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
	},
	onSelect: function(rec){
		id_sub = rec.id;
		<?php if($peran==MD5('1')){ ?> 
			$('#dasar_keluar').combobox('clear'); 
			$('#dasar_keluar').combobox('reload', './model/cb_sppb.php?id='+rec.id);
			$('#basket').datagrid('loadData', {"total":0,"rows":[],"footer":[]});	
		<?php } ?>
	}
});
</script></td>
<td width="170px" style="text-align:center;"> Tahun Anggaran</td>
<td>: <input class="easyui-combobox" style="width:70px;" id="ta" name="ta" readonly required="true"/>
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
</td>
</tr>
<tr>
<td>Tgl Terima</td>
<td colspan="2">: <input class="easyui-datebox" type="text" name="tgl_terima" id="tgl_terima" data-options="formatter:myformatter,parser:myparser,required:true" style="width:100px;"></td>
<td>Tempat Penyimpanan</td>
<td>: <input class="easyui-combobox" style="width:150px;" id="id_gudang" name="id_gudang" required="true"/>
<script>
$('#id_gudang').combobox({
    valueField:'id_gud',
    textField:'nama_gud',
    filter: function(q, row){
		var opts = $(this).combobox('options');
		return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
	}
});
</script></td>
</tr>
</table>
</form></br>
<div style="background:#fff">
   <table id="basket" fitColumns="true" rownumbers="true"  style="width1:150px;height:280px;" title="Data Barang">
	   <thead>
		   <tr>
			   <th data-options="field:'nama_bar',width:150, align:'left', halign:'center',
                        formatter:function(value,row){
                            return row.nama_bar;
                        }">Nama Barang</th>
			   <th field="jumlah" width=70 align="center" data-options="editor: {type:'textbox', options:{ required:'true'}}">Jumlah</th>
			   <th field="nama_sat" width=60 align="center"  data-options="editor: {type:'textbox', options:{ readonly:'true'}}">Satuan</th>
			   <th field="jmlhrg" width=110 align="right" halign="center" data-options="editor: {type:'textbox', options:{ readonly:'true'}}">Harga Total</th>
			   <th field="jmlhrg_asli" width=120 align="right" halign="center" hidden="true" >Harga Asli</th>
			   <th field="id_bar" width=80 align="left" halign="center" editor="textbox" hidden>id_bar</th>
		</tr>
		</thead>
	</table>	
</div>
<!--<div id="tb" style="height:auto">
	<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="append()">Tambah</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="removeit()">Hapus</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="accept()">Setuju</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-undo',plain:true" onclick="reject()">Batal</a>
</div>-->
</div>
<div id="dlg-buttons">
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" id="simpan" onclick="saveKonfirm()" style="width:90px">Save</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
</div>


<div id="dls" class="easyui-dialog" style="width:450px;height:340px;padding:10px 20px"
closed="true" buttons="#dls-buttons">
<div class="ftitle">Pencarian Data Konfirmasi Penyaluran</div>
<form id="fms" method="post">
<table cellpadding="5">
<?php if($peran==md5('1')){ ?>
<tr>
<td>Nama Sub Unit</td>
<td>: 
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
	<td>: <input class="easyui-combobox" style="width:70px;" id="ta_search" name="ta_search"/>
	<script>
	$('#ta_search').combobox({
		url:'./model/cb_ta.php',
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
	<td>Nomor</td>
	<td>: <input class="easyui-textbox" type="text" name="nomor_search" id="nomor_search" size="25"></input></td>
</tr>
<tr>
	<td>Tanggal</td>
	<td>: <input class="easyui-datebox" type="text" name="tgl_search" id="tgl_search" data-options="formatter:myformatter,parser:myparser" style="width:100px;"></input></td>
</tr>
<tr>
<td>Peruntukan</td>
<td>: 
<input class="easyui-combobox" style="width:250px;" id="untuk_search" name="untuk_search"/>
<script>
$('#untuk_search').combobox({
    url:'./model/cb_sub_unit.php?all',
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
$(function(){
	$('#basket').datagrid({
		singleSelect:true,
		showFooter:true,
		/* onClickCell: onClickCell,
		onEndEdit: onEndEdit,
		onBeginEdit: onBeginEdit,
		onBeforeEdit: onBeforeEdit */
	});
	$('#id_subt').combobox('textbox').css('background-color','#EEEEEE');
	$('#ta').combobox('textbox').css('background-color','#EEEEEE');
});

var urlu; var id_bar; var id_sat; var id_gud; var id_kel; var datser; var id_sub;
var format_options = {aSep:'.', aNeg:'', aDec: ',',aPad: false};

function viewSearch(){
	$('#dls').dialog('open').dialog('setTitle','Pencarian Data Konfirmasi Penyaluran');
}	
function doSearch(){
	$('#dgfull').datagrid('load',{
		<?php if($peran!=md5('3')){ ?> id_sub: $('#id_unit_search').combobox('getValue'), <?php } ?>
		ta: $('#ta_search').combobox('getValue'),
		nomor: $('#nomor_search').val(),
		tanggal: $('#tgl_search').datebox('getValue'),
		untuk: $('#untuk_search').combobox('getValue')
	});
}

function konfirmasi(){
	var row = $('#dgfull').datagrid('getSelected');
	if (row){
		if(row.stat==2){
			$.messager.alert('Peringatan','Tidak bisa mengkonfirmasi data yang sudah selesai !');
			return;
		}	
		$('#dlg').dialog('open').dialog('setTitle','Konfirmasi Penerimaan Barang');
		$('#id_gudang').combobox('reload', './model/cb_gudang.php?id='+row.id_subt);
		$('#fm').form('clear');
		$('#fm').form('load',row);
		datser = row;
		$('#basket').datagrid('load', "./model/konfirm_penyaluran_detail.php?id="+row.id);
		$('#simpan').show();
		id_sub = row.id_sub;
		urlu = './aksi.php?module=konfirm_penyaluran&oper=konfirm&id='+row.id;
	}else $.messager.alert('Peringatan','Pilih Data Penyaluran Barang yang akan diterima!');	
}

function lihatTerima(){
	var row = $('#dgfull').datagrid('getSelected');
	if (row){
		$('#dlg').dialog('open').dialog('setTitle','Lihat Penerimaan Barang');
		$('#id_gudang').combobox('reload', './model/cb_gudang.php?id='+row.id_subt);
		$('#fm').form('clear');
		$('#fm').form('load',row);
		$('#basket').datagrid('load', "./model/konfirm_penyaluran_detail.php?id="+row.id);
		$('#simpan').hide();
	}else $.messager.alert('Peringatan','Pilih Data Penyaluran Barang yang akan diterima!');	
}

function saveKonfirm(){
	var basket = $('#basket').datagrid('getData');
	/* var ceki;
	$.each(basket.rows, function(i,lab){
		if(!lab['tgl_terima']){ ceki = i; return false; }
		else if(!lab['id_gud']){ ceki = i; return false; }
	}); */
	
	if($('#fm').form('validate')==false){
		$.messager.show({ title: 'Error', msg: 'Data Penerimaan Barang belum diisi' });
	}else if(basket.total==0){
		$.messager.show({ title: 'Error', msg: 'Data Barang belum diisi' }); 
	/* }else if(ceki!=undefined){
		$('#basket').datagrid('selectRow', ceki).datagrid('beginEdit', ceki); 
		$.messager.alert('Peringatan','Field dengan warna merah harus diisi !'); */
	}else{	
		var formData = {}; var ubah = '';
		$('#fm').form().find('[name]').each(function() {
			formData[this.name] = this.value;  
		});
		
		if(datser!=undefined){ //jika edit
			$.each( formData, function( i, l ){
				if(formData[i]!=datser[i]) ubah += i+'::'+datser[i]+'|'+formData[i]+'||'; //console.log(i +" "+ formData[i]);
			});
		}
		
		$.ajax({
			type: "POST",
			url: urlu,
			data: { form: formData, ubahform : ubah },
			success: function(result){
				console.log(result);
				var result = eval('('+result+')');
				if (result.success==false){
					if(result.error=='nomor_sama'){ 
						$.messager.show({ title: 'Error', msg: result.pesan });
						return;
					}else $.messager.show({ title: 'Error', msg: result.pesan });
				} else {
					$.messager.show({ title: 'Sukses', msg: result.pesan }); 
					$('#dgfull').datagrid('reload');
				}
				$('#dlg').dialog('close');	
				datser = undefined;
			}
		});
	}

}
function destroyKeluar(){
	var rw1 = $('#dgfull').datagrid('getSelected');
	if (rw1){
		$.messager.confirm('Peringatan','Apakah Anda yakin akan menghapus Data Pengeluaran Barang ini?',function(r){
		if (r){
			$.ajax({
				type: "POST",
				url: './aksi.php?module=keluar_barang&oper=del',
				data: { id_hapus: rw1.id },
				success: function(data){
					var data = eval('('+data+')');
					if (data.success==false){
						$.messager.show({ title: 'Error', msg: data.pesan });
					} else {
						$.messager.show({ title: 'Sukses', msg: data.pesan }); 
						$('#dgfull').datagrid('reload');
					}	
				}
			});	
			}
		},'json');
	}else $.messager.alert('Peringatan','Pilih Data Pengeluaran Barang yang akan dihapus dahulu !');	
}

		/* var editIndex = undefined; var hrg_temp;
        function endEditing(){
            if (editIndex == undefined){return true}
			if ($('#basket').datagrid('validateRow', editIndex)){
				var ed = $('#basket').datagrid('getEditors', editIndex); // get the editor
				var barang = $(ed[8].target).val();
				var gudang = $(ed[9].target).val();
				var jumlah = $(ed[3].target).val();
				
				$.post( "./model/cek_stok.php", 
				{ 	id_bar : barang, id_gud : gudang, id_sub : $('#id_sub').combobox('getValue'), 
					jumlah : jumlah, jenis : 'kb', id_sp : $('#dasar_keluar').combobox('getValue') })
				.done(function( data ) {
					if(data.hasil==true){
						hrg_temp = data.total;
						$('#basket').datagrid('endEdit', editIndex);
						editIndex = undefined;
						return true;
					}else{
						$.messager.alert('Peringatan','Barang digudang tersisa : '+data.jumlah+' !');
						return false;
					}
				});
				
            } else {
				$.messager.alert('Peringatan','Field dengan warna merah harus diisi !');
                return false;
            }
        }
        function onClickCell(index, field){
			if (editIndex != index){
                if (endEditing()){
                    $('#basket').datagrid('selectRow', index)
                            .datagrid('beginEdit', index);
                    var ed = $('#basket').datagrid('getEditor', {index:index,field:field});
                    if (ed){
                        ($(ed.target).data('textbox') ? $(ed.target).textbox('textbox') : $(ed.target)).focus();
                    }
                    editIndex = index;
                } else {
                    setTimeout(function(){
                        $('#basket').datagrid('selectRow', editIndex);
                    },0);
                }
            }
        }
		function onBeginEdit(rowIndex){
			var editors = $('#basket').datagrid('getEditors', rowIndex);
			var barang = $(editors[2].target);
			var jumlah = $(editors[3].target);
			var harga = $(editors[5].target);
			var satuan = $(editors[4].target);
			var gudang = $(editors[6].target);
			var fbar = $(editors[8].target);
			var fgud = $(editors[9].target);
			var jmlstok = $(editors[10].target);
			
			jumlah.textbox('textbox').bind('keyup',function(e){
				var $this = $(this);
				var num = $this.val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
				$this.val(num);
			});
			harga.textbox('textbox').bind('keyup',function(e){
				var $this = $(this);
				var num = $this.val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
				$this.val(num);
			});
			
			var a = barang.combobox('getValue');
			var b = gudang.combobox('getValue');
			
			barang.combobox({
				onSelect: function(rec){
					satuan.textbox('setValue', rec.simbol);
					fbar.textbox('setValue', rec.id_bar);
				}
			}).combobox('setValue',a);
			gudang.combobox({
				onSelect: function(rec){
					fgud.textbox('setValue', rec.id_gud);
				}
			}).combobox('setValue',b);

		}
		
		function onBeforeEdit(row){
			var combar = $(this).datagrid('getColumnOption','nama_bar');
			var comgud = $(this).datagrid('getColumnOption','nama_gud');
			var tglmin = $(this).datagrid('getColumnOption','tgl_minta');
			var jml = $(this).datagrid('getColumnOption','jumlah');
			var jmlhrg = $(this).datagrid('getColumnOption','jmlhrg');
			
			if($('#dasar_keluar').combobox('getValue')==''){
				combar.editor = {
					type: 'combobox',
					options:{
						valueField:'id_bar',
						textField:'nama_bar',
						url:'./model/cb_barang.php',
						required:true,
						filter: function(q, row){
							var opts = $(this).combobox('options');
							return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
						}
					}
				}
				jmlhrg.editor = {
					type: 'textbox',
					options:{ readonly:'true' }
				}
			}else{
				combar.editor = {
					type: 'combobox',
					options:{
						valueField:'id_bar',
						textField:'nama_bar',
						url:'./model/cb_barang.php',
						readonly:true
					}
				}
				tglmin.editor = {
					type: 'datebox',
					options:{ 
						readonly:'true',
						formatter:myformatter,
						parser:myparser
					}
				}
				jml.editor = {
					type: 'textbox',
					options:{ readonly:'true' }
				}
				jmlhrg.editor = {
					type: 'textbox',
					options:{ readonly:'true' }
				}
			}
			
			comgud.editor = {
				type: 'combobox',
				options:{
					valueField:'id_gud',
					textField:'nama_gud',
					method:'get',
					url:'./model/cb_gudang.php?id='+id_sub,
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
			var ed4 = $(this).datagrid('getEditor', {
                index: index,
                field: 'jmlhrg'
            });
            var ed5 = $(this).datagrid('getEditor', {
                index: index,
                field: 'jumlah'
            });
            
            row.nama_bar = $(ed.target).combobox('getText');
            row.nama_gud = $(ed1.target).combobox('getText');
            row.jmlhrg = hrg_temp;
            var harga = hrg_temp.replace(/[^0-9,]/g,'').replace(",",".");
            var jumlah = $(ed5.target).textbox('textbox').val().replace(/[^0-9,]/g,'').replace(",",".");
			row.jmlhrg_asli = harga;
        }
        function append(){
			if($('#dasar_keluar').combobox('getValue')==''){
				if (endEditing()){
					$('#basket').datagrid('appendRow',{status:'P'});
					editIndex = $('#basket').datagrid('getRows').length-1;
					$('#basket').datagrid('selectRow', editIndex)
							.datagrid('beginEdit', editIndex);
				}
			}else{
				$.messager.alert('Peringatan','Tidak bisa menambah/menghapus data barang, pada data yang ada surat perintahnya !');
			}
        }
        function removeit(){
            if($('#dasar_keluar').combobox('getValue')==''){
				if (editIndex == undefined){return}
				$('#basket').datagrid('cancelEdit', editIndex)
						.datagrid('deleteRow', editIndex);
				editIndex = undefined;
			}else{
				$.messager.alert('Peringatan','Tidak bisa menambah/menghapus data barang, pada data yang ada surat perintahnya !');
			}	
        }
        function accept(){
			var rows = $('#basket').datagrid('getChanges');
			if (endEditing()){
                $('#basket').datagrid('acceptChanges');
            }
        }
        function reject(){
            $('#basket').datagrid('rejectChanges');
            editIndex = undefined;
        } */

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
