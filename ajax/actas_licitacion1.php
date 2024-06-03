<?php

require_once("../config/conexion.php");
//llamada al modelo categoria
require_once("../modelos/ScanActasL1.php");

$scan = new ScanActasL1();

switch ($_GET["op"]){
   case 'get_actas':
    $data= Array();
    $datos = $scan->getActas();
    foreach($datos as $row){
        $sub_array = array();
        $sub_array[] = $row["id_orden"];
        $siUploadActa = (int)$row['upload_acta'] == 1 ? '<span class="badge badge-success" style="padding: 2px; font-size: 11px">'.$row["paciente"].' <i class="fas fa-check-circle" style="font-size:14px"></i></span>' : $row["paciente"];
        $sub_array[] = $siUploadActa;
        $sub_array[] = $row["dui"];
        $sub_array[] = $row["sucursal"];
        //$estadoString = $row["upload_acta"] == 0 ? 'Pendiente' : 'Escaneada';
        $sub_array[] =  '-';
        $sub_array[] = '<i class="fa fa-upload fa-2x" aria-hidden="true" style="color: #008061;cursor: pointer" onClick="cargarActa('.$row["id_orden"].',\''.$row["dui"].'\',\''.$row["paciente"].'\',\''.$row["fecha"].'\')" data-toggle="tooltip" data-placement="bottom" title="CREAR PDF DE ACTAS FIRMADAS"></i>';
        $sub_array[] = '<i class="fas fa-file-pdf fa-2x" aria-hidden="true" style="color:#b14e4e; cursor:pointer" onClick="showPDFUploadActa(\''.$row["id_orden"].'\',\''.$row["paciente"].'\',\''.$row["dui"].'\')" data-toggle="tooltip" data-placement="bottom" title="Vista previa de PDF"></i>';

        $sub_array[] = '<i class="fas fa-trash fa-2x" aria-hidden="true" style="color:#b14e4e; cursor:pointer" onClick="confirmDelUploadActa(\''.$row["id_orden"].'\',\''.$row["paciente"].'\',\''.$row["dui"].'\')" data-toggle="tooltip" data-placement="bottom" title="Vista previa de PDF"></i>';

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
        $id_orden = isset($_POST['id_orden']) ? $_POST['id_orden'] : '';
        $dui = isset($_POST['dui']) ? $_POST['dui'] : '';
        if($scan->verifyExistsActasFir($id_orden,$dui)){
            $msg = 'exists';
            echo json_encode($msg);
        }else{
            //$data = $scan->getActasPorSucursal();
            echo json_encode([
                'not-existe'
            ]);
        }
        break;
    case 'upload_images_drive':

        require '../api-drive/vendor/autoload.php';
        $client = new Google_Client();
        $client->setAuthConfig('../modelos/inabve-actas-40f976130a1b.json');
        $client->addScope(Google_Service_Drive::DRIVE_FILE);
        //$data = json_decode(file_get_contents('php://input'), true);       
        $dui = $_POST['dui'];
        $id_orden = $_POST['id_orden'];
        $scan->uploadImages($client,$dui, $id_orden);
        break;
    case 'getScanActasUpload':
        $id_acta = isset($_POST['id_orden']) ? $_POST['id_orden'] : '';
        $data = $scan->getScanActasUpload($id_acta);
        echo json_encode($data);
        break;
    case 'removeUploadActa':
        $result = $scan->deleteUploadActa($_POST['id_orden']);
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
}