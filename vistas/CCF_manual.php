<?php 
require_once("../config/conexion.php");
if(isset($_SESSION["user"])){
$categoria_usuario = $_SESSION["categoria"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Home</title>
<?php require_once("links_plugin.php"); 

 require_once('../modales/modal_CCF_manual.php');

 date_default_timezone_set('America/El_Salvador');
 $hoy = date("Y-m-d");

 ?>
<style>
  .buttons-excel{
      background-color: green !important;
      margin: 2px;
      max-width: 150px;
  }
</style>
</head>
<body class="hold-transition sidebar-mini layout-fixed" style='font-family: Helvetica, Arial, sans-serif;'>
<div class="wrapper">
<!-- top-bar -->
  <?php require_once('top_menu.php')?>
  <!-- Main Sidebar Container -->
  <?php require_once('side_bar.php')?>
  <!--End SideBar Container-->
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <section class="content">
      <div class="container-fluid">
      <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $_SESSION["id_user"];?>"/>
      <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION["user"];?>"/>
      <input type="hidden" name="categoria" id="get_categoria" value="<?php echo $_SESSION["categoria"];?>"/>
      <input type="hidden" name="sucursal" id="sucursal" value="<?php echo $_SESSION["sucursal"];?>"/>
      <div style="border-top: 0px">      
      </div>
      <br>
      <button class="btn btn-outline-success btn-sm btn-flat rounded" style="text-align:center;font-size: 16px; display:flex; align-items:center; justify-content:space-between;" onClick='CFF_manual();' id="btn_factura_manual"><i class="nav-icon fas fa-file-invoice-dollar" style="margin-right: 5px;"></i></i> Nuevo CCF</button>


      <div class="card card-dark card-outline" style="margin: 2px;">
       <table width="100%" class="table-hover table-bordered" id="datatable_factura_ccf_manual"  data-order='[[ 0, "desc" ]]'>    
         <thead class="style_th bg-dark" style="color: white">
           <th>Correlativo</th>
           <th>ID</th>
            <th>No. Factura</th>
            <th>No. Registro</th>
            <th>Fecha</th>
            <th>Cliente</th>
            <th>Dirección</th>
            <th>Telefono</th>
           <th>Acciones</th>
         </thead>
         <tbody class="style_th"></tbody>
       </table>
      </div>

      </div><!-- /.container-fluid -->
    </section>
    <!-- /..content -->
  </div>

  <input type="hidden" value="<?php echo $categoria_usuario;?>" id="cat_users">
  <input type="hidden" id="fecha_act" value="<?php echo $hoy;?>">
  <input type="hidden" id="id_factura" class="clear_input">
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
<script type="text/javascript" src="js/bootbox.min.js"></script>
<script type="text/javascript" src="../js/cleave.js"></script>
<script type="text/javascript" src="../js/facturas.js?v=<?php echo rand() ?>"></script>

</body>
</html>
<script>
  var telefono = new Cleave('#tel', {
  delimiter: '-',
  blocks: [4,4],
  uppercase : true
  });
  var telefono = new Cleave('#nit', {
  delimiter: '-',
  blocks: [4,6,3,1],
  uppercase : true
  });
</script>

 <?php } else{
echo "Acceso denegado";
  } ?>
