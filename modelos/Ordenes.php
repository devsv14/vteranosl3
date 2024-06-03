<?php
require_once("../config/conexion.php");  

  class Ordenes extends Conectar{



    public function get_correlativo_orden($fecha){
    $conectar = parent::conexion();
    $fecha_act = $fecha.'%';         
    $sql= "select codigo from orden_lab where fecha_correlativo like ? order by id_orden DESC limit 1;";
    $sql=$conectar->prepare($sql);
    $sql->bindValue(1,$fecha_act);
    $sql->execute();
    return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);
  }

  /////////////////  COMPROBAR SI EXISTE CORRELATIVO ///////////////
  public function validar_correlativo_orden($codigo){
    $conectar = parent::conexion();
    parent::set_names();
    $sql="select*from orden_lab where codigo=?;";
    $sql= $conectar->prepare($sql);
    $sql->bindValue(1, $codigo);
    $sql->execute();
    return $resultado=$sql->fetchAll();
  }

    public function validar_existe_correlativo($dui){
    $conectar = parent::conexion();
    parent::set_names();
    $sql="select*from orden_lab where dui=?;";
    $sql= $conectar->prepare($sql);
    $sql->bindValue(1, $dui);
    $sql->execute();
    return $resultado=$sql->fetchAll();
  }
  //////////////CREAR  BARCODE///////////////////////////////////
  public function crea_barcode($codigo){
    include 'barcode.php';       
    barcode('../codigos/' . $codigo . '.png', $codigo, 50, 'horizontal', 'code128', true);
  }
  /////////////   REGISTRAR ORDEN ///////////////////////////////
  public function registrar_orden($correlativo_op,$paciente,$od_pupilar,$oipupilar,$odlente,$oilente,$id_aro,$id_usuario,$observaciones_orden,$dui,$od_esferas,$od_cilindros,$od_eje,$od_adicion,$oi_esferas,$oi_cilindros,$oi_eje,$oi_adicion,$tipo_lente,$edad,$ocupacion,$avsc,$avfinal,$avsc_oi,$avfinal_oi,$telefono,$genero,$user,$depto,$municipio,$instit,$patologias,$color,$indice,$id_cita,$sucursal,$categoria_lente,$laboratorio,$titular,$dui_titular,$modelo_aro_orden,$marca_aro_orden,$material_aro_orden,$color_aro_orden,$usuario_lente){

    $conectar = parent::conexion();
    date_default_timezone_set('America/El_Salvador'); 
    $hoy = date("d-m-Y H:i:s");
    $fecha_creacion = date("Y-m-d");
    $estado = 0;
    //Insertar aro si id es vacio
    $updateAroStock = false;
    if($id_aro == "" or $id_aro == null){
      $sql_aro = "insert into aros_manuales values(null,?,?,?,?,?);";
      $sql_aro = $conectar->prepare($sql_aro);
      $sql_aro->bindValue(1, $correlativo_op);
      $sql_aro->bindValue(2, $marca_aro_orden);
      $sql_aro->bindValue(3, $modelo_aro_orden);
      $sql_aro->bindValue(4, $color_aro_orden);
      $sql_aro->bindValue(5, $material_aro_orden);
      $sql_aro->execute();
      //default id
      $id_aro = 0;
      $updateAroStock = true;
    }else{
      $sql_aros = "SELECT stock FROM `stock_aros` WHERE id_aro =:id_aro AND bodega = :bodega";
      $sql_aros = $conectar->prepare($sql_aros);
      $sql_aros->bindParam(':id_aro',$id_aro);
      $sql_aros->bindParam(':bodega',$sucursal);
      $sql_aros->execute();
      $resultado = $sql_aros->fetchAll(PDO::FETCH_ASSOC);
      if(count($resultado) > 0){
        if($resultado[0]['stock'] > 0){
          //Actualiza el stock de los aros
          $stock = $resultado[0]['stock'] - 1;
          $sql_update_stock = "UPDATE stock_aros SET stock=? WHERE id_aro=? AND bodega=?";
          $sql_update_stock = $conectar->prepare($sql_update_stock);
          $sql_update_stock->bindParam(1,$stock);
          $sql_update_stock->bindParam(2,$id_aro);
          $sql_update_stock->bindParam(3,$sucursal);
          if($sql_update_stock->execute()){
            $updateAroStock = true;
          }
        }
      }
    }
    //Validacion si se inserto
    if($updateAroStock){
        $conectar->beginTransaction();
      $sql = "insert into orden_lab values (null,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,null,?,?,?,?,?);";
      $sql = $conectar->prepare($sql);
      $sql->bindValue(1, $correlativo_op);
      $sql->bindValue(2, trim($paciente));
      $sql->bindValue(3, $fecha_creacion);
      $sql->bindValue(4, $od_pupilar);
      $sql->bindValue(5, $oipupilar);
      $sql->bindValue(6, $odlente);
      $sql->bindValue(7, $oilente);
      $sql->bindValue(8, $id_aro);
      $sql->bindValue(9, $id_usuario);
      $sql->bindValue(10, $observaciones_orden);
      $sql->bindValue(11, trim($dui));
      $sql->bindValue(12, $estado);
      $sql->bindValue(13, $hoy);
      $sql->bindValue(14, $tipo_lente);
      $sql->bindValue(15, $laboratorio);
      $sql->bindValue(16, $categoria_lente);
      $sql->bindValue(17, $edad);
      $sql->bindValue(18, $usuario_lente);
      $sql->bindValue(19, $ocupacion);
      $sql->bindValue(20, $avsc);
      $sql->bindValue(21, $avfinal);
      $sql->bindValue(22, $avsc_oi);
      $sql->bindValue(23, $avfinal_oi);
      $sql->bindValue(24, $telefono);
      $sql->bindValue(25, $genero);
      $sql->bindValue(26, $depto);
      $sql->bindValue(27, $municipio);
      $sql->bindValue(28, $instit);
      $sql->bindValue(29, $color);
      $sql->bindValue(30, $indice);
      $sql->bindValue(31, $patologias);
      $sql->bindValue(32, $id_cita);
      $sql->bindValue(33, $sucursal);

      if($sql->execute()){
        $id_orden_lab = $conectar->lastInsertId();
        //Insertamos la RX_orden lab
        $sql2 = "insert into rx_orden_lab value(null,?,?,?,?,?,?,?,?,?);";
        $sql2 = $conectar->prepare($sql2);
        $sql2->bindValue(1, $correlativo_op);
        $sql2->bindValue(2, $od_esferas);
        $sql2->bindValue(3, $od_cilindros);
        $sql2->bindValue(4, $od_eje);
        $sql2->bindValue(5, $od_adicion);
        $sql2->bindValue(6, $oi_esferas);
        $sql2->bindValue(7, $oi_cilindros);
        $sql2->bindValue(8, $oi_eje);
        $sql2->bindValue(9, $oi_adicion);
        $sql2->execute();
      }
      $conectar->commit();
      //Insertar titular
      if($instit=="CONYUGE"){
        $sql8 = "insert into titulares values(null,?,?,?);";
        $sql8 = $conectar->prepare($sql8);
        $sql8->bindValue(1, $titular);
        $sql8->bindValue(2, $dui_titular);
        $sql8->bindValue(3, $correlativo_op);
        $sql8->execute();
      }
      $accion = "Digitación orden";
      $sql7 = "insert into acciones_orden values(null,?,?,?,?,?,?);";
      $sql7 = $conectar->prepare($sql7);
      $sql7->bindValue(1, $hoy);
      $sql7->bindValue(2, $user);
      $sql7->bindValue(3, $correlativo_op);
      $sql7->bindValue(4, $accion);
      $sql7->bindValue(5, $accion);
      $sql7->bindValue(6, $sucursal);
      $sql7->execute();
      return $id_orden_lab;
    }else{
      return false;
    }
      
  }
   ////////////////////LISTAR ORDENES///////////////
   public function editar_orden($correlativo_op,$paciente,$od_pupilar,$oipupilar,$odlente,$oilente,$id_aro,$id_usuario,$observaciones_orden,$dui,$od_esferas,$od_cilindros,$od_eje,$od_adicion,$oi_esferas,$oi_cilindros,$oi_eje,$oi_adicion,$tipo_lente,$edad,$ocupacion,$avsc,$avfinal,$avsc_oi,$avfinal_oi,$telefono,$genero,$user,$depto,$municipio,$instit,$patologias,$color,$indice,$id_cita,$sucursal,$categoria_lente,$laboratorio,$titular,$dui_titular,$id_titular,$modelo_aro_orden,$marca_aro_orden,$material_aro_orden,$color_aro_orden,$usuario_lente,$old_id_aro,$obser_edicion){
    $fecha_creacion = date("Y-m-d");
    $hoy = date("d-m-Y H:i:s");
    $conectar = parent::conexion();
    $edit_ord = "update orden_lab set
      paciente = ?,
      fecha = ?,
  
      pupilar_od = ?,                                            
      pupilar_oi = ?,
      lente_od = ?,
      lente_oi = ?,
  
      id_aro = ?,
      id_usuario = ?,
  
      observaciones = ?,
      dui = ?,
  
  
      fecha_correlativo=?,
      tipo_lente=?,
      laboratorio=?,
      categoria=?,
  
      edad=?,
      usuario_lente=?,
      ocupacion = ?,
      avsc =?,
      avfinal =?,
      avsc_oi=?,
      avfinal_oi=?,
      telefono = ?,
      genero = ?,
      depto=?,
      municipio=?,
      institucion = ?,
      color=?,
      patologias=?,
      id_cita=?
  
      where codigo = ?;";
  
    $edit_ord = $conectar->prepare($edit_ord);
    $edit_ord->bindValue(1, trim($paciente));
    $edit_ord->bindValue(2, $fecha_creacion);
  
    $edit_ord->bindValue(3, $od_pupilar);
    $edit_ord->bindValue(4, $oipupilar);
    $edit_ord->bindValue(5, $odlente);
    $edit_ord->bindValue(6, $oilente);
  
    $edit_ord->bindValue(7, $id_aro);
    $edit_ord->bindValue(8, $id_usuario);
    $edit_ord->bindValue(9, $observaciones_orden);
    $edit_ord->bindValue(10, trim($dui));
    $edit_ord->bindValue(11, $hoy);
    $edit_ord->bindValue(12, $tipo_lente);
    $edit_ord->bindValue(13, $laboratorio);
    $edit_ord->bindValue(14, $categoria_lente);
  
    $edit_ord->bindValue(15, $edad);
    $edit_ord->bindValue(16, $usuario_lente);
    $edit_ord->bindValue(17, $ocupacion);
    $edit_ord->bindValue(18, $avsc);
    $edit_ord->bindValue(19, $avfinal);
    $edit_ord->bindValue(20, $avsc_oi);
    $edit_ord->bindValue(21, $avfinal_oi);
    $edit_ord->bindValue(22, $telefono);
    $edit_ord->bindValue(23, $genero);
    $edit_ord->bindValue(24, $depto);
    $edit_ord->bindValue(25, $municipio);
    $edit_ord->bindValue(26, $instit);
  
    $edit_ord->bindValue(27, $color);
    
    $edit_ord->bindValue(28, $patologias);
  
    $edit_ord->bindValue(29, $id_cita);
    $edit_ord->bindValue(30, $correlativo_op);
  
    if($edit_ord->execute()){

      //Update cita
      $sql_up_cita = "update citas set paciente=?,dui=?,edad=?,telefono=?,ocupacion=?,genero=?,sector=?,depto=?,municipio=? where id_cita=?";
  
      $sql_up_cita = $conectar->prepare($sql_up_cita);
      $sql_up_cita->bindValue(1,$paciente);
      $sql_up_cita->bindValue(2,$dui);
      $sql_up_cita->bindValue(3,$edad);
      $sql_up_cita->bindValue(4,$telefono);
      $sql_up_cita->bindValue(5,$ocupacion);
      $sql_up_cita->bindValue(6,$genero);
      $sql_up_cita->bindValue(7,$instit);
      $sql_up_cita->bindValue(8,$depto);
      $sql_up_cita->bindValue(9,$municipio);
      $sql_up_cita->bindValue(10,$id_cita);
      if($sql_up_cita->execute()){
        $sql2 = "update rx_orden_lab set
      od_esferas=?,
      od_cilindros=?,
      od_eje=?,
      od_adicion=?,
      oi_esferas=?,
      oi_cilindros=?,
      oi_eje=?,
      oi_adicion=?
      where codigo=?";
      $sql2 = $conectar->prepare($sql2);  
      $sql2->bindValue(1, $od_esferas);
      $sql2->bindValue(2, $od_cilindros);
      $sql2->bindValue(3, $od_eje);
      $sql2->bindValue(4, $od_adicion);
      $sql2->bindValue(5, $oi_esferas);
      $sql2->bindValue(6, $oi_cilindros);
      $sql2->bindValue(7, $oi_eje);
      $sql2->bindValue(8, $oi_adicion);
      $sql2->bindValue(9, $correlativo_op);
      $sql2->execute();
      //Control de orden
      $accion = "Edición orden";
  
      $sql7 = "insert into acciones_orden values(null,?,?,?,?,?,?);";
      $sql7 = $conectar->prepare($sql7);
      $sql7->bindValue(1, $hoy);
      $sql7->bindValue(2, $user);
      $sql7->bindValue(3, $correlativo_op);
      $sql7->bindValue(4, $accion);
      $sql7->bindValue(5, $obser_edicion);
      $sql7->bindValue(6, $sucursal);
      $sql7->execute();
  
      if($instit=="CONYUGE"){
        $sql_titular = "UPDATE `titulares` SET titular=:titular,dui_titular=:dui_titular WHERE id_titulares=:id_titulares";
        $sql_titular = $conectar->prepare($sql_titular);
        $sql_titular->bindParam(':titular',$titular);
        $sql_titular->bindParam(':dui_titular',$dui_titular);
        $sql_titular->bindParam(':id_titulares',$id_titular);
        $sql_titular->execute();
      }
      //Rollback ARO
      if($old_id_aro != $id_aro){
        if($old_id_aro != ""){
          //Increment +1 stock
          $sql = "update stock_aros set stock=stock + 1 where bodega=:bodega and id_aro=:old_id_aro";
          $sql = $conectar->prepare($sql);
          $sql->bindParam(':bodega',$sucursal);
          $sql->bindParam(':old_id_aro',$old_id_aro);
          $sql->execute();
          //Decrement -1 stock
          $sql_update_stock = "UPDATE stock_aros SET stock=stock - 1 WHERE id_aro=:id_aro AND bodega=:bodega";
          $sql_update_stock = $conectar->prepare($sql_update_stock);
          $sql_update_stock->bindParam(':id_aro',$id_aro);
          $sql_update_stock->bindParam(':bodega',$sucursal);
          $sql_update_stock->execute();
        }
      }else{
        //Update aro
        if($id_aro != 0){
          $sql_aro = "update aros set marca=?,modelo=?,color=?,material=? where id_aro=?";
          $id_aro = $id_aro;
        }else{
          $sql_aro = "update aros_manuales set marca=?,modelo=?,color=?,material=? where codigo_orden=?";
          $id_aro = $correlativo_op;
        }
        $sql_aro = $conectar->prepare($sql_aro);
        $sql_aro->bindValue(1, $marca_aro_orden);
        $sql_aro->bindValue(2, $modelo_aro_orden);
        $sql_aro->bindValue(3, $color_aro_orden);
        $sql_aro->bindValue(4, $material_aro_orden);
        $sql_aro->bindValue(5, $id_aro);
        $sql_aro->execute();
      }
        return true;
      }else{
        return false;
      }
    }else{
      return false;
    }

  }

  public function get_ordenes($sucursal,$permisos){
    $conectar= parent::conexion();

    if($permisos=="Ok"){
      $sql= "select*from orden_lab order by id_orden DESC;";
      $sql=$conectar->prepare($sql);
      $sql->execute();
      return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);
    }else{
      $sql= 'select o.id_orden,o.codigo,o.fecha,o.estado,o.tipo_lente,o.telefono,o.id_aro,o.id_cita,o.institucion,o.id_usuario,o.sucursal,o.dui, COALESCE((SELECT CONCAT(paciente," - RN") as paciente from orden_lab as od WHERE od.dui=o.dui and od.estado="l1"),o.paciente) as paciente from orden_lab as o where o.sucursal=? or o.estado="l1" order by o.id_orden DESC;';
      $sql=$conectar->prepare($sql);
      $sql->bindValue(1, $sucursal);
      $sql->execute();
      return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);

    }
    
  }

  public function get_ordenes_filter_date($inicio,$fin){
    $conectar= parent::conexion();
    $sql= "select*from orden_lab where fecha between ? and ? order by fecha DESC;";
    $sql=$conectar->prepare($sql);
    $sql->bindValue(1, $inicio);
    $sql->bindValue(2, $fin);
    $sql->execute();
    return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);
  }

  public function get_data_orden($codigo,$paciente,$id_aro,$institucion,$id_cita){
    $conectar = parent::conexion();

    //Verificador para ver si tiene ingresado un aro en manuales
    $sql = "select id_aro from aros_manuales where codigo_orden=?";
    $sql = $conectar->prepare($sql);
    $sql->bindValue(1,$codigo);
    $sql->execute();
    $data = $sql->fetchAll(PDO::FETCH_ASSOC);

    if($institucion == "CONYUGE"){
        if($id_aro == 0){
          if(count($data) > 0){
            $sql = "select o.alto_indice,am.marca,am.modelo,am.color,am.material,titulares.id_titulares,titulares.titular,titulares.dui_titular,o.id_orden,o.id_cita,o.genero,o.sucursal,o.telefono,o.laboratorio,o.categoria,o.codigo,o.paciente,o.fecha,o.pupilar_od,o.pupilar_oi,o.lente_od,o.patologias,o.lente_oi,o.id_usuario,o.observaciones,o.dui,o.estado,o.tipo_lente,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,o.color as colorTratamiento,o.dui,o.edad,o.usuario_lente,o.ocupacion,o.avsc,o.avfinal,o.avsc_oi,o.avfinal_oi,o.depto,o.municipio,o.institucion from orden_lab as o inner join rx_orden_lab as rx on o.codigo=rx.codigo INNER JOIN titulares ON titulares.codigo=o.codigo INNER JOIN aros_manuales as am ON o.codigo=am.codigo_orden where o.codigo = ? and rx.codigo = ? and o.paciente=?;";
          }else{
            $sql = "select o.alto_indice,titulares.id_titulares,titulares.titular,titulares.dui_titular,o.id_orden,o.id_cita,o.genero,o.sucursal,o.telefono,o.laboratorio,o.categoria,o.codigo,o.paciente,o.fecha,o.pupilar_od,o.pupilar_oi,o.lente_od,o.patologias,o.lente_oi,o.id_usuario,o.observaciones,o.dui,o.estado,o.tipo_lente,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,o.color as colorTratamiento,o.dui,o.edad,o.usuario_lente,o.ocupacion,o.avsc,o.avfinal,o.avsc_oi,o.avfinal_oi,o.depto,o.municipio,o.institucion from orden_lab as o inner join rx_orden_lab as rx on o.codigo=rx.codigo INNER JOIN titulares ON titulares.codigo=o.codigo where o.codigo = ? and rx.codigo = ? and o.paciente=?;";
          }

        }else{
            $sql = "select o.alto_indice,titulares.id_titulares,titulares.titular,titulares.dui_titular,o.id_orden,o.id_cita,o.genero,o.sucursal,o.telefono,o.laboratorio,o.categoria,o.codigo,o.paciente,o.fecha,o.pupilar_od,o.pupilar_oi,o.lente_od,aros.marca,aros.modelo,aros.color,aros.material,aros.id_aro,o.patologias,o.lente_oi,o.id_usuario,o.observaciones,o.dui,o.estado,o.tipo_lente,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,o.color as colorTratamiento,o.dui,o.edad,o.usuario_lente,o.ocupacion,o.avsc,o.avfinal,o.avsc_oi,o.avfinal_oi,o.depto,o.municipio,o.institucion from orden_lab as o inner join rx_orden_lab as rx on o.codigo=rx.codigo INNER JOIN titulares ON titulares.codigo=o.codigo INNER JOIN aros ON o.id_aro = aros.id_aro where o.codigo = ? and rx.codigo = ? and o.paciente=?;";
        }

    }else if($id_aro == 0){
      if(count($data) > 0){
        $sql = "select o.alto_indice,am.marca,am.modelo,am.color,am.material,o.id_orden,o.id_cita,o.genero,o.sucursal,o.telefono,o.laboratorio,o.categoria,o.codigo,o.paciente,o.fecha,o.pupilar_od,o.pupilar_oi,o.lente_od,o.patologias,o.lente_oi,o.id_usuario,o.observaciones,o.dui,o.estado,o.tipo_lente,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,o.color as colorTratamiento,o.dui,o.edad,o.usuario_lente,o.ocupacion,o.avsc,o.avfinal,o.avsc_oi,o.avfinal_oi,o.depto,o.municipio,o.institucion from orden_lab as o inner join rx_orden_lab as rx on o.codigo=rx.codigo INNER JOIN aros_manuales as am ON o.codigo=am.codigo_orden where o.codigo = ? and rx.codigo = ? and o.paciente=?;";
      }else{
        $sql = "select o.alto_indice,o.id_orden,o.id_cita,o.genero,o.sucursal,o.telefono,o.laboratorio,o.categoria,o.codigo,o.paciente,o.fecha,o.pupilar_od,o.pupilar_oi,o.lente_od,o.patologias,o.lente_oi,o.id_usuario,o.observaciones,o.dui,o.estado,o.tipo_lente,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,o.color as colorTratamiento,o.dui,o.edad,o.usuario_lente,o.ocupacion,o.avsc,o.avfinal,o.avsc_oi,o.avfinal_oi,o.depto,o.municipio,o.institucion from orden_lab as o inner join rx_orden_lab as rx on o.codigo=rx.codigo where o.codigo = ? and rx.codigo = ? and o.paciente=?;";
      }
      
    }else{
      $sql = "select o.alto_indice,o.id_orden,o.id_cita,o.genero,o.sucursal,o.telefono,o.laboratorio,o.categoria,o.codigo,o.paciente,o.fecha,o.pupilar_od,o.pupilar_oi,o.lente_od,o.patologias,o.lente_oi,aros.marca,aros.modelo,o.id_usuario,o.observaciones,o.dui,o.estado,o.tipo_lente,rx.od_esferas,aros.id_aro,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,aros.color,o.color as colorTratamiento,aros.material,o.dui,o.edad,o.usuario_lente,o.ocupacion,o.avsc,o.avfinal,o.avsc_oi,o.avfinal_oi,o.depto,o.municipio,o.institucion from orden_lab as o inner join rx_orden_lab as rx on o.codigo=rx.codigo INNER JOIN aros ON o.id_aro = aros.id_aro where o.codigo = ? and rx.codigo = ? and o.paciente=?;";
    }
    
    $sql=$conectar->prepare($sql);
    $sql->bindValue(1,$codigo);
    $sql->bindValue(2,$codigo);
    $sql->bindValue(3,$paciente);
    $sql->execute();
    return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);
  }

  public function eliminar_orden($codigo){
    $conectar= parent::conexion();

    //Seleccionar el orden_lab y trae el id de la cita
    $sql ="SELECT codigo,estado,id_cita,sucursal from orden_lab where codigo=?;";
    $sql =$conectar->prepare($sql);
    $sql->bindValue(1,$codigo);
    $sql->execute();
    $result = $sql->fetchAll(PDO::FETCH_ASSOC);
    $id_cita = $result[0]['id_cita'];
    $codigo_orden = $result[0]['codigo'];
    $sucursal = $result[0]['sucursal'];
    $estado = $result[0]['estado'];
    //Validacion si la orden estado estado 1
    if($estado > 0){
      return true;
    }
    //UPDATE A CITA EN ESTADO 0
    if($id_cita != 0){
      $sql ="UPDATE citas SET estado=0 where id_cita=?;";
      $sql =$conectar->prepare($sql);
      $sql->bindValue(1,$id_cita);
      $sql->execute();
    }

    $sql ="delete from rx_orden_lab where codigo=?;";
    $sql=$conectar->prepare($sql);
    $sql->bindValue(1,$codigo);
    $sql->execute();

    $sql2 ="delete from orden_lab where codigo=?;";
    $sql2=$conectar->prepare($sql2);
    $sql2->bindValue(1,$codigo);
    $sql2->execute();
    //delete titulares
    $sql3 ="delete from titulares where codigo=?;";
    $sql3=$conectar->prepare($sql3);
    $sql3->bindValue(1,$codigo);
    $sql3->execute();

    $accion = "Eliminación orden";
    $hoy = date("d-m-Y H:i:s");
    $user = $_SESSION["user"];

    $sql7 = "insert into acciones_orden values(null,?,?,?,?,?,?);";
    $sql7 = $conectar->prepare($sql7);
    $sql7->bindValue(1, $hoy);
    $sql7->bindValue(2, $user);
    $sql7->bindValue(3, $codigo_orden);
    $sql7->bindValue(4, $accion);
    $sql7->bindValue(5, $accion);
    $sql7->bindValue(6, $sucursal);
    $sql7->execute();
    
  }

  public function show_create_order($codigo){
    $conectar= parent::conexion();
    $sql="select u.nombres,o.fecha_correlativo from orden_lab as o inner join usuarios as u on u.id_usuario=o.id_usuario where o.codigo=?;";
    $sql=$conectar->prepare($sql);
    $sql->bindValue(1,$codigo);
    $sql->execute();
    return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
  }

  public function enviar_orden($codigo){
    $conectar= parent::conexion();
    $sql="update orden_lab set estado='1' where codigo=?;";
    $sql=$conectar->prepare($sql);
    $sql->bindValue(1,$codigo);
    $sql->execute();
  }
  
   public function get_ordenes_enviadas(){
    $conectar= parent::conexion();
    $sql= "select*from orden_lab where estado='1' order by id_orden ASC;";
    $sql=$conectar->prepare($sql);
    $sql->execute();
    return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);
  }
 ////////////////////////////////flitrar por fecha    ////
  public function get_ordenes_por_enviar($inicio,$fin){
      $conectar = parent::conexion();
      $sql = "select o.id_orden,o.codigo,o.paciente,o.fecha,o.pupilar_od,o.pupilar_oi,o.lente_od,o.lente_oi,o.marca_aro,o.modelo_aro,o.horizontal_aro,o.vertical_aro,o.puente_aro,o.id_usuario,o.observaciones,o.dui,o.estado,o.tipo_lente,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion
      from
      orden_lab as o inner join rx_orden_lab as rx on o.codigo=rx.codigo where o.estado='0' and fecha between ? and ?  order by o.fecha ASC;";
      $sql=$conectar->prepare($sql);
      $sql->bindValue(1,$inicio);
      $sql->bindValue(2,$fin);
      $sql->execute();
      return $resulta = $sql->fetchAll(PDO::FETCH_ASSOC);
  }
