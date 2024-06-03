<?php

require_once("../config/conexion.php");

class Actas extends Conectar{
    
    public function getCorrelativoactaSuc($sucursal){
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "select correlativo_sucursal from actas where sucursal=? order by id_acta DESC limit 1;";
        $sql=$conectar->prepare($sql);
        $sql->bindValue(1, $sucursal);
        $sql->execute();
        $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);    
        if (is_array($resultado) == true and count($resultado) > 0) {
            foreach ($resultado as $row) {
              $corr = substr($row["correlativo_sucursal"], 2, 15) + 1;
              $correlativo = "A-". $corr;
            }
          } else {
            $correlativo = "A-1";
          }
        return $correlativo;
    }

    public function registrarActa($codigo_orden,$titular,$nombre_receptor,$receptor,$sucursal,$id_usuario,$dui_receptor){

        $conectar = parent::conexion();
        parent::set_names();

        date_default_timezone_set('America/El_Salvador'); 
        $hoy = date("d-m-Y");
        $hora = date(" H:i:s");
        $correlativo=$this->getCorrelativoactaSuc($sucursal);
            
            $ord = "select sucursal from orden_lab where dui=? and codigo=?;";
            $ord = $conectar->prepare($ord);
            $ord->bindValue(1, $_POST['dui_acta']);
            $ord->bindValue(2, $codigo_orden);
            $ord->execute();
            $data_acta = $ord->fetchAll(PDO::FETCH_ASSOC);
            $sucursal_acta = $data_acta[0]["sucursal"];
            $sql = "insert into actas values (null,?,?,?,?,?,?,?,?,?,?,?,?,?);";
            $sql = $conectar->prepare($sql);
            $sql->bindValue(1, $codigo_orden);
            $sql->bindValue(2, $titular);
            $sql->bindValue(3, $hoy);
            $sql->bindValue(4, $hora);
            $sql->bindValue(5, $receptor);
            $sql->bindValue(6, $nombre_receptor);
            $sql->bindValue(7, $dui_receptor);
            $sql->bindValue(8, $id_usuario);
            $sql->bindValue(9, $sucursal_acta);
            $sql->bindValue(10, $correlativo);
            $sql->bindValue(11, $_POST['dui_acta']);
            $sql->bindValue(12,0);
            $sql->bindValue(13,'');
            $sql->execute();
        
            $sql2 = "select*from actas where codigo_orden=? and dui_acta=? order by id_acta desc limit 1;";
            $sql2 = $conectar->prepare($sql2);
            $sql2->bindValue(1, $codigo_orden);
            $sql2->bindValue(2, $_POST['dui_acta']);
            $sql2->execute();
            $data=$sql2->fetchAll(PDO::FETCH_ASSOC);
            $id_acta = $data[0]["id_acta"];
            $correlativo_sucursal = $data[0]["correlativo_sucursal"];

            $hoy_en = date("Y-m-d");
            
            $sql3 = "insert into hoja_atencion values(null,?,?,?,?);";
            $sql3 = $conectar->prepare($sql3);
            $sql3->bindValue(1, $_POST['dui_acta']);
            $sql3->bindValue(2, $_SESSION["sucursal"]);
            $sql3->bindValue(3, $id_usuario);
            $sql3->bindValue(4, $hoy_en);
            $sql3->execute();

            $sql4 = "update orden_lab set estado='6' where codigo=? and dui=?;";
            $sql4 = $conectar->prepare($sql4);
            $sql4->bindValue(1, $codigo_orden);
            $sql4->bindValue(2, $_POST["dui_acta"]);
            $sql4->execute();
           
            $accion = "Impresion de Acta";
            $sql7 = "insert into acciones_orden values(null,?,?,?,?,?,?);";
            $sql7 = $conectar->prepare($sql7);
            $sql7->bindValue(1, $hoy_en);
            $sql7->bindValue(2, $_SESSION["user"]);
            $sql7->bindValue(3, $codigo_orden);
            $sql7->bindValue(4, $accion);
            $sql7->bindValue(5, $accion." -".$sucursal);
            $sql7->bindValue(6, $sucursal);
            $sql7->execute();


            $msj = array("id"=>$id_acta,"correlativo_sucursal"=>$correlativo_sucursal);
            echo json_encode($msj);

                
    }
     //Function multi-uso
  public function get_post_data($sql,$array_cond = [],$param = ""){
    $conectar = parent::conexion();
    parent::set_names();
    date_default_timezone_set('America/El_Salvador'); 
    $day = date("Y-m-d");
    $hora = date("H:i:s");
    $smtp = $conectar->prepare($sql);
    //PARAMS PARA I => INSERTED, U => UPDATE AND D => DELETE
    if($param == "I" or $param == "U" or $param == "D"){
      if($smtp->execute($array_cond)){
        return $conectar->lastInsertId();
      }
      return false;
      
    }else{
      $smtp->execute($array_cond);
      $result = $smtp->fetchAll(PDO::FETCH_OBJ); // Return $data->paciente
      return $result;
    }
  }
  /**
   * @param int id_acta
   * @param string sucursal
   * @return array table acta
   */
  public function get_acta_find_id($id_acta){
    $conectar = parent::conexion();
    parent::set_names();
    //Array data 
    $sendData = [];

    $sql = "select * from actas where id_acta=?";
    $stmt = $conectar->prepare($sql);
    $stmt->execute([$id_acta]);
    $result_acta = $stmt->fetchAll(PDO::FETCH_OBJ);
    if(count($result_acta) > 0){
        $codigo_orden = $result_acta[0]->codigo_orden;
        $id_acta = $result_acta[0]->id_acta;
        $fecha = $result_acta[0]->fecha_impresion;
        $paciente = $result_acta[0]->beneficiario;
        $dui_acta = $result_acta[0]->dui_acta;
        $sucursal = $result_acta[0]->sucursal;
        //Busqueda en orden lab si id_cita == 0
        //institucion
        //id_cita
        $sqlOrdenLab = "select institucion,id_cita from orden_lab where codigo=?";
        $stmt = $conectar->prepare($sqlOrdenLab);
        $stmt->execute([$codigo_orden]);
        $result_ordenLab = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        //Busqueda en orden o cita 
        if(count($result_ordenLab) > 0){
            if((int)$result_ordenLab[0]->id_cita == 0){
                $institu = $result_ordenLab[0]->institucion;
                $sendData = [
                    "id_acta" => $id_acta,
                    "fecha" => $fecha,
                    "paciente" => $paciente,
                    "dui" => $dui_acta,
                    "tipo_paciente" => $institu,
                    "sector" => $institu,
                    "sucursal" => $sucursal
                ];
            }else{
                $id_cita = (int)$result_ordenLab[0]->id_cita;
                //SQL PARA TRAER DATOS DE cita
                $sqlCita = "select * from citas where id_cita=?";
                $stmt = $conectar->prepare($sqlCita);
                $stmt->execute([$id_cita]);
                $result_cita = $stmt->fetchAll(PDO::FETCH_OBJ);
                //sector
                $sector = $result_cita[0]->sector;
                //tipo_paciente
                $tipo_paciente = $result_cita[0]->tipo_paciente;
                //Validacion si esta vacio tipo_paciente => institucion
                /* if($tipo_paciente == ""){
                  $tipo_paciente = $result_ordenLab[0]->institucion;
                  //$sector = null;
                } */
                //Datos a enviar al frontend
                $sendData = [
                    "id_acta" => $id_acta,
                    "fecha" => $fecha,
                    "paciente" => $paciente,
                    "dui" => $dui_acta,
                    "tipo_paciente" => $tipo_paciente,
                    "sector" => $sector,
                    "sucursal" => $sucursal
                ];
            }
        }
    }
    return $sendData;
  }
  public function getControlActaComprobarExistencia($id_acta){
    $conectar = parent::conexion();
    parent::set_names();
    //para obtener el codigo
    $sql = "SELECT * FROM control_actas where id_acta=?";
    $sql = $conectar->prepare($sql);
    $sql->bindValue(1,$id_acta);
    $sql->execute();
    return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
  }
  /**
   * @param array data de las ordenes
   * @param int id usuario logueado
   */
  public function insert_actas_entregas($data,$id_usuario){
    $conectar = parent::conexion();
    parent::set_names();
    date_default_timezone_set('America/El_Salvador'); 
    $day = date("Y-m-d");
    $hora = date("H:i:s");
    //Var
    $cod_entrega = $this->get_code_entrega_actas('E');
    //Insertar receptor
    $this->insert_receptor_actas($cod_entrega,$_POST['fullNameEmisor'],$_POST['fullNameReceptor']);
    $status_insert = false; //Controla la insercion de los datos, para retornar el COD: Entrega
    //Insert data table control actas
    foreach($data as $row){
      $sql = "insert into control_actas values(null,:id_acta,:cod_entrega,:dui,:paciente,:tipo_paciente,:sector,:sucursal,:usuario_id,:fecha_entrega,:hora)";
      $stmt = $conectar->prepare($sql);
      $dataInsert = [
        ":id_acta" => $row['id_acta'],
        ":cod_entrega" => $cod_entrega,
        ":dui" => $row['dui'],
        ":paciente" => $row['paciente'],
        ":tipo_paciente" => $row['tipo_paciente'],
        ":sector" => $row['sector'],
        ":sucursal" => $row['sucursal'],
        ":usuario_id" => $id_usuario,
        ":fecha_entrega" => $day,
        ":hora" => $hora
      ];
      if($stmt->execute($dataInsert)){
        $status_insert = true;
        //Registrar la acción de entrega de actas
        $accion = "Entrega oficial de actas. No. Entrega: ".$cod_entrega;
        $obser = "Entrega oficial - ".$row['sucursal'];
        $sql7 = "insert into acciones_orden values(null,?,?,?,?,?,?);";
        $sql7 = $conectar->prepare($sql7);
        $sql7->bindValue(1, $day." ".$hora);
        $sql7->bindValue(2, $_SESSION["user"]);
        $sql7->bindValue(3, $row['dui']);
        $sql7->bindValue(4, $accion);
        $sql7->bindValue(5, $obser);
        $sql7->bindValue(6, $row['sucursal']);
        $sql7->execute();
      }else{
        $status_insert = false;
      }
    }
    //Enviar code de entrega para generar reporte
    if($status_insert){
      return [
        "message" => "exito",
        "cod_entrega" => $cod_entrega
      ];
    }else{
      return $status_insert;
    }
  }
  public function insert_receptor_actas($codigo_entrega,$fullNameEmisor,$fullNameReceptor){
    $conectar = parent::conexion();
    parent::set_names();
    $sql = "insert into receptores_actas values(null,:codigo_entrega,:fullname_emisor,:fullname_receptor)";

    $stmt = $conectar->prepare($sql);
    $data = [
      ":codigo_entrega" => $codigo_entrega,
      ":fullname_emisor" => $fullNameEmisor,
      ":fullname_receptor" => $fullNameReceptor
    ];
    $stmt->execute($data);
  }
  /**
   *@param string prefijo a generar
   *@return string prefijo generado 
   */
  public function get_code_entrega_actas($prefijo){
    $conectar = parent::conexion();
    parent::set_names();
    //para obtener el codigo
    $sql = "SELECT cod_entrega FROM control_actas ORDER BY id_control DESC LIMIT 1;";
    $sql = $conectar->prepare($sql);
    $sql->execute();
    $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
    //Codigo
    $codigo = '';
    if (count($resultado) > 0) {
        //RV-
        $codigo = $resultado[0]['cod_entrega'];
        $codigo = explode("-", $codigo);
        $numero_unico = $codigo[1];
        $numero_unico += 1;
        $codigo = $prefijo . "-" . $numero_unico;
    } else {
        $codigo = $prefijo . "-1";
    }
    return $codigo;
  }
  //Caso especial -> usar este metodo para registrar la accion
  public function registrarAccionOrden($accion,$obser,$codigo_dui,$sucursal= "-"){
    $conectar = parent::conexion();
    parent::set_names();
    date_default_timezone_set('America/El_Salvador'); 
    $day = date("Y-m-d");
    $hora = date("H:i:s");
    //Registrar la acción de entrega de actas
    $sql7 = "insert into acciones_orden values(null,?,?,?,?,?,?);";
    $sql7 = $conectar->prepare($sql7);
    $sql7->bindValue(1, $day." ".$hora);
    $sql7->bindValue(2, $_SESSION["user"]);
    $sql7->bindValue(3, $codigo_dui);
    $sql7->bindValue(4, $accion);
    $sql7->bindValue(5, $obser);
    $sql7->bindValue(6, $sucursal);
    $sql7->execute();
  }
  
 public function getActasResumen($parametro,$desde,$hasta){
	  $conectar = parent::conexion();
      parent::set_names();
      
      if($parametro=='0'){
        $sql = "select * from actas;";
        $sql=$conectar->prepare($sql);
        $sql->execute();
        $total_actas = $sql->fetchAll(PDO::FETCH_ASSOC);  
      }else{
        $sql = "SELECT * FROM actas WHERE STR_TO_DATE(fecha_impresion, '%d-%m-%Y') BETWEEN ? AND ?;";
        $sql=$conectar->prepare($sql);
        $sql->bindValue(1, $desde);
        $sql->bindValue(2, $hasta);
        $sql->execute();
        $total_actas = $sql->fetchAll(PDO::FETCH_ASSOC);  
      }


      $array_actas_c = array();
      $array_actas_sc = array();
      ///recorro los DUIs buscando si existe cita en orden_lab
      foreach ($total_actas as $v){
        $sql2 = "select * from orden_lab_bk where dui=?;";
        $sql2=$conectar->prepare($sql2);
        $sql2->bindValue(1, $v["dui_acta"]);
        $sql2->execute();        
        $resultado_cita = $sql2->fetchAll(PDO::FETCH_ASSOC);

        $id_cita = $resultado_cita[0]["id_cita"];
        $fecha_imp = $v["fecha_impresion"];
        $tipo_receptor = $v["tipo_receptor"];
        $receptor = $v["receptor"];
        $id_acta = $v['id_acta'];
        $tipo_lente = $resultado_cita[0]["tipo_lente"];
        $alto_indice = $resultado_cita[0]["alto_indice"];
        $fecha_orden = $resultado_cita[0]["fecha"];
        $color=$resultado_cita[0]["color"];
        $paciente = $v["beneficiario"];
        $telefono =  $resultado_cita[0]["telefono"];
        //$institucion = $resultado_cita[0]['institucion'];
        //echo $id_cita;
        if($id_cita != 0){
           $sql3='select*from citas where dui=?';
           $sql3=$conectar->prepare($sql3);
           $sql3->bindValue(1, $v["dui_acta"]);
           $sql3->execute();
           $data_citado = $sql3->fetchAll(PDO::FETCH_ASSOC);
           $t_paciente = isset($data_citado[0]["tipo_paciente"]) ? $data_citado[0]["tipo_paciente"]: '';
           $sector = isset($data_citado[0]["sector"]) ? $data_citado[0]["sector"] : '' ;
           if($t_paciente=="" and ($sector !='' and $sector !='Seleccionar...' and $sector !='0')){
            $tipo_paciente = $sector;
            if($tipo_paciente=='DESIGNADOS' or $tipo_paciente=='CONYUGE'){
              $dui_titular = isset($data_citado[0]["dui_titular"]) ? $data_citado[0]["dui_titular"] : 'Datos incompletos';
              $vet_titular = isset($data_citado[0]["vet_titular"]) ? $data_citado[0]["vet_titular"] : 'Datos incompletos';
            }else{
              $dui_titular = 'N/A';
              $vet_titular = 'N/A';
            }
           }elseif($t_paciente=="" and ($sector =='' or $sector =='Seleccionar...' or $sector =='0')){
            $tipo_paciente = "Datos incompletos";
            $dui_titular = "Datos incompletos";
            $vet_titular = "Datos incompletos";
           }elseif($t_paciente !=""){
            $tipo_paciente = $t_paciente;
            if($tipo_paciente=='Designado' or $tipo_paciente=='Conyuge'){
              $dui_titular = $data_citado[0]["dui_titular"];
              $vet_titular = $data_citado[0]["vet_titular"];
            }elseif($tipo_paciente=='0' or $tipo_paciente==''){
              $dui_titular = "Datos incompletos";
              $vet_titular = "Datos incompletos";
            }else{
              $dui_titular = "N/A";
              $vet_titular = "N/A";
            }
            
           }
           
           if($sector =='' or $sector =='Seleccionar...' or $sector =='0'){
              $sec = 'Datos incompletos';
           }else{
              $sec = $sector;
           }
           
           /***************VARAIABLES DE CITAS****************/
            // $paciente = $data_citado[0]['paciente'];
            /*******************************/
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
           
           $sub_array = ['id_acta'=>$id_acta,'receptor'=>$receptor,'tipo_receptor'=>$tipo_receptor,'fecha_impresion'=>$fecha_imp,'paciente'=>$paciente,'tipo_paciente'=>$tipo_paciente,'sector'=>$sec,'tipo_lente'=>$tipo_lente,'alto_indice'=>$alto_indice,'color'=>$color,'fecha_orden'=>$fecha_orden,'sucursal'=>$v["sucursal"],'cita'=>'Si','dui'=>$v["dui_acta"],'dui_titular'=>$dui_titular,'vet_titular'=>$vet_titular,'telefono'=>$telefono,'precio'=>$precio];
           array_push($array_actas_c,$sub_array);
        }elseif($id_cita==0){
           // echo 'GH<br>';
            $sql4 ='select*from orden_lab where dui=?';
            $sql4=$conectar->prepare($sql4);
            $sql4->bindValue(1, $v["dui_acta"]);
            $sql4->execute();
            $data_paciente = $sql4->fetchAll(PDO::FETCH_ASSOC);
            $paciente = $data_paciente[0]['paciente'];
            $instit = $data_paciente[0]['institucion'];

            if($instit=='' or $instit=='Seleccionar...' or $instit=='0'){
              $tipo_paciente = 'Datos incompletos';
            }else{
              $tipo_paciente = $data_paciente[0]['institucion'];
              if($tipo_paciente=='DESIGNADOS' or $tipo_paciente=='CONYUGE'){
                $dui_titular = "Datos incompletos";
                $vet_titular = "Datos incompletos";
              }else{
                $dui_titular = "N/A";
                $vet_titular = "N/A";
              }
              $sec = $data_paciente[0]['institucion'];
            }
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
            $sub_array = ['id_acta'=>$id_acta,'receptor'=>$receptor,'tipo_receptor'=>$tipo_receptor,'fecha_impresion'=>$fecha_imp,'paciente'=>$paciente,'tipo_paciente'=>$tipo_paciente,'sector'=>$sec,'tipo_lente'=>$tipo_lente,'color'=>$color,'alto_indice'=>$alto_indice,'fecha_orden'=>$fecha_orden,'sucursal'=>$v["sucursal"],'cita'=>'No','dui'=>$v["dui_acta"],'dui_titular'=>$dui_titular,'vet_titular'=>$vet_titular,'telefono'=>$telefono,'precio'=>$precio];
            array_push($array_actas_sc,$sub_array);
        }
       
      }
    $result = array_merge($array_actas_c,$array_actas_sc);
    return $result;
}
/**
   * METHOD PARA OBTENER DATOS DE CITAS PARA EDITAR ACTA
   */
  public function getCitasFindByDUI($duiPaciente){
    $conectar = parent::conexion();
    parent::set_names();
    date_default_timezone_set('America/El_Salvador'); 
    $sql7 = "select id_cita,sector,tipo_paciente,vet_titular,dui_titular from citas where dui=?";
    $sql7 = $conectar->prepare($sql7);
    $sql7->bindValue(1, $duiPaciente);
    $sql7->execute();
    $result = $sql7->fetchAll(PDO::FETCH_ASSOC);
    if(count($result) > 0){
      return $result[0];
    }
    //Valores vacios si no hay datos
    return [
      "tipo_paciente" => "",
      "dui_titular" => "",
      "vet_titular" => "",
      "id_cita" => "",
      "sector" => ""
    ];
  }
  /**
   * method para obtener datos de orden_lab para editar acta
   */
  public function getOrdenFindByDUI($duiPaciente){
    $conectar = parent::conexion();
    parent::set_names();
    date_default_timezone_set('America/El_Salvador'); 
    $sql7 = "select codigo,institucion from orden_lab where dui=?";
    $sql7 = $conectar->prepare($sql7);
    $sql7->bindValue(1, $duiPaciente);
    $sql7->execute();
    $result = $sql7->fetchAll(PDO::FETCH_ASSOC);
    if(count($result) > 0){
      return $result[0];
    }
    //Valores vacios si no hay datos
    return [
      "institucion" => "",
      "codigo" => ""
    ];
  }
  /**
   * METHOD PARA OBTENER titulares de orden lab
   */
  public function getTitularFindByDUI($codigoOrden){
    $conectar = parent::conexion();
    parent::set_names();
    date_default_timezone_set('America/El_Salvador'); 
    $sql7 = "select t.titular as vet_titular, t.dui_titular from titulares as t where codigo=?";
    $sql7 = $conectar->prepare($sql7);
    $sql7->bindValue(1, $codigoOrden);
    $sql7->execute();
    $result = $sql7->fetchAll(PDO::FETCH_ASSOC);
    if(count($result) > 0){
      return $result[0];
    }
    //Valores vacios si no hay datos
    return [
      "vet_titular" => "",
      "dui_titular" => ""
    ];
  }
  
}

