
<?php ob_start();
use Dompdf\Dompdf;
use Dompdf\Options;

require_once '../dompdf/autoload.inc.php';
require_once ('../config/conexion.php');
require_once ('../modelos/InsertarIMG.php');
date_default_timezone_set('America/El_Salvador'); 
$hoy = date("d-m-Y");
$dateTime= date("d-m-Y H:i:s");
$hora = date("H:i");
//Actas scan
$scanActa = new ScanActas();
$id_acta = $_POST['id_acta'];
$data = $scanActa->getScanActasUpload($id_acta);
$options = new Options();
$options->set('isRemoteEnabled', TRUE);
$dompdf = new Dompdf($options);
//Array Tipo documento
$arrayInfoDoc = ['VIÑETA LABORATORIO','RECETA OPTÓMETRA','ACTA FIRMADA','HOJA DE IDENTIFICACIÓN'];
//print_r($data);
foreach($data as $row){
  $sucursal = $row['sucursal'];
  $ampo = $row['ampo'];
  $fecha_scan = $row['fecha_scan'];
  $tipo_exp = $row['tipo_expediente'];
}
?>

<!DOCTYPE html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=, initial-scale=1.0">
  <title>.::Actas::.</title>
   <link rel="stylesheet" href="../estilos/styles.css">
  <style>

  body{
    font-family: Helvetica, Arial, sans-serif;
    font-size: 12px;
  }

  html{
    margin-top: 10px;
    margin-left: 15px;
    margin-right:15px; 
    margin-bottom: 0px;
    font-family: Helvetica, Arial, sans-serif;
  }
 
  #watermark {
        position: fixed;
        top: 15%;
        margin-left: 5.2%;
        width: 100%;
        opacity: .20;    
        z-index: -1000;
  }
  .card{
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
    padding: 10px;
    border: 1px solid rgba(0, 0, 0, 0.1);
  }
  .card-header{
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
    padding: 5px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    margin-bottom: 5px;
  }
  .card_text{
    text-align:center;font-weight:700;font-size:13px;font-family: Helvetica, Arial, sans-serif;text-transform: uppercase;
    margin: 0px;
  }
  .card-content img{
    border-radius: 6px;
  }
  .card-footer{
    font-size: 13px;
    color: #f2f2f2;
    padding: 5px;
    color: #222;
  }
  .card-footer p{
    font-family: Helvetica, Arial, sans-serif;
    margin: 0px;
    font-weight: 700px;
    text-transform: uppercase;
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
  <img src='../dist/img/newlogoinabve.jpeg' width="180"/>
</td>
  
<td width="50%" style="width:50%;margin:0px">
<table style="width:100%">
  <br>
  <tr>
    <td  style="text-align: center;margin-top: 0px;font-size:15px;font-family: Helvetica, Arial, sans-serif;"><b>ACTAS FIRMADAS</b><br><b><?php echo $ampo ?></b><br><b><?php echo $sucursal ?></b></td>
  </tr>
</table>
</td>

<td width="25%" style="width:16%;margin:0px;float: left;">
  <img src='../dist/img/logo_avplus.jpg' width="120" style="margin-top:5px;float: left;"><br>
</td>
</tr>
</table>
<?php
$i = 0;
foreach($data as $row): ?>
<div class="card">
  <div class="card-header">
    <p class="card_text"><?php echo $arrayInfoDoc[$i] ?> - <?php echo $row['paciente'] ?> - <?php echo $row['dui_paciente'] ?> #<?php echo $ampo ?></p>
  </div>
  <div class="card-content">
    <?php if($i < 1): ?>
      <img src="<?php echo $row['url_expediente'] ?>" width="100%" height="850">
    <?php else: ?>
      <img src="<?php echo $row['url_expediente'] ?>" width="100%" height="940">
    <?php endif; ?>
  </div>
  <div class="card-footer">
      <p>Fecha de impresión: <?php echo $row['fecha_impresion']?> - Fecha escaneo: <?php echo $row['fecha_scan']?> - <?php echo $sucursal ?></p>
  </div>
</div>
  
<?php
  if(($i + 1) < count($data)){
    echo '<div style="page-break-after:always;"></div>';
  }
  $i += 1;
?>
<?php endforeach ?>
</body>
</html>

<?php
$salida_html = ob_get_contents();
ob_end_clean();
$dompdf->loadHtml($salida_html);
// (Optional) Setup the paper size and orientation
$dompdf->setPaper('letter', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream('document', array('Attachment'=>'0'));
?>