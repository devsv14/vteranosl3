<?php

  require_once("../config/conexion.php");
  

   class Ordenes extends Conectar{
   ///////////////////////AGRUPA AROS //////////////
   public function get_ordenes(){
    $conectar= parent::conexion();
    $sql= "select id_orden,codigo,marca_aro,modelo_aro,horizontal_aro,vertical_aro,puente_aro,img,COUNT(*) as cantidad from orden_lab group by modelo_aro,horizontal_aro,vertical_aro,puente_aro order by cantidad DESC";
    $sql=$conectar->prepare($sql);
    $sql->execute();
    return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getOrdenesArosEnviar($inicio,$hasta){
    $conectar= parent::conexion();
    $sql= "select id_orden,codigo,marca_aro,modelo_aro,horizontal_aro,vertical_aro,puente_aro,img,COUNT(*) as cantidad from orden_lab where estado_aro='0' and fecha between ? and ? group by modelo_aro,horizontal_aro,vertical_aro,puente_aro order by cantidad DESC";
    $sql=$conectar->prepare($sql);
    $sql->bindValue(1,$inicio);
    $sql->bindValue(2,$hasta);
    $sql->execute();
    return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);
    }


    public function get_ordenes_received(){
      $conectar = parent::conexion();
      $sql = "select o.codigo,o.paciente,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,o.id_orden,o.tipo_lente,a.fecha,a.observaciones from rx_orden_lab as rx INNER JOIN orden_lab as o on rx.codigo=o.codigo INNER JOIN acciones_orden as a on o.codigo=a.codigo WHERE a.tipo_accion='Envio' and o.estado='1';";
      $sql=$conectar->prepare($sql);
      $sql->execute();

      return $resulta = $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_orders_processing(){
      $conectar = parent::conexion();
      $sql = "select o.codigo,o.paciente,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,o.id_orden,o.tipo_lente,a.fecha,a.observaciones from rx_orden_lab as rx INNER JOIN orden_lab as o on rx.codigo=o.codigo INNER JOIN acciones_orden as a on o.codigo=a.codigo WHERE a.tipo_accion='Recibido' and o.estado='2';";
      $sql=$conectar->prepare($sql);
      $sql->execute();

      return $resulta = $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_data_array_received($codigo){
      $conectar = parent::conexion();
      $sql = "select o.id_orden,o.codigo,o.paciente,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,o.id_orden,o.tipo_lente,a.fecha,a.observaciones from
      rx_orden_lab as rx INNER JOIN orden_lab as o on rx.codigo=o.codigo INNER JOIN acciones_orden as a on o.codigo=a.codigo WHERE
      o.codigo=? and rx.codigo=?;";
      $sql=$conectar->prepare($sql);
      $sql->bindValue(1,$codigo);
      $sql->bindValue(2,$codigo);
      $sql->execute();

      return $resulta = $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registerReceived(){
    $conectar = parent::conexion();
    parent::set_names();
    date_default_timezone_set('America/El_Salvador'); $hoy = date("d-m-Y H:i:s");
    $detalle_envio = array();
    $detail_received = json_decode($_POST["arrayReceived"]);
    $user = 1;
    $obs = "";
    $accion = "Recibido";
    foreach ($detail_received as $value) {
      $codigoOrden = $value;

      /////////////////Validar si existe orden en tabla acciones
      $sql2 = "select codigo from acciones_orden where codigo=? and tipo_accion='';";
      $sql2=$conectar->prepare($sql2);
      $sql2->bindValue(1, $codigoOrden);
      $sql2->execute();
      $resultado = $sql2->fetchAll(PDO::FETCH_ASSOC);
      ############REGISTRAR ACCION#############
      if(is_array($resultado)==true and count($resultado)==0){
        $sql3 = "update orden_lab set estado='2' where codigo=?;";
        $sql3=$conectar->prepare($sql3);
        $sql3->bindValue(1,$codigoOrden);
        $sql3->execute();
        ###########################################################
        $sql = "insert into acciones_orden values(null,?,?,?,?,?);";
        $sql=$conectar->prepare($sql);
        $sql->bindValue(1, $hoy);
        $sql->bindValue(2, $user);
        $sql->bindValue(3, $codigoOrden);
        $sql->bindValue(4, $accion);
        $sql->bindValue(5, $obs);
        $sql->execute();
      }
      
    }//////////////FIN FOREACH 

    }

    public function sendAros($modelo,$horizontal,$vertical,$puente,$cantidad,$dest_aro){
    $conectar=parent::conexion();
    parent::set_names();
    
    /*$array = array();*/

    for($i = 1; $i <= $cantidad; $i++) {
        $sql="select * from orden_lab where modelo_aro=? and horizontal_aro=? and vertical_aro=? and puente_aro=? and estado_aro = '0' order by id_orden ASC limit 1;";
        $sql=$conectar->prepare($sql);
        $sql->bindValue(1, $modelo);
        $sql->bindValue(2, $horizontal);
        $sql->bindValue(3, $vertical);
        $sql->bindValue(4, $puente);
        $sql->execute();
        $resultado=$sql->fetchAll(PDO::FETCH_ASSOC);
        /*array_push($array,$resultado);*/
        foreach ($resultado as $value) {
            $codigo = $value["codigo"];
            $id_orden = $value["id_orden"];
            $sql2 = "update orden_lab set estado_aro='1',dest_aro=? where id_orden=? and codigo=?;";
            $sql2 = $conectar->prepare($sql2);
            $sql2->bindValue(1,$dest_aro);
            $sql2->bindValue(2,$id_orden);            
            $sql2->bindValue(3,$codigo);
            $sql2->execute();
         
        }
    }
}
    //return $array;*/
    //}
  //}

   }//Fin de la Clase




?>