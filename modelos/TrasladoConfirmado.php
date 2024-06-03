<?php

require_once("../config/conexion.php");

class Traslados extends Conectar{

    public function getPacientesTrasladar(){
    $conectar = parent::conexion();
    parent::set_names();
    
    $sucursales = array(
        "Metrocentro" => 35,
        "Ahuachapan" => 25,
        "Apopa" => 25,
        "Santa Ana" => 25,
        "Ciudad Arce" => 25,
        "Chalatenango" => 25,
        "San Vicente" => 8,
        "San Vicente Centro" => 8,
        "Gotera" => 25,
        "San Miguel AV PLUS" => 25
    );
    foreach ($sucursales as $suc => $cupos) {
    $cont = $cupos;
    }

    
  
    }

    public function getOrdenFilterRxRango(){
        $conectar = parent::conexion();
        parent::set_names();
        //Rx OD
        $od_array_esfera = $this->getArrayRxValidation($_POST['od_esfera']);
        $od_array_cilindro = $this->getArrayRxValidation($_POST['od_cilindros']);
        $od_array_add = isset($_POST['od_adicion']) ? $this->getArrayRxValidation($_POST['od_adicion']) : '';
        //Rx OI
        $oi_array_esfera = $this->getArrayRxValidation($_POST['oi_esfera']);
        $oi_array_cilindro = $this->getArrayRxValidation($_POST['oi_cilindros']);
        $oi_array_add = isset($_POST['oi_adicion']) ? $this->getArrayRxValidation($_POST['oi_adicion']) : '';

        $values = [];
        //Valores RX OJO Derecho
        if(is_array($od_array_add) && is_array($oi_array_add)){

            $sql = "select ord.id_orden,ord.codigo,ord.paciente,ord.dui,ord.tipo_lente,ord.fecha,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,ord.telefono from orden_lab_lc1 as ord inner join traslados_confirmados as trasConf on ord.dui=trasConf.dui inner join rx_orden_lab_l1 as rx on rx.codigo=ord.codigo where rx.od_esferas in (" . rtrim(str_repeat('?,', count($od_array_esfera)), ',') . ") and rx.od_cilindros in (".rtrim(str_repeat('?,',count($od_array_cilindro)),',').") and rx.od_adicion in (" . rtrim(str_repeat('?,', count($od_array_add)), ',') . ") and rx.oi_esferas in (" . rtrim(str_repeat('?,', count($oi_array_esfera)), ',') . ") and rx.oi_cilindros in (" . rtrim(str_repeat('?,', count($oi_array_cilindro)), ',') . ") and rx.oi_adicion in (" . rtrim(str_repeat('?,', count($oi_array_add)), ',') . ") and trasConf.estado='0' order by ord.id_orden DESC";
            $sql = $conectar->prepare($sql);
            $values = array_merge($od_array_esfera,$od_array_cilindro,$od_array_add,$oi_array_esfera,$oi_array_cilindro,$oi_array_add);
            $sql->execute($values);
        }else{
            $sql = "select ord.id_orden,ord.codigo,ord.paciente,ord.dui,ord.tipo_lente,ord.fecha,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,ord.telefono from orden_lab_lc1 as ord inner join traslados_confirmados as trasConf on ord.dui=trasConf.dui inner join rx_orden_lab_l1 as rx on rx.codigo=ord.codigo where rx.od_esferas in (" . rtrim(str_repeat('?,', count($od_array_esfera)), ',') . ") and rx.od_cilindros in (".rtrim(str_repeat('?,',count($od_array_cilindro)),',').") and rx.oi_esferas in (" . rtrim(str_repeat('?,', count($oi_array_esfera)), ',') . ") and rx.oi_cilindros in (" . rtrim(str_repeat('?,', count($oi_array_cilindro)), ',') . ") and trasConf.estado='0' order by ord.id_orden DESC";
            $sql = $conectar->prepare($sql);
            $values = array_merge($od_array_esfera,$od_array_cilindro,$oi_array_esfera,$oi_array_cilindro);
            $sql->execute($values);
        }

        $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
        return count($resultado) > 0 ? $resultado : [];
    }
    //Get tipo_lente
    public function getOrdenTipoLente(){
        $conectar = parent::conexion();
        parent::set_names();
        $condicion = !empty($_POST['tipo_lente']) ? 'where ord.tipo_lente=? and trasConf.estado = "0"' : 'where trasConf.estado = "0"';
        $sql = "select ord.id_orden,ord.codigo,ord.paciente,ord.dui,ord.tipo_lente,ord.fecha,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,ord.telefono from orden_lab_lc1 as ord inner join traslados_confirmados as trasConf on ord.dui=trasConf.dui inner join rx_orden_lab_l1 as rx on rx.codigo=ord.codigo $condicion  order by ord.id_orden DESC";
        $sql = $conectar->prepare($sql);
        $values = !empty($_POST['tipo_lente']) ? [$_POST['tipo_lente']]: [];
        $sql->execute($values);
        $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
        return count($resultado) > 0 ? $resultado : [];
    }
    //Function para devolver array de numeros
    function getArrayRxValidation($graduacion) {
        // Verificar si la variable es numérica
        if (is_numeric($graduacion)) {
            $graduacion = (float)$graduacion;
            $valores = [];
            for ($i = 0; $i < 5; $i++) {
                $valor = $graduacion + (($i - 2) * 0.25);
                if ($valor == 0.00) {
                    array_push($valores, sprintf('%0.2f', $valor));
                } else {
                    array_push($valores, sprintf('%+0.2f', $valor));
                }
            }
            return $valores;
        } else {
            return $graduacion;
        }
    }
    /////CODIGO OSCAR
   function rxDataImprimir($dui){
        $conectarl = parent::conexion();
        $sql = 'select*from rx_orden_lab_l1 as rx inner join orden_lab_lc1 as o on rx.codigo=o.codigo where o.dui=?;';
        $sql=$conectarl->prepare($sql);
        $sql->bindValue(1, $dui); 
        $sql->execute();
        return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    function horaAleatoria(){
        $horaInicio = strtotime('08:00 AM');
        $horaFin = strtotime('12:00 PM');
        $horaAleatoria = mt_rand($horaInicio, $horaFin);
        $minutos = (int)date('i', $horaAleatoria);
        $minutosMultiplo10 = round($minutos / 10) * 10;
        $horaMultiplo10 = date('h:',$horaAleatoria) . str_pad($minutosMultiplo10, 2, '0', STR_PAD_LEFT);
        return $horaMultiplo10;

    }

    function fechaCitaAgenda($fecha){
     $fecha = new DateTime($fecha);
     $fechaAtras = $fecha->modify('-8 days'); return $fechaAtras;
    }

    function  getCorrelativoOrdenes($sucursal_act){
        $sucursal_act != '0' ? $sucursal = $sucursal_act : $sucursal='Metrocentro';
        $conectar = parent::conexion();
        $fecha =date("d-m-Y");
        $fecha_act = $fecha.'%';
      
        $sql= "select codigo from orden_lab where fecha_correlativo like ? order by id_orden DESC limit 1;";
        $sql=$conectar->prepare($sql);
        $sql->bindValue(1,$fecha_act);
        $sql->execute();
        $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);
        $sucursales_array = [
            "Valencia" => "VLC","Metrocentro" => "MCT","Cascadas" => "CCD","Santa Ana" => "SAA","Chalatenango" => "CTG","Ahuachapan" => "ACP","Sonsonate" => "SST","Ciudad Arce" => "CAC","Opico" => "OPC","Apopa" => "APP","San Vicente Centro" => "SVC","San Vicente" => "SVT","Gotera" => "GTR","San Miguel" => "SMG","Usulutan" => "UST","inabve" => "INB","San Miguel AV PLUS" => "ASM"
          ];
        $now = date("dmY");
       // echo 'COD '.substr($resultado[0]["codigo"],11, 19)."*now->". $now; exit();
        $prefijo = $sucursales_array[$sucursal];
        if(count($resultado)>0){
            return $prefijo . $now . (intval(substr($resultado[0]["codigo"], 11, 19)) + 1);
        }else{
            return $prefijo.$now.'1';
        }
    }

