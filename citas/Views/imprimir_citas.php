<!DOCTYPE html>
<html lang="es">
<?php
require_once("../config/conexion.php");
if(isset($_SESSION["user"])){
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
  <?php require_once('../vistas/top_menu.php')?>
  <!-- Main Sidebar Container -->
  <?php require_once('side_bar.php'); ?>
  <div class="content-wrapper">   

    <div class="container">
        <div id="calendario-citas"></div>
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