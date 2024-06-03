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
 require_once('../modales/nueva_orden_lab.php');
 require_once('../modales/aros_en_orden.php');
 require_once('../modales/modal_overlay.php');
 ?>
<style>
  .buttons-excel{
      background-color: green !important;
      margin: 2px;
      max-width: 150px;

  }
  .odd:hover{
    background-color: lightyellow !important;
  }
  .even:hover{
    background-color: lightyellow !important;
  }
</style>
</head>
<body class="hold-transition sidebar-mini layout-fixed" style='font-family: Helvetica, Arial, sans-serif;'>
<div class="wrapper">
<!-- top-bar -->
  <?php require_once('top_menu.php')?>
  <!-- /.top-bar -->

  <!-- Main Sidebar Container -->
  <?php require_once('side_bar.php')?>
  <!--End SideBar Container-->
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <section class="content">
      <div class="container-fluid">
      <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $_SESSION["id_usuario"];?>"/>
      <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION["usuario"];?>"/>
      <input type="hidden" name="categoria" id="get_categoria" value="<?php echo $_SESSION["categoria"];?>"/>
      <div style="border-top: 0px">

      </div>
      <?php require_once('ordenes/header_estado_ordenes.php');?>
      <div class="card card-warning card-outline" style="margin: 2px;margin-top: 0px !important">
      <h5 style="text-align: center; font-size: 16px" align="center" class="bg-dark">ORDENES PENDIENTES (CLASIFICACIÓN)</h5>
       <table width="100%" class="table-bordered" id="data_ordenes_sin_procesar"  data-order='[[ 0, "asc" ]]' style="font-size: 11px">
      <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
        <li class="nav-item">
          <input type="date" class="form-control clear_orden_i" id="desde_clasif" placeholder="desde">
        </li>
        <li class="nav-item" style="margin-left: 5px;display: inline-flex;">
          <input type="date" class="form-control clear_orden_i" id="hasta_clasif" placeholder="hasta"><i class="fas fa-times-circle" style="margin-top: 9px" onClick="clear_input_date_clas()"></i>
        </li>
        <li class="nav-item">
          <select name="" id="tipo_lente_pendiente" class="form-control" style="margin-top: 1px">
            <option value="0">Selec. lente</option>
            <option value="Visión Sencilla">Visión Sencilla</option>
            <option value="Flaptop">Flaptop</option>
            <option value="Progresive">Progresive</option>
          </select>
          </li>
          <li class="nav-item">
          <select name="" id="inst-env" class="form-control" style="margin-top: 1px">
            <option value="0">Selec. Institucion</option>
            <option value="INABVE">INABVE</option>
            <option value="FOPROLYD">FOPROLYD</option>
          </select>
          </li>
        <li class="nav-item" onClick="get_ordenes_por_enviar()">
          <a class="nav-link" style="background:  #F5FCFF;cursor:pointer;"><i class="fas fa-search" style="color: green" onClick="get_ordenes_por_enviar()"></i> Filtrar</a>
        </li>
      </ul>        
        <thead class="style_th bg-info" style="color: white">
           <th>ID</th>
           <th>Fecha</th>
           <th>Clasificar</th>
           <th>Institucion</th>
           <th>Paciente</th>
           <th style="text-align: left;">Ojo derecho</th>
           <th>Ojo izquierdo</th>
           <th>Lente</th>
           <th style="text-align: center">Detalles</th>
         </thead>
         <tbody class="style_th" style="padding: 3px;text-align: left;font-size: 11px"></tbody>
       </table>
      <button type="button" class="btn btn-primary btn-block send_orden" onClick="enviar_confirm_v();" id="btn_enviar_lab"><i class="fas fa-share-square" style="border-radius: 0px;margin: 5px"></i> REGISTRAR CLASIFICACIÓN</button>
      </div>

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

  <input type="hidden" value="<?php echo $categoria_usuario;?>" id="cat_users">

   <!--Modal Imagen Aro-->
   <div class="modal" id="imagen_aro_orden">
    <div class="modal-dialog" style="max-width: 45%">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>        
        <!-- Modal body -->
        <div class="modal-body">
          <div style="  background-size: cover;background-position: center;display:flex;align-items: center;">
            <img src="" alt="" id="imagen_aro" style="width: 100%;border-radius: 8px;">
          </div>          
        </div>        
   
      </div>
    </div>
  </div>

  <!-----------MODAL COBNFIRMA ENVIO --------->
<div class="modal fade" id="confirmar_envio_ord">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Confirmación de envío</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
            <div class="modal-body">
              <h5 style="font-family: Helvetica, Arial, sans-serif;font-size: 18px;text-align: center;"><b>Confirmar el envío de <span id="n_trabajos_env" style="color: red"></span>&nbsp;trabajos</b></h5>
              <div class="dropdown-divider"></div>
              <div>
              <section class="input-group"> 
              <div class="form-group col-sm-4 select2-purple" style="margin: auto">
              <select class="select2 form-control" id="destino_envio" multiple="multiple" data-placeholder="Seleccionar destino" data-dropdown-css-class="select2-purple" style="width: 100%;height: ">              
                  <option value="">Seleccionar destino</option>
                  <option value="Jenny">Jenny</option>
                  <option value="Divel">Divel</option>
                  <option value="Lomed">Lomed</option>
                  <option value="Lenti">Lenti</option>
                  <option value="Arce">Arce</option>              
              </select>   
              </div>

              <div class="form-group col-sm-4 select2-purple" style="margin: auto">
              <select class="select2 form-control" id="cat_envio" multiple="multiple" data-placeholder="Seleccionar categoria" data-dropdown-css-class="select2-purple" style="width: 100%;height: ">              
                  <option value="">Seleccionar categoria</option>
                  <option value="Proceso">Proceso</option>
                  <option value="Terminado">Terminado</option>             
                </select>   
              </div>
            </section>
              </div>
              </div>
              <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
              <button type="button" class="btn btn-primary" onClick="registrarEnvioVet()">Aceptar</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <input type="hidden" id="user_act" value="<?php echo $_SESSION["usuario"];?>">
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>2021 Lenti || <b>Version</b> 1.0</strong>
     &nbsp;All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      
    </div>
  </footer>
</div>

<?php

require_once("links_js.php");

?>
<script type="text/javascript" src="../js/ordenes.js"></script>
<script type="text/javascript" src="../js/productos.js"></script>
<script type="text/javascript" src="../js/cleave.js"></script>
<script>
  var dui = new Cleave('#dui_pac', {
  delimiter: '-',
  blocks: [8,1],
  uppercase : true
});

$(function () {
    //Initialize Select2 Elements
    $('.select2').select2()
    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })

    $(".select2").select2({
    maximumSelectionLength: 1
  });
})
</script>
</body>
</html>
 <?php } else{
echo "Acceso denegado";
} ?>
