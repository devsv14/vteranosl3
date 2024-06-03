<?php 
require_once("../config/conexion.php");
if(isset($_SESSION["usuario"])){
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Home</title>
<?php require_once("links_plugin.php"); 
 require_once('../modelos/Tablas.php');
 $tablas = new Tablas();
 $laboratorio = $_POST["laboratorio"];
 $tipo_lente = $_POST["tipo_lente"];
 $base = $_POST["base"];
 $inicio = $_POST["inicio"];
 $fin = $_POST["fin"];
 if($tipo_lente=="Flaptop" or $tipo_lente=="Progresive"){
  $table_report = $tablas->flaptop_progresive($inicio,$fin,$laboratorio,$tipo_lente,$base);
}elseif($tipo_lente=="Visión Sencilla"){
  $table_report = $tablas->visionSencilla($inicio,$fin,$laboratorio,$tipo_lente,$base);
}
 
//var_dump($flaptop_od);exit(); 
 ?>
<style>
  .buttons-excel{
    background-color: green !important;
    margin: 2px;
    max-width: 150px;
  }
      <style>
  .buttons-excel{
    background-color: green !important;
    margin: 2px;
    max-width: 150px;
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

  .fila:hover {
    background-color: lightyellow;
  }
</style>
</style>
</head>
<body class="hold-transition sidebar-mini layout-fixed" style='font-family: Helvetica, Arial, sans-serif;'>
<div class="wrapper">
<!-- top-bar -->
  <?php require_once('top_menu.php')?>
  <!-- /.top-bar -->

  <!-- Main Sidebar Container -->
  <?php require_once('side_bar.php')?>
  <!--End SideBar Container-->
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <section class="content">
      <div class="container-fluid">
      <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $_SESSION["id_usuario"];?>"/>
      <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION["usuario"];?>"/>
    <div style="border-top: 0px">
    </div>
   <!--TABLAS PROGRESIVE Y FLAPTOP-->
   <?php if($tipo_lente=="Flaptop" or $tipo_lente=="Progresive"){?><!--INICIO TABLAS PROGRESIVE Y FLAPTOP-->
    <div class="card card-dark card-outline" style="margin: 2px;">
      <div class="row">
        <div class="col-sm-3">  
        <button type="button" class="btn btn-danger btn-xs" onClick="reiniciar_tabla()"><i class="fas fa-sync-alt"></i> REINICIAR REPORTE</button>
        <br>
        </div>
      </div>

      <?php
       $codigosFlaptopOd = Array();
       $fgraduacionOd = Array();
       $fgraduacionOi= Array();
       //flaptop_od
       foreach ($table_report as $value) {
         array_push($codigosFlaptopOd, $value["codigo"]);
         array_push($fgraduacionOd, $value["od_esferas"]."*".$value["od_adicion"]);
         array_push($fgraduacionOi, $value["oi_esferas"]."*".$value["oi_adicion"]);
       }

       $grad_flaptop_od[] = (array_count_values($fgraduacionOd));
       $grad_flaptop_oi[] = (array_count_values($fgraduacionOi));

      ?>
    
    <table width="100%" class="table-hover table-bordered table-striped" id="table_flap_progre">
          <h5 style="text-align: center;background:#034f84;color:white;font-family: Helvetica, Arial, sans-serif;font-size: 16px;"><?php echo strtoupper($tipo_lente);?> RIGTH <?php echo strtoupper($laboratorio)." DESDE:".date("d-m-Y",strtotime($inicio))." HASTA:".date("d-m-Y",strtotime($fin));?></h5>
      <thead class="style_th bg-dark" style="color: black">
           <th>Esf/Add</th>
           <th>1.00</th>
           <th>1.25</th>
           <th>1.50</th>
           <th>1.75</th>
           <th>2.00</th>
           <th>2.25</th>
           <th>2.05</th>
           <th>2.75</th>
           <th>3.00</th>
           <th>Total</th>
         </thead>
        <?php
        $html = "";
        for ($i = -2; $i <= 4; $i = $i + 0.25) {
        $html .="<tr class='fila'>";
        if($i>0){
        $esf = "+".number_format($i,2,".",",");
        }else{
        $esf  = number_format($i,2,".",",");
        }
        $html .= "<td class='stilot1'><b>".$esf." Right</b></td>";

        for ($j = 1; $j <= 3; $j = $j + 0.25) {
          $add = "+".number_format($j,2,".",",");
          $param = $esf."*".$add;

        foreach ($grad_flaptop_od as $k) {
        if(isset($k[$param])){
            $html .= "<td class='stilot1'>".$k[$param]."</td>";
        }else{
        $html .= "<td class='stilot1'>0</td>";
       }     
       }
     }
     $html .="<td class='stilot1' style='color:blue'></td></tr>";
     }

        ?>
        <?php echo $html; ?>
    <tfoot>
      <tr>
        <td class='stilot1' style='color:blue'>Total</td>
        <td class='stilot1' style='color:blue'></td>
        <td class='stilot1' style='color:blue'></td>
        <td class='stilot1' style='color:blue'></td>
        <td class='stilot1' style='color:blue'></td>
        <td class='stilot1' style='color:blue'></td>
        <td class='stilot1' style='color:blue'></td>
        <td class='stilot1' style='color:blue'></td>
        <td class='stilot1' style='color:blue'></td>
        <td class='stilot1' style='color:blue'></td>
        <td class='stilot1' style='color:green'></td>
      </tr>
    </tfoot>    
        </table>

        <table width="100%" class="table-hover table-bordered table-striped" id="table_flap_progre_oi">
        <h5 style="text-align: center;background:#034f84;color:white;font-family: Helvetica, Arial, sans-serif;font-size: 16px"><?php echo strtoupper($tipo_lente);?> LEFT <?php echo strtoupper($laboratorio)." DESDE:".$inicio." HASTA:".date("d-m-Y",strtotime($fin));?></h5>
        <thead class="style_th bg-dark" style="color: black">
           <th>Esf/Add</th>
           <th>1.00</th>
           <th>1.25</th>
           <th>1.50</th>
           <th>1.75</th>
           <th>2.00</th>
           <th>2.25</th>
           <th>2.05</th>
           <th>2.75</th>
           <th>3.00</th>
           <th>Total</th>
         </thead>
        <?php
        $html_oi = "";
        for ($i = -2; $i <= 4; $i = $i + 0.25) {
        $html_oi .="<tr class='fila'>";
        if($i>0){
        $esf = "+".number_format($i,2,".",",");
        }else{
        $esf  = number_format($i,2,".",",");
        }
        $html_oi .= "<td class='stilot1'><b>".$esf." Left</b></td>";

        for ($j = 1; $j <= 3; $j = $j + 0.25) {
          $add = "+".number_format($j,2,".",",");
          $parametro = $esf."*".$add;

        foreach ($grad_flaptop_oi as $v) {
        if(isset($v[$parametro])){
            $html_oi .= "<td class='stilot1'>".$v[$parametro]."</td>";
        }else{
        $html_oi .= "<td class='stilot1'>0</td>";
       }     
       }
     }$html_oi .="<td class='stilot1' style='color:blue'></tr>";
        }

        ?>
        <?php echo $html_oi; ?>
        <tfoot>
      <tr>
        <td class='stilot1' style='color:blue'>Total</td>
        <td class='stilot1' style='color:blue'></td>
        <td class='stilot1' style='color:blue'></td>
        <td class='stilot1' style='color:blue'></td>
        <td class='stilot1' style='color:blue'></td>
        <td class='stilot1' style='color:blue'></td>
        <td class='stilot1' style='color:blue'></td>
        <td class='stilot1' style='color:blue'></td>
        <td class='stilot1' style='color:blue'></td>
        <td class='stilot1' style='color:blue'></td>
        <td class='stilot1' style='color:green'></td>
      </tr>
    </tfoot>  
        </table>
    </div>
    <script>
    </script>
    <?php }elseif($tipo_lente=="Visión Sencilla"){

          $codigosVs = Array();
          $vsgraduacionOd = Array();
          $vsgraduacionOi= Array();
          foreach ($table_report as $key) {
            array_push($codigosVs, $key["codigo"]);
                array_push($vsgraduacionOd, $key["od_esferas"]."*".$key["od_cilindros"]);
                array_push($vsgraduacionOi, $key["oi_esferas"]."*".$key["oi_cilindros"]);
          }         

          $grad_vs_od[] = array_count_values($vsgraduacionOd);
          $grad_vs_oi[] = array_count_values($vsgraduacionOi);
      require_once("ordenes/tabla_vs.php");
    }  
    ?>    
    </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div> 
</div>
   <!--Modal Imagen Aro-->
   <div class="modal" id="verImg">
    <div class="modal-dialog" style="max-width: 45%">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
          <div style="  background-size: cover;background-position: center;display:flex;align-items: center;">
            <img src="" alt="" id="imagen_aro_v" style="width: 100%;border-radius: 8px;">
          </div>          
        </div>        
   
      </div>
    </div>
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>2021 Lenti || <b>Version</b> 1.0</strong>
     &nbsp;All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      
    </div>
  </footer>
</div>
<!-- ./wrapper -->
<?php 
require_once("links_js.php");
?>
<script type="text/javascript" src="../js/productos.js"></script>
<script type="text/javascript" src="../js/autocomplete.js"></script>
<script>  
function reiniciar_tabla(){
  
    let passedArray = <?php echo json_encode($codigosFlaptopOd); ?>;
    let laboratorio = <?php echo json_encode($laboratorio); ?>;
    let tipo_lente = <?php echo json_encode($tipo_lente); ?>;
    let base = <?php echo json_encode($base); ?>;
    $.ajax({
    url:"../ajax/ordenes.php?op=reset_tables",
    method:"POST",
    data:{'array_restart':JSON.stringify(passedArray),laboratorio:laboratorio,tipo_lente:tipo_lente,base:base},
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
    
}

function explode(){
  location.reload();
}

let tipo_lentes = <?php echo json_encode($tipo_lente); ?>;
if (tipo_lentes=="Flaptop" || tipo_lentes=="Progresive") {
  calcular();
}
function calcular() {
 
    /** sumamos las filas **/
 
    // obtenemos todas las filas del tbody
    const filas=document.querySelectorAll("#table_flap_progre tbody tr");
 
    // bucle por cada una de las filas
    filas.forEach((fila) => {
 
        // obtenemos los tds de cada fila
        const tds=fila.querySelectorAll("td");
 
        let total=0;
 
        // bucle por cada uno de los tds con excepcion el primero (producto) y ultimo (total)
        for(let i=1; i<tds.length-1; i++) {
 
            // sumamos los tds
            total+=parseFloat(tds[i].innerHTML);
        }
 
        // mostramos el total en la ultima casilla
        tds[tds.length-1].innerHTML=total.toFixed(0);
    });
 
 
    /** sumamos las columnas **/
 
    // obtenemos el numero de columnas
    const columnas=document.querySelectorAll("#table_flap_progre thead tr th");
 
    // obtenemos las fila de los totales
    const totalFila=document.querySelectorAll("#table_flap_progre tfoot tr td");
 
    // bucle por cada una de las columnas excepto la primera
    for(let i=1; i<columnas.length; i++) {
        let total=0;
 
        // obtenemos el valor de cada una de las filas
        filas.forEach((fila) => {
            total+=parseFloat(fila.querySelectorAll("td")[i].innerHTML);
        });
 
        // mostramos el total en la ultima fila
        totalFila[i].innerHTML=total.toFixed(0);
    }

    calcularFlapBifoOI();
 
}


function calcularFlapBifoOI() {
 
    /** sumamos las filas **/
 
    // obtenemos todas las filas del tbody
    const filas=document.querySelectorAll("#table_flap_progre_oi tbody tr");
 
    // bucle por cada una de las filas
    filas.forEach((fila) => {
 
        // obtenemos los tds de cada fila
        const tds=fila.querySelectorAll("td");
 
        let total=0;
 
        // bucle por cada uno de los tds con excepcion el primero (producto) y ultimo (total)
        for(let i=1; i<tds.length-1; i++) {
 
            // sumamos los tds
            total+=parseFloat(tds[i].innerHTML);
        }
 
        // mostramos el total en la ultima casilla
        tds[tds.length-1].innerHTML=total.toFixed(0);
    });
 
 
    /** sumamos las columnas **/
 
    // obtenemos el numero de columnas
    const columnas=document.querySelectorAll("#table_flap_progre_oi thead tr th"); 
    // obtenemos las fila de los totales
    const totalFila=document.querySelectorAll("#table_flap_progre_oi tfoot tr td");
 
    // bucle por cada una de las columnas excepto la primera
    for(let i=1; i<columnas.length; i++) {
        let total=0; 
        // obtenemos el valor de cada una de las filas
        filas.forEach((fila) => {
            total+=parseFloat(fila.querySelectorAll("td")[i].innerHTML);
        }); 
        // mostramos el total en la ultima fila
        totalFila[i].innerHTML=total.toFixed(0);
    }
 
}
</script> 

</body>
</html>
 <?php } else{
echo "Acceso denegado";
  } ?>
