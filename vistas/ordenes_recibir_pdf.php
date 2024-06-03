<?php ob_start();
use Dompdf\Dompdf;

require_once('../dompdf/autoload.inc.php');
require_once('../config/conexion.php');
require_once('../modelos/Reporteria.php');

$reporteria= new Reporteria;

$codigos = $_POST["orders"];
//print_r($codigos); exit();
$array_codigos=explode(",", $codigos);

?>

<html>
  <style>
  html{
    margin-top: 2;
    margin-left: 8px;
    margin-right:8px; 
    margin-bottom: 0;
  }
  body{
    font-family: Helvetica, Arial, sans-serif;
    font-size: 11px;
  }
  .stilot1{
    border: 1px solid black;
    padding: 2.5px;
    font-size: 9px;
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
	<body>
	 <h5 style="font-size: 14px;text-align: center;">ORDENES RECIBIDAS DEL <?php echo date("d-m-Y",strtotime($_POST["inicio"]))." AL ".date("d-m-Y",strtotime($_POST["fin"]));?></h5>
		<table width="100%" class='table2' style="font-size: 9px;text-transform: uppercase;">
			<tr style="text-align: center; background: #29364e;color:white">
            	<th>Codigo</th>
            	<th>Fecha</th>
            	<th>Paciente</th>
            	<th>Vertical</th>
            	<th>Horizontal</th>
            	<th>Color frente</th>
            	<th>Color varilla</th>
            	<th>Tipo lente</th>          
            </tr>
            <tbody>

		<?php
               foreach($array_codigos as $value){
				$data = $reporteria->get_ordenes_recibir_lab($value);
				foreach ($data as $key) {
				    echo "<tr>";
	            	echo "<td class='stilot1'>".$key['codigo']."</td>";
	            	echo "<td class='stilot1'>".date("d-m-Y",strtotime($key['fecha']))."</td>";
	            	echo "<td class='stilot1'>".$key['paciente']."</td>";
	            	echo "<td class='stilot1'>".$key['vertical_aro']."</td>";
	            	echo "<td class='stilot1'>".$key['horizontal_aro']."</td>";
	            	echo "<td class='stilot1'>".$key['color_frente']."</td>";
	            	echo "<td class='stilot1'>".$key['color_varilla']."</td>";
	            	echo "<td class='stilot1'>".$key['tipo_lente']."</td>";
				    echo "</tr>";
				}
			}
			?>
		</tbody>	
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