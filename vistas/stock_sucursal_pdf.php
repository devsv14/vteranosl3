<?php ob_start();
use Dompdf\Dompdf;
//use Dompdf\Options;

require_once '../dompdf/autoload.inc.php';
$bodega = $_POST['suc-bodega'];


require_once '../modelos/Reporteria.php';
$reporteria = new Reporteria();

$stock = $reporteria->stockSucursales($bodega);

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
	<title>.::Reporte Ingreso::.</title>
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

<table style="width: 100%;margin-top:2px">
<tr>
<td width="25%" style="width: 10%;margin:0px">
	<img src='../dist/img/inabve.jpg'  width="100" height="80" style="margin-top: 7px">
	
</td>
	
<td width="50%" style="width: 75%;margin:0px">
<table style="width:100%">
  <tr>
    <td  style="text-align: center;margin-top: 0px;font-size:15px;font-family: Helvetica, Arial, sans-serif;text-transform:uppercase"><b>STOCK <?php echo $bodega;?></b></td>
  </tr>
</table><!--fin segunda tabla-->
</td>
<td width="25%" style="width: 30%;margin:0px">
<table>
  <tr>
  <img src='../dist/img/logo_avplus.jpg' width="90" height="50"/></td>
  </tr>
</table><!--fin segunda tabla-->
</td> <!--fin segunda columna-->
</tr>
</table>

<table width="100%" style="width: 100%;margin-top: 0px i !important " >
  <tr>
    <td colspan="35" style="width: 30%;text-align: left;"><input type="text" class="input-report" value="Entregado por: "></td>
    <td colspan="30" style="width: 35%"><input type="text" class="input-report" value="Fecha : <?php echo $dateTime;?>"></td>
    <td colspan="35" style="width: 35%;text-align: left;"><input type="text" class="input-report" value="Recibido por: "></td>    
  </tr>

</table>

<table width="100%" id="pacientes" style="margin-top: 0px">
    <tr>
    <th>MODELO</th>
    <th>MARCA</th>
    <th>COLOR</th>
    <th>MATERIAL</th>
    <th>CANTIDAD</th>
  </tr>  
  <?php
  $sum = 0;
  foreach ($stock as $value) { ?>
    <tr> 
     <td><?php echo $value["modelo"]; ?></td>
     <td><?php echo $value["marca"]; ?></td>
     <td><?php echo $value["color"]; ?></td>
     <td><?php echo $value["material"]; ?></td>
     <td><?php echo $value["stock"]; ?></td>
    </tr> 

  <?php 
  $sum = $sum + $value["stock"];
} ?>  
  <tr>
    <td style="border: 1px solid white;background: white"></td>
    <td style="border: 1px solid white;background: white"></td>
    <td style="border: 1px solid white;background: white"></td>
    <td style="background: white;border-botton: 1px solid black;border-left: 1px solid black; font-size:13px; color blue">TOTAL</td>
    <td style="background: white;border-botton: 1px solid black; font-size:13px; color blue"><b><?php echo $sum?></b></td>
  </tr>
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