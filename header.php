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
  <title>PT. LIK | DOCUMENT Management</title>
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
  <link href="https://rawgit.com/jonmiles/bootstrap-treeview/master/dist/bootstrap-treeview.min.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="plugins/jstree/css/style.css" />

  <style>
    .detail {
      display: none;
    }


  .disabled {
    pointer-events: none; /* Mencegah interaksi pointer */
    cursor: default; /* Mengubah kursor menjadi default */
    opacity: 0.6; /* Memberikan tampilan elemen yang dinonaktifkan */
  }

  .underlined-blue{
    color: #1f2d3d !important;
  }

  .underlined-blue:hover{
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
<script src="https://rawgit.com/jonmiles/bootstrap-treeview/master/public/js/bootstrap-treeview.js"></script>
<script src="plugins/jstree/js/jstree.js"></script>


<script>
  $('.select2').select2({
    placeholder: 'Select an option'
  });

  function getDate(type, val){
    const month1 = ["January","February","March","April","May","June","July","August","September","October","November","December"];
    // alert(val);
    if(val == ""){
      var d = new Date();
      convertTZ(d, "Asia/Jakarta");
    }else{
      var d= new Date(val);
    }

    let year = d.getFullYear().toString().substr(-2);
    let month = (d.getMonth()+1).toString();
    let day = d.getDate().toString();
    let hour = d.getHours().toString();
    let minute = d.getMinutes().toString();
    let second = d.getSeconds().toString();

    switch(type) {
      case "FullYear" :
        return d.getFullYear();
        break;
      case "Year2Digit" :
        return year;
        break;
      case "Month" :
        return month1[d.getMonth()];
        break;
      case "MonthNumber" :
        return month;
        break;
      case "Day" :
        return addZero(day);
        break;
      case "Date" :
        return d.getFullYear() + "-" + addZero(month) + "-" + addZero(day);
      case "Hours" :
        return addZero(hour);
        break;
      case "Minute" :
        return addZero(minute);
        break;
      case "Second" :
        return addZero(second);
        break;
      case "FullDate" :
        //return d.getFullYear() + "-" + "08" + "-" + addZero(day) + " " + addZero(hour) + ":" + addZero(minute) + ":" + addZero(second);
        return d.getFullYear() + "-" + addZero(month) + "-" + addZero(day) + " " + addZero(hour) + ":" + addZero(minute) + ":" + addZero(second);

        break;
      case "FullDateMonthChar" :
        //return d.getFullYear() + "-" + "08" + "-" + addZero(day) + " " + addZero(hour) + ":" + addZero(minute) + ":" + addZero(second);
        return d.getFullYear() + " " + month1[d.getMonth()] + " " + addZero(day) + " " + addZero(hour) + ":" + addZero(minute) + ":" + addZero(second);
        // return d.getFullYear() + " " + month1[d.getMonth()].substring(0,4) + " " + addZero(day) + " " + addZero(hour) + ":" + addZero(minute) + ":" + addZero(second);

        break;
      default :
        return "";
        break;
    }

  }
  function addZero(i) {
    if (i < 10) {i = "0" + i}
    return i;
  }
  function convertTZ(date, tzString) {
    return new Date((typeof date === "string" ? new Date(date) : date).toLocaleString("en-US", {timeZone: tzString}));
  }
</script>
</head>
<body class="hold-transition sidebar-mini sidebar-collapse">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <!-- <img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60"> -->
  </div>

  <nav class="main-header navbar navbar-expand navbar-white navbar-light">

    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item">
        <li class="nav-item">
          <a class="nav-link" data-widget="fullscreen" href="#" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
          </a>
      </li>
    </ul>




    <!-- <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <li class="nav-item">
          <img src="Logo.png" width="50%">
        </li>
      </ul> -->
    <!-- <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <li class="nav-item">
          <a class="btn btn-danger" href="logout.php">Logout</a>
        </li>
      </ul> -->
    </nav>

  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">


    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <br>
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <!-- <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image"> -->
        </div>
        <div class="info">
          <!-- <h2 style="background-color: red">User Info</h2> -->
          <p style="font-size: 30px; color: white" href="#" class="d-block"><?php echo "$username"; ?></p>
          <a onmouseover="showDetail1()" onmouseout="hideDetail()">Detail User</a>


          <div id="detailInfo" class="detail">
            <p style="font-size: 15px; color: white" href="#" class="d-block"> - <?php echo "$key"; ?> <br> - <?php echo "$keterangan"; ?> <br> <!-- -  Lokasi <?php //echo "$site"; ?> </p> -->
          </div>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <!-- <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div> -->

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <?php
              if($_SESSION['doc']['keynum_rc'] == 495 || $_SESSION['doc']['keynum_rc'] == 20 || $_SESSION['doc']['keynum_rc'] == 18 || $_SESSION['doc']['keynum_rc'] == 4){
            ?>
              <li class="nav-item">
                <a href="index.php?mod=category&cmd=index" class="nav-link">
                  <i class="fa fa-download nav-icon" aria-hidden="true"></i>
                  <p>Master Category</p>
                </a>
              </li>
            <?php } ?>
              <li class="nav-item">
                <a href="index.php?mod=download&cmd=index" class="nav-link">
                  <i class="fa fa-list nav-icon" aria-hidden="true"></i>
                  <p>List Document</p>
                </a>
              </li>
              <?php
                $trim = rtrim($_SESSION['doc']['kode_rc'],"0");
                $rc = strlen($trim);
                if($rc < 4){


              ?>
              <li class="nav-item">
                <!-- <a href="index.php?mod=privilege&cmd=index" class="nav-link">
                  <i class="far fa-star nav-icon" aria-hidden="true"></i>
                  <p>Privilege</p>
                </a> -->
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
            <?php } ?>
              <li class="nav-item">
                <a href="logout.php" class="nav-link">
                  <i class="fa fa-sign-out-alt nav-icon" aria-hidden="true"></i>
                  <p>Log Out</p>
                </a>
              </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <script>
    var detail = document.getElementById("detailInfo");

    function showDetail1() {
      detail.style.display = "block";
    }

    function hideDetail() {
      detail.style.display = "none";
    }
  </script>
