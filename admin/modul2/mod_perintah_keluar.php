 <div class="dtabel" style="width:100%;height:100%;background:white">	
 <table id="dgfull"class="easyui-datagrid"
	url="./model/perintah_keluar.php" fit="true"
	toolbar="#toolbar" pagination="true" title="Perintah Pengeluaran Barang"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
<th field="skpd" width="200" align="left" halign="center">Nama SKPD</th>
<th field="ta" width="40" align="center">TA</th>
<th field="no_surat" width="100" align="left" halign="center" formatter="warnaText">Nomor SPPB</th>
<th field="tgl_surat" width="90" align="center" halign="center" formatter="warnaText">Tanggal SPPB</th>
<th field="subunit" width="200" align="left" halign="center">Peruntukan (UPT/Unit Kerja)</th>
<th field="no_sp" width="100" align="left" halign="center">Dasar / Nomor SP</th>
<th field="tgl_sp" width="90" align="center" halign="center">Tanggal SP</th>
<th field="status" width="100" align="left" halign="center"  formatter="statusText">Status</th>
</tr>
</thead>
</table>
</div>
<div id="toolbar">
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="verifikasi()">Verifikasi</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editPerintah()">Edit Perintah</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="batalPerintah()">Batal Perintah</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="cetakPerintah()">Cetak Surat Perintah</a>
<div style="float: right; margin-right: 5px;">
	<a href="#" class="easyui-linkbutton" plain="true" iconCls="icon-search" onclick="viewSearch()">Pencarian</a>
</div>
</div>
<div id="dlg" class="easyui-dialog" style="width:730px;height:450px;padding:5px 10px"
closed="true" buttons="#dlg-buttons">
<form id="fm" method="post">
<input type="hidden" name="id" id="id"/>
<table cellpadding="2" border=0>
<tr>
<td width="100px">UPT/Sub Unit</td>
<td colspan="3">: 
<input class="easyui-combobox" style="width:300px;" id="id_sub" name="id_sub" readonly required="true"/>
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
		<?php if($peran==MD5('1')){ ?> 
			$('#id_pengguna').combobox('clear'); 
			$('#id_pengguna').combobox('reload', './model/cb_pejabat.php?jbt=8&id='+rec.id); 
		<?php } ?>
	}
});
</script></td>
<td> Tahun Anggaran</td>
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
<td>No Permintaan</td>
<td colspan="2">: <input class="easyui-textbox" type="text" name="no_sp" id="no_sp" readonly style="width:100px;"></input></td>
<td>Tanggal Permintaan</td>
<td>: <input class="easyui-textbox" type="text" name="tgl_sp" id="tgl_sp" style="width:100px;" readonly></td>
</tr>
<tr>
<td>Nomor Surat SPPB</td>
<td colspan="2">: <input class="easyui-textbox" type="text" name="no_surat" id="no_surat" data-options="required:true" style="width:100px;"></input></td>
<td>Tanggal Surat SPPB</td>
<td>: <input class="easyui-datebox" type="text" name="tgl_surat" id="tgl_surat" data-options="formatter:myformatter,parser:myparser,required:true" style="width:100px;" validType="validDate"></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="cekBarang()" style="width:110px">Cek Barang</a></td>
</tr>
</table>
</form>
<div style="background:#fff">
   <table id="basket" fitColumns="true" rownumbers="true"  style="width1:150px;height:250px;" toolbar="#tb" url="./model/verifikasi_barang.php" title="Data Barang">
	   <thead>
		   <tr>
			   <th data-options="field:'nama_bar',width:220, align:'left', halign:'center',
                        formatter:function(value,row){
                            return row.nama_bar;
                        }">Nama Barang</th>
			   <th field="nama_sat" width=80 align="center" data-options="editor: {type:'textbox', options:{ readonly:'true'}}">Satuan</th>
			   <th field="jmlminta" width=100 align="center" data-options="editor: {type:'textbox', options:{ readonly:'true'}}">Jumlah Minta</th>
			   <th field="jmlstok" width=100 align="center" halign="center" data-options="editor: {type:'textbox', options:{ readonly:'true'}}">Jumlah Stok</th>
			   <th field="jmlkeluar" width=100 align="center" data-options="editor: {type:'textbox', options:{ required:'true'}}, formatter:formatStok">Jumlah Keluar</th>
			   <!--<th field="harga" width=110 align="right" halign="center" data-options="editor: {type:'textbox', options:{ readonly:'true'}}">Harga</th>-->
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
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-no" onclick="tolakSurat()" style="width:90px">Tolak</a>
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-save" onclick="savePerintah()" style="width:90px">Simpan</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Batal</a>
</div>


