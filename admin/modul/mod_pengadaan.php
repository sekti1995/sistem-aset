 <div class="dtabel" style="width:100%;height:100%;background:white">	
 <table id="dgfull"class="easyui-datagrid"
	url="./model/pengadaan.php" fit="true"
	toolbar="#toolbar" pagination="true" title="Input Pengadaan Barang Persediaan"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
<th field="unit_kerja" width="200" align="left" halign="center">Nama SKPD/Unit Kerja</th>
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

<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newPengadaan()">New Pengadaan</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editPengadaan()">Edit Pengadaan</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyPengadaan()">Remove Pengadaan</a>
<div style="float: right; margin-right: 5px;">
	<a href="#" class="easyui-linkbutton" plain="true" iconCls="icon-search" onclick="viewSearch()">Pencarian</a>
</div>
</div>
<div id="dlg" class="easyui-dialog" style="width:800px;height:500px;padding:5px 10px"
closed="true" buttons="#dlg-buttons" data-options="onClose:function(){$('#dld').dialog('close')}">
<form id="fm" method="post">
<input type="hidden" name="sp" id="sp"/>
<input type="hidden" name="tgl_penerimaan" id="tgl_penerimaan"/>
<input type="hidden" name="id_gud" id="id_gud"/>
<table cellpadding="2" border=0>
<tr>
<td width="100px">SKPD/Unit Kerja</td>
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
		uid = rec.uid;
		$('#basket').datagrid('loadData', {"total":0,"rows":[],"footer":[]});
		$('#bas_rinci').datagrid('loadData', {"total":0,"rows":[],"footer":[]});
		$('#id_sumber').combobox('clear');
		$('#id_sumber').combobox('reload', './model/cb_sumber_dana.php?id='+rec.id);
		$('#kd_awal').textbox('setValue', rec.awal_dpa);
		$('#dld').dialog('close');
		editIndex = undefined;
		basrinci = {};
	},
	onLoadSuccess: function(){
		var data = $(this).combobox('getData');
		$.each( data, function( x, y ){
			if(data[x].selected==true){
				$('#kd_awal').textbox('setValue', data[x].awal_dpa);
				return;
			}	
		});
		
	}	
});
</script></td>
<td>Tahun Anggaran</td>
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
<td>Nama Pengadaan</td>
<td colspan="5">: <input class="easyui-textbox" type="text" name="nama_pengadaan" id="nama_pengadaan" data-options="required:true"   style="width:600px;"></input></td>
</tr>
<tr>
<td>Penyedia</td>
<td>: <input class="easyui-textbox" type="text" name="nama_penyedia" id="nama_penyedia" data-options="required:true"  style="width:150px;" ></input></td>
<td>Kontrak</td>
<td>: <input class="easyui-textbox" type="text" name="no_kontrak" id="no_kontrak" data-options="required:true"  style="width:100px;" ></input></td>
<td>Tanggal</td>
<td>: <input class="easyui-datebox" type="text" name="tgl_pengadaan" id="tgl_pengadaan" data-options="formatter:myformatter,parser:myparser,required:true" style="width:100px;" validType="validDate"></td>
</tr>
<tr>
<td>No DPA-SKPD / DPPA-SKPD / DPAL-SKPD</td>
<td colspan="3">: 
<input class="easyui-textbox" type="text" name="kd_awal" id="kd_awal"  style="width:90px;" ></input>
<input class="easyui-textbox" type="text" name="kd_prog" id="kd_prog" data-options="required:true"  style="width:30px;" ></input>
<input class="easyui-textbox" type="text" name="id_prog" id="id_prog" data-options="required:true"  style="width:30px;" ></input>
<input class="easyui-textbox" type="text" name="kd_keg" id="kd_keg" data-options="required:true"  style="width:30px;" ></input>
<input class="easyui-textbox" type="text" name="kd_rek_1" id="kd_rek_1" data-options="required:true"  style="width:30px;" ></input>
<input class="easyui-textbox" type="text" name="kd_rek_2" id="kd_rek_2" data-options="required:true"  style="width:30px;" ></input>
<input class="easyui-textbox" type="text" name="kd_rek_3" id="kd_rek_3" data-options="required:true"  style="width:30px;" ></input>
<input class="easyui-textbox" type="text" name="kd_rek_4" id="kd_rek_4" data-options="required:true"  style="width:30px;" ></input>
<input class="easyui-textbox" type="text" name="kd_rek_5" id="kd_rek_5" data-options="required:true"  style="width:30px;" ></input>
<input class="easyui-textbox" type="text" name="no_rinc" id="no_rinc" data-options="required:true"  style="width:30px;" ></input>
</td>
<td>No Pembayaran</td>
<td colspan="2">: <input class="easyui-textbox" type="text" name="no_pembayaran" id="no_pembayaran" data-options="required:true"  style="width:100px;" ></input></td>
</tr>
<tr>
<td>
Sumber Dana
</td>
<td colspan='3'>: <input class="easyui-combobox" style="width:270px;" id="id_sumber" name="id_sumber" required="true"/>
<script>
$('#id_sumber').combobox({
    url:'./model/cb_sumber_dana.php',
    valueField:'id',
    textField:'text',
    filter: function(q, row){
		var opts = $(this).combobox('options');
		return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
	}
});
</script>
</td>
<td>Tgl Pembayaran</td>
<td colspan="2">: <input class="easyui-datebox" type="text" name="tgl_pembayaran" id="tgl_pembayaran" data-options="formatter:myformatter,parser:myparser,required:true" style="width:100px;" validType="validDate"></td>
</tr>
</table>
</form>

