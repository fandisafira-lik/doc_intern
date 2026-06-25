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
</style>


<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h3>Document Management |
            <a href="#" class="" onclick="openModalUpload()" style="height: 100px;">
              <i class="fas fa-file-upload fa-2xl"></i>
            </a></h3>
        </div>
      </div>
      <div class="row bg-white py-2">
        <div class="table-responsive">
          <table id="sample_data" class="hover" style="width: 100%;">
            <thead>
            <tr>
              <!-- <th>NO</th> -->
              <th style="width: 10% !important;">Category</th>
              <th style="width: 40% !important;">File Name</th>
              <th>RC Uploader</th>
              <!-- <th>Tgl  Input</th> -->
              <!-- <th></th> -->

            </tr>
            </thead>
            <tbody>
              <?php
              if($_SESSION['doc']['kode_rc'] == '0100000000' || $_SESSION['doc']['kode_rc'] == '0000000000'){
                $sql = "SELECT du.*, mcd.nama as cat_name, mrc.keterangan as nama_rc from doc_upload du left join m_category_doc mcd on du.jenis_doc = mcd.id LEFT JOIN m_resp_center mrc ON du.rc_penerbit = mrc.kode_rc";

              }else{
                $rc_induk = rtrim($_SESSION['doc']['kode_rc'],"0");
                // $rc_induk = explode("0", $_SESSION['doc']['kode_rc']);
                $sql = "SELECT du.*, mcd.nama as cat_name, mrc.keterangan as nama_rc from doc_upload du left join m_category_doc mcd on du.jenis_doc = mcd.id LEFT JOIN m_resp_center mrc ON du.rc_penerbit = mrc.kode_rc where rc_penerbit like '".$rc_induk."%'";
              }
              $stmt = sqlsrv_query( $conn, $sql );
              if( $stmt === false) {
                die( print_r( sqlsrv_errors(), true) );
              }
              $i=1;
              while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                ?>
                <tr>
                  <!-- <td><?=$i++;?></td> -->
                  <!-- <th></th> -->
                  <td><?=$row['cat_name']?></td>
                  <td><?=$row['nama']?></td>
                  <td>
                    <div class="row">
                      <div class="col-8">
                        <?=$row['nama_rc']?>
                      </div>
                      <div class="col-4 text-right">
                        <?php
                          $ext = explode(".", $row['nama']);
                          $view = array("jpg", "jpeg", "pdf", "gif", "png");
                          if(in_array($ext[1], $view)){
                        ?>
                        <a class="" target="_blank" href="<?= $baseurl_file."/".$row['url']?>" alt="View"><i class="fas fa-eye"></i></a>
                        <?php
                          }
                        ?> &nbsp;&nbsp;

                        <a href="<?=$baseurl_file.$row['url']?>" download>
                          <i class="fas fa-download"></i>
                        </a>&nbsp;&nbsp;
                        <?php
                          if($_SESSION['doc']['username'] == $row['userid']){
                        ?>
                        <a class="" onclick="confDel('<?=$row['id']?>', '<?=$row['nama']?>', '<?=$row['url']?>')" alt="Delete"><i class="fas fa-trash-alt"></i></a>
                      <?php } ?>
                      </div>
                    </div>
                  </td>
                  <!-- <td>

                </td> -->
                </tr>
              <?php }
              sqlsrv_free_stmt($stmt);  ?>
            </tbody>
            <!-- <tfoot>
            <tr>
              <th>NO</th>
              <th style="width: 30% !important;">NAMA FILE</th>
              <th>USER  INPUT</th>
              <th>TGL  INPUT</th>
              <th>ACTION</th>

            </tr>
            </tfoot> -->
          </table>
        </div>

      </div>
    </div><!-- /.container-fluid -->

  </section>
  <div id="alerts"></div>


</div>


<div class="modal fade" id="uploadModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <!-- <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">New Category</h5>
        <button type="button" class="close tutup" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div> -->
      <div class="modal-body">

        <div class="">
          <!-- <label for="jenis_doc" class="form-label">Category :</label> -->
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
            <!-- <label for="fileToUpload" class="form-label">Upload Files</label> -->
            <input class="form-control" type="file" id="fileToUpload" name="fileToUpload" multiple>
          </div>
          <!-- <label for="fileToUpload" class="form-label">Upload Files</label> -->
          <!-- Select file to upload:
          <input type="file" name="fileToUpload" id="fileToUpload"> -->
          <!-- <input type="submit" value="Upload File" name="submit"> -->
        </form>
        <div class="">
          <input type="text" name="rename" id="rename" class="form-control" placeholder="Input Name if you want to Rename ..">
          <label for="rename" class="form-label" style="color: red !important; font-size: 11px !important;">Input rename without extention and without dots(.) </label>

        </div>
        <hr class="mb-2 mt-0" style="color: lightgrey;">
        <div class="text-right">
          <button type="button" class="btn btn-secondary tutup btn-sm" data-dismiss="modal">Cancel</button>
          <input type="button" value="Upload File" name="submit" id="but_upload" class="btn btn-warning btn-sm">
        </div>
      </div>
      <!-- <div class="modal-footer"> -->
        <!-- <button class="btn btn-warning" onclick="subm('<?=time()?>', '<?=date("Y-m-d h:i:s");?>','checkout', 'edit')">Yakin</button> -->
      <!-- </div> -->
    </div>
  </div>
