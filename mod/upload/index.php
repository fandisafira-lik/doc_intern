<style type="text/css">
  #check-all {
  transform: scale(1.5); /* Mengubah skala checkbox */
  margin-right: 10px; /* Memberikan jarak antara checkbox dan label */
}

#check-all-label {
  font-size: 18px; /* Mengatur ukuran label */
}
</style>

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Upload Data SOP</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">DataTables</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <div id="alerts"></div>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">

          <div class="card">
            <div style="background-color: orange" class="card-header">
              <h3 class="card-title"></h3>
              <!-- <h3 class="card-title">DataTable with default features</h3> -->

          <div class="card-tools">

            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
              <i class="fas fa-times"></i>
            </button>
          </div>
            </div>

            <!-- /.card-header -->
            <div class="card-body">
              <!-- /.form group -->
              <!-- INI ISI CARD NYA -->
              <form>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Nama File :</label>
                    <div class="col-sm-12">
                      <input type="text" class="form-control" id="staticEmail" placeholder="Ketik nama file...">
                    </div>
                  </div>
                  <div class="mb-3">
                    <label for="formFileMultiple" class="form-label">Upload Files</label>
                    <input class="form-control" type="file" id="formFileMultiple" multiple>
                  </div>
                  <button type="button" class="btn btn-primary justify-content-end" onclick="alert('coming soon')">Submit</button>

              </form>

            </div>
          </div>



    </div>
    <!-- /.container-fluid -->
  </section>
</div>


<?php
include 'script.php'; ?>
