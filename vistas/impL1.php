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
        require_once('../modales/modal_busquedaRX.php');
        require_once('../modales/modal_rx_traslado.php');
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
            background-color: rgba(0, 0, 0, 0.5); /* Agrega transparencia al fondo */
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
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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

                        </div>


                        <div class="row">
                            <div class="col-sm-8">

                            <div class="row">
  
                               <!--   <div class="col-sm-3">
                              <label for="">Seleccionar lente</label>
                                <select  id="tipo_lente_print" class="form-control">
                                    <option value="0">Sel...</option>
                                    <option value="Flaptop">Flaptop</option>
                                    <option value="Progresive">Progresive</option>
                                    <option value="Visión Sencilla">Visión Sencilla</option>
                                </select>
                                </div>

                                <div class="col-sm-3">
                                <label for="">Seleccionar color</label>
                                <select  id="color_l1" class="form-control">
                                    <option value="0">Sel...</option>
                                    <option value="Blanco">Blanco</option>
                                    <option value="Photocromatico">Photocromatico</option> 
                                </select>
                                </div>

                                <div class="col-sm-3">
                                <label for="">Alto indice</label>
                                <select  id="indice_l1" class="form-control">
                                    <option value="0">Sel...</option>
                                    <option value="Si">Si</option>
                                    <option value="No">No</option>
                                </select>
                                </div>-->

                                <div class="col-sm-3">
                                    <button class="btn btn-primary btn-sm float-right" onclick='imprimirLabelL1()'><span id='counter-print-l1'></span> Imp</button>
                                </div>
                            </div>

                            <div class="card card-dark card-outline mt-2">
                            <h5 style="text-align: center; font-size: 14px" align="center" class="bg-info">TRASLADO ORDENES LICITACIÓN</h5>
                            <div class="card-body">
                                <table width="100%" class="table-hover table-bordered" id="dt-print-l1">
                                    <thead class="style_th bg-dark" style="color: white">
                                        <th>Sel..</th>
                                        <th>Paciente</th>
                                        <th>Tipo Lente</th>
                                        <th>DUI</th>
                                        <th>Rx OD</th>
                                        <th>Rx OI</th>
                                    </thead>
                                    <tbody class="style_th"></tbody>
                                </table>
                            </div>
                            </div>
                            </div>
                            <div class="col-sm-4">

                                <div class="card card card-success card-outline direct-chat direct-chat-success">
                                    <div class="card-header py-0">
                                        <h5 class="text-center">Reporte lente resumen</h5>
                                        <div class="form-row">
                                            <div class="col-sm-12 col-md-6 form-group mb-2">
                                                <label>Desde:</label>

                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                    </div>
                                                    <input type="date" class="form-control" data-mask="" id="f_desde">
                                                </div>
                                                <!-- /.input group -->
                                            </div>
                                            <div class="col-sm-12 col-md-6 form-group mb-2">
                                                <label>Hasta:</label>

                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                    </div>
                                                    <input type="date" class="form-control" data-mask="" id="f_hasta">
                                                </div>
                                                <!-- /.input group -->
                                            </div>
                                            <div class="col-sm-12 col-md-6 form-group mb-2">
                                                <label>Estado:</label>

                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                    </div>
                                                    <select class="form-control" id="filter-estado">
                                                        <option value="Atendidos">Atendidos</option>
                                                        <option value="Entregados">Entregados</option>
                                                    </select>
                                                </div>
                                                <!-- /.input group -->
                                            </div>
                                            <div class="form-group d-flex align-items-end mb-2">
                                                <button class="btn btn-outline-success" onclick="filtrar_lentes_category()"> <i class="fas fa-search"></i> Filtrar</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="card card-dark card-outline" style="margin: 2px;">
                                            <table width="100%" class="table-hover table-responsive-sm table-bordered" id="dt_reporte_lente" data-order='[[ 0, "desc" ]]'>
                                                <thead class="style_th bg-dark" style="color: white">
                                                    <th>Descripción</th>
                                                    <th>Cantidad</th>
                                                    <th>Fact.</th>
                                                    <th>Diff.</th>
                                                </thead>
                                                <tbody class="style_th"></tbody>
                                                <tfoot>
                                                    
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>

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
        <script type="text/javascript" src="../js/trasladoLicitacion.js?v<?php echo rand() ?>"></script>
        <script type="text/javascript" src="../js/cleave.js"></script>
        <script type="text/javascript" src="../js/apiSum.js"></script>
        <script type="text/javascript" src="../js/ordenes.js?v<?php echo rand() ?>"></script>
        <script>

        listar_ordenes('dt-print-l1', 'get_orden_print_l1', 0)
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