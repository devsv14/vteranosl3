<!DOCTYPE html>
<html lang="es">
<?php
require_once("../config/conexion.php");
if(isset($_SESSION["user"])){
require_once("../vistas/links_plugin.php");
require_once("modales/listarCitasPrint.php");
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Citas</title>    
    <link rel="stylesheet" href="<?php echo base_url; ?>Assets/css/main.min.css">
</head>

<body>

<div class="wrapper">
<!-- top-bar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light" style="background-color:#343a40;color: white">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button" style="color:white"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
    <h5>IMPRIMIR CITAS DIARIAS</h5>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

      <li class="nav-item">
        <a class="nav-link" data-slide="true" href="../vistas/logout.php" role="button" >
          <i class="fas fa-sign-out-alt" style="background-color: yelllow"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- Main Sidebar Container -->
  <?php require_once('side_bar.php'); ?>
  <div class="content-wrapper">
   <input type="hidden" value="<?php echo $_SESSION["sucursal"];?>" id="sucs">
   
    <div class="container">
        <div id="calendario-citas"></div>
    </div>


        <!--MODAL GESTION CITAS -->

<div class="modal" id="gestion-citas">
  <div class="modal-dialog" style="max-width:55%">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header bg-primary" style="padding:10px;">
        <h4 class="modal-title  w-100 text-center position-absolute" style="font-size:16px">GESTIONAR CITAS</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <table class="table-bordered table-hover" width="100%" id="data-gest-citas" style="font-family: Helvetica, Arial, sans-serif;font-size: 12px;text-align: center">

        <thead style="color:white;" class='bg-dark'>
            <tr>
                <th style="width:40%">Paciente</th>
                <th style="width:20%">DUI</th>
                <th style="width:20%">Sector</th>
                <th style="width:10%">Editar</th>
                <th style="width:10%">Eliminar</th>
            </tr>
        </thead>
        </table>
      </div>


    </div>
  </div>
</div>
 
</div>
</div>
<?php 
require_once("../vistas/links_js.php");
?>
  
    <script src="<?php echo base_url; ?>Assets/js/main.min.js"></script>
    <script src="<?php echo base_url; ?>Assets/js/es.js"></script>
    <script>
        const base_url = '<?php echo base_url; ?>';
    </script>
   
    <script src="<?php echo base_url; ?>Assets/js/app.js"></script>
    <script src='../js/cleave.js'></script>
    <script src='../js/citados.js'></script>
    <script>
        let telefono = new Cleave('#telefono-pac', {
        delimiter: '-',
        blocks: [4,4],
        uppercase: true
        });
    
        let dui = new Cleave('#dui-vet', {
        delimiter: '-',
        blocks: [8,1],
        uppercase: true
        });
    </script>

</body>

</html>

<?php } else{
echo "Acceso denegado";
  } ?>