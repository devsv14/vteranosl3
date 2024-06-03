<?php ob_start();
use Dompdf\Dompdf;
//use Dompdf\Options;

require_once '../dompdf/autoload.inc.php';

require_once '../modelos/Reporteria.php';
$reporteria = new Reporteria();

$data = $reporteria->getItemsSelect();
date_default_timezone_set('America/El_Salvador'); 
$hoy = date("d-m-Y");
$dateTime= date("d-m-Y H:i:s");

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <link rel="stylesheet" href="../estilos/styles.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>.::Reportes::.</title>
	<style>
	body{
      font-family: Helvetica, Arial, sans-serif;
      font-size: 12px;
    }
    html{
	    margin-top: 5px;
	    margin-left: 20px;
	    margin-right:20px; 
	    margin-bottom: 0px;
    }
    #pacientes {
      font-family: Arial, Helvetica, sans-serif;
      border-collapse: collapse;
      width: 100%;
      font-size: 11px;
      text-align:center;
      text-transform: uppercase;
    }

    #pacientes td, #pacientes th {
      border: 1px solid #ddd;
      padding: 3px;
    }

    #pacientes tr:nth-child(even){background-color: #f2f2f2;}

    #pacientes tr:hover {background-color: #ddd;}

    #pacientes th {
      padding-top: 3px;
      padding-bottom: 3px;
      text-align: center;
      background-color: #4c5f70;
      color: white;
    }
	</style>
</head>
<body>




 <b><h5 style="font-size:12px;font-family: Helvetica, Arial, sans-serif;text-align: center;margin-bottom: 0px"> DETALLE DE ENVÍO</h5></b>
	<table width="100%" id="pacientes" style="margin-top: 0px">
    <tr>
    <th>marca_aro</th>
    <th>modelo_aro</th>
    <th>horizontal_aro</th>
    <th>vertical_aro</th>
    <th>puente_aro</th>
    <th>Cant.</th>
    <th>IMg</th>
  </tr>  
  <?php

  foreach ($data as $value) { ?>
    <tr>     
     <td><?php echo $value["marca_aro"]; ?></td>
     <td><?php echo $value["modelo_aro"]; ?></td>
     <td><?php echo $value["horizontal_aro"]; ?></td>
     <td><?php echo $value["vertical_aro"]; ?></td>
     <td><?php echo $value["puente_aro"]; ?></td>
     <td><?php echo $value["cant"]; ?></td>
     <td><img src='../dist/img/<?php echo $value["img"]?>'  width="100" height="80"/ style="margin-top: 7px"></td>
    </tr> 

  <?php $i++; } ?>  
  </table>
</body>
</html>

<?php

$salida_html = ob_get_contents();

ob_end_clean();
$dompdf = new Dompdf();
$dompdf->loadHtml($salida_html);
$dompdf->setPaper('letter', 'portrait');
$dompdf->render();
$dompdf->stream('document', array('Attachment'=>'0'));
?>