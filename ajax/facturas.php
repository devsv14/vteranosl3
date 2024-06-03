<?php

require_once("../config/conexion.php");
//llamada al modelo categoria
require_once("../modelos/Facturas.php");
$factura = new Facturas();

switch ($_GET["op"]){
    case 'guardar_factura_manual':
        if($_POST['id_factura'] == ""){
            $result = $factura->guardar_factura_manual($_POST);
            if($result){
                echo json_encode("exito");
            }else{
                echo json_encode("error");
            }
        }else{
            $result = $factura->update_factura_manual($_POST);
            if($result){
                echo json_encode("edit");
            }else{
                echo json_encode("error");
            }
        }
    break;
    case 'procesar_CCF_manual':
        if($_POST['id_factura'] == ""){
            $result = $factura->save_factura_CCF_manual($_POST);
            if($result){
                echo json_encode("exito");
            }else{
                echo json_encode("error");
            }
        }else{
            $result = $factura->update_factura_CCF_manual($_POST);
            if($result){
                echo json_encode("edit");
            }else{
                echo json_encode("error");
            }
        }
    break;
    case 'listar_facturas_manuales':
        
        $data = Array();
        $i=1;
        $datos = $factura->listar_facturas_manuales();
        foreach ($datos as $row) { 
        $sub_array = array();
        $sub_array[] = $i;
        $sub_array[] = $row["id_factura"];
        $sub_array[] = $row["num_factura"]; 
        $sub_array[] = date("d-m-Y",strtotime($row["fecha"]));
        $sub_array[] = $row["cliente"];
        $sub_array[] = $row["direccion"];  
        $sub_array[] = $row["telefono"];     
        $sub_array[] = '<button type="button"  class="btn btn-sm bg-light" onClick="show_factura(\''.$row["id_factura"].'\')"><i class="fa fa-eye" aria-hidden="true" style="color:blue"></i></button>
        <button type="button"  class="btn btn-sm bg-light" onClick="delete_factura(\''.$row["id_factura"].'\')"><i style="color: red;" class="fas fa-trash-alt"></i></button>';

        $i++;                                             
        $data[] = $sub_array;
        }
        
        $results = array(
        "sEcho"=>1, //Información para el datatables
        "iTotalRecords"=>count($data), //enviamos el total registros al datatable
        "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
        "aaData"=>$data);
        echo json_encode($results);

    break;
    case 'listar_facturas_ccf_manual':
        
        $data = Array();
        $i=1;
        $fact_ccf_manual = true;
        $datos = $factura->listar_facturas_manuales($fact_ccf_manual);
        foreach ($datos as $row) { 
        $sub_array = array();
        $sub_array[] = $i;
        $sub_array[] = $row["id_factura"];
        $sub_array[] = $row["num_factura"]; 
        $sub_array[] = $row["no_registro"]; 
        $sub_array[] = date("d-m-Y",strtotime($row["fecha"]));
        $sub_array[] = $row["cliente"];
        $sub_array[] = $row["direccion"];  
        $sub_array[] = $row["telefono"];     
        $sub_array[] = '<button type="button"  class="btn btn-sm bg-light" onClick="show_factura_CCF_manual(\''.$row["id_factura"].'\')"><i class="fa fa-eye" aria-hidden="true" style="color:blue"></i></button>
        <button type="button"  class="btn btn-sm bg-light" onClick="delete_factura(\''.$row["id_factura"].'\')"><i style="color: red;" class="fas fa-trash-alt"></i></button>';

        $i++;                                             
        $data[] = $sub_array;
        }
        
        $results = array(
        "sEcho"=>1, //Información para el datatables
        "iTotalRecords"=>count($data), //enviamos el total registros al datatable
        "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
        "aaData"=>$data);
        echo json_encode($results);

    break;
    case 'show_factura':

        $data = $factura->show_factura($_POST);
        echo json_encode($data);
    break;

    case 'delete_factura':
        $result = $factura->delete_factura($_POST['id_factura']);
        if($result){
            $message = "eliminado";
        }else{
            $message = "error";
        }
        echo json_encode($message);
    break;
}


