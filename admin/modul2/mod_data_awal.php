<div id="hpanel" class="easyui-panel" title="Data Awal Persediaan" 
        style="width:auto;padding:10px;background:#fafafa;">
 <table id="dg" class="easyui-datagrid" style="width:720px;height:450px"
	toolbar="#toolbar" showFooter="true"
	rownumbers="true" fitColumns="true" singleSelect="true">
<thead>
<tr>
	<th field="kode" width=15 halign="center" align="left" data-options="editor: {type:'textbox', options:{ readonly:'true'}}">Kode Barang</th>
    <th data-options="field:'nama_bar',width:30, align:'left', halign:'center',
			formatter:function(value,row){
				return row.nama_bar;
			}">Nama Barang</th>
    <th field="satuan" width=10 halign="center" align="left"  data-options="editor: {type:'textbox', options:{ readonly:'true'}}">Satuan</th>
    <th field="saldo" width=13 align="center" data-options="editor: {type:'textbox', options:{ required:'true'}}">Saldo Awal</th>
    <th field="harga" width=20  halign="center" align="right" data-options="editor: {type:'textbox', options:{ required:'true'}}">Harga</th>
    <th field="total" width=20  halign="center" align="right" data-options="editor: {type:'textbox', options:{ required:'true'}}">Total</th>
    <th field="id_bar" width=80 align="left" halign="center" editor="textbox" hidden>id_bar</th>
    <th field="id_sat" width=80 align="left" halign="center" editor="textbox" hidden>id_sat</th>
</tr>
</thead>
</table>
<div id="toolbar">
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-file" plain="true" onclick="javascript:$('#dlk').dialog('open')">Template Data Awal</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-upload-file" plain="true" onclick="upDataAwal()">Upload Data Awal</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="saveDataAwal()">Simpan Data Awal</a>
	<hr>
	<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="append()">Tambah</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="removeit()">Hapus</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="accept()">Setuju</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-undo',plain:true" onclick="reject()">Batal</a>
</div>

<div id="dlg" class="easyui-dialog" style="width:610px;height:350px;padding:10px 20px"
closed="true" buttons="#dlg-buttons">
<div class="ftitle">Data Awal Persediaan</div>
<?php if($_SESSION['level']==md5('c')){ ?>
<input type="hidden" value="<?php echo $_SESSION['uidunit']; ?>" name="id_sub" id="id_sub">
<?php } ?>
<form id="fm" method="post" enctype="multipart/form-data">
	<?php if($_SESSION['level']!=md5('c')){ ?> 
	<div class="fitem">
	<label>SKPD/Unit Kerja</label>: 
	<input class="easyui-combobox" style="width:300px;" id="id_sub" name="id_sub" required="true"/>
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
			$('#id_gudang').combobox('clear');
			$('#id_gudang').combobox('reload', './model/cb_gudang.php?id='+rec.id);
			$('#id_sumber').combobox('clear');
			$('#id_sumber').combobox('reload', './model/cb_sumber_dana.php?id='+rec.id);
		}
	});
	</script>
	</div>
	<?php } ?>
	<div class="fitem">
	<label>Tanggal Data Awal</label>: 
	<input class="easyui-datebox" type="text" name="tgl_awal" id="tgl_awal" data-options="formatter:myformatter,parser:myparser,required:true" style="width:100px;" validType="validDate">
	</div>
	<div class="fitem">
	<label>Tanggal BA</label>: 
	<input class="easyui-datebox" type="text" name="tgl_ba" id="tgl_ba" data-options="formatter:myformatter,parser:myparser,required:true," style="width:100px;" validType="validDate">
	&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
	<label>Nomor BA</label>: 
	<input class="easyui-textbox" type="text" name="no_ba" id="no_ba" data-options="required:true" style="width:100px;">
	</div>
	<div class="fitem">
	<label>Tempat Penyimpanan</label>: 
	<input class="easyui-combobox" style="width:150px;" id="id_gudang" name="id_gudang" required="true"/>
	<script>
	$('#id_gudang').combobox({
		url:'./model/cb_gudang.php',
		valueField:'id_gud',
		textField:'nama_gud',
		filter: function(q, row){
			var opts = $(this).combobox('options');
			return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
		}
	});
	</script>
	</div>
	<div class="fitem">
	<label>Kelompok</label>: 
	<input class="easyui-combobox" style="width:200px;" id="id_kelompok" name="id_kelompok" required="true"/>
	<script>
	$('#id_kelompok').combobox({
		url:'./model/cb_kelompok.php',
		valueField:'id_kel',
		textField:'nama_kel',
		filter: function(q, row){
			var opts = $(this).combobox('options');
			return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
		}
	});
	</script>
	</div>
	<div class="fitem">
	<label>Sumber Dana</label>: 
	<input class="easyui-combobox" style="width:200px;" id="id_sumber" name="id_sumber" required="true"/>
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
	</div>
	<div class="fitem" id="divfile">
	<label>File Data Awal</label>: 
	<input class="easyui-filebox" type="text" name="file_awal" id="file_awal" style="width:260px;"></input>
	</div>
