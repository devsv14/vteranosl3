<?php
 ob_start();
 use Dompdf\Dompdf;
 //use Dompdf\Options;
 
 require_once '../dompdf/autoload.inc.php';
 require "vendor/autoload.php";
 $Bar = new Picqer\Barcode\BarcodeGeneratorHTML();
 require_once '../modelos/Reporteria.php';
 $reporteria = new Reporteria();
$sucursal = $_POST["sucursal"];
$correlativo =$_POST["correlativo"];
$tipo_desp = $_POST["tipo_desp"];
$corr = $Bar->getBarcode($correlativo, $Bar::TYPE_CODE_128,'1','45');
date_default_timezone_set('America/El_Salvador'); 
$hoy= date("d-m-Y H:i:s");
$fecha = date("Y-m-d");
    $data = $reporteria->getDetalleDespacho($correlativo);
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
      padding-top: 4px;
      padding-bottom: 3px;
      text-align: center;
      background-color: #4c5f70;
      color: white;
    }

    .stilot1{
    border: 1px solid black;
    padding: 1.5px;
    font-size: 11px;
    font-family: Helvetica, Arial, sans-serif;
    text-align: center;

  }
  .table2 {
    border-collapse: collapse;
  }
  .encabezado{
    background: #E8E8E8;
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
    <td  style="text-align: center;margin-top: 0px;font-size:15px;font-family: Helvetica, Arial, sans-serif;"><b>ENVIOS  DE OPTICA A LABORATORIO</b></td>
  </tr>
  <tr>
  <td  style="text-align: center;margin-top: 0px;font-size:15px;font-family: Helvetica, Arial, sans-serif;"><b><?php echo $sucursal;?></b></td>
  </tr>
  </tr>
  <tr>
  <td  style="text-align: center;margin-top: 0px;font-size:15px;font-family: Helvetica, Arial, sans-serif;"><u><?php echo $hoy;?></u></td>
  </tr>
  </tr>
</table><!--fin segunda tabla-->
</td>
<td width="25%" style="width: 30%;margin:0px">
<table>
  <tr>
    <td style="text-align:right; font-size:12px;color: #008C45"><strong>ORDEN</strong></td>
  </tr>
  <tr>
    <td style="color:red;text-align:right; font-size:12px;color: #CD212A"><strong >No.&nbsp;<span><?php echo $correlativo;
    echo $corr;
    
    ?></strong></td>
  </tr>
</table><!--fin segunda tabla-->
</td> <!--fin segunda columna-->
</tr>
</table>

<table width="100%" style="width: 100%;margin-top: 0px i !important " >
  <tr>
    <td colspan="25" style="width: 25%"><input type="text" class="input-report" value="Firma-Sello óptica: "></td>
    <td colspan="38" style="width: 38%;text-align: left;"><input type="text" class="input-report" value="Enviado por: "></td>
    <td colspan="37" style="width: 37%;text-align: left;"><input type="text" class="input-report" value="Mensajero: "></td>    
  </tr>


</table>
</table>
 <b><h5 style="font-size:12px;font-family: Helvetica, Arial, sans-serif;text-align: center;margin-bottom: 0px"> DETALLE DE ENVÍO A LABORATORIOS</h5></b>
	<table width="100%" id="pacientes" style="margin-top: 0px">
    <tr>
    <th>#</th>
    <th>DUI</th>
    <th>PACIENTE</th>
  </tr>  
  <?php
  $i=1;
  $array_dui = array();
  foreach ($data as $value) { ?>
    <tr> 
     <td><?php echo $i;?></td>
     <td><?php echo $value["dui"]; ?></td>
     <td align="left"><?php echo $value["paciente"]; ?></td>
    </tr> 
   
  <?php
  array_push($array_dui, $value["dui"]);
  $i++; } ?>  
  </table>


  <div style="page-break-after:always;"></div>


<table width="100%">
<?php
for($i=0;$i<count($array_dui);$i++){
    
$resultado = $reporteria->getDataOrdenDui($array_dui[$i]);
    foreach($resultado as $key){
      $codigo = $key["dui"];
    }
    
    $code = $Bar->getBarcode($codigo, $Bar::TYPE_CODE_128,'1','45');
 
  if($i % 2 == 0){
      echo "<tr>";
  }

    echo"<td>";
     
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
      $data_aro = $reporteria->getDataAro($key['id_aro'],$key['codigo']);

      echo "
      <tr>
      <td class='stilot1' colspan='100' style='text-align:center;font-size:13px'>Lente: <b>".$key["tipo_lente"]." ".$key["color"]."</b></td>
      </tr>
      
           <tr>
      <td class='stilot1' colspan='30' style='text-align:left'>Tel. ".$key["telefono"]."</td>
      <td class='stilot1' colspan='30' style='text-align:cente'><b>Suc.:</b> ".$key["sucursal"]."</td>
      <td class='stilot1' colspan='40' style='text-align:cente'><b>Fecha</b> ".date("d-m-Y",strtotime($key["fecha"]))."</td>
      </tr>
      
      <tr style='height: 14px'>
        <td class='stilot1 encabezado' colspan='65'><b style='padding: 0px'>Paciente:</b></td>
        <td class='stilot1 encabezado' colspan='20'><b style='padding: 0px'>DUI</b></td>
        <td class='stilot1 encabezado' colspan='15'><b style='padding: 0px'>Edad:</b></td>
      </tr>
      <tr>
        <td class='stilot1' colspan='65' style='text-transform:uppercase;font-size:10px'>".$key["paciente"]."</td>
        <td class='stilot1' colspan='20'>".$key["dui"]."</td>
        <td class='stilot1' colspan='15'>".$key["edad"]."</td>
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
        <td colspan='20' class='stilot1'>".$key["od_esferas"]."</td>
        <td colspan='20' class='stilot1'>".$key["od_cilindros"]."</td>
        <td colspan='20' class='stilot1'>".$key["od_eje"]."</td>
        <td colspan='20' class='stilot1'>".$key["od_adicion"]."</td>
      </tr>
      
       <tr>
      <td colspan='20' class='stilot1'><b>OI</b></td>
      <td colspan='20' class='stilot1'>".$key["oi_esferas"]."</td>
      <td colspan='20' class='stilot1'>".$key["oi_cilindros"]."</td>
      <td colspan='20' class='stilot1'>".$key["oi_eje"]."</td>
      <td colspan='20' class='stilot1'>".$key["oi_adicion"]."</td>
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
      <td colspan='15' class='stilot1'>".$key["pupilar_od"]." mm</td>
      <td colspan='15' class='stilot1'>".$key["pupilar_oi"]." mm</td>
      <td colspan='15' class='stilot1'>".$key["lente_od"]." mm</td>
      <td colspan='15' class='stilot1'>".$key["lente_oi"]." mm</td>
      <td colspan='20' class='stilot1'>".$key["avsc"]."</td>
      <td colspan='20' class='stilot1'>".$key["avfinal"]."</td>
    </tr>
    <tr>
    <td colspan='100' class='stilot1' style='height:40px'>".$key["observaciones"]."</td>
    </tr>
    <tr>    
      <td colspan='100' class='stilot1'>".$data_aro." mm</td>
    </tr>
      
      ";
      
      
    }
     
    echo "</table>--------------------------------------------------------------------------";
    
    echo "</td>";

    if ($i % 2 != 0) {
      echo "</tr>";
    }
  
   
}//fin recorrer array dui
?>
</table>
<?php

$salida_html = ob_get_contents();

ob_end_clean();
$dompdf = new Dompdf();
$dompdf->loadHtml($salida_html);
$dompdf->setPaper('letter', 'portrait');
$dompdf->render();
$dompdf->stream('document', array('Attachment'=>'0'));
?>