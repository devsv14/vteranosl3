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

 ?>
<style>
  .buttons-excel{
      background-color: green !important;
      margin: 2px;
      max-width: 150px;

  }
  .odd:hover{
    background-color: #c1e1ec !important;
  }
  .even:hover{
    background-color: #c1e1ec !important;

  }
  .vertical-alignment-helper {
    display:table;
    height: 100%;
    width: 100%;
    pointer-events:none;
}
.vertical-align-center {
    /* To center vertically */
    display: table-cell;
    vertical-align: middle;
    pointer-events:none;
}
.modal-content {
    /* Bootstrap sets the size of the modal in the modal-dialog class, we need to inherit it */
    width:inherit;
 max-width:inherit; /* For Bootstrap 4 - to avoid the modal window stretching full width */
    height:inherit;
    /* To center horizontally */
    margin: 0 auto;
    pointer-events:all;
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
    <!--CCCC-->
      <div class="card card-info card-outline" style="margin: 2px;margin-top: 0px !important">
      <h5 style="text-align: center; font-size: 18px" align="center" class="bg-dark">ORDENES ENVIADAS <span id="lab_actual_send" style="text-transform: uppercase;"></span></h5>
        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
          <li class="nav-item">
            <select name="" id="cat_lente_send" class="form-control" style="margin-top: 1px">
            <option value="0">Selec. base</option>
            <option value="Proceso">Proceso</option>
            <option value="Terminado">Terminado</option>
          </select>
          </li>
          <li class="nav-item">
            <select name="" id="tipo_lente_env" class="form-control" style="margin-top: 1px">
            <option value="0">Selec. lente</option>
            <option value="Visión Sencilla">Visión Sencilla</option>
            <option value="Flaptop">Flaptop</option>
            <option value="Progresive">Progresive</option>
          </select>
          </li>
          <li class="nav-item">
            <input type="date" class="form-control clear_orden_i" id="desde_table_send" placeholder="desde">
          </li>
          <li class="nav-item">
            <input type="date" class="form-control clear_orden_i" id="hasta_table_send" placeholder="desde">
          </li>
          <li class="nav-item">
            <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true" onClick="get_ordenes_env_lab('Lomed')">Lomed</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false" onClick="get_ordenes_env_lab('Jenny')">Jenny</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="custom-tabs-one-messages-tab" data-toggle="pill" role="tab" aria-controls="custom-tabs-one-messages" aria-selected="false"  onClick="get_ordenes_env_lab('Divel')">Divel</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="custom-tabs-one-messages-tab" data-toggle="pill" aria-controls="custom-tabs-one-messages" aria-selected="false"  onClick="get_ordenes_env_lab('Lenti')">Lenti</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="custom-tabs-one-messages-tab" data-toggle="pill" href="#custom-tabs-one-messages"  onClick="get_ordenes_env_lab('Arce')">Arce</a>
          </li>
          <li class="nav-item" onClick="showTablasEnv()">
            <a class="nav-link"><i class="fas fa-table" style="color: green" onClick="showTablasEnviadas()"></i></a>
          </li>
          <li class="nav-item">
            <a class="nav-link"><i class="fas fa-print" style="color: blue;cursor:pointer;" onClick="print_orden_alert()"></i></a>
          </li>
        </ul>
        <input type="hidden" id="get_ordenes_env">
        <h5 style="margin-left: 5px;font-size: 12px"><span id='count_select'>0</span> items seleccionados</h5>
        <table width="100%" class="table-bordered" id="data_ordenes_env_laboratorio"  data-order='[[ 1, "ASC" ]]' style="font-size: 10px">        
         <thead class="style_th bg-primary" style="color: white">
           <th>ID</th>
           <th>Fecha</th>
           <th>Recibir</th>
           <th>Paciente</th>
           <th>Ojo derecho</th>
           <th>Ojo izquierdo</th>
           <th>Lente</th>
           <th>Categoria</th>
           <th>Lab.</th>
           <th>Ver</th>
           <th>Edit</th>
         </thead>
         <tbody class="style_th" style="padding: 3px;text-align: center"></tbody>
       </table>
      <button type="button" class="btn btn-dark btn-block send_orden" onClick="enviar_confirm_v();" id="btn_enviar_lab"><i class="fas fa-download" style="border-radius: 0px;margin: 5px"></i> RECIBIR</button>
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
              <div class="form-group col-sm-5 select2-purple" style="margin: auto">
              <select class="select2 form-control" id="destino_envio" multiple="multiple" data-placeholder="Seleccionar destino" data-dropdown-css-class="select2-purple" style="width: 100%;height: ">              
                  <option value="">Seleccionar destino</option>
                  <option value="China">China</option>
                  <option value="Laboratorio">Laboratorio</option>             
                </select>   
              </div>
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

   <div class="modal" id="cambiaLabModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal body -->
        <div class="modal-body">
        <div>
          <p>Paciente: &nbsp;<span id="pac_edit_lab"></span></p>
        </div>
        <section class="input-group" id="enviar_a">                   
          <div class="form-group col-sm-6">
            <select class="custom-select" id="categoria_lente_edit" aria-label="Example select with button addon">
              <option value="0">Seleccionar opcion...</option>
              <option value="Proceso">Proceso</option>
              <option value="Terminado">Terminado</option>
            </select>
          </div>

          <div class="form-group col-sm-6">
          <div class="input-group">
          <select class="custom-select" id="destino_orden_lente_edit" aria-label="Example select with button addon">
            <option value="0">Enviar a...</option>
            <option value="Jenny">Jenny</option>
            <option value="Divel">Divel</option>
            <option value="Lomed">Lomed</option>
            <option value="Lenti">Lenti</option>
            <option value="Arce">Arce</option>
          </select>
          <div class="input-group-append">
          <button class="btn btn-primary" type="button" onClick='CambiarLab()'><i class="fas fa-edit"></i> Cambiar</button>
          </div>
        </div>
          </div>
        </section>
        </div>        
        <input type="hidden" id="codigoEd">
      </div>
    </div>
  </div>
  
</div>
<input type="hidden" id="user_act" value="<?php echo $_SESSION["usuario"];?>">
  <!-- /.content-wrapper -->
<!--MODAL IMPRIMIR-->
  <div class="modal" id="print_order">
  <div class="modal-dialog modal-xs">
    <div class="modal-content">
      <!-- Modal body -->
      <div class="modal-body">
         <b><h5 align="center" style="text-align: center;font-family: Helvetica, Arial, sans-serif;font-size: 18px"><span id="n_items_print" style="color: blue"></span> ordenes seran impresas y enviadas</h5></b>
         <button type="button" class="btn btn-dark btn-block" onClick="imprimir_ordenes()"><i class="fas fa-print"></i> Enviar e imprimir</button>
      </div>
    </div>
  </div>
</div>





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
