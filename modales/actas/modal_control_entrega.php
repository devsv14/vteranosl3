<div class="modal" id="modal_show_entrega">
    <div class="modal-dialog" style="max-width:80%">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title w-100 text-center position-absolute" id="title">Entrega oficial de actas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-between align-items-center">
                    <div class="col-sm-12 col-md-4">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-search-plus"></i></span>
                            </div>
                            <input type="text" class="form-control" id="id_acta" onchange="getActaOrden()" placeholder="ID ACTA">
                        </div>
                    </div>
                    <button onclick="show_modal_emisor()" class="btn btn-outline-success btn-sm border-rounded"><i class="far fa-save"></i> Registrar</button>
                </div>

                <table class="table-hover table-responsive-sm table-bordered" style="font-family: Helvetica, Arial, sans-serif;max-width: 100%;text-align: left;margin-top: 5px !important" width="100%">
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
                            <th>Eliminar</th>
                        </tr>
                    </thead>
                    <tbody id="items-entregas-actas" style="font-size: 12px"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>