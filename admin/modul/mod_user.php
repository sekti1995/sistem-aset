<div id="hpanel" class="easyui-panel" title="Daftar Pengguna" 
        style="width:auto;padding:10px;background:#fafafa;">
 <table id="dg"class="easyui-datagrid" style="width:870px;height:350px"
	url="./model/dd_user.php"
	toolbar="#toolbar" pagination="true"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
<th field="nama_pengelola" width="15">Nama User</th>
<th field="nm_sub2_unit" width="30">SKPD/Unit Kerja</th>
<th field="alamat" width="40">Alamat</th>
<th field="telpon" width="15">HP</th>
<th field="status" width="10">Status</th>
</tr>
</thead>
</table>
<div id="toolbar">
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newUser()">New User</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editUser()">Edit User</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyUser()">Remove User</a>
<div style="float: right; margin-right: 5px;">
	<a href="#" class="easyui-linkbutton" plain="true" iconCls="icon-search" onclick="javascript:$('#dls').dialog('open')">Pencarian</a>
</div>
</div>
<div id="dlg" class="easyui-dialog" style="width:530px;height:520px;padding:10px 20px"
closed="true" buttons="#dlg-buttons">
<div class="ftitle">Informasi User</div>
<form id="fm" method="post">
	<div class="fitem">
		<label>Nama User</label>: 
		<input name="nama_pengelola" class="easyui-textbox" required="true">
	</div>
	<div class="fitem">
		<label>Hak Akses</label>: 
		<input class="easyui-combobox" style="width:180px;" id="id_akses" name="id_akses" required="true"/>
		<script>
		var id_akses;
		$('#id_akses').combobox({
			url:'./model/cb_akses.php',
			valueField:'id',
			textField:'text',
			filter: function(q, row){
				var opts = $(this).combobox('options');
				return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
			},
			onSelect: function(rec){
				if(rec.id_role==1 || rec.id_role==10){
					$('#id_unit').combobox('clear');
					$('#id_unit').combobox('required', false);
					$('#id_unit').combobox('readonly', true);
					$('#id_sub_unit').combobox('clear');
					$('#id_sub_unit').combobox('required', false);
					$('#id_sub_unit').combobox('readonly', true);
					$('#id_sub2_unit').combobox('clear');
					$('#id_sub2_unit').combobox('required', false);
					$('#id_sub2_unit').combobox('readonly', true);	
				}else {
					$('#id_unit').combobox('clear');
					$('#id_unit').combobox('required', true);
					$('#id_unit').combobox('readonly', false);
					$('#id_sub_unit').combobox('clear');
					$('#id_sub_unit').combobox('required', true);
					$('#id_sub2_unit').combobox('clear');
					$('#id_sub2_unit').combobox('required', true);
					$('#id_unit').combobox('reload','./model/cb_sub2_unit.php?id_role=2');
					if(rec.id==2){
						$('#id_sub_unit').combobox('readonly', true);
						$('#id_sub2_unit').combobox('readonly', true);
					}else if(rec.id==3){
						$('#id_sub_unit').combobox('readonly', false);
						$('#id_sub2_unit').combobox('readonly', true);
					}else{
						$('#id_sub_unit').combobox('readonly', false);
						$('#id_sub2_unit').combobox('readonly', false);
					}
					id_akses = rec.id;
				}
			}
		});
		</script>
	</div>
	<div class="fitem">
		<label>SKPD</label>: 
		<input class="easyui-combobox" style="width:250px;" id="id_unit" name="id_unit" required="true"/>
		<script>
		$('#id_unit').combobox({
			url:'./model/cb_sub2_unit.php',
			valueField:'id',
			textField:'text',
			filter: function(q, row){
				var opts = $(this).combobox('options');
				return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
			},
			onSelect: function(rec){
				console.log(rec)
				//alert(id_akses)
				$('#id_sub_unit').combobox('clear');
				$('#id_sub2_unit').combobox('clear');
				if(id_akses==2){
					$('#id_sub_unit').combobox('reload','./model/cb_sub2_unit.php?id_role='+id_akses);
					$('#id_sub2_unit').combobox('reload','./model/cb_sub2_unit.php');
					$('#id_sub_unit').combobox('setValue', rec.id);
					$('#id_sub2_unit').combobox('setValue', rec.id);
				}else if(id_akses==3){
					$('#id_sub_unit').combobox('reload','./model/cb_sub2_unit.php?kd_unit='+rec.kd_unit+'&id_role='+id_akses);
					$('#id_sub2_unit').combobox('reload','./model/cb_sub2_unit.php');
					$('#id_sub_unit').combobox('setValue', rec.id);
					$('#id_sub2_unit').combobox('setValue', rec.id);
				}else{
					$('#id_sub_unit').combobox('reload','./model/cb_sub2_unit.php?id_role='+id_akses+'&peserta='+rec.id);
					
				}
			} 
		});
		</script>
	</div>
	<div class="fitem">
		<label>Sub Unit</label>: 
		<input class="easyui-combobox" style="width:280px;" id="id_sub_unit" name="id_sub_unit" required="true"/>
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
				$('#id_sub2_unit').combobox('clear');
				$('#id_sub2_unit').combobox('reload','./model/cb_sub2_unit.php?id_role=4&peserta2='+rec.id);
				$('#id_sub2_unit').combobox('setValue', rec.id);
			}
		});
		</script>
	</div>
	<div class="fitem">
		<label>Sub2 Unit</label>: 
		<input class="easyui-combobox" style="width:280px;" id="id_sub2_unit" name="id_sub2_unit" required="true"/>
		<script>
		$('#id_sub2_unit').combobox({
			url:'./model/cb_sub2_unit.php',
			valueField:'id',
			textField:'text',
			filter: function(q, row){
				var opts = $(this).combobox('options');
				return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
			}
		});
		</script>
	</div>
	<!--<div class="fitem">
		<label>Nama Jabatan</label>: 
		<input id="id_jabatan" name="id_jabatan" class="easyui-textbox" required="true" style="width:220px;">
		<script>
	$('#id_jabatan').combobox({
		url:'./model/cb_jabatan.php',
		valueField:'id',
		textField:'text',
		filter: function(q, row){
			var opts = $(this).combobox('options');
			return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
		}
	});
	</script>
	</div>

	<div class="fitem">
		<label>Nama Golongan</label>: 
		<input id="id_golongan" name="id_golongan" class="easyui-textbox" required="true">
		<script>
	$('#id_golongan').combobox({
		url:'./model/cb_golongan.php',
		valueField:'id',
		textField:'text',
		filter: function(q, row){
			var opts = $(this).combobox('options');
			return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
		}
	});
	</script>
	</div>-->
		<div class="fitem">
		<label>TA</label>: 
		<input id="ta" name="ta" class="easyui-textbox" required="true">
		<script>
	$('#ta').combobox({
		url:'./model/cb_tahun.php',
		valueField:'id',
		textField:'text',
		filter: function(q, row){
			var opts = $(this).combobox('options');
			return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
		}
	});
	</script>
	</div>
	<div class="fitem">
		<label>NIP</label>: 
		<input name="nip" class="easyui-textbox" required="true">
	</div>
	<div class="fitem">
		<label>Username</label>: 
		<input name="username" class="easyui-textbox" required="true">
	</div>
	<div class="fitem">
		<label>Password</label>: 
		<input type="password" name="password" id="password" class="easyui-textbox" required="true">
		<input type="hidden" name="password_lama" id="password_lama">
	</div>
	<div class="fitem">
		<label>Alamat</label>: 
		<input name="alamat" class="easyui-textbox" required="true" style="width:280px">
	</div>
	<div class="fitem">
		<label>Telpon</label>: 
		<input name="telpon" class="easyui-textbox" required="true">
	</div>
	<div class="fitem">
		<label>Email</label>: 
		<input name="email" class="easyui-textbox" required="true">
	</div>
	<div class="fitem">
		<label>Status</label>: 
		<input class="easyui-switchbutton" id="state" name="state">
	</div>
	<input type="hidden" id="uid" name="uid"/>
