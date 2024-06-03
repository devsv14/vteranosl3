<?php

require_once("../config/conexion.php");

class Stock extends Conectar{

	public function listar_tablas_terminados(){
	    $conectar=parent::conexion();
        parent::set_names();

        $sql = "select titulo,id_tabla_term,marca,diseno from tablas_terminado order by id_tabla_term ASC;";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getTableTerminados($id_tabla){

		$conectar=parent::conexion();
        parent::set_names();

        $sql = "select*from tablas_terminado where id_tabla_term=?;";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1,$id_tabla);
        $sql->execute();
        $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);

 
        foreach ($resultado as $value) {
        	$nombre = $value['titulo'];
        	$min_cil = $value['min_cil'];
        	$max_cil = $value['max_cil'];
        	$min_esf = $value['min_esf'];
        	$max_esf = $value['max_esf'];
        	$marca = $value['marca'];
        	$diseno = $value['diseno'];
        	$ident_tabla = $value['id_tabla_term'];
        }

        $html='<table width="100%" class="table-bordered" style="text-align: center" id="term_tabla_download_'.$ident_tabla.'">';
        $id=1;
        $html .= '<thead class="style_th bg-dark" style="color: white">';
        $html .="<th>Sph\Cil</td>";
        for($k = $max_cil;$k>=$min_cil;$k-=0.25){
          $k>0 ? $cilind = '+'.number_format($k,2,'.',',') : $cilind = number_format($k,2,'.',',');
          $html .= "<th>".$cilind."</th>";
        }
        $html .= "<thead>";
        $html .= "<tbody>";
        for($i = $max_esf; $i >= $min_esf; $i-=0.25){
        	if($i>0){
        		$esf = '+'.number_format($i,2,'.',',');
        	}else{
        		$esf = number_format($i,2,'.',',');
        	}
            for($j = $max_cil;$j>=$min_cil;$j-=0.25){                 
                if($j>0){
        			$cil = '+'.number_format($j,2,'.',',');
        	    }else{
        			$cil = number_format($j,2,'.',',');
        	    }
                if($cil==0){                   	        	
               		$html .= "<tr class='filas stilot1'><td class='bg-info'>".$esf."</td>";
                }

               $sql2 = "select CONCAT(stock,',',codigo) AS data from stock_terminados where id_tabla_term=? and esfera=? and cilindro=?;"; 
               $sql2 = $conectar->prepare($sql2);
               $sql2->bindValue(1,$id_tabla);
               $sql2->bindValue(2,$esf);
               $sql2->bindValue(3,$cil);
               $sql2->execute();

               $resultads= $sql2->fetchColumn();
               $new_result = explode(',',$resultads); 
               $new_result[0] !='' ? $n_lente = $new_result[0] : $n_lente = '0'; 
               isset($new_result[1]) ? $codigo = "$new_result[1]" : $codigo = '';
               $id_td = "term_".$ident_tabla."_".$id."";

               $html .= '<td class="stilot1" id="term_'.$ident_tabla.'_'.$id.'" onClick="getDataIngresoModal(\''.$esf.'\',\''.$cil.'\',\''.$codigo.'\',\''.$marca.'\',\''.$diseno.'\',\''.$nombre.'\',\''.$id_td.'\',\''.$ident_tabla.'\')" data-toggle="tooltip" title="Esfera: '.$esf.' * Cilindro: '.$cil.'">'.$n_lente.'</td>';               
               if($cil==($min_cil)){                  	        	
               	  $html .= "</tr>";
               }

               $id++;
   	        } 

	}
	$html .= "</tbody>";
	$html .= "</table>";
    echo $html;
}

public function comprobarExisteCodigo($codigo){
	$conectar=parent::conexion();
    parent::set_names();
    $sql = "select * from stock_terminados where codigo=?";
    $sql = $conectar->prepare($sql);
    $sql->bindValue(1, $codigo);
    $sql->execute();
    return $resultado=$sql->fetchAll(PDO::FETCH_ASSOC);
}

