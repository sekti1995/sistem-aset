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
</tr>
</thead>
</table>
</div>
<div id="toolbar">
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newNota()">New Nota</a>
	<!--
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editNota()">Edit Nota</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyNota()">Remove Nota</a>
	-->
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="cetakNota()">Cetak Nota Permintaan</a>
	<div style="float: right; margin-right: 5px;">
		<a href="#" class="easyui-linkbutton" plain="true" iconCls="icon-search" onclick="viewSearch()">Pencarian</a>
	</div>
</div>
<div id="dlg" class="easyui-dialog" style="width:680px;height:525px;padding:5px 10px"
closed="true" buttons="#dlg-buttons">
<form id="fm" method="post">
<table cellpadding="2" border=0>
<tr>
<td width="90px">SKPD/Unit Kerja</td>
<td colspan="3">: 
<input class="easyui-combobox" style="width:280px;" id="id_sub" name="id_sub" <?php if($_SESSION['level']==md5('c')) echo 'readonly'; ?> required="true"/>
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
		cek_no_urut();
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
	},
	onSelect: function(rec){
		cek_no_urut();
		$('#jenis').combobox('setValue','');
		$('#jenis').combobox('reload');
	}
});
</script>
</td>
</tr>
<td>Jenis Nota</td>
<td colspan="2" width="190">
	: <input type="text" name="jenis" id="jenis" prompt="Pilih Jenis Nota" />
	<script>
	$('#jenis').combobox({ 
    url:'./model/cb_jenis_nota_minta.php',
		valueField:'id',
		textField:'text', 
		filter: function(q, row){
			var opts = $(this).combobox('options');
			return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
		},
	onSelect: function(rec){ 
	$('#basket').datagrid('loadData', {"total":0,"rows":[],"footer":[]});
	editIndex = undefined;
		if(rec.id==3){
			/* $('#id-a').hide();
			$('#id-b').show(); */
				$('#iduntuk').val('');
				$('#vjenis').val('0');
				$('#txtuntuk').textbox('setValue','Diserahkan Kepada Masyarakat');
				$('#txtuntuk').textbox('readonly',true);
				id_sub_brg = id_sub;
			var uuid_skpd = $('#id_sub').combobox('getValue');
			var ta_dipilih = $('#ta').combobox('getValue');
			$('#daftar_pengadaan_kegiatan').combobox('reload', './model/cb_daftar_pengadaan_kegiatan.php?ta='+ta_dipilih+'&uuid_skpd='+uuid_skpd);
		} else if(rec.id==2){
			/* $('#id-a').show();
			$('#id-b').hide(); */
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
				editIndex = undefined;
				id_sub_brg = idskpd;
		} else {
			/* $('#id-a').show();
			$('#id-b').hide(); */
			$('#vjenis').val('0'); 
			$('#txtuntuk').textbox('readonly',false); 
		}
	}
	});
	</script>
	<!-- 
	<label id="lblsen"><input type="radio" name="jenis" value="sendiri"> Permintaan dari Bidang </label><br>
	<label id="lblskpd"><input type="radio" name="jenis" value="skpd"> Minta kepada SKPD </label>
	<label id="lblkegiatan"><input type="radio" name="jenis" value="kegiatan"> Barang Kegiatan </label>
	-->
	<input type="hidden" name="vjenis" id="vjenis" value="0">
</td>
<td colspan="3">
	<div id="id-a"><input class="easyui-textbox" type="text" name="txtuntuk" id="txtuntuk" data-options="required:true" style="width:300px;"></div>
	<!--
	<div id="id-b"><input class="easyui-combobox" style="width:300px;" id="daftar_pengadaan_kegiatan" name="daftar_pengadaan_kegiatan" required="true"/></div>
	<script>
	$('#daftar_pengadaan_kegiatan').combobox({
		url:'./model/cb_daftar_pengadaan_kegiatan.php',
		valueField:'id',
		textField:'text',
		filter: function(q, row){
			var opts = $(this).combobox('options');
			return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
		},
		onSelect: function(rec){
			cek_no_urut();
		}
	});
	</script>
	-->
	<input type="hidden" name="iduntuk" id="iduntuk" style="width:300px;">
</td>
<tr>
</tr>
<tr>
	<td>Nomor Nota</td>
	<td>: <input class="easyui-textbox" type="text" name="nomor" id="nomor" data-options="required:true" style="width:120px;"></input></td>
	<td>No Urut</td>
	<td width="100">: <label id="lblurut"></label></td>
	<td>Tanggal Nota</td>
	<td>: <input class="easyui-datebox" type="text" name="tanggal" id="tanggal" data-options="formatter:myformatter,parser:myparser,required:true" style="width:100px;" validType="validDate"></td>
</tr>
<tr>
	<td>Nomor Surat</td>
	<td>: <input class="easyui-textbox" type="text" name="no_spb" id="no_spb" data-options="required:true" style="width:120px;"></input> </td>
	<td> </td>
	<td width="100"> </td>
	<td>Tanggal Surat</td>
	<td>: <input class="easyui-datebox" type="text" name="tanggal_spb" id="tanggal_spb" data-options="formatter:myformatter,parser:myparser,required:true" style="width:100px;" validType="validDate"></td>
