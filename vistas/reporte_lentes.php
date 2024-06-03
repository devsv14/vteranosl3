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
        //require_once('../modales/nueva_orden_lab.php');
        date_default_timezone_set('America/El_Salvador');
        $hoy = date("Y-m-d");

        ?>
        <style>
            .buttons-excel {
                background-color: green !important;
                margin: 2px;
                max-width: 150px;
            }

            .dtr-control {
                text-align: left;
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
                        <input type="hidden" name="sucursal" id="session_sucursal" value="<?php echo $_SESSION["sucursal"]; ?>" />
                        <br>
                        <h5 class="text-center pb-2" style="font-weight: bold;color:#26aae1">Reporte de tipo de lentes</h5>
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
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
                            <div class="col-sm-12 col-md-8">
                                <div class="card card card-success card-outline direct-chat direct-chat-success">
                                    <div class="card-header py-0">
                                        <h5 class="text-center">Reporte lente por sucursal</h5>
                                        <div class="form-row">
                                            <div class="col-sm-12 col-md-3 form-group mb-2">
                                                <label>Desde:</label>

                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                    </div>
                                                    <input type="date" class="form-control" data-mask="" id="desde">
                                                </div>
                                                <!-- /.input group -->
                                            </div>
                                            <div class="col-sm-12 col-md-3 form-group mb-2">
                                                <label>Hasta:</label>

                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                    </div>
                                                    <input type="date" class="form-control" data-mask="" id="hasta">
                                                </div>
                                                <!-- /.input group -->
                                            </div>
                                            <div class="col-sm-12 col-md-3 form-group mb-2">
                                                <label>Sucursal:</label>

                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-store"></i></span>
                                                    </div>
                                                    <select class="form-control" name="" id="sucursal">
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
                                                <!-- /.input group -->
                                            </div>

                                            <div class="form-group d-flex align-items-end mb-2">
                                                <button class="btn btn-outline-success" onclick="filtrar_reporte_lentes()"> <i class="fas fa-search"></i> Filtrar</button>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="card card-dark card-outline" style="margin: 2px;">
                                            <table width="100%" class="table-hover table-responsive-sm table-bordered" id="dt_reporte_lente_filtro" data-order='[[ 1, "desc" ]]'>
                                                <thead class="style_th bg-dark" style="color: white">
                                                    <th>Descripción</th>
                                                    <th>Sucursal</th>
                                                    <th>Cantidad</th>
                                                </thead>
                                                <tbody class="style_th"></tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="3"></th>
                                                    </tr>
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
        <script type="text/javascript" src="../js/apiSum.js"></script>
        <script type="text/javascript" src="../js/ordenes.js?v<?php echo rand() ?>"></script>
        <script type="text/javascript" src="../js/cleave.js"></script>
        <script>
            $(function() {
                $("#sub").select2({
                    maximumSelectionLength: 1
                });
            })
        </script>

    </body>

    </html>
<?php } else {
    echo '<h3 class="text-center">Acceso denegado</h3>';
    echo '<div class="d-flex justify-content-center align-items-center">
<img src="../images/mantenimiento.gif" alt="En mantenimiento">
</div>';
} ?>