<?php 
require_once("../config/conexion.php");
if(isset($_SESSION["usuario"])){
$categoria_usuario = $_SESSION["usuario"];
require_once("../vistas/links_plugin.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Inventarios-Vet</title>
<style>
  .buttons-excel{
      background-color: green !important;
      margin: 2px;
      max-width: 150px;
  }
</style>
</head>
<body class="hold-transition sidebar-mini layout-fixed" style='font-family: Helvetica, Arial, sans-serif;'>
  <!-- top-bar -->
  <?php require_once('../vistas/top_menu.php')?>
  <!-- Main Sidebar Container -->
  <?php require_once('Views/side_bar.php')?>
  <div class="content-wrapper">
	  <div class="callout callout-info">
        <h5 align="center" style="margin:0px;font-family: Helvetica, Arial, sans-serif;font-size: 16px"><i class="fas fa-file" style="color:green"></i> <strong>REPORTERIA DE CITAS</strong></h5>
<br>
      <div class="row" style="margin-5px; font-family: Helvetica, Arial, sans-serif;font-size: 12px">
        <div class="col-sm-3">
            <div class="form-group">
              <label for="sel1">Tipo Reporte</label>
              <select class="form-control" id="tipo_rep">
                <option>Seleccionar...</option>
                <option>Citas</option>
                <option>Atendidos</option>
            </select>
          </div>
      </div>

      <div class="col-sm-2">
      <div class="form-group">
            <label for="sel1">Sucursal</label>
            <select class="form-control" id="suc-rep-citas">
              <?php echo $sucursales;?>
            </select>
          </div>
      </div>

      <div class="col-sm-2 form-group" >
      <label for="sel1">Desde</label>
      <input type="date" class="form-control clear_orden_i" id="desde" placeholder="desde" name="inicio">
      </div>

      <div class="col-sm-2 form-group">
      <label for="sel1">Hasta</label>
      <input type="date" class="form-control clear_orden_i" id="hasta" placeholder="desde" name="inicio">
      </div>

      <div class="col-sm-1 form-group">
      <label for="sel1">Filtrar</label>
      <button class="btn btn-md btn-info btn-block" onClick="getcitadosAtendidos()" style="margin-top:4px"><i class="fas fa-filter"></i></button>
      </div>

    </div>
    </div>

    <table width="100%" class="table-bordered table-hover"  id="data-citados-atend" data-order='[[ 0, "desc" ]]'>
      <thead style="color:white;font-family: Helvetica, Arial, sans-serif;font-size: 13px;text-align: center" class='bg-primary'>
        <tr>
          <th>Sucursal</th>
          <th>Paciente</th>
          <th>DUI</th>
          <th>Fecha</th>
          <th>Sector</th>
          <th>Estado</th>
        </tr>
      </thead>
      <tbody style="font-family: Helvetica, Arial, sans-serif;font-size: 12px;text-align:center"></tbody>
    </table>

  </div>

</body>  
  <footer class="main-footer">
    <strong>2021 Lenti || <b>Version</b> 1.0</strong>
     &nbsp;All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      
    </div>
  </footer>




<!-- ./wrapper -->
<?php 
require_once("../vistas/links_js.php");
?>
<script>
      $(function () {
    //Initialize Select2 Elements
    $('#marca_aros').select2()
    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })

    $("#marca_aros").select2({
        maximumSelectionLength: 1
    });

    $('#bodega-sucursal').select2()
    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })

    $("#bodega-sucursal").select2({
        maximumSelectionLength: 1
    });
 
    })
</script>
<script type="text/javascript" src="../js/citados.js"></script>
<script type="text/javascript" src="../js/productos.js"></script>
<script type="text/javascript" src="../js/cleave.js"></script>
<script>
  var dui = new Cleave('#dui_pac', {
  delimiter: '-',
  blocks: [8,1],
  uppercase : true
});
</script>
</body>
</html>
 <?php } else{
echo "Acceso denegado";
  } ?>
