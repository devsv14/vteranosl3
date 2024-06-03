<?php

require_once("../config/conexion.php");

class Expedientes extends Conectar{

       public function getDataOrden($tipo_lente,$color,$alto_index,$factura){
        $conectar= parent::conexion();
        parent::set_names();

        $fechasPorFactura = [
            1 => ['2022-10-01', '2022-11-30'],
            2 => ['2022-12-01', '2022-12-15'],
            3 => ['2022-12-16', '2022-12-31'],
            4 => ['2023-01-01', '2023-01-15'],
            5 => ['2023-01-16', '2023-01-31'],
            6 => ['2023-02-01', '2023-02-15'],
            7 => ['2023-02-16', '2023-02-24']
        ];
        $fechas = $fechasPorFactura[$factura];
        $sql = 'select count(id_orden) as cantidad FROM orden_lab_bk where fecha between ? and ?  and tipo_lente=? and color=? and alto_indice=?;';
        $sql=$conectar->prepare($sql); 
        $sql->bindValue(1, $fechas[0]);
        $sql->bindValue(2, $fechas[1]);        
        $sql->bindValue(3, $tipo_lente);
        $sql->bindValue(4, $color);
        $sql->bindValue(5, $alto_index);
        $sql->execute();
        return $atendidos = $sql->fetchAll(PDO::FETCH_ASSOC);

       }

       public function getFacturados($categoria){
        $conectar= parent::conexion();
        parent::set_names();
        $sql = "select id_factura,descripcion,concat(1,'-',f1)as f1,concat(2,'-',f2) as f2,  concat(3, '-', f3) as f3,concat(4, '-', f4) as f4,concat(5, '-', f5) as f5,
        concat(6, '-', f6) as f6,concat(7, '-', f7) as f7 from ordenes_facturadas where descripcion=?;";
        $sql=$conectar->prepare($sql);        
        $sql->bindValue(1, $categoria);
        $sql->execute();
        $facturadas = $sql->fetchAll(PDO::FETCH_ASSOC); 
        return $facturadas;  
       }


        public function getExpedientesUpdates(){
        $conectar = parent::conexion();
        $categorias = array(["tipo_lente" => 'Visión Sencilla', "alto_indice" => 'No', "color" => 'Blanco', "desc_atend" => 'Lente visión sencilla blanco (hasta +/- 4.00 dioptrías)'],["tipo_lente" => 'Visión Sencilla', "alto_indice" => 'No', "color" => 'Photocromatico', "desc_atend" => 'Lente visión sencilla fotosensible'],["tipo_lente" => 'Visión Sencilla', "alto_indice" => 'Si', "color" => 'Blanco', "desc_atend" => 'Lente visión sencilla blanco (mayor a +/- 4.00 dioptrías)'],["tipo_lente" => 'Visión Sencilla', "alto_indice" => 'Si', "color" => 'Photocromatico', "desc_atend" => 'Lente visión sencilla con tratamiento fotosensible (mayor a +/- 4.00 dioptrías)'],["tipo_lente" => 'Flaptop', "alto_indice" => 'No',"color" => 'Blanco',"desc_atend"=>'Lente bifocal blanco (hasta +/- 4.00 dioptrías)'],["tipo_lente" => 'Flaptop', "alto_indice" => 'No', "color" => 'Photocromatico', "desc_atend" => 'Lente bifocal con tratamiento fotosensible'],["tipo_lente" => 'Flaptop', "alto_indice" => 'Si', "color" => 'Blanco', "desc_atend" => 'Lente bifocal blanco (mayor a +/- 4.00 dioptrías)'],["tipo_lente" => 'Flaptop', "alto_indice" => 'Si', "color" => 'Photocromatico', "desc_atend" => 'Lente bifocal con tratamiento fotosensible (mayor a +/-4.00 dioptrías)'],["tipo_lente" => 'Progresive', "alto_indice" => 'No', "color" => 'Blanco', "desc_atend" => 'Lente progresivo blanco gama intermedia corredor amplio (hasta +/- 4.00 dioptrías)'],["tipo_lente" => 'Progresive', "alto_indice" => 'No', "color" => 'Photocromatico', "desc_atend" => 'Lente progresivo con tratamiento fotosensible de gama intermedia corredor amplio (hasta +/-4.00 dioptrías)'],["tipo_lente" => 'Progresive', "alto_indice" => 'Si', "color" => 'Blanco', "desc_atend" => 'Lente progresivo blanco gama intermedia corredor amplio (mayor a +/- 4.00 dioptrías)'],["tipo_lente" => 'Progresive', "alto_indice" => 'Si', "color" => 'Photocromatico', "desc_atend" => 'Lente progresivo con tratamiento fotosensible (mayor a +/- 4.00 dioptrías)']);
        //print_r($categorias); 
        $arr_fact = [];

        $table = '<table  width="100%" class="table-responsive-sm table-hover table-bordered" style="font-size:13px;text-align:center">
            <tr>
            <th rowspan="2" colspan="7">Descripción</th>
            <th colspan="3" style="">Factura 1</th>
            <th colspan="3">Factura 2</th>
            <th colspan="3">Factura 3</th>
            <th colspan="3">Factura 4</th>
            <th colspan="3">Factura 5</th>
            <th colspan="3">Factura 6</th>
            <th colspan="3">Factura 7</th>
            </tr>
            <tr>
            <th>A</th>
            <th>F</th>
            <th>D</th>
            <th>A</th>
            <th>F</th>
            <th>D</th>
            <th>A</th>
            <th>F</th>
            <th>D</th>
            <th>A</th>
            <th>F</th>
            <th>D</th>
            <th>A</th>
            <th>F</th>
            <th>D</th>
            <th>A</th>
            <th>F</th>
            <th>D</th>
            <th>A</th>
            <th>F</th>
            <th>D</th>
        
            </tr>';  
        
        foreach ($categorias as $at){
            $tipo_lente = $at["tipo_lente"];
            $color = $at["color"];
            $alto_index = $at["alto_indice"];
            
            $cat = $at["desc_atend"];
           
            $facturadas = $this->getFacturados($cat);
           
            foreach($facturadas as $f){array_push($arr_fact,['desc'=>$cat,'tipo_lente'=>$tipo_lente,'color'=>$color,'alto_indice'=>$alto_index,'facturas'=>array($f['f1'],$f['f2'],$f['f3'],$f['f4'],$f['f5'],$f['f6'],$f['f7'])]);
            } 
           
        }

    $sumatend1 = 0; $sumfac1 = 0; $sumdif1=0;


    foreach($arr_fact as $c){
        $table .="<tr>";
        $facturas = $c["facturas"];
        $table .="<td colspan='7'>".$c["desc"]."</td>";
        $tipo_lente =  $c["tipo_lente"];
        $color =  $c["color"];
        $alto_index =  $c["alto_indice"];
        $sumtd = 0;
        
        foreach($facturas as $f){
           $n_f = explode("-",$f);// echo $n_f[1].'.';
           $atend = $this->getDataOrden($tipo_lente,$color,$alto_index,$n_f[0]);
           $cant_atend = $atend[0]["cantidad"];
           $diferencia = $cant_atend - $n_f[1];
           $sumtd = $sumtd + $cant_atend; 
           if($diferencia < 0){
            $colortd = "#F47173";
           }elseif($diferencia > 0){
            $colortd = "#0275d8";
           }elseif($diferencia==0){
            $colortd = "#5cb85c";
           }
           
           if($n_f[0]==1){
            $sumatend1 += $cant_atend; $sumfac1 += $n_f[1]; $sumdif1 += $diferencia;
           }
           $table .= "<td style='cursor:pointer'>$cant_atend</td>";
           $table .= "<td style='cursor:pointer'>".$n_f[1]."</td>";
           $table .= "<td style='cursor:pointer;color:".$colortd."' onClick='getdataActualization(\"" . $tipo_lente . "\",\"" . $color . "\",\"" . $alto_index . "\",".$diferencia.",".$n_f[0].")'><b>$diferencia</b></td>";
        }
        

        $table .="</tr>";

    } 
    $table .= "<tr><td style='text-align:center' colspan='7'>TOTALES</td><td>".$sumatend1."</td></tr>";
    $table .= "</table>";  
    echo $table;
    
    }