<div id="dls" class="easyui-dialog" style="width:450px;height:340px;padding:10px 20px"
closed="true" buttons="#dls-buttons">
<div class="ftitle">Pencarian Perintah Pengeluaran</div>
<form id="fms" method="post">
<table cellpadding="5">
<?php //if($peran==md5('1')){ ?>
<tr>
<td>Nama SKPD</td>
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
<?php //} ?>
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
	<td>Nomor Surat SPPB</td>
	<td>: <input class="easyui-textbox" type="text" name="nomor_search" id="nomor_search" size="15"></input></td>
</tr>
<tr>
	<td>Tgl Surat SPPB</td>
	<td>: <input class="easyui-datebox" type="text" name="tgl_search" id="tgl_search" data-options="formatter:myformatter,parser:myparser" style="width:100px;"></input></td>
</tr>
<!--<tr>
<td>Peruntukan</td>
<td>: 
<input class="easyui-combobox" style="width:250px;" id="id_sub_search" name="id_sub_search"/>
<script>
$('#id_sub_search').combobox({
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

<tr>
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
	$('#tgl_surat').datebox({
		onSelect: function(date){
			$('#basket').datagrid('loadData', {"total":0,"rows":[],"footer":[]});
		}
	});
	$('#tgl_surat').datebox('options').keyHandler.query = function(q){
		$('#basket').datagrid('loadData', {"total":0,"rows":[],"footer":[]});
	}
	$('#id_sub').combobox('textbox').css('background-color','#EEEEEE');
	$('#ta').combobox('textbox').css('background-color','#EEEEEE');
	$('#no_sp').textbox('textbox').css('background-color','#EEEEEE');
	$('#tgl_sp').datebox('textbox').css('background-color','#EEEEEE');
});

var urlu; var id_bar; var id_sat; var id_gud; var id_kel; var datser; var id_sub;  var id_unit; var id_surat; var id_sp; var tgl_akhir; var act;
var format_options = {aSep:'.', aNeg:'', aDec: ',',aPad: false};
$dataLoader = { imgUrl: 'images/ajaxloader.gif' };
	
function viewSearch(){
	$('#dls').dialog('open').dialog('setTitle','Pencarian Surat Perintah Pengeluaran');
	//$('#fms').form('clear');
}	
function doSearch(){
        $('#dgfull').datagrid('load',{
			<?php if($peran==md5('1') or $_SESSION['uidunit_plain'] == 'cfa4f56a-5543-11e6-a2df-000476f4fa98'){ ?> id_unit: $('#id_unit_search').combobox('getValue'), <?php } ?>
			ta: $('#ta_search').combobox('getValue'),
			nomor: $('#nomor_search').val(),
			tanggal: $('#tgl_search').datebox('getValue'),
			//id_sub: $('#id_sub_search').combobox('getValue')
        });
}
function verifikasi(){
	var row = $('#dgfull').datagrid('getSelected');
	console.log(row)
	if (row){
		if(row.status==0){
			$('#dlg').dialog('open').dialog('setTitle','Verifikasi Daftar Permintaan Barang');
			$('#fm').form('clear');
			$('#fm').form('load',row);
			$('#tgl_surat').datebox('readonly', false);
			$('#basket').datagrid('loadData', {"total":0,"rows":[],"footer":[]});
			id_unit = row.id_sub;
			id_surat = row.idsur;
			id_sp = row.idsp;
			tgl_akhir = row.tgl_sp_akhir;
			act = 'ver';
			urlu = './aksi.php?module=perintah_keluar&oper=add&id_minta='+row.idsur+'&id_sp='+row.idsp;
			editIndex = undefined;
		}else $.messager.alert('Peringatan', 'Surat Permintaan telah terverifikasi !');
	}else $.messager.alert('Peringatan','Pilih Data Surat Permintaan yang akan diverifikasi !');	
}

