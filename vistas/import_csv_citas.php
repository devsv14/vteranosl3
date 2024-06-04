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
        <title>Importar CSV - AV PLUS</title>
        <?php require_once("links_plugin.php");
        require_once('../modelos/Orders.php');
        $ordenes = new Ordenes();
        //$suc = $ordenes->get_opticas();
        require_once('../modales/citas/vista_previa_citas.php');

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
    <section class="dots-container" id="loader_upload_file">
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
    </section>
        <div class="wrapper">
            <!-- top-bar -->
            <?php require_once('top_menu.php') ?>
            <!-- /.top-bar -->

            <!-- Main Sidebar Container -->
            <?php require_once('side_bar.php') ?>
            <!--End SideBar Container-->
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <section class="content">
                    <div style="margin: 0px">
                        <div class="callout callout-info">
                            <h5 align="center" style="margin:0px"><i class="fas fa-users" style="color:green"></i> <strong style="font-size: 16px;">IMPORTAR CSV - CITADOS</strong></h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" style="max-height: 200px">
                            <div class="card">
                                <div class="card-header p-1">
                                    <button id="btnImportCSV" class="btn btn-outline-success btn-sm" title="Importar csv de citados"><i class="fas fa-file-csv"></i> Importar</button>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body p-2" style="margin: 0px !important; padding: 2px">

                                    <table width="100%" class="table-bordered table-hover" id="dt_citados_csv" data-order='[[ 0, "desc" ]]'>
                                        <thead style="color:white;font-family: Helvetica, Arial, sans-serif;font-size: 13px;text-align: center" class='bg-info'>
                                            <tr>
                                                <th style="width:5%">#</th>
                                                <th style="width:5%">Id ref.</th>
                                                <th style="width:30%">Paciente</th>
                                                <th style="width:10%">Dui</th>
                                                <th style="width:10%">Telefono</th>
                                                <th style="width:10%">Tipo paciente</th>
                                                <th style="width:10%">Sector</th>
                                                <th style="width:15%">Sucursal</th>
                                                <!-- <th style="width:5%">Acciones</th> -->
                                            </tr>
                                        </thead>
                                        <tbody style="font-family: Helvetica, Arial, sans-serif;font-size: 11px;text-align: center;">
                                        </tbody>
                                    </table>

                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.COLUMNA DE AROS -->
                    </div>
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
            <footer class="main-footer">
                <strong><?php echo "2021 - " . date('Y') . " LENTI" ?> || <b>Version</b> 2.0</strong>
                &nbsp;All rights reserved.
                <div class="float-right d-none d-sm-inline-block">

                </div>
            </footer>
        </div>
        <!-- ./wrapper -->
        <?php
        require_once("links_js.php");
        ?>
        <script type="text/javascript" src="../js/cita/upload_file.js"></script>
        <script type="text/javascript" src="../js/cita/import_csv.js"></script>
        <script type="text/javascript" src="../js/cleave.js"></script>
    </body>

    </html>
<?php } else {
    echo "Acceso denegado";
} ?>