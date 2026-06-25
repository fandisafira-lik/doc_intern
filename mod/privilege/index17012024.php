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

.f-14{
  font-size:14px !important;

}

#saved_user table tbody th{
  border-bottom: dotted 2px black;

}
</style>


<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <!-- <div class="row"> -->
        <!-- <div class="col-sm-6"> -->
          <div class="row text-left">
            <h3 class="col-12 col-lg-3">Privilege |
            <!-- <h3 class="col-1 col-sm-1 text-left"> -->
              <!-- <a href="#" onclick="openModalUpload()" style="height: 100px;">
                <i class="fas fa-plus-circle"></i>
              </a> -->
            <!-- </h3> -->
            <!-- <h3 class="col-1 col-sm-1 text-left" > -->
              <a href="#" onclick="showSearch()" style="height: 100px;">
                <i class="fas fa-search fa-2xl"></i>
              </a>
            </h3>
            <div id="searchDt" class="col-12 col-lg-6 col-sm-12 text-left" style="visibility: hidden">
              <input type="text" class="form-control form-control-sm" id="searchDatatable" />
            </div>
          </div>
        <!-- </div> -->
      <!-- </div> -->

      <div class="row bg-white py-2">
        <div class="table-responsive overflow-auto col" style="height: 70vh;" id="all_data">
          <table id="sample_data" class="hover" style="width: 100%;">
            <thead>
            <tr>
              <th style="padding: 0px !important;width: 10% !important; line-height: 25px !important; vertical-align: text-top !important;" class="">Category</th>
              <th style="width: 40% !important;">File Description</th>
              <th>RC Uploader</th>
            </tr>
            </thead>
            <tbody>
              <?php
              if($_SESSION['doc']['kode_rc'] == '0100000000' || $_SESSION['doc']['kode_rc'] == '0000000000'){
                $sql = "SELECT du.*, mcd.nama as cat_name, mrc.keterangan as nama_rc from doc_upload du left join m_category_doc mcd on du.jenis_doc = mcd.id LEFT JOIN m_resp_center mrc ON du.rc_penerbit = mrc.keynum_rc";

              }else{
                $rc_induk = rtrim($_SESSION['doc']['kode_rc'],"0");
                $sql = "SELECT du.*, mcd.nama as cat_name, mrc.keterangan as nama_rc, mrc.kode_rc from doc_upload du left join m_category_doc mcd on du.jenis_doc = mcd.id LEFT JOIN m_resp_center mrc ON du.rc_penerbit = mrc.keynum_rc where mrc.kode_rc like '".$rc_induk."%'";
              }
              $stmt = sqlsrv_query( $conn, $sql );
              if( $stmt === false) {
                die( print_r( sqlsrv_errors(), true) );
              }
              $i=1;
              while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                ?>
                <tr>
                  <td onclick=""><?=$row['cat_name']?></td>
                  <td class="underlined-blue" onclick="showDetail('<?=$row['id']?>')"><?=$row['description']?></td>
                  <td>
                    <div class="row">
                      <div class="col-7">
                        <?=$row['nama_rc']?>
                      </div>
                      <div class="col-5 text-right">
                        <?php
                          if(empty($row['view_rc'])){
                        ?>
                        <label for="rename" class="form-label" style="color: red !important; font-size: 11px !important;"><i class="fas fa-star"></i></label>
                        <?php
                          }
                        ?>
                      </div>
                    </div>
                  </td>
                </tr>
              <?php }
              sqlsrv_free_stmt($stmt);  ?>
            </tbody>
          </table>
        </div>
        <div class="col-12 col-lg-4 overflow-auto" style="display:none;max-height: 40vh;" id="details">
          <!-- <h3><i class="fas fa-long-arrow-left"></i></h3> -->
          <div class="container">
            <div class="row">
              <!-- <a href="#" class="col-lg-2"> -->
                <h5 class="col">
                  <a href="#" onclick="showDetail('0')"><i class="fas fa-arrow-left"></i></a>
                </h5>
                <input disabled style="display: none;" id="id_doc" />
              <!-- </a> -->
              <!-- <a href="#" class="col-6 text-right"> -->
                <h5 class="col text-right">
                  <a href="#" onclick="update()"><i class="far fa-check-circle"></i></a>
                </h5>
              <!-- </a> -->
            </div>
            <!-- <div class="row"> -->
              <select id="view_rc" class="js-example-basic-multiple form-control f-14" name="view_rc[]" multiple="multiple">
                <?php
                  $query = "SELECT mru.*, mk.nama_pegawai, mrc.kode_rc, mrc.keterangan from m_respctr_userlog mru left join m_karyawan mk on mru.keynum_karyawan = mk.keynum_karyawan left join m_resp_center mrc on mru.keynum_rc = mrc.keynum_rc ORDER BY kode_rc ASC";
                  $res=sqlsrv_query($conn, $query);
                  while($row = sqlsrv_fetch_array( $res, SQLSRV_FETCH_ASSOC)){
                ?>
                <option value="<?=$row['userlog'];?>"><?=$row['nama_pegawai']." - ".$row['keterangan']?></option>

                <?php } ?>
              </select>

              <!-- <table id="saved_user" class="table-striped f-14 mt-4" style="">
                <tbody>
                </tbody>
              </table> -->

            <!-- </div> -->
          </div>
        </div>

      </div>
    </div>

  </section>
  <div id="alerts"></div>


