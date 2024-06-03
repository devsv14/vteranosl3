<?php

require_once("../config/conexion.php");

class Traslados extends Conectar
{

    public function getPacientesTrasladar()
    {
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

    public function getOrdenFilterRxRango()
    {
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
        if (is_array($od_array_add) && is_array($oi_array_add)) {

            $sql = "select ord.id_orden,ord.codigo,ord.paciente,ord.dui,ord.tipo_lente,ord.fecha,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,ord.telefono,trasl.int_llamadas from orden_lab_lc1 as ord inner join traslado_l1_l2 as trasl on ord.dui=trasl.dui inner join rx_orden_lab_l1 as rx on rx.codigo=ord.codigo where rx.od_esferas in (" . rtrim(str_repeat('?,', count($od_array_esfera)), ',') . ") and rx.od_cilindros in (" . rtrim(str_repeat('?,', count($od_array_cilindro)), ',') . ") and rx.od_adicion in (" . rtrim(str_repeat('?,', count($od_array_add)), ',') . ") and rx.oi_esferas in (" . rtrim(str_repeat('?,', count($oi_array_esfera)), ',') . ") and rx.oi_cilindros in (" . rtrim(str_repeat('?,', count($oi_array_cilindro)), ',') . ") and rx.oi_adicion in (" . rtrim(str_repeat('?,', count($oi_array_add)), ',') . ") and trasl.estado_cita='0' order by ord.id_orden DESC";
            $sql = $conectar->prepare($sql);
            $values = array_merge($od_array_esfera, $od_array_cilindro, $od_array_add, $oi_array_esfera, $oi_array_cilindro, $oi_array_add);
            $sql->execute($values);
        } else {
            $sql = "select ord.id_orden,ord.codigo,ord.paciente,ord.dui,ord.tipo_lente,ord.fecha,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,ord.telefono,trasl.int_llamadas from orden_lab_lc1 as ord inner join traslado_l1_l2 as trasl on ord.dui=trasl.dui inner join rx_orden_lab_l1 as rx on rx.codigo=ord.codigo where rx.od_esferas in (" . rtrim(str_repeat('?,', count($od_array_esfera)), ',') . ") and rx.od_cilindros in (" . rtrim(str_repeat('?,', count($od_array_cilindro)), ',') . ") and rx.oi_esferas in (" . rtrim(str_repeat('?,', count($oi_array_esfera)), ',') . ") and rx.oi_cilindros in (" . rtrim(str_repeat('?,', count($oi_array_cilindro)), ',') . ") and trasl.estado_cita='0' order by ord.id_orden DESC";
            $sql = $conectar->prepare($sql);
            $values = array_merge($od_array_esfera, $od_array_cilindro, $oi_array_esfera, $oi_array_cilindro);
            $sql->execute($values);
        }

        $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
        return count($resultado) > 0 ? $resultado : [];
    }
    //Get tipo_lente
    public function getOrdenTipoLente()
    {
        $conectar = parent::conexion();
        parent::set_names();
        $condicion = !empty($_POST['tipo_lente']) ? 'ord.tipo_lente=? and' : '';
        $sql = "select ord.id_orden,ord.codigo,ord.paciente,ord.dui,ord.tipo_lente,ord.fecha,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,ord.telefono,trasl.int_llamadas from orden_lab_lc1 as ord inner join traslado_l1_l2 as trasl on ord.dui=trasl.dui inner join rx_orden_lab_l1 as rx on rx.codigo=ord.codigo where $condicion trasl.estado_cita='0' order by ord.id_orden DESC";
        $sql = $conectar->prepare($sql);
        $values = !empty($_POST['tipo_lente']) ? [$_POST['tipo_lente']] : [];
        $sql->execute($values);
        $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
        return count($resultado) > 0 ? $resultado : [];
    }
    //Function para devolver array de numeros
    function getArrayRxValidation($graduacion)
    {
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
    function rxDataImprimir($dui)
    {
        $conectarl = parent::conexion();
        $sql = 'select*from rx_orden_lab_l1 as rx inner join orden_lab_lc1 as o on rx.codigo=o.codigo where o.dui=?;';
        $sql = $conectarl->prepare($sql);
        $sql->bindValue(1, $dui);
        $sql->execute();
        return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    function horaAleatoria()
    {
        $horaInicio = strtotime('08:00 AM');
        $horaFin = strtotime('12:00 PM');
        $horaAleatoria = mt_rand($horaInicio, $horaFin);
        $minutos = (int)date('i', $horaAleatoria);
        $minutosMultiplo10 = round($minutos / 10) * 10;
        $horaMultiplo10 = date('h:', $horaAleatoria) . str_pad($minutosMultiplo10, 2, '0', STR_PAD_LEFT);
        return $horaMultiplo10;
    }

    function fechaCitaAgenda($fecha)
    {
        $fecha = new DateTime($fecha);
        $fechaAtras = $fecha->modify('-8 days');
        return $fechaAtras;
    }

    function  getCorrelativoOrdenes($sucursal)
    {
        $conectar = parent::conexion();
        $fecha = date("d-m-Y");

        $fecha_act = $fecha . '%';
        $sql = "select codigo from orden_lab where fecha_correlativo like ? order by id_orden DESC limit 1;";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $fecha_act);
        $sql->execute();
        $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

        $sucursales_array = [
            "Valencia" => "VLC", "Metrocentro" => "MCT", "Cascadas" => "CCD", "Santa Ana" => "SAA", "Chalatenango" => "CTG", "Ahuachapan" => "ACP", "Sonsonate" => "SST", "Ciudad Arce" => "CAC", "Opico" => "OPC", "Apopa" => "APP", "San Vicente Centro" => "SVC", "San Vicente" => "SVT", "Gotera" => "GTR", "San Miguel" => "SMG", "Usulutan" => "UST", "inabve" => "INB", "San Miguel AV PLUS" => "ASM"
        ];

        $now = date("dmY");
        // echo 'COD '.substr($resultado[0]["codigo"],11, 19)."*now->". $now; exit();
        $prefijo = $sucursales_array[$sucursal];
        if (count($resultado) > 0) {
            return $prefijo . $now . (intval(substr($resultado[0]["codigo"], 11, 19)) + 1);
        } else {
            return $prefijo . $now . '1';
        }
    }

    function crearOrdenTraslado($dui, $clasificacion)
    {
        $conectar = parent::conexion();
        parent::set_names();
        date_default_timezone_set('America/El_Salvador');
        $hoy = date("Y-m-d");
        $hora = date(" H:i:s");
        $arrayData = array();
        $arrayData = json_decode($_POST['dataSIVET']);

        $sql = 'select*from citas where dui=? and estado="0"';
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $dui);
        $sql->execute();
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        $est_cita = 0;
        if (is_array($data) and count($data) > 0) {
            $upd = 'update citas set estado="1",telefono=? where dui=?;';
            $upd = $conectar->prepare($upd);
            $upd->bindValue(1, $_POST["tel_upd"]);
            $upd->bindValue(2, $dui);
            $upd->execute();
            if ($sql->rowCount() > 0) {
                $est_cita = 2;
            }
        } else {
            $sql2 = "insert into citas values (null,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);";
            $sql2 = $conectar->prepare($sql2);
            $sql2->bindValue(1, $arrayData[0]->nombres . " " . $arrayData[0]->apellidos);
            $sql2->bindValue(2, $arrayData[0]->dui);
            $sql2->bindValue(3, $hoy);
            $sql2->bindValue(4, $_POST["sucursal"]);
            $sql2->bindValue(5, '#116530');
            $sql2->bindValue(6, '1');
            $sql2->bindValue(7, $_POST["tel_upd"]);
            $sql2->bindValue(8, $arrayData[0]->edad);
            $sql2->bindValue(9, '');
            $sql2->bindValue(10, ($arrayData[0]->genero) == 'F' ? 'Femenino' : 'Masculino');
            $sql2->bindValue(11, '0');
            $sql2->bindValue(12, $arrayData[0]->sector);
            $sql2->bindValue(13, '-');
            $sql2->bindValue(14, '-');
            $sql2->bindValue(15, $this->horaAleatoria());
            $sql2->bindValue(16, $hoy);
            $sql2->bindValue(17, $this->horaAleatoria());
            $sql2->bindValue(18, $_SESSION['id_user']);
            $sql2->bindValue(19, "");
            $sql2->bindValue(20, "");
            $sql2->bindValue(21, '');
            $sql2->bindValue(22, ($arrayData[0]->sector) == 'FMLN' ? 'Ex-Combatiente' : 'Veterano');
            $sql2->bindValue(23, 'inabve');
            $sql2->bindValue(24, "l1");
            $sql2->execute();
            if ($sql2->rowCount() > 0) {
                $est_cita = 1;
            }
        }
        if ($est_cita == 1 or $est_cita == 2) {
            $sql3 = "insert into traslados_confirmados values(null,?,?,?,?,?,?,?);";
            $sql3 = $conectar->prepare($sql3);
            $sql3->bindValue(1, $dui);
            $sql3->bindValue(2, $hoy);
            $sql3->bindValue(3, $hora);
            $sql3->bindValue(4, $_SESSION["id_user"]);
            $sql3->bindValue(5, $clasificacion);
            $sql3->bindValue(6, $_POST["sucursal"]);
            $sql3->bindValue(7, '0');
            $sql3->execute();

            $sql4 = 'update traslado_l1_l2 set estado_cita="1" where dui=?';
            $sql4 = $conectar->prepare($sql4);
            $sql4->bindValue(1, $dui);
            $sql4->execute();
        }

        if ($est_cita = 1) {
            echo json_encode(['msj' => 'insert']);
        } elseif ($est_cita = 2) {
            echo json_encode(['msj' => 'update']);
        } else {
            echo json_encode(['msj' => 'error']);
        }
    }

    public function countIntentos($dui)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "select *from intento_llamadas where dui=?;";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $dui);
        $sql->execute();
        return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registraIntentoLLamadaL1($dui, $clasificacion)
    {
        $conectar = parent::conexion();
        parent::set_names();
        date_default_timezone_set('America/El_Salvador');
        $hoy = date("Y-m-d");
        $hora = date(" H:i:s");

        $sql = "insert into intento_llamadas values(null,?,?,?,?,?);";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $dui);
        $sql->bindValue(2, $_SESSION["id_user"]);
        $sql->bindValue(3, $hoy);
        $sql->bindValue(4, $hora);
        $sql->bindValue(5, $clasificacion);
        $sql->execute();

