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
    <title>INABVE - Actas</title>
    <?php require_once("links_plugin.php");
    require_once('../modales/actas/show_acta_edit.php');

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
          </div>
          <div class="card my-2 card card-success card-outline">
            <div class="card-header">              
              <div class="row align-items-center">
                <div class="col-sm-12 col-md-10">
                <h5 class="text-center text-dark" style="font-weight: bold;">ACTAS <span class="msg_reporteria"></span></h5>
                </div>
                <?php if(in_array('listado_general_actas',$_SESSION['names_permisos'])){?>
                <div class="form-group mb-1 col-sm-12 col-md-2 mb-1">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-store"></i></span>
                    </div>
                    <select class="form-control" id="filter_sub" onchange="filter_sucursal_acta()">
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
                <?php } ?>
              </div>
            </div>
            <div class="card-body">
              <table width="100%" class="table-responsive-sm table-hover table-bordered" id="dtable_actas" data-order='[[ 0, "desc" ]]'>

                <thead class="style_th bg-dark" style="color: white">
                  <th>ID Acta</th>
                  <th>Fecha</th>
                  <th>Paciente</th>
                  <th>DUI</th>
                  <th>Sucursal</th>
                  <th>Detalle</th>
                  <th>Imp. Acta</th>
                </thead>
                <tbody class="style_th"></tbody>
              </table>
            </div>
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
    <script type="text/javascript" src="../js/actas.js?v=<?php echo rand() ?>"></script>
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
  var dui_titular = new Cleave('#dui_titular', {
    delimiter: '-',
    blocks: [8,1],
    uppercase : true
  });
  var dui_titular = new Cleave('#dui_receptor', {
    delimiter: '-',
    blocks: [8,1],
    uppercase : true
  });
</script>