<!-- The Modal -->
<div class="modal" id="modal_rx_traslados">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header" style='padding:4px'>
        <h4 class="modal-title" id='pac_act' style='font-size:15 px'></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      
      <!-- Modal body -->
      <div class="modal-body">
           <div>
        <label for="">Telefono</label>
        <input type="text" class="form-control" id='telefono_trasl' style="width: 25%;">
      </div>
        <table class="table-hovered table-bordered" width='100%' style='text-align:center'>
            <tr class="bg-primary">
                <td></td>
                <td>Esfera</td>
                <td>Cilindro</td>
                <td>Eje</td>
                <td>Add</td>
            </tr>
            <tr>
                <td>OD</td>
                <td id='od_esferash'></td>
                <td id='od_cilindrosh'></td>
                <td id='od_ejesh'></td>
                <td id='od_addsh'></td>
            </tr>
            <tr>
                <td>OI</td>
                <td id='oi_esferash'></td>
                <td id='oi_cilindrosh'></td>
                <td id='oi_ejesh'></td>
                <td id='oi_addsh'></td>
            </tr>
        </table>

        <div class="row form-row">
            <div class="col-sm-4 form-group">
                <label for="">Modelo aro</label>                
                <input type="text" class="form-control" id='l1aromodel'>
            </div>
            <div class="col-sm-4 form-group">
                <label for="">Marca aro</label>
                <input type="text" class="form-control" id='l1aromarca'>
            </div>
            <div class="col-sm-4 form-group">
                <label for="">Color aro</label>
                <input type="text" class="form-control" id='l1arocolor'>
            </div>
            
                        
            <br>
            <p>Depto.: <span id='depto-traslado'></span>   Municipio: <span id='mun-trasl'></span></p>
        </div>

      </div>
      <input type="hidden" id='dui_rx_act'>
      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick='clasificarOrden()'>Clasificar</button>
      </div>

    </div>
  </div>
</div>