</tr>
<tr>
	<td>Nomor SPPB</td>
	<td>: <input class="easyui-textbox" type="text" name="no_surat" id="no_surat" data-options="required:true" style="width:120px;"></input> </td>
	<td> </td>
	<td width="100"> </td>
	<td>Tanggal SPPB</td>
	<td>: <input class="easyui-datebox" type="text" name="tgl_surat" id="tgl_surat" data-options="formatter:myformatter,parser:myparser,required:true" style="width:100px;" validType="validDate"></td>
</tr>
</table>
</form></br>
<div style="background:#fff">
   <table id="basket" fitColumns="true" rownumbers="true"  style="width1:150px;height:270px;" toolbar="#tb" url="./model/verifikasi_barang.php" title="Data Barang">
	   <thead>
		   <tr>
			   <th data-options="field:'nama_bar',width:220, align:'left', halign:'center',
                        formatter:function(value,row){
                            return row.nama_bar;
                        }">Nama Barang</th>
			   <th field="nama_sat" width=80 align="center" data-options="editor: {type:'textbox', options:{ readonly:'true'}}">Satuan</th>
			   <th field="jmlminta" width=100 align="center" data-options="editor: {type:'textbox', options:{ required:'true'}}, formatter:formatStok" hidden>Jml Minta</th>
			   <th field="jmlstok" width=100 align="center" halign="center" data-options="editor: {type:'textbox', options:{ readonly:'true'}}">Jml Stok</th>
			   <th field="jmlkeluar" width=100 align="center" data-options="editor: {type:'textbox', options:{ required:'true'}}, formatter:formatStok">Jml Keluar</th>
			   <th field="harga" width=110 align="right" halign="center" data-options="editor: {type:'textbox', options:{ readonly:'true'}}">Harga</th>
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
	$('#tanggal').datebox({
		onSelect: function(date){ 
			var tgl = $('#tanggal').datebox('getValue');
			$('#tanggal_spb').datebox('setValue', tgl);
			$('#tgl_surat').datebox('setValue', tgl);
		}
	});
	
	
	$('#nomor').textbox('textbox').bind('keyup', function(e){
		var $this = $(this);
		var ini = $this.val();
		$('#no_spb').textbox('setValue', ini);
		$('#no_surat').textbox('setValue', ini);
	}); 
	
	$('#basket').datagrid({
		singleSelect:true,
		showFooter:true,
		onClickCell: onClickCell,
		onEndEdit: onEndEdit,
		onBeginEdit: onBeginEdit,
		onBeforeEdit: onBeforeEdit
	});
	<?php if($_SESSION['level']==md5('c')){ ?>
			$('#id_sub').combobox('textbox').css('background-color','#EEEEEE');  
	<?php } ?> 
	/* 
	$('input[type="radio"]').click(function(){
		if ($(this).is(':checked')){
			console.log($(this).val())
			if($(this).val()=='sendiri'){ 
				$('#iduntuk').val('');
				$('#vjenis').val('0');
				$('#txtuntuk').textbox('setValue','');
				$('#txtuntuk').textbox('readonly',false);
				id_sub_brg = id_sub;
				
			}else if($(this).val()=='skpd'){ 
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
			}else{ 
			}
			$('#basket').datagrid('loadData', {"total":0,"rows":[],"footer":[]});
			editIndex = undefined;
		}
	}); */
});

