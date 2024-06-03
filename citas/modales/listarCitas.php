<div class="modal" id="listarCitas">
  <div class="modal-dialog" style="max-width:80%">
    <div class="modal-content">
      <div class="modal-header bg-info">
        <h5 class="modal-title w-100 text-center position-absolute">PACIENTES CITADOS</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table width="100%" class="table-hover table-bordered" id="datatable_citas_suc"  data-order='[[ 0, "desc" ]]' style="font-size: 12px">
            <thead style="text-align:center;font-size:12" class="style_th bg-dark">
                <th>Paciente</th>
                <th>DUI</th>
                <th>Tipo Paciente</th>
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