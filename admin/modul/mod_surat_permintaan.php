 <div class="dtabel" style="width:100%;height:100%;background:white">	
 <table id="dgfull"class="easyui-datagrid"
	url="./model/surat_permintaan.php" fit="true"
	toolbar="#toolbar" pagination="true" title="Input Surat Permintaan Barang"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
<th field="unit_kerja" width="200" align="left" halign="center">Nama Sub2/Unit Kerja</th>
<th field="ta" width="40" align="center">TA</th>
<th field="no_nota" width="70" align="left" halign="center">Nomor Nota</th>
<th field="no_spb" width="70" align="left" halign="center">Nomor Surat</th>
<th field="tanggal" width="70" align="center" halign="center">Tanggal Surat</th>
<th field="ket" width="180" align="left" halign="center">Jenis Permintaan</th>
<th field="petugas" width="80" align="left" halign="center">Petugas</th>
<th field="txt_status" width="80" align="left" halign="center">Status</th>
</tr>
</thead>
</table>
</div>
<div id="toolbar">

<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newSuratMinta()">New Surat</a>
<?php
	if($_SESSION['peran_id'] == md5("1")){
?>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editSuratMinta()">Edit Surat</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroySuratMinta()">Batal Surat</a>
<?php
	}
?>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="cetakSuratMinta()">Cetak Surat Permintaan</a>
<div style="float: right; margin-right: 5px;">
	<a href="#" class="easyui-linkbutton" plain="true" iconCls="icon-search" onclick="viewSearch()">Pencarian</a>
</div>
</div>
<div id="dlg" class="easyui-dialog" style="width:730px;height:450px;padding:5px 10px"
closed="true" buttons="#dlg-buttons">
<form id="fm" method="post">
<table cellpadding="2" border=0>
<tr>
<td width="100px">Sub2/Unit Kerja</td>
<td colspan="3">: 
<input class="easyui-combobox" style="width:300px;" id="id_sub" name="id_sub" <?php if($_SESSION['level']==md5('c')) echo 'readonly'; ?> required="true"/>
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
		<?php if($_SESSION['level']!=md5('c')){ ?> 
			$('#id_nota').combobox('clear'); 
			$('#id_nota').combobox('reload', './model/cb_nota_minta.php?id='+rec.id);
			$('#basket').datagrid('loadData', {"total":0,"rows":[],"footer":[]});	
		<?php } ?>
	}
});
</script></td>
<td> Tahun Anggaran</td>
<td>: <input class="easyui-combobox" style="width:70px;" id="ta" name="ta" required="true"/>
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
<td>Nomor Nota</td>
<td width="160px">: <input class="easyui-combobox" style="width:120px;" id="id_nota" name="id_nota" required="true"/>
<script>
$('#id_nota').combobox({
    url:'./model/cb_nota_minta.php',
    valueField:'id',
    textField:'text',
    filter: function(q, row){
		var opts = $(this).combobox('options');
		return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
	},
	onSelect: function(rec){
		$('#basket').datagrid('load', "./model/nota_permintaan_detail.php?id="+rec.id);
		$('#txtuntuk').textbox('setValue', rec.txtuntuk);
		$('#vjenis').val(rec.vjenis);
		$('#iduntuk').val(rec.iduntuk);
	}
});
</script>
</td>
<td>Dari / Kepada</td>
<td colspan="3">
	<input class="easyui-textbox" id="txtuntuk" name="txtuntuk" readonly="readonly" style="width:290px;"/>
	<input type="hidden" id="vjenis" name="vjenis"/>
	<input type="hidden" id="iduntuk" name="iduntuk"/>
