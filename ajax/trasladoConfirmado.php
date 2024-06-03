<?php

require_once("../config/conexion.php");
//llamada al modelo categoria
require_once("../modelos/TrasladoConfirmado.php");
$traslado = new Traslados();
switch ($_GET["op"]) {

	case 'getRXFinalOrden':
		$tipoBusqueda = isset($_POST['tipoBusqueda']) ? $_POST['tipoBusqueda'] : '';
		if ($tipoBusqueda == "RXFinal") {
			$datos2 = $traslado->getOrdenFilterRxRango();
		}else if($tipoBusqueda == "tipo_lente"){
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
			$sub_array[] = date('d-m-Y',strtotime($row["fecha"]));
			$sub_array[] = $row["tipo_lente"];
			$sub_array[] = '<button id="'.$row["id_orden"].'" type="button" class="btn btn-sm bg-light" onClick="showDataRxL1(\'' . $row["dui"] . '\')"><i class="fas fa-folder-plus"></i></button>';
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

		/* case 'crear_orden_traslado':
			$clasificacion = $_POST['clasificacion'];
			if($clasificacion=='contesta'){
				$traslado->crearOrdenTraslado($_POST['dui_act'],$clasificacion);
			}			
			break; */
			case 'crear_orden':
				$traslado->crearOrdenL1L2();
				break;

}
