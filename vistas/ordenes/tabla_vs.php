
<div class="card card-dark card-outline" style="margin: 2px;">
  <div class="row">
     <div class="col-sm-3">  
     <button type="button" class="btn btn-danger btn-xs" onClick="reiniciarTableVs()" style="margin: 5px"><i class="fas fa-sync-alt"></i> REINICIAR REPORTE VS</button>
 </div></div><br>
     <table width="100%" class="table-hover table-bordered table-striped" id="datable_aros_inv">
     <h5 style="text-align: center;background:#034f84;color:white;font-family: Helvetica, Arial, sans-serif;font-size: 16px;"><?php echo strtoupper($tipo_lente);?> RIGTH <?php echo strtoupper($laboratorio)." DESDE:".date("d-m-Y",strtotime($inicio))." HASTA:".date("d-m-Y",strtotime($fin));?></h5>
      <thead class="style_th bg-dark" style="color: black">
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
      </thead>
      <tbody>
      	<?php
       	    $table_negative_od = "";
            for ($i = 4; $i >= (-4); $i = $i - 0.25) {
            $table_negative_od .="<tr class='fila'>";
            if($i>0){
            $esf  = "+".number_format($i,2,".",",");
            }else{
            	$esf  = number_format($i,2,".",",");
            }
            $table_negative_od .= "<td class='stilot1'><b>".$esf."</b></td>";
            
              for ($j = 0; $j >= (-4); $j = $j - 0.25) {
                if($j>0){
                $cil = "+".number_format($j,2,".",",");
                }else{
                $cil = number_format($j,2,".",",");
                }
                //echo $cil;
                $param = $esf."*".$cil;
                //echo $param; exit();
                foreach ($grad_vs_od as $value) {
                    if(isset($value[$param])){
                      $table_negative_od .= "<td class='stilot1'>".$value[$param]."</td>";
                    }else{
                      $table_negative_od .= "<td class='stilot1'>0</td>";
                    }     
                }        
            }
            $table_negative_od .="</tr>";
        }
        echo $table_negative_od;
      	?>
      </tbody>
    </table>
    <!--OJO IZQUIERDO VS -->
        <table width="100%" class="table-hover table-bordered table-striped" id="datable_aros_inv">
     <h5 style="text-align: center;background:#034f84;color:white;font-family: Helvetica, Arial, sans-serif;font-size: 16px;"><?php echo strtoupper($tipo_lente);?> LEFT <?php echo strtoupper($laboratorio)." DESDE:".date("d-m-Y",strtotime($inicio))." HASTA:".date("d-m-Y",strtotime($fin));?></h5>
      <thead class="style_th bg-dark" style="color: black">
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
      </thead>
      <tbody>
      	<?php		

    	    $table_vs_oi = "";
            for ($i = 4; $i >= (-4); $i = $i - 0.25) {
            $table_vs_oi .="<tr class='fila'>";
            if($i>0){
            $esf  = "+".number_format($i,2,".",",");
            }else{
            	$esf  = number_format($i,2,".",",");
            }
            $table_vs_oi .= "<td class='stilot1'><b>".$esf."</b></td>";
            
              for ($j = 0; $j >= (-4); $j = $j - 0.25) {
                if($j>0){
                $cil = "+".number_format($j,2,".",",");
                }else{
                $cil = number_format($j,2,".",",");
                }
                //echo $cil;
                $param = $esf."*".$cil;
                //echo $param; exit();
                foreach ($grad_vs_oi as $value) {
                    if(isset($value[$param])){
                      $table_vs_oi .= "<td class='stilot1'>".$value[$param]."</td>";
                    }else{
                      $table_vs_oi .= "<td class='stilot1'>0</td>";
                    }     
                }        
            }
            $table_vs_oi .="</tr>";
        }
        echo $table_vs_oi;
      	?>
      </tbody>
    </table>
     </div>
  </div>
</div>
<script>	

function reiniciarTableVs(){
  let vs_codigos = <?php echo json_encode($codigosVs); ?>;
  let laboratorio = <?php echo json_encode($laboratorio);?>;
  let tipo_lente = <?php echo json_encode($tipo_lente); ?>;
  let base = <?php echo json_encode($base); ?>;
  $.ajax({
    url:"../ajax/ordenes.php?op=reset_tables",
    method:"POST",
    data:{'array_restart':JSON.stringify(vs_codigos),laboratorio:laboratorio,tipo_lente:tipo_lente,base:base},
    cache: false,
    dataType:"json", 

    success:function(data){
      Swal.fire({
        position: 'top-center',
        icon: 'success',
        title: 'Tabla Actualizada',
        showConfirmButton: true,
        timer: 2500
      });
      explode();
    }
  });//fin ajax

}</script>
 	 	 	 	 	 	 	 	 
