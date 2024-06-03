<?php
require '../modelos/renovacionLentes.php';

$renova = new Licitacion1();

$data = $renova->gestionRenovarLentes();
$resultados = array();
$ojo_d = array();

foreach($data as $v){
    $data_return = array();

    if($v['tipo_lente']=='Visión Sencilla' and $v['categoria']=='Terminado'){
       array_push($ojo_d,$v["od_esferas"].'*'.$v["od_cilindros"]);
       array_push($ojo_d,$v["oi_esferas"].'*'.$v["oi_cilindros"]);
    }
}
$esf = ['+4.00','+3.75','+3.50','+3.25','+3.00','+2.75','+2.50','+2.25','+2.00','+1.75','+1.50','+1.25','+1.00','+0.75','+0.50','+0.25','0.00','-0.25','-0.50','-0.75','-1.00','-1.25','-1.50','-1.75','-2.00','-2.25','-2.50','-2.75','-3.00','-3.25','-3.50','-3.75','-4.00'];
$cil = [0.00,-0.25,-0.50,-0.75,-1.00,-1.25,-1.50,-1.75,-2.00,-2.25,-2.50,-2.75,-3.00,-3.25,-3.50,-3.75,-4.00];
$counted = array_count_values($ojo_d);
print_r($counted);
?>
<style>
  td,th{
  border: 1px solid black;
}
</style>
<table  width="100%" cellspacing="0" style='border-collapse: collapse;'>
<tr>
  <th>Esf/Cil</th>
  <th>0.00</th>
  <th>-0.25</th>
  <th>-0.50</th>
  <th>-0.75</th>
  <th>-1.00</th>
  <th>-1.25</th>
  <th>-1.50</th>
  <th>-1.75</th>
  <th>-2.00</th>
  <th>-2.25</th>
  <th>-2.50</th>
  <th>-2.75</th>
  <th>-3.00</th>
  <th>-3.25</th>
  <th>-3.50</th>
  <th>-3.75</th>
  <th>-4.00</th>
</tr><tbody>
<?php 
foreach($esf as $e){ 
echo '</tr>';
echo "<td>$e</td>";
foreach ($cil as $a) {
    $valor = "$e*$a";
    $occurrences = isset($counted[$valor]) ? $counted[$valor] : 0;
    echo "<td>$occurrences</td>";
  
 }
 echo '</tr>';
}

?>

</tbody>

</table>

<?php



