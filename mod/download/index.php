<?php
// ========== LOGIKA PHP ==========
// Ambil data session dan siapkan parameter
$keynumKaryawan = $_SESSION['doc']['keynum_karyawan'] ?? '';
$keynumKaryawanPadded = str_pad($keynumKaryawan, 8, '0', STR_PAD_LEFT);

// Query dengan parameter binding (gunakan prepared statement untuk keamanan)
$sql =
  "SELECT 
    du.*,
    mcd.nama as cat_name,
    mrc.keterangan as nama_rc 
  FROM 
    doc_upload du 
    LEFT JOIN m_category_doc mcd ON du.jenis_doc = mcd.id 
    LEFT JOIN m_resp_center mrc ON du.rc_penerbit = mrc.keynum_rc 
  WHERE 
    view_rc LIKE ? 
    OR userid = ?";

$params = array("%$keynumKaryawanPadded%", $keynumKaryawan);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
  die(print_r(sqlsrv_errors(), true));
}

// Kumpulkan data ke dalam array untuk dipisahkan dari HTML
$rows = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
  // Format expiry_date jika ada, jika null beri string kosong
  $expiryFormatted = '';
  if (!empty($row['expiry_date'])) {
    $expiryFormatted = date_format($row['expiry_date'], 'Y-m-d');
  }
  // Tambahkan data yang sudah diformat ke array
  $rows[] = [
    'id'          => $row['id'],
    'cat_name'    => htmlspecialchars($row['cat_name'] ?? '', ENT_QUOTES, 'UTF-8'),
    'url'         => htmlspecialchars($row['url'] ?? '', ENT_QUOTES, 'UTF-8'),
    'description' => htmlspecialchars($row['description'] ?? '', ENT_QUOTES, 'UTF-8'),
    'nama_rc'     => htmlspecialchars($row['nama_rc'] ?? '', ENT_QUOTES, 'UTF-8'),
    'tags'        => htmlspecialchars(str_replace('^', ' ', $row['tags'] ?? ''), ENT_QUOTES, 'UTF-8'),
    'expiry'      => $expiryFormatted,
    'userid'      => $row['userid'] ?? ''
  ];
}
sqlsrv_free_stmt($stmt);

$baseurlFile = $baseurl_file ?? '';

// get privilege edit rotasi pdf
$query_get_privilege =
  "SELECT
    TOP 1 *
  FROM
    m_info mi
  WHERE
    mi.kategori_info = 'doc_intern'
    and mi.nm_info = 'privilege_update_pdf'
    and mi.site1 = ?
    and mi.info_value = ? -- keynum_karyawan";

$stmt_get_privilege = sqlsrv_query($conn, $query_get_privilege, array($_SESSION['doc']['site1'], $keynumKaryawan));
$has_privilege = sqlsrv_fetch_array($stmt_get_privilege, SQLSRV_FETCH_ASSOC);
sqlsrv_free_stmt($stmt_get_privilege);
?>

<style type="text/css">
  #check-all {
    transform: scale(1.5);
    /* Mengubah skala checkbox */
    margin-right: 10px;
    /* Memberikan jarak antara checkbox dan label */
  }

  #check-all-label {
    font-size: 14px;
    /* Mengatur ukuran label */
  }

  #sample_data {
    font-size: 14px !important;
  }

  .dataTables_length,
  .dataTables_length select {
    font-size: 14px;
  }

  .dataTables_filter,
  .dataTables_filter label {
    font-size: 14px !important;
  }

  .dataTables_info {
    font-size: 14px !important;
  }

  .dataTables_paginate,
  .dataTables_paginate a {
    font-size: 14px !important;
  }

  table.dataTable tbody th,
  table.dataTable tbody td {
    padding: 3px 5px 3px 5px !important;
    vertical-align: top;
  }

  table.dataTable thead th {
    padding: 3px 5px 3px 5px !important;
  }

  #userlogs_privilege table tbody th,
  table tbody td {
    padding: 3px 5px 3px 5px !important;
    vertical-align: top;
    font-size: 14px !important;
  }

  #tab_detail {
    line-height: 1.5;
  }

  #tab_detail td {
    font-size: 12px;
  }

  #all_category .select2 option {
    font-size: 4px !important;
  }

  #tags .select2-no-results {
    display: none !important;
  }

  .wrap-150 {
    display: block;
    width: 150px;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
  }

  .f-14 {
    font-size: 14px !important;

  }

  #details .form-control {
    margin: 0 !important;
  }

  /* --- 1. MEMBUAT HEADER STICKY --- */
  #all_data {
    overflow-y: auto;
    overflow-x: auto;
    /* KEMBALIKAN KE AUTO, jangan hidden */
  }

  #sample_data thead th {
    position: sticky;
    top: 0;
    background-color: #f8f9fa;
    z-index: 10;
    border-bottom: 2px solid #dee2e6;
    /* Tambahkan ini agar judul kolom tidak terlipat aneh saat layar menyempit */
    white-space: nowrap;
  }
