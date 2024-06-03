<?php
$codigo = $_POST["codigoOrden"];
$paciente = $_POST["paciente_orden"];

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
   <style>
   .padre {
  background: yellow;
  height: 150px;
  /*IMPORTANTE*/
  display: flex;
  justify-content: center;
  align-items: center;
}

.hijo {
  width: 120px;
}
   }
   </style>
  </head>
  <body>
    <div id="padre">
      <div class="hijo"> 
          <?php 
            echo $codigo.'<br>'.$paciente;

          ?>
        <img src="../codigos/<?php echo $codigo;?>.png"/ style=" margin-top: 10px">
      </div>  
      
    </div>
    
</body>

</html>