public function codigoGrad($esfera,$cilindro,$id_td,$codigo){
	$conectar=parent::conexion();
    parent::set_names();
    $sql = "select identificador,esfera,cilindro from stock_terminados where identificador=? and esfera=? and cilindro=? and codigo=?;";
    $sql = $conectar->prepare($sql);
    $sql->bindValue(1, $id_td);
    $sql->bindValue(2, $esfera);
    $sql->bindValue(3, $cilindro);
    $sql->bindValue(4, $codigo);
    $sql->execute();
    return $resultado=$sql->fetchAll(PDO::FETCH_ASSOC);
}

public function initStockTerm($codigoProducto,$cantidad,$id_tabla,$esfera,$cilindro,$id_td,$cat_codigo,$usuario){
	$conectar=parent::conexion();
    parent::set_names();
    date_default_timezone_set('America/El_Salvador'); 
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");
    $stock_min = '-';
    $tipo_lente = "Terminado";
    $sql = "insert into stock_terminados values(?,?,?,?,?,?,?)";
    $sql = $conectar->prepare($sql);
    $sql->bindValue(1, $codigoProducto);
    $sql->bindValue(2, $id_td);
    $sql->bindValue(3, $id_tabla);
    $sql->bindValue(4, $esfera);
    $sql->bindValue(5, $cilindro);
    $sql->bindValue(6, $stock_min);
    $sql->bindValue(7, $cantidad);
    $sql->execute();
    
    /////////////////////REGISTRAR MOVIMIENTO 
    $accion = "Ingreso";
    $sql2 = "insert into movimientos_bodega values(null,?,?,?,?,?,?,?,?);";
    $sql2 = $conectar->prepare($sql2);
    $sql2->bindValue(1, $codigoProducto);
    $sql2->bindValue(2, $esfera);
    $sql2->bindValue(3, $cilindro);
    $sql2->bindValue(4, $cantidad);
    $sql2->bindValue(5, $fecha);
    $sql2->bindValue(6, $hora);
    $sql2->bindValue(7, $usuario);
    $sql2->bindValue(8, $accion);
    $sql2->execute();
    

        
}

