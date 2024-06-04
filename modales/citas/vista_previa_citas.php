<div class="modal" id="modal-import-csv">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-dark py-1" style="padding:10px">
                <h5 class="modal-title w-100 position-absolute" style="font-size: 15px;">IMPORTAR CSV</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-sm-12 col-md-12">
                        <div class="uploadzone">
                            <div class="content-upload-csv" id="drag-warper">
                                <div class="drag-area" id="drag-area">
                                    <form class="form-upload" action="#" id="drag-form">
                                        <input class="file-input" type="file" id="file-input" name="file-csv" hidden>
                                        <i class="fas fa-cloud-upload-alt" id="drag-cloud"></i>
                                        <p id="drag_text">Arrastra y suelta el archivo aquí o haz clic para subirlo.</p>
                                    </form>
                                </div>
                                <section class="progress-area section-datails"></section>
                                <section class="uploaded-area section-datails"></section>
                            </div>
                        </div>
                        <div id="snackbar"></div>

                    </div>
                </div>
                <!-- <table width="100%" class="table-hover table-bordered" id="dt_modal_despachos" data-order='[[ 0, "desc" ]]' style="font-size: 12px">
                    <thead style="text-align:center;font-size:12;background:#343a40;color:#fff" class="style_th">
                        <th>#</th>
                        <th>Id ref.</th>
                        <th>Paciente</th>
                        <th>Dui</th>
                        <th>Edad</th>
                        <th>Telefono</th>
                        <th>Genero</th>
                        <th>Ocupacion</th>
                        <th>Departamento</th>
                        <th>Municipio</th>
                        <th>Tipo pac.</th>
                        <th>Fecha</th>
                        <th>hora</th>
                        <th>Telefono</th>
                        <th>Institución</th>
                        <th>Sucursal</th>
                        <th>Sector</th>
                    </thead>
                    <tbody style="text-align:center;font-size:12px" id="body-table-content"></tbody>
                </table> -->
                <div id="contador_citados"></div>
            </div>
        </div>
    </div>
</div>