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
 require_once('../modelos/Orders.php');
 $ordenes = new Ordenes();
 //$suc = $ordenes->get_opticas();
 require_once('../modales/nueva_orden_lab.php');
 require_once('../modales/aros_en_orden.php');

 ?>
<style>
  .buttons-excel{
      background-color: #292b2c !important;
      margin: 2px;
      max-width: 150px;
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

      <div class="card">
        <div class="card-body">

          <a class="btn btn-app btn-info" onClick="listar_pendientes();" style="color:black;border:solid #5bc0de 1px;">
            <span class="badge btn btn-outline-warning" id="alert_creadas_ord;"></span>
            <i class="fas fa-sign-in-alt" style="color:#f0ad4e"></i> PENDIENTES
          </a>

          <a class="btn btn-app" onClick="listar_ordenes_proceso();" style="color:black;border:solid #5bc0de 1px;">
            <span class="badge bg-success" id="alert_creadas_ord;"></span>
            <i class="fas fa-sync-alt" style="color:#20B320"></i> PROCESO
          </a>

          <a class="btn btn-app" onClick="listar_ordenes_enviadas();" style="color:black;border:solid #5bc0de 1px;">
            <span class="badge bg-info" id="alert_enviadas_ord"></span>
            <i class="fas fa-sign-out-alt" style="color:#2366E3"></i> ENVIADOS
          </a>

        </div>
        
      <div class="card card-dark card-outline" style="margin: 2px;">
        <div>
          <div class="row">
            <div class="col-sm-3">
              <label for="" class="etiqueta">Desde</label>
              <input type="date" class="form-control clear_orden_i" id="desde_env_aros">
            </div>
            <div class="col-sm-3">
              <label for="" class="etiqueta">Hasta</label>
              <input type="date" class="form-control clear_orden_i" id="hasta_env_aros">
            </div>
            <div class="col-sm-2">
              <label for="" class="etiqueta">Buscar</label>
              <button class="btn btn-block btn-outline-info btn-flat" onClick="buscarRangoAros()"><i class="fas fa-search"></i> Por rango</button>
            </div>
            <div class="col-sm-2">
              <label for="" class="etiqueta">Buscar</label>
              <button class="btn btn-block btn-outline-dark btn-flat" onClick="listar_ordenes()"><i class="fas fa-search"></i> Todos</button>
            </div>
          </div>
       <table width="100%" class="table-hover table-bordered" id="datatable_ordenes_china" data-order='[[ 5, "desc" ]]'>        
         <thead class="style_th bg-dark" style="color: white">
           <th>Marca</th>
           <th>Modelo</th>
           <th>Horizontal(mm)</th>
           <th>Vertical(mm)</th>
           <th>Puente(mm)</th>
           <th>Cantidad</th>
           <th>Aro</th>
         </thead>
         <tbody class="style_th"></tbody>
       </table>
      </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

  <input type="hidden" value="<?php echo $categoria_usuario;?>" id="cat_users">

   <!--Modal Imagen Aro-->
   <div class="modal" id="imagen_aro_order">
    <div class="modal-dialog" style="max-width: 45%">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
          <div style="  background-size: cover;background-position: center;display:flex;align-items: center;">
            <img src="" alt="" id="imagen_aro_ord" style="width: 100%;border-radius: 8px;">
          </div>
         <div class="row" style="margin-top: 5px">

         <div class="col-sm-6">

        <input type="text" class="form-control" id="cant_enviar" placeholder="cantidad enviar">
         </div>

         <div class="col-sm-6">
           <select name="" id="dest_send_aro" class="form-control">
            <option value="0" selected>Enviar a...</option>
            <option value="Jenny">Jenny</option>
            <option value="Lenti">Lenti</option>
            <option value="Arce">Arce</option>
           </select>
         </div>

       </div>
       Modelo: <span id="modelo_send"></span>
       <input type="hidden" id="horizontal_send">
       <input type="hidden" id="vertical_send">
       <input type="hidden" id="puente_send">
       <div style="margin-top: 8px">
         <button class="btn btn-primary btn-block" style="border-radius: 0px" onClick="send_aros()"><i class="fas fas fa-location-arrow"></i> Enviar</button>
       </div>
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
<script type="text/javascript" src="../js/orders.js"></script>
<!--<script type="text/javascript" src="../js/productos.js"></script>-->
<script type="text/javascript" src="../js/cleave.js"></script>
<script>
  var dui = new Cleave('#dui_pac', {
  delimiter: '-',
  blocks: [8,1],
  uppercase : true
});
</script>
</body>
</html>
 <?php } else{
echo "Acceso denegado";
  } ?>
