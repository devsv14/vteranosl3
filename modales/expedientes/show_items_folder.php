<style>

    .card-content-img{
        height: 450px;
        width: auto;
    }
    .card-content-img img{
        height: 100%;
        width: 100%;
        object-fit: cover;
        border-radius: 4px;
    }
    .text-card{
        font-size: 13px;
    }
</style>

<div class="modal fade show" id="modal-items-folder" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header py-2 bg-dark">
                <h4 class="modal-title" style="font-size: 14px;font-weight: 700;">LISTADO DE ORDENES A ENVIAR</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body p-1">
                <div class="card p-2">
                    <div class="card-header p-1">
                        <p class="m-0"><b>Listado general de pacientes a enviar</b></p>
                    </div>
                    <div class="card-body py-3 px-1">
                        <div class="row" id="ordenes-items-folder">
                            
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end p-1">
                        <button class="btn btn-outline-success btn-sm">Enviar</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>