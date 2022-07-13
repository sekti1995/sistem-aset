 <div class="dtabel" style="width:100%;height:100%;background:white">	
 <table id="dgfull"class="easyui-datagrid"
	url="./model/normalisasi.php" fit="true" showFooter="true"
	toolbar="#toolbar" title="NORMALISASI DATA"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
<th field="id_stok" width="120" align="center">ID STOK</th>
<th field="nama_barang" width="100" align="left">BARANG</th>
<th field="kode_stok" width="30" align="center">KODE</th>
<th field="id_sumber_dana" width="30" align="center">ID SD</th>
<th field="id_gudang" width="30" align="center">ID GUDANG</th>
<th field="nama_sumber" width="70" align="center">SUMBER DANA</th>
<th field="tgl_transaksi" width="40" align="center">TGL TRANS</th>
<th field="ta_stok" width="30" align="center">TA</th>
<th field="jml_in" width="40" align="center" halign="center">IN</th>
<th field="jml_out" width="40" align="center" halign="center">OUT</th>
<th field="akumul" width="40" align="center" halign="center">TTL OUT</th>
<th field="harga" width="70" halign="center" align="right">HARGA</th>
<th field="cd" width="70" halign="center" align="right">CREATE</th>
<th field="ud" width="70" halign="center" align="right">UPDATE</th>
</tr>
</thead>
</table>
</div>
<div id="toolbar"> 
	<div  >
		<table cellpadding="5" width='100%'>
		<?php if($_SESSION['level']!=md5('c')){ ?>
		<tr>
		<td>Nama OPD : 
		<input class="easyui-combobox" style="width:160px;" id="id_sub" name="id_sub"  required="true"/>
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
		<?php } ?>
			 Barang : <input class="easyui-combobox" style="width:200px;" id="id_bar" name="id_bar" />
				<script>
				$('#id_bar').combobox({
					url:'./model/cb_barang.php',
					valueField:'id_bar',
					textField:'nama_bar',
					filter: function(q, row){
						var opts = $(this).combobox('options');
						return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
					}
				});
				</script>
				
			 Harga : <input type='text' name='harga_cari' id='harga_cari' class='easyui-textbox' style="width:100px;" />
			 Sumber Dana : <input class="easyui-combobox" style="width:180px;" id="id_sumberd" name="id_sumberd" />
				<script>
				$('#id_sumberd').combobox({
					url:'./model/cb_sumber_dana.php',
					valueField:'id',
					textField:'text',
					filter: function(q, row){
						var opts = $(this).combobox('options');
						return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
					}
				});
				</script>
			 Kode : <input type='text' name='kode_cari' id='kode_cari' class='easyui-textbox' style="width:40px;" />
			 SD : <input type='text' name='sd' id='sd' class='easyui-textbox' style="width:40px;" />
				
				<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="cari()" style="width:90px" id="btnProses">Cari</a>
			</td> 
			<td>
				<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-edit" onclick="edit()" style="width:90px;float:right" id="btnProses">Edit</a>
			</td>
		</tr>	
		</table> 
	</div>
	<div style="clear:both"></div>
</div>
 

<div id="dls" class="easyui-dialog" style="width:500px;height:430px;padding:10px 20px"
closed="true" buttons="#dls-buttons" title="Normalisasi Data">
<div class="ftitle">Normalisasi Data</div>
<form id="fm" method="post">
<input type='hidden' name='kode_stok' id='kode_stok' />
<input type='hidden' name='id_transaksi' id='id_transaksi' />
<input type='hidden' name='id_transaksi_detail' id='id_transaksi_detail' />
<table cellpadding='4'>
	<tr>
	<td>SUMBER DANA </td><td>: <input class="easyui-combobox" style="width:270px;" id="id_sumber_dana" name="id_sumber_dana" required="true"/>
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
	</tr>
	<tr>
	<td>TA </td>
	<td>
	: <input type='text' name='ta_stok' id='ta_stok' class='easyui-textbox' style="width:50px;" />
	&nbsp; KODE : <input type='text' name='kode_stok' id='kode_stok' class='easyui-textbox' style="width:50px;" readonly />
	</td>
	</tr>
	<tr>
	<td>JML IN </td><td>: <input type='text' name='jml_in' id='jml_in' class='easyui-textbox' style="width:270px;" /></td>
	</tr>
	<tr>
	<td>JML OUT </td><td>: <input type='text' name='jml_out' id='jml_out' class='easyui-textbox' style="width:270px;" /></td>
	</tr>
	<tr>
	<td>HARGA </td><td>: <input type='text' name='harga' id='harga' class='easyui-textbox' style="width:270px;" /></td>
	</tr>
	<tr>
	<td>TGL TRANSAKSI </td><td>: <input type='text' name='tgl_transaksi' id='tgl_transaksi' class='easyui-datebox' style="width:100px;" data-options="formatter:myformatter,parser:myparser,required:true" /></td>
	</tr>
	<tr>
	<td>ID STOK </td><td>: <input type='text' name='id_stok' id='id_stok' class='easyui-textbox' style="width:270px;" /></td>
	</tr>
	<tr>
	<td>SOFT DELETE </td><td>: <input type='text' name='soft_delete' id='soft_delete' class='easyui-textbox' style="width:270px;" /></td>
	</tr>
	<tr>
	<td>KETERANGAN </td><td>: <b>a</b> (Saldo Awal), &nbsp; <b>i</b> (masuk), &nbsp; <b>ok</b> (keluar) </td>
	</tr>
</table>
</form>	
</div>
<div id="dls-buttons">
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="simpan()" style="width:90px">SIMPAN</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dls').dialog('close')" style="width:90px">Batal</a>
</div>


<script type="text/javascript">
function edit(){
	var row = $('#dgfull').datagrid('getSelected');
	if (row){
		$('#dls').dialog('open').dialog('setTitle','Normalisasi Data');
		$('#fm').form('clear');
		$('#fm').form('load',row);
		//alert('Tes');
		console.log(row)
		urlu = './aksi.php?module=normalisasi&oper=edit&id_ubah='+row.id_stok;
	}
}
function simpan(){
	$('#fm').form('submit',{
		url: urlu,
		onSubmit: function(){
		return $(this).form('validate');
		},
		success: function(result){
			console.log(result);
			var result = eval('('+result+')');
			if (result.success==false){
				if(result.error=='nomor_sama'){ 
					$.messager.show({ title: 'Error', msg: result.pesan });
					$('#tahun').focus();	
					return;
				}else $.messager.show({ title: 'Error', msg: result.pesan });
			} else {
				$.messager.show({ title: 'Sukses', msg: result.pesan }); 
				$('#dgfull').datagrid('reload');
			}

			$('#dls').dialog('close');
		}
	});
}

function cari(){
	var id_sub = $('#id_sub').combobox('getValue');
	var id_bar = $('#id_bar').combobox('getValue');
	var id_sumberd = $('#id_sumberd').combobox('getValue');
	var harga_cari = $('#harga_cari').textbox('getValue');
	var kode_cari = $('#kode_cari').textbox('getValue');
	var sd = $('#sd').textbox('getValue');
	$('#dgfull').datagrid('load',{
		id_sumberd: id_sumberd,
		harga_cari: harga_cari,
		kode_cari: kode_cari,
		id_sub: id_sub,
		sd: sd,
		id_bar : id_bar
	});	 
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