public function updateStockTerm($codigoProducto,$cantidad,$id_tabla,$esfera,$cilindro,$id_td,$usuario){

    $conectar=parent::conexion();
    parent::set_names();

    $sql = "select stock from stock_terminados where codigo=? and id_tabla_term=? and esfera=? and cilindro=?;";
    $sql = $conectar->prepare($sql);
    $sql->bindValue(1, $codigoProducto);
    $sql->bindValue(2, $id_tabla);
    $sql->bindValue(3, $esfera);
    $sql->bindValue(4, $cilindro);
    $sql->execute();
    $existencias = $sql->fetchAll(PDO::FETCH_ASSOC);

    foreach ($existencias as $key) {
    	$stock_act = $key['stock'];
    }

    $new_stock = $stock_act+$cantidad;

    $sql2 = "update stock_terminados set stock=? where codigo=? and esfera=? and cilindro=? and id_tabla_term=?;";
    $sql2 = $conectar->prepare($sql2);
    $sql2->bindValue(1, $new_stock);
    $sql2->bindValue(2, $codigoProducto);
    $sql2->bindValue(3, $esfera);
    $sql2->bindValue(4, $cilindro);
    $sql2->bindValue(5, $id_tabla);
    $sql2->execute();


    /////////////////////REGISTRAR MOVIMIENTO 
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");
    $accion = "Ingreso";
    $sql2 = "insert into movimientos_bodega values(null,?,?,?,?,?,?,?,?);";
    $sql2 = $conectar->prepare($sql2);
    $sql2->bindValue(1, $codigoProducto);
    $sql2->bindValue(2, $esfera);
    $sql2->bindValue(3, $cilindro);
    $sql2->bindValue(4, $cantidad);
    $sql2->bindValue(5, $fecha);
    $sql2->bindValue(6, $hora);
    $sql2->bindValue(7, $usuario);
    $sql2->bindValue(8, $accion);
    $sql2->execute();

    }

  /*======================GET DATA NEW STOCK ITEM ================*/
  public function newStockTerminados($codigoProducto,$id_tabla,$id_td){
  	$conectar=parent::conexion();
    parent::set_names();
    $sql="select stock,codigo from stock_terminados where codigo=? and id_tabla_term=? and identificador=?;";
    $sql = $conectar->prepare($sql);
    $sql->bindValue(1, $codigoProducto);
    $sql->bindValue(2, $id_tabla);
    $sql->bindValue(3, $id_td);
    $sql->execute();
    return $resultado=$sql->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getDataTerminados($codigoProducto){    
    $conectar=parent::conexion();
    parent::set_names();
    $sql = 'select t.id_tabla_term,t.marca,t.diseno,s.esfera,s.cilindro,s.stock,s.codigo from tablas_terminado as t inner join stock_terminados as s on t.id_tabla_term=s.id_tabla_term where s.codigo=?';
    $sql = $conectar->prepare($sql);
    $sql->bindValue(1, $codigoProducto);
    $sql->execute();
    return $resultado=$sql->fetchAll(PDO::FETCH_ASSOC);
  }

  public function verificarCodigo($codigo){
    $conectar=parent::conexion();
    parent::set_names();
    $sql = "select codigo from stock_terminados where codigo = ?;";
    $sql = $conectar->prepare($sql);
    $sql->bindValue(1, $codigo);
    $sql->execute();
    return $resultado=$sql->fetchAll(PDO::FETCH_ASSOC);
  }

  public function registroMultiple(){
    $conectar=parent::conexion();
    parent::set_names();

    $productosTerm = array();
    $productosTerm = json_decode($_POST["lentesUpdate"]);

    foreach ($productosTerm as $key => $value) {
        $cantidad = $value->cantidad;
        $codigo = $value->codigo;
        $cilindro = $value->cilindro;
        $esfera = $value->esfera;    
    /////////// GET STOCK ACTUAL ///////////
    $sql = "select stock from stock_terminados where codigo=? and esfera=? and cilindro=?";
    $sql = $conectar->prepare($sql);
    $sql->bindValue(1,$codigo);
    $sql->bindValue(2,$esfera);
    $sql->bindValue(3,$cilindro);
    $sql->execute();    
    $stock =$sql->fetchColumn();
    $nuevo_stock = $stock+$cantidad;

    $update = "update stock_terminados set stock=? where codigo=? and esfera=? and cilindro=?;";
    $update = $conectar->prepare($update);
    $update->bindValue(1,$nuevo_stock);
    $update->bindValue(2,$codigo);
    $update->bindValue(3,$esfera);
    $update->bindValue(4,$cilindro);
    $update->execute();
    }//Fin foreach
  }

  /*----------------------  DATA STOCK BASES  --------------------*/
  public function getTablesBases($marca){
    $conectar=parent::conexion();
    parent::set_names();

    $sql = 'select id_tabla_base,titulo,diseno from tablas_base where marca=? and diseno="vs";';
    $sql = $conectar->prepare($sql);
    $sql->bindValue(1,$marca);
    $sql->execute();
    $resultado=$sql->fetchAll(PDO::FETCH_ASSOC);

    $tam_array = count($resultado);
    $html = "";
    $count_tr = 0;    

    foreach ($resultado as $value) {
        if ($count_tr==0) { $html .= "</tr>"; } 
        $count_tr++;
        $id_tabla = $value["id_tabla_base"];
        $titulo = $value["titulo"];
        $diseno = $value["diseno"];

        $sql2 = "select graduacion from grad_tablas_base where id_tabla_base=?;";
        $sql2 = $conectar->prepare($sql2);
        $sql2->bindValue(1,$id_tabla);
        $sql2->execute();
        $graduaciones = $sql2->fetchAll(PDO::FETCH_ASSOC);
        $grads = array();
        foreach ($graduaciones as  $value) {
           array_push($grads,$value["graduacion"]);
        }
        asort($grads);
       $html .= "<td colspan=25 style='width:25%;vertical-align:top;border: 1px solid black;'><table width='100%' class='table-bordered".$titulo."'><tr><td colspan='100' class='bg-dark' style='text-align: center;font-size:12px'>".$titulo."</td></tr>
        <tr class='style_th'><th colspan='50'>Base</th><th colspan='50'>Stock</th></tr>";
        $id=1;
        foreach ($grads as $key) {

         $sql3 = "select stock,codigo from stock_bases where id_tabla_base=? and base=?;";
         $sql3 = $conectar->prepare($sql3);
         $sql3->bindValue(1,$id_tabla);
         $sql3->bindValue(2,$key);
         $sql3->execute();
         $existencias = $sql3->fetchAll(PDO::FETCH_ASSOC);

         if (is_array($existencias)==true and count($existencias)>0) {             
            foreach ($existencias as $v) {
               $stock = $v['stock'];
               $codigo = $v['codigo'];
            }
         }else{
            $stock=0;
            $codigo ='';
         }
         $id_td = 'base_'.$id_tabla."_".$id;
         $html .= "<tr class='filasb'><td colspan='50' style='text-align: center;cursor: pointer;' onClick='initStockBasesvs(\"".$key."\",\"".$codigo."\",".$id_tabla.",\"".$marca."\",\"".$diseno."\",\"".$id_td."\");'>".$key."</td><td colspan='50' id=".$id_td." style='text-align: center;cursor: pointer;' onClick='initStockBasesvs(\"".$key."\",\"".$codigo."\",".$id_tabla.",\"".$marca."\",\"".$diseno."\",\"".$id_td."\");'>".$stock."</td></tr>";

         $id++; 

        }
        $html .= "</td></table>";
        if ($count_tr==4 or $tam_array==0) {
            $html .="</tr>";
            $count_tr=0;
        }

        $tam_array = $tam_array-1; 
    }
    
    echo $html;

  }

/*-------------------- LISTAR BASES CON ADICION BIFOCALES(FTOP)-----------------------*/
  public function getTablesBasesFlaptop($marca){
      $conectar=parent::conexion();
      parent::set_names();

      $sql="select id_tabla_base,marca,titulo,diseno from tablas_base where marca = ? and diseno='bf' order by marca asc;";
      $sql = $conectar->prepare($sql);
      $sql->bindValue(1, $marca);
      $sql->execute();
      return $resultado=$sql->fetchAll(PDO::FETCH_ASSOC);

  }

/*-------------------- FIN LISTAR BASES CON ADICION BIFOCALES(FTOP)--------------------*/

/////////////////  INVENTARIO DE BASES /////////////////
public function comprobarExistebasevs($codigo,$identificador,$base){
    $conectar=parent::conexion();
    parent::set_names();
    $sql = "select codigo from stock_bases where codigo=? and identificador=? and base=?;";
    $sql = $conectar->prepare($sql);
    $sql->bindValue(1, $codigo);
    $sql->bindValue(2, $identificador);
    $sql->bindValue(3, $base);
    $sql->execute();
    return $resultado=$sql->fetchAll(PDO::FETCH_ASSOC);
}

public function inicializarStockBasesVs($codigo,$identificador,$base,$cantidad,$id_tabla,$cat_codigo){
    $conectar=parent::conexion();
    parent::set_names();
    $stock_min = "";
    $sql = "insert into stock_bases values(?,?,?,?,?,?)";
    $sql = $conectar->prepare($sql);
    $sql->bindValue(1, $codigo);
    $sql->bindValue(2, $identificador);
    $sql->bindValue(3, $base);
    $sql->bindValue(4, $stock_min);
    $sql->bindValue(5, $cantidad);
    $sql->bindValue(6, $id_tabla);
    $sql->execute();

    $tipo_lente = "Base";
    $sql2 = "insert into codigos_lentes values(null,?,?,?);";
    $sql2 = $conectar->prepare($sql2);
    $sql2->bindValue(1, $codigo);
    $sql2->bindValue(2, $identificador);
    $sql2->bindValue(3, $tipo_lente);
    $sql2->execute();

}

public function updateStockBasesVs($codigoProducto,$cantidad,$base,$id_tabla,$id_td){

    $conectar=parent::conexion();
    parent::set_names();

    $sql = "select stock from stock_bases where codigo=? and base=? and id_tabla_base=?;";
    $sql = $conectar->prepare($sql);
    $sql->bindValue(1, $codigoProducto);
    $sql->bindValue(2, $base);
    $sql->bindValue(3, $id_tabla);
    $sql->execute();
    $existencias = $sql->fetchAll(PDO::FETCH_ASSOC);

    foreach ($existencias as $key) {
        $stock_act = $key['stock'];
    }

    $new_stock = $stock_act+$cantidad;

    $sql2 = "update stock_bases set stock=? where codigo=? and base=? and id_tabla_base=?;";
    $sql2 = $conectar->prepare($sql2);
    $sql2->bindValue(1, $new_stock);
    $sql2->bindValue(2, $codigoProducto);
    $sql2->bindValue(3, $base);
    $sql2->bindValue(4, $id_tabla);
    $sql2->execute();    

}

  /*======================GET DATA NEW STOCK ITEM BASE VISION SENCILLA================*/
  public function newStockBaseVs($codigo,$base,$id_td){
    $conectar=parent::conexion();
    parent::set_names();
    $sql="select stock,codigo from stock_bases where codigo=? and base=? and identificador=?;";
    $sql = $conectar->prepare($sql);
    $sql->bindValue(1, $codigo);
    $sql->bindValue(2, $base);
    $sql->bindValue(3, $id_td);
    $sql->execute();
    return $resultado=$sql->fetchAll(PDO::FETCH_ASSOC);
  }

/*============= REGISTRAR DESCARGO DE LENTES ==================*/
public function validarExisteOrdenDescargos($codigo){
    $conectar=parent::conexion();
    parent::set_names();

    $sql = "select codigo_orden from descargos where codigo_orden=?;";
    $sql = $conectar->prepare($sql);
    $sql->bindValue(1, $codigo);
    $sql->execute();
    return $resultado=$sql->fetchAll(PDO::FETCH_ASSOC);
}

public function registrarDescargo(){
    $conectar=parent::conexion();
    parent::set_names();
    $paciente = $_POST["paciente"];
    $codigo_orden = $_POST["codigo_orden"];
    $id_optica = $_POST["id_optica"];
    $id_sucursal = $_POST["id_sucursal"];
    $id_usuario = $_POST["id_usuario"];
    $itemsDescargos = array();
    $itemsDescargos = json_decode($_POST["arrayItemsDescargo"]);

    foreach ($itemsDescargos as $key => $value) {
        $codigo = $value->codigo;
        $medidas = $value->medidas;
        $ojo = $value->ojo;
        $tipo_lente = $value->tipo_lente;

    $sql = "insert into descargos values(null,?,?,?,?,?,?,?,?,?,?);";
    $sql = $conectar->prepare($sql);
    $sql->bindValue(1, parent::fechas());
    $sql->bindValue(2, $tipo_lente);
    $sql->bindValue(3, $codigo);
    $sql->bindValue(4, $ojo);
    $sql->bindValue(5, $paciente);
    $sql->bindValue(6, $medidas);
    $sql->bindValue(7, $codigo_orden);
    $sql->bindValue(8, $id_optica);
    $sql->bindValue(9, $id_sucursal);
    $sql->bindValue(10, $id_usuario);
    $sql->execute();

    if ($tipo_lente=="Base"){
        $sql2 = "select stock from stock_bases where codigo=?;";
        $sql2 = $conectar->prepare($sql2);
        $sql2->bindValue(1, $codigo);
        $sql2->execute();
        $stock =$sql2->fetchColumn();
        $nuevo_stock = $stock-1;

        $set_stock = "update stock_bases set stock=? where codigo=?;";
        $set_stock = $conectar->prepare($set_stock);
        $set_stock->bindValue(1,$nuevo_stock);
        $set_stock->bindValue(2,$codigo);
        $set_stock->execute();

    }elseif($tipo_lente=="Terminado"){
        $sql2 = "select stock from stock_terminados where codigo=?;";
        $sql2 = $conectar->prepare($sql2);
        $sql2->bindValue(1, $codigo);
        $sql2->execute();
        $stock = $sql2->fetchColumn();
        $nuevo_stock = $stock-1;

        $set_stock = "update stock_terminados set stock=? where codigo=?;";
        $set_stock = $conectar->prepare($set_stock);
        $set_stock->bindValue(1,$nuevo_stock);
        $set_stock->bindValue(2,$codigo);
        $set_stock->execute();
        
    }elseif($tipo_lente=="Base Flaptop"){
        $sql2 = "select stock from stock_bases_adicion where codigo=?;";
        $sql2 = $conectar->prepare($sql2);
        $sql2->bindValue(1, $codigo);
        $sql2->execute();
        $stock = $sql2->fetchColumn();
        $nuevo_stock = $stock-1;

        $set_stock = "update stock_bases_adicion set stock=? where codigo=?;";
        $set_stock = $conectar->prepare($set_stock);
        $set_stock->bindValue(1,$nuevo_stock);
        $set_stock->bindValue(2,$codigo);
        $set_stock->execute();
    }

    }#Fin foreach
    date_default_timezone_set('America/El_Salvador');$hoy = date("d-m-Y H:i:s");
    $accion = "Despacho de Bodega";
    $sql3 = "insert into acciones_orden values(null,?,?,?,?,?);";
    $sql3 = $conectar->prepare($sql3);
    $sql3->bindValue(1, $codigo_orden);
    $sql3->bindValue(2, $hoy);
    $sql3->bindValue(3, $accion);
    $sql3->bindValue(4, "");
    $sql3->bindValue(5, $id_usuario);
    $sql3->execute();

}/*---------Fin registrar descargo----------*/

public function listadoDiarioDescargos(){
    $conectar=parent::conexion();
    parent::set_names();
    date_default_timezone_set('America/El_Salvador'); 
    $hoy = date("d-m-Y H:i:s");

    $sql = "select d.id_descargo,d.codigo_orden,d.fecha,d.paciente,d.ojo,d.tipo_lente,d.medidas,o.nombre,s.nombre_sucursal,d.codigo_lente from descargos as d INNER join optica as o on d.id_optica=o.id_optica inner JOIN sucursal_optica as s on o.id_optica=s.id_sucursal";
    $sql=$conectar->prepare($sql);
    $sql->execute();
    return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);

}