</div>



<div class="modal fade" id="viewModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <!-- <div class="modal-header"> -->
        <!-- <h5 class="modal-title" id="exampleModalLabel">New Document</h5>
        <button type="button" class="close tutup" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button> -->
      <!-- </div> -->
      <div class="modal-body">
        <iframe src="" title="W3Schools Free Online Web Tutorials" id="bd"></iframe>
      </div>
      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-secondary tutup" data-dismiss="modal">Batal</button>
        <input type="button" value="Upload File" name="submit" id="but_upload" class="btn btn-warning">
      </div> -->
    </div>
  </div>
</div>


<script type="text/javascript">
  // const collapseButton = document.getElementById('collapseButton');
  // const collapseContent = document.getElementById('collapseContent');
  //
  // // Tambahkan event listener untuk mengatur aksi ekspansi
  // collapseButton.addEventListener('click', () => {
  //   // Ubah visibilitas konten saat tombol di klik
  //   if (collapseContent.style.display === 'none') {
  //     collapseContent.style.display = 'block';
  //     collapseButton.innerHTML = '( Hide ) <i class="fa fa-caret-down fa-2x"></i>';
  //   } else {
  //     collapseContent.style.display = 'none';
  //     collapseButton.innerHTML = '( Show ) <i class="fa fa-caret-up fa-2x"></i>';
  //   }
  // });

  // $("#sample_data").DataTable();
  function openModalUpload(){
    $('#uploadModal').modal('show');
  }

  function openViewModal(url){
    $('#bd').src(url);
    $('#viewModal').modal('show');
    // document.getElementById('bd').src = "https://view.officeapps.live.com/op/embed.aspx?src=http://"+url;
    // document.getElementById('bd').src = "https://docs.google.com/gview?url=http://"+url+"&output=embed";
  }

  $(document).ready(function(){

     $("#but_upload").click(function(){
          var fd = new FormData();
          var files = $('#fileToUpload')[0].files;
          var jd = $('#jenis_doc').val();
          if(jd == '' || jd == null){
            alert("Document Type Can not be Empty!");
          }else{
          // Check file selected or not
            if(files.length > 0 ){
                 fd.append('file',files[0]);
                 fd.append('jenis_doc', jd);
                 if($('#rename').val() !== ""){
                   fd.append('rename', $('#rename').val());
                   // console.log($('#rename').val());
                 }
                 //Check if File Exist
                 $.ajax({
                      url:'mod/download/Check_Exist.php',
                      type:'post',
                      data:fd,
                      dataType: 'json',
                      contentType: false,
                      processData: false,
                      success:function(response1){
                           if(response1.status == "01"){
                             console.log("File Exist");
                               var text = "File with name "+response1.name+" already Exist, Click OK to Overwrite or Cancel to cancel Upload.";
                               if (confirm(text) == true) {

                                 $.ajax({
                                      url:'mod/download/upload.php',
                                      type:'post',
                                      data:fd,
                                      dataType: 'json',
                                      contentType: false,
                                      processData: false,
                                      success:function(response){
                                           if(response.status == 1){
                                                console.log(fd);
                                                // console.log(response.path);

                                                alert("Upload "+response.name+ " Success!");
                                                $('#uploadModal').modal('hide');

                                           }else{

                                             alert("Upload "+response.name+ "Fail!");
                                             $('#uploadModal').modal('hide');

                                           }
                                           location.reload();
                                      }
                                 });
                               } else {
                                 text = "You canceled!";
                               }
                                // console.log(response.path);

                           }else if(response1.status == "00"){
                             $.ajax({
                                  url:'mod/download/upload.php',
                                  type:'post',
                                  data:fd,
                                  dataType: 'json',
                                  contentType: false,
                                  processData: false,
                                  success:function(response){
                                       if(response.status == 1){
                                            console.log(fd);
                                            // console.log(response.path);
                                            alert("Upload "+response.name+ " Success!");

                                            $('#uploadModal').modal('hide');

                                       }else{
                                            alert('File upload Fail!');
                                            $('#uploadModal').modal('hide');
                                       }
                                       location.reload();

                                  }
                             });
                           }
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
      // text = "You pressed OK!";
      // alert("aaaa");
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
          // you can see the result from the console
          // tab of the developer tools
          alert("Document Deleted Successfully!");
          location.reload();
          // console.log(result);
        },
        error: function(xhr, resp, text) {
          console.log(xhr, resp, text);
        }
      });
    } else {
      text = "You canceled!";
    }
        // });
  }

  var table = $('#sample_data').DataTable();
  // table.dataTable td {
  //   font-size: 10px;
  // }
  $('#sample_data thead th').each(function () {
      var title = $(this).text();
      $(this).append('<input type="text" class="col mt-2" placeholder="Search ' + title + '" />');
  });

  // DataTable

  // Apply the search
  table.columns().eq( 0 ).each( function ( colIdx ) {
      $( 'input', table.column( colIdx ).header() ).on( 'keyup change', function () {
          table
              .column( colIdx )
              .search( this.value, true, false )
              .draw();
      } );
      $('input', table.column(colIdx).header()).on('click', function(e) {
          e.stopPropagation();
      });
  } );
</script>

<?php
include 'script.php'; ?>
