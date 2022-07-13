<div id="hpanel" class="easyui-panel" title="Pengelolaan Hak Akses Aplikasi " 
        style="width:auto;min-height: 600px;padding:10px;background:#fafafa;">

<table id="dg"class="easyui-datagrid" style="width:370px;height:350px"
	url="./model/dd_kelola_hak_akses.php"
	toolbar="#toolbar" pagination="true"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
<th field="nama_akses" width="15">Nama Hak Akses</th>
</tr>
</thead>
</table>
<div id="toolbar">
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newAkses()">New Hak Akses</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editAkses()">Edit Hak Akses</a>
</div>
<div id="dlg" class="easyui-dialog" style="width:830px;height:560px;padding:10px 20px"
closed="true" buttons="#dlg-buttons">
<div class="ftitle">Informasi Hak Akses</div>
<form id="fm" method="post">
	<div class="fitem">
		<label>Nama Hak Akses</label>: 
		<input name="nama_akses" id="nama_akses" class="easyui-textbox" required="true">
		<label></label> 
		<label>Level</label>: 
		<select class="easyui-combobox" style="width:100px;" id="id_role" name="id_role" required="true"/>
		 <option value="1">Admin</option>
		 <option value="10">Admin Bantu</option>
		 <option value="2">Operator</option>
		 
		</select>
	</div></br>
	<div id="p" class="easyui-panel" title="Daftar Menu" style="width:750px;height:360px;padding:10px;">
		<div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west', collapsible:false,border:false" style="width:220px">
				<div class="easyui-layout" data-options="fit:true">
					<div data-options="region:'north',collapsible:false" style="height:160px;padding:5px">				
						<ul id="tm1" class="easyui-tree"
								url="./model/tr_menu.php?id=1"
								checkbox="true">
						</ul>
					</div>
					<div data-options="region:'center'" style="padding:5px">
						<ul id="tm2" class="easyui-tree"
								url="./model/tr_menu.php?id=2"
								checkbox="true">
						</ul>
					</div>
				</div>
              
            </div>
            <div data-options="region:'east', collapsible:false,border:false" style="width:240px;">
                <div class="easyui-layout" data-options="fit:true">
					<div data-options="region:'north',collapsible:false" style="height:100px;padding:5px">				
						<ul id="tm5" class="easyui-tree"
								url="./model/tr_menu.php?id=5"
								checkbox="true">
						</ul>
					</div>
					<div data-options="region:'south',collapsible:false" style="height:100px;padding:5px">				
						<ul id="tm7" class="easyui-tree"
								url="./model/tr_menu.php?id=7"
								checkbox="true">
						</ul>
					</div>
					<div data-options="region:'center'" style="padding:5px">
						<ul id="tm4" class="easyui-tree"
								url="./model/tr_menu.php?id=4"
								checkbox="true">
						</ul>
					</div>
				</div>	
            </div>
            <div data-options="region:'center',border:false" style="padding:0px">
                <div class="easyui-layout" data-options="fit:true">
					<div data-options="region:'north',collapsible:false" style="height:160px;padding:5px">				
						<ul id="tm3" class="easyui-tree"
								url="./model/tr_menu.php?id=3"
								checkbox="true">
						</ul>
					</div>
					<div data-options="region:'center'" style="padding:5px">
						<ul id="tm6" class="easyui-tree"
								url="./model/tr_menu.php?id=6"
								checkbox="true">
						</ul>
					</div>
				</div>	
            </div>
        </div>	
    </div>
</form>
</div>
<div id="dlg-buttons">
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveAkses()" style="width:90px">Save</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
</div>
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
var urlu;
function newAkses(){
	$('#dlg').dialog('open').dialog('setTitle','Tambah Hak Akses');
	$('#fm').form('clear');
	for(e = 1; e <= 7; e++) { 
		var nodes = $('#tm'+e).tree('getChecked', 'checked');
		$.each( nodes, function( i, isi ){
			$('#tm'+e).tree('uncheck', isi.target);
		});
	}
	urlu = './aksi.php?module=kelola_hak_akses&oper=add';
}
function editAkses(){
	var row = $('#dg').datagrid('getSelected');
	if (row){
		$('#dlg').dialog('open').dialog('setTitle','Edit Hak Akses');
		$('#fm').form('clear');
		$('#fm').form('load',row);
		for(e = 1; e <= 7; e++) { 
			var nodes = $('#tm'+e).tree('getChecked', 'checked');
			$.each( nodes, function( i, isi ){
				$('#tm'+e).tree('uncheck', isi.target);
			});
		}	
		$.each( row.akses, function( i, isi ){
			$.each( isi, function( ii, lbl ){
				var node = $('#tm'+i).tree('find', lbl);
				$('#tm'+i).tree('check', node.target);
			});
		});
		
		if(row.id_role=='1'){
			$('#nama_akses').textbox('readonly', true).textbox('textbox').css('background-color','#EEEEEE');
			$('#id_role').combobox('readonly', true).combobox('textbox').css('background-color','#EEEEEE');
		}else{
			$('#nama_akses').textbox('readonly', false).textbox('textbox').css('background-color','#FFFFFF');
			$('#id_role').combobox('readonly', false).combobox('textbox').css('background-color','#FFFFFF');
		}
		urlu = './aksi.php?module=kelola_hak_akses&oper=edit&id_ubah='+row.id;
	}else $.messager.alert('Peringatan','Pilih Data Hak Akses yang akan diubah dahulu !');
}

function saveAkses(){
	var formData = {}; var menu = {};
	
	if($('#fm').form('validate')==false){
		$.messager.show({ title: 'Error', msg: 'Data Hak Akses belum diisi' });
	}else{
		$('#fm').form().find('[name]').each(function() {
			formData[this.name] = this.value;  
		});
		for(e = 1; e <= 7; e++) { 
			var sub = [];
			var nodes = $('#tm'+e).tree('getChecked', ['checked','indeterminate']);
			$.each( nodes, function( i, isi ){
				sub.push(isi.id);
			});
			if(sub.length!==0) menu[e] = sub;
		}
		
		$.ajax({
			type: "POST",
			url: urlu,
			data: { form : formData, menu : menu},
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
					$.messager.show({ title: 'Error', msg: result.pesan });
				} else {
					$.messager.show({ title: 'Sukses', msg: result.pesan }); 
					$('#dg').datagrid('reload');
				}
				$('#dlg').dialog('close');			
			}
		});	
	}
}

</script>
<style type="text/css">
	#fm{
	margin:0;
	padding:10px 10px;
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

