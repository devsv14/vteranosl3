<?php

require_once("../config/conexion.php");

class ordenesLenti extends Conectar
{

    //////////////////  GET CODIGO DE ORDEN ////////////////////////
    public function get_correlativo_orden($fecha)
    {
        $conectar = parent::conexion_lenti();
        $fecha_act = "%" . $fecha . '%';
        $sql = "select codigo from orden where fecha_creacion like ? order by id_orden DESC limit 1;";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $fecha_act);
        $sql->execute();
        return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    /////////////////  COMPROBAR SI EXISTE CORRELATIVO ///////////////
    public function comprobar_existe_correlativo($codigo)
    {
        $conectar = parent::conexion_lenti();
        $sql = "select id_orden from orden where codigo=?;";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $codigo);
        $sql->execute();
        return $resultado = $sql->fetchAll();
    }
    //en uso para registrar datos
    public function trasladoOrdenesLenti($codigo, $paciente, $observaciones, $usuario, $tipo_lente, $od_esferas, $od_cilindros, $od_eje, $od_adicion, $oi_esferas, $oi_cilindros, $oi_eje, $oi_adicion, $pupilar_od, $pupilar_oi, $lente_od, $lente_oi, $categoria_lente,$dui)
    {
        $conectar = parent::conexion_lenti();

        $precio = 0;
        parent::set_names();
        if ($tipo_lente == "Visión Sencilla") {
            $tratamiento = "BLANCO";
            $marca = "VS/PROCESO";
            $precio = "-";
        }elseif ($tipo_lente == "Flaptop") {
            $tratamiento = "BLANCO";
            $marca = "BIFOCAL 1.56";
            $precio = "-";
        }elseif ($tipo_lente == "Progresive"){
            $tratamiento = "Blanco";
            $marca = "GEMINI";
            $precio = "-";
        }

        date_default_timezone_set('America/El_Salvador');
        $hoy = date("d-m-Y H:i:s");

        
        $codigoExiste = $this->comprobar_existe_correlativo($codigo);
        if (is_array($codigoExiste) && count($codigoExiste) == 0) {
            $sql2 = "insert into orden value(null,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);";
            $sql2 = $conectar->prepare($sql2);
            $sql2->bindValue(1, $dui);
            $sql2->bindValue(2, $paciente);
            $sql2->bindValue(3, $observaciones);
            $sql2->bindValue(4, $usuario);
            $sql2->bindValue(5, $hoy);
            $sql2->bindValue(6, 0);
            $sql2->bindValue(7, 41);
            $sql2->bindValue(8, $tipo_lente);
            $sql2->bindValue(9, 30);
            $sql2->bindValue(10, "INABVE");
            $sql2->bindValue(11, $tratamiento);
            $sql2->bindValue(12, "1");
            $sql2->bindValue(13, $marca);
            $sql2->bindValue(14, $categoria_lente);
            $sql2->bindValue(15, "No");
            $sql2->bindValue(16, $precio);
            if ($sql2->execute()) {
                $sql = "insert into rx_orden values(?,?,?,?,?,?,?,?,?,?,?,?);";
                $sql = $conectar->prepare($sql);
                $sql->bindValue(1, $dui);
                $sql->bindValue(2, $paciente);
                $sql->bindValue(3, $od_esferas);
                $sql->bindValue(4, $od_cilindros);
                $sql->bindValue(5, $od_eje);
                $sql->bindValue(6, $od_adicion);
                $sql->bindValue(7, "*");
                $sql->bindValue(8, $oi_esferas);
                $sql->bindValue(9, $oi_cilindros);
                $sql->bindValue(10, $oi_eje);
                $sql->bindValue(11, $oi_adicion);
                $sql->bindValue(12, "*");
                $sql->execute();
                                //***INSERT INTO ALTURAS ORDEN ///

                if ($tipo_lente == "Visión Sencilla" or $tipo_lente == "Progresive") {
                    $sql4 = "insert into alturas_orden values(?,?,?,?,?,?,?,?);";
                    $sql4 = $conectar->prepare($sql4);
                    $sql4->bindValue(1, $dui);
                    $sql4->bindValue(2, $paciente);
                    $sql4->bindValue(3, $pupilar_od);
                    $sql4->bindValue(4, $lente_od);
                    $sql4->bindValue(5, "-");
                    $sql4->bindValue(6, $pupilar_oi);
                    $sql4->bindValue(7, $lente_oi);
                    $sql4->bindValue(8, "-");
                    $sql4->execute();
                } elseif ($tipo_lente == "Flaptop") {
                    $sql4 = "insert into alturas_orden values(?,?,?,?,?,?,?,?);";
                    $sql4 = $conectar->prepare($sql4);
                    $sql4->bindValue(1, $dui);
                    $sql4->bindValue(2, $paciente);
                    $sql4->bindValue(3, $pupilar_od);
                    $sql4->bindValue(4, "-");
                    $sql4->bindValue(5, $lente_od);
                    $sql4->bindValue(6, $pupilar_oi);
                    $sql4->bindValue(7, "-");
                    $sql4->bindValue(8, $lente_oi);
                    $sql4->execute();
                }
            }
            
        }
    }

    public function get_orden()
    {
        $conectar = parent::conexion_lenti();
        //$sql="select id_orden from orden where codigo=?;";
        $sql = "select * from orden";
        $sql = $conectar->prepare($sql);
        //$sql->bindValue(1, $codigo);
        $sql->execute();
        return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
    }
}
