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
        <title>Traslado Licitación 1</title>
        <?php require_once("links_plugin.php");
        require_once('../modelos/Ordenes.php');
        require_once('../modales/modal_busquedaRX.php');
        require_once('../modales/trasladoOrden/modal_upd_Rx_orden.php');
        $ordenes = new Ordenes();

        date_default_timezone_set('America/El_Salvador');
        $hoy = date("Y-m-d");

        ?>
        <style>
            .buttons-excel {
                background-color: green !important;
                margin: 2px;
                max-width: 150px;
            }

            #spinnner {
                display: none;
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                /* Agrega transparencia al fondo */
                z-index: 10000;
                align-items: center;
                justify-content: center;
            }

            .spinnerr {
                border: 16px solid #f3f3f3;
                border-top: 16px solid #3498db;
                border-radius: 50%;
                width: 200px;
                height: 200px;
                animation: spin 2s linear infinite;
            }

            @keyframes spin {
                0% {
                    transform: rotate(0deg);
                }

                100% {
                    transform: rotate(360deg);
                }
            }
        </style>
    </head>

    <body class="hold-transition sidebar-mini layout-fixed" style='font-family: Helvetica, Arial, sans-serif;'>
        <div id="spinnner">
            <div class="spinnerr"></div>
        </div>
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
                        <div class="mx-2">
                            <div class="row">
                                <button class="btn btn-outline-primary btn-sm" onclick="showBusquedaRXFinal()" style="height: 30px;" data-toggle="tooltip" data-placement="bottom" title="Buscar orden por graduación (RX Final)"><i class="fas fa-search"></i> Graduaciones</button>
                                <div class="col-sm-6 col-md-2">
                                    <select name="" onchange="selectedTipoOrden()" style="height: 30px;" id="tipo_lente" class="form-control">
                                        <option value="">Resumen</option>
                                        <option value="Flaptop">Flaptop</option>
                                        <option value="Progresive">Progresive</option>
                                        <option value="Visión Sencilla">Visión Sencilla</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <button class="btn btn-success btn-sm float-right" onClick='printLabelTraslado()'><span id='counter-print-traslados-conf'></span> Imprimir</button>
                                </div>
                            </div>
                        </div>

                        <div class="card card-dark card-outline mt-2">
                            <h5 style="text-align: center; font-size: 18px" align="center" class="bg-primary"><b></b>TRASLADOS CONFIRMADOS LICITACIÓN 1</b></h5>
                            <div class="card-body">
                                <table width="100%" class="table-hover table-bordered" id="dt_traslado_confirmados" data-order='[[ 1, "desc" ]]'>
                                    <thead class="style_th bg-dark" style="color: white">
                                        <th>#</th>
                                        <th>Paciente</th>
                                        <th>DUI</th>
                                        <th>Teléfono</th>
                                        <th>Rx OD</th>
                                        <th>Rx OI</th>
                                        <th>Fecha</th>
                                        <th>Tipo lente</th>
                                        <th>Crear Exp.</th>
                                    </thead>
                                    <tbody class="style_th"></tbody>
                                </table>
                            </div>
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
        <script type="text/javascript" src="../js/trasladoConfirmado.js?v<?php echo rand() ?>"></script>
        <script type="text/javascript" src="../js/despachos.js?v<?php echo rand() ?>"></script>
        <script type="text/javascript" src="../js/cleave.js"></script>
        <script>
            var dui = new Cleave('#dui_pac', {
                delimiter: '-',
                blocks: [8, 1],
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
            })
        </script>

    </body>

    </html>
<?php } else {
    echo '<h3 class="text-center">Acceso denegado</h3>';
} ?>