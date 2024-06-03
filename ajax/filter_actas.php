<?php

require_once("../config/conexion.php");
//llamada al modelo categoria
require_once("../modelos/filterActas.php");

$filter = new filterActas();

switch ($_GET["op"]){
    case 'filter_acta':
        $data = $filter->count_ampos($_POST["sucursal"]);   
            echo json_encode($data);
        break;

        case 'get_actas_por_ampo':
               $serie = 'serie'.$_POST["serie"];
               $datos = $filter->getRangoActas($_POST["serie"],$_POST["sucursal"]);   
               
                $data = array();
                foreach ($datos as $row) {
                    $sub_array = array();
                    $sub_array[] = $row["id_acta"];
                    $sub_array[] = $row["beneficiario"];
                    $sub_array[] = "<span id='".$row["dui_acta"]."' class='all-actas-ampos'>".$row["dui_acta"]."</span>";
                    $sub_array[] = $row["sucursal"];
                    $sub_array[] = $row["fecha_impresion"];
                    $sub_array[] = "<span id='estado".$row["dui_acta"]."' class='all-actas-ampos'>Busqueda pendiente...</span>";           
                
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
    
    }