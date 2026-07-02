<?php
include("dist/config/koneksi.php");
// include("dist/config/tes.php");

$username = ucfirst($_SESSION['doc']['username']);
$keterangan = $_SESSION['doc']['keterangan'];
$key = $_SESSION['doc']['kode_rc'];
//$site = $_SESSION['doc']['lokasi'];
//$ip_printer = $_SESSION['doc']['ip_printer'];
// echo " $keterangan"
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="Logo.png" type="image/ico" />
  <title>PT. LIK | DOCUMENT Management (Intern)</title>
  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"> -->

  <link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.0/themes/base/jquery-ui.css">
  <!-- <link rel="stylesheet" href="plugins/jquery/jquery-ui.css"> -->
  <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="plugins/font/font.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- DataTables -->

  <link rel="stylesheet" href="plugins/datatables/dataTables.min.css" />
  <!-- <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css"> -->
  <!-- daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.css">
  <link rel="stylesheet" href="plugins/bootstrap/css/bootstrap-treeview.min.css" type="text/css" />
  <link rel="stylesheet" href="plugins/jstree/css/style.css" />

  <style>
    .detail {
      display: none;
    }

    .disabled {
      pointer-events: none;
      /* Mencegah interaksi pointer */
      cursor: default;
      /* Mengubah kursor menjadi default */
      opacity: 0.6;
      /* Memberikan tampilan elemen yang dinonaktifkan */
    }

    .underlined-blue {
      color: #1f2d3d !important;
    }

    .underlined-blue:hover {
      color: #0096FF !important;
      text-decoration: underline;
      cursor: pointer;
    }
  </style>

  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="plugins/jquery-ui/jquery-ui.min.js"></script>
  <script src="plugins/moment/moment.min.js"></script>
  <script type="text/javascript" src="plugins/datetimepicker/datetimepicker.js"></script>
  <script src="plugins/datatables/dataTables.min.js"></script>
  <!-- Select2 -->
  <script src="plugins/select2/js/select2.full.min.js"></script>
  <script src="dist/js/script.js"></script>
  <script src="plugins/bootstrap/js/bootstrap-treeview.js"></script>
  <script src="plugins/jstree/js/jstree.js"></script>


  <script>
    $('.select2').select2({
      placeholder: 'Select an option'
    });

    // Helper function untuk menambah zero padding (built-in: String.padStart)
    function addZero(i) {
      // Convert ke number, lalu padStart 2 digit dengan '0'
      return String(i).padStart(2, '0'); // vanilla JS: padStart (ES2017)
    }

    // Fungsi untuk mengubah waktu ke timezone tertentu (built-in: Date, toLocaleString)
    function convertTZ(date, tzString) {
      const dateObj = typeof date === 'string' ? new Date(date) : date;
      return new Date(dateObj.toLocaleString('en-US', {
        timeZone: tzString
      }));
    }

    // Fungsi utama untuk memformat tanggal berdasarkan tipe
    function getDate(type, val) {
      const monthNames = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
      ];

      // Inisialisasi Date berdasarkan val
      let dateObj;
      if (!val || val === '') {
        dateObj = convertTZ(new Date(), 'Asia/Jakarta');
      } else {
        dateObj = new Date(val);
      }

      // Ambil komponen tanggal (built-in: getFullYear, getMonth, getDate, dll)
      const fullYear = dateObj.getFullYear();
      const monthIndex = dateObj.getMonth(); // 0-based
      const day = dateObj.getDate();
      const hours = dateObj.getHours();
      const minutes = dateObj.getMinutes();
      const seconds = dateObj.getSeconds();

      // Gunakan early return (switch langsung return)
      switch (type) {
        case 'FullYear':
          return fullYear;
        case 'Year2Digit':
          // Ambil 2 digit terakhir (built-in: slice)
          return String(fullYear).slice(-2);
        case 'Month':
          return monthNames[monthIndex];
        case 'MonthNumber':
          return addZero(monthIndex + 1);
        case 'Day':
          return addZero(day);
        case 'Date':
          return `${fullYear}-${addZero(monthIndex + 1)}-${addZero(day)}`;
        case 'Hours':
          return addZero(hours);
        case 'Minute':
          return addZero(minutes);
        case 'Second':
          return addZero(seconds);
        case 'FullDate':
          // Format YYYY-MM-DD HH:MM:SS
          return `${fullYear}-${addZero(monthIndex + 1)}-${addZero(day)} ${addZero(hours)}:${addZero(minutes)}:${addZero(seconds)}`;
        case 'FullDateMonthChar':
          // Format YYYY MonthName DD HH:MM:SS
          return `${fullYear} ${monthNames[monthIndex]} ${addZero(day)} ${addZero(hours)}:${addZero(minutes)}:${addZero(seconds)}`;
        default:
          return '';
      }
    }
  </script>
</head>

<body class="hold-transition sidebar-mini sidebar-collapse">
  <div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
      <!-- <img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60"> -->
    </div>

    <!-- navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>

        <li class="nav-item">
          <a class="nav-link" data-widget="fullscreen" href="#" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Sidebar -->
      <div class="sidebar">
        <br>
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image"></div>
          <div class="info">
            <p style="font-size: 30px; color: white" href="#" class="d-block"><?php echo "$username"; ?></p>
            <a onmouseover="showDetail1()" onmouseout="hideDetail()">Detail User</a>

            <div id="detailInfo" class="detail">
              <p style="font-size: 15px; color: white" href="#" class="d-block"> - <?php echo "$key"; ?> <br> - <?php echo "$keterangan"; ?> <br>
            </div>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <?php
            // ========== LOGIKA PHP ==========
            // Ambil data session dengan default kosong untuk menghindari error
            $keynumRc = $_SESSION['doc']['keynum_rc'] ?? null;
            $kodeRc   = $_SESSION['doc']['kode_rc'] ?? '';

            // Daftar role yang diizinkan untuk melihat "Master Category"
            $allowedRoles = [495, 20, 18, 4];
            $showMasterCategory = in_array($keynumRc, $allowedRoles, true);

            // Hitung panjang kode RC setelah menghilangkan trailing zero
            $trimmed = rtrim($kodeRc, '0');
            $rcLength = strlen($trimmed);
            $showPrivilege = ($rcLength < 4);
            ?>

            <!-- ========== HTML ========== -->
            <?php if ($showMasterCategory): ?>
              <li class="nav-item">
                <a href="index.php?mod=category&cmd=index" class="nav-link">
                  <i class="fa fa-download nav-icon" aria-hidden="true"></i>
                  <p>Master Category</p>
                </a>
              </li>
            <?php endif; ?>

            <li class="nav-item">
              <a href="index.php?mod=download&cmd=index" class="nav-link">
                <i class="fa fa-list nav-icon" aria-hidden="true"></i>
                <p>List Document</p>
              </a>
            </li>

            <?php if ($showPrivilege): ?>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-edit"></i>
                  <p>
                    Privilege
                    <i class="fas fa-angle-left right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="index.php?mod=privilege&cmd=index" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>By File</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="index.php?mod=user&cmd=index" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>By User</p>
                    </a>
                  </li>
                </ul>
              </li>
            <?php endif; ?>
            <li class="nav-item">
              <a href="logout.php" class="nav-link">
                <i class="fa fa-sign-out-alt nav-icon" aria-hidden="true"></i>
                <p>Log Out</p>
              </a>
            </li>
          </ul>
        </nav>
      </div>
    </aside>

    <script>
      const detail = document.getElementById("detailInfo");

      function showDetail1() {
        detail.style.display = "block";
      }

      function hideDetail() {
        detail.style.display = "none";
      }
    </script>