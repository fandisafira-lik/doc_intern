<?php
 session_start();
include_once 'dist/config/koneksi.php';

// print_r($_POST);
if(isset($_SESSION['doc']['username']))
{
  header("Location: index.php");
}
if(isset($_POST['btn-login']))
{
  $username = $_POST['username'];
  $pass = $_POST['pass'];
   // print_r($pass);
  $qulog = "SELECT * FROM user_master WHERE nm_user='$username' ";
  // echo "$qulog";

  $params = array();
  $options = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
  $res = sqlsrv_query($conn, $qulog, $params, $options);
  $row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC);
  // $md5pass = $pass;
  // print_r($row['pass']);
  if($row['pass'] === $pass)
  {
    // $_SESSION['doc']['user_id'] = $row['id_user'];
    $_SESSION['doc']['username'] = $row['nm_user'];
    // $_SESSION['doc']['privilage'] = $row['privilage'];
    // $_SESSION['doc']['keynum_karyawan'] = $row['keynum_karyawan'];
    // $_SESSION['doc']['email'] = $row['email'];
 ?>
    <script>
      alert('Selamat Datang Ultraman!');
      window.location.href = 'index.php?mod=sjwip&cmd=index';
    </script>
    <?php

  }
}
else
{
  ?>
    <script>
      alert('Kata sandi atau email Anda salah. Silakan coba lagi!');
      window.location.href = '#';
    </script>
     <?php } ?>