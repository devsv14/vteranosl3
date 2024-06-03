<?php

require_once("../config/conexion.php");
//require_once('../vistas/side_bar.php');
class Pruebas extends Conectar{

	public function getActas(){
	  $conectar = parent::conexion();
      parent::set_names();
      //obtenemos todas los duis de actas impresas     
      $sql = "select * from actas;";
      $sql=$conectar->prepare($sql);
      $sql->execute();
      $total_actas = $sql->fetchAll(PDO::FETCH_ASSOC);  


      $array_actas_c = array();
      $array_actas_sc = array();
      ///recorro los DUIs buscando si existe cita en orden_lab
      foreach ($total_actas as $v){
        $sql2 = "select * from orden_lab where dui=?;";
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
        $fecha_orden = $resultado_cita[0]["fecha"];
        $color=$resultado_cita[0]["color"];
        $paciente = $v["beneficiario"];
        //echo $id_cita;
        if($id_cita != 0){
           $sql3='select*from citas where dui=?';
           $sql3=$conectar->prepare($sql3);
           $sql3->bindValue(1, $v["dui_acta"]);
           $sql3->execute();
           $data_citado = $sql3->fetchAll(PDO::FETCH_ASSOC);
           $t_paciente = $data_citado[0]["tipo_paciente"];
           $sector = $data_citado[0]["sector"];
           if($t_paciente=="" and ($sector !='' and $sector !='Seleccionar...' and $sector !='0')){
            $tipo_paciente = $sector;
           }elseif($t_paciente=="" and ($sector =='' or $sector =='Seleccionar...' or $sector =='0')){
            $tipo_paciente = "Datos incompletos";
           }elseif($t_paciente !=""){
            $tipo_paciente = $t_paciente;
           }
           
           if($sector =='' or $sector =='Seleccionar...' or $sector =='0'){
              $sec = 'Datos imcompletos';
           }else{
              $sec = $sector;
           }
           
           /***************VARAIABLES DE CITAS****************/
            // $paciente = $data_citado[0]['paciente'];
            /*******************************/
           $sub_array = ['id_acta'=>$id_acta,'receptor'=>$receptor,'tipo_receptor'=>$tipo_receptor,'fecha_impresion'=>$fecha_imp,'paciente'=>$paciente,'tipo_paciente'=>$tipo_paciente,'sector'=>$sec,'tipo_lente'=>$tipo_lente,'color'=>$color,'fecha_orden'=>$fecha_orden,'sucursal'=>$v["sucursal"],'cita'=>'Si'];
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

            if($instit=='' or $instit=='Seleccionar...'){
              $tipo_paciente = 'Datos incompletos';
            }
            $sub_array = ['id_acta'=>$id_acta,'receptor'=>$receptor,'tipo_receptor'=>$tipo_receptor,'fecha_impresion'=>$fecha_imp,'paciente'=>$paciente,'tipo_paciente'=>$tipo_paciente,'sector'=>$sec,'tipo_lente'=>$tipo_lente,'color'=>$color,'fecha_orden'=>$fecha_orden,'sucursal'=>$v["sucursal"],'cita'=>'No'];
            array_push($array_actas_sc,$data_paciente[0]['paciente']);
        }
       
      }
    return $array_actas_c;
}

public function getDisponibilidadCitas($fecha){
     $conectar=parent::conexion();    
  $conectar=parent::conexion();    
    parent::set_names();
    $sql = "select nombre from sucursales";
    $sql=$conectar->prepare($sql);
    $sql->execute();
    $sucursales=$sql->fetchAll(PDO::FETCH_ASSOC);
    $data_disponibilidad = array();
    foreach($sucursales as $s){
        
        $cita = "SELECT count(*) as citados from citas WHERE fecha=? and sucursal=?";
        $cita=$conectar->prepare($cita);
        $cita->bindValue(1, $fecha);
        $cita->bindValue(2, $s["nombre"]);
        $cita->execute();
        $total_citas=$cita->fetchAll(PDO::FETCH_ASSOC);
        $citados =  $total_citas[0]['citados'];

        $cupo = "select cupos,direccion,referencia,optica from sucursales where nombre=?";
        $cupo=$conectar->prepare($cupo);
        $cupo->bindValue(1, $s["nombre"]);
        $cupo->execute();
        $total_cupos=$cupo->fetchAll(PDO::FETCH_ASSOC);
        $cupo_disp =  $total_cupos[0]['cupos'];
        $direccion =  strtoupper($total_cupos[0]['direccion']);
        $referencia =  strtoupper($total_cupos[0]['referencia']);
        $optica =  strtoupper($total_cupos[0]['optica']);


        $disponibilidad = ($cupo_disp-$citados)."/".$cupo_disp;
        $disp_act= $cupo_disp-$citados;
        $dias = array('Domingo', 'Lunes', 'Martes', 'Miércoles','Jueves', 'Viernes', 'Sábado');
        $fechats = strtotime($fecha);
        $dia= $dias[date('w', $fechats)];
 
        if($disp_act>0){
            $fecha_limite = '2023-06-15';
            if(($s["nombre"] == "Sonsonate" and $dia =="Jueves") or (($s["nombre"] == "Valencia" or $s["nombre"] == "Apopa" or $s["nombre"] == "San Vicente" or $s["nombre"] == "San Vicente Centro" or $s["nombre"] == "Usulutan" or $s["nombre"] == "San Miguel" or $s["nombre"] == "Sonsonate") and ($dia =="Sábado" or $dia =="Domingo")) or ($dia=='Domingo' and $s["nombre"] !='San Miguel AV PLUS') or (($s["nombre"] == "Usulutan" or $s["nombre"] == "San Miguel") and (strtotime($fecha) > strtotime($fecha_limite)))){            
            }else if($s["nombre"] == "San Miguel AV PLUS" and ($dia =="Lunes" or $dia =="Martes" or $dia =="Jueves" or $dia =="Viernes" or $dia =="Viernes" or $dia =="Sábado" or $dia =="Domingo")){
                array_push($data_disponibilidad,array("sucursal"=>$s["nombre"],"cupos"=>$disponibilidad,"direccion"=>$direccion,"referencia"=>$referencia,"optica"=>$optica));
            }else if($s["nombre"] != "San Miguel AV PLUS"){
                array_push($data_disponibilidad,array("sucursal"=>$s["nombre"],"cupos"=>$disponibilidad,"direccion"=>$direccion,"referencia"=>$referencia,"optica"=>$optica));
            }
        }
        
    }
    return $data_disponibilidad;
}

