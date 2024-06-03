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
 //$suc = $ordenes->get_opticas();
// require_once('../modales/nuevo_aro.php');
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
       <table width="100%" class="table-hover table-bordered table-striped" id="datable_aros_inv"  data-order='[[ 0, "desc" ]]'>        
       <h5 style="text-align: center;background:#034f84;color:white;font-family: Helvetica, Arial, sans-serif;font-size: 16px;">PROGRESSIVE RIGTH</h5>
          <?php $pruebas->progresive();?>

       </table>
      </div>

      <div class="card card-dark card-outline" style="margin: 2px;">
       <table width="100%" class="table-hover table-bordered table-striped" id="datable_aros_inv"  data-order='[[ 0, "desc" ]]'>        
       <h5 style="text-align: center;background:#034f84;color:white;font-family: Helvetica, Arial, sans-serif;font-size: 16px;">PROGRESSIVE LEFT</h5>
          <?php $pruebas->progresiveOI();?>

       </table>
      </div>

      <div class="card card-dark card-outline" style="margin: 2px;">
       <table width="100%" class="table-hover table-bordered table-striped" id="datable_aros_inv"  data-order='[[ 0, "desc" ]]'>        
       <h5 style="text-align: center;background:#034f84;color:white;font-family: Helvetica, Arial, sans-serif;font-size: 16px;">FLAPTOP RIGTH</h5>
          <?php $pruebas->flaptopOd();?>

       </table>
      </div>

    <div class="card card-dark card-outline" style="margin: 2px;">
       <table width="100%" class="table-hover table-bordered table-striped" id="datable_aros_inv"  data-order='[[ 0, "desc" ]]'>        
       <h5 style="text-align: center;background:#034f84;color:white;font-family: Helvetica, Arial, sans-serif;font-size: 16px;">FLAPTOP LEFT</h5>
          <?php $pruebas->flaptopOI();?>
       </table>
      </div>
      
    <div class="card card-dark card-outline" style="margin: 2px;">
       <table width="100%" class="table-hover table-bordered table-striped" id="datable_aros_inv"  data-order='[[ 0, "desc" ]]'>        
       <h5 style="text-align: center;background:#034f84;color:white;font-family: Helvetica, Arial, sans-serif;font-size: 16px;">VISION SENCILLA LENTI</h5>
          <?php $pruebas->VisionSencilla();?>
       </table>
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