    public function getOrdenesExcentesFechas($tipo_lente,$color,$indice){
        $conectar= parent::conexion();
        parent::set_names();
        $sql = "SELECT * FROM orden_lab_bk WHERE tipo_lente=?  and color=? and alto_indice=? and fecha BETWEEN '2023-02-25' and '2023-06-28' and sucursal not in ('Jornada San Miguel','Jornada Potonico','Jornada Conchagua','Jornada Santa Ana','Jornada Meanguera','Jornada San Vicente','Jornada Rancho Quemado','Jornada Meanguera 2') order by fecha ASC;";
        $sql=$conectar->prepare($sql);
        $sql->bindValue(1, $tipo_lente);
        $sql->bindValue(2, $color);
        $sql->bindValue(3, $indice);
        $sql->execute();
        return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC); 
    }

    public function actualizarOrdenesFechaAnt(){
        $conectar= parent::conexion();
        parent::set_names();
        $detalles = array();
        $detalles = json_decode($_POST['arrayOrdenesUpdFactura']);

       // print_r($detalles); 
        foreach ($detalles as $k => $v) {
        $dui = $v->dui;
        $fecha_ant = $v->fecha_correcta;
        $fecha = $v->new_date;

            $sql1 = "insert into respaldo_facturas_bk (id_resp,dui,fecha_ant,nueva_fecha) values(null,?,?,?)";
            $sql1 = $conectar->prepare($sql1);
            $sql1->bindValue(1,$dui);
            $sql1->bindValue(2,$fecha_ant);
            $sql1->bindValue(3,$fecha);
            $sql1->execute();

            $sql="update orden_lab_bk set fecha=? where dui=?;";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1, $fecha);
            $sql->bindValue(2, $dui);
            $sql->execute();
      
    }

    echo json_encode(['msj'=>'ok']);

        
    }

    public function getExpedientesFromSources(){
        $conectar= parent::conexion();
        parent::set_names();
        $sql = "SELECT * FROM citas_bk";
        $sql=$conectar->prepare($sql);
        $sql->execute();
        return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC); 
    }




}    

//$expedientes = new Expedientes;
//$expedientes->getExpedientesUpdates();