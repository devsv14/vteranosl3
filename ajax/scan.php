<?php

require_once("../config/conexion.php");
//llamada al modelo categoria
require_once("../modelos/InsertarIMG.php");

$scan = new ScanActas();

switch ($_GET["op"]){
   case 'get_actas':
    $data= Array();
    $datos = $scan->getActas();
    foreach($datos as $row){
        $sub_array = array();
        $inputCorrelativo = '<div class="input-group" style="width: 168px">
        <input type="text" readonly onchange="editCorrAmpo(this)" id="'.$row["id_acta"].'" value="'.$row["correlativo_ampo"].'" class="form-control">
        <div class="input-group-append">
          <span class="input-group-text" style="cursor:pointer" onclick="toggleInputCorr(this,\''.$row["id_acta"].'\')" id="basic-addon2"><i class="fas fa-pen-square"></i></span>
        </div>
      </div>';
        $sub_array[] = $row["id_acta"];
        $siUploadActa = (int)$row['upload_acta'] == 1 ? '<span class="badge badge-success" style="padding: 2px; font-size: 11px">'.$row["paciente"].' <i class="fas fa-check-circle" style="font-size:14px"></i></span>' : $row["paciente"];
        $sub_array[] = $siUploadActa;
        $sub_array[] = $row["dui_acta"];
        $sub_array[] = $inputCorrelativo;
        $sub_array[] = $row["sucursal"];
        $estadoString = $row["upload_acta"] == 0 ? 'Pendiente' : 'Escaneada';
        $sub_array[] =  $estadoString;
        $sub_array[] = '<i class="fa fa-upload fa-2x" aria-hidden="true" style="color: #008061;cursor: pointer" onClick="cargarActa('.$row["id_acta"].',\''.$row["dui_acta"].'\',\''.$row["paciente"].'\',\''.$row["fecha_impresion"].'\',\''.$row["sucursal"].'\')" data-toggle="tooltip" data-placement="bottom" title="CREAR PDF DE ACTAS FIRMADAS"></i>';
        $sub_array[] = '<i class="fas fa-file-pdf fa-2x" aria-hidden="true" style="color:#b14e4e; cursor:pointer" onClick="showPDFUploadActa(\''.$row["id_acta"].'\',\''.$row["sucursal"].'\',\''.$row["paciente"].'\',\''.$row["dui_acta"].'\')" data-toggle="tooltip" data-placement="bottom" title="Vista previa de PDF"></i>';

        $sub_array[] = '<i class="fas fa-trash fa-2x" aria-hidden="true" style="color:#b14e4e; cursor:pointer" onClick="confirmDelUploadActa(\''.$row["id_acta"].'\',\''.$row["sucursal"].'\',\''.$row["paciente"].'\',\''.$row["dui_acta"].'\')" data-toggle="tooltip" data-placement="bottom" title="Vista previa de PDF"></i>';

        $data[] = $sub_array;
      }

      $results = array(
      "sEcho"=>1, //Información para el datatables
      "iTotalRecords"=>count($data), //enviamos el total registros al datatable
      "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
      "aaData"=>$data);
      echo json_encode($results); 
    break;

    case 'get_ampo_acta':
        //Comprobacion existencia de expedientes cargados a drive
        $id_acta = isset($_POST['id_acta']) ? $_POST['id_acta'] : '';
        $dui = isset($_POST['dui']) ? $_POST['dui'] : '';
        if($scan->verifyExistsActasFir($id_acta,$dui)){
            $msg = 'exists';
            echo json_encode($msg);
        }else{
            $data = $scan->getActasPorSucursal();
            echo json_encode($data);
        }
        break;
    case 'upload_images_drive':

        require '../api-drive/vendor/autoload.php';
        $client = new Google_Client();
        $client->setAuthConfig('../modelos/inabve-actas-40f976130a1b.json');
        $client->addScope(Google_Service_Drive::DRIVE_FILE);
        //$data = json_decode(file_get_contents('php://input'), true);       
        $sucursal_ampo = $_POST['sucursal_acta'];
        $dui = $_POST['dui_acta'];
        $id_acta = $_POST['id_acta_ampo'];
        $ampo = $_POST['ampo_acta'];
        $correlativo = ""; //$_POST['corrInabve'];
        $scan->uploadImages($client,$sucursal_ampo,$dui, $id_acta,$ampo,$correlativo);
        break;
    case 'getScanActasUpload':
        $id_acta = isset($_POST['id_acta']) ? $_POST['id_acta'] : '';
        $data = $scan->getScanActasUpload($id_acta);
        echo json_encode($data);
        break;
    case 'removeUploadActa':
        $result = $scan->deleteUploadActa($_POST['id_acta']);
        echo $result ? json_encode('success') : json_encode('err');
        break;
    case 'get_actas_ampo':
        $data= Array();
        $datos = $scan->getAMPOActa();
        foreach($datos as $row){
            $sub_array = array();
            $sub_array[] = $row["id_acta"];
            $sub_array[] = $row["paciente"];
            $sub_array[] = $row['ampo'];
            $sub_array[] = $row["dui"];
            $sub_array[] = $row["sucursal"];
            $sub_array[] = $row["estado"] == 0 ? 'Pendiente' : 'Escaneada';
            $data[] = $sub_array;
            }
    
            $results = array(
            "sEcho"=>1, //Información para el datatables
            "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            "aaData"=>$data);
            echo json_encode($results); 
        break;
    case 'get_acta_dui_idActa':
        $value = isset($_POST['busqueda']) ? $_POST['busqueda'] : '';
        $data = $scan->getActasPorIdDui($value);
        echo json_encode($data);
        break;
    case 'updCorrAmpo':
        $id_acta = (int)$_POST['id_acta'];
        $correlativo = trim($_POST['valor']);
        $result = $scan->updCorrAmpoActa($id_acta,$correlativo);
        if($result){
            echo json_encode('success');
        }else{
            echo json_encode('error');
        }
        break;
}