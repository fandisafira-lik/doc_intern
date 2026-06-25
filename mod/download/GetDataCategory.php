
<?php

      include ('../../dist/config/koneksi.php');
      // echo $_GET{'tbl'};
      $qulog="SELECT * FROM m_category_doc where id = '".$_POST['id']."'";

      // echo $qulog;

      $res=sqlsrv_query($conn, $qulog);
      $i=0;
      $row = sqlsrv_fetch_array( $res, SQLSRV_FETCH_ASSOC);


      if(empty($row)){
        echo json_encode(array('status'=>'01','msg'=>'Tidak Ada Data', 'post' => $_POST, 'qu' => $qulog));
      }else{
        echo json_encode(array('status'=>'00','msg'=>$row));
      }
 ?>
