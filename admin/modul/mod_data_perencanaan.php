<div class="dtabel" style="width:100%;height:100%;background:white">	
 <table id="dgfull"class="easyui-datagrid"
	url="./model/log_import.php" fit="true" showFooter="true"
	toolbar="#toolbar" pagination="true" title="Import Data Perencanaan Barang"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
<th field="uuid_skpd" width="70" >OPD</th>
<th field="nm_kegiatan" width="70" >Nama Kegiatan</th>
<th field="nm_barang" width="150" align="left"  halign="center">Nama Barang</th>
<th field="nama_satuan" width="60" align="left"  halign="center">Satuan</th>
<th field="jumlah_barang" width="60" align="left"  halign="center">Jumlah Satuan Barang</th>
<th field="jumlah_barang_isi" width="60" align="left"  halign="center">Jumlah Sisa Satuan Barang</th>
<th field="harga" width="60" align="right"  halign="center">Harga Satuan</th>
<!--<th field="total_pengadaan" width="40" align="right" halign="center">Total</th>-->
</tr>
</thead>
</table>
</div>
<div id="toolbar"> 
	<div>
		<form id="fm" method="post" enctype="multipart/form-data">
			<table cellpadding="5">
			<tr>
			<td>Sub Unit</td>
			<td>: 
				<input class="easyui-combobox" style="width:225px;" id="id_sub" name="id_sub"  required="true"/>
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
						$('#id_sumber').combobox('clear');
						$('#id_sumber').combobox('reload', './model/cb_sumber_dana.php?cek&id='+rec.id);
					}
				});
				</script>
			</td> 
			<td>Sumber Dana</td>
			<td>: 
				<input class="easyui-combobox" style="width:160px;" id="id_sumber_dana" name="id_sumber_dana"  required="true"/>
				<script>
				$('#id_sumber_dana').combobox({
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
			<!-- <td>SMT</td>
			<td>: 
				<input class="easyui-combobox" style="width:50px;" id="smt" name="smt"  required="true"/>
				<script>
				$('#smt').combobox({
					valueField:'id',
					textField:'text',
					panelHeight:"auto",
					data:[{"id":"1","text":"1"},{"id":"2","text":"2"}],
					filter: function(q, row){
						var opts = $(this).combobox('options');
						return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
					}
				});
				</script>
			</td>  -->
			<td>TA</td>
			<td>: 
				<input class="easyui-combobox" style="width:80px;" id="ta" name="ta"  required="true"/>
				<script>
				$('#ta').combobox({
					url:'./model/cb_tahun.php',
					valueField:'id',
					textField:'text',
					panelHeight:"auto",
					filter: function(q, row){
						var opts = $(this).combobox('options');
						return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
					}
				});
				</script>
			</td> 
			<!--
			<td>Data</td>
			<td>: 
				<input class="easyui-combobox" style="width:150px;" id="data" name="data"  required="true"/>
				<script>
				$('#data').combobox({
					valueField:'id',
					textField:'text',
					data:[{"id":1,"text":"Pengadaan"},{"id":2,"text":"Pengeluaran"},{"id":3,"text":"Pengadaan & Pengeluaran"}]
				});
				</script>
			</td> 
			-->
			 
				<td>File Persediaan</td>
				<td>
					: <input class="easyui-filebox" type="text" name="file_awal" id="file_awal" style="width:200px;"></input>
				</td> 
				<td>
					<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="importDataPersediaan()" id="saveCek" style="width:90px">Upload</a>
				</td> 
			</tr>	
			<?php
			if($_SESSION['peran_id'] == md5("1")){
			?>
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="tambahPerencanaan()">Tambah Perencanaan</a>
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editPerencanaan()">Edit Perencanaan</a>
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyPerencanaan()">Remove Perencanaan</a>
			<?php
				}
			?>
			<div style="float: right;">
			<span>Cari :</span>
			<input id="cari" style="line-height:18px;border:1px solid #ccc">
			<a href="#" class="easyui-linkbutton" plain="true" onclick="doSearch()">Search</a>
			</div>
			<hr>
			</table> 
		</form>
	</div>
	<div style="clear:both"></div>
</div>
<div id="dlg" class="easyui-dialog" style="width:700px;height:500px;padding:10px 20px"
closed="true" buttons="#dlg-buttons">
<div class="ftitle">Informasi Perencanaan</div>
<form id="fmm" method="post">
<table cellpadding="5">
<tr>
<td>OPD</td>
<td>:
<input class="easyui-textbox" type="text" name="uuid_skpd" id="uuid_skpd" data-options="required:true" size="35" readonly="true"></input></td>
</tr>
<td>Kegiatan</td>
<td>: <input class="easyui-textbox" type="text" name="nm_kegiatan" id="nm_kegiatan" data-options="required:true" size="35"></input></td>
</tr>
<td>Nama Barang</td>
<td>: <input class="easyui-textbox" type="text" name="nm_barang" id="nm_barang" data-options="required:true" size="35"></input></td>
</tr>
<td>Satuan</td>
<td>: 
<input class="easyui-textbox" style="width:250px;" id="nama_satuan" name="nama_satuan" required="true"/>
</td>
</tr>
<td>Jumlah Satuan</td>
<td>: 
<input class="easyui-textbox" style="width:250px;" id="jumlah_barang" name="jumlah_barang" required="true"/>
</td>
</tr>
<td>Jumlah Sisa Satuan</td>
<td>: 
<input class="easyui-textbox" style="width:250px;" id="jumlah_barang_isi" name="jumlah_barang_isi" required="true" readonly="true"/>
</td>
</tr>
<tr>
<td>Harga Satuan</td>
<td>: <input class="easyui-textbox" type="text" id="harga" name="harga" required="true" readonly="true"></input></td>
</tr>
</table>
</form>
</div>
<div id="dlg-buttons">
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="savePerencanaan()" style="width:90px">Save</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
</div>
</div>

<div id="dlgadd" class="easyui-dialog" style="width:700px;height:500px;padding:10px 20px"
closed="true" buttons="#dlgadd-buttons">
<div class="ftitle">Informasi Tambah Perencanaan</div>
<form id="fmmadd" method="post">
<table cellpadding="5">
<tr>
<td>OPD</td>
<td>:
<input class="easyui-combobox" style="width:225px;" id="id_sub_unit" name="id_sub_unit"  required="true"/>
				<script>
				$('#id_sub_unit').combobox({
					url:'./model/cb_sub2_unit.php',
					valueField:'id',
					textField:'text',
					filter: function(q, row){
						var opts = $(this).combobox('options');
						return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
					},
					onSelect: function(rec){
						$('#id_kegiatan').combobox('clear');
						$('#id_kegiatan').combobox('reload', './model/cb_import_keg1.php?cek&id='+rec.id);
					}
				});
				</script></td>
</tr>
<td>Kegiatan</td>
<td>: <input class="easyui-combobox" style="width:270px;" id="id_kegiatan" name="id_kegiatan" /></input>
				<script>
				$('#id_kegiatan').combobox({
					url:'./model/cb_import_keg1.php',
					valueField:'kd_kegiatan',
					textField:'nm_kegiatan',
					filter: function(q, row){
						var opts = $(this).combobox('options');
						return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
					},
					onSelect: function(rec){
						$('#nama_kegiatan').val( rec.nm_kegiatan);
					}
				});
				</script>
<!-- <input type="hidden" name="nama_kegiatan" id="nama_kegiatan"/></input> -->
<input type="hidden"  style="width:250px;" id="nama_kegiatan" name="nama_kegiatan" required="true" readonly="true"  />
				</td>
</tr>
<td>Nama Barang</td>
<td>: <!-- <input class="easyui-textbox" type="text" name="nm_barang" id="nm_barang" data-options="required:true" size="35"></input> -->
<input class="easyui-combobox" style="width:270px;" id="id_barang" name="id_barang" /></input>
				<script>
				$('#id_barang').combobox({
					url:'./model/cb_barang_perencanaan.php',
					valueField:'id',
					textField:'nama_bar',
					filter: function(q, row){
						var opts = $(this).combobox('options');
						return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
					},
					onSelect: function(rec){
                        $('#nama_barang').val( rec.nama_bar);
						$('#satuan_barang').textbox('setValue', rec.simbol);
                        $('#id_sat_barang').textbox('setValue', rec.id_satuan);
						$('#jenis_barang').textbox('setValue', rec.namajen);
                        $('#id_jenis_barang').textbox('setValue', rec.id_jenis);
					}
				});
				</script>
 <input type="hidden"  style="width:250px;" id="nama_barang" name="nama_barang" required="true" readonly="true"  />   
				</td>
</tr>
<td>Satuan Barang</td>
<td>: 
<input class="easyui-textbox" style="width:50px;" id="id_sat_barang" name="id_sat_barang" required="true" readonly="true"  />
<input class="easyui-textbox" style="width:250px;" id="satuan_barang" name="satuan_barang" required="true" readonly="true"  />
</td>
</tr>
<td>Jenis Barang</td>
<td>: 
<input class="easyui-textbox" style="width:50px;" id="id_jenis_barang" name="id_jenis_barang" required="true"  readonly="true"/>
<input class="easyui-textbox" style="width:250px;" id="jenis_barang" name="jenis_barang" required="true"  readonly="true"/>
</td>
</tr>
<td>Sumber Dana</td>
<td>: 
<input class="easyui-combobox" style="width:270px;" id="id_sumber" name="id_sumber" /></input>
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
</tr>
<td>Jumlah Satuan</td>
<td>: 
<input class="easyui-textbox" style="width:250px;" id="jumlah_sat_barang" name="jumlah_sat_barang" required="true"/>
</td>
</tr>
<tr>
<td>Harga Satuan</td>
<td>: <input class="easyui-textbox" type="text" id="harga_sat" name="harga_sat" required="true" ></input></td>
</tr>
</table>
</form>
</div>
<div id="dlgadd-buttons">
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveaddPerencanaan()" style="width:90px">Save</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlgadd').dialog('close')" style="width:90px">Cancel</a>
</div>
</div>

<script type="text/javascript">
function importDataPersediaan(){
	//alert('asd');
	$('#fm').form('submit',{
		url: './import_tmp.php',
		onSubmit: function(){
			if($(this).form('validate')==true && validasiCombo('fm')==true){
				$.loader.open($dataLoader);
				return true;
			}else{
				return false;
			}		
		},
		success: function(result){
			$('#dgfull').datagrid('reload');
			$.loader.close($dataLoader);
			var result = eval('('+result+')');
			console.log(result);
			$.messager.alert('Sukses',result.pesan );
		}
	});
}
var urlu
function doSearch(){
        $('#dgfull').datagrid('load',{
            cari: $('#cari').val()
        });
}

function tambahPerencanaan(){
	
		$('#dlgadd').dialog('open').dialog('setTitle','Tambah Perencanaan');
		$('#fmmadd').form('clear');
		
	
}

function editPerencanaan(){
	var row = $('#dgfull').datagrid('getSelected');
	//console.log(row);
	if (row){
		$('#dlg').dialog('open').dialog('setTitle','Edit Perencanaan');
		$('#fmm').form('clear');
		$('#fmm').form('load',row);
		urlu = './aksi.php?module=perencanaan&oper=edit&id_ubah='+row.id_rencana;
	}else $.messager.alert('Peringatan','Pilih Data Perencanaan yang akan diubah !');
}

function saveaddPerencanaan(){
	var id_sub_unit = $("#id_sub_unit").combobox("getValue");
	//var nm_skpd = $("#id_sub_unit").combobox("getText");
	var id_kegiatan = $("#id_kegiatan").combobox("getValue");
	var nama_kegiatan = $("#nama_kegiatan").val();
	var id_barang = $("#id_barang").combobox("getValue");
	var nama_barang = $("#nama_barang").val();
	var id_sat_barang = $("#id_sat_barang").val();
	var id_jenis_barang = $("#id_jenis_barang").val();
	var id_sumber = $("#id_sumber").combobox("getValue");
	var jumlah_sat_barang = $("#jumlah_sat_barang").textbox('textbox').val();
	var harga_sat = $("#harga_sat").textbox('textbox').val();
    
    //alert(id_kegiatan);
    //alert(nama_kegiatan);
    if(id_sub_unit == ''|| id_kegiatan == '' || nama_kegiatan == '' || id_barang == '' || nama_barang == '' || id_sat_barang == '' || id_jenis_barang == '' || id_sumber == '' || jumlah_sat_barang == '' || harga_sat == ''){
			$.messager.alert('Peringatan','Mohon isi data Perencanaan terlebih dahulu.');
			//$('#dlg').dialog('close');
			return false;
		}
	$('#fmmadd').form('submit',{
		url: './aksi.php?module=perencanaan&oper=add',
		data: { id_sub_unit: id_sub_unit, id_kegiatan : id_kegiatan, nama_kegiatan : nama_kegiatan, id_barang : id_barang, nama_barang : nama_barang,id_sat_barang : id_sat_barang,id_jenis_barang : id_jenis_barang,id_sumber : id_sumber,jumlah_sat_barang : jumlah_sat_barang,harga_sat : harga_sat },
		onSubmit: function(){
		return $(this).form('validate');
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

			
			$('#dlgadd').dialog('close');
		}
	});
}

function savePerencanaan(){
	$('#fmm').form('submit',{
		url: urlu,
		onSubmit: function(){
		return $(this).form('validate');
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
			
		}
	});
}

function destroyPerencanaan(){
	var rw1 = $('#dgfull').datagrid('getSelected');
	
	if (rw1){
		if(rw1.jumlah_barang < rw1.jumlah_barang_isi || rw1.jumlah_barang_isi !=0 ){
		$.messager.confirm('Peringatan','Jumlah satuan Sudah Terisi di Pengadaan');
		}else{
		$.messager.confirm('Peringatan','Apakah Anda yakin akan menghapus Data ini?',function(r){
		if (r){
			$.ajax({
				type: "POST",
				url: './aksi.php?module=perencanaan&oper=del',
				data: { id_hapus: rw1.id_rencana },
				success: function(data){
					$.messager.show({ title: 'Konfirmasi', msg: data });	
					$('#dgfull').datagrid('reload');			
				}
			});	
			}
		},'json');
		}
	}
	
	else $.messager.alert('Peringatan','Pilih Perencanaan yang akan dihapus dahulu !');	
	
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
