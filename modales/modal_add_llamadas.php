<!-- The Modal -->
<style>

    @media (max-width: 760px){
        .modal-dialog{
            width: 100%;
        }
    }
</style>
<div class="modal col-sm-12 col-md-12" id="modal_add_phone" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog col-sm-12 col-md-6" style="max-width: 80%">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header" style="background: #162e41;color: white">
                <h4 class="modal-title" style="font-size: 20px;font-family: Helvetica, Arial, sans-serif;"><b><span class="badge badge-success" id="name_paciente"></span>
                        <span class="badge badge-info" id="paciente_sucursal"></span>
                    </b></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <h4 class="modal-title my-3" style="font-size: 15px;font-family: Helvetica, Arial, sans-serif;"><b><span id="tel_principal"></b></h4>
                <h4 class="modal-title my-3" style="font-size: 15px;font-family: Helvetica, Arial, sans-serif;"><b><span id="tel_opcional"></b></h4>
                <div class="card-body p-0">
                    <label>Observaciones:</label>
                    <div class="form-row">
                        <div class="col-sm-12 col-md-3">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                    </div>
                                    <select class="form-control oblig" id="estado_llamada">
                                        <option value="">Seleccionar</option>
                                        <option value="Enterado/a">Enterado/a</option>
                                        <option value="Llamó a Contact Center">Llamó a Contact Center</option>
                                        <option value="Número equivocado">Número equivocado</option>
                                        <option value="Buzón de voz">Buzón de voz</option>
                                        <option value="Cambio de número">Cambio de número</option>
                                        <option value="Otras">Otras</option>
                                    </select>
                                </div>
                                <!-- /.input group -->
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-8">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i style="font-size: 24px;" class="fas fa-pen-square"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="accion">
                                </div>
                                <!-- /.input group -->
                            </div>
                        </div>
                        <!-- /.form group -->
                        <div class="col-sm-12 col-md-1">
                            <button onclick="save_accion_entregas()" class="btn btn-outline-success btn-block"><i class="fas fa-save"></i> Guardar</button>
                        </div>
                    </div>

                </div>
                <div class="card-footer" id="preload_data">
                    <table width="100%" class="table table-responsive-sm table-hover table-bordered">
                        <thead class="style_th bg-dark" style="color: white">
                            <th>#</th>
                            <th>Estado llamada</th>
                            <th>Acción</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Usuario</th>
                        </thead>
                        <tbody class="style_th" id="dt_acc_phone"></tbody>
                    </table>
                </div>
            </div>
            <!-- Modal footer -->

        </div>
    </div>
</div>

<input type="hidden" id="id_accion_optica">