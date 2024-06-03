<?php 
require_once("../config/conexion.php");
if(isset($_SESSION["user"])){
$categoria_usuario = $_SESSION["categoria"];
date_default_timezone_set('America/El_Salvador'); $hoy = date("d-m-Y H-i-s");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Home</title>
<?php require_once("links_plugin.php"); 
 require_once('../modales/bodega_av_plus/modal_procesando.php');
 require_once("../modales/nueva_orden_lab.php");
 ?>

<style>
  .buttons-excel{
    margin: 2px;
    max-width: 150px;
}
</style>
 <script src="../plugins/exportoExcel.js"></script>
 <script src="../plugins/keymaster.js"></script>
</head>
<body class="hold-transition sidebar-mini layout-fixed" style='font-family: Helvetica, Arial, sans-serif;'>
<div class="wrapper">
<!-- top-bar -->
  <?php require_once('top_menu.php')?>
  <?php require_once('side_bar.php')?>
  <!--End SideBar Container-->
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <section class="content">
      <div class="container-fluid">
      <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $_SESSION["id_user"];?>"/>
      <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION["user"];?>"/>
      <input type="hidden" name="categoria" id="get_categoria" value="<?php echo $_SESSION["categoria"];?>"/>
      <div style="border-top: 0px">
      </div>

      <div class="card mt-4 pt-2">
      <?php include 'bodega/header_status_bodega.php'; ?>
      </div>
        <div class="card">
          <div class="card-header">
          <div class="d-flex justify-content-end align-items-center" style="margin-bottom: 5px !important">
              <div class="col-md-2">
                <select class="form-control" onchange="filter_sucursal()" id="filter_sucursal">
                  <option value="" selected>Select. Sucursal</option>
                  <option value="Valencia">Valencia</option>
                  <option value="Metrocentro">Metrocentro</option>
                  <option value="Cascadas">Cascadas</option>
                  <option value="Santa Ana">Santa Ana</option>
                  <option value="Chalatenango">Chalatenango</option>
                  <option value="Ahuachapan">Ahuachapan</option>
                  <option value="Sonsonate">Sonsonate</option>
                  <option value="Ciudad Arce">Ciudad Arce</option>
                  <option value="Opico">Opico</option>
                  <option value="Apopa">Apopa</option>
                  <option value="San Vicente Centro">San Vicente Centro</option>
                  <option value="San Vicente">San Vicente</option>
                  <option value="Gotera">Gotera</option>
                  <option value="San Miguel">San Miguel</option>
                  <option value="Usulutan">Usulutan</option>
                </select>
              </div>

                <button class="btn btn-outline-success ml-2" class="btn btn-info barcode_actions" data-toggle="modal" id="btn-procesando" data-target="#modal_procesando" onClick='input_focus_clearb()'><i class="fas fa-clipboard-check"></i> Finalizar</button>
            </div>
          </div>
          <div class="card-body">
          <table width="100%" class="table-hover table-bordered" id="ordenes_procesando_lab"  data-order='[[ 0, "desc" ]]'> 
              
              <thead class="style_th bg-dark" style="color: white">
                     <th>#</th>
                     <th>ID orden</th>
                     <th>Codigo</th>
                     <th>Fecha</th>
                     <th>Dui</th>
                     <th>Paciente</th>
                     <th>Sucursal</th>
                     <th>Tipo lente</th>
                     <th>Detalles</th>
              </thead>
              <tbody class="style_th"></tbody>
            </table>
          </div>
        </div>

    </section>
    <!-- /.content -->
  </div>

  <input type="hidden" value="<?php echo $categoria_usuario;?>" id="cat_users">

  <input type="hidden" id="tipo_accion" value="proceso">
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>2021 Lenti || <b>Version</b> 1.0</strong>
     &nbsp;All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      
    </div>
  </footer>
</div>

<!-- ./wrapper -->
<?php 
require_once("links_js.php");
?>
<script type="text/javascript" src="../js/bodega_avplus.js?v=<?php echo rand() ?>"></script>
<script type="text/javascript" src="../js/ordenes.js?v=<?php echo rand() ?>"></script>

</body>
</html>
 <?php } else{
echo "Acceso denegado";
  } ?>

<script>
  //Subtitle page
  $("#subtitle_bod").html('Procesando')
</script>