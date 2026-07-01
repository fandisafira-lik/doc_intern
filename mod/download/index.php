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

  /* #userlogs_privilege table thead th {
    padding: 3px 5px 3px 5px !important;
} */
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
</style>


<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <!-- <div class="row"> -->
      <!-- <div class="col-sm-6"> -->
      <div class="row text-left">
        <h3 class="col-12 col-lg-3">List Document |
          <!-- <h3 class="col-1 col-sm-1 text-left"> -->
          <a href="#" onclick="openModalUpload()" style="height: 100px;">
            <i class="fas fa-file-upload fa-2xl"></i>
          </a>
          <!-- </h3> -->
          <!-- <h3 class="col-1 col-sm-1 text-left" > -->
          <a href="#" onclick="showSearch()" style="height: 100px;">
            <i class="fas fa-search fa-2xl"></i>
          </a>
        </h3>
        <div id="searchDt" class="col-12 col-lg-6 col-sm-12 text-left" style="visibility: hidden">
          <input type="text" class="form-control form-control-sm" id="searchDatatable" placeholder="Search" />
        </div>
      </div>
      <!-- </div> -->
      <!-- </div> -->

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
              <?php
              // if($_SESSION['doc']['keynum_rc'] == '1' || $_SESSION['doc']['keynum_rc'] == '2'){
              //   $sql = "SELECT du.*, mcd.nama as cat_name, mrc.keterangan as nama_rc from doc_upload du left join m_category_doc mcd on du.jenis_doc = mcd.id LEFT JOIN m_resp_center mrc ON du.rc_penerbit = mrc.keynum_rc";
              //
              // }else{
              //   $rc_induk = $_SESSION['doc']['keynum_rc'];
              //   $sql = "SELECT du.*, mcd.nama as cat_name, mrc.keterangan as nama_rc from doc_upload du left join m_category_doc mcd on du.jenis_doc = mcd.id LEFT JOIN m_resp_center mrc ON du.rc_penerbit = mrc.keynum_rc where view_rc like '%".$_SESSION['doc']['username']."%' OR rc_penerbit = '".$_SESSION['doc']['keynum_rc']."'";
              // }
              $keynum_karyawan = str_pad($_SESSION['doc']['keynum_karyawan'], 8, 0, STR_PAD_LEFT);
              // echo $keynum_karyawan;
              $sql = "SELECT du.*, mcd.nama as cat_name, mrc.keterangan as nama_rc from doc_upload du left join m_category_doc mcd on du.jenis_doc = mcd.id LEFT JOIN m_resp_center mrc ON du.rc_penerbit = mrc.keynum_rc where view_rc like '%" . $keynum_karyawan . "%' OR userid = '" . $_SESSION['doc']['keynum_karyawan'] . "'";
              $stmt = sqlsrv_query($conn, $sql);
              if ($stmt === false) {
                die(print_r(sqlsrv_errors(), true));
              }
              $i = 1;
              while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
              ?>
                <tr>
                  <td style="cursor: hand !important;" class="underlined-blue" onclick="showDetail('<?= $row['id'] ?>','<?= $_SESSION['doc']['keynum_karyawan'] ?>')"><?= $row['cat_name'] ?></td>
                  <td style="cursor: hand !important;" class="underlined-blue" onclick="window.open('<?= $baseurl_file . $row['url'] ?>', '_blank');" class=""><?= $row['description']; ?><span style="visibility: hidden; "><?= date_format($row['expiry_date'], "Y-m-d") ?></span></td>
                  <td style="cursor: hand !important;"><a href="#">
                      <!-- <td class="wrap-150"> -->
                      <div class="row">
                        <div class="col">
                          <a class="underlined-blue" href="<?= $baseurl_file . $row['url'] ?>" download>
                            <!-- <a href="<?= $baseurl_file . $row['url'] ?>" download style="color: #212529 !important"> -->
                            <?= $row['nama_rc'] ?>
                          </a>
                        </div>
                      </div>
                    </a>
                  </td>
                  <td style="display: none"><?= str_replace("^", " ", $row['tags']); ?></td>
                </tr>
              <?php }
              sqlsrv_free_stmt($stmt);  ?>
            </tbody>
          </table>
        </div>
        <div class="col-12 col-lg-4 overflow-auto" style="display:none;" id="details">
          <!-- <h3><i class="fas fa-long-arrow-left"></i></h3> -->
          <div class="container">
            <div class="row">
              <!-- <a href="#" class="col-lg-2"> -->
              <h5 class="col">
                <a href="#" onclick="showDetail('0','<?= $_SESSION['doc']['keynum_karyawan'] ?>')"><i class="fas fa-arrow-left"></i></a>
              </h5>
              <input disabled style="display: none;" id="id_doc" />
              <input disabled style="display: none;" id="url_detail" />
              <!-- </a> -->
              <!-- <a href="#" class="col-6 text-right"> -->
              <div class="col text-right">
                <h5>
                  <a href="#" onclick="update()" id="saveS" style="visibility: hidden"><i class="far fa-check-circle"></i></a>
                  <a href="#" onclick="confDel()" id="delS" style="visibility: hidden"><i class="fas fa-trash"></i></a>
                </h5>
              </div>
              <!-- </a> -->
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
                <!-- <td id="description_detail"></td> -->
                <td>
                  <textarea class="form-control f-14" id="description_detail"></textarea>
                </td>
              </tr>
              <tr>
                <td>Category File </td>
                <td> : </td>
                <!-- <td id="category_detail"></td> -->
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
                <!-- <td id="tags_detail"></td> -->
                <td>
                  <!-- <input class="form-control" id="tags_detail" /> -->
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
    </div>

  </section>
  <div id="alerts"></div>


