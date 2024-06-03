<?php

require_once("../config/conexion.php");
//llamada al modelo categoria
require_once("../modelos/Citados.php");
require_once("../modelos/renovacionLentes.php");
$renovar = new Licitacion1();
$citas = new Citados();

switch ($_GET["op"]){

    case 'listar_pacientes_citados':

        $citados = $citas->listar_pacientes_citados();
        $data = Array();
        foreach($citados as $c){
            $sub_array = array();
            $sub_array[] = $c["id_cita"];
            $sub_array[] = $c["id_ref"];
            $sub_array[] = $c["paciente"]; 
            $sub_array[] = $c["dui"];
            $sub_array[] = $c["telefono"];
            $sub_array[] = $c["tipo_paciente"];
            $sub_array[] = $c["sector"];
            $sub_array[] = $c["sucursal"];
            $sub_array[] = "<i class='fas fa-user-check fa-2x' onClick='getCitados(".$c["id_cita"].")'></i>"; 
            $data[] = $sub_array;
        }

        $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
          echo json_encode($results);

        break;

        case 'get_citados_pend':

            $citados = $citas->listar_citados_pend();
            $data = Array();
            foreach($citados as $c){
                $sub_array = array();
                $sub_array[] = $c["paciente"]; 
                $sub_array[] = $c["dui"]; 
                $sub_array[] = $c["tipo_paciente"]; 
                $sub_array[] = $c["sector"];
                $sub_array[] = $c["fecha"]." ".$c["hora"];
                $sub_array[] = $c["sucursal"];
                $sub_array[] = "<button class='btn btn-outline-success btn-xs' onClick='editarCita(".$c["id_cita"].")'><i class='fas fa-edit'></i></button>";
                $data[] = $sub_array;
            }
    
            $results = array(
                "sEcho"=>1, //Información para el datatables
                "iTotalRecords"=>count($data), //enviamos el total registros al datatable
                "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
                "aaData"=>$data);
             echo json_encode($results);
    
            break; 
    
    case 'get_data_cita':

        $data = $citas->getDataCitaId($_POST["id_cita"]);
        echo json_encode($data[0]);
        break;

        case 'get_citados_sucursal':

            $citados = $citas->getDataCitadosSucursal($_POST["fecha"]);

            $data = Array();

            foreach($citados as $c){

                if($c["estado"]=="0"){
                    $estado="Pendiente";
                }elseif($c["estado"]=="1"){
                    $estado="Evaluado";
                }

                $sub_array = array();
                $sub_array[] = $c["paciente"]; 
                $sub_array[] = $c["dui"]; 
                $sub_array[] = $c["tipo_paciente"];
                $sub_array[] = $c["sector"];
                $sub_array[] = date("d-m-Y",strtotime($c["fecha"]));
                $sub_array[] = $c["sucursal"];
                $sub_array[] = $estado;
                $data[] = $sub_array;
            }
    
            $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);

            echo json_encode($results);

        break;


        case 'get_citados_sucursal_print':

            $citados = $citas->getDataCitadosSucursalPrint($_POST["sucursal"],$_POST["fecha"],$_POST["cat_user"],$_POST["sucursal_select"]);

            $data = Array();

            foreach($citados as $c){

                if($c["estado"]=="0"){
                    $estado="Pendiente";
                }elseif($c["estado"]=="1"){
                    $estado="Evaluado";
                }

                $sub_array = array();
                $sub_array[] = $c["paciente"]; 
                $sub_array[] = $c["dui"]; 
                $sub_array[] = $c["sector"];
                $sub_array[] = date("d-m-Y",strtotime($c["fecha"]));
                $sub_array[] = $c["sucursal"];
                $sub_array[] = $estado;
                $data[] = $sub_array;
            }
    
            $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);

            echo json_encode($results);

        break;

        case 'get_horas_select':
            
            $data = $citas->getHorasSelect($_POST["fecha"],$_POST["sucursal"]);
            $horas = array();
            foreach($data as $m){
                $hora = $m["hora"];
                array_push($horas,$hora);
             }
      
             echo json_encode($horas);

            break;

        case 'get_citados_atend':

            
            $args = $_POST["Args"];
            $tipo = $args[0];
            $sucursal = $args[1];
            $desde = $args[2];
            $hasta = $args[3];

            if($sucursal=="0"){
                $datos = $citas->getCitadosAtendAll($desde);
            }

            $data = Array();

            foreach($datos as $c){

                if($c["estado"]=="0"){
                    $estado="Pendiente";
                }elseif($c["estado"]=="1"){
                    $estado="Evaluado";
                }

                $sub_array = array();
                $sub_array[] = $c["sucursal"]; 
                $sub_array[] = $c["paciente"]; 
                $sub_array[] = $c["dui"];
                $sub_array[] = date("d-m-Y",strtotime($c["fecha"]))." ".$c["hora"];
                $sub_array[] = $c["sector"];
                $sub_array[] = $estado;
                $data[] = $sub_array;
            }
    
            $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);

            echo json_encode($results);

            break;

            case 'editar_cita';
              $citas->updateCitas();
            break;

            case 'get_disponilidad_citas':
                $citas->getDisponibilidadCitas($_POST["fecha"]);
            break;

            case 'get_reporteria_citados':
                $reques = [
                    "fecha_desde" => isset($_POST['fecha_desde']) ? $_POST['fecha_desde'] : '',
                    "fecha_hasta" => isset($_POST['fecha_hasta']) ? $_POST['fecha_hasta'] : '',
                    "estado_cita" => isset($_POST['estado_cita']) ? $_POST['estado_cita'] : '',
                    "sucursal" => isset($_POST['sucursal']) ? $_POST['sucursal'] : ''
                ];
                $data = array();
                $datos = $citas->get_reporteria_citados($reques);
                foreach ($datos as $row) {
                    $sub_array = array();
                    $sub_array[] = $row["id_cita"];
                    $sub_array[] = date("d-m-Y", strtotime($row["fecha"]));
                    $sub_array[] = $row["hora"];
                    $sub_array[] = $row["paciente"];
                    $sub_array[] = $row["dui"];
                    $sub_array[] = $row["telefono"];
                    $sub_array[] = $row["sector"];
                    $sub_array[] = $row["tipo_paciente"];
                    $sub_array[] = $row["sucursal"];
                    $sub_array[] = $row["estado"] == 1 ? "Atendido" : "Pendiente";
                    $sub_array[] = $row["nombres"];
                    $data[] = $sub_array;
                }
                ///////////////////////
                $results = array(
                    "sEcho" => 1, //Información para el datatables
                    "iTotalRecords" => count($data), //enviamos el total registros al datatable
                    "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
                    "aaData" => $data
                );
                echo json_encode($results);
        
            break;
            

            
            case 'citados_licitacion_uno':
            $datos = $renovar->gestionRenovarLentes();
            $data = array();
            foreach ($datos as $row) {
                $sub_array = array();
                $sub_array[] = $row["id"];
                $sub_array[] = $row["paciente"];
                $sub_array[] = $row["dui"];
                $sub_array[] = $row["telefono"];
                $sub_array[] = $row["depto"];
                $sub_array[] = $row["municipio"];
                $sub_array[] = $row["edad"];
                $sub_array[] = $row["tipo_lente"];
                $sub_array[] = $row["institucion"];
                $sub_array[] = date("d-m-Y", strtotime($row["fecha"]));
                $sub_array[] = '<button type="button" class="btn btn-sm bg-light" onClick="citasLicUno(\''.$row['dui']. '\',\''.$row['paciente'].'\')"><i class="fa fa-eye" aria-hidden="true" style="color:blue"></i></button>';
                $data[] = $sub_array;
            }
            ///////////////////////
            $results = array(
                "sEcho" => 1, //Información para el datatables
                "iTotalRecords" => count($data), //enviamos el total registros al datatable
                "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
                "aaData" => $data
            ); 
            echo json_encode($results);
            break;

}

