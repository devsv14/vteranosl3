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
      <div class="col-sm-10"><h5 style="text-align: center">INGRESO A LABORATORIOS</h5></div>
      <div class="form-row">
        <div class="col-sm-2" style="text-align: right;display: flex;align-items: right">
          <input type="date" class="form-control clear_orden_i" id="desde_orders_lab_pend" placeholder="desde" name="inicio">
        </div>

        <div class="col-sm-2 form-group" style="text-align: right;display: flex;align-items: right" name="fecha_fin">
          <input type="date" class="form-control clear_orden_i" id="hasta_orders_lab_pend" placeholder="desde">
        </div>
        
        <div class="col-sm-2" style="text-align: right;display: flex;align-items: right">          
          <select id="estado_proceso" class="form-control" style="margin-top: 1px">
            <option value="" selected>Selec. estado</option>
            <option value="0">Pendientes (Digitadas)</option>
            <option value="1">Despacho de óptica</option>
            <option value="2">Recibidas (Procesando)</option>
            <option value="3">Finalizadas</option>
            <option value="4">Enviadas a óptica</option>
            <option value="5">Recibida en optica</option>
            <option value="6">Entregada</option>
          </select>
        </div>
        <div class="ml-3">         
          <button class="btn btn-outline-primary btn-sm" id="modal_ingreso_lab" onclick="ingreso_laboratorio()">
            <span class="badge bg-success"></span>
            <i class="fas fa-paste" aria-hidden="true" style="color: #222222;"></i> INGRESO
          </button>
         </div>

         <div class="ml-3 float-right" style="margin-bottom: 5px !important">         
          <button class="btn btn-success btn-sm" class="btn btn-info btn-sm barcode_actions" data-toggle="modal" data-target="#barcode_ingresos_lab" id="btn_ingr_manual"><i class="fas fa-clipboard-check"></i> ING MANUAL</button>
         </div>
         <div class="ml-3">
          <button type="button" onclick="btn_recibir_dev()" class="btn btn-block btn-outline-info btn-sm">Recibir dev.</button>
         </div>
      </div>
      
      <table width="100%" class="table-hover table-bordered" id="ordenes_pendientes_lab"  data-order='[[ 0, "desc" ]]'> 
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

  <!--Modal buscar despacho-->
  <div class="modal" id="modal_ingreso_laboratorio" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" style="max-width: 80%">
    <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header" style="background: #162e41;color: white">
          <button type="button" class="close" data-dismiss="modal">×</button>
        </div>        
        <!-- Modal body -->
        <div class="modal-body">
          <div class="form-group">
            <label class="form-label-title" id="form-label-title">Código de envio</label>
            <input type="text" autofocus class="form-control" id="n_despacho" onchange="getDespachoLab(this.id)" placeholder="Buscar por código de envio">

            <input type="text" autofocus class="form-control" id="dui_despacho" onchange="buscar_dui_table(this.id)" placeholder="Buscar por Escaner">
          </div>

          <button type="button" class="btn btn-default float-right btn-sm " id='showModalEnviarLab' style="margin: 3px"><i class=" fas fa-file-export" style="color: #0275d8"></i> Ingresar <span id="totalOrdenLab">0</span></button>

          <table class="table-hover table-bordered" style="font-family: Helvetica, Arial, sans-serif;max-width: 100%;text-align: left;margin-top: 5px !important" width="100%">

          <thead style="font-family: Helvetica, Arial, sans-serif;width: 100%;text-align: center;font-size: 12px;" class="bg-dark">
            <tr>
              <th>#</th>
              <th width="80px"><div class="icheck-success d-inline">
                <input type="checkbox" id="select-all-desp" class="form-check-label">
                <label for="select-all-desp"></label>
              </div></th>
              <th>DUI</th>
              <th>PACIENTE</th>
              <th>No.Envio</th>
              <th>Sucursal</th>
          </tr></thead>
          <tbody id="result_despacho" style="font-size: 12px"></tbody>
        </table>

        </div>
        <!-- Modal footer -->
       
      </div>
    </div>
  </div>
  <!---Modal laboratorio --->
  <div class="modal" id="modal_laboratorio" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" style="max-width: 35%">
    <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header" style="background: #162e41;color: white">
          <button type="button" class="close" data-dismiss="modal">×</button>
        </div>        
        <!-- Modal body -->
        <div class="modal-body">
          <h5>Total de ordenes : <span id="totalOrdenLab_ingreso">0</span></h5>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="tipo_acciones">Tipo acción: </label>
              <select name="tipo_acciones" id="tipo_acciones" class="form-control" required>
                <option value="" disabled selected>Seleccionar</option>
                <option value="INGRESO LABORATORIO">Ingreso Lab</option>
                <option value="REENVIO A LAB">Reenvio a Lab</option>
              </select>
            </div>
            <div class="form-group col-md-6">
              <label for="laboratorio">Laboratorio: </label>
              <select name="laboratorio" id="laboratorio_ingreso" class="form-control" required>
              </select>
            </div>
          </div>
          <button id="btn_enviar_ingreso_lab" class="btn btn-primary btn-block" onclick="ingreso_lab()">Enviar</button>

        </div>
        <!-- Modal footer -->
       
      </div>
    </div>
  </div>
   
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