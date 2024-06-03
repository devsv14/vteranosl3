<div class="modal" id="modal_acta_show_edit">
    <div class="modal-dialog" style="max-width:80%">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title w-100 text-center position-absolute" id="title_modal_acc"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formulario" autocomplete="off">
                    <input type="hidden" id="cod_orden">
                    <input type="hidden" id="id_cita">
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-sm-12 col-md-2 mb-2">
                                <label for="id_acta">ID Acta</label>
                                <div class="input-group">
                                    <input id="id_acta" readonly type="number" min="1" class="form-control oblig" name="id_acta">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-edit" style="cursor: pointer;" onclick="allowEditInput('id_acta')"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-5 mb-2">
                                <label for="paciente-benef">Beneficiario</label>
                                <input id="paciente-benef" onkeyup="mayus(this)" type="text" class="form-control oblig" name="paciente-benef">
                            </div>
                            <div class="col-sm-12 col-md-2 mb-2">
                                <label for="dui-vet">DUI (Acta)</label>
                                <input id="dui-vet" type="text" class="form-control oblig" name="dui-vet">
                            </div>

                            <div class="col-sm-12 col-md-3 mb-2">
                                <label for="tipo-pac">Tipo Paciente</label>
                                <select class="form-control oblig" id="tipo-pac" name="tipo-pac">
                                    <option option="Veterano">Veterano</option>
                                    <option option="Ex-Combatiente">Ex-Combatiente</option>
                                    <option option="Conyuge">Conyuge</option>
                                    <option option="Designado">Designado</option>
                                </select>
                            </div>


                            <div class="col-sm-12 col-md-2 mb-2">
                                <label for="sector-pac">Sector</label>
                                <select class="form-control oblig" id="sector-pac" name="sector-pac">
                                    <option option="FMLN">FMLN</option>
                                    <option option="FAES">FAES</option>
                                    <option option="CONYUGE">CONYUGE</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-4 mb-2" id="content-Receptor">
                                <label for="name-receptor">Receptor</label>
                                <input id="name-receptor" type="text" onkeyup="mayus(this)" class="form-control oblig">
                            </div>
                            <div class="col-sm-12 col-md-2 mb-2 d-block" id="contentInput">
                                <label for="dui_receptor">DUI receptor</label>
                                <input id="dui_receptor" type="text" onkeyup="mayus(this)" class="form-control">

                            </div>
                            <div class="col-sm-12 col-md-4 mb-2">
                                <label for="vet_titular">Nombre titular</label>
                                <input id="vet_titular" type="text" onkeyup="mayus(this)" class="form-control oblig">
                            </div>
                            <div class="col-sm-12 col-md-5 mb-2">
                                <label for="dui_titular">DUI titular</label>
                                <input id="dui_titular" type="text" onkeyup="mayus(this)" class="form-control oblig">
                            </div>
                            <div class="col-sm-12 col-md-2 mb-2">
                                <label for="fecha_impresion">Fecha de impresión</label>
                                <input id="fecha_impresion" type="date" class="form-control oblig">
                            </div>
                        </div>
                        <button type="button" id="edit_acta" onclick="update_acta()" class="btn btn-outline-success btn-sm btn-block my-3">Editar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>