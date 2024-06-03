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
    require_once('../modales/nueva_orden_lab.php');
    //Modal para recibir ordenes de bodega
    require_once('../modales/modal_recibir_ordenes_bodega.php');

    ?>
    <style>
      .buttons-excel {
        /*background-color: green !important;*/
        margin: 2px;
        max-width: 150px;
      }

      .form-check_selected:disabled {
        background-color: #0275d8 !important;
      }

      /*
      MEDIA QUERIES
      */
      @media (max-width: 700px) {
        .content-btn-center-sm {
          display: flex;
          justify-content: center;
          align-items: center;
        }
      }
    </style>
    <script src="../plugins/exportoExcel.js"></script>
    <script src="../plugins/keymaster.js"></script>
  </head>

  <body class="hold-transition sidebar-mini layout-fixed" style='font-family: Helvetica, Arial, sans-serif;'>
    <div class="wrapper">
      <input type="hidden" id="cod_envio" name="cod_envio">
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
            <h5 style="text-align: center">ORDENES FINALIZADAS</h5>
            <div class="d-flex justify-content-end content-btn-center-sm">
              <div class="content_filtro mx-2">
                <select class="form-control" id="filtro_sucursal" onchange="filtro_suc_ordenes_fin()">
                  <option value="">Seleccionar sucursal...</option>
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
                  <option value="San Miguel AV PLUS">San Miguel AV PLUS</option>
                  <option value="Jornada Rancho Quemado">Jornada Rancho Quemado</option>
                  <option value="Jornada San Miguel">Jornada San Miguel</option>
                  <option value="Jornada Potonico">Jornada Potonico</option>
                  <option value="Jornada Conchagua">Jornada Conchagua</option>
                  <option value="Jornada Santa Ana">Jornada Santa Ana</option>
                  <option value="Jornada Meanguera">Jornada Meanguera</option>
                  <option value="Jornada San Vicente">Jornada San Vicente</option>
                  <option value="Jornada Sonsonate">Jornada Sonsonate</option>
                  <option value="Jornada Meanguera 2">Jornada Meanguera 2</option>
                </select>
              </div>
              <button onclick="recibir_ordenes_bodega()" type="button" class="btn btn-outline-info"><i class="fal fa-box-check"></i>Recibir Bog.</button>
              <button class="btn btn-success barcode_actions_chk mx-3" onClick='envioOrrdenesCheck()'><i class="fas fa-shipping-fast"></i> Enviar</button>

            </div>
            <table width="100%" class="table-hover table-bordered" id="ordenes_finalizadas_lab" data-order='[[ 0, "desc" ]]'>
              <thead class="style_th bg-dark" style="color: white">
                <th>
                  <div class="icheck-success d-inline">
                    <input type="checkbox" onchange="selected_all_orden()" id="check_all">
                    <label for="check_all">Select.</label>
                  </div>
                </th>
                <th>ID</th>
                <th>Codigo</th>
                <th>Fecha Fin.</th>
                <th>DUI</th>
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
      <input type="hidden" id="tipo_accion" value="recibir_bodega">
      <input type="hidden" id="cat_data_barcode" value="finalizar_lab">
      <input type="hidden" id='fecha_envios_veteranos_i' value="<?php echo $hoy; ?>">
      <!-- /.content-wrapper -->
      <footer class="main-footer">
        <strong>2021 Lenti || <b>Version</b> 1.0</strong>
        &nbsp;All rights reserved.
        <div class="float-right d-none d-sm-inline-block">

        </div>
      </footer>
    </div>

    <!--ENVIO DE ITEMS POR CHECKBOX-->
    <div class="modal" id="envios_chk" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog" style="max-width: 85%">
        <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header" style="background: #162e41;color: white">
            <h4 class="modal-title" style="font-size: 14px;font-family: Helvetica, Arial, sans-serif;"><b><span id="c_accion"></span></b></h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <!-- Modal body -->
          <div class="modal-body">
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="check_ingreso_id">
              <label class="form-check-label" for="check_ingreso_id">Ingreso por ID</label>
            </div>
            <input type="text" class="form-control" id="reg_ingresos_barcode" onchange="getOrdenBarcode()">

            <button type="button" class="btn btn-default float-right btn-sm " onClick="registrarBarcodeOrdenes()" style='margin: 3px'><i class=" fas fa-file-export" style="color: #0275d8"></i> Registrar</button>

            <table class="table-hover table-bordered" style="font-family: Helvetica, Arial, sans-serif;max-width: 100%;text-align: left;margin-top: 5px !important" width="100%" id="tabla_acciones_veterans">

              <thead style="font-family: Helvetica, Arial, sans-serif;width: 100%;text-align: center;font-size: 12px;" class="bg-dark">
                <th>ID</th>
                <th>#Orden</th>
                <th>Fecha</th>
                <th>DUI</th>
                <th>Paciente</th>
                <th>Cod. Envio</th>
                <th>Sucursal</th>
                <th>Eliminar</th>
              </thead>
              <tbody id="items-ordenes-barcode" style="font-size: 12px"></tbody>
            </table>

          </div>
          <!-- Modal footer -->

        </div>
      </div>
    </div>


    <!-- ./wrapper -->
    <?php
    require_once("links_js.php");
    ?>
    <script type="text/javascript" src="../js/laboratorios.js?v=<?php echo rand() ?>"></script>
    <script type="text/javascript" src="../js/ordenes.js?v=<?php echo rand() ?>"></script>

  </body>

  </html>
<?php } else {
  echo "Acceso denegado";
} ?>