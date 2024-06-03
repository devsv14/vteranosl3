<?php

require_once("../config/conexion.php");
//llamada al modelo
require_once("../modelos/Entregas.php");
$entregas = new Entregas();

switch ($_GET["op"]) {

  case 'get_entregas_ordenes':
    $datos = $entregas->get_entregas_ordenes($_POST['permiso_listar']);
    $data = array();
    foreach ($datos as $row) {
      $sub_array = array();

      $sub_array[] = $row["id_orden"];
      $sub_array[] = date('d-m-Y',strtotime($row["fechaExp"]));
      $sub_array[] = date('d-m-Y',strtotime($row["fecha"]));
      $sub_array[] = $row["dui"];
      $sub_array[] = strtoupper($row["paciente"]);
      $sub_array[] = $row["sucursal"];
      $sub_array[] = $row["institucion"];
      $cantidadLlamadas = $entregas->getCantidadLlamadasOrden($row['id_accion']);
      $sub_array[] = count($cantidadLlamadas) > 0 ? '<span class="badge badge-success" style="font-size: 11px;cursor:pointer" data-toggle="tooltip" data-placement="bottom" title="fecha: '.date('d-m-Y',strtotime($cantidadLlamadas[0]['fecha'])).'">Contactado</span>' : '<span class="badge badge-danger" style="font-size:11px">Recibido</span>';
      $sub_array[] = count($cantidadLlamadas) > 0 ? date('d-m-Y',strtotime($cantidadLlamadas[0]['fecha'])): '-';

      $sub_array[] = '<button type="button"  class="btn bg-light" onClick="show_modal_add_phone(\'' . $row["id_accion"] . '\',\'' . $row["telefono"] . '\',\'' . strtoupper($row["paciente"]). '\',\'' .$row["dui"]. '\',\'' .$row["sucursal"]. '\',\'' .$row["codigo"]. '\')"><i class="fas fa-phone"></i></button>';

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
  case 'get_acc_entregas':
    $data = $entregas->get_acc_entregas($_POST['id_acc']);
    $data_final = [];
    foreach($data as $row){
      $array['id_acc_entrega'] = $row['id_acc_entrega'];
      $array['estado_llamada'] = $row['estado_llamada'];
      $array['accion'] = $row['accion'];
      $array['fecha'] = date('d-m-Y',strtotime($row['fecha']));
      $array['hora'] = $row['hora'];
      $array['usuario'] = $row['usuario'];
      $data_final[] = $array;
    }
    echo json_encode($data_final);
  break;
  case 'save_accion_entrega':
    $result = $entregas->save_accion_entrega($_POST);
    if($result){
      $message = "save";
    }else{
      $message = "error";
    }
    echo json_encode($message);
  break;
  case 'get_cita_tel':
    $data = $entregas->get_cita_tel($_POST['dui_paciente']);
    if(count($data) > 0){
      $result = $data[0];
    }else{
      $result = 'null';
    }
    echo json_encode($result);
  break;
}