<div style="background:#fff">
   <table id="basket" fitColumns="true" rownumbers="true"  style="width1:150px;height:240px;" toolbar="#tb">
	   <thead>
		   <tr>
			   <th data-options="field:'nama_kel',width:180, halign:'center',
                        formatter:function(value,row){
                            return row.nama_kel;
                        },
                        editor:{
                            type:'combobox',
                            options:{
                                valueField:'id_kel',
                                textField:'nama_kel',
                                method:'get',
                                url:'./model/cb_kelompok.php',
                                required:true,
								filter: function(q, row){
									var opts = $(this).combobox('options');
									return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
								},
								onSelect: function(rec){
									id_kel=rec.id_kel;
								}
                            }
                        }">Kelompok</th>
			   <th data-options="field:'nama_bar',width:250, align:'left', halign:'center',
                        formatter:function(value,row){
                            return row.nama_bar;
                        }">Nama Barang</th>
			   <th field="tahun" width=50 align="left" halign="center" editor="textbox">Tahun</th>
			   <th field="jumlah" width=70 align="center" data-options="editor: {type:'textbox', options:{ required:'true'}}">Jumlah</th>
			   <th field="nama_sat" width=70 align="center"  data-options="editor: {type:'textbox', options:{ readonly:'true'}}">Satuan</th>
			   <th field="harga_satuan" width=90 align="right" halign="center" data-options="editor: {type:'textbox', options:{ required:'true'}}">Harga Satuan</th>
			   <th field="harga" width=90 align="right" halign="center" data-options="editor: {type:'textbox', options:{ required:'true'}}">Harga Total</th>
			   <th field="harga_asli" width=150 align="right" halign="center" hidden="true" >Harga Asli</th>
			   <th field="ket" width=60 align="left" halign="center" editor="textbox">ket</th>
			   <th field="id_bar" width=80 align="left" halign="center" editor="textbox" hidden>id_bar</th>
			   <th field="id_sat" width=80 align="left" halign="center" editor="textbox" hidden>id_sat</th>
			   <th field="id_kel" width=80 align="left" halign="center" editor="textbox" hidden>id_kel</th>
			   <th field="idbas" width=80 align="left" halign="center" editor="textbox" hidden>idbas</th>
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
<a href="javascript:void(0)" id="savMasuk" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="savePengadaan()" style="width:90px">Save</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
</div>

