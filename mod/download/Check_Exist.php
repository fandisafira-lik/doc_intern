<?php
include '../../dist/config/koneksi.php';
// session_start();
// print_r($_FILES);
if(isset($_FILES['file']['name'])){

      /* Getting file name */
      $filename = $_FILES['file']['name'];

      /* Location */
      $location = "uploads/".$_SESSION['doc']['kode_rc']."/".$filename;
      if(file_exists($location)){
        $response['status'] = "01";
      }else{
        $response['status'] = "00";
      }

      $response['session'] = $_SESSION['doc'];
      $response['name'] = $filename;
      echo json_encode($response);
      exit;
}

echo 0;
