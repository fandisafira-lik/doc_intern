<style type="text/css">
  #check-all {
  transform: scale(1.5); /* Mengubah skala checkbox */
  margin-right: 10px; /* Memberikan jarak antara checkbox dan label */
}

#check-all-label {
  font-size: 14px; /* Mengatur ukuran label */
}
#sample_data{
  font-size: 14px !important;
}
.dataTables_length, .dataTables_length select{ font-size: 14px;}
.dataTables_filter, .dataTables_filter label{ font-size: 14px !important;}
.dataTables_info { font-size: 14px !important;}
.dataTables_paginate, .dataTables_paginate a{ font-size: 14px !important;}

table.dataTable tbody th, table.dataTable tbody td {
    padding: 3px 5px 3px 5px !important;
    vertical-align: top;
}
table.dataTable thead th {
    padding: 3px 5px 3px 5px !important;
}
#userlogs_privilege table tbody th, table tbody td {
    padding: 3px 5px 3px 5px !important;
    vertical-align: top;
    font-size: 14px !important;
}
#tab_detail {
  line-height: 1.5;
}
#tab_detail td{
  font-size: 12px;
}
#all_category .select2 option{
  font-size:4px !important;
}
#tags .select2-no-results {
    display: none !important;
}
.wrap-150{
  display: block;
  width: 150px;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}

.f-14{
  font-size:14px !important;

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
            <h3 class="col-12 col-lg-3">Privilege By User |
            <!-- <h3 class="col-1 col-sm-1 text-left"> -->
              <!-- <a href="#" onclick="openModalUpload()" style="height: 100px;">
                <i class="fas fa-file-upload fa-2xl"></i>
              </a> -->
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
              <!-- <th style="padding: 0px !important;width: 10% !important; line-height: 25px !important; vertical-align: text-top !important;" class="">Category/Detail</th> -->
              <th style="width: 40% !important;">Nama Karyawan / File List</th>
              <th>RC</th>
              <!-- <th style="display: none;">tags</th> -->
            </tr>
            </thead>
            <tbody>
              <?php
                $rc_induk = $_SESSION['doc']['keynum_rc'];
                $sql = "SELECT mk.*, mru.userlog, mrc.keterangan from m_respctr_userlog mru left join m_karyawan mk on mru.keynum_karyawan = mk.keynum_karyawan left join m_resp_center mrc on mru.keynum_rc = mrc.keynum_rc";
              $stmt = sqlsrv_query( $conn, $sql );
              if( $stmt === false) {
                die( print_r( sqlsrv_errors(), true) );
              }
              $i=1;
              while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $qu = "SELECT * from doc_upload where view_rc like '%".$row['userlog']."%'";
                $stmt2 = sqlsrv_query( $conn, $qu );
                $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC);
                if(empty($row2)){

                }else{
                ?>
                <tr>
                  <td class="underlined-blue" onclick="showDetail('<?=$row['id']?>','<?=$row['userlog']?>')"><?php if(is_null($row['nama_pegawai'])){echo $row['userlog'];}else{ echo $row['nama_pegawai'];}?></td>
                  <td class=""><?=strtoupper($row['keterangan'])?></td>
                  <!-- <td style="display: none"><?=str_replace("^"," ",$row['tags']);?></td> -->
                </tr>
              <?php }}
              sqlsrv_free_stmt($stmt2);
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
                  <a href="#" onclick="showDetail('0','<?=$_SESSION['doc']['username']?>')"><i class="fas fa-arrow-left"></i></a>
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

            <div class="text-center mt-1">
              <p>Privilege File</p>
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
            $qulog1="SELECT * FROM m_category_doc";
            // echo $qulog;
            $res1=sqlsrv_query($conn, $qulog1);
            while($row1 = sqlsrv_fetch_array( $res1, SQLSRV_FETCH_ASSOC)){
              ?>
              <option value="<?=$row1['id']?>"><?=$row1['nama']?></option>
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
});

  function showDetail(id, user){
    if(id == "0"){

    }else{
      $.ajax({
           url:'mod/user/GetData.php?userlog='+user,
           type:'get',
           dataType: 'json',
           success:function(response){
                if(response.status == "00"){
                  var tbodyRef = document.getElementById('userlogs_privilege');
                  // var tbodyRef = document.getElementById('userlogs_privilege').getElementsByTagName('tbody')[0];
                  if(response.msg[0]['view_rc'] == null){
                    $('#userlogs_privilege tbody').empty();
                  }else{
                    // var privilege = response.msg['view_rc'].split("^");
                    $('#userlogs_privilege tbody').empty();
                    for(var i = 0; i < response.msg.length; i++){
                      $('#userlogs_privilege').append('<tr><td><a href="mod/download/'+response.msg[i]['url']+'" target="_blank">'+response.msg[i]['description']+'</a></td></tr>');
                    }
                  }
                }else{
                  // alert("No Data!");
                  $('#userlogs_privilege tbody').empty();
                }
           }
      });
    }
    if ($(window).width() < 1100) {       // if width is less than 600px
       // MobileFunctions();                 // execute mobile function
       if(document.getElementById("details").style.display === "none"){
         $('#all_data').fadeOut();
         $('#details').fadeIn();

       }else{
         $('#details').fadeOut();
         $('#all_data').fadeIn();
       }
       // document.getElementById("details").style.display = "flex";

    }
    else {
       // DesktopFunctions()
       if(id == "0"){
         $('#details').fadeOut();
       }else{
         $('#details').fadeIn();
       }
    }
  }

  function showSearch(){
    // $("#searchDt").show();
    var stat = document.getElementById("searchDt");
    // console.log(stat);

    if(stat.style.visibility == "hidden"){
      stat.style.visibility = "visible";
    }else {
      stat.style.visibility = "hidden";
    }
  }
  var table = $('#sample_data').DataTable({
    "dom": 'rtip',
    "paging": false,
    "info": false
  });
  $('#searchDatatable').keyup(function(){
      table.search($(this).val()).draw() ;
  });

</script>
