<style>
    .uploadzone {
        display: flex;
        align-items: center;
        justify-content: center;
        padding-top: 0.1px;
        padding-bottom: 0.1em;

    }

    .content-upload-csv {
        width: 498px;
        background: #fff;
        border-radius: 5px;
        padding: 10px;
        box-shadow: 0px 0px 12px rgba(0, 0, 0, 0.1);
    }

    .content-upload-csv .form-upload {
        height: 110px;
        display: flex;
        cursor: pointer;
        margin: 10px 0;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        border-radius: 5px;
        border: 3px dashed #6990F2;
    }

    .form-upload :where(i, p) {
        color: #6990F2;
    }

    .form-upload i {
        font-size: 50px;
    }

    .form-upload p {
        font-size: 16px;
        padding: 8px;
    }

    .section-datails .row {
        margin-bottom: 10px;
        background: #E9F0FF;
        list-style: none;
        padding: 15px 20px;
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .progress-area .row .content {
        width: 100%;
        margin-left: 15px;
    }

    .progress-area .details {
        display: flex;
        align-items: center;
        margin-bottom: 7px;
        justify-content: space-between;
    }

    .progress-area .content .progress-bar {
        height: 6px;
        width: 100%;
        margin-bottom: 4px;
        background: #fff;
        border-radius: 30px;
    }

    .content .progress-bar .progress {
        height: 100%;
        width: 0%;
        background: #6990F2;
        border-radius: inherit;
    }

    .uploaded-area {
        max-height: 232px;
        overflow-y: scroll;
    }

    .uploaded-area.onprogress {
        max-height: 150px;
    }

    .uploaded-area::-webkit-scrollbar {
        width: 0px;
    }

    .uploaded-area .row .content {
        display: flex;
        align-items: center;
    }

    .uploaded-area .row .details {
        display: flex;
        margin-left: 15px;
        flex-direction: column;
    }

    .uploaded-area .row .details .size {
        color: #404040;
        font-size: 11px;
    }

    .uploaded-area i.fa-check {
        font-size: 16px;
    }

    #snackbar {
        visibility: hidden;
        min-width: 250px;
        /* margin: auto; */
        /* margin-left: -125px; */
        background-color: #ff4d4d;
        color: #fff;
        text-align: center;
        border-radius: 2px;
        padding: 16px;
        position: fixed;
        z-index: 1;
        /* left: 50%; */
        bottom: 30px;
        font-size: 17px;
    }

    #snackbar.show {
        visibility: visible;
        -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
        animation: fadein 0.5s, fadeout 0.5s 2.5s;
    }

    @-webkit-keyframes fadein {
        from {
            bottom: 0;
            opacity: 0;
        }

        to {
            bottom: 30px;
            opacity: 1;
        }
    }

    @keyframes fadein {
        from {
            bottom: 0;
            opacity: 0;
        }

        to {
            bottom: 30px;
            opacity: 1;
        }
    }

    @-webkit-keyframes fadeout {
        from {
            bottom: 30px;
            opacity: 1;
        }

        to {
            bottom: 0;
            opacity: 0;
        }
    }

    @keyframes fadeout {
        from {
            bottom: 30px;
            opacity: 1;
        }

        to {
            bottom: 0;
            opacity: 0;
        }
    }


    @keyframes slide {
        0% {
            transform: translateX(-25%);
        }

        100% {
            transform: translateX(25%);
        }
    }
</style>
<div class="modal" id="modal-import-csv">
    <div class="modal-dialog" style="max-width:70%">
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
                                        <input class="file-input" type="file" id="file-input" name="file" hidden multiple>
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
                <table width="100%" class="table-hover table-bordered" id="dt_modal_despachos" data-order='[[ 0, "desc" ]]' style="font-size: 12px">
                    <thead style="text-align:center;font-size:12" class="style_th bg-primary">
                        <th>Selec</th>
                        <th>Paciente</th>
                        <th>DUI</th>
                        <th>Fecha</th>
                        <th>Sector</th>
                    </thead>
                    <tbody style="text-align:center;font-size:14px" id="body-table-env"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>