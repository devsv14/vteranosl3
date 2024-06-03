<?php
require_once("../config/conexion.php");

class Reporteria extends Conectar{

public function print_orden($codigo){
    $conectar= parent::conexion();
    parent::set_names(); 

    $sql = "select o.id_orden,o.fecha,o.paciente,o.dui,o.edad,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,o.pupilar_od,o.pupilar_oi,o.lente_od,o.lente_oi,o.pupilar_od,o.pupilar_oi,o.lente_od,o.lente_oi,o.avsc,o.avfinal,o.modelo_aro,o.marca_aro,o.horizontal_aro,o.vertical_aro,o.puente_aro,o.tipo_lente,o.codigo,o.codigo_lenti from orden_lab as o inner join rx_orden_lab as rx on o.codigo=rx.codigo where o.codigo=?;";
    $sql = $conectar->prepare($sql);
    $sql->bindValue(1,$codigo);
    $sql->execute();
    return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);    
}

public function get_ordenes_recibir_lab($codigo){
  $conectar= parent::conexion();
  parent::set_names();
  $sql = "select*from orden_lab where id_orden=?;";
  $sql=$conectar->prepare($sql);
  $sql->bindValue(1,$codigo);
  $sql->execute();
  return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);
}

public function getItemsReporteOrdenes($correlativo){
  $conectar= parent::conexion();
  parent::set_names();
  $sql = "select o.telefono,o.paciente,o.dui,o.fecha as fecha_o,o.tipo_lente,o.codigo,d.codigo_orden,a.fecha,a.hora,a.usuario,a.ubicacion,o.tipo_lente,d.id_detalle_accion from orden_lab as o inner join detalle_acciones_veteranos as d on o.codigo=d.codigo_orden INNER join acciones_ordenes_veteranos as a on a.correlativo_accion=d.correlativo_accion where d.correlativo_accion=? order by o.fecha ASC;";
  $sql=$conectar->prepare($sql);
  $sql->bindValue(1,$correlativo);
  $sql->execute();
  return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);
}

public function getItemsSelect(){
$conectar= parent::conexion();
  parent::set_names();
  $sql = "select marca_aro,modelo_aro,horizontal_aro,vertical_aro,puente_aro,COUNT(modelo_aro) as cant,img from orden_lab where fecha between '2021-12-01' and '2022-05-09' GROUP by modelo_aro,marca_aro order by cant desc;";
  $sql=$conectar->prepare($sql);
  $sql->execute();
  return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);

}

//obterner citas segun día seleccionado
public function get_pacientes_citados($fecha,$sucursal){
  $conectar= parent::conexion();
  parent::set_names();
  $sql = "select*from citas where fecha = ? and sucursal=? order by hora DESC;";
  $sql=$conectar->prepare($sql);
  $sql->bindValue(1,$fecha);
  $sql->bindValue(2,$sucursal);
  $sql->execute();
  return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);
}
public function datosIngresoBodega($correlativo){
  $conectar= parent::conexion();
  parent::set_names();
  $sql = "SELECT u.nombres,i.fecha,i.hora,i.bodega from ingreso_aros as i INNER join usuarios as u on u.id_usuario=i.id_usuario where i.n_ingreso=?;";
  $sql=$conectar->prepare($sql);
  $sql->bindValue(1,$correlativo);
  $sql->execute();
  return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);
}

public function detalle_ingresoBodega($id_ingreso){
  $conectar= parent::conexion();
  parent::set_names();
  $sql = "SELECT a.modelo,a.marca,a.color,a.material,i.cantidad from aros as a INNER JOIN detalle_ingreso_aros as i on a.id_aro=i.id_aro where i.n_ingreso=? order by material DESC;";
  $sql=$conectar->prepare($sql);
  $sql->bindValue(1,$id_ingreso);
  $sql->execute();
  return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);
}

public function stockSucursales($bodega){
  $conectar= parent::conexion();
  parent::set_names();
  $sql = "SELECT a.modelo,a.marca,a.color,a.material,s.stock from aros as a INNER JOIN stock_aros as s on a.id_aro=s.id_aro where s.bodega=? order by material DESC";
  $sql=$conectar->prepare($sql);
  $sql->bindValue(1,$bodega);
  $sql->execute();
  return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);
}

public function getDetalleDespacho($n_despacho){
  $conectar = parent::conexion();
  parent::set_names();
  $sql = "SELECT * FROM `det_despacho_lab` where n_despacho=? order by trim(paciente) asc";
  $sql = $conectar->prepare($sql);
  $sql->bindValue(1, $n_despacho);
  $sql->execute();
  return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
}

