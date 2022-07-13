<?php
session_start();
require_once "../config/db.koneksi.php";
require_once "../config/db.function.php";
// cekLogin($local);
// catatKegiatan('pegawai','Logout',$local);
 if(isset($_SESSION['login'])) $login = $_SESSION['login']; else $login = "../";
 unset($_SESSION['namauser']);
 unset($_SESSION['idpengguna']);
 unset($_SESSION['peran_id']);
 session_destroy();
 header("location:$login")
?>