public function getTablesBasesFtop($id_tabla,$marca,$diseno){
    $conectar=parent::conexion();
    parent::set_names();

    $sql = "select graduacion from grad_tablas_base where id_tabla_base=?;";
    $sql = $conectar->prepare($sql);
    $sql->bindValue(1,$id_tabla);
    $sql->execute();
    $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
    $grads_tabla = array();
    foreach ($resultado as $grad) {
        array_push($grads_tabla, $grad["graduacion"]);

    }
    sort($grads_tabla);
    $html =  '<table width="100%"  class="table-bordered" style="text-align: center;font-size:14px">';
    $html .= '<thead class="style_th bg-dark" style="color: white">';
    $html .= "<th>Base\Add</th>";

    for($a = 1;$a <=3; $a+=0.25){
      $html .= "<th>".number_format($a,2,'.',',')."</th>";
    }
    $html .= "<thead>";
    $contador = 1;
    foreach ($grads_tabla as $key) {#Recorremos las bases
        $b = number_format($key,2,'.',',');
        $b > 0 ? $base = '+'.number_format($b,2,'.',',') : $base = number_format($b,2,'.',',');
        $cont = 0;
        for ($o = 0; $o < 2;$o++) {
            ($cont % 2 == 0) ? $ojo='R' : $ojo = 'L';
            ($cont % 2 == 0) ? $ojo_lente='Derecho' : $ojo_lente = 'Izquierdo';   
            $html .= "<tr class='filasb'>";
            $html .= "<td>".$base."-".$ojo."</td>";
            
            for ($a = 1;$a <=3; $a+=0.25) {                

                $a > 0 ? $adicion = '+'.number_format($a,2,'.',',') : $adicion = number_format($a,2,'.',',');
                $sql2 = "select CONCAT(stock,',',codigo) as data from stock_bases_adicion where base=? and adicion=? and id_tabla_base=? and ojo=?;";
                $sql2 = $conectar->prepare($sql2);
                $sql2->bindValue(1,$base);
                $sql2->bindValue(2,$adicion);
                $sql2->bindValue(3,$id_tabla);
                $sql2->bindValue(4,$ojo_lente);
                $sql2->execute();
                $resultads= $sql2->fetchColumn();
                /////////////////////////////////////
                $new_result = explode(',',$resultads); 
                $new_result[0] !='' ? $stock = $new_result[0] : $stock = '0'; 
                isset($new_result[1]) ? $codigo = $new_result[1] : $codigo = '';
                $id_td = "td_ftop_".$contador.$id_tabla.$a.$key."";
                $html .= "<td style='text-align: center;cursor: pointer;' onClick='getDataModalFtop(\"".$codigo."\",\"".$marca."\",\"".$base."\",\"".$adicion."\",\"".$ojo_lente."\",\"".$id_td."\",".$id_tabla.",\"".$diseno."\")' data-toggle='tooltip' title='Base: ".$base." * Add: ".$adicion." ".$ojo_lente."' id='".$id_td."' class='class-bf-td".$id_tabla."'>".$stock."</td>";
                $contador++;
            }
            $html .= "</tr>";
            $cont++;
        }
       
    }

    $html .= "</table>";    
    echo $html;

}

