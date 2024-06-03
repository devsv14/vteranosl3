<?php

require_once("../config/conexion.php");

class Licitacion1 extends Conectar{
    public function getOrdenesFase1(){
        $conexionl1 = parent::conexion_inabve1();
        $sql="select dui,telefono,paciente from orden_lab";
        $sql = $conexionl1->prepare($sql);
        $sql->execute();
        $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
        $dui_inv = array();
        $dui_valid =array();
        $pattern = '/^[0-9]{8}-[0-9]$/';
        
        foreach($resultado as $r){
            $dui = $r['dui'];
            $valida = preg_match($pattern, $dui) === 1;

            if($valida){
                array_push($dui_valid,$dui);
            }else{
                array_push($dui_inv,$dui);
            }


        }
        return $dui_valid ;
    }

    public function citadosLicitacionDos(){
        $conectar = parent::conexion();
        $sql = 'select dui,telefono,paciente from citas;';
        $sql = $conectar->prepare($sql);
        $sql->execute();
        $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
        $citados = array();
        foreach($resultado as $r){
            array_push($citados, $r["dui"]);
        }
        return $citados;
    }

    public function getOrdenesSinCita(){
        $conectar = parent::conexion();
        $sql = 'select dui,telefono,paciente from orden_lab where id_cita="0";';
        $sql = $conectar->prepare($sql);
        $sql->execute();
        $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
        $sincitas = array();
        foreach($resultado as $r){
            array_push($sincitas, $r["dui"]);
        }
        return $sincitas;
    }

    public function gestionRenovarLentes(){
        $citados = $this->citadosLicitacionDos();
        $sinCita = $this->getOrdenesSinCita();
        $licitacion1 = $this->getOrdenesFase1();
        $array_result = array_unique(array_merge($citados, $sinCita));
        $result = array_diff($licitacion1, $array_result);
        //$comun = array_intersect($licitacion1, $array_result);
        $result_dtable = array();
        $conexionl1 = parent::conexion_inabve1();
        foreach($result as $res){
          // $sql="select * from orden_lab where dui=?";
            $sql='select o.codigo,o.paciente,o.dui,o.telefono,o.depto,o.municipio,o.tipo_lente,o.categoria,o.fecha,o.institucion,o.edad,o.id_orden,rx.od_esferas,rx.od_adicion,rx.oi_adicion,rx.oi_esferas,rx.oi_adicion,rx.od_cilindros,rx.oi_cilindros from orden_lab as o INNER JOIN rx_orden_lab as rx on o.codigo=rx.codigo where o.dui=?;';
            $sql = $conexionl1->prepare($sql);
            $sql->bindValue(1, $res);
            $sql->execute();
            $rs = $sql->fetchAll(PDO::FETCH_ASSOC);
            $paciente = $rs[0]['paciente'];
            $dui = $rs[0]['dui'];
            $telefono = $rs[0]['telefono'];
            
            array_push($result_dtable,['paciente'=>$paciente,'dui'=>$dui,'telefono'=>$telefono,'depto'=> $rs[0]['depto'],'municipio'=>$rs[0]['municipio'],'tipo_lente'=>$rs[0]['tipo_lente'],'fecha'=>$rs[0]['fecha'],'institucion'=>$rs[0]['institucion'],'edad'=>$rs[0]['edad'],'id'=>$rs[0]['id_orden'],'od_esferas'=>$rs[0]['od_esferas'],'oi_esferas'=>$rs[0]['oi_esferas'],'od_cilindros'=>$rs[0]['od_cilindros'],'oi_cilindros'=>$rs[0]['oi_cilindros'],'categoria'=>$rs[0]['categoria'],'od_adicion'=>$rs[0]['od_adicion'],'oi_adicion'=>$rs[0]['oi_adicion'],]);
            //array_push($result_dtable,['paciente'=>$paciente,'dui'=>$dui,'telefono'=>$telefono,'depto'=> $rs[0]['depto'],'municipio'=>$rs[0]['municipio'],'tipo_lente'=>$rs[0]['tipo_lente'],'fecha'=>$rs[0]['fecha'],'institucion'=>$rs[0]['institucion'],'edad'=>$rs[0]['edad'],'id'=>$rs[0]['id_orden']]);
        }
        return $result_dtable;
    } 
    
        public function getDataProcesosBases(){
        $conectar = parent::conexion();
        $dataBases = array();
        //$filter = $this-> gestionRenovarLentes();
        $conexionl1 = parent::conexion_inabve1();
        $base_cero = 0;
        $base_dos = 0;
        $base_cuatro = 0;
        $base_seis = 0;
        $base_ocho = 0;
        $resultados = array();
        $sql='select o.codigo,o.paciente,o.dui,o.telefono,o.depto,o.municipio,o.tipo_lente,o.fecha,o.institucion,o.edad,o.id_orden,rx.od_esferas,rx.od_adicion,rx.oi_esferas,rx.oi_adicion from orden_lab as o INNER JOIN rx_orden_lab as rx on o.codigo=rx.codigo where  o.tipo_lente="Flaptop" and o.categoria="Terminado";';
            $sql = $conexionl1->prepare($sql);
            $sql->execute();
            $filter = $sql->fetchAll(PDO::FETCH_ASSOC);
        foreach($filter as $f){
           if($f["od_esferas"] > -7 and $f["od_esferas"] <= -4.25){
               array_push($resultados,$f["dui"]);
               $base_cuatro++;
           }
           
           if($f["oi_esferas"] > -7 and $f["oi_esferas"] <= -4.25){
               array_push($resultados,$f["dui"]);
               $base_cuatro++;
           }
        }
        
        return $resultados;

    }
}


//$renova = new Licitacion1();
//$bases = $renova->getDataProcesosBases();
//var_dump($bases);


