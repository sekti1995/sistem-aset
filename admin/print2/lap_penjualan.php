<?php
ob_start();
session_start();
require_once "../../config/library.php";
require_once "../../config/mpdf/mpdf.php";
require_once "../../config/db.koneksi.php";
require_once "../../config/db.function.php";
ini_set('memory_limit', '-1');
$basket = $_POST['basket']['rows'];
$footer = $_POST['basket']['footer'];
$no = 1;
$html = "<h1>Laporan Penjualan</h1>";
$html .= "<table width='100%' class='noborder'><tr><td>Pencetak : $_SESSION[namauser]</td>";
$html .= "<td align='right'>Tanggal Cetak : ".date("d-m-Y H:i:s")."</td></tr></table><p></p>";
$html .= "<table width='100%'>
		  <tr><th>NO</th><th>TANGGAL</th><th>NOTA PENJUALAN</th><th>NAMA BARANG</th>
		  <th>ALAMAT</th><th>TELEPON</th><th>JUMLAH</th><th>HARGA</th><th>TOTAL</th></tr>";
foreach($basket as $kunci => $val){
	$html .= "<tr><td>$no</td>
			<td>$val[tgl_jual]</td>
			<td>$val[id_penjualan]</td>
			<td>$val[nama_stok_bahan]</td>
			<td>$val[alamat]</td>
			<td>$val[telepon]</td>
			<td align=right>$val[jumlah]</td>
			<td align=right>$val[harga]</td>
			<td align=right>$val[bayar]</td>
			</tr>";
	$no++;
}
$total = $footer[0]['bayar'];
$html .= "<tr><th colspan='8' align='center'>TOTAL</th><th align=right>$total</th></tr>";
$html .= "</table>";

$tglcetak = date('ymdhis');
$mpdf=new mPDF('c',array(210,290),'','',10,10,10,10,6,3); 
$mpdf->SetDisplayMode('fullpage');
$mpdf->list_indent_first_level = 0;	
$stylesheet = file_get_contents('../../config/mpdf/examples/mpdfstyletables2.css');
$mpdf->WriteHTML($stylesheet,1);
$mpdf->WriteHTML($html);
$mpdf->Output('../pdf/lap_penjualan'.$tglcetak.'.pdf','F');
echo $tglcetak;
?>