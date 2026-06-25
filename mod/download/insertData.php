<?php
  include ('../../dist/config/koneksi.php');

  $query2 = "INSERT INTO m_category_doc
  (nama, keterangan, access)
  values ( ?, ?, ? ) ";
  $params = array(
    $_POST['nama'],
    $_POST['keterangan'],
    $_POST['access']
  );
  // echo json_encode($query2);
  $stmt2 = sqlsrv_query($conn, $query2, $params);
  if( $stmt2 ) {
     sqlsrv_commit( $conn );
     sqlsrv_free_stmt($stmt2);
     sqlsrv_close( $conn);
     echo json_encode(array('status'=>'00','msg'=>'Data Berhasil di Tambahkan!'));
  } else {
     sqlsrv_rollback( $conn );
     sqlsrv_close( $conn);
     // echo "Transaction rolled back.<br />";
     echo json_encode(array('status'=>'01','msg'=>'Data Gagal di Tambahkan!'));
  }

 ?>
