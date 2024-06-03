<style>
  .button {
    display: inline-block;
    border: 0;
    outline: 0;
    padding: 12px 16px;
    line-height: 1.4;
    background: linear-gradient(#4d4d4d, #2f2f2f);
    border-radius: 5px;
    border: 1px solid black;
    font-family: "Lucida Grande", "Lucida Sans Unicode", Tahoma, Sans-Serif;
    color: white !important;
    font-size: 1.2em;
    cursor: pointer;
    /* Important part */
    position: relative;
    transition: padding-right .3s ease-out;
  }

  .button.loading {
    background-color: #CCC;
    padding-right: 40px;
  }

  .button.loading:after {
    content: "";
    position: absolute;
    border-radius: 100%;
    right: 6px;
    top: 50%;
    width: 0px;
    height: 0px;
    margin-top: -2px;
    border: 2px solid rgba(255, 255, 255, 0.5);
    border-left-color: #FFF;
    border-top-color: #FFF;
    animation: spin .6s infinite linear, grow .3s forwards ease-out;
  }

  @keyframes spin {
    to {
      transform: rotate(359deg);
    }
  }

  @keyframes grow {
    to {
      width: 14px;
      height: 14px;
      margin-top: -8px;
      right: 13px;
    }
  }
</style>
<!-- Modal -->
<div class="modal fade" id="modal-upload-actas">
  <div class="modal-dialog" role="document" style='max-width:100%'>
    <div class="modal-content">
      <div class="modal-header sticky-top" style='background-color: rgba(255, 255, 255, 0.9)'>
        <h5 class="modal-title" id="exampleModalLongTitle"><span id="acta-data" style='text-transform:uppercase'></span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <form id='imageForm' enctype="multipart/form-data">

          <!-- <div class="row justify-content-center">
            <div class="col-sm-12 col-md-4 col-lg-4">
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1">Correlativo ampo INABVE</span>
                </div>
                <input type="text" name="corrInabve" id="corrInabve" class="form-control" placeholder="Digitar el correlativo" aria-label="Username" aria-describedby="basic-addon1">
              </div>
            </div>
          </div> -->

          <div class="row justify-content-around">

            <div class="col-sm-6 col-md-5 mb-3 shadow bg-white rounded" style='border-radius: 5px'>
              <img id="receta-preview" src="#" alt="Vista previa de la imagen" style='display:none;width:100%; border-radius: 5px; margin-top: 20px;' width='100%' class="img-ampos">
              <input type="file" class="custom-file-input" id="receta" name='receta' onchange='showPreviewImage(this.id)'>
              <label class="custom-file-label" for="receta">Scan viñeta</label>
              <p>Viñeta (Cod. Barras Lab)</p>
            </div>

            <div class="col-sm-6 col-md-5 shadow mb-3 bg-white rounded" style='border-radius: 5px'>
              <img id="expediente-preview" src="#" alt="Vista previa de la imagen" style='display:none; width:100%; border-radius: 5px; margin-top: 20px;' width='100%' class="img-ampos">
              <input type="file" class="custom-file-input" id="expediente" name='expediente' onchange='showPreviewImage(this.id)'>
              <label class="custom-file-label" for="expediente">Scan Expediente</label>
              <p>Receta optometra</p>
            </div>

            <div class="col-sm-6 col-md-5 shadow mb-3 bg-white rounded" style='border-radius: 5px'>
              <img id="acta-preview" src="#" alt="Vista previa de la imagen" style='display:none; width:100%; border-radius: 5px; margin-top: 20px;' width='100%' class="img-ampos">
              <input type="file" name="acta" id="acta" class="custom-file-input" onchange='showPreviewImage(this.id)'>
              <label for="acta" class="custom-file-label">Imagen para acta:</label>
              <p>Acta</p>
            </div>
            <div class="col-sm-6 col-md-5 shadow mb-3 bg-white rounded" style='border-radius: 5px;margin-bottom:8px'>
              <img id="identificacion-preview" src="#" alt="Vista previa de la imagen" style='display:none; width:100%; border-radius: 5px; margin-top: 20px;' width='100%' class="img-ampos">
              <input type="file" name="identificacion" id="identificacion" class="custom-file-input" onchange='showPreviewImage(this.id)'>
              <label for="identificacion" class="custom-file-label">Imagen para identificación:</label>
              <p>Hoja identificacion</p>
            </div>

          </div>
          <input type="hidden" id="ampo_acta" name="ampo_acta">
          <input type="hidden" id="dui_acta" name="dui_acta">
          <input type="hidden" id="sucursal_acta" name="sucursal_acta">
          <input type="hidden" id="id_acta_ampo" name="id_acta_ampo">
        </form>

      </div><!-- Fin modal body -->
      <div class="modal-footer">
        <button class='btn btn-block btn-dark button' id='submitBtnImg' style='margin-top:10px'> CREAR PDF</button>
      </div>
    </div>
  </div>
</div>