    function crearOrdenTraslado($dui,$clasificacion){
        $conectar = parent::conexion();
        parent::set_names();
        date_default_timezone_set('America/El_Salvador'); 
        $hoy = date("Y-m-d");
        $hora = date(" H:i:s");
        $arrayData = array();
        $arrayData = json_decode($_POST['dataSIVET']);

        $sql='select*from citas where dui=? and estado="0"';
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1,$dui);
        $sql->execute();
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        $est_cita = 0;
        if(is_array($data) and count($data)>0){            
            $upd = 'update citas set estado="1" where dui=?;';
            $upd = $conectar->prepare($upd);
            $upd->bindValue(1,$dui);
            $upd->execute();
            if($sql->rowCount() > 0 ){
                $est_cita = 2; 
            }
        }else{
            $sql2 = "insert into citas values (null,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);";
            $sql2 = $conectar->prepare($sql2);
            $sql2->bindValue(1, $arrayData[0]->nombres." ".$arrayData[0]->apellidos);
            $sql2->bindValue(2, $arrayData[0]->dui);
            $sql2->bindValue(3, $hoy);
            $sql2->bindValue(4, $_POST["sucursal"]);
            $sql2->bindValue(5, '#116530');
            $sql2->bindValue(6, '1');
            $sql2->bindValue(7, $arrayData[0]->telefono." ".$arrayData[0]->celular1);
            $sql2->bindValue(8, $arrayData[0]->edad);
            $sql2->bindValue(9, '');
            $sql2->bindValue(10, ($arrayData[0]->genero)=='F'?'Femenino' : 'Masculino');
            $sql2->bindValue(11, '0');
            $sql2->bindValue(12, $arrayData[0]->sector);
            $sql2->bindValue(13, '-');
            $sql2->bindValue(14, '-');
            $sql2->bindValue(15, $this->horaAleatoria());
            $sql2->bindValue(16, $hoy);
            $sql2->bindValue(17, $this->horaAleatoria());
            $sql2->bindValue(18,$_SESSION['id_user']);
            $sql2->bindValue(19, "");
            $sql2->bindValue(20, "");
            $sql2->bindValue(21, '');
            $sql2->bindValue(22, ($arrayData[0]->sector)=='FMLN'?'Ex-Combatiente' : 'Veterano');
            $sql2->bindValue(23, 'inabve');
            $sql2->bindValue(24,"l1");
            $sql2->execute();
            if($sql2->rowCount() > 0 ){
                $est_cita = 1; 
            }
            
        }
        if($est_cita==1 or $est_cita==2){
        $sql3="insert into traslados_confirmados values(null,?,?,?,?,?,?);";
        $sql3 = $conectar->prepare($sql3);
        $sql3->bindValue(1, $dui);
        $sql3->bindValue(2, $hoy);
        $sql3->bindValue(3, $hora);
        $sql3->bindValue(4, $_SESSION["id_user"]);
        $sql3->bindValue(5, $clasificacion);
        $sql3->bindValue(6, $_POST["sucursal"]);
        $sql3->execute();
        
        $sql4 = 'update traslados_confirmados set estado="1" where dui=?';
        $sql4 = $conectar->prepare($sql4);
        $sql4->bindValue(1,$dui);
        $sql4->execute();
        }

