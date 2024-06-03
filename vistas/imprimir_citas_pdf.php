
<?php ob_start();
use Dompdf\Dompdf;
use Dompdf\Options;

require_once '../dompdf/autoload.inc.php';
require_once ('../config/conexion.php');
require_once ('../modelos/Reporteria.php');
date_default_timezone_set('America/El_Salvador'); 
//$hoy = date("d-m-Y");
//$dateTime= date("d-m-Y H:i:s");

$citas = new Reporteria();
$fecha=$_POST["fecha-cita"];
$sucursal = $_POST["sucursal"];
$fecha_cita = date("d-m-Y", strtotime($fecha));
$data = $citas->get_pacientes_citados($_POST["fecha-cita"],$sucursal);
//var_dump($data);
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
    <td  style="text-align:center;margin-top:0px;font-size:14px;font-family: Helvetica, Arial, sans-serif;"><b>FECHA: <?php echo date("d-m-Y",strtotime($fecha_cita)); ?></b></td>
  </tr>
  <tr>
    <td  style="text-align:center;margin-top:0px;font-size:15px;font-family: Helvetica, Arial, sans-serif;text-transform: uppercase"><u><b>SUCURSAL: <?php echo $sucursal?> </u></b></td>
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
   <th colspan="10" style="width:10%">Hora</th> 
   <th colspan="10" style="width:10%">DUI</th>
   <th colspan="10" style="width:10%">Teléfono</th>   
   <th colspan="35" style="width:35%">Nombre</th>
   <th colspan="15" style="width:15%">Firma</th>
   <th colspan="15" style="width:15%">Observaciones</th>
 </tr>
 <tbody class="style_th" style="font-size:11px">
 <?php
  $i=1;
  foreach ($data as $value) { ?>
    <tr> 
     <td colspan="5" style="padding:3px;width:5%;padding:10px"><?php echo $i;?></td>
     <td colspan="10" style="padding:3px;width:10%"><?php echo $value["hora"]; ?></td>
     <td colspan="10" style="padding:3px;width:10%"><?php echo $value["dui"]; ?></td>
     <td colspan="10" style="padding:3px;width:10%"><?php echo $value["telefono"]; ?></td>
     <td colspan="35" style="padding:3px;width:35%"><?php echo $value["paciente"]; ?></td>
     <td colspan="15" style="padding:3px;width:15%"></td>
     <td colspan="15" style="padding:3px;width:15%"></td>
    </tr> 

  <?php $i++; } ?>  
 </tbody>
</table>

<div style="page-break-after:always;"></div>

<table style="width: 100%;margin-top:2px" width="100%">
<td width="25%" style="width:10%;margin:0px">
  <img src='../dist/img/inabve.jpg' width="90" height="70"/>
</td>
  
<td width="60%" style="width:60%;margin:0px">
<table style="width:100%">
  <br>
  <tr>
    <td  style="text-align: center;margin-top: 0px;font-size:18px;font-family: Helvetica, Arial, sans-serif;"><b>CITAS DE PACIENTES - INAVBE (AGREGADAS)</b></td>
  </tr>
  <tr>
    <td  style="text-align:center;margin-top:0px;font-size:14px;font-family: Helvetica, Arial, sans-serif;"><b>FECHA: <?php echo date("d-m-Y",strtotime($fecha_cita)); ?></b></td>
  </tr>
  <tr>
    <td  style="text-align:center;margin-top:0px;font-size:15px;font-family: Helvetica, Arial, sans-serif;text-transform: uppercase"><u><b>SUCURSAL: <?php echo $sucursal?> </u></b></td>
  </tr>
</table>
</td>

</table>
<table width="100%" i data-order='[[ 0, "desc" ]]' style="margin: 3px"  class="tabla_reporte_citas">        
 <tr>
  <th colspan="1" style="width:1%"></th>
   <th colspan="9" style="width:9%">Hora</th> 
   <th colspan="15" style="width:15%">DUI</th>
   <th colspan="10" style="width:10%">Teléfono</th>   
   <th colspan="30" style="width:30%">Nombre</th>
   <th colspan="15" style="width:15%">Firma</th>
   <th colspan="20" style="width:20%">Observaciones</th>
 </tr>
 <tbody class="style_th" style="font-size:11px">
 <?php
  //$j=1;
  for ($j=1;$j<=25;$j++) { ?> 
    <tr> 
    <td colspan="1" style="padding:9px;width:1%;font-size:9px;color:#696969"><?php echo $i;?></td>
     <td colspan="9" style="padding:0px;width:9%;text-align:left;font-size:9px;color:#696969"></td>
     <td colspan="15" style="padding:9px;width:15%"></td>
     <td colspan="10" style="padding:3px;width:10%"></td>
     <td colspan="30" style="padding:3px;width:30%"></td>
     <td colspan="15" style="padding:3px;width:15%"></td>
     <td colspan="20" style="padding:10px;width:20%;color:white">I</td>
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
// (Optional) Setup the paper size and orientation
$dompdf->setPaper('letter', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream('document', array('Attachment'=>'0'));
?>