<div id="dld" class="easyui-dialog" style="width:550px;height:240px;padding:10px 20px;top:50px" closed="true" closable="false" data-options="onBeforeClose:cekBasRinci">
	<table id="bas_rinci" fitColumns="true" rownumbers="true"  style="width1:150px;height:180px;" toolbar="#tbr">
	   <thead>
		   <tr>
			   <th data-options="field:'nama_bar',width:160, align:'left', halign:'center',
                        formatter:function(value,row){
                            return row.nama_bar;
                        }">Nama Barang</th>
			   <th field="jumlah" width=100 align="center" data-options="editor: {type:'textbox', options:{ required:'true'}}">Jumlah</th>
			   <th field="nama_sat" width=80 align="center"  data-options="editor: {type:'textbox', options:{ readonly:'true'}}">Satuan</th>
			   <th field="harga_satuan" width=90 align="right" halign="center" data-options="editor: {type:'textbox', options:{ required:'true'}}">Harga Satuan</th>
			   <th field="harga" width=150 align="right" halign="center" data-options="editor: {type:'textbox', options:{ required:'true'}}">Harga Total</th>
			   <th field="id_bar" width=80 align="left" halign="center" editor="textbox" hidden>id_bar</th>
			   <th field="id_sat" width=80 align="left" halign="center" editor="textbox" hidden>id_sat</th>
		</tr>
		</thead>
	</table>
<div id="tbr" style="height:auto">
	<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="appendr()">Tambah</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="removeitr()">Hapus</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="acceptr()">Setuju</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-undo',plain:true" onclick="rejectr()">Batal</a>
</div>
</div>



<div id="dls" class="easyui-dialog" style="width:450px;height:380px;padding:10px 20px"
closed="true" buttons="#dls-buttons">
<div class="ftitle">Pencarian Pengadaan</div>
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

$(function(){
	var tgl1; var bln1; var thn1; var tgl2; var bln2; var thn2;
	
	<?php
	if($_SESSION['level'] != 'd41d8cd98f00b204e9800998ecf8427e'){
	?>
	 
	$.get( "./model/get_lock.php", function( data ) {
		var data = eval('('+data+')');
		// //console.log(data)
		var lock_skpd = "<?php echo $_SESSION['kode_sub']; ?>";
		// //console.log(lock_skpd)
		if( lock_skpd in oc(data) ) {
			//NOT LOCK
		// } else { 
			// START
			
			$.get( "./model/get_lock_date.php", function( data ) {
				var data = eval('('+data+')');
				tgl1 = data[0].tgl1;
				bln1 = data[0].bln1;
				thn1 = data[0].thn1;
				
				tgl2 = data[0].tgl2;
				bln2 = data[0].bln2;
				thn2 = data[0].thn2;
				
				$('#tgl_pengadaan').datebox().datebox('calendar').calendar({
					validator: function(date){
						var d1 = new Date(thn1, bln1, tgl1);
						var d2 = new Date(thn2, bln2, tgl2);
						
						return date<=d2 && date >= d1;
					}
				});
				$('#tgl_pembayaran').datebox().datebox('calendar').calendar({
					validator: function(date){
						var d1 = new Date(thn1, bln1, tgl1);
						var d2 = new Date(thn2, bln2, tgl2);
						
						return date<=d2 && date >= d1;
					}
				});
			
			});
			
			// END
		} else { 
			
		}
	});
	 
	<?php
	}
	?>
	
	
	
	
	$('#basket').datagrid({
		singleSelect:true,
		showFooter:true,
		onClickCell: onClickCell,
		onEndEdit: onEndEdit,
		onBeginEdit: onBeginEdit,
		onBeforeEdit: onBeforeEdit
	});
	$('#bas_rinci').datagrid({
		singleSelect:true,
		onClickCell: clickRinci,
		onEndEdit: endRinci,
		onBeginEdit: beginRinci,
		onBeforeEdit: beforeRinci
	});

	$('#kd_prog').textbox('textbox').bind('keyup', function(e){
		var $this = $(this);
		var num = $this.val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
		$this.val(num);
		if($this.val().length >= 2){
			$this.val(num.substr(0, 2));
			//$('#id_prog').textbox('textbox').focus();
		}	
	});
	$('#id_prog').textbox('textbox').bind('keyup', function(e){
		var $this = $(this);
		var num = $this.val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
		$this.val(num);
		if($(this).val().length >= 3){
			$this.val(num.substr(0, 3));
			//$('#kd_keg').textbox('textbox').focus();
		}	
	});
	$('#kd_keg').textbox('textbox').bind('keyup', function(e){
		var $this = $(this);
		var num = $this.val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
		$this.val(num);
		if($(this).val().length >= 2){
			$this.val(num.substr(0, 2));
			//$('#kd_rek_1').textbox('textbox').focus();
		}	
	});
	$('#kd_rek_1').textbox('textbox').bind('keyup', function(e){
		var $this = $(this);
		var num = $this.val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
		$this.val(num);
		if($(this).val().length >= 2){
			$this.val(num.substr(0, 2));
			//$('#kd_rek_2').textbox('textbox').focus();
		}	
	});
	$('#kd_rek_2').textbox('textbox').bind('keyup', function(e){
		var $this = $(this);
		var num = $this.val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
		$this.val(num);
		if($(this).val().length >= 2){
			$this.val(num.substr(0, 2));
			//$('#kd_rek_3').textbox('textbox').focus();
		}	
	});
	$('#kd_rek_3').textbox('textbox').bind('keyup', function(e){
		var $this = $(this);
		var num = $this.val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
		$this.val(num);
		if($(this).val().length >= 2){
			$this.val(num.substr(0, 2));
			//$('#kd_rek_4').textbox('textbox').focus();
		}	
	});
	$('#kd_rek_4').textbox('textbox').bind('keyup', function(e){
		var $this = $(this);
		var num = $this.val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
		$this.val(num);
		if($(this).val().length >= 2){
			$this.val(num.substr(0, 2));
			//$('#kd_rek_5').textbox('textbox').focus();
		}	
	});
	$('#kd_rek_5').textbox('textbox').bind('keyup', function(e){
		var $this = $(this);
		var num = $this.val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
		$this.val(num);
		if($(this).val().length >= 2){
			$this.val(num.substr(0, 2));
			//$('#no_rinc').textbox('textbox').focus();
		}	
	});
	$('#no_rinc').textbox('textbox').bind('keyup', function(e){
		var $this = $(this);
		var num = $this.val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
		$this.val(num);
		if($(this).val().length >= 2){
			$this.val(num.substr(0, 2));
			//$('#no_pembayaran').textbox('textbox').focus();
		}	
	});
	
	<?php if($_SESSION['level']==md5('c')){ ?> $('#id_sub').combobox('textbox').css('background-color','#EEEEEE'); <?php } ?>
});