</div>

<script type="text/javascript">

// $(document).ready(function() {
//   $('#jstree_demo_div').jstree();
// });
  $(document).ready(function() {
    $('#view_rc').select2({
      closeOnSelect: false,
      placeholder: "Please Select users"
    });

    $('#view_rc').on('select2:select', function (e) {
      var data = e.params.data.id;
    // console.log(data);
    // console.log('select event');
});
  });

  function showDetail(id){
    if(id == "0"){

    }else{
      // var data = {
      //   "id" : id
      // };
      $('#id_doc').val(id);
      // GetPrivilege(id);
      $.ajax({
           url:'mod/privilege/GetDetail.php?id='+id,
           type:'get',
           dataType: 'json',
           success:function(response){
              if(response.status == "00"){
                if(response.msg['view_rc'] != null){
                  // $('#view_rc').html('');
                  // var tags = response.msg['view_rc'].split("^");
                  // console.log(tags);
                  // var tag = "";
                  // for(var i = 0; i < tags.length; i++){
                  //   tag += tags[i];
                  //   if(i!=(tags.length-1)){
                  //     tag += " | ";
                  //   }
                  // }
                  // console.log(response.msg['view_rc'])
                  $('#view_rc').val(response.msg['view_rc']);

                  $('#view_rc').trigger('change');
                  //
                  // if(response.msg['view_rc'] == null){
                  //   $('#saved_user tbody').empty();
                  // }else{
                  //   // var saved = ;
                  //   $('#saved_user tbody').empty();
                  //   for(var i = 0; i < response.msg['saved'].length; i++){
                  //     $('#saved_user').append('<tr><td>'+response.msg['saved'][i]+'</td></tr>');
                  //   }
                  // }
                }else{
                  $('#view_rc').val(null);
                  $('#view_rc').trigger('change');

                }
              }else{
                alert("No Data!");
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

  function update(){
    var tags = $('#view_rc').val();
    var tag ="";
    if(tags.length > 0){
      for(var i=0;i<tags.length;i++){
        tag += tags[i];
          if(i!=(tags.length-1)){
            tag += "^";
          }
      }
    }
    var data = {
      // "description" : $('#description_detail').val(),
      // "jenis_doc" : $('#category_detail').val(),
      "view_rc" : tag
    }
    $.ajax({
         url:'mod/download/UpdateData.php?id='+$('#id_doc').val()+'&mod=doc_upload',
         type:'post',
         data:data,
         dataType: 'json',
         success:function(response1){
              if(response1.status == "00"){
                alert("Document Updated!")
              }else{
                alert("Document Update Fail!")
              }
              location.reload();
         }
    });
  }
  // $("#sample_data").DataTable();
  function openModalUpload(){
    $('#uploadModal').modal('show');
  }

  function openViewModal(url){
    $('#bd').src(url);
    $('#viewModal').modal('show');
  }

  function showSearch(){
    console.log("aaa");
    // $("#searchDt").show();
    var stat = document.getElementById("searchDt");
    // console.log(stat);

    if(stat.style.visibility == "hidden"){
      stat.style.visibility = "visible";
    }else {
      stat.style.visibility = "hidden";
    }
    // $("#searchDatatable").addClass("fade-in");
  }

  $(document).ready(function(){

     $("#but_upload").click(function(){
          var fd = new FormData();
          var files = $('#fileToUpload')[0].files;
          var jd = $('#jenis_doc').val();
          var description = $('#rename').val();
          var tags = $('#tags').val();
          var tag ="";
          if(tags.length > 0){
            for(var i=0;i<tags.length;i++){
              tag += tags[i];
                if(i!=(tags.length-1)){
                  tag += "^";
                }
            }
          }
          // console.log(tag);
          if(jd == '' || jd == null){
            alert("Document Type Can not be Empty!");
          }else if(description == '' || description == null){
            alert("Description Can not be Empty!");
          }else{
            if(files.length > 0 ){
                 fd.append('file',files[0]);
                 fd.append('jenis_doc', jd);
                 fd.append('description', description);
                 fd.append('tags', tag);
                 $.ajax({
                      url:'mod/download/upload.php',
                      type:'post',
                      data:fd,
                      dataType: 'json',
                      contentType: false,
                      processData: false,
                      success:function(response){
                           if(response.status == 1){
                                alert("Upload "+response.name+ " Success!");
                                $('#uploadModal').modal('hide');
                           }else{
                                alert('File upload Fail!');
                                $('#uploadModal').modal('hide');
                           }
                           // location.reload();
                      }
                 });

            }else{
                 alert("Please select a file.");
            }
            $('#fileToUpload').val('');
          }
       });
  });

  function confDel(id, filename, url){
    let text;
    if (confirm("Are you sure to delete file "+filename+" ?") == true) {
      var data = {
        "id" : id,
        "filename" : filename,
        "url" : url
      };
      $.ajax({
        url: 'mod/download/deleteFile.php', // url where to submit the request
        type : "POST", // type of action POST || GET
        data : data, // post data || get data
        dataType : 'json', // data type
        success : function(result) {
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
    initComplete: function () {
        this.api().columns([0]).every( function () {
            var column = this;
            var div = $('<span class="" style="line-height: 150% !important; margin-top: 50px !important;"> </span>')
            // var div = $('<div style="line-height=0.5;">&nbsp;</div>')
                .appendTo( $(column.header()) );
            var select = $('<select class="form-control form-control-sm select2" style="margin-top: 50px !important;" id="all_category"><option selected value="">-- All --</option></select>')
                .appendTo( $(column.header()) )
                .on( 'change', function () {
                    var val = $.fn.dataTable.util.escapeRegex(
                        $(this).val()
                    );

                    column
                        .search( val ? '^'+val+'$' : '', true, false )
                        .draw();
                } );

            column.data().unique().sort().each( function ( d, j ) {
                select.append( '<option value="'+d+'">'+d+'</option>' )
            } );
        } );
        $("#all_category").select2({ dropdownCssClass: "dropdownFont" });
        this.api().columns([1]).every( function () {
            var column = this;
            // var title = column.text();
            var text = $('<input type="text" class="mt-2 form-control form-control-sm" placeholder="Search Description" />')
                .appendTo( $(column.header()) )
                .on( 'keyup change', function () {
                    var val = $.fn.dataTable.util.escapeRegex(
                        $(this).val()
                    );

                    column
                        .search( this.value, true, false )
                        .draw();
                } );

        } );
        this.api().columns([2]).every( function () {
            var column = this;
            // var title = column.text();
            var text = $('<input type="text" class="mt-2 form-control form-control-sm" placeholder="Search RC" />')
                .appendTo( $(column.header()) )
                .on( 'keyup change', function () {
                    var val = $.fn.dataTable.util.escapeRegex(
                        $(this).val()
                    );

                    column
                        .search( this.value, true, false )
                        .draw();
                } );

        } );
    }
  });
  $('#searchDatatable').keyup(function(){
      table.search($(this).val()).draw() ;
  });

  function GetPrivilege(id){
    $.ajax({
         url:'mod/privilege/GetDetail.php?id='+id,
         type:'GET',
         // data:data,
         dataType: 'json',
         success:function(response){
              if(response.status == "00"){
                var tags = response.msg['tags'].split("^");
                var tag = "";
                $('#view_rc').html('');
                for(var i = 0; i < tags.length; i++){
                  tag = '<option value='+tags[i]+' selected>'+tags[i]+'</option>';
                  $('#view_rc').append(tag);
                }
              }else{
                alert("No Data!");
              }
         }
    });
  }

  function getList(selected) {
    console.log(selected);
    var html = '<ul>';
    $(selected).each(function() {
      //console.log(this);
    });
    html += '</ul>';
  }
</script>
