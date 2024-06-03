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
<div class="modal fade" id="modal_ingresos_term" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog" role="document" style="max-width: 85%">
    <div class="modal-content">
      <div class="modal-header" style="background: black;color: white">
        <h5 class="modal-title" id="title_modal_term"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-row">
          <div class="col-sm-4">
            <label for="">Codigo</label>
            <div class="input-group">
              <input type="text" class="form-control codigoBarras" id="codigo_lente_term">
              <div class="input-group-append" onClick='editCode();'>
                <span class="input-group-text bg-dark"><i class="far fa-edit"> </i></span>
              </div>
            </div>
          </div>          
          <div class="col-sm-4" class="autocomplete">
            <label for="">Marca</label>
            <input type="text" class="form-control" name="marca_lente" id="marca_lente" readonly="">
          </div>          
          <div class="col-sm-4">
            <label for="">Diseño</label>
            <input type="text" class="form-control" id="dis_lente" readonly="">
          </div>          
        </div>      
      
        <div class="eight" style="align-items: center;margin-top: 4px" id="flap_terminado_section">
          <div class="d-flex justify-content-center form-row">          
          <div class="form-group col-sm-2">
              <label for="inlineRadio1">Esf.</label>
              <input class="form-control" type="text" name="inlineRadioOptions" id="esfera_terminado" readonly="">
          </div>
          <div class="form-group col-sm-2">
            <label for="inlineRadio1">Cil.</label>
              <input class="form-control" type="text" name="inlineRadioOptions" id="cilindro_terminado" readonly="">
          </div>
          <div class="form-group col-sm-4">
            <label for="inlineRadio1">Cant. Ingreso</label>
              <input class="form-control" type="search" name="inlineRadioOptions" id="cant_ingreso" value="0" placeholder="Unidades" style="border: 1px solid green">
          </div>
          <div class="form-group col-sm-4">
            <label for="inlineRadio1">Descargo</label>
              <input class="form-control" type="search" name="inlineRadioOptions" id="cant_descargo" value="0" placeholder="Unidades" style="border: 1px solid red">
          </div>
        </div>
      </div>
      </div>
      <input type="hidden" id="id_lente_term">
      <input type="hidden" id="id_td">
      <input type="hidden" id="id_tabla">
      <input type="hidden" id="categoria_codigo" value="Fabricante">
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-block" onClick='setStockTerminados()'>REGISTRAR INGRESO</button>
      </div>
    </div>
  </div>
</div>