public function getDataOrdenDui($dui){
  
    $conectar= parent::conexion();
    parent::set_names(); 

    $sql = "select o.sucursal,o.id_orden,o.fecha,o.id_aro,o.paciente,o.dui,o.edad,o.observaciones,o.color,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,o.pupilar_od,o.pupilar_oi,o.lente_od,o.lente_oi,o.pupilar_od,o.pupilar_oi,o.lente_od,o.lente_oi,o.avsc,o.avfinal,o.tipo_lente,o.codigo,o.codigo_lenti,o.sucursal,o.telefono from orden_lab as o inner join rx_orden_lab as rx on o.codigo=rx.codigo where o.dui=?;";
    $sql = $conectar->prepare($sql);
    $sql->bindValue(1,$dui);
    $sql->execute();
    return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);    
}
//Function para generar el reporte de enviadas a laboratorio
public function get_detalle_ordenes_envio($cod_despacho){
    $conectar= parent::conexion();
    parent::set_names(); 
    $sql = "SELECT det_o.id_ordenes_envio,det_o.cod_despacho,o.codigo,o.dui,o.paciente,o.fecha,o.telefono,o.tipo_lente FROM `detalle_ordenes_envio` as det_o INNER JOIN orden_lab as o ON det_o.cod_orden_lab=o.codigo WHERE det_o.cod_despacho=?";
    $sql = $conectar->prepare($sql);
    $sql->bindValue(1,$cod_despacho);
    $sql->execute();
    return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
}

public function getDataCita($dui){
  $conectar= parent::conexion();
  parent::set_names(); 
  $sql = "select*from citas where dui=?";
  $sql = $conectar->prepare($sql);
  $sql->bindValue(1,$dui);
  $sql->execute();
  return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
}

public function getDataOrden($dui){
  $conectar= parent::conexion();
  parent::set_names(); 
  $sql = "select*from orden_lab where dui=?";
  $sql = $conectar->prepare($sql);
  $sql->bindValue(1,$dui);
  $sql->execute();
  return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
}

public function getDataActa($dui){
  $conectar= parent::conexion();
  parent::set_names(); 
  $sql = "select*from actas where dui_acta=?";
  $sql = $conectar->prepare($sql);
  $sql->bindValue(1,$dui);
  $sql->execute();
  return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
}

public function getDataSucursal($sucursal){
  $conectar= parent::conexion();
  parent::set_names(); 
  $sql = "select*from sucursales where nombre=?";
  $sql = $conectar->prepare($sql);
  $sql->bindValue(1,$sucursal);
  $sql->execute();
  return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
}

public function getCitasDiariasResumen($fecha){
  $conectar= parent::conexion();
  parent::set_names();
  $sql="select sucursal, count(id_cita) as totales from citas WHERE fecha = ? GROUP by sucursal;";
  $sql = $conectar->prepare($sql);
  $sql->bindValue(1,$fecha);
  $sql->execute();
  return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
}

public function getDataAro($id_aro,$codigo){
  $conectar= parent::conexion();
  parent::set_names();
  if($id_aro != 0){
    $sql = 'select CONCAT("Mod.: ",modelo," Marca: ",marca," ",color) as "aro" from aros where id_aro=?';
    $sql = $conectar->prepare($sql);
    $sql->bindValue(1,$id_aro);   
  }elseif($id_aro == 0){
    $sql = 'select CONCAT("Mod.: ",modelo," Marca: ",marca," ",color) as "aro" from aros_manuales where codigo_orden=?';
    $sql = $conectar->prepare($sql);
    $sql->bindValue(1,$codigo);  
  }else{
    $sql = 'select CONCAT("Mod.: A2021"," Marca: ANDVAS"," C1") as "aro"';
    $sql = $conectar->prepare($sql);
  }
  $sql->execute(); 
  $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
  return 'Aro: '.$resultado[0]["aro"];
}

public function getDataAroL1($dui){
  $conectar= parent::conexion();
  parent::set_names();
//Esta consulta se modifico porque se esta utilizando para actualizar fecha de la orden original
  //$ord = 'select*from orden_lab_lc1 where dui = ?';
  $ord = 'select*from orden_lab where dui = ?';
  $ord=$conectar->prepare($ord);
  $ord->bindValue(1, $dui);
  $ord->execute();
  $dataord = $ord->fetchAll(PDO::FETCH_ASSOC); 
  //return "Aro Mod.". $dataord[0]['modelo_aro']." H:".$dataord[0]['horizontal_aro'];
}

public function getNumeroOrden($fecha){
  $conectar= parent::conexion();
  $sql = "select count(dui) as cant FROM hoja_atencion WHERE fecha = ? and sucursal=?;";
  $sql = $conectar->prepare($sql);
  $sql->bindValue(1,$fecha);
  $sql->bindValue(2,$_SESSION['sucursal']);
  $sql->execute(); 
  $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
  return $resultado[0]["cant"];
}

//Function para generar el reporte de enviadas a laboratorio
public function get_detalle_ordenes_envio_new($cod_despacho){
  $conectar= parent::conexion();
  parent::set_names(); 
  $sql = "SELECT det_o.id_ordenes_envio,det_o.cod_despacho,o.codigo,o.dui,o.paciente,o.fecha,o.telefono,o.tipo_lente,o.sucursal FROM `detalle_ordenes_envio` as det_o INNER JOIN orden_lab as o ON det_o.cod_orden_lab=o.codigo WHERE det_o.cod_despacho=?";
  $sql = $conectar->prepare($sql);
  $sql->bindValue(1,$cod_despacho);
  $sql->execute();
  return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
}