/////////////////////////////FILTRAR POR LENTE ///////////////////
  public function ordenEnviarLente($lente){
      $conectar = parent::conexion();
      $sql = "select o.id_orden,o.codigo,o.paciente,o.fecha,o.pupilar_od,o.pupilar_oi,o.lente_od,o.lente_oi,o.marca_aro,o.modelo_aro,o.horizontal_aro,o.vertical_aro,o.puente_aro,o.id_usuario,o.observaciones,o.dui,o.estado,o.tipo_lente,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion
      from
      orden_lab as o inner join rx_orden_lab as rx on o.codigo=rx.codigo where o.estado='0' and o.tipo_lente=?  order by o.fecha ASC;";
      $sql=$conectar->prepare($sql);
      $sql->bindValue(1,$lente);
      $sql->execute();
      return $resulta = $sql->fetchAll(PDO::FETCH_ASSOC);
  }
//////////////////////////FILTRAR POR FECHA Y LENTE ///////////////////
public function ordenEnviarFechaLente($inicio,$fin,$lente){
  $conectar = parent::conexion();
  $sql = "select o.id_orden,o.codigo,o.paciente,o.fecha,o.pupilar_od,o.pupilar_oi,o.lente_od,o.lente_oi,o.marca_aro,o.modelo_aro,o.horizontal_aro,o.vertical_aro,o.puente_aro,o.id_usuario,o.observaciones,o.dui,o.estado,o.tipo_lente,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion
      from
      orden_lab as o inner join rx_orden_lab as rx on o.codigo=rx.codigo where o.estado='0' and o.tipo_lente=? and fecha between ? and ?  order by o.fecha ASC;";
  $sql=$conectar->prepare($sql);
  $sql->bindValue(1,$lente);
  $sql->bindValue(2,$inicio);
  $sql->bindValue(3,$fin);
  $sql->execute();
  return $resulta = $sql->fetchAll(PDO::FETCH_ASSOC);
}


