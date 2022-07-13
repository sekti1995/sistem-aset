 <div class="dtabel" style="width:100%;height:100%;background:white">	
 <table id="dgfull"class="easyui-datagrid"
	url="./model/hapus_barang.php" fit="true"
	toolbar="#toolbar" pagination="true" title="Input Penghapusan Barang"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
	<th field="nama_unit" width="200" align="left" halign="center">Nama Unit</th>
	<th field="ta" width="50" align="center">TA</th>
	<th field="nomor_ba" width="110" align="left" halign="center">Nomor BA</th>
	<th field="tanggal" width="80" align="center" halign="center">Tanggal BA</th>
	<th field="nomor_sk" width="110" align="left" halign="center">Nomor SK</th>
</tr>
</thead>
</table>
</div>
<div id="toolbar">
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newPenghapusan()">New Penghapusan</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editPenghapusan()">Edit Penghapusan</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyPenghapusan()">Remove Penghapusan</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="cetakBAPenghapusan()">Cetak BA Penghapusan</a>
<div style="float: right; margin-right: 5px;">
	<a href="#" class="easyui-linkbutton" plain="true" iconCls="icon-search" onclick="viewSearch()">Pencarian</a>
</div>
</div>
<div id="dlg" class="easyui-dialog" style="width:900px;height:485px;padding:5px 10px"
closed="true" buttons="#dlg-buttons">
<form id="fm" method="post">
<table cellpadding="2" border=0>
<tr>
<td width="100px"></td>
<td width="125px"></td>
<td width="30px"></td>
<td width="100px"></td>
<td width="100px"></td>
</tr>
<tr>
<td width="100px">Unit/Sub Unit</td>
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
		$('#basket').datagrid('loadData', {"total":0,"rows":[],"footer":[]});
		editIndex = undefined;
		$('#nomor_ba').combobox("clear");
		$('#nomor_ba').combobox('reload', './model/cb_usulan_hapus.php?id_sub='+id_sub);
	}
});
</script></td>
<td width="100px"> Tahun Anggaran</td>
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
<td width="125px">: <input class="easyui-textbox" type="text" name="nomor_ba" id="nomor_ba" data-options="required:true" style="width:100px;"></input>
<input type="hidden" name="nomor_ba2" id="nomor_ba2" />
	<script>
	$('#nomor_ba').combobox({
		url:'./model/cb_usulan_hapus.php',
		valueField:'id',
		textField:'text',
		filter: function(q, row){
			var opts = $(this).combobox('options');
			return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
		},
		onSelect: function(rec){
			id_sub = rec.id;
			$('#basket').datagrid('load',{
				id: id_sub
			});
			console.log(rec.tgl_ba_usulan)
			$("#nomor_ba2").val(rec.text);
			$("#tanggal").datebox("setValue", rec.tgl_ba_usulan);
		}
	});
	</script>

