<?php 
require_once("../config/conexion.php");
if(isset($_SESSION["usuario"])){
$categoria_usuario = $_SESSION["categoria"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Home</title>
<?php require_once("links_plugin.php"); 
    require_once('../modelos/Ordenes.php');
    $ordenes = new Ordenes();
    $suc = $ordenes->get_opticas();
    require_once('../modales/modal_ingresos_lab.php');
    require_once('../modales/nueva_orden_lab.php');
    require_once('../modales/aros_en_orden.php');
?>
<style>
  .buttons-excel{
    margin: 2px;
    max-width: 150px;
  }
</style>
</head>
<body class="hold-transition sidebar-mini layout-fixed" style='font-family: Helvetica, Arial, sans-serif;'>
<div class="wrapper">
<!-- top-bar -->
  <?php require_once('top_menu.php')?>

  <?php require_once('side_bar.php')?>
  <!--End SideBar Container-->
  <div class="content-wrapper">
    <section class="content">
      <div class="container-fluid">
      <input type="hidden" id="correlativo_acc_vet">
      <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $_SESSION["id_usuario"];?>"/>
      <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION["usuario"];?>"/>
      <input type="hidden" name="categoria" id="get_categoria" value="<?php echo $_SESSION["categoria"];?>"/>
      <div style="border-top: 0px">
      </div>

      <?php include 'ordenes/header_status_veteranos.php'; ?>

        <button class="btn btn-success float-right barcode_actions" data-toggle="modal" data-target="#barcode_ingresos_lab" onClick='input_focus_clearb()' style="border: solid 1px #1f2e50"><i class="fas fa-upload"></i> Entregar</button>
        <h5 style="font-size: 16px; text-align: center;font-weight: bold;color: green">ORDENES ENTREGADAS</h5>
        <table width="100%" class="table-hover table-bordered" id="ordenes_entregados_veteranos_data"  data-order='[[ 0, "desc" ]]'> 
              
         <thead class="style_th bg-dark" style="color: white">
           <th>ID</th>
           <th>Fecha Ent.</th>
           <th># Orden</th>
           <th>Entregado por</th>
           <th>Paciente</th>
           <th>DUI</th>
           <th>Tipo lente</th>
           <th>Detalles</th>
         </thead>
         <tbody class="style_th"></tbody>
       </table>

    </section>
    <!-- /.content -->
  </div>

  <input type="hidden" value="<?php echo $categoria_usuario;?>" id="cat_users">
   <!--Modal Imagen Aro-->
   <div class="modal" id="imagen_aro_orden">
    <div class="modal-dialog" style="max-width: 55%">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>        
        <!-- Modal body -->
        <div class="modal-body">
          <span><b>Código: </b></span><span id="cod_orden_lab"></span>&nbsp;&nbsp;&nbsp;<span><b>Paciente: </b></span><span id="paciente_ord_lab"></span>
          <div style="  background-size: cover;background-position: center;display:flex;align-items: center;">
            <img src="" alt="" id="imagen_aro_v" style="width: 100%;border-radius: 8px;">
          </div>          
        </div> 
      </div>
    </div>
  </div>
  <input type="hidden" id="cat_data_barcode" value="entregar_veteranos">
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

</body>
</html>
 <?php } else{
echo "Acceso denegado";
  } ?>
