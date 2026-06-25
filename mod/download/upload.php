<?php
include '../../dist/config/koneksi.php';
// session_start();
// print_r($_POST);
if(isset($_FILES['file']['name'])){

      /* Getting file name */
      $filename = $_FILES['file']['name'];
      /* Location */
      $location = "uploads/".$filename;

      /* Extension */
      $extension = pathinfo($location,PATHINFO_EXTENSION);
      $extension = strtolower($extension);

      /* Allowed file extensions */
      $allowed_extensions = array("jpg","jpeg","png","pdf","gif","mp4");
      // $allowed_extensions = array("jpg","jpeg","png","pdf","docx","doc","xls","xlsx","ppt","pptx");

      $response = array();
      $status = 0;

      $qulog="SELECT * from m_resp_center where kode_rc = '".$_SESSION['doc']['kode_rc']."'";
      $stmt = sqlsrv_query( $conn, $qulog );
      $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
      // print_r($row);

      $qulog2="SELECT max(no_urut) no_urut from doc_upload where date_uploaded > '".date('Y-m-01')."'";
      // echo $qulog2;
      $stmt2 = sqlsrv_query( $conn, $qulog2 );
      $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC);
      if(empty($row2)){
        $row2['no_urut'] = 0;
      }
      $no_urut = str_pad(($row2['no_urut'] + 1), 5, '0', STR_PAD_LEFT);
      // echo $no_urut;
      $no_transaksi = $_SESSION['doc']['site1'].$row['ref_doc']."DOC".date("ym").$no_urut;
      $filename2 = $no_transaksi.".".$extension;
      $location = "uploads/".$filename2;

      $query2 = "INSERT INTO doc_upload
        (nama, jenis_doc, userid, rc_penerbit, url, jml_revisi, no_transaksi, description, tags, no_urut, expiry_date, view_rc)
        values ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? ) ";

      $params = array(
        $filename,
        $_POST['jenis_doc'],
        $_SESSION['doc']['keynum_karyawan'],
        $_SESSION['doc']['keynum_rc'],
        $location,
        0,
        $no_transaksi,
        $_POST['description'],
        $_POST['tags'],
        $row2['no_urut']+1,
        $_POST['expiry_date'],
        '00000237'
      );
      $response['params'] = $params;
      $stmt2 = sqlsrv_query($conn, $query2, $params);
      if( $stmt2 ) {
      //print_r($stmt2);
      //echo "aaaa";
        sqlsrv_commit( $conn );
        sqlsrv_free_stmt($stmt2);
        sqlsrv_close( $conn);
      } else {
      //print_r($stmt2);
      //echo "bbbb";
        sqlsrv_rollback( $conn );
        sqlsrv_close( $conn);
      }


      /* Check file extension */
      if(in_array(strtolower($extension), $allowed_extensions)) {
           /* Upload file */
           if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){

                 $status = 1;
                 $response['path'] = $location;
                 $response['name'] = $filename;
                 $response['extension'] = $extension;
                 $response['no_urut'] = $no_urut;
                 $response['no_transaksi'] = $no_transaksi;
                 $response['qulog2'] = $qulog2;

           }
      }

      $response['status'] = $status;
      $response['session'] = $_SESSION['doc'];
      echo json_encode($response);
      exit;
}

//echo 0;
