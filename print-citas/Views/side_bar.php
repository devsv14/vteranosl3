<?php
 $cat_usuario = $_SESSION["categoria"];
 require_once('../modales/modal_det_rectificaciones.php');
 ?>
 <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a class="brand-link">
    </a>
     <!-- Sidebar Menu -->
      <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
       

      <li class="nav-item">
            <a href='../vistas/orden.php'class="nav-link" style="color: white">
              <i class="nav-icon fas fa-file"></i>
              <p>Ordenes</p><i class="fas fa-angle-left right"></i>
            </a>
            <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="../vistas/orden.php" class="nav-link">
                <i class="far fa-circle nav-icon text-info"></i>
                  <p>Ordenes</p>
                </a>
            </li>
            <li class="nav-item">
              <a href="../vistas/despachos_lab.php" class="nav-link">
                <i class="fas fa-shipping-fast"></i>
                  <p>Despachos lab</p>
                </a>
            </li>
            <li class="nav-item">
              <a href="../vistas/ordenes_recibidas_veteranos.php" class="nav-link">
                <i class="far fa-circle nav-icon text-success"></i>
                  <p>Recibir ordenes</p>
                </a>
              </li>
            <li class="nav-item">
              <a href="../vistas/rectificaciones.php" class="nav-link">
                <i class="far fa-circle nav-icon text-danger"></i>
                  <p>Rectificaciones</p>
                </a>
              </li>
            </ul>  
          </li>


          <?php if($cat_usuario=="Admin" or $cat_usuario=='suc' or $_SESSION["citas_callcenter"]==1){ ?>
          <li class="nav-item">
            <a href='#' class="nav-link" style="color: white">
              <i class="nav-icon fas fa-file"></i>
              <p>Citas</p><i class="fas fa-angle-left right"></i>
            </a>

          <ul class="nav nav-treeview">
          <?php if($_SESSION["citas_callcenter"]==1){ ?>
          <li class="nav-item">
              <a href="../citas/index.php" class="nav-link">
                <i class="far fa-circle nav-icon text-success"></i>
                  <p>Agendar citas</p>
                </a>
          <?php }?>
          <?php if($_SESSION["citas_sucursal"]==1){ ?>
          <li class="nav-item">
              <a href="../print-citas/index.php" class="nav-link">
                <i class="far fa-circle nav-icon text-success"></i>
                  <p>Citas Diarias</p>
                </a>
              </li>
          <?php }?>    
            <li class="nav-item">
              <a href="rectificaciones.php" class="nav-link">
                <i class="far fa-circle nav-icon text-danger"></i>
                  <p>Reporteria Citas</p>
                </a>
              </li>
          </ul>
          </li>
          <?php } ?>

        <?php if($cat_usuario==1){ ?>
          <li class="nav-item">
            <a href='../vistas/envios_ord.php' class="nav-link" style="color: white">
              <i class="nav-icon  fas fa-exchange-alt"></i>
              <p>Gestionar Lentes</p>
            </a>
          </li>

          <?php } ?>
          <?php if($cat_usuario==1 or $cat_usuario==4){ ?>
          <li class="nav-item">
            <a href='laboratorios.php' class="nav-link" style="color: white">
              <i class="nav-icon fas fa-file"></i>
              <p>Laboratorio</p>
            </a>
          </li>
          <?php } ?>
          
          
          <?php if($cat_usuario==1 or $cat_usuario==4){ ?>
          <li class="nav-item">
            <a href='stock_term.php' class="nav-link" style="color: white">
              <i class="nav-icon fas fa-file"></i>
              <p>Bodegas</p>
              <i class="fas fa-angle-left right"></i>
            </a>
              <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="stock_term.php" class="nav-link">
                  <i class="far fa-circle nav-icon text-info"></i>
                  <p>Gestionar bodegas</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="ingresos_bodega.php" class="nav-link">
                  <i class="far fa-circle nav-icon text-success"></i>
                  <p>Ingresos</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="descargos_bodega.php" class="nav-link">
                  <i class="far fa-circle nav-icon text-warning"></i>
                  <p>Inv. Bases</p>
                </a>
              </li>
            </ul>
          </li>
          <?php } ?>

        </ul>
          
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
    <input type="hidden" id="categoria-usuer-hist" value="<?php echo $_SESSION["categoria"];?>">
    <script>
    var cat_user = <?php echo json_encode($_SESSION["categoria"])?>;
  </script>
  </aside>