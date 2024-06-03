<style>
.fullscreen-modal .modal-dialog {
  margin: 0;
  margin-right: auto;
  margin-left: auto;
  width: 100%;
}
@media (min-width: 768px) {
  .fullscreen-modal .modal-dialog {
    width: 750px;
  }
}

* {
  box-sizing: border-box;
}

body {
  font: 16px Arial;  
}
</style>
<script>
  function focus_input(){
    console.log('Ok')
   // document.getElementById(codigob_lente).focus();
    $('#codigob_lente').focus(); 
  }
  </script>
<div class="modal fade" id="modal_ingresos_basevs" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog" role="document" style="max-width: 85%">
    <div class="modal-content">
      <div class="modal-header" style="background: black;color: white">
        <h5 class="modal-title" id="title_modal_bases" style="font-size: 14px;text-transform: uppercase;"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-row">
          <div class="col-sm-4">
            <label for="">Codigo</label>
            <div class="input-group">
              <input type="text" class="form-control codigoBarras" id="codigo_lente_term" readonly="">
              <div class="input-group-append" onClick='editCode();'>
                <span class="input-group-text bg-dark"><i class="far fa-edit"> </i></span>
              </div>
            </div>
          </div>          
          <div class="col-sm-4" class="autocomplete">
            <label for="">Marca</label>
            <input type="text" class="form-control" name="marca_basevs" id="marca_basevs" readonly="">
          </div>          
          <div class="col-sm-4">
            <label for="">Diseño</label>
            <input type="text" class="form-control" id="dis_base" readonly="">
          </div>  
          <div class="form-group col-sm-3">
              <label for="inlineRadio1">Base.</label>
              <input class="form-control" type="text" name="inlineRadioOptions" id="base_basevs" readonly="">
          </div>
          <div class="form-group col-sm-3">
            <label for="inlineRadio1">Cant. Ingreso</label>
              <input class="form-control" type="number" name="inlineRadioOptions" id="cant_ingreso_base" value="0" placeholder="Unidades">
          </div>
          <div class="form-group col-sm-3">
            <label for="inlineRadio1">#CCF/Fact.</label>
              <input class="form-control" type="number" name="inlineRadioOptions" id="comprobante_base" value="0" placeholder="Unidades">
          </div>
          <div class="form-group col-sm-3">
            <label for="inlineRadio1">#Costo</label>
              <input class="form-control" type="number" name="inlineRadioOptions" id="costo_base" value="0" placeholder="Unidades">
          </div>
        </div> 
      </div>
      <input type="hidden" id="id_lente_term">
      <input type="hidden" id="id_td_base">
      <input type="hidden" id="id_tabla_base">
      <input type="hidden" id="categoria_codigo" value="Fabricante">
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-block" onClick='setStockBases()'>REGISTRAR INGRESO</button>
      </div>
    </div>
  </div>
</div>

