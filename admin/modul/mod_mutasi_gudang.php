 <div class="dtabel" style="width:100%;height:100%;background:white">	
 <table id="dgfull"class="easyui-datagrid"
	url="./model/mutasi_gudang.php" fit="true"
	toolbar="#toolbar" pagination="true" title="Input Mutasi Tempat Barang"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
<th field="nama_unit" width="200" align="left" halign="center">Nama Unit</th>
<th field="ta" width="40" align="center">TA</th>
<th field="nomor" width="150" align="left" halign="center">Nomor</th>
<th field="tanggal" width="80" align="center" halign="center">Tanggal</th>
<th field="dari" width="100" align="left" halign="center">Dari</th>
<th field="ke" width="100" align="left" halign="center">Ke</th>
</tr>
</thead>
</table>
</div>
<div id="toolbar">

<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newMutasi()">New Mutasi</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editMutasi()">Edit Mutasi</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyMutasi()">Remove Mutasi</a>
<div style="float: right; margin-right: 5px;">
	<a href="#" class="easyui-linkbutton" plain="true" iconCls="icon-search" onclick="viewSearch()">Pencarian</a>
</div>
</div>
<div id="dlg" class="easyui-dialog" style="width:780px;height:450px;padding:5px 10px"
closed="true" buttons="#dlg-buttons">
<form id="fm" method="post">
<table cellpadding="2" border=0>
<tr>
<td width="110px">Unit/Sub Unit</td>
<td colspan="3">: 
<input class="easyui-combobox" style="width:310px;" id="id_sub" name="id_sub" <?php if($peran!=md5('1')) echo 'readonly'; ?> required="true"/>
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
		<?php if($peran==md5('1')){ ?> 
			//$('#id_pengguna, #id_pengurus').combobox('clear'); 
			$('#dari_gud, #ke_gud').combobox('clear'); 
			//$('#id_pengguna').combobox('reload', './model/cb_pejabat.php?jbt=8&id='+rec.id); 
			//$('#id_pengurus').combobox('reload', './model/cb_pejabat.php?jbt=13&id='+rec.id); 
			$('#dari_gud, #ke_gud').combobox('reload', './model/cb_gudang.php?id='+rec.id); 
			$('#basket').datagrid('loadData', {"total":0,"rows":[],"footer":[]});
			editIndex = undefined;
		<?php } ?>
	}
});
</script></td>
<td width="170px" style="text-align:center;"> Tahun Anggaran</td>
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
<td>Nomor</td>
<td colspan="2" width="170px">: <input class="easyui-textbox" type="text" name="nomor" id="nomor" data-options="required:true" style="width:130px;"></input></td>
<td>Tanggal</td>
<td>: <input class="easyui-datebox" type="text" name="tanggal" id="tanggal" data-options="formatter:myformatter,parser:myparser,required:true" style="width:100px;" validType="validDate"></td>
</tr>
<!--<tr>
<td>Pengurus Barang</td>
<td colspan="2">: 
<input class="easyui-combobox" style="width:100px;" id="id_pengurus" name="id_pengurus" required="true"/>
<script>
$('#id_pengurus').combobox({
    url:'./model/cb_pejabat.php?jbt=10',
    valueField:'id',
    textField:'text',
    filter: function(q, row){
		var opts = $(this).combobox('options');
		return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
	}
});
</script></td>
<td> Pengguna Barang</td>
<td>: 
<input class="easyui-combobox" style="width:100px;" id="id_pengguna" name="id_pengguna" required="true"/>
<script>
$('#id_pengguna').combobox({
    url:'./model/cb_pejabat.php?jbt=8',
    valueField:'id',
    textField:'text',
    filter: function(q, row){
		var opts = $(this).combobox('options');
		return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
	}
});
</script></td>
</tr>-->
<tr>
<td width="100px">Dari Tempat</td>
<td colspan="2">: 
<input class="easyui-combobox" style="width:140px;" id="dari_gud" name="dari_gud" required="true"/>
</td>
<td>Ke Tempat</td>
<td>: 
<input class="easyui-combobox" style="width:140px;" id="ke_gud" name="ke_gud" required="true"/>
<script>
$('#dari_gud').combobox({
    url:'./model/cb_gudang.php',
    valueField:'id_gud',
    textField:'nama_gud',
    filter: function(q, row){
		var opts = $(this).combobox('options');
		return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
	},
	onSelect: function(rec){
		$('#basket').datagrid('loadData', {"total":0,"rows":[],"footer":[]});
		editIndex = undefined;
	}
});
$('#ke_gud').combobox({
    url:'./model/cb_gudang.php',
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
</form>
<div style="background:#fff">
   <table id="basket" fitColumns="true" rownumbers="true"  style="width1:150px;height:240px;" toolbar="#tb" url="./model/mutasi_gudang_detail.php" title="Data Barang">
	   <thead>
		   <tr>
			   <th data-options="field:'nama_sumber',width:150, align:'left', halign:'center',
                        formatter:function(value,row){
                            return row.nama_sumber;
                        }">Sumber Dana</th>
			   <th data-options="field:'nama_bar',width:150, align:'left', halign:'center',
                        formatter:function(value,row){
                            return row.nama_bar;
                        }">Nama Barang</th>
			   <th field="jumlah" width=70 align="center" data-options="editor: {type:'textbox', options:{ required:'true'}}">Jumlah</th>
			   <th field="nama_sat" width=60 align="center"  data-options="editor: {type:'textbox', options:{ readonly:'true'}}">Satuan</th>
			   <!--<th field="harga" width=110 align="right" halign="center" data-options="editor: {type:'textbox', options:{ required:'true'}}">Harga Total</th>-->
			   <th field="harga_asli" width=120 align="right" halign="center" hidden="true" >Harga Asli</th>
			   <th field="ket" width=80 align="left" halign="center" editor="textbox">ket</th>
			   <th field="id_bar" width=80 align="left" halign="center" editor="textbox" hidden>id_bar</th>
			   <th field="id_sum" width=80 align="left" halign="center" editor="textbox" hidden>id_sum</th>
			   <th field="harga_sat" width=80 align="left" halign="center" editor="textbox" hidden>harga_sat</th>
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
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveMutasi()" style="width:90px">Save</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
</div>


<div id="dls" class="easyui-dialog" style="width:450px;height:340px;padding:10px 20px"
closed="true" buttons="#dls-buttons">
<div class="ftitle">Pencarian Data Mutasi Gudang</div>
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
	},
	onSelect: function(rec){
		$('#dari_gud_search, #ke_gud_search').combobox('clear'); 
		$('#dari_gud_search, #ke_gud_search').combobox('reload', './model/cb_gudang.php?id='+rec.id); 
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
<td>Dari Gudang</td>
<td>: 
<input class="easyui-combobox" style="width:140px;" id="dari_gud_search" name="dari_gud_search"/>
</tr>
<tr>
<td>Ke Gudang</td>
<td>: 
<input class="easyui-combobox" style="width:140px;" id="ke_gud_search" name="ke_gud_search"/>
<script>
$('#dari_gud_search, #ke_gud_search').combobox({
    url:'./model/cb_gudang.php',
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
</form>
</div>
<div id="dls-buttons">
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="doSearch()" style="width:90px">Cari</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#fms').form('clear')" style="width:90px">Bersihkan</a>
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
});

var urlu; var id_bar; var id_sat; var id_gud; var id_kel; var datser; var id_sub;
var format_options = {aSep:'.', aNeg:'', aDec: ',',aPad: false};

function viewSearch(){
	$('#dls').dialog('open').dialog('setTitle','Pencarian Data Mutasi Gudang');
	//$('#fms').form('clear');
}	
function doSearch(){
	$('#dgfull').datagrid('load',{
		<?php if($peran!=md5('3')){ ?> id_sub: $('#id_unit_search').combobox('getValue'), <?php } ?>
		ta: $('#ta_search').combobox('getValue'),
		nomor: $('#nomor_search').val(),
		tanggal: $('#tgl_search').datebox('getValue'),
		dari_gud: $('#dari_gud_search').combobox('getValue'),
		ke_gud: $('#ke_gud_search').combobox('getValue')
	});
	$('#dls').dialog('close');
}
function newMutasi(){
	$('#dlg').dialog('open').dialog('setTitle','Tambah Data Mutasi Gudang');
	$('#fm').form('clear');
	$('#id_sub').combobox('reload');
	$('#basket').datagrid('loadData', {"total":0,"rows":[],"footer":[]});
	urlu = './aksi.php?module=mutasi_gudang&oper=add';
	editIndex = undefined;
}
function editMutasi(){
	var row = $('#dgfull').datagrid('getSelected');
	if (row){
		$('#dlg').dialog('open').dialog('setTitle','Edit Data Mutasi Gudang');
		$('#fm').form('clear');
		$('#dari_gud, #ke_gud').combobox('reload', './model/cb_gudang.php?id='+row.id_sub); 
		$('#fm').form('load',row);
		datser = row;
		$('#basket').datagrid('load',{
			id: row.id
		});
		id_sub = row.id_sub;
		urlu = './aksi.php?module=mutasi_gudang&oper=edit&id_ubah='+row.id;
		editIndex = undefined;
	}else $.messager.alert('Peringatan','Pilih Data Mutasi Gudang yang akan diubah !');	
}
function saveMutasi(){
	var basket = $('#basket').datagrid('getData');
	
	if($('#fm').form('validate')==false){
		$.messager.show({ title: 'Error', msg: 'Data Mutasi Gudang belum diisi' });
	}else if(validasiCombo('fm')==false){
		return;
	}else if(basket.total==0){
		$.messager.show({ title: 'Error', msg: 'Data Barang belum diisi' }); 
	}else if(editIndex!=undefined){
		$.messager.show({ title: 'Error', msg: 'Setujui dulu perubahan data barang!' }); 
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

function destroyMutasi(){
	var rw1 = $('#dgfull').datagrid('getSelected');
	if (rw1){
		$.messager.confirm('Peringatan','Apakah Anda yakin akan menghapus Data Mutasi Gudang ini?',function(r){
		if (r){
			$.ajax({
				type: "POST",
				url: './aksi.php?module=mutasi_gudang&oper=del',
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
	}else $.messager.alert('Peringatan','Pilih Data Mutasi Gudang yang akan dihapus dahulu !');	
}

		var editIndex = undefined; //var hrg_temp;
        function endEditing(){
            if (editIndex == undefined){return true}
            if ($('#basket').datagrid('validateRow', editIndex)){
				var ed = $('#basket').datagrid('getEditors', editIndex); // get the editor
				var sumber = $(ed[6].target).val();
				var barang = $(ed[5].target).val();
				var jumlah = $(ed[2].target).val();
				var tanggal = $('#tanggal').datebox('getValue');
				
				var sama = "";
				var basket = $('#basket').datagrid('getData');
				$.each(basket.rows, function(i,lab){
					if(lab['id_bar']==barang && lab['id_sum']==sumber && i!=editIndex) sama = 'ya';
				});
				
				if(validasiCombo2(ed)==false) return false;
				if(sama=='ya'){
					$.messager.show({ title: 'Error', msg: "Barang Sudah ada dalam daftar!" }); 
					return false;
				}else{
					$.loader.open($dataLoader); 
					$.post( "./model/cek_stok.php", 
					{ 	id_bar : barang, id_gud : $('#dari_gud').combobox('getValue'), id_sub : $('#id_sub').combobox('getValue'), 
						jumlah : jumlah, jenis : 'so', id_sum : sumber,  tanggal : tanggal })
					.done(function( data ) {
						$.loader.close($dataLoader);
						var jml = jumlah.replace(/[^0-9,]/g,'').replace(",",".");
						var saldo = data.saldo.replace(/[^0-9,]/g,'').replace(",",".");
						var harga = data.harga.replace(/[^0-9,]/g,'').replace(",",".");
						
						if(parseFloat(saldo)>=parseFloat(jml)){
							hrg_temp = harga*jml;
							$('#basket').datagrid('endEdit', editIndex);
							editIndex = undefined;
							return true;
						}else{
							var msg = 'Barang digudang yang siap tersisa : '+data.saldo+' !';
							$.messager.alert('Peringatan',msg);
							return false;
						}
					});
				}
				
                /* $('#basket').datagrid('endEdit', editIndex);
                editIndex = undefined;
                return true; */
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
			var sumber = $(editors[0].target);
			var barang = $(editors[1].target);
			var jumlah = $(editors[2].target);
			//var harga = $(editors[3].target);
			var satuan = $(editors[3].target);
			var fbar = $(editors[5].target);
			var fsum = $(editors[6].target);
			
			jumlah.textbox('textbox').bind('keyup',function(e){
				var $this = $(this);
				var num = $this.val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
				$this.val(num);
			});
			/* harga.textbox('textbox').bind('keyup',function(e){
				var $this = $(this);
				var num = $this.val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
				$this.val(num);
			}); */
			
			//var a = barang.combobox('getValue');
			//var b = sumber.combobox('getValue');
			var a = fbar.textbox('getValue');
			var b = fsum.textbox('getValue');
			
			barang.combobox({
				onSelect: function(rec){
					satuan.textbox('setValue', rec.simbol);
					fbar.textbox('setValue', rec.id_bar);
				}
			}).combobox('setValue',a);
			sumber.combobox({
				onSelect: function(rec){
					fsum.textbox('setValue', rec.id);
				},
				onLoadSuccess: function(){
					var s = fsum.textbox('getValue')
					if(s==undefined || s=="") fsum.textbox('setValue', sumber.combobox('getValue'));
				}
			}).combobox('setValue',b);
			satuan.textbox('textbox').css('background-color','#EEEEEE');
		}
		
		function onBeforeEdit(row){
			var combar = $(this).datagrid('getColumnOption','nama_bar');
			var comsum = $(this).datagrid('getColumnOption','nama_sumber');
			
			combar.editor = {
				type: 'combobox',
				options:{
					valueField:'id_bar',
					textField:'nama_bar',
					method:'get',
					url:'./model/cb_barang_ada.php?cek&id='+id_sub,
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
                field: 'nama_sumber'
            });
			/* var ed4 = $(this).datagrid('getEditor', {
                index: index,
                field: 'harga'
            }); */
            var ed5 = $(this).datagrid('getEditor', {
                index: index,
                field: 'jumlah'
            });
            
            row.nama_bar = $(ed.target).combobox('getText');
            row.nama_sumber = $(ed1.target).combobox('getText');
            //var harga = $(ed4.target).textbox('textbox').val().replace(/[^0-9,]/g,'').replace(",",".");
            var jumlah = $(ed5.target).textbox('textbox').val().replace(/[^0-9,]/g,'').replace(",",".");
			row.harga_asli = hrg_temp;
			//row.harga_sat = harga / jumlah;
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