</table>
</form>
</div>
<div id="dlg-buttons">
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="cekDataAwal()" id="saveCek" style="width:90px">Save</a>
<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="formData()" id="saveForm" style="width:90px">Save</a>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
</div>

<div id="dlk" class="easyui-dialog" style="width:310px;height:140px;padding:10px 20px"
closed="true" buttons="#dlk-buttons" title="Template">
Pilih Jenis Template yang sesuai dengan komputer anda !

</div>
<div id="dlk-buttons" style="text-align:center">
<a href="javascript:void(0)" class="easyui-linkbutton" onclick="unduh('lama')" style="width:100px">Ms.Excel (EN)</a>
<a href="javascript:void(0)" class="easyui-linkbutton" onclick="unduh('baru')" style="width:100px">Ms.Excel (ID)</a>
</div>
</div>
<script type="text/javascript">
var urlu;
$(function(){
	$('#file_awal').filebox({ accept: '.csv', required: true });

	$('#dg').datagrid({
		singleSelect:true,
		showFooter:true,
		onClickCell: onClickCell,
		onEndEdit: onEndEdit,
		onBeginEdit: onBeginEdit,
		onBeforeEdit: onBeforeEdit
	});	
});

function unduh(v){
	$.loader.open($dataLoader);
	$.post( "./print/data_awal.php", { versi: v })
	.done(function( data ) {
		window.location.href = data.url;
		$('#dlk').dialog('close');
		$.loader.close($dataLoader);
	});
}

function upDataAwal(){
	$('#dlg').dialog('open').dialog('setTitle','Upload Data');
	$('#divfile').attr('hidden', false);
	$('#saveCek').show()
	$('#saveForm').hide();
	$('#fm').form('clear');
	urlu = './aksi.php?module=data_awal&oper=up';
}

function formDataAwal(){
	$('#dlg').dialog('open').dialog('setTitle','Form Data Awal');
	$('#divfile').attr('hidden', true);
	$('#saveCek').hide();
	$('#saveForm').show();
	$('#fm').form('clear');
}

