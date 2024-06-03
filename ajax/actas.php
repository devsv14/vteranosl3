<?php

require_once("../config/conexion.php");
//llamada al modelo categoria
require_once("../modelos/Actas.php");

$actas = new Actas();

switch ($_GET["op"]){
    case 'crear_acta':
        $actas->registrarActa($_POST["codigo_orden"],$_POST["titular"],$_POST["nombre_receptor"],$_POST["receptor"],$_POST["sucursal"],$_POST["id_usuario"],$_POST['dui_receptor']);
        break;
    case 'get_actas_generadas':

        $sucursal = isset($_POST['sucursal']) ? $_POST['sucursal'] : '';
        $permiso_listado_g = isset($_POST['listado_general']) ? $_POST['listado_general'] : "";
        
        if($sucursal != ""){
            $sql = "SELECT a.id_acta,a.fecha_impresion,a.codigo_orden,o.paciente,a.dui_acta,a.sucursal FROM `actas` as a inner join orden_lab as o on a.codigo_orden=o.codigo where a.sucursal=? ORDER BY id_acta DESC";
            $datos = $actas->get_post_data($sql,[$sucursal]);
        }else{
            if($permiso_listado_g == "Ok"){
                $sql = "SELECT a.id_acta,a.fecha_impresion,a.codigo_orden,o.paciente,a.dui_acta,a.sucursal FROM `actas` as a inner join orden_lab as o on a.codigo_orden=o.codigo ORDER BY id_acta DESC";
                $datos = $actas->get_post_data($sql);
            }else{
                $sql = "SELECT a.id_acta,a.fecha_impresion,a.codigo_orden,o.paciente,a.dui_acta,a.sucursal FROM `actas` as a inner join orden_lab as o on a.codigo_orden=o.codigo where a.sucursal=? ORDER BY id_acta DESC";
    
                $datos = $actas->get_post_data($sql,[$_SESSION["sucursal"]]);
            }
        }

        $data = array();
        $i = 1;
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row->id_acta;
            $sub_array[] = date("d-m-Y", strtotime($row->fecha_impresion));
            $sub_array[] = strtoupper($row->paciente);
            $sub_array[] = $row->dui_acta;
            $sub_array[] = $row->sucursal;
            $sub_array[] = '<button type="button" class="btn btn-sm bg-light" onClick="acta_edit(\'' . $row->dui_acta . '\')"><i class="fa fa-eye" aria-hidden="true" style="color:blue"></i></button>';
            $sub_array[] = '<button type="button" class="btn btn-block bg-light" onclick="genrar_pdf_acta(\'' .$row->id_acta. '\')"><i class="fa fa-file-pdf" aria-hidden="true" style="color:red"></i></button>';
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
    case 'get_acta_id':
        $dui_paciente = $_POST['dui_paciente'];
        
        $sql = "select * from actas where dui_acta=?";
        
        $data = $actas->get_post_data($sql,[$dui_paciente]);
        //Merge tabla citas y orden
        $array = [];
        foreach($data as $row){
            $array['id_acta'] = $row->id_acta;
            $array['beneficiario'] = $row->beneficiario;
            $array['dui_acta'] = $row->dui_acta;
            $array['receptor'] = $row->receptor;
            $array['tipo_receptor'] = $row->tipo_receptor;
            $array['dui_receptor'] = $row->dui_receptor;
            $array['codigo_orden'] = $row->codigo_orden;
            $array['fecha_impresion'] = date('Y-m-d',strtotime($row->fecha_impresion));
            //Datos de table citas
            $citas = $actas->getCitasFindByDUI($row->dui_acta);
            $orden_lab = $actas->getOrdenFindByDUI($row->dui_acta);

            $titularOrden = $actas->getTitularFindByDUI($orden_lab['codigo']);

            $array['tipo_paciente'] = $citas['tipo_paciente'] != "" ? $citas['tipo_paciente'] : $orden_lab['institucion'];
            $array['sector'] = $citas['sector'] != "" ? $citas['sector'] : $orden_lab['institucion'];
            $array['vet_titular'] = $citas['id_cita'] != "" ? $citas['vet_titular'] : $titularOrden['vet_titular'];
            $array['dui_titular'] = $citas['id_cita'] != "" ? $citas['dui_titular'] : $titularOrden['dui_titular'];
            $array['id_cita'] = $citas['id_cita'];
        }

        echo json_encode($array); //Return json {}
        break;
    case 'update_acta':

        $sql_upd = "update actas set id_acta=?,dui_acta=?,beneficiario=?,receptor=?,dui_receptor=?,fecha_impresion=? where codigo_orden=?";
        $newDate = date('d-m-Y',strtotime($_POST['fecha_impresion']));
        $data = [
            $_POST['id_acta'],
            $_POST['dui'],
            $_POST['beneficiario'],
            $_POST['receptor'],
            $_POST['dui_receptor'],
            $newDate,
            $_POST['cod_orden']
        ];
        $result = $actas->get_post_data($sql_upd,$data,"U");
        //Update paciente y DUI en table orden_lab
        $sql_orden = "update orden_lab set paciente=?,dui=? where codigo=?";
        $data_up = [
            $_POST['beneficiario'],
            $_POST['dui'],
            $_POST['cod_orden']
        ];
        $actas->get_post_data($sql_orden,$data_up,"U");
        if($_POST['id_cita'] != ""){
            //Update cita
            $sql_citas = "update citas set paciente=?,dui=?,vet_titular=?,dui_titular=? where id_cita=?";
            $data_up = [
                $_POST['beneficiario'],
                $_POST['dui'],
                $_POST['vet_titular'],
                $_POST['dui_titular'],
                $_POST['id_cita']
            ];
            $actas->get_post_data($sql_citas,$data_up,"U");
        }else{
            //Comprobacion de titulres 
            $result = $actas->getTitularFindByDUI($_POST['cod_orden']);
            if($result['vet_titular'] == "" and $result['dui_titular'] == ""){
                $sql8 = "insert into titulares values(null,?,?,?);";
                $data = [
                    $_POST['vet_titular'],
                    $_POST['dui_titular'],
                    $_POST['cod_orden']
                ];
                $actas->get_post_data($sql8,$data,"I");
            }else{
                $sqlUpdate = "update titulares set titular=?,dui_titular=? where codigo=?";
                $dataInsert = [
                    $_POST['vet_titular'],
                    $_POST['dui_titular'],
                    $_POST['cod_orden']
                ];
                $actas->get_post_data($sqlUpdate,$dataInsert,"U");
            }
        }
        //Registramos la accion
        $accion = "Edición Acta";
        $actas->registrarAccionOrden($accion,$accion,$_POST['cod_orden']);
        //Message
        $message = "update";
        echo json_encode($message);
        break;
    case 'get_data_acta_pdf':
        $sql = "select * from actas where id_acta=?";
        $data = [$_POST['id_acta']];
        $data = $actas->get_post_data($sql,$data);
        //Registro de accion de reimpresión de acta
        $codigo_orden = $data[0]->codigo_orden;
        $sucursal = $data[0]->sucursal;
        $accion = "Reimpresión de Acta";
        $obser = "Reimpresión de Acta - ".$sucursal;
        //$actas->registrarAccionOrden($accion,$obser,$codigo_orden,$sucursal);
        echo json_encode($data[0]);
        break;
    //Obtener la data para mostrar en Modal de entregas de actas
    case 'get_acta_orden':
        $search = false;
        $id_acta = (int)$_POST['id_acta'];
        $sucursal = $_POST['sucursal'];
        //Validacion de existen en table control acta
        $dataControlActa = $actas->getControlActaComprobarExistencia($id_acta);
        if(count($dataControlActa) > 0){
            echo json_encode("existe");
            return 0;
        }
        //Validation for sucursal (Para evitar cambio de sucursal desde fronted)
        $data = [];
        if($sucursal == $_SESSION["sucursal"]){
            $data = $actas->get_acta_find_id($id_acta);
        }
        echo json_encode($data);
        break;
    case 'save_entregas_actas':
        $result = $actas->insert_actas_entregas($_POST['data'],$_POST['id_usuario']);
        if($result){
            echo json_encode($result);
            return 0;
        }
        echo json_encode($result); //El metodo retorno false
    break;
    case 'getNumberOfActas':
        $listado_general = $_POST['permi_listado_general'];
        if($listado_general == "Ok"){
            $sql = "SELECT cod_entrega,fecha_entrega,sucursal,COUNT(cod_entrega) as cantidad FROM `control_actas` GROUP BY cod_entrega order by cod_entrega asc";
            $datos = $actas->get_post_data($sql);
        }else{
            $sql = "SELECT cod_entrega,fecha_entrega,sucursal,COUNT(cod_entrega) as cantidad FROM `control_actas` where sucursal=? GROUP BY cod_entrega order by cod_entrega asc";
            $datos = $actas->get_post_data($sql,[$_SESSION["sucursal"]]);
        }
        $data = array();
        $i = 1;
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $i;
            $sub_array[] = $row->cod_entrega;
            $sub_array[] = date("d-m-Y", strtotime($row->fecha_entrega));
            $sub_array[] = $row->sucursal;
            $sub_array[] = $row->cantidad;
            $sub_array[] = '<button type="button" class="btn btn-block bg-light" onclick="showActasEntregadas(\'' .$row->cod_entrega. '\')"><i class="fa fa-file-pdf" aria-hidden="true" style="color:red"></i></button>';
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
    case 'get_control_actas_all':
        $sql = "select * from `control_actas` where cod_entrega=? order by id_control desc";
        $datos = $actas->get_post_data($sql,[$_POST['code']]);
        $data = array();
        $i = 1;
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $i;
            $sub_array[] = $row->id_acta;
            $sub_array[] = date("d-m-Y", strtotime($row->fecha_entrega));
            $sub_array[] = $row->dui;
            $sub_array[] = strtoupper($row->paciente);
            $sub_array[] = $row->tipo_paciente;
            $sub_array[] = $row->sector;
            $sub_array[] = $row->sucursal;
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
        
         case 'get_resumen_actas':
          
            $args = $_POST["Args"]; 
            $desde = $args[1];
            $hasta = $args[2];           
            $datos = $actas->getActasResumen($args[0],$desde,$hasta); 
            $data = array();
          
            foreach ($datos as $row) {
                $sub_array = array();
                $sub_array[] = $row["id_acta"];
                $sub_array[] = date("d-m-Y", strtotime($row["fecha_orden"]));
                $sub_array[] = date("d-m-Y", strtotime($row["fecha_impresion"]));
                $sub_array[] = $row["paciente"];
                $sub_array[] = $row["telefono"];
                $sub_array[] = $row["dui"];
                $sub_array[] = $row["sucursal"];
                $sub_array[] = $row["receptor"];
                $sub_array[] = $row["tipo_paciente"];
                $sub_array[] = $row["vet_titular"];
                $sub_array[] = $row["dui_titular"];
                $sub_array[] = $row["sector"];
                $sub_array[] = $row["tipo_lente"];
                $sub_array[] = $row["color"];
                $sub_array[] = $row["alto_indice"];
                $sub_array[] = $row["precio"];
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
