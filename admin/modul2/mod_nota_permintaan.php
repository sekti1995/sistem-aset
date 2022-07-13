 <div class="dtabel" style="width:100%;height:100%;background:white">	
 <table id="dgfull"class="easyui-datagrid"
	url="./model/nota_permintaan.php" fit="true"
	toolbar="#toolbar" pagination="true" title="Input Nota Permintaan Barang"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
<th field="unit_kerja" width="200" align="left" halign="center">Nama Sub2/Unit Kerja</th>
<th field="ta" width="40" align="center">TA</th>
<th field="nomor" width="70" align="left" halign="center">Nomor Nota</th>
<th field="tanggal" width="60" align="center" halign="center">Tanggal</th>
<th field="ket" width="180" align="left" halign="center">Jenis Permintaan</th>
<th field="petugas" width="80" align="left" halign="center">Petugas</th>
<th field="status" width="80" align="left" halign="center">Status</th>
</tr>
</thead>
</table>
</div>
<div id="toolbar">

<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newNota()">New Nota</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editNota()">Edit Nota</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyNota()">Remove Nota</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="cetakNota()">Cetak Nota Permintaan</a>
<div style="float: right; margin-right: 5px;">
	<a href="#" class="easyui-linkbutton" plain="true" iconCls="icon-search" onclick="viewSearch()">Pencarian</a>
</div>
</div>
<div id="dlg" class="easyui-dialog" style="width:680px;height:465px;padding:5px 10px"
closed="true" buttons="#dlg-buttons">
<form id="fm" method="post">
<table cellpadding="2" border=0>
<tr>
<td width="90px">SKPD/Unit Kerja</td>
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
		idskpd = rec.idskpd;
		nmskpd = rec.nmskpd;
		$('#basket').datagrid('loadData', {"total":0,"rows":[],"footer":[]});
		editIndex = undefined;
		$('#iduntuk').val('');
		$('#txtuntuk').textbox('setValue','').textbox('readonly', false);
		$('input[type="radio"][value="sendiri"]').click();
		if(rec.peran==1) $('#lblskpd').hide();
		else $('#lblskpd').show();
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
<td>Jenis Nota</td>
<td colspan="2" width="150">
	<label id="lblsen"><input type="radio" name="jenis" value="sendiri"> Permintaan dari Bidang </label><br>
	<label id="lblskpd"><input type="radio" name="jenis" value="skpd"> Minta kepada SKPD </label>
	<input type="hidden" name="vjenis" id="vjenis" value="0">
</td>
<td colspan="3">
	<input class="easyui-textbox" type="text" name="txtuntuk" id="txtuntuk" data-options="required:true" style="width:300px;">
	<input type="hidden" name="iduntuk" id="iduntuk" style="width:300px;">
</td>
<tr>
</tr>
<tr>
<td>Nomor Nota</td>
<td colspan="2">: <input class="easyui-textbox" type="text" name="nomor" id="nomor" data-options="required:true" style="width:120px;"></input></td>
<td>Tanggal Nota</td>
<td colspan="2">: <input class="easyui-datebox" type="text" name="tanggal" id="tanggal" data-options="formatter:myformatter,parser:myparser,required:true" style="width:100px;" validType="validDate"></td>
</tr>
</table>
</form></br>
<div style="background:#fff">
   <table id="basket" fitColumns="true" rownumbers="true"  style="width1:150px;height:240px;" toolbar="#tb" url="./model/nota_permintaan_detail.php" title="Data Barang">
	   <thead>
		   <tr>
			   <th data-options="field:'nama_bar',width:160, align:'left', halign:'center',
                        formatter:function(value,row){
                            return row.nama_bar;
                        }">Nama Barang</th>
			   <th field="jumlah_stok" width=100 align="center" data-options="editor: {type:'textbox', options:{ readonly:'true'}}">Jumlah Stok</th>
			   <th field="jumlah" width=100 align="center" data-options="editor: {type:'textbox', options:{ required:'true'}}">Jumlah</th>
			   <th field="nama_sat" width=80 align="center"  data-options="editor: {type:'textbox', options:{ readonly:'true'}}">Satuan</th>
			   <th field="ket" width=80 align="left" halign="center" editor="textbox">ket</th>
			   <th field="id_bar" width=80 align="left" halign="center" editor="textbox" hidden>id_bar</th>
			   <th field="id_sat" width=80 align="left" halign="center" editor="textbox" hidden>id_sat</th>
		</tr>
		</thead>
	</table>	
