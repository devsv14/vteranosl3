<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Home</title>
<?php require_once("links_plugin.php"); ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
<!-- top-bar -->
  <?php require_once('top_menu.php')?>
  <!-- /.top-bar -->
  <!-- Main Sidebar Container -->
  <?php require_once('side_bar.php')?>
  <!--End SideBar Container-->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">

      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

<div class="row">
<div class="col-md-12">
  <!-- /.card -->
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Crear Lente</h3>
              </div>
              <div class="card-body">
            <form method="post" style=" margin-top:10px">
                <div class="form-group">
                  <label>Codigo:</label>
                  <input type="text" class="form-control my-colorpicker1" name="codigo">
                </div>

                <div class="form-group">
                  <label>Tipo Lente:</label>
                  <input type="text" class="form-control my-colorpicker1">
                </div>

                <div class="form-group">
                  <label>Diseño:</label>
                  <input type="text" class="form-control my-colorpicker1">
                </div>

                <div class="form-group">
                  <label>Tratamiento:</label>
                  <input type="text" class="form-control my-colorpicker1">
                </div>
                <!-- /.form group -->
              </div>
              <!-- /.card-body -->
              <button type="submit" class="btn btn-primary btn-block">Registrar</button>
            </form>

            </div>
<?php
if(isset($_POST['codigo'])){
          
?>             
<img class="barcode" src="../plugins/barcode.php?text=<?php echo $_POST['codigo'];?>r&size=50&orientation=horizontal&codetype=Code39&print=true&sizefactor=1"/>
 <?php }?> 
          </div>
</div>          
       
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>2021 Lenti || <b>Version</b> 1.0</strong>
     &nbsp;All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      
    </div>
  </footer>
</div>
<!-- ./wrapper -->
<?php require_once("links_js.php"); ?>
</body>
</html>
