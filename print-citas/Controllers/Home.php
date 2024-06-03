<?php
class Home extends Controller{
    public function __construct() {
        parent::__construct();
    }
    public function index(){
        $this->views->getView($this, "index");
    }

    public function listar(){
        
        $data = $this->model->getEventos($_GET["filtro"],$_GET["categoria"]);
        echo json_encode($data);
        die();
    }

   
}
