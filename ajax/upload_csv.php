<?php

require_once("../config/conexion.php");
//llamada al modelo

require_once "../modelos/Citados.php";

$citados = new Citados();

switch ($_GET["op"]) {

    case 'get_data_citados':
        if(isset($_FILES['file-csv'])){
            $tipo       = $_FILES['file-csv']['type'];
            $tamanio    = $_FILES['file-csv']['size'];
            $archivotmp = $_FILES['file-csv']['tmp_name'];
            if($tamanio > 0){
                $lineas = file($archivotmp);
                $i = 1;
                $array_data_file = [];
                foreach ($lineas as $linea) {
                    $array = [];
                    $datos = explode(";", $linea);

                    $id = !empty($datos[0])  ? ($datos[0]) : '';
                    $paciente = !empty($datos[1])  ? ($datos[1]) : '';
                    $dui = !empty($datos[2])  ? ($datos[2]) : '';
                    $edad = !empty($datos[3])  ? ($datos[3]) : '';
                    $telefono = !empty($datos[4])  ? ($datos[4]) : '';
                    $genero = !empty($datos[5])  ? ($datos[5]) : '';
                    $ocupacion = !empty($datos[6])  ? ($datos[6]) : '';
                    $departamento = !empty($datos[7])  ? ($datos[7]) : '';
                    $municipio = !empty($datos[8])  ? ($datos[8]) : '';
                    $tipo_paciente = !empty($datos[9])  ? ($datos[9]) : '';
                    $fecha = !empty($datos[10])  ? ($datos[10]) : '';
                    $hora = !empty($datos[11])  ? ($datos[11]) : '';
                    $telefono2 = !empty($datos[12])  ? ($datos[12]) : '';
                    $institucion = !empty($datos[13])  ? ($datos[13]) : '';
                    $sucursal = !empty($datos[14])  ? ($datos[14]) : '';
                    $sector = !empty($datos[15])  ? ($datos[15]) : '';
                    
                    $array["contador"] = $i;
                    $array["id"] = $id;
                    $array["paciente"] = trim($paciente);
                    $array["dui"] = trim($dui);
                    $array["edad"] = (int)trim($edad);
                    $array["telefono"] = trim($telefono);
                    $array["genero"] = trim($genero);
                    $array["ocupacion"] = trim($ocupacion);
                    $array["departamento"] = trim($departamento);
                    $array["municipio"] = trim($municipio);
                    $array["tipo_paciente"] = trim($tipo_paciente);
                    $array["fecha"] = $fecha;
                    $array["hora"] = $hora;
                    $array["telefono2"] = trim($telefono2);
                    $array["institucion"] = trim($institucion);
                    $array["sucursal"] = trim($sucursal);
                    $array["sector"] = trim($sector);

                    $array_data_file[] = $array;
                    $i ++;
                }
    
                echo json_encode([
                    'status' => 'success',
                    'result' => $array_data_file
                ]);
            }else{
                echo json_encode([
                    'error' => 'El archivo está vacío.'
                ]);
            }
        }else{
            echo json_encode([
                'status' => 'Error no se ha especificado ningun archivo'
            ]);
        }
        break;
    case 'procesar_csv':
        $data = json_decode($_POST['data'],true);
        foreach($data as $row){
            if($citados->validarExiste($row['id'])){
                $citados->save_citados_csv($row);
            }
        }
        echo json_encode([
            'status' => 'success',
            'result' => []
        ]);
        break;
}
