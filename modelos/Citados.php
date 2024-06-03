<?php

require_once("../config/conexion.php");

class Citados extends Conectar
{

    public function listar_pacientes_citados($user_sucursal)
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT * FROM `citas` WHERE citas.sucursal=:sucursal AND citas.estado != 1";
        $sql = $conectar->prepare($sql);
        $sql->bindParam(':sucursal', $user_sucursal);
        $sql->execute();
        return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listar_citados_pend()
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "select * from citas where estado='0';";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDataCitaId($id_cita){
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "select * from citas where id_cita=?;";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $id_cita);
        $sql->execute();
        return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDataCitasSucursal($sucursal, $fecha){
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "select * from citas where id_cita=?;";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $id_cita);
        $sql->execute();
        return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDataCitadosSucursal($fecha)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "select * from citas where fecha=? order by sucursal;";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $fecha);
        $sql->execute();
        return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDataCitadosSucursalPrint($sucursal,$fecha,$cat_user,$sucursal_select){
        $conectar = parent::conexion();
        parent::set_names();
        if($sucursal_select !=0){
            $suc_select = explode('-',$sucursal_select);
            $sql = "select * from citas where sucursal=? and fecha=?;";
            $sql = $conectar->prepare($sql);
            $sql->bindValue(1, $suc_select[1]);
            $sql->bindValue(2, $fecha);
            $sql->execute();
        }elseif($sucursal_select==0 and $cat_user=='Admin'){
            $sql = "select * from citas where fecha=? order by sucursal DESC;";
            $sql = $conectar->prepare($sql);
            $sql->bindValue(1, $fecha);
            $sql->execute();
        } 
        
        return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHorasSelect($fecha,$sucursal){
        $conectar = parent::conexion();
        parent::set_names();
        $sql2 = "SELECT hora FROM `citas` where fecha = ? and sucursal=?;";
        $sql2 = $conectar->prepare($sql2);
        $sql2->bindValue(1, $fecha);
        $sql2->bindValue(2, $sucursal);
        $sql2->execute();
        return  $resultado = $sql2->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCitadosAtendAll($fecha)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql2 = "select * FROM `citas` where fecha = ? order by sucursal DESC;";
        $sql2 = $conectar->prepare($sql2);
        $sql2->bindValue(1, $fecha);
        $sql2->execute();
        return  $resultado = $sql2->fetchAll(PDO::FETCH_ASSOC);
    }


    public function updateCitas(){
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "update citas set paciente=?,dui=?,fecha=?,sucursal=?,telefono=?,edad=?,ocupacion=?,genero=?,sector=?,depto=?,municipio=?,hora=?,vet_titular=?,dui_titular=?,tel_opcional=?,tipo_paciente=? where id_cita=?";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $_POST["paciente"]);
        $sql->bindValue(2, $_POST["dui"]);
        $sql->bindValue(3, $_POST["fecha"]);
        $sql->bindValue(4, $_POST["sucursal"]);
        $sql->bindValue(5, $_POST["telefono"]);
        $sql->bindValue(6, $_POST["edad"]);
        $sql->bindValue(7, $_POST["ocupacion"]);
        $sql->bindValue(8, $_POST["genero"]);
        $sql->bindValue(9, $_POST["sector"]);
        $sql->bindValue(10, $_POST["depto"]);
        $sql->bindValue(11, $_POST["municipio"]);
        $sql->bindValue(12, $_POST["hora"]);
        $sql->bindValue(13, $_POST["titular"]);
        $sql->bindValue(14, $_POST["dui_titular"]);
        $sql->bindValue(15, $_POST["tel_opcional"]);
        $sql->bindValue(16, $_POST["tipo_paciente"]);
        $sql->bindValue(17, $_POST["id_cita"]);
        $sql->execute();

        echo json_encode(["msj"=>"Ok"]);


}

