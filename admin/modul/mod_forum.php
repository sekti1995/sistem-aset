<div id="dlg" class="easyui-dialog" style="width:800px;height:350px;padding:5px 10px"
closed="true" buttons="#dlg-buttons" data-options="onClose:function(){$('#dld').dialog('close')}">
	<form id='fm' name='fm' method='POST' enctype='multipart/form-data'>
		<input type='text' name='url' id='url' hidden />
		<table width='100%' style='font-size:14px'> 
			<tr>
				<td width='150px'>SKPD/Unit Kerja</td>
				<td>
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
							uid = rec.uid;
							$('#basket').datagrid('loadData', {"total":0,"rows":[],"footer":[]});
							$('#bas_rinci').datagrid('loadData', {"total":0,"rows":[],"footer":[]});
							$('#dld').dialog('close');
							editIndex = undefined;
							basrinci = {};
						}
					});
					</script>
				</td>
			</tr>
			<tr>
				<td width='120px'>Topik Masalah</td>
				<td><input type='text' name='topik' id='topik' class='easyui-textbox' style='width:100%' /></td>
			</tr>
			<tr>
				<td width='120px'><span id='desk'></span></td>
				<td><input type='text' name='isi' id='isi' class='easyui-textbox' multiline='true' style='width:100%;height:120px' /></td>
			</tr>
			<tr>
				<td>Lampiran</td>
				<td><input type='text' name='inc_files' id='inc_files' class='easyui-filebox' style='width:100%;' />
					<i>Format File : .jpg, .png, .gif, .csv, .xls, .doc, .docx</i></td>
			</tr> 
		</table> 
	</form> 
</div>
<div id="dlg-buttons">
	<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveForum()" style="width:90px">Save</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Batal</a>
</div>
<script type='text/javascript'>
var urlu;
function newPembahasan(){
	$('#dlg').dialog('open').dialog('setTitle','Pembahasan Baru'); 
	$('#isi').textbox('clear');  
	$('#topik').textbox('clear');  
	$('#desk').html('Deskripsi');  
	urlu = './aksi.php?module=forum&oper=add';  
}
$(function() {
    $("a").click(function(event) {
		//var id = $(this).attr('id');
		var data = event.target.id;
		var data = eval('('+data+')');   
       console.log(data[1]);
		$('#dlg').dialog('open').dialog('setTitle','Balas'); 
		$('#isi').textbox('clear');  
		$('#topik').textbox('setValue',data[1]);    
		$('#desk').html('Balasan');   
		$('#id_sub').combobox('setValue','Administrator'); 
		urlu = './aksi.php?module=forum&oper=balas&id_forum='+data[0];  
    });
});

function saveForum(){  
	$('#fm').form('submit',{
		url: urlu,
		onSubmit: function(){
			return $(this).form('validate');
		},
		success: function(result){
			var result = eval('('+result+')');
			if (result.success==false){
					$.messager.show({ title: 'Error', msg: result.pesan });
			} else {
				$.messager.show({ title: 'Sukses', msg: result.pesan });  
			}

			$('#dlg').dialog('close');
			$('#id_sub').combobox('reload'); location.reload();
		}
	});
}
</script>
<div style='padding:10px;margin:auto;background:#eee;border-bottom:solid 1px #bbb'>
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newPembahasan()">Pembahasan Baru</a>
</div>
<div style='height:90%;overflow:auto'>
<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
<div style='margin:auto;width:90%;margin-top:1%;text-align:center'>
<?php
	$q1 = mysql_query("SELECT * FROM forum LEFT JOIN ref_sub2_unit ON skpd = uuid_sub2_unit ORDER BY tgl_entry DESC");
	$items = array();
	while($row = mysql_fetch_assoc($q1)){
		$tggl = tgl_indo(substr($row['tgl_entry'],0,10));
		if($row['file'] == ""){
			$lampiran = " ";
		} else {
			$lampiran = "<hr><a href='lampiran/".$row['file']."' target='_blank'><span style='font-size:10pt'>Unduh Lampiran</span></a> ";
		} 	
		 	
?>

	<div class="alert alert-info" style='text-align:justify' id="<?php echo $row['id_forum']; ?>"> 
		<table width='100%' border='0' cellpadding='0'>
			<tr>
				<td width='80px'><span style='font-size:10pt;'>Oleh</span></td> 
				<td width='25%'><span style='font-size:10pt;'>: <?php echo $row['nm_sub2_unit']; ?></span></td>
				<td rowspan='2'><span style='font-size:16pt;'><?php echo $row['topik']; ?></span></td>
			</tr>
			<tr>
				<td><span style='font-size:10pt;'>Tanggal</span></td> 
				<td><span style='font-size:10pt;'>: <?php echo tgl_indo($row['tgl_entry']); echo " / ".substr($row['tgl_entry'],-8) ?></span></td>
				<td> </td>
			</tr>
		</table>
		<hr>
		<div style='font-size:10pt'><?php echo $row['isi']; ?> </div>
		<?php echo $lampiran; ?>
		
		<?php
		if($_SESSION['peran_id'] == md5('1')){
		?>
		<hr><a href='javascript:void(0)' style='float:right'>
		<span style='font-size:10pt' <?php echo "id="."'".json_encode(array($row['id_forum'],$row['topik']))."'"; ?> >Balas</span>
		</a>
		<?php
		} 
		?>
		<div style='clear:both'></div>
		<hr>
			<table style='font-size:10pt' width='100%'>
				<tr>
					<td width='80px'>Balasan</td>
					<td width='5px'>:</td>
					<td>
					<?php
						$q2 = mysql_query("SELECT * FROM forum_balas WHERE id_forum = '$row[id_forum]' ORDER BY tgl_entry"); 
						while($row2 = mysql_fetch_assoc($q2)){  
							echo $row2['isi'];
							if($row2['file']) echo " <a href='lampiran/".$row['file']."' target='_blank'><span style='font-size:10pt'>Unduh Lampiran</span></a> ";
							echo "<br>";
						}
					?>
					</td>
				</tr>
			</table>
	</div> 
	<?php } ?>
</div> 
</div> 
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
	width:80px;
	}
	.fitem input{
	width:160px;
	}
</style> 