<style>
 .modal-header{
        background-color: #7A92A1;
        color: white;
        text-align: center;
    }


  #tamModalAros{
    max-width: 90%;
  }
</style>
  
<!-- Modal -->
<div class="modal fade" id="nuevo_aro" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document" id='tamModalAros'>
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-plus-square"></i> NUEVO ARO</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:white;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
  <div class="form-row" autocomplete="on">
    <div class="form-group  col-md-6">
      <button class="btn btn-sm btn-outline-success btn-flat" style="margin:solid black 1px" data-toggle="modal" data-target="#new-marca" ><i class="fas fa-plus-square"></i> Crear Marca</button>
    </div>
    
   <div class="form-group col-md-6">
      <input type="hidden">
   </div>
  

    <div class="form-group col-md-3 select2-purple">
       <label for="sel1">Seleccione marca:</label>
      <select class="form-control select2" name="" id="marca_aros" multiple="multiple" data-dropdown-css-class="select2-purple" clear_i>

      </select>
    </div>
    
    <div class="form-group col-md-3">
      <label for="inputPassword4">Modelo</label>
      <input type="text" class="form-control clear_i" id="modelo_aro" placeholder="Escriba el Modelo" required="" onkeyup="mayus(this);" >
    </div>

    <div class="form-group col-md-3">
      <label for="inputPassword4">Color</label>
      <input type="text" class="form-control clear_i" id="color_aro" placeholder="Escriba el color" required="" onkeyup="mayus(this);" >
    </div>

    <div class="form-group col-md-3">
      <label for="inputPassword4">Materiales</label>
      <select class="form-control clear_i" id="materiales_aro" required="">
        <option value="">Seleccionar Material</option>
        <option value="METAL">Metal</option>
        <option value="ACETATO">Acetato</option>
        <option value="METAL/ACETATO">Metal/Acetato</option>
        <option value="FIBRA DE CARBONO">Fibra de Carbono</option>
        <option value="TITANIO">Titanio</option>
        <option value="TR90">TR90</option>
      </select>
    </div>
    </div>
  <div class="modal-footer">
  <button type="button" class="btn btn-info btn-block" onClick="guardar_aros();"><i class="fas fa-save"></i> GUARDAR</button>
  </div>

  </div>
      <input type="hidden" id="categoria_producto" value="aros"/>

     
    </div>
  </div>
</div>