<div class="modal" id="modal_show_actas_entregadas">
    <div class="modal-dialog" style="max-width:80%">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <button type="button" class="btn btn-sm" onclick="print_entrega_actas()" style="background:#6d0202;color:white;"><i style="font-size: 18px" class="fas fa-file-pdf"></i></button>
                <h5 class="modal-title w-100 text-center" id="title" style="font-size: 18px; font-weight: bold;">Reemprimir Entregas de Actas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table-hover table-responsive-sm table-bordered" style="font-family: Helvetica, Arial, sans-serif;max-width: 100%;text-align: left;margin-top: 5px !important" width="100%" id="dtable_reemprimir_actas">
                    <thead style="font-family: Helvetica, Arial, sans-serif;width: 100%;text-align: center;font-size: 12px;" class="bg-dark">
                        <tr>
                            <th>#</th>
                            <th>ID Acta</th>
                            <th>Fecha</th>
                            <th>DUI</th>
                            <th>Paciente</th>
                            <th>Tipo paciente</th>
                            <th>Sector</th>
                            <th>Sucursal</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 12px"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="codeEntregaActas">