/////////////////  INVENTARIO DE BASES CON ADICION   /////////////////
public function comprobarExistebasevsftop($codigo,$identificador,$base,$adicion,$ojo){
    $conectar=parent::conexion();
    parent::set_names();
    $sql = "select codigo from stock_bases_adicion where codigo=? and identificador=? and base=? and adicion=? and ojo=?;";
    $sql = $conectar->prepare($sql);
    $sql->bindValue(1, $codigo);
    $sql->bindValue(2, $identificador);
    $sql->bindValue(3, $base);
    $sql->bindValue(4, $adicion);
    $sql->bindValue(5, $ojo);
    $sql->execute();
    return $resultado=$sql->fetchAll(PDO::FETCH_ASSOC);
}

public function inicializarStockBasesFtop($codigo,$identificador,$base,$adicion,$cantidad,$ojo,$id_tabla){
    $conectar=parent::conexion();
    parent::set_names();
    $stock_min = "";
    $sql = "insert into stock_bases_adicion values(null,?,?,?,?,?,?,?,?)";
    $sql = $conectar->prepare($sql);
    $sql->bindValue(1, $codigo);
    $sql->bindValue(2, $identificador);
    $sql->bindValue(3, $base);
    $sql->bindValue(4, $adicion);
    $sql->bindValue(5, $stock_min);
    $sql->bindValue(6, $cantidad);
    $sql->bindValue(7, $ojo);
    $sql->bindValue(8, $id_tabla);
    $sql->execute();

    $tipo_lente = "Base Flaptop";
    $sql2 = "insert into codigos_lentes values(null,?,?,?);";
    $sql2 = $conectar->prepare($sql2);
    $sql2->bindValue(1, $codigo);
    $sql2->bindValue(2, $identificador);
    $sql2->bindValue(3, $tipo_lente);
    $sql2->execute();
}

