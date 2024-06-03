<?php

require_once("../config/conexion.php");
//llamada al modelo categoria
require_once("../modelos/Laboratorios.php");
//Modelo orden lenti
require_once("../modelos/OrdenesLenti.php");
require_once("../modelos/Reporteria.php");

$ordenes = new Laboratorios();
$orden_lenti = new ordenesLenti(); //Para insertar datos a lenti
$reporteria = new Reporteria();
switch ($_GET["op"]) {

  case 'get_ordenes_pendientes_lab':

    if ($_POST['inicio'] != "" and $_POST['hasta'] != "" and $_POST['estado_proceso'] != "") {
      $datos = $ordenes->get_ordenes_filter_date($_POST["inicio"], $_POST["hasta"], $_POST["estado_proceso"]);
    } else {
      $datos = $ordenes->get_rango_estados_ordenes($_POST['estado_proceso']);
    }

    $data = array();
    $i = 1;
    foreach ($datos as $row) {
      $sub_array = array();

      switch ($row['estado']) {
        case 0:
          $estado = "Pendientes (Digitadas)";
          break;
        case 1:
          $estado = "Despacho de óptica";
          break;
        case 2:
          $estado = "Recibidas (Procesando)";
          break;
        case 3:
          $estado = "Finalizadas";
          break;
        case 4:
          $estado = "Enviadas a ópticas";
          break;
        case 5:
          $estado = "Recibidas en optica";
          break;
        case 6:
          $estado = "Entregadas";
          break;
      }


      $sub_array = array();
      $sub_array[] = $i;
      $sub_array[] = $row["id_orden"];
      $sub_array[] = date("d-m-Y", strtotime($row["fecha"]));
      $sub_array[] = $row["dui"];
      $sub_array[] = strtoupper($row["paciente"]);
      $sub_array[] = $row["tipo_lente"];
      $sub_array[] = $row["institucion"];
      $sub_array[] = $estado;
      $sub_array[] = $row["sucursal"];
      $sub_array[] = '<button type="button" class="btn btn-sm bg-light" onClick="verOrdenLaboratorio(\'' . $row["dui"] . '\',\'' . $row["codigo"] . '\')"><i class="fa fa-eye" aria-hidden="true" style="color:blue"></i></button>';
      $data[] = $sub_array;
      $i++;
    }

    $results = array(
      "sEcho" => 1, //Información para el datatables
      "iTotalRecords" => count($data), //enviamos el total registros al datatable
      "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
      "aaData" => $data
    );
    echo json_encode($results);
    break;

  case 'recibir_ordenes_laboratorio':
    $ordenes->recibirOrdenesLab();
    $mensaje = "Ok";
    echo json_encode($mensaje);
    break;

  case 'get_ordenes_procesando_lab':
    $data = array();
    $i = 1;
    $datos = $ordenes->get_ordenes_procesando_lab($_POST['sucursal']);
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

  case 'get_ordenes_procesando_lab_envios':
    $data = array();
    $i = 0;
    $datos = $ordenes->get_ordenesFinalEnviadaLab();
    foreach ($datos as $row) {
      $sub_array = array();

      $sub_array[] = $row["id_orden"];
      $sub_array[] = $row["codigo"];
      $sub_array[] = date("d-m-Y", strtotime($row["fecha"]));
      $sub_array[] = $row["dui"];
      $sub_array[] = strtoupper($row["paciente"]);
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

  case 'finalizar_ordenes_laboratorio':
    $ordenes->finalizarOrdenesLab($_POST['usuario']);
    $mensaje = "Ok";
    echo json_encode($mensaje);

    break;

    ////////////////// 

  case 'get_ordenes_finalizadas_lab':
    $data = array();
    $sucursal = isset($_POST['sucursal']) ? $_POST['sucursal'] : '';
    $datos = $ordenes->get_ordeOrdenesFinalizadas($sucursal);
    $cont = 0;
    foreach ($datos as $row) {
      $sub_array = array();
      $sub_array[] = '<input type="checkbox" class="check_selected" onchange="checkFinalizadasLab(this)" data-dui=' . $row["dui"] . ' id=' . $cont . '>';
      $sub_array[] = $row["id_orden"];
      $sub_array[] = $row["codigo"];
      $sub_array[] = date("d-m-Y", strtotime($row["fecha"]));
      $sub_array[] = $row["dui"];
      $sub_array[] = strtoupper($row["paciente"]);
      $sub_array[] = $row["sucursal"];
      $sub_array[] = $row["tipo_lente"];
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


  case 'get_data_orden_barcode':
    if ($_POST["tipo_accion"] == "ingreso_lab") {
      $datos = $ordenes->get_ordenes_lab_rectificaciones($_POST["paciente_dui"], $_POST['search_id']);
      if (count($datos) > 0) {
        $estado = true;
      } else {
        $datos = $ordenes->get_ordenes_despacho($_POST["paciente_dui"], $_POST['search_id']);
      }
    } else if ($_POST["tipo_accion"] == "en_proceso_lab") {
      $datos = $ordenes->get_ordenes_barcode_lab_id($_POST["paciente_dui"], $_POST['search_id']);
    } else if ($_POST["tipo_accion"] == "finalizar_lab") {
      $datos = $ordenes->get_ordenes_barcode_lab($_POST["paciente_dui"], $_POST['search_id']);
    }

    if (is_array($datos) == true and count($datos) > 0) {
      foreach ($datos as $row) {
        $output["id_orden"] = $row["id_orden"];
        $output["n_despacho"] = $row["n_despacho"];
        $output["codigo"] = $row["codigo"];
        $output["estado"] = isset($estado) ? "rectificacion" : $row["codigo"];
        $output["fecha"] = date("d-m-Y", strtotime($row["fecha"]));
        $output["dui"] = $row["dui"];
        $output["paciente"] = $row["paciente"];
        $output["sucursal"] = $row["sucursal"];
      }
    } else {
      $output = $datos;
    }

    echo json_encode($output);

    break;

    ///////////////BARCODE PROCESOS //////////

  case 'get_correlativo_detalle_envio':

    $correlativo = $ordenes->get_correlativo_detalle_envio();

    if (is_array($correlativo) == true and count($correlativo) > 0) {
      foreach ($correlativo as $row) {
        $codigo = $row["cod_despacho"];
        $cod = (substr($codigo, 4, 15)) + 1;
        $output["correlativo"] = "DSP-" . $cod;
      }
    } else {
      $output["correlativo"] = "DSP-1";
    }
    echo json_encode($output);
    break;

    //////////////////PROCESAR ORDENES BARCODE /////////////
  case 'procesar_ordenes_barcode':
    if ($_POST['tipo_accion'] == "ingreso_lab") {
      //$ordenes->ingreso_lab();
    }
    if ($_POST['tipo_accion'] == 'en_proceso_lab') { ///FINALIZAR LAB
      $ordenes->finalizarOrdenesLab($_POST["usuario"]);
      $mensaje = "Ok";
    } elseif ($_POST['tipo_accion'] == 'recibir_veteranos' or $_POST['tipo_accion'] == 'entregar_veteranos') {
      $comprobar_correlativo = $ordenes->compruebaCorrelativo($_POST['correlativo_accion']);
      if (is_array($comprobar_correlativo) == true and count($comprobar_correlativo) == 0) {
        $ordenes->recibirOrdenesVeteranos();
        $mensaje = "Ok";
      } else {
        $mensaje = 'Error';
      }
    } elseif ($_POST['tipo_accion'] == 'finalizar_lab') {
      $array_cod_envio = $ordenes->finalizarOrdenesLabEnviar($_POST["usuario"], $_POST['cod_envio']);
      $mensaje = [
        "message" => "Ok",
        "cod_envios" => $array_cod_envio
      ];
    }

    echo json_encode($mensaje);
    break;

  case 'listar_ordenes_recibidas_veteranos':
    $data = array();
    $i = 0;
    $datos = $ordenes->listarOrdenesRecibidasVeteranos();
    foreach ($datos as $row) {
      $sub_array = array();

      $sub_array[] = $row["id_detalle_accion"];
      $sub_array[] = date("d-m-Y", strtotime($row["fecha"])) . " " . $row["hora"];
      $sub_array[] = $row["codigo_orden"];
      $sub_array[] = $row["paciente"];
      $sub_array[] = $row["dui"];
      $sub_array[] = $row["tipo_lente"];
      $sub_array[] = '<button type="button"  class="btn btn-block bg-light" onClick="verEditar(\'' . $row["codigo_orden"] . '\',\'' . $row["paciente"] . '\')"><i class="fa fa-eye" aria-hidden="true" style="color:blue"></i></button>';
      $sub_array[] = '<button type="button"  class="btn btn-block bg-light" onClick="modalImprimirActa(\'' . $row["codigo_orden"] . '\',\'' . $row["paciente"] . '\')"><i class="fa fa-file-pdf" aria-hidden="true" style="color:red"></i></button>';

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

  case 'listar_ordenes_entregadas_veteranos':
    $data = array();
    $i = 0;
    $datos = $ordenes->listarOrdenesEntregadasVeteranos();
    foreach ($datos as $row) {
      $sub_array = array();

      $sub_array[] = $row["id_detalle_accion"];
      $sub_array[] = date("d-m-Y", strtotime($row["fecha"])) . " " . $row["hora"];
      $sub_array[] = $row["codigo_orden"];
      $sub_array[] = $row["usuario"];
      $sub_array[] = $row["paciente"];
      $sub_array[] = $row["dui"];
      $sub_array[] = $row["tipo_lente"];
      $sub_array[] = '<button type="button"  class="btn btn-sm bg-light" onClick="verEditar(\'' . $row["codigo_orden"] . '\',\'' . $row["paciente"] . '\')"><i class="fa fa-eye" aria-hidden="true" style="color:blue"></i></button>';


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
    $datos = $ordenes->listarOrdenesEnvio();
    foreach ($datos as $row) {
      $sub_array = array();

      $sub_array[] = $row["id_ordenes_envio"];
      $sub_array[] = $row["cod_despacho"];
      $sub_array[] = date("d-m-Y", strtotime($row["fecha"]));
      $sub_array[] = $row["usuario"];
      $sub_array[] = $row["cant"];
      $sub_array[] = $row["sucursal"];
      $sub_array[] = '<button type="button" class="btn btn-sm bg-light" onClick="show_ordenes_enviadas(\'' . $row["cod_despacho"] . '\',\'' . $row["sucursal"] . '\')"><i style="font-size: 18px" class="fas fa-print" aria-hidden="true" style="color:blue"></i></button>';


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


  case 'buscar_orden_graduacion':
    $orden = $ordenes->getOrdenesGraduaciones($_POST["od_esfera"], $_POST["od_cilindro"], $_POST["od_eje"], $_POST["od_adi"], $_POST["oi_esfera"], $_POST["oi_cilindro"], $_POST["oi_eje"], $_POST["oi_adi"]);
    $data = array();
    $results = array();
    $estado = '';
    $badge = "";
    if (is_array($orden) == true and count($orden) > 0) {
      foreach ($orden as $key) {
        if ($key["estado_aro"] == "0") {
          $estado = 'Sin procesar';
          $badge = "danger";
        } elseif ($key["estado_aro"] == "2") {
          $estado = 'Recibido en laboratorio';
          $badge = "warning";
        } elseif ($key["estado_aro"] == "3") {
          $estado = 'En proceso';
          $badge = "info";
        } else if ($key[""] == "4") {
          $estado = 'Despachado de laboratorio';
          $badge = "success";
        }
        $output["id_orden"] = $key["id_orden"];
        $output["codigo"] = $key["codigo"];
        $output["fecha"] = date("d-m-Y", strtotime($key["fecha"]));
        $output["paciente"] = $key["paciente"];
        $output["estado_aro"] = '<span class="right badge badge-' . $badge . '" style="font-size:12px">' . $estado . '</span>';
        array_push($results, $output);
      }
      $data = $results;
    } else {
      $data = "Vacio";
    }

    echo json_encode($data);
    break;


  case 'cambiar_estado_aro_print':
    $ordenes->cambiaEstadoAroPrint();
    break;
  case 'get_despacho_lab':

    $data = $ordenes->get_despacho_lab($_POST['n_despacho']);

    if (count($data) > 0) {
      echo json_encode($data);
    } else {
      $mensaje = "vacio";
      echo json_encode($mensaje);
    }
    break;
  case 'ingreso_lab':

    $data = $_POST['data'];
    $ACCIONES = "ingresos_lab";
    require_once("../modelos/OrdenesLic1.php");
    $olic1 = new Licitacion1();
    $cod_reenvio = $ordenes->get_correlativo_reenvio();
    foreach ($data as $row) {
      //$ordenes->ingreso_lab($cod_reenvio, $row['codigo'], $row['n_despacho'], $row['dui'], $row['paciente'], $ACCIONES, $_POST['tipo_acciones'], $_POST['laboratorio'], $row['estado'], $_POST['rectificacion']);
      //insertar a LENTI
      if ($_POST['laboratorio'] == "LENTI") {
        //$data_orden_lab = $ordenes->getDataOrdenLenti($row['dui']);
        $data_orden_lab = $olic1->getDataOrdenesl1($row['dui']);
   
        if ($_POST['rectificacion'] == "Si") {
          foreach ($data_orden_lab as $k) {
            //$marca = (isset($k['marca'])) ? $k['marca'] : '-';
            //$modelo = (isset($k['modelo'])) ? $k['modelo'] : '-';

            $orden_lenti->trasladoOrdenesLenti($k['codigo'], $k['paciente'], $k['observaciones'], $k['id_usuario'], $k['tipo_lente'], $k['od_esferas'], $k['od_cilindros'], $k['od_eje'], $k['od_adicion'], $k['oi_esferas'], $k['oi_cilindros'], $k['oi_eje'], $k['oi_adicion'], $k['pupilar_od'], $k['pupilar_oi'], $k['lente_od'], $k['lente_oi'], $k['categoria'], $k['color'], $k['modelo'], $k['material'], $k['marca'], $k['trat'], $_POST['tipo_acciones'], $k["dui"] . "-R");
          }
        } else {
          foreach ($data_orden_lab as $k) {
            //$marca = (isset($k['marca'])) ? $k['marca'] : '-';
            //$modelo = (isset($k['modelo'])) ? $k['modelo'] : '-';

            $orden_lenti->trasladoOrdenesLenti($k['codigo'], $k['paciente'], $k['observaciones'], $k['id_usuario'], $k['tipo_lente'], $k['od_esferas'], $k['od_cilindros'], $k['od_eje'], $k['od_adicion'], $k['oi_esferas'], $k['oi_cilindros'], $k['oi_eje'], $k['oi_adicion'], $k['pupilar_od'], $k['pupilar_oi'], $k['lente_od'], $k['lente_oi'], $k['categoria'], $k["dui"]);
          }
        }
      }
    }

    $mensaje = [
      "status" => "exito",
      "code_reenvio" => $cod_reenvio
    ];
    echo json_encode($mensaje);
    break;


  case 'listar_ingreso_lab':
    $data = array();

    $datos = $ordenes->get_acciones_lab();
    // $sub_array[] = date("d-m-Y", strtotime($row["fecha"]));
    $contador = 0;
    foreach ($datos as $row) {
      $sub_array = array();
      $sub_array[] = $row["id_acc_lab"];
      $sub_array[] = $row["n_despacho"];
      $sub_array[] = $row["fecha_creacion"];
      $sub_array[] = $row["tipo_accion"];
      $sub_array[] = $row["laboratorio"];
      $sub_array[] = $row["dui"];
      $sub_array[] = $row["paciente"];
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
  case 'get_data_orden':
    $datos = $ordenes->get_data_orden($_POST["dui"]);
    echo json_encode($datos[0]);
    break;

  case 'listar_ordenes_enviadas':
    $data = array();

    $datos = $reporteria->get_detalle_ordenes_envio_new($_POST['cod_orden']);
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
      $sub_array[] = '<button type="button" class="btn btn-sm bg-light" onClick="verOrdenLaboratorio(\'' . $row["dui"] . '\')"><i class="fa fa-eye" aria-hidden="true" style="color:blue"></i></button>';
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

  case 'listar_ordenes_reenviadas':
    $data = array();

    $datos = $ordenes->get_ordenes_reenviadas();
    $cont = 1;
    foreach ($datos as $row) {
      $sub_array = array();
      $sub_array[] = $cont;
      $sub_array[] = $row["cod_envio"];
      $sub_array[] = date("d-m-Y", strtotime($row["fecha"]));
      $sub_array[] = $row['hora'];
      $sub_array[] = $row["cantidad"];
      $sub_array[] = $row["laboratorio"];
      $sub_array[] = '<button type="button" class="btn btn-sm bg-light" onClick="show_ordenes_reenviadas(\'' . $row["cod_envio"] . '\',\'' . $row["laboratorio"] . '\')"><i style="font-size: 18px" class="fas fa-print" aria-hidden="true" style="color:blue"></i></button>';
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

  case 'get_ordenes_reenviadas_reporte':
    $data = array();

    $datos = $reporteria->get_ordenes_reenviadas_pdf($_POST['cod_envio']);
    $cont = 1;
    foreach ($datos as $row) {
      $sub_array = array();
      $sub_array[] = $cont;
      $sub_array[] = date("d-m-Y", strtotime($row["fecha"]));
      $sub_array[] = $row["hora"];
      $sub_array[] = $row["paciente"];
      $sub_array[] = $row["dui"];
      $sub_array[] = '<button type="button" class="btn btn-sm bg-light" onClick="verOrdenLaboratorio(\'' . $row["dui"] . '\')"><i class="fa fa-eye" aria-hidden="true" style="color:blue"></i></button>';
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
    //Accion para obtener los datos de enviados
  case 'get_ordenes_finalizadas_bodega':
    //Comprobar si ya fueron ingresadas al Laboratorio
    $comprobar_existencia = $ordenes->get_recibido_lab($_POST['cod_envio'], $_POST['tipo_busqueda']);
    if (count($comprobar_existencia) > 0) {
      echo json_encode("recibido");
      return 0;
    }
    $datos = $ordenes->get_order_pend_bodega($_POST['cod_envio'], $_POST['tipo_busqueda']);

    $cont = 1;
    $data = [];
    foreach ($datos as $row) {
      $sub_array = array();
      $sub_array[] = $cont;
      $sub_array['codigo'] = $row["codigo"];
      $sub_array['n_orden'] = $row["codigo"];
      $sub_array['dui'] = $row["dui"];
      $sub_array['paciente'] = $row["paciente"];
      $sub_array['fecha'] = date("d-m-Y", strtotime($row["fecha"]));
      $sub_array['n_despacho'] = $row["cod_bodega"];
      $sub_array['sucursal'] = $row["sucursal"];
      $data[] = $sub_array;
      $cont++;
    }
    echo json_encode($data);
    break;
  case 'get_recibir_devoluciones':
    //Comprobar si ya fueron ingresadas al Laboratorio
    $comprobar_existencia = $ordenes->comprobar_exist_recib_orden($_POST['cod_recib_dev'], $_POST['tipo_busqueda']);
    if (count($comprobar_existencia) > 0) {
      echo json_encode("recibido");
      return 0;
    }
    $datos = $ordenes->get_recibir_devoluciones($_POST['cod_recib_dev'], $_POST['tipo_busqueda']);

    $cont = 1;
    $data = [];
    foreach ($datos as $row) {
      $sub_array = array();
      $sub_array[] = $cont;
      $sub_array['codigo'] = $row["codigo"];
      $sub_array['n_orden'] = $row["codigo"];
      $sub_array['dui'] = $row["dui"];
      $sub_array['paciente'] = $row["paciente"];
      $sub_array['fecha'] = date("d-m-Y", strtotime($row["fecha"]));
      $sub_array['n_despacho'] = $row["cod_bodega"];
      $sub_array['sucursal'] = $row["sucursal"];
      $data[] = $sub_array;
      $cont++;
    }
    echo json_encode($data);
    break;
  case 'procesar_orden':
    $data = $_POST['arrayData'];
    $tipo_accion = $_POST['tipo_accion'];
    foreach ($data as $row) {
      $ordenes->procesar_orden($row['codigo'], $row['dui'], $row['n_despacho']);
    }
    $mensaje = "ok";
    echo json_encode($mensaje);
    break;

  case 'procesar_devoluciones':
    $data = $_POST['arrayData'];
    foreach ($data as $row) {
      $ordenes->procesar_devoluciones($row['codigo'], $row['dui'], $row['n_despacho']);
    }
    $mensaje = "ok";
    echo json_encode($mensaje);
    break;
    /**
     * Ordenes pendientes
     */

  case 'ordenesPendientesLab':
    $desde = isset($_POST['fechaInicio']) ? $_POST['fechaInicio'] : '';
    $hasta = isset($_POST['fechaHasta']) ? $_POST['fechaHasta'] : '';
    $selectEstado = isset($_POST['selectEstado']) ? $_POST['selectEstado'] : '';
    $datos = $ordenes->ordenesPendientesLabEst($desde,$hasta,$selectEstado);
    $data = array();
    $i = 1;
    foreach ($datos as $row) {
      $sub_array = array();

      switch ($row['estado']) {
        case 0:
          $estado = "Digitada";
          break;
        case 1:
          $estado = "Despacho de óptica";
          break;
        case 2:
          $estado = "Recibidas (Procesando)";
          break;
        case '2-b':
          $estado = "Recibidas (Bodega)";
          break;
        case 3:
          $estado = "Finalizadas";
          break;
        case '3-b':
          $estado = "Finalizadas en Bodega";
          break;
      }
      $sub_array = array();
      $sub_array[] = $i;
      $sub_array[] = $row["id_orden"];
      $sub_array[] = date("d-m-Y", strtotime($row["fecha"]));
      $sub_array[] = $row["dui"];
      $sub_array[] = strtoupper($row["paciente"]);
      $sub_array[] = $row["tipo_lente"];
      $sub_array[] = $row["institucion"];
      $sub_array[] = $estado;
      $sub_array[] = $row["sucursal"];
      $sub_array[] = '<button type="button" class="btn btn-sm bg-light" onClick="verOrdenLaboratorio(\'' . $row["dui"] . '\',\'' . $row["codigo"] . '\')"><i class="fa fa-eye" aria-hidden="true" style="color:blue"></i></button>';
      $data[] = $sub_array;
      $i++;
    }

    $results = array(
      "sEcho" => 1, //Información para el datatables
      "iTotalRecords" => count($data), //enviamos el total registros al datatable
      "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
      "aaData" => $data
    );
    echo json_encode($results);
    break;
  case 'getDistinctEstadoOrden':
    $desde = isset($_POST['desdeFecha']) ? $_POST['desdeFecha'] : '';
    $hasta = isset($_POST['hastaFecha']) ? $_POST['hastaFecha'] : '';
    $data = $ordenes->getDistinctEstadoOrden($desde,$hasta);
    $newArrayEstado = [];
    foreach($data as $row){
      switch ($row['estado']) {
        case '0':
          $estado = "Digitadas";
          break;
        case '1':
          $estado = "Despacho de óptica";
          break;
        case '2':
          $estado = "Procesando Lab";
          break;
        case '2-b':
          $estado = "Procesando en Bodega";
          break;
        case '3':
          $estado = "Finalizadas";
          break;
        case '3-b':
          $estado = "Fanalizadas en Bodega";
          break;
      }
      $array['value'] = $row['estado'];
      $array['text'] = $estado;
      $newArrayEstado[] = $array;
    }
    echo json_encode($newArrayEstado);
    break;
}