var urlu; var id_bar; var id_sat; var id_gud; var id_kel; var datser; var id_sub; var uid; var aksi;
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
function newPengadaan(){
	$('#dlg').dialog('open').dialog('setTitle','Tambah Data Pengadaan');
	$('#savMasuk').linkbutton('enable');
	$('#fm').form('clear');
	$('#id_sub').combobox('reload');
	$('#basket').datagrid('loadData', {"total":0,"rows":[],"footer":[]});
	urlu = './aksi.php?module=pengadaan&oper=add';
	id_sub = $('#id_sub').combobox('getValue');
	$('#ta').combobox('reload');
	$('#ta').combobox('readonly', true);
	$('#id_sumber').combobox('reload');
	$('#id_sumber').combobox('readonly', true);
	editIndex = undefined; 
	aksi = "add";
}
function editPengadaan(){
	var row = $('#dgfull').datagrid('getSelected');
	if(row.lock == "1"){
		$.messager.alert('Peringatan','Entrian Anda dikunci sampai dengan '+row.tgl_kunci_sampai);	
		return false;
	}
	if (row){
		$('#dlg').dialog('open').dialog('setTitle','Lihat Data Pengadaan');
		//$('#savMasuk').linkbutton('disable');
		$('#fm').form('clear');
		$('#fm').form('load',row);
		datser = row;
		var url="./model/pengadaan_detail.php"
		$.post( url, { id: row.id }, function( data ) {
			var data = eval('('+data+')');
			$('#basket').datagrid('loadData',data);
			x = data.total;
			basrinci = data.rinci;
		});
		id_sub = row.id_sub;
		uid = row.uid;
		editIndex = undefined; 
		aksi = "edit";
		urlu = './aksi.php?module=pengadaan&oper=edit&id_ubah='+row.id;
		$('#id_sumber').combobox('reload', './model/cb_sumber_dana.php?id='+id_sub);
	}else $.messager.alert('Peringatan','Pilih Data Pengadaan yang akan diubah !');	
}
function savePengadaan(){
	var basket = $('#basket').datagrid('getData');
	
	if($('#fm').form('validate')==false){
		$.messager.show({ title: 'Error', msg: 'Data Pengadaan belum diisi' });
	}else if(validasiCombo('fm')==false) return false;
	else if(basket.total==0){
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
		
		$.ajax({
			type: "POST",
			url: urlu,
			data: { form: formData, basket : basket, ubahform : ubah, basrinci : basrinci },
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
					$('#dlg').dialog('close');	
					aksi = datser = undefined;
					basrinci = {};
				}
			}
		});
	}

}
function destroyPengadaan(){
	var rw1 = $('#dgfull').datagrid('getSelected');
	if(rw1.lock == "1"){
		$.messager.alert('Peringatan','Entrian Anda dikunci sampai dengan '+row.tgl_kunci_sampai);	
		return false;
	}
	if (rw1){
		$.messager.confirm('Peringatan','Apakah Anda yakin akan menghapus Barang ini?',function(r){
		if (r){
			$.ajax({
				type: "POST",
				url: './aksi.php?module=pengadaan_baru&oper=softdel',
				data: { id_hapus: rw1.id },
				success: function(data){
					$.messager.show({ title: 'Konfirmasi', msg: data });	
					$('#dgfull').datagrid('reload');			
				}
			});	
			}
		},'json');
	}else $.messager.alert('Peringatan','Pilih Pengadaan yang akan dihapus dahulu !');	
}

		var editIndex = undefined;
        function endEditing(){
            if (editIndex == undefined){return true}
            if ($('#basket').datagrid('validateRow', editIndex)){
				var ed = $('#basket').datagrid('getEditors', editIndex); // get the editor
				var barang = $(ed[8].target).val();
				var kel = $(ed[10].target).val();
				var idb = $(ed[11].target).val();
				
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
					if(kel==3 || kel==4){
						if(indexRinci==undefined){
							var brData = $('#bas_rinci').datagrid('getData');
							if(!(idb in basrinci)==true){
								basrinci[idb] = brData;
							}
						}else{
							$.messager.show({ title: 'Error', msg: "Rincian Barang belum disetujui!" }); 
							return false;
						}
					}
					$('#basket').datagrid('endEdit', editIndex);
					editIndex = undefined;
					return true;
				}
            } else {
				$.messager.show({ title: 'Error', msg: "Inputan Berwarna merah harus diisi!" }); 
                return false;
            }
        }
        function onClickCell(index, field){
            if (editIndex != index){
                if (endEditing()){
                    $('#basket').datagrid('selectRow', index);
					var row = $('#basket').datagrid('getSelected');
					var kel;
					if(row.id_kel==3 || row.id_kel==4){
						urlbar = './model/cb_barang.php?kel='+row.id_kel+'&idsub='+$('#id_sub').combobox('getValue');
						kel = "ya";
					}else urlbar = './model/cb_barang.php?kel='+row.id_kel;
					
					if(aksi=='edit'){
						
						if(kel=='ya'){
							$.messager.alert('Peringatan','Tidak bisa mengedit dengan kelompok 3 & 4 !');
							return;
						}
						if(row.edited=='1'){
							$.messager.alert('Peringatan','Tidak bisa mengedit Barang yang sudah dikeluarkan !');
							return;
						}	
					}
					$('#basket').datagrid('beginEdit', index);
					var ed = $('#basket').datagrid('getEditor', {index:index,field:field});
					if (ed){
						($(ed.target).data('textbox') ? $(ed.target).textbox('textbox') : $(ed.target)).focus();
					}
					editIndex = index;
					var editors = $('#basket').datagrid('getEditors', editIndex);
					var fkel = $(editors[10].target).textbox('getValue');
					var idb = $(editors[11].target).textbox('getValue');
					
					if(fkel==3 || fkel==4){
						$('#dld').dialog('open').dialog('setTitle','Rincian Barang');
						$('#bas_rinci').datagrid('loadData', basrinci[idb]);
					}	
					
                } else {
                    setTimeout(function(){
                        $('#basket').datagrid('selectRow', editIndex);
                    },0);
                }
            }
        }
		function onBeginEdit(rowIndex){
			var editors = $('#basket').datagrid('getEditors', rowIndex);
			var barang = $(editors[1].target);
			var jumlah = $(editors[3].target);
			var harga = $(editors[6].target);
			var hargasat = $(editors[5].target);
			var satuan = $(editors[4].target);
			var kelompok = $(editors[0].target);
			var fbar = $(editors[8].target);
			var fsat = $(editors[9].target);
			var fkel = $(editors[10].target);
			
			jumlah.textbox('textbox').bind('keyup',function(e){
				var $this = $(this);
				/* var jum = $this.val().replace(/[^\d\,]/g, '').replace(',','.');
				var sat = hargasat.textbox('textbox').val().replace(/[^\d\,]/g, '').replace(',','.');
				var tot = sat*jum;
				if(jum.indexOf('.')>-1){
					var jm = jum.split('.');
					var jm0 = jm[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
					var jmgab = jm0+','+jm[1];
				}else var jmgab = jum.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
				$this.val(jmgab);
				harga.textbox('setValue', accounting.formatMoney(tot, '', 2, '.', ',')); */
				var $this = $(this);
				var jum = $this.val().replace(/\D/g, "");
				var sat = hargasat.textbox('textbox').val().replace(/\D/g, "");
				var tot = sat*jum;
				$this.val(jum.replace(/\B(?=(\d{3})+(?!\d))/g, "."));
				harga.textbox('setValue', accounting.formatMoney(tot, '', 0, '.', ','));
			});
			hargasat.textbox('textbox').bind('keyup',function(e){
				var $this = $(this);
				var sat = $this.val().replace(/\D/g, "");
				var jum = jumlah.textbox('textbox').val().replace(/\D/g, "");
				var tot = sat*jum;
				$this.val(sat.replace(/\B(?=(\d{3})+(?!\d))/g, "."));
				harga.textbox('setValue', accounting.formatMoney(tot, '', 0, '.', ','));
			});
			harga.textbox('textbox').bind('keyup',function(e){
				var $this = $(this);
				var tot = $this.val().replace(/\D/g, "");
				var jum = jumlah.textbox('getValue').replace(/\D/g, "");
				var sat = tot/jum;
				$this.val(tot.replace(/\B(?=(\d{3})+(?!\d))/g, "."));
				hargasat.textbox('setValue', accounting.formatMoney(sat, '', 0, '.', ','));
			});
			
			//var a = barang.combobox('getValue');
			//var c = kelompok.combobox('getValue');
			var a = fbar.textbox('getValue');
			var c = fkel.textbox('getValue');
			
			barang.combobox({
				onSelect: function(rec){
					satuan.textbox('setValue', rec.simbol);
					fsat.textbox('setValue', rec.id_satuan);
					fbar.textbox('setValue', rec.id_bar);
					//harga.textbox('setValue', rec.hrgi);
				}
			}).combobox('setValue',a);
			if(aksi=='edit'){
				if(c=="") c = 1;
				var ekel = true;
			}else var ekel = false;

			kelompok.combobox({
				onSelect: function(rec){
					fkel.textbox('setValue', rec.id_kel);
					if(rec.id_kel==3 || rec.id_kel==4){
						$('#dld').dialog('open').dialog('setTitle','Rincian Barang');
						hargasat.textbox('readonly', true).textbox('setValue', '');
						harga.textbox('readonly', true).textbox('setValue', '');
						jumlah.textbox('readonly', true).textbox('setValue', '1');
						barang.combobox('clear');
						satuan.textbox('setValue', '');
						barang.combobox('reload', './model/cb_barang.php?kel='+rec.id_kel+'&idsub='+$('#id_sub').combobox('getValue'));
					}else{
						$('#dld').dialog('close');
						hargasat.textbox('readonly', false).textbox('setValue', '');
						harga.textbox('readonly', false).textbox('setValue', '');
						jumlah.textbox('readonly', false).textbox('setValue', '');
						barang.combobox('clear');
						satuan.textbox('setValue', '');
						barang.combobox('reload', './model/cb_barang.php?kel='+rec.id_kel);
					}
				},
				readonly: ekel
			}).combobox('setValue',c);
			satuan.textbox('textbox').css('background-color','#EEEEEE');
		}
		
		function onBeforeEdit(row){
			var comkel = $(this).datagrid('getColumnOption','nama_kel');
			var combar = $(this).datagrid('getColumnOption','nama_bar');
			var satuan = $(this).datagrid('getColumnOption','nama_sat');
			
			var editors = $('#basket').datagrid('getEditors', row);
			combar.editor = {
				type: 'combobox',
				options:{
					valueField:'id_bar',
					textField:'nama_bar',
					method:'get',
					url:urlbar,
					required:true,
					filter: function(q, row){
						var opts = $(this).combobox('options');
						return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
					}
				}
			}
			/* if(aksi=='edit')
			comkel.editor = {
				type: 'combobox',
				options:{
					readonly: true
				}
			} */
		}
		
        function onEndEdit(index, row){
            var ed = $(this).datagrid('getEditor', {
                index: index,
                field: 'nama_bar'
            });
			var ed2 = $(this).datagrid('getEditor', {
                index: index,
                field: 'nama_kel'
            });
			var ed4 = $(this).datagrid('getEditor', {
                index: index,
                field: 'harga'
            });
            var ed5 = $(this).datagrid('getEditor', {
                index: index,
                field: 'nama_sat'
            });
			
            row.nama_bar = $(ed.target).combobox('getText');
            row.nama_kel = $(ed2.target).combobox('getText');
            row.harga_asli = $(ed4.target).textbox('textbox').val().replace(/[^0-9,]/g,'').replace(",",".");
        }
		var x = 0;
        function append(){
            if (endEditing()){
				urlbar = './model/cb_barang.php';
                $('#basket').datagrid('appendRow',{idbas:x});
                editIndex = $('#basket').datagrid('getRows').length-1;
                $('#basket').datagrid('selectRow', editIndex)
                        .datagrid('beginEdit', editIndex);
				$('#dld').dialog('close');
				x++;				
            }
        }
        function removeit(){
            if (editIndex == undefined){return}
			var ed = $('#basket').datagrid('getEditors', editIndex);
			var idr = $(ed[11].target).val();
			
			if (idr > -1) {
				delete basrinci[idr];
			}
			
            $('#basket').datagrid('cancelEdit', editIndex)
                    .datagrid('deleteRow', editIndex);
            editIndex = undefined;
			hitungTotal();
			
        }
        function accept(){
			var rows = $('#basket').datagrid('getChanges');
            if (endEditing()){
                $('#basket').datagrid('acceptChanges');
				hitungTotal();
            }
        }
        function reject(){
            $('#basket').datagrid('rejectChanges');
            editIndex = undefined;
			hitungTotal();
        }
		