public function get_ordenes_enviar_general($instit){
      $conectar = parent::conexion();

      $sql = "select o.institucion,o.id_orden,o.codigo,o.paciente,o.fecha,o.pupilar_od,o.pupilar_oi,o.lente_od,o.lente_oi,o.marca_aro,o.modelo_aro,o.horizontal_aro,o.vertical_aro,o.puente_aro,o.id_usuario,o.observaciones,o.dui,o.estado,o.tipo_lente,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion
      from
      orden_lab as o inner join rx_orden_lab as rx on o.codigo=rx.codigo where o.estado='0'  order by o.fecha ASC;";
      $sql=$conectar->prepare($sql);
      $sql->execute();
      return $resulta = $sql->fetchAll(PDO::FETCH_ASSOC);
    
    
  }

  public function get_ordenes_env($laboratorio,$cat_lente,$inicio,$fin,$tipo_lente){
    $conectar = parent::conexion();
    $sql = "select o.modelo_aro,o.id_orden,o.codigo,o.paciente,o.laboratorio,o.categoria,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,o.id_orden,o.tipo_lente,a.fecha,a.observaciones,o.fecha from rx_orden_lab as rx INNER JOIN orden_lab as o on rx.codigo=o.codigo INNER JOIN acciones_orden as a on o.codigo=a.codigo WHERE o.tipo_lente=? and a.tipo_accion='Envio' and o.estado='1' and o.laboratorio=? and o.categoria=? and o.fecha between ? and ? group by o.id_orden order by o.fecha ASC;";
    $sql=$conectar->prepare($sql);
    $sql->bindValue(1,$tipo_lente);
    $sql->bindValue(2,$laboratorio);
    $sql->bindValue(3,$cat_lente);
    $sql->bindValue(4,$inicio);
    $sql->bindValue(5,$fin);
    $sql->execute();
    return $resulta = $sql->fetchAll(PDO::FETCH_ASSOC);
  }

 ################FILTRAR POR BASE #############
    public function getOrdenesEnvBase($laboratorio,$cat_lente,$instit){
    $conectar = parent::conexion();
    if($instit=="" or $instit=="INABVE"){

      $sql = "select o.institucion,o.modelo_aro,o.estado,o.id_orden,o.codigo,o.paciente,o.laboratorio,o.categoria,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,o.id_orden,o.tipo_lente,o.fecha from rx_orden_lab as rx INNER JOIN orden_lab as o on rx.codigo=o.codigo WHERE o.estado='1' and o.laboratorio=? and o.categoria=?  and (o.institucion='INABVE' OR o.institucion='') order by o.fecha ASC;";
      $sql=$conectar->prepare($sql);
      $sql->bindValue(1,$laboratorio);
      $sql->bindValue(2,$cat_lente);
      $sql->execute();
      return $resulta = $sql->fetchAll(PDO::FETCH_ASSOC);
    }else{
      $sql = "select o.institucion,o.modelo_aro,o.estado,o.id_orden,o.codigo,o.paciente,o.laboratorio,o.categoria,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,o.id_orden,o.tipo_lente,o.fecha from rx_orden_lab as rx INNER JOIN orden_lab as o on rx.codigo=o.codigo WHERE o.estado='1' and o.laboratorio=? and o.categoria=?  and o.institucion='FOPROLYD' order by o.fecha ASC;";
      $sql=$conectar->prepare($sql);
      $sql->bindValue(1,$laboratorio);
      $sql->bindValue(2,$cat_lente);
      $sql->execute();
      return $resulta = $sql->fetchAll(PDO::FETCH_ASSOC);
    }
  }
