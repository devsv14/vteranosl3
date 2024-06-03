<?php

require_once("../config/conexion.php");  

class Facturas extends Conectar{

    public function guardar_factura_manual($data){
        $conectar= parent::conexion();
        date_default_timezone_set('America/El_Salvador');
        $fecha_hora = date("d-m-Y H:i:s");
        $date = date('Y-m-d');
        $sql1 = "insert into facturas(id_factura,num_factura,cliente,telefono,direccion,retencion,fecha,fecha_creacion,det_fact_json) values(null,?,?,?,?,?,?,?,?)";
        $sql1 = $conectar->prepare($sql1);
        $sql1->bindValue(1,$data['cod_factura']);
        $sql1->bindValue(2,$data['cliente']);
        $sql1->bindValue(3,$data['telefono']);
        $sql1->bindValue(4,$data['direccion']);
        $sql1->bindValue(5,$data['retencion']);
        $sql1->bindValue(6,$data['fecha']);
        $sql1->bindValue(7,$fecha_hora);
        $sql1->bindValue(8,$data['data']);
        if($sql1->execute()){
            $id_factura = $conectar->lastInsertId();
            foreach($data['info'] as $row){
                $sql2 = "insert into det_facturas values(null,?,?,?,?,?)";
                $sql2 = $conectar->prepare($sql2);
                $sql2->bindValue(1,$row['id']);
                $sql2->bindValue(2,$row['cantidad']);
                $sql2->bindValue(3,$row['desc']);
                $sql2->bindValue(4,$row['punit']);
                $sql2->bindValue(5,$id_factura);
                $sql2->execute();
            }
            return true;
        }else{
            return false;
        }

    }
    public function save_factura_CCF_manual($data){
        $conectar= parent::conexion();
        date_default_timezone_set('America/El_Salvador');
        $fecha_hora = date("d-m-Y H:i:s");
        $date = date('Y-m-d');
        $sql1 = "insert into facturas(id_factura,num_factura,no_registro,cliente,telefono,direccion,nit,giro,retencion,gran_contribuyente,fecha,fecha_creacion) values(null,?,?,?,?,?,?,?,?,?,?,?)";
        $sql1 = $conectar->prepare($sql1);
        $sql1->bindValue(1,$data['cod_factura']);
        $sql1->bindValue(2,$data['num_registro']);
        $sql1->bindValue(3,$data['cliente']);
        $sql1->bindValue(4,$data['telefono']);
        $sql1->bindValue(5,$data['direccion']);
        $sql1->bindValue(6,$data['nit']);
        $sql1->bindValue(7,$data['giro']);
        $sql1->bindValue(8,$data['retencion']);
        $sql1->bindValue(9,$data['contribuyente']);
        $sql1->bindValue(10,$data['fecha']);
        $sql1->bindValue(11,$fecha_hora);
        if($sql1->execute()){
            $id_factura = $conectar->lastInsertId();
            foreach($data['info'] as $row){
                $sql2 = "insert into det_facturas values(null,?,?,?,?,?)";
                $sql2 = $conectar->prepare($sql2);
                $sql2->bindValue(1,$row['id']);
                $sql2->bindValue(2,$row['cantidad']);
                $sql2->bindValue(3,$row['desc']);
                $sql2->bindValue(4,$row['punit']);
                $sql2->bindValue(5,$id_factura);
                $sql2->execute();
            }
            return true;
        }else{
            return false;
        }
    }

    public function listar_facturas_manuales($fact_ccf_manual = false){
        $conectar = parent::conexion();
        if($fact_ccf_manual){
            $sql = "select * from facturas where no_registro !='' order by id_factura";
        }else{
            $sql = "select * from facturas where no_registro = '' or no_registro is null order by id_factura";
        }
        $sql = $conectar->prepare($sql);
        $sql->execute();
        return $result = $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function show_factura($array_data){
        $conectar = parent::conexion();
        //DONDE SEAN SOLO FACTURA
        $sql = "SELECT * FROM `facturas` WHERE id_factura=?";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1,$array_data['id_factura']);
        $sql->execute();
        $datosFactura = $sql->fetchAll(PDO::FETCH_ASSOC);
        //DATOS DE LA FACTURA
        $sql2 = "SELECT * FROM `det_facturas` WHERE factura_id=?";
        $sql2 = $conectar->prepare($sql2);
        $sql2->bindValue(1,$array_data['id_factura']);
        $sql2->execute();
        $det_factura = $sql2->fetchAll(PDO::FETCH_ASSOC);
        //Structura para entregar al controller
        $data = [
            "factura" => $datosFactura[0],
            "det_factura_manual" => $det_factura
        ];
        return $data;
    }