var tgl_awal, id_gudang, id_sub, id_kel, tgl_ba, no_ba, id_sumber;
function cekDataAwal(){
	$('#fm').form('submit',{
		url: urlu,
		onSubmit: function(){
			if($(this).form('validate')==true && validasiCombo('fm')==true){
				$.loader.open($dataLoader);
				return true;
			}else{
				return false;
			}		
		},
		success: function(result){
			var result = eval('('+result+')');
			if (result.success==false){
				if(result.error=='nomor_sama'){ 
					$.messager.show({ title: 'Error', msg: result.pesan });	
					return;
				}else $.messager.show({ title: 'Error', msg: result.pesan });
			} else {
				$.messager.alert('Sukses',result.pesan );
				$('#dg').datagrid({ data : result.data.rows });
				$('#dg').datagrid( 'reloadFooter', [{ harga:'Total', total:result.data.total}] );
				tgl_awal = $('#tgl_awal').datebox('getValue');
				tgl_ba = $('#tgl_ba').datebox('getValue');
				no_ba = $('#no_ba').textbox('getValue');
				id_gudang = $('#id_gudang').combobox('getValue');
				id_kel = $('#id_kelompok').combobox('getValue');
				id_sumber = $('#id_sumber').combobox('getValue');
				<?php if($_SESSION['level']!=md5('c')){ ?>  id_sub = $('#id_sub').combobox('getValue'); 
				<?php }else{ ?>  id_sub = $('#id_sub').val(); <?php } ?>
			}
			$('#dlg').dialog('close');
			$.loader.close($dataLoader);
		}
	});
}

function formData(){
	if(validasiCombo('fm')==true){
		tgl_awal = $('#tgl_awal').datebox('getValue');
		tgl_ba = $('#tgl_ba').datebox('getValue');
		no_ba = $('#no_ba').textbox('getValue');
		id_gudang = $('#id_gudang').combobox('getValue');
		id_kel = $('#id_kelompok').combobox('getValue');
		id_sumber = $('#id_sumber').combobox('getValue');
		<?php if($_SESSION['level']!=md5('c')){ ?>  id_sub = $('#id_sub').combobox('getValue'); 
		<?php }else{ ?>  id_sub = $('#id_sub').val(); <?php } ?>
		$('#dlg').dialog('close');
		append();
	}	
}

