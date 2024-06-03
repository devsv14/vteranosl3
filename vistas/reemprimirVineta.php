<?php
ob_start();

use Dompdf\Dompdf;
//use Dompdf\Options;

require_once '../dompdf/autoload.inc.php';
require "vendor/autoload.php";
$Bar = new Picqer\Barcode\BarcodeGeneratorHTML();
require_once '../modelos/Reporteria.php';
$reporteria = new Reporteria();
$dui_vet = $_POST["dui_vet"];
$hoy = date('d-m-Y');
$resultado = $reporteria->getDataOrdenDui($dui_vet);
foreach ($resultado as $key) {
  $codigo = $key["dui"];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../estilos/styles.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>.::Reportes::.</title>
  <style>
    body {
      font-family: Helvetica, Arial, sans-serif;
      font-size: 12px;
    }

    html {
      margin-top: 5px;
      margin-left: 20px;
      margin-right: 20px;
      margin-bottom: 0px;
    }

    #pacientes {
      font-family: Arial, Helvetica, sans-serif;
      border-collapse: collapse;
      width: 100%;
      font-size: 11px;
      text-align: center;
      text-transform: uppercase;
    }

    #pacientes td,
    #pacientes th {
      border: 1px solid #ddd;
      padding: 3px;
    }

    #pacientes tr:nth-child(even) {
      background-color: #f2f2f2;
    }

    #pacientes tr:hover {
      background-color: #ddd;
    }

    #pacientes th {
      padding-top: 4px;
      padding-bottom: 3px;
      text-align: center;
      background-color: #4c5f70;
      color: white;
    }

    .stilot1 {
      border: 1px solid black;
      padding: 1.5px;
      font-size: 11px;
      font-family: Helvetica, Arial, sans-serif;
      text-align: center;

    }

    .table2 {
      border-collapse: collapse;
    }

    .encabezado {
      background: #E8E8E8;
    }
  </style>
</head>

<body>
  <table width="50%">
    <?php
    $code = $Bar->getBarcode($codigo, $Bar::TYPE_CODE_128, '1', '45');

    echo "<td>";

    echo "<table class='table2' width='100%' style='margin-top;0px !important'>";
    echo "
     <tr>
      <td colspan='30'>

        <img src='../dist/img/inabve.jpg' width='55' height='55'>
      </td>
      <td colspan='40' style='text-align:center;'>";
    echo $code;
    echo "</td>
      <td colspan='30' align='right' style='text-align:right'>
        <img src='../dist/img/logooficial.jpg' width='50' height='25'>
      </td>
     </tr>
     ";

    foreach ($resultado as $key) {
      $data_aro = $reporteria->getDataAro($key['id_aro'], $key['codigo']);

      echo "
      <tr>
      <td class='stilot1' colspan='100' style='text-align:center;font-size:13px'>Lente: <b>" . $key["tipo_lente"] . " " . $key["color"] . "</b></td>
      </tr>
      
           <tr>
      <td class='stilot1' colspan='30' style='text-align:left'>Tel. " . $key["telefono"] . "</td>
      <td class='stilot1' colspan='30' style='text-align:cente'><b>Suc.:</b> " . $key["sucursal"] . "</td>
      <td class='stilot1' colspan='40' style='text-align:cente'><b>Fecha</b> " . date("d-m-Y", strtotime($key["fecha"])) . "</td>
      </tr>
      
      <tr style='height: 14px'>
        <td class='stilot1 encabezado' colspan='65'><b style='padding: 0px'>Paciente:</b></td>
        <td class='stilot1 encabezado' colspan='20'><b style='padding: 0px'>DUI</b></td>
        <td class='stilot1 encabezado' colspan='15'><b style='padding: 0px'>Edad:</b></td>
      </tr>
      <tr>
        <td class='stilot1' colspan='65' style='text-transform:uppercase;font-size:10px'>" . $key["paciente"] . "</td>
        <td class='stilot1' colspan='20'>" . $key["dui"] . "</td>
        <td class='stilot1' colspan='15'>" . $key["edad"] . "</td>
      </tr>
      <tr>
        <td colspan='100' class='stilot1 encabezado' style='text-align: center'><b>Rx final</b></td>
      </tr>
      <tr>
      <th style='text-align: center;' colspan='20' class='stilot1'><b>OJO</b></th>
        <th style='text-align: center;' colspan='20' class='stilot1'><b>Esfera</b></th>
        <th style='text-align: center;' colspan='20' class='stilot1'><b>Cilindro</b></th>
        <th style='text-align: center;' colspan='20' class='stilot1'><b>Eje</b></th>
        <th style='text-align: center;' colspan='20' class='stilot1'><b>Adición</b></th>
      </tr>
      <tr>
        <td colspan='20' class='stilot1'><b>OD</b></td>
        <td colspan='20' class='stilot1'>" . $key["od_esferas"] . "</td>
        <td colspan='20' class='stilot1'>" . $key["od_cilindros"] . "</td>
        <td colspan='20' class='stilot1'>" . $key["od_eje"] . "</td>
        <td colspan='20' class='stilot1'>" . $key["od_adicion"] . "</td>
      </tr>
      
       <tr>
      <td colspan='20' class='stilot1'><b>OI</b></td>
      <td colspan='20' class='stilot1'>" . $key["oi_esferas"] . "</td>
      <td colspan='20' class='stilot1'>" . $key["oi_cilindros"] . "</td>
      <td colspan='20' class='stilot1'>" . $key["oi_eje"] . "</td>
      <td colspan='20' class='stilot1'>" . $key["oi_adicion"] . "</td>
    </tr>
    <tr>
    <td colspan='30' class='stilot1 encabezado' style='height:10px'>Dist. Pupilar</td>
    <td colspan='30' class='stilot1 encabezado' style='height:10px'>Altura de lente</td>
    <td colspan='40' class='stilot1 encabezado' style='height:10px'>Agudeza visual</td>
    </tr>
    
    <tr>
      <td colspan='15' class='stilot1'><b>OD</b></td>
      <td colspan='15' class='stilot1'><b>OI</b></td>
      <td colspan='15' class='stilot1'><b>OD</b></td>
      <td colspan='15' class='stilot1'><b>OI</b></td>
      <td colspan='20' class='stilot1'><b>AVsc</b></td>
      <td colspan='20' class='stilot1'><b>AVfinal</b></td>
    </tr>
    
    <tr>
      <td colspan='15' class='stilot1'>" . $key["pupilar_od"] . " mm</td>
      <td colspan='15' class='stilot1'>" . $key["pupilar_oi"] . " mm</td>
      <td colspan='15' class='stilot1'>" . $key["lente_od"] . " mm</td>
      <td colspan='15' class='stilot1'>" . $key["lente_oi"] . " mm</td>
      <td colspan='20' class='stilot1'>" . $key["avsc"] . "</td>
      <td colspan='20' class='stilot1'>" . $key["avfinal"] . "</td>
    </tr>
    <tr>
    <td colspan='100' class='stilot1' style='height:40px'>" . $key["observaciones"] . "</td>
    </tr>
    <tr>    
      <td colspan='100' class='stilot1'>" . $data_aro . " mm</td>
    </tr>
      
      ";
    }

    echo "</table>--------------------------------------------------------------------------";

    echo "</td>";
    ?>
  </table>
  <?php

  $salida_html = ob_get_contents();

  ob_end_clean();
  $dompdf = new Dompdf();
  $dompdf->loadHtml($salida_html);
  $dompdf->setPaper('letter', 'portrait');
  $dompdf->render();
  $dompdf->stream('document', array('Attachment' => '0'));
  ?>