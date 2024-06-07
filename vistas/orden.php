<?php
require_once("../config/conexion.php");
if (isset($_SESSION["user"])) {
  $categoria_usuario = $_SESSION["categoria"];
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

    require_once('../modales/nueva_referencia.php');
    require_once('../modales/aros_en_orden.php');
    require_once('../modales/modal_rectificaciones.php');
    require_once('../modales/estadisticas.php');
    require_once('../modales/modal_citas.php');

    date_default_timezone_set('America/El_Salvador');
    $hoy = date("Y-m-d");

    ?>
    <style>
      .buttons-excel {
        background-color: green !important;
        margin: 2px;
        max-width: 150px;
      }
    </style>
  </head>

  <body class="hold-transition sidebar-mini layout-fixed" style='font-family: Helvetica, Arial, sans-serif;'>
    <div class="wrapper">
      <!-- top-bar -->
      <?php require_once('top_menu.php') ?>
      <!-- Main Sidebar Container -->
      <?php require_once('side_bar.php') ?>
      <!--End SideBar Container-->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <section class="content">
          <div class="container-fluid">
            <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $_SESSION["id_user"]; ?>" />
            <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION["user"]; ?>" />
            <input type="hidden" name="categoria" id="get_categoria" value="<?php echo $_SESSION["categoria"]; ?>" />
            <input type="hidden" name="sucursal" id="sucursal" value="<?php echo $_SESSION["sucursal"]; ?>" />
            <input type="hidden" name="sucursal" id="session_sucursal" value="<?php echo $_SESSION["sucursal"]; ?>" />
            <div style="border-top: 0px">
            </div>
            <br>
            <!-- <button class="btn btn-outline-primary btn-sm btn-flat" data-toggle="modal" data-target="#nueva_orden_lab" onClick='get_numero_orden();' id="order_new"><i class="fa fa-glasses" style="margin-top: 2px"> Crear Orden</i></button> --->
            <!-- <button class="btn btn-outline-primary btn-sm btn-flat" data-toggle="modal" data-target="#nueva_orden_lab" ><i class="fa fa-glasses" style="margin-top: 2px"> Crear Orden</i></button> -->
            <button type="button" class="btn btn-outline-primary btn-flat" style='border-radius:3px' data-toggle="modal" data-target="#nueva_orden_lab"><i class="fa fa-book"></i> Nueva Orden</button>
            <div class="card card-dark card-outline" style="margin: 2px;">
              <table width="100%" class="table-hover table-bordered" id="datatable_ordenes" data-order='[[ 1, "desc" ]]'>
                <thead class="style_th bg-dark" style="color: white">
                  <th>ID orden</th>
                  <th>Fecha</th>
                  <th>Paciente</th>
                  <th>DUI</th>
                  <th>Telefono</th>
                  <th>Tipo lente</th>
                  <th>Sucursal</th>

                  <th>Ver y Editar</th>

                  <th>Eliminar</th>
                </thead>
                <tbody class="style_th"></tbody>
              </table>
            </div>

          </div><!-- /.container-fluid -->
        </section>
        <!-- /..content -->
      </div>

      <input type="hidden" value="<?php echo $categoria_usuario; ?>" id="cat_users">
      <input type="hidden" id="fecha_act" value="<?php echo $hoy; ?>">

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
    <!-- <script type="text/javascript" src="../js/ordenes.js?v<?php echo "" //rand() 
                                                                ?>"></script> -->
    <script type="text/javascript" src="../js/referencias.js?v<?php echo rand() ?>"></script>
    <script type="text/javascript" src="../js/productos.js"></script>
    <script type="text/javascript" src="../js/cleave.js"></script>
    <script type="text/javascript" src="../js/citados.js"></script>
    <script>
      var dui = new Cleave('#dui_pac', {
        delimiter: '-',
        blocks: [8, 1],
        uppercase: true
      });

      var dui_titular = new Cleave('#dui_titular', {
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

    <script>
      $(function() {
        //Initialize Select2 Elements
        $('.select2').select2()
        //Initialize Select2 Elements
        $('.select2bs4').select2({
          theme: 'bootstrap4'
        })

        $(".select2").select2({
          maximumSelectionLength: 1
        });
        //Formulario orden
        $("#departamento_pac").select2({
          maximumSelectionLength: 1
        });

        $("#munic_pac").select2({
          maximumSelectionLength: 1
        });
      })
    </script>

  </body>

  </html>
<?php } else {
  echo '<h3 class="text-center">Acceso denegado</h3>';
} ?>

<div class="modal fade" id="selectLentes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="max-width: 90%">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header bg-primary" style="padding: 5px;">
        <h4 class="modal-title w-200 text-rigth" style="font-size: 15px"><b>LENTES Y TRATAMIENTOS</b></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row  p-0 mb-0 bg-white rounded">

          <div class="col-md-6 col-lg-3 shadow-none">
            <div class="card card-light" style="height: 100px;">
              <div class="card-header bg-light" style="padding: 3px; text-align: center;">
                <h3 class="card-title" style="margin: 0 auto; width: 100%;"><b>TIPO LENTE</b></h3>
              </div>
              <div class="card-body  p-1 d-flex justify-content-between">
                <div class="icheck-success d-inline">
                  <input type="radio" name="lentes-ref" value="Visión Sencilla" id='vsref'>
                  <label for="vsref">VS</label>
                </div>

                <div class="icheck-success d-inline">
                  <input type="radio" name="lentes-ref"  value="Bifocal" id='bfref'>
                  <label for="bfref">BF</label>
                </div>

                <div class="icheck-success d-inline">
                  <input type="radio" name="lentes-ref"  value="Multifocal" id='pgref'>
                  <label for="pgref">Progresive</label>
                </div>
              </div>
            </div>
          </div>
       
          <div class="col-md-6 col-lg-4 shadow-none">
            <div class="card card-light" style="height: 100px;">
              <div class="card-header bg-light" style="padding: 3px; text-align: center;">
                <h3 class="card-title" style="margin: 0 auto; width: 100%;"><b>MATERIAL</b></h3>
              </div>
              <div class="card-body card-body p-3 d-flex justify-content-between">
                <div class="icheck-success d-inline">
                  <input type="radio" name="r3" value="Visión Sencilla">
                  <label for="radioSuccess1">Poli</label>
                </div>

                <div class="icheck-success d-inline">
                  <input type="radio" name="r3"  value="Bifocal"->
                  <label for="radioSuccess1">AI</label>
                </div>

                <div class="icheck-success d-inline">
                  <input type="radio" name="r3"  value="Multifocal">
                  <label for="radioSuccess1">Vidrio</label>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-12 col-lg-5 shadow-none">
            <div class="card card-light" style="height: 100px;">
              <div class="card-header bg-light" style="padding: 3px; text-align: center;">
                <h3 class="card-title" style="margin: 0 auto; width: 100%;"><b>TRATAMIENTO</b></h3>
              </div>
              <div class="card-body card-body p-3 d-flex justify-content-between">
                <div class="icheck-success d-inline">
                  <input type="radio" name="r3" value="Visión Sencilla">
                  <label for="radioSuccess1">Blanco</label>
                </div>

                <div class="icheck-success d-inline">
                  <input type="radio" name="r3"  value="Bifocal"->
                  <label for="radioSuccess1">Fotocroma</label>
                </div>

                <div class="icheck-success d-inline">
                  <input type="radio" name="r3"  value="Multifocal">
                  <label for="radioSuccess1">Polariz.</label>
                </div>
                <div class="icheck-success d-inline">
                  <input type="radio" name="r3"  value="Multifocal">
                  <label for="radioSuccess1">Fotogray</label>
                </div>

              </div>
            </div>
          </div>

        </div>
      </div><!-- Fin modal body -->
    </div>
  </div>
</div>