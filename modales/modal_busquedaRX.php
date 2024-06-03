<style>
  
  .label-mat{
    display: block;
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    left: 16px;
    color: rgba(0, 0, 0, 0.5);
    transform-origin: left top;
    user-select: none;
    transition: transform 150ms cubic-bezier(0.4, 0, 0.2, 1),color 150ms cubic-bezier(0.4, 0, 0.2, 1), top 500ms;
  }

  .input-mat {
    width: 100%;
    height: 100%;
    box-sizing: border-box;
    background: transparent;
    caret-color: var(--accent-color);
    border: 1px solid transparent;
    border-bottom-color: #99d0e0;
    color: rgba(0, 0, 0, 0.87);
    /*transition: border 500ms;*/
    padding: 16px 11px 4px;
    font-size: 1rem;
  }
  
  .input-mat:focus {
    outline: none;
    border-bottom-width: 2px;
    border-bottom-color: var(--accent-color);
  }
  
  .input-mat:focus + label {
    color: var(--accent-color);
    
  }
  input:focus + .label-mat,
  input.is-valid + .label-mat {
  transform: translateY(-100%) scale(0.75);
}
/**
*input cantidad de ordenes  modal de para crear ordenes
*/

.input-cantidad-ordenes{
  width: 80px;
  padding: 2px;
  outline: none;
  background: none;
  font-size: 14px;
  font-weight: 700;
  font-family: Helvetica;
  border: 0px;
  border-bottom: 1px solid #99d0e0;
  border-radius: 5px;
  box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.5);
}
.input-cantidad-ordenes:focus{
  border: 1px solid #d9e0e6;
}

/*
* Style de input checked modulo de creditos y cobros::: Fecha manual en modal
*/

.labelInputFechaMan::before{
  border-radius: 50% !important;
}

/*
*estilos para input checked en facturacion
*/
.checkInputFact::before{
  border-radius: 50% !important;
}
/*
*Checkbox style type radio
*/
.checkInputStyle::before{
  border-radius: 50% !important;
}

/**
*style para input
*/

.custom-input {
  position: relative; /* Posición relativa para alinear el texto inicial */
}

.custom-input input {
  border-bottom: 1px solid #5bc0de; /* Borde del campo de texto */
  padding: 5px; /* Espacio interno del campo de texto */
  border-top: none;
  border-radius: 1px;
  border-left: none;
  border-right: none;
  outline: none;
  width: 100%;
  font-size: 15px !important;
}

.custom-input label {
  position: absolute; /* Posición absoluta para superponer el texto inicial */
  top: 12px; /* Ajuste de posición vertical */
  left: 12px; /* Ajuste de posición horizontal */
  pointer-events: none; /* Evita que el texto inicial sea interactivo */
  transition: all 0.2s; /* Animación de transición al hacer focus */
  color: #999; /* Color del texto inicial */
  font-family: "Avenir Next", Avenir, 'Helvetica Neue', 'Lato', 'Segoe UI', Helvetica, Arial, sans-serif;
}

.custom-input input:focus + label,.custom-input input:not(:placeholder-shown) ~ label,.custom-input input:focus {
  top: -12px; /* Mueve el texto hacia arriba al hacer focus o cuando hay texto */
  font-size: 12px; /* Tamaño de fuente más pequeño para el texto movido */
  background: #fff;
}

