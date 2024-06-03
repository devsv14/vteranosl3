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
        <meta http-equiv='cache-control' content='no-cache'>
        <meta http-equiv='expires' content='0'>
        <meta http-equiv='pragma' content='no-cache'>
        <title>Home - Entregas</title>
        <?php require('../modales/modal_add_llamadas.php') ?>
        <?php require_once("links_plugin.php");
        ?>
        <style>
            .buttons-excel {
                /*background-color: green !important;*/
                margin: 2px;
                max-width: 150px;
            }

            #loader {
                position: absolute;
            }

            .spinner {
                position: absolute;
                top: 50%;
                left: 50%;
                right: 50%;
                bottom: 50%;
                width: 100%;
                height: 100%;
                z-index: 2;
                border: 16px solid #f3f3f3;
                /* Grosor del borde */
                border-top: 16px solid #3498db;
                /* Color del borde superior */
                border-radius: 50%;
                /* Redondez del círculo */
                width: 150px;
                /* Ancho del círculo */
                height: 150px;
                /* Altura del círculo */
                animation: spin 2s linear infinite;
                /* Animación */
            }

            @keyframes spin {

                /* Grados de rotación inicial */
                0% {
                    transform: rotate(0deg);
                }

                /* Grados de rotación final */
                100% {
                    transform: rotate(360deg);
                }
            }
        </style>
    </head>

    <body class="hold-transition sidebar-mini layout-fixed" style='font-family: Helvetica, Arial, sans-serif;'>
        <div class="spinner"></div>
        <div class="wrapper">
            <!-- top-bar -->
            <?php require_once('top_menu.php') ?>

            <?php require_once('side_bar.php') ?>
            <!--End SideBar Container-->
            <div class="content-wrapper">
                <section class="content">
                    <div class="container-fluid">
                        <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $_SESSION["id_usuario"]; ?>" />
                        <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION["user"]; ?>" />
                        <input type="hidden" name="categoria" id="get_categoria" value="<?php echo $_SESSION["categoria"]; ?>" />
                        <div style="border-top: 0px">
                        </div>

                        <h4 class="text-center py-4">ORDENES-ENTREGAS</h4>

                        <div class="card p-3 shadow-lg">
                            <table width="100%" class="table-hover table-bordered" id="dt_entregas_ordenes" data-order='[[ 0, "asc" ]]'>
                                <thead class="style_th bg-dark" style="color: white">
                                    <th>#</th>
                                    <th>Fecha expediente</th>
                                    <th>Fecha ingreso</th>
                                    <th>DUI</th>
                                    <th>Paciente</th>
                                    <th>Sucursal</th>
                                    <th>Sector</th>
                                    <th>Estado</th>
                                    <th>Ultima acción</th>
                                    <th>Llamadas</th>
                                </thead>
                                <tbody class="style_th"></tbody>
                            </table>
                        </div>
                    </div>
            </div>
        </div>
        </div>
        <input type="hidden" id="codigo">
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
        <script type="text/javascript" src="../js/entregas.js?v=<?php rand() ?>"></script>
    </body>

    </html>
<?php } else {
    echo "Acceso denegado";
} ?>