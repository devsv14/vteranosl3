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
                $array_data_file = [];
                $rows = [];

                $handle = fopen($archivotmp, "r");
                while (($data = fgetcsv($handle, 0, ";",'"')) !== false) {
                    if(count($data) > 0){
                        $rows[] = $data;
                    }
                }
                
                $i = 1;
                foreach ($rows as $row) {
                    $array = [];

                    $id = !empty($row[0])  ? ($row[0]) : '';

                    $paciente = !empty($row[1])  ? mb_convert_encoding( htmlspecialchars( ($row[1]), ENT_QUOTES, 'UTF-8' ), 'HTML-ENTITIES', 'UTF-8' ) : '';

                    $dui = !empty($row[2])  ? ($row[2]) : '';
                    $edad = !empty($row[3])  ? ($row[3]) : '';
                    $telefono = !empty($row[4])  ? ($row[4]) : '';
                    $genero = !empty($row[5])  ? ($row[5]) : '';

                    $ocupacion = !empty($row[6])  ? ($row[6]) : '';

                    $departamento = !empty($row[7])  ? ($row[7]) : '';
                    $municipio = !empty($row[8])  ? ($row[8]) : '';

                    $tipo_paciente = !empty($row[9])  ? ($row[9]) : '';
                    $fecha = !empty($row[10])  ? ($row[10]) : '';
                    $hora = !empty($row[11])  ? ($row[11]) : '';
                    $telefono2 = !empty($row[12])  ? ($row[12]) : '';
                    $institucion = !empty($row[13])  ? ($row[13]) : '';
                    $sucursal = !empty($row[14])  ? ($row[14]) : '';
                    $sector = !empty($row[15])  ? ($row[15]) : '';
                    $n_expediente = !empty($row[16])  ? ($row[16]) : '';
                    
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
                    $array["n_expediente"] = trim($n_expediente);

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
        $cont_insercion = 0;
        $cont_existe = 0;

        foreach($data as $row){
            if($citados->validarExiste($row['id'])){
                $citados->save_citados_csv($row);
                $cont_insercion += 1;
            }else{
                $cont_existe += 1;
            }
        }
        echo json_encode([
            'status' => 'success',
            'result' => [
                'cont_insertados' => $cont_insercion,
                'cont_existe' => $cont_existe
            ]
        ]);
        break;
}
