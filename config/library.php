
<?php
function konversi($x){
  
  $x = abs($x);
  $angka = array ("","satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
  $temp = "";
  
  if($x < 12){
   $temp = " ".$angka[$x];
  }else if($x<20){
   $temp = konversi($x - 10)." belas";
  }else if ($x<100){
   $temp = konversi($x/10)." puluh". konversi($x%10);
  }else if($x<200){
   $temp = " seratus".konversi($x-100);
  }else if($x<1000){
   $temp = konversi($x/100)." ratus".konversi($x%100);   
  }else if($x<2000){
   $temp = " seribu".konversi($x-1000);
  }else if($x<1000000){
   $temp = konversi($x/1000)." ribu".konversi($x%1000);   
  }else if($x<1000000000){
   $temp = konversi($x/1000000)." juta".konversi($x%1000000);
  }else if($x<1000000000000){
   $temp = konversi($x/1000000000)." milyar".konversi($x%1000000000);
  }
  
  return $temp;
 }
  
 function tkoma($x){
  $str = stristr($x,",");
  $ex = explode(',',$x);
  
  if(($ex[1]/10) >= 1){
   $a = abs($ex[1]);
  } else {
	$a ="";
  }
  $string = array("nol", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan",   "sembilan","sepuluh", "sebelas");
  $temp = "";
 
  $a2 = $ex[1]/10;
  $pjg = strlen($str);
  $i =1;
    
  
  if($a>=1 && $a< 12){   
   $temp .= " ".$string[$a];
  }else if($a>12 && $a < 20){   
   $temp .= konversi($a - 10)." belas";
  }else if ($a>20 && $a<100){   
   $temp .= konversi($a / 10)." puluh". konversi($a % 10);
  }else{
   if($a2<1){
    
    while ($i<$pjg){     
     $char = substr($str,$i,1);     
     $i++;
     $temp .= " ".$string[$char];
    }
   }
  }  
  return $temp;
 }
 
 function terbilang($x){
  if($x<0){
   $hasil = "minus ".trim(konversi(x));
  }else{
   $poin = trim(tkoma($x));
   $hasil = trim(konversi($x));
  }
  
if($poin){
   $hasil = $hasil." koma ".$poin;
  }else{
   $hasil = $hasil;
  }
  return $hasil;  
 } 


 
 
//Format nilai dari angka
function numbertell($x){
$abil = array(
	"",
	"Satu", "Dua", "Tiga",
	"Empat", "Lima", "Enam",
	"Tujuh", "Delapan", "Sembilan",
	"Sepuluh", "Sebelas"
	);
	if ($x < 12)
	return " ".$abil[$x];
elseif ($x<20)
	return numbertell($x-10)." Belas";
elseif ($x<100)
	return numbertell($x/10)." Puluh".numbertell($x%10);
elseif ($x<200)
	return " Seratus".numbertell($x-100);
elseif ($x<1000)
	return numbertell($x/100)." Ratus".numbertell($x % 100);
elseif ($x<2000)
	return " Seribu".numbertell($x-1000);
elseif ($x<1000000)
	return numbertell($x/1000)." Ribu".numbertell($x%1000);
elseif ($x<1000000000)
	return numbertell($x/1000000)." Juta".numbertell($x%1000000);
elseif ($x<1000000000000)
	return numbertell($x/1000000000)." Milyar".numbertell($x%1000000000);
elseif ($x<1000000000000000)
	return numbertell($x/1000000000000)." Trilyun".numbertell($x%1000000000000);
}


// format angka rupiah jurnal
function formatAngka($angka){
	$jadi = number_format(abs($angka),0,',','.');
	if($angka<0) $jadi = "(".$jadi.")";
	
	return $jadi;
}

// cek waktu antara
function cekWaktuAntara($waktu_mulai, $waktu_selesai, $waktu)
{

  $start_timestamp = strtotime($waktu_mulai);
  $end_timestamp = strtotime($waktu_selesai);
  $today_timestamp = strtotime($waktu);

  return (($today_timestamp >= $start_timestamp) && ($today_timestamp <= $end_timestamp));

}

//tentukan ROLE
function roles($string){
		switch ($string) {
			case 'mnj':
				$a = "Manajer";
			break;
			case 'sup':
				$a = "Supervisor";
			break;
			case 'opr':
				$a = "Operator";
			break;
		}
		return $a;
}


//MEMBALIK FORMAT TANGGAL
function balikTanggal($string){
if (!empty($string)){
$tanggal = date("Y-m-d", strtotime($string));
}
else
{
$tanggal = "0000-00-00";
}
return $tanggal;
}
//MEMBALIK FORMAT TANGGAL INDO
function balikTanggalIndo($string){
if (!empty($string)){
$tanggal = date("d-m-Y", strtotime($string));
}
else
{
$tanggal = "";
}
return $tanggal;
}
	function tgl_indo($tgl){
			$tanggal = substr($tgl,8,2);
			$bulan = getBulan(substr($tgl,5,2));
			$tahun = substr($tgl,0,4);
			return $tanggal.' '.$bulan.' '.$tahun;		 
	}	

	function nama_hari($tgl){
			$dt = date(strtotime($tgl));
			$hari= getdate($dt);
			return getHari($hari['wday']);		 
	}

	function jam($tgl){
			$jam = date("H:i:s",strtotime($tgl) );
			return $jam;		 
	}

	function tgl($tgl){
			$tanggal = date("d",strtotime($tgl) );
			return $tanggal;		 
	}

	function detkeJam($time){
		$j = $time/3600;
		$t = $time%3600;
		$hari = $j/24;
		$jam = $j%24;
		$m = $t/60;
		$d = $t%60;
		$time = floor($hari)."d ".floor($jam).":".floor($m).":".$d;
		return $time;		 
	}
//nama bulan
	function getBulan($bln){
				switch ($bln){
					case 1: 
						return "Januari";
						break;
					case 2:
						return "Februari";
						break;
					case 3:
						return "Maret";
						break;
					case 4:
						return "April";
						break;
					case 5:
						return "Mei";
						break;
					case 6:
						return "Juni";
						break;
					case 7:
						return "Juli";
						break;
					case 8:
						return "Agustus";
						break;
					case 9:
						return "September";
						break;
					case 10:
						return "Oktober";
						break;
					case 11:
						return "November";
						break;
					case 12:
						return "Desember";
						break;
				}
			} 
//nama hari
function getHari($hari){
				switch ($hari){
					case 1: 
						return "Senin";
						break;
					case 2:
						return "Selasa";
						break;
					case 3:
						return "Rabu";
						break;
					case 4:
						return "Kamis";
						break;
					case 5:
						return "Jumat";
						break;
					case 6:
						return "Sabtu";
						break;
					case 0:
						return "Minggu";
						break;
				}
} 


// validasi tanggal
function validTanggal($tgl)
{

$tanggal1 = date( "Y", strtotime($tgl));
$bulan1 = date( "m", strtotime($tgl));
$tahun1 = date( "d", strtotime($tgl));
$jam = date("H:i:s", strtotime($tgl));

if ($tgl!='0000-00-00'){
$output= $tanggal1."-". $bulan1."-". $tahun1;}
else
{
$output= "-";
}

return $output;
}

//rupiah
function rp($nominal){  
	$rp = number_format($nominal,0,'','.');
  return "Rp. ".$rp;  
} 
function desim($nomi){ 
 $nom = number_format($nomi,0,'','.');
  return $nom;  
}

// PESAN POP UP
function pesan($module,$str)
	{
	echo "<script>alert('$str');location.href='media.php?module=$module'</script>";
	}
//MEMBALIK FORMAT TANGGAL
function aksibalikTanggal($string){
if (!empty($string)){
$tanggal = date("Y-m-d", strtotime($string));
}
else
{
$tanggal = "0000-00-00";
}
return $tanggal;
}
function cekAdaData($table, $field, $id){
	$totalRows = mysql_num_rows(mysql_query("SELECT * FROM $table WHERE $field ='$id'"));
return $totalRows;
}
function cekSesi($nama, $level){
$peg = mysql_fetch_assoc(mysql_query("SELECT * FROM pegawai WHERE username ='$nama'"));
if(md5($peg['role'])==$level)
return 1;
else
return 0;
}
function cekLevel($level){
				switch ($level){
					case 1: 
						$a = "Admin";
						break;
					case 2:
						$a = "Kasir";
						break;
					case 3:
						$a = "Koki";
						break;
					case 4:
						$a = "Keuangan";
						break;
					}
return $a;
}

	###For Single Searching###
	
	//array to translate the search type
	$ops = array(
            'eq'=>'=', //equal
            'ne'=>'<>',//not equal
            'lt'=>'<', //less than
            'le'=>'<=',//less than or equal
            'gt'=>'>', //greater than
            'ge'=>'>=',//greater than or equal
            'bw'=>'LIKE', //begins with
            'bn'=>'NOT LIKE', //doesn't begin with
            'in'=>'LIKE', //is in
            'ni'=>'NOT LIKE', //is not in
            'ew'=>'LIKE', //ends with
            'en'=>'NOT LIKE', //doesn't end with
            'cn'=>'LIKE', // contains
            'nc'=>'NOT LIKE'  //doesn't contain
	);
	function getWhereClause($col, $oper, $val){
            global $ops;
            if($oper == 'bw' || $oper == 'bn') $val .= '%';
            if($oper == 'ew' || $oper == 'en' ) $val = '%'.$val;
            if($oper == 'cn' || $oper == 'nc' || $oper == 'in' || $oper == 'ni') $val = '%'.$val.'%';
            return " WHERE $col {$ops[$oper]} '$val' ";
	}
	
function detectDelimiter($csvFile)
{
    $delimiters = array(
        ';' => 0,
        ',' => 0,
        "\t" => 0,
        "|" => 0
    );

    $handle = fopen($csvFile, "r");
    $firstLine = fgets($handle);
    fclose($handle); 
    foreach ($delimiters as $delimiter => &$count) {
        $count = count(str_getcsv($firstLine, $delimiter));
    }

    return array_search(max($delimiters), $delimiters);
}
?>