        if($est_cita=1){
            echo json_encode(['msj'=>'insert']);
        }elseif($est_cita=2){
            echo json_encode(['msj'=>'update']);
        }else{
            echo json_encode(['msj'=>'error']);
        }
        

    }
    //Codigo para traslado confirmados licitacion 1
    function crearOrdenL1L2(){
        
        $conectar = parent::conexion();
        parent::set_names();
        date_default_timezone_set('America/El_Salvador');
        $hoy = date("Y-m-d");
        $hora = date(" H:i:s");
        $f_correlativo = date("d-m-Y H:i:s");
        /////////// GET DATA CITA //////////////
        $cita = 'select*from citas where dui =?';
        $cita=$conectar->prepare($cita);
        $cita->bindValue(1, $_POST["dui_traslado"]);
        $cita->execute();
        $datac = $cita->fetchAll(PDO::FETCH_ASSOC);

        $ord = 'select*from orden_lab_lc1 where dui = ?';
        $ord=$conectar->prepare($ord);
        $ord->bindValue(1, $_POST["dui_traslado"]);
        $ord->execute();
        $dataord = $ord->fetchAll(PDO::FETCH_ASSOC);
        $correlativo_op = $this->getCorrelativoOrdenes($datac[0]['sucursal']); 
        /////////////////////////////////////////////////////////////////////
        $est_orden =  $_POST["tipo_orden"]=='ent-trasl' ? ' (ENT)' : '(LB)';
        $estado_o = $_POST["tipo_orden"]=='ent-trasl'? '5' : '1';
        $suc_act = $datac[0]["sucursal"] !="0" ? $datac[0]["sucursal"] : "Metrocentro";
    
        $sql = "insert into orden_lab values (null,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $correlativo_op);
        $sql->bindValue(2, $datac[0]["paciente"]);
        $sql->bindValue(3, $hoy);
        $sql->bindValue(4, $dataord[0]["pupilar_od"]);
        $sql->bindValue(5, $dataord[0]["pupilar_oi"]);
        $sql->bindValue(6, $dataord[0]["lente_od"]);
        $sql->bindValue(7, $dataord[0]["lente_oi"]);
        $sql->bindValue(8, 0);
        $sql->bindValue(9, $_SESSION["id_user"]);
        $sql->bindValue(10, $est_orden);
        $sql->bindValue(11, trim($_POST["dui_traslado"]));
        $sql->bindValue(12, $estado_o);
        $sql->bindValue(13, $f_correlativo);
        $sql->bindValue(14, $_POST["tipoLenteSeleccionado"]);
        $sql->bindValue(15, '-');
        $sql->bindValue(16, 'SC');
        $sql->bindValue(17, $datac[0]['edad']);
        $sql->bindValue(18, '-');
        $sql->bindValue(19, $datac[0]['ocupacion']);
        $sql->bindValue(20, $dataord[0]["avsc"]);
        $sql->bindValue(21, $dataord[0]["avfinal"]);
        $sql->bindValue(22, $dataord[0]["avsc_oi"]);
        $sql->bindValue(23, $dataord[0]["avfinal_oi"]);
        $sql->bindValue(24, $datac[0]["telefono"]);
        $sql->bindValue(25, $datac[0]["genero"]);
        $sql->bindValue(26, $datac[0]["depto"]);
        $sql->bindValue(27, $datac[0]["municipio"]);
        $sql->bindValue(28, $datac[0]["sector"]);
        $sql->bindValue(29, "");
        $sql->bindValue(30, "");
        $sql->bindValue(31, $_POST["altoIndice"]);
        $sql->bindValue(32, 'No');
        $sql->bindValue(33, $datac[0]["id_cita"]);
        $sql->bindValue(34, $suc_act);
        $sql->execute();
          
            //Insertamos la RX_orden lab
            $sql2 = "insert into rx_orden_lab value(null,?,?,?,?,?,?,?,?,?);";
            $sql2 = $conectar->prepare($sql2);
            $sql2->bindValue(1, $correlativo_op);
            $sql2->bindValue(2, $_POST["od_esferash"]);
            $sql2->bindValue(3, $_POST["od_cilindrosh"]);
            $sql2->bindValue(4, $_POST["od_ejesh"]);
            $sql2->bindValue(5, $_POST["od_addsh"]);
            $sql2->bindValue(6, $_POST["oi_esferash"]);
            $sql2->bindValue(7, $_POST["oi_cilindrosh"]);
            $sql2->bindValue(8, $_POST["oi_ejesh"]);
            $sql2->bindValue(9, $_POST["oi_addsh"]);
            $sql2->execute();

            $sql_aro = "insert into aros_manuales values(null,?,?,?,?,?);";
            $sql_aro = $conectar->prepare($sql_aro);
            $sql_aro->bindValue(1, $correlativo_op);
            $sql_aro->bindValue(2, $_POST['modelo_aro']);
            $sql_aro->bindValue(3, $_POST['marca_aro']);
            $sql_aro->bindValue(4, $_POST['color_aro']);
            $sql_aro->bindValue(5, '');
            $sql_aro->execute();

       
        $sql3 = 'update traslados_confirmados set estado="1" where dui=?';
        $sql3 = $conectar->prepare($sql3);
        $sql3->bindValue(1, trim($_POST["dui_traslado"]));
        $sql3->execute(); 


        if($sql->rowCount() > 0 and $sql2->rowCount() > 0 and $sql_aro->rowCount() > 0) {
            echo json_encode(['msj'=>'InsertOrder','dui'=> $_POST["dui_traslado"],'paciente'=>$datac[0]["paciente"]]);
        }else{
            echo json_encode(['msj'=>'ErrorInserted']);
        }

    }

}

/* $trs = new Traslados();
$trs->getPacientesTrasladar(); */