###################FILTRAR LENTE###############
  public function getOrdenesEnvLente($laboratorio,$tipo_lente,$instit){
    $conectar = parent::conexion();
    if($instit=="" or $instit=="INABVE"){
    $sql = "select o.institucion,o.modelo_aro,o.estado,o.id_orden,o.codigo,o.paciente,o.laboratorio,o.categoria,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,o.id_orden,o.tipo_lente,o.fecha from rx_orden_lab as rx INNER JOIN orden_lab as o on rx.codigo=o.codigo WHERE o.estado='1' and o.laboratorio=? and o.tipo_lente=? and (o.institucion='INABVE' OR o.institucion='') order by o.fecha ASC;";
    $sql=$conectar->prepare($sql);
    $sql->bindValue(1,$laboratorio);
    $sql->bindValue(2,$tipo_lente);
    $sql->execute();
    return $resulta = $sql->fetchAll(PDO::FETCH_ASSOC);
    }else{
      $sql = "select o.institucion,o.modelo_aro,o.estado,o.id_orden,o.codigo,o.paciente,o.laboratorio,o.categoria,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,o.id_orden,o.tipo_lente,o.fecha from rx_orden_lab as rx INNER JOIN orden_lab as o on rx.codigo=o.codigo WHERE o.estado='1' and o.laboratorio=? and o.tipo_lente=? and  o.institucion='FOPROLYD' order by o.fecha ASC;";
    $sql=$conectar->prepare($sql);
    $sql->bindValue(1,$laboratorio);
    $sql->bindValue(2,$tipo_lente);
    $sql->execute();
    return $resulta = $sql->fetchAll(PDO::FETCH_ASSOC);
    }
  }
################## FILTRAR POR BASE Y LENTE #####################
  public function getOrdenesBaseLente($laboratorio,$cat_lente,$tipo_lente,$instit){
    $conectar = parent::conexion();
    if($instit=="" or $instit=="INABVE"){
    $sql = "select o.institucion,o.modelo_aro,o.estado,o.id_orden,o.codigo,o.paciente,o.laboratorio,o.categoria,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,o.id_orden,o.tipo_lente,o.fecha from rx_orden_lab as rx INNER JOIN orden_lab as o on rx.codigo=o.codigo WHERE o.estado='1' and o.laboratorio=? and o.categoria=? and o.tipo_lente=? and (o.institucion='INABVE' OR o.institucion='') order by o.fecha ASC;";
    $sql=$conectar->prepare($sql);
    $sql->bindValue(1,$laboratorio);
    $sql->bindValue(2,$cat_lente);
    $sql->bindValue(3,$tipo_lente);
    $sql->execute();
    return $resulta = $sql->fetchAll(PDO::FETCH_ASSOC);
    }else{
    $sql = "select o.institucion,o.modelo_aro,o.estado,o.id_orden,o.codigo,o.paciente,o.laboratorio,o.categoria,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,o.id_orden,o.tipo_lente,o.fecha from rx_orden_lab as rx INNER JOIN orden_lab as o on rx.codigo=o.codigo WHERE o.estado='1' and o.laboratorio=? and o.categoria=? and o.tipo_lente=? and o.institucion='FOPROLYD' order by o.fecha ASC;";
    $sql=$conectar->prepare($sql);
    $sql->bindValue(1,$laboratorio);
    $sql->bindValue(2,$cat_lente);
    $sql->bindValue(3,$tipo_lente);
    $sql->execute();
    return $resulta = $sql->fetchAll(PDO::FETCH_ASSOC);
    }
  }
