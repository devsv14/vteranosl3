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
    <?php require_once("links_plugin.php"); ?>
    <?php require_once("../modales/bodega_av_plus/modal_recibir_orden.php"); ?>
    <?php require_once("../modales/nueva_orden_lab.php"); ?>
    <?php require_once("../modales/bodega_av_plus/modal_recib_manual.php"); ?>

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
            <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION["usuario"]; ?>" />
            <input type="hidden" name="categoria" id="get_categoria" value="<?php echo $_SESSION["categoria"]; ?>" />
            <div style="border-top: 0px">
            </div>

            <div class="card mt-4 pt-2 shadow-md">
              <?php include 'bodega/header_status_bodega.php'; ?>
            </div>
            <div class="card">
              <div class="card-header d-flex justify-content-end">
                <button class="btn btn-primary btn-sm" onclick="show_modal_recibir_orden()">
                  <span class="badge bg-success"></span>
                  <i class="fas fa-paste" aria-hidden="true" style="color: #e8eff1;"></i> Recibir
                </button>
                <button class="btn btn-outline-primary btn-sm ml-3" onclick="show_modal_ingreso_manual()">
                  <span class="badge bg-success"></span>
                  <i class="fas fa-paste" aria-hidden="true"></i> Recib.Manual
                </button>
              </div>
              <div class="card-body">
                <table width="100%" class="table-hover table-bordered" id="dt_ordenes_recibidas" data-order='[[ 0, "desc" ]]'>
                  <thead class="style_th bg-dark" style="color: white">
                    <th>Correlativo</th>
                    <th>Codigo orden</th>
                    <th>Fecha</th>
                    <th>DUI</th>
                    <th>Paciente</th>
                    <th>Tipo lente</th>
                    <th>Institución</th>
                    <th>Estado</th>
                    <th>Sucursal</th>
                    <th>Detalles</th>
                  </thead>
                  <tbody class="style_th"></tbody>
                </table>
              </div>
            </div>

        </section>
        <!-- /.content -->
      </div>

      <input type="hidden" value="<?php echo $categoria_usuario; ?>" id="cat_users">
      <input type="hidden" id="tipo_accion" value="recibido_bog">
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
    <script type="text/javascript" src="../js/bodega_avplus.js?v=<?php echo rand() ?>"></script>
    <script type="text/javascript" src="../js/ordenes.js?v=<?php echo rand() ?>"></script>
  </body>

  </html>
<?php } else {
  echo "Acceso denegado";
} ?>

<script>
  //Subtitle page
  $("#subtitle_bod").html('General')
</script>