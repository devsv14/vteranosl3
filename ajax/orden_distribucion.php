<?php

require_once("../config/conexion.php");
//llamada al modelo categoria
require_once("../modelos/Orden_distribucion.php");
require_once("../modelos/ExpActualization.php");
$distribucion = new ScanActas();
$actualizacion = new Expedientes();
switch ($_GET["op"]) {
     case 'getOrdenes':
        $data = array();
        $datos = $distribucion->getOrdenesAll();
        $cont = 0;
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = '<input type="checkbox" class="check_selectedaud" onClick="checkvinetaAud(this)"  data-dui=' . $row["dui"] . ' id=c' . $cont . '>';
            $sub_array[] = $row["paciente"];
            $sub_array[] = $row["sucursal"];
            $sub_array[] = $row['dui'];
            $sub_array[] = $row["genero"];
            $sub_array[] = $row["estado"];
            $sub_array[] = $row["fecha"];
            $data[] = $sub_array;
            $cont++;
        }

        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);
        break;

        case 'eliminar_ordenesad':
             $distribucion->eliminarOrdenesAd();
            break;
       
       
        case 'get_acta_dui_idActa':
        $value = isset($_POST['busqueda']) ? $_POST['busqueda'] : '';
        $data = $distribucion->getActasPorIdDui($value);
        echo json_encode($data);
        break;

        case 'get_resumen_fact_atend':
            $actualizacion->getExpedientesUpdates();
            break;

        case 'get_ordenes_update':
            $data = array();
            $args = $_POST['Args'];
            $datos = $actualizacion->getOrdenesExcentesFechas($args[0],$args[1],$args[2]);
            $cont = 0;
            foreach ($datos as $row) {
                $sub_array = array();
                $sub_array[] = '<input type="checkbox" class="ord-facts" onClick="addOrderUpd(this)"  data-dui=' . $row["dui"] . ' data-fecha=' . $row["fecha"] . ' id=upd' . $cont . '>';
                $sub_array[] = date("d-m-Y",strtotime($row["fecha"]));
                $sub_array[] = $row["paciente"];
                $sub_array[] = $row["dui"];
                $sub_array[] = $row["sucursal"];
                $sub_array[] = $row['tipo_lente'];
                $sub_array[] = $row["color"];
                $sub_array[] = $row["alto_indice"];

                $data[] = $sub_array;
                $cont++;
            }
    
            $results = array(
                "sEcho" => 1, //Información para el datatables
                "iTotalRecords" => count($data), //enviamos el total registros al datatable
                "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
                "aaData" => $data
            );
            echo json_encode($results);
            break;

            case  'actualizar_ordenes_fecha_ant':
                if($_POST["accion"]=='actualizar'){
                    $actualizacion->actualizarOrdenesFechaAnt();
                }elseif($_POST["accion"]=='importar'){
                    $actualizacion->importarCrearExpedientes();
                }
                    
                break;

        case 'import_pacientes_verificados':
            $data = array();
            $args = $_POST['Args'];
            
            $datos = $actualizacion->getExpedientesFromSources();
            $cont = 0;
            foreach ($datos as $row) {
                $sub_array = array();
                $sub_array[] = '<input type="checkbox" class="ord-facts" onClick="addOrderUpd(this)"  data-dui=' . $row["dui"] . ' data-fecha=' . $row["fecha"] . ' id=upd' . $cont . '>';
                $sub_array[] = date("d-m-Y",strtotime($row["fecha"]));
                $sub_array[] = $row["paciente"];
                $sub_array[] = $row["dui"];
                $sub_array[] = $row["sucursal"];
                $sub_array[] = $args[0];
                $sub_array[] = $args[1];
                $sub_array[] = $args[2];

                $data[] = $sub_array;
                $cont++;
            }
    
            $results = array(
                "sEcho" => 1, //Información para el datatables
                "iTotalRecords" => count($data), //enviamos el total registros al datatable
                "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
                "aaData" => $data
            ); 
            echo json_encode($results);
            break;     

            case 'export_pacientes_exced':
                $data = array();
                $args = $_POST['Args'];
                
                $datos = $actualizacion->getExpedientesFromExport($args[0],$args[1],$args[2],$args[3],$args[4]);
                $cont = 0;
                foreach ($datos as $row) {
                    $sub_array = array();
                    $sub_array[] = '<input type="checkbox" class="ord-facts" onClick="addOrderUpd(this)"  data-dui=' . $row["dui"] . ' data-fecha=' . $row["fecha"] . ' id=upd' . $cont . '>';
                    $sub_array[] = date("d-m-Y",strtotime($row["fecha"]));
                    $sub_array[] = $row["paciente"];
                    $sub_array[] = $row["dui"];
                    $sub_array[] = $row["sucursal"];
                    $sub_array[] = $args[0];
                    $sub_array[] = $args[1];
                    $sub_array[] = $args[2];
    
                    $data[] = $sub_array;
                    $cont++;
                }
        
                $results = array(
                    "sEcho" => 1, //Información para el datatables
                    "iTotalRecords" => count($data), //enviamos el total registros al datatable
                    "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
                    "aaData" => $data
                ); 
                echo json_encode($results);
                break;  
                
            case 'exportar_ordenesad';
            $actualizacion->exportarExpedientes();
                break;
}
