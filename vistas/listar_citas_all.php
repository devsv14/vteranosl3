
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
$fecha_cita = $_POST["fecha-cita"];

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
        float: center;

  }
  hr {
  page-break-after: always;
  border: 0;
  margin: 0;
  padding: 0;
}
  </style>

</head>

<html>
<body>
<div id="watermark">
<img src="../dist/img/Logo_Gobierno.jpg" width="600" height="700" >
</div>

<?php
$data_citas_hoy = $citas->getCitasDiariasResumen($fecha_cita);

?>

<table style="width: 100%;margin-top:2px" width="100%">
<td width="25%" style="width:10%;margin:0px">
  <img src='../dist/img/inabve.jpg' width="90" height="70"/>
</td>
  
<td width="60%" style="width:60%;margin:0px">
<table style="width:100%">
  <br>
  <tr>
    <td  style="text-align: center;margin-top: 0px;font-size:18px;font-family: Helvetica, Arial, sans-serif;"><b> RESUMEN CITAS DIARIAS  INAVBE-OPTICAS AV PLUS</b></td>
  </tr>
  <tr>
    <td  style="text-align:center;margin-top:0px;font-size:14px;font-family: Helvetica, Arial, sans-serif;"><b>FECHA: <?php echo date("d-m-Y",strtotime($fecha_cita)); ?></b></td>
  </tr>
</table>
</td>

<td width="25%" style="width:15%;margin:0px">
  <img src='../dist/img/logo_avplus.jpg' width="60" height="35" style="margin-top:25px;"></td>
</table><!--fin tabla-->
<table width="100%" class="tabla_reporte_citas">
<tr>
   <th colspan="5" style="width:5%">#</th>
   <th colspan="30" style="width:30%">Sucursal</th>
   <th colspan="20" style="width:20%">Cantidad</th>
   <th colspan="45" style="width:45%">Observaciones</th>
 </tr>
 <?php
 $h=1;
 $totales = 0;
 foreach($data_citas_hoy as $r){?>
  <tr>
  <td colspan="5" style="width:5%"><?php echo $h;?></td>
  <td colspan="30" style="width:30%"><?php echo $r["sucursal"];?></td>
  <td colspan="20" style="width:20%"><?php echo $r["totales"];?></td>
  <td colspan="45" style="width:45%"></td>

 </tr>
  <?php
$h++;
$totales=$totales+$r["totales"];
}?>
  <tr>
  <td colspan="5" style="width:5%"></td>
  <td colspan="30" style="width:30%;background:white;font-size:13px"><b>TOTAL CITADOS</b></td>
  <td colspan="20" style="width:20%;color:blue;background:white;font-size:13px"><b><?php echo $totales;?></b></td>
  <td colspan="45" style="width:45%"></td>

 </tr>
</table>
<div style="page-break-after:always;"></div>
<?php 

$j=1;
$tam_array = count($sucursales_array);
$resumen = array();
$cont = 0;
for ($i = 0 ;$i < $tam_array; $i++) {
  $sucursal = $sucursales_array[$i];
  $data = $citas->get_pacientes_citados($fecha_cita,$sucursal);
  $tam_data = count($data);
  if($tam_data>0){

  ?>
<table style="width: 100%;margin-top:2px" width="100%">
<td width="15%" style="width:15%;margin:0px">
  <img src='../dist/img/inabve.jpg' width="120" height="90"/>
</td>
  
<td width="70%" style="width:70%;margin:0px">
<table style="width:100%">
  <br>
  <tr>
    <td  style="text-align: center;margin-top: 0px;font-size:18px;font-family: Helvetica, Arial, sans-serif;"><b>CITAS DIARIAS  INAVBE-OPTICAS AV PLUS</b></td>
  </tr>
  <tr>
    <td  style="text-align:center;margin-top:0px;font-size:14px;font-family: Helvetica, Arial, sans-serif;"><b>FECHA: <?php echo date("d-m-Y",strtotime($fecha_cita)); ?></b></td>
  </tr>
  <tr style="text-align:center;margin-top:0px;font-size:15px;font-family: Helvetica, Arial, sans-serif;text-transform: uppercase"><td><u  style="text-align:center;">  <b>SUCURSAL: <?php echo $sucursal?> </u></b></td>
  </tr>
</table>
</td>

<td style="width:15%;">
  <img src='../dist/img/logo_avplus.jpg' width="85" height="55" style="margin-top:20px;">
</td>
</table><!--fin tabla-->


  <table width="100%" id="tabla_reporte_citas" data-order='[[ 0, "desc" ]]' style="margin-top: 23px" class="tabla_reporte_citas">
        
 <tr>
   <th colspan="5" style="width:5%">#</th>
   <th colspan="10" style="width:10%">DUI</th>
   <th colspan="10" style="width:10%">Teléfono</th>   
   <th colspan="40" style="width:40%">Nombre</th>
   <th colspan="20" style="width:20%">Estado</th>
   <th colspan="15" style="width:15%">Observaciones</th>
 </tr>
 <?php
 
 foreach($data as $key){
  if($key["estado"]=="0"){
    $estado = "Sin evaluar";
  }elseif($key["estado"]=="1"){
    $estado = "Paciente atendido";
  }
  ?>
 <tr>
  <td colspan="5" style="width:5%"><?php echo $j;?></td>
  <td colspan="10" style="width:10%"><?php echo $key["dui"];?></td>
  <td colspan="10" style="width:10%"></td>
  <td colspan="40" style="width:40%"><?php echo $key["paciente"];?></td>
  <td colspan="20" style="width:20%"><?php echo $estado;?></td>
  <td colspan="15" style="width:15%"></td>
 </tr>
 <?php
 if($j<count($data)){
  $j++;
 }else{
  $j=1;
 }
}?>

</table>
<?php if($j != $tam_array){?>
<div style="page-break-after:always;"></div>
<?php } ?>
<?php
//array_push($resumen,array("cantidad"=>count($data),"sucursal"=>$sucursal));
}

}

?>




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
  

</body>
</html>

