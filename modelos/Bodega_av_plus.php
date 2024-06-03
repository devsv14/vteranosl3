<?php
require_once("../config/conexion.php");

class Bodega extends Conectar
{

    public function get_ordenes_pendientes($cod_envio)
    {
        date_default_timezone_set('America/El_Salvador');
        $conectar = parent::conexion();
        $sql = "select o.codigo, rv.fecha, o.paciente, o.dui, rv.cod_envio, o.sucursal from detalle_reenvio_lab as rv inner join orden_lab as o on rv.dui=o.dui WHERE rv.estado=0 and rv.cod_envio=? order by rv.id_reenvio DESC";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $cod_envio);
        $sql->execute();
        return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    //Funcion para recibir las ordenes del laboratorio
    public function procesar_orden($cod_ingreso, $cod_orden, $dui)
    {
        date_default_timezone_set('America/El_Salvador');
        $day = date('Y-m-d');
        $hora = date('H:i:s');
        $estado = 0;
        $conectar = parent::conexion();
        $conectar->beginTransaction();
        //Update a detealle reenvio
        $sql_up = "update detalle_reenvio_lab set estado=1 where dui=?";
        $sql_up = $conectar->prepare($sql_up);
        $sql_up->bindValue(1, $dui);
        $sql_up->execute();
        //Registro de acciones bodega av plus
        $codigo_accion = $cod_ingreso;
        $tipo_accion = "Ingreso a bodega AV Plus";
        $values = [$codigo_accion, $dui, $tipo_accion, $_SESSION['id_user'], $day, $hora];
        $sql_bodega = "insert into acciones_bodega_avplus values(null,?,?,?,?,?,?)";
        $sql_bodega = $conectar->prepare($sql_bodega);
        $sql_bodega->execute($values);
        //Accion generales
        $fechaHora = $day . " " . $hora;
        $observaciones = "Recibido(Procesando)";
        $values_acc = [$fechaHora, $_SESSION['user'], $cod_orden, $tipo_accion, $observaciones, "-"];
        $sql = "insert into acciones_orden values(null,?,?,?,?,?,?);";
        $sql = $conectar->prepare($sql);
        $sql->execute($values_acc);
        $conectar->commit();
    }
    //Funcion que devuelve el correlativo de recibido 
    public function get_correlativo_bodega($string_initial)
    {
        $conectar = parent::conexion();
        parent::set_names();
        //para obtener el codigo
        $sql = "SELECT cod_bodega FROM acciones_bodega_avplus ORDER BY id_acc_bodega DESC LIMIT 1;";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
        //Codigo
        $codigo = '';
        if (count($resultado) > 0) {
            //RV-
            $codigo = $resultado[0]['cod_bodega'];
            $codigo = explode("-", $codigo);
            $numero_unico = $codigo[1];
            $numero_unico += 1;
            $codigo = $string_initial . "-" . $numero_unico;
        } else {
            $codigo = $string_initial . "-1";
        }
        return $codigo;
    }
    //Datos para el datatable de bodegas
    public function get_ordenes_bog()
    {
        date_default_timezone_set('America/El_Salvador');
        $conectar = parent::conexion();
        $sql = "select o.id_orden,o.codigo, rv.fecha, o.paciente, o.dui, rv.cod_envio,o.tipo_lente,o.institucion,o.sucursal,rv.estado, o.sucursal,o.estado as orden_estado from orden_lab as o inner join detalle_reenvio_lab as rv on o.codigo=rv.cod_orden WHERE rv.estado=0 AND o.estado='2-b' order by o.id_orden DESC";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    //GET DATOS PROCESANDO BODEGAS AV PLUS
    public function get_ordenes_procesando_lab($sucursal)
    {
        $conectar = parent::conexion();
        parent::set_names();
        if ($sucursal == "") {
            $sql = "select o.id_orden,o.codigo, det.fecha , o.paciente, o.dui,o.tipo_lente,o.institucion,o.sucursal,o.estado, o.sucursal from detalle_reenvio_lab as det inner join orden_lab as o on det.dui=o.dui WHERE o.estado=? and det.estado=1;";
            $sql = $conectar->prepare($sql);
            $sql->bindValue(1, '2-b');
            $sql->execute();
        } else {
            $sql = "select o.id_orden,o.codigo, det.fecha , o.paciente, o.dui,o.tipo_lente,o.institucion,o.sucursal,o.estado, o.sucursal from detalle_reenvio_lab as det inner join orden_lab as o on det.dui=o.dui WHERE o.estado=? and det.estado=1 and o.sucursal=?";
            $sql = $conectar->prepare($sql);
            $sql->bindValue(1, '2-b');
            $sql->bindValue(2, $sucursal);
            $sql->execute();
        }
        return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    public function get_procesando_lab($dui)
    {
        date_default_timezone_set('America/El_Salvador');
        $conectar = parent::conexion();
        $sql = "SELECT o.id_orden,o.codigo,o.paciente,o.dui,o.fecha,o.sucursal,det.cod_envio FROM `orden_lab` as o inner join detalle_reenvio_lab as det on o.dui=det.dui WHERE o.estado='2-b' and det.estado=1 and o.dui=?";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $dui);
        $sql->execute();
        return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    public function get_reenvio_lab($dui)
    {
        date_default_timezone_set('America/El_Salvador');
        $conectar = parent::conexion();
        $sql = "SELECT o.id_orden,o.codigo,o.paciente,o.dui,o.fecha,o.sucursal,det.cod_envio FROM `orden_lab` as o inner join detalle_reenvio_lab as det on o.dui=det.dui WHERE det.estado=1 and o.dui=?";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $dui);
        $sql->execute();
        return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    public function get_finalizadas($dui)
    {
        date_default_timezone_set('America/El_Salvador');
        $conectar = parent::conexion();
        $sql = "SELECT o.id_orden,o.codigo,o.paciente,o.dui,o.fecha,o.sucursal,det.cod_envio FROM `orden_lab` as o inner join detalle_reenvio_lab as det on o.dui=det.dui WHERE o.estado='3-b' and det.estado=1 and o.dui=?";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $dui);
        $sql->execute();
        return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    //Finalizar orden
    public function finalizarOrdenesLab($usuario)
    {
        $conectar = parent::conexion();
        parent::set_names();
        date_default_timezone_set('America/El_Salvador');
        $hoy = date("d-m-Y H:i:s");
        $fecha_creacion = date("Y-m-d");
        $hora = date('H:i:s');
        $detalle_finalizados = array();
        $detalle_finalizados = json_decode($_POST["arrayOrdenesBarcode"]);
        $cod_finalizado = $this->get_correlativo_bodega('FN');
        foreach ($detalle_finalizados as $k => $v) {

            $codigoOrden = $v->n_orden;
            $dui_paciente = $v->dui;
            $paciente = $v->paciente;
            $tipo_accion = "Finalizada orden Lab";
            $observaciones = "Bodega AV Plus";
            $destino = "-";

            $sql2 = "update orden_lab set estado='3-b' where codigo=?;";
            $sql2 = $conectar->prepare($sql2);
            $sql2->bindValue(1, $codigoOrden);
            $sql2->execute();

            //Insertado a acciones lab
            $values = [$cod_finalizado, $dui_paciente, $tipo_accion, $_SESSION['id_user'], $fecha_creacion, $hora];
            $sql_bodega = "insert into acciones_bodega_avplus values(null,?,?,?,?,?,?)";
            $sql_bodega = $conectar->prepare($sql_bodega);
            $sql_bodega->execute($values);

            $sql = "insert into acciones_orden values(null,?,?,?,?,?,?);";
            $sql = $conectar->prepare($sql);
            $sql->bindValue(1, $hoy);
            $sql->bindValue(2, $_SESSION['user']);
            $sql->bindValue(3, $codigoOrden);
            $sql->bindValue(4, $tipo_accion);
            $sql->bindValue(5, $observaciones);
            $sql->bindValue(6, $destino);
            $sql->execute();
        }
    }
    //Estado 4-B para enviar a optica
    public function finalizarOrdenesLabEnviar($usuario)
    {
        $conectar = parent::conexion();
        parent::set_names();
        date_default_timezone_set('America/El_Salvador');
        $hoy = date("d-m-Y H:i:s");
        $fecha_creacion = date("Y-m-d");
        $hora = date('H:i:s');
        $detalle_finalizados = array();
        $detalle_finalizados = json_decode($_POST["arrayOrdenesBarcode"]);
        
        //Generando el codigo de envio
        $cod_finalizado = $this->get_correlativo_bodega('L');
        $sucursal = '';
        foreach ($detalle_finalizados as $k => $v) {
            $sucursal = $v->sucursal; //Guardamos la sucursal
            $codigoOrden = $v->n_orden;
            $dui_paciente = $v->dui;
            $paciente = $v->paciente;
            $tipo_accion = "Enviada a Laboratorio";
            $observaciones = "Código de envio: " . $cod_finalizado;
            $destino = "-";

            $sql2 = "update orden_lab set estado='4-b' where codigo=?;";
            $sql2 = $conectar->prepare($sql2);
            $sql2->bindValue(1, $codigoOrden);
            $sql2->execute();
            //para obtener el codigo
            $values = [$cod_finalizado, $dui_paciente, $tipo_accion, $_SESSION['id_user'], $fecha_creacion, $hora];
            $sql_bodega = "insert into acciones_bodega_avplus values(null,?,?,?,?,?,?)";
            $sql_bodega = $conectar->prepare($sql_bodega);
            $sql_bodega->execute($values);

            //Insertado a acciones lab
            $acciones = "Envio de bodega AV PLus";

            $sql = "insert into acciones_orden values(null,?,?,?,?,?,?);";
            $sql = $conectar->prepare($sql);
            $sql->bindValue(1, $hoy);
            $sql->bindValue(2, $usuario);
            $sql->bindValue(3, $codigoOrden);
            $sql->bindValue(4, $tipo_accion);
            $sql->bindValue(5, $observaciones);
            $sql->bindValue(6, $destino);
            $sql->execute();   
        }
        $data[] = [
            "codigo_envio" => $cod_finalizado
        ];
        return $data;
    }
    //GET DATATABLE DATA
    public function get_ordenes_finalizadas_lab($sucursal)
    {
        $conectar = parent::conexion();
        parent::set_names();
        if ($sucursal == "") {
            $sql = "select o.id_orden,o.codigo, det.fecha , o.paciente, o.dui,o.tipo_lente,o.institucion,o.sucursal,o.estado, o.sucursal from detalle_reenvio_lab as det inner join orden_lab as o on det.dui=o.dui WHERE o.estado=? and det.estado=1 GROUP BY det.dui";
            $sql = $conectar->prepare($sql);
            $sql->bindValue(1, '3-b');
            $sql->execute();
        } else {
            $sql = "select o.id_orden,o.codigo, det.fecha , o.paciente, o.dui,o.tipo_lente,o.institucion,o.sucursal,o.estado, o.sucursal from detalle_reenvio_lab as det inner join orden_lab as o on det.dui=o.dui WHERE o.estado=? and det.estado=1 and o.sucursal=? GROUP BY det.dui";
            $sql = $conectar->prepare($sql);
            $sql->bindValue(1, '3-b');
            $sql->bindValue(2, $sucursal);
            $sql->execute();
        }
        return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    //GET DATATABLE DATA
    public function get_ordenes_enviadas_lab($sucursal)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "select o.id_orden,o.codigo, b.fecha , o.paciente, o.dui,o.tipo_lente,o.institucion,o.sucursal,o.estado, o.sucursal from acciones_bodega_avplus as b inner join orden_lab as o on b.dui=o.dui WHERE b.cod_bodega LIKE 'L-%'";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, 3);
        $sql->execute();
        return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    //GET DATA PARA MOSTRAR LA CANTIDA DE ENVIOS
    function listar_ordenes_enviadas()
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "select acc.id_acc_bodega,acc.cod_bodega, COUNT(acc.id_acc_bodega) as cantidad,acc.fecha,u.nombres as usuario,o.sucursal from acciones_bodega_avplus as acc inner join orden_lab as o on acc.dui=o.dui inner join usuarios as u on u.id_usuario=acc.id_usuario WHERE acc.tipo_accion='Enviada a Laboratorio' GROUP BY acc.cod_bodega";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    //Funcion para generar reporte y modal de vista previa en lab av plus
    public function get_ordenes_envio($cod_bodega)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT acc.id_acc_bodega,acc.cod_bodega,o.codigo,o.dui,o.paciente,acc.fecha,o.telefono,o.tipo_lente,o.sucursal FROM acciones_bodega_avplus as acc INNER JOIN orden_lab as o ON acc.dui=o.dui WHERE acc.cod_bodega=?";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $cod_bodega);
        $sql->execute();
        return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    //Genera el reporte por sucursal
    public function get_report_ordenes_enviadas($cod_despacho)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT acc.id_acc_bodega,acc.cod_bodega,o.codigo,o.dui,o.paciente,o.fecha,o.telefono,o.tipo_lente,o.sucursal FROM acciones_bodega_avplus as acc INNER JOIN orden_lab as o ON acc.dui=o.dui WHERE acc.cod_bodega=? order by trim(o.paciente) asc";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $cod_despacho);
        $sql->execute();
        return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    //Function para mostrar los datos en la tabla de reenvios
    public function get_ordenes_reenviadas()
    {
        $conectar = parent::conexion();
        parent::set_names();
        //$sql = "SELECT o.id_orden,rv.cod_envio,rv.fecha,rv.hora,o.paciente,o.dui,o.telefono,o.tipo_lente,rv.laboratorio FROM `detalle_reenvio_lab` as rv inner join orden_lab as o on rv.dui=o.dui;"
        $sql = "SELECT acc.id_acc_bodega,acc.cod_bodega,acc.fecha,acc.hora,COUNT(acc.cod_bodega) as cantidad, u.nombres as usuario FROM acciones_bodega_avplus as acc inner join orden_lab as o on acc.dui=o.dui inner join usuarios as u on u.id_usuario=acc.id_usuario where acc.tipo_accion='Reenvio a laboratorio' GROUP By acc.cod_bodega;";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    //Funcion para mostrar los datos renviadas
    public function get_ordenes_reenviadas_pdf($cod_reenvio)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT o.id_orden,acc.cod_bodega,acc.fecha,acc.hora,o.paciente,o.dui,o.telefono,o.tipo_lente FROM acciones_bodega_avplus as acc inner join orden_lab as o on acc.dui=o.dui where acc.cod_bodega=? order by trim(o.paciente) asc";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $cod_reenvio);
        $sql->execute();
        return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    public function update_reenvio_lab($usuario)
    {
        $conectar = parent::conexion();
        parent::set_names();
        date_default_timezone_set('America/El_Salvador');
        $hoy = date("d-m-Y H:i:s");
        $fecha_creacion = date("Y-m-d");
        $hora = date('H:i:s');
        $detalle_finalizados = array();
        $usuario = $_SESSION['user']; //Nombre del usuario identificado
        $detalle_finalizados = json_decode($_POST["arrayOrdenesBarcode"]);
        $cod_finalizado = $this->get_correlativo_bodega('LAB');
        foreach ($detalle_finalizados as $k => $v) {

            $codigoOrden = $v->n_orden;
            $dui_paciente = $v->dui;
            $paciente = $v->paciente;
            $tipo_accion = "Reenvio a laboratorio";
            $observaciones = "Reenvio desde Bodega AV Plus";
            $destino = "-";

            $sql2 = "update orden_lab set estado=1 where codigo=?;";
            $sql2 = $conectar->prepare($sql2);
            $sql2->bindValue(1, $codigoOrden);
            $sql2->execute();
            //Update para det_despacho lab
            $sql = "update det_despacho_lab set estado=0 where dui=?";
            $sql = $conectar->prepare($sql);
            $sql->bindValue(1, $dui_paciente);
            $sql->execute();
            //para obtener el codigo
            $values = [$cod_finalizado, $dui_paciente, $tipo_accion, $_SESSION['id_user'], $fecha_creacion, $hora];
            $sql_bodega = "insert into acciones_bodega_avplus values(null,?,?,?,?,?,?)";
            $sql_bodega = $conectar->prepare($sql_bodega);
            $sql_bodega->execute($values);

            //Insertado a acciones lab
            $acciones = "Reenvio a Laboratorio";

            $sql = "insert into acciones_orden values(null,?,?,?,?,?,?);";
            $sql = $conectar->prepare($sql);
            $sql->bindValue(1, $hoy);
            $sql->bindValue(2, $usuario);
            $sql->bindValue(3, $codigoOrden);
            $sql->bindValue(4, $tipo_accion);
            $sql->bindValue(5, $observaciones);
            $sql->bindValue(6, $destino);
            $sql->execute();
        }
    }
    //fUNCION PARA VER EL HISTORIAL
    public function get_data_orden($dui)
    {
        $conectar = parent::conexion();

        //Traer datos y relleno
        $sql = "select codigo,id_aro,institucion from orden_lab where dui=?";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $dui);
        $sql->execute();
        $result = $sql->fetchAll(PDO::FETCH_ASSOC);
        $institucion = $result[0]['institucion'];
        $codigo = $result[0]['codigo'];
        $id_aro = $result[0]['id_aro'];

        //Verificador para ver si tiene ingresado un aro en manuales
        $sql = "select id_aro from aros_manuales where codigo_orden=?";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $codigo);
        $sql->execute();
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);

        if ($institucion == "CONYUGE") {
            if ($id_aro == 0) {
                if (count($data) > 0) {
                    $sql = "select am.marca,am.modelo,am.color,am.material,titulares.id_titulares,titulares.titular,titulares.dui_titular,o.id_orden,o.id_cita,o.genero,o.sucursal,o.telefono,o.laboratorio,o.categoria,o.codigo,o.paciente,o.fecha,o.pupilar_od,o.pupilar_oi,o.lente_od,o.patologias,o.lente_oi,o.id_usuario,o.observaciones,o.dui,o.estado,o.tipo_lente,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,o.color as colorTratamiento,o.dui,o.edad,o.usuario_lente,o.ocupacion,o.avsc,o.avfinal,o.avsc_oi,o.avfinal_oi,o.depto,o.municipio,o.institucion from orden_lab as o inner join rx_orden_lab as rx on o.codigo=rx.codigo INNER JOIN titulares ON titulares.codigo=o.codigo INNER JOIN aros_manuales as am ON o.codigo=am.codigo_orden where o.codigo = ? and rx.codigo = ? ";
                } else {
                    $sql = "select titulares.id_titulares,titulares.titular,titulares.dui_titular,o.id_orden,o.id_cita,o.genero,o.sucursal,o.telefono,o.laboratorio,o.categoria,o.codigo,o.paciente,o.fecha,o.pupilar_od,o.pupilar_oi,o.lente_od,o.patologias,o.lente_oi,o.id_usuario,o.observaciones,o.dui,o.estado,o.tipo_lente,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,o.color as colorTratamiento,o.dui,o.edad,o.usuario_lente,o.ocupacion,o.avsc,o.avfinal,o.avsc_oi,o.avfinal_oi,o.depto,o.municipio,o.institucion from orden_lab as o inner join rx_orden_lab as rx on o.codigo=rx.codigo INNER JOIN titulares ON titulares.codigo=o.codigo where o.codigo = ? and rx.codigo = ? ";
                }
            } else {
                $sql = "select titulares.id_titulares,titulares.titular,titulares.dui_titular,o.id_orden,o.id_cita,o.genero,o.sucursal,o.telefono,o.laboratorio,o.categoria,o.codigo,o.paciente,o.fecha,o.pupilar_od,o.pupilar_oi,o.lente_od,aros.marca,aros.modelo,aros.color,aros.material,aros.id_aro,o.patologias,o.lente_oi,o.id_usuario,o.observaciones,o.dui,o.estado,o.tipo_lente,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,o.color as colorTratamiento,o.dui,o.edad,o.usuario_lente,o.ocupacion,o.avsc,o.avfinal,o.avsc_oi,o.avfinal_oi,o.depto,o.municipio,o.institucion from orden_lab as o inner join rx_orden_lab as rx on o.codigo=rx.codigo INNER JOIN titulares ON titulares.codigo=o.codigo INNER JOIN aros ON o.id_aro = aros.id_aro where o.codigo = ? and rx.codigo = ? ";
            }
        } else if ($id_aro == 0) {
            if (count($data) > 0) {
                $sql = "select am.marca,am.modelo,am.color,am.material,o.id_orden,o.id_cita,o.genero,o.sucursal,o.telefono,o.laboratorio,o.categoria,o.codigo,o.paciente,o.fecha,o.pupilar_od,o.pupilar_oi,o.lente_od,o.patologias,o.lente_oi,o.id_usuario,o.observaciones,o.dui,o.estado,o.tipo_lente,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,o.color as colorTratamiento,o.dui,o.edad,o.usuario_lente,o.ocupacion,o.avsc,o.avfinal,o.avsc_oi,o.avfinal_oi,o.depto,o.municipio,o.institucion from orden_lab as o inner join rx_orden_lab as rx on o.codigo=rx.codigo INNER JOIN aros_manuales as am ON o.codigo=am.codigo_orden where o.codigo = ? and rx.codigo = ? ";
            } else {
                $sql = "select o.id_orden,o.id_cita,o.genero,o.sucursal,o.telefono,o.laboratorio,o.categoria,o.codigo,o.paciente,o.fecha,o.pupilar_od,o.pupilar_oi,o.lente_od,o.patologias,o.lente_oi,o.id_usuario,o.observaciones,o.dui,o.estado,o.tipo_lente,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,o.color as colorTratamiento,o.dui,o.edad,o.usuario_lente,o.ocupacion,o.avsc,o.avfinal,o.avsc_oi,o.avfinal_oi,o.depto,o.municipio,o.institucion from orden_lab as o inner join rx_orden_lab as rx on o.codigo=rx.codigo where o.codigo = ? and rx.codigo = ? ";
            }
        } else {
            $sql = "select o.id_orden,o.id_cita,o.genero,o.sucursal,o.telefono,o.laboratorio,o.categoria,o.codigo,o.paciente,o.fecha,o.pupilar_od,o.pupilar_oi,o.lente_od,o.patologias,o.lente_oi,aros.marca,aros.modelo,o.id_usuario,o.observaciones,o.dui,o.estado,o.tipo_lente,rx.od_esferas,aros.id_aro,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,aros.color,o.color as colorTratamiento,aros.material,o.dui,o.edad,o.usuario_lente,o.ocupacion,o.avsc,o.avfinal,o.avsc_oi,o.avfinal_oi,o.depto,o.municipio,o.institucion from orden_lab as o inner join rx_orden_lab as rx on o.codigo=rx.codigo INNER JOIN aros ON o.id_aro = aros.id_aro where o.codigo = ? and rx.codigo = ? ";
        }
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $codigo);
        $sql->bindValue(2, $codigo);
        $sql->execute();
        return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    
    //New code para obtener ordenes manual
    public function get_ordenes_recibir_man($dui){
        $conectar = parent::conexion();
        $sql = "select o.codigo, rv.fecha, o.paciente, trim(o.dui) as dui, rv.cod_envio, o.sucursal from detalle_reenvio_lab as rv inner join orden_lab as o on rv.dui=o.dui WHERE rv.estado=0 and rv.dui=? order by rv.id_reenvio DESC";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $dui);
        $sql->execute();
        return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
    
    }
    //Método para comprobar si ya fue recibidad la orden
    public function get_orden_recibir_comprobar($dui){
        $conectar = parent::conexion();
        $sql = "SELECT * FROM acciones_bodega_avplus where cod_bodega LIKE 'INGR-%' and dui=?";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $dui);
        $sql->execute();
        return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * methodos para graficos
     */
    public function listar_cant_orden_mes()
    {
        $conectar = parent::conexion();
        parent::set_names();
        //Mostrar solo los datos que ingresan, para contar bien la data
        $sql = "select month(fecha) as month, YEAR(fecha) as year, COUNT(id_acc_bodega) as cantidad,fecha from acciones_bodega_avplus where tipo_accion='Ingreso a bodega AV Plus' group by month(fecha) order by id_acc_bodega desc";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        return $result = $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * Obtener data para grafico de mes
     */
    public function get_data_orden_mes($yearMonth){
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "select COUNT(*) as cantidad, DATE_FORMAT(fecha,'%d-%m-%Y') as fecha from acciones_bodega_avplus WHERE fecha LIKE ? and tipo_accion='Ingreso a bodega AV Plus' group by fecha order by fecha asc";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1,$yearMonth."%");
        $sql->execute();
        $cantidadRecibidad = $sql->fetchAll(PDO::FETCH_ASSOC);
        //Ordenes despachadas o finalizadas
        $sql = "select COUNT(*) as cantidad, DATE_FORMAT(fecha,'%d-%m-%Y') as fecha from acciones_bodega_avplus WHERE fecha LIKE ? and tipo_accion='Finalizada orden Lab' group by fecha order by fecha asc;";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1,$yearMonth."%");
        $sql->execute();
        $cantidadDespachadas = $sql->fetchAll(PDO::FETCH_ASSOC);
        return [
            "ordenesRecibidas" => $cantidadRecibidad,
            "ordenesDespachadas" => $cantidadDespachadas
        ];
    }
    public function get_month_string($number_month)
    {
        $array_month = [1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'];
        return $array_month[$number_month];
    }
}
