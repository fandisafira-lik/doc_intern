
<?php

      include ('../../dist/config/koneksi.php');
      // echo $_GET{'tbl'};


      $qulog_rc="SELECT distinct(mru.keynum_rc), mrc.keterangan from m_respctr_userlog mru left join m_resp_center mrc on mru.keynum_rc = mrc.keynum_rc";

      // echo $qulog;

      $res_rc=sqlsrv_query($conn, $qulog_rc);
      while($row_rc = sqlsrv_fetch_array( $res_rc, SQLSRV_FETCH_ASSOC)){

        $qulog="SELECT mru.*, mk.nama_pegawai, mrc.kode_rc, mru.keynum_karyawan from m_respctr_userlog mru left join m_karyawan mk on mru.keynum_karyawan = mk.keynum_karyawan left join m_resp_center mrc on mru.keynum_rc = mrc.keynum_rc where keynum_rc = '".$row_rc['keynum_rc']."'";

        // echo $qulog;

        $res=sqlsrv_query($conn, $qulog);
        $i=0;
        $data=[];
        $kode_rc="";
        while($row = sqlsrv_fetch_array( $res, SQLSRV_FETCH_ASSOC)){
          $kode_rc = rtrim($row['kode_rc'], "0");
          if(empty($kode_rc)){
            $kode_rc = 0;
          }
          $id="";
          // for($i=0;$i<sizeOf($kode_rc); $i++){
          //   $id .= $kode_rc[$i];
          //   if($i!=(sizeOf($kode_rc)-1)){
          //     $id .= ";";
          //   }
          // }
          $data[$i]['id'] = $row['keynum_karyawan'];
          $data[$i]['parent'] = substr_replace($kode_rc ,"", -1);
          // $data[$i]['parent'] = substr_replace($kode_rc ,"", -1);
          $data[$i]['text'] = $row['nama_pegawai'];
          // $data[$i]['icon'] = "";
          // $data[$i]['state']['opened'] = false;
          // $data[$i]['state']['disabled'] = false;
          // $data[$i]['state']['selected'] = false;
          // $data[$i]['li'] = false;
          $i++;
        }
      }
      // $data['row'] = $row;


      if(empty($data)){
        // echo json_encode(array('status'=>'01','msg'=>'Tidak Ada Data', 'kode_rc'=>$kode_rc ,'qulog'=>$qulog,'row'=>$row));
      }else{
        echo json_encode(array('status'=>'00','msg'=>$data));
      }
 ?>
