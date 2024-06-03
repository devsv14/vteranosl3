<?php

require_once("../config/conexion.php");
//llamada al modelo categoria
require_once("../modelos/Traslados.php");
$traslado = new Traslados();
switch ($_GET["op"]) {

	case 'getRXFinalOrden':
		$tipoBusqueda = isset($_POST['tipoBusqueda']) ? $_POST['tipoBusqueda'] : '';
		if ($tipoBusqueda == "RXFinal") {
			$datos2 = $traslado->getOrdenFilterRxRango();
		} else if ($tipoBusqueda == "tipo_lente") {
			$datos2 = $traslado->getOrdenTipoLente();
		}
		$data = [];
		foreach ($datos2 as $row) {
			$sub_array = array();
			$sub_array[] = $row["id_orden"];
			$sub_array[] = strtoupper($row["paciente"]);
			$sub_array[] = $row["dui"];
			$sub_array[] = $row["telefono"];
			$sub_array[] = "Esf. " . $row["od_esferas"] . " Cil." . $row["od_cilindros"] . "  Add." . $row["od_adicion"];
			$sub_array[] = "Esf. " . $row["oi_esferas"] . " Cil." . $row["oi_cilindros"] . " Add." . $row["oi_adicion"];
			$sub_array[] = date('d-m-Y', strtotime($row["fecha"]));
			$sub_array[] = $row["tipo_lente"];
			$sub_array[] = $row["int_llamadas"];
			$sub_array[] = '<button id="' . $row["id_orden"] . '" type="button" class="btn btn-sm bg-light" onClick="showDataRxL1(\'' . $row["dui"] . '\',this.id)"><i class="fa fa-eye" aria-hidden="true" style="color:blue"></i></button>';
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

		//////Fin codigo miguel

	case 'get_data_rx_l1':
		$data =  $traslado->rxDataImprimir($_POST["dui"]);
		echo json_encode($data);
		break;

	case 'crear_orden_traslado':
		$clasificacion = $_POST['clasificacion'];
		if ($clasificacion == 'contesta') {
			$traslado->crearOrdenTraslado($_POST['dui_act'], $clasificacion);
		} elseif ($clasificacion == 'nocontesta') {
			$intentos = $traslado->countIntentos($_POST['dui_act']);
			if (count($intentos) < 2) {
				$traslado->registraIntentoLLamadaL1($_POST['dui_act'], $clasificacion);
			} elseif ($intentos >= 2) {
				$traslado->crearOrdenTraslado($_POST['dui_act'], $clasificacion);
			}
		} elseif ($clasificacion == 'citarechazada' or $clasificacion == 'fallecido') {
			$traslado->cancelaCita($_POST['dui_act'], $clasificacion);
		}

		break;

	case 'get_orden_print_l1':
		$dataprint = $traslado->getOrdenPrintL1();
		foreach ($dataprint as $row) {
			$sub_array = array();
			$sub_array[] = '<input type="checkbox" class="check_selected_print" onClick="addUpdatePrintL1(this)" id="' . $row["dui"] . '" value="' . $row["dui"] . '">';
			$sub_array[] = strtoupper($row["paciente"]);
			$sub_array[] = $row["tipo_lente"];
			$sub_array[] = $row["dui"];
			$sub_array[] = "Esf. " . $row["od_esferas"] . " Cil." . $row["od_cilindros"] . "  Add." . $row["od_adicion"];
			$sub_array[] = "Esf. " . $row["oi_esferas"] . " Cil." . $row["oi_cilindros"] . " Add." . $row["oi_adicion"];

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

	case 'update_print_ordenl1':

		function fechaAleatoria($fechaInicio, $fechaFin)
		{
			$timestampInicio = strtotime($fechaInicio);
			$timestampFin = strtotime($fechaFin);
			$timestampAleatorio = rand($timestampInicio, $timestampFin);
			$fechaAleatoria = date('Y-m-d', $timestampAleatorio);
			return $fechaAleatoria;
		}

		$fechaInicio = '2022-11-15';
		$fechaFin = '2023-02-24';
		$fechaGenerada = fechaAleatoria($fechaInicio, $fechaFin);
		if ($fechaGenerada == '2022-12-25' || $fechaGenerada == '2023-01-01') {
			$fechaGenerada = date('Y-m-d', strtotime($fechaGenerada . ' +2 days'));
		}
		//$traslado->updatePrintOrdenL1($fechaGenerada);
		$traslado->updateOrdenFecha();
		break;
	case 'getPacienteById':
		$data =  $traslado->getPacienteById($_POST["dui"]);
		echo json_encode($data);
		break;
	case 'datatableUP':
		$datos = $traslado->datatableUp();
		$data = array();
		foreach ($datos as $row) {
			$sub_array = array();
			$sub_array[] = $row["codigo"];
			$sub_array[] = $row["correlativo"];
			$sub_array[] = strtoupper($row["paciente"]);
			$sub_array[] = $row["dui"];
			$sub_array[] = date('d-m-Y',strtotime($row["fecha_ant"]));
			$sub_array[] = date('d-m-Y',strtotime($row["fecha"]));
			$sub_array[] = $row["sucursal"];
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