    public function update_factura_manual($data){

        $conectar= parent::conexion();
        date_default_timezone_set('America/El_Salvador');
        $fecha_hora = date("d-m-Y H:i:s");
        $date = date('Y-m-d');
        $sql1 = "update facturas set num_factura=?,cliente=?,telefono=?,direccion=?,fecha=? where id_factura=?";
        $sql1 = $conectar->prepare($sql1);
        $sql1->bindValue(1,$data['cod_factura']);
        $sql1->bindValue(2,$data['cliente']);
        $sql1->bindValue(3,$data['telefono']);
        $sql1->bindValue(4,$data['direccion']);
        $sql1->bindValue(5,$data['fecha']);
        $sql1->bindValue(6,$data['id_factura']);
        if($sql1->execute()){
            //Eliminación
            $sql_del_fact = "delete from det_facturas where factura_id=?";
            $sql_del_fact = $conectar->prepare($sql_del_fact);
            $sql_del_fact->bindValue(1,$data['id_factura']);
            $sql_del_fact->execute();

            foreach($data['info'] as $row){
                $sql2 = "insert into det_facturas values(null,?,?,?,?,?)";
                $sql2 = $conectar->prepare($sql2);
                $sql2->bindValue(1,$row['id']);
                $sql2->bindValue(2,$row['cantidad']);
                $sql2->bindValue(3,$row['desc']);
                $sql2->bindValue(4,$row['punit']);
                $sql2->bindValue(5,$data['id_factura']);
                $sql2->execute();
            }
            return true;
        }else{
            return false;
        }

    }
    public function update_factura_CCF_manual($data){
        $conectar= parent::conexion();
        date_default_timezone_set('America/El_Salvador');
        $fecha_hora = date("d-m-Y H:i:s");
        $date = date('Y-m-d');
        $sql1 = "update facturas set num_factura=?,no_registro=?,cliente=?,telefono=?,direccion=?,nit=?,giro=?,retencion=?,fecha=? where id_factura=?";
        $sql1 = $conectar->prepare($sql1);
        $sql1->bindValue(1,$data['cod_factura']);
        $sql1->bindValue(2,$data['num_registro']);
        $sql1->bindValue(3,$data['cliente']);
        $sql1->bindValue(4,$data['telefono']);
        $sql1->bindValue(5,$data['direccion']);
        $sql1->bindValue(6,$data['nit']);
        $sql1->bindValue(7,$data['giro']);
        $sql1->bindValue(8,$data['retencion']);
        $sql1->bindValue(9,$data['fecha']);
        $sql1->bindValue(10,$data['id_factura']);
        if($sql1->execute()){
            //Eliminación
            $sql_del_fact = "delete from det_facturas where factura_id=?";
            $sql_del_fact = $conectar->prepare($sql_del_fact);
            $sql_del_fact->bindValue(1,$data['id_factura']);
            $sql_del_fact->execute();

            foreach($data['info'] as $row){
                $sql2 = "insert into det_facturas values(null,?,?,?,?,?)";
                $sql2 = $conectar->prepare($sql2);
                $sql2->bindValue(1,$row['id']);
                $sql2->bindValue(2,$row['cantidad']);
                $sql2->bindValue(3,$row['desc']);
                $sql2->bindValue(4,$row['punit']);
                $sql2->bindValue(5,$data['id_factura']);
                $sql2->execute();
            }
            return true;
        }else{
            return false;
        }
    }

    public function delete_factura($id_factura){
        $conectar = parent::conexion();
        $sql1 = "delete from facturas where id_factura=?";
        $sql1 = $conectar->prepare($sql1);
        $sql1->bindValue(1,$id_factura);
        if($sql1->execute()){
            return true;
        }else{
            return false;
        }
    }

}