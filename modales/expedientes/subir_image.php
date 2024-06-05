<style>
    #imagePreview {
        /* width: 416px; */
        width: auto;
        height: 656px;
        border: 2px dotted #f0f1f3;
        border-radius: 4px;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #fff;
    }

    #imagePreview img {
        width: 100%;
        height: 100%;
        border-radius: 4px;
        padding: 4px;
    }
</style>
<div class="modal fade show" id="modal-subir-imagen" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header py-2 bg-dark">
                <h4 class="modal-title" style="font-size: 14px;font-weight: 700;">SUBIR FOTO</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <form id="form-data-foto" enctype="multipart/form-data">
                        <div class="card-header p-1">
                            <div class="input-group">
                                <input type="text" readonly class="form-control form-sm" id="ref-id-paciente" placeholder="Seleccionar paciente">
                                <div class="input-group-append">
                                    <span class="input-group-text" style="cursor: pointer;" onclick="listarPacientesRef()"><i class="fas fa-user-plus"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-1 d-flex justify-content-center align-items-center">
                            <input type="file" id="file_imagen" onclick="agregar_imagen()" name="file_imagen" accept="image/*" style="display: none;">
                            <div class="container-img">
                                <button type="button" onclick="agregar_imagen()" class="btn btn-outline-info w-100"><i class="fas fa-upload"></i> Seleccionar imagen</button>
                                <div id="imagePreview">
                                    <div class="images-content" style="padding: 10px;">
                                        <i class="far fa-file-image" style="font-size: 70px;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer p-1 d-flex justify-content-end bg-none">
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>