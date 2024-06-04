<style>
  .etiqueta {
    color: black;
    font-family: Helvetica, Arial, sans-serif;
    font-size: 13px;
    text-align: center;
  }
  /*
  CSS para ocultar btn
  */
  .hide{
    display: none;
    width: 0;
    height: 0;
  }

</style>
<div class="modal fade" id="" style="" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable" style="max-width: 95%">
    <div class="modal-content">
      <div class="modal-header bg-dark" style="padding: 5px;">
        <h4 class="modal-title w-200 text-rigth" style="font-size: 15px"><span id="correlativo_op"></span>
          <h5 id="modal_title" style="text-align: center; font-size:14px" class="modal-title w-100 text-center">NUEVA ORDEN</h5>
        </h4>
        <button id="reimpresioReceta" data-toggle="tooltip" data-placement="bottom" title="Reemprimir receta de la orden" class="btn btn-xs btn-info mx-2" onclick="reemprimirVinetas()"><i class="fas fa-id-card-alt fa-2x"></i></button>
        <button class="btn btn-xs btn-success" id="btnBuscarCitado" onClick="buscarCitado()"><i class="fas fa-search fa-2x"></i></button>&nbsp;&nbsp;
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <!--START MODAL BODY-->

        <div class="row mb-2" id="radio_button_orden">         
       

        <div class="shadow-sm" id="cita_content">

          <div id="show_form_manual">
            <div class="form-row">

            <div class="col-sm-6 sel2-mb">
                    <div class="content-input input-group">
                    <input type="text" class="custom-input clear-input material form-control" id='paciente' name='paciente-nombres'>
                        <label class="input-label" for="">Buscar Paciente*</label>
                        <button class="btn-add-input btn-primary" type="button" id="btn-consultas"> <i class="fas fa-search" style="color:white">
                    </i></button>
                    </div>
            </div>

            <div class="col-sm-3 sel2-mb">
                    <div class="content-input input-group">
                    <input type="text" class="custom-input clear-input material form-control" id='dui_pac' name='paciente-nombres'>
                        <label class="input-label" for="">DUI</label>
                    
                    </div>
            </div>
            
            <div class="col-sm-3 sel2-mb">
                    <div class="content-input input-group">
                    <input type="text" class="custom-input clear-input material form-control" id='dui_pac' name='paciente-nombres'>
                        <label class="input-label" for="">Telefono</label>
                    
                    </div>
            </div>


              <div class="form-group col-md-2" id="select_manual">
                <label class="form-check-label">Sucursal*</label>
                <div class="input-group">
                  <select class="form-control clear_orden_i oblig_form_manual" id="sucursal_optica" required>
                    <option value="0" selected disabled>Seleccionar sucursal...</option>
                    <option value="Metrocentro">Metrocentro</option>
                    <option value="San Miguel AV PLUS">San Miguel AV PLUS</option>
                    <option value="Cascadas">Cascadas</option>
                    <option value="Santa Ana">Santa Ana</option>
                    <option value="Chalatenango">Chalatenango</option>
                    <option value="Ahuachapan">Ahuachapan</option>
                    <option value="Ciudad Arce">Ciudad Arce</option>                               
                    <option value="Apopa">Apopa</option>
                    <option value="San Vicente Centro">San Vicente Centro</option>
                    <option value="San Vicente">San Vicente</option>
                    <option value="Gotera">Gotera</option>
                    <!---- <option value="Jornada Rancho Quemado">Jornada Rancho Quemado</option>
                    <option value="Jornada San Miguel">Jornada San Miguel</option>
                    <option value="Jornada Potonico">Jornada Potonico</option>
                    <option value="Jornada Conchagua">Jornada Conchagua</option>
                    <option value="Jornada Santa Ana">Jornada Santa Ana</option>
                    <option value="Jornada Meanguera">Jornada Meanguera</option>
                    <option value="Jornada San Vicente">Jornada San Vicente</option>
                    <option value="Jornada Sonsonate">Jornada Sonsonate</option>
                    <option value="Jornada Meanguera 2">Jornada Meanguera 2</option> -->
                  </select>
                </div>
              </div>

              <div class="form-group col-md-2" id="select_cita">
                <label class="form-check-label">Sucursal*</label>
                <div class="input-group">
                  <select class="form-control clear_orden_i" id="sucursal_optica_cita" required>
                    <option value="0" selected disabled>Seleccionar sucursal...</option>
                    <option value="Valencia">Valencia</option>
                    <option value="Metrocentro">Metrocentro</option>
                    <option value="Cascadas">Cascadas</option>
                    <option value="Santa Ana">Santa Ana</option>
                    <option value="Chalatenango">Chalatenango</option>
                    <option value="Ahuachapan">Ahuachapan</option>
                    <option value="Sonsonate">Sonsonate</option>
                    <option value="Ciudad Arce">Ciudad Arce</option>                                   
                    <option value="Opico">Opico</option>
                    <option value="Apopa">Apopa</option>
                    <option value="San Vicente Centro">San Vicente Centro</option>
                    <option value="San Vicente">San Vicente</option>
                    <option value="Gotera">Gotera</option>
                    <option value="San Miguel">San Miguel</option>
                    <option value="Usulutan">Usulutan</option>
                    
                  </select>
                </div>
              </div>

              <div class="form-group col-md-2">
                <label class="form-check-label">Sector*</label>
                <select name="" id="instit" class="form-control clear_orden_i oblig_form_manual" required>
                  <option value="" selected disabled>Seleccionar</option>
                  <option value="FAES">FAES</option>
                  <option value="FMLN">FMLN</option>
                  <option value="CONYUGE">CÓNYUGE</option>
                  <option value="BRF">BRF</option>
                </select>
              </div>

            </div>
            <div id="titular_form" style="display: none;">
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label class="form-check-label">Titular*</label>
                  <input type="text" id="titular" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" class="form-control clear_orden_i" required>
                </div>

                <div class="form-group col-md-3">
                  <label class="form-check-label">DUI Titular*</label>
                  <input type="text" id="dui_titular" pattern="[1-9]-?\d{4}-?\d{4}" class="form-control clear_orden_i" required>
                </div>


              </div>
            </div>
          </div>

          <div id="tables_cita">
            <table width="100%" class="table-bordered" style="text-align:center; text-transform:uppercase;font-size:13px">
              <thead style="color:black;font-family: Helvetica, Arial, sans-serif;font-size: 11px;text-align: center;background: 	#F0F0F0">
                <tr>
                  <th style="width:40%">PACIENTE</th>
                  <th style="width:15%">DUI</th>
                  <th style="width:10%">EDAD</th>
                  <th style="width:20%">TELEFONO</th>
                  <th style="width:15%">GENERO</th>
                </tr>
              </thead>
              <tr>
                <td id="paciente_t" style="width:40%"></td>
                <td id="dui_pac_t" style="width:15%"></td>
                <td id="edad_pac_t" style="width:10%"></td>
                <td id="telef_pac_t" style="width:20%"></td>
                <td id="genero_pac_t" style="width:15%"></td>
              </tr>
            </table>

            <table width="100%" class="table-bordered" style="text-align:center;text-transform:uppercase;font-size:13px">
              <thead style="color:black;font-family: Helvetica, Arial, sans-serif;font-size: 11px;text-align: center;background:#F0F0F0">
                <tr>
                  <th style="width:20%">OCUPACIÓN</th>
                  <th style="width:20%">SECTOR</th>
                  <th style="width:25%">DEPARTAMENTO</th>
                  <th style="width:35%">MUNICIPIO</th>
                </tr>
              </thead>
              <tr>
                <td id="ocupacion_pac_t" style="width:20%"></td>
                <td id="instit_t" style="width:20%"></td>
                <td id="departamento_pac_t" style="width:25%"></td>
                <td id="munic_pac_data_t" style="width:35%"></td>
              </tr>
            </table>
          </div>


        </div>
        <div class="row">
          <div class="col-sm-3 bg-light" style="font-size:12px; text-align:center;">PATOLOGIAS</div>
          <div class="col-sm-2 bg-light" style="font-size:12px; text-align:center;">USUARIO LENTE</div>
          <div class="col-sm-4 bg-primary" style="font-size:12px; text-align:center;">TRATAMIENTOS</div>
          <div class="col-sm-3 bg-dark" style="font-size:12px; text-align:center;">TIPO LENTE</div>

          <div class="col-sm-3" style="margin-top:3px;">

            <select class="form-control clear_orden_i oblig" id="patologias-ord" style="border: 1px solid green" name="usuario">
              <option value="">Seleccionar patologias...</option>
              <option value="No">No</option>
              <option value="Cataratas">Cataratas</option>
              <option value="Pterigión">Pterigión</option>
              <option value="Retinopatía">Retinopatía</option>
              <option value="Glaucoma">Glaucoma</option>
            </select>

          </div>

          <div class="form-group col-sm-2" style="margin-top:3px;">
            <select name="usuario_lente" class="form-control clear_orden_i oblig" id="usuario_lente">
              <option value="" selected disabled>Selec. usuario lente</option>
              <option value="Si">Si</option>
              <option value="No">No</option>
            </select>
          </div>

          <div class="col-sm-4" style="margin-top:3px;background:#E3EFF9">
            <div class="row">
              <div class="col-sm-4" class="d-flex justify-content-center" style="display:flex;justify-content: center;margin-top:0px;">
                <div class="form-check form-check-inline">
                  <input class="form-check-input chk_element" type="radio" id="blanco" value="Blanco" name="colors">
                  <label class="form-check-label" for="blanco" id="lentevs">Blanco</label>
                </div>
              </div>
              <div class="col-sm-4" style="display:flex;justify-content: center;margin-top:0px;">
                <div class="form-check form-check-inline">
                  <input class="form-check-input chk_element" type="radio" id="photo" value="Photocromatico" name="colors">
                  <label class="form-check-label" for="photo" id="lentebf">Photocroma</label>
                </div>
              </div>
              <div class="col-sm-4" style="display:flex;justify-content: center;margin-top:0px;">
                <div class="form-check form-check-inline">
                  <input class="form-check-input chl_element chl_element" type="radio" id="alto-indice" value="Progresive" name="indice" disabled="disabled">
                  <label class="form-check-label" for="alto-indice" id="label-index">Alto indice</label>
                </div>
              </div>
            </div>
          </div>

          <div class="col-sm-3" style="margin-top:3px;background:#f8f8f8">
            <div class="row">
              <div class="col-sm-4" class="d-flex justify-content-center" style="display:flex;justify-content: center;margin-top:0px;">
                <div class="form-check form-check-inline">
                  <input class="form-check-input chk_element" type="radio" id="VisionSencilla" value="Visión Sencilla" name="tipo_lente">
                  <label class="form-check-label" for="VisionSencilla" id="lentevs">VS</label>
                </div>
              </div>
              <div class="col-sm-4" style="display:flex;justify-content: center;margin-top:0px;">
                <div class="form-check form-check-inline">
                  <input class="form-check-input chk_element" type="radio" id="Flaptop" value="Flaptop" name="tipo_lente">
                  <label class="form-check-label" for="Flaptop" id="lentebf">Flaptop</label>
                </div>
              </div>
              <div class="col-sm-4" style="display:flex;justify-content: center;margin-top:0px;">
                <div class="form-check form-check-inline">
                  <input class="form-check-input chl_element" type="radio" id="Progresive" value="Progresive" name="tipo_lente">
                  <label class="form-check-label" for="Progresive" id="lentemulti">Progresive</label>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!--################ RX final + medidas #############-->
        <div class="eight">
          <strong>
            <h1 style="color: #034f84">GRADUACIÓN(Rx Final)</h1>
          </strong>
          <div class="row">
            <div class="col-sm-12">
              <table style="margin:0px;width:100%">
                <thead class="thead-light" style="color: black;font-family: Helvetica, Arial, sans-serif;font-size: 11px;text-align: center;background: #f8f8f8">
                  <tr>
                    <th style="text-align:center">OJO</th>
                    <th style="text-align:center">ESFERAS</th>
                    <th style="text-align:center">CILIDROS</th>
                    <th style="text-align:center">EJE</th>
                    <th style="text-align:center">ADICION</th>

                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>OD</td>
                    <td> <input type="text" class="form-control clear_orden_i rx_f oblig" id="odesferasf" style="text-align: center"  onClick="validaAltoIndice()" onkeyup=" validaAltoIndice()"></td>
                    <td> <input type="text" class="form-control clear_orden_i rx_f oblig" id="odcilindrosf" style="text-align: center"></td>
                    <td> <input type="text" class="form-control clear_orden_i rx_f oblig" id="odejesf" style="text-align: center"></td>
                    <td> <input type="text" class="form-control clear_orden_i rx_f oblig" id="oddicionf" style="text-align: center"></td>

                  </tr>
                  <tr>
                    <td>OI</td>
                    <td> <input type="text" class="form-control clear_orden_i rx_f oblig" id="oiesferasf" style="text-align: center"  onClick="validaAltoIndice()" onkeyup=" validaAltoIndice()">
                    </td>
                    <td> <input type="text" class="form-control clear_orden_i rx_f oblig" id="oicilindrosf" style="text-align: center"></td>
                    <td> <input type="text" class="form-control clear_orden_i rx_f oblig" id="oiejesf" style="text-align: center"></td>
                    <td> <input type="text" class="form-control clear_orden_i rx_f oblig" id="oiadicionf" style="text-align: center"></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <!--################ FIN rx final + medidas #############-->
        <div class="row distancias">
          <div class="col-sm-3 dist_pupilar">
            <div class="eight" style="align-items: center">
              <h1>DISTANCIA PUPILAR</h1>
              <div class="d-flex justify-content-center">
                <div class="form-group row">

                  <div class="col-md-6">
                    <label for="od_pupilar" class="etiqueta">OD</label>
                    <input type="text" class="form-control clear_orden_i oblig" placeholder="mm" id="od_pupilar"  onClick=" validaAltoIndice()" onkeyup=" validaAltoIndice()">
                  </div>

                  <div class="col-md-6">
                    <label for="" class="etiqueta">OI</label>
                    <input type="text" class="form-control clear_orden_i oblig" placeholder="mm" id="oipupilar"  onClick=" validaAltoIndice()" onkeyup=" validaAltoIndice()">
                  </div>

                </div>
                <!--FIN Form Row-->

              </div>
            </div>
          </div>
          <!--Fin distancia pupilar-->

          <!--###### ALTURA DE LENTE ########-->

          <div class="col-sm-3 dist_pupilar">
            <div class="eight" style="align-items: center">
              <h1>ALTURA DE LENTE</h1>
              <div class="d-flex justify-content-center">
                <div class="form-row">

                  <div class="col-md-6">
                    <label for="" class="etiqueta">OD</label>
                    <input type="text" class="form-control clear_orden_i oblig" placeholder="mm" id="odlente" onClick=" validaAltoIndice()" onkeyup=" validaAltoIndice()">
                  </div>

                  <div class="col-md-6">
                    <label for="" class="etiqueta">OI</label>
                    <input type="text" class="form-control clear_orden_i oblig" placeholder="mm" id="oilente">
                  </div>

                </div>
                <!--FIN Form Row-->

              </div>
            </div>
          </div>
          <!--Fin distancia pupilar-->

          <div class="col-sm-6 agudeza">
            <div class="eight" style="align-items: center">
              <h1>AGUDEZA VISUAL</h1>
              <div class="d-flex justify-content-center">
                <div class="form-row">

                  <div class="col-md-6">
                    <label for="" class="etiqueta" style="text-align: center">AVsc</label>
                    <div style="display: flex">
                      <input type="text" class="form-control clear_orden_i" placeholder="OD" id="avsc">
                      <input type="text" class="form-control clear_orden_i" placeholder="OI" id="avsc_oi">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <label for="" class="etiqueta" style="text-align: center">AVfinal</label>
                    <div style="display: flex">
                      <input type="text" class="form-control clear_orden_i" placeholder="OD" id="avfinal">
                      <input type="text" class="form-control clear_orden_i" placeholder="OI" id="avfinal_oi">
                    </div>
                  </div>

                </div>
                <!--FIN Form Row-->

              </div>
            </div>
          </div>
          <!--Fin distancia pupilar-->

        </div>
        <div class="row">
          <div class="col-sm-12">
            <div class="eight">
              <h1>ARO</h1>

              <div class="form-group row" style="margin: 4px">

                <div class="col-sm-3">
                  <label class="etiqueta"> Modelo <span style="color: red">*</span></label>
                  <div class="input-group">
                    <input type="text" class="form-control clear_orden_i oblig" id="modelo_aro_orden" placeholder="Especificar aro" required>
                    <div class="input-group-append" onClick="buscarAro()" id="buscar_aro">
                      <span class="input-group-text bg-success"><i class="fas fa-search"> </i></span>
                    </div>

                    <div style="display: none;" class="input-group-append" data-toggle="modal" data-target="#imagen_aro_orden" id="mostrar_imagen">
                      <span class="input-group-text bg-primary"><i class="fas fa-file-image"> </i></span>
                    </div>

                  </div>
                </div>

                <input type="hidden" id="id_aro">
                <div class="form-group col-sm-3">
                  <label for="" class="etiqueta">Marca </label>
                  <input type="text" class="form-control clear_orden_i oblig" id="marca_aro_orden" required>
                </div>

                <div class="form-group col-sm-3">
                  <label for="" class="etiqueta">Material <span style="color:blue"></span></label>
                  <select class="form-control clear_orden_i oblig" name="material_aro_orden" id="material_aro_orden" required>
                    <option value="" selected disabled>Seleccionar</option>
                    <option value="METAL">Metal</option>
                    <option value="ACETATO">Acetato</option>
                    <option value="METAL/ACETATO">Metal/Acetato</option>
                    <option value="FIBRA DE CARBONO">Fibra de carbono</option>
                    <option value="TITANIO">Titanio</option>
                    <option value="TR90">TR90</option>
                  </select>
                </div>

                <div class="form-group col-sm-3">
                  <label for="" class="etiqueta">Color (opcional) <span style="color:blue"></span></label>
                  <input type="text" class="form-control clear_orden_i" id="color_aro_orden">
                </div>

              </div>
            </div>
          </div>

        </div>
         <!--Fin Div Aros row-->
        <div class="form-row">
            <div class="form-group col-sm-12 col-md-6" id="observaciones_order">
              <label for="" class="etiqueta">Observaciones</label>
              <input type="text" class="form-control clear_orden_i oblig" id="observaciones_orden">
            </div>
            <div class="form-group col-sm-12 col-md-6" id="input_obser">
              <label for="obser_edicion" class="etiqueta">Observaciones (Edición)</label>
              <input type="text" class="form-control clear_orden_i" id="obser_edicion">
            </div>
        </div>

        <p id="created"></p>

        <input type="hidden" id="codigoOrden" name="codigoOrden">
        <input type="hidden" id="img_ord">
        <input type="hidden" id="validate">
       <div class="card-header bg-light" id="tableAcciones">
        <div class="d-flex justify-content-between">
        <h5 class="card-title" style="font-size: 16px">HISTORIAL DE ACCIONES</h5>
        <a onclick="get_table_acciones()" data-toggle="collapse" id="btnDisplayAcciones" href="#collapseAcciones" role="button" aria-expanded="false" aria-controls="collapseAcciones"><i class="fas fa-plus"></i></a>
        </div>
        <div class="collapse" id="collapseAcciones">
          <table width="100%" class="table-hover table-responsive-sm table-bordered mt-3" data-order='[[ 0, "desc" ]]'>
            <thead class="style_th bg-dark" style="color: white">
              <th>ID</th>
              <th>Usuario</th>
              <th>Digitación</th>
              <th>Observaciones</th>
              <th>Fecha</th>
            </thead>
            <tbody id="datatable_acciones_orden"></tbody>
          </table>
        </div>
       </div>
        
      </div>
      <!--/END MODAL BODY-->

      <?php if ($_SESSION["sucursal"] != "Valencia") : ?>
        <div class="eight" id="hist_orden" style="display:none">
          <h1>HISTORIAL</h1>
          <table width="100%" class="table-hover table-bordered display nowrap">
            <tr style="text-align: center;font-size: 12px;background: #162e41;color: white;margin-top: 5px">
              <td colspan="15" class="ord_1" style="width:10%">Fecha</td>
              <td colspan="25" class="ord_1" style="width:25%">Usuario</td>
              <td colspan="25" class="ord_1" style="width:25%">Acción</td>
              <td colspan="35" class="ord_1" style="width:35%">Observaciones</td>
            </tr>
            <tbody id="hist_orden_detalles" class="ord_2" style="text-align: center;font-size: 13px;"></tbody>
          </table>
        </div>
        <section class="input-group" id="" style="display:none">
          <div class="form-group col-sm-6">
            <select class="custom-select clear_orden_i" id="categoria_lente" aria-label="Example select with button addon">
              <option value="0" selected disabled>Seleccionar opcion...</option>
              <option value="Proceso">Proceso</option>
              <option value="Terminado">Terminado</option>
            </select>
          </div>

          <div class="form-group col-sm-6" style="display:none">
            <div class="input-group">
              <select class="custom-select clear_orden_i" id="laboratorio" aria-label="Example select with button addon">
                <option value="0" selected disabled>Enviar a...</option>
                <option value="Jenny">Lenti 1</option>
                <option value="Divel">Lenti 2</option>
                <option value="Divel">LOMED</option>
              </select>
            </div>
          </div>
        </section>
      <?php endif; ?>

      <input type="hidden" id="id_cita_ord">
      <input type="hidden" id="old_id_aro">
      
      <input type="hidden" id="user_sucursal" value="<?php echo $_SESSION["sucursal"] ?>">
      <input type="hidden" id="user_act" value="<?php echo $_SESSION["user"]; ?>">
      <input type="hidden" id="titular_id">
      <input type="hidden" id="codigo_correlativo" class="clear_orden_i">
       <input type="hidden" id="dui_paciente">
      <div class="form-group justify-content-between" style="margin: 4px; display: flex;justify-content: space-between;">

        <button type="button" class="btn btn-dark" style="margin: 5px;" id="btn_rectificar" data-toggle="modal" data-target="#rectificacionesModal" data-index-number="12314Os"><i class="fas fa-wrench"></i> &nbsp;Rectificar</button>
        
        <button type="button" class="btn pull-rigth" onClick='guardar_orden();' id="order_create_edit" style="margin: 5px;background: #073763;color: white"><i class="fas fa-save"></i> Guardar</button>
      </div>


    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
</div>