</td>
<td width="100px">Ketua</td>
<td colspan="3">: <input class="easyui-textbox" type="text" name="ketua" id="ketua" data-options="required:true"  style="width:130px;" ></input>
Jabatan Dinas : 
<input class="easyui-textbox" type="text" name="jab_ket" id="jab_ket" data-options="required:true"  style="width:130px;" ></input>
</td>
</tr>
<tr>
<td>Tanggal BA</td>
<td width="125px">: <input class="easyui-datebox" type="text" name="tanggal" id="tanggal" data-options="formatter:myformatter,parser:myparser,required:true" style="width:100px;" validType="validDate"></td>
<td> Sekretaris</td>
<td colspan="3">: <input class="easyui-textbox" type="text" name="sekretaris" id="sekretaris" data-options="required:true"  style="width:130px;" ></input> 
Jabatan Dinas : 
<input class="easyui-textbox" type="text" name="jab_sek" id="jab_sek" data-options="required:true"  style="width:130px;" ></input>
</td>
</tr>
<tr>
<td>No SK</td>
<td>: <input class="easyui-textbox" type="text" name="nomor_sk" id="nomor_sk" data-options="required:true" style="width:100px;"></input></td>
<td> Anggota</td>
<td colspan="3">: <input class="easyui-textbox" type="text" name="anggota1" id="anggota1" data-options="required:true"  style="width:130px;" ></input>
Jabatan Dinas : 
<input class="easyui-textbox" type="text" name="jab_ang1" id="jab_ang1" data-options="required:true"  style="width:130px;" ></input>
</td>
</tr>
<tr>
<td>Tahun SK</td>
<td>: <input class="easyui-textbox" type="text" name="tahun_sk" id="tahun_sk" data-options="required:true" style="width:100px;"></input></td>
<td> Anggota</td>
<td colspan="3">: <input class="easyui-textbox" type="text" name="anggota2" id="anggota2" data-options="required:true"  style="width:130px;" ></input>
Jabatan Dinas : 
<input class="easyui-textbox" type="text" name="jab_ang2" id="jab_ang2" data-options="required:true"  style="width:130px;" ></input>
</td>
</tr>
</table>
</form>
<div style="background:#fff">
   <table id="basket" fitColumns="true" rownumbers="true"  style="width1:150px;height:240px;" toolbar="#tb" url="./model/hapus_barang_detail.php" title="Data Barang">
	   <thead>
		    <tr>
				<th data-options="field:'nama_bar',width:160, align:'left', halign:'center',
                        formatter:function(value,row){
                            return row.nama_bar;
                        }" rowspan="2">Nama Barang</th>
			   <th field="nama_sat" width=80 align="center"  data-options="editor: {type:'textbox', options:{ readonly:'true'}}" rowspan="2">Satuan</th>
			   <th align="center" colspan="4" hidden>Kondisi</th>
				<th field="jumlah_usul" width=100 align="center" data-options="editor: {type:'textbox', options:{ readonly:'true'}}" rowspan="2">Jumlah Usulan</th>
				<th field="jumlah_stok" width=100 align="center" data-options="editor: {type:'textbox', options:{ readonly:'true'}}" rowspan="2">Jumlah Stok</th>
				<th field="jumlah" width=100 align="center" data-options="editor: {type:'textbox'}, formatter:formatStok" rowspan="2" >Jumlah Dihapus</th>
			   <!--<th field="harga" width=110 align="right" halign="center" data-options="editor: {type:'textbox', options:{ readonly:'true'}}" rowspan="2">Harga Satuan</th>
			   <th field="harga_total" width=110 align="right" halign="center"  data-options="editor: {type:'textbox', options:{ readonly:'true'}}"  rowspan="2">Harga Total</th>-->
			   <th data-options="field:'nama_gud',width:120, align:'left', halign:'center',
                        formatter:function(value,row){
                            return row.nama_gud;
                        }" rowspan="2" hidden>Dari Tempat</th>
			   <th data-options="field:'nama_sumber',width:120, align:'left', halign:'center',
                        formatter:function(value,row){
                            return row.nama_sumber;
                        }" rowspan="2" hidden>Sumber Dana</th>
			   <th field="id_bar" width=80 align="left" halign="center" editor="textbox" hidden rowspan="2">id_bar</th>
			   <th field="id_gud" width=80 align="left" halign="center" editor="textbox" hidden rowspan="2">id_gud</th>
			   <th field="id_sum" width=80 align="left" halign="center" editor="textbox" hidden rowspan="2">id_sum</th>
			   
			</tr>
			<tr hidden>
			   <th hidden field="baik" width=100 align="center" data-options="editor: {type:'textbox', options:{ required:'true'}}">Baik</th>
			   <th hidden field="ringan" width=110 align="center" data-options="editor: {type:'textbox', options:{ required:'true'}}">Rusak Ringan</th>
			   <th hidden field="berat" width=100 align="center" data-options="editor: {type:'textbox', options:{ required:'true'}}">Rusak Berat</th>
			   <th hidden field="kadaluarsa" width=100 align="center" data-options="editor: {type:'textbox', options:{ required:'true'}}">Kadaluarsa</th>
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
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="savePenghapusan()" style="width:90px">Save</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
</div>

<div id="dls" class="easyui-dialog" style="width:450px;height:340px;padding:10px 20px"
closed="true" buttons="#dls-buttons">
<div class="ftitle">Pencarian Penghapusan</div>
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
	<td>Nomor BA</td>
	<td>: <input class="easyui-textbox" type="text" name="nomor_search" id="nomor_search" size="15"></input>
	
	</td>
