<style>
.button {
    display: inline-block;
    border: 0;
    outline: 0;
    padding: 12px 16px;
    line-height: 1.4;
    background: linear-gradient(#4d4d4d,#2f2f2f);
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
    border: 2px solid rgba(255,255,255,0.5);
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
<div class="modal fade" id="modal-upload-actas" >
  <div class="modal-dialog" role="document" style='max-width:95%'>
    <div class="modal-content">
      <div class="modal-header sticky-top p-1" style='background-color: rgba(255, 255, 255, 0.9);margin-right: 0px !important; margin-right: opx;'>
        <h5 class="modal-title" id="exampleModalLongTitle"><span id="acta-data" style='text-transform:uppercase;font-size:14px'></span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
    <form id='imageForm' enctype="multipart/form-data">

    <div class="row justify-content-around" >

    <div class="col-sm-6 col-md-5 shadow mb-3 bg-white rounded" style='border-radius: 5px'>
        <img id="acta-preview" src="#" alt="Vista previa de la imagen" style='display:none; width:100%; border-radius: 5px; margin-top: 20px;' width='100%' class="img-ampos">
        <input type="file" name="acta" id="acta" class="custom-file-input" onchange='showPreviewImage(this.id)'>
        <label for="acta" class="custom-file-label">Imagen para acta:</label>
        <p>Acta</p>
    </div>

    <div class="col-sm-6 col-md-5 shadow mb-3 bg-white rounded" style='border-radius: 5px'>    
    <img id="expediente-preview" src="#" alt="Vista previa de la imagen" style='display:none; width:100%; border-radius: 5px; margin-top: 20px;' width='100%' class="img-ampos">  
    <input type="file" class="custom-file-input" id="expediente" name='expediente' onchange='showPreviewImage(this.id)'>
    <label class="custom-file-label" for="expediente">Scan Expediente</label>
    <p>Receta optometra</p>
    </div>
    
    <div class="col-sm-6 col-md-5 mb-3 shadow bg-white rounded" style='border-radius: 5px'>     
    <img id="receta-preview" src="#" alt="Vista previa de la imagen" style='display:none;width:100%; border-radius: 5px; margin-top: 20px;' width='100%' class="img-ampos">     
    <input type="file" class="custom-file-input" id="receta" name='receta'  onchange='showPreviewImage(this.id)'>
    <label class="custom-file-label" for="receta">Scan viñeta</label>    
    <p>Viñeta (Cod. Barras Lab)</p>
    </div>

  </div> 
 <input type="hidden" id="dui" name="dui">
 <input type="hidden" id="id_orden" name="id_orden">      
 </form>

      </div><!-- Fin modal body -->
      <div class="modal-footer">
      <button class='btn btn-block btn-dark button' id='submitBtnImg' style='margin-top:10px'> CREAR PDF</button>
      </div>
    </div>
  </div>
</div>

