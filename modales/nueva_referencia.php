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


                    <hr>
                    <div class="col-sm-12 rx-final divs-sections shadow p-2 mb-3 bg-white rounded">
                        <table width="100%" style='max-width:100%;margin:5px'>
                            <h5 style="color:blue;text-align:center"><strong>RX Final</strong></h5>

                            <thead class="thead-light" style="background: black;color: white;font-family: Helvetica, Arial, sans-serif;font-size: 11px;text-align: center">
                                <tr>
                                    <th style="text-align:center">OJO</th>
                                    <th style="text-align:center">ESFERAS</th>
                                    <th style="text-align:center">CILIDROS</th>
                                    <th style="text-align:center">EJE</th>
                                    <th style="text-align:center">PRISMA</th>
                                    <th style="text-align:center">ADICION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>OD</td>
                                    <td><input type="text" class="input-mat rx_final_val odrxfinal_val cls-input" name="odesferasf" id="odesferasf" autocomplete="off"></td>
                                    <td> <input type="text" class="input-mat rx_final_val odrxfinal_val cls-input" name="odcilindrosf" id="odcilindrosf" autocomplete="off"></td>
                                    <td> <input type="text" class="input-mat cls-input" name="odejesf" id="odejesf" autocomplete="off"></td>
                                    <td> <input type="text" class="input-mat cls-input" name="dprismaf" id="dprismaf" autocomplete="off"></td>
                                    <td> <input type="text" class="input-mat rx_final_val odrxfinal_val cls-input" name="oddicionf" id="oddicionf" onKeyup="fill_rx()" autocomplete="off">
                                    </td>
                                </tr>
                                <tr>
                                    <td>OI</td>
                                    <td> <input type="text" class="input-mat rx_final_val oirxfinal_val cls-input" id="oiesferasf" name="oiesferasf" autocomplete="off"></td>
                                    <td> <input type="text" class="input-mat rx_final_val oirxfinal_val cls-input" id="oicolindrosf" name="oicolindrosf" autocomplete="off"></td>
                                    <td> <input type="text" class="input-mat cls-input" name="oiejesf" id="oiejesf" autocomplete="off"></td>
                                    <td> <input type="text" class="input-mat cls-input" id="oiprismaf" name="oiprismaf" autocomplete="off"></td>
                                    <td> <input type="text" class="input-mat rx_final_val oirxfinal_val cls-input" id="oiadicionf" name="oiadicionf" autocomplete="off"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div><!-- Fin rx Final -->

                </div>

                <div class="row shadow p-2 mb-3 bg-white rounded">

                    <div class="col-md-6 col-lg-3">
                        <div class="card card-secondary" style="height: 105px;">
                            <div class="card-header bg-secondary" style='padding:3px; text-align:center'>
                                <h3 class="card-title">Usuario lente</h3>
                            </div>
                            <div class="card-body p-3">
                                <div class="icheck-success d-inline">
                                    <input type="radio" name="r3" checked="" id="radioSuccess1">
                                    <label for="radioSuccess1">Si</label>
                                </div>

                                <div class="icheck-success d-inline">
                                    <input type="radio" name="r3" checked="" id="radioSuccess2">
                                    <label for="radioSuccess1">No</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card card-secondary">
                            <div class="card-header bg-secondary" style='padding:3px; text-align:center !important'>
                                <h3 class="card-title text-center" style="text-align:center">Distancia pupilar</h3>
                            </div>
                            <div class="card-body p-3 form-row row">
                                <div class="content-input input-group col-sm-6">
                                    <input type="text" class="custom-input clear-input material form-control" id='dui_pac' name='paciente-nombres'>
                                    <label class="input-label" for="">OI</label>
                                </div>

                                <div class="content-input input-group col-sm-6">
                                    <input type="text" class="custom-input clear-input material form-control" id='dui_pac' name='paciente-nombres'>
                                    <label class="input-label" for="">OD</label>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="col-md-6 col-lg-3">
                        <div class="card card-secondary">
                            <div class="card-header bg-secondary" style='padding:3px'>
                                <h3 class="card-title" style="text-align:center">Altura de lente</h3>
                            </div>
                            <div class="card-body p-3 form-row row">
                                <div class="content-input input-group col-sm-6">
                                    <input type="text" class="custom-input clear-input material form-control" id='dui_pac' name='paciente-nombres'>
                                    <label class="input-label" for="">OI</label>
                                </div>

                                <div class="content-input input-group col-sm-6">
                                    <input type="text" class="custom-input clear-input material form-control" id='dui_pac' name='paciente-nombres'>
                                    <label class="input-label" for="">OD</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <div class="card card-secondary">
                            <div class="card-header bg-secondary" style='padding:3px'>
                                <h3 class="card-title" style="text-align:center">Agudeza visual</h3>
                            </div>
                            <div class="card-body p-3 form-row row">
                                <div class="content-input input-group col-sm-6">
                                    <input type="text" class="custom-input clear-input material form-control" id='dui_pac' name='paciente-nombres'>
                                    <label class="input-label" for="">OI</label>
                                </div>

                                <div class="content-input input-group col-sm-6">
                                    <input type="text" class="custom-input clear-input material form-control" id='dui_pac' name='paciente-nombres'>
                                    <label class="input-label" for="">OD</label>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row shadow p-2 mb-3 bg-white rounded">

                    <div class="col-sm-4 mb-2">
                        <div class="content-input input-group">
                            <input type="text" class="custom-input clear-input material form-control" id='dui_pac' name='paciente-nombres'>
                            <label class="input-label" for="">Patologías</label>
                        </div>
                    </div>

                    <div class="col-sm-8 mb-2">
                        <div class="content-input input-group">
                            <input type="text" class="custom-input clear-input material form-control" id='paciente' name='paciente-nombres'>
                            <label class="input-label" for="">Lentes y tratamientos*</label>
                            <button class="btn-add-input btn-primary" type="button" id="btn-consultas">
                                <i class="fas fa-search" style="color:white"></i>
                            </button>
                        </div>
                    </div>

                    <div class="col-sm-6 mb-2">
                        <label for="">Modelo aro</label>
                        <select class="form-control">
                            <option value="0" selected>Modelo Aro</option>
                            <option value="">11222222</option>
                            <option value="">252325585</option>
                            <option value="">2453585888</option>
                        </select>
                    </div>

                    <div class="col-sm-6 mb-2">
                        <label for="">Material aro</label>
                        <select class="form-control">
                            <option value="0" selected>Modelo Aro</option>
                            <option value="">11222222</option>
                            <option value="">252325585</option>
                            <option value="">2453585888</option>
                        </select>
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