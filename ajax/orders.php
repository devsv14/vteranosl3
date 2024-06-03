<?php

require_once("../config/conexion.php");
//llamada al modelo categoria
require_once("../modelos/Orders.php");

$ordenes = new Ordenes();

switch ($_GET["op"]){

////////////////// AGRUPA AROS  ////////////////////
case 'get_ordenes':
  $datos = $ordenes->get_ordenes();
  $data = Array();
    $about = "about:blank";
    $print = "print_popup";
    $ancho = "width=600,height=500";
  foreach ($datos as $row) { 
  $sub_array = array();

  $sub_array[] = $row["marca_aro"];
  $sub_array[] = $row["modelo_aro"];
  $sub_array[] = $row["horizontal_aro"];
  $sub_array[] = $row["vertical_aro"];
  $sub_array[] = $row["puente_aro"];
  $sub_array[] = $row["cantidad"];
  $sub_array[] = '<i class="fa fa-image" aria-hidden="true" style="color:blue" onClick="verImagen(\''.$row["img"].'\',\''.$row["modelo_aro"].'\',\''.$row["horizontal_aro"].'\',\''.$row["vertical_aro"].'\',\''.$row["puente_aro"].'\')"></i></button>';  
    $data[] = $sub_array;
  }
  
  $results = array(
      "sEcho"=>1, //Información para el datatables
      "iTotalRecords"=>count($data), //enviamos el total registros al datatable
      "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
      "aaData"=>$data);
    echo json_encode($results);
  break;


  case 'get_ordenes_aros_enviar':
  $datos = $ordenes->getOrdenesArosEnviar($_POST["inicio"],$_POST["hasta"]);
  $data = Array();
  foreach ($datos as $row) { 
  $sub_array = array();

  $sub_array[] = $row["marca_aro"];
  $sub_array[] = $row["modelo_aro"];
  $sub_array[] = $row["horizontal_aro"];
  $sub_array[] = $row["vertical_aro"];
  $sub_array[] = $row["puente_aro"];
  $sub_array[] = $row["cantidad"];
  $sub_array[] = '<i class="fa fa-image" aria-hidden="true" style="color:blue" onClick="verImagen(\''.$row["img"].'\',\''.$row["modelo_aro"].'\',\''.$row["horizontal_aro"].'\',\''.$row["vertical_aro"].'\',\''.$row["puente_aro"].'\')"></i></button>';  
    $data[] = $sub_array;
  }
  
  $results = array(
      "sEcho"=>1, //Información para el datatables
      "iTotalRecords"=>count($data), //enviamos el total registros al datatable
      "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
      "aaData"=>$data);
    echo json_encode($results);
    break;


  case 'send_aros':
    $data=$ordenes->sendAros($_POST["modelo"],$_POST["horizontal"],$_POST["vertical"],$_POST["puente"],$_POST["cantidad"],$_POST["dest_aro"]);
    $datos = Array();
    foreach($data as $row){
      $sub_array = array();
      $sub_array[] = $row["codigo"];
      $sub_array[] = $row["paciente"];    
      $datos[] = $sub_array;                      
      }      

  echo json_encode($data);

    break;
}













