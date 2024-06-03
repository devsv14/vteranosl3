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
  <title>Ordenes pendientes</title>
<?php require_once("links_plugin.php"); 
 require_once('../modelos/Ordenes.php');


 require_once('../modales/modal_ingresos_lab.php');
 require_once('../modales/nueva_orden_lab.php');
 require_once('../modales/aros_en_orden.php');
 //Modal para recibir devoluciones
 require_once('../modales/modal_recibir_dev.php');

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
      <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION["usuario"];?>"/>
      <input type="hidden" name="categoria" id="get_categoria" value="<?php echo $_SESSION["categoria"];?>"/>
      <div style="border-top: 0px">
      </div>

      <?php include 'ordenes/header_status_lab.php'; ?>
      <div class="col-sm-12"><h5 style="text-align: center">ORDENES PENDIENTES</h5></div>
      <div class="form-row">
        <div class="col-sm-2" style="text-align: right;display: flex;align-items: right">
          <input type="date" class="form-control clear_orden_i" id="desde_fecha" placeholder="desde" name="inicio">
        </div>

        <div class="col-sm-2 form-group" style="text-align: right;display: flex;align-items: right" name="fecha_fin">
          <input type="date" class="form-control clear_orden_i" onchange="listOrdenPendientesLab()" id="hasta_fecha" placeholder="desde">
        </div>
        
        <div class="col-sm-2" style="text-align: right;display: flex;align-items: right">          
          <select id="selectEstado" onchange="listOrdenPendientesLab()" class="form-control" style="margin-top: 1px">
            <option value="" selected>Selec. estado</option>
          </select>
        </div>
      </div>
      
      <table width="100%" class="table-hover table-bordered" id="ordersPendientesLab"  data-order='[[ 0, "desc" ]]'> 
         <thead class="style_th bg-dark" style="color: white">
           <th>Correlativo</th>
           <th>ID orden</th>
           <th>Fecha</th>
           <th>DUI</th>
           <th>Paciente</th>
           <th>Tipo lente</th>
           <th>Institución</th>
           <th>Estado</th>
           <th>Sucursal</th>
           <th>Detalles</th>
         </thead>
         <tbody class="style_th"></tbody>
       </table>

    </section>
    <!-- /.content -->
  </div>

  <input type="hidden" value="<?php echo $categoria_usuario;?>" id="cat_users">

  <input type="hidden" id="cat_data_barcode" value="ingreso_lab">
  <!--RETIFICACION ORDEN--->
  <input type="hidden" id="rectificacion">
  <!----END RETIFICACION ORDEN--->
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
<script type="text/javascript" src="../js/laboratorios.js"></script>
<script type="text/javascript" src="../js/ordenes.js"></script>
<script type="text/javascript" src="../js/cleave.js"></script>

</body>
</html>
 <?php } else{
echo "Acceso denegado";
  } ?>