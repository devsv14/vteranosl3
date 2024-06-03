
<?php

require_once("../config/conexion.php");
//llamada al modelo categoria
require_once("../modelos/Precios.php");

$precios = new Precios();

switch ($_GET["op"]){
    case 'dt_get_sucursal_montos':
        $datos = $precios->getSucursalMonto();
        $data = array();
        /* $sucursalesMonto = [
            [
                'sucursal' => 'Metrocentro',
                'monto' => 0,
                'cantidad' => 0
            ],
            [
                'sucursal' => 'San Miguel AV PLUS',
                'monto' => 0,
                'cantidad' => 0
            ],
            [
                'sucursal' => 'Cascadas',
                'monto' => 0,
                'cantidad' => 0
            ],
            [
                'sucursal' => 'Santa Ana',
                'monto' => 0,
                'cantidad' => 0
            ],
            [
                'sucursal' => 'Chalatenango',
                'monto' => 0,
                'cantidad' => 0
            ],
            [
                'sucursal' => 'Ahuachapan',
                'monto' => 0,
                'cantidad' => 0
            ],
            [
                'sucursal' => 'Ciudad Arce',
                'monto' => 0,
                'cantidad' => 0
            ],
            [
                'sucursal' => 'Apopa',
                'monto' => 0,
                'cantidad' => 0
            ],
            [
                'sucursal' => 'San Vicente Centro',
                'monto' => 0,
                'cantidad' => 0
            ],
            [
                'sucursal' => 'San Vicente',
                'monto' => 0,
                'cantidad' => 0
            ],
            [
                'sucursal' => 'Gotera',
                'monto' => 0,
                'cantidad' => 0
            ]
        ]; */
        $sucursalesMonto = $precios->getSucursal();
        foreach($datos as $row){
            $arrayMonto = $precios->getCantidadOrdenesMonto($row['tipo_lente'],$row['alto_indice'],$row['color'],$row['sucursal']);
            //$index = array_search($row['sucursal'], array_column($sucursalesMonto, 'sucursal'));
            $monto = 0;
            foreach($sucursalesMonto as $k => $v){
                if($arrayMonto['sucursal'] == $v['sucursal']){
                    $monto = (int)$sucursalesMonto[$k]['monto'] + (int)$arrayMonto['precio'];
                    //print_r([$arrayMonto['sucursal'] => $arrayMonto['precio']]);
                    $cantidad = $sucursalesMonto[$k]['cantidad'] + 1;

                    $sucursalesMonto[$k]['monto'] = $monto;
                    $sucursalesMonto[$k]['cantidad'] = $cantidad;
                }
            }
        } 
        $i = 1;
        foreach ($sucursalesMonto as $row) {
            $sub_array = array();
            $sub_array[] = $i;
            $sub_array[] = $row['sucursal'];
            $sub_array[] = $row['cantidad'];
            $sub_array[] = '$' . number_format($row['monto'],2);
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
}


/* 
           
           
           $sub_array = ['id_acta'=>$id_acta,'receptor'=>$receptor,'tipo_receptor'=>$tipo_receptor,'fecha_impresion'=>$fecha_imp,'paciente'=>$paciente,'tipo_paciente'=>$tipo_paciente,'sector'=>$sec,'tipo_lente'=>$tipo_lente,'alto_indice'=>$alto_indice,'color'=>$color,'fecha_orden'=>$fecha_orden,'sucursal'=>$v["sucursal"],'cita'=>'Si','dui'=>$v["dui_acta"],'dui_titular'=>$dui_titular,'vet_titular'=>$vet_titular,'telefono'=>$telefono,'precio'=>$precio];
           array_push($array_actas_c,$sub_array); */