</form>
</div>
<div id="dlg-buttons">
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveUser()" style="width:90px">Save</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
</div>
</div>



<div id="dls" class="easyui-dialog" style="width:450px;height:240px;padding:10px 20px"
closed="true" buttons="#dls-buttons" title="Pencarian Data Pengguna">
<div class="ftitle">Pencarian Data Pengguna</div>
<form id="fms" method="post">
<table cellpadding="5">
<tr>
	<td>Nama User</td>
	<td>: <input class="easyui-textbox" type="text" name="nama_search" id="nama_search" size="25"></input></td>
</tr>
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

function doSearch(){
	$('#dg').datagrid('load',{
		id_sub: $('#id_unit_search').combobox('getValue'),
		nama: $('#nama_search').val()
	});
}

var urlu;
function newUser(){
	$('#dlg').dialog('open').dialog('setTitle','Tambah User');
	$('#fm').form('clear');
	urlu = './aksi.php?module=user&oper=add';
	$('#state').switchbutton('check');
}
function editUser(){
	var row = $('#dg').datagrid('getSelected');
	if (row){
		$('#dlg').dialog('open').dialog('setTitle','Edit User');
		//alert(row.id_pengelola);
		$('#fm').form('clear');
		setLevel(row.id_akses, row.id_role)
		$('#fm').form('load',row);
		urlu = './aksi.php?module=user&oper=edit&id_ubah='+row.id;
		if(row.state==0) var c = 'check'; else var c = 'uncheck';
		$('#state').switchbutton(c);
	}
}

