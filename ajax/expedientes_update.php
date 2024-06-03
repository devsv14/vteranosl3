<?php

require_once("../config/conexion.php");
//llamada al modelo categoria
require_once("../modelos/UpdateExpedientes.php");

$expedientes = new Expedientes();

switch ($_GET["op"]){
    case 'get_data_update':
        
        $expedientes->getExpedientesUpdates($_POST["sucursal"],$_POST["factura"]);
        
        break;

    case 'get_exced_fechas':
        $args= $_POST['Args'];

        $data= Array();
        $datos = $expedientes->getExcedentesFecha($args[0],$args[1],$args[2],$args[3],);
        foreach($datos as $row){
            $sub_array = array();
            $sub_array[] =  '<input type="checkbox" class="check_selected" onClick="addUpdate('.$row["dui"].')" >';
            $sub_array[] = $row["paciente"];
            $sub_array[] = $row["dui"];
            $sub_array[] = $row["sucursal"];
            $sub_array[] = $row["fecha"];
            $sub_array[] = '<i class="fa fa-trash fa-2x" aria-hidden="true" style="color: red" onClick="eliminarItemUpdate()"></i>';
    
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