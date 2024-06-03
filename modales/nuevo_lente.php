<style>
  /*
Full screen Modal 
*/
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
    $('#codigob_lente').focus(); 
  }
  </script>
  
<div class="modal fade" id="nuevo_lente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" style="max-width: 65%">
    <div class="modal-content">
      <div class="modal-header" style="background: black;color: white">
        <h5 class="modal-title" id="exampleModalLabel">NUEVO LENTE</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <button type="button" class="btn btn-info btn-flat btn-xs" onClick="create_barcode_interno();"><i class="fas fa-barcode"></i> Generar Cod.</button>

      <div class="form-row">
          <div class="col-sm-4">
            <label for="">Codigo</label>
            <input type="text" class="form-control" id="codigob_lente" onchange="read_barcode();" autofocus="">
          </div>
          
          <div class="col-sm-4" class="autocomplete">
            <label for="">Marca</label>
            <input type="text" class="form-control" name="marca_lente" id="marca_lente" onchange="autocomplete_marcas()" onkeyup="valida_diseno();">
          </div>
          
          <div class="col-sm-4">
            <label for="">Diseño</label>
            <input type="text" class="form-control" id="dis_lente">
          </div>
          
      </div>
      
      <div class="eight" style="align-items: center">
          <h1>Tipo de lente</h1>
          <div class="d-flex justify-content-center">
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="inlineRadioOptions" id="vs_term" value="option1" onClick="valida_base_term();">
              <label class="form-check-label" for="inlineRadio1">VS (Terminado)</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="inlineRadioOptions" id="vs_semi_term" value="option2" onClick="valida_base_term();">
              <label class="form-check-label" for="inlineRadio2">VS (Semi-terminado)</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="inlineRadioOptions" id="bifo_flap" value="option3" onClick="valida_base_term();">
              <label class="form-check-label" for="inlineRadio3">Bifocal-Flaptop</label>
            </div>
          </div>
      </div>

      <div class="eight" style="align-items: center" id="terminado_section">
          <h1>Terminado</h1>
          <div class="d-flex justify-content-center form-row">
            <div class="form-group col-sm-4">
              <label for="inlineRadio1">Esfera</label>
              <input class="form-control" type="number" name="inlineRadioOptions" id="vs_term">   
            </div>
          <div class="form-group col-sm-4">
            <label for="inlineRadio1">Cilindro</label>
              <input class="form-control" type="number" name="inlineRadioOptions" id="vs_term">
            </div>
          </div>

        <span id="tipo_lente"></span>  
      </div>

      <div class="eight" style="align-items: center" id="semiterminado_section">
          <h1>VS(Semiterminado)</h1>
          <div class="d-flex justify-content-center form-row">
            <div class="form-group col-sm-4">
              <label for="inlineRadio1">Base</label>
              <input class="form-control" type="text" name="inlineRadioOptions" id="vs_term">
            </div>
            <div class="form-group col-sm-4">
              <label for="inlineRadio1">Diametro</label>
              <input class="form-control" type="text" name="inlineRadioOptions" id="vs_term">
            </div>
          </div>
      </div>

      <div class="eight" style="align-items: center" id="base_section">
          <h1>Base</h1>
          <div class="d-flex justify-content-center form-row">
            <div class="form-group col-sm-4">
              <label for="inlineRadio1">Base</label>
              <input class="form-control" type="text" name="inlineRadioOptions" id="base_flap" onchange="proof()">
            </div>
          <div class="form-group col-sm-4">
            <label for="inlineRadio1">Add.</label>
              <input class="form-control" type="text" name="inlineRadioOptions" id="add_flap">
            </div>

            <div class="form-group col-sm-4">
              <label for="inlineRadio1">Diametro</label>
              <input class="form-control" type="text" name="inlineRadioOptions" id="vs_term">
            </div>
          </div>
      </div>

      <script>
$('#proofsel').select2('data', {id: 100, a_key: 'Lorem Ipsum'}).change();
</script>

      <select class="select2" id="proofsel" multiple="multiple"  data-dropdown-css-class="select2-purple" style="width: 100%;">
                  
      </select>


      <div class="form-row">
          <div class="col-12 col-sm-12">
          <div class="form-group">
          <label>Tratamientos</label>
              <div class="select2-purple">
                <select class="select2" id="lentes_sel" multiple="multiple" data-placeholder="Seleccionar tratamientos" data-dropdown-css-class="select2-purple" style="width: 100%;">
                  <option value="1">BLANCO</option>
                  <option value="2">BLUECAP</option>
                  <option value="3">PHOTOCROM</option>
                  <option value="4">TRANSITIONS</option>
                  <option value="5">1.67</option>
                  <option value="6">TRANSITION 1.67</option>
                  <option value="7">ANTIRREFLEJANTE</option>
                </select>
              </div>
            </div>
          </div>       
      </div><!--Fin form row-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">GUARDAR</button>
      </div>
    </div>
  </div>
</div>