</style>

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <h3 class="col-12 col-lg-3">List Document |
        <a href="#" onclick="openModalUpload()" style="height: 100px;">
          <i class="fas fa-file-upload fa-2xl"></i>
        </a>
        <a href="#" onclick="showSearch()" style="height: 100px;">
          <i class="fas fa-search fa-2xl"></i>
        </a>
      </h3>
      <div id="searchDt" class="col-12 col-lg-6 col-sm-12 text-left" style="visibility: hidden">
        <input type="text" class="form-control form-control-sm" id="searchDatatable" placeholder="Search" />
      </div>
    </div>

    <div class="row bg-white py-2">
      <div class="table-responsive table-striped overflow-auto col" style="height: 70vh;" id="all_data">
        <table id="sample_data" class="hover" style="width: 100%;">
          <thead>
            <tr>
              <th style="padding: 0px !important;width: 11% !important; line-height: 25px !important; vertical-align: text-top !important;" class="">Category / Detail</th>
              <th style="width: 40% !important;">File Description / View File</th>
              <th>RC Uploader / Download</th>
              <th style="display: none;">tags</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $row): ?>
              <tr>
                <td>
                  <span style="cursor: pointer !important;" class="underlined-blue" onclick="showDetail('<?= $row['id'] ?>','<?= $_SESSION['doc']['keynum_karyawan'] ?>')"><?= $row['cat_name'] ?></span>

                  <?php if ($has_privilege): ?>
                    <a href="#" class="btn-edit-pdf" data-file="<?= $baseurl_file . $row['url'] ?>"><i class="fas fa-pencil-alt"></i></a>
                  <?php endif; ?>
                </td>

                <td style="cursor: pointer !important;" class="underlined-blue" onclick="window.open('<?= $baseurlFile . $row['url'] ?>', '_blank');">
                  <?= $row['description'] ?>
                  <span style="visibility: hidden;"><?= $row['expiry'] ?></span>
                </td>

                <td style="cursor: pointer !important;">
                  <div class="col">
                    <a class="underlined-blue" href="<?= $baseurlFile . $row['url'] ?>" download>
                      <?= $row['nama_rc'] ?>
                    </a>
                  </div>
                </td>

                <td style="display: none"><?= $row['tags'] ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <div class="col-12 col-lg-4 overflow-auto" style="display:none;" id="details">
        <div class="container">
          <div class="row">
            <h5 class="col">
              <a href="#" onclick="showDetail('0','<?= $_SESSION['doc']['keynum_karyawan'] ?>')"><i class="fas fa-arrow-left"></i></a>
            </h5>

            <input disabled style="display: none;" id="id_doc" />
            <input disabled style="display: none;" id="url_detail" />

            <div class="col text-right">
              <h5>
                <a href="#" onclick="update()" id="saveS" style="visibility: hidden"><i class="far fa-check-circle"></i></a>
                <a href="#" onclick="confDel()" id="delS" style="visibility: hidden"><i class="fas fa-trash"></i></a>
              </h5>
            </div>
          </div>

          <table style="border: 0;" id="tab_detail" class="" style="width: 100% !important">
            <tr>
              <td>No Transaksi</td>
              <td> : </td>
              <td id="no_transaksi_detail"></td>
            </tr>

            <tr>
              <td>File Name</td>
              <td> : </td>
              <td id="filename_detail"></td>
            </tr>

            <tr>
              <td>Description</td>
              <td> : </td>
              <td>
                <textarea class="form-control f-14" id="description_detail"></textarea>
              </td>
            </tr>

            <tr>
              <td>Category File </td>
              <td> : </td>
              <td>
                <select class="form-control select2 f-14" id="category_detail"></select>
              </td>
            </tr>

            <tr>
              <td>Uploader </td>
              <td> : </td>
              <td id="uploader_detail" style="text-transform: capitalize;"></td>
            </tr>

            <tr>
              <td>RC Uploader </td>
              <td> : </td>
              <td id="rc_uploader_detail"></td>
            </tr>

            <tr>
              <td>Tags </td>
              <td> : </td>
              <td>
                <select id="tags_detail" class="js-example-basic-multiple form-control" name="tags_detail[]" multiple="multiple">
                </select>
              </td>
            </tr>

            <tr>
              <td>Upload Date </td>
              <td> : </td>
              <td id="date_detail"></td>
            </tr>

            <tr>
              <td>Expiry Date </td>
              <td> : </td>
              <td><input type="text" id="expiry_date" class="form-control" placeholder="yyyy-mm-dd" /></td>
            </tr>
          </table>

          <div class="text-center mt-1">
            <p>Privilege Users</p>
          </div>

          <table id="userlogs_privilege" class="table table-striped">
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>

  <div id="alerts"></div>
