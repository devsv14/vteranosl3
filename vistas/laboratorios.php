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
      /*background-color: green !important;*/
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
      <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $_SESSION["id_usuario"];?>"/>
      <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION["usuario"];?>"/>
      <input type="hidden" name="categoria" id="get_categoria" value="<?php echo $_SESSION["categoria"];?>"/>
      <div style="border-top: 0px">
      </div>

      <?php include 'ordenes/header_status_lab.php'; ?>

      <div class="form-row">
        
        <div class="col-sm-2" style="text-align: right;display: flex;align-items: right">
          <input type="date" class="form-control clear_orden_i" id="desde_orders_lab_pend" placeholder="desde" name="inicio">
        </div>

        <div class="col-sm-2 form-group" style="text-align: right;display: flex;align-items: right" name="fecha_fin">
          <input type="date" class="form-control clear_orden_i" id="hasta_orders_lab_pend" placeholder="desde">
        </div>

       
        <div class="col-sm-2" style="text-align: right;display: flex;align-items: right">         
          <select name="" id="tipo_lente_ing" class="form-control" style="margin-top: 1px">
            <option value="0">Selec. lente</option>
            <option value="Visión Sencilla">Visión Sencilla</option>
            <option value="Flaptop">Flaptop</option>
            <option value="Progresive">Progresive</option>
          </select>
        </div>

         <div class="col-sm-2" style="text-align: right;display: flex;align-items: right">          
          <select name="" id="categoria_lente_ing" class="form-control" style="margin-top: 1px">
            <option value="0">Selec. base</option>
            <option value="Proceso">Proceso</option>
            <option value="Terminado">Terminado</option>
          </select>
        </div>
        
        <div class="col-sm-2" style="text-align: right;display: flex;align-items: right">          
          <select name="0" id="estado_proceso" class="form-control" style="margin-top: 1px">
            <option value="0">Selec. estado</option>
            <option value="2">Pendiente</option>
            <option value="3">En proceso</option>
            <option value="4">Finalizado</option>
          </select>
        </div>
        
        <div class="col-sm-2 form-group" style="text-align: right;display: flex;align-items: right">
          <button class="btn btn-success" onClick="listar_ordenes_pend_lab()"><i class="fas fa-search" style="cursor:pointer;margin-top: 4px" ></i> Filtrar</button>
        </div>
        <div class="col-sm-2 form-group" >
            <i class="fas fa-download barcode_actions ingresa_ordenes_id" data-toggle="modal" data-target="#barcode_ingresos_lab" onClick='input_focus_clearb()'></i>
            <i class="fas fa-print" style="color: green;cursor:pointer;" onClick="print_orden_alert_multiple()"></i>

        </div>
         </div>
        </div> 
        <table width="100%" class="table-hover table-bordered" id="ordenes_pendientes_lab"  data-order='[[ 0, "desc" ]]'> 
              
         <thead class="style_th bg-dark" style="color: white">
           <th>ID</th>
           <th><label><input type="checkbox" id="select-all-env" class="form-check-label" onClick="selectOrdenesImprimir()"> Selecc.</label></th>
           <th>Mod.Aro</th>
           <th>Codigo</th>
           <th>Fecha</th>
           <th>Paciente</th>
           <th>Tipo lente</th>
           <th>Categoria</th>
           <th>Detalles</th>
           <th>Aro</th>
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


   <!--Modal Ingreso a laboratorio-->
   <div class="modal" id="modal_ingreso_lab" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" style="max-width: 35%">
      <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">ORDENES RECIBIDAS EN LABORATORIO</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        </div>       
        <!-- Modal body -->
        <div class="modal-body">
          <b><h5 style="font-size: 16px;text-align: center">Confirmar que recibe <span id="count_select"></span> ordenes.</h5></b>
          
        </div>
        <div class="modal-footer">
        <form action="ordenes_recibir_pdf.php"  method="post" target="_blank">
          <input type="hidden" id="ordenes_imp" name="orders" value="">
          <input type="hidden" id="inicio_rec" name="inicio">
          <input type="hidden" id="fin_rec" name="fin">

          <button type="submit" class="btn btn-info" onClick="confirmarIngresoLab();"> Recibir</button>
        </form>
        
      </div>       
   
      </div>
    </div>
    <input type="hidden" id="cat_data_barcode" value="ing_lab">
  </div>

  <!--==================== MODAL BUSQUEDAS ================-->
