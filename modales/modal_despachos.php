<div class="modal" id="modal-despachos">
  <div class="modal-dialog" style="max-width:70%">
    <div class="modal-content">
      <div class="modal-header bg-dark" style="padding:10px">
        <h5 class="modal-title w-100 text-center position-absolute">DESPACHAR ORDENES A LABORATORIOS</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">

        <div class="form-group clearfix" style="margin:6px;margin-left:30px">
        <?php if($categoria_usuario=="mensajero" or $categoria_usuario=="Admin" or $_SESSION["sucursal"]=="San miguel" or $_SESSION["sucursal"]=="Usulutan"){?>
        <div class="icheck-success d-inline">
            <input type="radio" name="tipo-desp" id="dsp-cita" class="chk-despachos" value="citas">
            <label for="dsp-cita">Citas diarias
            </label>
        </div>
        <?php }?>
        <div class="icheck-warning d-inline" style="margin:6px">
            <input type="radio" name="tipo-desp" id="dsp-exp" class="chk-despachos" value="expedientes">
            <label for="dsp-exp">Expedientes digitados
            </label>
        </div>

        </div>
       <div class="col-sm-2">
           <button class="btn btn-outline-success btn-flat float-rigth" onClick="sendLab()"><i class="fas fa-paper-plane"></i> Enviar</button> 
       </div>
       <div col-sm-6><span id="cant-env" style="color:red"></span></div>
        </div>
        <table width="100%" class="table-hover table-bordered" id="dt_modal_despachos"  data-order='[[ 0, "desc" ]]' style="font-size: 12px">
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