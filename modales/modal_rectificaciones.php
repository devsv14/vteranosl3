<style> 
.shadow-textarea textarea.form-control::placeholder {
    font-weight: 300;
}
.shadow-textarea textarea.form-control {
    padding-left: 0.8rem;
}
</style>
<div class="modal" id="rectificacionesModal">
  <div class="modal-dialog" style="max-width: 70%;">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header" style="font-size: 12px;padding:5px;background: #787878; color: white">
        <h4 class="modal-title" style="margin-left: 5px;font-size: 14px"><i class="fas fa-tools"></i> MODAL RECTIFICACIONES <span id="correlativo_rectificacion"> </span></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <div class="form-group shadow-textarea">
            <label for="exampleFormControlTextarea6">Motivo</label>
            <textarea class="form-control z-depth-1" id="motivo-rct" rows="2" placeholder="Especifique el motivo de la rectificación..."></textarea>
        </div>

        <div class="form-group shadow-textarea">
            <label for="exampleFormControlTextarea6">Estado de aro</label>
            <textarea class="form-control z-depth-1" id="est-aro-rct" rows="2" placeholder="Describa el estado del aro..."></textarea>
        </div>
      </div>
      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-block" style="background: #073763;color: white" onClick='registrarRectificacion()'>REGISTRAR RECTIFICACIÓN</button>
      </div>

    </div>
  </div>
</div>