.custom-input input:focus{
  border: 1px solid #1a73e8;
}
.custom-input input:read-only {
  background-color: #f2f2f2;
}
.container-card{
  position: relative;
}
.custom_title{
  position: absolute;
  top: -8px;
  left: 50%;
  transform: translate(-50%, -50%);
  text-align: center;
  font-size: 15px;
  font-weight: 700;
  background: #fff;
  border-radius: 5px;
  padding: 3px;
}
/*
*END CUSTOM INPUT MODAL DE REGALIA
/*
* Estylos 
**/
</style>
<div class="modal fade" id="busqueda_rx_final" style="text-transform: uppercase;" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header" style="background:black;color:white;">
        <h4 class="modal-title w-100 text-center position-absolute" style="font-size: 15px;">BÚSQUEDA POR GRADUACIÓN &nbsp;&nbsp;</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body"><!--START MODAL BODY-->
        <form action="barcode_orden_print.php" method="POST" target="print_popup" onsubmit="window.open('about:blank','print_popup','width=600,height=500');">
          <!-- <div class="eight datos-generales block-divs">
            <div class="row">
              <div class="form-group col-sm-3">
                <label for="desdeF">Desde</label>
                <input type="date" class="next-input form-control clear_orden_i" id="desdeF" autocomplete='off' style="text-transform: capitalize;" tabindex="1">
              </div>

              <div class="form-group col-sm-3">
                <label for="hastaF">Hasta</label>
                <input type="date" class="next-input form-control clear_orden_i" id="hastaF" autocomplete='off' style="text-transform: capitalize;" tabindex="1">
              </div>
            </div>
          </div> --><!--./*********Fin datos-generales************-->
          <!--################ RX final + medidas #############-->
          <div>
            <div class="row shadow-sm  bg-white">
              <div class="col-sm-12 col-md-12">
                <table style="margin:0px;width:100%">
                  <thead class="thead-light" style="color: black;font-family: Helvetica, Arial, sans-serif;font-size: 11px;text-align: center;background: #f8f8f8">
                    <tr>
                      <th style="text-align:center">OJO</th>
                      <th style="text-align:center">ESFERAS</th>
                      <th style="text-align:center">CILIDROS</th>
                      <th style="text-align:center">ADICION</th>
                      <th style="text-align:center">OJO</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>OD</td>
                      <td> <input type="text" class="next-input input-mat rx_final_values clear_orden_i esf_cil rx_final_val rx_od" id="od_esfera" style="text-align: center"><label for="odesferasf_orden"></label></td>

                      <td> <input type="text" class="next-input input-mat rx_final_values clear_orden_i esf_cil rx_final_val rx_od" id="od_cilindro" style="text-align: center"></td>

                      <td> <input type="text" class="next-input input-mat rx_final_values clear_orden_i rx_final_val rx_od" id="od_adicion" style="text-align: center"></td>

                      <td>
                        <div class="icheck-primary d-inline">
                          <input type="checkbox" id="checkOD" class="checkInputStyle clear_check" name="checkOJO" value="OD">
                          <label for="checkOD">
                          </label>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td>OI</td>
                      <td> <input type="text" class="next-input input-mat rx_final_values clear_orden_i esf_cil rx_final_val rx_oi" id="oi_esfera" style="text-align: center"></td>

                      <td> <input type="text" class="next-input input-mat rx_final_values clear_orden_i esf_cil rx_final_val rx_oi" id="oi_cilindros" style="text-align: center"></td>

                      <td> <input type="text" class="next-input input-mat rx_final_values clear_orden_i rx_final_val rx_oi" id="oi_adicion" style="text-align: center"></td>

                      <td>
                        <div class="icheck-primary d-inline">
                          <input type="checkbox" id="checkOI" class="checkInputStyle clear_check" name="checkOJO" value="OI">
                          <label for="checkOI">
                          </label>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!------------- RESUMEN COBROS ------------>
            </div>
          </div>
        </form>
        <div class="modal-footer d-flex justify-content-end">
          <button class="btn btn-outline-success btn-sm" onclick="buscarOrdenRXFinal()">Buscar</button>
        </div>
        <!-- <div class="content-search-rxfinal">
          <p class="text-center" style="font-weight: bold;">RESULTADOS</hp>
          <div class="card p-1 shadow-lg">
            <table width="100%" class="table-hover table-bordered" id="datatable_search_RXfinal" data-order='[[ 0, "desc" ]]'>
              <thead class="style_th" style="color: white;background:#17a2b8">
                <th>Código</th>
                <th>Fecha</th>
                <th>Paciente</th>
                <th>Ojo derecho</th>
                <th>Ojo izquierdo</th>
                <th>Óptica</th>
                <th>Estado</th>
              </thead>
              <tbody class="style_th"></tbody>
            </table>
          </div>
        </div> -->
      </div>
    </div>
  </div>
  <input type="hidden" id="categoria_opticas">
  <input type="hidden" name="" id="cat_orden" class="clear_orden_i">
  <input type="hidden" id="codigoOrden" class="clear_orden_i" name="codigoOrden">
</div><!--/END MODAL BODY-->


</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->