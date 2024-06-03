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
    <title>INABVE - Distribuccion</title>

    <?php
     require_once("links_plugin.php");
    ?>

    <style>
      .buttons-excel {
        margin: 2px;
        max-width: 150px;
      }
      .custom-file-input{
        margin:5px !important
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
        <div id="loader" class="loader"></div>
          <div class="card  card card-info card-outline px-3">
                <div class="card-header">
                  <h4 class="text-center" style="font-size: 18px; font-weight: 700; font-family:Helvetica, sans-serif">ORDENAMIENTO FACTURAS</h4>
                  </div>                

          <div id='resumen-facturas-atend'></div>
            
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


    <!-- The Modal -->
<div class="modal" id="modal-actualization">
  <div class="modal-dialog"  style="max-width: 75%">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header" style="padding:6px;" >
        <h5 class="modal-title" >
        <span id='lente-act-update'></span>&nbsp;&nbsp;&nbsp;
        <span id='color-act-update'></span>&nbsp;&nbsp;&nbsp;
        <span id='aindex-act-update'></span></h5>
        <input type="hidden" id='alto-indice-value'>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-3">

          <select class="form-control" id="factura-update">
            <option value="0">Seleccionar factura...</option>
            <option value="1">Factura 1</option>
            <option value="2">Factura 2</option>
            <option value="3">Factura 3</option>
            <option value="4">Factura 4</option>
            <option value="5">Factura 5</option>
            <option value="6">Factura 6</option>
            <option value="7">Factura 7</option>
       </select>
       </div>
        
       <div class="col-sm-9" >
        <button class="btn btn-outline-primary ml-auto btn-xs" onClick="ActualizacionesExpedientes()"><i class="fas fa-history"></i>Actualizar</button>
        <button class="btn btn-outline-danger ml-auto btn-xs"><b>Dif. <span id='dif-expedientesf'></span></b></button>
        <button class="btn btn-outline-secondary ml-auto btn-xs"><b> Sel. <span id='count-expedientesf'></span></b></button>

        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" id='actualizar' name="radiosUpdate" onClick="getExpedientesExcedeFechas()" value="actualizar" >
          <label class="form-check-label" for="actualizar">Actualizar  Fecha</label>
        </div>

        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" id='importar' name="radiosUpdate" onClick="importarExpedientes(this.value)" value="importar">
          <label class="form-check-label" for="importar">Importar</label>
        </div>

        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" id='exportar' name="radiosUpdate" onClick="exportarExpedientes(this.value)" value="exportar" >
          <label class="form-check-label" for="exportar">Exportar</label>
        </div>
      </div>
      </div>

      <table width="100%" class="table-responsive-sm table-hover table-bordered" id="dt-upd-orders" data-order='[[ 1, "asc" ]]'>
      <thead class="style_th bg-dark" style="color: white">
      <th>
        <input type="checkbox" onClick="selectActFacturas(this)" id="check-order-facts">
      </th>

        <th>Fecha</th>
        <th>Paciente</th>
        <th>DUI</th>
        <th>Sucursal</th>
        <th>Tipo lente</th>
        <th>Color</th>
        <th>Alto indice</th>       
      </thead>
      <tbody class="style_th" id='table-acciones-act'></tbody>
      </table>
      </div>
    </div>
  </div>
</div>

<!-- MODAL PARA Exportar ordenes  -->
<div class="modal" id="modal-import-update">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title"></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <h5 style='text-align:center' id='desc-import1'></h5>

        <ul>
          <li>Tipo Lente: <span id="lente-list"></span></li>
          <li>Color: <span id="color-list"></span></li>
          <li>Alto Indice: <span id="ai-list"></span></li>
      </ul>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer" id='btn-act-update'>
        <button class="btn btn-outline-primary" id="btnActions"  onclick="actualizarOrdenesFechaAnt()"></button>
      </div>

    </div>
  </div>
</div>

<!-- Modal para exportar -->
<div class="modal" id="modal-export-update">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">EXPORTAR EXPEDIENTES</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <h5 style='text-align:center' id='desc-export1'></h5>

        <ul>
          <li>Tipo Lente: <span id="lente-list-e"></span></li>
          <li>Color: <span id="color-list-e"></span></li>
          <li>Alto Indice: <span id="ai-list-e"></span></li>
      </ul>
      <h5><b>EXPORTAR A:</b></h5>
      <div class="row form-row">
        <div class="col-sm-3 form-group">
            <label for="">Factura</label>
            <select class="form-control" id="factura-export">
            <option value="0">Seleccionar factura...</option>
            <option value="1">Factura 1</option>
            <option value="2">Factura 2</option>
            <option value="3">Factura 3</option>
            <option value="4">Factura 4</option>
            <option value="5">Factura 5</option>
            <option value="6">Factura 6</option>
            <option value="7">Factura 7</option>
       </select>
       </div>
        <div class="col-sm-3 form-group">
        <label for="">Tipo Lente</label>
        <select class="form-control" id="lente-export">
            <option value="0">Seleccionar tipo lente...</option>
            <option value="Flaptop">Flaptop	</option>
            <option value="Visión Sencilla">Visión Sencilla	</option>
            <option value="Progresive">Progresive	</option>

       </select>
       </div>
        <div class="col-sm-3 form-group">
        <label for="">Color</label>
        <select class="form-control" id="color-export">
            <option value="0">Seleccionar color...</option>
            <option value="Blanco">Blanco	</option>
            <option value="Photocromatico">Photocromatico</option>
       </select>
      </div>
      <div class="col-sm-3 form-group">
      <label for="">Alto Indice</label>
        <select class="form-control" id="aind-export">
            <option value="0">Seleccionar ...</option>
            <option value="Si">Si	</option>
            <option value="No">No</option>
       </select>
     </div>
    </div>
  </div>


      <!-- Modal footer -->
      <div class="modal-footer" id='btn-act-update'>
        <button class="btn btn-outline-primary" id="btnActions"  onclick="exportarOrdenesFechaAnt()">Exportar</button>
      </div>

    </div>
  </div>
</div>
    <!-- ./wrapper -->
    <?php
    require_once("links_js.php");
    ?>
     <script>
       $('#loader').hide();
  </script>

    <script type="text/javascript" src="../js/orden_distribucion.js?v=<?php echo rand() ?>"></script>
    <script type="text/javascript" src="../js/cleave.js"></script>
    <script>
       $(".modal-header").on("mousedown", function (mousedownEvt) {
    let $draggable = $(this);
    let x = mousedownEvt.pageX - $draggable.offset().left,
      y = mousedownEvt.pageY - $draggable.offset().top;
    $("body").on("mousemove.draggable", function (mousemoveEvt) {
      $draggable.closest(".modal-dialog").offset({
        "left": mousemoveEvt.pageX - x,
        "top": mousemoveEvt.pageY - y
      });
    });
    $("body").one("mouseup", function () {
      $("body").off("mousemove.draggable");
    });
    $draggable.closest(".modal").one("bs.modal.hide", function () {
      $("body").off("mousemove.draggable");
    });
  });
    </script>
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

const celdas = document.querySelectorAll('.td-update');
console.log(celdas)
// Recorrer todas las celdas y agregar el evento click a las celdas con la clase especificada
celdas.forEach(celda => {
    celda.addEventListener('click', () => {
      // Cambiar el color de fondo de la celda al hacer clic
      celda.style.backgroundColor = 'yellow';
    });

});
</script>