	public function getEntregar(){
	  $conectar = parent::conexion();
      parent::set_names();
      //obtenemos todas los duis de actas impresas     
      $sql = "SELECT o.paciente,o.dui,c.sector,c.tipo_paciente,concat(a.fecha,' ',a.hora) as fechaIng,o.sucursal,o.telefono from orden_lab as o INNER JOIN acciones_optica as a on a.dui=o.dui INNER join citas as c on o.dui=c.dui where a.accion = 'ingreso_orden_optica' and o.estado IN ('5','5-e') and o.sucursal='Chalatenango' order by a.id_accion,o.sucursal,o.institucion ASC;";
      $sql=$conectar->prepare($sql);
      $sql->execute();
      $total_entregar = $sql->fetchAll(PDO::FETCH_ASSOC); 
	  return $total_entregar;
	    
	}
	
	public function convertSucursal(){
	  $conectar = parent::conexion();
      parent::set_names();
      //obtenemos todas los duis de actas impresas     
      $sql = "select*from actas where sucursal = 'Jornada Sonsonate';";
      $sql=$conectar->prepare($sql);
      $sql->execute();
      $res_actas = $sql->fetchAll(PDO::FETCH_ASSOC); 
	  $cont = 0;
      foreach ($res_actas as $v) {
           //echo $cont.'-'.$v["dui_acta"].'<br>';
           $sql2 = 'update orden_lab set sucursal="Jornada Sonsonate" where dui=?;';
           $sql2=$conectar->prepare($sql2);
           $sql2->bindValue(1,$v["dui_acta"]);
           $sql2->execute();
      }
      
      echo 'Finish...';
	
    
}

public function updateFechaOrdenes($fechaInicio, $fechaFin){
    $timestampInicio = strtotime($fechaInicio);
	$timestampFin = strtotime($fechaFin);
	$timestampAleatorio = rand($timestampInicio, $timestampFin);
	$fechaAleatoria = date('Y-m-d', $timestampAleatorio);
	
	return $fechaAleatoria;
}

public function newFechaActa($fecha_orden){

$fecha_objeto = new DateTime($fecha_orden);
$dias_aleatorios = mt_rand(25, 70);
$fecha_objeto->modify('+' . $dias_aleatorios . ' days');
$fecha_ajustada = $fecha_objeto->format('Y-m-d');

$dia_semana = date_format($fecha_objeto, 'N'); 
$dia_mes = date_format($fecha_objeto, 'd');

if ($dia_semana >= 6 || $dia_mes == '01' || $dia_mes == '02') {
    $fecha_objeto->modify('+2 day'); 
    $fecha_ajustada = $fecha_objeto->format('Y-m-d'); // Actualiza la fecha ajustada
}
return $fecha_ajustada;

}


public function updateFechaActa(){
  $conectar = parent::conexion();
  parent::set_names();

  $duis = ['00055105-1',
'02356715-2',
'01093174-0',
'00259037-8',
'01713610-5',
'01610470-7',
'02899592-8',
'02476511-9',
'01517198-9',
'02149517-1',
'00512317-0',
'02101532-9',
'01858885-0',
'04012955-1',
'01791966-8',
'01721964-4',
'03681128-5',
'01326915-0',
'02515391-7',
'01831043-5',
'00266489-2',
'00114766-9',
'01413801-9',
'01677408-1',
'00029300-1',
'01358517-4',
'00407214-8',
'01725920-4',
'01760854-2',
'00724597-8',
'02081864-3',
'01122540-1',
'01410756-3',
'01657510-2',
'00274139-3',
'05510724-7',
'02096276-9',
'00043655-2',
'02782780-5',
'00919311-9',
'01317067-8',
'01990902-5',
'00574027-3',
'01156050-0',
'00971715-9',
'01171893-3',
'00178918-6',
'00023521-5',
'00038214-3',
'00510769-5',
'02572701-7',
'01718094-2',
'00610628-6',
'01054622-8',
'01741911-3',
'01375504-6',
'00251596-2',
'01495856-6',
'00297033-2',
'01781797-1',
'02696664-8',
'00015773-4',
'00649633-0',
'01355411-5',
'00090460-2',
'00507030-1',
'02249716-8',
'00291997-0',
'03608622-0',
'01400996-9',
'01033244-1',
'02148341-7',
'01431897-8',
'02209199-6',
'01742031-8',
'03055107-3',
'02718400-3',
'02120911-4',
'01375363-8',
'01240613-1',
'01251841-7',
'01919280-6',
'01593861-6',
'00148732-8',
'01949701-0',
'00572577-8',
'01902545-7',
'00599903-4',
'01606280-8',
'02212153-9',
'00533732-1',
'01677708-9',
'02631610-2',
'01825410-0',
'01808395-7',
'03053197-6',
'00332416-0',
'00551845-6',
'02189669-4',
'00592982-7',
'00186156-4',
'01392496-2',
'00542813-0',
'01408772-1',
'01507990-9',
'01981935-1',
'00288592-7',
'02156763-5',
'03207224-5',
'02069549-3',
'00927367-6',
'00889006-9',
'00483689-3',
'02025765-1',
'02630176-7',
'00427128-9',
'02909309-6',
'00641486-7',
'02143883-6',
'00042420-4',
'01887114-8',
'01361112-9',
'02261509-1',
'00709569-0',
'01982804-1',
'01357192-1',
'02078180-4',
'01286450-9',
'02637962-1',
'00232108-8',
'00947764-4',
'01063610-4',
'00077661-9',
'02353702-6',
'00747306-8',
'01429518-8',
'00907664-2',
'02153723-2',
'04462508-8',
'00353355-7',
'01785702-8',
'01921815-7',
'01995681-0',
'01563013-7',
'02050982-0',
'01355910-7',
'01469997-6',
'00378770-8',
'03266803-8',
'03196607-7',
'00905895-3',
'02002565-6',
'03590017-0',
'01394393-2',
'00139684-4',
'03288774-7',
'05315651-7',
'01175214-9',
'01181584-0',
'00068494-3',
'03115577-3',
'01198585-7',
'01095444-7',
'00162454-8',
'00317918-3',
'01951770-5',
'01984232-0',
'00614327-0',
'00994483-7',
'02095524-1',
'00071777-0',
'02465710-4',
'00290168-4',
'00268678-9',
'00854169-4',
'01169754-3',
'01417315-8',
'02740507-7',
'00320923-9',
'01310030-6',
'00254207-4',
'00583412-9',
'01649780-9',
'00627205-3',
'01022157-7',
'02112187-9',
'02015091-4',
'00364218-6',
'02782679-4',
'00111374-1',
'02636953-7',
'01424399-5',
'01206988-2',
'00815542-7',
'00579873-9',
'01601888-3',
'00238406-0',
'02779441-8',
'00872528-0',
'00164958-0',
'02193263-6',
'01738684-9',
'02884938-9',
'00146905-3',
'02108118-4',
'03087276-2',
'02148690-2',
'00239730-6',
'01949987-6',
'02127732-9',
'00051094-0',
'01527423-2',
'02183207-2',
'01698996-1',
'01209725-9',
'01374561-9',
'00584155-8',
'00011463-9',
'03303305-8',
'00051242-1',
'01034759-3',
'01230544-0',
'01447624-7',
'00626853-3',
'04708143-7',
'03860885-8',
'00187791-3',
'02138438-8',
'00886047-0',
'00554265-9',
'00547618-3',
'01314488-9',
'02193480-8',
'00277739-4',
'01760686-7',
'01326649-5',
'03192030-6',
'00886217-1',
'00704698-4',
'01204181-8',
'00035075-6',
'00954863-1',
'02847950-8',
'04107796-9',
'01726537-8',
'00192270-0',
'02160047-5',
'01315825-2',
'00445597-2',
'00195586-8',
'01921252-5',
'00400877-5',
'01674012-1',
'01816167-4',
'01245747-5',
'01284232-9',
'01898649-8',
'01412819-5',
'00799285-0',
'01227532-8',
'01269566-7',
'01172204-7',
'01812169-0',
'00834604-4',
'02619486-9',
'01325180-6',
'02690654-1',
'00491704-7',
'02437898-8',
'00472862-6',
'00037051-0',
'00868558-7',
'01212244-4',
'02362755-4',
'00536100-3',
'01303929-6',
'03514656-4',
'01642808-8',
'00215514-4',
'00381809-6',
'00292374-1',
'00293441-7',
'00151714-9',
'00508530-6',
'01748752-2',
'00257496-6',
'00054890-1',
'03420853-3',
'00122528-9',
'02828761-8',
'03880019-1',
'00294547-6',
'02461899-8',
'01375313-3',
'00192212-4',
'02038534-9',
'02102132-0',
'01428360-2',
'02446211-9',
'03461099-2',
'00329947-0',
'00143333-7',
'00396637-8',
'02992605-3',
'03515878-1',
'01701453-1',
'03006737-5',
'01840004-4',
'02727377-1',
'01450121-2',
'00654156-7',
'02630195-3',
'00901856-3',
'02618646-8',
'03093567-5',
'01780519-4',
'01085889-5',
'01006277-9',
'01670299-5',
'00520373-4',
'02087230-4',
'01272070-5',
'01707206-8',
'02121290-5',
'01902727-1',
'00614573-5',
'02726405-7',
'01955132-7',
'00587860-2',
'02742999-0',
'00876328-8',
'01215818-6',
'01610386-6',
'01246265-8',
'00150445-5',
'00530470-0',
'01818981-8',
'01742977-8',
'02145478-5',
'02689367-5',
'02933918-3',
'01741766-6',
'01864609-8',
'02121957-5',
'01275604-9',
'04413441-9',
'00506269-1',
'01019974-6',
'02483600-9',
'00107105-4',
'00782643-1',
'00678356-7',
'00087325-9',
'00140103-9',
'00968736-2',
'02248837-1',
'00524482-9',
'01980926-7',
'00594861-9',
'01382216-0',
'03238233-1',
'01224293-5',
'02280176-5',
'00964508-5',
'03191150-1',
'02856225-4',
'00004692-5',
'01450693-7',
'00121093-3',
'00006810-5',
'03324515-0',
'01038915-5',
'00902585-3',
'00014577-9',
'02032073-9',
'00298432-3',
'00346127-1',
'01435373-2',
'02199372-1',
'01242159-7',
'01477126-5',
'00002859-5',
'04772558-6',
'03554745-1',
'01691949-5',
'02730383-5',
'01945391-9',
'05026528-6',
'01265273-2',
'01239978-2',
'03023459-0',
'03966122-3',
'03232835-3',
'01641677-2',
'00478383-8',
'01416904-4',
'04029400-5',
'01476512-5',
'03372065-5',
'01732399-8',
'00755901-8',
'03681186-1',
'02122462-7',
'00449248-7',
'00117387-2',
'00809159-2',
'02823022-1',
'01507526-4',
'00949825-0',
'00677204-5',
'03246760-2',
'02487788-5',
'02905985-6',
'00918025-5',
'01258781-4',
'01326563-5',
'02239706-7',
'01434084-4',
'00652497-1',
'00631917-2',
'00607406-5',
'01970923-9',
'01757657-5',
'00462723-6',
'02073809-7',
'00898825-2',
'01392460-3',
'01659046-1',
'01828353-1',
'01915987-4',
'00406758-3',
'01995726-4',
'00045428-3',
'00432933-3',
'01874817-5',
'01796740-9',
'03114652-0',
'02402121-4',
'00002025-4',
'00022251-3',
'01963455-7',
'01520885-9',
'01115587-6',
'01831269-9',
'01890208-8',
'01941006-8',
'00797784-2',
'01907611-5',
'01642119-1',
'01833786-9',
'01296668-6',
'02175004-2',
'00256936-9',
'00825135-4',
'02181706-4',
'02339724-8',
'00396069-9',
'02238422-6',
'00386666-7',
'01837952-8',
'00579398-3',
'01783486-8',
'00307099-9',
'01630298-1',
'00886494-5',
'00147389-0',
'02068632-1',
'02543235-3',
'01770589-9',
'02050488-8',
'01879866-7',
'02138549-9',
'00069977-8',
'01783818-9',
'01736344-3',
'01650207-8',
'02655280-5',
'05515202-2',
'01567142-6',
'02492071-9',
'02968573-8',
'03496069-8',
'03065327-3',
'00712399-8',
'02507792-5',
'01089664-9',
'01959533-9',
'01195939-3',
'01983742-2',
'01427485-7',
'00858954-5',
'00544099-6',
'01500126-5',
'04686302-2',
'00251647-1',
'01554592-6',
'04447337-6',
'01319737-9',
'01024787-4',
'00410135-3',
'03217226-5',
'00692778-9',
'00304397-6',
'00490189-2',
'02107915-3',
'00604201-8',
'00407909-3',
'00044839-7',
'02888417-7',
'02480010-5',
'00482095-7',
'00001909-1',
'01216039-5',
'00454285-0',
'02977912-1',
'01744109-7',
'02038615-9',
'03280470-7',
'02629239-0',
'00939754-3',
'02289541-3',
'02036755-3',
'02283611-8',
'01932321-1',
'00653710-2',
'02129125-0',
'00962244-3',
'00543788-8',
'03534738-0',
'02215424-9',
'02815696-4',
'04868451-3',
'01029099-0',
'01595609-6',
'01221965-7',
'03223028-3',
'01708947-1',
'01269025-1',
'00431591-0',
'03066717-5',
'00283908-1',
'00657097-2',
'00192636-4',
'02170787-9',
'01727409-2',
'03002820-8',
'03967876-7',
'02249614-6',
'02662595-9',
'01095137-6',
'00504528-3',
'01084982-0',
'02061714-4',
'00407111-8',
'02160925-9',
'00548254-0',
'02583193-9',
'00034713-5',
'01521985-0',
'01299232-8',
'01255708-9',
'03990848-9',
'00126985-1',
'02858303-0',
'00992708-9',
'02486969-6',
'00531716-9',
'01822050-9',
'00128381-3',
'00539135-9',
'01389429-8',
'01165762-4',
'01110606-3',
'01103449-4',
'00484902-4',
'02633543-1',
'01809304-1',
'00582675-2',
'01626714-9',
'03461650-8',
'01373766-6',
'01382715-2',
'01084759-3',
'01220586-0',
'00564356-0',
'02714693-2',
'01241415-0',
'02409226-5',
'01280250-7',
'01310109-3',
'01332428-5',
'01405175-4',
'02394998-0',
'01784842-7',
'00316521-5',
'03039588-3',
'00712831-2',
'02471998-0',
'01878854-9',
'00105619-3',
'01316055-0',
'00570666-9',
'01482771-5',
'01589716-1',
'01475803-9',
'01037129-1',
'00570973-0',
'00268899-3',
'03248946-8',
'00526869-5',
'01669759-8',
'03104726-3',
'01499852-4',
'01536666-5',
'00461813-0',
'01394038-2',
'03509044-6',
'00338243-5',
'00976448-1',
'01879936-2',
'00920307-9',
'03013299-2',
'02932028-1',
'02334151-4',
'01422129-4',
'01754878-4',
'01625385-7',
'02218693-7',
'03172452-2',
'02532997-4',
'00223309-9',
'01410006-6',
'02532458-4',
'02532744-3',
'00862574-9',
'01352906-3',
'00057285-3',
'04537096-1',
'01616667-8',
'00981178-3',
'02515807-2',
'00980354-4',
'01680556-5',
'00939117-3',
'02719069-8',
'00091998-2',
'00982411-8',
'02336333-8',
'00465462-3',
'02178120-5',
'01833696-0',
'01206009-0',
'00910008-5',
'01043808-5',
'00255510-8',
'00727550-9',
'01131510-9',
'00308778-4',
'04637833-6',
'01986198-4',
'01704249-5',
'06450375-1',
'05378632-1',
'06273173-0',
'03713496-1',
'01691351-2',
'00972362-1',
'03021888-7',
'02684163-6',
'00590958-4',
'00299739-2',
'00343603-0',
'01380436-6',
'00782959-4',
'00819905-7',
'02503906-6',
'00718582-7',
'00763111-1',
'02603549-7',
'02783787-6',
'01577198-3',
'00431726-3',
'05807735-2',
'02569145-2',
'02243022-1',
'02056704-8',
'01269980-7',
'01912774-6',
'02293535-2',
'01763351-3',
'00637329-9',
'00339318-5',
'02311094-7',
'02824748-0',
'00958027-7',
'00633621-3',
'02105906-4',
'01831354-8',
'02015291-6',
'01101244-2',
'00415344-9',
'01270812-7',
'01501083-2',
'00707551-9',
'00287815-8',
'00209569-5',
'00605689-7',
'05121942-4',
'01978562-5',
'03140569-9',
'00032396-1',
'02484389-4',
'01341067-0',
'03602390-5',
'00812997-1',
'02407257-4',
'02561862-4',
'03256484-4',
'02044444-4',
'01776805-9',
'00050417-7',
'01962823-9',
'01519875-3',
'02424371-9',
'00406893-7',
'00222998-5',
'00726838-2',
'03975912-3',
'01434067-4',
'01610218-7',
'01328615-2',
'00186919-8',
'00412528-4',
'01539694-5',
'02986393-8',
'01670350-1',
'01674070-7',
'01171474-3',
'00134852-4',
'01509820-4',
'00412490-3',
'02634383-2',
'02255029-1',
'01221587-3',
'01358729-9',
'01906423-1',
'00921639-9',
'02624122-6',
'01956213-2',
'01583886-6',
'01612040-2',
'01642309-6',
'00329269-8',
'01965539-1',
'02016703-4',
'03304492-',
'00818303-0',
'02341333-7',
'01636728-2',
'01674792-9',
'02954933-0',
'01996515-2',
'02459355-4',
'00672241-4',
'02804067-6',
'02169695-5',
'01311020-4',
'03013003-9',
'03352128-9',
'00094064-0',
'00036227-4',
'01508001-5',
'00472246-8',
'00243907-7',
'00431007-5',
'01201324-7',
'03864626-2',
'03259079-8',
'00335401-8',
'00625601-5',
'00137990-7',
'01637608-7',
'01493639-4',
'02880458-3',
'00201915-2',
'01274780-4',
'02635158-4',
'00327107-4',
'03080367-4',
'03936984-7',
'02492052-3',
'02545911-9',
'01009864-9',
'00546063-7',
'02447481-5',
'01573640-4',
'01260570-1',
'00106632-6',
'00571375-5',
'00413337-6',
'02130395-0',
'04318033-6',
'01735141-2',
'02808283-0',
'00598352-0',
'00067036-8',
'02197503-2',
'00090897-3',
'00608808-0',
'02162453-4',
'00030862-8',
'02223564-7',
'00016345-0',
'01324338-2',
'00100079-4',
'04647301-3',
'00051724-3',
'01935055-1',
'00257040-9',
'00019399-2',
'02734133-8',
'00885624-3',
'00524534-6',
'00974907-5',
'01019770-2',
'00620553-5',
'00510429-9',
'00656073-1',
'03942041-5',
'02263183-5',
'00479929-5',
'00206338-9',
'01162938-8',
'01789304-0',
'00088235-5',
'00620177-7',
'02778485-3',
'01981272-3',
'04811064-5',
'02400195-5',
'01083554-6',
'01953710-3',
'01255084-1',
'03030675-3',
'02324840-7',
'01433887-1',
'02452571-3',
'00116674-4',
'00509695-9',
'01740007-5',
'02509037-1',
'01367798-9',
'00426592-9',
'02923233-1',
'01261288-9',
'02726066-3',
'00688166-6',
'02929932-5',
'00474584-8',
'01200988-2',
'01243408-7',
'00559395-1',
'02768802-3',
'01866257-3',
'02003473-6',
'00754435-6',
'00959105-8',
'02978671-2',
'02637668-1',
'02809168-5',
'01287033-0',
'01938958-4',
'01604236-1',
'02096257-3',
'02595384-6',
'01463727-5',
'01450677-5',
'01245679-6',
'01341657-9',
'00016514-3',
'01691602-3',
'02193296-1',
'02029673-6',
'02691913-8',
'00557454-1',
'03238954-5',
'01608481-8',
'02520318-6',
'01734480-5',
'02697327-1',
'05239783-5',
'03504391-0',
'00022176-1',
'00454631-7',
'01424605-8',
'02792065-3',
'00467597-0',
'00153736-9',
'02502630-6',
'02717090-7',
'01325629-6',
'00534027-7',
'01829892-6',
'02126467-7',
'00302493-0',
'00323557-3',
'00566696-6',
'01537950-3',
'00619458-0',
'00989528-2',
'01039896-8',
'00248706-2',
'00577281-4',
'00793628-6',
'00824665-0',
'00795787-6',
'02183474-9',
'01544148-1',
'00716235-8',
'01780312-6',
'00353017-7',
'00231387-3',
'00518293-8',
'02847965-5',
'03423248-5',
'01007296-0',
'02635123-3',
'02955753-7',
'01629335-2',
'01677870-0',
'00471953-8',
'01261902-7',
'02517717-3',
'02674077-5',
'02141111-9',
'03219553-0',
'01440821-0',
'01561172-7',
'03777354-5',
'01512138-2',
'00362333-6',
'01772957-6',
'03061617-4',
'00108168-5',
'00675380-5',
'00819741-1',
'00536453-0',
'03424490-3',
'00834712-1',
'01981365-6',
'00678226-0',
'02584107-3',
'00292141-4',
'00498751-3',
'02282794-9',
'00545746-4',
'01267991-2',
'01349853-9',
'01786260-9',
'00200858-3',
'02275488-7',
'03008518-7',
'02487391-2',
'00295800-5',
'00305214-5',
'00106609-1',
'02354509-5',
'00587584-0',
'02209425-3',
'01859492-4',
'02976700-1',
'00541360-6',
'01737324-4',
'00822119-7',
'01789265-4',
'00377390-3',
'00167444-6',
'02270990-5',
'02171732-9',
'02085893-6',
'02526320-9',
'01074778-5',
'02854112-7',
'00938532-6',
'02185888-2',
'02930945-5',
'01645338-4',
'00184174-2',
'00661507-3',
'01715592-1',
'00982676-2',
'01676407-8',
'02475416-8',
'00161303-4',
'01731865-0',
'00781765-2',
'00212827-8',
'00341080-6',
'03034279-1',
'00896555-5',
'00583798-1',
'01542458-6',
'02885267-5',
'00491093-0',
'01856619-1',
'00086991-7',
'01062719-7',
'00372790-2',
'03179118-9',
'02045512-8',
'00626107-8',
'01603865-5',
'01430819-3',
'00190301-5',
'01837102-5',
'00027026-5',
'02486929-8',
'02339677-1',
'00504041-1',
'00745622-8',
'01563850-9',
'02094396-9',
'00907734-7',
'01427531-6',
'02785325-4',
'01691709-5',
'01630663-4',
'00554480-5',
'01634071-9',
'01496662-4',
'00578456-0',
'01315020-4',
'01221102-3',
'01870680-6',
'00527525-2',
'00214695-9',
'01200900-2',
'01934169-1',
'02159850-5',
'00160947-5',
'02427655-0',
'03200540-0',
'01170358-0',
'00352007-5',
'03141920-8',
'01950908-7',
'00732906-5',
'00536343-7',
'02957572-1',
'02225980-3',
'03234295-9',
'02048931-3',
'00107095-1',
'00663658-2',
'00196683-5',
'01835144-9',
'03016169-0',
'00012176-7',
'01885694-4',
'02062310-3',
'01784378-6',
'00153961-2',
'00560475-2',
'00305769-0',
'02024427-6',
'01268522-2',
'00480983-8',
'00257672-2',
'02487608-3',
'02963507-6',
'01283255-2',
'01253251-8',
'01278667-0',
'00650403-6',
'01299270-0',
'02744969-9',
'01353078-9',
'00027412-0',
'00400723-2',
'00249931-0',
'01750086-7',
'03322246-1',
'00262699-1',
'02044177-1',
'00462960-2',
'00551309-0',
'02639176-2',
'01523559-7',
'00652543-0',
'01281042-9',
'02022521-4',
'01349147-2',
'01273561-1',
'02676246-8',
'00526627-9',
'00383184-0',
'00041056-4',
'00818061-8',
'00249769-3',
'01984598-8',
'00957623-6',
'00221089-7',
'00143998-5',
'01477245-7',
'03418889-8',
'03369386-6',
'01569198-9',
'01258895-9',
'00125505-6',
'01753505-8',
'00800766-6',
'03171870-9',
'00701784-6',
'00997428-0',
'02557775-5',
'00778966-3',
'00621974-6',
'00893932-6',
'00044131-1',
'01964575-2',
'01857819-8',
'02856065-0',
'01635958-0',
'02936431-6',
'03196312-6',
'02577401-4',
'01856937-7',
'01573165-8',
'02032431-9',
'03758498-8',
'00946549-3',
'02711306-0',
'01896100-8',
'02733302-6',
'02363439-9',
'00256700-8',
'01836351-9',
'02024241-0',
'02085827-9',
'00347515-7',
'00812185-0',
'00462868-0',
'01093824-7',
'00674575-5',
'01755485-8',
'01269751-2',
'00366459-4',
'02591900-4',
'01827611-0',
'01391386-4',
'01577761-2',
'02023311-0',
'00880353-3',
'01017072-6',
'00012568-0',
'00109650-9',
'00325778-7',
'01404833-7',
'01135709-6',
'01306785-9',
'01504742-3',
'02161588-6',
'01014954-7',
'01957952-9',
'01083019-8',
'03439511-0',
'00424344-8',
'00241074-8',
'01352073-4',
'00523585-4',
'00865377-6',
'02477846-3',
'03848737-5',
'02429713-2',
'03392875-8',
'01978340-3',
'02553879-3',
'02077113-4',
'02139101-8',
'00407778-2',
'01673092-2',
'02493311-0',
'01189590-5',
'00039776-6',
'02148335-2',
'00288379-7',
'00662845-8',
'00487760-3',
'00835321-1',
'04165107-2',
'00393172-1',
'02024461-6',
'02122434-2',
'01992043-7',
'02540771-4',
'00640407-4',
'00842935-5',
'01023815-0',
'01730563-1',
'00124327-9',
'02079909-3',
'01464294-5',
'00150440-5',
'01984258-2',
'00431510-6',
'01101240-0',
'00989705-6',
'02031852-0',
'00973902-0',
'01241374-8',
'01775176-9',
'02388977-4',
'01059880-1',
'02445632-0',
'02577060-4',
'03405956-0',
'01005990-4',
'01862741-8',
'03117066-8',
'01867456-2',
'01832479-3',
'00762978-2',
'03351145-4',
'02785447-0',
'00745017-5',
'00687324-9',
'02505907-4',
'01143951-3',
'00019717-4',
'02011624-5',
'02032084-4',
'00523264-4',
'01148946-1',
'01741344-2',
'00532896-6',
'03102243-3',
'02940623-1',
'05034137-5',
'00717605-6',
'02570728-7',
'01974852-6',
'02001000-9',
'06651750-2',
'00510810-4',
'00501082-2',
'01944470-8',
'02055957-4',
'00578793-2',
'02737368-6',
'03427169-1',
'01463860-3',
'00480071-1',
'0134045-6',
'00288963-8',
'01420115-5',
'02970725-5',
'02081332-6',
'00566266-1',
'02049589-3',
'02397442-2',
'00246001-0',
'00706490-8',
'02264556-7',
'01313397-7',
'01639704-1',
'03002458-9',
'02161740-6',
'02266296-7',
'01562394-4',
'00576018-4',
'00121747-2',
'01339255-5',
'01274616-7',
'00023915-4',
'02827057-2',
'05307856-5',
'00099027-1',
'03720532-2',
'01930133-2',
'01580266-1',
'02132986-7',
'01903191-1',
'01055192-2',
'00830950-5',
'01266970-5',
'02678519-9',
'02134601-3',
'01807214-2',
'01490213-3',
'02715189-8',
'01171205-0',
'03149630-7',
'01891750-4',
'01996387-5',
'00328654-0',
'00298837-7',
'02487236-4',
'02410528-8',
'02093454-6',
'00024460-4',
'05589312-3',
'00314705-5',
'02222884-4',
'02084503-0',
'01730338-8',
'01918011-8',
'00625286-7',
'00473094-0',
'03054837-1',
'01283145-9',
'02113191-3',
'01859976-2',
'02919837-5',
'02732183-3',
'01680139-1',
'02433312-4',
'00201553-0',
'00049536-0',
'00120068-7',
'01868183-6',
'00900198-0',
'01388380-7',
'01674610-1',
'02149538-3',
'01130553-6',
'01757167-2',
'01935122-2',
'01170469-1',
'03988794-2',
'01662047-8',
'03415194-8',
'02364481-5',
'03004742-2',
'02134463-9',
'02340695-8',
'00393792-1',
'00685601-9',
'02274548-0',
'01567190-5',
'01796522-9',
'03899844-9',
'03064774-3',
'00740778-2',
'04368952-6',
'03265305-9',
'00890717-5',
'04198277-4',
'02740881-3',
'00816565-0',
'01824958-7',
'00243778-2',
'00876293-1',
'02692489-0',
'00791411-1',
'03211164-1',
'00558989-7',
'03438311-3',
'00981275-5',
'03986964-3',
'00998318-2',
'01536937-0',
'00598322-9',
'02872457-1',
'02116196-8',
'00507697-5',
'01991144-6',
'00847368-9',
'02070416-1',
'01312713-8',
'01728951-8',
'01317588-0',
'05343189-4',
'00726653-4',
'03093059-4',
'01363960-6',
'01614531-3',
'00980166-5',
'01017621-9',
'01685412-4',
'00019011-4',
'02744623-5',
'06450399-7',
'01279046-7',
'01674864-0',
'02008619-9',
'00057714-6',
'00442479-3',
'00216535-1',
'00118319-4',
'00895711-2',
'00018413-9',
'02276113-5',
'01371639-3',
'01272104-4',
'01622475-1',
'01341404-8',
'00497735-6',
'03856685-2',
'00298433-1',
'02580010-8',
'01159871-5',
'00544223-1',
'02029697-2',
'01779390-7',
'01494977-9',
'01385759-7',
'02691377-6',
'01031588-9',
'00702164-1',
'01280790-5',
'00132273-0',
'00273539-2',
'03492064-8',
'00437037-6',
'00283635-0',
'00897250-2',
'02159725-8',
'02456105-2',
'01275669-1',
'02889583-5',
'00886640-0',
'03005282-5',
'00433039-2',
'00781703-4',
'01063788-3',
'00510893-4',
'01444297-1',
'00155764-4',
'00305167-8',
'01992937-6',
'01189296-5',
'00891176-8',
'02327156-5',
'00473461-9',
'02107261-4',
'01854661-2',
'03232441-4',
'00555176-3',
'02150995-4',
'02102240-7',
'02739630-9',
'02165488-0',
'00648194-5',
'00390700-7',
'01027765-9',
'00524159-6',
'02115072-1',
'02063967-5',
'02185661-0',
'00810427-2',
'01810495-7',
'02330569-9',
'03072380-8',
'00085992-0',
'02083987-7',
'01084854-9',
'01297076-6',
'00391730-3',
'01094851-9',
'03044100-8',
'00472830-9',
'01471978-4',
'02852147-8',
'00293160-5',
'00357851-5',
'01470543-4',
'02572066-7',
'00068666-0',
'02137947-2',
'00713101-4',
'02881107-7',
'02693535-4',
'02459948-7',
'01626127-4',
'02328859-6',
'00376744-9',
'00372056-0',
'02338288-7',
'00045425-9',
'02145023-6',
'01444791-3',
'00084064-6',
'02453778-6',
'02753672-1',
'00759034-9',
'01996452-0',
'00176261-3',
'02245693-4',
'01760433-6',
'02070625-2',
'02034867-2',
'02120844-3',
'00417976-2',
'01115114-9',
'01831055-8',
'00931652-1',
'02110172-2',
'05633479-0',
'02074104-0',
'01685812-8',
'02448817-3',
'01570451-2',
'01893911-6',
'01697356-2',
'02443264-3',
'02712837-4',
'00224312-5',
'03194633-6',
'04809871-2',
'00131597-9',
'00743901-4',
'01686307-6',
'00333077-1',
'00873382-7',
'00288071-5',
'01842761-4',
'02755236-1',
'01179758-9',
'01236131-5',
'00129721-0',
'01772236-2',
'01654567-8',
'00627584-9',
'01761060-4',
'00549520-0',
'02497695-6',
'01927640-6',
'00564976-0',
'01153692-5',
'01809923-3',
'01227915-2',
'00808767-4',
'03344884-7',
'02254159-3',
'01847166-3',
'01907386-6',
'02103892-9',
'02220585-4',
'00319207-6',
'02340049-9',
'03661634-2',
'01639882-7',
'03252955-1',
'00770768-7',
'00482985-4',
'01892668-4',
'00006226-4',
'00830923-8',
'01847848-7',
'02329554-3',
'01584426-5',
'00983256-9',
'01196548-3',
'01080575-3',
'02004976-5',
'00277345-5',
'00130074-6',
'02149122-4',
'01584053-8',
'01335674-5',
'02444927-6',
'00284638-9',
'01223565-3',
'02200266-2',
'00232666-4',
'00155797-9',
'00288189-2',
'01437218-4',
'01425551-0',
'00496286-4',
'03061078-8',
'01056600-8',
'00247727-9',
'03245589-1',
'01710400-1',
'00684977-9',
'00040856-7',
'00566968-9',
'00346368-9',
'01645115-4',
'01077977-4',
'02167559-3',
'01987292-7',
'00486905-8',
'00375220-8',
'02114562-9',
'03311103-4',
'00436446-4',
'01648008-0',
'02732214-8',
'00112527-7',
'00391710-9',
'02981239-3',
'00693425-7',
'02144762-3',
'02137379-3',
'02378201-1',
'01343565-4',
'01824931-7',
'01232678-9',
'01970775-8',
'02030431-9',
'01576065-7',
'01391274-5',
'01304203-7',
'01233898-0',
'01010123-0',
'00480089-2',
'02793352-5',
'00348481-3',
'02093683-1',
'01613078-2',
'00019252-2',
'01929644-8',
'00404145-6',
'05260919-3',
'00672581-0',
'01027921-1',
'05420908-8',
'00287635-0',
'00410101-0',
'02034623-0',
'00521901-0',
'01998843-5',
'01379501-2',
'00936246-7',
'03184848-1',
'03385847-4',
'04027728-1',
'00335195-5',
'00946973-0',
'01896549-2',
'01750122-9',
'00507923-2',
'02305105-4',
'00661608-7',
'00847043-7',
'01807893-6',
'06189606-6',
'00417903-9',
'02281948-3',
'00642589-2',
'00063409-5',
'02485190-2',
'01729572-1',
'02336673-4',
'00004089-8',
'01136809-7',
'02537534-9',
'02074580-8',
'01274714-7',
'00121979-1',
'01834405-2',
'01582110-2',
'01599223-8',
'02747009-8',
'00526938-2',
'00517048-6',
'01761480-2',
'00208574-7',
'01480468-6',
'01067618-8',
'02111737-5',
'02462647-0',
'00285485-3',
'00542753-2',
'03066358-7',
'00097022-1',
'02214772-1',
'02045588-5',
'00639768-3',
'00967771-5',
'01184026-9',
'02235434-4',
'01752315-8',
'01921353-9',
'03451383-1',
'00202941-6',
'02468007-6',
'01733669-0',
'01420135-9',
'01726197-6',
'01352272-8',
'01832011-3',
'01737298-9',
'01903325-6',
'01975104-0',
'01635804-7',
'03991715-3',
'02827204-5',
'00983670-9',
'00953612-1',
'00124926-7',
'02393717-9',
'02509966-8',
'01671162-7',
'00092596-7',
'01742020-3',
'01777201-6',
'00343336-7',
'00191923-6',
'03358364-7',
'00203260-5',
'01703778-3',
'02186238-6',
'01683006-5',
'00576398-8',
'00867338-6',
'02316204-1',
'01353072-1',
'01669880-3',
'00311750-5',
'01182737-6',
'02607414-0',
'01738830-4',
'01315324-4',
'00743067-0',
'01550820-9',
'03227308-7',
'03698386-2',
'01520245-5',
'01830524-4',
'01451496-4',
'01429771-6',
'01305282-0',
'00979885-4',
'00239880-7',
'01491804-5',
'01058157-9',
'02661288-3',
'00091747-7',
'01313027-0',
'01414942-6',
'01968261-5',
'01829425-7',
'00219935-0',
'02047679-2',
'00524370-0',
'00454674-9',
'04069203-3',
'00997350-1',
'01482030-7',
'01613162-3',
'00590523-9',
'00165606-6',
'00245172-8',
'00108256-8',
'01302754-0',
'00517604-2',
'02511286-4',
'00940591-4',
'02004331-1',
'01980849-9',
'01225536-0',
'00905178-1',
'01545562-6',
'00130658-0',
'02788892-4',
'01331421-4',
'03179528-0',
'01835282-7',
'01269631-2',
'6942051973-M',
'03231054-6',
'03591007-8',
'02637683-5',
'02323488-0',
'00541632-9',
'01063344-9',
'01264707-0',
'01001866-5',
'02146865-3',
'01224421-2',
'02142778-8',
'00396668-7',
'01256995-5',
'01305615-9',
'03256926-8',
'01850554-3',
'00730104-1',
'03437171-8',
'02418126-8',
'00783776-7',
'00953863-6',
'01999432-1',
'02403905-5',
'00121941-6',
'00371401-4',
'00269707-3',
'00543365-6',
'01915908-6',
'00549233-3',
'00542812-2',
'00022821-8',
'01977700-4',
'00310311-6',
'00172524-7',
'00485812-0',
'02535722-8',
'00773979-9',
'00443201-3',
'02057535-0',
'01144311-4',
'03034943-4',
'00255015-8',
'02655058-6',
'00215910-6',
'03299773-8',
'01974411-6',
'02579126-0',
'01609249-7',
'00484536-3',
'02058436-7',
'00517095-7',
'02747261-8',
'00457665-5',
'03723674-7',
'04591291-7',
'00223695-8',
'03349659-9',
'01459257-2',
'02122183-1',
'02118676-4',
'01577340-6',
'01392593-4',
'02093417-2',
'01101893-5',
'02219283-1',
'02638998-5',
'00114471-8',
'01247693-2',
'00736852-2',
'02006396-3',
'00609179-0',
'03212167-0',
'02898678-3',
'00067846-3',
'00493500-3',
'00060578-7',
'01925447-0',
'02761316-7',
'00341284-0',
'02142693-6',
'01604252-3',
'01924250-4',
'01718681-7',
'00342652-2',
'02202299-7',
'00484411-3',
'01419832-8',
'03057086-5',
'00041114-6',
'02469227-7',
'00108678-2',
'00330432-2',
'00513738-1',
'01648077-1',
'02252138-1',
'01956281-5',
'00275329-3',
'02305945-0',
'01766468-7',
'01393593-9',
'01835299-0',
'01782213-8',
'00367883-6',
'00791098-9',
'00161074-3',
'00857019-8',
'03170272-4',
'02182443-5',
'00249430-2',
'00959744-4',
'00902398-2',
'02218402-4',
'00466158-1',
'01979142-2',
'01218571-9',
'01257205-5',
'02275626-1',
'00056187-8',
'01329105-0',
'03423319-8',
'02144921-9',
'00530076-4',
'02098921-6',
'00438841-8',
'00779453-7',
'01968071-0',
'03338826-7',
'02998981-5',
'00753239-1',
'00552249-7',
'03154641-1',
'02725312-9',
'00892794-7',
'02835319-2',
'00003057-6',
'01934251-6',
'04553002-4',
'05704472-0',
'01516427-5',
'02678242-6',
'02024027-2',
'02128263-3',
'00394363-9',
'02826840-2',
'00758628-5',
'01288515-7',
'03523952-9',
'03054491-1',
'01897966-1',
'00996663-5',
'00558304-5',
'00520056-6',
'04749902-0',
'01630928-4',
'05169047-6',
'02197594-3',
'00159498-9',
'03942123-3',
'02171158-5',
'01403187-7',
'00754057-2',
'01098798-7',
'00093900-5',
'02970245-9'

];
//var_dump($duis); exit();
  $table = "<table border='1'>";
  foreach ($duis as $dui) {

    $sql= "select * from orden_lab where dui=?;";
    $sql=$conectar->prepare($sql);
    $sql->bindValue(1,$dui);
    $sql->execute();
    $resultado= $sql->fetchAll(PDO::FETCH_ASSOC);                    
    $fechaOrden = $resultado[0]["fecha"];    
    $fecha_limite = '2023-02-23';
    
    $fecha_dada = new DateTime($fechaOrden);
    $limite = new DateTime($fecha_limite);
   
    if ($fecha_dada > $limite) {
        $table  .= "
           <tr>
            <td>".$dui."</td>
          </tr>
        ";
        if($resultado[0]["sucursal"]=="Valencia"){
            $fechaInicio = '2023-01-16';
	      	$fechaFin = '2023-02-23';
        }else{
             $fechaInicio = '2022-11-15';
	      	 $fechaFin = '2023-02-24';
        }
    $fechaGenerada = $this->updateFechaOrdenes($fechaInicio, $fechaFin);
    $nFechaActa = $this->newFechaActa($fechaGenerada);
    
    $sql3 = 'update orden_lab set fecha=? where dui=?';
    $sql3 = $conectar->prepare($sql3);
    $sql3->bindValue(1,$fechaGenerada);
    $sql3->bindValue(2,$dui);
    $sql3->execute();
    $nueva_fecha = date('d-m-Y', strtotime($nFechaActa));
    
    $sql = 'update actas set fecha_impresion=? where dui_acta=?';
    $sql = $conectar->prepare($sql);
    $sql->bindValue(1,$nueva_fecha);
    $sql->bindValue(2,$dui);
    $sql->execute();
        
        
    } else {
        
        $nFechaActa = $this->newFechaActa($fechaOrden);
        $nueva_fecha = date('d-m-Y', strtotime($nFechaActa));
        
        $sql = 'update actas set fecha_impresion=? where dui_acta=?';
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1,$nueva_fecha);
        $sql->bindValue(2,$dui);
        $sql->execute();
        
    } 
}
$table .= "</table>";
echo $table;
echo 'Update Finish...';
}


}


$prueba = new Pruebas();
$prueba->updateFechaActa();









 
 