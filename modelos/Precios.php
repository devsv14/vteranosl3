<?php
require_once("../config/conexion.php");  

  class Precios extends Conectar{
    public function getSucursalMonto(){
    $conectar = parent::conexion();     
    $sql= "SELECT id_orden,tipo_lente,color,alto_indice,sucursal FROM `orden_lab` WHERE estado!='l1'";
    $sql=$conectar->prepare($sql);
    $sql->execute();
    return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);
  }
  public function getCantidadOrdenesMonto($tipo_lente,$alto_indice,$color,$sucursal){

    $precio = 0;
    if($tipo_lente == "Visión Sencilla" and $alto_indice == "No" and $color == "Blanco"){
       $precio = 30;
 
     }else if($tipo_lente == "Visión Sencilla" and $alto_indice == "No" and $color == "Photocromatico"){
       $precio = 40;
       
     }else if($tipo_lente == "Visión Sencilla" and $alto_indice == "Si" and $color == "Blanco"){
       $precio = 45;
 
     }else if($tipo_lente == "Visión Sencilla" and $alto_indice == "Si" and $color == "Photocromatico"){
       $precio = 50;

     }else if($tipo_lente == "Flaptop" and $alto_indice == "No" and $color == "Blanco"){
       $precio = 42;
 
     }else if($tipo_lente == "Flaptop" and $alto_indice == "No" and $color == "Photocromatico"){
       $precio = 52;
 
     }else if($tipo_lente == "Flaptop" and $alto_indice == "Si" and $color == "Blanco"){
       $precio = 57;
 
     }else if($tipo_lente == "Flaptop" and $alto_indice == "Si" and $color == "Photocromatico"){
       $precio = 62;
 
 
     }else if($tipo_lente == "Progresive" and $alto_indice == "No" and $color == "Blanco"){
       $precio = 60;
 
     }else if($tipo_lente == "Progresive" and $alto_indice == "No" and $color == "Photocromatico"){
       $precio = 70;
 
     }else if($tipo_lente == "Progresive" and $alto_indice == "Si" and $color == "Blanco"){
       $precio = 75;
 
     }else if($tipo_lente == "Progresive" and $alto_indice == "Si" and $color == "Photocromatico"){
       $precio = 80;
     }
     //Return $precio  y sucursal
     return [
        'sucursal' => $sucursal,
        'precio' => (int)$precio
     ];
  }

  public function getSucursal(){
    $conectar = parent::conexion();     
    $sql = "SELECT DISTINCT sucursal,'0' as 'monto', '0' as 'cantidad' FROM orden_lab WHERE estado <='6'";
    $sql=$conectar->prepare($sql);
    $sql->execute();
    return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);
  }
  
}//Fin de la Clase

