<?php ob_start();
use Dompdf\Dompdf;
use Dompdf\Options;
use Luecano\NumeroALetras\NumeroALetras;

require_once '../dompdf/autoload.inc.php';
require_once '../helpers/convert_text_number.php';
//Convertir numero a letras
$txt_a_number = new NumeroALetras(); //Instancia de libreria convertir
$data= json_decode($_POST['data']);
$data_items = $data->info;

$retencion = $data->retencion;
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
   <style>
      html{
        margin-top: 0;
        margin-left: 18px;
        margin-right:40px; 
        margin-bottom: 0;
    }
    .stilot1{
       border: 1px solid black;
       padding: 5px;
       font-size: 12px;
       font-family: Helvetica, Arial, sans-serif;
       border-collapse: collapse;
       text-align: center;
    }

    .stilot2{
       border: 1px solid black;
       text-align: center;
       font-size: 11px;
       font-family: Helvetica, Arial, sans-serif;
    }
    .stilot3{
       text-align: center;
       font-size: 11px;
       font-family: Helvetica, Arial, sans-serif;
    }

    #table2 {
       border-collapse: collapse;
    }
   </style>
  </head>
  <body>
 <?php date_default_timezone_set('America/El_Salvador'); $hoy = date("d-m-Y");?>
<div style="margin-top: 130px;height:500px" >

<?php include '../helpers/factura_man_plantilla.php'; 

?>

</div>

<!--ORIGINAL EMISOR-->
<div style="margin-top: 30px;max-height:100px" >
<?php include '../helpers/factura_man_plantilla.php'; ?>
</div>


</body>
</html>
<?php
$salida_html = ob_get_contents();

//$user=$_SESSION["id_usuario"];
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