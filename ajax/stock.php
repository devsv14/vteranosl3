<?php

require_once("../config/conexion.php");
//llamada al modelo categoria
require_once("../modelos/Stock.php");

$stock = new Stock();

switch ($_GET["op"]){

	case 'get_tableTerm':
		$datos = $stock->getTableTerminados($_POST["id_tabla"]); 
	break;

	case 'update_stock_terminados':

	if($_POST["operacion"]=="Ingreso"){

	    $codigo = $stock->comprobarExisteCodigo($_POST['codigoProducto']);
	    $codigo_grad = $stock->codigoGrad($_POST['esf'],$_POST['cil'],$_POST['id_td'],$_POST["codigoProducto"]);

	    if (is_array($codigo)==true and count($codigo)==0 and is_array($codigo_grad)==true and count($codigo_grad)==0) {    	
	    $stock->initStockTerm($_POST['codigoProducto'],$_POST['cantidad_ingreso'],$_POST['id_tabla'],$_POST['esf'],$_POST['cil'],$_POST['id_td'],$_POST["cat_codigo"],$_POST["usuario"]);
	    	$mensaje = "insertar";
	    }elseif(is_array($codigo)==true and count($codigo)>0 and is_array($codigo_grad)==true and count($codigo_grad)>0){
	    	$stock->updateStockTerm($_POST['codigoProducto'],$_POST['cantidad_ingreso'],$_POST['id_tabla'],$_POST['esf'],$_POST['cil'],$_POST['id_td'],$_POST["usuario"]);
	    	$mensaje = "Editar";
	    }elseif (is_array($codigo)==true and count($codigo)>0 and is_array($codigo_grad)==true and count($codigo_grad)==0) {
	    	foreach ($codigo as $value) {
	    		$mensaje = "Este codigo existe en: Esfera ".$value["esfera"].", Cilindro ".$value["cilindro"];
	    	}
	    	//$mensaje="error";
	    }
        echo json_encode($mensaje);
    }elseif($_POST["operacion"]=="Descargo"){
    	$stock->descargoStockTerm($_POST['codigoProducto'],$_POST["cant_descargo"],$_POST['esf'],$_POST['cil'],$_POST["usuario"]);
	    	$mensaje = "descargos";
	    echo json_encode($mensaje);
    }

	break;

	case 'new_stock_terminados':
		$data=$stock->newStockTerminados($_POST['codigoProducto'],$_POST['id_tabla'],$_POST['id_td']);
		if (is_array($data)==true and count($data)>0) {
        	foreach ($data as $key) {
        		$output["stock"]=$key["stock"];
        		$output["codigo"]=$key["codigo"];
        	}
        }
        echo json_encode($output);
	break;

	case 'getDataTerminados':
	    $data_codigo = $stock->verificarCodigo($_POST['codigoTerminado']);
		$data = $stock->getDataTerminados($_POST['codigoTerminado']);
        if(is_array($data_codigo)==true and count($data_codigo)>0){
		    if(is_array($data)==true and count($data)>0){
			foreach ($data as $v) {
				$output["marca"] = $v["marca"];
            	$output["diseno"] = $v["diseno"];
                $output["cilindro"] = $v["cilindro"];
            	$output["esfera"] = $v["esfera"];
            	$output["stock"] = $v["stock"];
            	$output["codigo"] = $v["codigo"];
            }
		}
	    }else{
		    	$output = "Vacio";
		}
        echo json_encode($output);
		break;

	case 'registroMultiple':
		$stock->registroMultiple();
		$message = "Ok";
		echo json_encode($message);	
	break;
/////////////////////////  BASES ////////////////
	case 'get_tableBaseVs':
		$datos = $stock->getTablesBases($_POST["marca"]); 
	break;

	case 'update_stock_basevs':
	// Comprobar si existe lente en inventario ///////
	$codigo = $stock->comprobarExistebasevs($_POST["codigoProducto"],$_POST["id_td"],$_POST["base"]);
	if (is_array($codigo)==true and count($codigo)==0) {
		$stock->inicializarStockBasesVs($_POST["codigoProducto"],$_POST["id_td"],$_POST["base"],$_POST["cantidad"],$_POST["id_tabla"],$_POST["cat_codigo"]);
		$mensaje = "Insert";
	}else{
		$stock->updateStockBasesVs($_POST["codigoProducto"],$_POST["cantidad"],$_POST["base"],$_POST["id_tabla"],$_POST["id_td"]);
		$mensaje = "Edit";
	}

	echo json_encode($mensaje);

	break;

	case 'new_stock_basevs':
	$data=$stock->newStockBaseVs($_POST['codigo'],$_POST['base'],$_POST['id_td']);
	if (is_array($data)==true and count($data)>0) {
        foreach ($data as $key) {
        	$output["stock"]=$key["stock"];
        }
    }
    echo json_encode($output);
	break;

	case 'registrar_descargo':
	$validar_codigo = $stock->validarExisteOrdenDescargos($_POST["codigo_orden"]);
	    if (is_array($validar_codigo)==true and count($validar_codigo)==0) {
	    	$stock->registrarDescargo();
		    $message = "Ok";		
	    }else{
	    	$message = "Error";
	    }
	echo json_encode($message);
	break;

case 'listar_descargos':
  $datos = $stock->listadoDiarioDescargos();
  $data = Array();
  foreach ($datos as $row) { 
  $sub_array = array();

  $sub_array[] = $row["id_descargo"];  
  $sub_array[] = $row["codigo_orden"];  
  $sub_array[] = $row["fecha"];
  $sub_array[] = $row["paciente"];   
  $sub_array[] = $row["nombre"];
  $sub_array[] = $row["ojo"];
  $sub_array[] = $row["tipo_lente"];
  $sub_array[] = $row["medidas"];
  $sub_array[] = $row["codigo_lente"];
  $data[] = $sub_array;
  }
  
  $results = array(
      "sEcho"=>1, //Información para el datatables
      "iTotalRecords"=>count($data), //enviamos el total registros al datatable
      "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
      "aaData"=>$data);
    echo json_encode($results);
  break;

/*============================== BASES BIFOCALES ========================*/
case 'get_tableBaseFlaptop':
	$datos = $stock->getTablesBasesFtop($_POST["id_tabla"],$_POST["marca"],$_POST["diseno"]);
break;

case 'update_stock_baseftop':

	$codigo = $stock->comprobarExistebasevsftop($_POST["codigoProducto"],$_POST["identificador"],$_POST["base"],$_POST["adicion"],$_POST["ojo"]);
	
	if (is_array($codigo)==true and count($codigo)==0) {
		$stock->inicializarStockBasesFtop($_POST["codigoProducto"],$_POST["identificador"],$_POST["base"],$_POST["adicion"],$_POST["cantidad"],$_POST["ojo"],$_POST["id_tabla"]);
        $mensaje = "Insert";
    }else{
    	$stock->updateStockBasesFtop($_POST["codigoProducto"],$_POST["identificador"],$_POST["base"],$_POST["adicion"],$_POST["cantidad"],$_POST["ojo"],$_POST["id_tabla"]);
        $mensaje = "Edit";
    }
    echo json_encode($mensaje);
	
	break;


	case 'new_stock_base_ftp':
	$data=$stock->newStockBaseFtp($_POST['codigoProducto'],$_POST['base'],$_POST['adicion'],$_POST['id_tabla'],$_POST['id_td']);
	if (is_array($data)==true and count($data)>0) {
        foreach ($data as $key) {
        	$output["stock"]=$key["stock"];
        }
    }
    echo json_encode($output);
	break;

	case 'listar_descargos_bodega':
    $datos = $stock->listarDescargosBodega();

	foreach ($datos as $row) { 
	  $sub_array = array();

	  $sub_array[] = $row["id_movimiento"];
	  $sub_array[] = $row["fecha"]." ".$row["hora"];
	  $sub_array[] = $row["usuario"];
	  $sub_array[] = $row["codigo"];
	  $sub_array[] = $row["esfera"];
	  $sub_array[] = $row["cilindro"];
	  $sub_array[] = $row["cantidad"];
	  $sub_array[] = $row["tipo_movimiento"];            
	                                                
	  $data[] = $sub_array;
	  }
	  
  $results = array(
      "sEcho"=>1, //Información para el datatables
      "iTotalRecords"=>count($data), //enviamos el total registros al datatable
      "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
      "aaData"=>$data);
    echo json_encode($results);
  break;

}