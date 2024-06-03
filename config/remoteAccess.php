<?php

class remoteAccess {

    protected $dbh;
    protected function conexion(){
    try {
       
       $conectar = $this->dbh = new PDO("mysql:host=sql208.main-hosting.eu;dbname=u579024306_veteranos","u579024306_vets","Veteranos_2022");
       return $conectar;
   }catch (Exception $e) {
            print "¡Error!: " . $e->getMessage() . "<br/>";
           die();
    }
   } //cierre de llave de la function conexion()
}