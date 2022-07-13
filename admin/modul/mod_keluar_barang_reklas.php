 <div class="dtabel" style="width:100%;height:100%;background:white">	
 <table id="dgfull"class="easyui-datagrid"
	url="./model/keluar_barang.php" fit="true"
	toolbar="#toolbar" pagination="true" title="Input Pengeluaran Barang"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
<th field="nm_skpd" width="200" align="left" halign="center">Nama Unit</th>
<th field="ta" width="40" align="center">TA</th>
<th field="nomor" width="150" align="left" halign="center">Nomor</th>
<th field="tanggal" width="80" align="center" halign="center">Tanggal</th>
<th field="untuk" width="200" align="left" halign="center">Peruntukan</th>
<th field="jenis_out" width="200" align="left" halign="center">Jenis</th>
</tr>
</thead>
</table>
</div>
<div id="toolbar">

<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newKeluar()">New Pengeluaran</a>
<!--<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editKeluar()">Edit Pengeluaran</a>-->
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyKeluar()">Remove Pengeluaran</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="cetakBA()">Cetak BA</a>
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
<input class="easyui-combobox" style="width:310px;" id="id_sub" name="id_sub" <?php if($_SESSION['level']==md5('c')) echo 'readonly'; ?> required="true"/>
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
		<?php if($_SESSION['level']!=MD5('c')){ ?> 
			$('#dasar_keluar').combobox('clear'); 
			$('#id_subt').combobox('clear'); 
			$('#dasar_keluar').combobox('reload', './model/cb_sppb.php?id='+rec.id);
			$('#basket').datagrid('loadData', {"total":0,"rows":[],"footer":[]});	
			editIndex = undefined;
		<?php } ?>
	}
});
</script></td>
<td width="130px" style="text-align:center;"> Tahun Anggaran</td>
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
<td>Nomor BA</td>
<td colspan="2" width="170px">: <input class="easyui-textbox" type="text" name="nomor" id="nomor" data-options="required:true" style="width:130px;"></input></td>
<td>Tanggal</td>
<td>: <input class="easyui-datebox" type="text" name="tanggal" id="tanggal" data-options="formatter:myformatter,parser:myparser,required:true" style="width:100px;"></td>
<td> </td>
</tr>
<tr id="trkeluar">
<td width="100px">Untuk Unit Kerja</td>
<td colspan="3">: <input class="easyui-textbox" type="text" name="peruntukan" id="peruntukan" data-options="required:true" style="width:280px;"></input>
</td>
</tr>
<tr id="trsalur">
<td width="100px">Dasar Keluar/No SPPB</td>
<td>: <input class="easyui-combobox" style="width:150px;" id="dasar_keluar" name="dasar_keluar"/>
<script>
$('#dasar_keluar').combobox({
    url:'./model/cb_sppb.php',
    valueField:'id',
    textField:'text',
    filter: function(q, row){
		var opts = $(this).combobox('options');
		return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
	},
	onSelect: function(rec){
		$('#id_subt').combobox('clear'); 
		$('#id_subt').combobox('reload', './model/cb_sub2_unit.php?id_unit_sppb='+rec.id_sub); 
		$('#basket').datagrid('load', "./model/perintah_keluar_detail.php?id="+rec.id);
	}
});
</script></td>
<td>Untuk Unit</td>
<td colspan="3">: 
<input class="easyui-combobox" style="width:280px;" id="id_subt" name="id_subt"/>
<script>
$('#id_subt').combobox({
    valueField:'id',
    textField:'text',
	readonly:true
});
</script></td>
</tr>
</table>
</form>
<div style="background:#fff">
   <table id="basket" fitColumns="true" rownumbers="true"  style="width1:150px;height:260px;" toolbar="#tb" title="Data Barang">
	   <thead>
		   <tr>
			   <th field="tgl_minta" width=120 align="center" data-options="editor: 
					{type:'datebox', 
						options:{ required:'true',
									formatter:myformatter,
									parser:myparser
								}}">Tgl Minta</th>
			   <th field="tgl_terima" width=120 align="center" data-options="editor: 
					{type:'datebox', 
						options:{ required:'true',
									formatter:myformatter,
									parser:myparser
								}}">Tgl Diserahkan</th>
			   <th data-options="field:'nama_bar',width:150, align:'left', halign:'center',
                        formatter:function(value,row){
                            return row.nama_bar;
                        }">Nama Barang</th>
			   <th field="jumlah" width=70 align="center" data-options="editor: {type:'textbox', options:{ required:'true'}}">Jumlah</th>
			   <th field="nama_sat" width=60 align="center"  data-options="editor: {type:'textbox', options:{ readonly:'true'}}">Satuan</th>
			   <th field="jmlhrg" width=110 align="right" halign="center" data-options="editor: {type:'textbox', options:{ readonly:'true'}}">Harga Total</th>
			   <th data-options="field:'nama_gud',width:160, align:'left', halign:'center',
                        formatter:function(value,row){
                            return row.nama_gud;
                        }">Dari Tempat</th>
			   <th field="jmlhrg_asli" width=120 align="right" halign="center" hidden="true" >Harga Asli</th>
			   <th field="ket" width=80 align="left" halign="center" editor="textbox">ket</th>
			   <th field="id_bar" width=80 align="left" halign="center" editor="textbox" hidden>id_bar</th>
			   <th field="id_gud" width=80 align="left" halign="center" editor="textbox" hidden>id_gud</th>
			   <th field="jmlstok" width=80 align="left" halign="center" editor="textbox" hidden>jmlstok</th>
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
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveKeluar()" style="width:90px">Save</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
</div>