</div>
<div id="tb" style="height:auto">
	<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="append()">Tambah</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="removeit()">Hapus</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="accept()">Setuju</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-undo',plain:true" onclick="reject()">Batal</a>
</div>
</div>
<div id="dlg-buttons">
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveNota()" style="width:90px">Save</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
</div>


<div id="dls" class="easyui-dialog" style="width:450px;height:340px;padding:10px 20px"
closed="true" buttons="#dls-buttons">
<div class="ftitle">Pencarian Nota Permintaan</div>
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
<!--<tr>
	<td>Nama Penyedia</td>
	<td>: <input class="easyui-textbox" type="text" name="penyedia_search" id="penyedia_search" size="25"></input></td>
</tr>
<tr>
	<td>No Kontrak</td>
	<td>: <input class="easyui-textbox" type="text" name="kontrak_search" id="kontrak_search" size="25"></input></td>
</tr>-->
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
		onClickCell: onClickCell,
		onEndEdit: onEndEdit,
		onBeginEdit: onBeginEdit,
		onBeforeEdit: onBeforeEdit
	});
	<?php if($_SESSION['level']==md5('c')){ ?> $('#id_sub').combobox('textbox').css('background-color','#EEEEEE'); <?php } ?>
	<?php if($_SESSION['level']==md5('a') || $peran==md5('1')){ ?> $('#lblskpd').hide(); <?php } ?>
	
	$('input[type="radio"]').click(function(){
		if ($(this).is(':checked')){
			if($(this).val()=='sendiri'){
				/* $('#lblbid').show();
				$('#lblskpd').hide(); */
				$('#iduntuk').val('');
				$('#vjenis').val('0');
				$('#txtuntuk').textbox('setValue','');
				$('#txtuntuk').textbox('readonly',false);
				id_sub_brg = id_sub;
				
			}else{
				/* $('#lblbid').hide();
				$('#lblskpd').show(); */
				$.each($('#id_sub').combobox('getData'), function(id,isi){
					if(isi.selected==true){
						idskpd = isi.idskpd;
						nmskpd = isi.nmskpd;
						return;
					}
				});
				$('#iduntuk').val(idskpd);
				$('#vjenis').val('1');
				$('#txtuntuk').textbox('setValue',nmskpd);
				$('#txtuntuk').textbox('readonly',true);
				id_sub_brg = idskpd;
			}
			$('#basket').datagrid('loadData', {"total":0,"rows":[],"footer":[]});
			editIndex = undefined;
		}
	});
});

