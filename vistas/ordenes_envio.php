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
    $ordenes = new Ordenes();
    //$suc = $ordenes->get_opticas();
    require_once('../modales/modal_ingresos_lab.php');
    require_once('../modales/nueva_orden_lab.php');
    require_once('../modales/aros_en_orden.php');
    require_once('../modales/modal_ordenes_envio.php');

    ?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap');
          .total{
            text-align: right;
            font-weight: bold;
            font-family: 'Roboto', sans-serif;
        }
      .buttons-excel {
        /*background-color: green !important;*/
        margin: 2px;
        max-width: 150px;
      }
    </style>
    <script src="../plugins/exportoExcel.js"></script>
    <script src="../plugins/keymaster.js"></script>
  </head>

  <body class="hold-transition sidebar-mini layout-fixed" style='font-family: Helvetica, Arial, sans-serif;'>
    <div class="wrapper">
      <input type="hidden" id="correlativo_acc_vet" name="correlativo_acc_vet">
      <!-- top-bar -->
      <?php require_once('top_menu.php') ?>
      <?php require_once('side_bar.php') ?>
      <!--End SideBar Container-->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <section class="content">
          <div class="container-fluid">
            <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $_SESSION["id_usuario"]; ?>" />
            <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION["usuario"]; ?>" />
            <input type="hidden" name="categoria" id="get_categoria" value="<?php echo $_SESSION["categoria"]; ?>" />
            <div style="border-top: 0px">
            </div>

            <?php include 'ordenes/header_status_lab.php'; ?>
            <div class="row">
              <div class="col-sm-12">
                <h5 style="text-align: center">ORDENES ENVIADAS</h5>
              </div>
            </div>
            <table width="100%" class="table-hover table-bordered" id="ordenes-de-envio" data-order='[[ 0, "desc" ]]'>

              <thead class="style_th bg-dark" style="color: white">
                <th>ID</th>
                <th>Correlativo</th>
                <th>Fecha Envio</th>
                <th>Usuario</th>
                <th>Cant.</th>
                <th>Sucursal</th>
                <th>Imprimir</th>
              </thead>
              <tbody class="style_th"></tbody>
              <tfoot>
                <tr>
                  <th colspan="7" class="total"></th>
                </tr>
              </tfoot>
            </table>

        </section>
        <!-- /.content -->
      </div>

      <input type="hidden" value="<?php echo $categoria_usuario; ?>" id="cat_users">

      <!-- /.content-wrapper -->
      <footer class="main-footer">
        <strong>2022 Lenti || <b>Version</b> 1.0</strong>
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

    <script>
      var dui = new Cleave('#dui_pac', {
        delimiter: '-',
        blocks: [8, 1],
        uppercase: true
      });

      var telefono = new Cleave('#telef_pac', {
        delimiter: '-',
        blocks: [4, 4],
        uppercase: true
      });
    </script>
  </body>

  </html>
<?php } else {
  echo "Acceso denegado";
} ?>