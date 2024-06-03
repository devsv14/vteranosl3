      <div class="card-body" style="margin: 1px solid red;color: black !important">

        <a href="envios_ord.php" class="btn btn-app" style="color: black;border: solid #5bc0de 1px;">
          <span class="badge bg-warning" id="alert_creadas_ord"></span>
          <i class="fas fa-history" style="color: #f0ad4e"></i> PENDIENTES
        </a>

        <a href="ordenes_enviadas.php" class="btn btn-app" style="color: black;border: solid #5bc0de 1px;">
          <span class="badge bg-dark" id="alert_enviadas_ord"></span>
          <i class="fas fa-boxes" style="color: #5bc0de"></i> CLASIFICADOS
        </a>

        <a href="ordenes_enviadas_lab.php" class="btn btn-app" style="color: black;border: solid #5bc0de 1px;">
          <span class="badge bg-dark" id="alert_enviadas_ord"></span>
          <i class="fas fa-file-export" style="color: green"></i> ENVIADOS
        </a>

        <a href="ordenes_desp" class="btn btn-app" style="color: black;border: solid #5bc0de 1px;">
          <span class="badge bg-primary" id="alert_enviadas_ord"></span>
          <i class="fas fa-shipping-fast" style="color: #0275d8"></i> DESPACHADO
        </a>

        <a class="btn btn-app" onClick="listado_ordenes_recibidas();" style="color: black;border: solid #5bc0de 1px;">
          <span class="badge bg-success" id="alert_recibidos_ord"></span>
          <i class="fas fa-file-import" style="color: #5cb85c"></i> RECIBIDOS
        </a>
      </div>