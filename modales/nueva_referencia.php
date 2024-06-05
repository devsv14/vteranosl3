<!-- The Modal -->


<style>
    .form-row {
        display: flex;
        flex-wrap: wrap;
    }
    .content-input {
        width: 100%;
    }
    .d-flex {
        display: flex;
    }
    .align-items-center {
        align-items: center;
    }
    .justify-content-center {
        justify-content: center;
    }

</style>
<div class="modal" id="nueva_orden_lab">
  <div class="modal-dialog" style="max-width: 95%">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header bg-dark" style="padding: 5px;">
        <h4 class="modal-title w-200 text-rigth" style="font-size: 15px"><b>NUEVA REFERENCIA</b></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
      <div class="row form-row">
    <div class="col-sm-6 mb-2">
        <div class="content-input input-group">
            <input type="text" class="custom-input clear-input material form-control" id='paciente' name='paciente-nombres'>
            <label class="input-label" for="">Buscar Paciente*</label>
            <button class="btn-add-input btn-primary" type="button" id="btn-consultas">
                <i class="fas fa-search" style="color:white"></i>
            </button>
        </div>
    </div>

    <div class="col-sm-3 mb-2">
        <div class="content-input input-group">
            <input type="text" class="custom-input clear-input material form-control" id='dui_pac' name='paciente-nombres'>
            <label class="input-label" for="">DUI</label>
        </div>
    </div>

    <div class="col-sm-3 mb-2">
        <div class="content-input input-group">
            <input type="text" class="custom-input clear-input material form-control" id='dui_pac' name='paciente-nombres'>
            <label class="input-label" for="">Teléfono</label>
        </div>
    </div>

    <div class="col-sm-3 mb-2">
        <div class="content-input input-group">
            <input type="text" class="custom-input clear-input material form-control" id='dui_pac' name='paciente-nombres'>
            <label class="input-label" for="">Patologías</label>
        </div>
    </div>

    <div class="col-sm-3 mb-2 d-flex flex-column align-items-center justify-content-center">
        <h1 style="font-size:14px">Usuario lentes</h1>
        <div class="d-flex align-items-center">
            <div class="i">
                <input type="radio" name="r3"  id="radioSuccess1">
                <label for="radioSuccess1">Si</label>
            </div>&nbsp;&nbsp;
            <div class="">
                <input type="radio" name="r3" id="radioSuccess2">
                <label for="radioSuccess2">No</label>
            </div>
        </div>
    </div>

    <div class="col-sm-6 mb-2">
        <div class="content-input input-group">
            <input type="text" class="custom-input clear-input material form-control" id='paciente' name='paciente-nombres'>
            <label class="input-label" for="">Lentes y tratamientos*</label>
            <button class="btn-add-input btn-primary" type="button" id="btn-consultas">
                <i class="fas fa-search" style="color:white"></i>
            </button>
        </div>
    </div>

    <div class="col-sm-12 mb-2">
        <div class="content-input input-group">
            <input type="text" class="custom-input clear-input material form-control" id='paciente' name='paciente-nombres'>
            <label class="input-label" for="">Lentes y tratamientos*</label>
            <button class="btn-add-input btn-primary" type="button" id="btn-consultas">
                <i class="fas fa-search" style="color:white"></i>
            </button>
        </div>
    </div>
</div>

      </div> <!-- Fin modal body -->

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>