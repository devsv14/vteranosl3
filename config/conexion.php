<?php
session_start([
    'cookie_lifetime' => 86400,
    'gc_maxlifetime' => 86400,
]);
class Conectar {

 	protected $dbh;
 	protected function conexion(){
 	try {
	    $conectar = $this->dbh = new PDO("mysql:local=localhost;dbname=veteranos3","oscargz","oscar1411");
	    //$conectar = $this->dbh = new PDO("mysql:local=localhost;dbname=u579024306_inabe3","u579024306_avplus24","AndVas2024_inabve");
		return $conectar;
    }catch (Exception $e) {
 			print "¡Error!: " . $e->getMessage() . "<br/>";
            die();
 	}
	} //cierre de llave de la function conexion()


    public function set_names(){
		return $this->dbh->query("SET NAMES 'utf8'");
    }
	public function ruta(){
		return "localhost/veteranos/";
	}
	protected function conexion_lenti(){
		try {
		   $conectarLenti = $this->dbh = new PDO("mysql:local=localhost;dbname=lenti","root","");
		   //$conectarLenti = $this->dbh = new PDO("mysql:local=localhost;dbname=u579024306_lenti","u579024306_rlenti","And20vas08");
		   return $conectarLenti;
	   }catch (Exception $e) {
				print "¡Error!: " . $e->getMessage() . "<br/>";
			   die();
		}
	}
	
	protected function conexion_inabve1(){
		try {
		    $conectarVet1 = $this->dbh = new PDO("mysql:local=localhost;dbname=veteranos3","oscargz","oscar1411");
		   
		    //$conectarVet1 = $this->dbh = new PDO("mysql:local=localhost;dbname=u579024306_envios","u579024306_env2021","Envios_2021");
		   return $conectarVet1;
	   }catch (Exception $e) {
				print "¡Error!: " . $e->getMessage() . "<br/>";
			   die();
		}
	} 

    //Función para convertir fecha del mes de numero al nombre, ejemplo de 01 a enero
	public static function convertir($string){
	    $string = str_replace(
	    array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'),
	    array('ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', ' DICIEMBRE'),$string);
	    return $string;
	}

}//cierre de llave conectar


class conexionLenti{
	protected $dbh;
 	protected function conexion_lenti(){
 	try {
	    //$conectarLenti = $this->dbh = new PDO("mysql:local=localhost;dbname=lenti","root","");
		$conectarLenti = $this->dbh = new PDO("mysql:local=localhost;dbname=u579024306_lenti","u579024306_rlenti","And20vas08");
		return $conectarLenti;
    }catch (Exception $e) {
 			print "¡Error!: " . $e->getMessage() . "<br/>";
            die();
 	}
	} //cierre de llave de la function conexion()

	public function set_names(){
		return $this->dbh->query("SET NAMES 'utf8'");
    }
    	public function ruta(){
		return "localhost/veteranos/";
	}
	
}
$sucursales_array = ["Valencia","Metrocentro","Cascadas","Santa Ana","Chalatenango","Ahuachapan","Sonsonate","Ciudad Arce","Opico","Apopa","San Vicente Centro","San Vicente","Gotera","San Miguel","Usulutan","Sede Bernal"];
$sucursales = '
	<option value="0">Seleccionar sucursal...</option>
	<option value="inabve">INABVE</option>
	<option value="Valencia">Valencia</option>
	<option value="Metrocentro">Metrocentro</option>
	<option value="San Miguel AV PLUS">San Miguel AV PLUS</option>
	<option value="Cascadas">Cascadas</option>
	<option value="Santa Ana">Santa Ana</option>
	<option value="Chalatenango">Chalatenango</option>
	<option value="Ahuachapan">Ahuachapan</option>
	<option value="Sonsonate">Sonsonate</option>
	<option value="Ciudad Arce">Ciudad Arce</option>                                   
	<option value="Opico">Opico</option>
	<option value="Apopa">Apopa</option>
	<option value="San Vicente Centro">San Vicente Centro</option>
	<option value="San Vicente">San Vicente</option>
	<option value="Gotera">Gotera</option>
	<option value="San Miguel">San Miguel</option>
	<option value="Usulutan">Usulutan</option>
	<option value="Sede Bernal">Sede Bernal</option>
	

';

?>