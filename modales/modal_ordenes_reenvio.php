<div class="modal" id="listarOrdenesReenviadas" style="z-index: 1049">
    <div class="modal-dialog" style="max-width:80%">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="btn btn-sm" onclick="generar_pdf_ordenes_reenvios()" style="background:#6d0202;color:white;"><i style="font-size: 18px" class="fas fa-file-pdf"></i></button>
                <h5 class="modal-title w-100 text-center">ORDENES REENVIADAS (<span id="lab"></span>)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table width="100%" class="table-hover table-bordered" id="dt_ordenes_reenviadas" data-order='[[ 0, "desc" ]]' style="font-size: 12px">
                    <thead style="text-align:center;font-size:12" class="style_th bg-dark">
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Paciente</th>
                            <th>Dui</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody style="text-align:center;font-size:14px"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="cod_reenvio" name="cod_reenvio">