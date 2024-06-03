<?php 
require_once("../config/conexion.php");
if(isset($_SESSION["usuario"])){

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Home</title>
<?php require_once("links_plugin.php"); 

 require_once('../modales/warehouseIncome/modalIngresosTerm.php');
 require_once('../modales/warehouseIncome/modalIngresosTermGeneral.php');
 require_once('../modales/modalNuevaVinetaProducto.php');
 require_once('../modelos/Stock.php');
 $stock = new Stock();
 $tablas_term = $stock->listar_tablas_terminados();

 date_default_timezone_set('America/El_Salvador'); $hoy = date("d-m-Y H-i-s");
 ?>
<style>
  .buttons-excel{
    background-color: green !important;
    margin: 2px;
    max-width: 150px;
  }

  .stilot1{
    border: 1px solid black;
    padding: 5px;
    font-size: 12px;
    font-family: Helvetica, Arial, sans-serif;
    border-collapse: collapse;
    text-align: center;
  }

  .stilot2{
    border: 1px solid black;
    text-align: center;
    font-size: 11px;
    font-family: Helvetica, Arial, sans-serif;
  }
  .stilot3{
    text-align: center;
    font-size: 11px;
    font-family: Helvetica, Arial, sans-serif;
  }

  #table2 {
    border-collapse: collapse;
  }

  .filas:hover {
    background-color: lightyellow;
  }
  td:hover {
  background: #e1f5f4;
  color: black;
}

</style>

  <script src="../plugins/exportoExcel.js"></script>
  <script src="../plugins/keymaster.js"></script>

</head>
<body class="hold-transition sidebar-mini layout-fixed" style='font-family: Helvetica, Arial, sans-serif;'>
<div class="wrapper">
<!-- top-bar -->
  <?php 
 
  require_once('top_menu.php')?>

  <?php require_once('side_bar.php');   
  ?>
  <div class="content-wrapper">
    <section class="content">
      
<table width="100%" class="table-bordered" id="data_ingresos_bodega"  data-order='[[ 0, "asc" ]]' style="font-size: 13px">
      <thead class="style_th bg-info" style="color: white">
           <th>ID</th>
           <th>Fecha</th>
           <th>Usuario</th>
           <th>Caja</th>
           <th>Esfera</th>
           <th>Cilindro</th>
           <th>Cantidad</th>
           <th>Movimiento</th>
        </thead>
         <tbody class="style_th" style="padding: 3px;text-align: center;font-size: 13px"></tbody>
       </table>

    </section>    
  </div>

<input type="hidden" id="tipo_lente_code" value="Terminado">

  <!-- /.content-wrapper -->
<footer class="main-footer">
    <strong>2021 Lenti || <b>Version</b> 1.0</strong>
     &nbsp;All rights reserved.
    <div class="float-right d-none d-sm-inline-block">      
    </div>
    <?php 
require_once("links_js.php");
?>
<script type="text/javascript" src="../js/productos.js"></script>
<script type="text/javascript" src="../js/stock.js"></script>

</footer>
</div>

<!-- ./wrapper -->

</body>
</html>
 <?php } else{
echo "Acceso denegado";
  } ?>