        $sql2 = "select count(dui) as contador from intento_llamadas where dui=?;";
        $sql2 = $conectar->prepare($sql2);
        $sql2->bindValue(1, $dui);
        $sql2->execute();
        $contador = $sql2->fetchAll(PDO::FETCH_ASSOC);

        $sql3 = "update traslado_l1_l2 set int_llamadas=? where dui=?;";
        $sql3 = $conectar->prepare($sql3);
        $sql3->bindValue(1, $contador[0]["contador"]);
        $sql3->bindValue(2, $dui);
        $sql3->execute();

        echo json_encode(['msj' => 'nocontesta']);
    }

    public function cancelaCita($dui, $clasificacion)
    {
        $conectar = parent::conexion();
        parent::set_names();
        date_default_timezone_set('America/El_Salvador');
        $hoy = date("Y-m-d");
        $hora = date(" H:i:s");

        $sql = "insert into intento_llamadas values(null,?,?,?,?,?);";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $dui);
        $sql->bindValue(2, $_SESSION["id_user"]);
        $sql->bindValue(3, $hoy);
        $sql->bindValue(4, $hora);
        $sql->bindValue(5, $clasificacion);
        $sql->execute();

        $sql4 = 'update traslado_l1_l2 set estado_cita="1" where dui=?';
        $sql4 = $conectar->prepare($sql4);
        $sql4->bindValue(1, $dui);
        $sql4->execute();

        echo json_encode(['msj' => 'cancela']);
    }


    public function getOrdenPrintL1()
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "select ord.id_orden,ord.codigo,ord.paciente,ord.dui,ord.tipo_lente,ord.fecha,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,ord.telefono from orden_lab as ord inner join rx_orden_lab as rx on rx.codigo=ord.codigo;";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    function generarFechaAleatoria($fechaInicio, $fechaFin, $fechasExcluidas)
    {
        $fechaAleatoria = mt_rand($fechaInicio, $fechaFin);
        $fechan = date('Y-m-d', $fechaAleatoria);

        if (in_array($fechan, $fechasExcluidas)) {
            return generarFechaAleatoria($fechaInicio, $fechaFin, $fechasExcluidas);
        }

        return $fechan;
    }


    public function updatePrintOrdenL1($fechan)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $hora = date(" H:i:s");
        $hoy = date("Y-m-d");
        $arrayOrd = array();
        $arrayOrd = $_POST['duiPrint'];
        $ord_traslados = array();

        foreach ($arrayOrd as $valor) {
            $ord = 'SELECT * FROM orden_lab_lc1 INNER JOIN rx_orden_lab_l1 ON orden_lab_lc1.codigo = rx_orden_lab_l1.codigo where orden_lab_lc1.dui like ?;';
            $ord = $conectar->prepare($ord);
            $ord->bindValue(1, '%' . $valor . '%');
            $ord->execute();
            $dataord = $ord->fetchAll(PDO::FETCH_ASSOC);
            $sucursales = ['Metrocentro', 'San Miguel AV PLUS', 'Cascadas', 'Santa Ana', 'Chalatenango', 'Ahuachapan', 'Ciudad Arce', 'Apopa', 'San Vicente Centro', 'San Vicente', 'Gotera'];
            $indiceAleatorio = array_rand($sucursales);
            $sucursalAleatoria = $sucursales[$indiceAleatorio];
            $correlativo_op = $this->getCorrelativoOrdenes($sucursalAleatoria);
            $f_correlativo = date("d-m-Y H:i:s");

            //$fechan = $this->generarFechaAleatoria($fechaInicio, $fechaFin, $fechasExcluidas);

            $sql = "insert into orden_lab values (null,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);";
            $sql = $conectar->prepare($sql);
            $sql->bindValue(1, $correlativo_op);
            $sql->bindValue(2, $dataord[0]["paciente"]);
            $sql->bindValue(3, $fechan);
            $sql->bindValue(4, $dataord[0]["pupilar_od"]);
            $sql->bindValue(5, $dataord[0]["pupilar_oi"]);
            $sql->bindValue(6, $dataord[0]["lente_od"]);
            $sql->bindValue(7, $dataord[0]["lente_oi"]);
            $sql->bindValue(8, 0);
            $sql->bindValue(9, $_SESSION["id_user"]);
            $sql->bindValue(10, '-');
            $sql->bindValue(11, $valor);
            $sql->bindValue(12, 'l1');
            $sql->bindValue(13, $f_correlativo);
            $sql->bindValue(14, $_POST["tipo_lente"]);
            $sql->bindValue(15, '-');
            $sql->bindValue(16, 'SC');
            $sql->bindValue(17, ' ');
            $sql->bindValue(18, '-');
            $sql->bindValue(19, '');
            $sql->bindValue(20, $dataord[0]["avsc"]);
            $sql->bindValue(21, $dataord[0]["avfinal"]);
            $sql->bindValue(22, $dataord[0]["avsc_oi"]);
            $sql->bindValue(23, $dataord[0]["avfinal_oi"]);
            $sql->bindValue(24, $dataord[0]["telefono"]);
            $sql->bindValue(25, $dataord[0]["genero"]);
            $sql->bindValue(26, $dataord[0]["depto"]);
            $sql->bindValue(27, $dataord[0]["municipio"]);
            $sql->bindValue(28, "");
            $sql->bindValue(29, "");
            $sql->bindValue(30, $_POST["color"]);
            $sql->bindValue(31, $_POST["aindex"]);
            $sql->bindValue(32, 'No');
            $sql->bindValue(33, '0');
            $sql->bindValue(34, $sucursalAleatoria);

            if ($sql->execute()) {

                $sql2 = "insert into rx_orden_lab value(null,?,?,?,?,?,?,?,?,?);";
                $sql2 = $conectar->prepare($sql2);
                $sql2->bindValue(1, $correlativo_op);
                $sql2->bindValue(2, $dataord[0]["od_esferas"]);
                $sql2->bindValue(3, $dataord[0]["od_cilindros"]);
                $sql2->bindValue(4, $dataord[0]["od_eje"]);
                $sql2->bindValue(5, $dataord[0]["od_adicion"]);
                $sql2->bindValue(6, $dataord[0]["oi_esferas"]);
                $sql2->bindValue(7, $dataord[0]["oi_cilindros"]);
                $sql2->bindValue(8, $dataord[0]["oi_eje"]);
                $sql2->bindValue(9, $dataord[0]["oi_adicion"]);
                $sql2->execute();

                $ord_traslados[] = $valor;
            }

            $sql3 = 'update traslado_l1_l2 set estado_cita="1" where dui=?';
            $sql3 = $conectar->prepare($sql3);
            $sql3->bindValue(1, $valor);
            $sql3->execute();
        }
        echo json_encode(['msj' => 'ok', 'duiPrinter' => $ord_traslados]);
    }
    //Get paciente by DUI
    public function getPacienteById($dui)
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT codigo,paciente,dui,sucursal,fecha FROM `orden_lab` where dui=?";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $dui);
        $sql->execute();
        return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    public function fechaAleatoria($fechaInicio, $fechaFin){
			$timestampInicio = strtotime($fechaInicio);
			$timestampFin = strtotime($fechaFin);
			$timestampAleatorio = rand($timestampInicio, $timestampFin);
			$fechaAleatoria = date('Y-m-d', $timestampAleatorio);
			return $fechaAleatoria;
	}
    //Actualizar fecha de paciente orden
    public function updateOrdenFecha()
    {
        $conectar = parent::conexion();
        parent::set_names();
        $hora = date(" H:i:s");
        $hoy = date("Y-m-d");
        $arrayOrd = array();
        $arrayOrd = $_POST['data'];
        $ord_traslados = array();
        $correlativo = $this->correlativoUpd();
        $fechaInicio = '2022-11-15';
		$fechaFin = '2023-02-24';
        foreach ($arrayOrd as $row) {
                $fechaGenerada = $this->fechaAleatoria($fechaInicio, $fechaFin);
        		if ($fechaGenerada == '2022-12-25' || $fechaGenerada == '2023-01-01') {
        			$fechaGenerada = date('Y-m-d', strtotime($fechaGenerada . ' +2 days'));
        		}
                $sql3 = 'update orden_lab set fecha=? where dui=?';
                $sql3 = $conectar->prepare($sql3);
                $sql3->bindValue(1,$fechaGenerada);
                $sql3->bindValue(2,$row['dui']);
                if($sql3->execute()){
                    $ord_traslados[] = $row['dui'];
                }
        }
        echo json_encode(['status' => 'ok', 'duiPrinter' => $ord_traslados]);
    }
    /*function fechaAleatoria($fechaInicio, $fechaFin)
		{
			$timestampInicio = strtotime($fechaInicio);
			$timestampFin = strtotime($fechaFin);
			$timestampAleatorio = rand($timestampInicio, $timestampFin);
			$fechaAleatoria = date('Y-m-d', $timestampAleatorio);
			return $fechaAleatoria;
		} */
    //Update
    public function correlativoUpd()
    {
        $conectar = parent::conexion();
        parent::set_names();
        //Insertar en upd_expedientes
        $sql = "SELECT * FROM upd_expedientes order by id_upd_exp desc limit 1";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        $correlativo = $sql->fetchAll(PDO::FETCH_ASSOC);
        if (count($correlativo) > 0) {
                $codigo = $correlativo[0]["correlativo"];
                $cod = explode('-',$codigo);
                $number = $cod[1];
                $output = (int)$number + 1;
            return "UP-".$output;
        } 
        return $output = "UP-1";

    }
    //Sirve para datatable
    public function datatableUp(){
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT o.codigo,up.correlativo,o.paciente,o.fecha,o.sucursal,up.fecha_ant,up.fecha_act FROM `orden_lab` as o inner join upd_expedientes as up on o.dui=up.dui order by up.id_upd_exp desc";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
}

/* $trs = new Traslados();
$data = $trs->getOrdenPrintL1();
echo count($data); */
/*var_dump($data); */