<?php

class HomeModel extends Query{
    public function __construct()
    {
        parent::__construct();
    }

    public function comprobarExisteDui($dui){
        $sql = "select*from citas where dui='$dui';";
        return $this->selectAll($sql);
    }

    public function comprobarCupos($sucursal){
        $sql = "select cupos from sucursales where nombre='$sucursal';";
        return $this->selectAll($sql);
    }
    public function contarCupos($sucursal,$fecha){
        $sql = "select count(dui) as tot_cupos from citas where sucursal='$sucursal' and fecha='$fecha';";
        return $this->selectAll($sql);
    }

    public function validaHora($fecha,$sucursal,$hora){
        $sql = "select * from citas where sucursal='$sucursal' and fecha='$fecha' and hora='$hora';";
        return $this->selectAll($sql);
    }
    
    public function registrar($paciente, $dui, $fecha,$sucursal,$edad,$telefono,$ocupacion,$genero,$usuario_lente,$sector,$depto,$municipio,$hora,$user_login,$vet_titular,$dui_titular,$tel_opcional,$tipo_paciente,$institucion,$licitacion){
   
        $color="#116530";
        $estado="0";
        date_default_timezone_set('America/El_Salvador');
        $hoy_reg = date("Y-m-d");
        $hora_reg = date("H:i:s");

        $resp_dui = $this->comprobarExisteDui($dui);
        $cupos = $this->comprobarCupos($sucursal);
        $cuentaCupos = $this->contarCupos($sucursal,$fecha);
        $cupos_suc = $cupos[0]["cupos"];
        $sum_cupos = $cuentaCupos[0]["tot_cupos"];
        $valida_hora = $this->validaHora($fecha,$sucursal,$hora);
        if(count($resp_dui)>0){
            $res = 'error';
        }elseif(count($valida_hora)>0){
            $res = 'errorhora';
        }
        elseif((int)$sum_cupos >= (int)$cupos_suc){
            $res = "not";
        }elseif(((int)$sum_cupos < (int)$cupos_suc) and count($resp_dui)==0 and count($valida_hora)==0){

            $sql = "INSERT INTO citas (paciente,dui,fecha,sucursal,color,estado,telefono,edad,ocupacion,genero,usuario_lente,sector,depto,municipio,hora,fecha_reg,hora_reg,id_usuario,vet_titular,dui_titular,tel_opcional,tipo_paciente,institucion,licitacion) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $array = array($paciente, $dui, $fecha,$sucursal,$color,$estado,$telefono,$edad,$ocupacion,$genero,$usuario_lente,$sector,$depto,$municipio,$hora,$hoy_reg,$hora_reg,$user_login,$vet_titular,$dui_titular,$tel_opcional,$tipo_paciente,$institucion,$licitacion);
            
            $data = $this->save($sql, $array);
            $res = 'ok';
        }
        return $res;

    }
    
    
    public function getEventos(){
        $sql = "SELECT id_cita as id,concat(count(paciente),'-', 'citas') as title,fecha as start, color FROM citas where estado='0' group by fecha;";
        return $this->selectAll($sql);
    }
    public function modificar($title, $inicio, $color, $id)
    {
        $sql = "UPDATE evento SET title=?, start=?, color=? WHERE id=?";
        $array = array($title, $inicio, $color, $id);
        $data = $this->save($sql, $array);
        if ($data == 1) {
            $res = 'ok';
        } else {
            $res = 'error';
        }
        return $res;
    }
    public function eliminar($id)
    {
        $sql = "DELETE FROM evento WHERE id=?";
        $array = array($id);
        $data = $this->save($sql, $array);
        if ($data == 1) {
            $res = 'ok';
        } else {
            $res = 'error';
        }
        return $res;
    }
    public function dragOver($start, $id)
    {
        $sql = "UPDATE evento SET start=? WHERE id=?";
        $array = array($start, $id);
        $data = $this->save($sql, $array);
        if ($data == 1) {
            $res = 'ok';
        } else {
            $res = 'error';
        }
        return $res;
    }
}

?>