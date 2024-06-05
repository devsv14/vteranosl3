<?php
require_once("../config/conexion.php");

class EnviarExpediente extends Conectar
{
    public function get_correlativo_orden($fecha)
    {
        $conectar = parent::conexion();
        $fecha_act = $fecha . '%';
        $sql = "select codigo from orden_lab where fecha_correlativo like ? order by id_orden DESC limit 1;";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $fecha_act);
        $sql->execute();
        return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
    }
}//Fin de la Clase