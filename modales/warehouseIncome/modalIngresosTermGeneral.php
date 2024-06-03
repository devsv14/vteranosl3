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
.header {
  position: sticky;
  top:0;
  }
.container {
  overflow: auto;
}
</style>
<div class="modal fade" id="modal_ingresos_term_general" tabindex="-1" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document" style="max-width: 85%">
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
              <input type="text" class="form-control codigoBarras" id="codigo_lente_term" readonly="">
              <input type="text" class="form-control " id="codigo_term_ingreso" onchange="getLenteTermData()">
              <div class="input-group-append" onClick='clearCode();'>
                <span class="input-group-text bg-dark"><i class="far fa-times-circle"> </i></span>
              </div>
            </div>
          </div>     
          <div class="form-group col-sm-4">
            <label for="inlineRadio1">#CCF/Fact.</label>
              <input class="form-control" type="number" name="inlineRadioOptions" id="num_comprobante__ingreso" value="0" placeholder="Unidades">
          </div>                   
        </div>
        <!-- ITEMS INGRESO -->
          <div>
            <table style="width: 100%;font-family: Helvetica, Arial, sans-serif;text-align: center;font-size: 14px" width="100%" class="table-bordered table-hover">
              <thead class="bg-dark" style="position: sticky;top: 0">
                <td class="header" style="width: 5%">#</td>
                <td class="header" style="width: 7%">Codigo</td>
                <td class="header" style="width: 13%">Marca</td>
                <td class="header" style="width: 13%">Diseño</td>
                <td class="header" style="width: 9%">Stock act.</td>
                <td class="header" style="width: 13%">Esfera</td>
                <td class="header" style="width: 13%">Cilindro</td>
                <td class="header" style="width: 9%">Cantidad</td>
                <td class="header" style="width: 9%">Costo c/u</td>
                <td class="header" style="width: 9%">Eliminar</td>
              </thead>
              <tbody id="items_ingresos_terminados"></tbody>
            </table>
          </div>
          <!-- fin ITEMS INGRESO -->      

      </div>
      <input type="hidden" id="id_lente_term">
      <input type="hidden" id="id_td_ingreso">
      <input type="hidden" id="id_tabla_ingreso">
      <audio id="error_sound_ingreso"><source src="../error-beep.wav" type="audio/wav"></audio> 
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-block" onClick='registroMultiple()'>REGISTRAR INGRESO</button>
      </div>
    </div>
  </div>
</div>

