
<?php

include('../../dist/config/koneksi.php');
// echo $_GET{'tbl'};
$qulog = "SELECT du.*, mrc.keterangan, mcd.nama category from doc_upload du left join m_category_doc mcd on du.jenis_doc = mcd.id left join m_resp_center mrc on du.rc_penerbit = mrc.keynum_rc where du.id = '" . $_POST['id'] . "'";

// echo $qulog;

$res = sqlsrv_query($conn, $qulog);
$i = 0;
$row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC);

$keynums = explode("^", $row['view_rc']);
$sql_keynum = implode(', ', $keynums);
$row['keynums'] = $sql_keynum;

$qulog_keynums = "SELECT v.*, mk.keynum_karyawan, mru.userlog from [200.200.200.222\SQL2008R2].[dbLUCKY].dbo.v_emp_position v
              left join m_karyawan mk on v.no_pegawai = mk.no_pegawai
              left join m_respctr_userlog mru on mru.keynum_karyawan = mk.keynum_karyawan
              where mru.userlog is not NULL AND mru.keynum_karyawan IN (" . $sql_keynum . ")";
$res_keynums = sqlsrv_query($conn, $qulog_keynums);
while ($row_keynums = sqlsrv_fetch_array($res_keynums, SQLSRV_FETCH_ASSOC)) {
  $row['detail'][$i] = $row_keynums;
  $i++;
}

if (empty($row)) {
  echo json_encode(array('status' => '01', 'msg' => 'Tidak Ada Data'));
} else {
  echo json_encode(array('status' => '00', 'msg' => $row, 'aaa' => $qulog_keynums));
}
?>
