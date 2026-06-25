
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
      $data['view_rc'] = $userlogs;

      for($i=0;$i<count($userlogs);$i++){
        $qu="SELECT mru.keynum_karyawan, mru.keynum_rc, mrc.keterangan, mk.nama_pegawai, mru.userlog from m_respctr_userlog mru
              left join m_resp_center mrc on mru.keynum_rc = mrc.keynum_rc
              left join m_karyawan mk on mru.keynum_karyawan = mk.keynum_karyawan where mru.userlog = '".$userlogs[$i]."' order by mrc.kode_rc asc";
        $ress=sqlsrv_query($conn, $qu);
        $rows = sqlsrv_fetch_array( $ress, SQLSRV_FETCH_ASSOC);

        $data['saved'][$i] = $rows['nama_pegawai']." - ".$rows['keterangan'];
      }


      if(empty($data)){
        echo json_encode(array('status'=>'01','msg'=>'Tidak Ada Data', 'kode_rc'=>$kode_rc ,'qulog'=>$qulog,'row'=>$row));
      }else{
        echo json_encode(array('status'=>'00','msg'=>$data));
      }
 ?>
