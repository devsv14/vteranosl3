<?php

require_once("../config/conexion.php");

class Licitacion1 extends Conectar{
    
    public function getDataOrdenesl1($dui){
        $conectar = parent::conexion_inabve1();
        $sql = "SELECT * FROM `orden_lab` as o inner join rx_orden_lab as r on r.codigo = o.codigo where dui=?; ";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $dui);
        $sql->execute();
        return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    
}