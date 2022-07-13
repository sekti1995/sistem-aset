<?php
  ob_start();
  session_start();
  require_once "config/library.php";
  require_once "config/db.koneksi.php";
  require_once "config/db.function.php"; 
  ?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>::: Sim-BaPer:::</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="images/kra.png" href=""/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
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
	
		$.ajax({
			type: "POST",
			url: './cek_login.php?oper=cek_login',
			data: { uname: $('#username').val(), password : passw, ta: $('#ta').val(), id_sumber: $('#id_sumber').val() },
			success: function(data){
				console.log(data);
				var data = eval('('+data+')');
				if(data.success==false) alert(data.pesan);
				location.href=data.url;
				//alert(data);
			}
		});	
	
}
</script>
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
					<img src="images/img01.png" width="120%">
				</div>

				<form class="login100-form validate-form">
					<span class="login100-form-title">
						Member Login
					</span>

					<div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
						<input class="input100" type="text" name="uname" id="username" placeholder="username">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-user" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate = "Password is required">
						<input class="input100" type="password" name="pass" id="pass" placeholder="Password">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>
					<div class="wrap-input100 validate-input" data-validate = "Password is required">
						<select class="input100"  id="ta" name="ta" required="true"prompt="Tahun Anggaran" value="<?php echo date("Y"); ?>">
						<?php
						//include '../config/db.koneksi.php';
						$option = mysql_query("SELECT* FROM ref_tahun order by tahun desc");
						while ($ta=mysql_fetch_array($option)) { ?>
						<option value="<?=$ta['tahun']?>"><?=$ta['tahun']?></option>
						<?php
						}
						?>
					</select>
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-calendar" aria-hidden="true"></i>
						</span>
					</div>
					<div class="wrap-input100 validate-input" data-validate = "Password is required">
					<select class="input100"  id="id_sumber" name="id_sumber" required="true" prompt="Sumber Dana">
					<?php
						//include '../config/db.koneksi.php';
						$option = mysql_query("SELECT* FROM ref_sumber_dana order by id_sumber ");
						while ($ta=mysql_fetch_array($option)) { ?>
						<option value="<?=$ta['id_sumber']?>"><?=$ta['nama_sumber']?></option>
						<?php
						}
						?>
					</select>
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-bars" aria-hidden="true"></i>
						</span>
					</div>
					
					<div class="container-login100-form-btn">
						<button class="login100-form-btn" type="button" id="login">
							Login
						</button>
					</div>

					<!-- <div class="text-center p-t-12">
						<span class="txt1">
							Forgot
						</span>
						<a class="txt2" href="#">
							Username / Password?
						</a>
					</div>

					<div class="text-center p-t-136">
						<a class="txt2" href="#">
							Create your Account
							<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
						</a>
					</div> -->
				</form>
			</div>
		</div>
	</div>
	
	

	
<!--===============================================================================================-->	
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/tilt/tilt.jquery.min.js"></script>
	<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>

</body>
</html>