function cekBarang(){
	if($('#tgl_surat').datebox('getValue')==''){
		$.messager.alert('Peringatan', 'Tanggal Surat SPPB belum diisi !');
	}else{
		var d1 = new Date(myparser($('#tgl_surat').datebox('getValue')));
		var d2 = new Date(myparser(tgl_akhir));
		if(d1 < d2){
			$.messager.alert('Peringatan', 'Tanggal SPPB tidak bisa mundur lebih dari tanggal SPPB yang sudah ada !');
		}else{
			$('#tgl_surat').datebox('readonly', true);
			$('#basket').datagrid('load',{
				id: id_surat, act: act, tgl: $('#tgl_surat').datebox('getValue')
			});
		}
	}
}	

function tolakSurat(){
	$.messager.confirm('Peringatan','Apakah Anda yakin akan Menolak Surat Permintaan Barang ini?',function(r){
	if (r){
		$.ajax({
			type: "POST",
			url: './aksi.php?module=perintah_keluar&oper=tolak',
			data: { id_tolak: id_sp },
			beforeSend: function() {
				$.loader.open($dataLoader);
			},
			complete: function(){
				$.loader.close($dataLoader);
			},		
			success: function(data){
				var data = eval('('+data+')');
				if (data.success==false){
					$.messager.show({ title: 'Error', msg: data.pesan });
				} else {
					$.messager.show({ title: 'Sukses', msg: data.pesan }); 
				}	
				$('#dlg').dialog('close');
				id_surat = undefined;
			}
		});	
		}
	},'json');	
}