#####################FILTRAR PR FECHA#################
  public function ogetOrdenesEnvFechas($laboratorio,$inicio,$hasta){
    $conectar = parent::conexion();
    $sql = "select o.modelo_aro,o.estado,o.id_orden,o.codigo,o.paciente,o.laboratorio,o.categoria,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,o.id_orden,o.tipo_lente,o.fecha from rx_orden_lab as rx INNER JOIN orden_lab as o on rx.codigo=o.codigo WHERE o.estado='1' and o.laboratorio=? and o.fecha between ? and ? order by o.fecha ASC;";
    $sql=$conectar->prepare($sql);
    $sql->bindValue(1,$laboratorio);
    $sql->bindValue(2,$inicio);
    $sql->bindValue(3,$hasta);
    $sql->execute();
    return $resulta = $sql->fetchAll(PDO::FETCH_ASSOC);
  }


  public function get_ordenes_env_general(){
    $conectar = parent::conexion();
    $sql = "select o.modelo_aro,o.id_orden,o.codigo,o.paciente,o.laboratorio,o.categoria,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,o.id_orden,o.tipo_lente,o.fecha,a.observaciones from rx_orden_lab as rx INNER JOIN orden_lab as o on rx.codigo=o.codigo INNER JOIN acciones_orden as a on o.codigo=a.codigo WHERE o.estado='1' group by o.id_orden order by a.id_accion desc";
    $sql=$conectar->prepare($sql);
    $sql->execute();
    return $resulta = $sql->fetchAll(PDO::FETCH_ASSOC);
  }
  
    public function get_ordenes_por_lab($laboratorio){
    $conectar = parent::conexion();
    $sql = "select o.modelo_aro,o.id_orden,o.codigo,o.paciente,o.laboratorio,o.categoria,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,o.id_orden,o.tipo_lente,o.fecha,a.observaciones,o.institucion from rx_orden_lab as rx INNER JOIN orden_lab as o on rx.codigo=o.codigo INNER JOIN acciones_orden as a on o.codigo=a.codigo WHERE o.estado='1'  and laboratorio=? group by o.id_orden order by a.id_accion desc";
    $sql=$conectar->prepare($sql);
    $sql->bindValue(1,$laboratorio);
    $sql->execute();
    return $resulta = $sql->fetchAll(PDO::FETCH_ASSOC);
  }

  ////////////////LENTES ENVIADOS LABORATORIO
    public function getOrdenesEnviadasLab($laboratorio,$cat_lente,$inicio,$fin,$tipo_lente){
    $conectar = parent::conexion();
    $sql = "select o.estado,o.id_orden,o.codigo,o.paciente,o.laboratorio,o.categoria,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,o.id_orden,o.tipo_lente,o.fecha from rx_orden_lab as rx INNER JOIN orden_lab as o on rx.codigo=o.codigo WHERE (o.estado='2' or o.estado='3') and o.tipo_lente=? and o.laboratorio=? and o.categoria=? and o.fecha between ? and ? order by o.fecha ASC;";
    $sql=$conectar->prepare($sql);
    $sql->bindValue(1,$tipo_lente);
    $sql->bindValue(2,$laboratorio);
    $sql->bindValue(3,$cat_lente);
    $sql->bindValue(4,$inicio);
    $sql->bindValue(5,$fin);
    $sql->execute();
    return $resulta = $sql->fetchAll(PDO::FETCH_ASSOC);
  }
  
  public function getEnviosGeneral(){
    $conectar = parent::conexion();
    $sql = "select o.estado,o.id_orden,o.codigo,o.paciente,o.laboratorio,o.categoria,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,o.id_orden,o.tipo_lente,o.fecha from rx_orden_lab as rx INNER JOIN orden_lab as o on rx.codigo=o.codigo  WHERE o.estado='2' or o.estado='3' order by o.fecha DESC;";
    $sql=$conectar->prepare($sql);
    $sql->execute();
    return $resulta = $sql->fetchAll(PDO::FETCH_ASSOC);

  }

  public function enviarOrdenes(){
    $conectar = parent::conexion();
    parent::set_names();
    date_default_timezone_set('America/El_Salvador'); $hoy = date("d-m-Y H:i:s");
    $detalle_envio = array();
    $detalle_envio = json_decode($_POST["arrayEnvio"]);
    $user = $_POST["user"];
    $destino = $_POST["destino"];
    $categoria = $_POST["categoria_len"];
    $accion = "Envio";
    foreach ($detalle_envio as $k => $v) {
      $codigoOrden = $v->id_item;
      /////////////////Validar si existe orden en tabla acciones
      $sql2 = "select codigo from acciones_orden where codigo=? and tipo_accion='Envio';";
      $sql2=$conectar->prepare($sql2);
      $sql2->bindValue(1, $codigoOrden);
      $sql2->execute();
      $resultado = $sql2->fetchAll(PDO::FETCH_ASSOC);
      ############REGISTRAR ACCION#############
      if(is_array($resultado)==true and count($resultado)==0){
        $sql3 = "update orden_lab set estado='1',laboratorio=?,categoria=? where codigo=?;";
        $sql3=$conectar->prepare($sql3);
        $sql3->bindValue(1,$destino);
        $sql3->bindValue(2,$categoria);
        $sql3->bindValue(3,$codigoOrden);
        $sql3->execute();
        ###########################################################
        $sql = "insert into acciones_orden values(null,?,?,?,?,?);";
        $sql=$conectar->prepare($sql);
        $sql->bindValue(1, $hoy);
        $sql->bindValue(2, $user);
        $sql->bindValue(3, $codigoOrden);
        $sql->bindValue(4, $accion);
        $sql->bindValue(5, $destino);
        $sql->execute();
      }
      
    }//////////////FIN FOREACH 

    }//////////fin metodo enviar ordenes

  public function get_ordenes_procesando(){
    $conectar = parent::conexion();      
    $sql = "select o.codigo,o.paciente,o.laboratorio,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,o.id_orden,o.tipo_lente,a.fecha,a.observaciones from rx_orden_lab as rx INNER JOIN orden_lab as o on rx.codigo=o.codigo INNER JOIN acciones_orden as a on o.codigo=a.codigo WHERE a.tipo_accion='Recibido' and o.estado='2';";
    $sql=$conectar->prepare($sql);
    $sql->execute();

    return $resulta = $sql->fetchAll(PDO::FETCH_ASSOC);
  }

  public function enviarLente($codigo,$destino,$usuario){
    $conectar = parent::conexion();
    date_default_timezone_set('America/El_Salvador'); $hoy = date("d-m-Y H:i:s");
    $accion = "Envio";
    $sql3 = "update orden_lab set estado='1',laboratorio=? where codigo=?;";
    $sql3=$conectar->prepare($sql3);
    $sql3->bindValue(1,$destino);
    $sql3->bindValue(2,$codigo);
    $sql3->execute();

    $sql = "insert into acciones_orden values(null,?,?,?,?,?);";
    $sql=$conectar->prepare($sql);
    $sql->bindValue(1, $hoy);
    $sql->bindValue(2, $usuario);
    $sql->bindValue(3, $codigo);
    $sql->bindValue(4, $accion);
    $sql->bindValue(5, $destino);
    $sql->execute();

  }

  public function editarEnvio($codigo,$dest,$cat,$paciente){
    $conectar = parent::conexion();
    $sql3 = "update orden_lab set laboratorio=?,categoria=? where codigo=? and paciente=?;";
    $sql3=$conectar->prepare($sql3);
    $sql3->bindValue(1,$dest);
    $sql3->bindValue(2,$cat);
    $sql3->bindValue(3,$codigo);
    $sql3->bindValue(4,$paciente);
    $sql3->execute();

  }

    public function resetTables(){
    $conectar = parent::conexion();
    parent::set_names();
    date_default_timezone_set('America/El_Salvador'); $hoy = date("d-m-Y H:i:s");
    $accion = "Envio laboratorio";
    $arrayReset = array();
    $arrayReset = json_decode($_POST["array_restart"]);
    $laboratorio = $_POST["laboratorio"];
    $tipo_lente = $_POST["tipo_lente"];
    $base = $_POST["base"];
    $usuario = "Andvas";
    foreach ($arrayReset as $k) {
    $codigoOrden = $k;      
    $sql3 = "update orden_lab set estado='2' where codigo=? and laboratorio=? and tipo_lente=? and categoria=? and estado='1';";
    $sql3=$conectar->prepare($sql3);
    $sql3->bindValue(1,$codigoOrden);
    $sql3->bindValue(2,$laboratorio);
    $sql3->bindValue(3,$tipo_lente);
    $sql3->bindValue(4,$base);

    $sql3->execute();

    $sql = "insert into acciones_orden values(null,?,?,?,?,?);";
    $sql=$conectar->prepare($sql);
    $sql->bindValue(1, $hoy);
    $sql->bindValue(2, $usuario);
    $sql->bindValue(3, $codigoOrden);
    $sql->bindValue(4, $accion);
    $sql->bindValue(5, $laboratorio);
    $sql->execute();

      
    }//////////////FIN FOREACH 

    }//////////fin metodo enviar ordenes

     public function resetTablesPrint(){
    $conectar = parent::conexion();
    parent::set_names();
    date_default_timezone_set('America/El_Salvador'); $hoy = date("d-m-Y H:i:s");
    $accion = "Envio laboratorio - Imprimir";
    $arrayReset = array();
    $arrayReset = json_decode($_POST["array_restart_print"]);
    $usuario = "Andvas";
    foreach ($arrayReset as $k) {
    $codigoOrden = $k;      
    $sql3 = "update orden_lab set estado='3' where codigo=?;";
    $sql3=$conectar->prepare($sql3);
    $sql3->bindValue(1,$codigoOrden);
    $sql3->execute();

    $sql2 = "select laboratorio from orden_lab where codigo=?;";
    $sql2=$conectar->prepare($sql2);
    $sql2->bindValue(1,$codigoOrden);
    $sql2->execute();

    $resultado = $sql2->fetchAll(PDO::FETCH_ASSOC);

    foreach ($resultado as $value) {
      $laboratorio = $value["laboratorio"];
    }


    $sql = "insert into acciones_orden values(null,?,?,?,?,?);";
    $sql=$conectar->prepare($sql);
    $sql->bindValue(1, $hoy);
    $sql->bindValue(2, $usuario);
    $sql->bindValue(3, $codigoOrden);
    $sql->bindValue(4, $accion);
    $sql->bindValue(5, $laboratorio);
    $sql->execute();
      
    }//////////////FIN FOREACH 

    }//////////fin metodo enviar ordenes


    public function getCorrelativoRectificacion(){
      $conectar = parent::conexion();
      parent::set_names();

      $sql = "select codigo_rectifi from rectificacion order by id_rectifi DESC limit 1;";
      $sql=$conectar->prepare($sql);
      $sql->execute();
      return $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);      
    }

     public function registrarRectificacion($motivo,$estado_aro,$usuario,$codigoOrden,$correlativo_rc){
      
      $conectar = parent::conexion();
      parent::set_names();

      date_default_timezone_set('America/El_Salvador');
      $hoy = date("Y-m-d");
      $hora = date(" H:i:s");

      $cr = "select codigo_rectifi from rectificacion where codigo_rectifi = ?;";
      $cr=$conectar->prepare($cr);
      $cr->bindValue(1, $codigoOrden);
      $cr->execute();
     
      if($cr->rowCount() == 0) {
      $codigo_ord = explode(":", $codigoOrden);
      $accion = "Rectificacion";
     // $observaciones = '<i class="fa fa-eye" aria-hidden="true" style="color:blue" onClick="detRecti(\''.$codigoOrden.'\')"></i>';


      $sql = 'insert into rectificacion values(null,?,?,?,?,?,?);';
      $sql=$conectar->prepare($sql);
      $sql->bindValue(1, $correlativo_rc);
      $sql->bindValue(2, $hoy);
      $sql->bindValue(3, $hora);
      $sql->bindValue(4, $usuario);
      $sql->bindValue(5, $motivo);
      $sql->bindValue(6, $estado_aro);
      $sql->execute();
    

      $sql2 = "select*from orden_lab where codigo=?;";
      $sql2 = $conectar->prepare($sql2);
      $sql2->bindValue(1, $codigo_ord[1]);
      $sql2->execute();

      $dataOrden = $sql2->fetchAll(PDO::FETCH_ASSOC);
      //echo json_encode(['data'=> $dataOrden]); exit();
      foreach ($dataOrden as $value) {
          $id_orden = $value["id_orden"];
          $codigo = $value["codigo"];
          $paciente = $value["paciente"];
          $fecha = $value["fecha"];
          $pupilar_od = $value["pupilar_od"];
          $pupilar_oi = $value["pupilar_oi"];
          $lente_od = $value["lente_od"];
          $lente_oi = $value["lente_oi"];
          $id_aro = $value["id_aro"];

          $id_usuario = $value["id_usuario"];
          $observaciones = $value["observaciones"];
          $dui = $value["dui"];
          $estado = $value["estado"];
          $fecha_correlativo = $value["fecha_correlativo"];
          $tipo_lente = $value["tipo_lente"];
          $categoria = $value["categoria"];
          $edad = $value["edad"];
          $usuario_lente = $value["usuario_lente"];
          $ocupacion = $value["ocupacion"];
          $avsc = $value["avsc"];
          $avfinal = $value["avfinal"];
          $avsc_oi = $value["avsc_oi"];
          $avfinal_oi = $value["avfinal_oi"];
          $telefono = $value["telefono"];
          $genero = $value["genero"];
          $color = $value["color"];
          $alto_indice = $value["alto_indice"];
          $patologias = $value["patologias"];
        }

        $laboratorio='';
        $estado = '0';
        $observaciones = 'Rectificacion';
        $sql4 = "insert into detalle_orden_rectificicacion values (null,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);";
          $sql4 = $conectar->prepare($sql4);
          $sql4->bindValue(1, $correlativo_rc);
          $sql4->bindValue(2, $codigo);
          $sql4->bindValue(3, $paciente);
          $sql4->bindValue(4, $fecha);
          $sql4->bindValue(5, $pupilar_od);
          $sql4->bindValue(6, $pupilar_oi);
          $sql4->bindValue(7, $lente_od);
          $sql4->bindValue(8, $lente_oi);
          $sql4->bindValue(9, $id_usuario);
          $sql4->bindValue(10, $observaciones);
          $sql4->bindValue(11, $dui);  
          $sql4->bindValue(12, $estado); 
          $sql4->bindValue(13, $fecha_correlativo);
          $sql4->bindValue(14, $tipo_lente);
          $sql4->bindValue(15, $laboratorio);
          $sql4->bindValue(16, $categoria);
          $sql4->bindValue(17, $edad);
          $sql4->bindValue(18, $usuario_lente);
          $sql4->bindValue(19, $ocupacion);
          $sql4->bindValue(20, $avsc);
          $sql4->bindValue(21, $avfinal);
          $sql4->bindValue(22, $avsc_oi);
          $sql4->bindValue(23, $avfinal_oi);
          $sql4->bindValue(24, $telefono);
          $sql4->bindValue(25, $genero);
          $sql4->bindValue(26, $color);
          $sql4->bindValue(27, $alto_indice);
          $sql4->bindValue(28, $patologias);

          $sql4->execute();
          
          $sql7 = "insert into acciones_orden values(null,?,?,?,?,?,?);";
          $sql7 = $conectar->prepare($sql7);
          $sql7->bindValue(1, $hoy."  ".$hora);
          $sql7->bindValue(2, $usuario);
          $sql7->bindValue(3, $dui);
          $sql7->bindValue(4, $accion);
          $sql7->bindValue(5, $motivo);
          $sql7->bindValue(6, $_SESSION['sucursal']);
          $sql7->execute();


          $sql5 = "select*from rx_orden_lab where codigo = ?;";
          $sql5 = $conectar->prepare($sql5);
          $sql5->bindValue(1, $codigo_ord[1]);
          $sql5->execute();
          $dataOrdenRx = $sql5->fetchAll(PDO::FETCH_ASSOC);
          /* echo json_encode(['data'=> $dataOrdenRx]); exit();
          exit(); */
          foreach ($dataOrdenRx as $key) {
              $codigo = $key["codigo"];
              $od_esferas = $key["od_esferas"];
              $od_cilindros = $key["od_cilindros"];
              $od_eje = $key["od_eje"];
              $od_adicion = $key["od_adicion"];
              $oi_esferas = $key["oi_esferas"];
              $oi_cilindros = $key["oi_cilindros"];
              $oi_eje = $key["oi_eje"];
              $oi_adicion = $key["oi_adicion"];
          }

          $sql6 = "insert into rx_det_orden_recti values(null,?,?,?,?,?,?,?,?,?,?);";
          $sql6 = $conectar->prepare($sql6);
          $sql6->bindValue(1, $correlativo_rc);
          $sql6->bindValue(2, $codigo);
          $sql6->bindValue(3, $od_esferas);
          $sql6->bindValue(4, $od_cilindros);
          $sql6->bindValue(5, $od_eje);
          $sql6->bindValue(6, $od_adicion);
          $sql6->bindValue(7, $oi_esferas);
          $sql6->bindValue(8, $oi_cilindros);
          $sql6->bindValue(9, $oi_eje);
          $sql6->bindValue(10, $oi_adicion);
          $sql6->execute();          
      }

      if ($sql->rowCount() > 0 and $sql4->rowCount() > 0 and $sql6->rowCount() > 0){
        $sql9 = 'update orden_lab set estado="0" where dui=?';
        $sql9 = $conectar->prepare($sql9);
        $sql9->bindValue(1, $dui);
        $sql9->execute();
        echo "Insert!";
      }elseif($cr->rowCount() == 0){
        echo "Error!";
      }
}