public function get_detalle_ordenes_envio_sucursal($cod_despacho,$sucursal){
  $conectar= parent::conexion();
  parent::set_names(); 
  $sql = "SELECT det_o.id_ordenes_envio,det_o.cod_despacho,o.codigo,o.dui,o.paciente,o.fecha,o.telefono,o.tipo_lente,o.sucursal FROM `detalle_ordenes_envio` as det_o INNER JOIN orden_lab as o ON det_o.cod_orden_lab=o.codigo WHERE det_o.cod_despacho=? and o.sucursal=? ORDER BY TRIM(o.paciente) ASC";
  $sql = $conectar->prepare($sql);
  $sql->bindValue(1,$cod_despacho);
  $sql->bindValue(2,$sucursal);
  $sql->execute();
  return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
}
//Reporte para reenvios
public function get_ordenes_reenviadas_pdf($cod_reenvio){
  $conectar= parent::conexion();
  parent::set_names();
  $sql = "SELECT o.id_orden,rv.cod_envio,rv.fecha,rv.hora,o.paciente,o.dui,o.telefono,o.tipo_lente,rv.laboratorio,o.sucursal FROM `detalle_reenvio_lab` as rv inner join orden_lab as o on rv.dui=o.dui where rv.cod_envio=? ORDER BY TRIM(o.paciente) ASC";
  $sql = $conectar->prepare($sql);
  $sql->bindValue(1,$cod_reenvio);
  $sql->execute();
  return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
}
//Reporte para control de actas
/**
 *@param string Codigo generado al momento de enviar la entrega de actas 
 */
public function get_control_actas_all($cod_entrega){
  $conectar= parent::conexion();
  parent::set_names();
  $sql = "select * from `control_actas` where cod_entrega=? order by trim(paciente) asc";
  $stmt = $conectar->prepare($sql);
  $stmt->bindValue(1,$cod_entrega);
  $stmt->execute();
  return $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function get_receptor_actas($cod_entrega){
  $conectar= parent::conexion();
  parent::set_names();
  $sql = "SELECT * FROM `receptores_actas` where codigo_entrega=?";
  $stmt = $conectar->prepare($sql);
  $stmt->bindValue(1,$cod_entrega);
  $stmt->execute();
  return $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * METHOD QUE RETORNA DATOS DE ORDEN SIN CITAS Y INSTITUCION CONYUGE (ACTAS)
 */
public function getPacientesActasConyuge($dui_acta){
  $connnet = parent::conexion();
  parent::set_names();
  $sql = "SELECT * FROM `orden_lab` AS o where o.dui=? limit 1";
  $sql = $connnet->prepare($sql);
  $sql->bindValue(1,$dui_acta);
  $sql->execute();
  $result = $sql->fetchAll(PDO::FETCH_ASSOC);
  //FORMATO ARRAY A RETORNAR
  if(count($result) > 0){
    foreach($result as $row){
      $array['codigo'] = $row['codigo'];
      $array['institucion'] = $row['institucion'];
      $titular = $this->getTitularOrden($row['codigo']);
      $array['titular'] = $titular != "" ? $titular['titular'] : '';
      $array['dui_titular'] = $titular['dui_titular'] != "" ? $titular['dui_titular'] : '';
      //Fecha y hora acta
      $acta = $this->getDateHoursImpresionActa($dui_acta);
      $array['fecha_impresion'] = $acta['fecha_impresion'] != "" ? $acta['fecha_impresion'] : '';
      $array['hora_impresion'] = $acta['hora_impresion'] != "" ? $acta['hora_impresion'] : '';
    }
    return $array;
  };
  return [];
}
/**
 * Retorna el titular de ordenes sin cita
 */
public function getTitularOrden($codigo){
  $connnet = parent::conexion();
  parent::set_names();
  $sql = "select * from `titulares` where codigo=? limit 1";
  $sql = $connnet->prepare($sql);
  $sql->bindValue(1,$codigo);
  $sql->execute();
  $result = $sql->fetchAll(PDO::FETCH_ASSOC);
  if(count($result) > 0) return $result[0];
  return ['titular' => '','dui_titular' => ''];
}
/**
 * Retorna la fecha y la hora de impresion
 */
public function getDateHoursImpresionActa($dui_acta){
  $connnet = parent::conexion();
  parent::set_names();
  $sql = "select fecha_impresion,hora_impresion from `actas` where dui_acta=? limit 1";
  $sql = $connnet->prepare($sql);
  $sql->bindValue(1,$dui_acta);
  $sql->execute();
  $result = $sql->fetchAll(PDO::FETCH_ASSOC);
  if(count($result) > 0) return $result[0];
  return ['fecha_impresion' => '','hora_impresion' => ''];
}

}///FIN DE LA CLASE





