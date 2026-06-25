
<?php

      include ('../../dist/config/koneksi.php');
      // echo $_GET{'tbl'};
      $qulog="SELECT * FROM m_category_doc";
      $res=sqlsrv_query($conn, $qulog);
      while($row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC)){
        $data[]=$row;
      }

      if(empty($data)){
        echo json_encode(array('status'=>'01','msg'=>'Tidak Ada Data'));
      }else{
        echo json_encode(array('status'=>'00','msg'=>$data));
      }
 ?>
