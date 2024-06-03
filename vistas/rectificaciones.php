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
  <title>.::RECTIFICACIONES::.</title>
<?php require_once("links_plugin.php"); 
 require_once('../modelos/Ordenes.php');


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
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <section class="content">
      <div class="container-fluid">
      <input type="hidden" name="id_us" id="id_us" value="<?php echo $_SESSION["id_user"];?>"/>
      <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION["user"];?>"/>
      <input type="hidden" name="categoria" id="get_categoria" value="<?php echo $_SESSION["categoria"];?>"/>
      <div style="border-top: 0px">
      </div>

     <div class="row">
        <div class="col-sm-12"><h5 style="text-align: center">RECTIFICACIONES</h5></div>
      </div>
        <table width="100%" class="table-hover table-bordered" id="data_rectificaciones"  data-order='[[ 0, "desc" ]]'> 
              
         <thead class="style_th bg-dark" style="color: white">
           <th>ID</th>
           <th># Rectif.</th>
           <th>Fecha</th>
           <th>Creada por</th>
           <th>Paciente</th>
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


   <!--Modal Ingreso a laboratorio-->
   <div class="modal" id="modal_procesando_lab" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" style="max-width: 35%">
      <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">ORDENES FINALIZADAS</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>       
        <!-- Modal body -->
        <div class="modal-body">
          <b><h5 style="font-size: 16px;text-align: center">Confirmar que se han finzalizado <span id="count_select"></span> ordenes.</h5></b>
          
        </div>
        <div class="modal-footer">
        <form action="listado_ordenes_finalizadas.php"  method="post" target="_blank">
          <input type="hidden" id="ordenes_imp_finish" name="ordenes_imp_finish" value="">
           <button type="submit" class="btn btn-info" onClick="confirmarSalidaLab();"> Finzalizr</button>
        </form>        
      </div>       
   
      </div>
    </div>
  </div>
  <input type="hidden" id="cat_data_barcode" value="finalizar_lab">
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
