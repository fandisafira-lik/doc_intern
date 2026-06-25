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
/* #userlogs_privilege table thead th {
    padding: 3px 5px 3px 5px !important;
} */
a{
  color: #1f2d3d;
}
a:hover{
  color: #1f2d3d;
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
      <div class="row text-left">
        <h3 class="col-12 col-lg-3">Category |
        <!-- <h3 class="col-1 col-sm-1 text-left"> -->
          <a href="#" onclick="openModal('add',0)" style="height: 100px;">
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
      <div class="row bg-white py-2">
        <div class="table-responsive table-striped overflow-auto col" style="height: 70vh;" id="all_data">
          <table id="sample_data" class="hover" style="width: 100%;">
            <thead>
              <tr>
                <!-- <th>ID</th> -->
                <th>Name</th>
                <th>Description</th>
                <th>Access</th>

              </tr>
              <!-- <tr>
              <th style="padding: 0px !important;width: 10% !important; line-height: 25px !important; vertical-align: text-top !important;" class="">Category/Detail</th>
              <th style="width: 40% !important;">File Description / View File</th>
              <th>RC Uploader / Download</th>
              <th style="display: none;">tags</th>
            </tr> -->
            </thead>
            <tbody>
              <?php
              $rc_induk = rtrim($_SESSION['doc']['kode_rc'],"0");
              // $rc_induk = explode("0", $_SESSION['doc']['kode_rc']);
              $sql = "SELECT * FROM m_category_doc";
              $stmt = sqlsrv_query( $conn, $sql );
              if( $stmt === false) {
                die( print_r( sqlsrv_errors(), true) );
              }
              $i=1;
              while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                ?>
                <tr>
                  <!-- <th><?=$row['id']?></th> -->
                  <!-- <th></th> -->
                  <td class="underlined-blue" onclick="showDetail('<?=$row['id']?>')"><?=$row['nama']?></td>
                  <!-- <td onclick="openModal('edit', '<?=$row['id']?>')"><?=$row['nama']?></td> -->
                  <td><?=$row['keterangan']?></td>
                  <td><?php if($row['access'] == '1'){ echo 'Specific';}else{echo 'All';}?></td>

                  <!-- <td>
                    <button class="btn btn-sm btn-warning" onclick="openModal('edit', '<?=$row['id']?>')">Edit</button>
                  </td> -->
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
                  <a href="#" onclick="showDetail('0')"><i class="fas fa-arrow-left"></i></a>
                </h5>
                <input disabled style="display: none;" id="id_doc" />
                <input disabled style="display: none;" id="url_detail" />
              <!-- </a> -->
              <!-- <a href="#" class="col-6 text-right"> -->
                <div class="col text-right">
                  <h5>
                    <a href="#" onclick="update()" id="saveS" style="visibility: hidden"><i class="far fa-check-circle"></i></a>
                    <!-- <a href="#" onclick="confDel()" id="delS" style="visibility: hidden"><i class="fas fa-trash"></i></a> -->
                  </h5>
                </div>
              <!-- </a> -->
            </div>
            <table style="border: 0;" id="tab_detail" class="" style="width: 100% !important">
              <tr>
                <td>Name</td>
                <td> : </td>
                <td>
                  <input type="text" id="name_detail" class="form-control" />
                </td>
              </tr>
              <tr>
                <td>Description</td>
                <td> : </td>
                <!-- <td id="description_detail"></td> -->
                <td >
                  <textarea class="form-control f-14" id="description_detail"></textarea>
                </td>
              </tr>
              <tr>
                <td>Access </td>
                <td> : </td>
                <!-- <td id="category_detail"></td> -->
                <td >
                  <select class="form-control select2 f-14" id="access_detail">
                  </select>
                </td>
              </tr>
            </table>
          </div>
        </div>

      </div>
    </div><!-- /.container-fluid -->
  </section>
  <div id="alerts"></div>
</div>



<div class="modal fade" id="categoryModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Category Document</h5>
        <input type="text" id="category_id" hidden />
        <input type="text" id="category_stat" hidden />
        <button type="button" class="close tutup" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="">
          <label for="category_name" class="form-label">Category Name :</label>
          <input type="text" class="form-control" name="category_name" id="category_name" placeholder="Please Input Category Name .."/>
        </div>
        <div class="">
          <label for="category_keterangan" class="form-label">Description :</label>
          <textarea class="form-control" name="category_keterangan" id="category_keterangan" placeholder="Please Input Description .."></textarea>
          <!-- <input type="text" class="form-control" placeholder="Please Input Category Name"/> -->
        </div>
        <div class="">
          <label for="category_access" class="form-label">Category Access :</label>
          <select class="select2 form-control" id="category_access">
            <option disabled>--Please Select Access--</option>
            <option value="1">Specific</option>
            <option value="2">All</option>
          </select>
          <!-- <input type="text" class="form-control" name="category_name" id="category_name" placeholder="Please Input Category Name .."/> -->
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary tutup" data-dismiss="modal">Cancel</button>
        <input type="button" value="Save" onclick="saveCategory()" class="btn btn-warning">
      </div>
    </div>
  </div>
</div>



<script type="text/javascript">
  $("#data_category").DataTable();
  $("#sample_data").DataTable({
    "dom": 'rtip',
    "paging": false,
    "info": false
  });
  function showDetail(id){
    if(id == "0"){

    }else{
      var data = {
        "id" : id
      };
      $.ajax({
           url:'mod/category/GetData.php',
           type:'post',
           data:data,
           dataType: 'json',
           success:function(response){
                if(response.status == "00"){
                  document.getElementById('saveS').style.visibility='visible';


                  var access ='';
                  $('#access_detail').html('');
                  access += '<option value="1" ';
                  if(response.msg['access'] == 1){access += 'selected'}
                  access += '>Specific</option>';
                  // $('#category_access').append(access);
                  access += '<option value="2" ';
                  if(response.msg['access'] == 2){access += 'selected'}
                  access += '>All</option>';
                  // alert(access);
                  $('#access_detail').append(access);


                  $('#id_doc').val(id);
                  $('#name_detail').val(response.msg['nama']);
                  $('#description_detail').val(response.msg['keterangan']);

                     // alert("Upload "+response.name+ " Success!");
                     // $('#uploadModal').modal('hide');
                }else{
                  alert("No Data!");
                  // $('#uploadModal').modal('hide');
                }
                // location.reload();
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
  function openModal(stat, id){
    $('#category_id').val(id);
    $('#category_stat').val(stat);
    if(stat == 'add'){

      $('#categoryModal').modal('show');
    }else if(stat == 'edit'){
      var data = {
        "id" : id
      };
      // alert(id);
      $.ajax({
           url:'mod/download/GetDataCategory.php',
           type:'post',
           data:data,
           dataType: 'json',
           success:function(response){
                if(response.status == '00'){
                     // console.log(fd);
                     // console.log(response.msg.nama);
                     $('#category_name').val(response.msg['nama']);
                     $('#category_keterangan').val(response.msg['keterangan']);
                     // alert("Upload "+response.name+ " Success!");
                     // $('#uploadModal').modal('hide');
                     $('#categoryModal').modal('show');

                }else{

                  alert("Oops! Something wrong!");
                  // $('#categoryModal').modal('show');
                  $('#uploadModal').modal('hide');

                }
           }
      });
    }
  }

  function saveCategory(){
    var stat = $('#category_stat').val();
    var id = $('#category_id').val();
    if(stat == 'add'){
      if($('#category_name').val() == null || $('#category_name').val() == ""){
        alert("Category Name can not be empty!");
      }else{
        var data = {
          // "id" : $('#category_id').val(),
          "nama" : $('#category_name').val(),
          "keterangan" : $('#category_keterangan').val(),
          "access" : $('#category_access').val()
        };
        $.ajax({
          url:'mod/download/insertData.php',
          type:'post',
          data:data,
          dataType: 'json',
          success:function(response){
            if(response.status == '00'){
              alert("Category Saved!");

              $('#categoryModal').modal('hide');

            }else{
              alert('File upload Fail!');
              // $('#uploadModal').modal('hide');
            }
            location.reload();

          }
        });
      }

    }else if(stat == 'edit'){
      var data = {
        "nama" : $('#category_name').val(),
        "keterangan" : $('#category_keterangan').val()
      };
      $.ajax({
        url:'mod/download/UpdateData.php?mod=m_category_doc&id='+id,
        type:'post',
        data:data,
        dataType: 'json',
        success:function(response){
          if(response.status == '00'){
            alert("Category Saved!");
            $('#categoryModal').modal('hide');
          }else{
            alert('File upload Fail!');
            // $('#uploadModal').modal('hide');
          }
          location.reload();

        }
      });

    }
  }

  function update(){
    var data = {
      "keterangan" : $('#description_detail').val(),
      "nama" : $('#name_detail').val(),
      "access" : $('#access_detail').val()
    }

    $.ajax({
         url:'mod/download/UpdateData.php?id='+$('#id_doc').val()+'&mod=m_category_doc',
         type:'post',
         data:data,
         dataType: 'json',
         success:function(response1){
              if(response1.status == "00"){
                alert("Category Updated!")
              }else{
                alert("Category Update Fail!")
              }
              location.reload();
         }
    });
  }



</script>

<?php
include 'script.php'; ?>
