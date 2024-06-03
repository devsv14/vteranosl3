<div class="modal" id="modal_estadisticas" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true"> 
  <div class="modal-dialog" style="max-width: 50%">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header" style="background: #162e41;color: white">
        <h4 class="modal-title" style="font-size: 14px;"><b><span id="n_ing_tallado">RESULTADOS Y ESTADISTICAS</span></b></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">

      <div class="card-body" style="margin: 1px solid red;color: black !important">

<a href="envios_ord.php" class="btn btn-app" style="color: black;border: solid #5bc0de 1px;">
  <span class="badge bg-warning" id="alert_creadas_ord"></span>
  <i class="fas fa-history" style="color: #f0ad4e"></i> PENDIENTES (0)
</a>

<a href="ordenes_enviadas.php" class="btn btn-app" style="color: black;border: solid #5bc0de 1px;">
  <span class="badge bg-dark" id="alert_enviadas_ord"></span>
  <i class="fas fa-file-medical" style="color: #5bc0de"></i> CITADOS (0)
</a>

<a href="ordenes_enviadas_lab.php" class="btn btn-app" style="color: black;border: solid #5bc0de 1px;">
  <span class="badge bg-dark" id="alert_enviadas_ord"></span>
  <i class="fas fa-user-md" style="color: green"></i> ATENDIDOS (0)
</a>

<a href="ordenes_desp" class="btn btn-app" style="color: black;border: solid #5bc0de 1px;">
  <span class="badge bg-primary" id="alert_enviadas_ord"></span>
  <i class="fas fa-glasses" style="color: #0275d8"></i> ENTREGADOS (0)
</a>


</div>



        <div class="row">
          <div class="col-sm-4" style="text-align: right;display: flex;align-items: right">

           <input type="date" class="form-control clear_orden_i" id="desde_estadistica" placeholder="desde">
          </div>
          <div class="col-sm-4" style="text-align: right;display: flex;align-items: right">

            <input type="date" class="form-control clear_orden_i" id="hasta_estadistica" placeholder="desde">
         </div>

         <div class="col-sm-2" style="text-align: right;display: flex;align-items: right">
           <button class="btn btn-light"><i class="fas fa-search" style="color: green;cursor:pointer;margin-top: 4px" onClick="listar_estadisticas()"></i></button>
         </div>

        </div>
      <div class="card card-dark card-outline" style="margin: 2px;">
       <table width="100%" class="table-hover table-bordered" id="datatable_estadisticas"  data-order='[[ 0, "desc" ]]'>        
         <thead class="style_th bg-dark" style="color: white">
           <th>Sucursal</th>
           <th>Cantidad</th>
         </thead>
         <tbody class="style_th"></tbody>
       </table>
      </div>

      </div><!--Modal body..-->

    </div>
  </div>
</div>