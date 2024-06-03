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
    <title>INABVE - Distribuccion</title>

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

          <div class="card  card card-info card-outline px-3">
                <div class="card-header">
                  <h4 class="text-center" style="font-size: 18px; font-weight: 700; font-family:Helvetica, sans-serif">IMPRESION VIÑETAS</h4>
                  </div>
                  <div class="d-flex justify-content-end">
                  <button onclick="destroyOrders()" class="btn btn-outline-success btn-sm" ><b><span id='cont-aud' style='color:blue'></b></span> <i class='fas fa-print'></i></button>
                  </div>

                <div class="row">
                  <div class="col-sm-4">
                  <label for="sucursal">Sucursal traslado</label>
                  <select id='suc-traslado' class="form-control">
                  <option value="Cascadas">Cascadas</option>
                  <option value="Metrocentro">Metrocentro</option>
                  <option value="Apopa">Apopa</option>
                </select>
                  </div>
                </div>

                

              <table width="100%" class="table-responsive-sm table-hover table-bordered" id="dt_distribucion_orden" data-order='[[ 0, "asc" ]]'>
                <thead class="style_th bg-dark" style="color: white">
                <th>
                <input type="checkbox" onClick="selected_all_ordenaud()" id="check_allad">
                </th>

                  <th>Paciente</th>
                  <th>Sucursal</th>
                  <th>DUI</th>
                  <th>Genero</th>
                  <th>Estado</th>
                  <th>Fecha</th>
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

    <script type="text/javascript" src="../js/orden_distribucion.js?v=<?php echo rand() ?>"></script>
    <script type="text/javascript" src="../js/cleave.js"></script>
  </body>

  </html>
<?php } else {
  echo "Acceso denegado";
} ?>

<script>
  var dui_titular = new Cleave('#dui-vet', {
    delimiter: '-',
    blocks: [8,1],
    uppercase : true
  });
</script>