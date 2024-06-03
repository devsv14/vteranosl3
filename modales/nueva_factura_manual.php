<div class="modal" id="modal_factura_manual" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" style="max-width: 90%;">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header bg-dark" style="padding:5px;color:white; text-align:center">
      <span id="txt_num_factura"></span>
            <h4 class="modal-title  w-100 text-center position-absolute" id="title-cobros-gen" style='font-size:15px'>FACTURA MANUAL</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">

        <div class="form-row">

            <div class="form-group col-md-4">
                <label for="ex3">Cliente</label>
                <input type="search" onkeyup="mayus(this)"  class="form-control clear_input oblig" name=""  id="cliente">
            </div>

            <div class="form-group col-md-3">
                <label for="ex3">Dirección</label>
                <input type="search" onkeyup="mayus(this)" class="form-control clear_input oblig" name=""  id="dir">
            </div>

            <div class="form-group col-md-2">
                <label for="ex3">Tel.</label>
                <input type="search"  class="form-control clear_input oblig" name=""  id="tel">
            </div>

            <div class="form-group col-md-2">
                <label for="ex3">Fecha.</label>
                <input type="date"  class="form-control clear_input oblig" name=""  id="fecha_fac">
            </div>
            <div class="form-group col-md-1">
                <label for="ex3">No. Factura</label>
                <input type="text"  class="form-control clear_input oblig" name=""  id="num_factura">
            </div>
        </div>
        
        <div class="form-row">

            <div class="form-group col-md-2">
                <label for="ex3">Cantidad</label>
                <input type="number"  class="form-control clear_input" name="" placeholder="Cant." required="" id="cantfac">
            </div>

            <div class="form-group col-md-8">
                <label for="ex3">Descripcion</label>
                <input type="search" onkeyup="mayus(this)" class="form-control clear_input" name="" placeholder="Descripcion" required="" id="desc_fact">
            </div>

            <div class="form-group col-md-2">
                <label for="ex3">P. Unit.</label>
                <input type="number" class="form-control clear_input" name="" placeholder="Precio Unitario." required="" id="p_unit_fact">
            </div>            
        </div>


        <table class="table-striped" width="100%">
        <tr class="bg-primary" style="text-align:center">
            <th colspan="15">Cantidad</th>
            <th colspan="50">Descripcion</th>
            <th colspan="10">P.Unit</th>
            <th colspan="10">Subtotal</th>
            <th colspan="15">Acc.</th>
        </tr>    
        <tbody id="det_manual"></tbody>
        <tfoot><tr>
            <td colspan="75"></td>
            <td colspan="10" style="text-align:center"><b><span id="totales_man" style="border-bottom: double 1px;"></span></b></td>
            <td colspan="15"></td>
        </tr></tfoot>
        </table>
   

        <div class="eight">
            <h1>RETENCIóN</h1>
            <div class="d-flex justify-content-center">
            <input type="number" class="form-control clear_input" style="width:110px" placeholder="Retencion" id="retencion">
            </div> 
        </div>    

      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-block btn-secondary" onClick="sendDataFact()">IMPRIMIR</button>
      </div>

    </div>
  </div>
</div>