public function getDisponibilidadCitas($fecha){
    $conectar=parent::conexion();    
    parent::set_names();
    $sql = "select nombre from sucursales";
    $sql=$conectar->prepare($sql);
    $sql->execute();
    $sucursales=$sql->fetchAll(PDO::FETCH_ASSOC);
    $data_disponibilidad = array();
    foreach($sucursales as $s){
        
        $cita = "SELECT count(*) as citados from citas WHERE fecha=? and sucursal=?";
        $cita=$conectar->prepare($cita);
        $cita->bindValue(1, $fecha);
        $cita->bindValue(2, $s["nombre"]);
        $cita->execute();
        $total_citas=$cita->fetchAll(PDO::FETCH_ASSOC);
        $citados =  $total_citas[0]['citados'];

        $cupo = "select cupos,direccion,referencia,optica from sucursales where nombre=?";
        $cupo=$conectar->prepare($cupo);
        $cupo->bindValue(1, $s["nombre"]);
        $cupo->execute();
        $total_cupos=$cupo->fetchAll(PDO::FETCH_ASSOC);
        $cupo_disp =  $total_cupos[0]['cupos'];
        $direccion =  strtoupper($total_cupos[0]['direccion']);
        $referencia =  strtoupper($total_cupos[0]['referencia']);
        $optica =  strtoupper($total_cupos[0]['optica']);


        $disponibilidad = ($cupo_disp-$citados)."/".$cupo_disp;
        $disp_act= $cupo_disp-$citados;
        $dias = array('Domingo', 'Lunes', 'Martes', 'Miércoles','Jueves', 'Viernes', 'Sábado');
        $fechats = strtotime($fecha);
        $dia= $dias[date('w', $fechats)];
 
        if($disp_act>0 and $s["nombre"] != "Usulutan" and $s["nombre"] != "San Miguel" and $s["nombre"] != "Sonsonate" ){
            $fecha_limite = '2023-06-15';
            if(($s["nombre"] == "Sonsonate" and $dia =="Jueves") or (($s["nombre"] == "Valencia" or $s["nombre"] == "Apopa" or $s["nombre"] == "San Vicente" or $s["nombre"] == "San Vicente Centro" or $s["nombre"] == "Usulutan" or $s["nombre"] == "San Miguel" or $s["nombre"] == "Sonsonate" or $s["nombre"] == "Gotera") and ($dia =="Sábado" or $dia =="Domingo")) or ($dia=='Domingo' and $s["nombre"] !='San Miguel AV PLUS') or (($s["nombre"] == "Usulutan" or $s["nombre"] == "San Miguel") and (strtotime($fecha) > strtotime($fecha_limite)))){            
            }else if($s["nombre"] == "San Miguel AV PLUS" and ($dia =="Lunes" or $dia =="Martes" or $dia =="Jueves" or $dia =="Viernes" or $dia =="Viernes" or $dia =="Sábado" or $dia =="Domingo")){
                array_push($data_disponibilidad,array("sucursal"=>$s["nombre"],"cupos"=>$disponibilidad,"direccion"=>$direccion,"referencia"=>$referencia,"optica"=>$optica));
            }else if($s["nombre"] != "San Miguel AV PLUS"){
                array_push($data_disponibilidad,array("sucursal"=>$s["nombre"],"cupos"=>$disponibilidad,"direccion"=>$direccion,"referencia"=>$referencia,"optica"=>$optica));
            }
        }
        
    }

        echo json_encode($data_disponibilidad);

        //echo json_encode(["msj" => "OLK"]);
}

    public function updateEstadoCita($id_cita){
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "UPDATE citas SET estado = '1' WHERE citas.id_cita = :id_cita";
        $sql = $conectar->prepare($sql);
        $sql->bindParam(':id_cita',$id_cita);
        if($sql->execute()){
            return true;
        }
        //echo json_encode(["msj" => "OLK"]);
    }
    public function get_reporteria_citados($data)
    {
        $conectar = parent::conexion();
        parent::set_names();
        //Validacion de undefined
        $fecha_desde_dia = isset($data['fecha_desde']) ? $data['fecha_desde'] : "";
        $fecha_hasta = isset($data['fecha_hasta']) ? $data['fecha_hasta'] : "";
        $sucursal = isset($data['sucursal']) ? $data['sucursal'] : "";

        $sql2 = "SELECT c.id_cita,c.paciente,c.dui,c.sucursal,c.fecha,c.hora,c.sector,c.tipo_paciente,c.estado,u.nombres,c.telefono FROM `citas` as c INNER JOIN usuarios as u ON c.id_usuario=u.id_usuario";
        $arr_params = [];
        switch ($data['estado_cita']) {
            case 'citados':
                if ($fecha_desde_dia != "" and $fecha_hasta != "" and $sucursal != "") {
                    $sql2 .= " where c.fecha between ? and ? and c.sucursal=? ORDER BY c.sucursal ASC";
                    //Execute sql
                    $sql2 = $conectar->prepare($sql2);
                    $arr_params = [$fecha_desde_dia, $fecha_hasta, $sucursal];
                    $sql2->execute($arr_params);
                    return  $resultado = $sql2->fetchAll(PDO::FETCH_ASSOC);
                }
                if ($fecha_desde_dia != "" and $fecha_hasta != "" and $sucursal == "") {
                    $sql2 .= " where c.fecha between ? and ? ORDER BY c.sucursal ASC";
                    //Execute sql
                    $sql2 = $conectar->prepare($sql2);
                    $arr_params = [$fecha_desde_dia, $fecha_hasta];
                    $sql2->execute($arr_params);
                    return  $resultado = $sql2->fetchAll(PDO::FETCH_ASSOC);
                }
                
                //Si sucursal no esta vacio
                if ($sucursal != "" and $fecha_desde_dia != "") {
                    $sql2 .= " where c.fecha=? and c.sucursal=? ORDER BY c.sucursal ASC";
                    $arr_params = [$fecha_desde_dia,$sucursal];
                    //Execute sql
                    $sql2 = $conectar->prepare($sql2);
                    $sql2->execute($arr_params);
                    return  $resultado = $sql2->fetchAll(PDO::FETCH_ASSOC);
                }
                //Seleccionar por dias
                if($fecha_desde_dia != "" and $sucursal == ""){
                    $sql2 .= " and c.fecha=? ORDER BY c.sucursal ASC";
                    //Execute sql
                    $sql2 = $conectar->prepare($sql2);
                    $arr_params = [$fecha_desde_dia];
                    $sql2->execute($arr_params);
                    return  $resultado = $sql2->fetchAll(PDO::FETCH_ASSOC);
                }
                //Solo filtrar por sucursal
                //Si sucursal no esta vacio
                if ($sucursal != "") {
                    $sql2 .= " where c.sucursal=? ORDER BY c.sucursal ASC";
                    $arr_params = [$sucursal];
                    //Execute sql
                    $sql2 = $conectar->prepare($sql2);
                    $sql2->execute($arr_params);
                    return  $resultado = $sql2->fetchAll(PDO::FETCH_ASSOC);
                }
                //Execute sql
                $sql2 = $conectar->prepare($sql2);
                $sql2->execute();
                return  $resultado = $sql2->fetchAll(PDO::FETCH_ASSOC);

                break;
            case 'atendidos':
                if ($fecha_desde_dia != "" and $fecha_hasta != "" and $sucursal != "") {
                    $sql2 .= " where c.estado=1 and c.fecha between ? and ? and c.sucursal=? ORDER BY c.sucursal ASC";
                    //Execute sql
                    $sql2 = $conectar->prepare($sql2);
                    $arr_params = [$fecha_desde_dia, $fecha_hasta, $sucursal];
                    $sql2->execute($arr_params);
                    return  $resultado = $sql2->fetchAll(PDO::FETCH_ASSOC);
                }

                if ($fecha_desde_dia != "" and $fecha_hasta != "" and $sucursal == "") {
                    $sql2 .= " where c.estado=1 and c.fecha between ? and ? ORDER BY c.sucursal ASC";
                    //Execute sql
                    $sql2 = $conectar->prepare($sql2);
                    $arr_params = [$fecha_desde_dia, $fecha_hasta];
                    $sql2->execute($arr_params);
                    return  $resultado = $sql2->fetchAll(PDO::FETCH_ASSOC);
                }

                if ($sucursal != "" and $fecha_desde_dia != "") {
                    $sql2 .= " where c.fecha=? and c.estado=1 and c.sucursal=? ORDER BY c.sucursal ASC";
                    //Execute sql
                    $sql2 = $conectar->prepare($sql2);
                    $arr_params = [$fecha_desde_dia,$sucursal];
                    $sql2->execute($arr_params);
                    return  $resultado = $sql2->fetchAll(PDO::FETCH_ASSOC);
                }
                 //Seleccionar por dias
                 if($fecha_desde_dia != "" and $sucursal == ""){
                    $sql2 .= " where c.estado=1 and c.fecha=? ORDER BY c.sucursal ASC";
                    //Execute sql
                    $sql2 = $conectar->prepare($sql2);
                    $arr_params = [$fecha_desde_dia];
                    $sql2->execute($arr_params);
                    return  $resultado = $sql2->fetchAll(PDO::FETCH_ASSOC);
                }
                //Filtrar solo por sucursal
                if ($sucursal != "") {
                    $sql2 .= " where c.estado=1 and c.sucursal=? ORDER BY c.sucursal ASC";
                    //Execute sql
                    $sql2 = $conectar->prepare($sql2);
                    $arr_params = [$sucursal];
                    $sql2->execute($arr_params);
                    return  $resultado = $sql2->fetchAll(PDO::FETCH_ASSOC);
                }
                //Execute sql
                $sql2 .= " where c.estado=1 ORDER BY c.sucursal ASC";
                //Execute sql
                $sql2 = $conectar->prepare($sql2);
                $sql2->execute();
                return  $resultado = $sql2->fetchAll(PDO::FETCH_ASSOC);
                break;
            case 'sin_atender':
                if ($fecha_desde_dia != "" and $fecha_hasta != "" and $sucursal != "") {
                    $sql2 .= " where c.estado=0 and c.fecha between ? and ? and c.sucursal=? ORDER BY c.sucursal ASC";
                    //Execute sql
                    $sql2 = $conectar->prepare($sql2);
                    $arr_params = [$fecha_desde_dia, $fecha_hasta, $sucursal];
                    $sql2->execute($arr_params);
                    return  $resultado = $sql2->fetchAll(PDO::FETCH_ASSOC);
                }

                if ($fecha_desde_dia != "" and $fecha_hasta != "" and $sucursal == "") {
                    $sql2 .= " where c.estado=0 and c.fecha between ? and ? ORDER BY c.sucursal ASC";
                    //Execute sql
                    $sql2 = $conectar->prepare($sql2);
                    $arr_params = [$fecha_desde_dia, $fecha_hasta];
                    $sql2->execute($arr_params);
                    return  $resultado = $sql2->fetchAll(PDO::FETCH_ASSOC);
                }
                //Seleccionar por dias
                if($fecha_desde_dia != "" and $sucursal != ""){
                    $sql2 .= " where c.estado=0 and c.fecha=? and c.sucursal=? ORDER BY c.sucursal ASC";
                    //Execute sql
                    $sql2 = $conectar->prepare($sql2);
                    $arr_params = [$fecha_desde_dia,$sucursal];
                    $sql2->execute($arr_params);
                    return  $resultado = $sql2->fetchAll(PDO::FETCH_ASSOC);
                }
                if ($fecha_desde_dia != "" and $sucursal == "") {
                    $sql2 .= " where c.estado=0 and c.fecha=? ORDER BY c.sucursal ASC";
                    //Execute sql
                    $sql2 = $conectar->prepare($sql2);
                    $arr_params = [$fecha_desde_dia];
                    $sql2->execute($arr_params);
                    return  $resultado = $sql2->fetchAll(PDO::FETCH_ASSOC);
                }
                //Filtrar solo por sucursal
                if($sucursal != ""){
                    $sql2 .= " where c.estado=0 and c.sucursal=? ORDER BY c.sucursal ASC";
                    //Execute sql
                    $sql2 = $conectar->prepare($sql2);
                    $arr_params = [$sucursal];
                    $sql2->execute($arr_params);
                    return  $resultado = $sql2->fetchAll(PDO::FETCH_ASSOC);
                }
                $sql2 .= " where c.estado=0 ORDER BY c.sucursal ASC";
                //Execute sql
                $sql2 = $conectar->prepare($sql2);
                $sql2->execute();
                return  $resultado = $sql2->fetchAll(PDO::FETCH_ASSOC);
                break;
        }
    }

}////Fin de la clase