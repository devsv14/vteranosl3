<table width="100%">
    <tr>

    <td colspan="40" style="color:black;font-size:11px;border: 1px solid white;font-family: Helvetica, Arial, sans-serif;width: 40%"><strong>CLIENTE:</strong> <?php echo $data->cliente;?></td>
    <td colspan="40" style="color:black;font-size:11px;border: 1px solid white;font-family: Helvetica, Arial, sans-serif;width: 40%"><strong>DIRECCION:</strong> <?php echo $data->direccion;?></td>    
    <td colspan="20" style="color:black;font-size:11px;border: 1px solid white;font-family: Helvetica, Arial, sans-serif;width: 40%"><strong>FECHA:</strong> <?php echo date("d-m-Y",strtotime($data->fecha));?></td>


</tr>

<tr>
  <td colspan="40" style="color:black;font-size:11px;border: 1px solid white;font-family: Helvetica, Arial, sans-serif;width: 40%"><strong>REGISTRO No:</strong> <?php echo $data->num_registro;?></td>
  <td colspan="40" style="color:black;font-size:11px;border: 1px solid white;font-family: Helvetica, Arial, sans-serif;width: 40%"><strong>GIRO:</strong> <?php echo $data->giro;?></td>
  <td colspan="20" style="color:black;font-size:11px;border: 1px solid white;font-family: Helvetica, Arial, sans-serif;width: 40%"><strong>NIT:</strong> <?php echo $data->nit;?></td>
</tr>

</table>
<table id="table2" width="100%">
<tr>
    <th bgcolor="#0061a9" colspan="10" style="color:white;font-size:8px;border: 1px solid #034f84;font-family: Helvetica, Arial, sans-serif;width: 10%"><span class="Estilo11">CANT.</span></th>
    <th bgcolor="#0061a9" colspan="50" style="color:white;font-size:8px;border: 1px solid #034f84;font-family: Helvetica, Arial, sans-serif;width: 50%"><span class="Estilo11">DESCRIPCIÓN</span></th>
    <th bgcolor="#0061a9" colspan="10" style="color:white;font-size:8px;border: 1px solid #034f84;font-family: Helvetica, Arial, sans-serif;width: 10%"><span class="Estilo11">P/UNITARIO</span></th>
    <th bgcolor="#0061a9" colspan="10" style="color:white;font-size:7px;border: 1px solid #034f84;font-family: Helvetica, Arial, sans-serif;width: 10%"><span class="Estilo11">VENTAS NO SUJETAS</span></th>
    <th bgcolor="#0061a9" colspan="10" style="color:white;font-size:8px;border: 1px solid #034f84;font-family: Helvetica, Arial, sans-serif;width:10% "><span class="Estilo11">VENTAS EXENTAS</span></th>
    <th bgcolor="#0061a9" colspan="10" style="color:white;font-size:8px;border: 1px solid #034f84;font-family: Helvetica, Arial, sans-serif;width: 10%"><span class="Estilo11">VENTAS AFECTAS</span></th>
</tr>

<tr style="height:50px;">
  <td colspan="10" style="border: 1px solid black;font-family: Helvetica, Arial, sans-serif;font-size: 10px;text-align: center;margin:20px;height: 95px">
    <?php 
    for ($i=0; $i < count($data_items); $i++) {
     echo $data_items[$i]->cantidad?><br>
     <?php } ?>     
  </td>
 
  <td colspan="50" style="border: 1px solid black;font-family: Helvetica, Arial, sans-serif;font-size: 10px;text-align: left;margin:20px">
  <?php 
    for ($i=0; $i < count($data_items); $i++) {
     echo $data_items[$i]->desc?><br>
     <?php } ?>     
  </td>
 
  <td colspan="10" style="border: 1px solid black;font-family: Helvetica, Arial, sans-serif;font-size:10px;text-align: right;margin:20px">

    <?php 
    for ($i=0; $i < count($data_items); $i++) {
     echo "$".number_format(($data_items[$i]->punit),2,".",",");?><br>
     <?php } ?> 
    
  </td>
  <td colspan="10" style="border: 1px solid black">
      
  </td>
  <td colspan="10" style="border: 1px solid black">
      
  </td>
  <td colspan="10" style="border: 1px solid black;font-family: Helvetica, Arial, sans-serif;font-size:10px;text-align: right;margin:20px">

    <?php 
    $subtotal=0;
    for ($i=0; $i < count($data_items); $i++) {
      $subtotal=$subtotal+$data_items[$i]->subt;
     echo "$".number_format(($data_items[$i]->subt),2,".",",");?><br>

     <?php } ?>
   
  </td>
