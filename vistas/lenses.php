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
      <?php require_once('ordenes/header_status_orders.php');?>

      <div class="card card-warning card-outline" style="margin: 2px;margin-top: 0px !important">
      <h5 style="text-align: center; font-size: 16px" align="center" class="bg-dark">ORDERS RECEIVED</h5>
       <table width="100%" class="table-bordered" id="data_lenses_received"  data-order='[[ 0, "desc" ]]' style="font-size: 11px">        
         <thead class="style_th bg-primary" style="color: white;font-size: 11px">
           <th>ID</th>
           <th style="text-align: center;vertical-align: middle;"><input type="checkbox" value="" class="" style="margin-top: 3px"  data-toggle="tooltip" title="Received All" id="received_all"></th>
           <th>Right</th>
           <th>Left</th>
           <th>Lense</th>
         </thead>
         <tbody class="style_th" style="padding: 3px;text-align: left"></tbody>
       </table>
      <button type="button" class="btn btn-dark btn-block send_orden" onClick="received_confirm_v();" id="btn_enviar_lab"><i class="fas fa-download" style="border-radius: 0px;margin: 5px"></i> RECEIVED</button>
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
<div class="modal fade" id="received_envio_ord">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-body">
      <div class="dropdown-divider"></div>
        <h5 style="font-family: Helvetica, Arial, sans-serif;font-size: 18px;text-align: center;"><b><span id="n_orders_received"></span></b></span> orders received</h5>
        </div>
          <div>
            <div id="download_received" style="margin:15px">
              <button type="button" class="btn btn-success btn-block" onClick="downloadAsExcel()"><i class="fas fa-file-excel"></i> Download excel</i></button>
            </div>
            <div id="btns_received" class="modal-footer justify-content-between">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-primary" onClick="registerReceived()">Confirm</button>
            </div>

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

<div class="modal fade" id="modal-overlay">
  <div class="modal-dialog" style="max-width: 25%">
     <div class="modal-content">
      <div class="overlay">
          <i class="fas fa-2x fa-sync fa-spin" style="color: green"></i>
      </div>
      <div class="modal-body">
        <p>Wait Generating Excel...&hellip;</p>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- ./wrapper -->
<?php 
require_once("links_js.php");
?>
<script src="json-excel.js"></script>
<script type="text/javascript" src="../js/lens.js"></script>
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
