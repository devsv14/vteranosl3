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
    <title>Home</title>
    <?php require_once("links_plugin.php");
    require_once('../modelos/Ordenes.php');


    require_once('../modales/modal_ingresos_lab.php');
    require_once('../modales/nueva_orden_lab.php');
    require_once('../modales/aros_en_orden.php');

    ?>

    <style>
      .buttons-excel {
        margin: 2px;
        max-width: 150px;
      }
    </style>
    <script src="../plugins/exportoExcel.js"></script>
    <script src="../plugins/keymaster.js"></script>
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
          <div class="container-fluid">
            <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $_SESSION["id_user"]; ?>" />
            <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION["user"]; ?>" />
            <input type="hidden" name="categoria" id="get_categoria" value="<?php echo $_SESSION["categoria"]; ?>" />
            <div style="border-top: 0px">
            </div>

            <?php include 'ordenes/header_status_lab.php'; ?>
            <h5 style="text-align: center">ORDENES EN PROCESO</h5>
            <div class="d-flex justify-content-end align-items-center" style="margin-bottom: 5px !important">
              <div class="col-md-2">
                <select class="form-control" onchange="filter_sucursal()" id="filter_sucursal">
                  <option value="" selected>Select. Sucursal</option>
                  <option value="Valencia">Valencia</option>
                  <option value="Metrocentro">Metrocentro</option>
                  <option value="Cascadas">Cascadas</option>
                  <option value="Santa Ana">Santa Ana</option>
                  <option value="Chalatenango">Chalatenango</option>
                  <option value="Ahuachapan">Ahuachapan</option>
                  <option value="Sonsonate">Sonsonate</option>
                  <option value="Ciudad Arce">Ciudad Arce</option>
                  <option value="Opico">Opico</option>
                  <option value="Apopa">Apopa</option>
                  <option value="San Vicente Centro">San Vicente Centro</option>
                  <option value="San Vicente">San Vicente</option>
                  <option value="Gotera">Gotera</option>
                  <option value="San Miguel">San Miguel</option>
                  <option value="Usulutan">Usulutan</option>
                </select>
              </div>

                <button class="btn btn-outline-success ml-2" class="btn btn-info barcode_actions" data-toggle="modal" data-target="#barcode_ingresos_lab" onClick='input_focus_clearb()'><i class="fas fa-clipboard-check"></i> Finalizar</button>
            </div>
            <table width="100%" class="table-hover table-bordered" id="ordenes_procesando_lab" data-order='[[ 0, "desc" ]]'>

              <thead class="style_th bg-dark" style="color: white">
                <th>#</th>
                <th>ID orden</th>
                <th>Codigo</th>
                <th>Fecha</th>
                <th>Dui</th>
                <th>Paciente</th>
                <th>Sucursal</th>
                <th>Tipo lente</th>
                <th>Detalles</th>
              </thead>
              <tbody class="style_th"></tbody>
            </table>

        </section>
        <!-- /.content -->
      </div>

      <input type="hidden" value="<?php echo $categoria_usuario; ?>" id="cat_users">

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
              <b>
                <h5 style="font-size: 16px;text-align: center">Confirmar que se han finzalizado <span id="count_select"></span> ordenes.</h5>
              </b>

            </div>
            <div class="modal-footer">
              <form action="listado_ordenes_finalizadas.php" method="post" target="_blank">
                <input type="hidden" id="ordenes_imp_finish" name="ordenes_imp_finish" value="">
                <button type="submit" class="btn btn-info" onClick="confirmarSalidaLab();"> Finzalizr</button>
              </form>
            </div>

          </div>
        </div>
      </div>
      <input type="hidden" id="cat_data_barcode" value="en_proceso_lab">
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
    <script type="text/javascript" src="../js/laboratorios.js?v=<?php echo rand() ?>"></script>
    <script type="text/javascript" src="../js/ordenes.js"></script>

  </body>

  </html>
<?php } else {
  echo "Acceso denegado";
} ?>