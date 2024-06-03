<div class="modal" id="modal_recibir_manual_ordenes" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 80%">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header" style="background: #162e41;color: white">
                <h4 class="modal-title" style="font-size: 14px;font-family: Helvetica, Arial, sans-serif;"><b><span>RECIBIR ORDENES DE LABORATORIO LENTI</span></b></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row justify-content-between">
                    <div class="col-sm-12 col-md-8">
                    <input type="text" class="form-control" id="dui" onchange="get_recibir_manual()" placeholder="Digita el DUI">
                    </div>
                    <div class="col-sm-12 col-md-2">
                    <button type="button" id="btn-recib-manual" class="btn btn-default float-right btn-sm " onClick="registrar_recib_orden()" style='margin: 3px'><i class=" fas fa-file-export" style="color: #0275d8"></i> Recibir</button>
                    </div>
                </div>

                <table class="table-hover table-bordered" style="font-family: Helvetica, Arial, sans-serif;max-width: 100%;text-align: left;margin-top: 5px !important" width="100%" id="tabla_acciones_veterans">

                    <thead style="font-family: Helvetica, Arial, sans-serif;width: 100%;text-align: center;font-size: 12px;" class="bg-dark">
                        <th>ID</th>
                        <th>Cod. orden</th>
                        <th>Fecha</th>
                        <th>DUI</th>
                        <th>Paciente</th>
                        <th>Cod. Envio</th>
                        <th>Sucursal</th>
                        <th>Eliminar</th>
                    </thead>
                    <tbody id="items-recibir-ordenes" style="font-size: 12px"></tbody>
                </table>

            </div>
            <!-- Modal footer -->
        </div>
    </div>
</div>