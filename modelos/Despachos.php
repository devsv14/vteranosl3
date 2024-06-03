<?php

require_once("../config/conexion.php");

class Despachos extends Conectar{
  
    public function getDespachos(){
        $conectar = parent::conexion();
        parent::set_names();
    }

    public function getCorrelativoEnvio(){
        $conectar = parent::conexion();
        $sql= "select n_despacho from despachos_lab order by id_despacho DESC limit 1;";
        $sql=$conectar->prepare($sql);
        $sql->execute();
        $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);

        if(count($resultado)==0){
            $correlativo = "ENV-1";
        }else{
            foreach($resultado as $row){
                $corr = $row["n_despacho"];
            }
            $nuevo_corr = substr($corr,4,20);
            $correlativo = "ENV-".((int)$nuevo_corr +(int)1);
        }
        return $correlativo;
    }

    public function registrarDespachos(){
        $conectar = parent::conexion();
        parent::set_names();

        date_default_timezone_set('America/El_Salvador');
        $hoy = date("Y-m-d");
        $hora = date("H:i:s");

        $sucursal = $_POST['sucursal'];
        $id_usuario = $_POST["id_usuario"];

        $ordenes = array();
        $ordenes = json_decode($_POST["ordenes_desp"]);

        $correlativo = $this->getCorrelativoEnvio();

        $sql = "insert into despachos_lab values(null,?,?,?,?,?)";
        $sql=$conectar->prepare($sql);
        $sql->bindValue(1, $correlativo);
        $sql->bindValue(2, $hoy);
        $sql->bindValue(3, $hora);
        $sql->bindValue(4, $id_usuario);
        $sql->bindValue(5, $sucursal);
        $sql->execute();

        foreach($ordenes as $key=>$v){
            $sql2 = "insert into det_despacho_lab values(null,?,?,?,?);";
            $sql2 = $conectar->prepare($sql2);
            $sql2->bindValue(1, $correlativo);
            $sql2->bindValue(2, $v->dui);
            $sql2->bindValue(3, $v->paciente);
            $sql2->bindValue(4, '0');
            $sql2->execute();

            $accion = "Envio a laboratorio desde ".$sucursal;

            $accion = "Envio a laboratorio desde ".$sucursal;

            $sql7 = "insert into acciones_orden values(null,?,?,?,?,?,?);";
            $sql7 = $conectar->prepare($sql7);
            $sql7->bindValue(1, $hoy);
            $sql7->bindValue(2, $_POST["usuario"]);
            $sql7->bindValue(3, $v->dui);
            $sql7->bindValue(4, "Despacho a Lab. No. Envio ".$correlativo);
            $sql7->bindValue(5, $accion);
            $sql7->bindValue(6, $sucursal);
            $sql7->execute();

            $sql3 = "update orden_lab set estado='1' where dui=?";
            $sql3 = $conectar->prepare($sql3); 
            $sql3->bindValue(1, $v->dui);
            $sql3->execute();
        } 
        $msj = ["correlativo"=>$correlativo];
        echo json_encode($msj);
    }
    
        public function getOrdenesDespachadas($list_gen,$sucursal){
        $conectar = parent::conexion();
        parent::set_names();

        if($list_gen=="true"){
            $sql = "SELECT d.id_despacho,count(dt.n_despacho) as cantidad,d.hora,d.n_despacho,d.fecha,d.sucursal from despachos_lab as d INNER join det_despacho_lab as dt on dt.n_despacho=d.n_despacho GROUP by d.n_despacho order by d.id_despacho DESC;";
            $sql = $conectar->prepare($sql);
            $sql->execute();
        }else{
            $sql = "SELECT d.id_despacho,count(dt.n_despacho) as cantidad,d.hora,d.n_despacho,d.fecha,d.sucursal from despachos_lab as d INNER join det_despacho_lab as dt on dt.n_despacho=d.n_despacho where sucursal=? GROUP by d.n_despacho order by d.id_despacho DESC;";
            $sql = $conectar->prepare($sql);
            $sql->bindValue(1,$sucursal);
            $sql->execute();
        }

        return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
    }

}