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
    <title>INABVE - CITAS L1</title>
    <?php 
    require_once("links_plugin.php");

    ?>

    <style>
      @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap');
      .total{
        text-align: right;
        font-weight: bold;
        font-family: 'Roboto', sans-serif;
      }
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
              <div class="row align-items-between">
                <div class="col-sm-12 col-md-1">
                  <span><i class="fas fa-id-card-alt fa-2x"></i></span>
                </div>
                <div class="col-sm-12 col-md-9">
                  <h5 class="text-center text-dark" style="font-weight: bold;">CITAS RENOVACION LENTES <span class="msg_reporteria"></span></h5>
                </div>

              </div>
            </div>
            <div>
              <table width="100%" class="table-responsive-sm table-hover table-bordered" id="renovacion_lentes_listar" data-order='[[ 0, "asc" ]]'>

                <thead class="style_th bg-dark" style="color: white">
                  <th>ID</th>
                  <th>Paciente</th>
                  <th>DUI</th>
                  <th>Telefono</th>
                  <th>Depto.</th>
                  <th>Municipio</th>
                  <th>Edad</th>
                  <th>Tipo lente</th>
                  <th>Institucion</th>
                  <th>Fecha Eval.</th>
                  <th>Citar</th>
                </thead>
                <tbody class="style_th"></tbody>     
              </table>
            </div>
          </div>
        </section>
        <!-- /.content -->
      </div>

      <!--modal actas-->
      <div class="modal" id="modal-actas-entregas">
        <div class="modal-dialog" style="max-width: 60%">
          <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header bg-info" style="padding:5px">
              <h4 class="modal-title w-100 text-center" style="font-size:16px">ENTREGA OFICIAL DE ACTAS</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
              <h5 style="text-align:center;font-size:18px"><span style="color:red">NOTA: </span><u>Las siguientes actas seran entregadas a:</u></h5>
              <div class="row ">

                <div class="col-sm-6">
                  <label for="fullname-emisor">Entrega: </label>
                  <input onkeyup="mayus(this)" type="text" class="form-control oblig" id="fullname-emisor" name="fullname-emisor" placeholder="nombre completo" style='text-tranform:uppercase'>
                </div>
                <div class="col-sm-6">
                  <label for="fullname-receptor">Recibe: </label>
                  <input onkeyup="mayus(this)" type="text" class="form-control oblig" id="fullname-receptor" name="fullname-receptor" placeholder="nombre completo">
                </div>

              </div>
            </div><!-- Fin body modal -->
            <!-- Modal footer -->
            <div class="modal-footer">
              <button onclick="registrarEntregasActas()" class="btn btn-outline-success btn-sm border-rounded btn-block"><i class="far fa-save"></i> Registrar</button>
            </div>

          </div>
        </div>
      </div>
      <input type="hidden" id="sucursal" value="<?php echo $_SESSION["sucursal"] ?>">
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
    <script type="text/javascript" src="../js/renovacion.js?v=<?php echo rand() ?>"></script>
    <script type="text/javascript" src="../js/cleave.js"></script>
  </body>

  </html>
<?php } else {
  echo "Acceso denegado";
} ?>

<script>
  var dui_titular = new Cleave('#dui-vet', {
    delimiter: '-',
    blocks: [8, 1],
    uppercase: true
  });
</script>