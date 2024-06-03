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
 require_once('../modelos/Pruebas.php');
 $pruebas = new Pruebas();
 $laboratorio = $_POST["laboratorio"];
 $tipo_lente = $_POST["tipo_lente"];
 $base = $_POST["base"];
 $inicio = $_POST["inicio"];
 $fin = $_POST["fin"];

 ?>
<style>
  .buttons-excel{
      background-color: green !important;
      margin: 2px;
      max-width: 150px;
  }
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

  .fila:hover {
    background-color: lightyellow;
  }
</style>
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
    <div style="border-top: 0px">
    </div>

    <div class="card card-dark card-outline" style="margin: 2px;">
      

          <?php 

          if($laboratorio=="Jenny" and $tipo_lente=="Progresive"){?>
          <table width="100%" class="table-hover table-bordered table-striped" id="datable_aros_inv"  data-order='[[ 0, "desc" ]]'>
            <h5 style="text-align: center;background:#034f84;color:white;font-family: Helvetica, Arial, sans-serif;font-size: 16px;">PROGRESSIVE RIGTH <?php echo strtoupper($laboratorio)." Desde:".date("d-m-Y",strtotime($inicio))." Hasta:".date("d-m-Y",strtotime($fin));?></h5>
            <?php $pruebas->progresive($inicio,$fin);?>
          </table>
          <br><br>
          <table width="100%" class="table-hover table-bordered table-striped" id="datable_aros_inv"  data-order='[[ 0, "desc" ]]'>
          <h5 style="text-align: center;background:#034f84;color:white;font-family: Helvetica, Arial, sans-serif;font-size: 16px">PROGRESSIVE LEFT <?php echo strtoupper($laboratorio)." Desde:".date("d-m-Y",strtotime($inicio))." Hasta:".date("d-m-Y",strtotime($fin));?></h5>
            <?php $pruebas->progresiveOI($inicio,$fin);?>
          </table>
         <?php }elseif ($laboratorio=="Jenny" and $tipo_lente=="Flaptop") {?>
         <table width="100%" class="table-hover table-bordered table-striped" id="datable_aros_inv"  data-order='[[ 0, "desc" ]]'>
            <h5 style="text-align: center;background:#034f84;color:white;font-family: Helvetica, Arial, sans-serif;font-size: 16px;">FLAPTOP RIGTH <?php echo strtoupper($laboratorio)." Desde:".date("d-m-Y",strtotime($inicio))." Hasta:".date("d-m-Y",strtotime($fin));?></h5>
            <?php $pruebas->flaptopOd($inicio,$fin);?>
          </table>
          <br><br>
          <table width="100%" class="table-hover table-bordered table-striped" id="datable_aros_inv"  data-order='[[ 0, "desc" ]]'>
          <h5 style="text-align: center;background:#034f84;color:white;font-family: Helvetica, Arial, sans-serif;font-size: 16px">FLAPTOP LEFT <?php echo strtoupper($laboratorio)." Desde:".$inicio." Hasta:".date("d-m-Y",strtotime($fin));?></h5>
            <?php $pruebas->flaptopOi($inicio,$fin);?>
          </table>
         <?php } ?> 

      </div>

    </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div> 
</div>
   <!--Modal Imagen Aro-->
   <div class="modal" id="verImg">
    <div class="modal-dialog" style="max-width: 45%">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
          <div style="  background-size: cover;background-position: center;display:flex;align-items: center;">
            <img src="" alt="" id="imagen_aro_v" style="width: 100%;border-radius: 8px;">
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
<script type="text/javascript" src="../js/productos.js"></script>
<script type="text/javascript" src="../js/autocomplete.js"></script>

</body>
</html>
 <?php } else{
echo "Acceso denegado";
  } ?>