</tr>
<?php
//$iva = $subtotal*0.13;
//$retenido = $subtotal*0.01;
//$total = ($subtotal+$iva)-$retenido;
$sumas = $subtotal/(1.13);
$iva = $sumas*(0.13);
$subtotal_ccf = $sumas+$iva;
if ($data->contribuyente=="SI") {
  $retenido = $sumas*0.01;
}else{
  $retenido = 0;
}
if ($retenido>0) {
  $total = ($subtotal_ccf)-$retenido;
}else{
  $total= $subtotal_ccf;
}

$total_t = 21020;
?>
<tr>
  <td colspan="60" rowspan="2" class="stilot1" style="width: 60%;text-align: left"><b>SON</b>: <?php echo $txt_a_number->toMoney($total,2,'DÓLARES', 'CENTAVOS');?> </td>
  <td colspan="10" class="stilot1" style="font-size:8px">SUMAS</td>
  <td colspan="10" class="stilot1"></td>
  <td colspan="10" class="stilot1"></td>
  <td colspan="10" class="stilot1" style="font-size:10px;;text-align: right;"><?php echo "$".number_format($subtotal_ccf,2,".",","); ?></td>
</tr>
<tr>
  <td colspan="20" class="stilot1" style="font-size:8px">VENTA EXENTA</td>
  <td colspan="10" class="stilot1"></td>
  <td colspan="10" class="stilot1" style=""></td>
</tr>

<tr>
  <td colspan="60" class="stilot1" style="font-size:8px">LLENAR SI LA OPERACIÓN IGUAL O MAYOR A $11,428.58</td>
  <td colspan="20" class="stilot1" style="font-size:8px">IVA 13%</td>
   <td colspan="10" class="stilot1" style="font-size:8px"></td>
  <td colspan="10" class="stilot1" style="text-align: right;font-size:10px"> <?php echo "$".number_format($iva,2,".",",");?></td>
</tr>

<tr>
  <td colspan="30" rowspan="4" class="stilot1" style="width: 60%;text-align: left;font-size: 9px">
  Entregado por:<br>
  Nombre:<br>
  DUI:<br>
  Firma:<br>
  </td>
  <td colspan="30" rowspan="4" class="stilot1" style="width: 60%;text-align: left;font-size: 9px">
  Entregado por:<br>
  Nombre:<br>
  DUI:<br>
  Firma:<br>
  </td>
  <td colspan="20" class="stilot1" style="font-size:8px; height:8px">SUBTOTAL</td>
  <td colspan="10" class="stilot1" style="height:8px"></td>
  <td colspan="10" class="stilot1" style="height:8px;font-size:10px;text-align: right;"><?php echo "$".number_format($subtotal_ccf,2,".",",");?></td>
</tr>
<tr>
  <td colspan="20" class="stilot1" style="font-size:8px">IVA RETENIDO (1%)</td>
  <td colspan="10" class="stilot1"></td>
  <td colspan="10" class="stilot1" style="text-align: center;font-size:10px;text-align: right;"><?php echo "$".number_format($retenido,2,".",",");?></td>
</tr>
<tr>
  <td colspan="20" class="stilot1" style="font-size:8px">VENTA NO SUJETA</td>
  <td colspan="10" class="stilot1"></td>
  <td colspan="10" class="stilot1"></td>
</tr>

<tr>
  <td colspan="20" class="stilot1" style="font-size:8px"><strong>TOTAL</strong></td>
  <td colspan="20" class="stilot1" style="text-align: right;font-size:11px"><strong><?php echo "$".number_format($total,2,".",",");?></strong></td>
</tr>
</table>