</div>

<div class="modal fade" id="uploadModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">

        <div class="">
          <?php
          // ========== LOGIKA PHP ==========
          // Ambil data kategori dari database
          $query = "SELECT id, nama, access FROM m_category_doc";
          $stmt = sqlsrv_query($conn, $query);
          if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
          }

          // Kumpulkan data ke dalam array dengan pemformatan
          $categories = [];
          while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $accessLabel = ($row['access'] == 1) ? 'Specific' : 'All';
            $categories[] = [
              'id'   => htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8'),
              'name' => htmlspecialchars($row['nama'], ENT_QUOTES, 'UTF-8'),
              'access' => $accessLabel
            ];
          }
          sqlsrv_free_stmt($stmt);
          ?>

          <!-- ========== HTML ========== -->
          <select id="jenis_doc" class="select2">
            <option selected disabled>-- Select Category --</option>
            <?php foreach ($categories as $category): ?>
              <option value="<?= $category['id'] ?>">
                <?= $category['name'] ?> - <?= $category['access'] ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <form action="" method="post" enctype="multipart/form-data">
          <div class="my-2">
            <input class="form-control" type="file" id="fileToUpload" name="fileToUpload" multiple>
          </div>
        </form>
        <div class="">
          <textarea type="text" name="rename" id="rename" class="form-control" placeholder="Input Description .."></textarea>
        </div>
        <div class="mt-2">
          <select id="tags" class="js-example-basic-multiple form-control" name="tags[]" multiple="multiple">
          </select>
          <!-- <label for="rename" class="form-label" style="color: red !important; font-size: 11px !important;">Input rename without extention and without dots(.) </label> -->
        </div>
        <div class="mt-2">
          <input type="text" id="expired_date" class="form-control" placeholder="Input expiry date .." />
          <!-- <label for="rename" class="form-label" style="color: red !important; font-size: 11px !important;">Input rename without extention and without dots(.) </label> -->
        </div>
        <hr class="mb-2 mt-0" style="color: lightgrey;">
        <div class="text-right">
          <button type="button" class="btn btn-secondary tutup btn-sm" data-dismiss="modal">Cancel</button>
          <input type="button" value="Upload File" name="submit" id="but_upload" class="btn btn-warning btn-sm">
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Rotasi File PDF</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center p-0">
        <div id="pdf-preview-wrapper" style="height: 600px; overflow-y: auto; background: #525659; padding: 20px;">
          <div id="pdf-preview-container"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="btn-save-pdf">Simpan Perubahan</button>
      </div>
    </div>
  </div>
</div>