public function newStockBaseFtp($codigo,$base,$adicion,$id_tabla,$id_td){
    $conectar=parent::conexion();
    parent::set_names();
    $sql="select stock from stock_bases_adicion where codigo=? and base=? and adicion=? and id_tabla_base=? and identificador=?;";
    $sql = $conectar->prepare($sql);
    $sql->bindValue(1, $codigo);
    $sql->bindValue(2, $base);
    $sql->bindValue(3, $adicion);
    $sql->bindValue(4, $id_tabla);
    $sql->bindValue(5, $id_td);
    $sql->execute();
    return $resultado=$sql->fetchAll(PDO::FETCH_ASSOC);
}

public function updateStockBasesFtop($codigo,$identificador,$base,$adicion,$cantidad,$ojo,$id_tabla){
   $conectar = parent::conexion();
   parent::set_names();

   $sql = "select stock from stock_bases_adicion where codigo=? and base=? and adicion=? and ojo=? and id_tabla_base=?;";
    $sql = $conectar->prepare($sql);
    $sql->bindValue(1, $codigo);
    $sql->bindValue(2, $base);
    $sql->bindValue(3, $adicion);
    $sql->bindValue(4, $ojo);
    $sql->bindValue(5, $id_tabla);
    $sql->execute();
    $existencias = $sql->fetchAll(PDO::FETCH_ASSOC);

    foreach ($existencias as $key) {
        $stock_act = $key['stock'];
    }

    $new_stock = $stock_act+$cantidad;

    $sql2 = "update stock_bases_adicion set stock=? where codigo=? and base=? and adicion=? and ojo=? and id_tabla_base=?;";
    $sql2 = $conectar->prepare($sql2);
    $sql2->bindValue(1, $new_stock);
    $sql2->bindValue(2, $codigo);
    $sql2->bindValue(3, $base);
    $sql2->bindValue(4, $adicion);
    $sql2->bindValue(5, $ojo);
    $sql2->bindValue(6, $id_tabla);
    $sql2->execute();     
}