</div>


<div class="modal fade" id="uploadModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">

        <div class="">
          <select id="jenis_doc" class="select2">
            <option selected disabled>-- Select Category --</option>
            <?php
            $qulog1 = "SELECT * FROM m_category_doc";
            // echo $qulog;
            $res1 = sqlsrv_query($conn, $qulog1);
            while ($row1 = sqlsrv_fetch_array($res1, SQLSRV_FETCH_ASSOC)) {
            ?>
              <option value="<?= $row1['id'] ?>"><?= $row1['nama'] ?> - <?php if ($row1['access'] == '1') {
                                                                      echo 'Specific';
                                                                    } else {
                                                                      echo 'All';
                                                                    } ?></option>option>
            <?php
            }
            ?>
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




<script type="text/javascript">
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
  });

  function showDetail(id, user) {
    if (id == "0") {

    } else {
      var data = {
        "id": id
      };
      $.ajax({
        url: 'mod/download/GetData.php',
        type: 'post',
        data: data,
        dataType: 'json',
        success: function(response) {
          if (response.status == "00") {

            $.ajax({
              url: 'mod/download/GetDataAllCategory.php',
              type: 'post',
              data: data,
              dataType: 'json',
              success: function(response1) {
                if (response1.status == "00") {
                  var cate = '';
                  $('#category_detail').html('');
                  for (var i = 0; i < response1.msg.length; i++) {
                    if (response1.msg[i]['id'] == response.msg['jenis_doc']) {
                      cate = '<option value=' + response1.msg[i]['id'] + ' selected>' + response1.msg[i]['nama'] + ' - ' + response1.msg[i]['access'] + '</option>';
                    } else {
                      cate = '<option value=' + response1.msg[i]['id'] + '>' + response1.msg[i]['nama'] + ' - ' + response1.msg[i]['access'] + '</option>';
                    }
                    $('#category_detail').append(cate);
                  }
                } else {
                  alert("No Data!");
                  // $('#uploadModal').modal('hide');
                }
                // location.reload();
              }
            });

            $('#id_doc').val(id);
            $('#filename_detail').html(response.msg['nama']);
            $('#url_detail').val(response.msg['url']);
            $('#description_detail').val(response.msg['description']);
            $('#no_transaksi_detail').html(response.msg['no_transaksi']);
            $('#category_detail').html(response.msg['category']);
            $('#uploader_detail').html(response.msg['userid']);
            $('#date_detail').html(getDate("Date", response.msg['date_uploaded']['date']));
            if (response.msg['expiry_date'] == null || response.msg['expiry_date'] == '') {
              $('#expiry_date').val("");
            } else {
              $('#expiry_date').val(getDate("Date", response.msg['expiry_date']['date']));
            }
            $('#rc_uploader_detail').html(response.msg['keterangan']);
            $('#tags_detail').html('');
            if (response.msg['tags'] == null || response.msg['tags'] == '') {

            } else {
              var tags = response.msg['tags'].split("^");
              // console.log(tags);
              var tag = "";
              for (var i = 0; i < tags.length; i++) {
                tag = '<option value=' + tags[i] + ' selected>' + tags[i] + '</option>';
                // tag += tags[i];
                // if(i!=(tags.length-1)){
                //   tag += " | ";
                // }
                $('#tags_detail').append(tag);
              }
            }
            var tbodyRef = document.getElementById('userlogs_privilege');
            // var tbodyRef = document.getElementById('userlogs_privilege').getElementsByTagName('tbody')[0];
            if (response.msg['detail'] == null) {
              $('#userlogs_privilege tbody').empty();
            } else {
              $('#userlogs_privilege tbody').empty();
              // var privilege = response.msg['detail'].split("^");
              for (var i = 0; i < response.msg['detail'].length; i++) {
                $('#userlogs_privilege').append('<tr><td>' + response.msg['detail'][i]['first_name'] + ' - ' + response.msg['detail'][i]['position_name_id'] + '</td></tr>');
              }
            }

            if (user == response.msg['userid']) {
              document.getElementById('delS').style.visibility = 'visible';
              document.getElementById('saveS').style.visibility = 'visible';
              document.getElementById('category_detail').disabled = false;
              document.getElementById('description_detail').disabled = false;
              document.getElementById('tags_detail').disabled = false;
            } else {
              document.getElementById('delS').style.visibility = 'hidden';
              document.getElementById('saveS').style.visibility = 'hidden';
              document.getElementById('category_detail').disabled = true;
              document.getElementById('description_detail').disabled = true;
              document.getElementById('tags_detail').disabled = true;

            }

            // alert("Upload "+response.name+ " Success!");
            // $('#uploadModal').modal('hide');
          } else {
            alert("No Data!");
            // $('#uploadModal').modal('hide');
          }
          // location.reload();
        }
      });
    }
    if ($(window).width() < 1100) { // if width is less than 600px
      // MobileFunctions();                 // execute mobile function
      if (document.getElementById("details").style.display === "none") {
        $('#all_data').fadeOut();
        $('#details').fadeIn();

      } else {
        $('#details').fadeOut();
        $('#all_data').fadeIn();
      }
      // document.getElementById("details").style.display = "flex";

    } else {
      // DesktopFunctions()
      if (id == "0") {
        $('#details').fadeOut();
      } else {
        $('#details').fadeIn();
      }
    }
  }
  // $("#sample_data").DataTable();
  function openModalUpload() {
    $('#uploadModal').modal('show');
  }

  function update() {
    var tags = $('#tags_detail').val();
    var tag = "";
    if (tags.length > 0) {
      for (var i = 0; i < tags.length; i++) {
        tag += tags[i];
        if (i != (tags.length - 1)) {
          tag += "^";
        }
      }
    }
    var expired = '';
    if ($('#expiry_date').val() == '') {
      expired = '';
    } else {
      expired = $('#expiry_date').val();
    }
    var data = {
      "description": $('#description_detail').val(),
      "expiry_date": expired,
      "jenis_doc": $('#category_detail').val(),
      "tags": tag
    }

    $.ajax({
      url: 'mod/download/UpdateData.php?id=' + $('#id_doc').val() + '&mod=doc_upload',
      type: 'post',
      data: data,
      dataType: 'json',
      success: function(response1) {
        if (response1.status == "00") {
          alert("Document Updated!")
        } else {
          alert("Document Update Fail!")
        }
        location.reload();
      }
    });
  }

  function showSearch() {
    // console.log("aaa");
    // $("#searchDt").show();
    var stat = document.getElementById("searchDt");
    // console.log(stat);

    if (stat.style.visibility == "hidden") {
      stat.style.visibility = "visible";
    } else {
      stat.style.visibility = "hidden";
    }
    // $("#searchDatatable").addClass("fade-in");
  }

  $(document).ready(function() {

    $("#but_upload").click(function() {
      var fd = new FormData();
      var files = $('#fileToUpload')[0].files;
      var jd = $('#jenis_doc').val();
      var description = $('#rename').val();
      var tags = $('#tags').val();
      var tag = "";
      if (tags.length > 0) {
        for (var i = 0; i < tags.length; i++) {
          tag += tags[i];
          if (i != (tags.length - 1)) {
            tag += "^";
          }
        }
      }
      // console.log(tag);
      if (jd == '' || jd == null) {
        alert("Document Type Can not be Empty!");
      } else if (description == '' || description == null) {
        alert("Description Can not be Empty!");
      } else {
        if (files.length > 0) {
          fd.append('file', files[0]);
          fd.append('jenis_doc', jd);
          fd.append('description', description);
          fd.append('tags', tag);
          fd.append('expiry_date', $('#expired_date').val());
          $.ajax({
            url: 'mod/download/upload.php',
            type: 'post',
            data: fd,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function(response) {
              if (response.status == 1) {
                alert("Upload " + response.name + " Success!");
                $('#uploadModal').modal('hide');
              } else {
                alert('File upload Fail!');
                $('#uploadModal').modal('hide');
              }
              location.reload();
            }
          });
        } else {
          alert("Please select a file.");
        }
        $('#fileToUpload').val('');
      }
    });
  });

  function confDel() {
    let text;
    if (confirm("Are you sure to delete file " + $('#filename_detail').html() + " ?") == true) {
      var data = {
        "id": $('#id_doc').val(),
        "url": $('#url_detail').val()
      };
      $.ajax({
        url: 'mod/download/deleteFile.php', // url where to submit the request
        type: "POST", // type of action POST || GET
        data: data, // post data || get data
        dataType: 'json', // data type
        success: function(result) {
          alert("Document Deleted Successfully!");
          location.reload();
        },
        error: function(xhr, resp, text) {
          console.log(xhr, resp, text);
        }
      });
    } else {
      text = "You canceled!";
    }
  }
  // $("#all_category").select2({ dropdownCssClass: "dropdownFont" });
  var table = $('#sample_data').DataTable({
    "dom": 'rtip',
    "paging": false,
    "info": false,
    initComplete: function() {
      this.api().columns([0]).every(function() {
        var column = this;
        var div = $('<span class="" style="line-height: 150% !important; margin-top: 50px !important;"> </span>')
          // var div = $('<div style="line-height=0.5;">&nbsp;</div>')
          .appendTo($(column.header()));
        var select = $('<select class="form-control form-control-sm select2" style="margin-top: 50px !important;" id="all_category"><option selected value="">-- All --</option></select>')
          .appendTo($(column.header()))
          .on('change', function() {
            var val = $.fn.dataTable.util.escapeRegex(
              $(this).val()
            );

            column
              // .search( val ? '^'+val+'$' : '', true, false )
              .search(this.value, true, false)
              .draw();
          });

        column.data().unique().sort().each(function(d, j) {
          // console.log(d+"  "+j);
          select.append('<option value=' + d + '>' + d + '</option>');
        });
      });
      // $("#all_category").select2({ dropdownCssClass: "dropdownFont" });
      this.api().columns([1]).every(function() {
        var column = this;
        // var title = column.text();
        var text = $('<input type="text" class="mt-2 form-control form-control-sm" placeholder="Search Description" />')
          .appendTo($(column.header()))
          .on('keyup change', function() {
            var val = $.fn.dataTable.util.escapeRegex(
              $(this).val()
            );

            column
              .search(this.value, true, false)
              .draw();
          });

      });
      this.api().columns([2]).every(function() {
        var column = this;
        // var title = column.text();
        var text = $('<input type="text" class="mt-2 form-control form-control-sm" placeholder="Search RC" />')
          .appendTo($(column.header()))
          .on('keyup change', function() {
            var val = $.fn.dataTable.util.escapeRegex(
              $(this).val()
            );

            column
              .search(this.value, true, false)
              .draw();
          });

      });
      // this.api().columns([1]).every( function () {
      //     // var title = this.text();
      //       this.append('<input type="text" class="col mt-2" placeholder="Search Description" />');
      //
      // });
    }
  });
  $('#searchDatatable').keyup(function() {
    table.search($(this).val()).draw();
  });
  // $('#sample_data thead th').columns([0])(function () {
  //     // var title = $(this).text();
  //     $(this).append('<select><option selected disabled>-- Category --</option></select>');
  // });
  // $('#sample_data thead th').each(function () {
  //     var title = $(this).text();
  //     $(this).append('<input type="text" class="col mt-2" placeholder="Search ' + title + '" />');
  // });
  // table.columns().eq(0).each( function ( colIdx ) {
  //     if(colIdx == 0){
  //     }else{
  //       $( 'input', table.column( colIdx ).header() ).on( 'keyup change', function () {
  //         table
  //         .column( colIdx )
  //         .search( this.value, true, false )
  //         .draw();
  //       } );
  //       $('input', table.column(colIdx).header()).on('click', function(e) {
  //         e.stopPropagation();
  //       });
  //
  //     }
  // } );
</script>