<script src="plugins/pdf-lib/js/pdf-lib.min.js"></script>
<script src="plugins/pdf-js/js/pdf.min.js"></script>
<script type="text/javascript">
  /**
   * Variable untuk rotate
   */

  pdfjsLib.GlobalWorkerOptions.workerSrc = 'plugins/pdf-js/js/pdf.worker.min.js'; // Sesuaikan path

  let currentFileUrl = '';
  let currentPdfDoc = null;
  let pageRotations = {}; // Menyimpan rotasi spesifik per halaman, contoh: { 1: 90, 2: 0, 3: 270 }
  let currentPdfBytes = null; // <-- TAMBAHKAN VARIABEL BARU INI

  $(document).ready(function() {
    $('.js-example-basic-multiple').select2({
      tags: true,
      placeholder: "Please Type the Tags ..",
      language: {
        noResults: function(term) {
          return 'Typed Tags';
        }
      }
    });

    $('#expiry_date').datepicker({
      dateFormat: 'yy-mm-dd'
    });

    $('#expired_date').datepicker({
      dateFormat: 'yy-mm-dd'
    });

    // $("#but_upload").click(function() {
    //   var fd = new FormData();
    //   var files = $('#fileToUpload')[0].files;
    //   var jd = $('#jenis_doc').val();
    //   var description = $('#rename').val();
    //   var tags = $('#tags').val();
    //   var tag = "";
    //   if (tags.length > 0) {
    //     for (var i = 0; i < tags.length; i++) {
    //       tag += tags[i];
    //       if (i != (tags.length - 1)) {
    //         tag += "^";
    //       }
    //     }
    //   }
    //   // console.log(tag);
    //   if (jd == '' || jd == null) {
    //     alert("Document Type Can not be Empty!");
    //   } else if (description == '' || description == null) {
    //     alert("Description Can not be Empty!");
    //   } else {
    //     if (files.length > 0) {
    //       fd.append('file', files[0]);
    //       fd.append('jenis_doc', jd);
    //       fd.append('description', description);
    //       fd.append('tags', tag);
    //       fd.append('expiry_date', $('#expired_date').val());
    //       $.ajax({
    //         url: 'mod/download/upload.php',
    //         type: 'post',
    //         data: fd,
    //         dataType: 'json',
    //         contentType: false,
    //         processData: false,
    //         success: function(response) {
    //           if (response.status == 1) {
    //             alert("Upload " + response.name + " Success!");
    //             $('#uploadModal').modal('hide');
    //           } else {
    //             alert('File upload Fail!');
    //             $('#uploadModal').modal('hide');
    //           }
    //           location.reload();
    //         }
    //       });
    //     } else {
    //       alert("Please select a file.");
    //     }
    //     $('#fileToUpload').val('');
    //   }
    // });
    $("#but_upload").click(function() {
      // Ambil nilai dari elemen form, gunakan jQuery secara konsisten
      const fileInput = $('#fileToUpload');
      const files = fileInput.get(0).files; // vanilla property files
      const docType = $('#jenis_doc').val();
      const description = $('#rename').val();
      // tags bisa berupa array (multiple select) atau string biasa
      const tagsRaw = $('#tags').val();
      const expiryDate = $('#expiry_date').val(); // gunakan #expiry_date sesuai form sebelumnya

      // Konversi tags menjadi string dipisah "^"
      // Array.isArray adalah built-in vanilla js untuk cek tipe array
      const tagString = Array.isArray(tagsRaw) ? tagsRaw.join('^') : (tagsRaw || '');

      // --- Validasi dengan early return ---
      if (!docType) {
        alert('Document Type Can not be Empty!');
        return;
      }
      if (!description) {
        alert('Description Can not be Empty!');
        return;
      }
      if (!files || files.length === 0) {
        alert('Please select a file.');
        fileInput.val('');
        return;
      }

      // Siapkan FormData
      const formData = new FormData();
      formData.append('file', files[0]);
      formData.append('jenis_doc', docType);
      formData.append('description', description);
      formData.append('tags', tagString);
      formData.append('expiry_date', expiryDate);

      // Kirim data melalui AJAX dengan setting tradisional
      $.ajax({
        url: 'mod/download/upload.php',
        type: 'post',
        data: formData,
        dataType: 'json',
        contentType: false,
        processData: false,
        success: function(response) {
          // Tampilkan pesan sukses/gagal, tutup modal, dan reload
          if (response.status == 1) {
            alert(`Upload ${response.name} Success!`);
          } else {
            alert('File upload Fail!');
          }
          $('#uploadModal').modal('hide');
          location.reload();
        }
      });

      // Reset input file setelah upload dimulai
      fileInput.val('');
    });

    /**
     * EVENT LISTENERS ROTATE
     */

    // 1. Tampilkan Modal & Load PDF
    $('.btn-edit-pdf').click(async function() {
      currentFileUrl = $(this).data('file');
      pageRotations = {};
      currentPdfBytes = null; // Reset biner lama

      $('#pdfModal').modal('show');
      $('#pdf-preview-container').html('<div class="text-white mt-5">Memuat dokumen...</div>');

      if (currentPdfDoc) {
        try {
          await currentPdfDoc.destroy();
        } catch (e) {}
        currentPdfDoc = null;
      }

      try {
        // Ambil data dari server dengan timestamp unik agar mendapat versi paling segar
        const response = await fetch(currentFileUrl + '?v=' + Date.now(), {
          cache: 'no-store'
        });
        currentPdfBytes = await response.arrayBuffer(); // <-- SIMPAN BINER KE MEMORI GLOBAL

        const loadingTask = pdfjsLib.getDocument({
          data: currentPdfBytes
        });
        currentPdfDoc = await loadingTask.promise;

        await setupAllPages();
      } catch (error) {
        console.error('Error:', error);
        $('#pdf-preview-container').html('<div class="text-danger mt-5">Gagal memuat dokumen.</div>');
      }
    });

    // 2. Aksi Putar Kanan (Menggunakan Event Delegation karena tombol dibuat dinamis)
    $('#pdf-preview-container').on('click', '.btn-rotate-right', function() {
      const pageNum = $(this).data('page');
      pageRotations[pageNum] = (pageRotations[pageNum] + 90) % 360;
      renderSinglePage(pageNum); // Hanya render ulang halaman yang diklik
    });

    // 3. Aksi Putar Kiri
    $('#pdf-preview-container').on('click', '.btn-rotate-left', function() {
      const pageNum = $(this).data('page');
      pageRotations[pageNum] = (pageRotations[pageNum] - 90) % 360;
      if (pageRotations[pageNum] < 0) pageRotations[pageNum] += 360;
      renderSinglePage(pageNum); // Hanya render ulang halaman yang diklik
    });

    // 4. Proses Simpan ke Server (Menyimpan sesuai halaman masing-masing)
    $('#btn-save-pdf').click(async function() {
      const hasChanges = Object.values(pageRotations).some(rot => rot !== 0);
      if (!hasChanges) {
        alert('Tidak ada perubahan rotasi pada halaman manapun.');
        return;
      }

      const $btn = $(this);
      $btn.prop('disabled', true).text('Menyimpan Perubahan...');

      try {
        // KUNCI UTAMA: JANGAN FETCH KE SERVER LAGI
        // Langsung muat dari memori RAM browser yang tersimpan sejak modal dibuka
        const pdfDoc = await PDFLib.PDFDocument.load(currentPdfBytes);
        const pages = pdfDoc.getPages();

        pages.forEach((page, index) => {
          const pageNum = index + 1;
          const extraRotation = pageRotations[pageNum] || 0;

          if (extraRotation !== 0) {
            const currentAngle = page.getRotation().angle;
            page.setRotation(PDFLib.degrees(currentAngle + extraRotation));
          }
        });

        const pdfBytes = await pdfDoc.save();
        const blob = new Blob([pdfBytes], {
          type: 'application/pdf'
        });

        const formData = new FormData();
        const fileName = currentFileUrl.split('/').pop();
        formData.append('pdf_file', blob, fileName);
        formData.append('file_path', currentFileUrl);

        $.ajax({
          url: 'mod/download/update_pdf.php',
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: async function() {
            alert('PDF berhasil diperbarui!');

            // 1. Perbarui cache lokal di RAM dengan biner baru yang sudah miring
            currentPdfBytes = pdfBytes;
            pageRotations = {}; // Reset status miring visual

            $('#pdf-preview-container').html('<div class="text-white mt-5">Memperbarui tampilan...</div>');

            // 2. Hancurkan instance PDF.js lama
            if (currentPdfDoc) {
              try {
                await currentPdfDoc.destroy();
              } catch (e) {}
              currentPdfDoc = null;
            }

            $('#pdfModal').modal('hide')
          },
          error: function(xhr) {
            alert('Gagal menyimpan PDF: ' + xhr.responseText);
          },
          complete: function() {
            $btn.prop('disabled', false).text('Simpan Perubahan');
          }
        });

      } catch (error) {
        console.error('Error saat menyimpan:', error);
        alert('Terjadi kesalahan saat memproses file.');
        $btn.prop('disabled', false).text('Simpan Perubahan');
      }
    });
    /**
     * END LISTENERS ROTATE
     */
  });

  /**
   * Function utk rotate
   */
  // Fungsi untuk merender SATU halaman spesifik (dipanggil saat tombol putar per-halaman diklik)
  async function renderSinglePage(pageNum) {
    if (!currentPdfDoc) return;

    try {
      const page = await currentPdfDoc.getPage(pageNum);

      // KUNCI UTAMA: Ambil rotasi bawaan file asli (yang sudah disimpan oleh pdf-lib)
      // baseViewport pasti mengembalikan rotasi asli (0, 90, 180, atau 270)
      const baseViewport = page.getViewport({
        scale: 1.0
      });
      const nativeRotation = baseViewport.rotation;

      // Gabungkan rotasi bawaan dengan rotasi klik dari tombol (jika ada)
      const userRotation = pageRotations[pageNum] || 0;
      const totalRotation = (nativeRotation + userRotation) % 360;

      const wrapper = document.getElementById('pdf-preview-wrapper');
      const canvas = document.getElementById(`canvas-page-${pageNum}`);
      const context = canvas.getContext('2d');

      // Gunakan totalRotation agar posisi bawaan dan posisi edit bisa sejalan
      const unscaledViewport = page.getViewport({
        scale: 1.0,
        rotation: totalRotation
      });

      const paddingOffset = 60;
      const containerWidth = wrapper.clientWidth - paddingOffset;
      const containerHeight = wrapper.clientHeight - paddingOffset;

      const ratioWidth = containerWidth / unscaledViewport.width;
      const ratioHeight = containerHeight / unscaledViewport.height;
      const optimalScale = Math.min(ratioWidth, ratioHeight);

      // Terapkan ukuran dan rotasi final
      const viewport = page.getViewport({
        scale: optimalScale,
        rotation: totalRotation
      });

      canvas.height = viewport.height;
      canvas.width = viewport.width;

      await page.render({
        canvasContext: context,
        viewport: viewport
      }).promise;

    } catch (error) {
      console.error(`Gagal merender halaman ${pageNum}:`, error);
    }
  }

  // Fungsi Utama: Setup Awal Semua Halaman (Kanvas + Tombol)
  async function setupAllPages() {
    if (!currentPdfDoc) return;

    const container = document.getElementById('pdf-preview-container');
    container.innerHTML = '';

    for (let pageNum = 1; pageNum <= currentPdfDoc.numPages; pageNum++) {
      // Set default rotasi tambahan halaman ini adalah 0
      pageRotations[pageNum] = 0;

      // Buat Wrapper per halaman (untuk menampung tombol + kanvas)
      const pageWrapper = document.createElement('div');
      pageWrapper.className = 'page-wrapper mb-5 pb-3 border-bottom border-secondary';

      // Buat UI Tombol Kontrol per Halaman
      const controls = document.createElement('div');
      controls.className = 'mb-2 text-white';
      controls.innerHTML = `
        <span class="badge badge-dark mr-2">Halaman ${pageNum}</span>
        <button class="btn btn-sm btn-light btn-rotate-left" data-page="${pageNum}">↺ Putar Kiri</button>
        <button class="btn btn-sm btn-light btn-rotate-right" data-page="${pageNum}">Putar Kanan ↻</button>
      `;

      // Buat Kanvas
      const canvas = document.createElement('canvas');
      canvas.id = `canvas-page-${pageNum}`;
      canvas.className = 'shadow d-block mx-auto bg-white';

      // Masukkan elemen ke DOM
      pageWrapper.appendChild(controls);
      pageWrapper.appendChild(canvas);
      container.appendChild(pageWrapper);

      // Render visual halaman tersebut
      await renderSinglePage(pageNum);
    }
  }
  /**
   * End
   */

  function showDetail(id, user) {
    // Early return: jika id "0", sembunyikan detail dan hentikan eksekusi
    if (id === '0') {
      handlePanelVisibility(id);
      return;
    }

    const postData = {
      id: id
    };

    $.ajax({
      url: 'mod/download/GetData.php',
      type: 'post',
      data: postData,
      dataType: 'json',
      success: function(response) {
        // Early return jika status bukan "00"
        if (response.status !== '00') {
          alert('No Data!');
          return;
        }

        const docData = response.msg;
        // Set data utama terlebih dahulu
        $('#id_doc').val(id);
        $('#filename_detail').text(docData.nama);
        $('#url_detail').val(docData.url);
        $('#description_detail').val(docData.description);
        $('#no_transaksi_detail').text(docData.no_transaksi);
        $('#uploader_detail').text(docData.userid);
        $('#date_detail').text(getDate('Date', docData.date_uploaded.date));

        // Expiry date
        const expiryValue = (docData.expiry_date == null || docData.expiry_date === '') ?
          '' :
          getDate('Date', docData.expiry_date.date);
        $('#expiry_date').val(expiryValue);

        $('#rc_uploader_detail').text(docData.keterangan);

        // Tags: proses array dengan map dan join, assign dahulu baru set
        const tagsRaw = (docData.tags == null || docData.tags === '') ? [] : docData.tags.split('^');
        const tagsHtml = tagsRaw.map(tag => `<option value="${tag}" selected>${tag}</option>`).join('');
        $('#tags_detail').empty().append(tagsHtml);

        // User privilege table
        const detailEntries = docData.detail;
        const tableBody = $('#userlogs_privilege tbody');
        tableBody.empty();
        if (detailEntries != null && Array.isArray(detailEntries)) {
          const rowsHtml = detailEntries.map(item =>
            `<tr><td>${item.first_name} - ${item.position_name_id}</td></tr>`
          ).join('');
          tableBody.append(rowsHtml);
        }

        // Panggil AJAX untuk kategori, hanya setelah detail selesai
        $.ajax({
          url: 'mod/download/GetDataAllCategory.php',
          type: 'post',
          data: postData,
          dataType: 'json',
          success: function(catResponse) {
            if (catResponse.status !== '00') {
              alert('No Category Data!');
              return;
            }

            const selectedCategoryId = docData.jenis_doc;
            const catOptionsHtml = catResponse.msg.map(cat => {
              const selectedAttr = (cat.id == selectedCategoryId) ? 'selected' : '';
              return `<option value="${cat.id}" ${selectedAttr}>${cat.nama} - ${cat.access}</option>`;
            }).join('');
            $('#category_detail').empty().append(catOptionsHtml);
          }
        });

        // Hak akses user: update visibilitas dan disabled state dengan jQuery
        const isOwner = (user === docData.userid);
        $('#delS').css('visibility', isOwner ? 'visible' : 'hidden');
        $('#saveS').css('visibility', isOwner ? 'visible' : 'hidden');
        $('#category_detail').prop('disabled', !isOwner);
        $('#description_detail').prop('disabled', !isOwner);
        $('#tags_detail').prop('disabled', !isOwner);
      }
    });

    // Penanganan tampilan mobile/desktop
    handlePanelVisibility(id);
  }

  // Fungsi terpisah untuk menangani visibilitas panel,
  // memanfaatkan early return agar logika bersih.
  function handlePanelVisibility(id) {
    const isMobile = $(window).width() < 1100;
    const $details = $('#details');
    const $allData = $('#all_data');

    if (isMobile) {
      // Toggle tampilan jika detail sedang disembunyikan
      if ($details.css('display') === 'none') {
        $allData.fadeOut();
        $details.fadeIn();
      } else {
        $details.fadeOut();
        $allData.fadeIn();
      }
      return;
    }

    // Desktop: tampilkan detail hanya jika id bukan "0"
    if (id === '0') {
      $details.fadeOut();
    } else {
      $details.fadeIn();
    }
  }

  function openModalUpload() {
    $('#uploadModal').modal('show');
  }

  // function update() {
  //   var tags = $('#tags_detail').val();
  //   var tag = "";
  //   if (tags.length > 0) {
  //     for (var i = 0; i < tags.length; i++) {
  //       tag += tags[i];
  //       if (i != (tags.length - 1)) {
  //         tag += "^";
  //       }
  //     }
  //   }
  //   var expired = '';
  //   if ($('#expiry_date').val() == '') {
  //     expired = '';
  //   } else {
  //     expired = $('#expiry_date').val();
  //   }
  //   var data = {
  //     "description": $('#description_detail').val(),
  //     "expiry_date": expired,
  //     "jenis_doc": $('#category_detail').val(),
  //     "tags": tag
  //   }

  //   $.ajax({
  //     url: 'mod/download/UpdateData.php?id=' + $('#id_doc').val() + '&mod=doc_upload',
  //     type: 'post',
  //     data: data,
  //     dataType: 'json',
  //     success: function(response1) {
  //       if (response1.status == "00") {
  //         alert("Document Updated!")
  //       } else {
  //         alert("Document Update Fail!")
  //       }
  //       location.reload();
  //     }
  //   });
  // }

  function update() {
    const docId = $('#id_doc').val();
    const description = $('#description_detail').val();
    const category = $('#category_detail').val();
    // expiry_date: gunakan nilai input atau string kosong
    const expiryDate = $('#expiry_date').val() || '';

    // Ambil nilai tags_detail. Jika select multiple, val() mengembalikan array;
    // jika single, mengembalikan string. Tangani kedua kemungkinan.
    const tagsVal = $('#tags_detail').val();
    // Gunakan Array.isArray untuk deteksi, fallback ke string kosong jika null/undefined
    const tagString = Array.isArray(tagsVal) ? tagsVal.join('^') : (tagsVal || '');

    const postData = {
      description: description,
      expiry_date: expiryDate,
      jenis_doc: category,
      tags: tagString
    };

    $.ajax({
      url: `mod/download/UpdateData.php?id=${docId}&mod=doc_upload`,
      type: 'post',
      data: postData,
      dataType: 'json',
      success: function(response) {
        if (response.status === '00') {
          alert('Document Updated!');
        } else {
          alert('Document Update Fail!');
        }
        location.reload();
      }
    });
  }

  function showSearch() {
    const $searchDt = $('#searchDt');
    if ($searchDt.length === 0) return;

    if ($searchDt.css('visibility') === 'hidden') {
      $searchDt.css('visibility', 'visible');
      return;
    }
    $searchDt.css('visibility', 'hidden');
  }

  // function confDel() {
  //   let text;
  //   if (confirm("Are you sure to delete file " + $('#filename_detail').html() + " ?") == true) {
  //     var data = {
  //       "id": $('#id_doc').val(),
  //       "url": $('#url_detail').val()
  //     };
  //     $.ajax({
  //       url: 'mod/download/deleteFile.php', // url where to submit the request
  //       type: "POST", // type of action POST || GET
  //       data: data, // post data || get data
  //       dataType: 'json', // data type
  //       success: function(result) {
  //         alert("Document Deleted Successfully!");
  //         location.reload();
  //       },
  //       error: function(xhr, resp, text) {
  //         console.log(xhr, resp, text);
  //       }
  //     });
  //   } else {
  //     text = "You canceled!";
  //   }
  // }

  function confDel() {
    const filename = $('#filename_detail').text();
    const id = $('#id_doc').val();
    const fileUrl = $('#url_detail').val();

    // Early return: batalkan proses jika user tidak konfirmasi
    if (!confirm(`Are you sure to delete file ${filename} ?`)) return;

    const postData = {
      id: id,
      url: fileUrl
    };

    $.ajax({
      url: 'mod/download/deleteFile.php',
      type: 'POST',
      data: postData,
      dataType: 'json',
      success: function(result) {
        alert('Document Deleted Successfully!');
        location.reload();
      },
      error: function(xhr, resp, text) {
        console.log(xhr, resp, text);
      }
    });
  }

  let table = $('#sample_data').DataTable({
    "dom": 'rtip',
    "paging": false,
    "info": false,
    initComplete: function() {
      this.api().columns([0]).every(function() {
        let column = this;
        let div = $('<span class="" style="line-height: 150% !important; margin-top: 50px !important;"> </span>')
          .appendTo($(column.header()));
        let select = $('<select class="form-control form-control-sm select2" style="margin-top: 50px !important;" id="all_category"><option selected value="">-- All --</option></select>')
          .appendTo($(column.header()))
          .on('change', function() {
            let val = $.fn.dataTable.util.escapeRegex(
              $(this).val()
            );

            column
              .search(this.value, true, false)
              .draw();
          });

        column.data().unique().sort().each(function(d, j) {
          select.append('<option value=' + d + '>' + d + '</option>');
        });
      });
      this.api().columns([1]).every(function() {
        let column = this;
        let text = $('<input type="text" class="mt-2 form-control form-control-sm" placeholder="Search Description" />')
          .appendTo($(column.header()))
          .on('keyup change', function() {
            let val = $.fn.dataTable.util.escapeRegex(
              $(this).val()
            );

            column
              .search(this.value, true, false)
              .draw();
          });
      });
      this.api().columns([2]).every(function() {
        let column = this;
        let text = $('<input type="text" class="mt-2 form-control form-control-sm" placeholder="Search RC" />')
          .appendTo($(column.header()))
          .on('keyup change', function() {
            let val = $.fn.dataTable.util.escapeRegex(
              $(this).val()
            );

            column
              .search(this.value, true, false)
              .draw();
          });
      });
    }
  });

  $('#searchDatatable').keyup(function() {
    table.search($(this).val()).draw();
  });
</script>