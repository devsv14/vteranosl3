<?php

require_once("../config/conexion.php");
//llamada al modelo categoria
require_once("../modelos/Bodega_av_plus.php");

$bodega = new Bodega();
switch ($_GET["op"]) {

  case 'get_ordenes_pendientes':

    $datos = $bodega->get_ordenes_pendientes($_POST['cod_reenvio']);

    echo json_encode($datos);
    break;

  case 'procesar_orden':
    $data = $_POST['arrayData'];
    $tipo_accion = $_POST['tipo_accion'];
    $codigo_recibido = $bodega->get_correlativo_bodega("INGR");
    foreach ($data as $row) {
      $bodega->procesar_orden($codigo_recibido, $row['codigo'], $row['dui']);
    }
    $mensaje = "ok";
    echo json_encode($mensaje);
    break;

  case 'get_ordenes_bodega':
    $data = array();

    $datos = $bodega->get_ordenes_bog();
    // $sub_array[] = date("d-m-Y", strtotime($row["fecha"]));
    $contador = 0;
    foreach ($datos as $row) {
      $sub_array = array();
      $sub_array[] = $row["id_orden"];
      $sub_array[] = $row["codigo"];
      $sub_array[] = date('d-m-Y', strtotime($row["fecha"]));
      $sub_array[] = $row["dui"];
      $sub_array[] = $row["paciente"];
      $sub_array[] = $row["tipo_lente"];
      $sub_array[] = $row["institucion"];
      $sub_array[] = $row["estado"] == 0 ? 'Pendientes' : '';
      $sub_array[] = $row["sucursal"];
      $sub_array[] = '<button type="button" class="btn btn-sm bg-light" onClick="verOrdenLaboratorio(\'' . $row["dui"] . '\')"><i class="fa fa-eye" aria-hidden="true" style="color:blue"></i></button>';
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

    //Procesando datatable
  case 'get_ordenes_procesando_lab':
    $data = array();
    $i = 1;
    $datos = $bodega->get_ordenes_procesando_lab($_POST['sucursal']);
    foreach ($datos as $row) {
      $sub_array = array();
      $sub_array[] = $i;
      $sub_array[] = $row["id_orden"];
      $sub_array[] = $row["codigo"];
      $sub_array[] = date("d-m-Y", strtotime($row["fecha"]));
      $sub_array[] = strtoupper($row["dui"]);
      $sub_array[] = strtoupper($row["paciente"]);
      $sub_array[] = $row["sucursal"];
      $sub_array[] = $row["tipo_lente"];
      $sub_array[] = '<button type="button"  class="btn btn-sm bg-light" onClick="verOrdenLaboratorio(\'' . $row["dui"] . '\')"><i class="fa fa-eye" aria-hidden="true" style="color:blue"></i></button>';

      $i++;
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
  /* 
   *  CASO PARA CONTROLAR LOS ESTADOS
   */
  case 'get_data_orden_barcode':
    if ($_POST["tipo_accion"] == "proceso") {
      $datos = $bodega->get_procesando_lab($_POST["paciente_dui"]);
    } else if ($_POST["tipo_accion"] == "finalizadas") {
      $datos = $bodega->get_finalizadas($_POST["paciente_dui"]);
    }else if($_POST["tipo_accion"] == "reenvio"){
      $datos = $bodega->get_reenvio_lab($_POST["paciente_dui"]);
    }

    if (is_array($datos) == true and count($datos) > 0) {
      foreach ($datos as $row) {
        $output["id_orden"] = $row["id_orden"];
        $output["codigo"] = $row["codigo"];
        $output["fecha"] = date("d-m-Y", strtotime($row["fecha"]));
        $output["dui"] = $row["dui"];
        $output["paciente"] = $row["paciente"];
        $output["sucursal"] = $row["sucursal"];
        $output['cod_envio'] = $row['cod_envio'];
      }
    } else {
      $output = $datos;
    }

    echo json_encode($output);

    break;

  case 'procesar_ordenes_estado':
    if ($_POST['tipo_accion'] == "proceso") {
      $bodega->finalizarOrdenesLab($_POST["usuario"]);
      $mensaje = "Ok";
    } elseif ($_POST['tipo_accion'] == 'finalizadas') {
      $array_code_envio = $bodega->finalizarOrdenesLabEnviar($_POST["usuario"]);
      $mensaje = [
        "message" => "Ok",
        "cod_envios" => $array_code_envio
      ];
    }else if($_POST['tipo_accion'] == "reenvio"){
      $bodega->update_reenvio_lab($_POST["usuario"]);
      $mensaje = "Ok";
    }

    echo json_encode($mensaje);
    break;
    //GET DATA PARA DATATABLE
  case 'get_ordenes_finalizadas_lab':
    $data = array();
    $i = 1;
    $datos = $bodega->get_ordenes_finalizadas_lab($_POST['sucursal']);
    $cont = 0;
    foreach ($datos as $row) {
      $sub_array = array();
      $sub_array[] = '<div class="icheck-primary d-inline"><input type="checkbox" class="check_selected" onchange="checkFinalizadasLab(this)" data-dui='.$row["dui"].' id='.$cont.'><label for='.$cont.'></div></div>';
      $sub_array[] = $i;
      $sub_array[] = $row["id_orden"];
      $sub_array[] = $row["codigo"];
      $sub_array[] = date("d-m-Y", strtotime($row["fecha"]));
      $sub_array[] = strtoupper($row["dui"]);
      $sub_array[] = strtoupper($row["paciente"]);
      $sub_array[] = $row["sucursal"];
      $sub_array[] = $row["tipo_lente"];
      $sub_array[] = '<button type="button"  class="btn btn-sm bg-light" onClick="verOrdenLaboratorio(\'' . $row["dui"] . '\')"><i class="fa fa-eye" aria-hidden="true" style="color:blue"></i></button>';

      $i++;
      $cont++;
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
    //GET DATA PARA DATATABLE
  case 'get_ordenes_enviadas_lab':
    $data = array();
    $i = 1;
    $datos = $bodega->get_ordenes_enviadas_lab($_POST['sucursal']);
    foreach ($datos as $row) {
      $sub_array = array();
      $sub_array[] = $i;
      $sub_array[] = $row["id_orden"];
      $sub_array[] = $row["codigo"];
      $sub_array[] = date("d-m-Y", strtotime($row["fecha"]));
      $sub_array[] = strtoupper($row["dui"]);
      $sub_array[] = strtoupper($row["paciente"]);
      $sub_array[] = $row["sucursal"];
      $sub_array[] = $row["tipo_lente"];
      $sub_array[] = '<button type="button"  class="btn btn-sm bg-light" onClick="verOrdenLaboratorio(\'' . $row["dui"] . '\')"><i class="fa fa-eye" aria-hidden="true" style="color:blue"></i></button>';

      $i++;
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

  case 'listar_ordenes_de_envio':
    $data = array();
    $i = 0;
    $datos = $bodega->listar_ordenes_enviadas();
    foreach ($datos as $row) {
      $sub_array = array();

      $sub_array[] = $row["id_acc_bodega"];
      $sub_array[] = $row["cod_bodega"];
      $sub_array[] = date("d-m-Y", strtotime($row["fecha"]));
      $sub_array[] = $row["usuario"];
      $sub_array[] = $row["cantidad"] . " ordenes";
      $sub_array[] = '<button type="button" class="btn btn-sm bg-light" onClick="show_ordenes_enviadas(\'' . $row["cod_bodega"] . '\',\'' . $row["sucursal"] . '\')"><i style="font-size: 18px" class="fas fa-print" aria-hidden="true" style="color:blue"></i></button>';
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
  case 'listar_ordenes_enviadas':
    $data = array();

    $datos = $bodega->get_ordenes_envio($_POST['codigo_bodega']);
    $cont = 1;
    foreach ($datos as $row) {
      $sub_array = array();
      $sub_array[] = $cont;
      $sub_array[] = $row["codigo"];
      $sub_array[] = date("d-m-Y", strtotime($row["fecha"]));;
      $sub_array[] = $row["paciente"];
      $sub_array[] = $row["dui"];
      $sub_array[] = $row["telefono"];
      $sub_array[] = $row["tipo_lente"];
      $sub_array[] = $row["sucursal"];
      $sub_array[] = '<button type="button"  class="btn btn-sm bg-light" onClick="verOrdenLaboratorio(\'' . $row["dui"] . '\')"><i class="fa fa-eye" aria-hidden="true" style="color:blue"></i></button>';
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
  case 'listar_cantidad_ordenes_reenviadas':
    $data = array();
    $i = 0;
    $datos = $bodega->get_ordenes_reenviadas();
    foreach ($datos as $row) {
      $sub_array = array();

      $sub_array[] = $row["id_acc_bodega"];
      $sub_array[] = $row["cod_bodega"];
      $sub_array[] = date("d-m-Y", strtotime($row["fecha"]));
      $sub_array[] = $row["usuario"];
      $sub_array[] = $row["cantidad"] . " ordenes";
      $sub_array[] = '<button type="button" class="btn btn-sm bg-light" onClick="show_ordenes_reenviadas(\'' . $row["cod_bodega"] . '\')"><i style="font-size: 18px" class="fas fa-print" aria-hidden="true" style="color:blue"></i></button>';
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
    case 'get_ordenes_reenviadas_reporte':
      $data = array();

      $datos = $bodega->get_ordenes_reenviadas_pdf($_POST['cod_bodega']);
      $cont = 1;
      foreach ($datos as $row) {
        $sub_array = array();
        $sub_array[] = $cont;
        $sub_array[] = date("d-m-Y",strtotime($row["fecha"]));
        $sub_array[] = $row["hora"];
        $sub_array[] = $row["paciente"];
        $sub_array[] = $row["dui"];
        $sub_array[] = '<button type="button"  class="btn btn-sm bg-light" onClick="verOrdenLaboratorio(\'' . $row["dui"] . '\')"><i class="fa fa-eye" aria-hidden="true" style="color:blue"></i></button>';
        $data[] = $sub_array;
        $cont ++;
      }

      $results = array(
        "sEcho" => 1, //Información para el datatables
        "iTotalRecords" => count($data), //enviamos el total registros al datatable
        "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
        "aaData" => $data
      );
      echo json_encode($results);
    break;
    //New code recib manual
  case 'get_recibir_orden_manual':
    $data = $bodega->get_orden_recibir_comprobar($_POST['dui']);
    if(count($data) > 0){
      $message = "existe";
      echo json_encode($message);
      return 0; //Salidad forzada
    }
    $datos = $bodega->get_ordenes_recibir_man($_POST['dui']);
    if (is_array($datos) == true and count($datos) > 0) {
      foreach ($datos as $row) {
        $output["n_orden"] = $row["codigo"];
        $output["codigo"] = $row["codigo"];
        $output["fecha"] = date("d-m-Y", strtotime($row["fecha"]));
        $output["dui"] = $row["dui"];
        $output["paciente"] = $row["paciente"];
        $output["sucursal"] = $row["sucursal"];
        $output['cod_envio'] = $row['cod_envio'];
      }
    } else {
      $output = $datos;
    }
    echo json_encode($output);
    break;
  case 'procesar_recib_manual':
    $data = $_POST['arrayData'];
    $codigo_recibido = $bodega->get_correlativo_bodega("INGR");
    foreach ($data as $row) {
      $bodega->procesar_orden($codigo_recibido, $row['codigo'], $row['dui']);
    }
    $mensaje = "ok";
    echo json_encode($mensaje);
    break;
    /**
   * CASE USE PARA GRAFICOS DE INGRESOS Y SALIDAS DE BODEGA
   */

   case 'listar_cant_orden_mes':
    $data = array();

      $datos = $bodega->listar_cant_orden_mes();
      $cont = 1;
      foreach ($datos as $row) {
        $sub_array = array();
        $sub_array[] = $cont;
        $sub_array[] = $bodega->get_month_string($row['month']);
        $sub_array[] = $row["year"];
        $sub_array[] = $row["cantidad"];
        $sub_array[] = '<button type="button"  class="btn btn-sm bg-light" onClick="show_graph_mes(\'' . $row["fecha"] . '\')"><i class="fas fa-chart-bar" style="color:blue"></i></button>';
        $data[] = $sub_array;
        $cont ++;
      }

      $results = array(
        "sEcho" => 1, //Información para el datatables
        "iTotalRecords" => count($data), //enviamos el total registros al datatable
        "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
        "aaData" => $data
      );
      echo json_encode($results);
    break;

  case 'generate_graph_month':
    $year_month = substr($_POST['fecha'], 0, 7); //return año-mes
    $data = $bodega->get_data_orden_mes($year_month);
    if (count($data) > 0) {
      echo json_encode($data);
    }
    break;
}