public function marcarRectificacion(){
  $conectar = parent::conexion();
  parent::set_names();
  $sql='update orden_lab set observaciones=? where dui=?;';
  $sql = $conectar->prepare($sql);
  $sql->bindValue(1, $_POST["observaciones_orden"]." - <<RECTIFICACION>>");
  $sql->bindValue(2, $_POST["dui"]);
  $sql->execute(); 

}
/*-------------- GET ACCIONES ORDEN ----------------------*/

/*-------------------LISTAR DETALLE RECTIFICACIONES ----------------*/
public function getTablasRectificaciones($codigoOrden){

  $conectar = parent::conexion();
  parent::set_names();

  $sql = "select o.id_det_recti,o.codigo_recti,o.fecha,o.paciente,o.dui,o.edad,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,o.pupilar_od,o.pupilar_oi,o.lente_od,o.lente_oi,o.pupilar_od,o.pupilar_oi,o.lente_od,o.lente_oi,o.avsc,o.avfinal,o.codigo_orden from detalle_orden_rectificicacion as o inner join rx_det_orden_recti as rx on o.codigo_orden=rx.codigo where o.codigo_orden=? GROUP BY o.id_det_recti ORDER by o.id_det_recti ASC;";
  $sql = $conectar->prepare($sql);
  $sql->bindValue(1, $codigoOrden);
  $sql->execute();
  $resultado=$sql->fetchAll(PDO::FETCH_ASSOC);
  $tabla = "";
  $cont = 0; 
  foreach ($resultado as $key) {
    $correlativo_rc = $key["codigo_recti"];

    $sql2 = "select *from rectificacion where codigo_rectifi=?;";
    $sql2 = $conectar->prepare($sql2);
    $sql2->bindValue(1, $correlativo_rc);
    $sql2->execute();
    $correlativo=$sql2->fetchAll(PDO::FETCH_ASSOC);

    foreach ($correlativo as $cr) {
      $motivo = $cr["motivo"];
      $est_aro = $cr["estado_aro"];
      $fecha = date("d-m-Y",strtotime($cr["fecha"]))." ".$cr["hora"];
    }    
    $cont == 0 ? $titulo = "<b>ORDEN ORIGINAL</b>" : $titulo="RECTIFICACION";
    $cont%2 == 0 ? $background = "" : $background="";

    $tabla .= "
      <table width='100%'  class='table2' style='style:margin-top:0px'>
      <tr>
      <td colspan='100' class='stilot1' style='text-align: left;padding:2px'><b>Motivo: </b>".$motivo."</td>     
    </tr>

    <tr> 
      <td colspan='70' class='stilot1' style='text-align: left;padding:2px'><b>Estado aro: </b>".$est_aro."</td>
      <td colspan='30' class='stilot1' style='text-align: left;padding:2px'><b>Fecha rectificacion: </b>".$fecha."</td>
    </tr>
    
    <tr> <td colspan='100' class='' style='font-size:10px;color:white'>|</td></tr>
    <tr> <td colspan='100' class='' style='font-size:10px;color:gray;text-align:center'>------------- * --------------</td>
    <tr> <td colspan='100' class='' style='font-size:10px;color:white'>|</td></tr></tr>
    <tr>
      <td class='stilot1' colspan='100' style='text-align:center;background:#177694;color:white'><b>".$titulo."</b></td>
    </tr>
      <tr>
      <td class='stilot1' colspan='60' style='text-align:center'>".$key["codigo_orden"]."</td>
      
      <td class='stilot1' colspan='40' style='text-align:center'><b>Fecha</b> ".date("d-m-Y",strtotime($key["fecha"]))."</td>
      </tr>
            <tr style='height: 14px'>
        <td class='stilot1 encabezado' colspan='65'><b style='padding: 0px'>Paciente:</b></td>
        <td class='stilot1 encabezado' colspan='20'><b style='padding: 0px'>DUI</b></td>
        <td class='stilot1 encabezado' colspan='15'><b style='padding: 0px'>Edad:</b></td>
      </tr>
      <tr>
        <td class='stilot1' colspan='65' style='text-transform:uppercase;'>".$key["paciente"]."</td>
        <td class='stilot1' colspan='20'>".$key["dui"]."</td>
        <td class='stilot1' colspan='15'>".$key["edad"]."</td>
      </tr>
      <tr>
        <td colspan='100' class='stilot1 encabezado' style='text-align: center'><b>Rx final</b></td>
      </tr>
      <tr>
      <th style='text-align: center;' colspan='20' class='stilot1'><b>OJO</b></th>
        <th style='text-align: center;' colspan='20' class='stilot1'><b>Esfera</b></th>
        <th style='text-align: center;' colspan='20' class='stilot1'><b>Cilindro</b></th>
        <th style='text-align: center;' colspan='20' class='stilot1'><b>Eje</b></th>
        <th style='text-align: center;' colspan='20' class='stilot1'><b>Adición</b></th>
      </tr>
      <tr>
        <td colspan='20' class='stilot1'><b>OD</b></td>
        <td colspan='20' class='stilot1'>".$key["od_esferas"]."</td>
        <td colspan='20' class='stilot1'>".$key["od_cilindros"]."</td>
        <td colspan='20' class='stilot1'>".$key["od_eje"]."</td>
        <td colspan='20' class='stilot1'>".$key["od_adicion"]."</td>
      </tr>
    <tr>
      <td colspan='20' class='stilot1'><b>OI</b></td>
      <td colspan='20' class='stilot1'>".$key["oi_esferas"]."</td>
      <td colspan='20' class='stilot1'>".$key["oi_cilindros"]."</td>
      <td colspan='20' class='stilot1'>".$key["oi_eje"]."</td>
      <td colspan='20' class='stilot1'>".$key["oi_adicion"]."</td>
    </tr>
    <tr>
    <td colspan='30' class='stilot1 encabezado' style='height:10px'>Dist. Pupilar</td>
    <td colspan='30' class='stilot1 encabezado' style='height:10px'>Altura de lente</td>
    <td colspan='40' class='stilot1 encabezado' style='height:10px'>Agudeza visual</td>
    </tr>
    
    <tr>
      <td colspan='15' class='stilot1'><b>OD</b></td>
      <td colspan='15' class='stilot1'><b>OI</b></td>
      <td colspan='15' class='stilot1'><b>OD</b></td>
      <td colspan='15' class='stilot1'><b>OI</b></td>
      <td colspan='20' class='stilot1'><b>AVsc</b></td>
      <td colspan='20' class='stilot1'><b>AVfinal</b></td>
    </tr>
    
    <tr>
      <td colspan='15' class='stilot1'>".$key["pupilar_od"]." mm</td>
      <td colspan='15' class='stilot1'>".$key["pupilar_oi"]." mm</td>
      <td colspan='15' class='stilot1'>".$key["lente_od"]." mm</td>
      <td colspan='15' class='stilot1'>".$key["lente_oi"]." mm</td>
      <td colspan='20' class='stilot1'>".$key["avsc"]."</td>
      <td colspan='20' class='stilot1'>".$key["avfinal"]."</td>
    </tr>
    <tr>
      <td colspan='100' class='stilot1 encabezado'><b>ARO</b></td>
    </tr>
    
    <tr>
      <td colspan='15' class='stilot1'><b>Mod.</b></td>
      <td colspan='30' class='stilot1'><b>Marca</b></td>
      <td colspan='15' class='stilot1'><b>Horiz.</b></td>
      <td colspan='20' class='stilot1'><b>Vertical</b></td>
      <td colspan='20' class='stilot1'><b>Puente</b></td>
    </tr>
    </table><br>
    ";
    $cont++;

  }

  
echo $tabla;

}

