
<?php

      include ('../../dist/config/koneksi.php');
      // echo $_GET{'tbl'};
      $qulog="SELECT * from doc_upload where view_rc like '%".$_GET['userlog']."%'";

      // echo $qulog;

      $res=sqlsrv_query($conn, $qulog);
      $i=0;
      while($row = sqlsrv_fetch_array( $res, SQLSRV_FETCH_ASSOC)){
        $data[$i]=$row;
        $i++;
      }


      if(empty($data)){
        echo json_encode(array('status'=>'01','msg'=>'Tidak Ada Data'));
      }else{
        echo json_encode(array('status'=>'00','msg'=>$data));
      }
 ?>