</td>
</tr>
<tr>
<td>Nomor Surat</td>
<td>: <input class="easyui-textbox" type="text" name="no_spb" id="no_spb" data-options="required:true" style="width:100px;"></input></td>
<td>Tanggal</td>
<td>: <input class="easyui-datebox" type="text" name="tanggal" id="tanggal" data-options="formatter:myformatter,parser:myparser,required:true" style="width:100px;" validType="validDate">
<input type="text" name="tgl_max" id="tgl_max" hidden />
</td>
</tr>
</table>
</form></br>
<div style="background:#fff">
   <table id="basket" fitColumns="true" rownumbers="true"  style="width1:150px;height:240px;" title="Data Barang">
	   <thead>
		   <tr>
			   <th data-options="field:'nama_bar',width:160, align:'left', halign:'center',
                        formatter:function(value,row){
                            return row.nama_bar;
                        }">Nama Barang</th>
			   <th field="jumlah" width=100 align="center" data-options="editor: {type:'textbox', options:{ required:'true'}}">Jumlah</th>
			   <th field="nama_sat" width=80 align="center"  data-options="editor: {type:'textbox', options:{ readonly:'true'}}">Satuan</th>
			   <th field="ket" width=80 align="left" halign="center" editor="textbox">ket</th>
			   <th field="id_bar" width=80 align="left" halign="center" editor="textbox" hidden>id_bar</th>
			   <th field="id_sat" width=80 align="left" halign="center" editor="textbox" hidden>id_sat</th>
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
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveSuratMinta()" style="width:90px">Save</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
</div>


<div id="dls" class="easyui-dialog" style="width:450px;height:340px;padding:10px 20px"
closed="true" buttons="#dls-buttons">
<div class="ftitle">Pencarian Data Surat Permintaan</div>
<form id="fms" method="post">
<table cellpadding="5">
<?php if($_SESSION['level']!=md5('c')){ ?>
<tr>
<td>Nama Unit</td>
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
	<td>Nomor Surat</td>
	<td>: <input class="easyui-textbox" type="text" name="nomor_search" id="nomor_search" size="15"></input></td>
</tr>
<tr>
	<td>Tgl Surat</td>
	<td>: <input class="easyui-datebox" type="text" name="tgl_search" id="tgl_search" data-options="formatter:myformatter,parser:myparser" style="width:100px;"></input></td>
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
	
	
	var tgl1; var bln1; var thn1; var tgl2; var bln2; var thn2;
	
	$.get( "./model/get_lock.php", function( data ) {
		var data = eval('('+data+')');
		var lock_skpd = "<?php echo $_SESSION['kode_sub']; ?>";
		if( lock_skpd in oc(data) ) { 
			//LOCK
			
			
			$.get( "./model/get_lock_date.php", function( data ) {
				var data = eval('('+data+')');
				// console.log(data)
				tgl1 = data[0].tgl1;
				bln1 = data[0].bln1;
				thn1 = data[0].thn1;
				
				tgl2 = data[0].tgl2;
				bln2 = data[0].bln2;
				thn2 = data[0].thn2;
				
				$('#tanggal').datebox().datebox('calendar').calendar({
					validator: function(date){
						var d1 = new Date(thn1, bln1, tgl1);
						var d2 = new Date(thn2, bln2, tgl2);
						
						return date<=d2 && date >= d1;
					}
				});
			})
			
		} else { 
			// START
			/* 
			$.get( "./model/get_lock_date.php", function( data ) {
				var data = eval('('+data+')');
				tgl1 = data[0].tgl1;
				bln1 = data[0].bln1;
				thn1 = data[0].thn1;
				
				tgl2 = data[0].tgl2;
				bln2 = data[0].bln2;
				thn2 = data[0].thn2;
				
				$('#tanggal').datebox().datebox('calendar').calendar({
					validator: function(date){
						var now = new Date();
						var d1 = new Date(thn1, bln1, tgl1+1);
						var d2 = new Date(thn2, bln2, tgl2);
						return d1<=date && date<=d2;
					}
				});
				$('#tanggal_spb').datebox().datebox('calendar').calendar({
					validator: function(date){
						var now = new Date();
						var d1 = new Date(thn1, bln1, tgl1+1);
						var d2 = new Date(thn2, bln2, tgl2);
						return d1<=date && date<=d2;
					}
				});
				$('#tgl_surat').datebox().datebox('calendar').calendar({
					validator: function(date){
						var now = new Date();
						var d1 = new Date(thn1, bln1, tgl1+1);
						var d2 = new Date(thn2, bln2, tgl2);
						return d1<=date && date<=d2;
					}
				});
			
			});
			 */
			// END
			
		}
	});
	$('#tanggal').datebox({
		onSelect: function(date){ 
			var tgl = $('#tanggal').datebox('getValue');
			var ta = $("#ta").combobox('getValue');
			var tap = tgl.substring(6,10);
			if(parseInt(ta) != parseInt(tap)){
				$('#tanggal').datebox('clear');
				$.messager.alert({ title: 'Error', msg: "Tahun Anggaran Salah" });
				return false;
			} 
			var uuid_skpd = $('#id_sub').combobox('getValue');
			$.ajax({
				type: "POST",
				url: './model/load_tgl_max.php',
				data: { uuid_skpd: uuid_skpd },
				success: function(result){
					var result = eval('('+result+')');
					$('#tgl_max').val(result[0].tgl_max);
				}
			});
			
			
		}
	});
	$('#basket').datagrid({
		singleSelect:true,
		/* showFooter:true,
		onClickCell: onClickCell,
		onEndEdit: onEndEdit,
		onBeginEdit: onBeginEdit,
		onBeforeEdit: onBeforeEdit */
	});
	<?php if($_SESSION['level']==md5('c')){ ?> $('#id_sub').combobox('textbox').css('background-color','#EEEEEE'); <?php } ?>
});