var urlu; var id_bar; var id_sat; var id_gud; var id_kel; var datser; var id_sub; var idskpd; var nmskpd;
var format_options = {aSep:'.', aNeg:'', aDec: ',',aPad: false};
var id_sub_brg;
function viewSearch(){
	$('#dls').dialog('open').dialog('setTitle','Pencarian Data Nota Permintaan');
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
function newNota(){
	$('#dlg').dialog('open').dialog('setTitle','Tambah Data Nota Permintaan');
	$('#fm').form('clear');
	$('#id_sub').combobox('reload');
	$('#basket').datagrid('loadData', {"total":0,"rows":[],"footer":[]});
	urlu = './aksi.php?module=nota_minta&oper=add';
	id_sub = $('#id_sub').combobox('getValue');
	editIndex = undefined;
	$("input[name='jenis'][value='sendiri']").prop("checked", true);
	$('#txtuntuk').textbox('readonly',false);
	
}
function editNota(){
	var row = $('#dgfull').datagrid('getSelected');
	if (row){
		if(row.status==1){
			$.messager.alert('Peringatan','Tidak bisa Mengubah nota yang sudah dibuat Surat Permintaan !');
		}else{
			$('#dlg').dialog('open').dialog('setTitle','Edit Data Nota Permintaan');
			$('#fm').form('clear');
			$('#fm').form('load',row);
			datser = row;
			$('#basket').datagrid('load',{ id: row.id });
			id_sub = row.id_sub;
			urlu = './aksi.php?module=nota_minta&oper=edit&id_ubah='+row.id;
			editIndex = undefined;
			if($('#vjenis').val()==0) $('#txtuntuk').textbox('readonly',false);
			else $('#txtuntuk').textbox('readonly',true);
		}	
	}else $.messager.alert('Peringatan','Pilih Data Nota Permintaan yang akan diubah !');	
}
function saveNota(){
	$dataLoader = { imgUrl: 'images/ajaxloader.gif' };
	var basket = $('#basket').datagrid('getData');
	
	if($('#fm').form('validate')==false){
		$.messager.show({ title: 'Error', msg: 'Data Nota Permintaan belum diisi lengkap' });
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
				//$.loader.open($dataLoader);
			},
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
					$('#dgfull').datagrid('reload');
				}
				$('#dlg').dialog('close');	
				datser = undefined;
			}
		});
	}
}
function destroyNota(){
	var rw1 = $('#dgfull').datagrid('getSelected');
	if (rw1){
		if(rw1.status==1){
			$.messager.alert('Peringatan','Tidak bisa Menghapus nota yang sudah dibuat Surat Permintaan !');
		}else{
			$.messager.confirm('Peringatan','Apakah Anda yakin akan menghapus Nota Permintaan ini?',function(r){
			if (r){
				$.ajax({
					type: "POST",
					url: './aksi.php?module=nota_minta&oper=del',
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
		}	
	}else $.messager.alert('Peringatan','Pilih Data Nota Permintaan yang akan dihapus dahulu !');	
}

		var editIndex = undefined;
        function endEditing(){
            if (editIndex == undefined){return true}
            if ($('#basket').datagrid('validateRow', editIndex)){
				var ed = $('#basket').datagrid('getEditors', editIndex); // get the editor
				var barang = $(ed[4].target).val();
				var jmlkeluar = parseInt($(ed[2].target).val().replace(/\D/g, ""));
				var jmlstok = parseInt($(ed[1].target).val().replace(/\D/g, ""));
				
				var sama = "";
				var basket = $('#basket').datagrid('getData');
				$.each(basket.rows, function(i,lab){
					if(lab['id_bar']==barang && i!=editIndex) sama = 'ya';
				});
				
				if(validasiCombo2(ed)==false) return false;
				else if(sama=='ya'){
					$.messager.show({ title: 'Error', msg: "Barang Sudah ada dalam daftar!" }); 
					return false;
				}else if(jmlkeluar>jmlstok){
					$.messager.show({ title: 'Error', msg: "Jumlah Barang lebih dari stok!" }); 
					return false;
				}else{
					$('#basket').datagrid('endEdit', editIndex);
					editIndex = undefined;
					return true;
				}
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
			var jmstok = $(editors[1].target);
			var jumlah = $(editors[2].target);
			var satuan = $(editors[3].target);
			var fbar = $(editors[5].target);
			var fsat = $(editors[6].target);
			
			jumlah.textbox('textbox').bind('keyup',function(e){
				var $this = $(this);
				var num = $this.val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
				$this.val(num);
			});
			
			var a = fbar.textbox('getValue');
			
			barang.combobox({
				onSelect: function(rec){
					jmstok.textbox('setValue', rec.jml);
					satuan.textbox('setValue', rec.simbol);
					fsat.textbox('setValue', rec.id_satuan);
					fbar.textbox('setValue', rec.id_bar);
				}
			}).combobox('setValue',a);
			satuan.textbox('textbox').css('background-color','#EEEEEE');
		}
		
		function onBeforeEdit(row){
			var combar = $(this).datagrid('getColumnOption','nama_bar');
			
			combar.editor = {
				type: 'combobox',
				options:{
					valueField:'id_bar',
					textField:'nama_bar',
					method:'get',
					url:'./model/cb_barang_ada.php?id='+id_sub_brg+'&jns='+$('#vjenis').val()+'&barang',
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
function cetakNota(){
	var rw1 = $('#dgfull').datagrid('getSelected');
	if (rw1){
		$.loader.open($dataLoader);
		$.post( "./print/nota_permintaan.php", { id : rw1.id })
		.done(function( data ) {
			window.location.href = data.url;
			$.loader.close($dataLoader);
		});
	}else $.messager.alert('Peringatan','Pilih Data Nota Permintaan yang akan dicetak !');	
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
