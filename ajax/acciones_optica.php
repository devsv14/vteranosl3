<?php

require_once("../config/conexion.php");
//llamada al modelo categoria
require_once("../modelos/AccionesOrden.php");

$acciones = new AccionesOptica();

switch ($_GET["op"]){
    case 'get_data_orden_barcode':
        $acciones->get_dataOrden($_POST["paciente_dui"],$_POST["tipo_accion"]);
        break;

    case 'registrar_accion':
        $acciones->registrarAccion();
        break;

    case 'get_ordenes_ing':
        $datos = $acciones->getOrdenesIngresadas();        
        $data = array();
        foreach ($datos as $row) {
          $sub_array = array();
          $sub_array[] = $row["id_accion"];
          $sub_array[] = date("d-m-Y", strtotime($row["fecha"])). $row["hora"];
          $sub_array[] = $row["paciente"];
          $sub_array[] = $row["dui"];
          $sub_array[] = $row["sucursal"];
          $sub_array[] = '<button type="button"  class="btn btn-sm bg-light" onClick="verEditar(\'' . $row["codigo"] . '\',\'' . $row["paciente"] . '\',\''.$row["id_aro"].'\',\''.$row["institucion"].'\',\''.$row["id_cita"].'\')"><i class="fa fa-eye" aria-hidden="true" style="color:blue"></i></button>';
          $sub_array[] = '<button type="button"  class="btn btn-block bg-light" onClick="modalImprimirActa(\''.$row["codigo"].'\',\''.$row["paciente"].'\',\''.$row["dui"].'\')"><i class="fa fa-file-pdf" aria-hidden="true" style="color:red"></i></button>'; 
          $data[] = $sub_array;
        }
  
        $results = array(
          "sEcho" => 1, //Información para el datatables
          "iTotalRecords" => count($data), //enviamos el total registros al datatable
          "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
          "aaData" => $data
        );
        echo json_encode($results);
        
        break;

        case 'get_data_despacho_lab':
            $acciones->getOrdenesDespachar($_POST['cod_despacho']);
          break;
}
