<?php ob_start();
use Dompdf\Dompdf;
//use Dompdf\Options;

require_once '../dompdf/autoload.inc.php';

$paciente = "Esf +1.75 || Cil. -1.25";//$_POST["paciente_orden"];
$optica = $_POST["optica_orden"];
$codigo = 02225123//;$_POST["codigoOrden"];
date_default_timezone_set('America/El_Salvador'); 
$hoy = date("d-m-Y H:i:s");


?>
<html lang="en" dir="ltr">
  <head>
   <style>
      #footer .page:after { content: counter(page, upper-roman); }
   </style>
  </head>
  <body onload="window.print()">
    <div style="text-align: center; font-size: 10px;">
      <div> 
          <?php 
            echo strtoupper($optica).'<br>'.strtoupper($paciente);
          ?>
      </div>  
      <img src="../codigos/<?php echo $codigo;?>.png" style=" margin-top: 10px;margin:8px" width="250" height="100">
    </div>
    <!------- <p style="text-align: center;color: gray"> -------------------------</p>
    <div style="text-align: center; font-size: 10px;">

    <div id="content"> 
    </div>
      <div> 
          <?php 
         //   echo strtoupper($optica).'<br>'.strtoupper($paciente);
          ?>
      </div>  
      <img src="../codigos/<?php// echo $codigo;?>.png" style=" margin-top: 10px">
    </div>-->
    
</body>
</html>
<?php

$salida_html = ob_get_contents();
  //$user=$_SESSION["id_usuario"];
ob_end_clean();
$dompdf = new Dompdf();
$dompdf->loadHtml($salida_html);
$dompdf->setPaper('tabloid', 'portrait');
// (Optional) Setup the paper size and orientation
$dompdf->setPaper(array(0,0,220,210));

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
//$dompdf->stream();
$dompdf->stream('document', array('Attachment'=>'0'));
?>


