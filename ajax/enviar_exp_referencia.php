<?php

require_once("../config/conexion.php");
//llamada al modelo categoria
require_once("../modelos/Ordenes.php");

$ordenes = new Ordenes();

switch ($_GET["op"]) {

    case 'comprobar_exit_DUI_pac':
        $datos = $ordenes->comprobar_exit_DUI_pac($_POST['dui_pac']);
        echo json_encode($datos[0]);
        break;
}
