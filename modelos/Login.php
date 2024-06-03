<?php

require_once("config/conexion.php");

class Login extends Conectar{

  public function listar_permisos_por_usuario($id_usuario){
    $conectar=parent::conexion();
    $sql="select u.id_permiso,p.nombre,u.id_usuario from permisos as p INNER join usuario_permiso as u on p.id_permiso=u.id_permiso where u.id_usuario=?;";
    $sql=$conectar->prepare($sql);
    $sql->bindValue(1, $id_usuario);
    $sql->execute();
    return $resultado=$sql->fetchAll();
 }
  
public function login_users(){
  $conectar=parent::conexion();
  parent::set_names();
  if(isset($_POST["enviar"])){
//********VALIDACIONES  DE ACCESO*****************
  $password = $_POST["pass"];
  $usuario = $_POST["usuario"];
  $sucursal = $_POST["sucursal-user"];

  if(empty($usuario) or empty($password)){
      header("Location:index.php?m=2");
      exit();
    }else { 
      
    $sql= "select * from usuarios where usuario=? and pass=? and sucursal=?";
        $sql=$conectar->prepare($sql);
        $sql->bindValue(1, $usuario);
        $sql->bindValue(2, $password);
        $sql->bindValue(3, $sucursal);
        $sql->execute();
        $results = $sql->fetch();

    if(is_array($results) and count($results)>0){
        $_SESSION["id_user"] = $results["id_usuario"];           
        $_SESSION["user"] = $results["usuario"];
        $_SESSION["categoria"] = $results["categoria"];
        $_SESSION["sucursal"] = $results["sucursal"];

        $marcados = $this->listar_permisos_por_usuario($results["id_usuario"]);
        //print_r($marcados);
        $valores=array();
        foreach($marcados as $row){
          $valores[]= $row["id_permiso"];
          $names_permisos[]=$row["nombre"];
        }
        $_SESSION['permisos'] = $valores;
        $_SESSION['names_permisos'] = $names_permisos;
        //in_array(4,$valores)?$_SESSION['citas_callcenter']=1:$_SESSION['citas_callcenter']=0;
        in_array(5,$valores)?$_SESSION['citas_sucursal']=1:$_SESSION['citas_sucursal']=0;

        in_array('citas_callcenter',$names_permisos)?$_SESSION['citas_callcenter']=1:$_SESSION['citas_callcenter']=0;
        in_array('citas_sucursal',$names_permisos)?$_SESSION['citas_sucursal']=1:$_SESSION['citas_sucursal']=0;
        in_array('listado_general_citas',$names_permisos)?$_SESSION['listado_general_citas']=1:$_SESSION['listado_general_citas']=0;
        in_array('actas_listar',$names_permisos)?$_SESSION['actas_listar']=1:$_SESSION['actas_listar']=0;
      
      header("Location:vistas/home.php");
      exit();
    } else {                         
    //si no existe el registro entonces le aparece un mensaje
    header("Location:index.php?m=1");
    exit();
    } 
  }//cierre del else
  }//condicion enviar
}///FIN FUNCION LOGIN

}