var urlu; var id_bar; var id_sat; var id_gud; var id_kel; var datser; var id_sub;
var format_options = {aSep:'.', aNeg:'', aDec: ',',aPad: false};

function viewSearch(){
	$('#dls').dialog('open').dialog('setTitle','Pencarian Data Surat Permintaan');
	//$('#fms').form('clear');
}	
function doSearch(){
        $('#dgfull').datagrid('load',{
			<?php if($_SESSION['level']!=md5('c')){ ?> id_sub: $('#id_unit_search').combobox('getValue'), <?php } ?>
			ta: $('#ta_search').combobox('getValue'),
			nomor: $('#nomor_search').val(),
			tanggal: $('#tgl_search').datebox('getValue')
        });
}
function newSuratMinta(){
	$('#dlg').dialog('open').dialog('setTitle','Tambah Data Surat Permintaan');
	$('#fm').form('clear');
	$('#id_sub').combobox('reload');
	$('#basket').datagrid('loadData', {"total":0,"rows":[],"footer":[]});
	$('#id_nota').combobox('reload', './model/cb_nota_minta.php?id='+id_sub);
	urlu = './aksi.php?module=surat_minta&oper=add';
	id_sub = $('#id_sub').combobox('getValue');
}
function editSuratMinta(){
	var row = $('#dgfull').datagrid('getSelected');
	if (row){
		if(row.status==0){
			$('#dlg').dialog('open').dialog('setTitle','Edit Data Surat Permintaan');
			$('#id_nota').combobox('reload', './model/cb_nota_minta.php?id='+row.id_sub+'&idn='+row.id_nota);
			$('#fm').form('clear');
			$('#fm').form('load',row);
			datser = row;
			$('#basket').datagrid('load', "./model/surat_permintaan_detail.php?id="+row.id);
			id_sub = row.id_sub;
			urlu = './aksi.php?module=surat_minta&oper=edit&id_ubah='+row.id;
		}else{
			$.messager.alert('Peringatan','Tidak bisa Mengubah Surat yang sudah ditindaklanjuti !');
		}
	}else $.messager.alert('Peringatan','Pilih Data Surat Permintaan yang akan diubah !');	
}
function saveSuratMinta(){
	if($('#tanggal').datebox('getValue')==''){
		$.messager.alert('Peringatan', 'Tanggal Nota belum diisi !');
	}else{
		var d1 = new Date(myparser($('#tanggal').datebox('getValue')));
		var d2 = new Date(myparser($('#tgl_max').val()));
		if(d1 < d2){
			$.messager.alert('Peringatan', 'Tanggal Nota tidak bisa mundur lebih dari tanggal Nota yang sudah ada ! ');
			return false;
		}
		
	}
	$dataLoader = { imgUrl: 'images/ajaxloader.gif' };
	var basket = $('#basket').datagrid('getData');
	
	if($('#fm').form('validate')==false){
		$.messager.show({ title: 'Error', msg: 'Data Surat Permintaan belum diisi lengkap' });
	}else if(validasiCombo('fm')==false){
		return false;
	}else if(editIndex!=undefined){
		$.messager.show({ title: 'Error', msg: 'Setujui dulu perubahan data barang!' }); 
	}else if(basket.total==0){
		$.messager.show({ title: 'Error', msg: 'Data Barang belum diisi' }); 
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
		//console.log(basket);
		
		$.ajax({
			type: "POST",
			url: urlu,
			data: { form: formData, basket: basket.rows, ubahform : ubah },
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
					if(result.error=='nomor_sama'){ 
						$.messager.show({ title: 'Error', msg: result.pesan });
						return;
					}else $.messager.show({ title: 'Error', msg: result.pesan });
				} else {
					$.messager.show({ title: 'Sukses', msg: result.pesan }); 
					$('#dgfull').datagrid('reload');
					$('#id_nota').combobox('reload');
				}
				$('#dlg').dialog('close');	
				datser = undefined;
			}
		});
	}
}
function destroySuratMinta(){
	var rw1 = $('#dgfull').datagrid('getSelected');
	if (rw1){
		if(rw1.status==0){
			$.messager.confirm('Peringatan','Apakah Anda yakin akan menghapus Surat Permintaan ini?',function(r){
			if (r){
				$.ajax({
					type: "POST",
					url: './aksi.php?module=surat_minta&oper=del',
					data: { id_hapus: rw1.id },
					success: function(data){
						var data = eval('('+data+')');
						if (data.success==false){
							$.messager.show({ title: 'Error', msg: data.pesan });
						} else {
							$.messager.show({ title: 'Sukses', msg: data.pesan }); 
							$('#dgfull').datagrid('reload');
							$('#id_nota').combobox('reload');
						}	
					}
				});	
				}
			},'json');
		}else{
			$.messager.alert('Peringatan','Tidak bisa Menghapus Surat yang sudah ditindaklanjuti !');
		}
	}else $.messager.alert('Peringatan','Pilih Data Surat Permintaan yang akan dihapus dahulu !');	
}

		var editIndex = undefined;
        function endEditing(){
            if (editIndex == undefined){return true}
            if ($('#basket').datagrid('validateRow', editIndex)){
                $('#basket').datagrid('endEdit', editIndex);
                editIndex = undefined;
                return true;
            } else {
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
			var barang = $(editors[0].target);
			var jumlah = $(editors[1].target);
			var satuan = $(editors[2].target);
			var fbar = $(editors[4].target);
			var fsat = $(editors[5].target);
			
			jumlah.textbox('textbox').bind('keyup',function(e){
				var $this = $(this);
				var num = $this.val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
				$this.val(num);
			});
			
			var a = barang.combobox('getValue');
			
			barang.combobox({
				onSelect: function(rec){
					satuan.textbox('setValue', rec.simbol);
					fsat.textbox('setValue', rec.id_satuan);
					fbar.textbox('setValue', rec.id_bar);
				}
			}).combobox('setValue',a);

		}
		
		function onBeforeEdit(row){
			var combar = $(this).datagrid('getColumnOption','nama_bar');
			
			combar.editor = {
				type: 'combobox',
				options:{
					valueField:'id_bar',
					textField:'nama_bar',
					method:'get',
					url:'./model/cb_barang.php?id='+id_sub,
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
			
            row.nama_bar = $(ed.target).combobox('getText');
        }
        function append(){
            if (endEditing()){
                $('#basket').datagrid('appendRow',{status:'P'});
                editIndex = $('#basket').datagrid('getRows').length-1;
                $('#basket').datagrid('selectRow', editIndex)
                        .datagrid('beginEdit', editIndex);
            }
        }
        function removeit(){
            if (editIndex == undefined){return}
            $('#basket').datagrid('cancelEdit', editIndex)
                    .datagrid('deleteRow', editIndex);
            editIndex = undefined;
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
function cetakSuratMinta(){
	var rw1 = $('#dgfull').datagrid('getSelected');
	if (rw1){
		$.loader.open($dataLoader);
		$.post( "./print/surat_permintaan.php", { id : rw1.id })
		.done(function( data ) {
			window.location.href = data.url;
			$.loader.close($dataLoader);
		});
	}else $.messager.alert('Peringatan','Pilih Data Surat Permintaan yang akan dicetak !');	
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
