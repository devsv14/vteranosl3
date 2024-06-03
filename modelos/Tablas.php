<?php

require_once("../config/conexion.php");
//require_once('../vistas/side_bar.php');
class Tablas extends Conectar{

	public function flaptop_progresive($inicio,$fin,$laboratorio,$tipo_lente,$base){
    $conectar=parent::conexion();
    parent::set_names();
    $html = '<thead class="style_th bg-dark" style="color: black">
           <th>Esf/Add</th>
           <th>1.00</th>
           <th>1.25</th>
           <th>1.50</th>
           <th>1.75</th>
           <th>2.00</th>
           <th>2.25</th>
           <th>2.05</th>
           <th>2.75</th>
           <th>3.00</th>
         </thead>';

$sql="select o.codigo,rx.od_esferas,rx.od_adicion,rx.oi_esferas,rx.oi_adicion from orden_lab as o INNER JOIN rx_orden_lab as rx on o.codigo=rx.codigo where laboratorio=? and o.estado='1' and o.categoria=? and  o.tipo_lente=? and fecha between ? and ?;";
        $sql=$conectar->prepare($sql);
        $sql->bindValue(1,$laboratorio);
        $sql->bindValue(2,$base);
        $sql->bindValue(3,$tipo_lente);
        $sql->bindValue(4,$inicio);
        $sql->bindValue(5,$fin);
        
    $sql->execute();
    return $resultado=$sql->fetchAll(PDO::FETCH_ASSOC);    

}

public function visionSencilla($inicio,$fin,$laboratorio,$tipo_lente,$base){
	$conectar=parent::conexion();
    parent::set_names();
    $sql="select o.dui,rx.codigo,rx.od_esferas,rx.od_cilindros,rx.oi_esferas,rx.oi_cilindros from orden_lab as o INNER JOIN rx_orden_lab as rx on o.codigo=rx.codigo where laboratorio=? and o.estado='1' and o.tipo_lente='Visión Sencilla' and o.categoria=? and fecha between ? and ?;";
        $sql=$conectar->prepare($sql);
        $sql->bindValue(1,$laboratorio);
        $sql->bindValue(2,$base);
        $sql->bindValue(3,$inicio);
        $sql->bindValue(4,$fin);
        
    $sql->execute();
    return $resultado=$sql->fetchAll(PDO::FETCH_ASSOC);    
}

public function flaptop_progresiveEnviar($inicio,$fin,$laboratorio,$tipo_lente,$base){
    $conectar=parent::conexion();
    parent::set_names();
    $html = '<thead class="style_th bg-dark" style="color: black">
           <th>Esf/Add</th>
           <th>1.00</th>
           <th>1.25</th>
           <th>1.50</th>
           <th>1.75</th>
           <th>2.00</th>
           <th>2.25</th>
           <th>2.05</th>
           <th>2.75</th>
           <th>3.00</th>
         </thead>';

  $sql="select o.codigo,rx.od_esferas,rx.od_adicion,rx.oi_esferas,rx.oi_adicion from orden_lab as o INNER JOIN rx_orden_lab as rx on o.codigo=rx.codigo where laboratorio=? and o.estado='2' and o.categoria=? and  o.tipo_lente=? and fecha between ? and ?;";
        $sql=$conectar->prepare($sql);
        $sql->bindValue(1,$laboratorio);
        $sql->bindValue(2,$base);
        $sql->bindValue(3,$tipo_lente);
        $sql->bindValue(4,$inicio);
        $sql->bindValue(5,$fin);
        
    $sql->execute();
    return $resultado=$sql->fetchAll(PDO::FETCH_ASSOC);    

}

}