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
 require_once('../modelos/Ordenes.php');
 require_once('../modales/modal_despachos.php');
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
      <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $_SESSION["id_user"];?>"/>
      <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION["user"];?>"/>
      <input type="hidden" name="categoria" id="get_categoria" value="<?php echo $_SESSION["categoria"];?>"/>
      <input type="hidden" name="sucursal" id="sucursal" value="<?php echo $_SESSION["sucursal"];?>"/>
      <div style="border-top: 0px">

      </div>
        <a class="btn btn-app" style="color: black;border: solid #5bc0de 1px;margin-top:5px" onClick="showModalDespachos()">
          <span class="badge bg-primary" id="alert_enviadas_ord"></span>
          <i class="fas fa-shipping-fast" style="color: #0275d8"></i> CREAR DESPACHO
        </a>
      <div class="card card-warning card-outline" style="margin: 2px;margin-top: 0px !important">
      <h5 style="text-align: center; font-size: 16px" align="center" class="bg-dark">LISTADO DE DESPACHOS A LABORATORIO</h5>
       <table width="100%" class="table-bordered" id="data_despachos_suc"  data-order='[[ 0, "desc" ]]' style="font-size: 11px; text-align-center">
     
        <thead class="style_th bg-info" style="color: white">
           <th>ID</th>
           <th>No. despacho</th>
           <th>Fecha</th>
           <th>Enviado por</th>
           <th>Sucursal</th>
           <th>Ordenes</th>
           <th style="text-align: center">Detalles</th>
         </thead>
         <tbody class="style_th" style="padding: 3px;text-align: center;font-size: 11px"></tbody>
       </table>

      </div>

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

  <input type="hidden" value="<?php echo $categoria_usuario;?>" id="cat_users">

   
      <input type="hidden" id="user_act" value="<?php echo $_SESSION["usuario"];?>">
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>2022 Lenti || <b>Version</b> 1.0</strong>
     &nbsp;All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      
    </div>
  </footer>
</div>

<?php

require_once("links_js.php");

?>
<script type="text/javascript" src="../js/despachos.js"></script>
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