<div id="dls" class="easyui-dialog" style="width:450px;height:340px;padding:10px 20px"
closed="true" buttons="#dls-buttons">
<div class="ftitle">Pencarian Data Pengeluaran Barang</div>
<form id="fms" method="post">
<table cellpadding="5">
<?php if($peran==md5('1')){ ?>
<tr>
<td>Nama Sub Unit</td>
<td>: 
<input class="easyui-combobox" style="width:250px;" id="id_unit_search" name="id_unit_search"/>
<script>
$('#id_unit_search').combobox({
    url:'./model/cb_sub2_unit.php?nots',
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
    url:'./model/cb_sub2_unit.php?nots',
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
$.extend($.fn.validatebox.methods, {
	required: function(jq, required){
		return jq.each(function(){
			var opts = $(this).validatebox('options');
			opts.required = required != undefined ? required : true;
			$(this).validatebox('validate');
		})
	}
})
$(function(){
	$('#basket').datagrid({
		singleSelect:true,
		showFooter:true,
		onClickCell: onClickCell,
		onEndEdit: onEndEdit,
		onBeginEdit: onBeginEdit,
		onBeforeEdit: onBeforeEdit
	});
	$('#trsalur').hide();
	$("#state").change(function() {
		if(this.checked) {
			cekSalur(1);
		}else{
			cekSalur(0);
		}
	});
	<?php if($_SESSION['level']==md5('c')){ ?> $('#id_sub').combobox('textbox').css('background-color','#EEEEEE'); <?php } ?>
	$('#id_subt').combobox('textbox').css('background-color','#EEEEEE');
});

function cekSalur(v){
	if(v==1){
		$('#trkeluar').hide();
		$('#peruntukan').textbox('required', false);
		$('#peruntukan').textbox('setValue', '');
		$('#trsalur').show();
		$('#dasar_keluar').combobox('required');
		$('#id_subt').combobox('required');
		$('#jenis_out').val('s');
	}else{
		$('#trkeluar').show();
		$('#peruntukan').textbox('required');
		$('#trsalur').hide();
		$('#dasar_keluar').combobox('required', false);
		$('#id_subt').combobox('required', false);
		$('#dasar_keluar').combobox('setValue', '');
		$('#id_subt').combobox('setValue', '');
		$('#jenis_out').val('k');
		$('#basket').datagrid('loadData', {"total":0,"rows":[],"footer":[]});
	}
}

var urlu; var id_bar; var id_sat; var id_gud; var id_kel; var datser; var id_sub;
var format_options = {aSep:'.', aNeg:'', aDec: ',',aPad: false};

function viewSearch(){
	$('#dls').dialog('open').dialog('setTitle','Pencarian Data Pengeluaran Barang');
	//$('#fms').form('clear');
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
function newKeluar(){
	$('#dlg').dialog('open').dialog('setTitle','Tambah Data Pengeluaran Barang');
	$('#fm').form('clear');
	cekSalur(0);
	$('#id_sub').combobox('reload');
	$('#basket').datagrid('loadData', {"total":0,"rows":[],"footer":[]});
	urlu = './aksi.php?module=keluar_barang_reklas&oper=add';
	id_sub = $('#id_sub').combobox('getValue');
	editIndex = undefined;
}
function editKeluar(){
	var row = $('#dgfull').datagrid('getSelected');
	if (row){
		$('#dlg').dialog('open').dialog('setTitle','Edit Data Pengeluaran Barang');
		$('#dasar_keluar').combobox('reload', './model/cb_sppb.php?id='+row.id_sub+'&idn='+row.dasar_keluar);
		$('#fm').form('clear');
		$('#fm').form('load',row);
		datser = row;
		$('#id_subt').combobox('reload', './model/cb_sub2_unit.php?id='+row.id_subt);
		$('#basket').datagrid('load', "./model/keluar_barang_reklas_detail.php?id="+row.id);
		id_sub = row.id_sub;
		urlu = './aksi.php?module=keluar_barang_reklas&oper=edit&id_ubah='+row.id;
	}else $.messager.alert('Peringatan','Pilih Data Pengeluaran Barang yang akan diubah !');	
}
function saveKeluar(){
	var basket = $('#basket').datagrid('getData');
	var ceki;
	$.each(basket.rows, function(i,lab){
		if(!lab['tgl_terima']){ ceki = i; return false; }
		else if(!lab['id_gud']){ ceki = i; return false; }
	});
	
	if($('#fm').form('validate')==false){
		$.messager.show({ title: 'Error', msg: 'Data Pengeluaran Barang belum diisi lengkap!' });
	}else if(basket.total==0){
		$.messager.show({ title: 'Error', msg: 'Data Barang belum diisi' }); 
	}else if(editIndex!=undefined){
		$.messager.show({ title: 'Error', msg: 'Setujui dulu perubahan data barang!' }); 
	}else if(ceki!=undefined){
		$('#basket').datagrid('selectRow', ceki).datagrid('beginEdit', ceki); 
		$.messager.alert('Peringatan','Field dengan warna merah harus diisi !');
		editIndex=ceki;
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
		console.log(basket);
		
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
				url: './aksi.php?module=keluar_barang_reklas&oper=del',
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

		var editIndex = undefined; var hrg_temp;
        function endEditing(){
            if (editIndex == undefined){return true}
			if ($('#basket').datagrid('validateRow', editIndex)){
				var ed = $('#basket').datagrid('getEditors', editIndex); // get the editor
				var barang = $(ed[8].target).val();
				var gudang = $(ed[9].target).val();
				var jumlah = $(ed[3].target).val();
								
				var sama = "";
				var basket = $('#basket').datagrid('getData');
				$.each(basket.rows, function(i,lab){
					if(lab['id_bar']==barang && lab['id_gud']==gudang && i!=editIndex) sama = 'ya';
				});
				if(sama=='ya'){
					$.messager.show({ title: 'Error', msg: "Barang Sudah ada dalam daftar!" }); 
					return false;
				}else{				
					$.loader.open($dataLoader); 
					$.post( "./model/cek_stok.php", 
					{ 	id_bar : barang, id_gud : gudang, id_sub : $('#id_sub').combobox('getValue'), 
						jumlah : jumlah, jenis : 'kb', id_sp : $('#dasar_keluar').combobox('getValue') })
					.done(function( data ) {
						$.loader.close($dataLoader);
						if(data.hasil==true){
							hrg_temp = data.total;
							$('#basket').datagrid('endEdit', editIndex);
							editIndex = undefined;
							return true;
						}else{
							var msg = 'Barang digudang yang siap tersisa : '+data.jumlah+' !';
							if(data.pesan!=0) msg += '<br>Dengan saldo : '+data.saldo+'<br>Dan dipesan : '+data.pesan;
							$.messager.alert('Peringatan',msg);
							return false;
						}
					});
				}
				/*	$('#basket').datagrid('endEdit', editIndex);
					editIndex = undefined;
					return true;
				} */
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
				tglmin.editor = {
					type: 'datebox',
					options:{ 
						required:'true',
						formatter:myformatter,
						parser:myparser
					}
				}
				jml.editor = {
					type: 'textbox',
					options:{ required:'true' }
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
        }

function cetakBA(){
	var rw1 = $('#dgfull').datagrid('getSelected');
	if (rw1){
		$.loader.open($dataLoader);
		$.post( "./print/ba_keluar_barang_reklas.php", { id : rw1.id })
		.done(function( data ) {
			window.location.href = data.url;
			$.loader.close($dataLoader);
		});
	}else $.messager.alert('Peringatan','Pilih Data Pengeluaran Barang yang akan dicetak !');	
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