</tr>
<tr>
	<td>Tanggal BA</td>
	<td>: <input class="easyui-datebox" type="text" name="tgl_search" id="tgl_search" data-options="formatter:myformatter,parser:myparser" style="width:100px;"></input></td>
</tr>
<tr>
	<td>Nomor SK</td>
	<td>: <input class="easyui-textbox" type="text" name="nomor_sk_search" id="nomor_sk_search" size="15"></input></td>
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
		onClickCell: onClickCell,
		onEndEdit: onEndEdit,
		onBeginEdit: onBeginEdit,
		onBeforeEdit: onBeforeEdit
	});
	<?php if($_SESSION['level']==md5('c')){ ?> $('#id_sub').combobox('textbox').css('background-color','#EEEEEE'); <?php } ?>
	$('#tanggal').datebox({
		onSelect: function(date){
			$('#basket').datagrid('loadData', {"total":0,"rows":[],"footer":[]});
			editIndex = undefined;
		}
	});
});

var urlu; var id_bar; var id_sat; var id_gud; var id_kel; var datser; ;
var format_options = {aSep:'.', aNeg:'', aDec: ',',aPad: false};

function viewSearch(){
	$('#dls').dialog('open').dialog('setTitle','Pencarian Data Penghapusan Barang');
	//$('#fms').form('clear');
}
function doSearch(){
        $('#dgfull').datagrid('load',{
			<?php if($_SESSION['level']!=md5('c')){ ?> id_sub: $('#id_unit_search').combobox('getValue'), <?php } ?>
			ta: $('#ta_search').combobox('getValue'),
			nomor: $('#nomor_search').val(),
			tanggal: $('#tgl_search').datebox('getValue'),
			nomor_sk: $('#nomor_sk_search').val()
        });
}
function newPenghapusan(){
	$('#dlg').dialog('open').dialog('setTitle','Tambah Data Penghapusan Barang');
	$('#fm').form('clear');
	$('#id_sub').combobox('reload');
	$('#basket').datagrid('loadData', {"total":0,"rows":[],"footer":[]});
	urlu = './aksi.php?module=hapus_barang&oper=add';
	id_sub = $('#id_sub').combobox('getValue');
	editIndex = undefined;
}
function editPenghapusan(){
	var row = $('#dgfull').datagrid('getSelected');
	if (row){
		$('#dlg').dialog('open').dialog('setTitle','Edit Data Penghapusan Barang');
		$('#fm').form('clear');
		$('#fm').form('load',row);
		datser = row;
		$('#basket').datagrid('load',{
			id: row.id
		});
		id_sub = row.id_sub;
		urlu = './aksi.php?module=hapus_barang&oper=edit&id_ubah='+row.id;
		editIndex = undefined;
	}else $.messager.alert('Peringatan','Pilih Data Penghapusan Barang yang akan diubah !');	
}
function savePenghapusan(){
	var basket = $('#basket').datagrid('getData');
	
	if($('#fm').form('validate')==false){
		$.messager.show({ title: 'Error', msg: 'Data Penghapusan Barang belum diisi' });
	}else if(validasiCombo('fm')==false){
		return;
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
function destroyPenghapusan(){
	var rw1 = $('#dgfull').datagrid('getSelected');
	if (rw1){
		$.messager.confirm('Peringatan','Apakah Anda yakin akan menghapus Data Penghapusan ini?',function(r){
		if (r){
			$.ajax({
				type: "POST",
				url: './aksi.php?module=hapus_barang&oper=del',
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
	}else $.messager.alert('Peringatan','Pilih Data Penghapusan Barang yang akan dihapus dahulu !');	
}

		var editIndex = undefined; var tot_temp, hrg_temp;
        function endEditing(){
            if (editIndex == undefined){return true}
            if ($('#basket').datagrid('validateRow', editIndex)){
                var ed = $('#basket').datagrid('getEditors', editIndex); // get the editor
				var barang = $(ed[9].target).val();
				var jumlah = $(ed[6].target).val();
				var gudang = $(ed[10].target).val();
				var sumber = $(ed[11].target).val();
				
				var sama = "";
				var basket = $('#basket').datagrid('getData');
				$.each(basket.rows, function(i,lab){
					if( i!=editIndex) sama = 'ya'; 
				});
				// if(validasiCombo2(ed)==false) return false;
				if(sama=='ya'){
					$.messager.show({ title: 'Error', msg: "Barang Sudah ada dalam daftar!" }); 
					return false;
				}else{
					// $.loader.open($dataLoader); 
					// $.post( "./model/cek_stok.php", 
					// { 	id_bar : barang, id_gud : gudang, id_sub : $('#id_sub').combobox('getValue'), 
						// jumlah : jumlah, jenis : 'kb', id_sum : sumber, tanggal : $('#tanggal').datebox('getValue') })
					// .done(function( data ) {
						// $.loader.close($dataLoader);
						// if(data.hasil==true){
							// hrg_temp = data.harga;
							// tot_temp = data.total;
							$('#basket').datagrid('endEdit', editIndex);
							editIndex = undefined;
							return true;
						// }else{
							// var msg = 'Barang digudang yang siap tersisa : '+data.jumlah+' !';
							// if(data.pesan!=0) msg += '<br>Dengan saldo : '+data.saldo+'<br>Dan dipesan : '+data.pesan;
							// $.messager.alert('Peringatan',msg);
							// return false;
						// }
					// });
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
			var barang = $(editors[0].target);
			var satuan = $(editors[1].target);
			var baik = $(editors[2].target);
			var ringan = $(editors[3].target);
			var berat = $(editors[4].target);
			var kadaluarsa = $(editors[5].target);
			var jumlah = $(editors[6].target);
			var jumlah_stok = $(editors[7].target);
			var jumlah_akhir = $(editors[8].target);
			//var harga = $(editors[7].target);
			//var total = $(editors[8].target);
			var gudang = $(editors[9].target);
			var sumber = $(editors[10].target);
			var fbar = $(editors[11].target);
			var fgud = $(editors[12].target);
			var fsum = $(editors[13].target);
			
			baik.textbox('textbox').bind('keyup',function(e){
				var $this = $(this);
				var ba = Number($this.val().replace(/[^0-9,]/g,'').replace(",","."));
				var ri = Number(ringan.val().replace(/[^0-9,]/g,'').replace(",","."));
				var be = Number(berat.val().replace(/[^0-9,]/g,'').replace(",","."));
				var ka = Number(kadaluarsa.val().replace(/[^0-9,]/g,'').replace(",","."));
				//var hrg = harga.val().replace(/[^0-9,]/g,'').replace(",",".");
				var jm = ba+ri+be+ka;
				//var tt = jm*hrg;
				var jum = accounting.formatMoney(jm, '', 0, '.', ',');
				//var tot = accounting.formatMoney(tt, '', 0, '.', ',');
				var num = $this.val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
				$this.val(num);
				jumlah.textbox('setValue', jum);
				//total.textbox('setValue', tot);
			});
			ringan.textbox('textbox').bind('keyup',function(e){
				var $this = $(this);
				var ba = Number(baik.val().replace(/[^0-9,]/g,'').replace(",","."));
				var ri = Number($this.val().replace(/[^0-9,]/g,'').replace(",","."));
				var be = Number(berat.val().replace(/[^0-9,]/g,'').replace(",","."));
				var ka = Number(kadaluarsa.val().replace(/[^0-9,]/g,'').replace(",","."));
				//var hrg = harga.val().replace(/[^0-9,]/g,'').replace(",",".");
				var jm = ba+ri+be+ka;
				//var tt = jm*hrg;
				var jum = accounting.formatMoney(jm, '', 0, '.', ',');
				//var tot = accounting.formatMoney(tt, '', 0, '.', ',');
				var num = $this.val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
				$this.val(num);
				jumlah.textbox('setValue', jum);
				//total.textbox('setValue', tot);
			});
			berat.textbox('textbox').bind('keyup',function(e){
				var $this = $(this);
				var ba = Number(baik.val().replace(/[^0-9,]/g,'').replace(",","."));
				var ri = Number(ringan.val().replace(/[^0-9,]/g,'').replace(",","."));
				var be = Number($this.val().replace(/[^0-9,]/g,'').replace(",","."));
				var ka = Number(kadaluarsa.val().replace(/[^0-9,]/g,'').replace(",","."));
				//var hrg = harga.val().replace(/[^0-9,]/g,'').replace(",",".");
				var jm = ba+ri+be+ka;
				//var tt = jm*hrg;
				var jum = accounting.formatMoney(jm, '', 0, '.', ',');
				//var tot = accounting.formatMoney(tt, '', 0, '.', ',');
				var num = $this.val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
				$this.val(num);
				jumlah.textbox('setValue', jum);
				//total.textbox('setValue', tot);
			});
			kadaluarsa.textbox('textbox').bind('keyup',function(e){
				var $this = $(this);
				var ba = Number(baik.val().replace(/[^0-9,]/g,'').replace(",","."));
				var ri = Number(ringan.val().replace(/[^0-9,]/g,'').replace(",","."));
				var be = Number(berat.val().replace(/[^0-9,]/g,'').replace(",","."));
				var ka = Number($this.val().replace(/[^0-9,]/g,'').replace(",","."));
				//var hrg = harga.val().replace(/[^0-9,]/g,'').replace(",",".");
				var jm = ba+ri+be+ka;
				//var tt = jm*hrg;
				var jum = accounting.formatMoney(jm, '', 0, '.', ',');
				//var tot = accounting.formatMoney(tt, '', 0, '.', ',');
				var num = $this.val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
				$this.val(num);
				jumlah.textbox('setValue', jum);
				//total.textbox('setValue', tot);
			});
			/* harga.textbox('textbox').bind('keyup',function(e){
				var $this = $(this);
				var jum = jumlah.val().replace(/[^0-9,]/g,'').replace(",",".");
				var hrg = $this.val().replace(/[^0-9,]/g,'').replace(",",".");
				var tot = accounting.formatMoney((hrg*jum), '', 0, '.', ',');
				var num = $this.val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
				$this.val(num);
				total.textbox('setValue', tot);
			}); */
			
			var a = barang.combobox('getValue');
			var b = gudang.combobox('getValue');
			var c = sumber.combobox('getValue');
			
			barang.combobox({
				onSelect: function(rec){
					satuan.textbox('setValue', rec.simbol);
					fbar.textbox('setValue', rec.id_bar);
				}
			}).combobox('setValue',a);
			gudang.combobox({
				onSelect: function(rec){
					fgud.textbox('setValue', rec.id_gud);
				},
				onLoadSuccess: function(){
					var g = fgud.textbox('getValue')
					if(g==undefined || g=="") fgud.textbox('setValue', gudang.combobox('getValue'));
				}
			}).combobox('setValue',b);
			sumber.combobox({
				onSelect: function(rec){
					fsum.textbox('setValue', rec.id);
				},
				onLoadSuccess: function(){
					var s = fsum.textbox('getValue')
					if(s==undefined || s=="") fsum.textbox('setValue', sumber.combobox('getValue'));
				}
			}).combobox('setValue',c);
			jumlah.textbox('textbox').css('background-color','#EEEEEE');
			jumlah_stok.textbox('textbox').css('background-color','#EEEEEE');
			// jumlah_akhir.textbox('textbox').css('background-color','#EEEEEE');
			//harga.textbox('textbox').css('background-color','#EEEEEE');
			//total.textbox('textbox').css('background-color','#EEEEEE');
			satuan.textbox('textbox').css('background-color','#EEEEEE');
		}
		
		function onBeforeEdit(row){
			var combar = $(this).datagrid('getColumnOption','nama_bar');
			var comgud = $(this).datagrid('getColumnOption','nama_gud');
			var comsum = $(this).datagrid('getColumnOption','nama_sumber');
			
			id_sub = $("#id_sub").combobox("getValue");
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
			//row.harga = hrg_temp;
			//row.harga_total = tot_temp;
        }
        function append(){
			if($('#tanggal').datebox('getValue')=='') $.messager.alert('Peringatan','Tanggal BA diisi dulu!');
			else{
				if (endEditing()){
					$('#basket').datagrid('appendRow',{status:'P'});
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

function cetakBAPenghapusan(){
	var rw1 = $('#dgfull').datagrid('getSelected');
	if (rw1){
		$.post( "./print/ba_hapus_barang.php", { id : rw1.id })
		.done(function( data ) {
			window.location.href = data.url;
		});
	}else $.messager.alert('Peringatan','Pilih Data Penghapusan Barang yang akan dicetak !');	
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

function formatStok(val,row){
	if(val!=undefined){
		if (parseInt(val.replace(/\D/g, "")) > parseInt(row.jumlah_stok.replace(/\D/g, ""))){
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