function saveDataAwal(){
	var dg = $('#dg').datagrid('getData');
	if(dg.total==0){
		$.messager.show({ title: 'Error', msg: 'Data Barang belum diisi' }); 
	}else if(tgl_awal==undefined){
		$.messager.show({ title: 'Error', msg: 'Tanggal belum diisi' }); 
	}else if(editIndex!=undefined){
		$.messager.show({ title: 'Error', msg: 'Setujui dulu perubahan data barang!' }); 
	}else{		
		$.ajax({
			type: "POST",
			url: './aksi.php?module=data_awal&oper=save',
			data: { basket: dg.rows, tgl_awal : tgl_awal, id_gudang, id_gudang, id_kelompok : id_kel, uid_skpd : id_sub,
					tgl_ba: tgl_ba, no_ba: no_ba, id_sumber : id_sumber },
			beforeSend: function() {
				$.loader.open($dataLoader);
			},
			complete: function(){
				$.loader.close($dataLoader);
			},
			success: function(data){
				console.log(data);
				var data = eval('('+data+')');
				if (data.success==false){
					if(data.error=='nomor_sama'){ 
						$.messager.show({ title: 'Error', msg: data.pesan });	
						return;
					}else $.messager.show({ title: 'Error', msg: data.pesan });
				} else {
					//$.messager.alert('Sukses',data.pesan );
					$.messager.show({ title: 'Sukses', msg: data.pesan });	
					$('#dg').datagrid('loadData', {"total":0,"rows":[],"footer":[]});
					editIndex = tgl_awal = id_gudang = id_sub = id_kel = tgl_ba = no_ba = id_sumber = undefined;
				}
			}
		});	
	}
}

		var editIndex = undefined;
        function endEditing(){
            if (editIndex == undefined){return true}
            if ($('#dg').datagrid('validateRow', editIndex)){
				var ed = $('#dg').datagrid('getEditors', editIndex); // get the editor
				var barang = $(ed[6].target).val();
				
				var sama = "";
				var basket = $('#dg').datagrid('getData');
				$.each(basket.rows, function(i,lab){
					if(lab['id_bar']==barang && i!=editIndex) sama = 'ya';
				});
				
				if(validasiCombo2(ed)==false) return false;	
				//if(sama=='ya'){
					/* 
					$.messager.show({ title: 'Error', msg: "Barang Sudah ada dalam daftar!" }); 
					return false;
					 */
				//}else{
					$('#dg').datagrid('endEdit', editIndex);
					editIndex = undefined;
					return true;
				//}
            } else {
                return false;
            }
        }
        function onClickCell(index, field){
            if (editIndex != index){
                if (endEditing()){
                    $('#dg').datagrid('selectRow', index)
                            .datagrid('beginEdit', index);
                    var ed = $('#dg').datagrid('getEditor', {index:index,field:field});
                    if (ed){
                        ($(ed.target).data('textbox') ? $(ed.target).textbox('textbox') : $(ed.target)).focus();
                    }
                    editIndex = index;
                } else {
                    setTimeout(function(){
                        $('#dg').datagrid('selectRow', editIndex);
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
			var editors = $('#dg').datagrid('getEditors', rowIndex);
			var kode = $(editors[0].target);
			var barang = $(editors[1].target);
			var satuan = $(editors[2].target);
			var saldo = $(editors[3].target);
			var harga = $(editors[4].target);
			var total = $(editors[5].target);
			var fbar = $(editors[6].target);
			
			/* saldo.textbox('textbox').bind('keyup',function(e){
				var $this = $(this);
				var num = $this.val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
				$this.val(num);
			}); */
			/* harga.textbox('textbox').bind('keyup',function(e){
				var $this = $(this);
				var num = $this.val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
				$this.val(num);
			}); */
			/* total.textbox('textbox').bind('keyup',function(e){
				var $this = $(this);
				var num = $this.val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
				$this.val(num);
			}); */
			
			/* saldo.textbox('textbox').bind('keyup',function(e){
				var $this = $(this);
				var jum = $this.val().replace(/\D/g, "");
				var sat = harga.textbox('textbox').val().replace(/\D/g, "");
				var tot = sat*jum;
				$this.val(jum.replace(/\B(?=(\d{3})+(?!\d))/g, "."));
				total.textbox('setValue', accounting.formatMoney(tot, '', 0, '.', ','));
			}); */
			
			saldo.textbox('textbox').bind('keyup',function(e){
				var $this = $(this);
				var jum = $this.val();
				//var sat = hargasat.textbox('textbox').val().replace(/\D/g, "");
				//var tot = sat*jum;
				$this.val(jum);
				
				
				var x = harga.textbox('getValue');
				x2 = x.replace(".","");
				x3 = x2.replace(".","");
				x4 = x3.replace(".","");
				x5 = x4.replace(".","");
				var valdes = toRpDec(x5);
					 
				//$this.val(valdes); 
				var sat = x5;
				x6 = x5.replace(",",".");
				sat2 = parseFloat(x6);
				var tot = sat2*jum;
				tot = "'"+tot;
				var s_tot = tot.replace(".",",");
				var s_tot2 = s_tot.replace("'",""); 
				var tulis_tot = toRpDec(s_tot2);
				console.log(tulis_tot);
				//if(tulis_tot>=0){
					total.textbox('setValue',tulis_tot);  
				//} else {
					//total.textbox('setValue', 0);  
				//}
			});
			
			harga.textbox('textbox').bind('keyup',function(e){
				var $this = $(this);
				var x = $this.val();
				x2 = x.replace(".","");
				x3 = x2.replace(".","");
				x4 = x3.replace(".","");
				x5 = x4.replace(".","");
				var valdes = toRpDec(x5);
					 
						$this.val(valdes);
						console.log(valdes)
						var jum = saldo.textbox('textbox').val();
						j2 = jum.replace(".","");
						j3 = j2.replace(".","");
						j4 = j3.replace(".","");
						j5 = j4.replace(".","");
						j6 = j5.replace(",",".");
						j7 = parseFloat(j6);
						
						var sat = x5;
						x6 = x5.replace(",",".");
						sat2 = parseFloat(x6);
						var tot = sat2*j7;
						tot = "'"+tot;
						var s_tot = tot.replace(".",",");
						var s_tot2 = s_tot.replace("'",""); 
						var tulis_tot = toRpDec(s_tot2);
						total.textbox('setValue',tulis_tot); 
			});
			/* harga.textbox('textbox').bind('keyup',function(e){
				var $this = $(this);
				var sat = $this.val().replace(/\D/g, "");
				var jum = saldo.textbox('textbox').val().replace(/\D/g, "");
				var tot = sat*jum;
				$this.val(sat.replace(/\B(?=(\d{3})+(?!\d))/g, "."));
				total.textbox('setValue', accounting.formatMoney(tot, '', 0, '.', ','));
			}); */
			/* total.textbox('textbox').bind('keyup',function(e){
				var $this = $(this);
				var tot = $this.val().replace(/\D/g, "");
				var jum = saldo.textbox('getValue').replace(/\D/g, "");
				var sat = tot/jum;
				$this.val(tot.replace(/\B(?=(\d{3})+(?!\d))/g, "."));
				harga.textbox('setValue', accounting.formatMoney(sat, '', 0, '.', ','));
			}); */
			total.textbox('textbox').bind('keyup',function(e){
				var $this = $(this);
				//var tot = $this.val().replace(/\D/g, ""); 
						var jum = saldo.textbox('textbox').val();
						j2 = jum.replace(".","");
						j3 = j2.replace(".","");
						j4 = j3.replace(".","");
						j5 = j4.replace(".","");
						j6 = j5.replace(",",".");
						j7 = parseFloat(j6);
				//var sat = tot/jum;
				//$this.val(tot.replace(/\B(?=(\d{3})+(?!\d))/g, "."));
				
				
				var x = $this.val();
				x2 = x.replace(".","");
				x3 = x2.replace(".","");
				x4 = x3.replace(".","");
				x5 = x4.replace(".","");
				var valdes = toRpDec(x5);
					 
				$this.val(valdes); 
				var sat = x5;
				x6 = x5.replace(",",".");
				sat2 = parseFloat(x6);
				var tot = sat2/j7;
				tot = "'"+tot;
				var s_tot = tot.replace(".",",");
				var s_tot2 = s_tot.replace("'",""); 
				var tulis_tot = toRpDec(s_tot2);
				harga.textbox('setValue',tulis_tot);  
			});
			
			var a = fbar.textbox('getValue');
			
			barang.combobox({
				onSelect: function(rec){
					//jmstok.textbox('setValue', rec.jml);
					kode.textbox('setValue', rec.kode);
					satuan.textbox('setValue', rec.simbol);
					fbar.textbox('setValue', rec.id_bar);
				}
			}).combobox('setValue',a);
			kode.textbox('textbox').css('background-color','#EEEEEE');
			satuan.textbox('textbox').css('background-color','#EEEEEE');
		}
		
		function onBeforeEdit(row){
			var combar = $(this).datagrid('getColumnOption','nama_bar');
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
		
        function onEndEdit(index, row){
            var ed = $(this).datagrid('getEditor', {
                index: index,
                field: 'nama_bar'
            });
			
            row.nama_bar = $(ed.target).combobox('getText');
        }
        function append(){
            if(id_sub==undefined){
				formDataAwal();
			}else{
				if (endEditing()){
					$('#dg').datagrid('appendRow',{status:'P'});
					editIndex = $('#dg').datagrid('getRows').length-1;
					$('#dg').datagrid('selectRow', editIndex)
							.datagrid('beginEdit', editIndex);
				}
            }
        }
        function removeit(){
            if (editIndex == undefined){return}
            $('#dg').datagrid('cancelEdit', editIndex)
                    .datagrid('deleteRow', editIndex);
            editIndex = undefined;
        }
        function accept(){
			var rows = $('#dg').datagrid('getChanges');
            if (endEditing()){
                $('#dg').datagrid('acceptChanges');
            }
        }
        function reject(){
            $('#dg').datagrid('rejectChanges');
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
	width:120px;
	}
	.fitem input{
	width:160px;
	}
</style>