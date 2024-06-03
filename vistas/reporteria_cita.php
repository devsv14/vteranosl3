<?php
require_once("../config/conexion.php");
if (isset($_SESSION["user"]) && in_array('listado_reporteria_citas', $_SESSION['names_permisos'])) {
  $categoria_usuario = $_SESSION["categoria"];
  date_default_timezone_set('America/El_Salvador');
  $hoy = date("d-m-Y H-i-s");
?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>INABVE - Reporteria cita</title>
    <?php require_once("links_plugin.php");

    ?>

    <style>
      .buttons-excel {
        margin: 2px;
        max-width: 150px;
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
          <div class="container-fluid">
            <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $_SESSION["id_user"]; ?>" />
            <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION["user"]; ?>" />
            <input type="hidden" name="categoria" id="get_categoria" value="<?php echo $_SESSION["categoria"]; ?>" />
            <div style="border-top: 0px">
            </div>

            <h4 class="text-center py-4 text-dark">REPORTERIA CITA <span class="msg_reporteria"></span></h4>

            <div class="row align-items-center">

              <div class="form-group mb-2 col-sm-12 col-md-2 mb-3">
                <label for="fecha_desde">Desde/día</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                  </div>
                  <input onchange="get_citados_estado_rango_fecha()" id="fecha_desde" type="date" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask="" inputmode="numeric">
                </div>
              </div>

              <div class="form-group mb-2 col-sm-12 col-md-2 mb-3">
                <label for="fecha_hasta">Hasta</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                  </div>
                  <input onchange="get_citados_estado_rango_fecha()" id="fecha_hasta" type="date" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask="" inputmode="numeric">
                </div>
              </div>

              <div class="form-group mb-2 col-sm-12 col-md-2 mb-3">
                <label for="sucursal">Sucursal</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-store"></i></span>
                  </div>
                  <select class="form-control" id="sucursal" onchange="get_citados_estado_rango_fecha()">
                    <option value="">Resumen</option>
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
              </div>

              <div class="icheck-success d-inline mx-3 mb-3">
                <input name="citados" class="form-check-input citados" type="radio" onchange="get_citados_estado_rango_fecha()" id="radioSuccess1" value="citados">
                <label class="form-check-label" for="radioSuccess1">Citados</label>
              </div>

              <div class="icheck-success d-inline mr-3 mb-3">
                <input name="citados" class="form-check-input citados" type="radio" onchange="get_citados_estado_rango_fecha()" id="radioSuccess2" value="atendidos">
                <label class="form-check-label" for="radioSuccess2">Atendidos</label>
              </div>
              <div class="icheck-success d-inline mr-4 mb-3">
                <input name="citados" class="form-check-input citados" type="radio" onchange="get_citados_estado_rango_fecha()" id="radioSuccess3" value="sin_atender">
                <label class="form-check-label" for="radioSuccess3">Sin atender</label>
              </div>
              <button id="generar_pdf_citas" class="btn btn-sm btn-outline-info my-3 d-flex align-items-center"><i class="fas fa-file-pdf mr-2"></i> IMPRIMIR <span class="msg_reporteria"></span></button>
            </div>

            <table width="100%" class="table-responsive-sm table-hover table-bordered" id="dt_reporteria_citados" data-order='[[ 0, "desc" ]]'>

              <thead class="style_th bg-dark" style="color: white">
                <th>ID</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Paciente</th>
                <th>DUI</th>
                <th>Teléfono</th>
                <th>Sector</th>
                <th>Tipo paciente</th>
                <th>Sucursal</th>
                <th>Estado</th>
                <th>Agendado por</th>
              </thead>
              <tbody class="style_th"></tbody>
            </table>

        </section>
        <!-- /.content -->
      </div>

      <input type="hidden" value="<?php echo $categoria_usuario; ?>" id="cat_users">

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
    <script type="text/javascript" src="../js/citados.js?v=<?php echo rand() ?>"></script>
  </body>

  </html>
<?php } else {
  echo "Acceso denegado";
} ?>