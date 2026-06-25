<?php
  include ('../../dist/config/koneksi.php');

  // print_r($_POST);
  $as="";
  $size = sizeof($_POST);
  $array_keys = array_keys($_POST);
  for($i=0;$i<$size;$i++){

    if($i!=($size-1)){
      $as .= $array_keys[$i]."= '".$_POST[$array_keys[$i]]."', ";
    }else {
      $as .= $array_keys[$i]."= '".$_POST[$array_keys[$i]]."'";
    }
  }


  // $sql = "INSERT INTO Table_1 (id, data) VALUES (?, ?)";
  $params = array(0, "");

  // $stmt = sqlsrv_query( $conn, $sql, $params);
  // if( $stmt === false ) {
  //      die( print_r( sqlsrv_errors(), true));
  // }


  $query = "update ".$_GET['mod']." set ".$as." where id = '".$_GET['id']."' ";
  // echo $query;
  $stmt2 = sqlsrv_query($conn, $query, $params);
  // var_dump($params);
  // while (sqlsrv_next_result($stmt2) != null){};
  // echo $query2;
  if( $stmt2 ) {
     sqlsrv_commit( $conn );
     sqlsrv_free_stmt($stmt2);
     sqlsrv_close( $conn);
     echo json_encode(array('status'=>'00','msg'=>'Data Berhasil di Edit!', 'qu' => $query));
  } else {
     sqlsrv_rollback( $conn );
     sqlsrv_close( $conn);
     // echo "Transaction rolled back.<br />";
     echo json_encode(array('status'=>'01','msg'=>'Data Gagal di Edit!','qu'=>$query));
  }

 ?>
