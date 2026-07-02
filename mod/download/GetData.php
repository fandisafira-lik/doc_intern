<?php
require_once __DIR__ . '/../../dist/config/koneksi.php';

// 1. Ambil & validasi input
$id = $_POST['id'] ?? null;
if ($id === null) {
  echo json_encode(['status' => '01', 'msg' => 'ID tidak dikirim']);
  exit;
}

// 2. Query pertama dengan parameterized query (hindari SQL Injection)
$sql1 = "SELECT du.*, mrc.keterangan, mcd.nama AS category
         FROM doc_upload du
         LEFT JOIN m_category_doc mcd ON du.jenis_doc = mcd.id
         LEFT JOIN m_resp_center mrc ON du.rc_penerbit = mrc.keynum_rc
         WHERE du.id = ?";
$params1 = [$id];
$stmt1 = sqlsrv_query($conn, $sql1, $params1);

if ($stmt1 === false) {
  echo json_encode(['status' => '01', 'msg' => 'Gagal query dokumen']);
  exit;
}

$row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC);
if ($row === null) {
  echo json_encode(['status' => '01', 'msg' => 'Tidak Ada Data']);
  exit;
}

// 3. Olah keynum dari kolom view_rc
$view_rc = $row['view_rc'] ?? '';
$keynums = array_filter(explode('^', $view_rc), fn($v) => $v !== '');
$row['keynums'] = implode(', ', $keynums); // opsional, untuk kompatibilitas

// 4. Query kedua dengan IN clause yang aman (parameterized)
if (empty($keynums)) {
  $row['detail'] = [];
} else {
  $placeholders = implode(',', array_fill(0, count($keynums), '?'));
  $sql2 = "SELECT v.*, mk.keynum_karyawan, mru.userlog
             FROM [200.200.200.222\SQL2008R2].[dbLUCKY].dbo.v_emp_position v
             LEFT JOIN m_karyawan mk ON v.no_pegawai = mk.no_pegawai
             LEFT JOIN m_respctr_userlog mru ON mru.keynum_karyawan = mk.keynum_karyawan
             WHERE mru.userlog IS NOT NULL
               AND mru.keynum_karyawan IN ($placeholders)";
  $params2 = array_values($keynums); // pastikan indeks numerik
  $stmt2 = sqlsrv_query($conn, $sql2, $params2);

  if ($stmt2 === false) {
    echo json_encode(['status' => '01', 'msg' => 'Gagal query data pegawai']);
    exit;
  }

  $detail = [];
  while ($rowKeynums = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
    $detail[] = $rowKeynums;
  }
  $row['detail'] = $detail;
}

// 5. Kembalikan respons JSON bersih (debug dihapus)
echo json_encode([
  'status' => '00',
  'msg'    => $row
]);