public function getDetOrdenActRec($codigoOrden){

  $conectar = parent::conexion();
  parent::set_names();

  $sql = "select o.fecha,o.paciente,o.dui,o.edad,rx.od_esferas,rx.od_cilindros,rx.od_eje,rx.od_adicion,rx.oi_esferas,rx.oi_cilindros,rx.oi_eje,rx.oi_adicion,o.pupilar_od,o.pupilar_oi,o.lente_od,o.lente_oi,o.pupilar_od,o.pupilar_oi,o.lente_od,o.lente_oi,o.avsc,o.avfinal,o.tipo_lente,o.codigo from orden_lab as o inner join rx_orden_lab as rx on o.codigo=rx.codigo where o.codigo=?;";
  $sql = $conectar->prepare($sql);
  $sql->bindValue(1, $codigoOrden);
  $sql->execute();
  $resultado=$sql->fetchAll(PDO::FETCH_ASSOC);
  $tabla = "";
  
  foreach ($resultado as $key) {
   
    $tabla .= "<table width='100%' class='table-striped'>
      <tr>
      <td class='stilot1' colspan='100' style='text-align:center;background:#13263b;color:white'><b>ORDEN ACTUAL</b></td>
      </tr>
      <tr>
      <td class='stilot1' colspan='30' style='text-align:center'>".$key["codigo"]."</td>
      <td class='stilot1' colspan='30' style='text-align:center'><b>Lente:</b> ".$key["tipo_lente"]."</td>
      <td class='stilot1' colspan='40' style='text-align:center'><b>Fecha</b> ".date("d-m-Y",strtotime($key["fecha"]))."</td>
      </tr>
            <tr style='height: 14px'>
        <td class='stilot1 encabezado' colspan='60'><b style='padding: 0px'>Paciente:</b></td>
        <td class='stilot1 encabezado' colspan='25'><b style='padding: 0px'>DUI</b></td>
        <td class='stilot1 encabezado' colspan='15'><b style='padding: 0px'>Edad:</b></td>
      </tr>
      <tr>
        <td class='stilot1' colspan='60' style='text-transform:uppercase;'>".$key["paciente"]."</td>
        <td class='stilot1' colspan='25'>".$key["dui"]."</td>
        <td class='stilot1' colspan='15'>".$key["edad"]."</td>
      </tr>
      <tr>
        <td colspan='100' class='stilot1 encabezado' style='text-align: center'><b>Rx final</b></td>
      </tr>
      <tr>
      <th style='text-align: center;' colspan='20' class='stilot1'><b>OJO</b></th>
        <th style='text-align: center;' colspan='20' class='stilot1'><b>Esfera</b></th>
        <th style='text-align: center;' colspan='20' class='stilot1'><b>Cilindro</b></th>
        <th style='text-align: center;' colspan='20' class='stilot1'><b>Eje</b></th>
        <th style='text-align: center;' colspan='20' class='stilot1'><b>Adición</b></th>
      </tr>
      <tr>
        <td colspan='20' class='stilot1'><b>OD</b></td>
        <td colspan='20' class='stilot1'>".$key["od_esferas"]."</td>
        <td colspan='20' class='stilot1'>".$key["od_cilindros"]."</td>
        <td colspan='20' class='stilot1'>".$key["od_eje"]."</td>
        <td colspan='20' class='stilot1'>".$key["od_adicion"]."</td>
      </tr>
    <tr>
      <td colspan='20' class='stilot1'><b>OI</b></td>
      <td colspan='20' class='stilot1'>".$key["oi_esferas"]."</td>
      <td colspan='20' class='stilot1'>".$key["oi_cilindros"]."</td>
      <td colspan='20' class='stilot1'>".$key["oi_eje"]."</td>
      <td colspan='20' class='stilot1'>".$key["oi_adicion"]."</td>
    </tr>
    <tr>
    <td colspan='30' class='stilot1 encabezado' style='height:10px'>Dist. Pupilar</td>
    <td colspan='30' class='stilot1 encabezado' style='height:10px'>Altura de lente</td>
    <td colspan='40' class='stilot1 encabezado' style='height:10px'>Agudeza visual</td>
    </tr>
    
    <tr>
      <td colspan='15' class='stilot1'><b>OD</b></td>
      <td colspan='15' class='stilot1'><b>OI</b></td>
      <td colspan='15' class='stilot1'><b>OD</b></td>
      <td colspan='15' class='stilot1'><b>OI</b></td>
      <td colspan='20' class='stilot1'><b>AVsc</b></td>
      <td colspan='20' class='stilot1'><b>AVfinal</b></td>
    </tr>
    
    <tr>
      <td colspan='15' class='stilot1'>".$key["pupilar_od"]." mm</td>
      <td colspan='15' class='stilot1'>".$key["pupilar_oi"]." mm</td>
      <td colspan='15' class='stilot1'>".$key["lente_od"]." mm</td>
      <td colspan='15' class='stilot1'>".$key["lente_oi"]." mm</td>
      <td colspan='20' class='stilot1'>".$key["avsc"]."</td>
      <td colspan='20' class='stilot1'>".$key["avfinal"]."</td>
    </tr>
    <tr>
      <td colspan='100' class='stilot1 encabezado'><b>ARO</b></td>
    </tr>
    
    <tr>
      <td colspan='15' class='stilot1'><b>Mod.</b></td>
      <td colspan='30' class='stilot1'><b>Marca</b></td>
      <td colspan='15' class='stilot1'><b>Horiz.</b></td>
      <td colspan='20' class='stilot1'><b>Vertical</b></td>
      <td colspan='20' class='stilot1'><b>Puente</b></td>
    </tr>

    </table
    ";

  }

  
echo $tabla;

}
public function listar_rectificaciones(){
  $conectar = parent::conexion();
  parent::set_names();

  $sql = 'select r.id_rectifi,r.codigo_rectifi,r.fecha,r.hora,r.usuario,d.paciente,d.codigo_orden,r.motivo from rectificacion as r inner join detalle_orden_rectificicacion as d on r.codigo_rectifi = d.codigo_recti order by r.id_rectifi DESC;';
  $sql=$conectar->prepare($sql);
  $sql->execute();
  return $resulta = $sql->fetchAll(PDO::FETCH_ASSOC);
}

public function get_estadisticas_orden($inicio,$hasta){
  $conectar = parent::conexion();
  parent::set_names();

  $sql = "select u.nombres,COUNT(o.codigo) as cantidad from orden_lab as o INNER JOIN usuarios as u on o.id_usuario=u.id_usuario where STR_TO_DATE(LEFT(fecha_correlativo,LOCATE(' ',fecha_correlativo)),'%d-%m-%Y') BETWEEN ? AND ? GROUP by u.id_usuario;";
  $sql=$conectar->prepare($sql);
  $sql->bindValue(1, $inicio);
  $sql->bindValue(2, $hasta);
  $sql->execute();
  return $resulta = $sql->fetchAll(PDO::FETCH_ASSOC);
}

public function updateOrdenact($codigo_lenti,$codigo_veteranos,$categoria){
  $conectar = parent::conexion();
  parent::set_names();

  $edit_ord = "update orden_lab set estado='1',codigo_lenti=?,laboratorio='Lenti',categoria=? where codigo=?";
  $edit_ord = $conectar->prepare($edit_ord);
  $edit_ord->bindValue(1, $codigo_lenti);
  $edit_ord->bindValue(2, $categoria);
  $edit_ord->bindValue(3, $codigo_veteranos);
  $edit_ord->execute();
}
public function agregarHistorial($codigo,$user){
  
  $conectar = parent::conexion();
  parent::set_names();

  $accion = "Traslado de orden a Lenti";
  date_default_timezone_set('America/El_Salvador'); $hoy = date("d-m-Y H:i:s");
  $sql7 = "insert into acciones_orden values(null,?,?,?,?,?);";
  $sql7 = $conectar->prepare($sql7);
  $sql7->bindValue(1, $hoy);
  $sql7->bindValue(2, $user);
  $sql7->bindValue(3, $codigo);
  $sql7->bindValue(4, $accion);
  $sql7->bindValue(5, $accion);
  $sql7->execute();


}

public function getOrdenesSucursalDia($sucursal){
   $permisos =  $_SESSION['names_permisos'];
  $listar = in_array('listar_despachos_lab_gen',$permisos);
  if($listar){
   $query = "select dui,institucion as sector,paciente,dui,fecha from orden_lab where estado='0';";
  }else{
    $query = "select dui,institucion as sector,paciente,dui,fecha from orden_lab where sucursal=?  and estado='0';";   
  }
  $conectar = parent::conexion();
  parent::set_names();
  $sql = $query;
  $sql = $conectar->prepare($sql);
  $sql->bindValue(1, $sucursal);
  $sql->execute();
  return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
}

public function comprobar_exit_DUI_pac($dui_pac){
  $conectar = parent::conexion();
  parent::set_names();

  $sql = "select dui from orden_lab where dui=?";
  $sql = $conectar->prepare($sql);
  $sql->bindValue(1,$dui_pac);
  $sql->execute();
  return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

}
/**
 * 
 * GET ACCIONES ORDENES
*/
public function getHistorialOrden($codigo,$dui_paciente){
  $conectar = parent::conexion();
  parent::set_names();
  //Validacion de permiso
  //Si es admin vera el historial completo, caso contrario no
  //('Digitación orden','Recibido en Laboratorio','Recibir en optica','Impresion de Acta','')
  if($_SESSION['categoria'] == "Admin"){
    $sql = "select a.sucursal,a.id_accion,u.nombres,a.codigo,a.fecha,a.tipo_accion,a.observaciones from usuarios as u inner join acciones_orden as a on u.usuario=a.usuario where a.codigo=? or a.codigo=?";
  }else{
    $sql = "select a.sucursal,a.id_accion,u.nombres,a.codigo,a.fecha,a.tipo_accion,a.observaciones from usuarios as u inner join acciones_orden as a on u.usuario=a.usuario where a.codigo=? and a.tipo_accion IN ('Digitación orden','Recibido en Laboratorio','Recibir en optica','Impresion de Acta','Finalizada orden Lab','Ingreso a Laboratorio LENTI','Ingreso a laboratorio orden') or a.codigo=?";
  }
  $sql = $conectar->prepare($sql);
  $sql->bindValue(1,$codigo);
  $sql->bindValue(2,$dui_paciente);
  $sql->execute();
  return $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
}

