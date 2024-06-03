<div class="modal align-items-center" id="modalUpdFecha" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-1 bg-info">
                <h5 class="modal-title text-center">Actualizar fecha</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card p-2">
                    <p>Paciente: <span id="paciente_gui" style="font-size: 12px;"></span></p>
                    <p>DUI: <span id="dui_gui" style="font-size: 12px;"></span></p>
                </div>
                <div>
                    <div class="form-group">
                        <input type="date" name="date" id="date" class="form-control" placeholder="fecha">
                    </div>
                    <button class="btn btn-outline-info btn-sm btn-block" id="btnUpdFecha">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>