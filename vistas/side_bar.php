<?php
$cat_usuario = $_SESSION["categoria"];
require_once('../modales/modal_det_rectificaciones.php');
?>
<script>
  var names_permisos = <?php echo json_encode($_SESSION["names_permisos"]) ?>;
</script>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a class="brand-link">
  </a>
  <!-- Sidebar Menu -->
  <nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

      <li class="nav-item">
        <a href='' class="nav-link" style="color: white">
          <i class="nav-icon fas fa-file"></i>
          <p>Ordenes</p><i class="fas fa-angle-left right"></i>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="orden.php" class="nav-link">
              <i class="far fa-circle nav-icon text-info"></i>
              <p>Ordenes</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="despachos_lab.php" class="nav-link">
              <i class="fas fa-shipping-fast"></i>
              <p>Despachos lab</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="ordenes_recibidas_veteranos.php" class="nav-link">
              <i class="far 	fas fa-clipboard-check"></i>
              <p>Recibir ordenes</p>
            </a>
          </li>

          <!-- <li class="nav-item">
            <a href="rectificaciones.php" class="nav-link">
              <i class="fas fa-tools nav-icon text-warning"></i>
              <p>Rectificaciones</p>
            </a>
          </li> -->

        </ul>
      </li>
      <?php if ($cat_usuario == "Admin") { ?>
        <li class="nav-item">
          <a href='inventarios.php' class="nav-link" style="color: white">
            <i class="nav-icon fas fa-file"></i>
            <p>Inventarios</p>
          </a>
        </li>
      <?php } ?>

      <li class="nav-item">
        <a href='#' class="nav-link" style="color: white">
          <i class="nav-icon fas fa-file"></i>
          <p>Citas</p><i class="fas fa-angle-left right"></i>
        </a>

        <ul class="nav nav-treeview">
        <?php if(in_array('importar_csv',$_SESSION['names_permisos']) || $cat_usuario=="Admin"){?>
          <li class="nav-item">
            <a href="./import_csv_citas.php" class="nav-link">
              <i class="far fa-circle nav-icon text-success"></i>
              <p>Importar csv</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="./import_csv_citas.php" class="nav-link">
              <i class="far fa-circle nav-icon text-success"></i>
              <p>Citas</p>
            </a>
          </li>
        <?php } ?>
        </ul>
    </ul>
  </nav>
  <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
  <input type="hidden" id="categoria-usuer-hist" value="<?php echo $_SESSION["categoria"]; ?>">
</aside>