public function buscaOrdenLicitacion1($dui){
  $conectar = parent::conexion_inabve1();
  parent::set_names();
  $sql = "select*from orden_lab where dui=?";
  $sql = $conectar->prepare($sql);
  $sql->bindValue(1,$dui);
  $sql->execute();
  $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
  if(count($resultado)>0){
    $msj = ["resp"=>"error","id_orden"=>$resultado[0]["id_orden"],"fecha"=>date("d-m-Y",strtotime($resultado[0]["fecha"]))];
  }else{
    $msj =["resp"=>"ok"];
  }
  echo json_encode($msj);
}
  /**
   * VALIDACION PARA ESTADO DE LA ORDEN EN EDITAR
   * 
   */
  public function get_data_orden_estado($codigo_orden){
    $conectar = parent::conexion();
    parent::set_names();
    $sql = "select estado from orden_lab where codigo=?";
    $sql = $conectar->prepare($sql);
    $sql->bindValue(1,$codigo_orden);
    $sql->execute();
    $result = $sql->fetchAll(PDO::FETCH_ASSOC);
    return $result[0]['estado'];
  }
  //@RETURN PREFIJO DE LA SUCURSAL
  public function get_prefijo_sucursal($sucursal){
    $sucursales_array = [
      "Valencia" => "VLC",
      "Metrocentro" => "MCT",
      "Cascadas" => "CCD",
      "Santa Ana" => "SAA",
      "Chalatenango" => "CTG",
      "Ahuachapan" => "ACP",
      "Sonsonate" => "SST",
      "Ciudad Arce" => "CAC",
      "Opico" => "OPC",
      "Apopa" => "APP",
      "San Vicente Centro" => "SVC",
      "San Vicente" => "SVT",
      "Gotera" => "GTR",
      "San Miguel" => "SMG",
      "Usulutan" => "UST",
      "inabve" => "INB",
      "San Miguel AV PLUS" => "ASM"
    ];

      return $sucursales_array[$sucursal];
  }
  
  //FUNCTION PARA REPORTE DE LENTES
  public function get_reporte_lentes($data){
    $conectar = parent::conexion();
    parent::set_names();
    //SQL PRINCIPAL
    $sql_main = "SELECT o.sucursal,o.tipo_lente, COUNT(*) as cantidad, o.color, o.alto_indice FROM `orden_lab_bk` as o ";
    $params = []; //Parametros para filtrar informacion
    if($data['desdeFecha'] !="" and $data['hastaFecha'] != ""){
      if($data['sucursal'] == ""){
        //Concat filtrado solo por rango de fecha
        $sql_main .= " WHERE fecha BETWEEN ? AND ? GROUP BY o.tipo_lente,o.color,o.alto_indice,o.sucursal";
        $params = [$data['desdeFecha'],$data['hastaFecha']];
      }else{
        //Concat filtrado rango de fechas y sucursal
        $sql_main .= " WHERE fecha BETWEEN ? AND ? AND o.sucursal=? GROUP BY o.tipo_lente,o.color,o.alto_indice,o.sucursal";
        $params = [$data['desdeFecha'],$data['hastaFecha'],$data['sucursal']];
      }
    }else if($data['sucursal'] != ""){
      //Concat solo Agrupaciones de orden
      $sql_main .= "WHERE o.sucursal=? GROUP BY o.tipo_lente,o.alto_indice,o.color,o.sucursal";
      $params = [$data['sucursal']];
    }else{
      $sql_main .= "GROUP BY o.tipo_lente,o.alto_indice,o.color,o.sucursal";
    }
    $stmt = $conectar->prepare($sql_main);
    //Comprobar si array contiene parametros
    if(count($params) > 0){
      $stmt->execute($params);
    }else{
      $stmt->execute();
    }
    return $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  
  public function get_reporte_lentes_resumen($data){
    $conectar = parent::conexion();
    parent::set_names();
    //SQL PRINCIPAL
    //Filtrar
    if($data['desde'] != "" && $data['hasta'] != "" && $data['estadoOrdenes'] != ""){
      $sql = "SELECT o.sucursal,o.tipo_lente, COUNT(*) as cantidad, o.color, o.alto_indice FROM orden_lab as o WHERE o.fecha BETWEEN ? and ? GROUP BY o.tipo_lente,o.alto_indice,o.color order by o.tipo_lente desc;";
      //Validation interface
      if($data['estadoOrdenes'] == "Entregados"){
        //SQL para filtrar por estado = 6 Actas entregadas
        //$sql = "SELECT o.sucursal,o.tipo_lente, COUNT(DISTINCT o.codigo) as cantidad, o.color, o.alto_indice FROM orden_lab as o INNER JOIN acciones_orden as ao on o.codigo=ao.codigo WHERE ao.fecha BETWEEN ? and ? and o.estado='6' and ao.tipo_accion='Impresion de Acta' GROUP BY o.tipo_lente,o.alto_indice,o.color order by o.tipo_lente desc;";
        //new sql
        $sql = "SELECT o.sucursal,o.tipo_lente, COUNT(DISTINCT o.codigo) as cantidad, o.color, o.alto_indice FROM orden_lab as o inner join actas as a on o.dui=a.dui_acta WHERE DATE_FORMAT(STR_TO_DATE(a.fecha_impresion, '%d-%m-%Y'), '%Y-%m-%d') BETWEEN ? and ? GROUP BY o.tipo_lente,o.alto_indice,o.color order by o.tipo_lente desc;";
      }else{
          
      }
      $stmt = $conectar->prepare($sql);
      $stmt->bindValue(1,$data['desde']);
      $stmt->bindValue(2,$data['hasta']);
      $stmt->execute();
      return $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }elseif($data['estadoOrdenes'] == "Entregados"){
      /*$sql_main = "SELECT o.sucursal,o.tipo_lente, COUNT(*) as cantidad, o.color, o.alto_indice FROM orden_lab as o INNER JOIN acciones_orden as ao on o.codigo=ao.codigo WHERE o.estado='6' and ao.tipo_accion='Impresion de Acta' GROUP BY o.tipo_lente,o.alto_indice,o.color order by o.tipo_lente desc;";
      $stmt = $conectar->prepare($sql_main);
      $stmt->execute();
      return $data = $stmt->fetchAll(PDO::FETCH_ASSOC);*/
    }else{
      $sql_main = "SELECT o.sucursal,o.tipo_lente, COUNT(*) as cantidad, o.color, o.alto_indice FROM orden_lab as o GROUP BY o.tipo_lente,o.alto_indice,o.color order by o.tipo_lente desc;";
      $stmt = $conectar->prepare($sql_main);
      $stmt->execute();
      return $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
 }
  public function get_description_lente($row){
    //Validacion para descripcion lente
    $data_lente = array();
    if($row['tipo_lente'] == "Visión Sencilla" and $row['alto_indice'] == "No" and $row['color'] == "Blanco"){
      $descripcion ="LENTE ".strtoupper($row['tipo_lente'])." ".strtoupper($row["color"])." (HASTA +/- 4.00 DIOPTRÍAS)";
      $data_lente = [
        'descripcion' => $descripcion,
        'factura' => '2724'
      ];

    }else if($row['tipo_lente'] == "Visión Sencilla" and $row['alto_indice'] == "No" and $row['color'] == "Photocromatico"){
      $descripcion ="LENTE ".strtoupper($row['tipo_lente'])." FOTOSENSIBLE";
      $data_lente = [
        'descripcion' => $descripcion,
        'factura' => '1273'
      ];
      
    }else if($row['tipo_lente'] == "Visión Sencilla" and $row['alto_indice'] == "Si" and $row['color'] == "Blanco"){
      $descripcion ="LENTE ".strtoupper($row['tipo_lente'])." ".strtoupper($row["color"])." (MAYOR A +/- 4.00 DIOPTRÍAS)";
        $data_lente = [
        'descripcion' => $descripcion,
        'factura' => '358'
      ];

    }else if($row['tipo_lente'] == "Visión Sencilla" and $row['alto_indice'] == "Si" and $row['color'] == "Photocromatico"){
      $descripcion ="LENTE ".strtoupper($row['tipo_lente'])." "."FOTOSENSIBLE (MAYOR A +/- 4.00 DIOPTRÍAS)";
        $data_lente = [
        'descripcion' => $descripcion,
        'factura' => '246'
      ];
      

    }else if($row['tipo_lente'] == "Flaptop" and $row['alto_indice'] == "No" and $row['color'] == "Blanco"){
      $descripcion ="LENTE BIFOCAL ".strtoupper($row["color"])." (HASTA +/- 4.00 DIOPTRÍAS)";
        $data_lente = [
        'descripcion' => $descripcion,
        'factura' => '8820'
      ];

    }else if($row['tipo_lente'] == "Flaptop" and $row['alto_indice'] == "No" and $row['color'] == "Photocromatico"){
      $descripcion ="LENTE BIFOCAL FOTOSENSIBLE";
        $data_lente = [
        'descripcion' => $descripcion,
        'factura' => '5375'
      ];

    }else if($row['tipo_lente'] == "Flaptop" and $row['alto_indice'] == "Si" and $row['color'] == "Blanco"){
      $descripcion ="LENTE BIFOCAL ".strtoupper($row['color'])." (MAYOR A +/- 4.00 DIOPTRÍAS)";
        $data_lente = [
        'descripcion' => $descripcion,
        'factura' => '675'
      ];

    }else if($row['tipo_lente'] == "Flaptop" and $row['alto_indice'] == "Si" and $row['color'] == "Photocromatico"){
      $descripcion ="LENTE BIFOCAL FOTOSENSIBLE (MAYOR A +/- 4.00 DIOPTRÍAS)";
        $data_lente = [
        'descripcion' => $descripcion,
        'factura' => '450'
      ];


    }else if($row['tipo_lente'] == "Progresive" and $row['alto_indice'] == "No" and $row['color'] == "Blanco"){
      $descripcion ="LENTE ".strtoupper($row['tipo_lente'])." BLANCO GAMA INTERMEDIA CORREDOR AMPLIO (HASTA +/- 4.00 DIOPTRÍAS)";
        $data_lente = [
        'descripcion' => $descripcion,
        'factura' => '5444'
      ];

    }else if($row['tipo_lente'] == "Progresive" and $row['alto_indice'] == "No" and $row['color'] == "Photocromatico"){
      $descripcion ="LENTE ".strtoupper($row['tipo_lente'])." FOTOSENSIBLE DE GAMA INTERMEDIA CORREDOR AMPLIO (HASTA +/- 4.00 DIOPTRÍAS)";
        $data_lente = [
        'descripcion' => $descripcion,
        'factura' => '3114'
      ];

    }else if($row['tipo_lente'] == "Progresive" and $row['alto_indice'] == "Si" and $row['color'] == "Blanco"){
      $descripcion ="LENTE ".strtoupper($row['tipo_lente'])." BLANCO INTERMEDIA CORREDOR AMPLIO (MAYOR A +/- 4.00 DIOPTRÍAS)";
        $data_lente = [
        'descripcion' => $descripcion,
        'factura' => '589'
      ];

    }else if($row['tipo_lente'] == "Progresive" and $row['alto_indice'] == "Si" and $row['color'] == "Photocromatico"){
      $descripcion ="LENTE ".strtoupper($row['tipo_lente'])." FOTOSENSIBLE (MAYOR A +/- 4.00 DIOPTRÍAS)";
        $data_lente = [
        'descripcion' => $descripcion,
        'factura' => '423'
      ];
    }
    return $data_lente;
  }
  
}//Fin de la Clase