function setLevel(id, id_role){
	if(id==1){
		$('#id_unit').combobox('clear');
		$('#id_unit').combobox('required', false);
		$('#id_unit').combobox('readonly', true);
		$('#id_sub_unit').combobox('clear');
		$('#id_sub_unit').combobox('required', false);
		$('#id_sub_unit').combobox('readonly', true);
		$('#id_sub2_unit').combobox('clear');
		$('#id_sub2_unit').combobox('required', false);
		$('#id_sub2_unit').combobox('readonly', true);	
	}else {
		$('#id_unit').combobox('clear');
		$('#id_unit').combobox('required', true);
		$('#id_unit').combobox('readonly', false);
		$('#id_sub_unit').combobox('clear');
		$('#id_sub_unit').combobox('required', true);
		$('#id_sub2_unit').combobox('clear');
		$('#id_sub2_unit').combobox('required', true);
		
		if(id_role==2){
			$('#id_sub_unit').combobox('readonly', true);
			$('#id_sub2_unit').combobox('readonly', true);
			$('#id_unit').combobox('reload','./model/cb_sub2_unit.php?id_role='+id_role);
		}else if(id_role==3){
			$('#id_sub_unit').combobox('readonly', false);
			$('#id_sub2_unit').combobox('readonly', true);
			$('#id_unit').combobox('reload','./model/cb_sub2_unit.php?id_role='+id_role);
		}else{
			$('#id_sub_unit').combobox('readonly', false);
			$('#id_sub2_unit').combobox('readonly', false);
		}
		id_akses = id_role;
	}
}

function saveUser(){
	var pass = $('#password').val();
	var pass_lama = $('#password_lama').val();
	if(pass!="" && pass!=pass_lama) $('#password').textbox('setValue', $.MD5($('#password').val()));
	
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
					$('#Kd_Sub').focus();	
					return;
				}else $.messager.show({ title: 'Error', msg: result.pesan });
			} else {
				$.messager.show({ title: 'Sukses', msg: result.pesan }); 
				$('#dg').datagrid('reload');
			}

			$('#dlg').dialog('close');
		}
	});
}
function destroyUser(){
	var rw1 = $('#dg').datagrid('getSelected');
	if (rw1){
		$.messager.confirm('Peringatan','Apakah Anda yakin akan menghapus User ini?',function(r){
		if (r){
			$.ajax({
				type: "POST",
				url: './aksi.php?module=user&oper=del',
				data: { id_hapus: rw1.id },
				success: function(data){
					$.messager.show({ title: 'Konfirmasi', msg: data });	
					$('#dg').datagrid('reload');			
				}
			});	
			}
		},'json');
	}else $.messager.alert('Peringatan','Pilih User yang akan dihapus dahulu !');	
}

$(function(){
	$('#state').switchbutton({
		onText : 'Aktif',
		offText : 'Non-Aktif',
		width : 90
	});
})
</script>
<style type="text/css">
	#fm{
	margin:0;
	padding:10px 30px;
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
	width:100px;
	}
	.fitem input{
	width:160px;
	}
</style>	