function hitungTotal(){
	$('#dld').dialog('close');
	var dg = $('#basket');
	var cost = 0;
	var rows = dg.datagrid('getRows');
	for(var i=0; i<rows.length; i++){
		cost += parseInt(rows[i].harga_asli);
	}
	
	var total2 = accounting.formatMoney(cost, '', 0, '.', ',');
	dg.datagrid('reloadFooter', [{ merk_tipe:'Total', harga:total2}]);
	tot = cost;
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


		var indexRinci = undefined;
        function endEditRinci(){
            if (indexRinci == undefined){return true}
            if ($('#bas_rinci').datagrid('validateRow', indexRinci)){
                $('#bas_rinci').datagrid('endEdit', indexRinci);
                indexRinci = undefined;
                return true;
            } else {
                return false;
            }
        }
        function clickRinci(index, field){
            if (indexRinci != index){
                if (endEditRinci()){
                    $('#bas_rinci').datagrid('selectRow', index)
                            .datagrid('beginEdit', index);
                    var ed = $('#bas_rinci').datagrid('getEditor', {index:index,field:field});
                    if (ed){
                        ($(ed.target).data('textbox') ? $(ed.target).textbox('textbox') : $(ed.target)).focus();
                    }
                    indexRinci = index;
                } else {
                    setTimeout(function(){
                        $('#bas_rinci').datagrid('selectRow', indexRinci);
                    },0);
                }
            }
        }
		function beginRinci(rowIndex){
			var editors = $('#bas_rinci').datagrid('getEditors', rowIndex);
			var barang = $(editors[0].target);
			var jumlah = $(editors[1].target);
			var satuan = $(editors[2].target);
			var hargasat = $(editors[3].target);
			var harga = $(editors[4].target);
			var fbar = $(editors[5].target);
			var fsat = $(editors[6].target);
			
			jumlah.textbox('textbox').bind('keyup',function(e){
				var $this = $(this);
				var jum = $this.val().replace(/\D/g, "");
				var sat = hargasat.textbox('textbox').val().replace(/\D/g, "");
				var tot = sat*jum;
				$this.val(jum.replace(/\B(?=(\d{3})+(?!\d))/g, "."));
				harga.textbox('setValue', accounting.formatMoney(tot, '', 0, '.', ','));
			});
			hargasat.textbox('textbox').bind('keyup',function(e){
				var $this = $(this);
				var sat = $this.val().replace(/\D/g, "");
				var jum = jumlah.textbox('textbox').val().replace(/\D/g, "");
				var tot = sat*jum;
				$this.val(sat.replace(/\B(?=(\d{3})+(?!\d))/g, "."));
				harga.textbox('setValue', accounting.formatMoney(tot, '', 0, '.', ','));
			});
			harga.textbox('textbox').bind('keyup',function(e){
				var $this = $(this);
				var tot = $this.val().replace(/\D/g, "");
				var jum = jumlah.textbox('getValue').replace(/\D/g, "");
				var sat = tot/jum;
				$this.val(tot.replace(/\B(?=(\d{3})+(?!\d))/g, "."));
				hargasat.textbox('setValue', accounting.formatMoney(sat, '', 0, '.', ','));
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
		
		function beforeRinci(row){
			var combar = $(this).datagrid('getColumnOption','nama_bar');
			var satuan = $(this).datagrid('getColumnOption','nama_sat');
			
			var editors = $('#bas_rinci').datagrid('getEditors', row);
			combar.editor = {
				type: 'combobox',
				options:{
					valueField:'id_bar',
					textField:'nama_bar',
					method:'get',
					url:'./model/cb_barang.php',
					required:true,
					filter: function(q, row){
						var opts = $(this).combobox('options');
						return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
					}
				}
			}
			
		}
		
        function endRinci(index, row){
            var ed = $(this).datagrid('getEditor', {
                index: index,
                field: 'nama_bar'
            });
			var ed4 = $(this).datagrid('getEditor', {
                index: index,
                field: 'harga'
            });
            var ed5 = $(this).datagrid('getEditor', {
                index: index,
                field: 'nama_sat'
            });
			
            row.nama_bar = $(ed.target).combobox('getText');
            row.harga_asli = $(ed4.target).textbox('textbox').val().replace(/[^0-9,]/g,'').replace(",",".");
        }
        function appendr(){
            if (endEditRinci()){
                $('#bas_rinci').datagrid('appendRow',{status:'P'});
                indexRinci = $('#bas_rinci').datagrid('getRows').length-1;
                $('#bas_rinci').datagrid('selectRow', indexRinci)
                        .datagrid('beginEdit', indexRinci);
            }
        }
        function removeitr(){
            if (indexRinci == undefined){return}
            $('#bas_rinci').datagrid('cancelEdit', indexRinci)
                    .datagrid('deleteRow', indexRinci);
			tulisHrgBarang();
            indexRinci = undefined;
        }
        function acceptr(){
			var rows = $('#bas_rinci').datagrid('getChanges');
            if (endEditRinci()){
                $('#bas_rinci').datagrid('acceptChanges');
				tulisHrgBarang();
            }
        }
        function rejectr(){
            $('#bas_rinci').datagrid('rejectChanges');
			tulisHrgBarang();
            indexRinci = undefined;
        }
		var basrinci = {};
		function tulisHrgBarang(){
			var dg = $('#bas_rinci');
			var cost = 0;
			var rows = dg.datagrid('getRows');
			for(var i=0; i<rows.length; i++){
				cost += parseInt(rows[i].harga_asli);
			}
			
			var total = accounting.formatMoney(cost, '', 0, '.', ',');

			var editors = $('#basket').datagrid('getEditors', editIndex);
			var hargasat = $(editors[5].target);
			var harga = $(editors[6].target);
			//var idb = $(editors[11].target).textbox('getValue');
			hargasat.textbox('setValue', total);
			harga.textbox('setValue', total);
			
			/* var brData = $('#bas_rinci').datagrid('getData');
			if(!(idb in basrinci)==true){
				basrinci[idb] = brData;
			} */
			//console.log(editors);
		}
		
		function cekBasRinci(){
			/* var br = $('#bas_rinci').datagrid('getData');
			if(br.total==0){
				$.messager.show({ title: 'Error', msg: 'Data Rincian Barang Belum diisi!' });
				return false;
			}else{
				if (!endEditRinci()){
					$.messager.show({ title: 'Error', msg: 'Data Rincian Barang Belum diisi!' });
					return false;
				}else{ */
					$('#bas_rinci').datagrid('loadData', {"total":0,"rows":[]});
			//	}
			//}
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
