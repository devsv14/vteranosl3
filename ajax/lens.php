<?php

require_once("../config/conexion.php");
//llamada al modelo categoria
require_once("../modelos/Orders.php");

$orders = new Ordenes();

switch ($_GET["op"]){

	case 'get_orders_received':
	$datos = $orders->get_ordenes_received();
	$data = Array();
	$tit = "Recibir";

  	foreach ($datos as $row) {

  		if($row["tipo_lente"]=="Visión Sencilla"){
  			$tipo_lente = "Single vision";
  		}else{
  			$tipo_lente =$row["tipo_lente"];
  		}

        $od_esferas = ($row["od_esferas"]=="-" or $row["od_esferas"]=="")? '': "<span style='color:black'><b>SPH.</b> </span>".$row["od_esferas"];
        $od_cilindro = ($row["od_cilindros"]=="-" or $row["od_cilindros"]=="")? '': "<span style='color:black'><b>CYL.</b> </span>".$row["od_cilindros"];
        $od_eje = ($row["od_eje"]=="-" or $row["od_eje"]=="")? '': "<span style='color:black'><b>AX.</b> </span>".$row["od_eje"];
        $od_add = ($row["od_adicion"]=="-" or $row["od_adicion"]=="")? '': "<span style='color:blue'>ADD. </span>".$row["od_adicion"];
        //////////////////////////////////////////////////;//////////////////////////////////////////////////////////////////////
        $oi_esferas = ($row["oi_esferas"]=="-" or $row["oi_esferas"]=="")? '': "<span style='color:black'><b>SPH.</b> </span>".$row["oi_esferas"];
        $oi_cilindro = ($row["oi_cilindros"]=="-" or $row["oi_cilindros"]=="")? '': "<span style='color:black'><b>CYL.</b> </span>".$row["oi_cilindros"];
        $oi_eje = ($row["oi_eje"]=="-" or $row["oi_eje"]=="")? '': "<span style='color:black'><b>AX.</b> </span>".$row["oi_eje"];
        $oi_add = ($row["oi_adicion"]=="-" or $row["oi_adicion"]=="")? '': "<span style='color:blue'>ADD. </span>".$row["oi_adicion"];
        ///////////////////////////   
  	  	$sub_array = array();
		$sub_array[] = $row["id_orden"];  
		$sub_array[] = '<div data-toggle="tooltip" title="Date received: '.$row["fecha"].'" style="text-align:center"><input type="checkbox" class="form-check-input received_item" value="'.$row["id_orden"].'" name="'.$row["paciente"].'" id="'.$row["codigo"].'" style="text-align: center"><span style="color:white">.</span></div>';
		$sub_array[] = $od_esferas." ".$od_cilindro." ".$od_eje." ".$od_add;
		$sub_array[] = $oi_esferas." ".$oi_cilindro." ".$oi_eje." ".$oi_add;
		$sub_array[] = $tipo_lente;    
    	$data[] = $sub_array;
	}
	
	$results = array(
 		"sEcho"=>1, //Información para el datatables
 		"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 		"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 		"aaData"=>$data);
 	echo json_encode($results);
		break;

    case 'get_data_array_received':
       $data = $orders->get_data_array_received($_POST["code"]);
        if (is_array($data)==true and count($data)>0) {
        	foreach($data as $row){
        		if($row["tipo_lente"]=="Visión Sencilla"){
  	               $tipo_lente = "Single vision";
  		        }else{
  			       $tipo_lente =$row["tipo_lente"];
  		        }
  		    /////variables right eye
  			$od_esferas = ($row["od_esferas"]=="-" or $row["od_esferas"]=="")? '': "SPH.".$row["od_esferas"];
        	$od_cilindro = ($row["od_cilindros"]=="-" or $row["od_cilindros"]=="")? '': "CYL.".$row["od_cilindros"];
        	$od_eje = ($row["od_eje"]=="-" or $row["od_eje"]=="")? '': "AX.".$row["od_eje"];
        	$od_add = ($row["od_adicion"]=="-" or $row["od_adicion"]=="")? '': "ADD.".$row["od_adicion"];
        	////variables left eye 
        	$oi_esferas = ($row["oi_esferas"]=="-" or $row["oi_esferas"]=="")? '': "SPH.".$row["oi_esferas"];
        	$oi_cilindro = ($row["oi_cilindros"]=="-" or $row["oi_cilindros"]=="")? '': "CYL.".$row["oi_cilindros"];
        	$oi_eje = ($row["oi_eje"]=="-" or $row["oi_eje"]=="")? '': "AX.".$row["oi_eje"];
        	$oi_add = ($row["oi_adicion"]=="-" or $row["oi_adicion"]=="")? '': "ADD.".$row["oi_adicion"];

        	$output["id_orden"] = $row["id_orden"];
        	$output['right'] = $od_esferas." ".$od_cilindro." ".$od_eje." ".$od_add;
        	$output['left'] = $oi_esferas." ".$oi_cilindro." ".$oi_eje." ".$oi_add;
            $output['lente'] = $tipo_lente;
        	}//Fin foreach

        }      			

    echo json_encode($output);
    break;

    case 'registerReceived':
    $orders->registerReceived();
    $mensaje = "Ok";
    echo json_encode($mensaje);    
    break;


  case 'get_orders_processing':
  $datos = $orders->get_orders_processing();
  $data = Array();
  $tit = "Recibir";

    foreach ($datos as $row) {

      if($row["tipo_lente"]=="Visión Sencilla"){
        $tipo_lente = "Single vision";
      }else{
        $tipo_lente =$row["tipo_lente"];
      }

      $od_esferas = ($row["od_esferas"]=="-" or $row["od_esferas"]=="")? '': "<span style='color:black'><b>SPH.</b> </span>".$row["od_esferas"];
      $od_cilindro = ($row["od_cilindros"]=="-" or $row["od_cilindros"]=="")? '': "<span style='color:black'><b>CYL.</b> </span>".$row["od_cilindros"];
      $od_eje = ($row["od_eje"]=="-" or $row["od_eje"]=="")? '': "<span style='color:black'><b>AX.</b> </span>".$row["od_eje"];
      $od_add = ($row["od_adicion"]=="-" or $row["od_adicion"]=="")? '': "<span style='color:blue'>ADD. </span>".$row["od_adicion"];
        //////////////////////////////////////////////////;//////////////////////////////////////////////////////////////////////
      $oi_esferas = ($row["oi_esferas"]=="-" or $row["oi_esferas"]=="")? '': "<span style='color:black'><b>SPH.</b> </span>".$row["oi_esferas"];
      $oi_cilindro = ($row["oi_cilindros"]=="-" or $row["oi_cilindros"]=="")? '': "<span style='color:black'><b>CYL.</b> </span>".$row["oi_cilindros"];
      $oi_eje = ($row["oi_eje"]=="-" or $row["oi_eje"]=="")? '': "<span style='color:black'><b>AX.</b> </span>".$row["oi_eje"];
      $oi_add = ($row["oi_adicion"]=="-" or $row["oi_adicion"]=="")? '': "<span style='color:blue'>ADD. </span>".$row["oi_adicion"];
        ///////////////////////////   
      $sub_array = array();
      $sub_array[] = $row["id_orden"];  
      $sub_array[] = '<div data-toggle="tooltip" title="Date received: '.$row["fecha"].'" style="text-align:right;"><input type="checkbox" class="form-check-input send_item" value="'.$row["id_orden"].'" name="'.$row["paciente"].'" id="'.$row["codigo"].'" style="text-align: center"><span style="color:black;padding:3px">Send</span></div>';
      $sub_array[] = $od_esferas." ".$od_cilindro." ".$od_eje." ".$od_add;
      $sub_array[] = $oi_esferas." ".$oi_cilindro." ".$oi_eje." ".$oi_add;
      $sub_array[] = $tipo_lente;    
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