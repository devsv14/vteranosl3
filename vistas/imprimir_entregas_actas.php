
<?php ob_start();
use Dompdf\Dompdf;
use Dompdf\Options;

require_once '../dompdf/autoload.inc.php';
require_once ('../config/conexion.php');
require_once ('../modelos/Reporteria.php');
require_once ('../modelos/Ordenes.php');
date_default_timezone_set('America/El_Salvador'); 
$hoy = date("d-m-Y");
$dateTime= date("d-m-Y H:i:s");
$hora = date("H:i");
$reportes = new Reporteria();

$data = json_decode($_POST['actData']);
$cod_entrega = $data->codigo_entrega;
$resultData = $reportes->get_control_actas_all($data->codigo_entrega);
$data_receptor = $reportes->get_receptor_actas($data->codigo_entrega);
$receptor = $data_receptor[0]['fullname_receptor'];
$emisor = $data_receptor[0]['fullname_emisor'];
//Variables para mostrar datos
$sucursal = $resultData[0]['sucursal'];

//Data sucursales
$data_sucursal = $reportes->getDataSucursal($sucursal);
$depto = $data_sucursal[0]['departamento'];
$sucursalReporte = $data_sucursal[0]['nombre'];
$suc = explode('-',$sucursalReporte);
$suc = $suc[0];
//Información
$msj_main = strtoupper($depto.",".$suc)." a las $hora horas del dia $hoy se hace constar la entrega oficial de actas a: ".strtoupper("<b>$receptor</b>");
?>

<!DOCTYPE html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=, initial-scale=1.0">
  <title>.::Actas - Veteranos::.</title>
   <link rel="stylesheet" href="../estilos/styles.css">
  <style>

  body{
    font-family: Helvetica, Arial, sans-serif;
    font-size: 12px;
  }

  html{
    margin-top: 10px;
    margin-left: 10px;
    margin-right:10px; 
    margin-bottom: 0px;
    font-family: Helvetica, Arial, sans-serif;
  }
 
.input-report{
    font-family: Helvetica, Arial, sans-serif;
    border: none;
    border-bottom: 2.2px dotted #C8C8C8;
    text-align: left;
    background-color: transparent;
    font-size: 14px;
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

footer {
position: fixed;
bottom: -60px;
left: 0px;
right: 0px;
height: 50px;

/** Extra personal styles **/
background-color: #03a9f4;
color: black;
text-align: center;
line-height: 35px;
}
  </style>

</head>

<body>

<html>
<div id="watermark">
<img src="../dist/img/Escudo_Gobierno.jpg" width="700" height="700"/>
</div>

<table style="width: 100%;margin-top:2px" width="100%">
<tr>
<td width="25%" style="width:25%;margin:0px">
  <img src='../dist/img/newlogoinabve.jpeg' width="230" height="80"/>
</td>
  
<td width="50%" style="width:50%;margin:0px">
<table style="width:100%">
  <br>
  <tr>
    <td  style="text-align: center;margin-top: 0px;font-size:15px;font-family: Helvetica, Arial, sans-serif;"><b>ENTREGA OFICIAL DE ACTAS</b></td>
  </tr>


  <tr>
    <td  style="text-align:center;margin-top:0px;font-size:15px;font-family: Helvetica, Arial, sans-serif;text-transform: uppercase"><u>ACTAS DE ENTREGAS</u></td>
  </tr>
  <!-- <tr>
    <td  style="text-align:center;margin-top:0px;font-size:16px;font-family: Helvetica, Arial, sans-serif;text-transform: uppercase"><u>Fecha:</u></td>
  </tr> -->
</table>
</td>

<td width="25%" style="width:25%;margin:0px;float: left;">
  <img src='../dist/img/logo_avplus.jpg' width="150" height="80" style="margin-top:5px;float: left;"><br>
 
</td>
</tr>
</table>
<span style='float: right;margin-top: 0px !important;font-size:18px;margin-right:65px'><b><?php echo $cod_entrega?></b></span>
<!--fin tabla--> <br><br> 

<div style="font-family: Helvetica, Arial, sans-serif;font-size:14px;padding:2px;text-align: justify">
<table width="100%" style="width: 100%;margin-top: 0px i !important " >
  <tr>
    <td colspan="25" style="width: 25%"><input type="text" class="input-report" value="Cant. Actas: <?php echo count($resultData);?>"></td>  
    <td colspan="38" style="width: 38%;text-align: left;"><input type="text" class="input-report" value="Recibe: <?php echo $receptor ?>"></td>
    <td colspan="37" style="width: 37%;text-align: left;"><input type="text" class="input-report" value="Firma-Sello: "></td>    
  </tr>
  <tr>
    <td colspan="25" style="width: 25%"><input type="text" class="input-report" value="Fecha: <?php echo $hoy;?>"></td>
    <td colspan="37" style="width: 38%;text-align: left;"><input type="text" class="input-report" value="Entrega: <?php echo $emisor ?> "></td>    
    <td colspan="38" style="width: 37%;text-align: left;"><input type="text" class="input-report" value="Firma-Sello: "></td>
  </tr>
</table>
 <b><h5 style="font-size:12px;font-family: Helvetica, Arial, sans-serif;text-align: center;margin-bottom: 0px"> DETALLE DE ACTAS</h5></b>
	<table width="100%" id="pacientes" style="margin-top: 0px">
    <thead>
    <tr>
    <th>#</th>
    <th>ID Acta</th>
    <th>Sucursal</th>
    <th>Paciente</th>
    <th>DUI</th>
    <th>Tipo paciente</th>
    <th>Sector</th>
  </tr>
    </thead>  
  <?php
  $cont = 1;
  foreach ($resultData as $row) { ?>
    <tr> 
     <td><?php echo $cont; ?></td>
     <td><?php echo $row['id_acta']; ?></td>
     <td><?php echo $row['sucursal']; ?></td>
     <td align="left"><?php echo $row['paciente']; ?></td>
     <td><?php echo $row['dui']; ?></td>
     <td><?php echo $row['tipo_paciente']; ?></td>
     <td><?php echo $row['sector']; ?></td>
    </tr> 

  <?php $cont++; } ?>  
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