public function descargoStockTerm($codigo,$cant_descargo,$esfera,$cilindro,$usuario){
    $conectar = parent::conexion();
    parent::set_names();
    $sql2 = "select stock from stock_terminados where codigo=? and esfera=? and cilindro=?;";
    $sql2 = $conectar->prepare($sql2);
    $sql2->bindValue(1, $codigo);
    $sql2->bindValue(2, $esfera);
    $sql2->bindValue(3, $cilindro);
    $sql2->execute();
    $stock = $sql2->fetchColumn();
    $nuevo_stock = $stock-$cant_descargo;

    $set_stock = "update stock_terminados set stock=? where codigo=? and esfera=? and cilindro=?;";
    $set_stock = $conectar->prepare($set_stock);
    $set_stock->bindValue(1,$nuevo_stock);
    $set_stock->bindValue(2,$codigo);
    $set_stock->bindValue(3, $esfera);
    $set_stock->bindValue(4, $cilindro);
    $set_stock->execute();

    ///////////////////// REGISTRAR MOVIMIENTO ///
    date_default_timezone_set('America/El_Salvador'); 
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");
    $accion = "Descargo";
    $sql2 = "insert into movimientos_bodega values(null,?,?,?,?,?,?,?,?);";
    $sql2 = $conectar->prepare($sql2);
    $sql2->bindValue(1, $codigo);
    $sql2->bindValue(2, $esfera);
    $sql2->bindValue(3, $cilindro);
    $sql2->bindValue(4, $cant_descargo);
    $sql2->bindValue(5, $fecha);
    $sql2->bindValue(6, $hora);
    $sql2->bindValue(7, $usuario);
    $sql2->bindValue(8, $accion);
    $sql2->execute();
}

  public function listarDescargosBodega(){
    $conectar= parent::conexion();
    $sql= "select*from movimientos_bodega where tipo_movimiento='Ingreso' order by id_movimiento DESC;";
    $sql=$conectar->prepare($sql);
    $sql->execute();
    return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);
  }

}///////////FIN DE LA CLASE

?>


