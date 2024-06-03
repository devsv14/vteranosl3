<style>
  .rx_final_val {
    text-align: center;
  }

  label {
    font-size: 13px;
  }
</style>
<!-- The Modal -->
<div class="modal" id="modal_upd_rx_orden">
  <div class="modal-dialog" style='max-width: 85%'>
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header" style='padding:4px'>
        <h4 class="modal-title" id='pac_act' style='font-size:15 px'></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <span id='dui-trasl-confirm'></span>
        <table class="table-hovered table-bordered mb-2" width='100%' style="background:#f8f8f8;">
          <thead>
            <tr class="text-center" style="font-size: 12px;">
              <th style="background: #343a40!important;color:#f2f2f2;">TRATAMIENTOS</th>
              <th style="background: #343a40!important;color:#f2f2f2;">LENTE</th>
              <th style="background: #343a40!important;color:#f2f2f2;">AI</th>
              <th style="background: #343a40!important;color:#f2f2f2;">TIPO ORDEN</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <div class="col-sm-12 d-flex justify-content-center">
                  <!-- radio -->
                  <div class="form-group m-0 clearfix">
                    <div class="icheck-success d-inline mr-2">
                      <input type="radio" name="tratamiento" value="Blanco" id="Blanco">
                      <label for="Blanco">
                        Blanco
                      </label>
                    </div>
                    <div class="icheck-success d-inline">
                      <input type="radio" name="tratamiento" value="Photocromatico" id="Photocromatico">
                      <label for="Photocromatico">
                        Photocromatico
                      </label>
                    </div>
                  </div>
                </div>
              </td>
              <td>
                <div class="col-sm-12 d-flex justify-content-center">
                  <!-- radio -->
                  <div class="form-group m-0 clearfix">
                    <div class="icheck-dark d-inline mr-2">
                      <input type="radio" name="tipo_lente" value="Visión Sencilla" id="VisionSencilla">
                      <label for="VisionSencilla">
                        Visión Sencilla
                      </label>
                    </div>
                    <div class="icheck-dark d-inline mr-2">
                      <input type="radio" name="tipo_lente" value="Flaptop" id="Flaptop">
                      <label for="Flaptop">
                        Flaptop
                      </label>
                    </div>
                    <div class="icheck-dark d-inline">
                      <input type="radio" name="tipo_lente" value="Progresive" id="Progresive">
                      <label for="Progresive">
                        Progresive
                      </label>
                    </div>
                  </div>
                </div>
              </td>
              <td>
                <div class="icheck-success d-inline">
                  <input type="radio" name="alto-indice" disabled id="alto-indice">
                  <label class="label-index">
                    Alto indice
                  </label>
                </div>
              </td>

              <td>
                <div class="icheck-success d-inline">
                  <input type="radio" name="lab-traslado" id="t-lab" value='lab-trasl'>
                  <label for="t-lab">
                   Lab.
                  </label>
                </div>

                <div class="icheck-success d-inline">
                  <input type="radio" name="lab-traslado" id="t-term" value='ent-trasl'>
                  <label for="t-term">
                   Entrega
                  </label>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
        <table class="table-hovered table-bordered" width='100%' style='text-align:center'>
          <tr>
            <td colspan="5" class="text-center" style="font-size: 13px;font-weight:700;">RX FINAL</td>
          </tr>
          <tr class="bg-primary">
            <td></td>
            <td>Esfera</td>
            <td>Cilindro</td>
            <td>Eje</td>
            <td>Add</td>
          </tr>
          <tr>
            <td>OD</td>
            <td><input type="text" class="form-control rx_final_val" onchange="validaAltoIndice()" onkeyup="validaAltoIndice()" id='od_esferash'></td>
            <td><input type="text" class="form-control rx_final_val" onchange="validaAltoIndice()" onkeyup="validaAltoIndice()" id='od_cilindrosh'></td>
            <td><input type="text" class="form-control text-center" id='od_ejesh'></td>
            <td><input type="text" class="form-control rx_final_val" id='od_addsh'></td>
          </tr>
          <tr>
            <td>OI</td>
            <td><input type="text" class="form-control rx_final_val" onchange="validaAltoIndice()" onkeyup="validaAltoIndice()" id='oi_esferash'></td>
            <td><input type="text" class="form-control rx_final_val" onchange="validaAltoIndice()" onkeyup="validaAltoIndice()" id='oi_cilindrosh'></td>
            <td><input type="text" class="form-control text-center" id='oi_ejesh'></td>
            <td><input type="text" class="form-control rx_final_val" id='oi_addsh'></td>
          </tr>
        </table>

        <div class="row form-row mb-2">
          <div class="col-sm-2 form-group">
            <label class="mb-0" for="l1aromodel">Modelo aro</label>
            <input type="text" class="form-control" id='l1aromodelt'>
          </div>
          <div class="col-sm-2 form-group">
            <label class="mb-0" for="l1aromarca">Marca aro</label>
            <input type="text" class="form-control" id='l1aromarcat'>
          </div>
          <div class="col-sm-2 form-group">
            <label class="mb-0" for="l1arocolor">Color aro</label>
            <input type="text" class="form-control" id='l1arocolort'>
          </div>

          <div class="col-sm-3 form-group">
            <label class="mb-0" for="l1arocolor">Horizontal</label>
            <input type="text" class="form-control" id='horizontalt' readonly>
          </div>

          <div class="col-sm-3 form-group">
            <label class="mb-0" for="l1arocolor">Vertical</label>
            <input type="text" class="form-control" id='verticalt' readonly>
          </div>

          <!-- Alturas pupilares -->
          <div class="col-sm-3 form-group">
            <label class="mb-0" for="l1arocolor">DP OD</label>
            <input type="text" class="form-control" id='dpodt' readonly>
          </div>

          <div class="col-sm-3 form-group">
            <label class="mb-0" for="l1arocolor">DP OI</label>
            <input type="text" class="form-control" id='dpoit' readonly>
          </div>

          <div class="col-sm-3 form-group">
            <label class="mb-0" for="l1arocolor">Alt. OD</label>
            <input type="text" class="form-control" id='aodt' readonly>
          </div>

          <div class="col-sm-3 form-group">
            <label class="mb-0" for="l1arocolor">Alt. OI</label>
            <input type="text" class="form-control" id='aodi' readonly>
          </div>

        </div>
     
      </div>
      <input type="hidden" id='dui_rx_act'>
      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-info btn-block btn-sm" onclick='crearOrdenTraslado()' style="height: 30px;">Actualizar</button>
      </div>

    </div>
  </div>
</div>