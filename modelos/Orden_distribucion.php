<?php



require_once("../config/conexion.php");
class ScanActas extends Conectar{
    

  /**GET AMPO */
  public function getOrdenesAll(){
    $conectar = parent::conexion();
    $sql = "SELECT codigo,paciente,dui,estado,genero,sucursal,fecha FROM `orden_distribucion` WHERE estado in ('1','2','3','4','5','2-b','3-b','4-b') and sucursal not in ('Apopa','Metrocentro','Cascada')";
    $sql = $conectar->prepare($sql);
    $sql->execute();
    return $result = $sql->fetchAll(PDO::FETCH_ASSOC);
  }
  /**
   * GET DATA
   * Function para traer datos y seleccionar el ampo a almacenar
   */
  public function getActasPorIdDui($value){
    $conectar=parent::conexion();
    $sql="select*from actas where dui_acta=? or id_acta=?;";
    $sql=$conectar->prepare($sql);
    $sql->bindValue(1, $value);
    $sql->bindValue(2, $value);
    $sql->execute();
    $data = $sql->fetchAll(PDO::FETCH_ASSOC);
    //Sucursal
    $sucursal = (count($data) > 0) ? $data[0]['sucursal'] : '';
    //Full DATA ACTAS
    $actas = []; $resData = [];
    if(count($data) > 0){
      $sql="select*from actas where sucursal=?;";
      $sql=$conectar->prepare($sql);
      $sql->bindValue(1, $sucursal);
      $sql->execute();
      $actas = $sql->fetchAll(PDO::FETCH_ASSOC);
      $resData = [
        "acta" => $data,
        "dataActas" => $actas
      ];
    }
    return $resData;
}

  public  function eliminarOrdenesAd(){
    $conectar=parent::conexion();
    $detalles = array();
    $detalles = json_decode($_POST['arrayDist']);
    foreach ($detalles as $k => $v) {
      $dui = $v->dui;
      $sql="delete from orden_distribucion where dui=?;";
      $sql=$conectar->prepare($sql);
      $sql->bindValue(1, $dui);
      $sql->execute();
      
    }

    echo json_encode(['msj'=>'ok']);

  } 
}





//uploadImages();