var id_unit = $('#id_sub').combobox('getValue');
var urlu; var id_bar; var id_sat; var id_gud; var id_kel; var datser; var id_sub; var idskpd; var nmskpd; var id_surat;
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
	$('#id-b').hide();
	$('#dlg').dialog('open').dialog('setTitle','Tambah Data Nota Permintaan');
	$('#fm').form('clear');
	$('#jenis').combobox('reload');
	$('#id_sub').combobox('reload');
	$('#basket').datagrid('loadData', {"total":0,"rows":[],"footer":[]});
	urlu = './aksi.php?module=nota_minta_baru&oper=add';
	id_sub = $('#id_sub').combobox('getValue');
	editIndex = undefined;
	//$("input[name='jenis'][value='sendiri']").prop("checked", true);
	$('#txtuntuk').textbox('readonly',false);
	$('#lblurut').html('');
	
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
			urlu = './aksi.php?module=nota_minta_baru&oper=edit&id_ubah='+row.id;
			editIndex = undefined;
			if($('#vjenis').val()==0) $('#txtuntuk').textbox('readonly',false);
			else $('#txtuntuk').textbox('readonly',true);
			$('#lblurut').html('');
		}	
	}else $.messager.alert('Peringatan','Pilih Data Nota Permintaan yang akan diubah !');	
}
function saveNota(){
	$dataLoader = { imgUrl: 'images/ajaxloader.gif' };
	var basket = $('#basket').datagrid('getData');
	
	if(editIndex!=undefined){
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
					url: './aksi.php?module=nota_minta_baru&oper=del',
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
				var barang = $(ed[7].target).val();
				
				var sama = "";
				var basket = $('#basket').datagrid('getData');
				$.each(basket.rows, function(i,lab){
					if(lab['id_bar']==barang && i!=editIndex) sama = 'ya';
				});
				if(validasiCombo2(ed)==false) return false;
				if(sama=='ya'){
					$.messager.show({ title: 'Error', msg: "Barang Sudah ada dalam daftar!" }); 
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
		
		function toRpDec(bilangan){
		
				var arr = bilangan.toString().split(',');
				var primer = arr[0];
				var pecahan = arr[1];
				
				    var array = primer.toString().split('');
					var index = -3;
					while (array.length + index > 0) {
						array.splice(index, 0, '.');
						// Decrement by 4 since we just added another unit to the array.
						index -= 4;
					}
					var res = array.join('');	
					if(pecahan === undefined){
						valRp = res;
					} else {
						valRp = res+','+pecahan ;
					}
					return valRp;
		}
		function onBeginEdit(rowIndex){
			var editors = $('#basket').datagrid('getEditors', rowIndex);
			var barang = $(editors[0].target);
			var satuan = $(editors[1].target);
			var jmlminta = $(editors[2].target);
			var jmlstok = $(editors[3].target);
			var jumlah = $(editors[4].target);
			var harga = $(editors[5].target);
			var fbar = $(editors[7].target);
			var fsat = $(editors[8].target);
			
			jmlminta.textbox('textbox').bind('keyup',function(e){
				var $this = $(this);
				var jum = $this.val();
				j2 = jum.replace(".","");
				j3 = j2.replace(".","");
				j4 = j3.replace(".","");
				j5 = j4.replace(".","");
				j6 = j5.replace(",",".");
				j7 = parseFloat(j6);
				$this.val(jum);
			});
			jumlah.textbox('textbox').bind('keyup',function(e){
				var $this = $(this);
				var jum = $this.val();
				j2 = jum.replace(".","");
				j3 = j2.replace(".","");
				j4 = j3.replace(".","");
				j5 = j4.replace(".","");
				j6 = j5.replace(",",".");
				j7 = parseFloat(j6);
				
				var valdes = toRpDec(j5);
				$this.val(valdes); 
			});
			
			//var a = barang.combobox('getValue');
			var a = fbar.textbox('getValue');
			barang.combobox({
				onSelect: function(rec){
					var id_unit2 = $('#id_sub').combobox('getValue');
					//console.log(id_unit2);
					$.loader.open($dataLoader);
					$.post( "./model/cek_stok.php", { id_bar: rec.id_bar, id_sub: id_unit2, id_surat: id_surat })
					.done(function( data ) {
						//console.log(data);
						jmlstok.textbox('setValue', data.saldo);
						harga.textbox('setValue', data.harga);
						$.loader.close($dataLoader);
					}); 
					jmlminta.textbox('setValue', 0);
					jumlah.textbox('setValue', 0);
					satuan.textbox('setValue', rec.simbol);
					fsat.textbox('setValue', rec.id_satuan);
					fbar.textbox('setValue', rec.id_bar);
				}
			}).combobox('setValue',a);
			satuan.textbox('textbox').css('background-color','#EEEEEE');
			jmlstok.textbox('textbox').css('background-color','#EEEEEE');
			harga.textbox('textbox').css('background-color','#EEEEEE');
		}
		
		function onBeforeEdit(row){
			var combar = $(this).datagrid('getColumnOption','nama_bar');
			var jns_brg = $('#jenis').combobox('getValue');
			combar.editor = {
				type: 'combobox',
				options:{
					valueField:'id_bar',
					textField:'nama_bar',
					method:'get',
					url:'./model/cb_barang_ada.php?jns_brg='+jns_brg+'&id='+$('#id_sub').combobox('getValue'),
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
                $('#basket').datagrid('appendRow',{status:'P' });
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

function warnaText(val,row){
	if(row.status==0){
		return '<span style="color:red;">(Belum diverifikasi)</span>';
	}else if(row.status==2){
		return '<span style="color:blue;">(Permintaan Ditolak)</span>';
	}else {
		return val;
	}
}

function statusText(val,row){
	if(val==0){
		return '<span style="color:red; font-weight:bold;">Belum diverifikasi</span>';
	}else if(val==1){
		return '<span style="color:orange; font-weight:bold;">Terverifikasi</span>';
	}else if(val==2){
		return '<span style="color:blue; font-weight:bold;">Permintaan Ditolak</span>';
	}else if(val==3){
		return '<span style="color:green; font-weight:bold;">Barang disalurkan</span>';
	}else {
		return val;
	}
}

function formatStok(val,row){
	if(val!=undefined){
		if (parseInt(val) > parseInt(row.jmlstok)){
			return '<span style="color:red;">('+val+')</span>';
		} else {
			return val;
		}
	}
}

function cek_no_urut(){
	console.log(id_unit);
	$.post( "./model/cek_no_urut.php", {<?php if($_SESSION['level']!=md5('c')){ ?>id:id_unit, <?php } ?> 
		thn:$('#ta').combobox('getValue'), jenis:'sp' })
	.done(function( data ) {
		console.log(data);
		$('#lblurut').html(data);
	});
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
