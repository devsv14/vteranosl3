<!-- The Modal -->
<div class="modal" id="modal_acciones_veteranos" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">    
  <div class="modal-dialog" style="max-width: 85%">
    <div class="modal-content">      
      <!-- Modal Header -->
      <div class="modal-header" style="background: #162e41;color: white">
        <h4 class="modal-title" style="font-size: 14px;"><b><span id="n_ing_tallado">ACCIONES ORDEN</span></b></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>        
      <!-- Modal body -->
      <div class="modal-body">
        <div class="row">

        <div class="col-sm-4">
        <label>Codigo orden*</label>
          <input type="text" class="form-control" id="get_data_orden" onchange="getOrdenAct()">
        </div>

      </div>
    
      <table class="table-hover table-bordered" style="font-family: Helvetica, Arial, sans-serif;max-width: 100%;text-align: left;margin-top: 5px !important" width="100%" id="recibidas_ordenes_lab">
        <thead style="font-family: Helvetica, Arial, sans-serif;width: 100%;text-align: center;font-size: 12px;" class="bg-dark">
          <th>#</th>
          <th>#Orden</th>
          <th>Fecha</th>
          <th>Paciente</th>
          <th>Sucursal</th>
          <th>Eliminar</th>
        </thead>
        <tbody id="items-ordenes-registrar" style="font-size: 12px"></tbody>
      </table>
      </div> 
      
      <input type="hidden" id="acc-optica"> 
      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-block" onClick="registrarIngresoOrdenOpt();" id="btn-acc-opt">Registar ingreso </button>
      </div>
      
    </div>
  </div>
</div>