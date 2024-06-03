
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
    <title>Actas - Resumen</title>
    <?php require_once("links_plugin.php");
    require_once('../modales/actas/show_acta_edit.php');

    ?>

    <style>
      .buttons-excel {
        margin: 2px;
        max-width: 150px;
      }

      .loader {
  position: absolute;
  left: 50%;
  top: 50%;
  border: 16px solid #f3f3f3;
  border-top: 16px solid #3498db;
  border-radius: 50%;
  width: 120px;
  height: 120px;
  animation: spin 2s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
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
                <h5 class="text-center text-dark" style="font-weight: bold;">REPORTERÍA ACTAS <span class="msg_reporteria"></span></h5>                
                </div>
              </div>
            </div>
            <div class="row">
                <div class="col-sm-2" style='margin-left:8px'>
                    <label>Desde:</label>
                    <div class="input-group">
                        <input type="date" class="form-control" data-mask="" id="f_desde_act">                        
                    </div>
                </div>

               <div class="col-sm-2">
                    <label>Hasta:</label>
                    <div class="input-group">
                        <input type="date" class="form-control" data-mask="" id="f_hasta_act">
                        <div class="input-group-prepend bg-info" style='cursor:pointer' onClick='filtrarActasResumen()'><span class="input-group-text"><i class="fas fa-search"></i> Filtrar</span></div>
                    </div>
               </div>

            </div>

            <div class="card-body">
            <div id="loader" class="loader"></div>
              <table width="100%" class="table-responsive-sm table-hover table-bordered" id="dtable_actas_resumen" data-order='[[ 0, "desc" ]]'>

                <thead class="style_th bg-dark" style="color: white">
                  <th>ID Acta</th>
                  <th>Fecha Exp.</th>
                  <th>Fecha entrega</th>
                  <th>Paciente</th>
                   <th>Telefono</th>
                  <th>DUI</th>
                  <th>Sucursal</th>
                  <th>Receptor</th>
                  <th>Tipo paciente</th>
                  <th>Titular</th>
                  <th>DUI titular</th>
                  <th>Sector</th>
                  <th>Tipo lente</th>
                  <th>Tratamiento</th>
                  <th>Alto Indice</th>
                  <th>Precio</th>
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
        <strong>2023 Lenti || <b>Version</b> 1.0</strong>
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
</script>