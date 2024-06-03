<div class="modal" id="listarCitasPrint">
  <div class="modal-dialog" style="max-width:70%">
    <div class="modal-content">
      <div class="modal-header bg-dark">
        <h5 class="modal-title w-100 text-center position-absolute">PACIENTES CITADOS&nbsp;&nbsp;
          <span id="suc_act"></span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>

      </div>
      <input type="hidden" id="fecha_print">
      <input type="hidden" id="sucursal_print">
      <div class="modal-body">
      <button class="btn btn-outline-info btn-xs float-rigth text-center" style="margin-left:25px;font-size:13px" onClick="imprimirCitados()"><i class="fas fa-file-pdf" style="color:red;cursor:pointer"></i> Imprimir citas</button>
      <?php if($_SESSION["categoria"]=="Admin"){?>
        <button class="btn btn-outline-success btn-xs float-rigth text-center" style="margin-left:25px;font-size:13px" onClick="imprimirCitadosAll()"><i class="fas fa-file-pdf" style="color:red;cursor:pointer"></i> Imprimir todas</button>
          <?php }?>
        <table width="100%" class="table-hover table-bordered" id="datatable_citas_print"  data-order='[[ 0, "desc" ]]' style="font-size: 12px">
            <thead style="text-align:center;font-size:12" class="style_th bg-dark">
                <th>Paciente</th>
                <th>DUI</th>
                <th>Sector</th>
                <th>Dia</th>
                <th>Sucursal</th>
                <th>Estado</th>
            </thead>
            <tbody style="text-align:center;font-size:14px"></tbody>
        </table>
      </div>
    </div>
  </div>
</div>