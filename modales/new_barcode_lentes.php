<style>
#tam_modal_bc{
  max-width: 40% !important;
}
@media (min-width: 768px) {
  .fullscreen-modal .modal-dialog {
    width: 100%;
  }
}
body {
  font: 16px Arial;  
}
</style>
<div class="modal fade" id="new_barcode_lens" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" style="max-width: 65%" id="tam_modal_bc">
    <div class="modal-content">
      <div class="modal-header" style="background: #1c1d22;color: white">
        <h5 class="modal-title" id="exampleModalLabel">REGISTRAR CODIGO DE BARRA</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="col-sm-12">
            <label for="">Codigo</label>
            <div class="input-group">
            <input type="text" class="form-control" id="codebar_lente" name="barcode_lente" onchange="set_code_bar();">
              <div class="input-group-append" onClick="codigoInternoProducto();">
                <span class="input-group-text bg-dark"><i class="fas fa-barcode"> </i></span>
              </div>
            </div>  
          </div>        
      </div>
      <input type="hidden" id="id_terminado_lense">
    </div>
  </div>
</div>