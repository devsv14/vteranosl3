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
            <a href='orden.php'class="nav-link" style="color: white">
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
              <a href="../vistas/ordenes_recibidas_veteranos.php" class="nav-link">
                <i class="far fa-circle nav-icon text-success"></i>
                  <p>Gestionar ordenes</p>
                </a>
              </li>
            <li class="nav-item">
              <a href="rectificaciones.php" class="nav-link">
                <i class="far fa-circle nav-icon text-danger"></i>
                  <p>Rectificaciones</p>
                </a>
              </li>
            </ul>  
            </li>
            
        <?php if(in_array("control_llamadas",$_SESSION['names_permisos']) OR $cat_usuario=="Admin"):?>
          <li class="nav-item">
            <a href='../vistas/entregas.php' class="nav-link" style="color: white">
              <i class="nav-icon fas fa-file"></i>
              <p>LLamadas y entregas</p>
            </a>
          </li>
          <?php endif ?>

          <?php if($cat_usuario==1 or $cat_usuario==3){ ?>
          <li class="nav-item">
            <a href='../vistas/inventarios.php' class="nav-link" style="color: white">
              <i class="nav-icon fas fa-file"></i>
              <p>Inventarios</p>
            </a>
          </li>
          <?php } ?>

          <li class="nav-item">
          <a href='index.php'class="nav-link" style="color: white">
            <i class="nav-icon fas fa-file"></i>
            <p>Citas</p><i class="fas fa-angle-left right"></i>
          </a>

          <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="index.php" class="nav-link">
              <i class="far fa-circle nav-icon text-info"></i>
                <p>Citas</p>
              </a>
          </li>
          <li class="nav-item">
            <a href="reporte_citas.php" class="nav-link">
              <i class="far fa-circle nav-icon text-success"></i>
                <p>Reporte citas</p>
              </a>
            </li>
          </ul>  
          </li>

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
    var permisos = <?php echo json_encode($_SESSION["permisos"])?>;
    console.log(permisos)
  </script>
  </aside>