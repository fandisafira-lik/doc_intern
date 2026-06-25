<?php
include '../../dist/config/koneksi.php';
// session_start();
/* Location */
$location = url().$baseurl_file.$_POST['url'];
// print_r($_POST);
$qulog = "DELETE from doc_upload where id =".$_POST['id'];
	$res = sqlsrv_query($conn, $qulog);
	if( $res ) {
	   sqlsrv_commit( $conn );
	   sqlsrv_free_stmt($res);
	   sqlsrv_close( $conn);
	   // echo json_encode(array('status'=>'00','msg'=>'Data Berhasil di Hapus!'));
     $response['status'] = "00";
	} else {
	   sqlsrv_rollback( $conn );
	   sqlsrv_close( $conn);
	   // echo "Transaction rolled back.<br />";
     $response['status'] = "02";
	   // echo json_encode(array('status'=>'01','msg'=>'Data Gagal dihapus!'));
	}


$response['loc'] = $location;
$response['qu'] = $qulog;
if(unlink(getcwd()."/".$_POST['url'])){
  $response['status'] = "00";
}else{
  $response['status'] = "01";
}
$response['loc2'] = getcwd();

// $response['session'] = $_SESSION['doc'];
echo json_encode($response);
