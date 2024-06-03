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
        <title>Home | Resumen Bodega</title>
        <?php require_once("links_plugin.php");
        require_once("../modales/bodega_av_plus/modal_graph.php")
        ?>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap');

            .total {
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

                        <div class="card mt-4 pt-2">
                            <?php include 'bodega/header_status_bodega.php'; ?>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <table width="100%" class="table-hover table-bordered" id="dtable_cantidad_orden_mes" data-order='[[ 0, "asc" ]]'>
                                    <thead class="style_th bg-dark" style="color: white">
                                        <th>#</th>
                                        <th>Mes</th>
                                        <th>Año</th>
                                        <th>Cantidad</th>
                                        <th>Resultados</th>
                                    </thead>
                                    <tbody class="style_th"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- /.content -->
            </div>

            <input type="hidden" value="<?php echo $categoria_usuario; ?>" id="cat_users">
            <input type="hidden" id="tipo_accion" value="reenvio">
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
        <script src="https://code.highcharts.com/stock/highstock.js"></script>
        <script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
        <script src="https://code.highcharts.com/stock/modules/accessibility.js"></script>
        <script type="text/javascript" src="../js/bodega_graph.js?<?php echo rand() ?>"></script>
    </body>

    </html>
<?php } else {
    echo "Acceso denegado";
} ?>

<script>
    //Subtitle page
    $("#subtitle_bod").html('RESUMEN')
</script>