<div class="modal" id="modal_busqueda_grads">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h5 style="padding: 2px;font-size: 13px" class="modal-title">Busquedas por graduacion</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <!-- Modal body -->
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">    
            <table style="margin:0px;width:100%" class="table-bordered table-hover">
              <thead class="thead-light" style="color: black;font-family: Helvetica, Arial, sans-serif;font-size: 11px;text-align: center;background: #f8f8f8">
                <tr>
                  <th style="text-align:center">OJO</th>
                  <th style="text-align:center">ESFERAS</th>
                  <th style="text-align:center">CILIDROS</th>
                  <th style="text-align:center">EJE</th>      
                  <th style="text-align:center">ADICION</th>
  
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>OD</td>
                  <td> <input type="search" class="form-control clear_orden_i rx_f oblig"  id="odesferas_search"  style="text-align: center"></td>
                  <td> <input type="search" class="form-control clear_orden_i rx_f oblig"  id="odcilindros_search"  style="text-align: center"></td>
                  <td> <input type="search" class="form-control clear_orden_i rx_f oblig"  id="odejes_search"  style="text-align: center"></td>             
                 <td> <input type="search" class="form-control clear_orden_i rx_f oblig"  id="oddicion_search"  style="text-align: center"></td>
        
                </tr>
                <tr>
                  <td>OI</td>
                  <td> <input type="search" class="form-control clear_orden_i rx_f oblig"  id="oiesferas_search"   style="text-align: center">                        
                </td>
                  <td> <input type="search" class="form-control clear_orden_i rx_f oblig"  id="oicilindros_search"   style="text-align: center"></td>
                  <td> <input type="search" class="form-control clear_orden_i rx_f oblig"  id="oiejes_search"   style="text-align: center"></td>              
                  <td> <input type="search" class="form-control clear_orden_i rx_f oblig"  id="oiadicion_search"  style="text-align: center"></td>    
                </tr>
              </tbody>
            </table>
            </div>
        </div>

        <table width="100%" style="margin: 5px;text-align: center;font-size: 13px" class="table-bordered table-hover">
           <thead class="bg-dark">
             <th colspan='15'>ID</th>
             <th colspan='15'>CODIGO</th>
             <th colspan='15'>FECHA</th>
             <th colspan='40'>PACIENTE</th>
             <th colspan='15'>ESTADO</th>
           </thead>
           <tbody id="resultados_grads"></tbody>
        </table>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-info btn-block" onClick="buscarGraduacion()">Buscar</button>
      </div>

    </div>
  </div>
</div>
<!--====================    FIN MODAL BUSQUEDA ===============-->

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

    <!--MODAL IMPRIMIR-->
  <div class="modal" id="print_order">
  <div class="modal-dialog modal-xs">
    <div class="modal-content">
      <!-- Modal body -->
      <div class="modal-body">
         <b><h5 align="center" style="text-align: center;font-family: Helvetica, Arial, sans-serif;font-size: 18px"><span id="n_items_print" style="color: blue"></span> ordenes seran impresas y enviadas</h5></b>
         <button type="button" class="btn btn-dark btn-block" onClick="imprimir_ordenes()" id="print_received"><i class="fas fa-print"></i> Enviar e imprimir</button>
      </div>
    </div>
  </div>
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
<?php 
require_once("links_js.php");
?>
<script>
  const element = document.getElementById("print_received");
  element.addEventListener("click", recibirOrdenesbyChk);


function recibirOrdenesbyChk() {
  let usuario = $("#usuario").val();
  $.ajax({
  url:"../ajax/laboratorios.php?op=cambiar_estado_aro_print",
  method:"POST",
  data:{'arrayRCB':JSON.stringify(orders),'usuario':usuario}, 
  cache:false,
  dataType:'json',
  success:function(data){ 
  if (data=='Received') {
    $("#ordenes_pendientes_lab").DataTable().ajax.reload();
  }else{
        Swal.fire({
        position: 'top-center',
        icon: 'error',
        title: 'No ha sido posible registrar',
        showConfirmButton: true,
        timer: 2500
      });
  }       
    
  }
  });


}
</script>
<script type="text/javascript" src="../js/ordenes.js"></script>
<script type="text/javascript" src="../js/laboratorios.js"></script>

<script>
  var dui = new Cleave('#dui_pac', {
  delimiter: '-',
  blocks: [8,1],
  uppercase : true
});

var telefono = new Cleave('#telef_pac', {
  delimiter: '-',
  blocks: [4,4],
  uppercase : true
});
</script>
</body>
</html>
 <?php } else{
echo "Acceso denegado";
  } ?>
