<?php
 require_once("../config/conexion.php");  

   class Productos extends Conectar{

   	public function valida_existe_aro($modelo,$color,$marca){
    	   $conectar=parent::conexion();
        parent::set_names();

        $sql ="select*from aros where modelo=? and color=? and marca=?;";
        $sql= $conectar->prepare($sql);
        $sql->bindValue(1, $modelo);
        $sql->bindValue(2, $color);
        $sql->bindValue(3, $marca);
        $sql->execute();
        return $resultado=$sql->fetchAll();
   	}

   	public function crear_aro($marca,$modelo,$color,$material){
        $conectar=parent::conexion();
        parent::set_names();

        $sql = "insert into aros values(null,?,?,?,?)";
        $sql= $conectar->prepare($sql);
        $sql->bindValue(1, $marca);
        $sql->bindValue(2, $modelo);
        $sql->bindValue(3, $color);
        $sql->bindValue(4, $material);
        $sql->execute();

        echo json_encode(["msj"=>"ok"]);
        
   	}

   	public function get_aros(){
   	   $conectar=parent::conexion();
        parent::set_names();

        $sql="select*from aros ORDER BY id_aro DESC;";
        $sql= $conectar->prepare($sql);
        $sql->execute();
        return $resultado=$sql->fetchAll();


   	}

   	public function get_data_aro_id($id_aro){
   		$conectar=parent::conexion();
        parent::set_names();

        $sql = "select*from aros where id_aro=?;";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $id_aro);
        $sql->execute();
        return $resultado=$sql->fetchAll();

   	}

   	public function eliminar_aro($id_aro){
   	   $conectar=parent::conexion();
        parent::set_names();

        $sql ="delete from aros where id_aro=?;";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $id_aro);
        $sql->execute();

   	}

     public function getCorrelativoIngreso(){
          $conectar=parent::conexion();
          parent::set_names();
          $sql= "select n_ingreso from ingreso_aros order by id_ingreso DESC limit 1;";
          $sql=$conectar->prepare($sql);
          $sql->execute();
          return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);

     }

     public function comprobarExisteCorrelativo($correlativo){
          $conectar=parent::conexion();
          parent::set_names();
          $sql = "select n_ingreso from ingreso_aros where n_ingreso = ?;";
          $sql=$conectar->prepare($sql);
          $sql->bindValue(1,$correlativo);
          $sql->execute();
          return $resultado=$sql->fetchAll();  
     }

     public function registrarIngreso($correlativo){
          $conectar=parent::conexion();
          parent::set_names();
          date_default_timezone_set('America/El_Salvador');
          $hoy = date("Y-m-d");
          $hora = date("H:i:s");
          /*  REGISTRAR INGRESO  */
          $abono = 0;
		$sql = "insert into ingreso_aros values(null,?,?,?,?,?);";
		$sql = $conectar->prepare($sql);
		$sql->bindValue(1, $correlativo);
		$sql->bindValue(2, $hoy);
		$sql->bindValue(3, $hora);
		$sql->bindValue(4, $_POST["id_usuario"]);
		$sql->bindValue(5, $_POST["sucursal"]);
          $sql->execute();


          /* INGRESAR DETALLE AROS BODEGA */
          $aros_array = array();
          $aros_array = json_decode($_POST["arrayAros"]);

          foreach ($aros_array as $key => $value) {
               $sql4 = "insert into detalle_ingreso_aros values(null,?,?,?);";
               $sql4 = $conectar->prepare($sql4);
               $sql4->bindValue(1, $value->id_aro);
               $sql4->bindValue(2, $correlativo);
               $sql4->bindValue(3, $value->cantidad);
               $sql4->execute();

               /* INGRESO A STOCK DE SUCURSAL */

               #VERIFICAR EXISTENCIA ACTUAL
               $sql2= "select stock from stock_aros where id_aro=? and bodega=?";
               $sql2 = $conectar->prepare($sql2);
               $sql2->bindValue(1, $value->id_aro);
               $sql2->bindValue(2, $_POST["sucursal"]);
               $sql2->execute();
               $resultado = $sql2->fetchAll(PDO::FETCH_ASSOC);
               
               if(is_array($resultado) and count($resultado)>0){
                    
                    foreach($resultado as $r){
                         $stock = $r["stock"];
                         $nuevo_stock= $stock+$value->cantidad;
                    }
                    $sql3 = "update stock_aros set stock=? where bodega=? and id_aro=?";
                    $sql3 = $conectar->prepare($sql3);
                    $sql3->bindValue(1,$nuevo_stock);
                    $sql3->bindValue(2,$_POST["sucursal"]);
                    $sql3->bindValue(3,$value->id_aro);
                    $sql3->execute();
               }else{
                    $sql3="insert into stock_aros values (null,?,?,?);";
                    $sql3=$conectar->prepare($sql3);
                    $sql3->bindValue(1,$_POST["sucursal"]);
                    $sql3->bindValue(2,$value->cantidad);
                    $sql3->bindValue(3,$value->id_aro);
                    $sql3->execute();
               }     
          }

          if ($sql->rowCount()>0 and $sql4->rowCount()>0 and $sql3->rowCount()>0) {               
               $msj=["msj"=>'OkInsert',"correlativo"=>$correlativo];
               echo json_encode($msj);
          }else{
               $msj=["msj"=>'Error'];
               echo json_encode($msj);
          }

     }

     public function getStockArosBodega($sucursal){
          $conectar=parent::conexion();
          parent::set_names();
          $sql = "select s.bodega,s.stock,a.modelo,a.id_aro,a.marca,a.color,a.material from stock_aros as s INNER join aros as a on a.id_aro=s.id_aro where s.bodega=? and s.stock>0;";
          $sql=$conectar->prepare($sql);
          $sql->bindValue(1,$sucursal);
          $sql->execute();
          return $resultado=$sql->fetchAll();  
     }

     public function registrarMarca($marca){

          $conectar=parent::conexion();
          parent::set_names();
          $sql = "insert into marcas values(null,?)";
          $sql=$conectar->prepare($sql);
          $sql->bindValue(1,$marca);
          $sql->execute();

          $sql2 = "select marca from marcas";
          $sql2=$conectar->prepare($sql2);
          $sql2->execute();
         return  $resultado=$sql2->fetchAll(PDO::FETCH_ASSOC); 



     }

     public function getMarcas(){
          $conectar=parent::conexion();
          parent::set_names();
          $sql2 = "select marca from marcas";
          $sql2=$conectar->prepare($sql2);
          $sql2->execute();
          return  $resultado=$sql2->fetchAll(PDO::FETCH_ASSOC);
     }




}//Fin de la clase