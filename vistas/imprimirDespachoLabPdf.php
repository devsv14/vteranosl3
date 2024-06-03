<?php ob_start();
use Dompdf\Dompdf;
//use Dompdf\Options;

require_once '../dompdf/autoload.inc.php';
$correlativo = $_POST['correlativos_acc'];

require_once '../modelos/Reporteria.php';
$reporteria = new Reporteria();

$data = $reporteria->getItemsReporteOrdenes($correlativo);
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

<table style="width: 100%;margin-top:2px">
<tr>
<td width="25%" style="width: 10%;margin:0px">
	<img src='../dist/img/inabve.jpg'  width="100" height="80"/ style="margin-top: 7px">
	<img src='../dist/img/lenti_logo.jpg' width="80" height="60"/></td>
</td>
	
<td width="50%" style="width: 75%;margin:0px">
<table style="width:100%">
  <tr>
    <td  style="text-align: center;margin-top: 0px;font-size:15px;font-family: Helvetica, Arial, sans-serif;"><b>ORDEN DE ENVIOS</b></td>
  </tr>
</table><!--fin segunda tabla-->
</td>
<td width="25%" style="width: 30%;margin:0px">
<table>
  <tr>
    <td style="text-align:right; font-size:12px;color: #008C45"><strong>ORDEN</strong></td>
  </tr>
  <tr>
    <td style="color:red;text-align:right; font-size:12px;color: #CD212A"><strong >No.&nbsp;<span><?php echo $correlativo; ?></strong></td>
  </tr>
</table><!--fin segunda tabla-->
</td> <!--fin segunda columna-->
</tr>
</table>

<table width="100%" style="width: 100%;margin-top: 0px i !important " >
  <tr>
    <td colspan="25" style="width: 25%"><input type="text" class="input-report" value="Fecha envio: <?php echo $hoy;?>"></td>
    <td colspan="38" style="width: 38%;text-align: left;"><input type="text" class="input-report" value="Enviado por: "></td>
    <td colspan="37" style="width: 37%;text-align: left;"><input type="text" class="input-report" value="Mensajero: "></td>    
  </tr>
  <tr>
    <td colspan="25" style="width: 25%"><input type="text" class="input-report" value="Cant. ordenes: <?php echo count($data);?>"></td>  
    <td colspan="38" style="width: 37%;text-align: left;"><input type="text" class="input-report" value="Firma-Sello: "></td>
    <td colspan="37" style="width: 38%;text-align: left;"><input type="text" class="input-report" value="Recibido por: "></td>    
  </tr>
</table>
 <b><h5 style="font-size:12px;font-family: Helvetica, Arial, sans-serif;text-align: center;margin-bottom: 0px"> DETALLE DE ENVÍO</h5></b>
	<table width="100%" id="pacientes" style="margin-top: 0px">
    <tr>
    <th>#</th>
    <th>Cod. orden</th>
    <th>Fecha orden</th>
    <th>Paciente</th>
    <th>Dui</th>
    <th>Télefono</th>
    <th>Tipo Lente</th>
  </tr>  
  <?php
  $i=1;
  foreach ($data as $value) { ?>
    <tr> 
     <td><?php echo $i; ?></td>
     <td><?php echo $value["codigo"]; ?></td>
     <td><?php echo date("d-m-Y",strtotime($value["fecha_o"])); ?></td>
     <td><?php echo $value["paciente"]; ?></td>
     <td><?php echo $value["dui"]; ?></td>
     <td><?php echo $value["telefono"]; ?></td>
     <td><?php echo $value["tipo_lente"]; ?></td>
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