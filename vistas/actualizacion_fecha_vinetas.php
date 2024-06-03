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
    <title>INABVE - ACTUALIZACION VINETAS</title>

    <?php
     require_once("links_plugin.php");
     require_once "../modales/vinetas/modal_update_date_vinetas.php";
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

          <div class="card  card card-info card-outline px-3">
                <div class="card-header">
                  <h4 class="text-center" style="font-size: 18px; font-weight: 700; font-family:Helvetica, sans-serif">ACTUALIZAR FECHA DE EXPEDIENTE</h4>
                  </div>
                  <div class="d-flex justify-content-end">
                  <button onclick="openModal()" class="btn btn-outline-success btn-sm mb-2" data-toggle="tooltip" data-placement="bottom" title="Abrir ventana para actualizar fecha de vinetas">Actualizar fecha</button>
                  </div>
              <table width="100%" class="table-responsive-sm table-hover table-bordered" id="dt-update-fecha" data-order='[[ 0, "asc" ]]'>
                <thead class="style_th bg-dark" style="color: white">
                  <th>#</th>
                  <th>Correlativo</th>
                  <th>Paciente</th>
                  <th>DUI</th>
                  <th>Fecha ant.</th>
                  <th>Fecha</th>
                  <th>Sucursal</th>
                </thead>
                <tbody class="style_th"></tbody>
              </table>

            
          </div>
        </section>
        <!-- /.content -->
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

    <script type="text/javascript" src="../js/vinetas/update_date_vinetas.js?v=<?php echo rand() ?>"></script>
    <script type="text/javascript" src="../js/cleave.js"></script>
  </body>

  </html>
<?php } else {
  echo "Acceso denegado";
} ?>

<script>
  /*var dui_titular = new Cleave('#dui-vet', {
    delimiter: '-',
    blocks: [8,1],
    uppercase : true
  });*/
</script>