<fieldset id='logo'>
<legend>
		Laporan Penjualan <label id="lb_legend"></label>
	</legend>
	<form id="fmj" method="post">
		<table width='80%'class='gridtable'>
		<tr>
		<td>Nota Penjualan</td>
		<td><input id='nota_jual' class='easyui-textbox' type="text" name='nota_jual'></input>
		</td>
		<td>Nama Barang</td>
		<td>
		<input id='barang' class='easyui-textbox' type="text" name='barang'></input>
		<!--<input class="easyui-combobox" id="barang" name="barang" size="20px" />
		<script>
		$('#barang').combobox({
			url:'./model/cb_produk.php',
			valueField:'id',
			textField:'text',
			filter: function(q, row){
				var opts = $(this).combobox('options');
				return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
			} 
		});
		</script>-->
		</td>
		<td>Kategori</td>
		<td><input class="easyui-combobox" id="kategori" name="kategori" size="20px" />
		<script>
		$('#kategori').combobox({
			url:'./model/cb_kategori_produk.php',
			valueField:'id',
			textField:'text',
			filter: function(q, row){
				var opts = $(this).combobox('options');
				return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
			} 
		});
		</script>
			<td>Gudang</td>
		<td><input class='easyui-combobox' id='gudang' name='gudang'/>
		<script>
		$('#gudang').combobox({
				url: './model/cb_gudang.php',
				valueField: 'id',
				textField: 'text',
				filter: function(q, row){
					var opts = $(this).combobox('options');
					return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
				}
		});
		</script></td>
	
		
		</tr>
		<tr>
		<td>Mulai Tanggal</td>
		<td><input id="tgl_mulai" name="tgl_mulai" type="text" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"></td>
		<td>Hingga Tanggal</td>
		<td><input id="tgl_akhir" name="tgl_akhir" type="text" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"></td>
				<td>Sales</td>
		<td><input class="easyui-combobox" name="sales" id="sales">
			  <script>
				$('#sales').combobox({
					url:'./model/cb_sales.php',
					valueField:'id',
					textField:'text',
					filter: function(q, row){
						var opts = $(this).combobox('options');
						return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
					}
				});
			  </script>
		</td>
		<td><a href="javascript:void(0)" cosnplain="3" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="doCari()">Cari</a></td></tr>
            </thead>
        </table>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="doCetak()">Cetak</a>
        <div style="background:#fff">
        <table id="cartcontent" fitColumns="true" 
		url="./model/lap_penjualan.php?jenis=cash" rownumbers="true"
		class="easyui-datagrid" style="width1:300px;height:430px;">
            <thead>
                <tr>
                    <th field="tgl_jual" width=20 >TANGGAL</th>
					<th field="id_penjualan" width=20 >NOTA PENJUALAN</th>
					<th field="nama_stok_bahan" width=40 >NAMA BARANG</th>
					<!--<th field="petugas" width=20 >PETUGAS</th>
					<th field="nama_sales" width=20 >SALES</th>-->
					<th field="alamat" width=40 >ALAMAT</th>
					<th field="telepon" width=20 >TELEPON</th>
					<th field="jumlah" width=20 align="center">JUMLAH</th>
					<th field="harga" width=20 align="right">HARGA</th>
					<!--<th field="diskon" width=20 align="right">DISKON</th>-->
					<th field="bayar" width=20 align="right">TOTAL</th>
                </tr>
            </thead>
			<tfoot></tfoot> 
        </table>
        </div>
</form>
 <div id="wPrint">
		<div>
		<iframe width="742px" height="560px" id="myFrame"></iframe>
		</div>
</div>

<script type="text/javascript">
function doCari(){
	$('#cartcontent').datagrid('loadData', {"total":0,"rows":[],"footer":[]});
    $('#cartcontent').datagrid('load',{
		id_penjualan: $('#nota_jual').val(),
		mulai: $('#tgl_mulai').datebox('getValue'),
		akhir: $('#tgl_akhir').datebox('getValue'),
		barang: $('#barang').val(),
		kategori: $('#kategori').combobox('getValue'),
		gudang : $('#gudang').combobox('getValue'),
		sales : $('#sales').combobox('getValue')
    });
}
function doCetak(){
	$data = { imgUrl: 'images/ajaxloader.gif' };
	var data = $('#cartcontent').datagrid('getData');
	
	$.ajax({
		type: "POST",
		url: './print/lap_penjualan.php',
		data: {	basket:data },
		beforeSend: function() {
			 $.loader.open($data);
		  },
		  complete: function(){
		  	 $.loader.close($data);
		  },
		success: function(result){
    		var url = './pdf/lap_penjualan'+result+'.pdf';
			$('#myFrame').attr('src', url);
			$('#wPrint').window('open');
			$('#wPrint').window('setTitle', 'Laporan Penjualan' );
		}
	});
}
	function GetURLParameter(sParam){
		var sPageURL = window.location.search.substring(1);
		var sURLVariables = sPageURL.split('&');
		for (var i = 0; i < sURLVariables.length; i++) 
		{
			var sParameterName = sURLVariables[i].split('=');
			if (sParameterName[0] == sParam) 
			{
				return sParameterName[1];
			}
		}
	}
	var jenis = GetURLParameter('jenis');
	

$(function (){
	$('#wPrint').window({
    	onClose:function(){
    	 var url = '.' + $('#myFrame').attr("src");
       		$.ajax({
				type: "POST",
				url: './print/hapus_laporan.php',
				data: {	file:url },
			});
    	}
	});		
	$('#cartcontent').datagrid({
    	singleSelect:true,
    	showFooter:true,
    	url: "./model/lap_penjualan.php?jenis="+jenis
	});
	if(jenis=='cash'){
		$('#lb_legend').html("Cash");
	}else{
		$('#lb_legend').html("Grosir");
	}
});
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
$('#wPrint').window({
	title: 'Profil',
    width:760,
    height:600,
    modal:true,
    closed:true,
    minimizable:false,
    maximizable:false,
    collapsible:false
});
</script>

</fieldset>
