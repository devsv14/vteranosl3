<?php
class Home extends Controller
{
    public function __construct() {
        parent::__construct();
    }
    public function index()
    {
        $this->views->getView($this, "index");
    }
   
   public function registrar(){

        if (isset($_POST)) {
            if (empty($_POST['paciente-vet']) || empty($_POST['dui-vet'])) {
            }else{
                
                $dui = $_POST['dui-vet'];
                $fecha = $_POST['fecha-cita'];
                $id = $_POST['id'];
                $edad = $_POST["edad-pac"];
                $telefono = $_POST["telefono-pac"];
                $ocupacion = $_POST["ocupacion-pac"];
                $genero = $_POST["genero-pac"];
                $usuario_lente = $_POST["usuario-lente"];
                $sector = $_POST["sector-pac"];
                $depto = $_POST["departamento_pac"];
                $municipio = $_POST["munic_pac"];
                $hora = $_POST["hora"];
                $sucursal = $_POST["sucursal-cita"];
                $user_login = $_POST["id_usuario_vet"];
                $tel_opcional = $_POST["telefono-opcional"];
                $tipo_paciente = $_POST["tipo-pac"];
                 $licitacion = $_POST["licitacion"];
                $institucion = $_POST["chk-instit"];
                if(($institucion=='inabve' and ($tipo_paciente=='Conyuge' or $tipo_paciente=='Designado')) and $institucion != 'brf'){
                    $paciente = $_POST['beneficiarios-vet'];
                    $pos = strpos($paciente, '-');
                    if($pos){
                        $paciente = explode("-",$paciente);
                        $paciente = $paciente[1];                    
                    }                  

                }elseif(($institucion=='inabve' and ($tipo_paciente=='Veterano' or $tipo_paciente=='Ex-Combatiente')) or $institucion=='brf'){
                    $paciente = $_POST['paciente-vet'];
                    if($institucion=='inabve'){
                        $paciente = explode("*",$paciente);
                        $paciente = $paciente[1];
                        $paciente = explode("-",$paciente);
                        $paciente = $paciente[0];        
                    }
                }

                isset($_POST["vet-titular"]) ? $vet_titular=$_POST["vet-titular"] : $vet_titular="*";
                isset($_POST["dui-titular"]) ? $dui_titular=$_POST["dui-titular"] : $dui_titular="*";
                if ($id == '') {
                    $data = $this->model->registrar($paciente, $dui, $fecha,$sucursal,$edad,$telefono,$ocupacion,$genero,$usuario_lente,$sector,$depto,$municipio,$hora,$user_login,$vet_titular,$dui_titular,$tel_opcional,$tipo_paciente,$institucion,$licitacion);
                    if ($data == 'ok') {
                        $msg = array('msg' => 'Cita ingresada', 'estado' => true, 'tipo' => 'success');
                    }else if($data == 'not'){
                        $msg = array('msg' => 'Sucursal no admite mas cupos, selecione otra fecha', 'estado' => false, 'tipo' => 'error');
                    }else if($data == 'error'){
                        $msg = array('msg' => 'DUI ya existe', 'estado' => false, 'tipo' => 'error');
                    }else if($data == 'errorhora'){
                        $msg = array('msg' => 'hora ya ha sido seleccionada por otro usuario', 'estado' => false, 'tipo' => 'error');
                    }
                } else {
                    $data = $this->model->modificar($paciente, $dui, $fecha, $id);
                    if ($data == 'ok') {
                        $msg = array('msg' => 'Evento Modificado', 'estado' => true, 'tipo' => 'success');
                    } else {
                        $msg = array('msg' => 'Error al Modificar', 'estado' => false, 'tipo' => 'danger');
                    }
                }
                
            }
            echo json_encode($msg);
        }
        die();
    }
   
    public function listar(){
        
        $data = $this->model->getEventos();
        echo json_encode($data);
        die();
    }

    public function eliminar($id)
    {
        $data = $this->model->eliminar($id);
        if ($data == 'ok') {
            $msg = array('msg' => 'Evento Eliminado', 'estado' => true, 'tipo' => 'success');
        } else {
            $msg = array('msg' => 'Error al Eliminar', 'estado' => false, 'tipo' => 'danger');
        }
        echo json_encode($msg);
        die();
    }
    public function drag()
    {
        if (isset($_POST)) {
            if (empty($_POST['id']) || empty($_POST['start'])) {
                $msg = array('msg' => 'Todo los campos son requeridos', 'estado' => false, 'tipo' => 'danger');
            } else {
                $start = $_POST['start'];
                $id = $_POST['id'];
                $data = $this->model->dragOver($start, $id);
                if ($data == 'ok') {
                    $msg = array('msg' => 'Evento Modificado', 'estado' => true, 'tipo' => 'success');
                } else {
                    $msg = array('msg' => 'Error al Modificar', 'estado' => false, 'tipo' => 'danger');
                }
            }
            echo json_encode($msg);
        }
        die();
    }
}
