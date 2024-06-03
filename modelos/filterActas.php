<?php

require_once("../config/conexion.php");

class filterActas extends Conectar{

        public function count_ampos($sucursal)  {
            $conectar = parent::conexion();
            parent::set_names();
            $sql="select count(id_acta) as cant_actas from actas where sucursal=?;";
            $sql = $conectar->prepare($sql);
            $sql->bindValue(1,$sucursal);
            $sql->execute();
            $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
            $ampos = ($resultado[0]['cant_actas'])/125;
            return ceil($ampos);
          
        }

        public function getRangoActas($serie,$sucursal){

            $conectar = parent::conexion();
            parent::set_names();
            $limits = explode('-', $serie);
            $init = (int)$limits[0];
            $end = (int)$limits[1];
             
            $sql="select * from actas where sucursal=? limit $init,$end;";
            $sql = $conectar->prepare($sql);
            $sql->bindValue(1,$sucursal);
            $sql->execute();
            $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
            //return $sucursal." i".$init." e".$end;
            return $resultado;

        }

}

