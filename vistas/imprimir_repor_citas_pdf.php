<?php ob_start();
use Dompdf\Dompdf;
use Dompdf\Options;

require_once '../dompdf/autoload.inc.php';
require_once ('../config/conexion.php');
require_once ('../modelos/Citados.php');
$citas = new Citados();
date_default_timezone_set('America/El_Salvador'); 
$hoy = date("d-m-Y");
//$dateTime= date("d-m-Y H:i:s");
$data = json_decode($_POST['data']);
$data_format = [
  "fecha_desde" => $data->fecha_desde,
  "fecha_hasta" => $data->fecha_hasta,
  "estado_cita" => $data->estado_cita,
  "sucursal" => $data->sucursal
];
$data = $citas->get_reporteria_citados($data_format);

//Especificaciones en pdf de las fechas
$subtitlePDF = "FECHA: ";
if($data_format['fecha_desde'] != "" and $data_format['fecha_hasta'] == ""){
  $fechaDia = $data_format['fecha_desde'];
  $subtitlePDF .= $fechaDia; //Concat fecha + fecha bd
}else if($data_format['fecha_desde'] != "" and $data_format['fecha_hasta'] != ""){
  $fecha_rango = "DESDE ".date('d-m-Y',strtotime($data_format['fecha_desde']))." HASTA ".date('d-m-Y',strtotime($data_format['fecha_hasta']));
  $subtitlePDF .= $fecha_rango; //Concat fecha + fecha desde y fecha hasta
}else{
  $subtitlePDF = "";
}
?>

<!DOCTYPE html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=, initial-scale=1.0">
  <title>.::Citas - Veteranos::.</title>
   <link rel="stylesheet" href="../estilos/styles.css">
  <style>

  body{
    font-family: Helvetica, Arial, sans-serif;
    font-size: 12px;
  }

  html{
    margin-top: 5px;
    margin-left: 10px;
    margin-right:10px; 
    margin-bottom: 0px;
  }
 
.input-report{
    font-family: Helvetica, Arial, sans-serif;
    border: none;
    border-bottom: 2.2px dotted #C8C8C8;
    text-align: left;
    background-color: transparent;
    font-size: 13px;
    width: 100%;
    padding: 10px
  } 

  #watermark {
        position: fixed;
        top: 15%;
        margin-left: 5.2%;
        width: 100%;
        opacity: .20;    
        z-index: -1000;
  }
  </style>

</head>

<body>

<html>
<div id="watermark">
<img src="../dist/img/Logo_Gobierno.jpg" width="700" height="700"/>
</div>

<table style="width: 100%;margin-top:2px" width="100%">
<td width="25%" style="width:10%;margin:0px">
  <img src='../dist/img/inabve.jpg' width="90" height="70"/>
</td>
  
<td width="60%" style="width:75%;margin:0px">
<table style="width:100%">
  <br>
  <tr>
    <td  style="text-align: center;margin-top: 0px;font-size:18px;font-family: Helvetica, Arial, sans-serif;"><b>CITAS DE PACIENTES - INAVBE</b></td>
  </tr>
  <tr>
    <td  style="text-align:center;margin-top:0px;font-size:14px;font-family: Helvetica, Arial, sans-serif;"><b>
      <?php
        echo $subtitlePDF;
      ?>
      </b></td>
  </tr>
  <tr>
    <td  style="text-align:center;margin-top:0px;font-size:15px;font-family: Helvetica, Arial, sans-serif;text-transform: uppercase"><u><b>REPORTE DE ESTADO CITAS 
      <?php
        switch($data_format['estado_cita']){
          case 'citados':
              echo "- GENERAL";
          break;
          case 'atendidos':
             echo "- ATENDIDOS";
          break;
          case 'sin_atender':
              echo "- SIN ATENDER";
          break;
      }
      ?>  </u></b></td>
  </tr>
</table>
</td>

<td width="25%" style="width:15%;margin:0px">
  <img src='../dist/img/logo_avplus.jpg' width="60" height="35" style="margin-top:25px;"></td>
</table><!--fin tabla-->

<table width="100%" style="width: 100%;margin-top: 0px i !important " >
  <tr>
    <td colspan="25" style="width: 38%"><input type="text" class="input-report" value="Revisado por:"></td>
    <td colspan="38" style="width: 25%;text-align: left;"><input type="text" class="input-report" value="Firma: "></td>
    <td colspan="37" style="width: 37%;text-align: left;"><input type="text" class="input-report" value="Sello: "></td>    
  </tr>
</table>
<table width="100%" id="tabla_reporte_citas" data-order='[[ 1, "desc" ]]' style="margin: 3px"  class="tabla_reporte_citas">        
 <tr>
   <th colspan="5" style="width:5%">#</th>
   <th colspan="10" style="width:10%">Fecha</th>
   <th colspan="10" style="width:10%">DUI</th>   
   <th colspan="35" style="width:35%">Nombre</th>
   <th colspan="10" style="width:10%">Teléfono</th>
   <th colspan="8" style="width:8%">Sector</th> 
   <th colspan="12" style="width:12%">Sucursal</th> 
   <th colspan="10" style="width:10%">Estado</th> 
 </tr>
 <tbody class="style_th" style="font-size:11px">
 <?php
  $i=1;
  foreach ($data as $value) { ?>
    <tr> 
     <td colspan="5" style="padding:3px;width:5%;padding:10px"><?php echo $i;?></td>
     <td colspan="10" style="padding:3px;width:10%"><?php echo date('d-m-Y',strtotime($value["fecha"])); ?></td>
     <td colspan="10" style="padding:3px;width:10%"><?php echo $value["dui"]; ?></td>
     <td colspan="35" style="padding:3px;width:35%"><?php echo $value["paciente"]; ?></td>
     <td colspan="10" style="padding:3px;width:10%"><?php echo $value["telefono"]; ?></td>
     <td colspan="8" style="padding:3px;width:8%"><?php echo $value["sector"]; ?></td>
     <td colspan="12" style="padding:3px;width:12%"><?php echo $value["sucursal"]; ?></td>
     <td colspan="10" style="padding:3px;width:10%"><?php echo $value["estado"] == 1? 'Atendido' : 'Sin atender'; ?></td>
    </tr> 

  <?php $i++; } ?>  
 </tbody>
</table>
</body>
</html>

<?php
$salida_html = ob_get_contents();
ob_end_clean();
$dompdf = new Dompdf();
$dompdf->loadHtml($salida_html);
// (Optional) Setup the paper size and orientation
$dompdf->setPaper('letter', 'portrait');
//Memory
ini_set('memory_limit', '10000M');
// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream('reporte-citas-'.$hoy, array('Attachment'=>'0'));
?>