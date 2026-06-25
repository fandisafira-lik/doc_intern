
<?php

      include ('../../dist/config/koneksi.php');
      // echo $_GET{'tbl'};


      $qulog="select view_rc from doc_upload where id = '".$_GET['id']."'";
      $res=sqlsrv_query($conn, $qulog);
      $row = sqlsrv_fetch_array( $res, SQLSRV_FETCH_ASSOC);
      // while($row = sqlsrv_fetch_array( $res, SQLSRV_FETCH_ASSOC)){
        // $data[] = $row;
      // }
      $userlogs = explode("^",$row['view_rc']);

      for($i=0;$i<count($userlogs);$i++){
        $user_keynum = ltrim($userlogs[$i], "0");
        $data['view_rc'][$i] = $user_keynum;
        $qu="SELECT v.*, mk.keynum_karyawan, mru.userlog from [200.200.200.222\SQL2008R2].[dbLUCKY].dbo.v_emp_position v
              left join m_karyawan mk on v.no_pegawai = mk.no_pegawai
              left join m_respctr_userlog mru on mru.keynum_karyawan = mk.keynum_karyawan
              where mru.userlog is not NULL AND mru.keynum_karyawan = '".$user_keynum."'-- order by mrc.kode_rc asc";
        $data['qu2'][$i] = $qu;
        $ress=sqlsrv_query($conn, $qu);
        $rows = sqlsrv_fetch_array( $ress, SQLSRV_FETCH_ASSOC);

        $data['saved'][$i] = $rows['first_name']." - ".$rows['position_name_id'];
      }


      if(empty($data)){
        echo json_encode(array('status'=>'01','msg'=>'Tidak Ada Data', 'kode_rc'=>$kode_rc ,'qulog'=>$qulog,'row'=>$row));
      }else{
        echo json_encode(array('status'=>'00','msg'=>$data));
      }
 ?>
