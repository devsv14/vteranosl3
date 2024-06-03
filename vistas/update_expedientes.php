<?php
require_once("../config/conexion.php");
if (isset($_SESSION["user"])) {
  $categoria_usuario = $_SESSION["categoria"];
  date_default_timezone_set('America/El_Salvador');
  $hoy = date("d-m-Y H-i-s");
?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Actualiza Exp.</title>

    <?php

     require_once("links_plugin.php");
    ?>

    <style>
      .buttons-excel {
        margin: 2px;
        max-width: 150px;
      }
      .custom-file-input{
        margin:5px !important
      }
 

    </style>

  </head>

  <body class="hold-transition sidebar-mini layout-fixed" style='font-family: Helvetica, Arial, sans-serif;'>
    <div class="wrapper">

      <!-- top-bar -->
      <?php require_once('top_menu.php') ?>
      <?php require_once('side_bar.php') ?>
      <!--End SideBar Container-->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <section class="content">

          <div class="card  card card-info card-outline"> 
         <div class="row form-row">
          <div class="col-sm-3">
            <label for="sel1">Sucursal</label>
            <select class="form-control" id="sucursal-update" onchange='getDataFactura()'>
                <option value="0">Seleccionar suc...</option>
                <option value="Metrocentro">Metrocentro</option>
                <option value="Valencia">Valencia</option>
                <option value="San Miguel">San Miguel</option>
                <option value="Cascadas">Cascadas</option>
                <option value="Ahuachapan">Ahuachapan</option>
                <option value="Apopa">Apopa</option>
                <option value="Santa Ana">Santa Ana</option>
                <option value="Ciudad Arce">Ciudad Arce</option>
                <option value="Chalatenango">Chalatenango</option>
                <option value="San Vicente">San Vicente</option>
                <option value="San Vicente Centro">San Vicente Centro</option>
                <option value="Usulutan">Usulutan</option>
                <option value="Gotera">Gotera</option>
                <option value="Sonsonate">Sonsonate</option>
                <option value="San Miguel AV PLUS">San Miguel AV PLUS</option>
                <option value="Jornada San Miguel">Jornada San Miguel</option>
                <option value="Jornada Santa Ana">Jornada Santa Ana</option>
                <option value="Jornada Rancho Quemado">Jornada Rancho Quemado</option>
                <option value="Jornada Potonico">Jornada Potonico</option>
                <option value="Jornada San Vicente">Jornada San Vicente</option>
                <option value="Jornada Meanguera">Jornada Meanguera</option>
                <option value="Jornada Conchagua">Jornada Conchagua</option>
                <option value="Jornada Sonsonate">Jornada Sonsonate</option>
                <option value="Jornada Meanguera 2">Jornada Meanguera 2</option>
            </select>
           </div>

           <div class="col-sm-3">
            <label for="sel1">Factura</label>
            <select class="form-control" id="factura-update" onchange='getDataFactura()'>
                <option value="0">Seleccionar factura.......</option>
                <option value="1">Factura 1</option>
                <option value="2">Factura 2</option>
                <option value="3">Factura 3</option>
                <option value="4">Factura 4</option>
                <option value="5">Factura 5</option>
                <option value="5">Factura 6</option>
                <option value="6">Factura 7</option>
            </select>
           </div>

           </div>

            <table width="100%" class="table-responsive-sm table-hover table-bordered" id="table-update-exp" data-order='[[ 0, "asc" ]]' style="font-size:12px;margin-top:5px">
 
            </table>

            
          </div>
        </section>
        <!-- /.content -->
        <style>
            .modal-content-container {
            max-height: 500px; /* Fija la altura máxima de la modal */
            overflow-y: auto; /* Habilita el scroll vertical */
            }
            .table-responsive-sm thead th {
            position: sticky;
            top: 0;
            z-index: 1;
           
            }
        </style>
        <div class="modal" id="modal-expediente">
        <div class="modal-dialog" style='max-width:80%'>
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header py-2">
                <h4 class="modal-title" id='head-modal-update-exp' style='font-size:14px'> </h4>
                <div class="d-flex justify-content-end align-items-center">
                <span class="badge badge-info mx-2">Diferencia: <i id="difExp"></i></span> <span class="badge badge-success">Agregados: <b id="countExpUpd"></b></span>
                <button class="btn btn-outline-success btn-xs mx-2" onclick="procesarOrdExp()">Actualizar</button>
                </div>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body modal-content-container" >
            <table width="100%" class="table-responsive-sm table-hover table-bordered table-responsive-sm" id="table-listar-exced" data-order='[[ 0, "asc" ]]' style="font-size:12px;margin-top:5px;text-align:center">
            <thead class="style_th bg-info" style="color: white">
                <th>Sel.</th>
                <th>Paciente</th>
                <th>DUI</th>
                <th>Sucursal</th>
                <th>Fecha/Nueva Fecha</th>
                <th>Acciones</th>
            </thead>
            </table>
            </div>



    </div>
  </div>
</div><!-- Fin modal -->

<!-- MODAL ACT GRADUACIONES -->
<div class="modal" id="modal-update-expediente">
        <div class="modal-dialog" style='max-width:80%'>
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title" id='head-modal-update-exp' style='font-size:14px'></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body modal-content-container" >
            <table width="100%" class="table-responsive-sm table-hover table-bordered table-responsive-sm" id="table-listar-exced" data-order='[[ 0, "asc" ]]' style="font-size:12px;margin-top:5px;text-align:center">
            <thead class="style_th bg-info" style="color: white">
                <th>Sel.</th>
                <th>Paciente</th>
                <th>DUI</th>
                <th>Sucursal</th>
                <th>Fecha/Nueva Fecha</th>
                <th>Acciones</th>
            </thead>
            </table>
            </div>



    </div>
  </div>
</div><!-- Fin modal -->
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

    <script type="text/javascript" src="../js/update_expedientes.js?v=<?php echo rand() ?>"></script>
    <script type="text/javascript" src="../js/cleave.js"></script>
  </body>

  </html>
<?php } else {
  echo "Acceso denegado";
} ?>

<script>
   $(".modal-header").on("mousedown", function (mousedownEvt) {
    let $draggable = $(this);
    let x = mousedownEvt.pageX - $draggable.offset().left,
      y = mousedownEvt.pageY - $draggable.offset().top;
    $("body").on("mousemove.draggable", function (mousemoveEvt) {
      $draggable.closest(".modal-dialog").offset({
        "left": mousemoveEvt.pageX - x,
        "top": mousemoveEvt.pageY - y
      });
    });
    $("body").one("mouseup", function () {
      $("body").off("mousemove.draggable");
    });
    $draggable.closest(".modal").one("bs.modal.hide", function () {
      $("body").off("mousemove.draggable");
    });
  });
</script>