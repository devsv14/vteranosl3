<?php
require_once("../config/conexion.php");  

  class Entregas extends Conectar{
    public function get_entregas_ordenes($permiso){
    $conectar = parent::conexion();
    //Validacion para ocultar las sucursal (San Miguel,Usulutan, San Vicente y Centro, Sonsonate) con usuario ==> Categoria CCENTER
    if($_SESSION["categoria"] == "CCENTER"){
      $sql = "select acc.id_accion,o.id_orden,o.codigo,acc.fecha,o.fecha as fechaExp,o.dui,o.paciente,o.sucursal,o.institucion,o.estado,o.telefono from acciones_optica as acc inner join orden_lab as o on acc.dui=o.dui where o.estado IN ('5','5-e') and o.sucursal not in ('San Miguel','Usulutan','San Vicente','San Vicente Centro','Sonsonate')";
    }else{
      $sql = "select acc.id_accion,o.id_orden,o.codigo,acc.fecha,o.fecha as fechaExp,o.dui,o.paciente,o.sucursal,o.institucion,o.estado,o.telefono from acciones_optica as acc inner join orden_lab as o on acc.dui=o.dui where o.estado IN ('5','5-e')";
    }
    if($permiso == 'Ok'){
      $sql = $conectar->prepare($sql);
      $sql->execute();
      return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);
    }else{
      $sql .= " and o.sucursal=? order by o.id_orden asc";
      $sql = $conectar->prepare($sql);
      $sql->bindValue(1,$_SESSION["sucursal"]);
      $sql->execute();
      return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);
    }
  }

  public function get_acc_entregas($id_acc){
    $conectar = parent::conexion();
    $sql = "select acc.id_acc_entrega,acc.accion,acc.fecha,acc.hora,u.nombres as usuario,acc_op.id_accion,acc.estado_llamada from acciones_entrega as acc inner join usuarios as u on acc.usuario_id=u.id_usuario inner join acciones_optica as acc_op on acc.acc_optica_id=acc_op.id_accion where acc_op.id_accion=? order by id_acc_entrega DESC;";
    $sql = $conectar->prepare($sql);
    $sql->bindValue(1,$id_acc);
    $sql->execute();
    return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);
  }

  public function save_accion_entrega($data){
    $conectar=parent::conexion();
    parent::set_names();
    date_default_timezone_set('America/El_Salvador');
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");
    //Update estado == 5-e
    /* $sql_orden = "update orden_lab set estado='5-e' where codigo=?";
    $sql_orden = $conectar->prepare($sql_orden);
    $sql_orden->bindValue(1,$data['codigo']);
    $sql_orden->execute(); */
    //Insert acciones_entrega
    $sql ="insert into acciones_entrega values(null,?,?,?,?,?,?)";
    $sql= $conectar->prepare($sql);
    $sql->bindValue(1,$_POST['estadoLLamada']);
    $sql->bindValue(2,$_POST['accion']);
    $sql->bindValue(3,$fecha);
    $sql->bindValue(4,$hora);
    $sql->bindValue(5,$_SESSION["id_user"]);
    $sql->bindValue(6,$_POST['id_accion_optica']);
    if($sql->execute()){
      return 1;
    }else{
      return 0;
    }
  }

  public function get_cita_tel($dui_paciente){
    $conectar = parent::conexion();
    $sql = "select tel_opcional from citas where dui=?";
    $sql = $conectar->prepare($sql);
    $sql->bindValue(1,$dui_paciente);
    $sql->execute();
    return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getCantidadLlamadasOrden($id_accion_entrega){
    $conectar = parent::conexion();
    $sql = "select ae.fecha from acciones_entrega as ae WHERE ae.acc_optica_id=? ORDER by ae.id_acc_entrega DESC limit 1";
    $sql = $conectar->prepare($sql);
    $sql->bindValue(1,$id_accion_entrega);
    $sql->execute();
    $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);
    $resultado = count($resultado) > 0 ? $resultado : [];
    return $resultado;
  }
  
}//Fin de la Clase