function editPerintah(){
	var row = $('#dgfull').datagrid('getSelected');
	if (row){
		if(row.status==1){
			$('#dlg').dialog('open').dialog('setTitle','Edit Surat Perintah Pengeluaran');
			$('#fm').form('clear');
			$('#fm').form('load',row);
			$('#tgl_surat').datebox('readonly', false);
			datser = row;
			$('#basket').datagrid('load',{
				id: row.idsur, act : 'edt'
			});
			act = 'edt';
			id_unit = row.id_sub;
			id_surat = row.idsur;
			id_sp = row.idsp;
			tgl_akhir = row.tgl_sp_akhir;
			urlu = './aksi.php?module=perintah_keluar&oper=edit&id_minta='+row.idsur+'&id_ubah='+row.idsp;
			editIndex = undefined;
		}else if(row.status==0) $.messager.alert('Peringatan','Tidak bisa Mengubah Data yang belum diverifikasi !');	
		else if(row.status==2) $.messager.alert('Peringatan','Tidak bisa Mengubah Data yang sudah ditolak !');	
		else if(row.status==3) $.messager.alert('Peringatan','Tidak bisa Mengubah Data yang sudah disalurkan !');	
	}else $.messager.alert('Peringatan','Pilih Data yang akan diubah !');	
}
function savePerintah(){
	var basket = $('#basket').datagrid('getData');
	var ceki;
	$.each(basket.rows, function(i,lab){
		var jmlkeluar = parseInt(lab['jmlkeluar'].replace(/\D/g, ""));
		var jmlstok = parseInt(lab['jmlstok'].replace(/\D/g, ""));
		if(jmlkeluar>jmlstok){ ceki = i; return false; }
	});
	
	if($('#fm').form('validate')==false){
		$.messager.show({ title: 'Error', msg: 'Data Surat Perintah Pengeluaran belum diisi' });
	}else if(validasiCombo('fm')==false){
		return;
	}else if(editIndex!=undefined){
		$.messager.show({ title: 'Error', msg: 'Setujui dulu perubahan data barang!' }); 
	}else if(ceki!=undefined){
		$('#basket').datagrid('selectRow', ceki).datagrid('beginEdit', ceki); 
		 editIndex = ceki;
		$.messager.alert('Peringatan','Jumlah Keluar melebihi Jumlah Stok !');
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
			data: { form: formData, basket: basket.rows, ubahform : ubah, id_unit : id_unit },
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
function batalPerintah(){
	var rw1 = $('#dgfull').datagrid('getSelected');
	if (rw1){
		if(rw1.status==1){
			$.messager.confirm('Peringatan','Apakah Anda yakin akan membatalkan Surat Perintah ini?',function(r){
			if (r){
				$.ajax({
					type: "POST",
					url: './aksi.php?module=perintah_keluar&oper=del',
					data: { id_hapus: rw1.idsp },
					success: function(data){
						console.log(data);
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
		}else if(rw1.status==2) $.messager.alert('Peringatan','Tidak bisa membatalkan data yang sudah ditolak !');
		else if(rw1.status==3) $.messager.alert('Peringatan','Tidak bisa membatalkan data yang sudah disalurkan !');else if(rw1.status==0) $.messager.alert('Peringatan','Tidak bisa membatalkan data yang belum diverifikasi !');	
	}else $.messager.alert('Peringatan','Pilih Surat Perintah yang akan dihapus dahulu !');	
}

		var editIndex = undefined;
        function endEditing(){
            if (editIndex == undefined){return true}
            if ($('#basket').datagrid('validateRow', editIndex)){
				var ed = $('#basket').datagrid('getEditors', editIndex); // get the editor
				var barang = $(ed[6].target).val();
				//var harga = $(ed[5].target);
				
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
		function onBeginEdit(rowIndex){
			var editors = $('#basket').datagrid('getEditors', rowIndex);
			var barang = $(editors[0].target);
			var satuan = $(editors[1].target);
			var jmlminta = $(editors[2].target);
			var jmlstok = $(editors[3].target);
			var jumlah = $(editors[4].target);
			//var harga = $(editors[5].target);
			var fbar = $(editors[6].target);
			var fsat = $(editors[7].target);
			
			jumlah.textbox('textbox').bind('keyup',function(e){
				var $this = $(this);
				var num = $this.val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
				$this.val(num);
			});
			
			//var a = barang.combobox('getValue');
			var a = fbar.textbox('getValue');
			
			barang.combobox({
				onSelect: function(rec){
					$.loader.open($dataLoader);
					$.post( "./model/cek_stok_fifo.php", { id_bar: rec.id_bar, id_sub: id_unit, id_sp: id_sp })
					.done(function( data ) {
						//console.log(data);
						jmlstok.textbox('setValue', data.saldo);
						//harga.textbox('setValue', data.harga);
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
			jmlminta.textbox('textbox').css('background-color','#EEEEEE');
			//harga.textbox('textbox').css('background-color','#EEEEEE');
		}
		
		function onBeforeEdit(row){
			var combar = $(this).datagrid('getColumnOption','nama_bar');
			
			combar.editor = {
				type: 'combobox',
				options:{
					valueField:'id_bar',
					textField:'nama_bar',
					method:'get',
					url:'./model/cb_barang_ada.php?id='+$('#id_sub').combobox('getValue'),
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
			if($('#tgl_surat').datebox('getValue')==""){ 
				$.messager.alert('Peringatan', 'Tanggal Surat SPPB belum diisi !');
			}else{	
				var d1 = new Date(myparser($('#tgl_surat').datebox('getValue')));
				var d2 = new Date(myparser(tgl_akhir));
				if(d1 < d2){
					$.messager.alert('Peringatan', 'Tanggal SPPB tidak bisa mundur lebih dari tanggal SPPB yang sudah ada !');
					return;
				}
				$('#tgl_surat').datebox('readonly', true);
				if (endEditing()){
					$('#basket').datagrid('appendRow',{status:'P', jmlminta: 0});
					editIndex = $('#basket').datagrid('getRows').length-1;
					$('#basket').datagrid('selectRow', editIndex)
							.datagrid('beginEdit', editIndex);
				}
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
function cetakPerintah(){
	var rw1 = $('#dgfull').datagrid('getSelected');
	if (rw1){
		if(rw1.status!=0){
			$.loader.open($dataLoader);
			$.post( "./print/sp_keluar_barang.php", { id : rw1.idsp })
			.done(function( data ) {
				window.location.href = data.url;
				$.loader.close($dataLoader);
			});
		}else $.messager.alert('Peringatan','Tidak bisa Mencetak Data yang belum diverifikasi !');
	}else $.messager.alert('Peringatan','Pilih Data Surat Perintah yang akan dicetak !');	
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
		if (parseInt(val.replace(/\D/g, "")) > parseInt(row.jmlstok.replace(/\D/g, ""))){
			return '<span style="color:red;">('+val+')</span>';
		} else {
			return val;
		}
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
