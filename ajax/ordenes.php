<?php

require_once("../config/conexion.php");
//llamada al modelo categoria
require_once("../modelos/Ordenes.php");
require_once("../modelos/Citados.php");
require_once("../modelos/OrdenesLenti.php");
$ordenes = new Ordenes();
$citados = new Citados();
$lentiOrdenes = new ordenesLenti();

switch ($_GET["op"]) {

  case 'comprobar_exit_DUI_pac':
    $datos = $ordenes->comprobar_exit_DUI_pac($_POST['dui_pac']);
    echo json_encode($datos[0]);
  break;

  case 'crear_barcode':
    $datos = $ordenes->comprobar_existe_correlativo($_POST["codigo"]);
    if (is_array($datos) == true and count($datos) == 0) {
      $ordenes->crea_barcode($_POST["codigo"]);
      $variable = 'Exito';
      echo json_encode(array("bla" => $variable));
    }
    break;

  case 'sucursales_optica':
    $sucursales = $ordenes->get_sucursales_optica($_POST["optica"]);
    $options = "<option value='0'>Seleccionar sucursal...</option>";

    for ($i = 0; $i < sizeof($sucursales); $i++) {
      $options .= "<option value='" . $sucursales[$i]["id_sucursal"] . "'>" . $sucursales[$i]["direccion"] . "</option>";
    }

    echo $options;

    break;

  case 'registrar_orden':
    date_default_timezone_set('America/El_Salvador');
    //Validacion de datos de paciente
    if($_POST['paciente'] == "" || $_POST["dui"] == ""  || $_POST["municipio"] == "" || $_POST["instit"] == ""){
      $mensaje = "datos_incorrectos";
      echo json_encode($mensaje);
      return 0;
    }
    $now = date("dmY");
    $validate = $_POST["validate"];
    $fecha = date('d-m-Y');
    if ($validate != 1) {
      $correlativos = $ordenes->get_correlativo_orden($fecha);
      if (is_array($correlativos) == true and count($correlativos) > 0) {
        foreach ($correlativos as $row) {
          $numero_orden = substr($row["codigo"], 11, 19) + 1;
          $nuevo_correlativo = $now . $numero_orden;
        }
      } else {
        $nuevo_correlativo = $now . "1";
      }
      $dui = $ordenes->comprobar_exit_DUI_pac($_POST['dui']);
      
      $prefijo_sucursal = $ordenes->get_prefijo_sucursal($_POST['sessionSucursal']);
      $new_codigo_orden = $prefijo_sucursal.$nuevo_correlativo;
      $datos = $ordenes->validar_correlativo_orden($new_codigo_orden);
      //Nuevo codigo orden + prefijo de sucursal
      if (count($datos) == 0 and count($dui)==0) {
          $result = $ordenes->registrar_orden($new_codigo_orden, $_POST['paciente'], $_POST['od_pupilar'], $_POST['oipupilar'], $_POST["odlente"], $_POST["oilente"], $_POST['id_aro'], $_POST["id_usuario"], $_POST["observaciones_orden"], $_POST["dui"], $_POST["od_esferas"], $_POST["od_cilindros"], $_POST["od_eje"], $_POST["od_adicion"], $_POST["oi_esferas"], $_POST["oi_cilindros"], $_POST["oi_eje"], $_POST["oi_adicion"], $_POST["tipo_lente"], $_POST["edad"], $_POST["ocupacion"], $_POST["avsc"], $_POST["avfinal"], $_POST["avsc_oi"], $_POST["avfinal_oi"], $_POST["telefono"], $_POST["genero"], $_POST["user"], $_POST["depto"], $_POST["municipio"], $_POST["instit"], $_POST["patologias"], $_POST["color"], $_POST["indice"], $_POST["id_cita"], $_POST["sucursal"], $_POST['categoria_lente'], $_POST['laboratorio'],$_POST['titular'],$_POST['dui_titular'],$_POST['modelo_aro_orden'],$_POST['marca_aro_orden'],$_POST['material_aro_orden'],$_POST['color_aro_orden'],$_POST['usuario_lente']);
          //validacion de insertado
          if($result){
            //Cambia el estado de la cita
            if($_POST["id_cita"] != ""){
              $citados->updateEstadoCita($_POST["id_cita"]);
            }
            $data = ["mensaje" => "exito", "id_orden_lab"=>$result];
            echo json_encode($data);
          }else{
            $mensaje = "error";
            echo json_encode($mensaje);
          }
          
        }else{
          $mensaje = "dui_existe";
          echo json_encode($mensaje);
        }   
    } else {
      $estado_orden = $ordenes->get_data_orden_estado($_POST["codigo"]);
      if($estado_orden > 1){
        $mensaje = "en_proceso";
        echo json_encode($mensaje);
      }else{
        $result = $ordenes->editar_orden($_POST["codigo"], $_POST['paciente'], $_POST['od_pupilar'], $_POST['oipupilar'], $_POST["odlente"], $_POST["oilente"], $_POST['id_aro'], $_POST["id_usuario"], $_POST["observaciones_orden"], $_POST["dui"], $_POST["od_esferas"], $_POST["od_cilindros"], $_POST["od_eje"], $_POST["od_adicion"], $_POST["oi_esferas"], $_POST["oi_cilindros"], $_POST["oi_eje"], $_POST["oi_adicion"], $_POST["tipo_lente"], $_POST["edad"], $_POST["ocupacion"], $_POST["avsc"], $_POST["avfinal"], $_POST["avsc_oi"], $_POST["avfinal_oi"], $_POST["telefono"], $_POST["genero"], $_POST["user"], $_POST["depto"], $_POST["municipio"], $_POST["instit"], $_POST["patologias"], $_POST["color"], $_POST["indice"], $_POST["id_cita"], $_POST["sucursal"], $_POST['categoria_lente'], $_POST['laboratorio'],$_POST['titular'],$_POST['dui_titular'],$_POST['id_titular'],$_POST['modelo_aro_orden'],$_POST['marca_aro_orden'],$_POST['material_aro_orden'],$_POST['color_aro_orden'],$_POST['usuario_lente'],$_POST['old_id_aro'],$_POST['obser_edicion']);
        if($result){
          $mensaje = "edit_orden";
        }else{
          $mensaje = "error";
        }
        echo json_encode($mensaje);
      }
    }

    break;

  case "get_correlativo_orden":
    date_default_timezone_set('America/El_Salvador');
    $now = date("dmY");
    $fecha = date('d-m-Y');
    $datos = $ordenes->get_correlativo_orden($fecha);

    if (is_array($datos) == true and count($datos) > 0) {
      foreach ($datos as $row) {
        $numero_orden = substr($row["codigo"], 8, 15) + 1;
        $output["codigo_orden"] = $now . $numero_orden;
      }
    } else {
      $output["codigo_orden"] = $now . '1';
    }
    echo json_encode($output);

  break;

  case 'get_ordenes':
    $datos = $ordenes->get_ordenes();
    $data = array();
    $about = "about:blank";
    $print = "print_popup";
    $ancho = "width=600,height=500";
    foreach ($datos as $row) {
      $sub_array = array();

      $sub_array[] = $row["id_orden"];
      $sub_array[] = $row["codigo"];
      $sub_array[] = strtoupper($row["paciente"]);
      $sub_array[] = $row["dui"];
      $sub_array[] = date("d-m-Y", strtotime($row["fecha"]));
      $sub_array[] = '<button type="button" class="btn btn-sm bg-light" onClick="verEditar(\'' . $row["codigo"] . '\',\'' . $row["paciente"] . '\',\'' . $row["dui"] . '\')"><i class="fa fa-eye" aria-hidden="true" style="color:blue"></i></button>';
      $sub_array[] = '<button type="button"  class="btn btn-xs bg-light" onClick="eliminarBeneficiario(\'' . $row["codigo"] . '\')"><i class="fa fa-trash" aria-hidden="true" style="color:red"></i></button>';

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

  case 'get_ordenes_dig':

    $data = array();

    $datos = $ordenes->get_ordenes($_POST["sucursal"], $_POST["permiso_listar"]);

    $contador = count($datos) + 1;
    foreach ($datos as $row) {
      $sub_array = array();
      $sub_array[] = $contador -= 1;
      $sub_array[] = '<b style="color: #148F77; font-weight: 900;">'.$row["id_orden"].'</b>';
      $sub_array[] = date("d-m-Y", strtotime($row["fecha"]));
      $sub_array[] = strtoupper($row["paciente"]);
      $sub_array[] = $row["dui"];
      $sub_array[] = $row["telefono"];
      $sub_array[] = $row["tipo_lente"];
      $sub_array[] = $row["sucursal"];
      //Validacion
    //  if(in_array('ediccion_orden',$_SESSION['names_permisos'])){
        $sub_array[] = '<button type="button"  class="btn btn-sm bg-light" onClick="verEditar(\'' . $row["codigo"] . '\',\'' . $row["paciente"] . '\',\''.$row["id_aro"].'\',\''.$row["institucion"].'\',\''.$row["id_cita"].'\')"><i class="fa fa-eye" aria-hidden="true" style="color:blue"></i></button>';
    //  }
      $sub_array[] = '<button type="button"  class="btn btn-xs bg-light" onClick="eliminarBeneficiario(\'' . $row["codigo"] . '\')"><i class="fa fa-trash" aria-hidden="true" style="color:red"></i></button>';

      $data[] = $sub_array;
    }

    $results = array(
      "sEcho" => 1, //Información para el datatables
      "iTotalRecords" => count($data), //enviamos el total registros al datatable
      "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
      "aaData" => $data
    );
    echo json_encode($results);
    // echo $_POST["permiso_listar"];
    break;

  case 'get_data_orden':

    $datos = $ordenes->get_data_orden($_POST["codigo"], $_POST["paciente"],$_POST['id_aro'],$_POST['institucion'],$_POST['id_cita']);
    echo json_encode($datos[0]);

    break;


  case 'eliminar_orden':
    $data = $ordenes->eliminar_orden($_POST["codigo"]);
    if($data){
      $mensaje = "orden_proceso";
    }else{
      $mensaje = "Ok";
    }
    echo json_encode($mensaje);
    break;

  case 'show_create_order':
    $datos = $ordenes->show_create_order($_POST["codigo"]);
    foreach ($datos as $row) {
      $output["info_orden"] = "Creado por: " . $row["nombres"] . " * " . $row["fecha_correlativo"];
    }
    echo json_encode($output);
    break;


  case 'listar_ordenes_enviar':
    $datos = $ordenes->get_ordenes();
    $data = array();
    $i = 0;

    foreach ($datos as $row) {
      $sub_array = array();
      if ($row["estado"] == 0) {
        $est = "Digitado";
        $titulo = "Enviar";
      }

      $sub_array[] = $row["id_orden"];
      $sub_array[] = '<input type="checkbox"class="form-check-input envio_order" value="' . $row["codigo"] . '" name="' . $row["paciente"] . '" id="orden_env' . $i . '">' . $titulo . '';
      $sub_array[] = strtoupper($row["paciente"]);
      $sub_array[] = date("d-m-Y", strtotime($row["fecha"]));
      $sub_array[] = '<button type="button"  class="btn btn-xs bg-light" onClick="verEditar(\'' . $row["codigo"] . '\',\'' . $row["paciente"] . '\')"><i class="fa fa-eye" aria-hidden="true" style="color:blue"></i></button>';
      $sub_array[] = $est;
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

  case 'enviar_orden':
    $ordenes->enviar_orden($_POST["numero_orden"]);
    echo json_encode("OK!");
    break;

  case 'listar_ordenes_enviadas':
    $datos = $ordenes->get_ordenes_enviadas();
    $data = array();
    $i = 0;

    foreach ($datos as $row) {
      $sub_array = array();
      $est = "Enviado";
      $titulo = "Recibir";

      $sub_array[] = $row["id_orden"];
      // $sub_array[] = $row["fecha"];
      $sub_array[] = '<input type="checkbox"class="form-check-input envio_order" value="' . $row["codigo"] . '" name="' . $row["paciente"] . '" id="orden_env' . $i . '">' . $titulo . '';
      $sub_array[] = strtoupper($row["paciente"]);
      $sub_array[] = date("d-m-Y", strtotime($row["fecha"]));
      $sub_array[] = '<button type="button"  class="btn btn-xs bg-light actions_orders" onClick="verEditar(\'' . $row["codigo"] . '\',\'' . $row["paciente"] . '\')"><i class="fa fa-eye" aria-hidden="true" style="color:blue"></i></button>';
      $sub_array[] = $est;
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

    /////////////////////////////////////ORDENES PENDIENTES LENTES //////////////
  case 'get_ordenes_por_enviar':

    if ($_POST["inicio"] == "0" and $_POST["hasta"] == "0" and $_POST["lente"] == "0") {
      $datos = $ordenes->get_ordenes_enviar_general($_POST["instit"]);
    } elseif ($_POST["inicio"] != "0" and $_POST["hasta"] != "0" and $_POST["lente"] == "0") {
      $datos = $ordenes->get_ordenes_por_enviar($_POST["inicio"], $_POST["hasta"]);
    } elseif ($_POST["inicio"] != "0" and $_POST["hasta"] != "0" and $_POST["lente"] != "0") {
      $datos = $ordenes->ordenEnviarFechaLente($_POST["inicio"], $_POST["hasta"], $_POST["lente"]);
    } elseif ($_POST["inicio"] == "0" and $_POST["hasta"] == "0" and $_POST["lente"] != "0") {
      $datos = $ordenes->ordenEnviarLente($_POST["lente"]);
    }
    $data = array();
    $tit = "Enviar";

    foreach ($datos as $row) {

      $od_esferas = ($row["od_esferas"] == "-" or $row["od_esferas"] == "") ? '' : "<span style='color:black'><b>Esf.</b> </span>" . $row["od_esferas"];
      $od_cilindro = ($row["od_cilindros"] == "-" or $row["od_cilindros"] == "") ? '' : "<span style='color:black'><b>Cil.</b> </span>" . $row["od_cilindros"];
      $od_eje = ($row["od_eje"] == "-" or $row["od_eje"] == "") ? '' : "<span style='color:black'><b>Eje.</b> </span>" . $row["od_eje"];
      $od_add = ($row["od_adicion"] == "-" or $row["od_adicion"] == "") ? '' : "<span style='color:blue'>Add. </span>" . $row["od_adicion"];
      //////////////////////////////////////////////////;//////////////////////////////////////////////////////////////////////
      $oi_esferas = ($row["oi_esferas"] == "-" or $row["oi_esferas"] == "") ? '' : "<span style='color:black'><b>Esf.</b> </span>" . $row["oi_esferas"];
      $oi_cilindro = ($row["oi_cilindros"] == "-" or $row["oi_cilindros"] == "") ? '' : "<span style='color:black'><b>Cil.</b> </span>" . $row["oi_cilindros"];
      $oi_eje = ($row["oi_eje"] == "-" or $row["oi_eje"] == "") ? '' : "<span style='color:black'><b>Eje.</b> </span>" . $row["oi_eje"];
      $oi_add = ($row["oi_adicion"] == "-" or $row["oi_adicion"] == "") ? '' : "<span style='color:blue'>Add. </span>" . $row["oi_adicion"];
      ///////////////////////////   
      $sub_array = array();
      $sub_array[] = $row["id_orden"];
      $sub_array[] = date("d-m-Y", strtotime($row["fecha"]));
      $sub_array[] = '<div style="text-align:center"><input type="checkbox" class="form-check-input ordenes_enviar" value="' . $row["id_orden"] . '" name="' . $row["paciente"] . '" id="' . $row["codigo"] . '" style="text-align: center"><span style="color:white">.</span></div>';
      $sub_array[] = $row["institucion"];
      $sub_array[] = "<span style='font-size:11px'>" . strtoupper($row["paciente"]) . "</span>";
      $sub_array[] = $od_esferas . " " . $od_cilindro . " " . $od_eje . " " . $od_add;
      $sub_array[] = $oi_esferas . " " . $oi_cilindro . " " . $oi_eje . " " . $oi_add;
      $sub_array[] = $row["tipo_lente"];
      $sub_array[] = '<div style="text-align:center"><button type="button"  class="btn btn-sm bg-light show_panel_admin" onClick="verEditar(\'' . $row["codigo"] . '\',\'' . $row["paciente"] . '\')"><i class="fa fa-eye" aria-hidden="true" style="color:blue"></i></button></div>';

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
    ///////////////////////////////LISTAR ORDENES ENVIADAS LENTES///////////////
  case 'get_ordenes_env':

    if ($_POST["cat_lente"] != "0" and $_POST["inicio"] == "0" and $_POST["hasta"] == "0" and $_POST["tipo_lente"] == "0") {
      $datos = $ordenes->getOrdenesEnvBase($_POST['laboratorio'], $_POST["cat_lente"], $_POST["instit"]);
    } elseif ($_POST["tipo_lente"] != "0" and $_POST["cat_lente"] == "0" and $_POST["hasta"] == "0" and $_POST["hasta"] == "0") {
      $datos = $ordenes->getOrdenesEnvLente($_POST['laboratorio'], $_POST["tipo_lente"], $_POST["instit"]);
    } elseif ($_POST["inicio"] != "0" and $_POST["hasta"] != "0" and $_POST["cat_lente"] == "0" and $_POST["tipo_lente"] == "0") {
      $datos = $ordenes->getOrdenesEnvFechas($_POST['laboratorio'], $_POST["inicio"], $_POST["hasta"]);
    } elseif ($_POST["inicio"] == "0" and $_POST["hasta"] == "0" and $_POST["cat_lente"] != "0" and $_POST["tipo_lente"] != "0") {
      $datos = $ordenes->getOrdenesBaseLente($_POST['laboratorio'], $_POST["cat_lente"], $_POST["tipo_lente"], $_POST["instit"]);
    } elseif ($_POST["inicio"] != "0" and $_POST["hasta"] != "0" and $_POST["cat_lente"] != "0" and $_POST["tipo_lente"] != "0") {
      $datos = $ordenes->get_ordenes_env($_POST["laboratorio"], $_POST["cat_lente"], $_POST["inicio"], $_POST["hasta"], $_POST["tipo_lente"]);
    } elseif ($_POST["laboratorio"] == "0") {
      $datos = $ordenes->get_ordenes_env_general();
    } elseif ($_POST["cat_lente"] == "0" and $_POST["inicio"] == "0" and $_POST["hasta"] == "0" and $_POST["tipo_lente"] == "0" and $_POST["instit"] == '0') {
      $datos = $ordenes->get_ordenes_por_lab($_POST["laboratorio"]);
    }
    $data = array();
    $tit = "Recibir";

    foreach ($datos as $row) {

      $od_esferas = ($row["od_esferas"] == "-" or $row["od_esferas"] == "") ? '' : "<span style='color:black'><b>Esf.</b> </span>" . $row["od_esferas"];
      $od_cilindro = ($row["od_cilindros"] == "-" or $row["od_cilindros"] == "") ? '' : "<span style='color:black'><b>Cil.</b> </span>" . $row["od_cilindros"];
      $od_eje = ($row["od_eje"] == "-" or $row["od_eje"] == "") ? '' : "<span style='color:black'><b>Eje.</b> </span>" . $row["od_eje"];
      $od_add = ($row["od_adicion"] == "-" or $row["od_adicion"] == "") ? '' : "<span style='color:blue'>Add. </span>" . $row["od_adicion"];
      //////////////////////////////////////////////////;//////////////////////////////////////////////////////////////////////
      $oi_esferas = ($row["oi_esferas"] == "-" or $row["oi_esferas"] == "") ? '' : "<span style='color:black'><b>Esf.</b> </span>" . $row["oi_esferas"];
      $oi_cilindro = ($row["oi_cilindros"] == "-" or $row["oi_cilindros"] == "") ? '' : "<span style='color:black'><b>Cil.</b> </span>" . $row["oi_cilindros"];
      $oi_eje = ($row["oi_eje"] == "-" or $row["oi_eje"] == "") ? '' : "<span style='color:black'><b>Eje.</b> </span>" . $row["oi_eje"];
      $oi_add = ($row["oi_adicion"] == "-" or $row["oi_adicion"] == "") ? '' : "<span style='color:blue'>Add. </span>" . $row["oi_adicion"];
      ///////////////////////////   
      $sub_array = array();
      $sub_array[] = $row["id_orden"];
      $sub_array[] = date("d-m-Y", strtotime($row["fecha"]));
      $sub_array[] = $row["institucion"];
      $sub_array[] = '<div data-toggle="tooltip" title="Fecha envío: ' . $row["fecha"] . '" style="text-align:center"><input type="checkbox" class="form-check-input ordenes_enviar" value="' . $row["id_orden"] . '" name="' . $row["paciente"] . '" id="' . $row["codigo"] . '" style="text-align: center"><span style="color:white">.</span></div>';
      $sub_array[] = "<span style='font-size:11px' data-toggle='tooltip' title='Fecha envío: " . $row["fecha"] . "'>" . strtoupper($row["paciente"]) . "</span>";
      $sub_array[] = $od_esferas . " " . $od_cilindro . " " . $od_eje . " " . $od_add;
      $sub_array[] = $oi_esferas . " " . $oi_cilindro . " " . $oi_eje . " " . $oi_add;
      $sub_array[] = $row["tipo_lente"];
      $sub_array[] = $row["categoria"];
      $sub_array[] = $row["laboratorio"];
      $sub_array[] = $row["modelo_aro"];
      $sub_array[] = '<div style="text-align:center"><button type="button"  class="btn btn-sm bg-light show_panel_admin" onClick="verEditar(\'' . $row["codigo"] . '\',\'' . $row["paciente"] . '\')"><i class="fa fa-eye" aria-hidden="true" style="color:blue"></i></button></div>';
      $sub_array[] = '<i class="fas fa-edit" aria-hidden="true" style="color:green" onClick="editaLaboratorio(\'' . $row["paciente"] . '\',\'' . $row["categoria"] . '\',\'' . $row["laboratorio"] . '\',\'' . $row["codigo"] . '\')"></i></button>';
      $data[] = $sub_array;
    }

    $results = array(
      "sEcho" => 1, //Información para el datatables
      "iTotalRecords" => count($data), //enviamos el total registros al datatable
      "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
      "aaData" => $data
    );
    echo json_encode($results);
    // echo json_encode($stmt);
    break;
    ////////////////////////LISTAR ORDENES ENVIADAS A LABORATORIO
  case 'get_ordenes_enviadas_lab':
    if ($_POST["laboratorio"] != '0') {
      $datos = $ordenes->getOrdenesEnviadasLab($_POST["laboratorio"], $_POST["cat_lente"], $_POST["inicio"], $_POST["hasta"], $_POST["tipo_lente"]);
    } else {
      $datos = $ordenes->getEnviosGeneral();
    }
    $data = array();
    $tit = "Recibir";

    foreach ($datos as $row) {
      $clase = "";
      $od_esferas = ($row["od_esferas"] == "-" or $row["od_esferas"] == "") ? '' : "<span style='color:black'><b>Esf.</b> </span>" . $row["od_esferas"];
      $od_cilindro = ($row["od_cilindros"] == "-" or $row["od_cilindros"] == "") ? '' : "<span style='color:black'><b>Cil.</b> </span>" . $row["od_cilindros"];
      $od_eje = ($row["od_eje"] == "-" or $row["od_eje"] == "") ? '' : "<span style='color:black'><b>Eje.</b> </span>" . $row["od_eje"];
      $od_add = ($row["od_adicion"] == "-" or $row["od_adicion"] == "") ? '' : "<span style='color:blue'>Add. </span>" . $row["od_adicion"];
      //////////////////////////////////////////////////;//////////////////////////////////////////////////////////////////////
      $oi_esferas = ($row["oi_esferas"] == "-" or $row["oi_esferas"] == "") ? '' : "<span style='color:black'><b>Esf.</b> </span>" . $row["oi_esferas"];
      $oi_cilindro = ($row["oi_cilindros"] == "-" or $row["oi_cilindros"] == "") ? '' : "<span style='color:black'><b>Cil.</b> </span>" . $row["oi_cilindros"];
      $oi_eje = ($row["oi_eje"] == "-" or $row["oi_eje"] == "") ? '' : "<span style='color:black'><b>Eje.</b> </span>" . $row["oi_eje"];
      $oi_add = ($row["oi_adicion"] == "-" or $row["oi_adicion"] == "") ? '' : "<span style='color:blue'>Add. </span>" . $row["oi_adicion"];
      ///////////////////////////
      if ($row['estado'] == 3) {
        $clase = "fas fa-print";
      }
      $sub_array = array();
      $sub_array[] = $row["id_orden"];
      $sub_array[] = date("d-m-Y", strtotime($row["fecha"]));
      $sub_array[] = '<div data-toggle="tooltip" title="Fecha envío: ' . $row["fecha"] . '" style="text-align:center"><input type="checkbox" class="form-check-input ordenes_enviar" value="' . $row["id_orden"] . '" name="' . $row["paciente"] . '" id="' . $row["codigo"] . '" style="text-align: center"><span style="color:white">.</span></div>';
      $sub_array[] = "<span style='font-size:11px' data-toggle='tooltip' title='Fecha envío: " . $row["fecha"] . "'>" . strtoupper($row["paciente"]) . "</span><i class='" . $clase . "'' style='color:blue;font-size;8px'></i>";
      $sub_array[] = $od_esferas . " " . $od_cilindro . " " . $od_eje . " " . $od_add;
      $sub_array[] = $oi_esferas . " " . $oi_cilindro . " " . $oi_eje . " " . $oi_add;
      $sub_array[] = $row["tipo_lente"];
      $sub_array[] = $row["categoria"];
      $sub_array[] = $row["laboratorio"];
      $sub_array[] = '<div style="text-align:center"><button type="button"  class="btn btn-sm bg-light show_panel_admin" onClick="verEditar(\'' . $row["codigo"] . '\',\'' . $row["paciente"] . '\')"><i class="fa fa-eye" aria-hidden="true" style="color:blue"></i></button></div>';
      $sub_array[] = '<i class="fas fa-edit" aria-hidden="true" style="color:green" onClick="editaLaboratorio(\'' . $row["paciente"] . '\',\'' . $row["categoria"] . '\',\'' . $row["laboratorio"] . '\',\'' . $row["codigo"] . '\')"></i></button>';
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


    /*******TRASLADO DE DATA A VETERANOS ******* */
  case 'enviar_ordenes':

    if ($_POST["destino"] != "Lenti") {
      $ordenes->enviarOrdenes();
      $mensaje = "Ok";
      echo json_encode($mensaje);
    } elseif ($_POST["destino"] == "Lenti") {
      $arrayOrdenes = array();
      $arrayOrdenes = json_decode($_POST["arrayEnvio"]);
      foreach ($arrayOrdenes as $k => $v) {
        //$lentiOrdenes->trasladoOrdenesLenti($v->id_item);
        $orden = $ordenes->get_data_orden($v->id_item, $v->paciente);
        foreach ($orden as $row) {
          $response = $lentiOrdenes->trasladoOrdenesLenti($row["codigo"], $row["paciente"], $_POST["user"], $row["tipo_lente"], $row["od_esferas"], $row["od_cilindros"], $row["od_eje"], $row["od_adicion"], $row["oi_esferas"], $row["oi_cilindros"], $row["oi_eje"], $row["oi_adicion"], $row["marca_aro"], $row["modelo_aro"], $row["horizontal_aro"], $row["vertical_aro"], $row["puente_aro"], $row["pupilar_od"], $row["pupilar_oi"], $row["lente_od"], $row["lente_oi"], $_POST["categoria_len"]);
          echo $response . "<br>";
          echo $v->id_item . "<br>";
          $ordenes->updateOrdenAct($response, $v->id_item, $_POST["categoria_len"]);
          $ordenes->agregarHistorial($v->id_item, $_POST["user"]);
        }
      }
      $mensaje = "Ok";
      echo json_encode($mensaje);
    }
    break;

  case 'reset_tables':
    $ordenes->resetTables();
    $mensaje = "Ok";
    echo json_encode($mensaje);
    break;

  case 'reset_tables_print':
    $ordenes->resetTablesPrint();
    $mensaje = "Ok";
    echo json_encode($mensaje);
    break;


  case 'get_ordenes_enviar':
    $datos = $ordenes->get_ordenes_enviar();
    $data = array();
    $about = "about:blank";
    $print = "print_popup";
    $ancho = "width=600,height=500";
    foreach ($datos as $row) {
      $sub_array = array();

      $sub_array[] = $row["id_orden"];
      $sub_array[] = '<div style="margin-bottom:3px"><input type="checkbox"class="form-check-input envio_order" value="' . $row["codigo"] . '" name="' . $row["paciente"] . '" id="orden_env' . $i . '"><span style="color:white">.</span>';
      $sub_array[] = $row["paciente"];
      $sub_array[] = $row["marca_aro"];
      $sub_array[] = $row["modelo_aro"];
      $sub_array[] = $row["horizontal_aro"];
      $sub_array[] = $row["vertical_aro"];
      $sub_array[] = $row["puente_aro"];
      $sub_array[] = $row["color_varilla"];
      $sub_array[] = $row["color_frente"];
      $sub_array[] = '<i class="fa fa-image" aria-hidden="true" style="color:blue" onClick="verImagen(\'' . $row["img"] . '\')"></i></button>';
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

    ////////////////////////ORDENES PROCESANDO /////////////////
  case 'get_ordenes_procesando':
    $datos = $ordenes->get_ordenes_procesando();
    $data = array();
    $tit = "Recibir";

    foreach ($datos as $row) {

      $od_esferas = ($row["od_esferas"] == "-" or $row["od_esferas"] == "") ? '' : "<span style='color:black'><b>Esf.</b> </span>" . $row["od_esferas"];
      $od_cilindro = ($row["od_cilindros"] == "-" or $row["od_cilindros"] == "") ? '' : "<span style='color:black'><b>Cil.</b> </span>" . $row["od_cilindros"];
      $od_eje = ($row["od_eje"] == "-" or $row["od_eje"] == "") ? '' : "<span style='color:black'><b>Eje.</b> </span>" . $row["od_eje"];
      $od_add = ($row["od_adicion"] == "-" or $row["od_adicion"] == "") ? '' : "<span style='color:blue'>Add. </span>" . $row["od_adicion"];
      //////////////////////////////////////////////////;//////////////////////////////////////////////////////////////////////
      $oi_esferas = ($row["oi_esferas"] == "-" or $row["oi_esferas"] == "") ? '' : "<span style='color:black'><b>Esf.</b> </span>" . $row["oi_esferas"];
      $oi_cilindro = ($row["oi_cilindros"] == "-" or $row["oi_cilindros"] == "") ? '' : "<span style='color:black'><b>Cil.</b> </span>" . $row["oi_cilindros"];
      $oi_eje = ($row["oi_eje"] == "-" or $row["oi_eje"] == "") ? '' : "<span style='color:black'><b>Eje.</b> </span>" . $row["oi_eje"];
      $oi_add = ($row["oi_adicion"] == "-" or $row["oi_adicion"] == "") ? '' : "<span style='color:blue'>Add. </span>" . $row["oi_adicion"];
      ///////////////////////////   
      $sub_array = array();
      $sub_array[] = $row["id_orden"];
      $sub_array[] = $row["fecha"];
      $sub_array[] = "<span style='font-size:11px' data-toggle='tooltip' title='Fecha recibido: " . $row["fecha"] . "'>" . strtoupper($row["paciente"]) . "</span>";
      $sub_array[] = $od_esferas . " " . $od_cilindro . " " . $od_eje . " " . $od_add;
      $sub_array[] = $oi_esferas . " " . $oi_cilindro . " " . $oi_eje . " " . $oi_add;
      $sub_array[] = $row["tipo_lente"];
      $sub_array[] = $row["laboratorio"];
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

  case 'enviar_lente':
    $ordenes->enviarLente($_POST["codigo"], $_POST["destino"], $_POST["usuario"]);
    $messages[] = 'ok';
    if (isset($messages)) {
?>
        <?php
        foreach ($messages as $message) {
          echo json_encode($message);
        }
        ?>
        <?php
      }
      //mensaje error
      if (isset($errors)) { ?>
        <?php
        foreach ($errors as $error) {
          echo json_encode($error);
        }
        ?>
        <?php }

      break;

    case 'editar_envio':
      $ordenes->editarEnvio($_POST["codigo"], $_POST["dest"], $_POST["cat"], $_POST["paciente"]);
      $messages[] = 'ok';
      if (isset($messages)) {
        ?>
        <?php
        foreach ($messages as $message) {
          echo json_encode($message);
        }
        ?>
        <?php
      }
      //mensaje error
      if (isset($errors)) { ?>
        <?php
        foreach ($errors as $error) {
          echo json_encode($error);
        }
        ?>
        <?php }

      break;

    case 'registrar_rectificacion':
      $ordenes->registrarRectificacion($_POST['motivo'], $_POST['estado_aro'], $_POST['usuario'], $_POST["codigo"], $_POST["correlativo"]);
      break;

    case 'correlativo_rectificacion':
      $correlativo = $ordenes->getCorrelativoRectificacion();
      if (is_array($correlativo) == true and count($correlativo) > 0) {
        foreach ($correlativo as $row) {
          $codigo = $row["codigo_rectifi"];
          $cod = (substr($codigo, 2, 11)) + 1;
          $output["correlativo"] = "R-" . $cod;
        }
      } else {
        $output["correlativo"] = "R-1";
      }
      echo json_encode($output);

      break;

      //******************* GET HISTORIAL DE ORDEN ******************//
    case 'ver_historial_orden':
      $data = $ordenes->getHistorialOrden($_POST['codigo'],$_POST['dui_paciente']);
      $datos = [];
      foreach($data as $row){
        $arrayData = [
          "id_accion" => $row['id_accion'],
          "nombres" => $row['nombres'],
          "tipo_accion" => $row['tipo_accion'],
          "observaciones" => $row['observaciones'],
          "fecha" => date('d-m-Y H:i:s',strtotime($row['fecha']))
        ];
        array_push($datos,$arrayData);
      }
      echo json_encode($datos);
      break;


    case 'listar_ordenes_rect':
      $ordenes->getTablasRectificaciones($_POST["codigoOrden"]);
      break;

    case 'listar_det_orden_act':
      $ordenes->getDetOrdenActRec($_POST["codigoOrden"]);
      break;

    case 'listar_rectificaciones':

      $data = array();
      $datos = $ordenes->listar_rectificaciones();
      foreach ($datos as $row) {
        $sub_array = array();
        $sub_array[] = $row["id_rectifi"];
        $sub_array[] = $row["codigo_rectifi"];
        $sub_array[] = $row["fecha"] . " " . $row["hora"];
        $sub_array[] = $row["usuario"];
        $sub_array[] = $row["paciente"];
        $sub_array[] = '<i class="fas fa-eye" aria-hidden="true" style="color:blue" onClick="detRecti(\'' . $row["codigo_orden"] . '\')"></i>';

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


    case 'get_estadisticas':

      $data = array();
      $datos = $ordenes->get_estadisticas_orden($_POST["inicio"], $_POST["hasta"]);
      foreach ($datos as $row) {
        $sub_array = array();
        $sub_array[] = $row["nombres"];
        $sub_array[] = $row["cantidad"];
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
      
    case 'valida_licitacion_1':
         $ordenes->buscaOrdenLicitacion1($_POST["dui"]);
        break;
    case 'get_reporte_lentes_resumen':
      // Null validation
      $request = [
        "desde" => isset($_POST['desde']) ? $_POST['desde']: '',
        "hasta" => isset($_POST['hasta']) ? $_POST['hasta']: '',
        "estadoOrdenes" => isset($_POST['estadoOrdenes']) ? $_POST['estadoOrdenes'] : ''
      ];
      $datos = $ordenes->get_reporte_lentes_resumen($request);
      $data = array();
      foreach ($datos as $row) {
        $descripcion = $ordenes->get_description_lente($row); // return description lente
        $sub_array = array();
        //$color='';
        if($row["cantidad"]-$descripcion['factura']>0){
            $color='blue';
        }elseif($row["cantidad"]-$descripcion['factura']==0){
            $color='green';
        }elseif($row["cantidad"]-$descripcion['factura']<0){
            $color='red';
        }
        $sub_array[] = '<td align="left">'.$descripcion['descripcion'].'</td>';
        $sub_array[] = $row["cantidad"];
        $sub_array[] = $descripcion['factura'];
        $sub_array[] = "<p style=\"color:$color\"><b>" . ($row["cantidad"] - $descripcion['factura']) . "</b></p>";
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
    case 'get_reporte_lentes':
      //Request data frontend
      $request = [
        "desdeFecha" => isset($_POST['desdeFecha']) ? $_POST['desdeFecha'] : '',
        "hastaFecha" => isset($_POST['hastaFecha']) ? $_POST['hastaFecha'] : '',
        "sucursal" => isset($_POST['sucursal']) ? $_POST['sucursal'] : ''
      ];
      $datos = $ordenes->get_reporte_lentes($request);
      $data = array();
      foreach ($datos as $row) {
        $descripcion = $ordenes->get_description_lente($row); // return description lente
        $sub_array = array();
        $sub_array[] = $descripcion['descripcion'];
        $sub_array[] = $row["sucursal"];
        $sub_array[] = $row["cantidad"];
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
      
    case 'marcar_rectificacion':
        $ordenes->marcarRectificacion();
        break;
  }