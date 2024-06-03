<div class="modal" id="listActasAmpos" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog" style="max-width:80%">
        <div class="modal-content">
            <div class="modal-header py-1 bg-info">
                <h5 class="modal-title text-center">ORDENAR ACTAS EN AMPOS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <button class="btn btn-outline-light btn-sm float-left" style="text-align:left; display:flex"  data-toggle="tooltip"><i class="fas fa-trash" style='color:red' onclick="deleteOrdenesUp()"></i></button>
                    <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                    <input type="text" id="id_dui_scan" onchange="trasladarOrdenes()" class="form-control mb-3 col-sm-12 col-md-2 col-lg-2 col-xl-2" style="height: 32px;" placeholder="ID ACTA O DUI">
                    <select name="" class="form-control col-sm-12 col-md-4 mb-3" id="sucursal_expedientes">
                    <option value="">Selecc...Sucursal</option>
                        <!---<option value="Valencia">Valencia</option>
                        <option value="Metrocentro">Metrocentro</option>
                        <option value="San Miguel AV PLUS">San Miguel AV PLUS</option>
                        <!--<option value="Cascadas">Cascadas</option>
                        <option value="Santa Ana">Santa Ana</option>
                        <option value="Chalatenango">Chalatenango</option>
                        <option value="Ahuachapan">Ahuachapan</option>
                        <option value="Sonsonate">Sonsonate</option>
                        <option value="Ciudad Arce">Ciudad Arce</option>                                   
                        <option value="Opico">Opico</option>
                        <option value="San Vicente Centro">San Vicente Centro</option>
                        <option value="San Vicente">San Vicente</option>
                        <option value="Gotera">Gotera</option>
                        <option value="San Miguel">San Miguel</option>
                        <option value="Usulutan">Usulutan</option>---->
                    </select>
                    <!--<button class="btn btn-outline-primary btn-sm" onclick="printOrdenes()" data-toggle="tooltip" data-placement="bottom" title="Imprimir vinetas"><span id="count-print"></span> <i class="fas fa-folder"></i>Trasladar</button>-->
                    </div>
                    <table width="100%" class="table-hover table-bordered" id="dt_ordenes_enviadas" data-order='[[ 0, "desc" ]]' style="font-size: 12px">
                        <thead style="text-align:center;font-size:12" class="style_th bg-dark">
                            <tr>
                                <th>ID Acta</th>
                                <th>Paciente</th>
                                <th>AMPO</th>
                                <th>DUI</th>
                                <th>Fecha orden</th>
                                <th>Sucursal</th>
                                <th>Elim.</th>
                            </tr>
                        </thead>
                        <tbody style="text-align:center;font-size:14px" id="actas-ampos"></tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>