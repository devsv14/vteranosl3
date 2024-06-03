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
      
      <div class="row row-term" style="margin-top: 5px"><input type="hidden" id="user_term" value="<?php echo $_SESSION["usuario"]; ?>">

      <?php

        foreach ($tablas_term as $value) {
          $id_tabla = $value['id_tabla_term'];
          ($id_tabla % 2 == 0) ? $color='dark': $color='primary';
          ($id_tabla % 2 == 0) ? $borde='#292b2c': $borde='#5bc0de';
        ?>
            <div class="col-md-12" id="sphgreen">
            <div class="card card-<?php echo $color;?> collapsed-card" style="border: solid 1px <?php echo $borde;?>">
              <div class="card-header">
                <h5 class="card-title" style="font-size: 14px"><?php echo "(ID: ".$value["id_tabla_term"].") -> ".$value['titulo']; ?></h5>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" onClick="get_dataTableTerm('<?php echo $value['id_tabla_term'];?>','<?php echo 'tabla_term'.$value['id_tabla_term'];?>');"><i class="fas fa-plus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" onClick="get_dataTableTerm('<?php echo $value['id_tabla_term'];?>','<?php echo 'tabla_term'.$value['id_tabla_term'];?>');"><i class="fas fa-sync-alt"></i></button>
                  <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
                  <button type="button" class="btn btn-tool" onClick="downloadExcelTerm('term_tabla_download_<?php echo $value['id_tabla_term'];?>','<?php echo $value['marca'].$value['diseno']; ?>','<?php echo $hoy;?>')"><i class="fas fa-file-excel"></i>
                   <button type="button" data-toggle="tooltip" title="Ingresar a inventario" class="btn btn-tool" onClick="ingresosGeneral();"><i class="far fa-arrow-alt-circle-down"></i>
                  </button>
                  </button>                  
                </div><!-- /.card-tools -->                
              </div><!--./Card header-->

              <div class="card-body" id="<?php echo 'tabla_term'.$value['id_tabla_term'];?>">
              <!--Aqui iran las tablas de cada seccion-->
              </div>
              
            </div><!--./card-->            
          </div><!--./col-md-->
          
      <?php } ?>

      </div><!--./Row term-->
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


<script>
//Make the DIV element draggagle:
//dragElement(document.getElementById("sphgreen"));

function dragElement(elmnt) {
  var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
  if (document.getElementById(elmnt.id + "header")) {
    /* if present, the header is where you move the DIV from:*/
    document.getElementById(elmnt.id + "header").onmousedown = dragMouseDown;
  } else {
    /* otherwise, move the DIV from anywhere inside the DIV:*/
    elmnt.onmousedown = dragMouseDown;
  }

  function dragMouseDown(e) {
    e = e || window.event;
    e.preventDefault();
    // get the mouse cursor position at startup:
    pos3 = e.clientX;
    pos4 = e.clientY;
    document.onmouseup = closeDragElement;
    // call a function whenever the cursor moves:
    document.onmousemove = elementDrag;
  }

  function elementDrag(e) {
    e = e || window.event;
    e.preventDefault();
    // calculate the new cursor position:
    pos1 = pos3 - e.clientX;
    pos2 = pos4 - e.clientY;
    pos3 = e.clientX;
    pos4 = e.clientY;
    // set the element's new position:
    elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
    elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
  }

  function closeDragElement() {
    /* stop moving when mouse button is released:*/
    document.onmouseup = null;
    document.onmousemove = null;
  }
}

</script>
</footer>
</div>

<!-- ./wrapper -->

</body>
</html>
 <?php } else{
echo "Acceso denegado";
  } ?>


