<?php

  require_once("../config/conexion.php");
  

   class AccionesOptica extends Conectar{
   ///////////////////////GET DATA //////////////
   public function verificarExisteAccion($dui,$accion){
    $conectar= parent::conexion();
    $sql= "select * from acciones_optica where dui=? and accion=?";
    $sql=$conectar->prepare($sql);
    $sql->bindValue(1,$dui);
    $sql->bindValue(2,$accion);
    $sql->execute();
    return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);
   }


   public function get_dataOrden($dui,$tipo_accion){
    $conectar= parent::conexion();
        $resp_accion = $this->verificarExisteAccion($dui,$tipo_accion);

        $sql= "select * from orden_lab where dui=?";
        $sql=$conectar->prepare($sql);
        $sql->bindValue(1,$dui);
        $sql->execute();
        $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);   

    if(count($resp_accion)==0 and count($resultado)>0){
        echo json_encode(["msj"=>"ok","datos"=>$resultado[0]]);
    }elseif(count($resp_accion)>0 and count($resultado)>0){
        echo json_encode(["msj"=>"error","datos"=>$resultado[0]]);
    }elseif(count($resultado)==0){
        echo json_encode(["msj"=>"vacio"]);
    }
   }

   public function registrarAccion(){
    $conectar= parent::conexion();
    parent::set_names();
    date_default_timezone_set('America/El_Salvador'); $hoy = date("d-m-Y");$hora = date("H:i:s");
    $detalle_ordenes = array();
    $detalle_ordenes = json_decode($_POST["arrayOrdenesAccOpt"]);

    foreach($detalle_ordenes as $k=>$v){
        $dui = $v->dui;
        $sucursal = $v->sucursal;
        $accion = $v->accion;

        $sql = "insert into acciones_optica values(null,?,?,?,?,?,?,?);";
        $sql=$conectar->prepare($sql);
        $sql->bindValue(1, $dui);
        $sql->bindValue(2, $hoy);
        $sql->bindValue(3, $hora);
        $sql->bindValue(4, $_SESSION["sucursal"]);
        $sql->bindValue(5, $_SESSION["user"]);
        $sql->bindValue(6, $accion);
        $sql->bindValue(7, 0);
        $resp1 = $sql->execute() ? true : false;


        $sql2 ="update orden_lab set estado='5' where dui=?;";
        $sql2=$conectar->prepare($sql2);
        $sql2->bindValue(1, $dui);    
        $resp2 = $sql2->execute() ? true : false;
        /////////////Agregarlo en acciones orden

        $sql3 = "insert into acciones_orden values(null,?,?,?,?,?,?);";
        $sql3 = $conectar->prepare($sql3);
        $sql3->bindValue(1, $hoy." ".$hora);
        $sql3->bindValue(2, $_SESSION["user"]);
        $sql3->bindValue(3, $dui);
        $sql3->bindValue(4, "Recibir en optica");
        $sql3->bindValue(5, $accion);
        $sql3->bindValue(6, $_SESSION["sucursal"]);  
        $resp3 = $sql3->execute() ? true : false;

    }

    if($resp1 and $resp2 and $resp3){
       echo json_encode(["msj"=>'success-act']);
    }else{
       echo json_encode(["msj"=>'error-act']);
    }
   

   }

   public function getOrdenesIngresadas(){
    $conectar = parent::conexion();
    parent::set_names();
    $sucursal = $_SESSION["sucursal"];
    $lista_general = in_array('actas_lista_general',$_SESSION["names_permisos"]) ? 1 : 0;
    if($lista_general==1){
        $sql = "SELECT a.id_accion,o.paciente,o.dui,o.id_cita,o.id_aro,o.institucion,o.codigo,a.sucursal,a.fecha,a.hora,a.usuario from orden_lab as o INNER JOIN acciones_optica as a on a.dui=o.dui where a.accion = 'ingreso_orden_optica' and o.estado IN ('5','5-e') order by a.id_accion DESC";
    }else{
        $sql = "SELECT a.id_accion,o.paciente,o.dui,o.id_cita,o.id_aro,o.institucion,o.codigo,a.sucursal,a.fecha,a.hora,a.usuario from orden_lab as o INNER JOIN acciones_optica as a on a.dui=o.dui where a.sucursal = '$sucursal' and a.accion = 'ingreso_orden_optica' and o.estado IN ('5','5-e') order by a.id_accion DESC";
    }    
    $sql = $conectar->prepare($sql);
    $sql->execute();
    return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
    
   }
    
   public function getOrdenesDespachar($cod_despacho){
    
    $conectar= parent::conexion();
    parent::set_names();
    $accion = 'ingreso_orden_optica';
    $sql = 'select * from detalle_ordenes_envio where cod_despacho = ? ;';
    $sql = $conectar->prepare($sql);
    $sql->bindValue(1, $cod_despacho);
    $sql->execute();
    $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
    $return_data = array();
    if(count($resultado)>0){
        foreach($resultado as $r){
        
            $sql= "select * from orden_lab where codigo=?";
            $sql=$conectar->prepare($sql);
            $sql->bindValue(1,$r["cod_orden_lab"]);
            $sql->execute();
            $result_orden= $sql->fetchAll(PDO::FETCH_ASSOC); 
            $dui = $result_orden[0]["dui"];
            $tipo_accion = 'ingreso_orden_optica';

            $resp_accion = $this->verificarExisteAccion($dui,$tipo_accion);

            $paciente  =  $result_orden[0]["paciente"];
            $sucursal  =  $result_orden[0]["sucursal"];
            $fecha  =  $result_orden[0]["fecha"];
            if(count($resp_accion)==0){
               array_push($return_data,["msj"=>"ok","datos"=>['paciente'=>$paciente,'dui'=>$dui,'sucursal'=>$sucursal,'accion'=>'ingreso_orden_optica','fecha'=>$fecha]]);           
            }elseif(count($resp_accion)>0 and count($resultado)>0){
                array_push($return_data,["msj"=>"error","datos"=>['paciente'=>$paciente,'dui'=>$dui,'sucursal'=>$sucursal,'accion'=>'ingreso_orden_optica','fecha'=>$fecha]]);
            }
            
        }
    }else{
        echo json_encode(["msj"=>"vacio"]);
    }

    echo json_encode($return_data);

    

   }

}//Fin clase
