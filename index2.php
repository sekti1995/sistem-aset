<?php
  ob_start();
  session_start();
  require_once "config/library.php";
  require_once "config/db.koneksi.php";
  require_once "config/db.function.php"; 
  ?>
<!DOCTYPE html>
<html>
<head>
<title>::: Sim-BaPer:::</title>
<link rel="shortcut icon" href="images/kra.png" />
<meta http-equiv="content-type" content="text/html; charset=windows-1252">
<link href="css/style2.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="js/jeui/themes/default/easyui.css">
<script type="text/javascript" src="css/inc/jquery.js"></script>
<!--<script type="text/javascript" src="js/all.js"></script>-->
<script src="js/jquery-1.8.3.min.js" type="text/javascript"></script>
<script src="js/jquery.md5.min.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jeui/jquery.easyui.min.js"></script>
<script type="text/javascript" charset="utf-8">
$(document).ready(function($) {
    $("input").keydown(function(e) {
		if(e.which == 13) {
			var pass = $.MD5($('#pass').val());
			masuk(pass);
		}
	});
	$('#login').click(function() {
		var pass = $.MD5($('#pass').val());
		masuk(pass);
	});
});
function masuk(passw){
	if($('#id_sumber').combobox('getValue') == ""){
		alert("PILIH SUMBER DANA DAHULU !");
	} else {
		$.ajax({
			type: "POST",
			url: './cek_login.php?oper=cek_login',
			data: { uname: $('#username').val(), password : passw, ta: $('#ta').combobox('getValue'), id_sumber: $('#id_sumber').combobox('getValue') },
			success: function(data){
				console.log(data);
				var data = eval('('+data+')');
				if(data.success==false) alert(data.pesan);
				location.href=data.url;
				//alert(data);
			}
		});	
	}	
}
</script>

</head>
<body>
<div id="container" align="center" style="margin-bottom:50px;">
	<div class="lg-container">
	<form method=POST action='cek_login.php' id="lg-form" name="lg-form" enctype='multipart/form-data'>

			<img src="images/kra.png" width="30%">
			<div>
				<label>Username :</label>
				<input type="text" name="uname" id="username" placeholder="username" style="width:94%;height:30px"/>
			</div>
			<div>
				<label for="password">Password :</label>
				<input type="password" name="pass" id="pass" placeholder="password" style="width:94%;height:30px" />
			</div>
			<div>
				<label>Tahun Anggaran </label><br>
				<input class="easyui-combobox" style="width:100%;height:30px" id="ta" name="ta" required="true" prompt="Tahun Anggaran" value="<?php echo date("Y"); ?>"/>
				<script>
				$('#ta').combobox({
					url:'admin/model/cb_ta.php',
					valueField:'id',
					textField:'text',
					filter: function(q, row){
						var opts = $(this).combobox('options');
						return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
					}
				});
				</script>
			</div>
			<div>
				<label>Sumber Dana </label><br>
				<input class="easyui-combobox" style="width:100%;height:30px" id="id_sumber" name="id_sumber" required="true" prompt="Sumber Dana"  />
				<script>
				$('#id_sumber').combobox({
					url:'admin/model/cb_sumber_dana_index.php?semua=no',
					valueField:'id',
					textField:'text',
					filter: function(q, row){
						var opts = $(this).combobox('options');
						return row[opts.textField].toUpperCase().indexOf(q.toUpperCase()) >= 0;
					}
				});
				</script>
			</div>
			<div>				
				<button type="button" id="login">Login</button>
			</div>
	</form>
	</div>
</div>
</body></html>
