<div class="modal align-items-center" id="modalUpdFecha" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm modal-md modal-lg">
        <div class="modal-content">
            <div class="modal-header py-1 bg-info">
                <h5 class="modal-title text-center">Actualizar fecha</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card p-2 col-sm-12 col-md-4">
                    <label for="">Ingresar DUI:</label>
                    <input type="text" id="dui-vet" class="form-control">
                </div>
                <div class="mb-2">
                    <table width="100%" class="table-responsive-sm table-hover table-bordered" id="dt-actas-ampo" data-order='[[ 0, "asc" ]]'>
                        <thead class="style_th bg-dark" style="color: white">
                            <th>#</th>
                            <th>Codigo</th>
                            <th>Paciente</th>
                            <th>DUI</th>
                            <th>Sucursal</th>
                            <th>Eliminar</th>
                        </thead>
                        <tbody class="style_th" id="items-table-rows"></tbody>
                    </table>
                </div>
                <button class="btn btn-outline-info btn-sm btn-block" id="btnUpdFecha">Guardar</button>
            </div>
        </div>
    </div>
</div>